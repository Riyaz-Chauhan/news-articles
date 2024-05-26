@if (Session::has('error'))
    <div class="container mx-auto px-4 sm:px-8 py-8">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ Session::get('error') }}</span>
        </div>
    </div>
@endif
