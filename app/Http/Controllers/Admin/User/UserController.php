<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/User/Index');
    }

    public function getUserDt(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        // $page   = ($request->input('skip', 0) / $perPage) + 1;
        $page = $request->input('page', 1);

        $query = \App\Models\User::query();

        if ($search = $request->input('filters.name')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->has('sortField')) {
            $direction = $request->input('sortOrder') == 1 ? 'asc' : 'desc';
            $query->orderBy($request->input('sortField'), $direction);
        }

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $users->items(),
            'totalRecords' => $users->total(),
        ]);
    }
}
