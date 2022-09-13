<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Leave Request</title>
</head>
<body>
<h3>Today's Date: {{ $today->format('l d/m/y') }}</h3>
<h3>Name: {{ $employee->first_name }} {{ $employee->last_name }}</h3>
@if($employee->hasRole('employee'))
    <h3>Department Name: {{ $department->name }}</h3>
@elseif($employee->hasRole('human_resource'))
    <h3>Department Name: HR</h3>
@else
    <h3>Department Name: SG</h3>
@endif
<h3>Phone Number: {{ $employee->phone_number }}</h3>
    <form method="POST" action="{{ route('leaves.store') }}" enctype="multipart/form-data">
        @csrf
        <label for="from">From</label>
        <input
            onchange="setMinDate()"
            type="date"
            name="from"
            min={{ $today->addDay() }}
        />
        <label for="to">To</label>
        <input
            type="date"
            name="to"
        />
        <p>Travelling</p>
        <input type="radio" name="travelling" value=1>
        <label for="html">Yes</label><br>
        <input type="radio" name="travelling" value=0>
        <label for="css">No</label>

        <select name='leave_type_id' onchange="enableOrDisableReason(this);">
            <option value="" disabled>Choose Leave Type</option>
            @if(count($leave_types))
                @foreach ($leave_types as $leave_type)
                    <option value="{{ $leave_type->id }}">{{ $leave_type->name }}</option>
                @endforeach
            @endif
        </select>
        <label for="attachment_path">
            Attachment
        </label>
        <input
            type="file"
            name="attachment_path"
        />
        <select name='substitute_employee_id'>
            <option value="" disabled>Choose Substitute Employee</option>
            <option value="">None</option>
            @if(count($substitutes))
                @foreach ($substitutes as $substitute)
                    <option value="{{ $substitute->id }}">{{ $substitute->first_name }} {{ $substitute->last_name }}</option>
                @endforeach
            @endif
        </select>
        <button>Submit</button>
    </form>

<script type="text/javascript">
    function enableOrDisableReason(that) {
        if (that.value == 8) {
            document.getElementById("reason").disabled = false;
        } else {
            document.getElementById("reason").disabled = true;
            document.getElementById("reason").selectedIndex = 1;
        }
    }
    function setMinDate() {
        document.getElementById("fromInput").onchange = function () {
            var input = document.getElementById("toInput");
            input.setAttribute("min", this.value);
        }
    }

</script>
</body>
</html>
