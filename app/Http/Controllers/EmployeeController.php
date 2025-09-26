<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Endpoint GET All Employee
     */
    public function index(Request $request) {
        /**
         * PAGINATE NYA DISINI
         */
        $skip   = (int) $request->query('skip', 0);   // default 0
        $take   = (int) $request->query('take', 10);  // default 10
        $search = $request->query('search', '');

        /**
         * Dibawah ini berlaku untuk filter column di frontend
         */
        $name     = $request->query('name');
        $email    = $request->query('email');
        $position = $request->query('position');
        $created  = $request->query('createdate'); // format: YYYY-MM-DD

        $query = Employee::query();

        /**
         * Fitur Search Global, yang berada di luar data grid.
         */
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('position', 'like', "%{$search}%");
            });
        }

         /**
          * Fitur Filter
          */
        if (!empty($name)) {
            $query->where('name', 'like', "%{$name}%");
        }
        if (!empty($email)) {
            $query->where('email', 'like', "%{$email}%");
        }
        if (!empty($position)) {
            $query->where('position', 'like', "%{$position}%");
        }
        if (!empty($created)) {
            $query->whereDate('created_at', $created);
        }

        $total = $query->count();

        $employees = $query
                ->skip($skip)
                ->take($take)
                ->orderBy('id', 'desc')
                ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Get data all employee successfully',
            'data'    => $employees,
            'meta'    => [
                'skip'       => $skip,
                'take'       => $take,
                'total'      => $total,
                'returned'   => $employees->count(),
                'has_more'   => ($skip + $take) < $total,
            ],
        ]);
    }

    /**
     * Endpoint Store / Post Data Employee
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|min:5',
            'email' => 'required|email|unique:employees',
            'position' => 'string'
        ]);

        $employee = Employee::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Employee created successfully',
            'data' => $employee
        ], 201);
    }

    /**
     * Endpoint Find Employee By ID
     */
    public function show($id) {
        $employee = Employee::findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Get data employee successfully',
            'data' => $employee
        ]);
    }

    /**
     * Endpoint Update Employee By ID
     */
    public function update(Request $request, $id) {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|min:5',
            'email' => 'email|unique:employees,email,' . $id,
            'position' => 'string'
        ]);

        $employee->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Employee updated successfully',
            'data' => $employee
        ]);
    }

    /**
     * Endpoint Destroy / Delete Data Employee By ID
     */
    public function destroy($id) {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json([
            'status' => true,
            'message' => 'Employee deleted successfully'
        ]);
    }
}
