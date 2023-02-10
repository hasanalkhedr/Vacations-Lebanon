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
            @include('holidays-and-confessionnels.includes.holidays-tab')
        </div>
        <div class="{{ $activeTab == "confessionnels" ? '' : 'hidden' }}" id="confessionnels" role="tabpanel" aria-labelledby="confessionnels-tab">
            @include('holidays-and-confessionnels.includes.confessionnels-tab')
        </div>
    </div>
</x-sidebar>

