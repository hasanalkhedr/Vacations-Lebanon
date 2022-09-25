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
                        <label for="from" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Start Date</label>
                        <input type="text" name="from" id="fromDate" placeholder="Please select Date Range" data-input onload="disableDates(this)">
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
                <span id="error"></span>

                <div class="relative z-0 mb-6 w-full group">
                    <p>Use Confessionnels</p>
                    <div class="mt-2 flex flex-row">
                        <input type="checkbox" name="confessionnels" id="confessionnels">
                    </div>
                    @error('confessionnels')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative z-0 mb-6 w-full group">
                    <p>Travelling</p>
                    <div class="mt-2 flex flex-row">
                        <input type="radio" name="travelling" value=1>
                        <label for="travelling" class="mx-2">Yes</label><br>
                        <input type="radio" name="travelling" value=0 checked>
                        <label for="travelling" class="mx-2">No</label>
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
{{--                                            LeaveMails Year 2021--}}
{{--                                        </th>--}}
{{--                                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">--}}
{{--                                            LeaveMails Year 2022--}}
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
                            id="createButton"
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
            $('#createButton').attr('disabled', false);
            $("#error").text("");
            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();
            if(fromDate>toDate) {
                $("#toDate").val(fromDate);
                toDate = $('#toDate').val();
            }
            let newFromDate = new Date(fromDate);
            let newToDate = new Date(toDate);
            dateDifference = ((newToDate.getTime() - newFromDate.getTime()) / (1000*3600*24)) + 1;
            let tempDate = new Date(newFromDate.getTime());
            while(tempDate <= newToDate){
                newTempDate = new Date(Date.parse(new Date(tempDate.setDate(tempDate.getDate())))).toISOString().split('T')[0];
                if({!! json_encode($disabled_dates) !!}.includes(newTempDate) || tempDate.getDay() === 0 || tempDate.getDay() === 6){
                    dateDifference = dateDifference - 1;
                }
                tempDate.setDate(tempDate.getDate() + 1);
            }
            if($('#confessionnels')[0].checked) {
                console.log(dateDifference + ' ' + {{  auth()->user()->confessionnels }})
                if(dateDifference > {{  auth()->user()->confessionnels }}) {

                    $('#createButton').attr('disabled', true)
                    $('#error').css("color", "red");
                    $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->confessionnels }} + " confessionnels days left");
                }
            }
            else {
                console.log(dateDifference + ' ' + {{  auth()->user()->nb_of_days }})
                if(dateDifference > {{  auth()->user()->nb_of_days }}) {

                    $('#createButton').attr('disabled', true)
                    $('#error').css("color", "red");
                    $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->nb_of_days }} + " leave days left");
                }
            }
        });

        $('#toDate').change(function(){
            $('#createButton').attr('disabled', false)
            $("#error").text("");
            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();
            if(!fromDate) {
                $("#fromDate").val(toDate);
                fromDate = $('#fromDate').val();
            }
            let newFromDate = new Date(fromDate);
            let newToDate = new Date(toDate);
            dateDifference = ((newToDate.getTime() - newFromDate.getTime()) / (1000*3600*24)) + 1;
            let tempDate = new Date(newFromDate.getTime());
            while(tempDate <= newToDate){
                newTempDate = new Date(Date.parse(new Date(tempDate.setDate(tempDate.getDate())))).toISOString().split('T')[0];
                if({!! json_encode($disabled_dates) !!}.includes(newTempDate) || tempDate.getDay() === 0 || tempDate.getDay() === 6){
                    dateDifference = dateDifference - 1;
                }
                tempDate.setDate(tempDate.getDate() + 1);
            }
            if($('#confessionnels')[0].checked) {
                console.log(dateDifference + ' ' + {{  auth()->user()->confessionnels }})
                if(dateDifference > {{  auth()->user()->confessionnels }}) {

                    $('#createButton').attr('disabled', true)
                    $('#error').css("color", "red");
                    $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->confessionnels }} + " confessionnels days left");
                }
            }
            else {
                console.log(dateDifference + ' ' + {{  auth()->user()->nb_of_days }})
                if(dateDifference > {{  auth()->user()->nb_of_days }}) {

                    $('#createButton').attr('disabled', true)
                    $('#error').css("color", "red");
                    $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->nb_of_days }} + " leave days left");
                }
            }
        });

        $('#confessionnels').change(function(){
            $('#createButton').attr('disabled', false)
            $("#error").text("");
            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();
            if(!fromDate) {
                $("#fromDate").val(toDate);
                fromDate = $('#fromDate').val();
            }
            let newFromDate = new Date(fromDate);
            let newToDate = new Date(toDate);
            dateDifference = ((newToDate.getTime() - newFromDate.getTime()) / (1000*3600*24)) + 1;
            let tempDate = new Date(newFromDate.getTime());
            while(tempDate <= newToDate){
                newTempDate = new Date(Date.parse(new Date(tempDate.setDate(tempDate.getDate())))).toISOString().split('T')[0];
                if({!! json_encode($disabled_dates) !!}.includes(newTempDate) || tempDate.getDay() === 0 || tempDate.getDay() === 6){
                    dateDifference = dateDifference - 1;
                }
                tempDate.setDate(tempDate.getDate() + 1);
            }
            if($('#confessionnels')[0].checked) {
                console.log(dateDifference + ' ' + {{  auth()->user()->confessionnels }})
                if(dateDifference > {{  auth()->user()->confessionnels }}) {

                    $('#createButton').attr('disabled', true)
                    $('#error').css("color", "red");
                    $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->confessionnels }} + " confessionnels days left");
                }
            }
            else {
                console.log(dateDifference + ' ' + {{  auth()->user()->nb_of_days }})
                if(dateDifference > {{  auth()->user()->nb_of_days }}) {

                    $('#createButton').attr('disabled', true)
                    $('#error').css("color", "red");
                    $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->nb_of_days }} + " leave days left");
                }
            }
        });
    </script>

    <script type="text/javascript">
        let frompicker = $("#fromDate").flatpickr({
            minDate: "today",
            dateFormat: "Y-m-d",
            disable:[
                function(date) {
                    let date_temp = new Date(date.getTime());
                    let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate()+1)))).toISOString().split('T')[0];
                    return (date.getDay() === 0 || date.getDay() === 6 || {!! json_encode($disabled_dates) !!}.includes(disabled_date) );
                }],

            locale: {
                firstDayOfWeek: 1
            },
            onClose: function(selectedDates, dateStr, instance) {
                if(dateStr) {
                    topicker.set('minDate', dateStr);
                }
            },
        });

        let topicker = $("#toDate").flatpickr({
            minDate: "today",
            dateFormat: "Y-m-d",
            disable:[
                function(date) {
                    let date_temp = new Date(date.getTime());
                    let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate()+1)))).toISOString().split('T')[0];
                    return (date.getDay() === 0 || date.getDay() === 6 || {!! json_encode($disabled_dates) !!}.includes(disabled_date) );
                }],
            locale: {
                firstDayOfWeek: 1
            }
        });

    </script>

</x-sidebar>
