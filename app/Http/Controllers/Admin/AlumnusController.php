<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alumnus;
use App\Http\Traits\Upload;
use App\Http\Controllers\BaseController as Controller;
use App\Helpers\TranslateTextHelper;
use App\Http\Requests\AlumnusRequest;

class AlumnusController extends Controller
{
    use Upload;
    protected $title = 'Alumnus';
    protected $modelClass = Alumnus::class;
    protected $alias = 'a';
    protected $descKey = 'alumnus_name';
    protected $column = ['a.*'];
    protected $view = 'backend.admin.partials.alumnus';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $useDefaultShowView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'alumnus';
    protected $searchColumn = ['alumnus_name' => '%LIKE%', 'alumnus_email' => '%LIKE%', 'alumnus_nim' => '%LIKE%'];
    protected $searchColumnField = ['nama' => 'alumnus_name', 'email' => 'alumnus_email', 'nim' => 'alumnus_nim'];
    protected $sortColumn = ['nama', 'email', 'nim'];
    protected $sortColumnField = ['nama' => 'alumnus_name', 'email' => 'alumnus_email', 'nim' => 'alumnus_nim'];

    protected $permissionName = "alumnus";

    protected $formRequest = AlumnusRequest::class;

    protected function optionalQuery($db)
    {
        return $db->where('instance_id', decodeId(getInstanceId()));
    }

    protected function changeInputSave($input)
    {

        $input['instance_id'] = decodeId(getInstanceId());
        $input['alumnus_statement_id'] = $input['alumnus_statement_id'];
        $input['alumnus_statement_en'] = !empty($input['alumnus_auto_translate']) ? TranslateTextHelper::translate($input['alumnus_statement_id']) : $input['alumnus_statement_en'];
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'alumnus', 'alumnus_image'))) {
            $input['alumnus_image'] = $fileUpload;
        }

        unset($input['alumnus_auto_translate']);

        return $input;
    }

    protected function changeInputSaveEdit($input, $model)
    {
        $input['instance_id'] = decodeId(getInstanceId());
        $input['alumnus_statement_id'] = $input['alumnus_statement_id'];
        $input['alumnus_statement_en'] = !empty($input['alumnus_auto_translate']) ? TranslateTextHelper::translate($input['alumnus_statement_id']) : $input['alumnus_statement_en'];
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'alumnus', 'alumnus_image'))) {
            $input['alumnus_image'] = $fileUpload;
        }

        unset($input['alumnus_auto_translate']);

        return $input;
    }

    protected function beforeSaveDelete($oldData)
    {
        if (!empty($oldData->alumnus_image)) {
            $this->deleteFile($oldData->alumnus_image);
        }
    }
}
