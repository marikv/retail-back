<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Dealer;
use App\Models\Bid;
use App\Models\File;
use App\Models\Log;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class FileController extends Controller
{

    /**
     * @param string $extension
     * @param string $path
     * @param $size
     * @param string $path_mini
     * @param string $name
     * @param string|null $getMimeType
     * @return File
     */
    private static function resizeImagesAndAddToBd(string $extension, string $path, $size, string $path_mini, string $name, ?string $getMimeType): File
    {
        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'bmp', 'gif']);
        $wasResized = false;

        /* 1mb = 1000000 */
        if (/*$size > 500000 &&*/ $isImage) {
            $img = Image::make($path);
            if ($img && $img->width() > 1100) {
                Image::make($path)->resize(1100, null, static function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 90);
                $wasResized = true;
            }
            $img = Image::make($path);
            if ($img && $img->height() > 1500) {
                Image::make($path)->resize(null, 1500, static function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 90);
                $wasResized = true;
            }
            $img = Image::make($path);
            $size = $img->filesize();
        }
        if ($isImage && !$wasResized) {
            Image::make($path)->save($path, 90);
        }

        if ($isImage) {
            $img = Image::make($path);
            if ($img && $img->height() > 160) {
                Image::make($path)->resize(null, 160, static function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path_mini, 90);
            } else {
                Image::make($path)->save($path_mini, 90);
            }
            $img = Image::make($path_mini);
            if ($img && $img->width() > 210) {
                Image::make($path_mini)->resize(210, null, static function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path_mini, 90);
            } else {
                Image::make($path_mini)->save($path_mini, 90);
            }
        }

        $fileModel = new File();
        $fileModel->name = $name;
        $fileModel->size = $size;
        $fileModel->mimetype = $getMimeType;
        $fileModel->extension = $extension;
        $fileModel->path = $path;
        $fileModel->web_path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
        $fileModel->added_by_user_id = Auth::user()->id;
        $fileModel->save();
        return $fileModel;
    }

    /**
     * @return string
     */
    public function getDestinationPath(): string
    {
        $path = 'uploads' . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d');
        $destinationPath = public_path($path);
        if (!is_dir($destinationPath) && !mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
        }
        return $path;
    }

    private function _upload(Request $request, $nameFnc)
    {
        $fileModel = $this->_uploadAndReturnFileModel($request, $nameFnc);
        if ($fileModel) {
            Log::addNewLog(
                $request,
                Log::MODULE_FILES,
                Log::OPERATION_ADD,
                $fileModel->id,
                $fileModel->name
            );

            return response([
                'success' => true,
                'data' => [
                    'web_path' => $fileModel->web_path,
                    'id' => $fileModel->id,
                    'name' => $fileModel->name,
                    'type_id' => $fileModel->type_id,
                ],
            ]);
        }
        return response([
            'success' => true,
        ]);
    }

    public function uploadFile(Request $request)
    {
        return $this->_upload($request, 'fileForUpload');
    }

    public function uploadImage(Request $request)
    {
        return $this->_upload($request, 'image') ;
    }

    public function deleteUserAvatar($id, Request $request)
    {

        $data = [];
        $success = false;

        if ($id > 0) {
            $userModel = User::find($id);
            $avatar = $userModel->avatar;
            $file_id = null;
            if ($avatar) {
                $File = File::where('web_path', '=', $avatar)->first();
                if ($File) {
                    $file_id = $File->id;
                    $this->deleteFileById($file_id);
                }
            }
            $userModel->avatar = null;
            $userModel->save();
            $success = true;

            Log::addNewLog(
                $request,
                Log::MODULE_FILES,
                Log::OPERATION_DELETE,
                $file_id
            );
        }
        return response(['success' => $success, 'data' => $data]);
    }

    public function uploadUserAvatar($id, Request $request)
    {
        $data = [];
        $success = false;

        $fileModel = $this->_uploadAndReturnFileModel($request, 'avatar');
        if ($fileModel) {
            Log::addNewLog(
                $request,
                Log::MODULE_FILES,
                Log::OPERATION_ADD,
                $fileModel->id,
                'avatar ' . $fileModel->name
            );
            $fileModel->user_id = $id;
            $fileModel->save();

            if ($id > 0) {
                $User = User::find($id);
                if ($User) {
                    $User->avatar = $fileModel->web_path;
                    $User->save();
                }
            }

            $data = [
                'web_path' => $fileModel->web_path,
                'id' => $fileModel->id,
            ];
            $success = true;
        }

        return response(['success' => $success, 'data' => $data]);
    }

    public function getFiles(Request $request)
    {

        $files = DB::table('files') ->select(
            "files.*",
            DB::raw("DATE_FORMAT(files.created_at, '%d.%m.%Y %H:%i') as created_at2")
        );
        $files = $files->whereNull('deleted');
        if (!empty($request->client_id)) {
            $files = $files->where('client_id', '=', $request->client_id);
        }
        if (!empty($request->user_id)) {
            $files = $files->where('user_id', '=', $request->user_id);
        }
        if (!empty($request->dealer_id)) {
            $files = $files->where('dealer_id', '=', $request->dealer_id);
        }
        if (!empty($request->payment_id)) {
            $files = $files->where('payment_id', '=', $request->payment_id);
        }
        if (!empty($request->bid_id)) {
            $files = $files->where('bid_id', '=', $request->bid_id);
        }
        $files = $files->orderBy('id', 'desc' );
        $files = $files->paginate(999999999);


        return response([
            'success' => true,
            'data' => $files
        ]);
    }

    public function deleteFile (Request $request)
    {
        $v = $request->validate([
            'id' => 'required'
        ]);

        $id = $request->id;

        $fileModel = $this->deleteFileById($id);

        $Log_desc = $fileModel->id.' '.$fileModel->name.'. ';

        $success = true;
        if ($success) {
            Log::addNewLog(
                $request,
                Log::MODULE_FILES,
                Log::OPERATION_DELETE,
                $id,
                $Log_desc
            );
        }

        return  response([
            'success' => $success
        ]);
    }

    public function linkFileTo(Request $request): void
    {
        try{
            $fileModel = File::findOrFail($request->file_id);
            if (!empty($request->type_id)) {
                $fileModel->type_id = $request->type_id;
            }

            if (!empty($request->user_id)) {
                $fileModel->user_id = $request->user_id;
                if (!empty($request->makeAvatar)) {
                    $fileModel->type_id = File::FILE_TYPE_AVATAR;
                    /* @var $User User */
                    $User = User::find($request->user_id);
                    if ($User) {
                        $User->avatar = $fileModel->web_path;
                        $User->save();
                    }
                }
            }
            if (!empty($request->client_id)) {
                $fileModel->client_id = $request->client_id;
            }
            if (!empty($request->dealer_id)) {
                $fileModel->dealer_id = $request->dealer_id;

                if (!empty($request->makeAvatar)) {
                    $fileModel->type_id = File::FILE_TYPE_LOGO;
                    /* @var $Dealer Dealer */
                    $Dealer = Dealer::find($request->dealer_id);
                    if ($Dealer) {
                        $Dealer->logo = $fileModel->web_path;
                        $Dealer->save();
                    }
                }
            }
            if (!empty($request->payment_id)) {
                $fileModel->payment_id = $request->payment_id;
            }
            if (!empty($request->bid_id)) {
                $fileModel->bid_id = $request->bid_id;
            }
            if (!empty($request->add_to_chat) && !empty($request->bid_id)) {
                ChatMessage::sendNewMessage(null, null, $request->bid_id, $fileModel->id);
            }
            else if (!empty($request->add_to_chat) && !empty($request->user_id)) {
                ChatMessage::sendNewMessage($request->user_id, null, null, $fileModel->id);
            }
            $fileModel->save();
        }catch (\Exception $e) {}
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function uploadFileInBase64(Request $request)
    {
        try{
            if ($request->base64 && $request->size && $request->name) {

                $base64 = $request->base64;
                if (strpos($base64, ';base64,') !== false) {
                    $base64Arr = explode(';base64,', $base64);
                    if (!empty($base64Arr[1])) {
                        $base64 = $base64Arr[1];
                    }
                }

                $mimeType = $request->mimeType;
                $size = $request->size;
                $name = $request->name;
                $n = strrpos($name,".");
                $extension = ($n === false) ? "" : substr($name,$n + 1);

                $destinationPath = $this->getDestinationPath();
                $newBaseName = uniqid(date('YmdHis') . '_', true);
                $newFileName = $newBaseName . '.' . $extension;
                $newFileName_mini = $newBaseName . '_mini.' . $extension;
                $path = $destinationPath . DIRECTORY_SEPARATOR . $newFileName;
                $path_mini = $destinationPath . DIRECTORY_SEPARATOR . $newFileName_mini;

                file_put_contents($path, base64_decode($base64), FILE_APPEND);
                $fileModel = self::resizeImagesAndAddToBd($extension, $path, $size, $path_mini, $name, $mimeType);

                try{
                    $linkFileTo = (array)$request->linkFileTo;
                    if (!empty($linkFileTo['client_id'])) {
                        $fileModel->client_id = $linkFileTo['client_id'];
                    }
                    if (!empty($linkFileTo['user_id'])) {
                        $fileModel->user_id = $linkFileTo['user_id'];
                    }
                    if (!empty($linkFileTo['dealer_id'])) {
                        $fileModel->dealer_id = $linkFileTo['dealer_id'];
                    }
                    if (!empty($linkFileTo['bid_id'])) {
                        $fileModel->bid_id = $linkFileTo['bid_id'];
                    }
                    $fileModel->save();
                }catch (\Exception $e) {}
            }
        }catch (\Exception $e) {
            return response([
                'success' => true,
                'data' => $e->getMessage(),
            ]);
        }

        return response([
            'success' => true,
            'data' => '',
        ]);
    }

    /**
     * @param Request $request
     * @param $nameFnc
     * @return File|null
     */
    private function _uploadAndReturnFileModel(Request $request, $nameFnc): ?File
    {
        $file = $request->file($nameFnc);
        if ($file) {

            $name = $file->getClientOriginalName();
            $size = $file->getSize();
            $getMimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();

            $destinationPath = $this->getDestinationPath();
            $newBaseName = uniqid(date('YmdHis') . '_', true);
            $newFileName = $newBaseName . '.' . $extension;
            $newFileName_mini = $newBaseName . '_mini.' . $extension;
            $path = $destinationPath . DIRECTORY_SEPARATOR . $newFileName;
            $path_mini = $destinationPath . DIRECTORY_SEPARATOR . $newFileName_mini;

            $uploaded = $file->move($destinationPath, $newFileName);

            if ($uploaded) {
                return self::resizeImagesAndAddToBd($extension, $path, $size, $path_mini, $name, $getMimeType);
            }
        }
        return null;
    }

    /**
     * @param $id
     * @return File
     */
    private function deleteFileById($id): File
    {
        $entity = File::findOrFail($id);
        $entity->deleted = 1;
        $entity->save();

        $photo_url = $entity->web_path;
        if ($photo_url) {
            $public = public_path($photo_url);
            $ext = pathinfo($public, PATHINFO_EXTENSION);
            $miniPhoto = rtrim($public, '.' . $ext) . '_mini.' . $ext;
            if (file_exists($miniPhoto)) {
                @unlink($miniPhoto);
            }
            if (file_exists($public)) {
                @unlink($public);
            }
        }
        return $entity;
    }
}
