<x-sidebar>
    @section('title', 'Create Overtime Submission')
    <div class="relative w-full h-full md:h-auto">
        <div class="p-6">
            <form method="POST" action="{{ route('overtimes.store') }}">
                @csrf
                <table class="table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="p-2 text-s text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="py-3">Date</th>
                        <th class="py-3">From</th>
                        <th class="py-3">To</th>
                        <th class="py-3">Hours</th>
                        <th class="py-3">Objective</th>
                        <th class="py-3">Select</th>
                    </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>
                <div class="mx-6 flex justify-between">
                    <button type="button" onclick="addOvertime();" class="mt-4 text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                        Add Overtime
                    </button>
                    <button class="mt-4 text-white bg-green-700 border border-gray-300 focus:outline-none hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-800 dark:focus:ring-green-800">Submit</button>
                </div>
            </form>
        </div>

    </div>

    <script>
        let overtimes = 0;
        let multiplyHours = false;
        let MULTIPLIER = 1.5;
        function addOvertime() {
            overtimes++;

            let html = "<tr class='bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'>";
            html += "<td class='py-4 border-b'><input type='text' class='date border-none' placeholder='Please select Date' name='date[]' data-input></td>";
            html += "<td class='py-4 border-b'><input type='time' class='from border-none' id='overtimeFrom' name='from[]'></td>";
            html += "<td class='py-4 border-b'><input type='time' class='to border-none' name='to[]'></td>";
            html += "<td class='py-4 border-b'><input type='text' class='focus:ring-0 border-none' name='hours[]' readonly></td>";
            html += "<td class='py-4 border-b'><textarea type='text' name='objective[]'></textarea></td>";
            html += "<td class='py-4 border-b text-red-500'><button type='button' onclick='deleteRow(this);'>Delete</button></td>"
            html += "</tr>";

            var row = document.getElementById("tbody").insertRow();
            row.innerHTML = html;
            let frompicker = $(".date").flatpickr({
                dateFormat: "Y-m-d",
                locale: {
                    firstDayOfWeek: 1
                },
            });
        }

        function deleteRow(button) {
            overtimes--
            button.parentElement.parentElement.remove();
            // first parentElement will be td and second will be tr.
        }

        $('.table').on('change', '.date', function () {
            multiplyHours = false;
            let $row = $(this).closest("tr");
            let date = $row.find("input[name^='date']");
            let day = new Date(date.val())
            if(day.getDay() == 0 || {!! json_encode($holiday_dates) !!}.includes(string_day))
                multiplyHours = true;
        })

        $('.table').on('change', '.from', function () {
            let $row = $(this).closest("tr");
            let from = $row.find("input[name^='from']");
            let to = $row.find("input[name^='to']");
            if(to.val() != '') {
                from = from.val().split(':');
                to = to.val().split(':');
                let startDate = new Date(0, 0, 0, from[0], from[1], 0);
                let endDate = new Date(0, 0, 0, to[0], to[1], 0);
                let diff = endDate.getTime() - startDate.getTime();
                let hours = Math.floor(diff / 1000 / 60 / 60);
                diff -= hours * 1000 * 60 * 60;
                let minutes = Math.floor(diff / 1000 / 60);

                // If using time pickers with 24 hours format, add the below line get exact hours
                if (hours < 0)
                    hours = hours + 24;
                if(multiplyHours) {
                    let total_minutes = Math.ceil((hours*60 + minutes) * MULTIPLIER);
                    hours = Math.floor(total_minutes/60);
                    minutes = total_minutes % 60;
                }
                let val = (hours <= 9 ? "0" : "") + hours + ":" + (minutes <= 9 ? "0" : "") + minutes;
                $row.find("input[name^='hours']").val(val);
            }
        })

        $('.table').on('change', '.to', function () {
            let $row = $(this).closest("tr");
            let date = $row.find("input[name^='date']");
            let from = $row.find("input[name^='from']");
            let to = $row.find("input[name^='to']");
            if(from.val() != '') {
                from = from.val().split(':');
                to = to.val().split(':');
                let startDate = new Date(0, 0, 0, from[0], from[1], 0);
                let endDate = new Date(0, 0, 0, to[0], to[1], 0);
                let diff = endDate.getTime() - startDate.getTime();
                let hours = Math.floor(diff / 1000 / 60 / 60);
                diff -= hours * 1000 * 60 * 60;
                let minutes = Math.floor(diff / 1000 / 60);

                // If using time pickers with 24 hours format, add the below line get exact hours
                if (hours < 0)
                    hours = hours + 24;
                if(multiplyHours) {
                    let total_minutes = Math.ceil((hours*60 + minutes) * MULTIPLIER);
                    hours = Math.floor(total_minutes/60);
                    minutes = total_minutes % 60;
                }
                let val = (hours <= 9 ? "0" : "") + hours + ":" + (minutes <= 9 ? "0" : "") + minutes;

                $row.find("input[name^='hours']").val(val);
            }
        })
    </script>


</x-sidebar>
