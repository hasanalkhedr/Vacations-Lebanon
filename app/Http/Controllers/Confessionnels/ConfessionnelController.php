<?php

namespace App\Http\Controllers\Confessionnels;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfessionnelRequests\StoreConfessionnelRequest;
use App\Http\Requests\ConfessionnelRequests\UpdateConfessionnelRequest;
use App\Http\Requests\HolidayRequests\StoreHolidayRequest;
use App\Http\Requests\HolidayRequests\UpdateHolidayRequest;
use App\Models\Confessionnel;
use App\Models\Holiday;
use Illuminate\Http\Request;

class ConfessionnelController extends Controller
{
    public function create()
    {
        return view('confessionnels.create');
    }

    public function store(StoreConfessionnelRequest $request)
    {
        $validated = $request->validated();
        $confessionnel = Confessionnel::create([
            'name' => $validated['name'],
            'date' => $validated['date'],
        ]);
        return redirect()->route('confessionnels.index');
    }



    public function index()
    {
        $confessionnels = Confessionnel::search(request(['search']))
            ->orderBy('date', 'desc')
            ->paginate(10);
        $helper = new Helper();
        $confessionnelDates = $helper->getConfessionnels();
        return view('confessionnels.index', [
            'confessionnels' => $confessionnels,
            'confessionnel_days' => $confessionnelDates
        ]);
    }

    public function show(Confessionnel $confessionnel)
    {
        return view('confessionnels.show', [
            'confessionnel' => $confessionnel,
        ]);
    }

    public function update(UpdateConfessionnelRequest $request, Confessionnel $confessionnel)
    {
        $validated = $request->validated();
        $confessionnel->update([
            'name' => $validated['name'],
            'date' => $validated['date'],
        ]);
        return redirect()->route('confessionnels.index');
    }

    public function destroy(Confessionnel $confessionnel)
    {
        $confessionnel->delete();
        return redirect()->route('confessionnel.index');
    }
}
