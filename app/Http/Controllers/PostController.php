<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    public function index()
    {
        try {
            $posts = Post::latest()->get();
            return response()->json(['status' => true, 'message' => 'All posts data', 'data' => $posts], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    public function store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'content' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return response()->json(['status' => false, 'message' => $error], 400);
            }

            $result = Post::create($request->all());
            if ($result) {
                return response()->json(['status' => true, 'message' => 'post successfully created'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    public function show($id)
    {
        try {
            $post = Post::findOrfail($id);
            return response()->json(['success' => true, 'message' => 'Single post', 'data' => $post], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $post =  Post::findOrfail($id);
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'content' => 'required'
            ]);

            if($validator->fails()){
                $error = $validator->errors()->first();
                return response()->json(['status' => false, 'message' => $error], 400);
            }

            $post->title = $request->title;
            $post->content = $request->content;
            $post->update();
            return response()->json(['status' => true, 'message' => 'Post successfully updated'], 200);

        } catch(Exception $e){
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }

    }

    public function destroy($id)
    {
        try {
            $post = Post::findOrfail($id);
            $post->delete();
            return response()->json(['status' => true, 'message'=>'Post successfully deleted'],  200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message'=>'Internal server error'],  500);
        }
    }
}
