<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImageUploadRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class ImageController extends AdminController
{
    /**
     * @OA\Post(
     *   path="/upload",
     *   security={{"bearerAuth":{}}},
     *   tags={"Images"},
     *   @OA\Response(response="200",
     *     description="Upload Images",
     *   )
     * )
     */
    #[ArrayShape(['url' => "string"])] public function upload(ImageUploadRequest $request): array
    {
        $file = $request->file('image');
        $name = Str::random(10);
        $url = Storage::putFileAs(
            'images',
            $file,
            $name . '.' . $file->extension()
        );

        return ['url' => Storage::url($url)];
    }
}
