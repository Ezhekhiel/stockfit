<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\size_mode;
use Illuminate\Http\Request;
use App\Models\stf___pengawas;
use App\Models\tooling__model;
use App\Models\tooling__versi;
use App\Models\tooling__remark;
use App\Models\tooling___padPress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\tooling___pad_press_history;

class padPressController extends Controller
{
    function index() {
        return view('tooling.padPress.content.output');
    }
    public function main(Request $request) {
        if (Auth::user()) {
            $id_user = Auth::user()->id;
        }else{
            $id_user = 0;
        }
        $model = $request->model;
        $where = $request->where;
        if ($where != '') {
            $get = db::table('tooling___pad_press as a')
                        ->select(db::raw("max(gender) as gender,max(no_rack) as no_rack,max(a.remark) as remark,max(a.version) as version,max(a.id) as id, a.id_barcode,max(a.model) as model,max(a.size) as size,max(a.side) as side,max(a.pembuatan_pad_press) as pad_press_production, max(a.location) as location, max(a.status) as status, max(a.created_at) as created_at,case when (max(b.reason)='Audit') then max(b.created_at) else NULL end as audit_terakhir"))
                        ->where(function ($query) use ($where, $model) {
                            if ($model != '') {
                                $query->orwhere('a.model', 'like', '%'.$where.'%');
                            }else{
                                $query->orwhere('a.id_barcode', 'like', '%'.$where.'%');
                            }
                        })
                        ->leftJoin('tooling___pad_press_history as b',function($join)
                        {
                            $join->on(db::raw('a.id_barcode'), '=', db::raw('b.id_barcode'));
                            $join->on(db::raw('b.reason'),'=',db::raw("'Audit'"));
                        })
                        ->groupby('a.id_barcode')->get();
        }else{
            $get = db::table('tooling___pad_press as a')
                    ->select(db::raw("max(gender) as gender, max(no_rack) as no_rack,max(a.remark) as remark,max(a.version) as version,max(a.id) as id, a.id_barcode,max(a.model) as model,max(a.size) as size,max(a.side) as side, max(a.location) as location, max(a.status) as status,max(a.pembuatan_pad_press) as pad_press_production, max(a.created_at) as created_at,case when (max(b.reason)='Audit') then max(b.created_at) else NULL end as audit_terakhir"))
                    ->leftJoin('tooling___pad_press_history as b',function($join)
                    {
                        $join->on(db::raw('a.id_barcode'), '=', db::raw('b.id_barcode'));
                        $join->on(db::raw('b.reason'),'=',db::raw("'Audit'"));
                    })
                    ->groupby('a.id_barcode')->get();
        }
        $table = '';
        $no = 0;
        $diff_month_arr = [];
        foreach ($get as $key => $a) {
            $no++;
            //location
                if ($a->location == "WAREHOUSE") {
                    $button = '<td><button type="button" class="btn btn-secondary" onclick="showRackMap(\''.$a->no_rack.'\')">'.$a->location.' - '.$a->no_rack.'</button></td>';
                }else{
                    $button = '<td>'.$a->location.'</td>';
                }
            //remark
                if ($a->remark != '-') {
                    $remarks_=' ('.$a->remark.')';
                }else{
                    $remarks_=$a->remark;
                }
            //status
                if ($a->status == 'Pass') {
                    $color='bg-success';
                }else if ($a->status == 'Fail') {
                    $color='bg-danger';
                }else{
                    $color='';
                }
            //audit terakhir
                // return date("Y-m-d",strtotime("-3 month", strtotime($a->audit_terakhir)));
                $date_terakhir = ($a->audit_terakhir)?strtotime($a->audit_terakhir):'-';
                $today = strtotime("+3 month", strtotime($a->audit_terakhir));
                if ($date_terakhir!='-') {
                    $diff = abs($date_terakhir-$today);
                    $years = floor($diff / (365*60*60*24));
                    $diff_month = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                    if ($diff_month > 2 && $date_terakhir!=false) {
                        $color_diff_month = 'bg-danger';
                    }else{
                        $color_diff_month = '';
                    }
                    $date_terakhir_show = date("Y-m-d",$date_terakhir);
                }else{
                    $color_diff_month='bg-secondary';
                    $date_terakhir_show = '-';
                }
            //button
            if ($id_user == 0) {
                $button_action = '';
            }else{
                $button_action = '
                            <td>
                                <button type="button" class="btn btn-primary" onclick="updateModal(\''.$a->id_barcode.'\')">Ubah</button>
                                <button type="button" class="btn btn-danger" onclick="deleteModal(\''.$a->id_barcode.'\')">Hapus</button>
                            </td>
                        ';
            }
            $table.='
                <tr>
                    <td>'.$no.'</td>
                    <td>'.$a->id_barcode.'</td>
                    <td>'.$a->pad_press_production.'</td>
                    <td>'.$a->gender.' '.$a->model.' '.$a->version.$remarks_.'</td>
                    <td>'.$a->size.'</td>
                    <td>'.$a->side.'</td>
                    '.$button.'
                    <td class="'.$color.'">'.$a->status.'</td>
                    <td class="'.$color_diff_month.'">'.$date_terakhir_show.'</td>
                    '.$button_action.'
                </tr>
            ';
        }
        if (count($get) == 0) {
            $table ='
                <tr>
                    <td colspan="9">Data Not Found</td>
                </tr>
            ';
        }
        $data = array('table'=>$table);
        return json_encode($data);
    }
    function delete(Request $request) {
        $id_barcode = $request->id_barcode;
        try{
            tooling___padPress::where('id_barcode',$id_barcode)->delete();
            tooling___pad_press_history::where('id_barcode',$id_barcode)->delete();
        }catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
        $data = array(
            'alert'=>'Sukses!',
            'text'=> 'Hapus Data Berhasil!',
            'color'=>'success'
        );
        return json_encode($data);
    }
    function print(Request $request) {
        $total = $request->total;
        $total = $total*2;
        $save=[];
        for ($i=0; $i < $total; $i++) {
            $arr_save = $this->insertPadPress();
            if ($arr_save=='') {
                $i--;
            }else{
                $save[]=$arr_save;
            }
        }

        $no = $halaman = 0;
        foreach ($save as $key => $value) {
            $create = tooling___padPress::create(['id_barcode'=>$value]);
            if ($no == 40) {
                $no=0;
                $halaman++;
            }
            $arr_hal[$halaman][]=$value;
            $no++;
        }
        return view('tooling.padPress.print.barcode')->with(['data_hal'=>$arr_hal]);
    }
    function insertPadPress() {
        // do {
        //     $number = 'PWN-STF-'.random_int(1000000, 9999999);
        // } while (tooling___padPress::where("id_barcode", "=", $number)->first());

        // if (!$cek) {
        //     return  $number;
        // }else{
        //     return  '';
        // }
        $number = 'PWN-STF-'.mt_rand(1000000000, 9999999999);
        // $number = mt_rand(1000000000, 9999999999); // better than rand()

        if ( $this->barcodeNumberExists($number)) {
            return generateBarcodeNumber();
        }
        return $number;
    }
    function barcodeNumberExists($number) {
        // query the database and return a boolean
        // for instance, it might look like this in Laravel
        return tooling___padPress::where("id_barcode",$number)->exists();
    }
    function scan($id) {
        return view('tooling.padPress.content.scan',['id'=>$id]);
    }
    function scan_main(Request $request) {
        $id = $request->id;
        $get = tooling___padPress::where(['id_barcode'=>$id])->first();
        if ($get == "") {
            tooling___padPress::create(['id_barcode'=>$id]);
            $data = array('alert'=>'save database');
            return $data;
        }
        $arr_table = [];
        $arr_table['id_barcode']=$id;
        $model_option = '<option>'.$get->model.'</option>';
        $version_option = '<option>'.$get->version.'</option>';
        $remark_option = '<option>'.$get->remark.'</option>';
        $gender_option = '<option>'.$get->gender.'</option>';
        $size_option = '<option>'.$get->size.'</option>';
        $side_option = '<option>'.$get->side.'</option>';
        $location_option = '<option>'.$get->location.'</option>';
        $location_option .= '
                <option value="WAREHOUSE">WAREHOUSE</option>
                <option value="DEVELOPMENT">DEVELOPMENT</option>';
        if ($get->location == 'WAREHOUSE') {
            $status = $get->location;
        }else{
            $status = 'Outside';
        }
        $status_option = '<option>'.$get->status.'</option>';
        $no_rack_option = '<option>'.$get->no_rack.'</option>';
        // select option size
            $size = size_mode::get();
            foreach ($size as $key => $a) {
                $size_option.= '<option>'.$a->size.'</option>';
            }
        //detail
            //get to database mysql
                $json = Cache::remember('database', now()->addMinutes(60), function () {
                    return file_get_contents('http://10.2.11.4:5052/api/database');
                });
                $dataBase = json_decode($json);
            //get data model remark dan version
                $data_model = tooling__model::get();
                $data_remark = tooling__remark::get();
                $data_version = tooling__versi::get();
                $model_arr = $gender_arr = $g_arr = [];
                foreach ($dataBase->data as $key => $a) {
                    // preg_match_all("/[A-Z]+|\d+/", $a->style, $matches);

                    // if (count($matches[0])>1) {
                    //     $model_arr[$matches[0][1]]=$matches[0][1];
                    // }else{
                    //     $model_arr[$matches[0][0]]=$matches[0][0];
                    // }
                    $gender_arr[$a->g]=$a->g;
                    $g_arr[$a->g]=$a->g;
                }
                foreach ($data_model as $key => $a) {
                    $model_option .= '<option>'.$a->model.'</option>';
                }
                foreach ($data_remark as $key => $a) {
                    $remark_option .= '<option>'.$a->remark.'</option>';
                }
                foreach ($data_version as $key => $a) {
                    $version_option .= '<option>'.$a->versi.'</option>';
                }
                foreach ($gender_arr as $key => $a) {
                    $gender_option .= '<option>'.$a.'</option>';
                }

            //get to sql pengawas
                $getpengawas = Cache::remember('data_pengawas', now()->addMinutes(10), function () {
                   return stf___pengawas::get();
                });
                foreach ($getpengawas as $key => $a) {
                    $location_option .= '<option>'.$a->nama.'</option>';
                }
            $status_option .= '
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                            ';

            $side_option.='
                <option value="Left">Left</option>
                <option value="Right">Right</option>
            ';
            $no_rack_option.='
                <option value="1A">Rack 1A</option>
                <option value="1B">Rack 1B</option>
                <option value="2A">Rack 2A</option>
                <option value="2B">Rack 2B</option>
                <option value="3A">Rack 3A</option>
                <option value="3B">Rack 3B</option>
                <option value="4A">Rack 4A</option>
                <option value="4B">Rack 4B</option>
                <option value="5A">Rack 5A</option>
                <option value="5B">Rack 5B</option>
                <option value="6A">Rack 6A</option>
                <option value="6B">Rack 6B</option>
                <option value="7A">Rack 7A</option>
                <option value="7B">Rack 7B</option>
                <option value="8A">Rack 8A</option>
                <option value="8B">Rack 8B</option>
                <option value="9A">Rack 9A</option>
                <option value="9B">Rack 9B</option>
                <option value="10A">Rack 10A</option>
                <option value="10B">Rack 10B</option>
                <option value="11A">Rack 11A</option>
                <option value="11B">Rack 11B</option>
                <option value="12A">Rack 12A</option>
                <option value="12B">Rack 12B</option>
            ';
        //get history
            $get_history = tooling___pad_press_history::where(['id_barcode'=>$id])->get();
            $table_history = '';
            $no = 0;
            foreach ($get_history as $key => $a) {
                $no ++;
                ($a->reason == 'Moving') ? $color='bg-primary text-light fw-bold': $color='bg-danger text-light fw-bold';
                $table_history .='
                    <tr>
                        <td>'.$no.'</td>
                        <td>'.$a->location.'</td>
                        <td>'.$a->status.'</td>
                        <td class="'.$color.'">'.$a->reason.'</td>
                        <td>'.date_format($a->created_at,"Y-m-d H:i:s").'</td>
                    </tr>
                ';
            }

        $data = array(
            'arr_table'=>$arr_table,
            'model_option'=>$model_option,
            'gender_option'=>$gender_option,
            'size_option'=>$size_option,
            'side_option'=>$side_option,
            'status_option'=>$status_option,
            'location_option'=>$location_option,
            'no_rack_option'=>$no_rack_option,
            'table_history'=>$table_history,
            'remark_option'=>$remark_option,
            'version_option'=>$version_option,
            'status'=>$status
        );
        return json_encode($data);
    }
    function update(Request $request) {
        $id=$request->id;
        $arr = [];
        $model = $request->model;
        ($model != '') ? $arr['model']=$model: '';
        $gender = $request->gender;
        ($gender != '') ? $arr['gender']=$gender: '';
        $version = $request->version;
        ($version != '') ? $arr['version']=$version: '';
        $remark = $request->remark;
        ($remark != '') ? $arr['remark']=$remark: '';
        $size = $request->size;
        ($size != '') ? $arr['size']=$size: '';
        $side = $request->side;
        ($side != '') ? $arr['side']=$side: '';
        $location = $request->location;
        ($location != '') ? $arr['location']=$location: '';
        $no_rack = $request->no_rack;
        ($no_rack != '') ? $arr['no_rack']=$no_rack: '';
        $status = $request->status;
        ($status != '') ? $arr['status']=$status: '';
        $pad_press_production = $request->pad_press_production;
        ($pad_press_production != '') ? $arr['pembuatan_pad_press']=$pad_press_production: '';
        $reason = $request->reason;
        if (count($arr)<0) {
            $data=array(
                'text'=>'Tidak ada data yang di ubah!',
                'alert'=>'Error',
                'color'=>'danger'
            );
            return json_encode($data);
        }
        try{
            tooling___padPress::where('id_barcode',$id)->update($arr);
        }catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
        $get = tooling___padPress::where(['id_barcode'=>$id])->first();
        $data_insert = [
            'id_barcode'=>$id,
            'pic'=>'-',
            'location'=>$get->location,
            'status'=>$get->status,
            'reason'=>$reason,
        ];
        try {
            tooling___pad_press_history::create($data_insert);
        } catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
        if ($location != 'WAREHOUSE') {
            //hapus nomer rackk
            try {
                tooling___padPress::where(['id_barcode'=>$id])->update(['no_rack'=>'-']);
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
            'text'=>'Data berhasil di ubah!',
            'alert'=>'success',
            'color'=>'success'
        );
        return json_encode($data);
    }
}
