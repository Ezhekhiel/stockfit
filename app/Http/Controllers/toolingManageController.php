<?php

namespace App\Http\Controllers;
use App\Models\tooling__model;
use App\Models\tooling__versi;
use App\Models\tooling__remark;

use Illuminate\Http\Request;

class toolingManageController extends Controller
{
    public function index(){
        return view('tooling.index');
    }
    function main() {
        //get model
            $getModel = tooling__model::get();

            $table_model = '';
            $no = 0;
            foreach ($getModel as $key => $a) {
                $no++;
                $table_model.='
                    <tr>
                        <td>'.$no.'</td>
                        <td onclick="updateModel('.$a->id.',\''.$a->model.'\')">'.$a->model.'</td>
                    </tr>';
            }
        //get versi
            $getVersi = tooling__versi::get();
            $table_versi = '';
            $no = 0;
            foreach ($getVersi as $key => $a) {
                $no++;
                $table_versi.='
                    <tr>
                        <td>'.$no.'</td>
                        <td onclick="updateVersi('.$a->id.',\''.$a->versi.'\')">'.$a->versi.'</td>
                    </tr>';
            }
        //get Remark
            $getRemark = tooling__remark::get();
            $table_remark = '';
            $no = 0;
            foreach ($getRemark as $key => $a) {
                $no++;
                $table_remark.='
                    <tr>
                        <td>'.$no.'</td>
                        <td onclick="updateRemark('.$a->id.',\''.$a->remark.'\')">'.$a->remark.'</td>
                    </tr>';
            }
        $data = array(
            'table_model'=>$table_model,
            'table_versi'=>$table_versi,
            'table_remark'=>$table_remark,
        );
        return json_encode($data);
    }
    function save_model(Request $request) {
        $id = $request->id;
        $model = strtoupper($request->model);
        if ($model == '') {
            $data=array(
                'alert'=>'Error!',
                'text'=>'Data Model Cannot Empty!',
                'color'=>'danger'
            );
            return json_encode($data);
        }
        if (!$id) {
            // cek model ada atau tidak
                $getModel = tooling__model::where('model',$model)->first();
                if ($getModel) {
                    $data=array(
                        'alert'=>'Error!',
                        'text'=>'Data Model Already Exist!',
                        'color'=>'danger'
                    );
                    return json_encode($data);
                }
            try{
                tooling__model::insert(['model'=>$model]);
            }catch (\Exception $e) {
                $data = array(
                    'alert'=>'Error!',
                    'text'=> $e->getMessage(),
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            $data = array(
                'alert'=>'Success!',
                'text'=> 'Add Data Sukses',
                'color'=>'success'
            );
            return json_encode($data);
        }else{
            try{
                tooling__model::where('id',$id)->update(['model'=>$model]);
            }catch (\Exception $e) {
                $data = array(
                    'alert'=>'Error!',
                    'text'=> $e->getMessage(),
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            $data = array(
                'alert'=>'Success!',
                'text'=> 'Update Data Sukses',
                'color'=>'success'
            );
            return json_encode($data);
        }

    }
    function save_versi(Request $request) {
        $id = $request->id;
        $versi = strtoupper($request->versi);
        if ($versi == '') {
            $data=array(
                'alert'=>'Error!',
                'text'=>'Data Versi Cannot Empty!',
                'color'=>'danger'
            );
            return json_encode($data);
        }
        if (!$id) {
            // cek versi ada atau tidak
                $getVersi = tooling__versi::where('versi',$versi)->first();
                if ($getVersi) {
                    $data=array(
                        'alert'=>'Error!',
                        'text'=>'Data Versi Already Exist!',
                        'color'=>'danger'
                    );
                    return json_encode($data);
                }
            try{
                tooling__versi::insert(['versi'=>$versi]);
            }catch (\Exception $e) {
                $data = array(
                    'alert'=>'Error!',
                    'text'=> $e->getMessage(),
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            $data = array(
                'alert'=>'Success!',
                'text'=> 'Add Data Sukses',
                'color'=>'success'
            );
            return json_encode($data);
        }else{
            try{
                tooling__versi::where('id',$id)->update(['versi'=>$versi]);
            }catch (\Exception $e) {
                $data = array(
                    'alert'=>'Error!',
                    'text'=> $e->getMessage(),
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            $data = array(
                'alert'=>'Success!',
                'text'=> 'Update Data Sukses',
                'color'=>'success'
            );
            return json_encode($data);
        }

    }
    function save_remark(Request $request) {
        $id = $request->id;
        $remark = strtoupper($request->remark);
        if ($remark == '') {
            $data=array(
                'alert'=>'Error!',
                'text'=>'Data Remark Cannot Empty!',
                'color'=>'danger'
            );
            return json_encode($data);
        }
        if (!$id) {
            // cek Remark ada atau tidak
                $getRemark = tooling__remark::where('remark',$remark)->first();
                if ($getRemark) {
                    $data=array(
                        'alert'=>'Error!',
                        'text'=>'Data Remark Already Exist!',
                        'color'=>'danger'
                    );
                    return json_encode($data);
                }
            try{
                tooling__remark::insert(['remark'=>$remark]);
            }catch (\Exception $e) {
                $data = array(
                    'alert'=>'Error!',
                    'text'=> $e->getMessage(),
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            $data = array(
                'alert'=>'Success!',
                'text'=> 'Add Data Sukses',
                'color'=>'success'
            );
            return json_encode($data);
        }else{
            try{
                tooling__remark::where('id',$id)->update(['remark'=>$remark]);
            }catch (\Exception $e) {
                $data = array(
                    'alert'=>'Error!',
                    'text'=> $e->getMessage(),
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            $data = array(
                'alert'=>'Success!',
                'text'=> 'Update Data Sukses',
                'color'=>'success'
            );
            return json_encode($data);
        }

    }
}
