<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Facility Mapping') }}
        </h2>
    </x-slot>
    <x-slot name="info">
        <h2 class="font-semibold text-m text-gray-800 leading-tight">
            {{ __(Auth::user()->facility->name . '(' . Auth::user()->facility->facility_code . ')') }}
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


    <div class="pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-400 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-2">
                    <div class="p-6 font-semibold">
                        <div class="text-xl mb-6 font-semibold">Add facility:</div>
                        <form action="{{ route('facility-mapping.create') }}" method="post">
                            @csrf

                            <input type="hidden" name="base_facility_id" value="{{ $request->facility_id }}">

                            <div class="flex gap-4">
                                <div class="flex gap-4">
                                    <label for="facility_code_for_mapping" class="pt-2 text-lg font-semibold">Facility
                                        ID:</label>
                                    <input name="facility_code_for_mapping" id="facility_code_for_mapping" type="text"
                                        {{-- value="{{ $errors->any() ? old('facility_code_for_mapping') : '' }}" --}}
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </input>
                                </div>

                                <div>
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 max-12">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="p-6 font-semibold">
                        <div class="text-xl mb-6 font-semibold">Remove Facility:</div>
                        <form action="{{ route('facility-mapping.destroy') }}" method="post">
                            @csrf
                            @method('delete')

                            <input type="hidden" name="base_facility_id" value="{{ $request->facility_id }}">

                            <div class="flex gap-4">
                                <div class="flex gap-4">
                                    <label for="facility_code_for_delete" class="pt-2 text-lg font-semibold">Facility
                                        ID:</label>
                                    <input name="facility_code_for_delete" id="facility_code_for_delete" type="text"
                                        {{-- value="{{ $errors->any() ? old('facility_code_for_delete') : '' }}" --}}
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </input>
                                </div>

                                <div>
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 max-12">Remove</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('facility-mapping.show') }}" method="get">
                    <div class="p-6 flex items-center justify-between">
                        <div>
                            <label for="facility_id" class="pt-2 text-lg font-semibold">Map for Facility: </label>
                            <select name="facility_id" id="facility_id"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach ($active_facilities as $facility)
                                    <option value="{{ $facility->id }}"
                                        {{ $request->facility_id == $facility->id ? 'selected' : '' }}>
                                        {{ $facility->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div>
                            <label for="facility_type_id" class="pt-2 text-lg font-semibold">Facility Type: </label>
                            <select name="facility_type_id" id="facility_type_id"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All</option>
                                @foreach ($facility_types as $facility_type)
                                    <option value="{{ $facility_type->id }}"
                                        {{ $request->facility_type_id == $facility_type->id ? 'selected' : '' }}>
                                        {{ $facility_type->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div>
                            <label for="reporting_circle_id" class="pt-2 text-lg font-semibold">Reporting Circle:
                            </label>
                            <select name="reporting_circle_id" id="reporting_circle_id"
                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All</option>
                                @foreach ($reporting_circles as $reporting_circle)
                                    <option value="{{ $reporting_circle->id }}"
                                        {{ $request->reporting_circle_id == $reporting_circle->id ? 'selected' : '' }}>
                                        {{ $reporting_circle->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($facilities->total() > 0)
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $facilities->appends(request()->all())->links() }}
                </div>
            </div>
        </div>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-lg font-semibold p-4">
                    Currently Mapped Facilities
                </div>
            </div>
            @foreach ($facilities as $facility)
                <div class="p-1">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="p-4 bg-white border-b border-gray-200">
                            <div class="grid grid-cols-4 gap-1">
                                <div class="text-lg font-semibold">{{ $facility->name }}</div>
                                <div class="text-lg font-semibold">{{ $facility->facility_code }}</div>
                                <div>{{ $facility->district->name }}, {{ $facility->reportingCircle->name }}</div>
                                <div>{{ $facility->pincode }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $facilities->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-400 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 font-semibold">
                        No facilities mapped!
                    </div>
                </div>
            </div>
        </div>
    @endif

</x-app-layout>
