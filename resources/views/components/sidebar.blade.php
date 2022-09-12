<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
          integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />
    <script
        src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com/"></script>
</head>

<body>
<nav class="fixed z-30 w-full bg-white border-b-2 border-indigo-600 flex justify-between">
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
<div class="pt-12 lg:flex">
    <div class="flex flex-col w-full px-4 py-8 overflow-y-auto border-b lg:border-r lg:h-screen lg:w-64">
        <div class="flex flex-col justify-between mt-6">
            <aside class="fixed">
                <ul>
                    <li>
                        <a class="departmentsTitle flex items-center px-4 py-2 text-gray-700 rounded-md hover:bg-gray-200" href="{{ route('departments.index') }}">
                            <span class="mx-4 font-medium">Departments</span>
                        </a>
                    </li>

                    <li>
                        <a class="usersTitle flex items-center px-4 py-2 mt-5 text-gray-700 rounded-md hover:bg-gray-200" href="{{ route('employees.index') }}">
                            <span class="mx-4 font-medium">Users</span>
                        </a>
                    </li>

                    <li>
                        <a class="leaveRequestsTitle flex items-center px-4 py-2 mt-5 text-gray-700 rounded-md" href="{{ route('leaves.index') }}">
                            <span class="mx-4 font-medium">Leave Requests</span>
                        </a>
                    </li>
                </ul>

            </aside>

        </div>
    </div>
    <div class="w-full h-full overflow-y-auto">
        <div>
            {{ $slot }}
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('.departmentsTitle').on('click', function(e){
            $('.departmentsTitle').addClass('bg-gray-100').removeClass('hover:bg-gray-200');
            $('.usersTitle').removeClass('bg-gray-100').addClass('hover:bg-gray-200');
            $('.leaveRequestsTitle').removeClass('bg-gray-100').addClass('hover:bg-gray-200');
        });
        $('.usersTitle').on('click', function(e){
            $('.usersTitle').addClass('bg-gray-100').removeClass('hover:bg-gray-200');
            $('.departmentsTitle').removeClass('bg-gray-100').addClass('hover:bg-gray-200');
            $('.leaveRequestsTitle').removeClass('bg-gray-100').addClass('hover:bg-gray-200');
        });
        $('.leaveRequestsTitle').on('click', function(e){
            $('.leaveRequestsTitle').addClass('bg-gray-100').removeClass('hover:bg-gray-200');
            $('.departmentsTitle').removeClass('bg-gray-100').addClass('hover:bg-gray-200');
            $('.usersTitle').removeClass('bg-gray-100').addClass('hover:bg-gray-200');
        });
    });
</script>
</body>

</html>
