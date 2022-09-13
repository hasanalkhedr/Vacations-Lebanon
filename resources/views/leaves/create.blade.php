<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Leave Request</title>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
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
            class="fromInput"
            type="date"
            name="from"
            min={{ $today->addDay() }}
        />
        <label for="to">To</label>
        <input
            class="toInput"
            type="date"
            name="to"
            min={{ $today->addDay() }}
        />
        <p>Travelling</p>
        <input type="radio" name="travelling" value=1>
        <label for="html">Yes</label><br>
        <input type="radio" name="travelling" value=0>
        <label for="css">No</label>

        <select name='leave_type_id'>
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
            <option value="">No Replacement</option>
            @if(count($substitutes))
                @foreach ($substitutes as $substitute)
                    <option value="{{ $substitute->id }}">{{ $substitute->first_name }} {{ $substitute->last_name }}</option>
                @endforeach
            @endif
        </select>
        <button>Submit</button>
    </form>

<script type="text/javascript">
    $('.fromInput').change(function(){
        let fromDate = $('.fromInput').val();
        let toDate = $('.toInput').val();
        if(fromDate>toDate) {
            $(".toInput").attr("min", fromDate);
            $(".toInput").val(fromDate);
            toDate = $('.toInput').val();
        }
    });

</script>
</body>
</html>
