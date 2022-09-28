<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com/"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"
        integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css"
        integrity="sha512-MQXduO8IQnJVq1qmySpN87QQkiR1bZHtorbJBD0tzy7/0U9+YIC93QWHeGTEoojMVHWWNkoCp8V6OzVSYrX0oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('head')
</head>

<body>
    <div class="lg:flex h-full min-h-screen">
        <div class="lg:pt-12 w-1/6 border-gray-200 px-2 py-2.5 dark:bg-gray-900 sm:pt-4 lg:bg-gray-200">
            <button data-collapse-toggle="aside-default" type="button"
                class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
            <aside class="hidden w-full md:block md:w-auto" id="aside-default">
                <ul>
                    @unless(auth()->user()->hasRole('employee') ||
                        auth()->user()->hasRole('supervisor'))
                        <li>
                            <a class="flex items-center px-4 py-2 text-gray-700 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                               href="{{ route('departments.index') }}">
                                <span class="mx-2 font-medium">Departments</span>
                            </a>

                        </li>
                    @endunless
                    @unless(auth()->user()->hasRole('employee'))
                        <li>
                            <a class="flex items-center mt-5 px-4 py-2 text-gray-700 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                               href="{{ route('employees.index') }}">
                                <span class="mx-2 font-medium">Users</span>
                            </a>
                        </li>
                    @endunless

                    <li>
                        <button type="button"
                            class="flex items-center px-4 py-2 mt-5 w-full text-base font-normal text-gray-700 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-leave-requests" data-collapse-toggle="dropdown-leave-requests">
                            <span class="flex-1 text-left whitespace-nowrap font-medium mx-2" sidebar-toggle-item>Leave
                                Requests</span>
                            <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-leave-requests" class="hidden py-2 space-y-2">
                            @unless(auth()->user()->hasRole('employee'))
                                <li>
                                    <a href="{{ url(route('leaves.index')) }}"
                                        class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Incoming
                                        Leave Requests</a>
                                </li>
                            @endunless
                            <li>
                                <a href="{{ url(route('leaves.submitted')) }}"
                                    class="usersTitle flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Outgoing
                                    Leave Requests</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <button type="button"
                            class="flex items-center px-4 py-2 mt-5 w-full text-base font-normal text-gray-700 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-overtime-requests"
                            data-collapse-toggle="dropdown-overtime-requests">
                            <span class="flex-1 text-left whitespace-nowrap font-medium mx-2"
                                sidebar-toggle-item>Overtime Requests</span>
                            <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-overtime-requests" class="hidden py-2 space-y-2">
                            @unless(auth()->user()->hasRole('employee'))
                                <li>
                                    <a href="{{ url(route('overtimes.index')) }}"
                                        class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Incoming
                                        Overtime Requests</a>
                                </li>
                            @endunless
                            <li>
                                <a href="{{ url(route('overtimes.submitted')) }}"
                                    class="usersTitle flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Outgoing
                                    Overtime Requests</a>
                            </li>
                        </ul>
                    </li>
                    @if (auth()->user()->hasRole('human_resource') || auth()->user()->hasRole('sg'))
                        <li>
                            <a class="flex items-center mt-5 px-4 py-2 text-gray-700 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                               href="{{ route('holidays.index') }}">
                                <span class="mx-2 font-medium">Holidays</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->hasRole('supervisor') || auth()->user()->hasRole('human_resource') || auth()->user()->hasRole('sg'))
                        <li>
                            <a class="flex items-center mt-5 px-4 py-2 text-gray-700 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                href="{{ route('leaves.getCalendarForm') }}">
                                <span class="mx-2 font-medium">Calendar</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </aside>
        </div>

        <div class="lg:pt-8 w-full h-full overflow-y-auto sm:pt-0 lg:mx-4 sm:mx-0">
            <nav class="w-full bg-white border-b-2 border-indigo-600 flex justify-between">
                <div class="flex flex-col py-2">
                    <div class="px-2 text-xl font-bold text-black">
                        {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                    </div>
                    <div class="px-2 text-md italic text-black">
                        @if(auth()->user()->getRoleNames()->count() == 1)
                            {{ auth()->user()->roles()->first()->display_name }}
                        @else
                            @foreach(auth()->user()->getRoleNames() as $role_name)
                                @if(auth()->user()->getRoleNames()->last() == $role_name)
                                    {{ \Spatie\Permission\Models\Role::findByName($role_name)->display_name }}
                                @else
                                    {{ \Spatie\Permission\Models\Role::findByName($role_name)->display_name }} |
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="px-6 py-3 text-xl font-bold text-black">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                </div>
            </nav>
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
</body>

</html>
