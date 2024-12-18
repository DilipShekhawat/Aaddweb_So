<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Http\Request;
use Anik\Form\FormRequest as FormRequest;

abstract class CustomFormRequest extends FormRequest
{
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * @var \Illuminate\Contracts\Validation\Validator
     */
    protected $validator;

    protected function errorMessage(): string
    {
        return 'The given data was invalid.';
    }

    protected function statusCode(): int
    {
        return 422;
    }

    protected function errorResponse(): ?JsonResponse
    {
        $errorMsg = '';
        foreach ($this->validator->errors()->messages() as $value) {
            $errorMsg .= $value[0].'<br/>';
        }
        $errMsg['message'] = rtrim($errorMsg,'<br/>');
        $errorsArr = array_merge($errMsg,$this->validator->errors()->messages());
        return response()->json([
            'message' => $this->errorMessage(),
            'errors' => $errorsArr,

        ], $this->statusCode());
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException();
    }

    protected function validationFailed(): void
    {
        throw new ValidationException($this->validator, $this->errorResponse());
    }

    protected function validationPassed()
    {
        //
    }

    public function validated(): array
    {
        return $this->validator->validated();
    }

    public function validate(): void
    {
        if (false === $this->authorize()) {
            $this->failedAuthorization();
        }

        $this->validator = $this->app->make('validator')
                                     ->make($this->all(), $this->rules(), $this->messages(), $this->attributes());

        if ($this->validator->fails()) {
            $this->validationFailed();
        }

        $this->validationPassed();
    }

    public function setContainer($app)
    {
        $this->app = $app;
    }

    protected function authorize(): bool
    {
        return true;
    }

    abstract protected function rules(): array;

    protected function messages(): array
    {
        return [];
    }

    protected function attributes(): array
    {
        return [];
    }
}
