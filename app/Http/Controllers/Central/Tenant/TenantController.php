<?php

namespace App\Http\Controllers\Central\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\Shared\Datatable\DataTableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DB;

class TenantController extends Controller
{
    public function index()
    {
        $returnArr = [
            'tenant_status' => \App\Enums\TenantStatus::toSelectValue()
        ];

        return Inertia::render('Central/Tenant/Index', $returnArr);
    }

    public function getTenantDt(Request $request, DataTableService $dataTable)
    {
        $query  = $dataTable
                    ->for(
                        Tenant::select(['tenants.id', 'name', 'status', 'data', 'domain'])
                        ->join('domains', 'tenants.id', 'domains.tenant_id')
                    )
                    ->search(['name', 'domain'])
                    ->filter([
                        'status' => [
                            'field'       => 'tenants.status',
                            'operator'    => '=',
                            'request_key' => 'filter.status.id',
                        ]
                    ])
                    ->sort(['name', 'created_at'])
                    ->paginate();
        $data   = [];
        $num    = $dataTable->runningNo($query);

        if( $query->count() > 0 ) {
            foreach ($query->items() as $value) {
                $data[] = [
                    'no'            => $num++,
                    'slug'          => $value->id,
                    'name'          => $value->name,
                    'domain'        => $value->domain,
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
        $returnArr = [
            'isCreate'  => true,
            'title'     => 'Create New Tenant',
        ];

        return Inertia::render('Central/Tenant/Form', $returnArr);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'     => 'required|string|max:255',
            'domain'   => 'required|string|max:255',
            // 'database' => 'required|string|max:255',
        ]);

        try {
            $tenant = Tenant::create([
                'name' => $validate['name'],
                // 'tenancy_db_name' => $validate['database'],
            ]);

            $tenant->domains()->create([
                'domain' => $validate['domain'],
            ]);

            $result = array("severity" => "success", "summary" => "Save", "detail" => "Information successfully saved");
            return to_route('central.tenant.index')->with($result);
        } catch (\Throwable $th) {
            $result = array("severity" => "error", "summary" => "Save", "detail" => "Failed to save information");
            return to_route('central.tenant.index')->with($result);
        }
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['relationDomain:id,domain,tenant_id']);

        $returnArr = [
            'tenant' => $tenant,
            'title'  => 'Tenant Detail',
        ];

        return Inertia::render('Central/Tenant/Show', $returnArr);
    }

    public function edit(Tenant $tenant)
    {
        $tenant->load(['relationDomain:id,domain,tenant_id']);

        $returnArr = [
            'tenant'    => $tenant,
            'isCreate'  => false,
            'title'     => 'Update Tenant',
        ];

        return Inertia::render('Central/Tenant/Form', $returnArr);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validate = $request->validate([
            'name'     => 'required|string|max:255',
            'domain'   => 'required|string|max:255',
            // 'database' => 'required|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            $tenant->name                   = $validate['name'];
            // $tenant->tenancy_db_name               = $validate['database'];
            $tenant->relationDomain->domain = $validate['domain'];
            $tenant->save();

            DB::commit();
            $result = array("severity" => "success", "summary" => "Update", "detail" => "Information successfully updated");
            return to_route('central.tenant.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Update", "detail" => "Failed to update information");
            return to_route('central.tenant.index')->with($result);
        }
    }

    public function destroy(Tenant $tenant)
    {
        DB::beginTransaction();
        try {
            $tenant->delete();

            DB::commit();
            $result = array("severity" => "success", "summary" => "Delete", "detail" => "Information successfully deleted");
            return to_route('central.tenant.index')->with($result);
        } catch (\Throwable $th) {
            DB::rollback();
            $result = array("severity" => "error", "summary" => "Delete", "detail" => "Failed to delete information");
            return to_route('central.tenant.index')->with($result);
        }
    }
}
