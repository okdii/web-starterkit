<?php

namespace App\Http\Controllers\Tenant\ACL;

use App\Http\Controllers\Controller;
use App\Services\Shared\Datatable\DataTableService;
use App\Models\Module;
use App\Models\Role;
use App\Models\RoleModule;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;
use DB;

class RoleModuleController extends Controller
{
    public function index()
    {
        abort(404);
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $role_module = RoleModule::firstOrNew(
                [
                    'module_id' => Module::decodeSlug($request->module_id),
                    'role_id'   => Role::decodeSlug($request->role_id)
                ]
            );
            $role_module->save();

            Role::syncPermissionByRoleId($role_module->role_id);

            DB::commit();
            $result = array("severity" => "success", "summary" => "Save", "detail" => "Information successfully saved");
            return to_route('tenant.role-module.edit', $request->role_id)->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Save", "detail" => "Failed to save information");
            return to_route('tenant.role-module.edit', $request->role_id)->with($result);
        }
    }

    public function show(RoleModule $role_module)
    {
        abort(404);
    }

    public function edit(Role $role_module)
    {
        $returnArr      = [
            'isCreate'      => false,
            'role_module'   => $role_module,
        ];

        return Inertia::render('Tenant/ACL/RoleModule/Form', $returnArr);
    }

    public function getModuleDt(Request $request, DataTableService $dataTable)
    {
        $query = $dataTable
                    ->for(
                        Module::select(['modules.id', 'modules.name', 'role_modules.id AS role_modules_id'])
                                    ->leftJoin('role_modules', function($join) use($request) {
                                        $join->on('modules.id', 'role_modules.module_id')
                                            ->where('role_modules.role_id', Role::decodeSlug($request->filter['role_id']));
                                    })
                    )
                    ->search(['name'])
                    ->sort(['name'])
                    ->paginate();
        $data = [];
        $num = $dataTable->runningNo($query);

        if ($query->count() > 0) {
            foreach ($query->items() as $value) {
                $exist          = false;
                $role_module    = null;
                if(!is_null($value->role_modules_id)) {
                    $exist          = true;
                    $role_module    = RoleModule::where('id', $value->role_modules_id)->first()->slug;
                }

                $data[] = [
                    'no'              => $num++,
                    'slug'            => $value->slug(),
                    'name'            => Str::upper($value->name),
                    'role_modules_id' => $role_module,
                    'action' => [
                        'add'       => !$exist,
                        'delete'    => $exist,
                    ]
                ];
            }
        }

        return response()->json([
            'data' => $data,
            'totalRecords' => $query->total(),
        ]);
    }

    public function update(Request $request, RoleModule $role_module)
    {
        abort(404);
    }

    public function destroy(RoleModule $role_module)
    {
        DB::beginTransaction();
        try {
            $role_id = $role_module->relationRole;
            $role_module->delete();

            Role::syncPermissionByRoleId($role_module->role_id);

            DB::commit();
            $result = array("severity" => "success", "summary" => "Delete", "detail" => "Information successfully deleted");
            return to_route('tenant.role-module.edit', $role_id->slug)->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Delete", "detail" => "Failed to delete information");
            return to_route('tenant.role-module.edit', $role_id->slug)->with($result);
        }
    }
}
