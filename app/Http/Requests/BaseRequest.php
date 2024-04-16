<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use stdClass;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    /**
     * @var array $input
     */
    protected $input;
    /**
     * @var array $file
     */
    protected $file;
    /**
     * @var stdClass $model
     */
    protected $model;

    public function __construct($input, $file, $model = null)
    {
        $this->input = $input;
        $this->file = $file;
        $this->model = $model;
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */

    public function getValidator()
    {

        return $this->validator;
    }
}
