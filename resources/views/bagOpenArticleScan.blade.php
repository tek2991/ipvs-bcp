<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bag Open Scan') }}
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
                <div class="p-6 font-semibold">
                    Current Facility: {{ Auth::user()->facility->name }} -
                    {{ Auth::user()->facility->facility_code }} <br>
                    Current business date:
                    {{ $active_set->created_at->toDayDateTimeString() }}
                    <br>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('bag-open.articleScan') }}" method="post">
                    @csrf
                    <input type="hidden" name="bag_no" value="{{ $request->bag_no }}">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="grid grid-cols-2 gap-16">
                                <div class="grid grid-cols-2 gap-2">
                                    <label for="bag_no" class="pt-2 text-lg font-semibold">Bag No:</label>
                                    <input id="bag_no" type="text" disabled
                                        value="{{ $request->bag_no }}"
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </input>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-16 mt-6">
                                <div class="grid grid-cols-2 gap-2">
                                    <label for="article_no" class="pt-2 text-lg font-semibold">Article No:</label>
                                    <input name="article_no" id="article_no" type="text" autofocus
                                        value="{{ old('article_no') }}"
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

    {{-- @if ($open_bags->total() > 0)
        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $open_bags->links() }}
                </div>
            </div>
        </div>

        <div class="pt-6 pb-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-lg font-semibold p-4">
                    Active bag opening scans
                </div>
            </div>
            @foreach ($open_bags as $open_bag)
                <div class="p-2">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="p-4 bg-white border-b border-gray-200">
                            <div class="grid grid-cols-5 gap-1">
                                <div class="text-lg font-semibold mb-3">{{ $open_bag->bag_no }}</div>
                                <span>{{ $open_bag->bagType->description }}({{ $open_bag->bagTransactionType->description }})</span>
                                <span>From: {{ $open_bag->fromFacility->name }}</span>
                                <span>{{ $open_bag->creator->name }}</span>
                                <span>{{ $open_bag->created_at }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $open_bags->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-400 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 font-semibold">
                        No Bags Received!
                    </div>
                </div>
            </div>
        </div>
    @endif --}}

</x-app-layout>