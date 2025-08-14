<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;
use DB;

class RoleController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/ACL/Role/Index');
    }

    public function getRoleDt(Request $request, DataTableService $dataTable)
    {
        $query  = $dataTable
                    ->for(Role::query())
                    ->search(['name'])
                    ->sort(['name'])
                    ->paginate();
        $data   = [];
        $num    = $dataTable->runningNo($query);

        if( $query->count() > 0 ) {
            foreach ($query->items() as $value) {
                $data[] = [
                    'no'     => $num++,
                    'slug'   => $value->slug(),
                    'name'   => $value->name,
                    'action' => [
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
        $returnArr = [
            'isCreate'  => true,
            'title'     => 'Create New Role',
        ];

        return Inertia::render('Admin/ACL/Role/Form', $returnArr);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'   => 'required|max:255|string',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'guard_name' => 'web',
                'name'       => $validate['name']
            ]);

            DB::commit();
            $result = array("severity" => "success", "summary" => "Save", "detail" => "Information successfully saved");
            return to_route('admin.role.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Save", "detail" => "Failed to save information");
            return to_route('admin.role.index')->with($result);
        }
    }

    public function show(Role $role)
    {
        abort(404);
    }

    public function edit(Role $role)
    {
        $returnArr = [
            'role'      => $role,
            'isCreate'  => false,
            'title'     => 'Update Role',
        ];

        return Inertia::render('Admin/ACL/Role/Form', $returnArr);
    }

    public function update(Request $request, Role $role)
    {
        $validate = $request->validate([
            'name'   => 'required|max:255|string',
        ]);

        DB::beginTransaction();
        try {
            $role->name   = $validate['name'];
            $role->save();

            DB::commit();
            $result = array("severity" => "success", "summary" => "Update", "detail" => "Information successfully updated");
            return to_route('admin.role.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Update", "detail" => "Failed to update information");
            return to_route('admin.role.index')->with($result);
        }
    }

    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            $role->delete();

            DB::commit();
            $result = array("severity" => "success", "summary" => "Delete", "detail" => "Information successfully deleted");
            return to_route('admin.role.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Delete", "detail" => "Failed to delete information");
            return to_route('admin.role.index')->with($result);
        }
    }
}
