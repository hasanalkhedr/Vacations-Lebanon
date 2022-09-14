<x-sidebar>
    @section('title', 'Employees')
    <nav class="px-6 py-3 text-lg text-black font-bold">
        Users
    </nav>

    @include('partials._search')
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-s text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">
                    Name
                </th>
                <th scope="col" class="py-3 px-6">
                    Department
                </th>
                <th scope="col" class="py-3 px-6">
                    Role
                </th>
                <th scope="col" class="py-3 px-6">
                    <span class="sr-only">Edit</span>
                </th><th scope="col" class="py-3 px-6">
                    <span class="sr-only">Delete</span>
                </th>
            </tr>
            </thead>
            <tbody>
            @unless($employees->isEmpty())
                @foreach ($employees as $employee)
                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="border-b py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </th>
                        <td class="py-4 px-6 border-b">
                            @if($employee->department == NULL)
                                <div class="font-bold">
                                    -
                                </div>
                            @else
                                {{$employee->department->name}}
                            @endif
                        </td>
                        <td class="py-4 px-6 border-b">
                            {{ $employee->roles()->first()->name }}
                        </td>
                        <td class="py-4 px-6 text-right border-b">
                            <button class="font-medium text-blue-600 dark:text-blue-500 hover:underline" type="button" data-modal-toggle="editModal-{{$employee->id}}">
                                Edit
                            </button>
                        </td>
                        <td class="py-4 px-6 text-right border-b">
                            <button class="font-medium text-red-600 dark:text-red-500 hover:underline" type="button" data-modal-toggle="deleteModal-{{$employee->id}}">
                                Delete
                            </button>
                        </td>

                        <div id="editModal-{{$employee->id}}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div class="flex justify-between items-center p-4 rounded-t border-b dark:border-gray-600">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg @click="toggleModal" class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                                            Edit Employee: {{ $employee->first_name }} {{ $employee->last_name }}
                                        </div>
                                        <div>
                                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="editModal-{{$employee->id}}">
                                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-6">
                                        <div class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                            Are you sure you want to delete this employee? This action cannot be undone.
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                                        <div>
                                            <form method="POST" action="{{ route('employees.destroy', ['employee' => $employee->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button data-modal-toggle="editModal-{{$employee->id}}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Delete</button>
                                            </form>
                                        </div>
                                        <div>
                                            <button data-modal-toggle="editModal-{{$employee->id}}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="deleteModal-{{$employee->id}}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div class="flex justify-between items-center p-4 rounded-t border-b dark:border-gray-600">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg @click="toggleModal" class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                                            Delete Employee: {{ $employee->first_name }} {{ $employee->last_name }}
                                        </div>
                                        <div>
                                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="deleteModal-{{$employee->id}}">
                                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-6">
                                        <div class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                            Are you sure you want to delete this employee? This action cannot be undone.
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                                        <div>
                                        <form method="POST" action="{{ route('employees.destroy', ['employee' => $employee->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button data-modal-toggle="deleteModal-{{$employee->id}}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Delete</button>
                                        </form>
                                        </div>
                                        <div>
                                            <button data-modal-toggle="deleteModal-{{$employee->id}}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</button>
                                        </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        {{--                        <div id="deleteModal" class="fixed z-10 inset-0 invisible overflow-y-auto" aria-labelledby="modal-title" role="dialog" tabindex="-1" aria-hidden="true">--}}
{{--                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">--}}
{{--                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>--}}
{{--                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>--}}
{{--                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">--}}
{{--                                    <form method="POST" action="{{ route('employees.destroy', ['employee' => $employee->id]) }}">--}}
{{--                                        @csrf--}}
{{--                                        @method('DELETE')--}}
{{--                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">--}}
{{--                                            <div class="sm:flex sm:items-start">--}}
{{--                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">--}}
{{--                                                    <svg @click="toggleModal" class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">--}}
{{--                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />--}}
{{--                                                    </svg>--}}
{{--                                                </div>--}}
{{--                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">--}}
{{--                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">--}}
{{--                                                        Delete Employee: {{ $employee->first_name }} {{ $employee->last_name }} {{$employee->roles()->first()->name}}--}}
{{--                                                    </h3>--}}
{{--                                                    <div class="mt-2">--}}
{{--                                                        <p class="text-sm text-gray-500">--}}
{{--                                                            Are you sure you want to delete this employee? This action cannot be undone.--}}
{{--                                                        </p>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">--}}
{{--                                            <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">--}}
{{--                                                Delete--}}
{{--                                            </button>--}}
{{--                                            <button data-modal-toggle="deleteModal-{{ $employee->id }}" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">--}}
{{--                                                Cancel--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </tr>
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
    </div>

    <div class="mt-6 p-4">
        {{ $employees->links() }}
    </div>

</x-sidebar>>


{{--<x-sidebar>--}}


{{--</x-sidebar>--}}
