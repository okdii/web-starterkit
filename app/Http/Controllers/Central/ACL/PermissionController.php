<?php

namespace App\Http\Controllers\Central\ACL;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;
use DB;

class PermissionController extends Controller
{
    public function index()
    {
        return Inertia::render('Central/ACL/Permission/Index');
    }

    public function getPermissionDt(Request $request, DataTableService $dataTable)
    {
        $query  = $dataTable
                    ->for(Permission::query())
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
        abort(404);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $service = new \App\Services\Central\ACL\PermissionService;
            $service->generatePermission();

            DB::commit();
            $result = array("severity" => "success", "summary" => "Save", "detail" => "Information successfully saved");
            return to_route('central.permission.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Save", "detail" => "Failed to save information");
            return to_route('central.permission.index')->with($result);
        }
    }

    public function show(Permission $akses)
    {
        abort(404);
    }

    public function edit(Permission $akses)
    {
        abort(404);
    }

    public function update(Request $request, Permission $akses)
    {
        abort(404);
    }

    public function destroy(Permission $akses)
    {
        abort(404);
    }
}
