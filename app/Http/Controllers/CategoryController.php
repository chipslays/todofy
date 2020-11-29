<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Todo;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show($id, Request $request)
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

    public function edit()
    {
        return view('category', [
            'categories' => Category::where('author_id', Auth::id())->orderBy('id', 'desc')->get(),
        ]);
    }

    public function delete($id)
    {
        Todo::where('author_id', Auth::id())->where('category_id', $id)->update([
            'status' => Todo::STATUS_DELETE,
        ]);
    
        $result = Category::where('author_id', Auth::id())->where('id', $id)->delete();
        return Response::json([
            'ok' => $result,
            'html' => View::make('blocks.list_categories', [
                'categories' => Category::where('author_id', Auth::id())->orderBy('id', 'desc')->get(),
            ])->render(),
        ]);
    }

    public function add(Request $request)
    {
        $result = Category::create([
            'emoji' => $request->input('emoji'),
            'name' => $request->input('name'),
            'author_id' => Auth::id(),
        ]);
    
        return Response::json([
            'ok' => $result,
            'html' => View::make('blocks.list_categories', [
                'categories' => Category::where('author_id', Auth::id())->orderBy('id', 'desc')->get(),
            ])->render(),
        ]);
    }

    public function update(Request $request)
    {
        $result = Category::where('author_id', Auth::id())->where('id', $request->input('updateId'))->update([
            'emoji' => $request->input('emoji'),
            'name' => $request->input('name'),
        ]);
    
        return Response::json([
            'ok' => $result,
            'html' => View::make('blocks.list_categories', [
                'categories' => Category::where('author_id', Auth::id())->orderBy('id', 'desc')->get(),
            ])->render(),
        ]);
    }
}
