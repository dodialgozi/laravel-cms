<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use App\Http\Traits\Upload;
use App\Http\Requests\SliderRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\BaseController as Controller;

class SliderController extends Controller
{
    use Upload;
    protected $title = 'Slider';
    protected $modelClass = Slider::class;
    protected $alias = 's';
    protected $descKey = 'slider_title';
    protected $column = ['s.*'];
    protected $view = 'backend.admin.partials.slider';
    protected $useDefaultEditView = true;
    protected $useDefaultAddView = true;
    protected $addViewUpload = true;
    protected $editViewUpload = true;

    protected $urlRedirect = 'slider';
    protected $searchColumn = ['title' => '%LIKE%',];
    protected $searchColumnField = ['title' => 'slider_title'];

    protected $sortColumn = ['title'];
    protected $sortColumnField = ['title' => 'slider_title'];

    protected $permissionName = "slider";

    protected $formRequest = SliderRequest::class;

    protected function optionalQuery($db)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        return $db->where('instance_id', decodeId(getInstanceId()));
    }

    public function changeInputSave($input)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['instance_id'] = decodeId(getInstanceId());
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'slider', 'slider_image'))) {
            $input['slider_image'] = $fileUpload;
        }

        return $input;
    }

    public function changeInputSaveEdit($input, $model)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['instance_id'] = decodeId(getInstanceId());
        if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'slider', 'slider_image'))) {
            $input['slider_image'] = $fileUpload;
        }

        return $input;
    }

    protected function beforeSaveDelete($oldData)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');

        if ($oldData->instance_id != decodeId(getInstanceId())) {
            throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        }
    }
}
