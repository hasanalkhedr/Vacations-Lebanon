<x-sidebar>
    @section('title', __("Show Confessionnel"))
    <div class="m-4">
        <div class="mb-6">
            <a href="{{ url(route('confessionnels.index')) }}">
                <button type="button" class="inline-block px-6 py-2.5 text-white font-medium text-xs leading-tight uppercase rounded-full shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out blue-bg">
                    {{__("Back")}}</button>
            </a>
        </div>
        <div class="relative z-0 mb-6 w-full group">
            <input type="text" name="name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{ $confessionnel->name }}" />
            <label for="name" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{__("Name")}}</label>
        </div>
        <div class="relative z-0 mb-6 w-full group">
            <input type="text" name="date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" disabled value="{{ $confessionnel->date }}" />
            <label for="date" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{__("Date")}}</label>
        </div>

        @hasanyrole('human_resource|sg|head')
                <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center" data-modal-toggle="editModal">
                    {{__("Edit")}}</button>
                <button class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center" data-modal-toggle="deleteModal">
                    {{__("Delete")}}</button>
        @endhasanyrole
    </div>

    <div id="editModal" tabindex="-1" aria-hidden="true"
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div
                    class="flex justify-between items-center p-4 rounded-t border-b">
                    <div class="text-base font-bold mt-3 sm:mt-0 sm:ml-4 sm:text-left">
                        {{__("Edit Confessionnel")}}: {{ $confessionnel->name }}
                    </div>
                </div>
                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="editModal">
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
        <div class="p-6">
            <form method="POST"
                  action="{{ route('confessionnels.update', ['confessionnel' => $confessionnel->id]) }}">
                @csrf
                @method('PUT')
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="name"
                           class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                           value="{{$confessionnel->name}}" required/>
                    <label for="name"
                           class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{__("Name")}}</label>
                </div>
                <div class="relative z-0 w-full group flex flex-col">
                    <label for="date" class="mb-2 text-sm font-medium text-gray-900">Date</label>
                    <input type="text" name="date" id="date" placeholder="Please select date range" data-input value="{{ $confessionnel->from }}">
                    @error('date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div
                    class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                    <div>
                        <button data-modal-toggle="editModal"
                                type="button"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                            {{__("Cancel")}}
                        </button>
                    </div>
                    <div>
                        <button
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center"
                            data-modal-toggle="editModal">{{__("Edit")}}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="deleteModal" tabindex="-1" aria-hidden="true"
     class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div
                    class="flex justify-between items-center p-4 rounded-t border-b">
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
                        {{__("Delete Confessionnel")}}: {{ $confessionnel->name }}
                    </div>
                    <div>
                        <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                data-modal-toggle="deleteModal">
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
                    <div class="text-base leading-relaxed text-gray-500">
                        {{__("Are you sure you want to delete this confessionnel")}}? {{__("This action cannot be undone")}}.
                    </div>
                </div>
                <!-- Modal footer -->
                <div
                    class="flex justify-end items-center p-6 space-x-2 rounded-b border-t border-gray-200">
                    <div>
                        <button data-modal-toggle="deleteModal" type="button"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                            {{__("Cancel")}}
                        </button>
                    </div>
                    <div>
                        <form method="POST"
                              action="{{ route('confessionnels.destroy', ['confessionnel' => $confessionnel->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button data-modal-toggle="deleteModal"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                {{__("Delete")}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        let frompicker = $("#fromDate").flatpickr({
        dateFormat: "Y-m-d",
        locale: {
        firstDayOfWeek: 1
        },
        onClose: function(selectedDates, dateStr, instance) {
        topicker.set('minDate', dateStr);
        },
        });

        let topicker = $("#toDate").flatpickr({
        dateFormat: "Y-m-d",
        locale: {
        firstDayOfWeek: 1
        }
        });

    </script>

</x-sidebar>>

