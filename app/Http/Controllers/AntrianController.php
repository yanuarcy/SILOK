<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AntrianController extends Controller
{
    public function index()
    {
        return view('dashboard.front-office');
    }

    public function getData(Request $request)
    {
        try {
            $jenisAntrian = $request->input('jenis_antrian', 'Offline');
            $tanggal = now()->toDateString();

            $query = Antrian::where('tanggal', $tanggal)
                        ->where('jenis_antrian', $jenisAntrian);

            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);

        } catch (\Exception $e) {
            \Log::error('Error in getData: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error processing request'
            ], 500);
        }
    }

    public function call(Request $request)
    {
        try {
            $antrian = Antrian::findOrFail($request->id);
            $antrian->calling_by = 'Loket ' . $request->loket;
            $antrian->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function endCall(Request $request)
    {
        try {
            $antrian = Antrian::findOrFail($request->id);
            $antrian->calling_by = '';
            $antrian->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function kirimPesan(Request $request)
    {
        try {
            $antrian = Antrian::findOrFail($request->id);
            $antrian->status = '1';
            $antrian->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function panggil(Request $request)
    {
        try {
            $antrian = Antrian::findOrFail($request->id);
            $antrian->status = '1';
            $antrian->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
