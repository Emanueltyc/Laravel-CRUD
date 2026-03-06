<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Role $role)
    {
        if (!Auth::User()->can('view', $role)) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'You are not authorized to make this request!',
            ], 403);
        }

        $offset = $request->get('offset') ?? 0;
        $limit = $request->get('limit') ?? 10;
        $orderBy = $request->get('orderBy') ?? 'created_at';

        $roles = Role::skip($offset * $limit)->take($limit)->orderBy($orderBy)->get();

        return response()->json($roles, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        if (!Auth::User()->can('create')) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'You are not authorized to make this request!',
            ], 403);
        }

        try {
            $data = $request->validated();

            $role = new Role();
            $role->fill($data);
            $role->save();

            return response()->json([$role], 201);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'An error occurred when trying to create the role!',
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Auth::User()->can('view')) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'You are not authorized to make this request!',
            ], 403);
        }

        try {
            $role = Role::findOrFail($id);
            return response()->json($role, 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Role not found!',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        try {
            $data = $request->validated();

            $role = Role::findOrFail($id);
            $role->update($data);

            return response()->json($role, 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'An error occurred when trying to update the role!',
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $removed = Role::destroy($id);

            if (!$removed)
                throw new Exception();

            return response()->json(null, 204);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'An error occurred when trying to delete the role!',
            ], 400);
        }
    }
}
