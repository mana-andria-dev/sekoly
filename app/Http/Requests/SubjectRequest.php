<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $subjectId = $this->route('subject') ? $this->route('subject')->id : null;
        
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'nullable|in:maternelle,primaire,college,lycee',
            'coefficient' => 'required|numeric|min:0.1|max:10',
            'is_active' => 'boolean',
        ];

        // ⚠️ SUPPRIMER la condition tenant_id car bases séparées
        // Si c'est une création, générer le code automatiquement
        if (!$subjectId) {
            $rules['code'] = 'nullable|string|max:20|unique:subjects,code';
        } else {
            $rules['code'] = [
                'nullable',
                'string',
                'max:20',
                Rule::unique('subjects', 'code')->ignore($subjectId)
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom de la matière est requis.',
            'code.unique' => 'Ce code est déjà utilisé pour une autre matière.',
            'coefficient.required' => 'Le coefficient est requis.',
            'coefficient.min' => 'Le coefficient doit être au moins 0.1.',
            'coefficient.max' => 'Le coefficient ne peut pas dépasser 10.',
        ];
    }
}