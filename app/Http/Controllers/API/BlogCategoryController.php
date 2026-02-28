<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $categorys = BlogCategory::get();

       return response()->json([
        'status'=>'Success',
        'count'=>$categorys->count(),
        'data'=> $categorys,
       ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>"required|string|max:255|unique:blog_categories,name,",
        ]);

        if($validator->fails()):
            return response()->json(
               [
                 'status'=> 'Error',
                 'message'=>$validator->errors()
               ],
               400
            );

        endif;

        $data['name'] = $request->name;
        $data['slug']= Str::slug($request->name);

        BlogCategory::create($data);

        return response()->json([
            'status'=>'Success',
            'message'=>'Cataegory created successfully',
            'data'=> $data
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255|unique:blog_categories,name,'.$id,
        ]);

        if($validator->fails()):
            return response()->json(
               [
                 'status'=> 'Error',
                 'message'=>$validator->errors()
               ],
               422
            );
        endif;

        $category = BlogCategory::find($id);
        if($category):
             $category->name = $request->name;
             $category->slug = Str::slug($request->name);
             $category->save();
             
             return response()->json([
                 'status'=> 'Succes',
                 'message'=>'Category updated successfully',
                 'data'=>$category

            ], 200);
        else:
            return response()->json([
                 'status'=> 'Error',
                 'message'=>'Category not Found'

            ], 404);
        endif;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
     $category = BlogCategory::find($id);
      if($category):

           $category->delete();
             return response()->json([
                 'status'=> 'Succes',
                 'message'=>'Category Deleted successfully',
            ], 200);
        else:
            return response()->json([
                 'status'=> 'Error',
                 'message'=>'Category not Found'

            ], 404);
        endif;
    }
}
