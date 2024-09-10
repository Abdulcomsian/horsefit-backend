<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Module;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    private RoleService $roleService;
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:View Role', only: ['index', 'show']),
            new Middleware('permission:Create Role', only: ['create', 'store']),
            new Middleware('permission:Edit Role', only: ['edit', 'update']),
            new Middleware('permission:Delete Role', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Role/Index',[
            
            'roles' => Role::query()->filters()
                ->paginate(request('entries') ?? 10)
                ->appends($request->except('page')),
            'requestParam' => count($request->all()) ?? null

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Role/Create', [
            'modules' => Module::query()->with('permissions')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $storeRoleRequest)
    {
       $this->roleService->store($storeRoleRequest->all());  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return Inertia::render('Role/Edit', [
            'role' => $role,
            'modules' => Module::query()->with('permissions')->get(),
            'permissionIds' => $role->permissions->pluck('id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $updateRoleRequest, Role $role)
    {
        $this->roleService->update($updateRoleRequest->all(), $role);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->roleService->destroy($role);  
    }
}
