<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadMailingListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'file' => 'required|file|mimes:csv,txt'
        ];
    }


    /**
     * Move file to the import directory.
     *
     * @return string
     */
    public function moveFile() : string
    {
        $file = $this->file('file');
        $name = str_random(8) . '.csv';
        $file->storeAs('import', $name);

        return storage_path('app/import/' . $name);
    }
}
