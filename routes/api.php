<?php

use App\Http\Controllers\BidController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TypeCreditController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// public routes
Route::post('/login', [AuthController::class, 'login']);

// protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/checkToken', [AuthController::class, 'checkToken']);
    Route::post('/users-add-new-for-dealer', [AuthController::class, 'addNewUserForDealer']);
    Route::get('/users-get-by-dealer/{id}', [AuthController::class, 'getUsersByDealer']);
    Route::put('/users-edit-user-for-dealer/{id}', [AuthController::class, 'editUserForDealer']);

    Route::post('/dealers-list', [DealerController::class, 'dealersList']);
    Route::post('/dealer-add-or-edit/{id}', [DealerController::class, 'addOrEdit']);
    Route::get('/dealers/get-data-by-id/{id}', [DealerController::class, 'getDataById']);
    Route::delete('/dealers/{id}', [DealerController::class, 'deleteDealer']);
    Route::get('/dealer-products/{id}', [DealerController::class, 'dealerProducts']);

    Route::post('/users-list', [UserController::class, 'usersList']);
    Route::post('/user-add-or-edit/{id}', [UserController::class, 'addOrEdit']);
    Route::get('/users/get-data-by-id/{id}', [UserController::class, 'getDataById']);
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);

    Route::post('/user/upload-avatar/{id}', [FileController::class, 'uploadUserAvatar']);
    Route::delete('/user/delete-avatar/{id}', [FileController::class, 'deleteUserAvatar']);
    Route::post('/files/uploadImage', [FileController::class, 'uploadImage']);
    Route::post('/files/uploadFile', [FileController::class, 'uploadFile']);
    Route::post('/files/uploadFileInBase64', [FileController::class, 'uploadFileInBase64']);
    Route::post('/files/getFiles', [FileController::class, 'getFiles']);
    Route::post('/files/linkFileTo', [FileController::class, 'linkFileTo']);
    Route::post('/files/deleteFile', [FileController::class, 'deleteFile']);

    Route::post('/products/add-or-edit/{id}', [TypeCreditController::class, 'addOrEditProduct']);
    Route::post('/products-list', [TypeCreditController::class, 'productsList']);
    Route::get('/products/get-data-by-id/{id}', [TypeCreditController::class, 'productsGetDataById']);
    Route::delete('/products/{id}', [TypeCreditController::class, 'productDelete']);

    Route::post('/type-credits/add-or-edit/{id}', [TypeCreditController::class, 'addOrEdit']);
    Route::post('/type-credits-list', [TypeCreditController::class, 'typeCreditsList']);
    Route::get('/type-credits/get-data-by-id/{id}', [TypeCreditController::class, 'getDataById']);
    Route::delete('/type-credits/{id}', [TypeCreditController::class, 'delete']);
    Route::post('/type-credits-calculate', [TypeCreditController::class, 'calculate']);

    Route::post('/bids/add-or-edit/{id}', [BidController::class, 'addOrEdit']);
    Route::post('/bids/get-list', [BidController::class, 'getList']);
    Route::delete('/bids/{id}', [BidController::class, 'delete']);
    Route::get('/bids/get-data-by-id/{id}', [BidController::class, 'getDataById']);
    Route::post('/bids/set-bid-status/{id}', [BidController::class, 'setBidStatus']);
    Route::post('/bid-change-sum/{id}', [BidController::class, 'changeSum']);
    Route::post('/bid-save-client-data/{id}', [BidController::class, 'bidSaveClientData']);
    Route::post('/update-scoring/{id}', [BidController::class, 'addOrEditScoring']);
    Route::get('/get-scoring/{id}', [BidController::class, 'getScoring']);

    Route::post('/send-chat-message', [ChatMessageController::class, 'sendChatMessage']);
    Route::post('/get-chat-messages', [ChatMessageController::class, 'getChatMessage']);
    Route::post('/set-chat-messages-read', [ChatMessageController::class, 'setChatMessagesRead']);
    Route::post('/checkNewMessages', [ChatMessageController::class, 'checkNewMessages']);


    Route::get('print/pre-contract/{id}', [PdfController::class, 'preContract']);
    Route::get('print/contract/{bidId}', [PdfController::class, 'contract']);
    Route::get('print/anexa/{bidId}', [PdfController::class, 'anexa']);
    Route::get('print/contractDealer/{dealer}', [PdfController::class, 'contractDealer']);
    Route::get('print/contractDealerAcord/{dealer}', [PdfController::class, 'contractDealerAcord']);
    Route::get('print/contractDealerConsimtamant/{dealer}', [PdfController::class, 'contractDealerConsimtamant']);

});
