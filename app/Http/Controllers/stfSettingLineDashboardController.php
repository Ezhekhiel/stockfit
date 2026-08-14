<?php

namespace App\Http\Controllers;

use DB;
use App\Models\stf___pengawas;
use Illuminate\Http\Request;
use App\Models\stf___target;
use App\Exports\settingLineData;

class stfSettingLineDashboardController extends Controller
{
    function index() {

        return view('stockfit.pre-paration-stockfit.dashboard');
    }
    function main(Request $request) {
        $when = $request->when;
        $shift = $request->shift;
        $jam_ = [1,2,3,4,5,6,7,8,9,10];
        $hourNow = date('H')-6;
        $dayNow = date("Y-m-d");
        $where=[];
        if ($when == "now" || $when == "") {
            $when = date("Y-m-d");
        }
        $where['a.date']=$when;
        if (isset($shift)) {
            $where['a.shift']=$shift;
        }
        $target = 288;
        //Get Data
            $dataTarget=array('1'=>$target,'2'=>$target,'3'=>$target,'4'=>$target,'5'=>$target,'6'=>$target,'7'=>$target,'8'=>$target,'9'=>$target,'10'=>$target);
            $data = db::table('stf___input_setting_line as a')
                        ->select('a.jam','a.shift','a.date','a.line',db::raw('sum(a.qty) as sum_input'),'b.nama',db::raw('case when c.id IS NULL then "NOTYET" ELSE "DONE" END as status'))
                        ->leftjoin('stf___pengawas as b','a.id_pengawas','=','b.nik')
                        ->leftjoin('stf___output_setting_line as c','a.id','=','c.id_incoming')
                        ->where($where);
        //header
            $data_pengawas = $data->groupBy('b.nama')->orderBy('a.shift')->orderBy('b.nama')->get();
            $arr_pengawas = [];
            $th = '';
            $width = 100/count($data_pengawas);
            foreach ($data_pengawas as $key => $a) {
                $th.='
                    <th style="font-size:110%; width:'.$width.'%">('.$a->shift.') '.$a->nama.'</th>
                ';
                $arr_pengawas[$a->nama]=$a->shift.$a->nama;
            }
        // tbody
            $data = $data->groupBy('a.jam')->get();
            if (count($data)==0) {
                $th = '<th>NOT FOUND</th>';
                $data = array(
                    'th'=>$th,
                    'alert'=>"Tidak ada",
                );
                return json_encode($data);
            }
            // $tambah = 6;
            foreach ($data as $key => $a) {
                // $jam = $a->jam+$tambah;
                // $duaJam = $jam+1;
                // if ($jam>=12) {
                //     $jam=$jam+1;
                //     $duaJam = $duaJam+1;
                // }
                // if ($jam<10) {
                //     $jam='0'.$jam;
                // }
                // if ($duaJam<10) {
                //     $duaJam= '0'.$duaJam;
                // }
                // $jam = $jam.':00-'.$duaJam.':00';
                $dataArr[$a->jam][$a->shift.$a->nama]=[
                    'jam'=>$a->jam,
                    'date'=>$a->date,
                    'nama'=>$a->nama,
                    'line'=>$a->line,
                    'sum_input'=>$a->sum_input,
                    'status'=>$a->status
                ];
            }
            foreach ($dataArr as $key => $nama) {
                if (count($nama)<count($arr_pengawas)) {
                    foreach ($arr_pengawas as $key2 => $nama2) {
                        if (!array_key_exists($nama2,$nama)) {
                            $dataArr[$key][$nama2]=[
                                'jam'=>'-',
                                'date'=>'-',
                                'nama'=>$nama2,
                                'line'=>'-',
                                'sum_input'=>0,
                                'status'=>'NOTYET'
                            ];
                        }
                    }
                }
            }
            ksort($dataArr);
            $tbody='';
            $jamistirahat = 0;
            foreach ($dataArr as $key => $nama) {
                $jamistirahat++;
                $td='';
                ksort($nama);
                $jam = 0;
                foreach ($nama as $key2 => $a) {
                    if ($a['jam']!='-') {
                        $percent= ($a['sum_input']/$dataTarget[$a['jam']])*100;
                        $value = $a['sum_input'];
                        if ($percent >= 100) {
                            if ($a['status']=="DONE") {
                                    $color='bg-success';
                            }else{
                                $color='bg-info';
                            }
                        }else{
                            if ($a['sum_input']!=$dataTarget[$a['jam']]&&$a['status']=="DONE") {
                                $color='bg-dark';
                            }else{
                                if($percent>50){
                                    $color='bg-warning';
                                }else{
                                    $color='bg-danger';
                                }
                            }
                        }
                        $jam=$a['jam'];
                        $td .='<td><div id="'.$jam.'_'.$a['nama'].'"class="circle '.$color.'">'.$value.'</div></td>';
                    }else{
                        $td .='<td><div id="'.$jam.'_'.$a['nama'].'"class="circle">0</div></td>';
                    }
                }
                $tbody .= '<tr id="colomn_jam_'.$jam.'">
                        <td class="font-weight-bold align-middle" style="font-size:120%">'.$key.'</td>
                        '.$td.'
                    </tr>';
                if ($jamistirahat == 5) {
                    $colspan=count($arr_pengawas)+1;
                    $tbody .= '<tr id="colomn_jam_'.$jam.'">
                        <td class="fw-bold bg-secondary align-middle" colspan="'.$colspan.'">ISTIRAHAT</td>
                    </tr>';
                }

            }
        $data = array(
            'th'=>$th,
            'dataArr'=>$dataArr,
            'tbody' => $tbody,
            'colspan'=>count($data_pengawas)
        );
        return json_encode($data);
    }
    function show_modal(Request $request) {
        $id = explode('_',$request->id);
        if ($request->id=='') {
            $table='
                <tr>
                    <td colspan="11">Data Not Found</td>
                </tr>
            ';
            $data = array('table'=>$table);
            return json_encode($data);
        }
        $jam = $id[0];
        $nama = $id[1];
        $date = $request->date;
        if ($date == "") {
            $date = date("Y-m-d");
        }
        $where = [
            'a.jam'=>$jam,
            'b.nama'=>$nama,
            'a.date'=>$date
        ];

        $data = db::table('stf___input_setting_line as a')
                ->select('a.*','b.nama',db::raw('case when c.id IS NULL then "NOTYET" ELSE "DONE" END as status'),'d.article','d.xfd')
                ->leftjoin('stf___pengawas as b','a.id_pengawas','=','b.nik')
                ->leftjoin('stf___output_setting_line as c','a.id','=','c.id_incoming')
                ->leftjoin('dc__balance_order as d',function($join){
                    $join->on('a.po','=','d.po');
                    $join->on('a.wide','=','d.wide');
                })
                ->where($where)->get();
        if (count($data)==0) {
            $table='
                <tr>
                    <td colspan="11">Data Not Found</td>
                </tr>
            ';
            $data = array('table'=>$table);
            return json_encode($data);
        }else{
            $table = '';
            $total = 0;
            foreach ($data as $key => $a) {
                if ($a->status == "DONE") {
                    $status = '<i class="fa fa-check-circle" style="color:green" aria-hidden="true"></i>';
                }else{
                    $status = '<i class="fa fa-minus-circle" style="color:blue" aria-hidden="true"></i>';
                }
                $table.='
                    <tr>
                        <td>'.$a->nama.'</td>
                        <td>'.$a->line.'</td>
                        <td>'.$a->jam.'</td>
                        <td>'.$a->po.'</td>
                        <td>'.$a->wide.'</td>
                        <td>'.$a->article.'</td>
                        <td>'.$a->xfd.'</td>
                        <td>'.$a->qty_order.'</td>
                        <td>'.$a->size.'</td>
                        <td>'.$a->qty.'</td>
                        <td>'.$status.'</td>
                    </tr>
                ';
                $total=$total+$a->qty;
            }
            $table.=
                '<tr>
                    <td class="text-center fw-bold" colspan="9">Total</td>
                    <td class="text-center fw-bold" colspan="1">'.$total.'</td>
                    <td></td>
                </tr>'
            ;
            $data = array('table'=>$table);
            return json_encode($data);
        }
    }

    function data(Request $request) {
        $date = $request->date;
            if ($date == "") {
                $date = date('Y-m-d');
            }
            $where['a.date']=$date;
        $shift = $request->shift;
            if (isset($shift)) {
                $where['a.shift']=$shift;
            }
        $pengawas = $request->pengawas;
            if (isset($pengawas)) {
                $where['c.nama']=$pengawas;
            }
        $line = $request->line;
            if (isset($line)) {
                $where['a.line']=$line;
            }
        $jam = $request->jam;
            if (isset($jam)) {
                $where['a.jam']=$jam;
            }
        $po = $request->po;
            if (isset($po)) {
                $where['a.po']=$po;
            }
        $wide = $request->wide;
            if (isset($wide)) {
                $where['a.wide']=$wide;
            }
        $qty_order = $request->qty_order;
            if (isset($qty_order)) {
                $where['a.qty_order']=$qty_order;
            }
        $size_name = $request->size_name;
            if (isset($size_name)) {
                $where['d.size_name']=$size_name;
            }
        $qty = $request->qty;
            if (isset($qty)) {
                $where['a.qty']=$qty;
            }
        $status = $request->status;
            if (isset($status)) {
                $where['status']=$status;
            }
        $getData = db::table('stf___input_setting_line as a')
                        ->select('a.*',db::raw('case when b.id IS null then "Ready" else "Transfer" end as status'),'c.nama as nama_pengawas','d.size as size_name')
                        ->leftJoin('stf___output_setting_line as b','a.id','=','b.id_incoming')
                        ->leftJoin('stf___pengawas as c','a.id_pengawas','=','c.nik')
                        ->leftJoin('size_modes as d','a.size','=','d.id');
        foreach ($where as $key => $a) {
            $getData = $getData->where($key,$a);
        }
            $getData = $getData->orderBy(DB::raw('FIELD(a.shift, "NON SHIFT", "A", "B")'))
                        ->orderBy('c.nama')
                        ->orderBy('a.jam')
                        ->orderBy('d.id')
                        ->get();
        $no = $r_shift = $r_nama_pengawas = $r_jam = $r_po = 0;
        $option_shift = $option_pengawas = $option_line = $option_jam = $option_po = $option_wide = $option_qty_order = $option_size_name = $option_qty = $option_status ='';
        $arr_option = [];
        foreach ($getData as $key => $a) {
            $arr_option['shift'][$a->shift]=$a->shift;
            $arr_option['pengawas'][$a->nama_pengawas]=$a->nama_pengawas;
            $arr_option['line'][$a->line]=$a->line;
            $arr_option['jam'][$a->jam]=$a->jam;
            $arr_option['po'][$a->po]=$a->po;
            $arr_option['wide'][$a->wide]=$a->wide;
            $arr_option['qty_order'][$a->qty_order]=$a->qty_order;
            $arr_option['size_name'][$a->size_name]=$a->size_name;
            $arr_option['qty'][$a->qty]=$a->qty;
            $arr_option['status'][$a->status]=$a->status;
        }
        //tes
        if (count($arr_option)>0) {
            foreach ($arr_option['shift'] as $key =>  $a) {
                $option_shift.='<option>'.$a.'</option>';
            }
            foreach ($arr_option['pengawas'] as $key =>  $a) {
                $option_pengawas.='<option>'.$a.'</option>';
            }
            foreach ($arr_option['line'] as $key =>  $a) {
                $option_line.='<option>'.$a.'</option>';
            }
            foreach ($arr_option['jam'] as $key =>  $a) {
                $option_jam.='<option>'.$a.'</option>';
            }
            foreach ($arr_option['po'] as $key =>  $a) {
                $option_po.='<option>'.$a.'</option>';
            }
            foreach ($arr_option['wide'] as $key =>  $a) {
                $option_wide.='<option>'.$a.'</option>';
            }
            foreach ($arr_option['qty_order'] as $key =>  $a) {
                $option_qty_order.='<option>'.$a.'</option>';
            }
            foreach ($arr_option['size_name'] as $key =>  $a) {
                $option_size_name.='<option>'.$a.'</option>';
            }
            foreach ($arr_option['qty'] as $key =>  $a) {
                $option_qty.='<option>'.$a.'</option>';
            }
            foreach ($arr_option['status'] as $key =>  $a) {
                $option_status.='<option>'.$a.'</option>';
            }
        }

        $shift = $table = '';
        foreach ($getData as $key => $a) {
            if ($a->status == "Ready") {
                $color = "bg-info";
            }else{
                $color = "bg-success";
            }
            $no++;
            $table.=
            '<tr>
                <td class="text-center">'.$no.'</td>
                <td class="text-center">'.$a->shift.'</td>
                <td class="text-center">'.$a->nama_pengawas.'</td>
                <td class="text-center">'.$a->line.'</td>
                <td class="text-center">'.$a->jam.'</td>
                <td class="text-center">'.$a->po.'</td>
                <td class="text-center">'.$a->wide.'</td>
                <td class="text-center">'.$a->qty_order.'</td>
                <td class="text-center">'.$a->size_name.'</td>
                <td class="text-center">'.$a->qty.'</td>
                <td class="text-center '.$color.'">'.$a->status.'</td>
            </tr>'
            ;
            $shift=$a->shift;
        }
        $data = array(
            'table'=>$table,
            'shift_list'=>$option_shift,
            'pengawas_list'=>$option_pengawas,
            'line_list'=>$option_line,
            'jam_list'=>$option_jam,
            'po_list'=>$option_po,
            'wide_list'=>$option_wide,
            'qty_order_list'=>$option_qty_order,
            'size_name_list'=>$option_size_name,
            'qty_list'=>$option_qty,
            'status_list'=>$option_status,
        );
        return json_encode($data);
    }
    function data_export(Request $request) {
        $date = $request->date;
            if ($date == "") {
                $date = date('Y-m-d');
            }
            $where['a.date']=$date;
        $shift = $request->shift;
            if (isset($shift)) {
                $where['a.shift']=$shift;
            }
        $pengawas = $request->pengawas;
            if (isset($pengawas)) {
                $where['c.nama']=$pengawas;
            }
        $line = $request->line;
            if (isset($line)) {
                $where['a.line']=$line;
            }
        $jam = $request->jam;
            if (isset($jam)) {
                $where['a.jam']=$jam;
            }
        $po = $request->po;
            if (isset($po)) {
                $where['a.po']=$po;
            }
        $wide = $request->wide;
            if (isset($wide)) {
                $where['a.wide']=$wide;
            }
        $qty_order = $request->qty_order;
            if (isset($qty_order)) {
                $where['a.qty_order']=$qty_order;
            }
        $size_name = $request->size_name;
            if (isset($size_name)) {
                $where['d.size_name']=$size_name;
            }
        $qty = $request->qty;
            if (isset($qty)) {
                $where['a.qty']=$qty;
            }
        $status = $request->status;
            if (isset($status)) {
                $where['status']=$status;
            }
        $getData = db::table('stf___input_setting_line as a')
                        ->select('a.*',db::raw('case when b.id IS null then "Ready" else "Transfer" end as status'),'c.nama as nama_pengawas','d.size as size_name')
                        ->leftJoin('stf___output_setting_line as b','a.id','=','b.id_incoming')
                        ->leftJoin('stf___pengawas as c','a.id_pengawas','=','c.nik')
                        ->leftJoin('size_modes as d','a.size','=','d.id');
        foreach ($where as $key => $a) {
            $getData = $getData->where($key,$a);
        }
            $getData = $getData->orderBy(DB::raw('FIELD(a.shift, "NON SHIFT", "A", "B")'))
                        ->orderBy('c.nama')
                        ->orderBy('a.jam')
                        ->orderBy('d.id')
                        ->get();
            $data=array('getData'=>$getData,'date'=>$date);
        // return view('exports.stockfit.pacemaker',['getData'=>$getData,'date'=>$date]);
        return (new settingLineData($data))->download($date.'-Pacemaker.xlsx');
    }
}
