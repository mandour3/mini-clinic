<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function updatePassword(Request $request)
    {
        // return 0;
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|max:100|different:current_password',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $user = auth()->user();

        // Check if the provided current password matches the user's current password
        if (!password_verify($request->current_password, $user->password)) {
            return $this->apiResponse(null, ['current_password' => 'Incorrect current password'], 400);
        }

        // Update the user's password
        $user->update([
            'password' => bcrypt($request->new_password),
        ]);

        return $this->apiResponse($user, 'Password updated successfully', 200);
    }
}
