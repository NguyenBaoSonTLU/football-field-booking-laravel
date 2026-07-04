<?php

namespace App\Http\Requests\Admin;

class UpdateFootballFieldRequest extends StoreFootballFieldRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            'remove_image_ids' => ['nullable', 'array'],
            'remove_image_ids.*' => ['integer', 'exists:field_images,id'],
            'main_image_id' => ['nullable', 'integer', 'exists:field_images,id'],
        ];
    }
}
