<?php

namespace App\Http\Client\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

//    protected function isPreviewable()
//    {
//        $request = request();
//
//        return $request->user()
//            && $request->user()->hasAnyRole2(User::ROLE_ADMIN)
//            && $request->has('_preview');
//    }

    public function redirectBackOrJson(string $msg, array $added = [])
    {
        $request = request();

        if ($request->ajax()) {
            return response()->json([
                'message' => $msg
            ] + $added);
        }

        return redirect()->back()->with('success', $msg);
    }
}
