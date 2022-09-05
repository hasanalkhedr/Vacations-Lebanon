<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All Employees</title>
</head>
<body>
@foreach ($employees as $employee)
    <p>{{ $employee->first_name }} {{ $employee->last_name}} <b>{{$employee->getRoleNames()->first()}}</b> <i>{{$employee->department()->pluck('name')->first()}}</i></p>
    <form method="POST" action="{{ route('employees.destroy', ['employee' => $employee->id]) }}">
        @csrf
        @method('DELETE')
        <button>Delete</button>
    </form>
@endforeach
</body>
</html>
