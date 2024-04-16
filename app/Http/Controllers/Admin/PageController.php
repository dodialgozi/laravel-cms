<?php

namespace App\Http\Controllers\Admin;

use DOMDocument;
use App\Models\Page;
use App\Http\Traits\Upload;
use Illuminate\Support\Str;
use App\Helpers\TranslateTextHelper;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\BaseController as Controller;

class PageController extends Controller
{
    use Upload;
    protected $title = 'Page';
    protected $modelClass = Page::class;
    protected $alias = 'p';
    protected $descKey = 'page_title_id';
    protected $column = ['p.*'];
    protected $view = 'backend.admin.partials.page';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $useDefaultShowView = false;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'page';
    protected $searchColumn = ['page_title_id' => '%LIKE%', 'page_title_en' => '%LIKE%'];
    protected $searchColumnField = ['title_id' => 'p.page_title_id', 'title_en' => 'p.page_title_en'];

    protected $selectFind = ['user_name'];
    protected $selectColumn = ['id', 'user_name As nama'];

    protected $permissionName = "page";

    protected function optionalQuery($db)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        return $db->where('instance_id', decodeId(getInstanceId()));
    }

    protected function changeInputSave($input)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['page_title_id'] = $input['page_title_id'];
        $input['page_slug_id'] = Str::slug($input['page_title_id'], '-');

        $input['page_title_en'] = !empty($input['page_auto_translate']) ? TranslateTextHelper::translate($input['page_title_id']) : $input['page_title_en'];
        $input['page_slug_en'] = Str::slug($input['page_title_en'], '-');

        if (!empty($input['page_content_id'])) {
            $input['page_content_id'] = $input['page_content_id'];
            $input['page_content_en'] = !empty($input['page_auto_translate']) ? TranslateTextHelper::translate($input['page_content_id']) : $input['page_content_en'];
        }

        $input['user_id']   = currentUserId();
        $input['instance_id'] = decodeId(getInstanceId());

        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'foto', 'page_thumbnail'))) {
            $input['page_thumbnail'] = $fileUpload;
        }

        unset($input['page_auto_translate']);
        unset($input['page_language']);

        return $input;
    }

    protected function changeInputSaveEdit($input, $model)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['page_title_id'] = $input['page_title_id'];
        $input['page_slug_id'] = Str::slug($input['page_title_id'], '-');

        $input['page_title_en'] = !empty($input['page_auto_translate']) ? TranslateTextHelper::translate($input['page_title_id']) : $input['page_title_en'];
        $input['page_slug_en'] = Str::slug($input['page_title_en'], '-');

        $input['instance_id'] = decodeId(getInstanceId());

        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'foto', 'page_thumbnail'))) {
            $input['page_thumbnail'] = $fileUpload;

            if (!empty($model->page_thumbnail)) {
                $this->deleteFile($model->page_thumbnail);
            }
        }

        // delete all image content if new image uploaded
        $oldContentId = $model->page_content_id;
        $newContentId = $input['page_content_id'];

        if ($oldContentId) {
            $dom = new DOMDocument();
            $dom->loadHTML($oldContentId);

            $imgTags = $dom->getElementsByTagName('img');

            if ($imgTags->length > 0) {
                foreach ($imgTags as $imgTag) {
                    $imageUrl = $imgTag->getAttribute('src');
                    if (Str::startsWith($imageUrl, asset('/'))) {
                        $filePath = Str::remove(asset('/'), $imageUrl);
                        if (!Str::contains($newContentId, $filePath)) {
                            $this->deleteFile($imageUrl);
                        }
                    }
                }
            }
        }

        if (empty($input['page_auto_translate']) && !empty($input['page_content_id'])) {
            $oldContentEn = $model->page_content_en;
            $newContentEn = $input['page_content_en'];

            $dom = new DOMDocument();
            $dom->loadHTML($oldContentEn);

            $imgTags = $dom->getElementsByTagName('img');

            if ($imgTags->length > 0) {
                foreach ($imgTags as $imgTag) {
                    $imageUrl = $imgTag->getAttribute('src');
                    if (Str::startsWith($imageUrl, asset('/'))) {
                        $filePath = Str::remove(asset('/'), $imageUrl);
                        if (!Str::contains($newContentEn, $filePath)) {
                            $this->deleteFile($imageUrl);
                        }
                    }
                }
            }
        } else {
            $input['page_content_en'] = TranslateTextHelper::translate($input['page_content_id']);
        }

        unset($input['page_auto_translate']);
        unset($input['page_language']);

        return $input;
    }

    protected function beforeSaveDelete($model)
    {
        if (empty(getInstanceId())) throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        if ($model->instance_id != decodeId(getInstanceId())) {
            throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        }

        if (!empty($model->page_thumbnail)) {
            $this->deleteFile($model->page_thumbnail);
        }

        // delete all image in content
        if ($model->page_content) {
            $dom = new DOMDocument();
            $dom->loadHTML($model->page_content);

            $imgTags = $dom->getElementsByTagName('img');

            if ($imgTags->length > 0) {
                foreach ($imgTags as $imgTag) {
                    $imageUrl = $imgTag->getAttribute('src');
                    $this->deleteFile($imageUrl);
                }
            }
        }
    }

    public function constructDataDetail($model)
    {
        $result = [
            'Judul' => $model->page_title,
            'Author' => $model->user->user_name ?? '-',
            'Meta Title' => $model->meta_title ?? '-',
            'Meta Keyword' => $model->meta_keyword ?? '-',
            'Meta Description' => $model->meta_description ?? '-',
            'Thumbnail' => [
                'type' => 'image',
                'value' => $model->page_thumbnail,
            ]
        ];

        return $result;
    }
}
