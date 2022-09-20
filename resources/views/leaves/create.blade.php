<x-sidebar>
    <div class="relative w-full h-full md:h-auto">
        <div class="p-6">
            <form method="POST" action="{{ route('leaves.store') }}">
                @csrf
                <div class="relative z-0 mb-6 w-full group">
                    <label for="leave_duration_id" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Leave Duration</label>
                    <select name="leave_duration_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" disabled>Choose Leave Duration</option>
                        @if(count($leave_durations))
                            @foreach ($leave_durations as $leave_duration)
                                <option value="{{ $leave_duration->id }}">{{ $leave_duration->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full group flex flex-col">
                        <label for="leave_type_id" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Start Date</label>
                        <input type="text" name="from" id="fromDate" placeholder="Please select Date Range" data-input>
                        @error('from')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative z-0 mb-6 w-full group flex flex-col">
                        <label for="leave_type_id" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">End Date</label>
                        <input type="text" name="to" id="toDate" placeholder="Please select Date Range" data-input>
                        @error('to')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="relative z-0 mb-6 w-full group">
                    <p>Travelling</p>
                    <div class="mt-2 flex flex-row">
                        <input type="radio" name="travelling" value=1>
                        <label for="html" class="mx-2">Yes</label><br>
                        <input type="radio" name="travelling" value=0 checked>
                        <label for="css" class="mx-2">No</label>
                    </div>
                    @error('travelling')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <label for="leave_type_id" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Select
                        Leave Type</label>
                    <select name="leave_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" disabled>Choose Leave Type</option>
                        @if(count($leave_types))
                            @foreach ($leave_types as $leave_type)
                                <option value="{{ $leave_type->id }}">{{ $leave_type->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <input type="file" name="attachment_path"
                           class="block pt-2.5 px-0 w-full text-sm text-gray-900 bg-transparent appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    <label for="attachment_path"
                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Attachment</label>
                    @error('attachment_path')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <label for="substitute_employee_id" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Select
                        Substitute</label>
                    <select name="substitute_employee_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" disabled>Choose Substitute Employee</option>
                        <option value="">No Replacement</option>
                        @if(count($substitutes))
                            @foreach ($substitutes as $substitute)
                                <option value="{{ $substitute->id }}">{{ $substitute->first_name }} {{ $substitute->last_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

{{--                <div class="flex flex-col">--}}
{{--                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">--}}
{{--                        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">--}}
{{--                            <div class="overflow-hidden">--}}
{{--                                <table class="min-w-full">--}}
{{--                                    <thead class="border-b">--}}
{{--                                    <tr>--}}
{{--                                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">--}}

{{--                                        </th>--}}
{{--                                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">--}}
{{--                                            Leaves Year 2021--}}
{{--                                        </th>--}}
{{--                                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">--}}
{{--                                            Leaves Year 2022--}}
{{--                                        </th>--}}
{{--                                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">--}}
{{--                                            Confessionnels Year 2022--}}
{{--                                        </th>--}}
{{--                                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">--}}
{{--                                            Hours Recovery 2022--}}
{{--                                        </th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    <tr class="border-b">--}}
{{--                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">--}}
{{--                                            Droits à congés/heures de récupération--}}
{{--                                        </td>--}}
{{--                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">--}}
{{--                                            Mark--}}
{{--                                        </td>--}}
{{--                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">--}}
{{--                                            {{ $employee->nb_of_days }}--}}
{{--                                        </td>--}}
{{--                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">--}}
{{--                                            {{ $employee->confessionnels }}--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                    <tr class="bg-white border-b">--}}
{{--                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">--}}
{{--                                            Droits à congés utilisés--}}
{{--                                        </td>--}}
{{--                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">--}}
{{--                                            Jacob--}}
{{--                                        </td>--}}
{{--                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">--}}
{{--                                            Thornton--}}
{{--                                        </td>--}}
{{--                                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">--}}
{{--                                            @fat--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div
                    class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                    <div>
                        <a href="{{ url(route('leaves.submitted')) }}">
                            <button type="button"
                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Cancel
                            </button>
                        </a>
                    </div>
                    <div>
                        <button
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Create
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <script type="text/javascript">
        $('#fromDate').change(function(){
            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();
            if(fromDate>toDate) {
                $("#toDate").val(fromDate);
                toDate = $('#toDate').val();
            }
        });
    </script>

    <script type="text/javascript">
        let frompicker = $("#fromDate").flatpickr({
            minDate: "today",
            dateFormat: "Y-m-d",
            disable:[
                function(date) {
                    return (date.getDay() === 0 || date.getDay() === 6);
                },
            ],

            locale: {
                firstDayOfWeek: 1
            },
            onClose: function(selectedDates, dateStr, instance) {
                topicker.set('minDate', dateStr);
            },
        });

        let topicker = $("#toDate").flatpickr({
            minDate: "today",
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    return (date.getDay() === 0 || date.getDay() === 6);
                }
            ],
            locale: {
                firstDayOfWeek: 1
            }
        });

    </script>

</x-sidebar>
