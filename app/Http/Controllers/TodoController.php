<?php

namespace App\Http\Controllers;

use Response;
use Request as StaticRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Todo;
use App\Models\Category;

use Illuminate\Support\Str;

class TodoController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return view('list', [
                'data' => get_list_data(),
                'categories' => Category::where('author_id', Auth::id())->orderBy('id', 'desc')->get(),
                'author_name' => Auth::user()->username,
            ]);
        } else {
            return view('index');
        }
    }

    public function new()
    {
        return view('new', [
            'categories' => Category::where('author_id', Auth::id())->orderBy('id', 'desc')->get(),
        ]);
    }

    public function create(Request $request)
    {
        $result = Todo::create([
            'name' => $request->input('name'),
            'data' => $request->input('data'),
            'code' => Str::random(6),
            'status' => Todo::STATUS_ACTIVE,
            'shared' => Todo::SHARE_PRIVATE,
            'pinned' => Todo::RESET,
            'collapsed' => Todo::RESET,
            'views' => 0,
            'category_id' => $request->input('categoryId'),
            'author_id' => Auth::id(),
        ]);
    
        return Response::json([
            'ok' => $result,
        ]);
    }

    public function edit($id)
    {
        if (Todo::where('author_id', Auth::id())->where('id', $id)->count() == 0) {
            return back();
        }
    
        return view('edit', [
            'item' => Todo::where('author_id', Auth::id())->where('id', $id)->first(),
            'categories' => Category::where('author_id', Auth::id())->orderBy('id', 'desc')->get(),
        ]);
    }

    public function update($id, Request $request)
    {
        if (Todo::where('author_id', Auth::id())->where('id', $id)->count() == 0) {
            return back();
        }
    
        $result = Todo::where('author_id', Auth::id())->where('id', $id)->update([
            'name' => $request->input('name'),
            'data' => $request->input('data'),
            'status' => Todo::STATUS_ACTIVE,
            'category_id' => $request->input('categoryId'),
        ]);
    
        return Response::json([
            'ok' => $result,
        ]);
    }

    public function showUserDetailPublicNote($username, $code)
    {
        $author = User::select('id')
                    ->where('username', $username)
                    ->first();
    
        if (!$author) {
            return view('error', [
                'title' => 'Пользователь не найден',
                'icon' => '🙈',
                'text' => 'Пользователя с таким логином не существует.',
                'btn' => 'Ок, понял',
                'link' => url('/'),
            ]);
        }

        $note = Todo::where('author_id', $author['id'])
                    ->where('code', $code)
                    ->first();

        if (!$note) {
            // return abort(404);
            return view('error', [
                'title' => 'Упс!',
                'icon' => '🙄',
                'text' => 'Такой заметки не существует.',
                'btn' => 'Ок, понял',
                'link' => url('/'),
            ]);
        } 

        if ($note->shared !== Todo::SHARE_LINK) {
            return view('error', [
                'title' => 'Доступ ограничен',
                'icon' => '😯',
                'text' => 'Кажется, пользователь ограничел доступ к заметке.',
                'btn' => 'Ок, понял',
                'link' => url('/'),
            ]);
        }

        views_counter_add($note);

        return view('share_view', [
            'item' => $note,
            'categories' => Auth::check() ? Category::where('author_id', Auth::id())->orderBy('id', 'desc')->get() : false,
            'author_name' => $username,
        ]);
    }

    public function showUserPublicNotes($username)
    {
        $author = User::select('id')
                    ->where('username', $username)
                    ->first();

        if (!$author) {
            return view('error', [
                'title' => 'Пользователь не найден',
                'icon' => '🙈',
                'text' => 'Пользователя с таким логином не существует.',
                'btn' => 'Ок, понял',
                'link' => url('/'),
            ]);
        }  

        $categoryId = StaticRequest::input('category', false);

        $query = Todo::where('author_id', $author['id'])
                    ->where('shared', Todo::SHARE_LINK)
                    ->where('status', Todo::STATUS_ACTIVE)
                    ->orderBy('pinned', 'desc')
                    ->orderBy('updated_at', 'desc');

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        $data = $query->get();
        $data->map(function ($item) {
            views_counter_add($item);
        });  

        $categories = $query = Todo::select('category_id')
                                ->where('author_id', $author['id'])
                                ->where('shared', Todo::SHARE_LINK)
                                ->where('status', Todo::STATUS_ACTIVE)
                                ->orderBy('updated_at', 'desc')
                                ->get();

        $categories = $categories->map(function ($item) {
            return Category::where('id', $item->category_id)->first();
        })->unique()->sortDesc();

        return view('user_notes', [
            'data' => $data,
            'categories' => $categories,
            'shared' => true,
            'author_name' => $username,
            'is_author' => Auth::check() ? Auth::user()->username == $username : false,
        ]);
    }
}
