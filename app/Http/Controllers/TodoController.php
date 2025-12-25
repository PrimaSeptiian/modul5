<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TodoController extends Controller
{
    // GET ALL (Public)
    public function index(Request $req)
    {
        $q = Todo::query();

        if ($s = $req->query('search')) {
            $q->where(fn($qq) => $qq->where('title', 'like', "%$s%")
                ->orWhere('description', 'like', "%$s%"));
        }

        $todos = $q->latest()->paginate($req->integer('limit', 10));
        return response()->json($todos);
    }

    // GET DETAIL (Public)
    public function show($id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        return response()->json($todo);
    }

    // CREATE (Protected)
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string',
            'category'    => 'nullable|string',
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->storeAs('todos', time() . '_' . $file->getClientOriginalName(), 'public');
            $data['file_path'] = $path;
        }

        $todo = Todo::create($data);
        return response()->json(['message' => 'Todo created', 'data' => $todo], 201);
    }

    // UPDATE (Protected)
    public function update(Request $request, $id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        $data = $request->validate([
            'title'       => 'sometimes|required|string|max:150',
            'description' => 'nullable|string',
            'category'    => 'nullable|string',
            'file'        => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($todo->file_path && Storage::disk('public')->exists($todo->file_path)) {
                Storage::disk('public')->delete($todo->file_path);
            }
            $path = $request->file('file')->storeAs('todos', time() . '_' . $request->file('file')->getClientOriginalName(), 'public');
            $data['file_path'] = $path;
        }

        $todo->update($data);
        return response()->json(['message' => 'Todo updated', 'data' => $todo]);
    }

    // DELETE (Protected)
    public function destroy($id)
    {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        // Opsional: Hapus file fisik saat delete data
        if ($todo->file_path && Storage::disk('public')->exists($todo->file_path)) {
            Storage::disk('public')->delete($todo->file_path);
        }

        $todo->delete();
        return response()->json(['message' => 'Todo deleted successfully']);
    }
}