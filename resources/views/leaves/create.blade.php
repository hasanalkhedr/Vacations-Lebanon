<x-sidebar>
    <div class="relative w-full h-full md:h-auto">
        <div class="mx-4">
            <table class="mt-4 w-full text-sm text-left text-gray-500 dark:text-gray-400 border">
                <thead class="text-s text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr class="border-b">
                    <th scope="col" class="text-center py-3 px-2"></th>
                    <th scope="col" class="text-center py-3 px-2">
                        Remaining
                    </th>
                    <th scope="col" class="text-center py-3 px-2">
                        Pending
                    </th>
                    <th scope="col" class="text-center py-3 px-2">
                        Accepted
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="col" class="border-r-2 text-center py-3 px-2">
                        Leave Days
                    </th>
                    <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $employee->nb_of_days }}
                    </td>
                    <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $normal_pending_days }}
                    </td>
                    <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $normal_accepted_days }}
                    </td>
                </tr>
                <tr>
                    <th scope="col" class="border-r-2 text-center py-3 px-2">
                        Confessionnel Days
                    </th>
                    <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $employee->confessionnels }}
                    </td>
                    <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $confessionnel_pending_days }}
                    </td>
                    <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $confessionnel_accepted_days }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('leaves.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="relative z-0 mb-6 w-full group">
                    <label for="leave_duration_id" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Leave Duration</label>
                    <select name="leave_duration_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" disabled>Choose Leave Duration</option>
                        @if(count($leave_durations))
                            @foreach ($leave_durations as $leave_duration)
                                @if($leave_duration->name == "One or More Full Days")
                                    <option value="{{ $leave_duration->id }}" selected>{{ $leave_duration->name }}</option>
                                @else
                                    <option value="{{ $leave_duration->id }}">{{ $leave_duration->name }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 mb-6 w-full group">
                        <p>Use Confessionnels Only</p>
                        <div class="mt-2 flex flex-row">
                            <input type="checkbox" name="confessionnels" id="confessionnels">
                        </div>
                        @error('confessionnels')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative z-0 mb-6 w-full group" id="mix_of_leaves_div">
                        <p>Include Both</p>
                        <div class="mt-2 flex flex-row">
                            <input type="checkbox" name="mix_of_leaves" id="mix_of_leaves">
                        </div>
                        @error('mix_of_leaves')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full group flex flex-col">
                        <label id="fromDateLabel" for="from" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Start Date <span class="text-red-500">*</span></label>
                        <input type="text" name="from" id="fromDate" placeholder="Please Select Date Range" data-input onload="disableDates(this)">
                        @error('from')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative z-0 mb-6 w-full group flex flex-col" id="toDateDiv">
                        <label for="to" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">End Date <span class="text-red-500">*</span></label>
                        <input type="text" name="to" id="toDate" placeholder="Please Select Date Range" data-input>
                        @error('to')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <span id="error"></span>

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
                    <select id="leave_type" name="leave_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" disabled>Choose Leave Type</option>
                        @if(count($leave_types))
                            @foreach ($leave_types as $leave_type)
                                <option value="{{ $leave_type->id }}">{{ $leave_type->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <input type="file" name="attachment_path" id="attachment_path"
                           class="block pt-2.5 px-0 w-full text-sm text-gray-900 bg-transparent appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                    <label for="attachment_path"
                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Attachment <span id="attachment_file_span" class="text-red-500">*</span></label>
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
        $('#fromDate').change(function() {
            $('#createButton').attr('disabled', false);
            $("#error").text("");
            if ($('#confessionnels')[0].checked) {
                if ({{  auth()->user()->confessionnels }} === 0) {
                    $('#createButton').attr('disabled', true)
                    $('#error').css("color", "red");
                    $("#error").text("You don't have any confessionnel days left");
                }
                let fromDate = $('#fromDate').val();
                $("#toDate").val(fromDate);
            } else {
                let fromDate = $('#fromDate').val();
                let toDate = $('#toDate').val();
                if (fromDate > toDate) {
                    $("#toDate").val(fromDate);
                    toDate = $('#toDate').val();
                }
                let newFromDate = new Date(fromDate);
                let newToDate = new Date(toDate);
                let dateDifference = ((newToDate.getTime() - newFromDate.getTime()) / (1000 * 3600 * 24)) + 1;
                let dateDifference_confessionnels = 0;
                let tempDate = new Date(newFromDate.getTime());
                while (tempDate <= newToDate) {
                    let newTempDate = new Date(Date.parse(new Date(tempDate.setDate(tempDate.getDate())))).toISOString().split('T')[0];
                    if ($('#mix_of_leaves')[0].checked) {
                        if ({!! json_encode($disabled_dates) !!}.includes(newTempDate) || tempDate.getDay() === 0 || tempDate.getDay() === 6 || {!! json_encode($holiday_dates) !!}.includes(newTempDate) || {!! json_encode($confessionnel_dates) !!}.includes(newTempDate)) {
                            dateDifference = dateDifference - 1;
                        }
                        if ({!! json_encode($confessionnel_dates) !!}.includes(newTempDate)) {
                            dateDifference_confessionnels = dateDifference_confessionnels + 1;
                        }
                    }
                    else {
                        if ({!! json_encode($disabled_dates) !!}.includes(newTempDate) || tempDate.getDay() === 0 || tempDate.getDay() === 6 || {!! json_encode($holiday_dates) !!}.includes(newTempDate)) {
                            dateDifference = dateDifference - 1;
                        }
                    }
                    tempDate.setDate(tempDate.getDate() + 1);
                }
                if ($('#confessionnels')[0].checked) {
                    if (dateDifference > {{  auth()->user()->confessionnels }}) {

                        $('#createButton').attr('disabled', true)
                        $('#error').css("color", "red");
                        $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->confessionnels }} + " confessionnels days left");
                    }
                } else {
                    if (dateDifference > {{  auth()->user()->nb_of_days }}) {

                        $('#createButton').attr('disabled', true)
                        $('#error').css("color", "red");
                        $("#error").text("You chose a range of " + dateDifference + " days but you only have" + {{  auth()->user()->nb_of_days }} + " leave days left");
                    }
                    if (dateDifference_confessionnels > {{  auth()->user()->confessionnels }}) {

                        $('#createButton').attr('disabled', true)
                        $('#error').css("color", "red");
                        $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->confessionnels }} + " confessionnels days left");
                    }
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
            let dateDifference = ((newToDate.getTime() - newFromDate.getTime()) / (1000*3600*24)) + 1;
            let dateDifference_confessionnels = 0;
            let tempDate = new Date(newFromDate.getTime());
            while(tempDate <= newToDate){
                let newTempDate = new Date(Date.parse(new Date(tempDate.setDate(tempDate.getDate())))).toISOString().split('T')[0];
                if ($('#mix_of_leaves')[0].checked) {
                    if ({!! json_encode($disabled_dates) !!}.includes(newTempDate) || tempDate.getDay() === 0 || tempDate.getDay() === 6 || {!! json_encode($holiday_dates) !!}.includes(newTempDate) || {!! json_encode($confessionnel_dates) !!}.includes(newTempDate)) {
                        dateDifference = dateDifference - 1;
                    }
                    if ({!! json_encode($confessionnel_dates) !!}.includes(newTempDate) ) {
                        dateDifference_confessionnels = dateDifference_confessionnels + 1;
                    }
                }
                else {
                    if ({!! json_encode($disabled_dates) !!}.includes(newTempDate) || tempDate.getDay() === 0 || tempDate.getDay() === 6 || {!! json_encode($holiday_dates) !!}.includes(newTempDate)) {
                        dateDifference = dateDifference - 1;
                    }
                }
                tempDate.setDate(tempDate.getDate() + 1);
            }
            if($('#confessionnels')[0].checked) {
                if(dateDifference > {{  auth()->user()->confessionnels }}) {

                    $('#createButton').attr('disabled', true)
                    $('#error').css("color", "red");
                    $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->confessionnels }} + " confessionnels days left");
                }
            }
            else {
                if(dateDifference > {{  auth()->user()->nb_of_days }}) {

                    $('#createButton').attr('disabled', true)
                    $('#error').css("color", "red");
                    $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->nb_of_days }} + " leave days left");
                }
                if (dateDifference_confessionnels > {{  auth()->user()->confessionnels }}) {
                    console.log("DATE_DIFF_CONF : " + dateDifference_confessionnels);
                    console.log("CONF : " + {{  auth()->user()->confessionnels }});

                    $('#createButton').attr('disabled', true)
                    $('#error').css("color", "red");
                    $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->confessionnels }} + " confessionnels days left");
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
                if({!! json_encode($disabled_dates) !!}.includes(newTempDate) || tempDate.getDay() === 0 || tempDate.getDay() === 6 || {!! json_encode($holiday_dates) !!}.includes(newTempDate)){
                    dateDifference = dateDifference - 1;
                }
                tempDate.setDate(tempDate.getDate() + 1);
            }
            if($('#confessionnels')[0].checked) {
                if(dateDifference > {{  auth()->user()->confessionnels }}) {

                    $('#createButton').attr('disabled', true)
                    $('#error').css("color", "red");
                    $("#error").text("You chose a range of " + dateDifference + " days but you only have " + {{  auth()->user()->confessionnels }} + " confessionnels days left");
                }
            }
            else {
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
            dateFormat: "Y-m-d",
            disable: [
                function (date) {
                    let date_temp = new Date(date.getTime());
                    let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() + 1)))).toISOString().split('T')[0];
                    return (date.getDay() === 0 || date.getDay() === 6 || {!! json_encode($disabled_dates) !!}.includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date) || {!! json_encode($confessionnel_dates) !!}.includes(disabled_date));
                }],

            locale: {
                firstDayOfWeek: 1
            },
            onClose: function (selectedDates, dateStr, instance) {
                if (dateStr) {
                    topicker.set('minDate', dateStr);
                }
            },
        });

        let topicker = $("#toDate").flatpickr({
            dateFormat: "Y-m-d",
            disable: [
                function (date) {
                    let date_temp = new Date(date.getTime());
                    let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() + 1)))).toISOString().split('T')[0];
                    return (date.getDay() === 0 || date.getDay() === 6 || {!! json_encode($disabled_dates) !!}.includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date) || {!! json_encode($confessionnel_dates) !!}.includes(disabled_date));
                }],
            locale: {
                firstDayOfWeek: 1
            }
        });

        $("#confessionnels").change(function() {
            if(this.checked) {
                flatpickr("#fromDate", {}).clear();
                flatpickr("#toDate", {}).clear();
                $("#fromDateLabel").html("Date");
                $("#toDateDiv").addClass("invisible");
                $("#mix_of_leaves_div").addClass("invisible");
                $('#mix_of_leaves')[0].checked = false;
                $("#fromDate").attr("placeholder", "Please Select Date");
                $("#fromDate").flatpickr({
                    dateFormat: "Y-m-d",
                    enable: [
                        function (date) {
                            let date_temp = new Date(date.getTime());
                            let enable_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() + 1)))).toISOString().split('T')[0];
                            return ({!! json_encode($confessionnel_dates) !!}.includes(enable_date));
                        }],
                    locale: {
                        firstDayOfWeek: 1
                    },
                });
            }
            else{
                flatpickr("#fromDate", {}).clear();
                $("#fromDate").attr("placeholder", "Please Select Date Range");
                $("#fromDateLabel").html("Start Date");
                $("#toDateDiv").removeClass("invisible");
                $("#mix_of_leaves_div").removeClass("invisible");
                let fromDate = $('#fromDate').val();
                $("#toDate").val(fromDate);
                $("#fromDate").flatpickr({
                    dateFormat: "Y-m-d",
                    disable: [
                        function (date) {
                            let date_temp = new Date(date.getTime());
                            let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() + 1)))).toISOString().split('T')[0];
                            return (date.getDay() === 0 || date.getDay() === 6 || {!! json_encode($disabled_dates) !!}.includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date) || {!! json_encode($confessionnel_dates) !!}.includes(disabled_date));
                        }],

                    locale: {
                        firstDayOfWeek: 1
                    },
                });
            }
        });

        $("#mix_of_leaves").change(function() {
            if (this.checked) {
                flatpickr("#fromDate", {}).clear();
                flatpickr("#toDate", {}).clear();
                $("#fromDate").flatpickr({
                    dateFormat: "Y-m-d",
                    disable: [
                        function (date) {
                            let date_temp = new Date(date.getTime());
                            let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() + 1)))).toISOString().split('T')[0];
                            return (date.getDay() === 0 || date.getDay() === 6 || {!! json_encode($disabled_dates) !!}.includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date));
                        }],
                    locale: {
                        firstDayOfWeek: 1
                    },
                });

                $("#toDate").flatpickr({
                    dateFormat: "Y-m-d",
                    disable: [
                        function (date) {
                            let date_temp = new Date(date.getTime());
                            let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() + 1)))).toISOString().split('T')[0];
                            return (date.getDay() === 0 || date.getDay() === 6 || {!! json_encode($disabled_dates) !!}.includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date));
                        }],
                    locale: {
                        firstDayOfWeek: 1
                    },
                });
            }
            else {
                flatpickr("#fromDate", {}).clear();
                flatpickr("#toDate", {}).clear();
                $("#fromDate").flatpickr({
                    dateFormat: "Y-m-d",
                    disable: [
                        function (date) {
                            let date_temp = new Date(date.getTime());
                            let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() + 1)))).toISOString().split('T')[0];
                            return (date.getDay() === 0 || date.getDay() === 6 || {!! json_encode($disabled_dates) !!}.includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date) || {!! json_encode($confessionnel_dates) !!}.includes(disabled_date));
                        }],
                    locale: {
                        firstDayOfWeek: 1
                    },
                });

                $("#toDate").flatpickr({
                    dateFormat: "Y-m-d",
                    disable: [
                        function (date) {
                            let date_temp = new Date(date.getTime());
                            let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() + 1)))).toISOString().split('T')[0];
                            return (date.getDay() === 0 || date.getDay() === 6 || {!! json_encode($disabled_dates) !!}.includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date) || {!! json_encode($confessionnel_dates) !!}.includes(disabled_date));
                        }],
                    locale: {
                        firstDayOfWeek: 1
                    },
                });
            }
        })
    </script>

    <script>
        $("#leave_type").change(function () {
            if(this.options[this.selectedIndex].text === "recovery") {
                $('#attachment_file_span')[0].classList.remove('hidden')
            }
            else {
                $('#attachment_file_span')[0].classList.add('hidden')
            }
        })
    </script>
</x-sidebar>
