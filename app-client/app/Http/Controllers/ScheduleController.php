<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Unit;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $unit = $request->user()->unit;

        $schedules = Schedule::with(['customer', 'user'])
            ->where('unit_id', $unit->id)
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'title' => $schedule->customer->name,
                    'start' => $schedule->start_time,
                    'end' => $schedule->end_time,
                    'status' => $schedule->status,
                    'service_type' => $schedule->service_type,
                    'notes' => $schedule->notes,
                    'customer' => [
                        'id' => $schedule->customer->id,
                        'name' => $schedule->customer->name,
                    ],
                    'user' => [
                        'id' => $schedule->user->id,
                        'name' => $schedule->user->name,
                    ],
                ];
            });

        $customers = Customer::where('unit_id', $unit->id)->get();

        Log::info('Schedules data:', ['schedules' => $schedules->toArray()]);

        return view('schedules.index', [
            'schedules' => $schedules,
            'customers' => $customers,
            'unit' => $unit,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'service_type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $schedule = Schedule::create([
            'unit_id' => $request->user()->unit->id,
            'user_id' => $request->user()->id,
            'customer_id' => $validated['customer_id'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'service_type' => $validated['service_type'],
            'notes' => $validated['notes'],
            'status' => 'pending',
            'is_confirmed' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Agendamento criado com sucesso!'
        ]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        $schedule->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Agendamento atualizado com sucesso!'
        ]);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return response()->json([
            'success' => true,
            'message' => 'Agendamento excluído com sucesso!'
        ]);
    }
}
