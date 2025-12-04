public function index(Request $req)
{
    $q = Todo::query();

    if ($s = $req->query('search')) {
        $q->where(fn($qq) => $qq->where('title', 'like', "%$s%")
            ->orWhere('description', 'like', "%$s%"));
    }

    if ($status = $req->query('status'))  $q->where('status', $status);
    if ($cat = $req->query('category'))   $q->where('category', $cat);
    if ($prio = $req->query('priority'))  $q->where('priority', $prio);

    $q->latest('created_at');

    $todos = $q->paginate($req->integer('limit', 10));
    return response()->json($todos);
}
