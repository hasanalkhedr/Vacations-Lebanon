<x-sidebar>
    @section('title', 'Show Employee')
<h1>{{ $employee->first_name }} {{ $employee->last_name }}</h1>
<div>{{ $employee->email }}</div>
<div>{{ $employee->phone_number }}</div>
<div>{{ $employee->nb_of_days }}</div>
<div>{{ $employee->department()->pluck('name')->first() }}</div>
</x-sidebar>
