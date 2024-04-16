<?php

namespace App\Http\Controllers\Admin;

use App\Models\Partner;
use App\Http\Traits\Upload;
use App\Http\Requests\PartnerRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\BaseController as Controller;

class PartnerController extends Controller
{
    use Upload;
    protected $title = 'Partner';
    protected $modelClass = Partner::class;
    protected $alias = 'p';
    protected $descKey = 'partner_name';
    protected $column = ['p.*'];
    protected $view = 'backend.admin.partials.partner';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $useDefaultShowView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'partner';
    protected $searchColumn = ['partner_name' => '%LIKE%'];
    protected $searchColumnField = ['nama' => 'partner_name'];

    protected $sortColumn = ['nama'];
    protected $sortColumnField = ['nama' => 'partner_name'];

    protected $permissionName = "partner";

    protected $formRequest = PartnerRequest::class;

    protected function optionalQuery($db)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        return $db->where('instance_id', decodeId(getInstanceId()));
    }

    protected function changeInputSave($input)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['instance_id'] = decodeId(getInstanceId());
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'partner', 'partner_logo'))) {
            $input['partner_logo'] = $fileUpload;
        }

        return $input;
    }

    protected function changeInputSaveEdit($input, $model)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['instance_id'] = decodeId(getInstanceId());
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'partner', 'partner_logo'))) {
            $input['partner_logo'] = $fileUpload;
        }

        return $input;
    }

    protected function beforeSaveDelete($oldData)
    {
        if (empty(getInstanceId())) throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        if ($oldData->instance_id != decodeId(getInstanceId())) {
            throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        }

        if (!empty($oldData->partner_logo)) {
            $this->deleteFile($oldData->partner_logo);
        }
    }
}
