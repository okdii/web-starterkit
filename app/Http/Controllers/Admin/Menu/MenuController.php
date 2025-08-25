<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DB;

class MenuController extends Controller
{
    public function index()
    {
        $flat       = Menu::with('relationParent:id,name,icon,parent_id')
                        ->select(['id', 'name', 'icon', 'parent_id'])
                        ->orderBy('parent_id')
                        ->orderBy('order')
                        ->get()
                        ->toArray();
        $service    = new \App\Services\Admin\Menu\MenuService;
        $treeData   = $service->buildTree($flat);

        $returnArr  = [
            'treeData'  => $treeData
        ];

        return Inertia::render('Admin/Menu/Index', $returnArr);
    }

    public function create()
    {
        $list_route = \App\Models\Permission::select(['id', 'name'])
                                            ->where('method', 'GET')
                                            ->orderBy('name')
                                            ->get();

        $returnArr  = [
            'isCreate'      => true,
            'title'         => 'Create New Menu',
            'list_route'    => $list_route
        ];

        return Inertia::render('Admin/Menu/Form', $returnArr);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'          => 'required|string|max:255',
            'route_name'    => 'nullable|array',
            'icon'          => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $menu                = new Menu;
            $menu->name          = $validate['name'];
            $menu->icon          = $validate['icon']['value'];
            if(isset($validate['route_name']['slug'])) {
                $menu->permission_id = \App\Models\Permission::decodeSlug($validate['route_name']['slug']);
            }
            $menu->save();

            DB::commit();
            $result = array("severity" => "success", "summary" => "Save", "detail" => "Information successfully saved");
            return to_route('admin.menu.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Save", "detail" => "Failed to save information");
            return to_route('admin.menu.index')->with($result);
        }
    }

    public function show(Menu $menu)
    {
        abort(404);
    }

    public function edit(Menu $menu)
    {
        $list_route = \App\Models\Permission::select(['id', 'name'])
                                            ->where('method', 'GET')
                                            ->orderBy('name')
                                            ->get();

        $returnArr  = [
            'menu'          => $menu,
            'title'         => 'Update Menu',
            'list_route'    => $list_route,
        ];

        return Inertia::render('Admin/Menu/Form', $returnArr);
    }

    public function update(Request $request, Menu $menu)
    {
        $validate = $request->validate([
            'name'          => 'required|string|max:255',
            'route_name'    => 'nullable|array',
            'icon'          => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $menu->name          = $validate['name'];
            $menu->icon          = $validate['icon']['value'];
            if(isset($validate['route_name']['slug'])) {
                $menu->permission_id = \App\Models\Permission::decodeSlug($validate['route_name']['slug']);
            }
            $menu->save();

            DB::commit();
            $result = array("severity" => "success", "summary" => "Update", "detail" => "Information successfully updated");
            return to_route('admin.menu.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Update", "detail" => "Failed to update information");
            return to_route('admin.menu.index')->with($result);
        }
    }

    public function destroy(Menu $menu)
    {
        DB::beginTransaction();
        try {
            $menu->delete();

            DB::commit();
            $result = array("severity" => "success", "summary" => "Delete", "detail" => "Information successfully deleted");
            return to_route('admin.menu.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Delete", "detail" => "Failed to delete information");
            return to_route('admin.menu.index')->with($result);
        }
    }

    public function updateTree(Request $request)
    {
        DB::beginTransaction();
        try {
            $tree = $request->input('tree'); // or from DB

            $service = new \App\Services\Admin\Menu\MenuService;
            $flatten = $service->flattenTree($tree);

            foreach ($flatten as $item) {
                $menu = Menu::updateOrCreate(
                    [
                        'id' => Menu::decodeSlug($item['slug'])
                    ],
                    [
                        'parent_id' => Menu::decodeSlug($item['parent_id']),
                        'name'      => $item['name'],
                        'order'     => $item['depth'],
                    ]
                );
                $menu->save();
            }

            DB::commit();
            $result = array("severity" => "success", "summary" => "Update", "detail" => "Information successfully updated");
            return to_route('admin.menu.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            $result = array("severity" => "error", "summary" => "Update", "detail" => "Failed to update information");
            return to_route('admin.menu.index')->with($result);
        }
    }
}