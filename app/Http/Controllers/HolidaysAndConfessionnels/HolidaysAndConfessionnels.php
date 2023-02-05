<?php

namespace App\Http\Controllers\HolidaysAndConfessionnels;

use App\Http\Controllers\Controller;
use App\Models\Confessionnel;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidaysAndConfessionnels extends Controller
{
    public function index(Request $request) {

        $holidaysPage = $request->query('holidays_page', 1);
        $confessionnelsPage = $request->query('confessionnels_page', 1);
        $activeTab = $request->query('active_tab', 'holidays');

        $holidays = Holiday::paginate(10, ['*'], 'holidays_page');
        $confessionnels = Confessionnel::paginate(10, ['*'], 'confessionnels_page');

        $holidays->appends(['confessionnels_page' => $confessionnelsPage, 'active_tab' => $activeTab]);

        $confessionnels->setPageName('confessionnels_page');
        $confessionnels->appends(['holidays_page' => $holidaysPage, 'active_tab' => $activeTab]);

        return view('holidays-and-confessionnels.index', [
            'holidays' => $holidays,
            'confessionnels' => $confessionnels,
            'activeTab' => $activeTab
        ]);
    }
}
