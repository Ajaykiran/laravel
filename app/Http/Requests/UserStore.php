<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserStore extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:userinfos,email|max:255',
            // 'birth_date' => 'required|date|before_or_equal:today',
            // 'phone' => 'required|string|regex:/^[0-9]{10}$/',
            // 'qualification' => 'required|string|max:255',
            // 'gender' => 'required|in:male,female',
            // 'address' => 'required|string|max:255',
            'image' => [
                'required',
                'distinct',
                'mimes:xls,xlsx',
                function ($attribute, $value, $fail) {
                    $file = $this->file('image');
                    $spreadsheet = IOFactory::load($file->getRealPath());
                    $worksheet = $spreadsheet->getActiveSheet();
                    $rows = $worksheet->toArray();
                    $data = [];
                    foreach ($rows as $row) {
                        if (in_array($row, $data)) {
                            $fail("Duplicate data found in the uploaded file.");
                        }
                        $data[] = $row;
                    }
                },
            ],
        ];
    }
}
