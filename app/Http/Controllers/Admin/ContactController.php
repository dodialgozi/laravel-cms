<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\BaseController as Controller;
use App\Http\Traits\Upload;

class ContactController extends Controller
{
    use Upload;
    protected $title = 'Contact';
    protected $modelClass = Contact::class;
    protected $alias = 'c';
    protected $descKey = 'key';
    protected $column = ['c.*'];
    protected $view = 'backend.admin.partials.contact';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $useDefaultShowView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;
    protected $urlRedirect = 'contact';
    protected $searchColumn = ['key' => '%LIKE%'];
    protected $searchColumnField = ['key' => 'key'];

    protected $sortColumn = ['key'];
    protected $sortColumnField = ['key' => 'key'];

    protected $permissionName = "contact";

    protected $formRequest = ContactRequest::class;

    protected function optionalQuery($db)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        return $db->where('instance_id', decodeId(getInstanceId()));
    }

    protected function changeInputSave($input)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['instance_id'] = decodeId(getInstanceId());
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'contact', 'icon'))) {
            $input['icon'] = $fileUpload;
        }

        return $input;
    }

    protected function changeInputSaveEdit($input, $model)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['instance_id'] = decodeId(getInstanceId());
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'contact', 'icon'))) {
            $input['icon'] = $fileUpload;
        }

        return $input;
    }

    protected function beforeSaveDelete($oldData)
    {
        if (empty(getInstanceId())) throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        if ($oldData->instance_id != decodeId(getInstanceId())) {
            throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        }

        if (!empty($oldData->contact_photo)) {
            $this->deleteFile($oldData->contact_photo);
        }
    }
}
