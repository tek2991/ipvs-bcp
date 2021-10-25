<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Articles received but not processed') }}
        </h2>
    </x-slot>
    <x-slot name="info">
        <h2 class="font-semibold text-m text-gray-800 leading-tight">
            {{ __(Auth::user()->facility->name . '(' . Auth::user()->facility->facility_code . '), ' . $active_set->created_at->toDayDateTimeString()) }}
        </h2>
    </x-slot>

    @if ($articles->total() > 0)
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>

        <div class="py-1">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-lg font-semibold p-4">
                    articles:
                </div>
            </div>
            @foreach ($articles as $article)
                <div class="p-1">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="p-4 bg-white border-b border-gray-200">
                            <div class="grid grid-cols-5 gap-1">
                                <div class="text-lg font-semibold">{{ $article->article_no }} ({{ $article->articleType->name }})</div>
                                <span>{{ $article->articleTransactionType->description }}</span>
                                <span>{{ $article->fromFacility->name }} -> {{ $article->toFacility->name }}</span>
                                <span>{{ $article->updated_at }}</span>
                                <span>{{ $article->creator->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="py-1">
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
                        No articles found!
                    </div>
                </div>
            </div>
        </div>
    @endif

</x-app-layout>
