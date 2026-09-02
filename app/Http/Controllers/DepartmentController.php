<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Modules\Department\Service\DepartmentService;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    private $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function all(DepartmentRequest $request)
    {
        return $this->departmentService->all();
    }

    public function add(DepartmentRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->departmentService->add(
                $validated['name']
            );
        });

        return $returnValue;
    }

    public function edit(DepartmentRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->departmentService->edit(
                $validated['id'],
                $validated['name']
            );
        });

        return $returnValue;
    }

    public function delete(DepartmentRequest $request)
    {
        $validated = $request->validated();

        $returnValue = DB::transaction(function () use ($validated) {
            return $this->departmentService->delete(
                $validated['id']
            );
        });

        return $returnValue;
    }
}
