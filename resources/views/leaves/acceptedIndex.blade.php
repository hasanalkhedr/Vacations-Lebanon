<x-sidebar>
    @section('title', 'Accepted Leave Requests')
    @push('head')
        <script src="https://unpkg.com/flowbite@1.5.3/dist/datepicker.js"></script>
    @endpush
    <nav class="flex justify-between items-center p-2 text-black font-bold">
        <div class="text-lg">
            Accepted Leave Requests
        </div>
    </nav>
    <div class="rounded-lg p-4 overflow-x-auto relative shadow-md sm:rounded-lg">
        <table x-data="data()" class="rounded-lg border-collapse border border-slate-200 w-full text-sm text-left text-gray-500 dark:text-gray-400" x-data="leaveData">
            @unless($leaves->isEmpty())
                <thead class="text-s text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                        Employee
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                        From
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                        To
                    </th>
                    <th @click="sortByColumn" scope="col" class="cursor-pointer py-3 px-6">
                        Status
                    </th>
                </tr>
                </thead>
                <tbody x-ref="tbody">
                @foreach ($leaves as $leave)
                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="py-4 px-6 cursor-pointer" onclick="window.location.href = '{{ url(route('leaves.show', ['leave' => $leave->id])) }}'">
                            {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}
                        </td>
                        <td class="py-4 px-6 cursor-pointer" onclick="window.location.href = '{{ url(route('leaves.show', ['leave' => $leave->id])) }}'">
                            {{ $leave->from }}
                        </td>
                        <td class="py-4 px-6 cursor-pointer" onclick="window.location.href = '{{ url(route('leaves.show', ['leave' => $leave->id])) }}'">
                            {{ $leave->to }}
                        </td>
                        <td class="py-4 px-6 cursor-pointer" onclick="window.location.href = '{{ url(route('leaves.show', ['leave' => $leave->id])) }}'">
                            @if($leave->leave_status == 0)
                                Pending
                            @elseif($leave->leave_status == 1)
                                <div class="text-green-500">
                                    Accepted
                                </div>
                            @else
                                <div class="text-red-500">
                                    Rejected
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @else
                    <tr class="border-gray-300">
                        <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                            <p class="text-center">No Accepted Leave Requests Found</p>
                        </td>
                    </tr>
                @endunless
                </tbody>
        </table>

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

</x-sidebar>
