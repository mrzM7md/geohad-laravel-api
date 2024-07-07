<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerException;
use App\Exceptions\CustomeException;
use App\Models\Category;
use App\Http\Requests\StoreUpdateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\List_;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::with('info')->get();
            return response()->json(['success' => true, 'message' => 'get all categories successfully', 'data' => $categories], 200);
        } catch (Exception $e) {
            throw new CustomeException('حدث خطأ ما', 500);
        }
    }
    
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateCategoryRequest $request)
    {
        try {
            $category = new Category;
            $category->title = $request->title;
            $category->save();
            return response()->json(['success' => true, 'message' => 'تم إضافة عنوان جديد بنجاح !', 'data' => $category], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                throw new CustomeException('هذا العنوان موجود لدينا بالفعل', 409);
            }
            else{
                throw new ServerException();
            }
        }
    }

    /**
     * Display the specified resource.
    */
    public function show(Request $request)
    {
            $category = Category::with('info')->whereId($request->id)->first();
            if($category){
                return response()->json(['success' => true, 'message' => 'تم جلب العنوان بنجاح', 'data' => $category], 200);
            }else{
                throw new CustomeException('هذا العنوان غير موجود ', 404);
            }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateCategoryRequest $request)
    {
        try{
            $category = Category::with('info')->whereId($request->id)->first();
            if($category){
                $category->title = $request->title;
                $category->save();
                return response()->json(['success' => true, 'message' => 'تم تعديل العنوان بنجاح', 'data' => $category], 201);
            }else{
                throw new CustomeException('هذا العنوان غير موجود ', 404);
            }
        } catch (Exception $e) {
            throw new CustomeException('حدث خطأ ما', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $category = Category::with('info')->whereId($request->id)->first();
        if($category){
            if(count($category->info) !== 0){
                return response()->json(['success' => true, 'message' => 'لا يمكن حذف عنوان ترتبط به معلومات، إما أن تقوم بحذف المعلومات، أو تغير العنوان الخاص بكل المعلومات', 'data' => $category], 409);
            }
            $category->delete();
            return response()->json(['success' => true, 'message' => 'تم حذف العنوان بنجاح', 'data' => $category], 200);
        }else{
            throw new CustomeException('هذا العنوان غير موجود ', 404);
        }   
    }
}
