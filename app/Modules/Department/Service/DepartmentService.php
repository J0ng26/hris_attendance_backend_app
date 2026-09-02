<?php

namespace App\Modules\Department\Service;

use App\Modules\Department\Repository\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\Response;

class DepartmentService
{
    private $departmentRepository;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function all()
    {
        return $this->departmentRepository->index();
    }

    public function add(string $name)
    {
        $department = $this->departmentRepository->create([
            'name' => $name
        ]);

        return Response::json([
            'message' => 'Department ' . $department->name . ' added successfully',
            'department' => $department
        ], 201);
    }

    public function edit(string $id, string $name)
    {
        $department = $this->departmentRepository->update($id, [
            'name' => $name
        ]);

        return Response::json([
            'message' => 'Department ' . $department->name . ' updated successfully',
            'department' => $department
        ], 200);
    }

    public function delete(string $id)
    {

        $data = $this->departmentRepository->findByPrimaryId($id);

        if (!$data) {
            return Response::json([
                'message' => 'Department not found'
            ], 404);
        }

        $this->departmentRepository->delete($id);

        return Response::json([
            'message' => 'Department ' . $data->name . ' deleted successfully'
        ], 200);
    }
}
