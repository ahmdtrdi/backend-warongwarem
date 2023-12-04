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

    public function show($id)
    {
        $reservation = Reservation::find($id);
        return $reservation;
    }

    public function userReservations(Request $request)
    {
        // Get the user's ID from the request
        $userId = $request->user()->id;
        // Retrieve the reservatisons made by the user
        $reservations = Reservation::where('user_id', $userId)->get();
        // Return the reservations as a JSON response
        return response()->json($reservations);
    }

    public function store(Request $request)
    {
        $reservation = new Reservation;
        $reservation->name = $request->name;
        $reservation->table_type = $request->table_type;
        $reservation->people = $request->people;
        $reservation->time = $request->time;
        $reservation->date = $request->date;
        $reservation->phone_number = $request->phone_number;
        $reservation->user_id = $request->user_id;
        $reservation->save();

        return response()->json([
            'message' => 'Reservation created successfully',
            'reservation, 201' => $reservation
        ]);
    }
    public function delete(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json(null, 204);
    }
}
