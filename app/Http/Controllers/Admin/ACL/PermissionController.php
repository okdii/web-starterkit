<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class PermissionController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/ACL/Permission/Index');
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
            $existingRouteList  = Permission::pluck('hash', 'id')->toArray();
            $routesList         = Route::getRoutes();
            $excludedPrefixes   = ["generated::", "storage.", "debugbar.", "sanctum"];
            $filteredRoutes     = collect($routesList)
                                    ->filter(function ($route) use ($excludedPrefixes) {
                                        foreach ($excludedPrefixes as $prefix) {
                                            if (str_starts_with($route->getName(), $prefix)) {
                                                return false;
                                            }
                                        }

                                        return true;
                                    });
            $routeHash         = [];

            foreach ($filteredRoutes as $value) {
                if($value->getName()) {
                    $url            = $value->uri();
                    $function       = explode("@", $value->getActionName())[1] ?? '-';
                    $hash           = md5($value->methods()[0] . '_' . $value->getName() . '_' . $function . '_' . $url);
                    $routeHash[]    = $hash;
        
                    Permission::updateOrCreate(
                        [
                            'name'     => $value->getName(),
                            'method'   => $value->methods()[0],
                            'url'      => $url,
                            'function' => $function,
                        ],
                        [
                            'guard_name'    => 'web',
                            'hash'          => $hash,
                        ]
                    );
                }
            }

            $diff = array_diff($existingRouteList, $routeHash);
            if(count($diff) > 0) {
                Permission::whereIn('hash', $diff)->delete();
            }

            DB::commit();
            $result = array("severity" => "success", "summary" => "Save", "detail" => "Information successfully saved");
            return to_route('admin.permission.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            $result = array("severity" => "error", "summary" => "Save", "detail" => "Failed to save information");
            return to_route('admin.permission.index')->with($result);
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
