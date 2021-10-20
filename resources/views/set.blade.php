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
                <div class="px-6 pt-6 font-bold">
                    <h3>Set Begin:</h3>
                </div>
                <div class="p-6 font-semibold">
                    Current date: {{ now() }}
                </div>
                <form action="{{ route('set.create') }}" method="post">
                    @csrf
                    <div class="px-7 py-2 font-bold">
                        <label for="confirm" class="pt-2">Confirm</label>
                        <input type="checkbox" name="confirm" id="confirm"
                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <x-button class="mx-6 mb-6 bg-red-800 hover:bg-red-600">
                        {{ __('Generate subjects from main Database') }}
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
