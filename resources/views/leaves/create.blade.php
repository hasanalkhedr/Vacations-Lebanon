<x-sidebar>
    <div class="relative w-full h-full md:h-auto">
        <div class="mx-4">
            <table class="mt-4 w-full text-sm text-left text-gray-500 border">
                <thead class="text-s uppercase bg-gray-50 blue-color">
                    <tr class="border-b">
                        <th scope="col" class="text-center py-3 px-2"></th>
                        <th scope="col" class="text-center py-3 px-2">
                            {{ __('Remaining') }}
                        </th>
                        <th scope="col" class="text-center py-3 px-2">
                            {{ __('Pending') }}
                        </th>
                        <th scope="col" class="text-center py-3 px-2">
                            {{ __('Accepted') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white">
                        <th scope="col" class="border-r-2 text-center py-3 px-2 blue-color">
                            {{ __('Leave Days') }}
                        </th>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $employee->nb_of_days }}
                        </td>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $normal_pending_days }}
                        </td>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $normal_accepted_days }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="border-r-2 text-center py-3 px-2 blue-color">
                            {{ __('Confessionnel Days') }}
                        </th>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $employee->confessionnels }}
                        </td>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $confessionnel_pending_days }}
                        </td>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $confessionnel_accepted_days }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mx-4">
            <table class="mt-4 w-full text-sm text-left text-gray-500 border">
                <thead class="text-s uppercase bg-gray-50 blue-color">
                </thead>
                <tbody>
                    <tr class="bg-white">
                        <th scope="col" class="border-r-2 text-center py-3 px-2 blue-color">
                            {{ __('Total Overtime') }}
                        </th>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $overtimeTotalTime }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" class="border-r-2 text-center py-3 px-2 blue-color">
                            {{ __('Days') }}
                        </th>
                        <td class="text-center border-b py-4 px-2 font-bold text-gray-900 whitespace-nowrap">
                            {{ $overtimeDays }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('leaves.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="relative z-0 mb-6 w-full group">
                    <label for="leave_duration_id"
                        class="mb-2 text-sm font-medium blue-color">{{ __('Leave Duration') }}</label>
                    <select id="leave_duration_id" name="leave_duration_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        onchange="calculateDateDifference();">
                        <option value="" disabled>{{ __('Choose Leave Duration') }}</option>
                        @if (count($leave_durations))
                            @foreach ($leave_durations as $leave_duration)
                                @if ($leave_duration->name == 'One or More Full Days')
                                    <option value="{{ $leave_duration->id }}" selected>{{ __($leave_duration->name) }}
                                    </option>
                                @else
                                    <option value="{{ $leave_duration->id }}">{{ __($leave_duration->name) }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="relative z-0 mb-6 w-full group">
                    <label for="leave_type" class="mb-2 text-sm font-medium blue-color">
                        {{ __('Select Leave Type') }}
                    </label>
                    <select id="leave_type" name="leave_type_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="" disabled>{{ __('Select Leave Type') }}</option>
                        @if (count($leave_types))
                            @foreach ($leave_types as $leave_type)
                                <option value="{{ $leave_type->id }}">{{ __($leave_type->name) }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                @if ($showConfessionnelButtons)
                    <div class="grid md:grid-cols-2 md:gap-6" id="confessionnels_mix_div">
                        <div class="relative z-0 mb-6 w-full group">
                            <p class="mb-2 text-sm font-medium blue-color">{{ __('Use Confessionnels Only') }}</p>
                            <div class="mt-2 flex flex-row">
                                <input type="checkbox" name="confessionnels" id="confessionnels">
                            </div>
                            @error('confessionnels')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative z-0 mb-6 w-full group" id="mix_of_leaves_div">
                            <p class="mb-2 text-sm font-medium blue-color">{{ __('Include Both') }}</p>
                            <div class="mt-2 flex flex-row">
                                <input type="checkbox" name="mix_of_leaves" id="mix_of_leaves">
                            </div>
                            @error('mix_of_leaves')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="relative z-0 w-full group flex flex-col">
                        <label id="fromDateLabel" for="fromDate" class="mb-2 text-sm font-medium blue-color">
                            {{ __('Start Date') }} <span class="text-red-500">*</span>
                        </label>
                        <input required type="text" name="from" id="fromDate"
                            placeholder="{{ __('Please select date range') }}" data-input
                            onchange="calculateDateDifference()">
                        @error('from')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative z-0 mb-6 w-full group flex flex-col" id="toDateDiv">
                        <label for="toDate" class="mb-2 text-sm font-medium blue-color">
                            {{ __('End Date') }} <span class="text-red-500">*</span>
                        </label>
                        <input required type="text" name="to" id="toDate"
                            placeholder="{{ __('Please select date range') }}" data-input
                            onchange="calculateDateDifference()">
                        @error('to')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <span id="error"></span>

                <div class="relative z-0 mb-6 w-full group">
                    <p class="mb-2 text-sm font-medium blue-color">{{ __('Travelling') }}</p>
                    <div class="mt-2 flex flex-row">
                        <input type="radio" id="travelling-yes" name="travelling" value=1>
                        <label for="travelling-yes" class="mx-2">{{ __('Yes') }}</label><br>
                        <input type="radio" id="travelling-no" name="travelling" value=0 checked>
                        <label for="travelling-no" class="mx-2">{{ __('No') }}</label>
                    </div>
                    @error('travelling')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <p class="mb-2 text-sm font-medium blue-color">{{ __('Attachment') }}<span
                            class="hidden text-red-500" id="attachment_file_span">*</span></p>
                    <div class="flex w-full">
                        <label
                            class="px-2 w-max flex flex-col items-center px py-2 bg-white text-white rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer blue-bg">
                            <svg class="w-5 h-5" fill="white" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">
                                <path
                                    d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                            </svg>
                            <span class="text-xs text-white leading-normal">{{ __('Choose File') }}</span>
                            <input type="file" name="attachment_path" id="attachment_path" class="hidden " />
                        </label>
                    </div>

                    @error('attachment_path')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <label for="substitute_employee_id" class="mb-2 text-sm font-medium">
                        {{ __('Select Substitute') }}
                    </label>
                    <select id="substitute_employee_id" name="substitute_employee_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 blue-color">
                        <option value="" disabled>{{ __('Choose Substitute Employee') }}</option>
                        <option value="">{{ __('No Replacement') }}</option>
                        @if (count($substitutes))
                            @foreach ($substitutes as $substitute)
                                <option value="{{ $substitute->id }}">{{ $substitute->first_name }}
                                    {{ $substitute->last_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                    <div>
                        <a href="{{ url(route('leaves.submitted')) }}">
                            <button type="button"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                {{ __('Cancel') }}
                            </button>
                        </a>
                    </div>
                    <div>
                        <button id="createButton"
                            class="text-white hover:bg-blue-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center blue-bg">
                            {{ __('Create') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        function checkBalanceBeforeSubmit() {
            $('#createButton').attr('disabled', false);
            $('#createButton').removeClass('disabled-button');
            $("#error").text("");

            let leaveType = $('#leave_type').val();
            let leaveDuration = $('#leave_duration_id').val();
            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();

            // Check if the leave type is "recovery" by comparing it with a known recovery leave type ID
            if (leaveType == "{{ $leave_types->firstWhere('name', 'recovery')->id }}") {
                return true; // Skip balance and pendingDays check if the leave type is "recovery"
            }


            // Fetch the remaining balance and pending days for the employee
            let remainingDays = {{ $employee->nb_of_days }};
            let pendingDays = {{ $normal_pending_days_without_recovery }};
            let totalPending = parseInt(pendingDays);

            // Calculate the date difference to check the number of requested leave days
            let newFromDate = new Date(changeDateFormat(fromDate));
            let newToDate = new Date(changeDateFormat(toDate));
            let dateDifference = ((newToDate.getTime() - newFromDate.getTime()) / (1000 * 3600 * 24)) + 1;

            // Perform the balance check
            if (remainingDays - totalPending < dateDifference) {
                let errorText =
                    "{{ __('You have insufficient balance to submit this request. You may have already booked more days than available in your balance.') }}";
                disableButtonAndShowError(errorText);
                return false;
            }

            return true;
        }

        // Modify the existing submission function
        $('form').submit(function(event) {
            if (!checkBalanceBeforeSubmit()) {
                event.preventDefault(); // Stop form submission if the balance is insufficient
            }
        });

        // Reuse this existing function to display the error
        function disableButtonAndShowError(text) {
            $('#createButton').attr('disabled', true);
            $('#createButton').addClass('disabled-button');
            $('#error').css("color", "red");
            $("#error").text(text);
        }

        $('#confessionnels').change(function() {
            $('#createButton').attr('disabled', false)
            $('#createButton').removeClass('disabled-button')
            $("#error").text("");
            let fromDate = changeDateFormat($('#fromDate').val());
            let toDate = changeDateFormat($('#toDate').val());
            if (!fromDate) {
                $("#fromDate").val($('#toDate').val());
                fromDate = changeDateFormat($('#fromDate').val());
            }
            let newFromDate = new Date(fromDate);
            let newToDate = new Date(toDate);
            let dateDifference = ((newToDate.getTime() - newFromDate.getTime()) / (1000 * 3600 * 24)) + 1;
            let tempDate = new Date(newFromDate.getTime());
            while (tempDate <= newToDate) {
                let newTempDate = new Date(Date.parse(new Date(tempDate.setDate(tempDate.getDate())))).toISOString()
                    .split('T')[0];
                if ({!! json_encode($disabled_dates) !!}.includes(newTempDate) || tempDate.getDay() === 0 || tempDate
                    .getDay() === 6 ||
                    {!! json_encode($holiday_dates) !!}.includes(newTempDate)) {
                    dateDifference = dateDifference - 1;
                }
                tempDate.setDate(tempDate.getDate() + 1);
            }
            if ($('#confessionnels')[0]?.checked) {
                if (dateDifference >
                    {{ auth()->user()->confessionnels }}) {
                    let text = "{{ __('You chose a range of') }} " + dateDifference +
                        " {{ __('days but you only have') }} " + {{ auth()->user()->confessionnels }} +
                        " {{ __('confessionnel days left') }}";
                    disableButtonAndShowError(text);
                }
            } else {
                if (dateDifference > {{ auth()->user()->nb_of_days }}) {
                    let text = "{{ __('You chose a range of') }} " + dateDifference +
                        " {{ __('days but you only have') }} " + {{ auth()->user()->nb_of_days }} +
                        " {{ __('leave days left') }}";
                    disableButtonAndShowError(text);
                }
            }
        });

        function calculateDateDifference() {
            if ($('#fromDate').val() == '' && $('#toDate').val() == '') {
                return;
            }
            $('#createButton').attr('disabled', false);
            $('#createButton').removeClass('disabled-button')
            $("#error").text("");
            if ($('#confessionnels')[0]?.checked) {
                if ({{ auth()->user()->confessionnels }} === 0) {
                    let text = "{{ __("You don't have any confessionnel days left") }}";
                    disableButtonAndShowError(text);
                }
                let fromDate = $('#fromDate').val();
                $("#toDate").val(fromDate);
            } else {
                let fromDate = changeDateFormat($('#fromDate').val());
                let toDate = changeDateFormat($('#toDate').val());
                if (fromDate > toDate || !toDate) {
                    $("#toDate").val($('#fromDate').val());
                    toDate = changeDateFormat($('#toDate').val());
                } else if (!fromDate) {
                    $("#fromDate").val($('#toDate').val());
                    fromDate = changeDateFormat($('#fromDate').val());
                }
                let newFromDate = new Date(fromDate);
                let newToDate = new Date(toDate);
                let dateDifference = ((newToDate.getTime() - newFromDate.getTime()) / (1000 * 3600 * 24)) + 1;
                let dateDifference_confessionnels = 0;
                let tempDate = new Date(newFromDate.getTime());
                while (tempDate <= newToDate) {
                    let newTempDate = new Date(Date.parse(new Date(tempDate.setDate(tempDate.getDate())))).toISOString()
                        .split('T')[0];
                    if ($('#mix_of_leaves')[0]?.checked) {
                        if ({!! json_encode($disabled_dates) !!}.includes(newTempDate) ||
                            tempDate.getDay() === 0 || tempDate.getDay() === 6 || {!! json_encode($holiday_dates) !!}.includes(
                                newTempDate) ||
                            {!! json_encode($confessionnel_dates) !!}.includes(newTempDate)) {
                            dateDifference = dateDifference - 1;
                        }
                        if ({!! json_encode($confessionnel_dates) !!}.includes(newTempDate)) {
                            dateDifference_confessionnels = dateDifference_confessionnels + 1;
                        }
                    } else {
                        if ({!! json_encode($disabled_dates) !!}.includes(newTempDate) || tempDate.getDay() === 0 || tempDate
                            .getDay() === 6 ||
                            {!! json_encode($holiday_dates) !!}.includes(newTempDate)) {
                            dateDifference = dateDifference - 1;
                        }
                    }
                    tempDate.setDate(tempDate.getDate() + 1);
                }
                selected_leave_type = $("#leave_type")[0].options[$("#leave_type")[0].selectedIndex].text.toLowerCase();
                selected_leave_duration = $("#leave_duration_id")[0].options[$("#leave_duration_id")[0].selectedIndex].text
                    .toLowerCase();
                if (selected_leave_type == "{{ __('recovery') }}".toLowerCase() ||
                    selected_leave_type == "recovery".toLowerCase()) {
                    if (selected_leave_duration == "{{ __('One or More Full Days') }}".toLowerCase() ||
                        selected_leave_type == "One or More Full Days".toLowerCase()) {
                        if (dateDifference >
                            {{ (int) $overtimeDays }}) {
                            let text = "{{ __('You chose a range of') }} " + dateDifference +
                                " {{ __('days but you only have') }} " + {{ $overtimeDays }} +
                                " {{ __('leave days left') }}";
                            disableButtonAndShowError(text);
                        }
                    } else {
                        if (dateDifference > {{ 2 * $overtimeDays }}) {
                            let text = "{{ __('You chose a range of') }} " + dateDifference / 2 +
                                " {{ __('days but you only have') }} " + {{ $overtimeDays }} +
                                " {{ __('leave days left') }}";
                            disableButtonAndShowError(text);
                        }
                    }
                } else if (selected_leave_type != "{{ __('remote work') }}".toLowerCase() || selected_leave_type !=
                    "remote work".toLowerCase()) {
                    if (selected_leave_duration == "{{ __('One or More Full Days') }}".toLowerCase() ||
                        selected_leave_type == "One or More Full Days".toLowerCase()) {
                        if (dateDifference > {{ auth()->user()->nb_of_days }}) {
                            let text = "{{ __('You chose a range of') }} " + dateDifference +
                                " {{ __('days but you only have') }} " + {{ auth()->user()->nb_of_days }} +
                                " {{ __('leave days left') }}";
                            disableButtonAndShowError(text);
                        }
                        if (dateDifference_confessionnels > {{ auth()->user()->confessionnels }}) {
                            let text = "{{ __('You chose a range of') }} " + dateDifference +
                                " {{ __('days but you only have') }} " + {{ auth()->user()->confessionnels }} +
                                " {{ __('confessionnel days left') }}";
                            disableButtonAndShowError(text);
                        }
                    } else {
                        if (dateDifference > 2 * {{ auth()->user()->nb_of_days }}) {
                            let text = "{{ __('You chose a range of') }} " + dateDifference / 2 +
                                " {{ __('days but you only have') }} " + {{ auth()->user()->nb_of_days }} +
                                " {{ __('leave days left') }}";
                            disableButtonAndShowError(text);
                        }
                        if (dateDifference_confessionnels > 2 * {{ auth()->user()->confessionnels }}) {
                            let text = "{{ __('You chose a range of') }} " + dateDifference / 2 +
                                " {{ __('days but you only have') }} " + {{ auth()->user()->confessionnels }} +
                                " {{ __('confessionnel days left') }}";
                            disableButtonAndShowError(text);
                        }
                    }
                }
            }
        }

        function changeDateFormat(date) {
            if (!date) {
                return null
            }
            let separator = '/';
            const [day, month, year] = date.split(separator);

            const formattedDate = [year, month, day].join('-');

            return formattedDate;
        }

        function disableButtonAndShowError(text) {
            $('#createButton').attr('disabled', true)
            $('#createButton').addClass('disabled-button')
            $('#error').css("color", "red");
            $("#error").text(text);
        }

        function selectedRecovery() {
            resetConfessionnelsCheckbox();
            $("#confessionnels_mix_div").addClass("hidden");
        }
    </script>

    <script type="text/javascript">
        flatpickr.localize(flatpickr.l10ns.fr);
        let frompicker = $("#fromDate").flatpickr({
            dateFormat: "d/m/Y",
            disable: [
                function(date) {
                    let date_temp = new Date(date.getTime());
                    let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() +
                        1)))).toISOString().split('T')[0];
                    return ({!! json_encode(phpToJsWeekdayArray(auth()->user()->weekdays_off)) !!}.includes(date.getDay()) || {!! json_encode($disabled_dates) !!}.includes(
                            disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date) ||
                        {!! json_encode($confessionnel_dates) !!}.includes(disabled_date));
                }
            ],

            locale: {
                firstDayOfWeek: 1
            },
            allowInput: true,
            onClose: function(selectedDates, dateStr, instance) {
                if (dateStr) {
                    topicker.set('minDate', dateStr);
                }
            },
        });

        let topicker = $("#toDate").flatpickr({
            dateFormat: "d/m/Y",
            disable: [
                function(date) {
                    let date_temp = new Date(date.getTime());
                    let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() +
                        1)))).toISOString().split('T')[0];
                    return ({!! json_encode(phpToJsWeekdayArray(auth()->user()->weekdays_off)) !!}.includes(date.getDay()) || {!! json_encode($disabled_dates) !!}
                        .includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date) ||
                        {!! json_encode($confessionnel_dates) !!}.includes(disabled_date));
                }
            ],
            locale: {
                firstDayOfWeek: 1
            },
            allowInput: true,
        });

        $("#confessionnels").change(function() {
            if (this.checked) {
                flatpickr("#fromDate", {}).clear();
                flatpickr("#toDate", {}).clear();
                $("#fromDateLabel").html("Date");
                $("#toDateDiv").addClass("invisible");
                $("#mix_of_leaves_div").addClass("invisible");
                if ($('#mix_of_leaves')[0]) $('#mix_of_leaves')[0].checked = false;
                $("#fromDate").attr("placeholder", "{{ __('Please select date') }}");
                $("#fromDate").flatpickr({
                    dateFormat: "d/m/Y",
                    enable: [
                        function(date) {
                            let date_temp = new Date(date.getTime());
                            let enable_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp
                                .getDate() + 1)))).toISOString().split('T')[0];
                            return ({!! json_encode($confessionnel_dates) !!}.includes(enable_date));
                        }
                    ],
                    locale: {
                        firstDayOfWeek: 1
                    },
                    allowInput: true,
                });
            } else {
                resetConfessionnelsCheckbox();
            }
        });

        $("#mix_of_leaves").change(function() {
            if (this.checked) {
                mixConfessionnelsAndRegularDays();
            } else {
                separateConfessionnelsAndRegularDays();
            }
        })

        function mixConfessionnelsAndRegularDays() {
            flatpickr("#fromDate", {}).clear();
            flatpickr("#toDate", {}).clear();
            $("#fromDate").flatpickr({
                dateFormat: "d/m/Y",
                disable: [
                    function(date) {
                        let date_temp = new Date(date.getTime());
                        let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() +
                            1)))).toISOString().split('T')[0];
                        return ({!! json_encode(phpToJsWeekdayArray(auth()->user()->weekdays_off)) !!}.includes(date.getDay()) || {!! json_encode($disabled_dates) !!}
                            .includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date));
                    }
                ],
                locale: {
                    firstDayOfWeek: 1
                },
                allowInput: true,
            });

            $("#toDate").flatpickr({
                dateFormat: "d/m/Y",
                disable: [
                    function(date) {
                        let date_temp = new Date(date.getTime());
                        let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() +
                            1)))).toISOString().split('T')[0];
                        return ({!! json_encode(phpToJsWeekdayArray(auth()->user()->weekdays_off)) !!}.includes(date.getDay()) || {!! json_encode($disabled_dates) !!}
                            .includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date));
                    }
                ],
                locale: {
                    firstDayOfWeek: 1
                },
                allowInput: true,
            });
        }

        function separateConfessionnelsAndRegularDays() {
            flatpickr("#fromDate", {}).clear();
            flatpickr("#toDate", {}).clear();
            $("#fromDate").flatpickr({
                dateFormat: "d/m/Y",
                disable: [
                    function(date) {
                        let date_temp = new Date(date.getTime());
                        let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() +
                            1)))).toISOString().split('T')[0];
                        return ({!! json_encode(phpToJsWeekdayArray(auth()->user()->weekdays_off)) !!}.includes(date.getDay()) || {!! json_encode($disabled_dates) !!}
                            .includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date) ||
                            {!! json_encode($confessionnel_dates) !!}.includes(disabled_date));
                    }
                ],
                locale: {
                    firstDayOfWeek: 1
                },
                allowInput: true,
            });

            $("#toDate").flatpickr({
                dateFormat: "d/m/Y",
                disable: [
                    function(date) {
                        let date_temp = new Date(date.getTime());
                        let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() +
                            1)))).toISOString().split('T')[0];
                        return ({!! json_encode(phpToJsWeekdayArray(auth()->user()->weekdays_off)) !!}.includes(date.getDay()) || {!! json_encode($disabled_dates) !!}
                            .includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date) ||
                            {!! json_encode($confessionnel_dates) !!}.includes(disabled_date));
                    }
                ],
                locale: {
                    firstDayOfWeek: 1
                },
                allowInput: true,
            });
        }

        function resetConfessionnelsCheckbox() {
            flatpickr("#fromDate", {}).clear();
            $("#fromDate").attr("placeholder", "{{ __('Please select date range') }}");
            $("#fromDateLabel").html("{{ __('Start Date') }} <span class='text-red-500'>*</span>");
            $("#toDateDiv").removeClass("invisible");
            $("#mix_of_leaves_div").removeClass("invisible");
            if ($('#mix_of_leaves')[0]) $('#mix_of_leaves')[0].checked = false;
            let fromDate = $('#fromDate').val();
            $("#toDate").val(fromDate);
            $("#fromDate").flatpickr({
                dateFormat: "d/m/Y",
                disable: [
                    function(date) {
                        let date_temp = new Date(date.getTime());
                        let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() +
                            1)))).toISOString().split('T')[0];
                        return ({!! json_encode(phpToJsWeekdayArray(auth()->user()->weekdays_off)) !!}.includes(date.getDay()) || {!! json_encode($disabled_dates) !!}
                            .includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date) ||
                            {!! json_encode($confessionnel_dates) !!}.includes(disabled_date));
                    }
                ],

                locale: {
                    firstDayOfWeek: 1
                },
                allowInput: true,
                onClose: function(selectedDates, dateStr, instance) {
                    if (dateStr) {
                        topicker.set('minDate', dateStr);
                    }
                },
            });
            let topicker = $("#toDate").flatpickr({
                dateFormat: "d/m/Y",
                disable: [
                    function(date) {
                        let date_temp = new Date(date.getTime());
                        let disabled_date = new Date(Date.parse(new Date(date_temp.setDate(date_temp.getDate() +
                            1)))).toISOString().split('T')[0];
                        return ({!! json_encode(phpToJsWeekdayArray(auth()->user()->weekdays_off)) !!}.includes(date.getDay()) || {!! json_encode($disabled_dates) !!}
                            .includes(disabled_date) || {!! json_encode($holiday_dates) !!}.includes(disabled_date) ||
                            {!! json_encode($confessionnel_dates) !!}.includes(disabled_date));
                    }
                ],
                locale: {
                    firstDayOfWeek: 1
                },
                allowInput: true,
            });
        }
    </script>

    <script>
        $("#leave_type").change(function() {
            selected_leave_type = this.options[this.selectedIndex].text.toLowerCase();
            if (selected_leave_type == "{{ __('sick leave') }}".toLowerCase() || selected_leave_type ==
                "sick leave".toLowerCase()) {
                $('#attachment_file_span')[0].classList.remove('hidden')
                document.getElementById("attachment_path").required = true;
            } else {
                $('#attachment_file_span')[0].classList.add('hidden')
                document.getElementById("attachment_path").required = false;
            }
            if (selected_leave_type == "{{ __('recovery') }}".toLowerCase() || selected_leave_type == "recovery"
                .toLowerCase() || selected_leave_type == "{{ __('sick leave') }}".toLowerCase() ||
                selected_leave_type == "sick leave".toLowerCase()) {
                selectedRecovery();
            } else {
                $("#confessionnels_mix_div").removeClass("hidden");
                $('#confessionnels')[0].checked = false;
                resetConfessionnelsCheckbox();
            }
            if (selected_leave_type == "{{ __('sick leave') }}".toLowerCase() || selected_leave_type ==
                "sick leave".toLowerCase()) {
                mixConfessionnelsAndRegularDays();
            } else {
                separateConfessionnelsAndRegularDays();
            }
        })
    </script>
</x-sidebar>
