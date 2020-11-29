<?php 

use Request as StaticRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Todo;

// Счетчик просмотров
function views_counter_add($item)
{
    $key = md5($item->author_id . $item->code);
    if (!session()->exists($key)) {
        $item->increment('views', 1);
        session()->put($key, true);
    }
}

// Получение заметок
function get_list_data()
{
    $categoryId = StaticRequest::input('category', false);

    switch ($categoryId) {
        case 'all': 
            $data = Todo::where('author_id', Auth::id())
                        ->where('status', '!=', Todo::STATUS_ARCHIVE)
                        ->where('status', '!=', Todo::STATUS_DELETE)
                        ->orderBy('updated_at', 'desc')
                        ->get();
            break;    
        case 'active': 
        case false:
            $data = Todo::where('author_id', Auth::id())
                        ->where('status', Todo::STATUS_ACTIVE)
                        ->orderBy('updated_at', 'desc')
                        ->get();
            break;
        case 'finish': 
            $data = Todo::where('author_id', Auth::id())
                        ->where('status', Todo::STATUS_FINISH)
                        ->orderBy('updated_at', 'desc')
                        ->get();
            break;  
        case 'archive': 
            $data = Todo::where('author_id', Auth::id())
                        ->where('status', Todo::STATUS_ARCHIVE)
                        ->orderBy('updated_at', 'desc')
                        ->get();
            break; 
        case 'public': 
            $data = Todo::where('author_id', Auth::id())
                        ->where('shared', Todo::SHARE_LINK)
                        ->where('status', Todo::STATUS_ACTIVE)
                        ->orderBy('updated_at', 'desc')
                        ->get();
            break; 
        case 'private': 
            $data = Todo::where('author_id', Auth::id())
                        ->where('shared', Todo::SHARE_PRIVATE)
                        ->where('status', Todo::STATUS_ACTIVE)
                        ->orderBy('updated_at', 'desc')
                        ->get();
            break;   
        default: 
            $data = Todo::where('author_id', Auth::id())
                        ->where('category_id', $categoryId)
                        ->where('status', Todo::STATUS_ACTIVE)
                        ->orderBy('updated_at', 'desc')
                        ->get();
    }

    $data->map(function ($item) {
        if ($item->shared == Todo::SHARE_PRIVATE) {
            return $item;
        }
        views_counter_add($item);
    });

    return $data;
}