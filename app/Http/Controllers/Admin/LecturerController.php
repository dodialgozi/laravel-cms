<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lecturer;
use App\Http\Traits\Upload;
use App\Helpers\TranslateTextHelper;
use App\Http\Requests\LecturerRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\BaseController as Controller;

class LecturerController extends Controller
{
    use Upload;
    protected $title = 'Dosen';
    protected $modelClass = Lecturer::class;
    protected $alias = 'l';
    protected $descKey = 'lecturer_name';
    protected $column = ['l.*'];
    protected $view = 'backend.admin.partials.lecturer';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'lecturer';

    protected $searchColumn = ['nama' => '%LIKE%', 'nidn' => '%LIKE%', 'status' => '='];
    protected $searchColumnField = ['nama' => 'lecturer_name', 'nidn' => 'lecturer_nidn', 'status' => 'lecturer_active'];

    protected $sortColumn = ['nama', 'nidn', 'status'];
    protected $sortColumnField = ['nama' => 'lecturer_name', 'nidn' => 'lecturer_nidn', 'status' => 'lecturer_active'];

    protected $permissionName = 'lecturer';

    protected $formRequest = LecturerRequest::class;

    protected function optionalQuery($db)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        return $db->where('instance_id', decodeId(getInstanceId()));
    }

    protected function changeInputSave($input)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['lecturer_bio_en'] = !empty($input['lecturer_auto_translate']) ? TranslateTextHelper::translate($input['lecturer_bio_id']) : $input['lecturer_bio_en'];
        $input['lecturer_active'] = !empty($input['lecturer_active']) ? 1 : 0;

        $input['instance_id'] = decodeId(getInstanceId());
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'lecturer', 'lecturer_photo'))) {
            $input['lecturer_photo'] = $fileUpload;
        }

        unset($input['lecturer_auto_translate']);

        return $input;
    }

    protected function changeInputSaveEdit($input, $model)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['lecturer_bio_en'] = !empty($input['lecturer_auto_translate']) ? TranslateTextHelper::translate($input['lecturer_bio_id']) : $input['lecturer_bio_en'];
        $input['lecturer_active'] = !empty($input['lecturer_active']) ? 1 : 0;

        $input['instance_id'] = decodeId(getInstanceId());
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'lecturer', 'lecturer_photo'))) {
            $input['lecturer_photo'] = $fileUpload;

            if (!empty($model->lecturer_photo)) {
                $this->deleteFile($model->lecturer_photo);
            }
        }

        unset($input['lecturer_auto_translate']);

        return $input;
    }

    protected function beforeSaveDelete($oldData)
    {
        if (empty(getInstanceId())) throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        if ($oldData->instance_id != decodeId(getInstanceId())) {
            throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        }

        if (!empty($oldData->lecturer_photo)) {
            $this->deleteFile($oldData->lecturer_photo);
        }
    }
}
