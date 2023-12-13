<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use App\Http\Controllers\ReservationController;

class tablesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    public function viewAvailableTables(Request $request)
    {
        // Cek apakah pengguna adalah pelayan
        if ($request->user()->role == 'waiter') {
            // Dapatkan semua meja yang belum dipesan
            $tables = Table::where('reservation_id', null)->get();
            return response()->json($tables);
        } else {
            return response()->json(['error' => 'Hanya pelayan yang dapat melihat meja yang tersedia'], 403);
        }
    }

    public function assignTable(Request $request, $tableId, $reservationId)
    {
        // Cek apakah pengguna adalah pelayan
        if ($request->user()->role == 'waiter') {
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
        } else {
            return response()->json(['error' => 'Hanya pelayan yang dapat menetapkan meja untuk reservasi'], 403);
        }
    }
    public function unAssigntable(Request $request, $id)
    {
        // Cek apakah pengguna adalah manajer atau pelayan
        if ($request->user()->role == 'manager' || $request->user()->role == 'waiter') {
            $table = Table::find($id);
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
        } else {
            return response()->json(['error' => 'Hanya manajer atau pelayan yang dapat membuat meja tersedia'], 403);
        }
    }

}
