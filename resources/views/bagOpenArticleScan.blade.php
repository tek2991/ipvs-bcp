<x-app-layout>
    @php
        // Get the bag model
        $bag = \App\Models\Bag::where('bag_no',$request->bag_no)->first();
        // Check if it has Insured Articles
        $has_insured = $bag->hasInsuredArticles();
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bag Open Scan') }}
            @if ($has_insured === true)
                <span class="text-red-800 text-md"> &nbsp; Contains Insured Articles</span>
            @endif
        </h2>
    </x-slot>
    <x-slot name="info">
        <h2 class="font-semibold text-m text-gray-800 leading-tight">
            {{ __(Auth::user()->facility->name . '(' . Auth::user()->facility->facility_code . '), ' . $active_set->created_at->toDayDateTimeString() . ' ' . $active_set->setType->name) }}
        </h2>
    </x-slot>

    @if (session('scroll') || session('success') || session('error') || $errors->any())
        <script>
            // This prevents the page from scrolling down to where it was previously.
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
            // This is needed if the user scrolls down during page load and you want to make sure the page is scrolled to the top once it's fully loaded. This has Cross-browser support.
            setTimeout(function() {
                window.scrollTo(0, 0);
            }, 50);
        </script>
    @endif

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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form action="{{ route('bag-open.articleScan') }}" method="post">
                            @csrf
                            <input type="hidden" name="bag_no" value="{{ $request->bag_no }}">
                            <input type="hidden" name="bag_id" value="{{ $bag->id }}">
                            <input type="hidden" name="from_facility_id" value="{{ $bag->fromFacility->id }}">

                            <div class="grid grid-cols-2 gap-12">
                                <div class="grid grid-cols-2 gap-16 mt-6">
                                    <label for="bag_no" class="pt-2 text-lg font-semibold">Bag No:</label>
                                    <input id="bag_no" type="text" disabled value="{{ $request->bag_no }}"
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </input>
                                </div>
                                <div class="grid grid-cols-2 gap-16 mt-6">
                                    <label for="from" class="pt-2 text-lg font-semibold">From:</label>
                                    <input id="from" type="text" disabled value="{{ $bag->fromFacility->name }}"
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </input>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-12">
                                <div class="grid grid-cols-2 gap-16 mt-6">
                                    <label for="article_type_id" class="pt-2 text-lg font-semibold">Article
                                        type:</label>
                                    <select name="article_type_id" id="article_type_id"
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @foreach ($article_types as $article_type)
                                            <option value="{{ $article_type->id }}"
                                                {{ $article_type->id == old('article_type_id') ? 'selected' : '' }}>
                                                {{ $article_type->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-16 mt-6">
                                    <label for="is_insured" class="pt-2 text-lg font-semibold">Insured:</label>
                                    <select name="is_insured" id="is_insured"
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="0" {{ old('is_insured') == 0 ? 'selected' : '' }}>
                                            No
                                        </option>
                                        <option value="1" {{ old('is_insured') == 1 ? 'selected' : '' }}>
                                            Yes
                                        </option>
                                    </select>
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

                                <div class="mt-6 grid grid-cols-2">
                                    <div>
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 max-12">Submit</button>
                                    </div>
                                    <div>Total Articles: {{ $articles->total() }}, (Insured: {{ $bag->articles->where('is_insured', true)->count() }})</div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if ($articles->total() > 0)
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-lg font-semibold p-4">
                    Scaned articles
                </div>
            </div>
            @foreach ($articles as $article)
                <div class="p-1">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="p-4 bg-white border-b border-gray-200">
                            <div class="grid grid-cols-3 gap-1">
                                <div class="text-lg font-semibold">{{ $article->article_no }} {{ $article->is_insured ? '*' : '' }}</div>
                                <div>{{ $article->articleType->name }}</div>
                                <div>{{ $article->is_insured ? 'Insured' : 'Not Insured' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="py-6">
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
                    <form action="{{ route('bag-open.articleDeleteScan') }}" method="post">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" name="bag_id" value="{{ $bag->id }}">
                        <input type="hidden" name="bag_no" value="{{ $request->bag_no }}">
                        <div class="grid grid-cols-2 gap-12">
                            <div class="grid grid-cols-2 gap-16">
                                <label for="article_no_for_delete" class="pt-2 text-lg font-semibold">Article
                                    No:</label>
                                <input name="article_no_for_delete" id="article_no_for_delete" type="text"
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
