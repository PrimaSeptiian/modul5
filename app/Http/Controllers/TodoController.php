<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- Jangan lupa import ini

class TodoController extends Controller
{
    public function index(Request $req)
    {
        // (Biarkan kode index Anda yang lama, tidak perlu diubah)
        $q = Todo::query();
        if ($s = $req->query('search')) {
            $q->where(fn($qq) => $qq->where('title', 'like', "%$s%")
                ->orWhere('description', 'like', "%$s%"));
        }
        // ... filter lainnya ...
        $todos = $q->paginate($req->integer('limit', 10));
        return response()->json($todos);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:pending,in_progress,done',
            'category'    => 'nullable|in:personal,work,study,others',
            'file'        => 'nullable|file|max:5120', // Max 5MB
        ]);

        // Logika Upload File
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // Simpan file dengan nama unik di folder 'todos'
            $path = $file->storeAs('todos', time() . '_' . $file->getClientOriginalName(), 'public');
            $data['file_path'] = $path;
        }

        $todo = Todo::create($data);
        return response()->json(['message' => 'Todo created', 'data' => $todo], 201);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);
        
        $data = $request->validate([
            'title' => 'sometimes|string|max:150',
            'file'  => 'nullable|file|max:5120',
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

    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();
        return response()->json(['message' => 'Todo deleted']);
    }
    
    public function show($id)
    {
        return response()->json(Todo::findOrFail($id));
    }
}