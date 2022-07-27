<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bag Receive') }}
        </h2>
    </x-slot>
    <x-slot name="info">
        <h2 class="font-semibold text-m text-gray-800 leading-tight">
            {{ __(Auth::user()->facility->name . '(' . Auth::user()->facility->facility_code . '), ' . $active_set->created_at->toDayDateTimeString() . ' ' . $active_set->first()->setType->name) }}
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
                <form action="{{ route('bag-receive.store') }}" method="post">
                    @csrf
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="grid grid-cols-2 gap-16">
                                <div class="grid grid-cols-2 gap-2">
                                    <label for="from_facility_id" class="pt-2 text-lg font-semibold">From: </label>
                                    <select name="from_facility_id" id="from_facility_id" autofocus
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @foreach ($facilities as $facility)
                                            <option value="{{ $facility->id }}"
                                                {{ $facility->id == old('from_facility_id') ? 'selected' : '' }}>
                                                {{ $facility->name }} {{ $facility->pincode }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <label for="bag_type_id" class="pt-2 text-lg font-semibold">Bag Type: </label>
                                    <select name="bag_type_id" id="bag_type_id"
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @foreach ($bag_types as $bag_type)
                                            <option value="{{ $bag_type->id }}"
                                                {{ $bag_type->id == old('bag_type_id') ? 'selected' : '' }}>
                                                {{ $bag_type->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <label for="bag_no" class="pt-2 text-lg font-semibold">Bag No: </label>
                                    <input name="bag_no" id="bag_no" type="text"
                                        value="{{ $errors->any() ? old('bag_no') : '' }}"
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

    @if ($bags_received->total() > 0)
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $bags_received->links() }}
                </div>
            </div>
        </div>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-lg font-semibold p-4">
                    Received bags
                </div>
            </div>
            @foreach ($bags_received as $bag_received)
                <div class="p-1">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="p-4 bg-white border-b border-gray-200">
                            <div class="grid grid-cols-5 gap-1">
                                <div class="text-lg font-semibold">{{ $bag_received->bag_no }}</div>
                                <span>{{ $bag_received->bagType->description }}({{ $bag_received->bagTransactionType->description }})</span>
                                <span>{{ $bag_received->fromFacility->name }}</span>
                                <span>{{ $bag_received->creator->name }}</span>
                                <span>{{ $bag_received->created_at }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $bags_received->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-400 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 font-semibold">
                        No bags to open!
                    </div>
                </div>
            </div>
        </div>
    @endif

</x-app-layout>
