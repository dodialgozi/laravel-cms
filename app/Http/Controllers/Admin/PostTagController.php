<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Helpers\TranslateTextHelper;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostTagController extends BaseController
{
    protected $title = 'Tag';
    protected $modelClass = Tag::class;
    protected $alias = 't';
    protected $descKey = 'tag_name';
    protected $column = ['t.*'];
    protected $view = 'backend.admin.partials.post-tag';
    protected $useDefaultEditView = true;
    protected $urlRedirect = 'post-tag';
    protected $searchColumn = ['nama' => '%LIKE%', 'name' => '%LIKE%', 'popular' => '='];
    protected $searchColumnField = ['nama' => 'tag_name_id', 'name' => 'tag_name_en', 'popular' => 'tag_popular', 'tag_count'];

    protected $selectFind = ['tag_id', 'tag_name_id', 'tag_name_en', 'tag_popular', 'tag_count'];
    protected $selectColumn = ['tag_id as id', 'tag_name_id as name', 'tag_name_en as name_en', 'tag_popular as popular', 'tag_count as view'];

    protected $sortColumn = ['nama', 'name', 'popular', 'view'];
    protected $sortColumnField = ['nama' => 'tag_name_id', 'name' => 'tag_name_en', 'popular' => 'tag_popular', 'view' => 'tag_count'];

    protected $permissionName = "post-tag";

    protected function optionalQuery($db)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        return $db->where('instance_id', decodeId(getInstanceId()));
    }

    protected function moreDataIndex()
    {
        $status = [
            '1' => 'Popular',
            '0' => 'Tidak Popular',
        ];

        return [
            'status' => $status
        ];
    }

    public function store()
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        try {
            DB::beginTransaction();

            $input = request()->input($this->getInput());

            foreach ($input as $item) {
                $tag_name_id = $item['tag_name_id'];
                $tag_name_en = !empty($item['tag_auto_translate']) ? TranslateTextHelper::translate($item['tag_name_id']) : $item['tag_name_en'];

                $slug_id = Str::slug($tag_name_id);
                $slug_en = Str::slug($tag_name_en);

                $dataWhere = [
                    'tag_slug_id' => $slug_id,
                    'tag_slug_en' => $slug_en,
                ];
                $dataInput = [
                    'tag_name_id' => $tag_name_id,
                    'tag_slug_id' => $slug_id,
                    'tag_name_en' => $tag_name_en,
                    'tag_slug_en' => $slug_en,
                    'tag_popular' => isset($item['tag_popular']) && $item['tag_popular'] == 'on' ? 1 : 0,
                    'instance_id' => decodeId(getInstanceId()),
                ];

                Tag::updateOrCreate($dataWhere, $dataInput);

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

    public function update($id)
    {
        if (empty(getInstanceId())) abort(403, 'Unauthorized action.');
        $redirectParams = json_decode(base64_decode(request()->redirect_params));
        $urlRedirectParams = !empty($redirectParams) ? Arr::query($redirectParams) : '';

        try {
            DB::beginTransaction();

            $id = $this->getIdParemeter($id);

            $this->alias = null;
            $oldData = $this->dbBuilder()
                ->where($this->getTableIdentifier(), $id)
                ->first();
            if (empty($oldData)) abort(404);

            $input = request()->input($this->getInput());

            $tag_name_id = $input['tag_name_id'];
            $tag_name_en = !empty($input['tag_auto_translate']) ? TranslateTextHelper::translate($input['tag_name_id']) : $input['tag_name_en'];

            $slug_id = Str::slug($tag_name_id);
            $slug_en = Str::slug($tag_name_en);

            $dataWhere = [
                'tag_slug_id' => $slug_id,
            ];

            $dataInput = [
                'tag_name_id' => $tag_name_id,
                'tag_slug_id' => $slug_id,
                'tag_name_en' => $tag_name_en,
                'tag_slug_en' => $slug_en,
                'tag_popular' => isset($input['tag_popular']) && $input['tag_popular'] == 'on' ? 1 : 0,
                'instance_id' => decodeId(getInstanceId()),
            ];

            Tag::updateOrCreate($dataWhere, $dataInput);

            logHistory([
                'nama_proses' => 'UPDATE OR CREATE DATA',
                'ket_proses' => 'TABEL ' . $this->getTableName(),
                'data_proses' => json_encode([$dataWhere, $dataInput]),
            ]);

            DB::commit();

            return redirect($this->getUrlToRedirect() . exist($urlRedirectParams, prefix: '?'))->with([
                'success' => true,
                'message' => 'Perubahan Berhasil Disimpan.',
            ]);
        } catch (Exception $ex) {
            DB::rollback();

            if (is_a($ex, HttpResponseException::class)) throw $ex;

            return redirect($this->getUrlToRedirect() . exist($urlRedirectParams, prefix: '?'))->with([
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ]);
        }
    }

    protected function beforeSaveDelete($oldData)
    {
        if (empty(getInstanceId())) throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));

        if ($oldData->instance_id != decodeId(getInstanceId())) {
            throw new HttpResponseException(response()->json(['message' => 'Unauthorized action.'], 403));
        }
    }
}
