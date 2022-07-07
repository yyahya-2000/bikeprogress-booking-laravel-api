<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function users(Request $request)
    {
        return User::all();
    }

    public function userById(Request $request)
    {
        try {
            if ($idUser = $request->input('id')) {
                return User::query()->where('id', $idUser)->get();
            }
        } catch (Exception $e) {
        }
        return response()->json([
            'message' => 'couldn\'t find the user',
        ], Response::HTTP_BAD_REQUEST);
    }

    public function delete(Request $request)
    {
        try {
            if ($idUser = $request->input('id')) {
                User::query()->where('id', $idUser)->delete();
                return response()->json([
                    'message' => 'success',
                ], Response::HTTP_OK);
            }
        } catch (Exception $e) {
        }
        return response()->json([
            'message' => 'couldn\'t delete the user',
        ], Response::HTTP_BAD_REQUEST);
    }

    public function edit(Request $request)
    {
        $affected = 0;
        try {
            $id = $request->input('id');
            $affected = User::query()->where('id', $id)->update([
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'position' => $request->input('position'),
            ]);
        } catch (Exception $e) {
        }
        return $affected > 0 ?
            response()->json(['message' => 'success'], Response::HTTP_OK) :
            response()->json(['message' => 'couldn\'t update the user'], Response::HTTP_BAD_REQUEST);
    }

    public function adminResetPassword(Request $request)
    {
        try {
            if ($idUser = $request->input('id')) {
                $newPassword = $request->input('new-password');
                User::query()->where('id', $idUser)->update([
                    'password' => Hash::make($newPassword),
//                    'firstname' => 'test'
                ]);
                return response()->json([
                    'message' => 'success',
//                    'user' => User::query()->where('id', $idUser)->get(),
//                    'id' => $idUser,
//                    'password' => $newPassword
                ], Response::HTTP_OK);
            }
        } catch (Exception $e) {
        }
        return response()->json([
            'message' => 'couldn\'t reset the user password',
        ], Response::HTTP_BAD_REQUEST);
    }
}
