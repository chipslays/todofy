<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Todo;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index($method, $id, Request $request)
    {
        if (Todo::where('author_id', Auth::id())->where('id', $id)->count() == 0) {
            return Response::json([
                'ok' => false,
            ]);
        }
    
        $alert = [
            'icon' => 'success',
            'text' => '',
        ];
     
        switch ($method) {
            case 'finish': 
                $result = Todo::where('author_id', Auth::id())->where('id', $id)->update([
                    'status' => Todo::STATUS_FINISH,
                ]);
                break;
            case 'active': 
                $result = Todo::where('author_id', Auth::id())->where('id', $id)->update([
                    'status' => Todo::STATUS_ACTIVE,
                ]);
                break;
            case 'archive': 
                $result = Todo::where('author_id', Auth::id())->where('id', $id)->update([
                    'status' => Todo::STATUS_ARCHIVE,
                ]);
                break;
            case 'delete': 
                $result = Todo::where('author_id', Auth::id())->where('id', $id)->update([
                    'status' => Todo::STATUS_DELETE,
                ]);
                break;   
            case 'share': 
                $alert = [
                    'icon' => 'success',
                    'text' => 'Изменения успешно сохранены!',
                    'error' => false,
                ];
    
                $type = $request->input('type', Todo::SHARE_PRIVATE);
                $newCode = $request->input('new_code', false);
    
                $update = [
                    'shared' => in_array($type, [Todo::SHARE_PRIVATE, Todo::SHARE_LINK, Todo::SHARE_USERNAME]) ? $type : Todo::SHARE_PRIVATE,
                    'pinned' => $request->input('pinned', false) == 'true' ? Todo::PINNED : Todo::RESET,
                    'collapsed' => $request->input('collapsed', false) == 'true' ? Todo::COLLAPSED : Todo::RESET,
                ];
    
    
                if ($newCode !== $request->input('old_code', false)) {
                    $validator = Validator::make(
                        $request->all(), 
                        [
                            'new_code' => 'required|max:32|alpha_dash|string|regex:/^[a-zA-Z0-9_-]+$/u',
                        ],
                        [
                            'required' => 'Поле :attribute обязательно к заполнению.',
                            'max' => 'Поле :attribute должно быть не длиннее 32 символов.',
                            'alpha_dash' => 'Поле :attribute содержит недопустимые символы.',
                            'regex' => 'Поле :attribute содержит недопустимые символы.',
                            'string' => 'Поле :attribute должно быть строкой.',
                        ],
                        [
                            'new_code' => '"Уникальный код заметки"',
                        ]
                    );
    
                    if ($validator->fails()) {
                        $alert = [
                            'icon' => 'error',
                            'text' => $validator->errors()->first(),
                            'error' => true,
                        ];
                    } else {
                        if (!Todo::where('author_id', Auth::id())->where('code', $newCode)->exists()) {
                            $update['code'] = $newCode;
                        } else {
                            $alert = [
                                'icon' => 'warning',
                                'text' => 'Уникальный код заметки не был изменён, потому что он уже существует.',
                                'error' => true,
                            ];
                        }
                    }
                }
    
                $result = Todo::where('author_id', Auth::id())->where('id', $id)->update($update);
                break;     
            default:
                $result = false;
        }
    
        return Response::json([
            'ok' => $result,
            'html' => View::make('blocks.list_items', [
                'data' => get_list_data(),
                'author_name' => Auth::user()->username
            ])->render(),
            'alert' => $alert,
        ]);
    }
}
