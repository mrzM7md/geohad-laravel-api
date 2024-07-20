<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerException;
use App\Exceptions\CustomeException;
use App\Models\Category;
use App\Http\Requests\StoreUpdateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Info;
use App\Models\User;
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
            $categories = Category::with('info')->withCount('info')->get();
            // $response = ((object) $categories);
            // $response->info_count = $categories->info;
            return response()->json(['success' => true, 'message' => 'تم جلب جميع العناوين', 'data' => $categories], 200);
        } catch (Exception $e) {
            throw new ServerException();
        }
    }
    

    public function find(Request $request)
    {
            $category = Category::find($request->id);
            if($category){
                return response()->json(['success' => true, 'message' => 'تم جلب المعلومة بنجاح', 'data' => $category], 200);
            }
            else {
                throw new CustomeException('لا يوجد عنوان تابع لهذا المعرف' , 404);
            }
    }

    public function getCategoriesWithoutInfos()
    {
        try {
            $categories = Category::withCount('info')->get();
            return response()->json(['success' => true, 'message' => 'تم جلب جميع العناوين', 'data' => $categories], 200);
        } catch (Exception $e) {
            throw new ServerException();
        }
    }
    
    

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreUpdateCategoryRequest $request)
{
    try {
        $category = new Category;

        $this->authorize('create', $category);

        // التحقق من وجود category_id
        if($request->category_id){
            $parentCategory = Category::find($request->category_id);
            if (!$parentCategory) {
                return response()->json(['success' => false, 'message' => 'العنوان الأب غير موجود لدينا'], 400);
            }
            if($parentCategory->category_id){
                return response()->json(['success' => false, 'message' => 'غير ممكن لإن العنوان الأب في الأصل عنوان إبن'], 409);
            }
        }

        if(Info::whereCategoryId($request->category_id)->count() > 0) {
            return response()->json(['success' => false, 'message' => 'لا يمكن لعنوان مرتبط بمعلومات أن يكون أب'], 409);
        }

        $category->title = $request->title;
        $category->category_id = $request->category_id;
        $category->save();
        $category = Category::withCount('info')->get()->find($category->category_id);
        return response()->json(['success' => true, 'message' => 'تم إضافة عنوان جديد بنجاح !', 'data' => $category ], 201);
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
                $this->authorize('update', $category);
                
                // التحقق من وجود category_id
                if($request->category_id){
                    $parentCategory = Category::find($request->category_id);
                    if (!$parentCategory) {
                        return response()->json(['success' => false, 'message' => 'العنوان الأب غير موجود لدينا'], 400);
                    }
                    if($parentCategory->category_id){
                        return response()->json(['success' => false, 'message' => 'غير ممكن لإن العنوان الأب في الأصل عنوان إبن'], 409);
                    }
                }

                $category->title = $request->title;
                $category->category_id = $request->category_id;
                $category->save();
                $category = Category::withCount('info')->get()->find($category->category_id);
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
        $category = Category::with('info')->withCount('info')->whereId($request->id)->first();

        if($category){            
            $this->authorize('delete', $category);
            $count_of_how_maney_categories_depend_on = Category::whereCategoryId($category->id)->count();
            if($count_of_how_maney_categories_depend_on > 0){
                return response()->json(['success' => false, 'message' => 'لا يمكن حذف عنوان ترتبط به عدة عناوين'], 409);
            }
            if(count($category->info) !== 0){
                return response()->json(['success' => false, 'message' => 'لا يمكن حذف عنوان ترتبط به معلومات، إما أن تقوم بحذف المعلومات، أو تغير العنوان الخاص بكل المعلومات'], 409);
            }
            $category->delete();
            return response()->json(['success' => true, 'message' => 'تم حذف العنوان بنجاح', 'data' => $category], 200);
        }else{
            throw new CustomeException('هذا العنوان غير موجود ', 404);
        }   
    }
}
