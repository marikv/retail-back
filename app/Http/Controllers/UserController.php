<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    /**
     * @param $id
     * @param Request $request
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function getDataById($id, Request $request, UserRepository $userRepository): JsonResponse
    {
        if ($id) {
            return response()->json([
                'success' => true,
                'data' => $userRepository->getById($id)
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => [ 'message' => 'nu este id' ]
        ], 200);
    }

    /**
     * @param $id
     * @param Request $request
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function addOrEdit($id, Request $request, UserRepository $userRepository): JsonResponse
    {
        if ((int)$id > 0) {
            $User = User::findOrFail($id);
        } else {
            $User = new User();
        }
        $User->name = $request->name;
        $User->phone1 = $request->phone1;
        $User->email = $request->email;
        $User->role_id = $request->role_id ?? User::USER_ROLE_DEALER;
        if ($request->password) {
            $User->password = bcrypt($request->password);
        }
        $User->save();

        return response()->json([
            'success' => true,
            'data' => $userRepository->getById($User->id)
        ], 200);
    }

    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function usersList(Request $request, UserRepository $userRepository): JsonResponse
    {
        $filter = $request->filter;
        $pagination = $request->pagination;

        return response()->json([
            'success' => true,
            'data' => $userRepository->list($filter, $pagination)
        ]);
    }

    /**
     * @param $id
     * @param Request $request
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function deleteUser($id, Request $request, UserRepository $userRepository): JsonResponse
    {
        if (!$id) {
            return response()->json([
                'success' => false,
                'data' => [ 'message' => 'nu este id' ]
            ], 200);
        }

        $success = $userRepository->delete($id);

        if ($success) {
            Log::addNewLog(
                $request,
                Log::MODULE_USERS,
                Log::OPERATION_DELETE,
                $id
            );
        }

        return  response()->json([
            'success' => $success
        ]);
    }
}
