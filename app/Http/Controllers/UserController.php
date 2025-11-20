<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->get('page') ?? 1;
        $regsPerPage = $request->get('regsPerPage') ?? 10;
        $orderBy = $request->get('orderBy') ?? 'created_at';
        $skip = ($page - 1) * $regsPerPage;

        $users = User::skip($skip)->take($regsPerPage)->orderBy($orderBy)->get();

        return response()->json($users->toResourceCollection(), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user->toResource(), 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'User not found!',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $user = User::findOrFail($id);
            $user->update($data);
            return response()->json($user->toResource(), 200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'An error occurred when trying to update the user!',
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $removed = User::destroy($id);

            if (!$removed)
                throw new Exception();

            return response()->json(null, 204);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'An error occurred when trying to delete the user!',
            ], 400);
        }
    }
}
