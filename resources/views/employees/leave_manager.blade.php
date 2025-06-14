<x-sidebar>
    @section('title', __('Leave Balance Configuration'))

    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        @if (auth()->user()->hasRole('human_resource'))
            <div class="flex text-center justify-center">
                <div class="border rounded-lg border-gray-300 w-3/4 justify-center py-4">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-s text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="cursor-pointer py-3 px-6 blue-color">
                                    {{ __('Date to add new year leaves balance') }}
                                </th>
                                <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer">
                                    <div class="cursor-pointer">
                                        @php
                                            \Carbon\Carbon::setLocale('fr');
                                            $date = \Carbon\Carbon::create(
                                                null,
                                                \App\Models\LeaveConfig::find('start_month')->value,
                                                \App\Models\LeaveConfig::find('start_day')->value,
                                            );
                                        @endphp

                                        {{ $date->isoFormat('D MMMM') }}
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-right border-b">
                                    <button class="font-medium hover:underline text-red-600" type="button"
                                        data-modal-toggle="changeStartDateModal">
                                        {{ __('Change date') }}
                                    </button>
                                </td>
                                <div id="changeStartDateModal" tabindex="-1" aria-hidden="true"
                                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow">
                                            <!-- Modal header -->
                                            <div class="flex justify-between items-center p-4 rounded-t border-b">
                                                <div
                                                    class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                                                    {{ __('Date to add new year leaves balance') }}
                                                </div>
                                                <div>
                                                    <button type="button"
                                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                        data-modal-toggle="changeStartDateModal">
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
                                            <div class="p-6 overflow-y-auto" style="max-height: 500px">
                                                <form method="POST" action="{{ route('leaves.changeStartDate') }}"
                                                    id="changeStartDateForm">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="grid md:grid-cols-2 md:gap-6">
                                                        <div class="relative z-0 mb-6 w-full group">
                                                            <label for="start_month"
                                                                class="block mb-2 text-sm font-medium text-gray-900">
                                                                {{ __('Month') }}
                                                            </label>
                                                            <select name="start_month" id="start_month"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                                <option value="">{{ __('Select a month') }}
                                                                </option>
                                                                @php
                                                                    $date = \Carbon\Carbon::create()->locale('fr');
                                                                @endphp
                                                                @for ($i = 1; $i <= 12; $i++)
                                                                    <option value="{{ $i }}">
                                                                        {{ $i }} -
                                                                        {{ $date->month($i)->monthName }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="relative z-0 mb-6 w-full group">
                                                            <label for="start_day"
                                                                class="block mb-2 text-sm font-medium text-gray-900">
                                                                {{ __('Day') }}
                                                            </label>
                                                            <select name="start_day" id="start_day"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                                <option value="">{{ __('Select a day') }}
                                                                </option>
                                                                @for ($i = 1; $i <= 31; $i++)
                                                                    <option value="{{ $i }}">
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                                                        <div>
                                                            <button data-modal-toggle="changeStartDateModal"
                                                                type="button"
                                                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                                {{ __('Cancel') }}
                                                            </button>
                                                        </div>
                                                        <div>
                                                            <button
                                                                class="text-white hover:bg-blue-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center blue-bg"
                                                                data-modal-toggle="changeStartDateModal">{{ __('Change date') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                            <tr>
                                <th scope="col" class="cursor-pointer py-3 px-6 blue-color">
                                    {{ __('Date to reset previous year leaves balance') }}
                                </th>
                                <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer">
                                    <div class="cursor-pointer">
                                        @php
                                            \Carbon\Carbon::setLocale('fr');
                                            $date = \Carbon\Carbon::create(
                                                null,
                                                \App\Models\LeaveConfig::find('expire_month')->value,
                                                \App\Models\LeaveConfig::find('expire_day')->value,
                                            );
                                        @endphp

                                        {{ $date->isoFormat('D MMMM') }}
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-right border-b">
                                    <button class="font-medium hover:underline text-red-600" type="button"
                                        data-modal-toggle="changeExpireDateModal">
                                        {{ __('Change date') }}
                                    </button>
                                </td>
                                <div id="changeExpireDateModal" tabindex="-1" aria-hidden="true"
                                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
                                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow">
                                            <!-- Modal header -->
                                            <div class="flex justify-between items-center p-4 rounded-t border-b">
                                                <div
                                                    class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left blue-color">
                                                    {{ __('Date to reset previous year leaves balance') }}
                                                </div>
                                                <div>
                                                    <button type="button"
                                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                        data-modal-toggle="changeExpireDateModal">
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
                                            <div class="p-6 overflow-y-auto" style="max-height: 500px">
                                                <form method="POST" action="{{ route('leaves.changeExpireDate') }}"
                                                    id="changeExpireDateForm">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="grid md:grid-cols-2 md:gap-6">
                                                        <div class="relative z-0 mb-6 w-full group">
                                                            <label for="expire_month"
                                                                class="block mb-2 text-sm font-medium text-gray-900">
                                                                {{ __('Month') }}
                                                            </label>
                                                            <select name="expire_month" id="expire_month"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                                <option value="">{{ __('Select a month') }}
                                                                </option>
                                                                @php
                                                                    $date = \Carbon\Carbon::create()->locale('fr');
                                                                @endphp
                                                                @for ($i = 1; $i <= 12; $i++)
                                                                    <option value="{{ $i }}">
                                                                        {{ $i }} -
                                                                        {{ $date->month($i)->monthName }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="relative z-0 mb-6 w-full group">
                                                            <label for="expire_day"
                                                                class="block mb-2 text-sm font-medium text-gray-900">
                                                                {{ __('Day') }}
                                                            </label>
                                                            <select name="expire_day" id="expire_day"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                                <option value="">{{ __('Select a day') }}
                                                                </option>
                                                                @for ($i = 1; $i <= 31; $i++)
                                                                    <option value="{{ $i }}">
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                                                        <div>
                                                            <button data-modal-toggle="changeExpireDateModal"
                                                                type="button"
                                                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                                {{ __('Cancel') }}
                                                            </button>
                                                        </div>
                                                        <div>
                                                            <button
                                                                class="text-white hover:bg-blue-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center blue-bg"
                                                                data-modal-toggle="changeExpireDateModal">{{ __('Change date') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <div class="flex py-4 px-4 pt-8 text-center w-full justify-around">
                {{-- <button type="button"
                    onclick="submitPost('{{ route('employees.add-balance') }}', 'Are you sure you want to add new year leaves balance?')"
                    class="text-white hover:bg-blue-400 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-lg w-full sm:w-auto px-5 py-2.5 text-center blue-bg">
                    {{ __('Add new year leaves balance') }}
                </button> --}}

                <button type="button"
                    onclick="submitPost('{{ route('employees.delete-balance') }}', 'Are you sure you want to delete the previous year leaves balance?')"
                    class="text-white hover:bg-red-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-bold rounded-lg text-lg w-full sm:w-auto px-5 py-2.5 text-center bg-red-500">
                    {{ __('Delete previous year leave balance') }}
                </button>

                <script>
                    function submitPost(url, message) {
                        if (!confirm(message)) {
                            return;
                        }

                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;

                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = document.querySelector('meta[name="csrf-token"]').content;

                        form.appendChild(csrf);
                        document.body.appendChild(form);
                        form.submit();
                    }
                </script>
            </div>

            @if ($message)
                <div x-data="{ showMessageee: true }" x-show="showMessageee" class="flex justify-center">
                    <div class="flex items-center justify-between max-w-xl p-4 bg-white border rounded-md shadow-sm">
                        <div class="flex items-center flex-col">
                            <div class="ml-3 text-sm font-bold text-red-600">{{ $message }}</div>
                        </div>
                        <span @click="showMessageee = false" class="inline-flex items-center cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </div>
                </div>
            @endif
            @if ($employees)
                <table class="w-full text-sm text-left text-gray-500">
                    @unless ($employees->isEmpty())
                        <thead class="text-s text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="cursor-pointer py-3 px-6 blue-color">
                                    {{ __('Name') }}
                                </th>
                                <th scope="col" class="cursor-pointer py-3 px-6 blue-color">
                                    {{ __('Department') }}
                                </th>
                                <th scope="col" class="cursor-pointer py-3 px-6 blue-color">
                                    {{ __('Role') }}
                                </th>
                                <th scope="col" class="cursor-pointer py-3 px-6 blue-color">
                                    {{ __('Previous Year') }}
                                </th>
                                <th scope="col" class="cursor-pointer py-3 px-6 blue-color">
                                    {{ __('Current Year') }}
                                </th>
                                <th scope="col" class="cursor-pointer py-3 px-6 blue-color">
                                    {{ __('Total leaves balance') }}
                                </th>

                            </tr>
                        </thead>
                        <tbody x-ref="tbody">
                            @foreach ($employees as $employee)
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer"
                                        onclick="window.location.href = '{{ url(route('employees.show', ['employee' => $employee->id])) }}'">
                                        <div class="cursor-pointer">
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </div>
                                    </td>
                                    @if ($employee->department == null)
                                        <td class="py-4 px-6 border-b">
                                            <div class="font-bold">
                                                -
                                            </div>
                                        </td>
                                    @else
                                        <td class="py-4 px-6 border-b cursor-pointer">
                                            <div class="cursor-pointer">
                                                {{ $employee->department->name }}
                                            </div>
                                        </td>
                                    @endif
                                    <td class="py-4 px-6 border-b">
                                        {{ implode(' | ', $employee->getRoleNamesCustom()) }}
                                    </td>
                                    <td class="py-4 px-6 border-b cursor-pointer text-center">
                                        <div class="cursor-pointer">
                                            {{ $employee->prev_leaves }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 border-b cursor-pointer text-center">
                                        <div class="cursor-pointer">
                                            {{ $employee->nb_of_days - $employee->prev_leaves }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 border-b cursor-pointer text-center">
                                        <div class="cursor-pointer">
                                            {{ $employee->nb_of_days }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="border-gray-300">
                                <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                                    <p class="text-center">{{ __('No Employees Found') }}</p>
                                </td>
                            </tr>
                        @endunless
                    </tbody>
                </table>
            @endif
        @endif

    </div>

</x-sidebar>
