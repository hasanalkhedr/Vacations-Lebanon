<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Employee Profile</title>
</head>
<body>
<form method="POST" action="{{ route('employees.updateProfile',  ['employee' => $employee->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div>
        <label for="first_name">First Name</label>
        <input
            type="text"
            name="first_name"
            value="{{ $employee->first_name }}"
        />
        <label for="last_name">Last Name</label>
        <input
            type="text"
            name="last_name"
            value="{{ $employee->last_name }}"
        />
        <label for="email">Email</label>
        <input
            type="email"
            name="email"
            value="{{ $employee->email }}"
        />
        <label for="phone_number">Phone Number</label>
        <input
            type="text"
            name="phone_number"
            value="{{ $employee->phone_number }}"
        />
        <label for="nb_of_days">Number of Days Off</label>
        <input
            type="number"
            name="nb_of_days"
            value="{{ $employee->nb_of_days }}"
        />
        <select name='department_id'>
            <option value="" disabled>Choose Department</option>
            @if(count($departments))
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" {{ ( $department->id == $employee->department_id) ? 'selected' : '' }}>{{ $department->name }}</option>
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
