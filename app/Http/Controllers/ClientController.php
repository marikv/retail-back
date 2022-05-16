<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepository;
use App\Repositories\DealerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function clientsList(Request $request, ClientRepository $clientRepository): JsonResponse
    {
        $filter = $request->filter ?? null;
        $pagination = $request->pagination ?? null;
        $options = [
            'column' => $request->column ?? null,
        ];

        return response()->json([
            'success' => true,
            'data' => $clientRepository->list($filter, $pagination, $options)
        ]);
    }
}
