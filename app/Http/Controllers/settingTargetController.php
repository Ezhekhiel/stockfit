<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\stf___target;

class settingTargetController extends Controller
{
    function index() {
        return view('stockfit.setting_stockfit.setting_target');
    }
    public function search_target(Request $request){
        $date = $request->date;
        $shift = $request->shift;
        $nama = $request->nama;
        $getData = db::table('stf___pengawas as a')
                        ->select('a.nama','a.nik','b.date',
                        db::raw('COALESCE(b.shift, "NULL") AS shift'),
                        db::raw('COALESCE(b.jam_1, 0) AS jam_1'),
                        db::raw('COALESCE(b.jam_2, 0) AS jam_2'),
                        db::raw('COALESCE(b.jam_3, 0) AS jam_3'),
                        db::raw('COALESCE(b.jam_4, 0) AS jam_4'),
                        db::raw('COALESCE(b.jam_5, 0) AS jam_5'),
                        db::raw('COALESCE(b.jam_6, 0) AS jam_6'),
                        db::raw('COALESCE(b.jam_7, 0) AS jam_7'),
                        db::raw('COALESCE(b.jam_8, 0) AS jam_8'),
                        db::raw('COALESCE(b.jam_9, 0) AS jam_9'),
                        db::raw('COALESCE(b.jam_10, 0) AS jam_10')
                        )
                        ->leftJoin('stf___targets as b',function($join) use ($shift, $date){
                            $join->on('a.nik','=','b.nik')
                                ->where('b.shift','=',$shift)
                                ->where('b.date','=',$date);
                        });
        if ($nama!="") {
            $getData = $getData->where('a.nama','LIKE','%'.$nama.'%')->orderBy('a.nama')->get();
        }else{
            $getData = $getData->orderBy('a.nama')->get();
        }
            $tbody_target="";
        $i=0;
        $data_arr = [];
        foreach ($getData as $a ) {
            $i++;
            $data_arr=[
                        'date'=>$a->date,
                        'shift'=>$a->shift,
                        'nama'=>$a->nama,
                    ];
            $tbody_target.='<tr>
                                <td>'.$i.'</td>
                                <td>'.$a->nama.'</td>
                                <td>'.$a->shift.'</td>
                                <td onclick="editTarget(\''.$a->nik.'\',\''.$a->nama.'\',\'1\')">'.$a->jam_1.'</td>
                                <td onclick="editTarget(\''.$a->nik.'\',\''.$a->nama.'\',\'2\')">'.$a->jam_2.'</td>
                                <td onclick="editTarget(\''.$a->nik.'\',\''.$a->nama.'\',\'3\')">'.$a->jam_3.'</td>
                                <td onclick="editTarget(\''.$a->nik.'\',\''.$a->nama.'\',\'4\')">'.$a->jam_4.'</td>
                                <td onclick="editTarget(\''.$a->nik.'\',\''.$a->nama.'\',\'5\')">'.$a->jam_5.'</td>
                                <td onclick="editTarget(\''.$a->nik.'\',\''.$a->nama.'\',\'6\')">'.$a->jam_6.'</td>
                                <td onclick="editTarget(\''.$a->nik.'\',\''.$a->nama.'\',\'7\')">'.$a->jam_7.'</td>
                                <td onclick="editTarget(\''.$a->nik.'\',\''.$a->nama.'\',\'8\')">'.$a->jam_8.'</td>
                                <td onclick="editTarget(\''.$a->nik.'\',\''.$a->nama.'\',\'9\')">'.$a->jam_9.'</td>
                                <td onclick="editTarget(\''.$a->nik.'\',\''.$a->nama.'\',\'10\')">'.$a->jam_10.'</td>
                            </tr>';
        }
        $data = array('data_arr'=>$data_arr,'tbody_target'=>$tbody_target);
        return json_encode($data);
    }
    public function editTarget(Request $request)
    {
        $nik = $request->nik;
        $date = $request->date;
        if ($date == 0) {
            $date = date('Y-m-d');
        }
        $shift = $request->shift;
        $jam = $request->jam;
        $target = $request->target;
        try {
            stf___target::updateOrCreate(['nik'=>$nik,'date'=>$date,'shift'=>$shift],['jam_'.$jam=>$target]);
        } catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
        $data = array(
            'alert'=>'sukses',
            'text'=> 'Data pengawas sudah tersimpat',
            'color'=>'success'
        );
        return json_encode($data);
    }
}
