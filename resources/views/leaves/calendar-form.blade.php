<x-sidebar>
    @section('title', 'Calendar Form')
    <form method="POST" action="{{ route('leaves.generateCalendar') }}" enctype="multipart/form-data" class="m-2">
        @csrf
        <div>
            <label for="month" class="text-lg block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Select a month</label>
            <select name="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value='01'>January</option>
                <option value='02'>February</option>
                <option value='03'>March</option>
                <option value='04'>April</option>
                <option value='05'>May</option>
                <option value='06'>June</option>
                <option value='07'>July</option>
                <option value='08'>August</option>
                <option value='09'>September</option>
                <option value='10'>October</option>
                <option value='11'>November</option>
                <option value='12'>December</option>
            </select>
        </div>
        @hasanyrole('human_resource|sg')
            <div>
                <label for="department_id" class="text-lg block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Select department</label>
                <select name="department_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="all">All</option>
                    @if(count($departments))
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        @endhasanyrole
        <button class="mt-4 text-gray-600 hover:text-white border border-gray-500 hover:bg-gray-500 focus:ring-2 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-200 dark:focus:ring-gray-200">Generate New Calendar</button>
    </form>
</x-sidebar>
