<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\ReservationController;

class tablesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $table = Table::all();
        return response()->json($table);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(tablesController $tableController)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tablesController $tableController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tablesController $tableController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tablesController $tableController)
    {
        //
    }

    public function availableTables(Request $request)
    {
        $tables = Table::leftJoin('reservations', 'table_list.table_id', '=', 'reservations.table_id')
            ->select('table_list.table_id', 'table_list.type', 'table_list.capacity', 'table_list.on_used', 'reservations.name as reservation_name')
            ->where('table_list.on_used', 0) 
            ->get();
    
        return response()->json($tables);
    }

    public function assignTable(Request $request, $tableId, $reservationId)
    {
        $table = Table::find($tableId);
        $reservation = Reservation::find($reservationId);
        if ($table && $reservation) {
            if ($table->type == $reservation->table_type && $table->reservation_id == null) {
                $table->reservation_id = $reservationId;
                $table->save();
                return response()->json([
                    'message' => 'Meja berhasil ditetapkan untuk reservasi',
                    'table' => $table
                ]);
            } else {
                return response()->json(['error' => 'Meja tidak tersedia atau tidak sesuai dengan tipe yang diminta'], 403);
            }
        } else {
            return response()->json(['error' => 'Meja atau reservasi tidak ditemukan'], 404);
        }
    }
    
    public function unAssigntable(Request $request, $tableId)
{
    $table = Table::find($tableId);
    if ($table) {
        $table->reservation_id = null;
        $table->save();
        return response()->json([
            'message' => 'Meja sekarang tersedia',
            'table' => $table
        ]);
    } else {
        return response()->json(['error' => 'Meja tidak ditemukan'], 404);
    }
}

}
