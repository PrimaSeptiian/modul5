<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * 1. GET ALL
     */
    public function index(Request $request)
    {
        $query = Booking::query();

        // Fitur Search (Mencari berdasarkan nama pelanggan atau alamat)
        if ($search = $request->query('search')) {
            $query->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
        }

        // Fitur Sorting (orderBy & sortBy)
        $sortBy = $request->query('sortBy', 'created_at'); // Default sort by created_at
        $orderBy = $request->query('orderBy', 'desc');     // Default order descending
        $query->orderBy($sortBy, $orderBy);

        // Fitur Pagination (limit & page otomatis diurus oleh method paginate)
        $limit = $request->query('limit', 10);
        $bookings = $query->paginate($limit);

        return response()->json($bookings);
    }

    /**
     * 2. CREATE
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'address'        => 'required|string',
            'booking_date'   => 'required|date',
            'service_type'   => 'required|in:sedot_wc_penuh,perbaikan_saluran,sedot_limbah',
        ]);

        $booking = Booking::create($validated);

        return response()->json([
            'message' => 'Pemesanan berhasil dibuat',
            'data' => $booking
        ], 201);
    }

    /**
     * 3. GET DETAIL
     */
    public function show($id)
    {
        $booking = Booking::findOrFail($id);
        return response()->json($booking);
    }

    /**
     * 4. UPDATE
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'customer_name'  => 'sometimes|string|max:255',
            'customer_phone' => 'sometimes|string|max:20',
            'address'        => 'sometimes|string',
            'booking_date'   => 'sometimes|date',
            'service_type'   => 'sometimes|in:sedot_wc_penuh,perbaikan_saluran,sedot_limbah',
            'status'         => 'sometimes|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->update($validated);

        return response()->json([
            'message' => 'Data pemesanan diperbarui',
            'data' => $booking
        ]);
    }

    /**
     * 5. DELETE
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Pemesanan berhasil dihapus']);
    }
}