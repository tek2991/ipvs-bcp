<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Set Control') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-green-300 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-red-300 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 font-semibold">
                    Facility and set details
                </div>
                <div class="p-6 font-semibold">
                    Current Facility: {{ Auth::user()->facility->name }} -
                    {{ Auth::user()->facility->facility_code }} <br>

                    Current date: {{ now()->toDayDateTimeString() }} <br>

                    Current business date:
                    {{ count($currently_active) > 0 ? $currently_active->first()->created_at->toDayDateTimeString() : 'No active set' }} <br>
                    Previous bussiness date: {{ count($previously_active) > 0 ? $previously_active->first()->created_at->toDayDateTimeString() . ' to ' . $previously_active->first()->updated_at->toDayDateTimeString() : 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 pt-6 font-bold">
                    <h3>Set Begin</h3>
                </div>
                <form action="{{ route('set.create') }}" method="post">
                    @csrf
                    <div class="px-7 py-2 font-bold">
                        <label for="confirm" class="pt-2">Confirm</label>
                        <input type="checkbox" name="confirm" id="confirm"
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    @if (count($currently_active) > 0)
                        <x-button class="mx-6 mb-6 bg-gray-800" disabled>
                            {{ __('Set Begin') }}
                        </x-button>
                    @else
                        <x-button class="mx-6 mb-6 bg-red-800 hover:bg-red-600">
                            {{ __('Set Begin') }}
                        </x-button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 pt-6 font-bold">
                    <h3>Set Close</h3>
                </div>
                <form action="{{ route('set.update') }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="px-7 py-2 font-bold">
                        <label for="confirm" class="pt-2">Confirm</label>
                        <input type="checkbox" name="confirm" id="confirm"
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    @if (count($currently_active) == 0)
                        <x-button class="mx-6 mb-6 bg-gray-800" disabled>
                            {{ __('Set Close') }}
                        </x-button>
                    @else
                        <x-button class="mx-6 mb-6 bg-red-800 hover:bg-red-600">
                            {{ __('Set Close') }}
                        </x-button>
                    @endif
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
