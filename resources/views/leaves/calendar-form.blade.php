<x-sidebar>
    @section('title', __("Calendar Form"))
    <form method="POST" action="{{ route('leaves.generateCalendar') }}" enctype="multipart/form-data" class="m-2">
        @csrf
        <div>
            <label for="month" class="text-lg block mb-2 text-sm font-medium blue-color">
                {{__("Select a month")}}
            </label>
            <select name="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @foreach($months as $month)
                    @if($month[0] == \Carbon\Carbon::now()->month)
                        <option value="{{ $month[0] }}" selected>{{__($month[1]) }}</option>
                    @else
                        <option value="{{ $month[0] }}">{{__($month[1]) }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        @hasanyrole('human_resource|sg|head')
            <div>
                <label for="department_id" class="text-lg block mb-2 text-sm font-medium blue-color">{{__("Select Department")}}</label>
                <select name="department_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="all">All</option>
                    @if(count($departments))
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        @endhasanyrole
        <button class="mt-4 text-white hover:text-white border hover:bg-blue-400 focus:ring-2 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 blue-bg">
            {{__("Generate New Calendar")}}
        </button>
    </form>
</x-sidebar>
