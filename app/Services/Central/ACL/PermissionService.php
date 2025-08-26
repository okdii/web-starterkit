<?php

namespace App\Services\Central\ACL;

use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class PermissionService
{
    public function generatePermission()
    {
        $existingRouteList  = Permission::pluck('hash', 'id')->toArray();
        $routesList         = Route::getRoutes();
        $excludedPrefixes   = ["generated::", "storage.", "debugbar.", "sanctum", "stancl."];
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
                $controller     = explode("@", $value->getActionName())[0] ?? '-';
                $function       = explode("@", $value->getActionName())[1] ?? '-';
                $hash           = md5($value->methods()[0] . '_' . $value->getName() . '_' . $controller . '_' . $function . '_' . $url);
                $routeHash[]    = $hash;
    
                Permission::updateOrCreate(
                    [
                        'name'          => $value->getName(),
                        'method'        => $value->methods()[0],
                        'url'           => $url,
                        'controller'    => $controller,
                        'function'      => $function,
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
    }
}