<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::get();
        return response()->json([
            'status' => 'Success',
            'count' => $categories->count(),
            'data' => $categories,

        ], 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required|string|max:255|unique:blog_categories,name,",
            'category_image' => 'nullable|image|max:2048|mimes:png,jpg,jpeg,avif,webp'
        ]);

        if ($validator->fails()):
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => $validator->errors()
                ],
                400
            );

        endif;
        try {
            $data = $request->only(['name', 'category_image']);
            if ($request->hasFile('category_image')):
                $data['category_image'] = $request->file('category_image')->store('category_images', 'public');
            endif;
            $data['name'] = $request->name;
            $data['slug'] = Str::slug($request->name);

            BlogCategory::create($data);

            return response()->json([
                'status' => 'Success',
                'message' => 'Cataegory created successfully',
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Un expected error',
            ], 500);
        }
    }
    public function show(string $id)
    {
        //
    }
    public function update(Request $request, string $id)
    {
        $category = BlogCategory::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $id,
            'category_image' => 'nullable|image|max:2048|mimes:png,jpg,jpeg,avif,webp'
        ]);

        if ($validator->fails()):
            return response()->json(
                [
                    'status' => 'Error',
                    'message' => $validator->errors()
                ],
                422
            );
        endif;

        try {
            $data = $request->only(['name', 'category_image']);
            if ($category):
                $data['name'] = $request->name;
                $data['slug'] = Str::slug($request->name);
                if ($request->hasFile('category_image')) {
                    if ($category->category_image && Storage::disk('public')->exists($category->category_image)) {
                        Storage::disk('public')->delete($category->category_image);
                    }
                    $data['category_image'] = $request->file('category_image')->store('category_images', 'public');
                }
                $category->update($data);
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Category updated successfully',
                    'data' => $category
                ], 200);
            else:
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Category not Found'

                ], 404);
            endif;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Un expected error',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = BlogCategory::find($id);
        if ($category):
            $category->delete();
            return response()->json([
                'status' => 'Succes',
                'message' => 'Category Deleted successfully',
            ], 200);
        else:
            return response()->json([
                'status' => 'Error',
                'message' => 'Category not Found'

            ], 404);
        endif;
    }
}
