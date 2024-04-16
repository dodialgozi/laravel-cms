<?php

namespace App\Http\Controllers\Admin;

use DOMDocument;
use App\Models\Tag;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\PostMeta;
use App\Http\Traits\Upload;
use Illuminate\Support\Str;
use App\Models\PostCategory;
use App\Models\SettingPostMeta;
use App\Http\Requests\PostRequest;
use App\Helpers\TranslateTextHelper;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostController extends BaseController
{
    use Upload;
    protected $title = 'Post';
    protected $modelClass = Post::class;
    protected $alias = 'p';
    protected $descKey = 'post__id';
    protected $column = ['p.*'];
    protected $view = 'backend.admin.partials.post';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $useDefaultShowView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'post';
    protected $searchColumn = ['title_id' => '%LIKE%', 'title_en' => '%LIKE%', 'date' => '%LIKE%', 'status' => '='];
    protected $searchColumnField = ['title_id' => 'post_title_id', 'title_en' => 'post_title_en', 'date' => 'post_date', 'status' => 'post_status'];

    protected $selectFind = ['post_title_id', 'post_title_en', 'user_id', 'post_date', 'post_status'];
    protected $selectColumn = ['post_id as id', 'post_title_id as title_id', 'post_title_en as title_en', 'user_id as author', 'post_date as date', 'post_status as status'];

    protected $sortColumn = ['title_id', 'author', 'date', 'status'];
    protected $sortColumnField = ['title_id' => 'post_title_id', 'author' => 'user_id', 'date' => 'post_date', 'status' => 'post_status'];

    protected $permissionName = "post";

    protected $formRequest = PostRequest::class;

    protected function optionalQuery($db)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        return $db->where('instance_id', decodeId(getInstanceId()));
    }

    protected function moreDataIndex()
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

        if (userCan("{$this->permissionName}.schedule")) {
            $status['schedule'] = 'Jadwalkan';
        }

        if (userCan("{$this->permissionName}.publish")) {
            $status['publish'] = 'Publish';
        }

        $post_type = [
            'post' => 'Post',
            'video' => 'Video',
        ];

        $post_meta_default = SettingPostMeta::all();

        $meta_type = [
            'field' => 'Text',
            'boolean' => 'Boolean',
        ];

        return [
            'status' => $status,
            'post_type' => $post_type,
            'post_meta_default' => $post_meta_default,
            'meta_type' => $meta_type
        ];
    }

    protected function changeInputSave($input)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['post_slider'] = isset($input['post_slider']) ? 1 : 0;
        $input['post_hottopic'] = isset($input['post_hottopic']) ? 1 : 0;
        $input['post_trending_topic'] = isset($input['post_trending_topic']) ? 1 : 0;

        // set slug and user_id
        $input['post_title_id'] = $input['post_title_id'];
        $input['post_slug_id'] = Str::slug($input['post_title_id'], '-');

        $input['post_title_en'] = !empty($input['post_auto_translate']) ? TranslateTextHelper::translate($input['post_title_id']) : $input['post_title_en'];
        $input['post_slug_en'] = Str::slug($input['post_title_en'], '-');


        $imageUrl = null;

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

        // $input['post_slug'] = Str::slug($input['post_title'], '-');
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
                PostCategory::create([
                    'post_id' => $id,
                    'category_id' => $category
                ]);
            }
        }

        // save post tag
        if (request()->has('tags')) {
            foreach (request()->input('tags') as $item) {
                $tagValue = $item;

                // if tag is not numeric, create new tag
                if (!is_numeric($tagValue)) {
                    $newTag = Tag::create([
                        'tag_slug' => Str::slug($tagValue, '-'),
                        'tag_name' => $tagValue
                    ]);

                    $item = $newTag->tag_id;
                } else {
                    $item = (int) $tagValue;
                }

                $tag = Tag::firstOrCreate([
                    'tag_id' => $item,
                ], [
                    'tag_name' => $tagValue
                ]);

                PostTag::create([
                    'post_id' => $id,
                    'tag_id' => $tag->tag_id
                ]);
            }
        }

        // save post meta
        $metaInputs = ['default_meta', 'post_meta'];

        foreach ($metaInputs as $inputName) {
            if (request()->has($inputName)) {
                foreach (request()->input($inputName) as $item) {
                    if ($item['post_meta_type'] == 'boolean') {
                        $item['post_meta_value'] = isset($item['post_meta_value']) && $item['post_meta_value'] == 'on' ? 1 : 0;
                    }

                    PostMeta::create([
                        'post_id' => $id,
                        'post_meta_code' => $item['post_meta_code'],
                        'post_meta_value' => $item['post_meta_value'],
                        'post_meta_type' => $item['post_meta_type']
                    ]);
                }
            }
        }
    }

    protected function moreDataEdit($result)
    {
        $post_categories = PostCategory::where('post_id', $result->post_id)->get();
        $post_tags = PostTag::where('post_id', $result->post_id)->get();

        $post_date = $result->post_date ? date('Y-m-d', strtotime($result->post_date)) : null;
        $post_time = $result->post_date ? date('H:i', strtotime($result->post_date)) : null;

        $post_meta = PostMeta::where('post_id', $result->post_id)->get();

        return [
            'post_categories' => $post_categories,
            'post_tags' => $post_tags,
            'post_date' => $post_date,
            'post_time' => $post_time,
            'post_meta' => $post_meta
        ];
    }

    protected function changeInputSaveEdit($input, $oldData)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['post_slider'] = isset($input['post_slider']) ? 1 : 0;
        $input['post_hottopic'] = isset($input['post_hottopic']) ? 1 : 0;
        $input['post_trending_topic'] = isset($input['post_trending_topic']) ? 1 : 0;

        $imageUrl = null;

        $input['post_title_id'] = $input['post_title_id'];
        $input['post_slug_id'] = Str::slug($input['post_title_id'], '-');

        $input['post_title_en'] = !empty($input['post_auto_translate']) ? TranslateTextHelper::translate($input['post_title_id']) : $input['post_title_en'];
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
        $oldContentId = $oldData->post_content_id;
        $newContentId = $input['post_content_id'];

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

        if (empty($input['post_auto_translate']) && !empty($input['post_title_en'])) {
            $oldContentEn = $oldData->post_content_en;
            $newContentEn = $input['post_content_en'];

            if ($oldContentEn) {
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
            }
        } else {
            $input['post_content_en'] = TranslateTextHelper::translate($input['post_content_id']);
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
            PostCategory::where('post_id', $id)->delete();

            foreach (request()->input('categories') as $category) {
                PostCategory::create([
                    'post_id' => $id,
                    'category_id' => $category
                ]);
            }
        }

        // save post tag
        if (request()->has('tags')) {
            PostTag::where('post_id', $id)->delete();

            foreach (request()->input('tags') as $item) {
                $tagValue = $item;

                // if tag is not numeric, create new tag
                if (!is_numeric($tagValue)) {
                    $newTag = Tag::create([
                        'tag_slug' => Str::slug($tagValue, '-'),
                        'tag_name' => $tagValue
                    ]);

                    $item = $newTag->tag_id;
                } else {
                    $item = (int) $tagValue;
                }

                $tag = Tag::firstOrCreate([
                    'tag_id' => $item,
                ], [
                    'tag_name' => $tagValue
                ]);

                PostTag::create([
                    'post_id' => $id,
                    'tag_id' => $tag->tag_id
                ]);
            }
        }

        PostMeta::where('post_id', $id)->delete();

        $metaInputs = ['default_meta', 'post_meta'];

        foreach ($metaInputs as $inputName) {
            if (request()->has($inputName)) {
                foreach (request()->input($inputName) as $item) {
                    if ($item['post_meta_type'] == 'boolean') {
                        $item['post_meta_value'] = isset($item['post_meta_value']) && $item['post_meta_value'] == 'on' ? 1 : 0;
                    }

                    PostMeta::create([
                        'post_id' => $id,
                        'post_meta_code' => $item['post_meta_code'],
                        'post_meta_value' => $item['post_meta_value'],
                        'post_meta_type' => $item['post_meta_type']
                    ]);
                }
            }
        }
    }

    protected function beforeSaveDelete($oldData)
    {
        if (empty(getInstanceId())) throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        if ($oldData->instance_id != decodeId(getInstanceId())) {
            throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        }
        // delete all categories
        PostCategory::where('post_id', $oldData->post_id)->delete();

        // delete all tags
        PostTag::where('post_id', $oldData->post_id)->delete();

        // delete all post meta
        PostMeta::where('post_id', $oldData->post_id)->delete();

        // delete all input image
        $imageFieldsToDelete = ['first_image', 'medium_thumbnail', 'thumbnail'];

        foreach ($imageFieldsToDelete as $imageField) {
            if (!empty($oldData->$imageField)) {
                $this->deleteFile($oldData->$imageField);
            }
        }

        // delete all image in content
        if ($oldData->post_content_id) {
            $dom = new DOMDocument();
            $dom->loadHTML($oldData->post_content_id);

            $imgTags = $dom->getElementsByTagName('img');

            if ($imgTags->length > 0) {
                foreach ($imgTags as $imgTag) {
                    $imageUrl = $imgTag->getAttribute('src');
                    $this->deleteFile($imageUrl);
                }
            }
        }

        // delete all image in content
        if ($oldData->post_content_en) {
            $dom = new DOMDocument();
            $dom->loadHTML($oldData->post_content_en);

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

        $tags = $model->tags;
        if ($tags->isNotEmpty()) {
            $tags = $tags->map(function ($tag) {
                return $tag->tag->tag_name;
            })->implode(', ');
        } else {
            $tags = '-';
        }

        $excerpt = '-';
        if (!empty($model->post_excerpt_id)) {
            $excerpt = $model->post_excerpt_id;
        }

        $result = [
            'Judul (ID)' => $model->post_title_id,
            'Judul (EN)' => $model->post_title_en,
            'Post Type' => $model->post_type ?? '-',
            'Meta Title' => $model->meta_title ?? '-',
            'Meta Description' => $model->meta_description ?? '-',
            'Meta Keyword' => $model->meta_keyword ?? '-',
            'Author' => $model->user->user_name ?? '-',
            'Kategori' => $categories,
            'Tag' => $tags,
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

        $post_meta = PostMeta::where('post_id', $model->post_id)->get();

        if ($post_meta->isNotEmpty()) {
            array_push($result, 'Post Meta');

            foreach ($post_meta as $meta) {
                $post_meta_value = $meta->post_meta_value;

                if ($meta->post_meta_type == 'boolean') {
                    $post_meta_value = $meta->post_meta_value == 1 ? 'Ya' : 'Tidak';
                }

                $result[$meta->post_meta_code] = $post_meta_value;
            }
        }

        return $result;
    }
}
