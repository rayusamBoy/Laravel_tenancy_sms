<?php

namespace App\Repositories;

use App\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

class TenantRepo
{
    /*********** Tenant ***************/

    public function getAll()
    {
        return Tenant::with('domain')->get();
    }

    public function create(array $data)
    {
        return Tenant::create($data);
    }

    public function find(int $id)
    {
        return Tenant::with('domain')->find($id);
    }

    public function update(int $id, array $data)
    {
        return Tenant::find($id)->update($data);
    }

    public function delete(int $id)
    {
        return Tenant::find($id)->delete();
    }

    /*********** Domain ***************/

    public function updateDomain(array $where, array $data)
    {
        return Domain::where($where)->update($data);
    }

    public function deleteDomain(array $where)
    {
        return Domain::where($where)->delete();
    }
}
