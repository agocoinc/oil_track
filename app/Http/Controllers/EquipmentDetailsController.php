<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentDetailsRequest;
use App\Models\EquipmentCategory;
use App\Models\EquipmentDetails;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EquipmentDetailsController extends Controller
{
    public function index(EquipmentCategory $category)
    {
        if (Auth::user()->role != 'admin' && $category->company->id != Auth::user()->company_id) {
            return response()->json([
                'status' => false,
                'message' => 'لم يتم العثور على التفصيل'
            ]);
        }

        $details = EquipmentDetails::where(["equipment_category_id" => $category->id])->get();
        return response()->json([
            'status' => true,
            'message' => 'تم جلب التفاصيل بنجاح',
            'data' => $details,
        ]);
    }

    public function show(EquipmentDetails $detail)
    {
        if (Auth::user()->role != 'admin' && $detail->category->company->id != Auth::user()->company_id) {
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


    public function stats()
    {
        $user = Auth::user();
        $company = $user->company;
    
        if (!$company) {
            return response()->json([
                'status' => false,
                'message' => 'لا توجد شركة مرتبطة بهذا المستخدم',
                'data' => null,
            ], 404);
        }
    
        $company->load(['equipmentCategories.equipmentDetails']);
    
        $today = Carbon::today();
        $in30Days = Carbon::today()->addDays(30);
    
        $items = [];
        $totalDetails = 0;
    
        foreach ($company->equipmentCategories as $category) {
            $totalDetails += $category->equipmentDetails->count();
    
            $urgentItems = $category->equipmentDetails
                ->filter(function ($item) use ($today, $in30Days) {
                    if (!$item->date_to) return false;
    
                    $dateTo = Carbon::parse($item->date_to);
    
                    return $dateTo->lt($today) || $dateTo->between($today, $in30Days);
                });
    
            foreach ($urgentItems as $item) {
                $dateTo = Carbon::parse($item->date_to);
                $remainingDays = $dateTo->diffInDays($today, false);
    
                $items[] = [
                    'category_id' => $category->id,
                    'category_name' => $category->aname,
                    'status' => $dateTo->lt($today) ? 'expired' : 'expiring_soon',
                    'details_aname' => $item->details_aname,
                    'remaining_days' => $remainingDays,
                    'date_to' => $dateTo,
                ];
            }
        }
    
        usort($items, function ($a, $b) {
            return $a['date_to']->timestamp <=> $b['date_to']->timestamp;
        });
    
        $items = array_slice($items, 0, 10);
    
        $items = array_map(function($item) {
            unset($item['date_to']);
            return $item;
        }, $items);
    
        return response()->json([
            'status' => true,
            'message' => 'تم جلب الإحصائيات بنجاح',
            'data' => [
                'expiring_items' => $items,
                'categories_count' => $company->equipmentCategories->count(),
                'details_count' => $totalDetails,
            ]
        ]);
    }


    public function statsForAdmin()
    {
        $companies = Company::with(['equipmentCategories.equipmentDetails'])->get();

        if ($companies->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'لا توجد شركات في النظام',
                'data' => null,
            ], 404);
        }

        $today = Carbon::today();
        $in30Days = Carbon::today()->addDays(30);

        $items = [];
        $totalCategories = 0;
        $totalDetails = 0;

        foreach ($companies as $company) {
            $totalCategories += $company->equipmentCategories->count();

            foreach ($company->equipmentCategories as $category) {
                $totalDetails += $category->equipmentDetails->count();

                $urgentItems = $category->equipmentDetails
                    ->filter(function ($item) use ($today, $in30Days) {
                        if (!$item->date_to) return false;

                        $dateTo = Carbon::parse($item->date_to);

                        return $dateTo->lt($today) || $dateTo->between($today, $in30Days);
                    });

                foreach ($urgentItems as $item) {
                    $dateTo = Carbon::parse($item->date_to);
                    $remainingDays = $dateTo->diffInDays($today, false);

                    $items[] = [
                        'company_id' => $company->id,
                        'company_name' => $company->name, // assuming 'name' field exists
                        'category_id' => $category->id,
                        'category_name' => $category->aname,
                        'status' => $dateTo->lt($today) ? 'expired' : 'expiring_soon',
                        'details_aname' => $item->details_aname,
                        'remaining_days' => $remainingDays,
                        'date_to' => $dateTo,  // useful for sorting
                    ];
                }
            }
        }

        // Sort urgent items by date_to ascending
        usort($items, function ($a, $b) {
            return $a['date_to']->timestamp <=> $b['date_to']->timestamp;
        });

        // Limit to first 10
        $items = array_slice($items, 0, 10);

        // Remove date_to from output
        $items = array_map(function ($item) {
            unset($item['date_to']);
            return $item;
        }, $items);

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الإحصائيات لجميع الشركات بنجاح',
            'data' => [
                'expiring_items' => $items,
                'companies_count' => $companies->count(),
                'categories_count' => $totalCategories,
                'details_count' => $totalDetails,
            ],
        ]);
    }

    
    

}

    
