<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // GET ALL
    public function index()
    {
        return response()->json(Service::latest()->get());
    }

    // CREATE (Ini yang dipanggil saat tombol Simpan ditekan)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        $service = Service::create($validated);

        return response()->json([
            'message' => 'Layanan berhasil dibuat',
            'data' => $service
        ], 201);
    }

    // DELETE (By UUID)
    public function destroy($uuid)
    {
        $service = Service::where('uuid', $uuid)->firstOrFail();
        $service->delete();
        return response()->json(['message' => 'Layanan dihapus']);
    }
}