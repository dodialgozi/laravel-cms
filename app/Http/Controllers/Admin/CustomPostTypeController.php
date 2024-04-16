<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Traits\Upload;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomPostType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CustomPostCategoryRequest;

class CustomPostTypeController extends BaseController
{
    protected $title = 'Custom Post Type';
    protected $modelClass = CustomPostType::class;
    protected $alias = 't';
    protected $descKey = 'post_type_name';
    protected $column = ['t.*'];
    protected $view = 'backend.admin.partials.custom-post-type';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $useDefaultShowView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'custom-post-type';

    protected $searchColumn = ['nama' => '%LIKE%', 'code' => '='];
    protected $searchColumnField = ['nama' => 'post_type_name', 'code' => 'post_type_code'];

    protected $selectFind = ['post_type_name', 'post_type_code'];
    protected $selectColumn = ['post_type_id as id', 'post_type_name as nama', 'post_type_code as code'];

    protected $sortColumn = ['nama', 'code'];
    protected $sortColumnField = ['nama' => 'post_type_name', 'code' => 'post_type_code'];

    protected $permissionName = "custom-post-type";

    protected function moreDataForm()
    {
        $fields = arrayWithKey([
                    'textbox',
                    'textbox_list',
                    'radio',
                    'checkbox',
                    'tabel',
                    'texteditor',
                    'datebox',
                    'selectbox',
                    'multiselect'
                ]);
        return [
            'fields' => $fields
        ];
    }

    protected function changeInputSave($input)
    {
        $input['post_type_status'] = !empty($input['post_type_status']) ? 1 : 0;
        $input['post_type_code'] = Str::slug($input['post_type_name'],
         '-');
        if(!empty($input['field'])){
            $input['post_type_field'] = json_encode(array_values($input['field']));
        }else{
            $input['post_type_field'] = json_encode([]);
        }
        unset($input['field']);
        return $input;
    }

    protected function changeInputSaveEdit($input, $model)
    {
        $input['post_type_status'] = !empty($input['post_type_status']) ? 1 : 0;
        $input['post_type_code'] = Str::slug($input['post_type_name'], '-');
        if(!empty($input['field'])){
            $input['post_type_field'] = json_encode(array_values($input['field']));
        }else{
            $input['post_type_field'] = json_encode([]);
        }
        unset($input['field']);
        return $input;
    }

    public function constructDataDetail($model)
    {
        return [
            'Nama' => $model->post_type_name ?? '-',
            'Kode' => $model->post_type_code ?? '-',
            'Tipe Field' => [
                'class' => 'col-12',
                'type' => 'json',
                'value' => $model->post_type_field ?? '-',
            ],
        ];
    }
}
