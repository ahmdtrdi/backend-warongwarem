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

        // Get the user's ID from the request
        $reservation->user_id = $request->user()->id;

        $reservation->save();

        return response()->json([
            'message' => 'Reservasi berhasil',
            'reservation, 201' => $reservation
        ]);
    }
    public function delete(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json(null, 204);
    }

     public function viewReservation(Request $request, $id)
    {
    // Get the user's ID from the request
    $userId = $request->user()->id;
    // Retrieve the reservation made by the user
    $reservation = Reservation::where('id', $id)->where('user_id', $userId)->first();
    if ($reservation) {
        return response()->json([
            'name' => $reservation->name,
            'table_type' => $reservation->table_type,
            'people' => $reservation->people,
            'time' => $reservation->time,
            'date' => $reservation->date,
            'phone_number' => $reservation->phone_number,
            'status' => $reservation->status
        ]);
    } else {
        return response()->json(['error' => 'Reservasi tidak ditemukan'], 404);
    }
    }
    public function updateStatus(Request $request, $id)
    {
    // Cek apakah pengguna adalah manajer atau pelayan
    if ($request->user()->role == 'manager' || $request->user()->role == 'waiter') {
        $reservation = Reservation::find($id);
        if ($reservation) {
            if ($request->status == 'approved' && $reservation->status != 'paid') {
                return response()->json(['error' => 'Reservasi harus berstatus paid sebelum dapat disetujui'], 403);
            } else {
                $reservation->status = $request->status; // status diambil dari request
                $reservation->save();
                return response()->json([
                    'message' => 'Status reservasi diperbarui',
                    'reservation' => $reservation
                ]);
            }
        } else {
            return response()->json(['error' => 'Reservasi tidak ditemukan'], 404);
        }
    } else {
        return response()->json(['error' => 'Hanya manajer atau pelayan yang dapat memperbarui status reservasi'], 403);
    }
    }


    public function reschedule(Request $request, $id)
    {
        // Cek apakah pengguna adalah manajer
        if ($request->user()->role == 'manager') {
            $reservation = Reservation::find($id);
            if ($reservation) {
                $reservation->time = $request->time;
                $reservation->date = $request->date;
                $reservation->status = 'rescheduled';
                $reservation->save();
                return response()->json([
                    'message' => 'Reservasi berhasil direschedule',
                    'reservation' => $reservation
                ]);
            } else {
                return response()->json(['error' => 'Reservasi tidak ditemukan'], 404);
            }
        } else {
            return response()->json(['error' => 'Hanya manajer yang dapat mereschedule reservasi'], 403);
        }
    }
}
