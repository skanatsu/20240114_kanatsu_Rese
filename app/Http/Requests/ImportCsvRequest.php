<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use SplFileObject;

class ImportCsvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    //バリデーション実行まえにCSVを読み込んでパラメータにセットする。

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'csv_file' => ['required', 'file', 'mimes:csv', 'mimetypes:text/csv,application/csv'],
            'csv_array' => ['required', 'array'],
            'csv_array.*.shopname' => ['required', 'string', 'max:50'],
            'csv_array.*.area' => ['required', Rule::in(['東京都', '大阪府', '福岡県'])],
            'csv_array.*.genre' => ['required', Rule::in(['寿司', '焼肉', 'イタリアン', '居酒屋', 'ラーメン'])],
            'csv_array.*.description' => ['required', 'string', 'max:400'],
            'csv_array.*.image_url' => ['required', 'active_url', 'regex:/\.(jpeg|jpg|png)$/'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $file_path = $this->file('csv_file')->path();
        $file = new SplFileObject($file_path);
        $file->setFlags(
            SplFileObject::READ_CSV |
                SplFileObject::READ_AHEAD |
                SplFileObject::SKIP_EMPTY |
                SplFileObject::DROP_NEW_LINE
        );

        foreach ($file as $index => $line) {
            if (empty($header)) {
                $header = $line;
                continue;
            }
            $csv_array[$index]['shopname'] = $line[0];
            $csv_array[$index]['area'] = $line[1];
            $csv_array[$index]['genre'] = $line[2];
            $csv_array[$index]['description'] = $line[3];
            $csv_array[$index]['image_url'] = $line[4];
        }

        $this->merge([
            'csv_array' => $csv_array,
        ]);
    }

    public function attributes()
    {
        return [
            'csv_file' => 'CSVファイル',
            'csv_array.*.shopname' => '店舗名',
            'csv_array.*.area' => '地域',
            'csv_array.*.genre' => 'ジャンル',
            'csv_array.*.description' => '店舗概要',
            'csv_array.*.image_url' => '画像URL',
        ];
    }

    public function messages()
    {
        return [
            'csv_array.*.area.in' => '地域は東京都・大阪府・福岡県のいずれかを指定してください。',
            'csv_array.*.genre.in' => 'ジャンルは寿司・焼肉・イタリアン・居酒屋・ラーメンのいずれかを指定してください。',
            'csv_array.*.image_url.regex' => '画像URLはjpeg, jpg, pngのいずれかの形式を指定してください。',
        ];
    }
}