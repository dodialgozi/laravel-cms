<?php

namespace App\Http\Controllers\Admin;

use App\Http\Traits\Upload;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomPostType;
use App\Models\CustomPostCategory;
use App\Helpers\TranslateTextHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

class CustomPostCategoryController extends BaseController
{
    protected $title = 'Custom Post Category';
    protected $modelClass = CustomPostCategory::class;
    protected $alias = 'c';
    protected $descKey = 'category_name_id';
    protected $column = ['c.*'];
    protected $view = 'backend.admin.partials.custom-post-category';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'custom-post-category';

    protected $searchColumn = ['nama' => '%LIKE%', 'name' => '%LIKE%', 'status' => '='];
    protected $searchColumnField = ['nama' => 'category_name_id', 'name' => 'category_name_en', 'status' => 'category_active'];

    protected $selectFind = ['category_name_id', 'category_name_en', 'category_active'];
    protected $selectColumn = ['category_id as id', 'category_name_id as nama', 'category_name_en as name', 'category_active as status'];

    protected $sortColumn = ['nama', 'name', 'status'];
    protected $sortColumnField = ['nama' => 'category_name_id', 'name' => 'category_name_en', 'status' => 'category_active'];

    protected $permissionName = "custom-post-category";

    protected function optionalQuery($db)
    {
        return $db
            ->where('parent_id', null)
            ->where('instance_id', decodeId(getInstanceId()))
            ->with(['children', 'children.children']);
    }

    protected function moreDataEdit($result)
    {
        $parent_category = CustomPostCategory::where('category_id', $result->parent_id)
            ->select('category_id as id', 'category_name_id as name')
            ->first();
        $custom_post_type = CustomPostType::where('post_type_id', $result->post_type_id)
            ->select('post_type_id as id', 'post_type_name as name')->first();

        return [
            'parent_category'  => $parent_category,
            'custom_post_type' => $custom_post_type,
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
        $input['instance_id'] = decodeId(getInstanceId());
        unset($input['category_auto_translate']);
        return $input;
    }

    protected function getSelectOptionalQuery($db)
    {
        return
            $db->join('custom_post_type', 'custom_post_type.post_type_id', '=', 'c.post_type_id')
            ->when(request()->type, function ($q, $type) {
                $q->where('post_type_code', $type);
            });
    }
}
