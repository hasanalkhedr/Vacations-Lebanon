<x-sidebar>
    @section('title', 'Employees')
    @push('head')
        <style>
            [x-cloak] {
                display: none;
            }

            .svg-icon {
                width: 1em;
                height: 1em;
            }

            .svg-icon path,
            .svg-icon polygon,
            .svg-icon rect {
                fill: #333;
            }

            .svg-icon circle {
                stroke: #4691f6;
                stroke-width: 1;
            }

        </style>
    @endpush
    <nav class="flex justify-between items-center p-2 text-black font-bold">
        <div class="text-lg">
            Users
        </div>
        @hasanyrole('human_resource|sg')
        <div>
            <button class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-full"
                    data-modal-toggle="createModal">
                Add User
            </button>
        </div>
        @endhasanyrole
    </nav>

    @include('partials.searches._search-employees')
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table x-data="data()" class="w-full text-sm text-left text-gray-500 dark:text-gray-400" x-data="employeeData">
            @unless($employees->isEmpty())
            <thead class="text-s text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                    Name
                </th>
                <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                    Department
                </th>
                <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                    Role
                </th>
                @if(auth()->user()->hasRole('human_resource'))
                    <th scope="col" class="py-3 px-6">
                        <span class="sr-only">Edit</span>
                    </th>
                    <th scope="col" class="py-3 px-6">
                        <span class="sr-only">Delete</span>
                    </th>
                @endif
            </tr>
            </thead>
            <tbody x-ref="tbody">
                @foreach ($employees as $employee)
                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap dark:text-white" onclick="window.location.href = '{{ url(route('employees.show', ['employee' => $employee->id])) }}'">
                            <div class="cursor-pointer">
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </div>
                        </td>
                        @if($employee->department == NULL)
                            <td class="py-4 px-6 border-b">
                                <div class="font-bold">
                                    -
                                </div>
                            </td>
                        @else
                            <td class="py-4 px-6 border-b" onclick="window.location.href = '{{ url(route('departments.show', ['department' => $employee->department->id])) }}'">
                                <div class="cursor-pointer">
                                    {{$employee->department->name}}
                                </div>
                            </td>
                        @endif
                        <td class="py-4 px-6 border-b">
                            @if($employee->getRoleNames()->count() == 1)
                                {{ $employee->roles()->first()->name }}
                            @else
                                @foreach($employee->getRoleNames() as $role_name)
                                    @if($employee->getRoleNames()->last() == $role_name)
                                        {{ $role_name }}
                                    @else
                                        {{ $role_name }} |
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        @hasanyrole('human_resource|sg')
                            <td class="py-4 px-6 text-right border-b">
                                <button class="font-medium text-blue-600 dark:text-blue-500 hover:underline" type="button"
                                        data-modal-toggle="editProfileModal-{{$employee->id}}">
                                    Edit
                                </button>
                            </td>
                            <td class="py-4 px-6 text-right border-b">
                                <button class="font-medium text-red-600 dark:text-red-500 hover:underline" type="button"
                                        data-modal-toggle="deleteModal-{{$employee->id}}">
                                    Delete
                                </button>
                            </td>
                        @endhasanyrole

                        <div id="deleteModal-{{$employee->id}}" tabindex="-1" aria-hidden="true"
                             class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div
                                        class="flex justify-between items-center p-4 rounded-t border-b dark:border-gray-600">
                                        <div
                                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg @click="toggleModal" class="h-6 w-6 text-red-600"
                                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        </div>
                                        <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                                            Delete Employee: {{ $employee->first_name }} {{ $employee->last_name }}
                                        </div>
                                        <div>
                                            <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                    data-modal-toggle="deleteModal-{{$employee->id}}">
                                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
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
                                    <div
                                        class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                                        <div>
                                            <button data-modal-toggle="deleteModal-{{$employee->id}}" type="button"
                                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                Cancel
                                            </button>
                                        </div>
                                        <div>
                                            <form method="POST"
                                                  action="{{ route('employees.destroy', ['employee' => $employee->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button data-modal-toggle="deleteModal-{{$employee->id}}"
                                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="editProfileModal-{{$employee->id}}" tabindex="-1" aria-hidden="true"
                             class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div
                                        class="flex justify-between items-center p-4 rounded-t border-b dark:border-gray-600">
                                        <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                                            Edit Employee: {{ $employee->first_name }} {{ $employee->last_name }}
                                        </div>
                                        <div>
                                            <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                    data-modal-toggle="editProfileModal-{{$employee->id}}">
                                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-6">
                                        <form method="POST"
                                              action="{{ route('employees.updateProfile', ['employee' => $employee->id]) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid md:grid-cols-2 md:gap-6">
                                                <div class="relative z-0 mb-6 w-full group">
                                                    <input type="text" name="first_name"
                                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                           value="{{$employee->first_name}}" required/>
                                                    <label for="first_name"
                                                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First
                                                        name</label>
                                                </div>
                                                <div class="relative z-0 mb-6 w-full group">
                                                    <input type="text" name="last_name"
                                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                           value="{{$employee->last_name}}" required/>
                                                    <label for="last_name"
                                                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last
                                                        name</label>
                                                </div>
                                            </div>
                                            <div class="relative z-0 mb-6 w-full group">
                                                <input type="email" name="email"
                                                       class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                       value="{{$employee->email}}" required/>
                                                <label for="email"
                                                       class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
                                            </div>
                                            <div class="grid md:grid-cols-2 md:gap-6">
                                                <div class="relative z-0 mb-6 w-full group">
                                                    <input type="text" name="phone_number"
                                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                           value="{{$employee->phone_number}}" required/>
                                                    <label for="phone_number"
                                                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone
                                                        number</label>
                                                </div>
                                                <div class="relative z-0 mb-6 w-full group">
                                                    <input type="number" name="nb_of_days"
                                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                           value="{{$employee->nb_of_days}}" required/>
                                                    <label for="nb_of_days"
                                                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Number
                                                        of Days Off</label>
                                                </div>
                                            </div>
                                            <div class="relative z-40 mb-6 w-full group">
                                                <label for="role_id"
                                                       class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Select
                                                    role</label>
                                                <select x-cloak name="role_id" id="role_id_create" onchange="enableOrDisableDepartment(this)"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    @if(count($roles))
                                                        @foreach ($roles as $role)
                                                            @unless($role->id == \Spatie\Permission\Models\Role::findByName('supervisor')->id)
                                                                @if($employee->hasRole($role->name))
                                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                                @else
                                                                    <option
                                                                        value="{{ $role->id }}">{{ $role->name }}</option>
                                                                @endif
                                                            @endunless
                                                        @endforeach
                                                    @endif
                                                </select>

                                                <div x-data="dropdown()" x-init="loadOptions()" class="w-full flex flex-col items-center">
                                                    <input name="values" type="hidden" x-bind:value="selectedValues()">
                                                    <div class="inline-block relative w-full">
                                                        <div class="flex flex-col items-center relative">
                                                            <div x-on:click="open" class="w-full">
                                                                <div class="my-2 p-1 flex border border-gray-200 bg-white rounded">
                                                                    <div class="flex flex-auto flex-wrap">
                                                                        <template x-for="(option,index) in selected" :key="options[option].value">
                                                                            <div class="flex justify-center items-center m-1 font-medium py-1 px-1 bg-white rounded bg-gray-100 border">
                                                                                <div class="text-xs font-normal leading-none max-w-full flex-initial x-model=" options[option] x-text="options[option].text"></div>
                                                                                <div class="flex flex-auto flex-row-reverse">
                                                                                    <div x-on:click.stop="remove(index,option)">
                                                                                        <svg class="fill-current h-4 w-4 " role="button" viewBox="0 0 20 20">
                                                                                            <path d="M14.348,14.849c-0.469,0.469-1.229,0.469-1.697,0L10,11.819l-2.651,3.029c-0.469,0.469-1.229,0.469-1.697,0
                                           c-0.469-0.469-0.469-1.229,0-1.697l2.758-3.15L5.651,6.849c-0.469-0.469-0.469-1.228,0-1.697s1.228-0.469,1.697,0L10,8.183
                                           l2.651-3.031c0.469-0.469,1.228-0.469,1.697,0s0.469,1.229,0,1.697l-2.758,3.152l2.758,3.15
                                           C14.817,13.62,14.817,14.38,14.348,14.849z" />
                                                                                        </svg>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </template>
                                                                        <div x-show="selected.length == 0" class="flex-1">
                                                                            <input placeholder="Select role" class="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800" x-bind:value="selectedValues()">
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200 svelte-1l8159u">

                                                                        <button type="button" x-show="isOpen() === true" x-on:click="open" class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                                                            <svg version="1.1" class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                                                <path d="M17.418,6.109c0.272-0.268,0.709-0.268,0.979,0s0.271,0.701,0,0.969l-7.908,7.83
	c-0.27,0.268-0.707,0.268-0.979,0l-7.908-7.83c-0.27-0.268-0.27-0.701,0-0.969c0.271-0.268,0.709-0.268,0.979,0L10,13.25
	L17.418,6.109z" />
                                                                            </svg>

                                                                        </button>
                                                                        <button type="button" x-show="isOpen() === false" @click="close" class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                                                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                                                <path d="M2.582,13.891c-0.272,0.268-0.709,0.268-0.979,0s-0.271-0.701,0-0.969l7.908-7.83
	c0.27-0.268,0.707-0.268,0.979,0l7.908,7.83c0.27,0.268,0.27,0.701,0,0.969c-0.271,0.268-0.709,0.268-0.978,0L10,6.75L2.582,13.891z
	" />
                                                                            </svg>

                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="w-full px-4">
                                                                <div x-show.transition.origin.top="isOpen()" class="absolute shadow top-100 bg-white z-40 w-full left-0 rounded max-h-select" x-on:click.away="close">
                                                                    <div class="flex flex-col w-full overflow-y-auto h-64">
                                                                        <template x-for="(option,index) in options" :key="option" class="overflow-auto">
                                                                            <div class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-gray-100" @click="select(index,$event)">
                                                                                <div class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative">
                                                                                    <div class="w-full items-center flex justify-between">
                                                                                        <div class="mx-2 leading-6" x-model="option" x-text="option.text"></div>
                                                                                        <div x-show="option.selected">
                                                                                            <svg class="svg-icon" viewBox="0 0 20 20">
                                                                                                <path fill="none" d="M7.197,16.963H7.195c-0.204,0-0.399-0.083-0.544-0.227l-6.039-6.082c-0.3-0.302-0.297-0.788,0.003-1.087
							C0.919,9.266,1.404,9.269,1.702,9.57l5.495,5.536L18.221,4.083c0.301-0.301,0.787-0.301,1.087,0c0.301,0.3,0.301,0.787,0,1.087
							L7.741,16.738C7.596,16.882,7.401,16.963,7.197,16.963z"></path>
                                                                                            </svg>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="relative z-0 mb-6 w-full group">
                                                <label for="department_id"
                                                       class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Select
                                                    Department</label>
                                                <select name="department_id" id="department_id"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option value="" disabled>Choose Department</option>
                                                    @if(count($departments))
                                                        @foreach ($departments as $department)
                                                            <option
                                                                value="{{ $department->id }}" {{ ( $department->id == $employee->department_id) ? 'selected' : '' }}>{{ $department->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div
                                                class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                                                <div>
                                                    <button data-modal-toggle="editProfileModal-{{$employee->id}}"
                                                            type="button"
                                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                        Cancel
                                                    </button>
                                                </div>
                                                <div>
                                                    <button
                                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                        data-modal-toggle="editProfileModal-{{$employee->id}}">Edit
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

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

        <div id="createModal" tabindex="-1" aria-hidden="true"
             class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex justify-between items-center p-4 rounded-t border-b dark:border-gray-600">
                        <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                            Create User
                        </div>
                        <div>
                            <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-toggle="createModal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6">
                        <form method="POST" action="{{ route('employees.store') }}">
                            @csrf
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 mb-4 w-full group">
                                    <input type="text" name="first_name"
                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                           placeholder="" required/>
                                    <label for="first_name"
                                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First
                                        name</label>
                                    @error('first_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="relative z-0 mb-4 w-full group">
                                    <input type="text" name="last_name"
                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                           placeholder="" required/>
                                    <label for="last_name"
                                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last
                                        name</label>
                                    @error('last_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="relative z-0 mb-4 w-full group">
                                <input type="email" name="email"
                                       class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder="" required/>
                                <label for="email"
                                       class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
                                @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative z-0 mb-4 w-full group">
                                <input type="password" name="password"
                                       class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder="" required/>
                                <label for="password"
                                       class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
                                @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative z-0 mb-4 w-full group">
                                <input type="password" name="password_confirmation"
                                       class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder="" required/>
                                <label for="password_confirmation"
                                       class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirm
                                    Password</label>
                                @error('password_confirmation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative z-0 mb-4 w-full group">
                                <input type="text" name="phone_number"
                                       class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                       placeholder="" required/>
                                <label for="phone_number"
                                       class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone
                                    number</label>
                                @error('phone_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 mb-4 w-full group">
                                    <input type="number" name="nb_of_days"
                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                           placeholder="" required/>
                                    <label for="nb_of_days"
                                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Number
                                        of Days Off</label>
                                    @error('nb_of_days')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="relative z-0 mb-4 w-full group">
                                    <input type="number" name="confessionnels"
                                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                           placeholder="" required/>
                                    <label for="confessionnels"
                                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confessionnels</label>
                                    @error('confessionnels')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="relative z-40 mb-4 w-full group">
                                <label for="role_id" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Select
                                    role</label>
                                <select x-cloak name="role_id" id="role_id_create" onchange="enableOrDisableDepartment(this);"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    @if(count($roles))
                                        @foreach ($roles as $role)
                                            @unless($role->id == \Spatie\Permission\Models\Role::findByName('supervisor')->id)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endunless
                                        @endforeach
                                    @endif
                                </select>

                                <div x-data="dropdown()" x-init="loadOptions()" class="w-full flex flex-col items-center">
                                    <input name="values" type="hidden" x-bind:value="selectedValues()">
                                    <div class="inline-block relative w-full">
                                        <div class="flex flex-col items-center relative">
                                            <div x-on:click="open" class="w-full">
                                                <div class="my-2 p-1 flex border border-gray-200 bg-white rounded">
                                                    <div class="flex flex-auto flex-wrap">
                                                        <template x-for="(option,index) in selected" :key="options[option].value">
                                                            <div class="flex justify-center items-center m-1 font-medium py-1 px-1 bg-white rounded bg-gray-100 border">
                                                                <div class="text-xs font-normal leading-none max-w-full flex-initial x-model=" options[option] x-text="options[option].text"></div>
                                                                <div class="flex flex-auto flex-row-reverse">
                                                                    <div x-on:click.stop="remove(index,option)">
                                                                        <svg class="fill-current h-4 w-4 " role="button" viewBox="0 0 20 20">
                                                                            <path d="M14.348,14.849c-0.469,0.469-1.229,0.469-1.697,0L10,11.819l-2.651,3.029c-0.469,0.469-1.229,0.469-1.697,0
                                           c-0.469-0.469-0.469-1.229,0-1.697l2.758-3.15L5.651,6.849c-0.469-0.469-0.469-1.228,0-1.697s1.228-0.469,1.697,0L10,8.183
                                           l2.651-3.031c0.469-0.469,1.228-0.469,1.697,0s0.469,1.229,0,1.697l-2.758,3.152l2.758,3.15
                                           C14.817,13.62,14.817,14.38,14.348,14.849z" />
                                                                        </svg>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>
                                                        <div x-show="selected.length == 0" class="flex-1">
                                                            <input placeholder="Select role" class="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800" x-bind:value="selectedValues()">
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200 svelte-1l8159u">

                                                        <button type="button" x-show="isOpen() === true" x-on:click="open" class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                                            <svg version="1.1" class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                                <path d="M17.418,6.109c0.272-0.268,0.709-0.268,0.979,0s0.271,0.701,0,0.969l-7.908,7.83
	c-0.27,0.268-0.707,0.268-0.979,0l-7.908-7.83c-0.27-0.268-0.27-0.701,0-0.969c0.271-0.268,0.709-0.268,0.979,0L10,13.25
	L17.418,6.109z" />
                                                            </svg>

                                                        </button>
                                                        <button type="button" x-show="isOpen() === false" @click="close" class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                                <path d="M2.582,13.891c-0.272,0.268-0.709,0.268-0.979,0s-0.271-0.701,0-0.969l7.908-7.83
	c0.27-0.268,0.707-0.268,0.979,0l7.908,7.83c0.27,0.268,0.27,0.701,0,0.969c-0.271,0.268-0.709,0.268-0.978,0L10,6.75L2.582,13.891z
	" />
                                                            </svg>

                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-full px-4">
                                                <div x-show.transition.origin.top="isOpen()" class="absolute shadow top-100 bg-white z-40 w-full left-0 rounded max-h-select" x-on:click.away="close">
                                                    <div class="flex flex-col w-full overflow-y-auto h-64">
                                                        <template x-for="(option,index) in options" :key="option" class="overflow-auto">
                                                            <div class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-gray-100" @click="select(index,$event)">
                                                                <div class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative">
                                                                    <div class="w-full items-center flex justify-between">
                                                                        <div class="mx-2 leading-6" x-model="option" x-text="option.text"></div>
                                                                        <div x-show="option.selected">
                                                                            <svg class="svg-icon" viewBox="0 0 20 20">
                                                                                <path fill="none" d="M7.197,16.963H7.195c-0.204,0-0.399-0.083-0.544-0.227l-6.039-6.082c-0.3-0.302-0.297-0.788,0.003-1.087
							C0.919,9.266,1.404,9.269,1.702,9.57l5.495,5.536L18.221,4.083c0.301-0.301,0.787-0.301,1.087,0c0.301,0.3,0.301,0.787,0,1.087
							L7.741,16.738C7.596,16.882,7.401,16.963,7.197,16.963z"></path>
                                                                            </svg>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="relative z-0 mb-4 w-full group">
                                <label for="department_id"
                                       class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Select
                                    Department</label>
                                <select name="department_id" id="department_id_create"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled>Choose Department</option>
                                    @if(count($departments))
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div
                                class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                                <div>
                                    <button data-modal-toggle="createModal" type="button"
                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                        Cancel
                                    </button>
                                </div>
                                <div>
                                    <button
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        Create
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-6 p-4">
        {{ $employees->links() }}
    </div>

    <script type="text/javascript">
        function enableOrDisableDepartment(that) {
            let select = document.getElementById('role_id');
            let role = select.options[select.selectedIndex].text;
            console.log(role);
            if (role == "employee") {
                document.getElementById("department_id").disabled = false;
            } else {
                document.getElementById("department_id").disabled = true;
            }
        }
    </script>
    <script type="text/javascript">
        function data() {
            return {
                sortBy: "",
                sortAsc: false,
                sortByColumn($event) {
                    if (this.sortBy === $event.target.innerText) {
                        if (this.sortAsc) {
                            this.sortBy = "";
                            this.sortAsc = false;
                        } else {
                            this.sortAsc = !this.sortAsc;
                        }
                    } else {
                        this.sortBy = $event.target.innerText;
                    }

                    let rows = this.getTableRows()
                        .sort(
                            this.sortCallback(
                                Array.from($event.target.parentNode.children).indexOf(
                                    $event.target
                                )
                            )
                        )
                        .forEach((tr) => {
                            this.$refs.tbody.appendChild(tr);
                        });
                },
                getTableRows() {
                    return Array.from(this.$refs.tbody.querySelectorAll("tr"));
                },
                getCellValue(row, index) {
                    return row.children[index].innerText;
                },
                sortCallback(index) {
                    return (a, b) =>
                        ((row1, row2) => {
                            return row1 !== "" &&
                            row2 !== "" &&
                            !isNaN(row1) &&
                            !isNaN(row2)
                                ? row1 - row2
                                : row1.toString().localeCompare(row2);
                        })(
                            this.getCellValue(this.sortAsc ? a : b, index),
                            this.getCellValue(this.sortAsc ? b : a, index)
                        );
                }
            };
        }

    </script>

    <script>
        function dropdown() {
            return {
                options: [],
                selected: [],
                show: false,
                open() { this.show = true },
                close() { this.show = false },
                isOpen() { return this.show === true },
                select(index, event) {

                    if (!this.options[index].selected) {

                        this.options[index].selected = true;
                        this.options[index].element = event.target;
                        this.selected.push(index);

                    } else {
                        this.selected.splice(this.selected.lastIndexOf(index), 1);
                        this.options[index].selected = false
                    }
                },
                remove(index, option) {
                    this.options[option].selected = false;
                    this.selected.splice(index, 1);


                },
                loadOptions() {
                    const options = document.getElementById('role_id_create').options;
                    for (let i = 0; i < options.length; i++) {
                        this.options.push({
                            value: options[i].value,
                            text: options[i].innerText,
                            selected: options[i].getAttribute('selected') != null ? options[i].getAttribute('selected') : false
                        });
                    }


                },
                selectedValues(){
                    return this.selected.map((option)=>{
                        return this.options[option].value;
                    })
                }
            }
        }
    </script>

</x-sidebar>
