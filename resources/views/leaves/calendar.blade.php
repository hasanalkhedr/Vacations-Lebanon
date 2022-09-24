<x-sidebar>
    <div class="p-4 text-lg text-center">
        {{ $month_name }}
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
                            <td class="aspect-square border border-b py-2 px-5 text-gray-900 bg-gray-500 whitespace-nowrap dark:text-white w-60">
                            </td>
                        @elseif(array_key_exists($employee->id . '&' . $date->format('Y-m-d'), $leaveId_dates_pairs))
                            @if($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->employee->id == $employee->id)
                                @if($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_duration->name == "Half Day AM")
                                    <td class="aspect-square border border-b py-2 px-5 text-gray-900 bg-red-500 whitespace-nowrap dark:text-white w-60">
                                    </td>
                                @elseif($leaveId_dates_pairs[$employee->id . '&' . $date->format('Y-m-d')]->leave_duration->name == "Half Day PM")
                                    <td class="aspect-square border border-b py-2 px-5 text-gray-900 bg-blue-600 whitespace-nowrap dark:text-white w-60">
                                    </td>
                                @else
                                    <td class="aspect-square border border-b py-2 px-5 text-gray-900 bg-yellow-600 whitespace-nowrap dark:text-white w-60">
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
