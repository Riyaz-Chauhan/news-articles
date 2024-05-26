@section('title', $title)
@extends('layouts.app')
@section('content')

    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div>
                <h2 class="text-2xl font-semibold leading-tight">{{ $title }}</h2>
            </div>
            <form action="{{ route('newsList') }}" method="GET">
                <div class="bg-white rounded-lg shadow-lg grid grid-cols-4 gap-4 mt-5">
                    <div>
                        <div class="w-full max-w-sm p-4">
                            <label for="multiSelect" class="block text-sm font-medium text-gray-700 mb-2">Choose
                                Sources</label>
                            <div class="relative">
                                <button id="dropdownButton"
                                    class="w-full pl-3 pr-10 py-2 text-left bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    type="button">
                                    Select Sources
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>
                                <div id="dropdownMenu"
                                    class="hidden absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">
                                    <ul class="max-h-48 overflow-auto p-2">
                                        @if ($responseSource)
                                            <li class="flex items-center p-2">
                                                <input id="selectAll" type="checkbox"
                                                    class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                                <label for="selectAll" class="ml-2 block text-sm text-gray-700">All</label>
                                            </li>
                                            @foreach ($responseSource as $index => $source)
                                                <li class="flex items-center p-2">
                                                    <input id="{{ $source['id'] }}" name="source[]" type="checkbox"
                                                        value="{{ $source['id'] }}"
                                                        class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                                        @if (in_array($source['id'], $sources)) checked @endif>
                                                    <label for="{{ $source['id'] }}"
                                                        class="ml-2 block text-sm text-gray-700">{{ $source['name'] }}</label>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full max-w-sm p-4 ">
                        <label for="sortby" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select
                            class="w-full text-black/70 bg-white px-3 py-2 transition-all cursor-pointer hover:border-blue-600/30 border border-gray-200 rounded-lg outline-blue-600/50 appearance-none invalid:text-black/30"
                            name="sort_by">
                            <option value="publishedAt" selected>Published At</option>
                            <option value="popularity" @if ($sortBy == 'popularity') selected @endif>Popularity</option>
                            <option value="relevancy" @if ($sortBy == 'relevancy') selected @endif>Relevancy</option>
                        </select>
                    </div>
                    <div>
                        <div class="w-full max-w-sm p-4 ">
                            <label for="daterange" class="block text-sm font-medium text-gray-700 mb-2">Select Publish
                                Date</label>
                            <input type="text" id="daterange" name="publish_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Select date range">

                        </div>
                    </div>
                    <div>
                        <div class="w-full max-w-sm p-4 ">
                            <label for="daterange" class="block text-sm font-medium text-gray-700 mb-2">Search </label>
                            <input
                                class="w-full border border-gray-300 bg-white h-10 px-5 rounded-lg text-sm focus:outline-none"
                                type="search" name="search" placeholder="Search" value="{{ $search }}">
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex items-center w-full max-w-sm p-4 ">
                            <div>
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Apply</button>
                            </div>
                            <div>
                                <a href="{{ route('newsList') }}" class="text-dark font-bold py-2 px-4 rounded">
                                    Clear</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-5">
                    <div class="text-left">
                        <label for="hide_show" class="block text-sm font-medium text-gray-700 mb-2">Hide/Show
                            Columns</label>
                        <input type="checkbox" class="toggle_column" data-column="1" checked> Author
                        <input type="checkbox" class="toggle_column" data-column="2" checked> Title
                        <input type="checkbox" class="toggle_column" data-column="3" checked> Source
                        <input type="checkbox" class="toggle_column" data-column="4" checked> Published At
                        <input type="checkbox" class="toggle_column" data-column="5" checked> Content
                        <input type="checkbox" class="toggle_column" data-column="6" checked> Description
                    </div>
                </div>
                <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                    <div class="inline-block min-w-full shadow-md rounded-lg overflow-hidden">
                        <table class="min-w-full leading-normal table-auto" id="newsTable">
                            <thead>
                                <tr>
                                    <th data-column="1"
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex justify-between">
                                            <div>
                                                Author
                                            </div>
                                            <div>
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M16 18L16 16M16 6L20 10.125M16 6L12 10.125M16 6L16 13"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M8 18L12 13.875M8 18L4 13.875M8 18L8 11M8 6V8"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th data-column="2"
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex justify-between">
                                            <div>
                                                Title
                                            </div>
                                            <div>
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M16 18L16 16M16 6L20 10.125M16 6L12 10.125M16 6L16 13"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M8 18L12 13.875M8 18L4 13.875M8 18L8 11M8 6V8"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th data-column="3"
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex justify-between">
                                            <div>
                                                Source
                                            </div>
                                            <div>
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M16 18L16 16M16 6L20 10.125M16 6L12 10.125M16 6L16 13"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M8 18L12 13.875M8 18L4 13.875M8 18L8 11M8 6V8"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th data-column="4"
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex justify-between">
                                            <div>
                                                Published At
                                            </div>
                                            <div>
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M16 18L16 16M16 6L20 10.125M16 6L12 10.125M16 6L16 13"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M8 18L12 13.875M8 18L4 13.875M8 18L8 11M8 6V8"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th data-column="5"
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex justify-between">
                                            <div>
                                                Content
                                            </div>
                                            <div>
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M16 18L16 16M16 6L20 10.125M16 6L12 10.125M16 6L16 13"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M8 18L12 13.875M8 18L4 13.875M8 18L8 11M8 6V8"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                    <th data-column="6"
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex justify-between">
                                            <div>
                                                Description
                                            </div>
                                            <div>
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M16 18L16 16M16 6L20 10.125M16 6L12 10.125M16 6L16 13"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M8 18L12 13.875M8 18L4 13.875M8 18L8 11M8 6V8"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($paginator && count($paginator) > 0)
                                    @foreach ($paginator as $index => $article)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <div class="flex">
                                                    <div class="flex-shrink-0 w-10 h-10">
                                                        <img class="w-full h-full rounded-full"
                                                            src="{{ $article['urlToImage'] }}" alt="" />
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-gray-900 whitespace-no-wrap">
                                                            {{ $article['author'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">
                                                    <a href="{{ $article['url'] }}"> {{ $article['title'] }}</a>
                                                </p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">
                                                    {{ $article['source']['name'] }}
                                                </p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">
                                                    {{ date('d-m-Y A', strtotime($article['publishedAt'])) }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-left">
                                                {{ $article['content'] }}
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-left">
                                                {{ $article['description'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No records found..!!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if ($paginator)
                        <div class="flex justify-center mt-4 custom_pagination">
                            {{ $paginator->links() }}
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <script>
        var fromDate = "{{ $publishDateFrom }}";
        var toDate = "{{ $publishDateTo }}";
    </script>
@endsection
@push('page_js')
    <script src="{{ asset('/js/news.js') }}"></script>
@endpush