<x-sidebar>
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
            <li class="mr-2" role="presentation">
                <button id="holidaysButton"
                        class="inline-block p-4 border-b-2 rounded-t-lg {{$activeTab == "holidays" ? 'text-blue-600 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-500 border-blue-600 dark:border-blue-500' : 'dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300'}}"
                        id="holidays-tab" data-tabs-target="#holidays" type="button" role="tab" aria-controls="holidays" aria-selected="{{ $activeTab == "holidays" ? 'true' : 'false' }}">
                    {{__("Holidays")}}</button>
            </li>
            <li class="mr-2" role="presentation">
                <button id="confessionnelsButton"
                        class="inline-block p-4 border-b-2 rounded-t-lg {{$activeTab == "confessionnels" ? 'text-blue-600 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-500 border-blue-600 dark:border-blue-500' : 'dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300'}}"
                        id="confessionnels-tab" data-tabs-target="#confessionnels" type="button" role="tab" aria-controls="confessionnels" aria-selected="{{ $activeTab == "confessionnels" ? 'true' : 'false' }}">
                    {{__("Confessionnels")}}</button>
            </li>
        </ul>
    </div>
    <div id="myTabContent">
        <div class="{{ $activeTab == "holidays" ? '' : 'hidden' }}" id="holidays" role="tabpanel" aria-labelledby="holidays-tab">
            <div class="px-4 overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    @unless($holidays->isEmpty())
                        <thead class="text-s blue-color uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="cursor-pointer py-3 px-6">
                                {{ __('Name') }}
                            </th>
                            <th scope="col" class="cursor-pointer py-3 px-6">
                                {{ __('From') }}
                            </th>
                            <th scope="col" class="cursor-pointer py-3 px-6">
                                {{ __('To') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($holidays as $holiday)
                            <tr class="bg-white">
                                <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer"
                                    onclick="window.location.href = '{{ url(route('holidays.show', ['holiday' => $holiday->id])) }}'">
                                    {{ $holiday->name }}
                                </td>
                                <td class="py-4 px-6 border-b cursor-pointer"
                                    onclick="window.location.href = '{{ url(route('holidays.show', ['holiday' => $holiday->id])) }}'">
                                    {{ $holiday->from }}
                                </td>
                                <td class="py-4 px-6 border-b cursor-pointer"
                                    onclick="window.location.href = '{{ url(route('holidays.show', ['holiday' => $holiday->id])) }}'">
                                    {{ $holiday->to }}
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr class="border-gray-300">
                                <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                                    <p class="text-center">{{ __('No Holidays Found') }}</p>
                                </td>
                            </tr>
                        @endunless
                        </tbody>
                </table>
            </div>

            <div class="mt-6 p-4">
                {{ $holidays->appends(['confessionnels_page' => $confessionnels->currentPage(), 'active_tab' => 'holidays'])->links() }}
            </div>
        </div>
        <div class="{{ $activeTab == "confessionnels" ? '' : 'hidden' }}" id="confessionnels" role="tabpanel" aria-labelledby="confessionnels-tab">
            <div class="px-4 overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    @unless($confessionnels->isEmpty())
                        <thead class="text-s blue-color uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="cursor-pointer py-3 px-6">
                                {{__("Name")}}
                            </th>
                            <th scope="col" class="cursor-pointer py-3 px-6">
                                {{__("Date")}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($confessionnels as $confessionnel)
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="border-b py-4 px-6 font-bold text-gray-900 whitespace-nowrap cursor-pointer" onclick="window.location.href = '{{ url(route('confessionnels.show', ['confessionnel' => $confessionnel->id])) }}'">
                                    {{ $confessionnel->name }}
                                </td>
                                <td class="py-4 px-6 border-b cursor-pointer" onclick="window.location.href = '{{ url(route('confessionnels.show', ['confessionnel' => $confessionnel->id])) }}'">
                                    {{$confessionnel->date}}
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr class="border-gray-300">
                                <td colspan="4" class="px-4 py-8 border-t border-gray-300 text-lg">
                                    <p class="text-center">{{__("No Confessionnels Found")}}</p>
                                </td>
                            </tr>
                        @endunless
                        </tbody>
                </table>


            </div>

            <div class="mt-6 p-4">
                {{ $confessionnels->appends(['holidays_page' => $holidays->currentPage(), 'active_tab' => 'confessionnels'])->links() }}
            </div>
        </div>
    </div>
</x-sidebar>

<script>
    // $(document).ready(function () {
    //     const queryString = window.location.search;
    //     const urlParams = new URLSearchParams(queryString);
    //     const activeTab = urlParams.get('active_tab');
    //     const holidaysButton = document.getElementById('holidaysButton');
    //     const confessionnelsButton = document.getElementById('confessionnelsButton');
    //     if(activeTab === 'holidays') {
    //         holidaysButton.click()
    //     }
    //     if(activeTab === 'confessionnels') {
    //         confessionnelsButton.click()
    //     }
    // });
</script>


