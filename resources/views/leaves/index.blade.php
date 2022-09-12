<x-sidebar>
    @section('title', 'Leave Requests')
    <nav class="p-2 text-lg text-black font-bold">
        Leave Requests
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
                    Department
                </div>
            </th>
            <th class="py-4 border-t border-b border-gray-300 text-lg text-center">
                <div class="text-blue-400 px-6 py-2">
                    Reports To
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
        @unless($leaves->isEmpty())
            @foreach ($leaves as $leave)
                <tr>
                    <td class="w-1/5 px-4 py-4 border-t border-b border-gray-300 text-lg text-center" onclick="window.location.href='{{ route('leaves.show', ['leave' => $leave]) }}'">
                        <div class="text-blue-400 px-6 py-2 items-center">
                            {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}
                        </div>
                    </td>
                    <td class="w-1/5 px-4 py-4 border-t border-b border-gray-300 text-lg text-center" onclick="window.location.href='{{ route('leaves.show', ['leave' => $leave]) }}'">
                        <div class="text-blue-400 px-6 py-2">
                            {{$leave->employee->department->name}}
                        </div>
                    </td>
                    <td class="w-1/5 px-4 py-4 border-t border-b border-gray-300 text-lg text-center" onclick="window.location.href='{{ route('leaves.show', ['leave' => $leave]) }}'">
                        @if($leave->employee->id == $leave->employee->department->manager->id)
                            <div class="text-blue-400 px-6 py-2">
                                -
                            </div>
                        @else
                            <div class="text-blue-400 px-6 py-2">
                            {{$leave->employee->department->manager->first_name}} {{$leave->employee->department->manager->last_name}}
                            </div>
                        @endif
                    </td>
                    <td class="w-1/5 px-4 py-4 border-t border-b border-gray-300 text-lg text-center">
                        <button class="text-green-500 acceptModal">Accept</button>
                    </td>
                    <td class="w-1/5 px-4 py-4 border-t border-b border-gray-300 text-lg text-center">
                        <button class="text-red-500 rejectModal">Reject</button>
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
                                <form method="POST" action="{{ route('leaves.accept', ['leave' => $leave]) }}">
                                    @csrf
                                    <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Accept
                                    </button>
                                </form>

                                <button type="button" class="closeModalAccept mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fixed z-10 inset-0 invisible overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="interestModalReject">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form method="POST" action="{{ route('leaves.reject', ['leave' => $leave]) }}">
                                @csrf
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg @click="toggleModal" class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                Reject Leave Request
                                            </h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">
                                                    Are you sure you want to reject the leave request? This action cannot be undone.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-center items-center ">
                                        <div class="h-80 px-7 w-[700px] rounded-[12px] bg-white p-4">
                                            <p class="text-xl font-semibold text-blue-900 cursor-pointer transition-all hover:text-black">Add Rejection Reason</p>
                                            <textarea name="cancellation_reason" class="h-40 px-3 text-sm py-1 mt-5 outline-none border-pink-300 w-full resize-none border rounded-lg placeholder:text-sm" placeholder="Add your reason here"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">

                                        <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Reject
                                        </button>


                                    <button type="button" class="closeModalReject mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
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
                    <p class="text-center">No Leave Requests Found</p>
                </td>
            </tr>
        @endunless
        </tbody>
    </table>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.acceptModal').on('click', function(e){
                $('#interestModalAccept').removeClass('invisible');
            });
            $('.closeModalAccept').on('click', function(e){
                $('#interestModalAccept').addClass('invisible');
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.rejectModal').on('click', function(e){
                $('#interestModalReject').removeClass('invisible');
            });
            $('.closeModalReject').on('click', function(e){
                $('#interestModalReject').addClass('invisible');
            });
        });
    </script>

</x-sidebar>
