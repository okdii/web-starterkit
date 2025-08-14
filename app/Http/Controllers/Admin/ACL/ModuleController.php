<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;
use DB;

class ModuleController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/ACL/Module/Index');
    }

    public function getModuleDt(Request $request, DataTableService $dataTable)
    {
        $query  = $dataTable
                    ->for(Module::query())
                    ->search(['name', 'description'])
                    ->sort(['name', 'description'])
                    ->paginate();
        $data   = [];
        $num    = $dataTable->runningNo($query);

        if( $query->count() > 0 ) {
            foreach ($query->items() as $value) {
                $data[] = [
                    'no'          => $num++,
                    'slug'        => $value->slug(),
                    'name'        => $value->name,
                    'description' => $value->description,
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
            'title'     => 'Create New Module',
        ];

        return Inertia::render('Admin/ACL/Module/Form', $returnArr);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'          => 'required|max:255|string',
            'description'   => 'required|max:255|string',
        ]);

        DB::beginTransaction();
        try {
            $module = Module::create([
                'name'        => $validate['name'],
                'description' => $validate['description']
            ]);

            DB::commit();
            $result = array("severity" => "success", "summary" => "Save", "detail" => "Information successfully saved");
            return to_route('admin.module.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Save", "detail" => "Failed to save information");
            return to_route('admin.module.index')->with($result);
        }
    }

    public function show(Module $module)
    {
        abort(404);
    }

    public function edit(Module $module)
    {
        $returnArr = [
            'module'    => $module,
            'isCreate'  => false,
            'title'     => 'Update User',
        ];

        return Inertia::render('Admin/ACL/Module/Form', $returnArr);
    }

    public function update(Request $request, Module $module)
    {
        $validate = $request->validate([
            'name'          => 'required|max:255|string',
            'description'   => 'required|max:255|string',
        ]);

        DB::beginTransaction();
        try {
            $module->name        = $validate['name'];
            $module->description = $validate['description'];
            $module->save();

            DB::commit();
            $result = array("severity" => "success", "summary" => "Update", "detail" => "Information successfully updated");
            return to_route('admin.module.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Update", "detail" => "Failed to update information");
            return to_route('admin.module.index')->with($result);
        }
    }

    public function destroy(Module $module)
    {
        DB::beginTransaction();
        try {
            $module->delete();

            DB::commit();
            $result = array("severity" => "success", "summary" => "Delete", "detail" => "Information successfully deleted");
            return to_route('admin.module.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Delete", "detail" => "Failed to delete information");
            return to_route('admin.module.index')->with($result);
        }
    }
}
