<?php
namespace App\Http\Controllers;

use App\Entities\Comment;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct()
    {

    }

    public function newsList(Request $request) {
        $page = $request->get('page',1);
        $url = 'https://m.ithome.com/api/news/newslistpageget?Tag=it&page='.$page;
        $res = http_request($url);
        return response()->json(json_decode($res));
    }

    /**
     * 评论列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComment() {
        $data = Comment::all();
        return response()->json($data);
    }

    public function postComment() {
        return response()->json(['code'=>2, 'msg'=>'提交成功！']);
    }

}