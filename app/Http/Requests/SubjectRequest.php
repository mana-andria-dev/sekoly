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
            'level' => 'nullable|in:maternelle,primaire,college,lycee,collège',
            'hours_per_week' => 'required|integer|min:0|max:40',
            'coefficient' => 'required|numeric|min:0.1|max:10',
            'is_active' => 'boolean',
        ];

        // Si c'est une création, générer le code automatiquement
        if (!$subjectId) {
            $rules['code'] = 'nullable|string|max:20|unique:subjects,code,NULL,id,tenant_id,' . app('tenant')->id;
        } else {
            $rules['code'] = [
                'nullable',
                'string',
                'max:20',
                Rule::unique('subjects', 'code')
                    ->where('tenant_id', app('tenant')->id)
                    ->ignore($subjectId)
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom de la matière est requis.',
            'code.unique' => 'Ce code est déjà utilisé pour une autre matière.',
            'hours_per_week.required' => 'Le nombre d\'heures par semaine est requis.',
            'hours_per_week.min' => 'Le nombre d\'heures ne peut pas être négatif.',
            'hours_per_week.max' => 'Le nombre d\'heures ne peut pas dépasser 40.',
            'coefficient.required' => 'Le coefficient est requis.',
            'coefficient.min' => 'Le coefficient doit être au moins 0.1.',
            'coefficient.max' => 'Le coefficient ne peut pas dépasser 10.',
        ];
    }
}