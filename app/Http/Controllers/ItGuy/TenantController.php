<?php

namespace App\Http\Controllers\ItGuy;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\TenantRequest;
use App\Repositories\TenantRepo;
use App\Repositories\UserRepo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TenantController extends Controller implements HasMiddleware
{
    protected $tenant, $user;
    public function __construct(TenantRepo $tenant, UserRepo $user)
    {
        $this->tenant = $tenant;
        $this->user = $user;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new middleware('headSA', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data["tenants"] = $this->tenant->getAll();
        $data["user_types"] = $this->user->getAllTypes();

        return view("pages.it_guy.tenants.index", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TenantRequest $request)
    {
        set_time_limit(600); // Set time limit for this method to 10 minutes.
        $data = $request->except(['_token', '_method', 'domain']);

        try {
            $tenant = $this->tenant->create($data);
        } catch (\Exception $e) {
            // When creating tenant, database, and seeding the database etc., operations will proceed as usual.
            // If there was any exception ie., error. Delete the created ones and return the error.
            // The session's key 'created_tenant_id' is defined in TenancyServiceProvider.
            // Can't access the '$tenant' variable in try block above, since it is in different scope.
            if (session()->has("created_tenant_id"))
                $this->tenant->delete(session()->get("created_tenant_id"));
            
            session()->forget("created_tenant_id");
            return Qs::json($e->getMessage(), false);
        }

        $tenant->createDomain(['domain' => $request->domain]);

        session()->forget("created_tenant_id");

        return back()->with('pop_success', __('msg.store_ok'))->with('pop_timer', 0);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = Qs::decodeHash($id);
        $data["tenant"] = $this->tenant->find($id);

        return view("pages.it_guy.tenants.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TenantRequest $request, string $id)
    {
        $id = Qs::decodeHash($id);
        $domain = $request->domain;
        $tenant = $this->tenant->find($id);

        $tenant->domain = $domain; // Try to update domain
        // If domain appear to change
        if ($tenant->isDirty('domain')) {
            // Invalidate the cached domain for this tenant
            app(\Stancl\Tenancy\Resolvers\DomainTenantResolver::class)->invalidateCache($tenant);
            $this->tenant->updateDomain(['tenant_id' => $tenant->id], ['domain' => $domain]); // Update the domain
        }

        $data = $request->only(['account_status', 'payment_status', 'remarks', 'domain']);
        $this->tenant->update($id, $data);

        return Qs::json(null, null, ['msg' => __('msg.update_ok'), 'ok' => TRUE, 'pop' => TRUE]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Qs::decodeHash($id);
        $this->tenant->delete($id);

        return back()->with('pop_success', __('msg.del_ok'))->with('pop_timer', 0);
    }
}
