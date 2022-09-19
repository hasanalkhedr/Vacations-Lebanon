<x-sidebar>
    @section('title', 'Leave Requests')
    @push('head')
        <script src="https://unpkg.com/flowbite@1.5.3/dist/datepicker.js"></script>
    @endpush
    <nav class="flex justify-between items-center p-2 text-black font-bold">
        <div class="text-lg">
            Leave Requests
        </div>
        <div>
            <button class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-full"
                    data-modal-toggle="createModal">
                Submit Leave Request
            </button>
        </div>
    </nav>

    @include('partials.searches._search-leaves')
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table x-data="data()" class="w-full text-sm text-left text-gray-500 dark:text-gray-400" x-data="leaveData">
            @unless($leaves->isEmpty())
                <thead class="text-s text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                        Name
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                        Department
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                        Reports To
                    </th>
                    <th scope="col" class="py-3 px-6">
                        <span class="sr-only">Edit</span>
                    </th>
                    <th scope="col" class="py-3 px-6">
                        <span class="sr-only">Delete</span>
                    </th>
                </tr>
                </thead>
                <tbody x-ref="tbody">
                @foreach ($leaves as $leave)
                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}
                        </td>
                        <td class="py-4 px-6 border-b">
                            @if($leave->employee->department)
                                {{$leave->employee->department->name}}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-4 px-6 border-b">
                            @if($leave->employee->department)
                                @if($leave->employee->id == $leave->employee->department->manager->id)
                                    -
                                @else
                                    {{$leave->employee->department->manager->first_name}} {{$leave->employee->department->manager->last_name}}
                                @endif
                            @else
                                -
                            @endif

                        </td>
                        <td class="py-4 px-6 text-right border-b">
                            <button class="font-medium text-green-600 dark:text-green-500 hover:underline" type="button"
                                    data-modal-toggle="acceptModal-{{$leave->id}}">
                                Accept
                            </button>
                        </td>
                        <td class="py-4 px-6 text-right border-b">
                            <button class="font-medium text-red-600 dark:text-red-500 hover:underline" type="button"
                                    data-modal-toggle="rejectModal-{{$leave->id}}">
                                Reject
                            </button>
                        </td>

                        <div id="rejectModal-{{$leave->id}}" tabindex="-1" aria-hidden="true"
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
                                            Reject Leave Request from: {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}
                                        </div>
                                        <div>
                                            <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                    data-modal-toggle="rejectModal-{{$leave->id}}">
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
                                    <form method="POST"
                                          action="{{ route('leaves.reject', ['leave' => $leave->id]) }}">
                                        @csrf
                                        <div class="p-6 space-y-6">
                                            <div class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                                Are you sure you want to reject this leave request? This action cannot be undone.
                                            </div>
                                            <div class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                                <label for="cancellation_reason" class="text-lg text-gray-600">Rejection Reason</label>
                                                <textarea class="bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white rounded" name="cancellation_reason" ></textarea>
                                            </div>
                                        </div>
                                        <!-- Modal footer -->
                                        <div
                                            class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                                            <div>
                                                <button data-modal-toggle="rejectModal-{{$leave->id}}" type="button"
                                                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                    Cancel
                                                </button>
                                            </div>
                                            <div>
                                                <button data-modal-toggle="rejectModal-{{$leave->id}}"
                                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                                    Reject
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div id="acceptModal-{{$leave->id}}" tabindex="-1" aria-hidden="true"
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
                                            Accept Leave Request from: {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}
                                        </div>
                                        <div>
                                            <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                    data-modal-toggle="acceptModal-{{$leave->id}}">
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
                                            Are you sure you want to accept this leave request? This action cannot be undone.
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div
                                        class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                                        <div>
                                            <button data-modal-toggle="acceptModal-{{$leave->id}}" type="button"
                                                    class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                Cancel
                                            </button>
                                        </div>
                                        <div>
                                            <form method="POST"
                                                  action="{{ route('leaves.accept', ['leave' => $leave->id]) }}">
                                                @csrf
                                                <button data-modal-toggle="acceptModal-{{$leave->id}}"
                                                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                    Accept
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
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

        <div id="createModal" tabindex="-1" aria-hidden="true"
             class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
            <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex justify-between items-center p-4 rounded-t border-b dark:border-gray-600">
                        <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                            Submit Leave Request
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
                        <form method="POST" action="{{ route('leaves.store') }}">
                            @csrf
                            <div class="relative z-0 mb-6 w-full group">
                                <label for="leave_duration_id" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Leave Duration</label>
                                <select name="leave_duration_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled>Choose Leave Duration</option>
                                    @if(count($leave_durations))
                                        @foreach ($leave_durations as $leave_duration)
                                            <option value="{{ $leave_duration->id }}">{{ $leave_duration->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="w-full">
{{--                                <div date-rangepicker class="flex items-center">--}}
{{--                                    <div class="relative">--}}
{{--                                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">--}}
{{--                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>--}}
{{--                                        </div>--}}
{{--                                        <input name="from" id="from" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date start">--}}
{{--                                    </div>--}}
{{--                                    <span class="mx-4 text-gray-500">to</span>--}}
{{--                                    <div class="relative">--}}
{{--                                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">--}}
{{--                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>--}}
{{--                                        </div>--}}
{{--                                        <input name="to" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date end">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <input type="text" id="rangeDate" placeholder="Please select Date Range" data-input>
                            </div>
                            <div class="relative z-0 mt-2 mb-6 w-full group">
                                <p>Travelling</p>
                                <div class="flex flex-row">
                                    <input type="radio" name="travelling" value=1>
                                    <label for="html" class="mx-2">Yes</label><br>
                                    <input type="radio" name="travelling" value=0>
                                    <label for="css" class="mx-2">No</label>
                                </div>
                                @error('travelling')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative z-0 mb-6 w-full group">
                                <label for="leave_type_id" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Select
                                    Leave Type</label>
                                <select name="leave_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled>Choose Leave Type</option>
                                    @if(count($leave_types))
                                        @foreach ($leave_types as $leave_type)
                                            <option value="{{ $leave_type->id }}">{{ $leave_type->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="relative z-0 mb-6 w-full group">
                                <input type="file" name="attachment_path"
                                       class="block pt-2.5 px-0 w-full text-sm text-gray-900 bg-transparent appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                                <label for="attachment_path"
                                       class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
                                @error('attachment_path')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative z-0 mb-6 w-full group">
                                <label for="substitute_employee_id" class="mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Select
                                    Substitute</label>
                                <select name="substitute_employee_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled>Choose Substitute Employee</option>
                                    <option value="">No Replacement</option>
                                    @if(count($substitutes))
                                        @foreach ($substitutes as $substitute)
                                            <option value="{{ $substitute->id }}">{{ $substitute->first_name }} {{ $substitute->last_name }}</option>
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
        {{ $leaves->links() }}
    </div>


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

    <script type="text/javascript">
        $('.fromInput').change(function(){
            let fromDate = $('.fromInput').val();
            let toDate = $('.toInput').val();
            if(fromDate>toDate) {
                $(".toInput").attr("min", fromDate);
                $(".toInput").val(fromDate);
                toDate = $('.toInput').val();
            }
        });
    </script>

    <script type="text/javascript">
        $("#rangeDate").flatpickr({
            mode: 'range',
            minDate: "today",
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    return (date.getDay() === 0 || date.getDay() === 6);
                }
            ],
            locale: {
                firstDayOfWeek: 1
            }
        });
    </script>
</x-sidebar>
