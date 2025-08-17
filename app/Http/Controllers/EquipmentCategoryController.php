<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentCategoryRequest;
use App\Models\EquipmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentCategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $categories = EquipmentCategory::where('company_id', $user->company->id)->with('company')->get();
        return response()->json([
            'status' => true,
            'message' => 'تم جلب التصنيفات بنجاح',
            'data' => $categories,
        ]);
    }

    public function show(EquipmentCategory $category)
    {
        $user = Auth::user();
        if($category->company_id != $user->company->id) {
            return response()->json([
                'status' => false,
                'message' => 'لم يتم العثور على التصنيف'
            ]);
        }
        $category->load(['company', 'equipmentDetails']);
        return response()->json([
            'status' => true,
            'message' => 'تم جلب التصنيف بنجاح',
            'data' => $category
        ]);
    }

    public function store(StoreEquipmentCategoryRequest $request)
    {

        try {
            $data = $request->validated();
            $data['company_id'] = Auth::user()->company->id;

            $category = EquipmentCategory::create($data);

            return response()->json([
                'status' => true,
                'message' => 'تم إنشاء التصنيف بنجاح',
                'data' => $category,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء إنشاء التصنيف',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(EquipmentCategory $category)
    {
        try {
            if ($category->company->id !== Auth::user()->company->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'غير مصرح لك بحذف هذا التصنيف',
                ], 403); // Forbidden
            }

            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'تم حذف التصنيف بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء حذف التصنيف',
                'error' => app()->isLocal() ? $e->getMessage() : null,
            ], 500);
        }
    }

}
