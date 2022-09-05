<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show Single Department</title>
</head>
<body>
    <h1>{{ $department->name }}</h1>
    <h2>{{ $manager->first_name }} {{ $manager->last_name }}</h2>
    <ul>
        @foreach($employees as $employee)
            <li>{{$employee->first_name}} {{$employee->last_name}}</li>
        @endforeach
    </ul>
</body>
</html>
