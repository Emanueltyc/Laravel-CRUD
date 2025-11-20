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
    public function index(Request $request)
    {
        if (!Auth::User()->hasPermission('read_roles'))
            return response()->json([
                'status' => 'forbidden',
                'message' => 'You are not authorized to make this request!',
            ], 403);

        $page = $request->get('page') ?? 1;
        $regsPerPage = $request->get('regsPerPage') ?? 10;
        $orderBy = $request->get('orderBy') ?? 'created_at';
        $skip = ($page - 1) * $regsPerPage;

        $roles = Role::skip($skip)->take($regsPerPage)->orderBy($orderBy)->get();

        return response()->json($roles, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
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
