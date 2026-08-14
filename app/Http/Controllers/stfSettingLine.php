<?php

namespace App\Http\Controllers;

use DB;
use App\Models\gender_mode;
use Illuminate\Http\Request;
use App\Models\stf___pengawas;
use App\Models\balanceOrder;
use App\Models\stf___input_setting_line;
use App\Models\stf___output_setting_line;
use App\Models\stf___target;
use Illuminate\Support\Facades\Crypt;

class stfSettingLine extends Controller
{
    public function index() {
        $role = 0;
        if (auth()->user()) {
            $role = auth()->user()->role_id;
        }
        return view('stockfit.pre-paration-stockfit.setting_line',['role'=>$role]);
    }
    function main() {
        $getPengawas = stf___pengawas::get();
        $option ='<option>Pilih Pengawas</option>';
        foreach ($getPengawas as $key => $a) {
            $option.='<option value="'.$a->nik.'">'.$a->nama.'</option>';
        }
        $data = array('pengawas_list'=>$option);
        return json_encode($data);
    }
    public function getPo_list()
    {
        $getPO = balanceOrder::select('po')->groupby('po')->get();
        $getInputPO = stf___input_setting_line::select('po')->groupby('po')->get();
        $po_list='';
        $output_po_list='';
        $output_shortage_po_list='';
        foreach ($getPO as $a) {
            $po_list.='<option>'.$a->po.'</option>';
        }
        foreach ($getInputPO as $a) {
            $output_po_list.='<option>'.$a->po.'</option>';
        }
        $data = array('po_list'=>$po_list,'output_po_list'=>$output_po_list);
        return json_encode($data);
    }
    public function change_data(Request $request)
    {
        $po = $request->po;
        $cell = $request->cell;
        $wide = $request->wide;
        $shift = $request->shift;
        $jam = $request->jam;
        $pengawas = $request->pengawas;
        $form = $request->form;
        $identitasBalanceOrder = ['po'=>$po];
        //get detail
            if (isset($cell)) {
                $identitasBalanceOrder['cell']=$cell;
            }
            if (isset($wide)) {
                $identitasBalanceOrder['wide']=$wide;
            }
            $cekDuplicat = balanceOrder::where($identitasBalanceOrder)->get();
            //hapus data order yang duplicat
                $indexHapus = 0;
                //variable hapus index
                    $po_index='';
                    $wide_index='';
                    $cell_index='';
                    $qty_index=0;
                foreach ($cekDuplicat  as $key => $a) {
                    $arr = ['1'=>'po_index = '.$po_index.' , wide_index ='.$wide_index.' , cell_index ='.$cell_index,
                            '2'=>'po_index = '.$a->po.' , wide_index ='.$a->wide.' , cell_index ='.$a->cell
                            ];
                    if ($po_index != $a->po || $wide_index != $a->wide || $cell_index != $a->cell || $qty_index != $a->qty) {
                        $po_index = $a->po;
                        $wide_index = $a->wide;
                        $cell_index = $a->cell;
                        $qty_index = $a->qty;
                        $indexHapus = $a->id;
                    }else{
                        balanceOrder::where('id',$indexHapus)->delete();
                    }
                }
            //get qty incoming
            if ($form =="output") {
                $identitasBalanceOrder = ['a.po'=>$po];
                //get detail
                    if (isset($cell)) {
                        $identitasBalanceOrder['a.cell']=$cell;
                    }
                    if (isset($wide)) {
                        $identitasBalanceOrder['a.wide']=$wide;
                    }
            }
            if ($form=='input') {
                $dataForm = stf___input_setting_line::select('size',db::raw('sum(qty) as sumqty'))->where($identitasBalanceOrder)->groupby('size')->get();
                $dataAll = balanceOrder::where($identitasBalanceOrder)->get();
            }else if ($form == 'output') {
                $dataForm = db::table('stf___output_setting_line as a')->select('a.size',db::raw('sum(a.qty) as sumqty'))->where($identitasBalanceOrder)->groupby('a.size')->get();
                $query_output = db::table('stf___input_setting_line as a')->select('a.*','b.g',db::raw('sum(a.qty) as sum_qty'))
                                ->join('dc__balance_order as b', function($join){
                                    $join->on('a.po','=','b.po');
                                    $join->on('a.wide','=','b.wide');
                                    $join->on('a.cell','=','b.cell');
                                })->where($identitasBalanceOrder);
                $dataAll = $query_output->groupby('a.po','a.wide','a.cell')->get();
                $dataOutput = $query_output->groupby('a.size')->get();
            }
                $first = $dataAll->first();
                $arrQty = [];
                foreach ($dataForm as $key => $value) {
                        $arrQty[$value->size]=$value->sumqty;
                }
            //get size array
                $getSize = db::table('size_modes as a')->select('a.id')
                        ->join('gender_modes as b','a.id_size','=','b.id_size')
                        ->where('gender',$first->g)->get();
                $arrsize=[];
                    foreach ($getSize as $key => $value) {
                        $arrDatasize[]=$value->id;
                    }
            $count_data = count($dataAll);
            if ($count_data==0) {
                $data = array('err'=>'Data tidak ditemukan');
                return json_encode($data);
            }else if ($count_data>1) {
                $wide_list = '';
                $wide_status = '';
                $cell_list = '';
                $cell_status = '';
                foreach ($dataAll as $a) {
                    if ($wide_status != $a->wide) {
                        $wide_list .= '<option>'.$a->wide.'</option>';
                        $wide_status = $a->wide;
                    }
                    if ($cell_status != $a->cell) {
                        $cell_list .= '<option>'.$a->cell.'</option>';
                        $cell_status = $a->cell;
                    }
                }
                $data = array(
                    'wide_list'=>$wide_list,
                    'cell_list'=>$cell_list,
                    'count_data'=>$count_data,
                    'form'=>$form
                );
                return json_encode($data);
            }else{
                $data_qty = [];
                if ($form == "output") {
                    foreach ($dataOutput as $a) {
                        $cell = $a->cell;
                        $wide = $a->wide;
                        $style = $a->style;
                        $qty = $a->qty_order;
                        $gender = $a->g;
                        $size[]= $a->size;
                        for ($i=0; $i < count($arrDatasize) ; $i++) {
                            if ($a->size != $arrDatasize[$i]) {
                                if (empty($data_qty[$i])||$data_qty[$i]==0) {
                                    $data_qty[$i] = 0;
                                }
                            }else{
                                $data_qty[$i] = (int)$a->sum_qty;
                            }
                        }
                    }
                }else{
                    foreach ($dataAll as $a ) {
                        $cell = $a->cell;
                        $wide = $a->wide;
                        $style = $a->style;
                        $qty = $a->qty;
                        $gender = $a->g;
                        $data_qty[]=(int) $a->size_1;
                        $data_qty[]=(int) $a->size_2;
                        $data_qty[]=(int) $a->size_3;
                        $data_qty[]=(int) $a->size_4;
                        $data_qty[]=(int) $a->size_5;
                        $data_qty[]=(int) $a->size_6;
                        $data_qty[]=(int) $a->size_7;
                        $data_qty[]=(int) $a->size_8;
                        $data_qty[]=(int) $a->size_9;
                        $data_qty[]=(int) $a->size_10;
                        $data_qty[]=(int) $a->size_11;
                        $data_qty[]=(int) $a->size_12;
                        $data_qty[]=(int) $a->size_13;
                        $data_qty[]=(int) $a->size_14;
                        $data_qty[]=(int) $a->size_15;
                        $data_qty[]=(int) $a->size_16;
                        $data_qty[]=(int) $a->size_17;
                        $data_qty[]=(int) $a->size_18;
                        $data_qty[]=(int) $a->size_19;
                        $data_qty[]=(int) $a->size_20;
                        $data_qty[]=(int) $a->size_21;
                        $data_qty[]=(int) $a->size_22;
                        $data_qty[]=(int) $a->size_23;
                        $data_qty[]=(int) $a->size_24;
                        $data_qty[]=(int) $a->size_25;
                        $data_qty[]=(int) $a->size_26;
                        $data_qty[]=(int) $a->size_27;
                        $data_qty[]=(int) $a->size_28;
                        $data_qty[]=(int) $a->size_29;
                    }
                }
                for ($i=0; $i < count($arrDatasize); $i++) {
                    if ($data_qty[$i]==0) {
                        $data_qty[$i]="";
                    }
                    $arrData[$arrDatasize[$i]]=$data_qty[$i];
                }
                $arrVisual=[];
                $i=0;
                foreach ($arrData as $key => $value) {
                    $i++;
                    if ($value=="") {
                        $arrVisual[]="";
                    }else{
                        if(array_key_exists($key,$arrQty)){
                            $balance = $value-$arrQty[$key];
                            $arrVisual[]=$arrQty[$key].' of '.$value;
                            $data_qty[$i-1]=$balance;
                        }else{
                            $arrVisual[]='0 of '.$value;
                        }
                        $balance_qty[$i-1]=$value;
                    }
                }
                $data_1 = array(
                    'count_data'=> $count_data,
                    'wide'=>$wide,
                    'cell'=>$cell,
                    'style'=>$style,
                    'qty'=>$qty,
                    'gender'=>$gender,
                    'data_qty'=>$data_qty,
                    'balance_qty'=>$balance_qty,
                    'arrVisual'=>$arrVisual,
                    'form'=>$form
                );
            }
            $id_size = gender_mode::where('gender',$gender)->first();
        //set size model
            $data_size = db::table('size_modes as a')
                            ->select('a.size')
                            ->join('gender_modes as b','a.id_size','=','b.id_size')
                            ->where('b.gender',$gender)
                            ->get();
            $data_array=[];
            $data_2=[];
            foreach ($data_size as $a ) {
                array_push($data_array,$a->size);
            }
            $data_2['size']=$data_array;
        $data = array_merge($data_1,$data_2);
        return json_encode($data);
    }

    function save_input(Request $request) {
        $shift = $request->shift;
        $id_pengawas = $request->pengawas;
        $line = $request->line;
        $po = $request->po;
        $jam = $request->jam;

        $wide = $request->wide;
        $cell = $request->cell;
        $style = $request->style;
        $qty_po = $request->qty_po;
        $gender = $request->gender;
        $date = $request->date;

        $qty_balance = $request->qty_balance;
        $qty_input = $request->qty_input;
        $cekNull=0;
        //alert validation
            if ($shift == '') {
                $alert = 'gagal';
                $text = 'Data Shift Harus di isi';
                $data=array(
                    'alert'=>$alert,
                    'text'=>$text,
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            if ($line == '') {
                $alert = 'gagal';
                $text = 'Data Line Harus di isi';
                $data=array(
                    'alert'=>$alert,
                    'text'=>$text,
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            if ($po == '') {
                $alert = 'gagal';
                $text = 'Data PO Harus di isi';
                $data=array(
                    'alert'=>$alert,
                    'text'=>$text,
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            if ($date == '') {
                $alert = 'gagal';
                $text = 'Data Date Harus di isi';
                $data=array(
                    'alert'=>$alert,
                    'text'=>$text,
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            if ($id_pengawas == 'Pilih Pengawas') {
                $alert = 'gagal';
                $text = 'Data Pengawas Harus di pilih';
                $data=array(
                    'alert'=>$alert,
                    'text'=>$text,
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            if ($shift == "Pilih Shift") {
                $alert = 'gagal';
                $text = 'Data Shift Harus di pilih';
                $data=array(
                    'alert'=>$alert,
                    'text'=>$text,
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            /*cek kepemilikan line pada waktu yang bersamaan*/
            $cek_kepemilikan_line = $this->cek_kepemilikan_line($shift,$date,$line,$id_pengawas,'input');
            if ($cek_kepemilikan_line==1) {
                $data = array(
                    'alert'=>'Gagal!',
                    'text'=> 'Line Sudah Menjadi Identitas Pengawas Lain',
                    'color'=>'danger'
                );
                return json_encode($data);
            }
        //save
            //get array size
                $getSize = db::table('size_modes as a')
                                ->select('a.id','a.size')->join('gender_modes as b','a.id_size','=','b.id_size')
                                ->where('gender',$gender)->get();
                $arrsize=[];
                    foreach ($getSize as $key => $value) {
                        $arrsize[]=$value->id;
                        $arrSizeName[]=$value->size;
                    }
            for ($i=0; $i < count($qty_input); $i++) {
                if ($qty_input[$i]>$qty_balance[$i]) {
                    $data = array(
                        'alert'=>'Gagal!',
                        'text'=> 'Data Size : '.$arrSizeName[$i].' Tidak boleh lebih dari balance order',
                        'color'=>'danger'
                    );
                    return json_encode($data);
                }
                if (isset($qty_input[$i])||$qty_input[$i]=='0') {
                    $cekNull++;
                    $arrInput[]=(int)$qty_input[$i];
                    $arrSizeInput[]=$arrsize[$i];
                }
            }
            //cek data Incoming
            if ($cekNull>0) {
                try {
                    for ($i=0; $i < count($arrInput); $i++) {
                        stf___input_setting_line::insert([
                            'shift'=>$shift,
                            'id_pengawas'=>$id_pengawas,
                            'line'=>$line,
                            'jam'=>$jam,
                            'date'=>$date,
                            'po'=>$po,
                            'wide'=>$wide,
                            'cell'=>$cell,
                            'qty_order'=>$qty_po,
                            'size'=>$arrSizeInput[$i],
                            'qty'=>$arrInput[$i],
                        ]);
                    }
                } catch (\Exception $e) {
                    $data = array(
                        'alert'=>'Gagal!',
                        'text'=> $e->getMessage(),
                        'color'=>'danger'
                    );
                    return json_encode($data);
                }
            }
        $data=array(
            'alert'=>'sukses',
            'text'=>'Data berhasil di simpan',
            'color'=>'success',
            'po'=>$po,
            'cell'=>$cell,
            'wide'=>$wide,
            'shift'=>$shift,
            'jam'=>$jam,
            'pengawas'=>$id_pengawas,
            'form'=>'input'
        );
        return json_encode($data);
    }
    function update(Request $request) {
        //old
            $old_pengawas = $request->old_pengawas;
            $old_nik = $request->old_nik;
            $old_shift = $request->old_shift;
            $old_date = $request->old_date;
            $old_jam = $request->old_jam;
        // update
            $update=[];
            $pengawas = ($request->pengawas!='')?$update['id_pengawas']=$request->pengawas:'';
            $shift = ($request->shift!='')?$update['shift']=$request->shift:'';
            $date = (isset($request->date))?$update['date']=$request->date:'';
            $jam = (isset($request->jam))?$update['jam']=$request->jam:'';
            if (!$update) {
                $data = array(
                    'alert'=>'Tidak Ada!',
                    'text'=> 'Update Gagal karena tidak ada yang di update!',
                    'color'=>'info'
                );
                return json_encode($data);
            }
            try {
                stf___input_setting_line::where([
                                                'id_pengawas'=>$old_nik,
                                                'shift'=>$old_shift,
                                                'date'=>$old_date,
                                                'jam'=>$old_jam,
                                            ])
                                            ->update($update);
            } catch (\Exception $e) {
                $data = array(
                    'alert'=>'Gagal!',
                    'text'=> $e->getMessage(),
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            $data = array(
                'alert'=>'Sukses!',
                'text'=> 'Data berhasil di update!',
                'color'=>'success'
            );
            return json_encode($data);
    }
        function cek_kepemilikan_line($shift,$date,$line,$id_pengawas,$form)
        {
            if ($form == 'input') {
                $cek = stf___input_setting_line::where(['shift'=>$shift,'date'=>$date,'line'=>$line])->where('id_pengawas','!=',$id_pengawas)->get();
            }else{
                $cek = stf___output_setting_line::where(['shift'=>$shift,'date'=>$date,'line'=>$line])->where('id_pengawas','!=',$id_pengawas)->get();
            }
            if (count($cek)>1) {
                $status = 1;
            }else{
                $status = 0;
            }
            return $status;
        }
    function change_progress_bar(Request $request) {
        // search Mapping cell per jam
        $arrayMappingCell = $this->getArrayMappingCell($request->shift,$request->pengawas,$request->date);
        if ($arrayMappingCell!='Tidak ada') {
            $table='';
            for ($i=0; $i < count($arrayMappingCell['id']) ; $i++) {
                $table.='<tr class="accordion-toggle trButton" data-toggle="collapse" id="trAwal'.$arrayMappingCell['id'][$i].'">
                            <td><i onclick="functionDetail(\''.$request->shift.'\',\''.$request->pengawas.'\',\''.$request->date.'\',\''.$arrayMappingCell['id'][$i].'\')" class="fa fa-eye" aria-hidden="true"></i></td>
                            <td>Jam Ke - '.$arrayMappingCell['id'][$i].'</td>
                            <td>
                                <button type="button" onclick="functionModalUpdate(\''.$request->pengawas.'\',\''.$request->shift.'\',\''.$request->date.'\',\''.$arrayMappingCell['id'][$i].'\',\''.$arrayMappingCell['status'][$i].'\',\''.$arrayMappingCell['nama_pengawas'][$i].'\')" class="btn btn-primary btn-sm mr-2">Update</button>
                                <button type="button" onclick="functionModalDelete(\''.$request->pengawas.'\',\''.$request->shift.'\',\''.$request->date.'\',\''.$arrayMappingCell['id'][$i].'\',\''.$arrayMappingCell['status'][$i].'\')" class="btn btn-sm btn-danger mr-2">Remove</button>
                                <button type="button" onclick="functionTransfer(\''.$request->pengawas.'\',\''.$request->shift.'\',\''.$request->date.'\',\''.$arrayMappingCell['id'][$i].'\',\''.$arrayMappingCell['status'][$i].'\')" class="btn btn-sm btn-success">Transfer</button>
                            </td>
                        </tr>';
            }
            $data = array(
                'table'=>$table,
                'arrayMappingCell'=>$arrayMappingCell
            );
        }
        $data['arrayMappingCell']=$arrayMappingCell;
        return json_encode($data);
    }
    function getArrayMappingCell($shift,$pengawas,$date)
    {
        $target = 288;
        $dataTarget=array('1'=>$target,'2'=>$target,'3'=>$target,'4'=>$target,'5'=>$target,'6'=>$target,'7'=>$target,'8'=>$target,'9'=>$target,'10'=>$target);
        $data = db::table('stf___input_setting_line as a')
                    ->select('a.jam','a.date','a.line',db::raw('sum(a.qty) as sum_input'),'b.nama',db::raw('case when c.id IS NULL then "NOTYET" ELSE "DONE" END as status'))
                            ->where(['a.id_pengawas'=>$pengawas,'a.date'=>$date,'a.shift'=>$shift])
                            ->leftjoin('stf___pengawas as b','a.id_pengawas','=','b.nik')
                            ->leftjoin('stf___output_setting_line as c','a.id','=','c.id_incoming')
                            ->groupBy('a.jam')
                            ->get();
        if(count($data)==0){
            return "Tidak ada";
        }
        $arrayMappingCell = [];
        foreach ($data as $key => $a ) {
            $arrayMappingCell['id'][]=$a->jam;
            $percent = ($a->sum_input/$dataTarget[$a->jam])*100;
            $arrayMappingCell['width'][]=number_format($percent,2)."%";
            $kekurangan = $dataTarget[$a->jam]-$a->sum_input;
            if ($percent >= 100) {
                if ($a->status=="DONE") {
                        $arrayMappingCell['color'][]='bg-success';
                        $arrayMappingCell['status'][]='TRANSFER';
                        $arrayMappingCell['display'][]= $a->sum_input.'/'.$dataTarget[$a->jam].' - ('.$kekurangan.')';
                }else{
                    $arrayMappingCell['color'][]='bg-info';
                    $arrayMappingCell['status'][]= 'BARANG READY';
                    $arrayMappingCell['display'][]= $a->sum_input.'/'.$dataTarget[$a->jam].' - ('.$kekurangan.')';
                }
            }else{
                if ($a->sum_input!=$dataTarget[$a->jam]&& $a->status != "NOTYET") {
                    $arrayMappingCell['color'][]='bg-dark';
                    $arrayMappingCell['status'][]= 'TRANSFER TIDAK SESUAI TARGET';
                    $arrayMappingCell['display'][]= $a->sum_input.'/'.$dataTarget[$a->jam].' - ('.$kekurangan.')';
                }else{
                    if($percent>50){
                        $arrayMappingCell['color'][] ='bg-warning';
                        $arrayMappingCell['status'][] = 'BARANG TIDAK READY';
                        $arrayMappingCell['display'][] = $a->sum_input.'/'.$dataTarget[$a->jam].' - ('.$kekurangan.')';
                    }else{
                        $arrayMappingCell['color'][] ='bg-danger';
                        $arrayMappingCell['status'][] = 'BARANG TIDAK READY';
                        $arrayMappingCell['display'][] = $a->sum_input.'/'.$dataTarget[$a->jam].' - ('.$kekurangan.')';
                    }
                }
            }
            $arrayMappingCell['percent'][]= $percent;
            $arrayMappingCell['date'][]= $a->date;
            $arrayMappingCell['nama_pengawas'][]= $a->nama;
        }
        return $arrayMappingCell;
    }
    function detail_line(Request $request) {
        $shift = $request->shift;
        $pengawas = $request->pengawas;
        $date = $request->date;
        $jam = $request->jam;
        $data = db::table('stf___input_setting_line as a')
                    ->select('a.*','b.id as id_output','c.nama as nama_pengawas')
                    ->leftJoin('stf___output_setting_line as b','a.id','=','id_incoming')
                    ->join('stf___pengawas as c','a.id_pengawas','=','c.nik')
                    ->where(['a.id_pengawas'=>$pengawas,'a.date'=>$date,'a.shift'=>$shift,'a.jam'=>$jam])
                    ->get();
        $dataArr = [];
        foreach ($data as $key => $a) {
            $dataArr['shift'][]=$a->shift;
            $dataArr['nama_pengawas'][]=$a->nama_pengawas;
            $dataArr['date'][]=$a->date;
            $dataArr['line'][]=$a->line;
            $dataArr['po'][]=$a->po;
            $dataArr['wide'][]=$a->wide;
            $dataArr['qty_order'][]=$a->qty_order;
            $dataArr['qty'][]=$a->qty;
            $cekExist = 0;
            if ($a->id_output=="") {
                $dataArr['qty_output'][]=0;
                $dataArr['button'][]='<button type="button" onclick="functionModalDeleteDetail(\''.$a->id.'\',\''.$a->qty.'\',0)" class="btn btn-sm btn-danger">Remove</button>';
            }else{
                $dataArr['qty_output'][]=$a->qty;
                $dataArr['button'][]='';
            }
        }
        $tbody='';
        foreach ($dataArr as $key => $a) {
            for ($i=0; $i < count($dataArr[$key]); $i++) {
                $tbody.='<tr>
                            <td>'.$dataArr['shift'][$i].'</td>
                            <td>'.$dataArr['nama_pengawas'][$i].'</td>
                            <td>'.$dataArr['date'][$i].'</td>
                            <td>'.$dataArr['line'][$i].'</td>
                            <td>'.$dataArr['po'][$i].'</td>
                            <td>'.$dataArr['wide'][$i].'</td>
                            <td>'.$dataArr['qty_order'][$i].'</td>
                            <td>'.$dataArr['qty'][$i].'</td>
                            <td>'.$dataArr['qty_output'][$i].'</td>
                            <td>'.$dataArr['button'][$i].'</td>
                        </tr>';
            }
            break;
        }
        $table='
            <tr id="demoTargetDefault-'.$jam.'" class="trDetail">
                <td colspan="12">
                    <table class="table table-striped table-hover text-center" id="resultSearch">
                        <thead>
                            <tr>
                                <th>Shift</th>
                                <th>Pengawas</th>
                                <th>Date</th>
                                <th>Line</th>
                                <th>PO</th>
                                <th>Wide</th>
                                <th>Qty</th>
                                <th>Qty Input</th>
                                <th>Qty Output</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            '.$tbody.'
                        </tbody>
                    </table>
                </td>
            </tr>
        ';
        $data = array('table'=>$table);
        return json_encode($data);

    }
    function delete_input_detail(Request $request) {
        try {
            stf___input_setting_line::where('id',$request->id)->delete();
        } catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
        $data = array(
            'alert'=>'Sukses!',
            'text'=> 'Delete Berhasil!, Akan reaload dalam 5 Detik',
            'color'=>'danger'
        );
        return json_encode($data);
    }
    function delete_input(Request $request) {
        try {
            stf___input_setting_line::where([
                'id_pengawas'=>$request->pengawas,
                'shift'=>$request->shift,
                'date'=>$request->date,
                'jam'=>$request->jam
            ])->delete();
        } catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
        $data = array(
            'alert'=>'Sukses!',
            'text'=> 'Delete Berhasil!, Akan reaload dalam 5 Detik',
            'color'=>'danger'
        );
        return json_encode($data);
    }
    function transfer(Request $request) {
        $pengawas = $request->pengawas;
        $shift = $request->shift;
        $date = $request->date;
        $jam = $request->jam;

        $select = stf___input_setting_line::
                    where(['id_pengawas'=>$pengawas,'shift'=>$shift,'date'=>$date,'jam'=>$jam])->get();
        try {
            foreach ($select as $key => $a) {
                stf___output_setting_line::updateOrCreate(['id_incoming'=>$a->id],['date'=>date("Y-m-d")]);
            }
        } catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
        $data = array(
            'alert'=>'Sukses!',
            'text'=> 'Save Berhasil!',
            'color'=>'success'
        );
        return json_encode($data);
    }
    public function print(Request $request)
    {
        $this->validate($request,[
            'date' => 'required',
         ]);
        $option = $request->option;
        $date = $request->date;
        $data = db::table('stf___input_setting_line as a')
                    ->select(db::raw('a.shift,c.article,a.cell,a.po,a.wide,a.jam, a.qty_order, sum(a.qty) as qty_transfer'))
                    ->leftJoin('stf___output_setting_line as b','a.id','=','b.id_incoming')
                    ->leftJoin('dc__balance_order as c',function($join){
                        $join->on('a.po','=','c.po');
                        $join->on('a.wide','=','c.wide');
                    })->where('a.date',$date);
        if ($option == 'SHIFT A') {
            $data = $data->where(function ($query) {
                            $query->where('a.shift', '=', 'A')
                                ->orWhere('a.shift', '=', 'NON SHIFT');
                        });
        }else if($option == 'SHIFT B'){
            $data = $data->where('a.shift','B');
        }
        $data = $data->groupBy('a.po','a.wide','a.jam')->orderBy('a.cell')->orderBy('a.po')->orderBy('a.jam')->orderBy('a.wide')->get();
        $arr = [];
        $jam_ = [1,2,3,4,5,6,7,8,9,10];
        $poLast = '';
        foreach ($data as $key => $a) {
            if ($poLast!=$a->po) {
                $qtyJam = [
                    'jam-1'=>0,
                    'jam-2'=>0,
                    'jam-3'=>0,
                    'jam-4'=>0,
                    'jam-5'=>0,
                    'jam-6'=>0,
                    'jam-7'=>0,
                    'jam-8'=>0,
                    'jam-9'=>0,
                    'jam-10'=>0
                ];
                $tot_qty = 0;
            }
            $arr[$a->cell][$a->po]=[
                'cell'=>$a->cell,
                'model'=>$a->article,
                'po'=>$a->po,
                'wide'=>$a->wide,
                'qty_order'=>$a->qty_order,
            ];
            for ($i=0; $i < count($jam_); $i++) {
                if ($a->jam != $jam_[$i]) {
                    $index=$i+1;
                    $arr[$a->cell][$a->po]['jam-'.$index]=$qtyJam['jam-'.$index];
                }else{
                    $index=$i+1;
                    $qty_update = $qtyJam['jam-'.$index]=$qtyJam['jam-'.$index]+$a->qty_transfer;
                    $arr[$a->cell][$a->po]['jam-'.$index]=$qty_update;
                }
            }
            $poLast=$a->po;
        }
        dd($arr);
    }
}
