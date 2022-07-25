<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Facility') }}
        </h2>
    </x-slot>
    <x-slot name="info">
        <h2 class="font-semibold text-m text-gray-800 leading-tight">
            {{ __(Auth::user()->facility->name . '(' . Auth::user()->facility->facility_code . '), ') }}
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('facility.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="name" :value="__('Name')" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" required
                                    value="{{ old('name') }}" />
                            </div>
                            <div>
                                <x-label for="facility_code" :value="__('Facility Code')" />
                                <x-input id="facility_code" class="block mt-1 w-full" type="text" name="facility_code" required
                                    value="{{ old('facility_code') }}" />
                            </div>
                            <div>
                                <x-label for="pinode" :value="__('Pincode')" />
                                <x-input id="pincode" class="block mt-1 w-full" type="number" name="pincode" required
                                    value="{{ old('pincode') }}" />
                            </div>
                            
                            <div>
                                <x-label for="facility_type_id" :value="__('Facility Type')" />
                                <x-input-select id="facility_type_id" class="block mt-1 w-full" name="facility_type_id" required>
                                    @foreach ($facility_types as $facility_type)
                                        <option value="{{ $facility_type->id }}">{{ $facility_type->name }}</option>
                                    @endforeach
                                </x-input-select>
                            </div>

                            <div>
                                <x-label for="district_id" :value="__('District')" />
                                <x-input-select id="district_id" class="block mt-1 w-full" name="district_id" required>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </x-input-select>
                            </div>

                            <div>
                                <x-label for="reporting_circle_id" :value="__('Circle')" />
                                <x-input-select id="reporting_circle_id" class="block mt-1 w-full" name="reporting_circle_id" required>
                                    @foreach ($reporting_circles as $reporting_circle)
                                        <option value="{{ $reporting_circle->id }}">{{ $reporting_circle->name }}</option>
                                    @endforeach
                                </x-input-select>
                            </div>

                            <div>
                                <x-label for="is_active" :value="__('Status')" />
                                <x-input-select id="is_active" class="block mt-1 w-full" name="is_active" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </x-input-select>
                            </div>
                            
                        </div>
                        <div class="flex items-center justify-end mt-4 ">
                            <x-button class="ml-3" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                {{ __('Create') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
