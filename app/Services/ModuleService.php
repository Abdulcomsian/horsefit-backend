<?php

namespace App\Services;

use App\Models\Module;
use Illuminate\Support\Facades\DB;
use Exception;
use Spatie\Permission\Models\Permission;

class ModuleService
{
    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            $module = Module::create([
                'name' => $data['name'],
            ]);
            foreach ($data['permissions'] as $permission) {
                $dataArray[] = ['name' => $permission['name'], 'module_id' => $module->id, 'guard_name' => 'web'];
            }

            Permission::insert($dataArray);

            DB::commit();
            return true;
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }  

    } 

    public function update(array $data, Module $module)
    {
        try {
            DB::beginTransaction();
            $module->update([
                'name' => $data['name'],
            ]);
            if (count($data['permissions']) > 0) {
                foreach ($data['permissions'] as $permissionData) {

                    if (isset($permissionData) && isset($permissionData['id'])) {
                        $permission = $module->permissions()->find($permissionData['id']);
                        $reservedPermissionArray[] = $permissionData['id'];
                        if ($permission) {
                            $permission->update(['name' => $permissionData['name']]);
                        } else {
                            $module->permissions()->create(['name' => $permissionData['name'], 'module_id' => $module->id, 'guard_name' => 'web']);
                        }
                    } else {
                        $newPermission = Permission::create(['name' => $permissionData['name'], 'module_id' => $module->id, 'guard_name' => 'web']);
                        $reservedPermissionArray[] = $newPermission->id;
                    }
                }
            } else {
                $module->permissions()->delete();
            }
            $module->permissions()->whereNotIn('id', $reservedPermissionArray)->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(Module $module)
    {
        try {
            DB::beginTransaction();
            $module->permissions()->delete();
            $module->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
