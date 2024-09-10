<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Exception;

class RoleService
{
    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $data['name'],
                'status' => $data['status'],
                'description' => $data['description'],
                'guard_name' => 'web',
            ]);

            $role->givePermissionTo($data['permissions']);

            DB::commit();
            return true;
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }  

    } 

    public function update(array $data, Role $role)
    {
        try {
            DB::beginTransaction();
                $role->update([
                //'name' => $data['name'],
                'status' => $data['status'],
                'description' => $data['description'],
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($data['permissions']);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(Role $role)
    {
        try {
            DB::beginTransaction();
            $role->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
