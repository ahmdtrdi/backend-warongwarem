<?php
// app/Http/Controllers/ReservationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all();
        return response()->json($reservations);
    }

    public function store(Request $request)
    {
        $reservation = new Reservation;
        $reservation->table_type = $request->table_type;
        $reservation->people = $request->people;
        $reservation->time = $request->time;
        $reservation->date = $request->date;
        $reservation->phone_number = $request->phone_number;
        $reservation->customer_id = $request->customer_id;
        $reservation->save();

        return response()->json([
            'message' => 'Reservation created successfully',
            'reservation' => $reservation
        ]);
    }

}
