<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Services\DataTableService;
use App\Models\Module;
use App\Models\ModulePermission;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;
use DB;

class ModulePermissionController extends Controller
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
            $permission = Permission::findBySlugOrFail($request->permission_id);
            $module     = Module::findBySlugOrFail($request->module_id);

            $module_permission = ModulePermission::firstOrNew([
                'module_id'     => $module->id,
                'permission_id' => $permission->id,
            ]);
            $module_permission->save();

            Role::syncPermissionByModuleId($module_permission->module_id);

            DB::commit();

            $result = array("severity" => "success", "summary" => "Save", "detail" => "Information successfully saved");
            return to_route('admin.module-permission.edit', $request->module_id)->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Save", "detail" => "Failed to save information");
            return to_route('admin.module-permission.edit', $request->module_id)->with($result);
        }
    }

    public function show(ModulePermission $module_permission)
    {
        abort(404);
    }

    public function edit(Module $module_permission)
    {
        $returnArr      = [
            'isCreate'          => false,
            'module_permission' => $module_permission,
        ];

        return Inertia::render('Admin/ACL/ModulePermission/Form', $returnArr);
    }

    public function getPermissionDt(Request $request, DataTableService $dataTable)
    {
        $query = $dataTable
                    ->for(
                        Permission::select(['permissions.id', 'permissions.name', 'module_permissions.id AS module_permissions_id'])
                                    ->leftJoin('module_permissions', function ($join) use ($request) {
                                        $join->on('permissions.id', 'module_permissions.permission_id')
                                            ->where('module_permissions.module_id', Module::decodeSlug($request->filter['module_id']))
                                            ->whereNull('module_permissions.deleted_at');
                                    })
                    )
                    ->search(['name'])
                    ->sort(['name'])
                    ->paginate();
        $data = [];
        $num = $dataTable->runningNo($query);

        if ($query->count() > 0) {
            foreach ($query->items() as $value) {
                $exist              = false;
                $module_permission  = null;
                if(!is_null($value->module_permissions_id)) {
                    $exist = true;
                    $module_permission = ModulePermission::where('id', $value->module_permissions_id)->first()->slug();
                }
                $data[] = [
                    'no'                    => $num++,
                    'slug'                  => $value->slug(),
                    'module_permissions_id' => $module_permission,
                    'name'                  => Str::upper($value->name),
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

    public function update(Request $request, ModulePermission $module_permission)
    {
        abort(404);
    }

    public function destroy(ModulePermission $module_permission)
    {
        DB::beginTransaction();
        try {
            $module_id = $module_permission->relationModule;
            $module_permission->delete();

            Role::syncPermissionByModuleId($module_id);

            DB::commit();
            $result = array("severity" => "success", "summary" => "Delete", "detail" => "Information successfully deleted");
            return to_route('admin.module-permission.edit', $module_id->slug)->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Delete", "detail" => "Failed to delete information");
            return to_route('admin.module-permission.edit', $module_id->slug)->with($result);
        }
    }
}
