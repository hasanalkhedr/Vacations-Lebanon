<x-sidebar>
    @section('title', 'Show Leave Request')
    <h3>Submission Date: {{ $leave->date_of_submission }}</h3>
    <h3>Name: {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</h3>
    <h3>Department Name: {{ $leave->employee->department->name }}</h3>
    <h3>Date From: {{ $leave->from }}</h3>
    <h3>Date From: {{ $leave->to }}</h3>
    @if($leave->travelling)
        <h3>Travelling: Yes</h3>
    @else
        <h3>Travelling: No</h3>
    @endif
    <h3>Leave Type: {{ $leave->leave_type->name }}</h3>
    @if($leave->attachment_path)
        <h3><a href="{{ route('leaves.downloadAttachment', ['leave' => $leave]) }}">Download Attached File</a></h3>
    @else
        <h3>No Attached File</h3>
    @endif
    <h3>Replacement: {{ $leave->substitute_employee->first_name }} {{ $leave->substitute_employee->last_name }}</h3>


</x-sidebar>
