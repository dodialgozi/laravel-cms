<?php

namespace App\Http\Controllers\Admin;

use DOMDocument;
use App\Models\CustomPost;
use Soundasleep\Html2Text;
use App\Http\Traits\Upload;
use Illuminate\Support\Str;
use App\Helpers\TranslateTextHelper;
use App\Http\Requests\CustomPostRequest;
use App\Models\CustomPostCategoryRelation;
use App\Http\Controllers\BaseCustomPostController;

class CustomPostController extends BaseCustomPostController
{
    use Upload;
    protected $title = 'Custom Post';
    protected $modelClass = CustomPost::class;
    protected $alias = 'p';
    protected $descKey = 'post_title_id';
    protected $column = ['p.*'];
    protected $view = 'backend.admin.partials.custom-post';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $useDefaultShowView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'custom-post';
    protected $searchColumn = ['title_id' => '%LIKE%', 'title_en' => '%LIKE%', 'date' => '%LIKE%', 'status' => '='];
    protected $searchColumnField = ['title_id' => 'post_title_id', 'title_en' => 'post_title_en', 'date' => 'post_date', 'status' => 'post_status'];

    protected $selectFind = ['title_id', 'title_en', 'post_date', 'post_status'];
    protected $selectColumn = ['post_id as id', 'post_title_id as title_id', 'post_title_en as title_en', 'post_date as date', 'post_status as status'];

    protected $sortColumn = ['title_id', 'title_en', 'author', 'date', 'status'];
    protected $sortColumnField = ['title_id' => 'post_title_id', 'title_en' => 'post_title_en', 'author' => 'user_id', 'date' => 'post_date', 'status' => 'post_status'];

    protected $permissionName = "custom-post";

    protected $formRequest = CustomPostRequest::class;

    protected function optionalQuery($db)
    {
        return $db
            ->join('custom_post_type', 'custom_post_type.post_type_id', '=', 'p.post_type_id')
            ->where('p.instance_id', decodeId(getInstanceId()))
            ->where('post_type_code', $this->type);
    }


    protected function moreDataIndex($parent_id)
    {
        $status = [
            'draft' => 'Draft',
            'submit' => 'Submit',
            'publish' => 'Publish',
            'schedule' => 'Terjadwal'
        ];

        return [
            'status' => $status
        ];
    }

    protected function moreDataForm()
    {
        $status = [
            'draft' => 'Draft',
            'submit' => 'Submit',
            'publish' => 'Publish',
            'schedule' => 'Jadwalkan'
        ];

        if (userCan("{$this->permissionName}.{$this->type}.schedule")) {
            $status['schedule'] = 'Jadwalkan';
        }

        if (userCan("{$this->permissionName}.{$this->type}.publish")) {
            $status['publish'] = 'Publish';
        }

        return [
            'status' => $status,
            'postType' => $this->customPostType
        ];
    }

    protected function changeInputSave($parent_id, $input)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        // set slug and user_id
        $input['post_title_id'] = $input['post_title_id'];
        $input['post_slug_id'] = Str::slug($input['post_title_id'], '-');

        $input['post_title_en'] = !empty($input['post_auto_translate']) ? TranslateTextHelper::translate($input['post_title_id']) : $input['post_title_en'];
        $input['post_slug_en'] = Str::slug($input['post_title_en'], '-');

        $imageUrl = null;

        $input['post_type_id'] = $this->customPostType->post_type_id ?? 0;
        if (!empty($input['post_content_id'])) {
            // get first image from content
            $dom = new DOMDocument();
            $dom->loadHTML($input['post_content_id']);

            $imgTags = $dom->getElementsByTagName('img');

            if ($imgTags->length > 0) {
                $firstImgTag = $imgTags[0];
                $imageUrl = $firstImgTag->getAttribute('src');
            }

            // get excerpt from content
            $excerpt = \Soundasleep\Html2Text::convert($input['post_content_id']);
            $excerpt = preg_replace('/\s+/', ' ', $excerpt);
            $excerpt = Str::words($excerpt, 100, '');
            $input['post_excerpt_id'] = $excerpt;

            if (!empty($input['post_auto_translate'])) {
                $input['post_content_en'] = TranslateTextHelper::translate($input['post_content_id']);
                $excerpt = \Soundasleep\Html2Text::convert($input['post_content_en']);
                $excerpt = preg_replace('/\s+/', ' ', $excerpt);
                $excerpt = Str::words($excerpt, 100, '');
                $input['post_excerpt_en'] = $excerpt;
            }
        }

        if (empty($input['post_auto_translate']) && !empty($input['post_title_en'])) {
            // get first image from content
            $dom = new DOMDocument();
            $dom->loadHTML($input['post_content_en']);

            $imgTags = $dom->getElementsByTagName('img');

            if ($imgTags->length > 0) {
                $firstImgTag = $imgTags[0];
                $imageUrl = $firstImgTag->getAttribute('src');
            }

            // get excerpt from content
            $excerpt = \Soundasleep\Html2Text::convert($input['post_content_en']);
            $excerpt = preg_replace('/\s+/', ' ', $excerpt);
            $excerpt = Str::words($excerpt, 100, '');
            $input['post_excerpt_en'] = $excerpt;
        }

        // upload image (first_image, medium_thumbnail, thumbnail)
        $uploadFile = function ($inputName, $folder, $field, $resize = false, $width = null, $height = null) use (&$input, $imageUrl) {
            $fileUpload = $this->uploadFile($inputName, $folder, $field, $resize, $width, $height);

            if (!$fileUpload) {
                $input[$field] = $imageUrl;
                $fileUpload = $this->uploadFile($input, $folder, $field, $resize, $width, $height);
            }

            $input[$field] = $fileUpload ?: $imageUrl;
        };

        $uploadFile($this->getInputFile(), 'post', 'first_image');
        $uploadFile($this->getInputFile(), 'post', 'medium_thumbnail', true, 300, 180);
        $uploadFile($this->getInputFile(), 'post', 'thumbnail', true, 150, 150);

        $input['user_id'] = auth()->user()->user_id;

        // set publish date
        if ($input['post_status'] == 'publish') {
            $input['post_date'] = date('Y-m-d H:i:s');
        } elseif ($input['post_status'] == 'draft') {
            $input['post_date'] = null;
        } else {
            $publish_date = request()->input('publish_date');
            $publish_time = request()->input('publish_time');

            $input['post_date'] = date('Y-m-d H:i:s', strtotime($publish_date . ' ' . $publish_time));
        }

        $input['instance_id'] = decodeId(getInstanceId());

        unset($input['post_auto_translate']);
        unset($input['post_language']);

        return $input;
    }

    protected function afterSave($input, $id)
    {
        // save post category
        if (request()->has('categories')) {
            foreach (request()->input('categories') as $category) {
                CustomPostCategoryRelation::create([
                    'post_id' => $id,
                    'category_id' => $category
                ]);
            }
        }
    }

    protected function moreDataEdit($result, $parent_id)
    {
        $post_categories = CustomPostCategoryRelation::where('post_id', $result->post_id)->get();
        $post_date = $result->post_date ? date('Y-m-d', strtotime($result->post_date)) : null;
        $post_time = $result->post_date ? date('H:i', strtotime($result->post_date)) : null;

        return [
            'post_categories' => $post_categories,
            'post_date' => $post_date,
            'post_time' => $post_time,
        ];
    }

    protected function changeInputSaveEdit($parent_id, $input, $oldData)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $imageUrl = null;
        $input['post_title_id'] = $input['post_title'];
        $input['post_slug_id'] = Str::slug($input['post_title'], '-');

        $input['post_title_en'] = !empty($input['post_auto_translate']) ? TranslateTextHelper::translate($input['post_title']) : $input['post_title_en'];
        $input['post_slug_en'] = Str::slug($input['post_title_en'], '-');

        if (!empty($input['post_content_id'])) {
            // get first image from content
            $dom = new DOMDocument();
            $dom->loadHTML($input['post_content_id']);

            $imgTags = $dom->getElementsByTagName('img');

            if ($imgTags->length > 0) {
                $firstImgTag = $imgTags[0];
                $imageUrl = $firstImgTag->getAttribute('src');
            }

            // get excerpt from content
            $excerpt = \Soundasleep\Html2Text::convert($input['post_content_id']);
            $excerpt = preg_replace('/\s+/', ' ', $excerpt);
            $excerpt = Str::words($excerpt, 100, '');
            $input['post_excerpt_id'] = $excerpt;

            if (!empty($input['post_auto_translate'])) {
                $input['post_content_en'] = TranslateTextHelper::translate($input['post_content_id']);
                $excerpt = \Soundasleep\Html2Text::convert($input['post_content_en']);
                $excerpt = preg_replace('/\s+/', ' ', $excerpt);
                $excerpt = Str::words($excerpt, 100, '');
                $input['post_excerpt_en'] = $excerpt;
            }
        }

        if (empty($input['post_auto_translate']) && !empty($input['post_title_en'])) {
            // get first image from content
            $dom = new DOMDocument();
            $dom->loadHTML($input['post_content_en']);

            $imgTags = $dom->getElementsByTagName('img');

            if ($imgTags->length > 0) {
                $firstImgTag = $imgTags[0];
                $imageUrl = $firstImgTag->getAttribute('src');
            }

            // get excerpt from content
            $excerpt = \Soundasleep\Html2Text::convert($input['post_content_en']);
            $excerpt = preg_replace('/\s+/', ' ', $excerpt);
            $excerpt = Str::words($excerpt, 100, '');
            $input['post_excerpt_en'] = $excerpt;
        }

        // upload image (first_image, medium_thumbnail, thumbnail)
        $uploadFile = function ($inputName, $folder, $field, $resize = false, $width = null, $height = null) use (&$input, $imageUrl) {
            $fileUpload = $this->uploadFile($inputName, $folder, $field, $resize, $width, $height);

            if (!$fileUpload) {
                $fileUpload = $this->uploadFile($input, $folder, $field, $resize, $width, $height);
            }

            $input[$field] = $fileUpload ?: $imageUrl;
        };

        $imageFields = [
            'first_image' => ['resize' => false, 'width' => null, 'height' => null],
            'medium_thumbnail' => ['resize' => true, 'width' => 300, 'height' => 180],
            'thumbnail' => ['resize' => true, 'width' => 150, 'height' => 150]
        ];

        // delete old image if new image uploaded
        foreach ($imageFields as $imageField => $resizeData) {
            $resize = $resizeData['resize'];
            $width = $resizeData['width'];
            $height = $resizeData['height'];

            if (request()->has($this->getInputFile())) {
                $uploadedFile = request()->file($this->getInputFile())[$imageField] ?? null;
                if (!empty($uploadedFile)) {
                    $uploadFile($this->getInputFile(), 'post', $imageField, $resize, $width, $height);
                    if (!empty($input[$imageField])) {
                        $this->deleteFile($oldData->$imageField);
                    }
                }
            } else {
                if (empty($oldData->$imageField)) {
                    $input[$imageField] = $imageUrl;
                    $uploadFile($input, 'post', $imageField, $resize, $width, $height);
                }
            }
        }

        // delete all image content if new image uploaded
        $oldContent = $oldData->post_content;
        $newContent = $input['post_content'];

        if ($oldContent) {
            $dom = new DOMDocument();
            $dom->loadHTML($oldContent);

            $imgTags = $dom->getElementsByTagName('img');

            if ($imgTags->length > 0) {
                foreach ($imgTags as $imgTag) {
                    $imageUrl = $imgTag->getAttribute('src');
                    if (Str::startsWith($imageUrl, asset('/'))) {
                        $filePath = Str::remove(asset('/'), $imageUrl);
                        if (!Str::contains($newContent, $filePath)) {
                            $this->deleteFile($imageUrl);
                        }
                    }
                }
            }
        }

        // set publish date
        if ($input['post_status'] == 'publish' && $oldData->post_status != 'publish') {
            $input['post_date'] = date('Y-m-d H:i:s');
        } elseif ($input['post_status'] == 'draft') {
            $input['post_date'] = null;
        } else {
            $publish_date = request()->input('publish_date');
            $publish_time = request()->input('publish_time');

            $input['post_date'] = date('Y-m-d H:i:s', strtotime($publish_date . ' ' . $publish_time));
        }

        $input['instance_id'] = decodeId(getInstanceId());

        unset($input['post_auto_translate']);
        unset($input['post_language']);

        return $input;
    }

    protected function afterSaveEdit($input, $id)
    {
        // save post category
        if (request()->has('categories')) {
            CustomPostCategoryRelation::where('post_id', $id)->delete();

            foreach (request()->input('categories') as $category) {
                CustomPostCategoryRelation::create([
                    'post_id' => $id,
                    'category_id' => $category
                ]);
            }
        }
    }

    protected function beforeSaveDelete($oldData)
    {
        // delete all categories
        CustomPostCategoryRelation::where('post_id', $oldData->post_id)->delete();

        // delete all input image
        $imageFieldsToDelete = ['first_image', 'medium_thumbnail', 'thumbnail'];

        foreach ($imageFieldsToDelete as $imageField) {
            if (!empty($oldData->$imageField)) {
                $this->deleteFile($oldData->$imageField);
            }
        }

        // delete all image in content
        if ($oldData->post_content) {
            $dom = new DOMDocument();
            $dom->loadHTML($oldData->post_content);

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
        $categories = $model->categories;
        if ($categories->isNotEmpty()) {
            $categories = $categories->map(function ($category) {
                return $category->category->category_name_id;
            })->implode(', ');
        } else {
            $categories = '-';
        }

        $excerpt = '-';
        if (!empty($model->post_excerpt)) {
            $excerpt = $model->post_excerpt;
        }

        $result = [
            'Judul (ID)' => $model->post_title_id ?? '-',
            'Judul (EN)' => $model->post_title_en ?? '-',
            'Post Type' => $model->post_type ?? '-',
            'Meta Title' => $model->meta_title ?? '-',
            'Meta Description' => $model->meta_description ?? '-',
            'Meta Keyword' => $model->meta_keyword ?? '-',
            'Author' => $model->user->user_name ?? '-',
            'Kategori' => $categories,
            'First Image' => [
                'type' => 'image',
                'value' => $model->first_image,
            ],
            'Medium Thumbnail' => [
                'type' => 'image',
                'value' => $model->medium_thumbnail,
            ],
            'Thumbnail' => [
                'type' => 'image',
                'value' => $model->thumbnail,
            ],
            'Excerpt' => [
                'class' => 'col-12',
                'value' => $excerpt,
            ],
        ];

        return $result;
    }
}
