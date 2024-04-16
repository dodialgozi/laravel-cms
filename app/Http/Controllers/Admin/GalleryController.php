<?php

namespace App\Http\Controllers\Admin;

use DOMDocument;
use App\Models\Gallery;
use App\Http\Traits\Upload;
use Illuminate\Support\Str;
use App\Helpers\TranslateTextHelper;
use App\Http\Controllers\BaseController as Controller;
use App\Http\Requests\GalleryRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GalleryController extends Controller
{
    use Upload;
    protected $title = 'Gallery';
    protected $modelClass = Gallery::class;
    protected $alias = 'g';
    protected $descKey = 'gallery_title_id';
    protected $column = ['g.*'];
    protected $view = 'backend.admin.partials.gallery';
    protected $useDefaultAddView = true;
    protected $useDefaultEditView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'gallery';
    protected $searchColumn = ['title' => '%LIKE%'];
    protected $searchColumnField = ['title' => 'gallery_title_id'];

    protected $sortColumn = ['title'];
    protected $sortColumnField = ['title' => 'gallery_title_id'];

    protected $permissionName = "gallery";

    protected $formRequest = GalleryRequest::class;

    protected function optionalQuery($db)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        return $db->where('instance_id', decodeId(getInstanceId()));
    }

    protected function changeInputSave($input)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['gallery_title_en'] = !empty($input['gallery_auto_translate']) ? TranslateTextHelper::translate($input['gallery_title_id']) : $input['gallery_title_en'];
        $input['gallery_slug_id'] = Str::slug($input['gallery_title_id'], '-');
        $input['gallery_slug_en'] = Str::slug($input['gallery_title_en'], '-');

        if (!empty($input['gallery_description_id'])) {
            $input['gallery_description_en'] = !empty($input['gallery_auto_translate']) ? TranslateTextHelper::translate($input['gallery_description_id']) : $input['gallery_description_en'];
        }

        $input['instance_id'] = decodeId(getInstanceId());
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'gallery', 'gallery_image'))) {
            $input['gallery_image'] = $fileUpload;
        }

        unset($input['gallery_auto_translate']);
        unset($input['gallery_images']);
        unset($input['gallery_language']);

        return $input;
    }
    protected function afterSave($input, $id)
    {
        $images = request()->input('gallery_images');
        $images = json_decode($images);
        foreach ($images as $image) {
            $galleryImage = Gallery::find($id);
            $galleryImage->images()->create([
                'image' => $image->path,
                'size' => $image->size,
            ]);
        }
    }

    protected function changeInputSaveEdit($input, $model)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['gallery_title_en'] = !empty($input['gallery_auto_translate']) ? TranslateTextHelper::translate($input['gallery_title_id']) : $input['gallery_title_en'];
        $input['gallery_slug_id'] = Str::slug($input['gallery_title_id'], '-');
        $input['gallery_slug_en'] = Str::slug($input['gallery_title_en'], '-');

        $oldDescriptionId = $model->gallery_description_id;
        $newDescriptionId = $input['gallery_description_id'];

        if (!empty($oldDescriptionId)) {
            $dom = new DOMDocument();
            $dom->loadHTML($oldDescriptionId);
            $imgTags = $dom->getElementsByTagName('img');

            if ($imgTags->length > 0) {
                foreach ($imgTags as $imgTag) {
                    $imageUrl = $imgTag->getAttribute('src');
                    if (Str::startsWith($imageUrl, asset('/'))) {
                        $filePath = Str::remove(asset('/'), $imageUrl);
                        if (!Str::contains($newDescriptionId, $filePath)) {
                            $this->deleteFile($imageUrl);
                        }
                    }
                }
            }

            $input['gallery_description_en'] = !empty($input['gallery_auto_translate']) ? TranslateTextHelper::translate($input['gallery_description_id']) : $input['gallery_description_en'];
        }

        $input['instance_id'] = decodeId(getInstanceId());
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'gallery', 'gallery_image'))) {
            $input['gallery_image'] = $fileUpload;
        }

        unset($input['gallery_auto_translate']);
        unset($input['gallery_images']);
        unset($input['gallery_language']);

        return $input;
    }

    protected function afterSaveEdit($input, $id)
    {
        $images = request()->input('gallery_images');
        $images = json_decode($images);
        // remove old images
        $oldImages = Gallery::find($id)->images;
        foreach ($oldImages as $oldImage) {
            $oldImage->delete();
        }
        // add new images
        foreach ($images as $image) {
            $galleryImage = Gallery::find($id);
            $galleryImage->images()->create([
                'image' => $image->path,
                'size' => $image->size,
            ]);
        }
    }

    protected function beforeSaveDelete($oldData)
    {
        if (empty(getInstanceId())) throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        if ($oldData->instance_id != decodeId(getInstanceId())) {
            throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        }

        if (!empty($oldData->gallery_image)) {
            $this->deleteFile($oldData->gallery_image);
        }
    }
}
