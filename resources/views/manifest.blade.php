<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bag Manifest') }}
        </h2>
    </x-slot>
    <x-slot name="info">
        <h2 class="font-semibold text-m text-gray-800 leading-tight">
            {{ __(Auth::user()->facility->name . '(' . Auth::user()->facility->facility_code . '), ' . $active_set->created_at->toDayDateTimeString()) }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="py-6">
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
        <div class="py-6">
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
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('manifest-report.index') }}" method="get">
                    @csrf
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="grid grid-cols-2 gap-16">
                                <div class="grid grid-cols-2 gap-2">
                                    <label for="bag_no" class="pt-2 text-lg font-semibold">Bag No: </label>
                                    <input name="bag_no" id="bag_no" type="text"
                                        value="{{ $request->bag_no }}"
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </input>
                                </div>

                                <div>
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 max-12">Submit</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
