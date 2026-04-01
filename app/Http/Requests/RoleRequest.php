<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'display_name' => 'required|string|max:255|ascii',
            'description' => 'sometimes|nullable|string|max:255',
            'scope' => 'required|in:1,2',
            'is_protected' => 'required|boolean',
            'permissions' => 'required|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ];
    }

    public function messages()
    {
        return [
            'display_name.required' => '角色名稱為必填項。',
            'display_name.string' => '角色名稱必須是字串。',
            'display_name.max' => '角色名稱最多不能超過 255 個字元。',
            'display_name.ascii' => '角色名稱只能包含 ASCII 字元。',
            'description.string' => '描述必須是字串。',
            'description.max' => '描述最多不能超過 255 個字元。',
            'scope.required' => '範圍為必填項。',
            'scope.in' => '範圍必須是 1 或 2。',
            'is_protected.boolean' => '受保護欄位必須是 true 或 false。',
            'permissions.required' => '必須至少選擇一個權限。',
            'permissions.array' => '權限必須是陣列。',
            'permissions.*.integer' => '每個權限必須是整數。',
            'permissions.*.exists' => '一個或多個選定的權限不存在。',
        ];
    }
}
