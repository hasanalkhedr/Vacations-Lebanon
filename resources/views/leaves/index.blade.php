<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All Leave Requests</title>
</head>
<body>
@foreach ($leaves as $leave)
    <p>{{ $leave->employee->first_name }}
    <form method="POST" action="{{ route('leaves.accept', ['leave' => $leave->id]) }}">
        @csrf
        <button>Accept</button>
    </form>
    <form method="POST" action="{{ route('leaves.reject', ['leave' => $leave->id]) }}">
        @csrf
        <button>Reject</button>
    </form>
@endforeach
</body>
</html>
