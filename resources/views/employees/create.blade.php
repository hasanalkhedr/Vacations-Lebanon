<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Employees</title>
</head>
<body>
<form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="first_name">First Name</label>
        <input
            type="text"
            name="first_name"
            value="{{ old('first_name') }}"
        />
        <label for="last_name">Last Name</label>
        <input
            type="text"
            name="last_name"
            value="{{ old('last_name') }}"
        />
        <label for="email">Email</label>
        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
        />
        <label for="password">Password</label>
        <input
            type="password"
            name="password"
            value="{{ old('password') }}"
        />
        <label for="password_confirmation">Password Confirmation</label>
        <input
            type="password"
            name="password_confirmation"
            value="{{ old('password_confirmation') }}"
        />
        <label for="phone_number">Phone Number</label>
        <input
            type="text"
            name="phone_number"
            value="{{ old('phone_number') }}"
        />
        <label for="nb_of_days">Number of Days Off</label>
        <input
            type="number"
            name="nb_of_days"
            value="{{ old('nb_of_days') }}"
        />
        <select name='department_id'>
            <option value="" disabled>Choose Department</option>
            @if(count($departments))
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            @endif
        </select>
        <select name='role_id'>
            <option value="" disabled>Choose Role</option>

            @if(count($roles))
                @foreach ($roles as $role)
                    @unless($role->id == \Spatie\Permission\Models\Role::findByName('supervisor')->id)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endunless
                @endforeach
            @endif
        </select>
    </div>
    <button>Submit</button>
</form>
</body>
</html>
