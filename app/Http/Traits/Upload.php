<?php

namespace App\Http\Traits;

use GoDrive;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait Upload
{
    protected function uploadFile($name, $folderChild = null, $index = null, $resize = false, $thumbW = null, $thumbH = null)
    {
        if (env('APP_GOOGLE_DRIVE_UPLOAD', false)) {
            return $this->uploadGoDrive($name, folderChild: $folderChild, index: $index, resize: $resize, thumbW: $thumbW, thumbH: $thumbH);
        } else if (env('APP_S3_UPLOAD', false)) {
            return $this->uploadS3($name, folderChild: $folderChild, index: $index, resize: $resize, thumbW: $thumbW, thumbH: $thumbH);
        } else {
            return $this->uploadPublic($name, folderChild: $folderChild, index: $index, resize: $resize, thumbW: $thumbW, thumbH: $thumbH);
        }
    }

    protected function uploadPublic($name, $folderParent = 'upload', $folderChild = null, $index = null, $originalName = false, $resize = false, $thumbW = 150, $thumbH = 150)
    {
        $fileUpload = $name;

        if (request()->hasFile($name)) {
            $fileUpload = request()->file($name);
            if (!empty($index)) $fileUpload = $fileUpload[$index] ?? null;
        }

        if (!is_object($fileUpload) && !empty($index)) {
            $fileUpload = $fileUpload[$index] ?? null;
        }

        if (empty($fileUpload)) return null;

        if ($resize) $fileUpload = $this->resizeImage($fileUpload, $thumbW, $thumbH);

        if ($fileUpload instanceof UploadedFile && $fileUpload->isValid()) {
            $fileName = $fileUpload->getClientOriginalName();
            if (!$originalName) {
                $random = randomGen2(12);
                $name = $this->filenameWithoutExtension($fileName);
                $fileName = "{$name}-{$random}.{$fileUpload->getClientOriginalExtension()}";
            }

            $folder = $folderParent;
            if (!empty($folderChild)) $folder .= "/{$folderChild}";

            $fileUpload->move(public_path($folder), $fileName);

            return asset("{$folder}/{$fileName}");
        }

        return null;
    }

    protected function uploadGoDrive($name, $folderParent = 'LancangKuningV2', $folderChild = null, $index = null, $withExt = true,  $resize = false, $thumbW = 150, $thumbH = 150)
    {
        if (request()->hasFile($name)) {
            $fileUpload = request()->file($name);
            if (!empty($index)) $fileUpload = $fileUpload[$index] ?? null;
            if (empty($fileUpload)) return null;

            if ($resize) $fileUpload = $this->resizeImage($fileUpload, $thumbW, $thumbH);

            if ($fileUpload->isValid()) {
                $fileName = $fileUpload->getClientOriginalName();
                if (!$withExt) $fileName = $this->filenameWithoutExtension($fileName);

                $gd = new GoDrive();
                $folder = $gd->isDirectoryExists($folderParent);
                if (!empty($folderChild)) $folder = $gd->isDirectoryExists($folderChild, $folder->getId());

                $parent = $folder->getId();
                $uploadedFile = $gd->uploadFile($fileUpload->getPathname(), $fileName, $parent, 'public');
                if (!empty($uploadedFile->id)) {
                    return "https://drive.google.com/uc?id=" . $uploadedFile->id;
                }
            }
        }
        return null;
    }

    protected function uploadS3($name, $folderParent = 'LancangKuningV2', $folderChild = null, $index = null, $withExt = true,  $resize = false, $thumbW = 150, $thumbH = 150)
    {
        if (request()->hasFile($name)) {
            $fileUpload = request()->file($name);
            if (!empty($index)) $fileUpload = $fileUpload[$index] ?? null;
            if (empty($fileUpload)) return null;

            if ($resize) $fileUpload = $this->resizeImage($fileUpload, $thumbW, $thumbH);

            if ($fileUpload->isValid()) {
                $fileName = $fileUpload->getClientOriginalName();
                if (!$withExt) $fileName = $this->filenameWithoutExtension($fileName);

                // $gd = new GoDrive();
                // $folder = $gd->isDirectoryExists($folderParent);
                // if (!empty($folderChild)) $folder = $gd->isDirectoryExists($folderChild, $folder->getId());

                // $parent = $folder->getId();
                // $uploadedFile = $gd->uploadFile($fileUpload->getPathname(), $fileName, $parent, 'public');
                // if (!empty($uploadedFile->id)) {
                //     return "https://drive.google.com/uc?id=" . $uploadedFile->id;
                // }

                $uploaded = Storage::disk('s3')->put($folderParent, $fileUpload);

                if ($uploaded) {
                    return Storage::disk('s3')->url($uploaded);
                }
            }
        }
        return null;
    }

    protected function deleteFile($url)
    {
        // TODO: delete S3
        $goId = goDriveId($url);
        if (!empty($goId)) {
            return $this->deleteGoDriveId($goId);
        }

        return $this->deletePublic($url);
    }

    protected function deletePublic($url)
    {
        if (Str::startsWith($url, asset('/'))) {
            try {
                $filePath = Str::remove(asset('/'), $url);
                unlink(public_path($filePath));

                return true;
            } catch (Exception $ex) {
            }
        }

        return false;
    }

    protected function deleteGoDrive($url)
    {
        $goId = goDriveId($url);
        if (!empty($goId)) {
            return $this->deleteGoDriveId($goId);
        }
        return false;
    }

    protected function deleteGoDriveId($id)
    {
        try {
            $gd = new GoDrive();
            $gd->removeFile($id);

            return true;
        } catch (Exception $ex) {
        }
        return false;
    }

    private function filenameWithoutExtension($filename)
    {
        preg_match('{(?<filename>.*)\.(?<extension>.*)}', $filename, $data);
        return $data['filename'] ?? $filename;
    }

    protected function resizeImage($image, $width, $height)
    {
        $resizedImage = Image::make($image)->resize($width, $height);

        $tempImagePath = tempnam(sys_get_temp_dir(), 'resized_image_') . '.jpg';
        $resizedImage->stream('jpg');
        $stream = fopen($tempImagePath, 'w+');
        fwrite($stream, $resizedImage);
        fclose($stream);

        $size = getimagesize($tempImagePath);

        try {
            $originalName = "{$this->filenameWithoutExtension($image->getClientOriginalName())}-{$size[0]}x{$size[1]}";
        } catch (\Throwable $th) {
            $originalName = randomGen(12) . "-{$size[0]}x{$size[1]}";
        }

        $resizedImageExtension = 'jpg';

        $resizedImageFile = new UploadedFile($tempImagePath, $originalName . '.' . $resizedImageExtension, null, null, true);

        return $resizedImageFile;
    }
}
