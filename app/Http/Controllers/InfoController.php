<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomeException;
use App\Exceptions\ServerException;
use App\Models\Info;
use App\Http\Requests\StoreUpdateInfoRequest;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $infos = Info::with('category')->get();
            return response()->json(['success' => true, 'message' => 'تم جلب المعلومات بنجاح', 'data' => $infos], 200);
        } catch (\Exception) {
            throw new ServerException();
        }
    }


    public function getInfosByCategoryId(Request $request)
    { 
        $infos = Info::whereCategory_id($request->category_id)->get();
        if($infos){
            return response()->json(['success' => true, 'message' => 'تم جلب المعلومات بنجاح', 'data' => $infos], 200);
        }
        throw new CustomeException("لا توجد بيانات تابعة لمعرف العنوان الذي أدخلته", 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateInfoRequest $request)
    {
        try {
            $info = new Info;

            $this->authorize('create', $info);

            $info->title = $request->title;
            $info->category_id = $request->category_id;
            $info->type = $request->type;
            $info->content = $request->content;
            $info->file_path = $request->file_path;

            $info->save();

            return response()->json(['success' => true, 'message' => 'تم إضافة عنوان جديد بنجاح !', 'data' => $info], 201);
        } catch (\Exception $e) {
                throw new CustomeException($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $info = Info::with('category')->whereId($request->id)->first();
        if($info){
            return response()->json(['success' => true, 'message' => 'تم جلب المعلومة بنجاح', 'data' => $info], 200);
        }else{
            throw new CustomeException('هذه المعلومة غير موجودة', 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateInfoRequest $request, Info $info)
    {
        $info = Info::with('category')->whereId($request->id)->first();
        if($info){
            $this->authorize('update', $info);

            $info->title = $request->title;
            $info->category_id = $request->category_id;
            $info->type = $request->type;
            $info->content = $request->content;
            $info->file_path = $request->file_path;

            $info->save();
            return response()->json(['success' => true, 'message' => 'تم تعديل المعلومة بنجاح', 'data' => $info], 201);
        }else{
            throw new CustomeException('هذه المعلومة غير موجودة', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $info = Info::whereId($request->id)->first();
        if($info){
            $this->authorize('delete', $info);
            $info->delete();
            return response()->json(['success' => true, 'message' => 'تم حذف العنوان بنجاح', 'data' => $info], 200);
        }else{
            throw new CustomeException('هذه المعلومة غير موجودة', 404);
        }   
    }
}
