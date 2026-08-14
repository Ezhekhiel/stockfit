<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\stf___pengawas;

class registerPengawasController extends Controller
{
    function index() {
        return view('stockfit.setting_stockfit.register_pegawas');
    }
    public function data_pengawas()
    {
        $getDataPengawas = stf___pengawas::get();
        $tbody_pengawas = '';
        $list_pengawas = '<option value="">Pilih Pengawas</option>';
        $no=0;
        foreach ($getDataPengawas as $key => $a) {
            $no++;
            $tbody_pengawas.='
                <tr>
                    <td>'.$no.'</td>
                    <td>'.$a->nik.'</td>
                    <td>'.$a->nama.'</td>
                    <td>
                        <div class="row">
                            <div class="col-6 bg-primary" style="cursor:pointer" onclick="dataPengawasUpdate('.$a->id.')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
                            <div class="col-6 bg-danger" style="cursor:pointer" onclick="dataPengawasDelete('.$a->id.')"><i class="fa fa-trash-o" aria-hidden="true"></i></div>
                        </div>
                    </td>
                </tr>
            ';
            $list_pengawas.='<option value="'.$a->nik.'">'.$a->nama.'</option>';
        }
        $data = array('tbody_pengawas'=>$tbody_pengawas,'list_pengawas'=>$list_pengawas);
        return json_encode($data);
    }
    public function get_data_pengawas(Request $request)
    {
        $id = $request->id;
        $getDataPengawas = stf___pengawas::where('id',$id)->get();
        foreach ($getDataPengawas as $key => $a) {
            $arr= ['id'=>$a->id,'nik'=>$a->nik,'nama'=>$a->nama];
        }
        $data = array('arr'=>$arr);
        return json_encode($data);
    }
    public function save_pengawas(Request $request)
    {
        $nik = $request->nik;
        $nama = $request->nama;
        // validation
            if ($nik == '') {
                $data=array(
                    'alert'=>'gagal',
                    'text'=>'NIK Haru di isi',
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            if ($nama == '') {
                $data=array(
                    'alert'=>'gagal',
                    'text'=>'Nama Haru di isi',
                    'color'=>'danger'
                );
                return json_encode($data);
            }
        try {
            stf___pengawas::insert(['nik'=>$nik,'nama'=>$nama,'created_at'=>DB::raw('CURRENT_TIMESTAMP')]);
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
    public function update_pengawas(Request $request)
    {
        $nik = $request->nik;
        $nama = $request->nama;
        $id = $request->id;
        // validation
            if ($nik == '') {
                $data=array(
                    'alert'=>'gagal',
                    'text'=>'NIK Haru di isi',
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            if ($nama == '') {
                $data=array(
                    'alert'=>'gagal',
                    'text'=>'Nama Haru di isi',
                    'color'=>'danger'
                );
                return json_encode($data);
            }
        try {
            stf___pengawas::where('id',$id)->update(['nik'=>$nik,'nama'=>$nama,'updated_at'=>DB::raw('CURRENT_TIMESTAMP')]);
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
    public function delete_data_pengawas(Request $request)
        {
            $id=$request->id;
            try {
                stf___pengawas::where('id',$id)->delete();
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
                'text'=> 'Data pengawas sudah di hapus',
                'color'=>'success'
            );
            return json_encode($data);
        }
}
