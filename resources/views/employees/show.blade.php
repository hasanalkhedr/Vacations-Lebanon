<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show Single Employee</title>
</head>
<body>
<h1>{{ $employee->first_name }} {{ $employee->last_name }}</h1>
<div>{{ $employee->email }}</div>
<div>{{ $employee->phone_number }}</div>
<div>{{ $employee->nb_of_days }}</div>
<div>{{ $employee->department()->pluck('name')->first() }}</div>
</body>
</html>
