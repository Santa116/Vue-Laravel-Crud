<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Auth;

class PostController extends Controller
{
    /** 
    * Display a listing of resourse
    *
    * @return Response
    */
    public function index(Request $request)
    {
    	return view('posts.index');
    }

    /**
    * Get all posts
    * 
    * @return All resources
    */
    public function getAll(Request $request)
    {
    	if(request()->ajax()){
    		
    		if($request->get('activeFilter')=='all'){ 
                $posts = Post::latest()->withTrashed();
            }else if($request->get('activeFilter')=='deactive'){
                $posts = Post::latest()->onlyTrashed();
            }else{
                $posts = Post::latest();
            }

            $posts = $posts->paginate(2);
    		$response = [
    			'pagination' => [
	                'total' => $posts->total(),
	                'per_page' => $posts->perPage(),
	                'current_page' => $posts->currentPage(),
	                'last_page' => $posts->lastPage(),
	                'from' => $posts->firstItem(),
	                'to' => $posts->lastItem()
	            ],
    			'data' => $posts,
    		];
    		return response()->json($response);
		}
    	return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
    	if(!request()->ajax()){
            return redirect()->back();
        }

        $rules = [
            'title' => 'required|unique:posts,title|min:3',
            'description' => 'required|min:50',
        ];
        
        $request->validate($rules);

        $post = new Post;
        $post->title = $request->get('title');
        $post->description = $request->get('description');
       	$post->save();
        $post->deleted_at=null;
        return $post;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!request()->ajax()){
            return redirect()->back();
        }

        $rules = [
            'title' => 'required|unique:posts,title,'.$id.',id|min:3',
            'description' => 'required|min:50',
        ];
        
        $request->validate($rules);

        $post = Post::where('id',$id)->first();
        $post->title = $request->get('title');
        $post->description = $request->get('description');
       	$post->save();
        $post->deleted_at=null;
        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!request()->ajax()){
            return redirect()->back();
        }

        $post=Post::where('id',$id)->first();
        $post->delete();
        return $post;
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id){

        if(!request()->ajax()){
            return redirect()->back();
        }

        $post=Post::where('id',$id)->withTrashed()->first();
        if($post->trashed()){
            $post->restore();
        }
        return $post;
    }
}
