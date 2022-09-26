<x-sidebar>
    <div class="relative w-full h-full md:h-auto">
        <div class="p-6">
            <form method="POST" action="{{ route('holidays.store') }}">
                @csrf
                <div class="relative z-0 w-full group flex flex-col">
                    <label for="from" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Name</label>
                    <input type="text" name="name" placeholder="Please enter holiday's name">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full group flex flex-col">
                        <label for="from" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Start Date</label>
                        <input type="text" name="from" id="fromDate" placeholder="Please select Date Range" data-input>
                        @error('from')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative z-0 mb-6 w-full group flex flex-col">
                        <label for="to" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">End Date</label>
                        <input type="text" name="to" id="toDate" placeholder="Please select Date Range" data-input>
                        @error('to')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-sidebar>
