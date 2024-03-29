<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Export excel for BCP upload') }}
        </h2>
    </x-slot>
    <x-slot name="info">
        <h2 class="font-semibold text-m text-gray-800 leading-tight">
            {{ __('') }}
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

    @if (Auth::user()->is_admin() === true)
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form action="{{ route('export.export') }}" method="post">
                        @csrf
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <div>
                                    <h2 class="pb-6 text-lg font-semibold">Consolidated Exports (Admin Only)</h2>
                                    <div>
                                        <label for="set_id" class="pt-2 text-lg font-semibold">Select Set: </label>
                                        <select name="set_id" id="set_id" autofocus
                                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            @foreach ($sets as $set)
                                                <option value="{{ $set->id }}" >
                                                    {{ $set->facility->name }},
                                                    {{ $set->created_at->toDayDateTimeString() }} to
                                                    {{ $set->created_at != $set->updated_at ? $set->updated_at->toDayDateTimeString() : 'Current' }}
                                                    {{ $set->setType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex gap-16 pt-8">
                                        <div>
                                            <label for="report_type" class="pt-2 text-lg font-semibold">Bag Report Type:
                                            </label>
                                            <select name="report_type" id="report_type"
                                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                                                <option value="bag_receive">
                                                    Bag Receive
                                                </option>

                                                <option value="bag_dispatch">
                                                    Bag Dispatch
                                                </option>

                                                <option value="article_open">
                                                    Article Open
                                                </option>

                                                <option value="article_close">
                                                    Article Close
                                                </option>

                                            </select>
                                        </div>

                                        <div>
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 max-12">Submit</button>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('export.export-user') }}" method="post">
                    @csrf
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div>
                                <h2 class="pb-6 text-lg font-semibold">User Exports</h2>
                                <div>
                                    <label for="set_id" class="pt-2 text-lg font-semibold">Select Set: </label>
                                    <select name="set_id" id="set_id" autofocus
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @foreach ($sets as $set)
                                            <option value="{{ $set->id }}">
                                                {{ $set->facility->name }},
                                                {{ $set->created_at->toDayDateTimeString() }} to
                                                {{ $set->created_at != $set->updated_at ? $set->updated_at->toDayDateTimeString() : 'Current' }}
                                                {{ $set->setType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pt-8">
                                    <label for="user_id" class="pt-2 text-lg font-semibold">User: </label>
                                    <select name="user_id" id="user_id" autofocus
                                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex gap-16 pt-8">
                                    <div>
                                        <label for="report_type" class="pt-2 text-lg font-semibold">Bag Report Type:
                                        </label>
                                        <select name="report_type" id="report_type"
                                            class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                                            <option value="bag_receive">
                                                Bag Receive
                                            </option>

                                            <option value="bag_dispatch">
                                                Bag Dispatch
                                            </option>

                                            <option value="article_open">
                                                Article Open
                                            </option>

                                            <option value="article_close">
                                                Article Close
                                            </option>

                                        </select>
                                    </div>

                                    <div>
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 max-12">Submit</button>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
