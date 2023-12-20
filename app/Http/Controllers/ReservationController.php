<?php
// app/Http/Controllers/ReservationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;

use App\Models\Table;
use App\Models\User;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all();
        return response()->json($reservations);
    }

    public function show(Request $request)
    {
        $status = $request->input('status');
        $reservations = Reservation::where('status', $status)->get();
        return response()->json($reservations);
    }

    public function showByDate(Request $request)
    {
        $date = $request->input('date');
        $reservations = Reservation::where('date', $date)->get();
        return response()->json($reservations);
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
        Log::info('Request: ', $request->all());
        Log::info('Authenticated user: ', auth()->user());

        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'notes' => 'nullable|string',
                'table_type' => 'required|string',
                'people' => 'required|integer',
                'time' => 'required|date_format:H:i:s',
                'date' => 'required|date',
                'phone_number' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 400);
        }

        /*
        $userId = null;

        // Check for JWT token in the request headers
        if ($token = $request->header('Authorization')) {
            $token = str_replace('Bearer ', '', $token);

            // Get the payload of the token
            try {
                $payload = JWTAuth::getPayload($token);

                // Get the 'sub' claim from the payload
                $userId = $payload['sub'];
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                // Handle JWT exceptions here (e.g., token expired, invalid token)
                Log::error('JWTException: ' . $e->getMessage());
                return response()->json(['error' => 'Invalid token'], 401);
            }
        }

        if ($userId) {
            $validatedData['user_id'] = $userId;
            Log::info('validatedData after adding user_id: ' . json_encode($validatedData));
            $reservation = Reservation::create($validatedData);
            return response()->json($reservation, 201);
        } else {
            // Handle the case where no user is authenticated
            Log::info('No user authenticated');
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        */

        $user = auth()->user();
        Log::info('User: ' . json_encode($user));

        $token = $request->header('Authorization');
        Log::info('Token: ' . $token);

        if ($user) {
            $validatedData['user_id'] = $user->user_id;
            Log::info('validatedData after adding user_id: ' . json_encode($validatedData));
            $reservation = Reservation::create($validatedData);
            return response()->json($reservation, 201);
        } else {
            // Handle the case where no user is authenticated
            Log::info('No user authenticated');
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }

    public function delete(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json(null, 204);
    }

    public function historyReservation(Request $request, $id)
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

    public function acceptReservation(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            // Include all the fields that need to be validated
        ]);

        // Retrieve the table
        $table = Table::find($request->input('table_id'));

        // Check if the table is available
        if ($table->on_used == 0) {
            // Update the on_used field to 1
            $table->on_used = 1;
            $table->save();

            // Add the table_id to the validated data
            $validatedData['table_id'] = $table->table_id;

            // Create the reservation
            $reservation = Reservation::create($validatedData);

            // Return a success message
            return response()->json(['message' => 'Reservation created successfully', 'reservation' => $reservation]);
        } else {
            // Return an error message
            return response()->json(['error' => 'Table is not available'], 400);
        }
    }
}
