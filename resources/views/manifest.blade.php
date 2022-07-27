<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bag Manifest') }}
        </h2>
    </x-slot>
    <x-slot name="info">
        <h2 class="font-semibold text-m text-gray-800 leading-tight">
            @php
                $active_set_text = count($active_set) > 0 ? $active_set->first()->created_at->toDayDateTimeString() . ' ' . $active_set->first()->setType->name : 'No active set';
            @endphp
            {{ __(Auth::user()->facility->name . '(' . Auth::user()->facility->facility_code . '), ' . $active_set_text) }}
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
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="grid grid-cols-2 gap-16">
                                <div class="grid grid-cols-2 gap-2">
                                    <label for="bag_no" class="pt-2 text-lg font-semibold">Bag No: </label>
                                    <input name="bag_no" id="bag_no" type="text" value="{{ $request->bag_no }}"
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

    @if ($bags->total() > 0)
        <div class="py-1">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $bags->links() }}
                </div>
            </div>
        </div>

        <div class="py-1">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-lg font-semibold p-4">
                    Bags
                </div>
            </div>
            @foreach ($bags as $bag)
                <div class="p-1">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="p-4 bg-white border-b border-gray-200">
                            <div class="flex justify-between">
                                <div class="text-lg font-semibold">{{ $bag->bag_no }}</div>
                                <span>{{ $bag->bagType->description }}({{ $bag->bagTransactionType->description }})</span>
                                <span>{{ $bag->fromFacility->name }} -> {{ $bag->toFacility->name }}</span>
                                <span>{{ $bag->updated_at }}</span>
                                <a href="{{ route('bag-manifest', ['bag' => $bag->id]) }}"
                                    class="font-bold text-blue-800 hover:text-blue-500" target="_blank">Open</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="py-1">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $bags->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="py-1">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-400 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 font-semibold">
                        No bags found!
                    </div>
                </div>
            </div>
        </div>
    @endif

</x-app-layout>
