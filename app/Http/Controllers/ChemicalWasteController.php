<?php

namespace App\Http\Controllers;

use App\Events\ChemicalWasteCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Storechemical___wasteRequest;
use App\Http\Requests\Updatechemical___wasteRequest;
use App\Models\chemical___database;
use App\Models\chemical___waste;
use database;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ChemicalWasteExport;

class ChemicalWasteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $database = chemical___database::get();
        // return response()->json($getDatabase, 200, [], JSON_PRETTY_PRINT);

        return view('lab.waste', compact('database'));
    }

    public function main(Request $request) {
        $dates = explode(' to ', $request->date_range);

        if (count($dates) === 2) {
            $startDate = $dates[0];
            $endDate   = $dates[1];
        } else {
            $startDate = $dates[0];
            $endDate   = $dates[0];
        }

        $database =  chemical___waste::with('chemical')->where('area',$request->area)
                ->whereBetween('created_at', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59',
                ])
                ->get();
        return response()->json($database);
    }

    public function export(Request $request) {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        return Excel::download(
            new ChemicalWasteExport(
                $request->start_date,
                $request->end_date,
                $request->area
            ),
            'chemical-waste-'.$request->area.'-'.$request->start_date.'-'.$request->end_date.'.xlsx'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $chemical = chemical___waste::create([
                'id_chemical' => $request->id_chemical,
                'area'         => $request->area,
                'gram'          => $request->gram,
                'description'   => $request->description,
                'lot_number'    => $request->lot_number,
            ]);
            $area = $chemical->area;

            broadcast(new ChemicalWasteCreated($area));

            return response()->json([
                'alert' => 'Sukses!',
                'text' => 'Update Data Successful!',
                'color' => 'success'
            ]);
        }catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(chemical___waste $chemical___waste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(chemical___waste $chemical___waste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            $chemical = chemical___waste::findOrFail($id);

            $chemical->update([
                'gram'        => $request->gram,
                'description' => $request->description,
            ]);
            // Simpan data sebelum dihapus
            $area = $chemical->area;


            $chemical->refresh();

            broadcast(new ChemicalWasteCreated($area));

            return response()->json([
                'alert' => 'Sukses!',
                'text' => 'Update Data Successful!',
                'color' => 'success'
            ]);
        }catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            $chemical = chemical___waste::findOrFail($id);
            $area= $chemical->area;

            $chemical->delete();

            broadcast(new ChemicalWasteCreated($area));

            return response()->json([
                'alert' => 'Sukses!',
                'text' => 'Update Data Successful!',
                'color' => 'success'
            ]);
        }catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
    }
}
