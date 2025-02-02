<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function addUser(Request $request){
        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email',
                'username' => 'required|string|min:3',
            ]);

            $user = User::create($validated);

            return response()->json([
                'status' => 200,
                'msg' => 'Success Add User',
                'data' => $user
            ], 200);
            // Validation error
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'msg' => 'Validation Error. Please check your input',
                'errors' => $e->getMessage()
            ], 422);
            // Any error
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllUsers()
    {
        try {
            $users = User::all();

            if ($users->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'msg' => 'No Users Found in Database',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'status' => 200,
                'msg' => 'Success',
                'data' => $users
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUser($userId)
    {
        try {
            $user = User::findOrFail($userId);

            return response()->json([
                'status' => 200,
                'msg' => 'Success',
                'data' => $user
            ], 200);
        }catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUserStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id',
                'isActive' => 'required|boolean',
            ]);

            $user = User::findOrFail($validated['id']);
            $user->isActive = $validated['isActive'];
            $user->save();

            return response()->json([
                'status' => 200,
                'msg' => 'Success Update Status',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUsersPagination($limit, $page)
    {
        $totalData = User::count();
        $users = User::paginate($limit, ['*'], 'page', $page);

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 404,
                'msg' => 'No Users Found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'msg' => 'Success',
            'data' => [
                'users' => $users->items(),
                'max_page' => ceil($totalData / $limit),
                'totalData' => $totalData
            ]
        ], 200);
    }

}
