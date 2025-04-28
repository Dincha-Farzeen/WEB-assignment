<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function reset(Request $request)
    {
        // Validate the request 
        $validator = Validator::make($request->all(), [
            'reset_email' => 'required|email|exists:registered_user,u_email',
            'new_password' => 'required|min:2|confirmed',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed during password reset', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all(),
            ]);

            return back()->withErrors($validator)->withInput()->with([
                'popupMessage' => $validator->errors()->first(),
                'popupType' => 'error',
            ]);
        }

        try {
            // Update the password in the registered_user table
            $updated = DB::table('registered_user')
                ->where('u_email', $request->reset_email)
                ->update([
                    'pass_word' => Hash::make($request->new_password),
                ]);

            if (!$updated) {
                Log::error('Password update failed', [
                    'email' => $request->reset_email,
                ]);

                return back()->with([
                    'popupMessage' => 'Failed to reset password. Please try again.',
                    'popupType' => 'error',
                ]);
            }

            Log::info('Password reset successfully', [
                'email' => $request->reset_email,
            ]);

            return back()->with([
                'popupMessage' => 'Password reset successfully!',
                'popupType' => 'success',
            ]);
        } catch (\Exception $e) {
            Log::error('Exception during password reset', [
                'message' => $e->getMessage(),
                'email' => $request->reset_email,
            ]);

            return back()->with([
                'popupMessage' => 'An error occurred. Please try again later.',
                'popupType' => 'error',
            ]);
        }
    }
}