<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bag Close Scan') }}
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form action="{{ route('bag-close.articleScan') }}" method="post">
                            @csrf
                            <input type="hidden" name="bag_no" value="{{ $request->bag_no }}">
                            <input type="hidden" name="bag_id" value="{{ $bag->id }}">
                            <input type="hidden" name="to_facility_id" value="{{ $bag->toFacility->id }}">

                            <div class="grid grid-cols-2 gap-12">
                                <div class="grid grid-cols-2 gap-16 mt-6">
                                    <label for="bag_no" class="pt-2 text-lg font-semibold">Bag No:</label>
                                    <input id="bag_no" type="text" disabled value="{{ $request->bag_no }}"
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </input>
                                </div>
                                <div class="grid grid-cols-2 gap-16 mt-6">
                                    <label for="to" class="pt-2 text-lg font-semibold">To:</label>
                                    <input id="to" type="text" disabled value="{{ $bag->toFacility->name }}"
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </input>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-12 mt-6">
                                <div class="grid grid-cols-2 gap-16 mt-6">
                                    <label for="article_no" class="pt-2 text-lg font-semibold">Article No:</label>
                                    <input name="article_no" id="article_no" type="text" autofocus
                                        value="{{ $errors->any() ? old('article_no') : '' }}"
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </input>
                                </div>

                                <div class="mt-6">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 max-12">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if ($articles->total() > 0)
        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>

        <div class="pt-6 pb-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-lg font-semibold p-4">
                    Scaned articles
                </div>
            </div>
            @foreach ($articles as $article)
                <div class="p-2">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="p-4 bg-white border-b border-gray-200">
                            <div class="grid grid-cols-3 gap-1">
                                <div class="text-lg font-semibold">{{ $article->article_no }}</div>
                                <div>{{ $article->articleType->name }}</div>
                                <div>{{ $article->is_insured ? 'Insured' : 'Not Insured' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-400 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 font-semibold">
                        No articles scaned!
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 font-semibold">
                    <form action="{{ route('bag-open.save', ['bag' => $bag->id]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="bag_id" value="{{ $bag->id }}">
                        <input type="hidden" name="bag_no" value="{{ $request->bag_no }}">
                        <div class="flex gap-12">
                            <div class="text-xl font-semibold pt-1">Save bag:</div>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 max-12">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-400 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 font-semibold">
                    <div class="text-xl mb-6 font-semibold">Remove article:</div>
                    <form action="{{ route('bag-close.articleDeleteScan') }}" method="post">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" name="bag_id" value="{{ $bag->id }}">
                        <input type="hidden" name="bag_no" value="{{ $request->bag_no }}">
                        <div class="grid grid-cols-2 gap-12">
                            <div class="grid grid-cols-2 gap-16">
                                <label for="article_no_for_delete" class="pt-2 text-lg font-semibold">Article
                                    No:</label>
                                <input name="article_no_for_delete" id="article_no_for_delete" type="text" autofocus
                                    value="{{ $errors->any() ? old('article_no_for_delete') : '' }}"
                                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </input>
                            </div>

                            <div>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 max-12">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
