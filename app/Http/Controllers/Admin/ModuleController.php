<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModuleRequest;
use App\Http\Requests\UpdateModuleRequest;
use App\Models\Module;
use App\Services\ModuleService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ModuleController extends Controller implements HasMiddleware
{
    private ModuleService $moduleService;
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:View Module', only: ['index', 'show']),
            new Middleware('permission:Create Module', only: ['create', 'store']),
            new Middleware('permission:Edit Module', only: ['edit', 'update']),
            new Middleware('permission:Delete Module', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Module/Index',[
            
            'modules' => Module::query()->filters()
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
        return Inertia::render('Module/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModuleRequest $storeModuleRequest)
    {
       $this->moduleService->store($storeModuleRequest->all());  
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        return Inertia::render('Module/Edit', [
            'module' => $module->load('permissions'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModuleRequest $updateModuleRequest, Module $module)
    {
        $this->moduleService->update($updateModuleRequest->all(), $module);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        $this->moduleService->destroy($module);  
    }
}
