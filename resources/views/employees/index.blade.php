<x-sidebar>
    @section('title', 'Employees')

    <nav class="p-2 text-lg text-black font-bold">
        Departments
    </nav>

    <table class="w-full table-auto rounded-sm">
        <thead>
        <tr>
            <th class="py-4 border-t border-b border-gray-300 text-lg text-center">
                <div class="text-blue-400 px-6 py-2">
                    Name
                </div>
            </th>
            <th class="py-4 border-t border-b border-gray-300 text-lg text-center">
                <div class="text-blue-400 px-6 py-2">
                    Supervisor
                </div>
            </th>
            <th class="py-4 border-t border-b border-gray-300 text-lg text-center">
                <div class="text-blue-400 px-6 py-2">
                    Role
                </div>
            </th>
            <th colspan="2" class="py-4 border-t border-b border-gray-300 text-lg text-center">
                <div class="text-blue-400 px-6 py-2">
                    Actions
                </div>
            </th>
        </tr>
        </thead>
        <tbody>
        @unless($employees->isEmpty())
            @foreach ($employees as $employee)
                <tr>
                    <td class="px-4 py-4 border-t border-b border-gray-300 text-lg text-center" onclick="window.location.href='{{ route('employees.show', ['employee' => $employee]) }}'">
                        <div class="text-blue-400 px-6 py-2 items-center">
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </div>
                    </td>
                    <td class="px-4 py-4 border-t border-b border-gray-300 text-lg text-center" onclick="window.location.href='{{ route('employees.show', ['employee' => $employee]) }}'">
                        @if($employee->department == NULL)
                            <div class="text-blue-400 px-6 py-2">
                                -
                            </div>
                        @else
                            <div class="text-blue-400 px-6 py-2">
                                {{$employee->department->name}}
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-4 border-t border-b border-gray-300 text-lg text-center" onclick="window.location.href='{{ route('employees.show', ['employee' => $employee]) }}'">
                        <div class="text-blue-400 px-6 py-2 items-center">
                            {{ $employee->roles()->first()->name }}
                        </div>
                    </td>
                    <td class="px-4 py-4 border-t border-b border-gray-300 text-lg text-center">
                        <button class="text-green-500 acceptModal">Edit</button>
                    </td>
                    <td class="px-4 py-4 border-t border-b border-gray-300 text-lg text-center">
                        <button class="text-red-500 deleteModal">Delete</button>
                    </td>
                </tr>

                <div class="fixed z-10 inset-0 invisible overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="interestModalAccept">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg @click="toggleModal" class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Accept Leave Request
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                Are you sure you want to accept the leave request? This action cannot be undone.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                {{--                                <form method="POST" action="{{ route('leaves.accept', ['leave' => $leave]) }}">--}}
                                {{--                                    @csrf--}}
                                {{--                                    <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">--}}
                                {{--                                        Accept--}}
                                {{--                                    </button>--}}
                                {{--                                </form>--}}

                                <button type="button" class="closeModalAccept mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fixed z-10 inset-0 invisible overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="interestModalDelete">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form method="POST" action="{{ route('employees.destroy', ['employee' => $employee->id]) }}">
                                @csrf
                                @method('DELETE')
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg @click="toggleModal" class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                Delete Employee: {{ $employee->first_name }} {{ $employee->last_name }}
                                            </h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">
                                                    Are you sure you want to delete this employee? This action cannot be undone.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">

                                    <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Delete
                                    </button>


                                    <button type="button" class="closeModalDelete mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <tr class="border-gray-300">
                <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                    <p class="text-center">No Employees Found</p>
                </td>
            </tr>
        @endunless
        </tbody>
    </table>


@foreach ($employees as $employee)
    <p>{{ $employee->first_name }} {{ $employee->last_name}} <b>{{$employee->getRoleNames()->first()}}</b> <i>{{$employee->department()->pluck('name')->first()}}</i></p>
    <form method="POST" action="{{ route('employees.destroy', ['employee' => $employee->id]) }}">
        @csrf
        @method('DELETE')
        <button>Delete</button>
    </form>
@endforeach

    <script type="text/javascript">
        $(document).ready(function () {
            $('.deleteModal').on('click', function(e){
                $('#interestModalDelete').removeClass('invisible');
            });
            $('.closeModalDelete').on('click', function(e){
                $('#interestModalDelete').addClass('invisible');
            });
        });
    </script>

</x-sidebar>>
