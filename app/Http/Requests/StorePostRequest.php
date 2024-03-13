<?php

namespace App\Http\Requests;

use App\Http\Enum\GroupUserStatus;
use App\Models\GroupUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class StorePostRequest extends FormRequest
{
    public static array $extension =[
      'jpg','jpeg', 'png','gif','webp','svg',
      'mp3','wav','mp4',
      'doc','docx','pdf','csv','xls','xlsx',
      'zip'
      ];


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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
              'body'    => ['nullable', 'string'],
              'user_id' => ['numeric'],

              'attachments' => [
                  'array',
                  'max:50',

            function ($attribute, $value, $fail) { // $value is array of attachments

               $totalSize = collect($value)->sum(fn(UploadedFile $file) => $file->getSize());
                //dd($totalSize); // mb

                if ($totalSize > 1 * 1024 * 1024 * 1024) {
                    $fail('The total size of all files cannot exceed 1GB.');
                }
            },
          ],
              'attachments.*' => [
                  'file',
                  File::types(self::$extension), //File::types(self::$extension)->max(500 * 1024 * 1024 ),
                 // 'max:' .(500 * 1024), //MB
              ],
            'group_id' => ['nullable', 'exists:groups,id', function ($attribute,$value, \Closure $fail) {
                /*Query user group**/
                $groupUser = GroupUser::where('user_id', Auth::id())
                    ->where('group_id', $value)
                    ->where('status', GroupUserStatus::APPROVED->value)
                    ->exists();
                if (!$groupUser){
                    $fail('You don\'t have permission to create post in this group ');
                }
            }],
        ];

    }

    //preg_match('/(#\w+)/', '<a href="/search/$1">$1</a>' , $this->input('body') ? : ''),
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->user()->id,
            'body'    => $this->input('body') ? : '',

        ]);
    }

    public function messages()
    {

        return [
            'attachments.*.file' => 'Each attachment must be a file.',
            'attachments.*.mimes' => 'Invalid file type for attachments.',
            //'attachments.*.max' => 'Each attachment may not be larger than 500KB.',
        ];
    }

}


//        'attachments.*'  => 'Invalid file extensions: '.
//            implode(', ', self::$extension)
//    $totalSize = collect()->sum(function (UploadedFile $file){
//         return $file->getSize();
//   });


//'body'    => preg_replace_callback('/(#\w+)(?![^<]*<\/a>)/', function ($a){
//    return '<a href="/search/'.urlencode($a[0]).'">' .$a[0] .'</a>';
//}, $this->input('body') ? : ''),
