<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\TranslateTextHelper;
use App\Http\Requests\CourseRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\BaseController as Controller;

class CourseController extends Controller
{
    protected $title = 'Course';
    protected $modelClass = Course::class;
    protected $alias = 'c';
    protected $descKey = 'course_name';
    protected $column = ['c.*'];
    protected $view = 'backend.admin.partials.course';
    protected $useDefaultEditView = true;

    protected $urlRedirect = 'course';
    protected $searchColumn = ['nama' => '%LIKE%', 'name' => '%LIKE%'];
    protected $searchColumnField = ['nama' => 'course_name_id', 'name' => 'course_name_en'];

    protected $sortColumn = ['nama', 'name'];
    protected $sortColumnField = ['nama' => 'course_name_id', 'name' => 'course_name_en'];

    protected $permissionName = "course";

    protected $formRequest = CourseRequest::class;

    protected function optionalQuery($db)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        return $db->where('instance_id', decodeId(getInstanceId()));
    }

    public function store()
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        try {
            DB::beginTransaction();

            $input = request()->input($this->getInput());

            foreach ($input as $item) {
                $course_name_id = $item['course_name_id'];
                $course_name_en = !empty($item['course_auto_translate']) ? TranslateTextHelper::translate($item['course_name_id']) : $item['course_name_en'];

                $dataInput = [
                    'instance_id' => decodeId(getInstanceId()),
                    'course_name_id' => $course_name_id,
                    'course_name_en' => $course_name_en
                ];

                $dataWhere = [
                    'instance_id' => decodeId(getInstanceId()),
                    'course_name_id' => $course_name_id
                ];

                Course::updateOrCreate($dataWhere, $dataInput);

                logHistory([
                    'nama_proses' => 'UPDATE OR CREATE DATA',
                    'ket_proses' => 'TABEL ' . $this->getTableName(),
                    'data_proses' => json_encode([$dataWhere, $dataInput]),
                ]);
            }

            DB::commit();

            return redirect($this->getUrlToRedirect())->with([
                'success' => true,
                'message' => 'Data Berhasil Ditambahkan.',
            ]);
        } catch (Exception $ex) {
            DB::rollback();

            if (is_a($ex, HttpResponseException::class)) throw $ex;

            return redirect($this->getUrlToRedirect())->with([
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ]);
        }
    }

    protected function changeInputSaveEdit($input, $model)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $input['instance_id'] = decodeId(getInstanceId());
        $input['course_name_en'] = !empty($input['course_auto_translate']) ? TranslateTextHelper::translate($input['course_name_id']) : $input['course_name_en'];

        unset($input['course_auto_translate']);

        return $input;
    }

    protected function beforeSaveDelete($oldData)
    {
        if (empty(getInstanceId())) throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        if ($oldData->instance_id != decodeId(getInstanceId())) {
            throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        }
    }
}
