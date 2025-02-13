<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Ward;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{

    public function getRegions()
    {
        try {
            Log::info('Getting regions');
            $regions = Region::select('region_id', 'region_name')
                ->orderBy('region_name')
                ->get();
            Log::info('Regions fetched', ['count' => $regions->count()]);
            return response()->json($regions);
        } catch (\Exception $e) {
            Log::error('Error getting regions', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to load regions'], 500);
        }
    }
    /**
     * Get districts for a region
     */
    public function getDistricts($region)
    {
        try {
            Log::info('Getting districts for region', ['region_id' => $region]);
            
            // Check if region exists
            if (!Region::where('region_id', $region)->exists()) {
                Log::warning('Region not found', ['region_id' => $region]);
                return response()->json(['error' => 'Region not found'], 404);
            }

            $districts = District::where('region_id', $region)
                ->select('district_id', 'district_name')
                ->orderBy('district_name')
                ->get();

            Log::info('Districts fetched', [
                'region_id' => $region,
                'count' => $districts->count()
            ]);

            return response()->json($districts);

        } catch (\Exception $e) {
            Log::error('Error getting districts', [
                'region_id' => $region,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to load districts'], 500);
        }
    }

    /**
     * Get wards for a district
     */
    public function getWards($district)
    {
        try {
            Log::info('Getting wards for district', ['district_id' => $district]);
            
            // Check if district exists
            if (!District::where('district_id', $district)->exists()) {
                Log::warning('District not found', ['district_id' => $district]);
                return response()->json(['error' => 'District not found'], 404);
            }

            $wards = Ward::where('district_id', $district)
                ->select('ward_id', 'ward_name')
                ->orderBy('ward_name')
                ->get();

            Log::info('Wards fetched', [
                'district_id' => $district,
                'count' => $wards->count()
            ]);

            return response()->json($wards);

        } catch (\Exception $e) {
            Log::error('Error getting wards', [
                'district_id' => $district,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to load wards'], 500);
        }
    }
}
