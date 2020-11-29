<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Todo;
use App\Models\Category;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data, 
            [
                'username' => ['required', 'string', 'min:4', 'max:24', 'unique:users', 'alpha_num'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
            [
                'required' => 'Это поле обязательно к заполнению',
                'string' => 'Это поле должно быть строкой',
                'max' => 'Максимальная длина max символа(ов)',
                'unique' => 'Пользователь с таким логином уже существует',
                'alpha_num' => 'Поле содержит некорректные символы',
                'min' => 'Минимальная длина :min символа(ов)',
                'confirmed' => 'Пароли не совпадают'
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);

        $work = Category::create([
            'emoji' => '💼',
            'name' => 'Работа',
            'author_id' => $user->id,
        ]);

        $home = Category::create([
            'emoji' => '🏡',
            'name' => 'Домашние дела',
            'author_id' => $user->id,
        ]);

        $isNewYear = in_array(now()->month, [6, 7, 8, 9, 10, 11, 12]);

        $plans = Category::create([
            'emoji' => $isNewYear ? '🎅' : '🌞',
            'name' => $isNewYear ? 'Планы на Новый Год' : 'Планы на лето',
            'author_id' => $user->id,
        ]);

        $todofy = Category::create([
            'emoji' => '🎈',
            'name' => 'TODOfy',
            'author_id' => $user->id,
        ]);

        Todo::create([
            'name' => 'Добро пожаловать! 🥳',
            'data' => '{"time":1606479476434,"blocks":[{"type":"header","data":{"text":"Привет&nbsp;👋","level":5}},{"type":"paragraph","data":{"text":"&nbsp;&nbsp;"}},{"type":"paragraph","data":{"text":"<b>TODOfy</b>&nbsp;- это open-source проект, построенный на базе фреймворка&nbsp;<a href=\"https://laravel.com/\">Laravel</a>&nbsp;для back-end&nbsp;и&nbsp;<a href=\"https://getbootstrap.com/\">Bootstrap</a>&nbsp;для front-end части.&nbsp;&nbsp;"}},{"type":"paragraph","data":{"text":"&nbsp;&nbsp;"}},{"type":"paragraph","data":{"text":"Исходный код проекта доступен на <a href=\"https://github.com/aethletic/todofy\">GitHub</a>."}},{"type":"paragraph","data":{"text":"&nbsp;&nbsp;"}},{"type":"paragraph","data":{"text":"<b>Ключевые особенности:</b>"}},{"type":"list","data":{"style":"unordered","items":["Полноценный редактор текста <a href=\"https://editorjs.io/\">EditorJS</a>, поддерживает&nbsp;<b>жирный</b>, <i>наклонный,</i>&nbsp;<u class=\"cdx-underline\">подчеркнутый</u>, <mark class=\"cdx-marker\">выделяемый </mark>текст, списки и заголовки.","Возможность делиться заметками, использовать TODOfy как блог платформу.&nbsp;","Полная оптимизация для мобильных устройств."]}},{"type":"paragraph","data":{"text":"&nbsp;&nbsp;"}},{"type":"paragraph","data":{"text":"Кстати, эта заметка <b>уже опубликована и закреплена</b>,<b>&nbsp;</b>её можно увидеть отдельно <a href=\"' . url("/@{$user->username}/welcome") . '\">по ссылке здесь</a>&nbsp;или на <a href=\"' . url("/@{$user->username}") . '\">вашей публичной странице</a>."}},{"type":"paragraph","data":{"text":"Публичные заметки имеют&nbsp;в заголовке иконку 📢 , а если она закрплена, то иконку&nbsp;📌 , заметки которые доступны только вам (приватные), имеют иконку 🔒."}},{"type":"paragraph","data":{"text":"&nbsp;"}},{"type":"paragraph","data":{"text":"<b>Приятной работы вместе с TODOfy</b>&nbsp;🙃"}}],"version":"2.19.0"}',
            'code' => 'welcome',
            'status' => Todo::STATUS_ACTIVE,
            'shared' => Todo::SHARE_LINK,
            'pinned' => 1,
            'collapsed' => 1,
            'category_id' => $todofy->id,
            'author_id' => $user->id,
        ]);

        Todo::create([
            'name' => 'Сходить за продуктами 🛒',
            'data' => '{"time":1606308785346,"blocks":[{"type":"paragraph","data":{"text":"<b>Список покупок:</b>"}},{"type":"list","data":{"style":"ordered","items":["Молоко&nbsp;🥛","Крабовые палочки 1 шт.&nbsp;🦀","Кукуруза 1 шт.&nbsp;🌽","Огурцы&nbsp;🥒","Апельсиновый соооок&nbsp;🍹"]}}],"version":"2.19.0"}',
            'code' => 'products',
            'category_id' => $home->id,
            'author_id' => $user->id,
        ]);

        Todo::create([
            'name' => $isNewYear ? 'Слепить снеговика ⛄' : 'Съездить на море 🌊',
            'data' => '{"time":1606308447146,"blocks":[],"version":"2.19.0"}',
            'code' => 'snowman',
            'category_id' => $plans->id,
            'author_id' => $user->id,
        ]);
        
        Todo::create([
            'name' => 'Написать заявление на отпуск 🍻',
            'data' => '{"time":1606308447146,"blocks":[],"version":"2.19.0"}',
            'code' => 'holiday',
            'category_id' => $work->id,
            'author_id' => $user->id,
        ]);

        return $user;
    }
}
