<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentDetailsRequest;
use App\Models\EquipmentCategory;
use App\Models\EquipmentDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentDetailsController extends Controller
{
    public function index(EquipmentCategory $category)
    {
        if ($category->company->id != Auth::user()->company_id) {
            return response()->json([
                'status' => false,
                'message' => 'لم يتم العثور على التفصيل'
            ]);
        }

        $details = EquipmentDetails::where($category->id)->with('category')->get();
        return response()->json([
            'status' => true,
            'message' => 'تم جلب التفاصيل بنجاح',
            'data' => $details,
        ]);
    }

    public function show(EquipmentDetails $detail)
    {
        if ($detail->category->company->id != Auth::user()->company_id) {
            return response()->json([
                'status' => false,
                'message' => 'لم يتم العثور على التفصيل'
            ]);
        }

        $detail->load(['category']);
        return response()->json([
            'status' => true,
            'message' => 'تم جلب التفصيل بنجاح',
            'data' => $detail
        ]);
    }

    public function store(StoreEquipmentDetailsRequest $request)
    {
        try {
            $data = $request->validated();

            $detail = EquipmentDetails::create($data);

            return response()->json([
                'status' => true,
                'message' => 'تم إنشاء التفصيل بنجاح',
                'data' => $detail,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء إنشاء التفصيل',
                'error' => app()->isLocal() ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function destroy(EquipmentDetails $detail)
    {
        try {
            $detail->load('category.company');

            if (! $detail->category || ! $detail->category->company) {
                return response()->json([
                    'status' => false,
                    'message' => 'التفصيل غير مرتبط بفئة أو شركة صالحة',
                ], 400); // Bad Request or custom error
            }

            if ($detail->category->company->id !== Auth::user()->company->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'غير مصرح لك بحذف هذا التفصيل',
                ], 403); // Forbidden
            }

            $detail->delete();

            return response()->json([
                'status' => true,
                'message' => 'تم حذف التفصيل بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء حذف التفصيل',
                'error' => app()->isLocal() ? $e->getMessage() : null,
            ], 500);
        }
    }
}
