<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyStructure;

class CompanyController extends Controller
{
    public function index() {
        $companies = Company::all();

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الشركات بنجاح',
            'data' => $companies,
        ]);
    }

    public function storeStructure(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $data = $request->input('structure');

        if (empty($data)) {
            $data = new \stdClass();
        } else {
            if (is_string($data)) {
                $data = json_decode($data, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json(['error' => 'Invalid JSON format'], 422);
                }
            }
        }

        $validateStructure = function ($node) use (&$validateStructure) {
            if (!is_array($node)) {
                return false;
            }

            if (!isset($node['tradingName']) || !is_string($node['tradingName'])) {
                return false;
            }

            if (isset($node['organizationChildRelationship'])) {
                if (!is_array($node['organizationChildRelationship'])) {
                    return false;
                }
                foreach ($node['organizationChildRelationship'] as $child) {
                    if (!$validateStructure($child)) {
                        return false;
                    }
                }
            }

            return true;
        };

        if (is_object($data)) {
        } elseif (is_array($data)) {
            if (!$validateStructure($data)) {
                return response()->json(['error' => 'Invalid structure format'], 422);
            }
        } else {
            return response()->json(['error' => 'Invalid data format'], 422);
        }

        $jsonToStore = is_string($data) ? $data : json_encode($data);

        $companyStructure = CompanyStructure::updateOrCreate(
            ['company_id' => $request->company_id],
            ['structure' => $jsonToStore]
        );

        return response()->json([
            'message' => 'Structure saved successfully',
            'data' => $companyStructure,
        ]);
    }

    public function getStructureByCompanyId($companyId)
    {
        $companyStructure = CompanyStructure::where('company_id', $companyId)->first();

        if (!$companyStructure) {
            return response()->json(new \stdClass());
        }

        $structure = json_decode($companyStructure->structure);

        return response()->json($structure);
    }

}
