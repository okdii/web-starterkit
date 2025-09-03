<?php

namespace App\Http\Controllers\Central\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Shared\Datatable\DataTableService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use DB;

class UserController extends Controller
{
    public function index()
    {
        $returnArr = [
            'user_status' => \App\Enums\UserStatus::toSelectValue()
        ];

        return Inertia::render('Central/User/Index', $returnArr);
    }

    public function getUserDt(Request $request, DataTableService $dataTable)
    {
        $query  = $dataTable
                    ->for(User::query())
                    ->search(['name', 'email'])
                    ->filter([
                        'status' => [
                            'field'       => 'users.status',
                            'operator'    => '=',
                            'request_key' => 'filter.status.id',
                        ]
                    ])
                    ->sort(['name', 'email', 'created_at'])
                    ->paginate();
        $data   = [];
        $num    = $dataTable->runningNo($query);

        if( $query->count() > 0 ) {
            foreach ($query->items() as $value) {
                $data[] = [
                    'no'            => $num++,
                    'slug'          => $value->slug(),
                    'name'          => $value->name,
                    'email'         => $value->email,
                    'created_at'    => Carbon::parse($value->created_at)->format('d/m/Y H:i:s'),
                    'status' => [
                        'description'   => $value->status->description,
                        'severity'      => $value->status->getPillsSeverity()
                    ],
                    'action' => [
                        'show'      => true,
                        'edit'      => true,
                        'delete'    => true,
                    ]
                ];
            }
        }

        return response()->json([
            'data'          => $data,
            'totalRecords'  => $query->total(),
        ]);
    }

    public function create()
    {
        $list_role = \App\Models\Role::select(['id', 'name'])->orderBy('name')->get();

        $returnArr = [
            'isCreate'      => true,
            'title'         => 'Create New User',
            'list_role'     => $list_role,
            'list_status'   => \App\Enums\UserStatus::toSelectValue()
        ];

        return Inertia::render('Central/User/Form', $returnArr);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'role'      => 'required|array',
            'status'    => 'required',
        ]);

        DB::beginTransaction();
        try {
            $user           = new User();
            $user->name     = $validate['name'];
            $user->email    = $validate['email'];
            $user->status   = $validate['status'];
            $user->password = Hash::make('123456');
            if (count($validate['role']) > 0) {
                $user->role = array_map(fn ($item) => \App\Models\Role::decodeSlug($item), $validate['role']);
            }
            $user->save();

            event(new Registered($user));

            // Sync Role
            \App\Models\Role::syncRole($user);

            DB::commit();
            $result = array("severity" => "success", "summary" => "Save", "detail" => "Information successfully saved");
            return to_route('central.user.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Save", "detail" => "Failed to save information");
            return to_route('central.user.index')->with($result);
        }
    }

    public function show(User $user)
    {
        $returnArr = [
            'user'      => $user,
            'title'     => 'User Detail',
        ];

        return Inertia::render('Central/User/Show', $returnArr);
    }

    public function edit(User $user)
    {
        $list_role = \App\Models\Role::select(['id', 'name'])->orderBy('name')->get();

        $returnArr = [
            'user'        => $user,
            'isCreate'    => false,
            'title'       => 'Update User',
            'list_role'   => $list_role,
            'list_status' => \App\Enums\UserStatus::toSelectValue()
        ];

        return Inertia::render('Central/User/Form', $returnArr);
    }

    public function update(Request $request, User $user)
    {
        $validate = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role'  => 'required|array',
            'status' => 'required',
        ]);
        
        DB::beginTransaction();
        try {
            $user->name     = $validate['name'];
            $user->email    = $validate['email'];
            $user->status   = $validate['status'];
            if (count($validate['role']) > 0) {
                $user->role = array_map(fn ($item) => \App\Models\Role::decodeSlug($item), $validate['role']);
            }
            $user->save();

            // Sync Role
            \App\Models\Role::syncRole($user);

            DB::commit();
            $result = array("severity" => "success", "summary" => "Update", "detail" => "Information successfully updated");
            return to_route('central.user.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Update", "detail" => "Failed to update information");
            return to_route('central.user.index')->with($result);
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $user->delete();

            DB::commit();
            $result = array("severity" => "success", "summary" => "Delete", "detail" => "Information successfully deleted");
            return to_route('central.user.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Delete", "detail" => "Failed to delete information");
            return to_route('central.user.index')->with($result);
        }
    }
}
