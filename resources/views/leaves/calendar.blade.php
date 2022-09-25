<x-sidebar>
    <div class="w-full bg-white flex justify-between items-center">
        <div class="p-4 text-lg">
            {{ $month_name }}
        </div>
        <div class="px-6 py-3 text-xl font-bold text-black">
            <a href="{{ url(route('leaves.getCalendarForm')) }}">
                <button type="button" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-500 focus:ring-2 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-200 dark:focus:ring-gray-200">Generate New Calendar</button>
            </a>
        </div>
    </div>
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <table class="mx-4 w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-s text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th class="border"></th>
            @foreach($dates as $date)
                <th scope="col" class="text-center border py-3">
                    {{ $date->day }}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="border px-2 py-3 sm:text-sm font-bold text-gray-900 whitespace-nowrap dark:text-white">
                        <div>
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </div>
                    </td>
                    @foreach($dates as $date)
                        @if(in_array($date->format('Y-m-d'), $weekends))
                            <td class="border border-b py-2 px-5 text-gray-900 bg-gray-500 whitespace-nowrap dark:text-white w-60">
                            </td>
                        @elseif(array_key_exists($employee->id . '&' . $date->format('Y-m-d'), $leaveId_dates_pairs))
                            @if($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->employee->id == $employee->id)
                                @if($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_duration->name == "Half Day AM")
                                    <td class="border py-2 px-5 bg-red-500 text-gray-900 whitespace-nowrap dark:text-white w-60">
                                    </td>
                                @elseif($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_duration->name == "Half Day PM")
                                    <td class="border py-2 px-5 text-gray-900 bg-blue-600 whitespace-nowrap dark:text-white w-60">
                                    </td>
                                @else
                                    <td class="border py-2 px-5 text-gray-900 bg-yellow-600 whitespace-nowrap dark:text-white w-60">
                                    </td>
                                @endif
                            @else
                                <td class="border py-2 px-5 text-gray-900 whitespace-nowrap dark:text-white w-60">
                                </td>
                            @endif
                        @else
                            <td class="border py-2 px-5 text-gray-900 whitespace-nowrap dark:text-white w-60">
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6 p-4">
    {{ $employees->links() }}
</div>

</x-sidebar>
