<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Traits\Upload;
use Illuminate\Support\Str;
use App\Helpers\TranslateTextHelper;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostCategoryController extends BaseController
{
    use Upload;

    protected $title = 'Kategori Post';
    protected $modelClass = Category::class;
    protected $alias = 'c';
    protected $descKey = 'category_name_id';
    protected $column = ['c.*'];
    protected $view = 'backend.admin.partials.post-category';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'post-kategori';

    protected $searchColumn = ['nama' => '%LIKE%', 'name' => '%LIKE%', 'status' => '='];
    protected $searchColumnField = ['nama' => 'category_name_id', 'name' => 'category_name_en', 'status' => 'category_active'];

    protected $selectFind = ['category_name_id', 'category_name_en', 'category_active'];
    protected $selectColumn = ['category_id as id', 'category_name_id as nama', 'category_name_en as name', 'category_active as status'];

    protected $sortColumn = ['nama', 'status'];
    protected $sortColumnField = ['nama' => 'category_name_id', 'status' => 'category_active'];

    protected $permissionName = "post-kategori";

    protected $formRequest = CategoryRequest::class;

    protected $limit = null;

    protected function optionalQuery($db)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        return $db
            ->where('parent_id', null)
            ->where('instance_id', decodeId(getInstanceId()))
            ->with(['children', 'children.children'])
            ->orderBy('category_name_id', 'asc');
    }

    protected function moreDataEdit($result)
    {
        $parent_category = Category::where('category_id', $result->parent_id)
            ->select('category_id as id', 'category_name_id as nama', 'category_name_en as name')
            ->first();

        return [
            'parent_category' => $parent_category
        ];
    }

    protected function changeInputSave($input)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['category_active'] = !empty($input['category_active']) ? 1 : 0;

        $input['category_name_id'] = $input['category_name_id'];
        $input['category_slug_id'] = Str::slug($input['category_name_id'], '-');

        $input['category_name_en'] = !empty($input['category_auto_translate']) ? TranslateTextHelper::translate($input['category_name_id']) : $input['category_name_en'];
        $input['category_slug_en'] = Str::slug($input['category_name_en'], '-');

        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'kategori', 'category_thumbnail'))) {
            $input['category_thumbnail'] = $fileUpload;
        }

        $input['instance_id'] = decodeId(getInstanceId());

        unset($input['category_auto_translate']);

        return $input;
    }

    protected function changeInputSaveEdit($input, $model)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['category_active'] = !empty($input['category_active']) ? 1 : 0;

        $input['category_name_id'] = $input['category_name_id'];
        $input['category_slug_id'] = Str::slug($input['category_name_id'], '-');

        $input['category_name_en'] = !empty($input['category_auto_translate']) ? TranslateTextHelper::translate($input['category_name_id']) : $input['category_name_en'];
        $input['category_slug_en'] = Str::slug($input['category_name_en'], '-');

        if (!isset($input['parent_id']) || $input['parent_id'] == $model->category_id) {
            $input['parent_id'] = null;
        }

        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'kategori', 'category_thumbnail'))) {
            $input['category_thumbnail'] = $fileUpload;

            if (!empty($model->category_thumbnail)) {
                $this->deleteFile($model->category_thumbnail);
            }
        }

        $input['instance_id'] = decodeId(getInstanceId());

        unset($input['category_auto_translate']);

        return $input;
    }

    protected function beforeSaveDelete($oldData)
    {
        if (empty(getInstanceId())) throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        if ($oldData->instance_id != decodeId(getInstanceId())) {
            throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        }

        if (!empty($oldData->category_thumbnail)) {
            $this->deleteFile($oldData->category_thumbnail);
        }
    }
}
