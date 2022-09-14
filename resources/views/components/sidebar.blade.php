<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
{{--    <title>@yield('title') </title>--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
          integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />
    <script
        src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com/"></script>
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
</head>

<body>
<div class="lg:flex ">
    <div class="pt-12 flex flex-col w-full px-4 overflow-y-auto lg:h-screen lg:w-64 bg-gray-100 rounded ">
        <div class="flex flex-col justify-between">
            <aside class="fixed">
                <ul>
                    @unless(auth()->user()->roles()->first()->name == "employee" || auth()->user()->roles()->first()->name == "supervisor")
                    <li>
                        <a class="flex items-center px-4 py-2 text-gray-700 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" href="{{ route('departments.index') }}">
                            <span class="mx-2 font-medium">Departments</span>
                        </a>
                    </li>
                    @endunless
                    <li>
                        <button type="button" class="flex items-center px-4 py-2 mt-5 w-full text-base font-normal text-gray-700 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                            <span class="flex-1 text-left whitespace-nowrap font-medium mx-2" sidebar-toggle-item>Users</span>
                            <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        <ul id="dropdown-example" class="hidden py-2 space-y-2">
                            <li>
                                <a href="{{ url(route('employees.show' , ['employee' => auth()->user()])) }}" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Show Profile</a>
                            </li>
                            @unless(auth()->user()->roles()->first()->name == "employee")
                            <li>
                                <a href="{{ url(route('employees.index')) }}" class="usersTitle flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Employees</a>
                            </li>
                            @endunless
                        </ul>
                    </li>
                    <li>
                        <a class="flex items-center px-4 py-2 mt-4 text-gray-700 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" href="{{ route('leaves.index') }}">
                            <span class="mx-2 font-medium">Leave Requests</span>
                        </a>
                    </li>
                </ul>

            </aside>

        </div>
    </div>
    <div class="pt-12 w-full h-full overflow-y-auto">
        <nav class="w-full bg-white border-b-2 border-indigo-600 flex justify-between">
            <div class="px-6 py-3 text-xl font-bold text-blue-800">
                {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
            </div>
            <div class="px-6 py-3 text-xl font-bold text-black">
                <form method="POST" action="{{ route('employees.logout') }}">
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
