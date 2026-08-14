<?php

namespace App\Http\Controllers;

use DB;
use App\Models\gender_mode;
use App\Models\balanceOrder;
use App\Models\stf___target;
use Illuminate\Http\Request;
use App\Models\stf___pengawas;
use App\Models\stf___input_line;
use App\Models\stf___output_line;
use App\Models\stf___reject_line;
use App\Exports\ExportStockfitLine;
use App\Models\stf___shortage_input;
use App\Models\stf___shortage_output;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cache;

class stfOutputControllerErp extends Controller
{
    //main
        public function index()
        {
            $role = 0;
            if (auth()->user()) {
                $role = auth()->user()->role_id;
            }
            $getLine = Cache::remember('getLine', now()->addMinutes(60), function () {
                return DB::CONNECTION('sqlsrv_erp')->select("EXEC SP_SF_LINE_SCAN_DEV
                        @IFLAG = 'VIEWDATA' ,   -- COUNTDATA
                        @P_PROC_PROD_NEW = '0214' ,
                        @P_DATE = '' ,
                        @P_SDATE = '".date("Ymd")."' ,
                        @P_EDATE = '".date("Ymd")."' ,
                        @P_LINE_CD = '' ,
                        @P_O_NO = '' ,
                        @P_TIME_CD = ''");
            });
            $arrLine = [];
            foreach ($getLine as $key => $a) {
                $arrLine[$a->LINE_CD]=$a->LINE_NM;
            }
            ksort($arrLine);

            return view('stockfit.stockfit_line.output_erp',['role'=>$role,'arrLine'=>$arrLine]);
        }
        function getIncomingStf($when, $line, $yesterday, $po) {
            $getIncoming=DB::CONNECTION('sqlsrv_erp')->select("EXEC SP_SF_LINE_SCAN_DEV
                                                        @IFLAG = 'VIEWDATA' ,   -- COUNTDATA
                                                        @P_PROC_PROD_NEW = '0213' ,
                                                        @P_DATE = '' ,
                                                        @P_SDATE = '".$when."' ,
                                                        @P_EDATE = '".$when."' ,
                                                        @P_LINE_CD = '".$line."' ,
                                                        @P_O_NO = '".$po."' ,
                                                        @P_TIME_CD = ''");
            return $getIncoming;
        }

        function getLineNM($line){
            $lineNM=DB::CONNECTION('sqlsrv_erp')->select(
                "EXEC SP_SF_LINE_SCAN_DEV @IFLAG = 'LINE' , @P_PROC_PROD_NEW = '' , @P_DATE = '' , @P_SDATE = '' , @P_EDATE = '' , @P_LINE_CD = '".$line."' , @P_O_NO = '',@P_TIME_CD = ''"
            );
            $LINE_NM = explode('-',$lineNM[0]->LINE_NM);
            return $LINE_NM[0].'-Line-'.$LINE_NM[1];
        }
        function getOutputStf($when, $line, $yesterday, $po) {
            $getOutput=DB::CONNECTION('sqlsrv_erp')->select("EXEC SP_SF_LINE_SCAN_DEV
                                                            @IFLAG = 'VIEWDATA' ,   -- COUNTDATA
                                                            @P_PROC_PROD_NEW = '0214' ,
                                                            @P_DATE = '' ,
                                                            @P_SDATE = '".$when."' ,
                                                            @P_EDATE = '".$when."' ,
                                                            @P_LINE_CD = '".$line."' ,
                                                            @P_O_NO = '".$po."' ,
                                                            @P_TIME_CD = ''");
            return $getOutput;
        }
        public function main(Request $request){
            $when = $request->when;
            $shift = $request->shift;
            $when = date('Ymd',strtotime($when));
            if ($shift == 'SHIFT B') {
                $yesterday = date('Ymd',strtotime($when."-1 days"));
            }else{
                $yesterday = '';
            }
            $line = $request->line;
            $hournow = date('H');
            // $data_bm = balanceOrder::select('buymonth')->groupby('buymonth')->get();
            // input 0213 -- output 0214
            $arr = $this->getDataShift($when,$line,$yesterday);
            ksort($arr);
            $tb_perCell = '';
            $total = [
                'input'=>0,
                'output'=>0
            ];
            //get BTS
                $arrBTS = $this->getBTSPerDay($when,$line,$yesterday);
                $line=$arrBTS['line_nm'];
                $arr_bts_perday[$line] = [];
                foreach ($arrBTS['BTS_input'] as $key => $a) {
                    foreach ($a as $key2 => $b) {
                        if (!array_key_exists($key,$arr_bts_perday[$line])) {
                            $arr_bts_perday[$line][$key][$key2]=[
                                'size'=>$key2,
                                'qty_input'=>$b,
                                'qty_output'=>0,
                            ];
                        }else{
                            if (!array_key_exists($key2,$arr_bts_perday[$line][$key])) {
                                $arr_bts_perday[$line][$key][$key2]=[
                                    'size'=>$key2,
                                    'qty_input'=>$b,
                                    'qty_output'=>0,
                                ];
                            }else{
                                $arr_bts_perday[$line][$key][$key2]=$arr_bts_perday[$line][$key][$key2]+$b;
                            }
                        }
                    }
                }
                foreach ($arrBTS['BTS_output'] as $key => $a) {
                    foreach ($a as $key2 => $b) {
                        if (!array_key_exists($key,$arr_bts_perday[$line])) {
                            $arr_bts_perday[$line][$key][$key2]=[
                                'size'=>$key2,
                                'qty_output'=>$b,
                                'qty_input'=>0,
                            ];
                        }else{
                            if (!array_key_exists($key2,$arr_bts_perday[$line][$key])) {
                                $arr_bts_perday[$line][$key][$key2]=[
                                    'size'=>$key2,
                                    'qty_output'=>$b,
                                    'qty_input'=>0,
                                ];
                            }else{
                                $arr_bts_perday[$line][$key][$key2]['qty_output']=$arr_bts_perday[$line][$key][$key2]['qty_output']+$b;
                            }
                        }
                    }
                }
                $po_ = '';
                $arrayMix = $arrayTotalOutput = $arrayTotalInput = [];
                foreach ($arr_bts_perday[$line] as $key => $a) {
                    foreach ($a as $key2 => $b) {
                        $pisah = explode('%',$key);
                        $po_order = $pisah[0];
                        if ($po_ == '' || $po_ != $po_order) {
                            $mix = min(array_sum(array_column($a, 'qty_input')),array_sum(array_column($a, 'qty_output')));
                            $totalOutput = array_sum(array_column($a, 'qty_output'));
                            $totalInput = array_sum(array_column($a, 'qty_input'));
                            $arrayMix[]=$mix;

                            $arrayTotalOutput[]=$totalOutput;
                            $arrayTotalInput[]=$totalInput;

                            if (number_format(array_sum($arrayTotalOutput),2)==0) {
                                $mixPercent = number_format(array_sum($arrayMix)/1)*100;
                            }else{
                                $mixPercent = number_format(array_sum($arrayMix)/array_sum($arrayTotalOutput),2);
                            }
                            $volume =  number_format((array_sum($arrayTotalOutput)/array_sum($arrayTotalInput)),2);
                            $BTS = number_format(($mixPercent*$volume),2)*100;
                        }
                        $po_ = $po_order;
                    }
                }
                $jam_var = '';

            foreach ($arr as $key => $a) {
                $jam_sekarang = (strlen((string)$key)>1)?$key:'0'.$key;
                $jam_selanjutnya = $key + 1;
                $jam_selanjutnya = (strlen((string)$jam_selanjutnya)>1)?$jam_selanjutnya:'0'.$jam_selanjutnya;
                if ($jam_var == '') {
                    $tdBTS = '
                        <td class="align-middle" rowspan="'.count($arr).'" onclick="getBTSperDay(\''.$when.'\',\''.$shift.'\',\''.$request->line.'\')"><b style="font-size:200%;">'.$BTS.'%</b><br><b>Click Hire for Detail !</b></td>
                    ';
                }else{
                    $tdBTS='';
                }

                $tb_perCell.='
                    <tr>
                        <td>'.$jam_sekarang.'~'.$jam_selanjutnya.'</td>
                        <td>'.$a['input'].'</td>
                        <td>'.$a['output'].'</td>
                        '.$tdBTS.'
                    </tr>
                ';
                $total['input']=$total['input']+$a['input'];
                $total['output']=$total['output']+$a['output'];
                $jam_var = $jam_sekarang;
            }
            $tb_perCell.='
                <tr class="bg-dark">
                    <td></td>
                    <td>'.$total['input'].'</td>
                    <td>'.$total['output'].'</td>
                    <td></td>
                </tr>
            ';
            $data = array(
                    'tb_perCell'=>$tb_perCell,
            );
            return json_encode($data);
        }

        function getDataShift($when , $line, $yesterday) {
            $po='';
            $getIncoming = $this->getIncomingStf($when,$line, $yesterday,$po);
            $getOutput = $this->getOutputStf($when,$line, $yesterday,$po);
            $arr = [];
            foreach ($getIncoming as $key => $a) {
                $WORK_HOURS = intval(str_replace("A0","",$a->TIME_CD));
                if ($WORK_HOURS>5 && $WORK_HOURS<19) {
                    if (!array_key_exists($WORK_HOURS, $arr)) {
                        $arr[$WORK_HOURS]=[
                            'output'=>0,
                            'input'=>(int)$a->QTY
                        ];
                    }else{
                        $arr[$WORK_HOURS]['input'] = $arr[$WORK_HOURS]['input']+(int)$a->QTY;
                    }
                }
            }
            foreach ($getOutput as $key => $a) {
                $WORK_HOURS = intval(str_replace("A0","",$a->TIME_CD));
                if ($WORK_HOURS>6 && $WORK_HOURS<19) {
                    if (!array_key_exists($WORK_HOURS, $arr)) {
                        $arr[$WORK_HOURS] = [
                            'output'=>(int)$a->QTY,
                            'input'=>0
                        ];
                    }else{
                        $arr[$WORK_HOURS]['output'] = $arr[$WORK_HOURS]['output']+(int)$a->QTY;
                    }
                }
            }
            return $arr;
            if ($arr[6]['input']!=0) {
                $arr[7]['input']=$arr[7]['input']+$arr[6]['input'];
                unset($arr[6]);
            }
            return $arr;
        }
        function getBTS($when, $line, $jam, $yesterday){
            $po='';
            $getIncoming = $this->getIncomingStf($when,$line, $yesterday,$po);
            $getOutput = $this->getOutputStf($when,$line, $yesterday,$po);
            $arrInput = $arrOutput = [];
            foreach ($getIncoming as $key => $a) {
                $WORK_HOURS = intval(str_replace("A0","",$a->TIME_CD));
                $WIDE = explode("-",$a->ARTNO);
                // if ($jam != 'jam') {
                //     if ($jam != $WORK_HOURS) {
                //         continue;
                //     }
                // }
                if ($WORK_HOURS>6 && $WORK_HOURS<19) {
                    if (!array_key_exists($WORK_HOURS, $arrInput)) {
                        $arrInput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                    }else{
                        if (!array_key_exists($a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY,$arrInput[$WORK_HOURS])) {
                            $arrInput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                        }else{
                            if (!array_key_exists($a->UKSIZE,$arrInput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY])) {
                                $arrInput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                            }else{
                                $arrInput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = $arrInput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE]+(int)$a->QTY;
                            }
                        }
                    }
                }
            }
            foreach ($getOutput as $key => $a) {
                $WORK_HOURS = intval(str_replace("A0","",$a->TIME_CD));
                // if ($jam != 'jam') {
                //     if ($jam != $WORK_HOURS) {
                //         continue;
                //     }
                // }
                if ($WORK_HOURS>6 && $WORK_HOURS<19) {
                    if (!array_key_exists($WORK_HOURS, $arrOutput)) {
                        $arrOutput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                    }else{
                        if (!array_key_exists($a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY,$arrOutput[$WORK_HOURS])) {
                            $arrOutput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                        }else{
                            if (!array_key_exists($a->UKSIZE,$arrOutput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY])) {
                                $arrOutput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                            }else{
                                $arrOutput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = $arrOutput[$WORK_HOURS][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE]+(int)$a->QTY;
                            }
                        }
                    }
                }
            }
            $data = array(
                    'BTS_input'=>$arrInput,
                    'BTS_output'=>$arrOutput
                );
            return $data;
        }
        function getBTSPerDay($when, $line, $yesterday){
            $po='';
            $getIncoming = $this->getIncomingStf($when,$line,$yesterday,$po);
            $getOutput = $this->getOutputStf($when,$line,$yesterday,$po);
            $line_nm = $this->getLineNM($line);
            $arrInput = $arrOutput = [];
            foreach ($getIncoming as $key => $a) {
                $WORK_HOURS = intval(str_replace("A0","",$a->TIME_CD));
                if ($WORK_HOURS>5 && $WORK_HOURS<19) {
                    $WIDE = explode("-",$a->ARTNO);
                    if (!array_key_exists($a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY,$arrInput)) {
                        $arrInput[$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                    }else{
                        if (!array_key_exists($a->UKSIZE,$arrInput[$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY])) {
                            $arrInput[$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                        }else{
                            $arrInput[$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = $arrInput[$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE]+(int)$a->QTY;
                        }
                    }
                }
            }
            foreach ($getOutput as $key => $a) {
                $WORK_HOURS = intval(str_replace("A0","",$a->TIME_CD));
                if ($WORK_HOURS>6 && $WORK_HOURS<19) {
                    $WIDE = explode("-",$a->ARTNO);
                    if (!array_key_exists($a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY,$arrOutput)) {
                        $arrOutput[$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                    }else{
                        if (!array_key_exists($a->UKSIZE,$arrOutput[$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY])) {
                            $arrOutput[$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                        }else{
                            $arrOutput[$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = $arrOutput[$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE]+(int)$a->QTY;
                        }
                    }
                }
            }
            $data = array(
                    'BTS_input'=>$arrInput,
                    'BTS_output'=>$arrOutput,
                    'line_nm'=>$line_nm
                );
            return $data;
        }
        public function detailBTS(Request $request) {
            $when = $request->when;
            $shift = $request->shift;
            if ($shift == 'SHIFT B') {
                $yesterday = date('Ymd',strtotime($when."-1 days"));
            }else{
                $yesterday = '';
            }
            $line = $request->line;
            $arrBTS = $this->getBTSPerDay($when,$line,$yesterday);
            $line = $arrBTS['line_nm'];

            $arr = [];

            foreach ($arrBTS['BTS_input'] as $key => $a) {
                foreach ($a as $key2 => $b) {
                    if (!array_key_exists($line,$arr)) {
                        $arr[$line][$key][$key2]=[
                            'size'=>$key2,
                            'qty_input'=>$b,
                            'qty_output'=>0,
                        ];
                    }else{
                        if (!array_key_exists($key,$arr[$line])) {
                            $arr[$line][$key][$key2]=[
                                'size'=>$key2,
                                'qty_input'=>$b,
                                'qty_output'=>0,
                            ];
                        }else{
                            if (!array_key_exists($key2,$arr[$line][$key])) {
                                $arr[$line][$key][$key2]=[
                                    'size'=>$key2,
                                    'qty_input'=>$b,
                                    'qty_output'=>0,
                                ];
                            }else{
                                if (!array_key_exists('qty_input',$arr[$line][$key][$key2])) {
                                    $arr[$line][$key][$key2]=[
                                        'qty_input'=>0,
                                    ];
                                }else{
                                    $arr[$line][$key][$key2]['qty_input']=$arr[$line][$key][$key2]['qty_input']+$b;
                                }
                            }
                        }
                    }
                }
            }
            foreach ($arrBTS['BTS_output'] as $key => $a) {
                foreach ($a as $key2 => $b) {
                    if (!array_key_exists($key,$arr[$line])) {
                        $arr[$line][$key][$key2]=[
                            'size'=>$key2,
                            'qty_input'=>0,
                            'qty_output'=>$b,
                        ];
                    }else{
                        if (!array_key_exists($key2,$arr[$line][$key])) {
                            $arr[$line][$key][$key2]=[
                                'size'=>$key2,
                                'qty_input'=>0,
                                'qty_output'=>$b,
                            ];
                        }else{
                            if (!array_key_exists('qty_input',$arr[$line][$key][$key2])) {
                                $arr[$line][$key][$key2]=[
                                    'qty_input'=>0,
                                ];
                            }else{
                                $arr[$line][$key][$key2]['qty_output']=$arr[$line][$key][$key2]['qty_output']+$b;
                            }
                        }
                    }
                }
            }
            $table='';
            $line_ = $po_ = '';
            $arrayMix = $arrayTotalOutput = $arrayTotalInput = [];
            foreach ($arr[$line] as $key => $a) {
                foreach ($a as $key2 => $b) {
                    $pisah = explode('%',$key);
                    $po_order = $pisah[0];
                    if ($po_ == '' || $po_ != $po_order) {
                        $mix = min(array_sum(array_column($a, 'qty_input')),array_sum(array_column($a, 'qty_output')));
                        $totalOutput = array_sum(array_column($a, 'qty_output'));
                        $totalInput = array_sum(array_column($a, 'qty_input'));
                        $arrayMix[]=$mix;
                        $arrayTotalOutput[]=$totalOutput;
                        $arrayTotalInput[]=$totalInput;
                    }
                    $po_ = $po_order;
                }
            }
            $po_ = '';
            foreach ($arr[$line] as $key => $a) {
                foreach ($a as $key2 => $b) {
                    $pisah = explode('%',$key);
                    $orderqty = $pisah[1];
                    $po_order = $pisah[0];
                    $mix = min(array_sum(array_column($a, 'qty_input')),array_sum(array_column($a, 'qty_output')));
                    if ($po_ == '' || $po_ != $po_order) {
                        if ($b['qty_input']!=0) {
                            $poInput = '
                                <td rowspan="'.count($a).'">'.$orderqty.'</td>
                                <td rowspan="'.count($a).'" onclick="trackPO(\''.$po_order.'\')">'.$po_order.'</td>
                                <td>'.$key2.'</td>
                                <td class="bg-info">'.$b['qty_input'].'</td>
                                <td class="bg-info" rowspan="'.count($a).'">'.array_sum(array_column($a, 'qty_input')).'</td>
                            ';

                        }else{
                            $poInput = '
                                <td rowspan="'.count($a).'">'.$orderqty.'</td>
                                <td rowspan="'.count($a).'" onclick="trackPO(\''.$po_order.'\')">'.$po_order.'</td>
                                <td class="bg-dark"></td>
                                <td class="bg-dark"></td>
                                <td class="bg-info" rowspan="'.count($a).'">'.array_sum(array_column($a, 'qty_input')).'</td>
                            ';
                        }
                        if ($b['qty_output']!=0) {
                            $poOutput = '
                                <td rowspan="'.count($a).'">'.$po_order.'</td>
                                <td>'.$key2.'</td>
                                <td class="bg-info">'.$b['qty_output'].'</td>
                                <td class="bg-info" rowspan="'.count($a).'">'.array_sum(array_column($a, 'qty_output')).'</td>
                            ';
                        }else{
                            $poOutput = '
                                <td rowspan="'.count($a).'">'.$po_order.'</td>
                                <td class="bg-dark"></td>
                                <td class="bg-dark"></td>
                                <td class="bg-info" rowspan="'.count($a).'">'.array_sum(array_column($a, 'qty_output')).'</td>
                            ';
                        }
                        $tdMix='<td rowspan="'.count($a).'">'.$mix.'</td>';
                    }else{
                        if ($b['qty_input']!=0) {
                            $poInput = '
                                <td>'.$key2.'</td>
                                <td class="bg-info">'.$b['qty_input'].'</td>
                            ';

                        }else{
                            $poInput = '
                                <td class="bg-dark"></td>
                                <td class="bg-dark"></td>
                            ';
                        }
                        if ($b['qty_output']!=0) {
                            $poOutput = '
                                <td>'.$key2.'</td>
                                <td class="bg-info">'.$b['qty_output'].'</td>
                            ';
                        }else{
                            $poOutput = '
                                <td class="bg-dark"></td>
                                <td class="bg-dark"></td>
                            ';
                        }
                        $tdMix='';
                        $tdTotalMix='';
                    }
                    if ($line_!=$line) {
                        $line_td='<td class="line_class" rowspan="'.array_sum(array_map("count", $arr[$line])).'">'.$line.'</td>';
                        $tdTotalMix='<td rowspan="'.array_sum(array_map("count", $arr[$line])).'">'.array_sum($arrayMix).'</td>';

                        $mixPercent = number_format((array_sum($arrayMix))/array_sum($arrayTotalOutput),2);
                        $mixPercent_ = $mixPercent*100;
                        $tdMixPercent='<td rowspan="'.array_sum(array_map("count", $arr[$line])).'">'.$mixPercent_.'%</td>';

                        $volume =  number_format((array_sum($arrayTotalOutput)/array_sum($arrayTotalInput)),2);
                        $volume_ = $volume*100;
                        $tdVolume='<td rowspan="'.array_sum(array_map("count", $arr[$line])).'">'.$volume_.'%</td>';

                        $BTS = number_format(($mixPercent*$volume),2);
                        $BTS = $BTS*100;
                        $tdBTS='<td rowspan="'.array_sum(array_map("count", $arr[$line])).'">'.$BTS.'%</td>';

                    }else{
                        $line_td = $tdTotalMix = $tdVolume = $mixPercent = $tdMixPercent = $tdBTS ='';
                    }

                    $table.='
                    <tr>
                        '.$line_td.'
                        '.$poInput.'
                        '.$poOutput.'
                        '.$tdMix.'
                        '.$tdTotalMix.'
                        '.$mixPercent.'
                        '.$tdMixPercent.'
                        '.$tdVolume.'
                        '.$tdBTS.'
                    </tr>
                    ';
                    $line_ = $line;
                    $po_ = $po_order;
                }
            }
            $table.='<tr>
                        <td colspan="5">TOTAL INPUT</td>
                        <td>'.array_sum($arrayTotalInput).'</td>
                        <td colspan="3">TOTAL OUTPUT</td>
                        <td>'.array_sum($arrayTotalOutput).'</td>
                        <td>'.array_sum($arrayMix).'</td>
                        <td colspan="4"></td>

                    </tr>';
            $data = array(
                'table'=>$table,
                'when'=>$request->when,
                'line'=>$arrBTS['line_nm'],
            );
            return json_encode($data);
        }
        function export(Request $request) {
            $month = $request->month;
            $line = $request->line;
            $arrBTS = $this->getBTSPerMonth($month,$line);
        }
        function getBTSPerMonth($month, $line){
            $sDate = date('Ym01', strtotime($month));
            $eDate = date('Ymt', strtotime($month));
            $getIncoming = $this->getIncomingStfByMonth($sDate,$eDate,$line);
            $getOutput = $this->getOutputStfByMonth($sDate,$eDate,$line);
            $line_nm = $this->getLineNM($line);
            $arrInput = $arrOutput = [];
            foreach ($getIncoming as $key => $a) {
                $WORK_HOURS = intval(str_replace("A0","",$a->TIME_CD));
                if ($WORK_HOURS>5 && $WORK_HOURS<19) {
                    $date = date('Y-m-d',strtotime($a->IO_DATE));
                    $WIDE = explode("-",$a->ARTNO);
                    if (!array_key_exists($date,$arrInput)) {
                        $arrInput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                    }else{
                        if (!array_key_exists($a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY,$arrInput[$date])) {
                            $arrInput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                        }else{
                            if (!array_key_exists($a->UKSIZE,$arrInput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY])) {
                                $arrInput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                            }else{
                                $arrInput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = $arrInput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE]+(int)$a->QTY;
                            }
                        }
                    }
                }
            }
            foreach ($getOutput as $key => $a) {
                $WORK_HOURS = intval(str_replace("A0","",$a->TIME_CD));
                if ($WORK_HOURS>6 && $WORK_HOURS<19) {
                    $date = date('Y-m-d',strtotime($a->IO_DATE));
                    $WIDE = explode("-",$a->ARTNO);
                    if (!array_key_exists($date,$arrOutput)) {
                        $arrOutput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                    }else{
                        if (!array_key_exists($a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY,$arrOutput[$date])) {
                            $arrOutput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                        }else{
                            if (!array_key_exists($a->UKSIZE,$arrOutput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY])) {
                                $arrOutput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = (int)$a->QTY;
                            }else{
                                $arrOutput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE] = $arrOutput[$date][$a->O_NO.'-'.$WIDE[1].'%'.$a->ORDER_QTY][$a->UKSIZE]+(int)$a->QTY;
                            }
                        }
                    }
                }
            }
            $data = array(
                    'BTS_input'=>$arrInput,
                    'BTS_output'=>$arrOutput,
                    'line_nm'=>$line_nm
                );
            return $data;
        }
        function getIncomingStfByMonth($sDate, $eDate ,$line) {
            $getIncoming=DB::CONNECTION('sqlsrv_erp')->select("EXEC SP_SF_LINE_SCAN_DEV
                                                        @IFLAG = 'VIEWDATA' ,   -- COUNTDATA
                                                        @P_PROC_PROD_NEW = '0213' ,
                                                        @P_DATE = '' ,
                                                        @P_SDATE = '".$sDate."' ,
                                                        @P_EDATE = '".$eDate."' ,
                                                        @P_LINE_CD = '".$line."' ,
                                                        @P_O_NO = '' ,
                                                        @P_TIME_CD = ''");
            return $getIncoming;
        }
        function getOutputStfByMonth($sDate, $eDate , $line) {
            $getOutput=DB::CONNECTION('sqlsrv_erp')->select("EXEC SP_SF_LINE_SCAN_DEV
                                                            @IFLAG = 'VIEWDATA' ,   -- COUNTDATA
                                                            @P_PROC_PROD_NEW = '0214' ,
                                                            @P_DATE = '' ,
                                                            @P_SDATE = '".$sDate."' ,
                                                            @P_EDATE = '".$eDate."' ,
                                                            @P_LINE_CD = '".$line."' ,
                                                            @P_O_NO = '' ,
                                                            @P_TIME_CD = ''");
            return $getOutput;
        }
        function trackingPO(Request $request) {
            $po_wide = $request->po_wide;
            $arr = explode("-",$po_wide);
            $po = $arr[0];
            $wide = $arr[1];
            $when = $line = $yesterday = '';
            $getIncoming = $this->getIncomingStf($when,$line,$yesterday,$po);
            usort($getIncoming, fn($a, $b) => $a->LINE_NM <=> $b->LINE_NM);
            $getOutput = $this->getOutputStf($when,$line,$yesterday,$po);
            usort($getOutput, fn($a, $b) => $a->LINE_NM <=> $b->LINE_NM);

            $arr = $countPerLine = [
                'input'=>[],
                'output'=>[],
            ];
            foreach ($getIncoming as $key => $a) {
                //variable
                    $WORK_HOURS = intval(str_replace("A0","",$a->TIME_CD));
                    $DATE_CONV = date('Y-m-d',strtotime($a->IO_DATE));
                    $jam_sekarang = (strlen((string)$WORK_HOURS)>1)?$WORK_HOURS:'0'.$WORK_HOURS;
                        $jam_selanjutnya = $WORK_HOURS + 1;
                        $jam_selanjutnya = (strlen((string)$jam_selanjutnya)>1)?$jam_selanjutnya:'0'.$jam_selanjutnya;
                        $HOURS_FORMAT = $jam_sekarang.'~'.$jam_selanjutnya;
                if (!array_key_exists($a->LINE_NM, $arr['input'])) {
                    $arr['input'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT][$a->UKSIZE]=$a->QTY;
                }else{
                    if (!array_key_exists($DATE_CONV.'|'.$HOURS_FORMAT,
                        $arr['input'][$a->LINE_NM])) {
                        $arr['input'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT][$a->UKSIZE]=$a->QTY;
                    }else{
                        if(!array_key_exists($a->UKSIZE,$arr['input'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT])){
                            $arr['input'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT][$a->UKSIZE]=$a->QTY;
                        }else{
                            $arr['input'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT][$a->UKSIZE]=$arr['input'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT][$a->UKSIZE]+$a->QTY;
                        }
                    }
                }
            }

            $line_name_var = $time_var = $size_var = '';
            foreach ($getOutput as $key => $a) {
                $WORK_HOURS = intval(str_replace("A0","",$a->TIME_CD));
                $DATE_CONV = date('Y-m-d',strtotime($a->IO_DATE));
                    $jam_sekarang = (strlen((string)$WORK_HOURS)>1)?$WORK_HOURS:'0'.$WORK_HOURS;
                    $jam_selanjutnya = $WORK_HOURS + 1;
                    $jam_selanjutnya = (strlen((string)$jam_selanjutnya)>1)?$jam_selanjutnya:'0'.$jam_selanjutnya;
                    $HOURS_FORMAT = $jam_sekarang.'~'.$jam_selanjutnya;
                if (!array_key_exists($a->LINE_NM,$arr['output'])) {
                    $arr['output'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT][$a->UKSIZE]=$a->QTY;
                }else{
                    if (!array_key_exists($DATE_CONV.'|'.$HOURS_FORMAT,$arr['output'][$a->LINE_NM])) {
                        $arr['output'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT][$a->UKSIZE]=$a->QTY;
                    }else{
                        if(!array_key_exists($a->UKSIZE,$arr['output'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT])){
                            $arr['output'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT][$a->UKSIZE]=$a->QTY;
                        }else{
                            $arr['output'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT][$a->UKSIZE]=$arr['output'][$a->LINE_NM][$DATE_CONV.'|'.$HOURS_FORMAT][$a->UKSIZE]+$a->QTY;
                        }
                    }
                }
            }
            $tbody_input = '';
            $val_line = $val_date = $val_size = '';
            $count_1 = $count_2 = 0;
            foreach ($arr['input'] as $key => $a) {
                foreach ($a as $key2 => $b) {
                    foreach ($b as $key3 => $c) {
                        if ($val_line == '' || $val_line!=$key) {
                            $count_1 = array_sum(array_map("count", $a));
                            $line_row = '<td rowspan="'.$count_1.'">'.$key.'</td>';

                        }else{
                            $line_row = '';
                        }
                        if ($val_date == '' || $val_date!=$key2) {
                            $count_2 = count($b);
                            $time_row = '<td rowspan="'.$count_2.'">'.$key2.'</td>';
                        }else{
                            if ($val_line!=$key) {
                                $count_2 = count($b);
                                $time_row = '<td rowspan="'.$count_2.'">'.$key2.'</td>';
                            }
                            $time_row = '';
                        }
                        $tbody_input .='
                            <tr>
                                '.$line_row.'
                                '.$time_row.'
                                <td>'.$key3.'</td>
                                <td>'.$c.'</td>
                            </tr>
                        ';
                        $val_line = $key;
                        $val_date = $key2;
                    }
                }
            }
            $tbody_output = '';
            $val_line = $val_date = $val_size = '';
            $count_1 = $count_2 = 0;
            foreach ($arr['output'] as $key => $a) {
                foreach ($a as $key2 => $b) {
                    foreach ($b as $key3 => $c) {
                        if ($val_line == '' || $val_line!=$key) {
                            $count_1 = array_sum(array_map("count", $a));
                            $line_row = '<td rowspan="'.$count_1.'">'.$key.'</td>';

                        }else{
                            $line_row = '';
                        }
                        if ($val_date == '' || $val_date!=$key2) {
                            $count_2 = count($b);
                            $time_row = '<td rowspan="'.$count_2.'">'.$key2.'</td>';
                        }else{
                            if ($val_line!=$key) {
                                $count_2 = count($b);
                                $time_row = '<td rowspan="'.$count_2.'">'.$key2.'</td>';
                            }
                            $time_row = '';
                        }
                        $tbody_output .='
                            <tr>
                                '.$line_row.'
                                '.$time_row.'
                                <td>'.$key3.'</td>
                                <td>'.$c.'</td>
                            </tr>
                        ';
                        $val_line = $key;
                        $val_date = $key2;
                    }
                }
            }
            $data = array(
                'tbody_input'=>$tbody_input,
                'tbody_output'=>$tbody_output
            );
            return $data;
        }
        public function detail_gabungan(Request $request)
        {
            $jam = $request->jam;
            $id_pengawas = $request->pengawas;
                $getNama = stf___pengawas::where('nik',$id_pengawas)->first();

            $shift = $request->shift;
            $form = $request->form;
            $when = $request->when;
            $tbody = '';

            $getDataInput = db::table('stf___input_line as a')
                        ->select('a.*','b.size as size_name')
                        ->join('size_modes as b','a.size','=','b.id')
                        ->where(['a.id_pengawas'=>$id_pengawas,'a.shift'=>$shift,'a.date'=>$when])
                        ->orderby('po','asc')->orderby('wide','asc')->orderby('size','asc');
            $getDataShortageInput = db::table('stf___shortage_input as a')
                        ->select('a.*','b.size as size_name')
                        ->join('size_modes as b','a.size','=','b.id')
                        ->where(['a.id_pengawas'=>$id_pengawas,'a.shift'=>$shift,'a.date'=>$when])
                        ->orderby('po','asc')->orderby('wide','asc')->orderby('size','asc');
            $getDataOutput = db::table('stf___output_line as a')
                        ->select('a.*','b.size as size_name')
                        ->join('size_modes as b','a.size','=','b.id')
                        ->where(['a.id_pengawas'=>$id_pengawas,'a.shift'=>$shift,'a.date'=>$when])
                        ->orderby('po','asc')->orderby('wide','asc')->orderby('size','asc');
            $getDataShortageOutput = db::table('stf___shortage_output as a')
                        ->select('a.*','b.size as size_name')
                        ->join('size_modes as b','a.size','=','b.id')
                        ->where(['a.id_pengawas'=>$id_pengawas,'a.shift'=>$shift,'a.date'=>$when])
                        ->orderby('po','asc')->orderby('wide','asc')->orderby('size','asc');
            if (empty($jam)) {
                $getDataInput = $getDataInput->get();
                $getDataShortageInput = $getDataShortageInput->get();
                $getDataOutput = $getDataOutput->get();
                $getDataShortageOutput = $getDataShortageOutput->get();
            }else{
                $getDataInput = $getDataInput->where('jam',$jam)->get();
                $getDataShortageInput = $getDataShortageInput->where('jam',$jam)->get();
                $getDataOutput = $getDataOutput->where('jam',$jam)->get();
                $getDataShortageOutput = $getDataShortageOutput->where('jam',$jam)->get();
            }

            $arr_ = [];
            if ((count($getDataInput) + count($getDataShortageInput))==0 || (count($getDataOutput) + count($getDataShortageOutput))==0) {
                $tbody = '<tr>
                            <td colspan="15">Data Not Compleated</td>
                        </tr>';
            }else{
                // return $getDataShortageInput;
                $size=[];
                foreach ($getDataInput as $input) {
                    $keyLine=$input->line;
                    $keyPO=$input->po.'-'.$input->wide;
                    $size=$input->size_name;
                    if (array_key_exists($keyLine, $arr_)) {
                        if (array_key_exists($keyPO, $arr_[$keyLine])) {
                            if (array_key_exists($size, $arr_[$keyLine][$keyPO])) {
                                $arr_[$keyLine][$keyPO][$size]['qty_input']=$arr_[$keyLine][$keyPO][$size]['qty_input']+$input->qty;
                            }else{
                                $arr_[$keyLine][$keyPO][$size]=[
                                         'size'=>$size,
                                        'qty_order'=>$input->qty_order,
                                        'qty_input'=>$input->qty,
                                        'qty_output'=>0,
                                        'qty_shortage_input'=>0,
                                        'qty_shortage_output'=>0
                                    ];
                            }
                        }else{
                            $arr_[$keyLine][$keyPO][$size]=[
                                'size'=>$size,
                                'qty_order'=>$input->qty_order,
                                'qty_input'=>$input->qty,
                                'qty_output'=>0,
                                'qty_shortage_input'=>0,
                                'qty_shortage_output'=>0
                            ];
                        }
                    }else{
                        $arr_[$keyLine][$keyPO][$size]=[
                            'size'=>$size,
                            'qty_order'=>$input->qty_order,
                            'qty_input'=>$input->qty,
                            'qty_output'=>0,
                            'qty_shortage_input'=>0,
                            'qty_shortage_output'=>0
                        ];
                    }
                }
                foreach ($getDataShortageInput as $shortageInput) {
                    $keyLine=$shortageInput->line;
                    $keyPO=$shortageInput->po.'-'.$shortageInput->wide;
                    $size=$shortageInput->size_name;
                    if (array_key_exists($keyLine, $arr_)) {
                        if (array_key_exists($keyPO, $arr_[$keyLine])) {
                            if (array_key_exists($size, $arr_[$keyLine][$keyPO])) {
                                $arr_[$keyLine][$keyPO][$size]['qty_shortage_input']=$arr_[$keyLine][$keyPO][$size]['qty_shortage_input']+$shortageInput->qty;
                            }else{
                                $arr_[$keyLine][$keyPO][$size]=[
                                    'size'=>$size,
                                    'qty_order'=>$shortageInput->qty_order,
                                    'qty_input'=>0,
                                    'qty_output'=>0,
                                    'qty_shortage_input'=>$shortageInput->qty,
                                    'qty_shortage_output'=>0
                                ];
                            }
                        }else{
                            $arr_[$keyLine][$keyPO][$size]=[
                                    'size'=>$size,
                                    'qty_order'=>$shortageInput->qty_order,
                                    'qty_input'=>0,
                                    'qty_output'=>0,
                                    'qty_shortage_input'=>$shortageInput->qty,
                                    'qty_shortage_output'=>0
                                ];
                        }
                    }else{
                        $arr_[$keyLine][$keyPO][$size]=[
                            'size'=>$size,
                            'qty_order'=>$shortageInput->qty_order,
                            'qty_input'=>0,
                            'qty_output'=>0,
                            'qty_shortage_input'=>$shortageInput->qty,
                            'qty_shortage_output'=>0
                        ];
                    }
                }
                foreach ($getDataOutput as $output) {
                    $keyLine=$output->line;
                    $keyPO=$output->po.'-'.$output->wide;
                    $size=$output->size_name;
                    if (array_key_exists($keyLine, $arr_)) {
                        if (array_key_exists($keyPO, $arr_[$keyLine])) {
                            if (array_key_exists($size, $arr_[$keyLine][$keyPO])) {
                                $arr_[$keyLine][$keyPO][$size]['qty_output']=$arr_[$keyLine][$keyPO][$size]['qty_output']+$output->qty;
                            }else{
                                $arr_[$keyLine][$keyPO][$size]=[
                                        'size'=>$size,
                                        'qty_order'=>$output->qty_order,
                                        'qty_input'=>0,
                                        'qty_output'=>$output->qty,
                                        'qty_shortage_input'=>0,
                                        'qty_shortage_output'=>0
                                    ];
                            }
                        }else{
                            $arr_[$keyLine][$keyPO][$size]=[
                                    'size'=>$size,
                                    'qty_order'=>$output->qty_order,
                                    'qty_input'=>0,
                                    'qty_output'=>$output->qty,
                                    'qty_shortage_input'=>0,
                                    'qty_shortage_output'=>0
                                ];
                        }
                    }else{
                        $arr_[$keyLine][$keyPO][$size]=[
                                'size'=>$size,
                                'qty_order'=>$output->qty_order,
                                'qty_input'=>0,
                                'qty_output'=>$output->qty,
                                'qty_shortage_input'=>0,
                                'qty_shortage_output'=>0
                            ];
                    }

                }
                foreach ($getDataShortageOutput as $shortageOutput) {
                    $keyLine=$shortageOutput->line;
                    $keyPO=$shortageOutput->po.'-'.$shortageOutput->wide;
                    $size=$shortageOutput->size_name;
                    if (array_key_exists($keyLine, $arr_)) {
                        if (array_key_exists($keyPO, $arr_[$keyLine])) {
                            if (array_key_exists($size, $arr_[$keyLine][$keyPO])) {
                                $arr_[$keyLine][$keyPO][$size]['qty_shortage_output']=$arr_[$keyLine][$keyPO][$size]['qty_shortage_output']+$shortageOutput->qty;
                            }else{
                                $arr_[$keyLine][$keyPO][$size]=[
                                        'size'=>$size,
                                        'qty_order'=>$shortageOutput->qty_order,
                                        'qty_input'=>0,
                                        'qty_output'=>0,
                                        'qty_shortage_input'=>0,
                                        'qty_shortage_output'=>$shortageOutput->qty
                                    ];
                            }
                        }else{
                            $arr_[$keyLine][$keyPO][$size]=[
                                    'size'=>$size,
                                    'qty_order'=>$shortageOutput->qty_order,
                                    'qty_input'=>0,
                                    'qty_output'=>0,
                                    'qty_shortage_input'=>0,
                                    'qty_shortage_output'=>$shortageOutput->qty
                                ];
                        }
                    }else{
                        $arr_[$keyLine][$keyPO][$size]=[
                                'size'=>$size,
                                'qty_order'=>$output->qty_order,
                                'qty_input'=>0,
                                'qty_output'=>0,
                                'qty_shortage_input'=>0,
                                'qty_shortage_output'=>$shortageOutput->qty
                            ];
                    }

                }
                // return $arr_;
                $tes=[];
                $line='';
                $rowspanLine='';
                $rowspanPO='';
                foreach ($arr_ as $lineKey => $arr_line) {
                    $sumInput=$sumOutput=$sumMix= 0;
                    foreach ($arr_line as $poKey => $arr_po) {
                        $sumInput+=(array_sum(array_column($arr_[$lineKey][$poKey], 'qty_input'))+array_sum(array_column($arr_[$lineKey][$poKey], 'qty_shortage_input')));
                        $sumOutput+=(array_sum(array_column($arr_[$lineKey][$poKey], 'qty_output'))+array_sum(array_column($arr_[$lineKey][$poKey], 'qty_shortage_output')));
                        foreach ($arr_po as $key3 => $value) {
                            $mix_sum = min(($value['qty_input']+$value['qty_shortage_input']),($value['qty_output']+$value['qty_shortage_output']));
                            $sumMix += $mix_sum;
                        }
                    }
                    foreach ($arr_line as $poKey => $arr_po) {
                        foreach ($arr_po as $key3 => $value) {
                            $tdLine=$tdPO=$tdOrder=$tdRowspan=$tdRowSumInput=$tdRowSumOutput=$tdRowSumMix=$tdRowMixPercent=$tdRowVolumePercent=$tdRowBTSPercent=$tdInput=$tdOutput=$tdShortageInput=$tdShortageOutput=$tdPOInput=$tdSizeOutput='';
                            $poInput = $poOutput = 1;

                            $order = ($value['qty_order'])?$value['qty_order']:"";
                            $size = ($value['size'])?$value['size']:"";
                            $input = ($value['qty_input'])?$value['qty_input']:"";
                            $shortage_input = ($value['qty_shortage_input'])?$value['qty_shortage_input']:"";
                            $output = ($value['qty_output'])?$value['qty_output']:"";
                            $shortage_output = ($value['qty_shortage_output'])?$value['qty_shortage_output']:"";

                            if ($value['qty_input']==0 && $value['qty_shortage_input']==0) {
                                $poInput = 0;
                            }
                            if ($value['qty_output']==0 && $value['qty_shortage_output']==0) {
                                $poOutput = 0;
                            }
                            if ($rowspanLine != $lineKey) {
                                $count=array_sum(array_map("count", $arr_line))*2;
                                $tdLine='<td class="align-middle" rowspan="'.$count.'">'.$lineKey.'</td>';
                                $tdRowSumInput='<td class="align-middle" rowspan="'.$count.'">'.$sumInput.'</td>';
                                $tdRowSumOutput='<td class="align-middle" rowspan="'.$count.'">'.$sumOutput.'</td>';
                                $tdRowSumMix='<td class="align-middle" rowspan="'.$count.'">'.$sumMix.'</td>';
                                $mix_ = 0;
                                if ($sumOutput != 0) {
                                    $mix_ = ((int)$sumMix/(int)$sumOutput);
                                }
                                $mixPercent = round($mix_*100,0);
                                $tdRowMixPercent='<td class="align-middle" rowspan="'.$count.'">'.$mixPercent.'%</td>';
                                $volume_ = 0;
                                if ($sumInput != 0) {
                                    $volume_ = ((int)$sumOutput/(int)$sumInput);
                                }
                                $volumePercent = round($volume_*100,0);
                                $tdRowVolumePercent='<td class="align-middle" rowspan="'.$count.'">'.$volumePercent.'%</td>';
                                $btsPercent = round(($mix_*$volume_)*100,0);
                                $tdRowBTSPercent='<td class="align-middle" rowspan="'.$count.'">'.$btsPercent.'%</td>';
                            }
                            if ($rowspanPO != $poKey) {
                                $count2=count($arr_[$lineKey][$poKey])*2;
                                $tdPO='<td class="align-middle" rowspan="'.$count2.'">'.$poKey.'</td>';
                                $tdOrder='<td class="align-middle" rowspan="'.$count2.'">'.$order.'</td>';
                            }
                            if ($input && $shortage_input) {
                                $tdInput = '<td>'.$input.'</td>';
                                $tdShortageInput = '<td class="bg-warning">'.$shortage_input.'</td>';
                                $tdSizeInput = '<td class="align-middle" rowspan="2">'.$size.'</td>';
                            }else{
                                if ($input) {
                                    $tdInput = '<td rowspan="2" class="align-middle">'.$input.'</td>';
                                }else{
                                    $tdInput = '<td rowspan="2" class="bg-warning align-middle">'.$shortage_input.'</td>';
                                }
                                if (!$input && !$shortage_input) {
                                    $tdInput = '<td rowspan="2" class="align-middle bg-dark"></td>';
                                    $tdSizeInput = '<td rowspan="2" class="align-middle bg-dark"></td>';
                                    $poInput = 0;
                                }else{
                                    $tdSizeInput = '<td class="align-middle" rowspan="2">'.$size.'</td>';
                                }
                            }
                            if ($output && $shortage_output) {
                                $tdOutput = '<td>'.$output.'</td>';
                                $tdShortageOutput = '<td class="bg-warning">'.$shortage_output.'</td>';
                                $tdSizeOutput = '<td class="align-middle" rowspan="2">'.$size.'</td>';
                            }else{
                                if ($output) {
                                    $tdOutput = '<td rowspan="2" class="align-middle">'.$output.'</td>';
                                }else{
                                    $tdOutput = '<td rowspan="2" class="bg-warning align-middle">'.$shortage_output.'</td>';
                                }
                                if (!$output && !$shortage_output) {
                                    $tdOutput = '<td rowspan="2" class="align-middle bg-dark"></td>';
                                    $tdSizeOutput = '<td rowspan="2" class="align-middle bg-dark"></td>';
                                    $poOutput = 0;
                                }else{
                                    $tdSizeOutput = '<td class="align-middle" rowspan="2">'.$size.'</td>';
                                }
                            }
                            if ($poInput > 0 && $poOutput > 0) {
                                $mix = min(($value['qty_input']+$value['qty_shortage_input']),($value['qty_output']+$value['qty_shortage_output']));
                            }else{
                                $mix = 0;
                            }
                            $tbody.='
                                    <tr>
                                        '.$tdLine.'
                                        '.$tdOrder.'
                                        '.$tdPO.'
                                        '.$tdSizeInput.'
                                        '.$tdInput.'
                                        '.$tdPO.'
                                        '.$tdSizeOutput.'
                                        '.$tdOutput.'
                                        <td class="align-middle" rowspan="2">'.$mix.'</td>
                                        '.$tdRowSumInput.'
                                        '.$tdRowSumOutput.'
                                        '.$tdRowSumMix.'
                                        '.$tdRowMixPercent.'
                                        '.$tdRowVolumePercent.'
                                        '.$tdRowBTSPercent.'
                                    </tr>
                                    <tr>
                                        '.$tdShortageInput.'
                                        '.$tdShortageOutput.'
                                    </tr>
                                    ';
                            $rowspanLine = $lineKey;
                            $rowspanPO = $poKey;
                        }
                    }
                }
            }
            $table='<table class="table table-hover text-center" style="font-size: 80%;">
                        <thead class="bg-secondary">
                            <tr>
                                <th rowspan="2" class="align-middle">Line</th>
                                <th rowspan="2" class="align-middle">QTY Order</th>
                                <th colspan="3" class="bg-success">Input</th>
                                <th colspan="3" class="bg-primary">Output</th>
                                <th rowspan="2" class="align-middle">MIX</th>
                                <th rowspan="2" class="align-middle">Total Input</th>
                                <th rowspan="2" class="align-middle">Total Output</th>
                                <th rowspan="2" class="align-middle">Total MIX</th>
                                <th rowspan="2" class="align-middle">MIX %</th>
                                <th rowspan="2" class="align-middle">Volume %</th>
                                <th rowspan="2" class="align-middle">BTS %</th>
                            </tr>
                            <tr>
                                <th>PO</th>
                                <th>Size</th>
                                <th>QTY Input</th>
                                <th>PO</th>
                                <th>Size</th>
                                <th>QTY Output</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-detail-perline">'.$tbody.'</tbody>
                    </table>';
            // return $arr_;
            $data = array('table'=>$table,'nama_pengawas'=>$getNama->nama);
            return json_encode($data);
        }

        public function print(Request $request)
        {
            $option = $request->option;
            $date = $request->date;
            if (!isset($date)) {
                return Redirect::back()->withErrors(['msg' => 'Data Tanggal Harus di Isi!']);
            }
            if ($option == 'input') {
                $getData = db::table('stf___input_line as a');
                $getDataShortage = db::table('stf___shortage_input as a');
            }else if($option == 'output'){
                $getData = db::table('stf___output_line as a');
                $getDataShortage = db::table('stf___shortage_output as a');
            }else{
                return Redirect::back()->withErrors(['msg' => 'Data Export Reject Coming Soon']);
            }
            $getData = $getData->select('a.*','b.nama',db::raw('sum(a.qty) as sum_qty'),'c.buymonth','c.article','d.size as nama_size','c.g')
                                ->join('stf___pengawas as b','a.id_pengawas','=','b.nik')
                                ->join('dc__balance_order as c',function($join){
                                    $join->on('a.po','=','c.po');
                                    $join->on('a.wide','=','c.wide');
                                })
                                ->join('size_modes as d','a.size','=','d.id')
                                ->where('a.date',$date)
                                ->groupBy('a.id_pengawas','a.po','a.size')
                                ->orderBy('a.shift')
                                ->orderBy('b.nama')
                                ->orderBy('a.po')
                                ->orderBy('a.cell')
                                ->get();
            $getDataShortage = $getDataShortage->select('a.*','b.nama',db::raw('sum(a.qty) as sum_qty'),'c.buymonth','c.article','d.size as nama_size','c.g')
                                ->join('stf___pengawas as b','a.id_pengawas','=','b.nik')
                                ->join('dc__balance_order as c',function($join){
                                    $join->on('a.po','=','c.po');
                                    $join->on('a.wide','=','c.wide');
                                })
                                ->join('size_modes as d','a.size','=','d.id')
                                ->where('a.date',$date)
                                ->groupBy('a.id_pengawas','a.po','a.size')
                                ->orderBy('a.shift')
                                ->orderBy('b.nama')
                                ->orderBy('a.po')
                                ->orderBy('a.cell')
                                ->get();
            $arr_data = [];
            $size_index = 0;
            $total_prod = 0;
            $cell_terakhir = "";
            $po_terakhir = "";
            foreach ($getData as $key => $a) {
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['type']='normal';
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['color']='';
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['cell']=$a->cell;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['bm']=$a->buymonth;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['style']=$a->style;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['article']=$a->article;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size']=$a->nama_size;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['g']=$a->g;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['qty_order']=$a->qty_order;
                $getSize = db::table('size_modes as a')
                            ->select('a.size')
                            ->join('gender_modes as b','a.id_size','=','b.id_size')
                            ->where('gender',$a->g)->get();
                foreach ($getSize as $key => $b) {
                    $size_index++;
                    if (array_key_exists($a->po,$arr_data[$a->shift][$a->nama.'-'.$a->shift])) {
                        if (array_key_exists('size_'.$size_index,$arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po])) {
                            if ($a->nama_size==$b->size) {
                                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size_'.$size_index]=$a->sum_qty;
                                $total_prod+=$a->sum_qty;
                            }
                        }else{
                            if ($a->nama_size==$b->size) {
                                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size_'.$size_index]=$a->sum_qty;
                                $total_prod+=$a->sum_qty;
                            }else{
                                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size_'.$size_index]=0;
                            }
                        }
                    }else{
                        if ($a->nama_size==$b->size) {
                            $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size_'.$size_index]=$a->sum_qty;
                            $total_prod+=$a->sum_qty;
                        }else{
                            $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size_'.$size_index]=0;
                        }
                    }
                    $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['total_prod']=$total_prod;
                    if ($a->nama.'-'.$a->shift!=$cell_terakhir || $a->po != $po_terakhir) {
                        $total_prod = 0;
                    }
                    $cell_terakhir=$a->nama.'-'.$a->shift;
                    $po_terakhir=$a->po;
                }
                $size_index=0;
            }
            foreach ($getDataShortage as $key => $a){
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['type']='shortage';
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['color']='#ffea07';
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['cell']=$a->cell;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['bm']=$a->buymonth;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['style']=$a->style;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['article']=$a->article;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size']=$a->nama_size;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['g']=$a->g;
                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['qty_order']=$a->qty_order;
                $getSize = db::table('size_modes as a')
                            ->select('a.size')
                            ->join('gender_modes as b','a.id_size','=','b.id_size')
                            ->where('gender',$a->g)->get();
                foreach ($getSize as $key => $b) {
                    $size_index++;
                    if (array_key_exists($a->po,$arr_data[$a->shift][$a->nama.'-'.$a->shift])) {
                        if (array_key_exists('size_'.$size_index,$arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po])) {
                            if ($a->nama_size==$b->size) {
                                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size_'.$size_index]=$a->sum_qty;
                                $total_prod+=$a->sum_qty;
                            }
                        }else{
                            if ($a->nama_size==$b->size) {
                                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size_'.$size_index]=$a->sum_qty;
                                $total_prod+=$a->sum_qty;
                            }else{
                                $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size_'.$size_index]=0;
                            }
                        }
                    }else{
                        if ($a->nama_size==$b->size) {
                            $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size_'.$size_index]=$a->sum_qty;
                            $total_prod+=$a->sum_qty;
                        }else{
                            $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['size_'.$size_index]=0;
                        }
                    }
                    $arr_data[$a->shift][$a->nama.'-'.$a->shift][$a->po]['total_prod']=$total_prod;
                    if ($a->nama.'-'.$a->shift!=$cell_terakhir || $a->po != $po_terakhir) {
                        $total_prod = 0;
                    }
                    $cell_terakhir=$a->nama.'-'.$a->shift;
                    $po_terakhir=$a->po;
                }
                $size_index=0;
            }
            $data = array('arr_data'=>$arr_data,'option'=>$option,'date'=>$date);
            // return view('exports.stockfit.report',['data'=>$data]);
            return (new ExportStockfitLine($data))->download(strtoupper($option).'-summary.xlsx');
        }
        public function showDetail(Request $request)
        {
            $date = $request->date;
            $id_pengawas = $request->id_pengawas;
            $shift = $request->shift;
            $getData = db::table('stf___output_line as a')->select('a.po','a.line','c.nama as nama_pengawas','a.style','a.shift','a.cell','a.jam','b.size','a.qty')
                                ->where(['a.date'=>$date,'a.id_pengawas'=>$id_pengawas,'a.shift'=>$shift])
                                ->join('size_modes as b','a.size','=','b.id')
                                ->join('stf___pengawas as c','a.id_pengawas','=','c.nik')
                                ->orderBy('a.jam','asc')
                                ->orderBy('a.po','asc')
                                ->orderBy('a.size','asc')
                                ->get();
            $getDataShortage = db::table('stf___shortage_input as a')->select('a.po','a.line','a.style','a.shift','a.jam','b.size','a.qty')
                                ->where(['a.date'=>$date,'a.id_pengawas'=>$id_pengawas,'a.shift'=>$shift])
                                ->join('size_modes as b','a.size','=','b.id')
                                ->orderBy('a.jam','asc')
                                ->orderBy('a.po','asc')
                                ->orderBy('a.size','asc')
                                ->get();
            $tbody='';
            $sum_qty = 0;
            $sum_shortage = 0;
            $nama_pengawas = '';
            foreach ($getData as $key => $a) {
                $nama_pengawas = $a->nama_pengawas;
                $tbody.='<tr>
                            <td>'.$a->shift.'</td>
                            <td>'.$a->line.'</td>
                            <td>'.$a->jam.'</td>
                            <td>'.$a->cell.'</td>
                            <td>'.$a->po.'</td>
                            <td>'.$a->style.'</td>
                            <td>'.$a->size.'</td>
                            <td>'.$a->qty.'</td>
                        </tr>';
                $sum_qty+=$a->qty;
            }
            foreach ($getDataShortage as $key => $a) {
                $tbody.='<tr class="bg-warning">
                            <td>'.$a->shift.'</td>
                            <td>'.$a->line.'</td>
                            <td>'.$a->jam.'</td>
                            <td>Shortage</td>
                            <td>'.$a->po.'</td>
                            <td>'.$a->style.'</td>
                            <td>'.$a->size.'</td>
                            <td>'.$a->qty.'</td>
                        </tr>';
                $sum_shortage+=$a->qty;
            }

            $tfoot = '<tr class="bg-secondary">
                                <td colspan="7">Total Output</td>
                                <td>'.$sum_qty+$sum_shortage.'</td>
                            </tr>';
            $data = array(
                    'tbody'=>$tbody,
                    'tfoot'=>$tfoot,
                    'nama_pengawas'=>$nama_pengawas
                    );
            return json_encode($data);
        }
            function findColor($val)
            {
                if ($val >= 100 ) {
                    $color = 'success';
                }else if ($val >= 50) {
                    $color = 'warning';
                }else if ($val>0){
                    $color = 'danger';
                }else{
                    $color = 'dark';
                }
                return $color;
            }
        function getPengawas_List($date,$shift,$pengawas)
        {
            $getLine = db::table('stf___input_line as a')->select('b.nama','a.id_pengawas')
                            ->join('stf___pengawas as b','a.id_pengawas','=','b.nik')
                            ->where('a.date',$date)->where('a.shift',$shift)->groupBy('a.id_pengawas')->get();
                $nama = '';
            if (count($getLine)>0) {
                if ($pengawas == '') {
                    $nama = $getLine[0]->nama;
                    $pengawas = $getLine[0]->id_pengawas;
                }
            }
            $pengawas_list = '';
            foreach ($getLine as $key => $a) {
                $pengawas_list.='<option value="'.$a->id_pengawas.'">'.$a->nama.'</option>';
            }
            $data = array('pengawas_list'=>$pengawas_list,'nama_pengawas'=>$nama,'id_pengawas'=>$pengawas);
            return $data;
        }
        public function change_bm(Request $request)
        {
            $bm = $request->bm;
            $dataBm = balanceOrder::where('buymonth',$bm)->groupby('po')->get();
            $list_po = '';
            foreach ($dataBm as $a ) {
                $list_po.='<option>'.$a->po.'</option>';
            }
            $data = array('list_po'=>$list_po);
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
                if ($form =="output" || $form =="shortage_output") {
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
                        $dataForm = stf___input_line::select('size',db::raw('sum(qty) as sumqty'))->where($identitasBalanceOrder)->groupby('size')->get();
                        $dataAll = balanceOrder::where($identitasBalanceOrder)->get();
                    }else if ($form == 'shortage_input'){
                        $dataForm = stf___shortage_input::select('size',db::raw('sum(qty) as sumqty'))->where($identitasBalanceOrder)->groupby('size')->get();
                        $dataAll = balanceOrder::where($identitasBalanceOrder)->get();
                    }else if ($form == 'output') {
                        $dataForm = db::table('stf___output_line as a')->select('a.size',db::raw('sum(a.qty) as sumqty'))->where($identitasBalanceOrder)->groupby('a.size')->get();
                        $query_output = db::table('stf___input_line as a')->select('a.*','b.g',db::raw('sum(a.qty) as sum_qty'))
                                        ->join('dc__balance_order as b', function($join){
                                            $join->on('a.po','=','b.po');
                                            $join->on('a.wide','=','b.wide');
                                            $join->on('a.cell','=','b.cell');
                                        })->where($identitasBalanceOrder);
                        $dataAll = $query_output->groupby('a.po','a.wide','a.cell')->get();
                        $dataOutput = $query_output->groupby('a.size')->get();
                    }else if ($form == 'shortage_output'){
                        $dataForm = db::table('stf___shortage_output as a')->select('a.size',db::raw('sum(a.qty) as sumqty'))->where($identitasBalanceOrder)->groupby('a.size')->get();
                        $query_output = db::table('stf___shortage_input as a')->select('a.*','b.g',db::raw('sum(a.qty) as sum_qty'))
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
                    if ($form == "output"||$form == "shortage_output") {
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
        function convPercent($total_output,$total_reject)
        {
            if ($total_output!=0) {
                $percent = number_format((($total_output-$total_reject)/$total_output)*100,2);
            }else{
                $percent=0;
            }
            $data = $percent;
            return $data;
        }
        public function getPo_list()
        {
            $getPO = balanceOrder::select('po')->groupby('po')->get();
            $getInputPO = stf___input_line::select('po')->groupby('po')->get();
            $getShortageInputPO = stf___shortage_input::select('po')->groupby('po')->get();
            $po_list='';
            $output_po_list='';
            $output_shortage_po_list='';
            foreach ($getPO as $a) {
                $po_list.='<option>'.$a->po.'</option>';
            }
            foreach ($getInputPO as $a) {
                $output_po_list.='<option>'.$a->po.'</option>';
            }
            foreach ($getShortageInputPO as $a) {
                $output_shortage_po_list.='<option>'.$a->po.'</option>';
            }
            $data = array('po_list'=>$po_list,'output_po_list'=>$output_po_list,'output_shortage_po_list'=>$output_shortage_po_list);
            return json_encode($data);
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
        public function save_input(Request $request)
        {
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
                            stf___input_line::insert([
                                'shift'=>$shift,
                                'id_pengawas'=>$id_pengawas,
                                'line'=>$line,
                                'jam'=>$jam,
                                'date'=>$date,
                                'po'=>$po,
                                'style'=>$style,
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
            function cek_kepemilikan_line($shift,$date,$line,$id_pengawas,$form)
            {
                if ($form == 'input') {
                    $cek = stf___input_line::where(['shift'=>$shift,'date'=>$date,'line'=>$line])->where('id_pengawas','!=',$id_pengawas)->get();
                }else{
                    $cek = stf___output_line::where(['shift'=>$shift,'date'=>$date,'line'=>$line])->where('id_pengawas','!=',$id_pengawas)->get();
                }
                if (count($cek)>1) {
                    $status = 1;
                }else{
                    $status = 0;
                }
                return $status;
            }
        public function save_output(Request $request)
        {
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
            $qty_output = $request->qty_output;

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
                    $cek_kepemilikan_line = $this->cek_kepemilikan_line($shift,$date,$line,$id_pengawas,'output');
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
                for ($i=0; $i < count($qty_output); $i++) {
                    if ($qty_output[$i]>$qty_balance[$i]) {
                        $data = array(
                            'alert'=>'Gagal!',
                            'text'=> 'Data Size : '.$arrSizeName[$i].' Tidak boleh lebih dari balance order',
                            'color'=>'danger'
                        );
                        return json_encode($data);
                    }
                    if (isset($qty_output[$i])||$qty_output[$i]=='0') {
                        $cekNull++;
                        $arrOutput[]=(int)$qty_output[$i];
                        $arrSizeOutput[]=$arrsize[$i];
                    }
                }
                //cek data Output
                if ($cekNull>0) {
                    try {
                        for ($i=0; $i < count($arrOutput); $i++) {
                            stf___output_line::insert([
                                'shift'=>$shift,
                                'id_pengawas'=>$id_pengawas,
                                'line'=>$line,
                                'jam'=>$jam,
                                'date'=>$date,
                                'po'=>$po,
                                'style'=>$style,
                                'wide'=>$wide,
                                'cell'=>$cell,
                                'qty_order'=>$qty_po,
                                'size'=>$arrSizeOutput[$i],
                                'qty'=>$arrOutput[$i],
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
                'cell'=>$cell,
                'po'=>$po,
                'wide'=>$wide,
                'shift'=>$shift,
                'jam'=>$jam,
                'form'=>'output'
            );
            return json_encode($data);
        }
        public function save_shortage_input(Request $request)
        {
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

            $qty_shortage = $request->qty_shortage_input;

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
                    $cek_kepemilikan_line = $this->cek_kepemilikan_line($shift,$date,$line,$id_pengawas,'shortage');
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
                                ->select('a.id')->join('gender_modes as b','a.id_size','=','b.id_size')
                                ->where('gender',$gender)->get();
                $arrsize=[];
                    foreach ($getSize as $key => $value) {
                        $arrsize[]=$value->id;
                    }
                for ($i=0; $i < count($qty_shortage); $i++) {
                    if (isset($qty_shortage[$i])||$qty_shortage[$i]=='0') {
                        $cekNull++;
                        $arrOutput[]=(int)$qty_shortage[$i];
                        $arrSizeOutput[]=$arrsize[$i];
                    }
                }
                //cek data Output
                if ($cekNull>0) {
                    try {
                        for ($i=0; $i < count($arrOutput); $i++) {
                            stf___shortage_input::insert([
                                'shift'=>$shift,
                                'id_pengawas'=>$id_pengawas,
                                'line'=>$line,
                                'jam'=>$jam,
                                'date'=>$date,
                                'po'=>$po,
                                'style'=>$style,
                                'wide'=>$wide,
                                'cell'=>$cell,
                                'qty_order'=>$qty_po,
                                'size'=>$arrSizeOutput[$i],
                                'qty'=>$arrOutput[$i],
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
                'wide'=>$wide,
                'shift'=>$shift,
                'jam'=>$jam,
                'form'=>'shortage_input'
            );
            return json_encode($data);
        }
        public function save_shortage_output(Request $request)
        {
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

            $qty_shortage = $request->qty_shortage_output;

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
                    $cek_kepemilikan_line = $this->cek_kepemilikan_line($shift,$date,$line,$id_pengawas,'shortage');
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
                                ->select('a.id')->join('gender_modes as b','a.id_size','=','b.id_size')
                                ->where('gender',$gender)->get();
                $arrsize=[];
                    foreach ($getSize as $key => $value) {
                        $arrsize[]=$value->id;
                    }
                for ($i=0; $i < count($qty_shortage); $i++) {
                    if (isset($qty_shortage[$i])||$qty_shortage[$i]=='0') {
                        $cekNull++;
                        $arrOutput[]=(int)$qty_shortage[$i];
                        $arrSizeOutput[]=$arrsize[$i];
                    }
                }
                //cek data Output
                if ($cekNull>0) {
                    try {
                        for ($i=0; $i < count($arrOutput); $i++) {
                            stf___shortage_output::insert([
                                'shift'=>$shift,
                                'id_pengawas'=>$id_pengawas,
                                'line'=>$line,
                                'jam'=>$jam,
                                'date'=>$date,
                                'po'=>$po,
                                'style'=>$style,
                                'wide'=>$wide,
                                'cell'=>$cell,
                                'qty_order'=>$qty_po,
                                'size'=>$arrSizeOutput[$i],
                                'qty'=>$arrOutput[$i],
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
                'wide'=>$wide,
                'shift'=>$shift,
                'jam'=>$jam,
                'form'=>'shortage_input'
            );
            return json_encode($data);
        }
        public function save_reject(Request $request)
        {
            $shift = $request->shift;
            $pengawas = $request->pengawas;
            $jam = $request->jam;
            $jenis = $request->jenis;

            $date = $request->date;

            $qty = $request->qty;

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
                if ($jam == '') {
                    $alert = 'gagal';
                    $text = 'Data Jam Harus di isi';
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
                if ($jenis == '') {
                    $alert = 'gagal';
                    $text = 'Data Defect Jenis Harus di isi';
                    $data=array(
                        'alert'=>$alert,
                        'text'=>$text,
                        'color'=>'danger'
                    );
                    return json_encode($data);
                }
                if ($qty == '') {
                    $alert = 'gagal';
                    $text = 'Data QTY Harus di isi';
                    $data=array(
                        'alert'=>$alert,
                        'text'=>$text,
                        'color'=>'danger'
                    );
                    return json_encode($data);
                }
                if ($pengawas == 'Pilih Pengawas') {
                    $alert = 'gagal';
                    $text = 'Data Pengawas Harus di isi';
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
            //save
                try {
                        stf___reject_line::insert([
                            'shift'=>$shift,
                            'id_pengawas'=>$pengawas,
                            'jam'=>$jam,
                            'jenis'=>$jenis,
                            'date'=>$date,
                            'qty'=>$qty,
                        ]);
                } catch (\Exception $e) {
                    $data = array(
                        'alert'=>'Gagal!',
                        'text'=> $e->getMessage(),
                        'color'=>'danger'
                    );
                    return json_encode($data);
                }
            $data=array(
                'alert'=>'sukses',
                'text'=>'Data berhasil di simpan',
                'color'=>'success',
                'shift'=>$shift,
                'jam'=>$jam,
                'form'=>'reject'
            );
            return json_encode($data);
        }
        public function change_po(Request $request)
        {
            $po = $request->po;
            $getPO = balanceOrder::select('buymonth')->where('po',$po)->groupby('buymonth')->get();
            foreach ($getPO as $a) {
                $bm=$a->buymonth;
            }
            $data = array('bm'=>$bm);
            return json_encode($data);
        }
        public function get_wip(Request $request)
        {
            $id_pengawas = $request->pengawas;
            if ($id_pengawas == '') {
                return null;
            }
            $getNama = stf___pengawas::where('nik',$id_pengawas)->first();
            $shift = $request->shift;
            if ($shift == '') {
                return null;
            }
            $getData = db::select("select if(sum(input),id,null) as id_pengawas,
                                            if(sum(input),po,null) as po,
                                            if(sum(input),wide,null) as wide,
                                            if(sum(input),style,null) as style,
                                            if(sum(input),size,null) as size,
                                            if(sum(input),size_name,null) as size_name,
                                            if(sum(input),date_,null) as date_,
                                            sum(input) as qty_input, sum(output) as qty_output,
                                            sum(input)-sum(output) as wip
                                        FROM
                                        (
                                            SELECT a.id_pengawas as id, a.po, a.wide, a.style,a.size, b.size size_name,a.date as date_, a.qty as input , null as output
                                            FROM stf___input_line as a
                                            inner join size_modes as b on a.size=b.id
                                            where a.id_pengawas = '".$id_pengawas."'
                                            UNION ALL
                                            SELECT a.id_pengawas as id, a.po, a.wide, a.style,a.size, b.size size_name,a.date as date_, null, qty as ouput
                                            FROM stf___output_line as a
                                            inner join size_modes as b on a.size=b.id
                                            where a.id_pengawas = '".$id_pengawas."'
                                        ) as sub
                                        group by sub.po,sub.wide,sub.size
                                        having date_ != CURDATE() and (qty_output is null or qty_input>qty_output)
                                        order by po,wide,size");
            $getDataShortage = db::select("select if(sum(input),id,null) as id_pengawas,
                                            if(sum(input),po,null) as po,
                                            if(sum(input),wide,null) as wide,
                                            if(sum(input),style,null) as style,
                                            if(sum(input),size,null) as size,
                                            if(sum(input),size_name,null) as size_name,
                                            if(sum(input),date_,null) as date_,
                                            sum(input) as qty_input_shortage, sum(output) as qty_output_shortage,
                                            sum(input)-sum(output) as wip
                                        FROM
                                        (
                                            SELECT a.id_pengawas as id, a.po, a.wide, a.style,a.size, b.size size_name,a.date as date_, a.qty as input , null as output
                                            FROM stf___shortage_input as a
                                            inner join size_modes as b on a.size=b.id
                                            where a.id_pengawas = '".$id_pengawas."'
                                            UNION ALL
                                            SELECT a.id_pengawas as id, a.po, a.wide, a.style,a.size, b.size size_name,a.date as date_, null, qty as ouput
                                            FROM stf___shortage_output as a
                                            inner join size_modes as b on a.size=b.id
                                            where a.id_pengawas = '".$id_pengawas."'
                                        ) as sub
                                        group by sub.po,sub.wide,sub.size
                                        having date_ != CURDATE() and (qty_output_shortage is null or qty_input_shortage>qty_output_shortage)
                                        order by po,wide,size");
            $td_po = '';
            $tbodyShortage = '';
            $tbody = '';
            $last_po = '';
            $arr_ = [];
            foreach ($getData as $key => $a) {
                $arr_[$a->po][$a->size]=[
                                            'id_pengawas'=>$a->id_pengawas,
                                            'po'=>$a->po,
                                            'wide'=>$a->wide,
                                            'style'=>$a->style,
                                            'size'=>$a->size,
                                            'size_name'=>$a->size_name,
                                            'qty_input'=>$a->qty_input,
                                            'qty_input_shortage'=>0,
                                            'qty_output'=>$a->qty_output,
                                            'qty_output_shortage'=>0,
                                            'wip'=>$a->wip
                                        ];
            }
            // return $arr_;
            foreach ($getDataShortage as $key => $a) {
                if (array_key_exists($a->po, $arr_)) {
                    if (array_key_exists($a->size,$arr_[$a->po])) {
                        $arr_[$a->po][$a->size]['qty_input_shortage']=$arr_[$a->po][$a->size]['qty_input_shortage']+$a->qty_input_shortage;
                        $arr_[$a->po][$a->size]['qty_output_shortage']=$arr_[$a->po][$a->size]['qty_output_shortage']+$a->qty_input_shortage;
                    }else{
                        $arr_[$a->po][$a->size]=[
                                                    'id_pengawas'=>$a->id_pengawas,
                                                    'po'=>$a->po,
                                                    'wide'=>$a->wide,
                                                    'style'=>$a->style,
                                                    'size'=>$a->size,
                                                    'size_name'=>$a->size_name,
                                                    'qty_input'=>0,
                                                    'qty_input_shortage'=>$a->qty_input_shortage,
                                                    'qty_output'=>0,
                                                    'qty_output_shortage'=>$a->qty_output_shortage,
                                                    'wip'=>$a->wip
                                                ];
                    }
                }else{
                    $arr_[$a->po][$a->size]=[
                                                'id_pengawas'=>$a->id_pengawas,
                                                'po'=>$a->po,
                                                'wide'=>$a->wide,
                                                'style'=>$a->style,
                                                'size'=>$a->size,
                                                'size_name'=>$a->size_name,
                                                'qty_input'=>0,
                                                'qty_input_shortage'=>$a->qty_input_shortage,
                                                'qty_output'=>0,
                                                'qty_output_shortage'=>$a->qty_output_shortage,
                                                'wip'=>$a->wip
                                            ];
                }
            }
            // return $arr_;
            $count_po='';
            foreach ($arr_ as $key => $value) {
                foreach ($value as $a) {
                    if ($count_po != $key) {
                        $td_po='<td class="align-middle" rowspan="'.count($arr_[$key]).'">'.$key.'</td>';
                    }else{
                        $td_po='';
                    }
                    if ($a['qty_input_shortage']==0) {
                        if (empty($a['qty_output'])) {
                            $qty_output =0;
                            $color ='bg-danger';
                        }else{
                            $qty_output = $a['qty_output'];
                            $color ='bg-info';
                        }
                        if (empty($a['wip'])) {
                            $qty_wip = $a['qty_input'];
                        }else{
                            $qty_wip = $a['wip'];
                        }
                        $tbody .='<tr>
                                '.$td_po.'
                                <td>'.$a['wide'].'</td>
                                <td>'.$a['style'].'</td>
                                <td>'.$a['size_name'].'</td>
                                <td>'.$a['qty_input'].'</td>
                                <td class="'.$color.'">'.$qty_output.'</td>
                                <td class="'.$color.'">'.$qty_wip.'</td>
                            </tr>';
                    }else{
                        if (empty($a['qty_output_shortage'])) {
                            $qty_output =0;
                        }else{
                            $qty_output = $a['qty_output_shortage'];
                        }
                        if (empty($a['wip'])) {
                            $qty_wip = $a['qty_input_shortage'];
                        }else{
                            $qty_wip = $a['wip'];
                        }
                        $tbodyShortage .='<tr>
                                '.$td_po.'
                                <td class="bg-warning">'.$a['wide'].'</td>
                                <td class="bg-warning">'.$a['style'].'</td>
                                <td class="bg-warning">'.$a['size_name'].'</td>
                                <td class="bg-warning">'.$a['qty_input_shortage'].'</td>
                                <td class="bg-warning">'.$qty_output.'</td>
                                <td class="bg-warning">'.$qty_wip.'</td>
                            </tr>';
                    }
                    $count_po=$key;
                }
            }
            $table='<table class="table table-hover text-center" style="font-size: 80%;">
                        <thead class="bg-secondary">
                            <tr>
                                <th colspan="7">WIP STANDAR</th>
                            </tr>
                            <tr>
                                <th class="align-middle">PO</th>
                                <th class="align-middle">Wide</th>
                                <th class="align-middle">Style</th>
                                <th class="align-middle">Size</th>
                                <th class="align-middle">Qty Input</th>
                                <th class="align-middle">Qty Output</th>
                                <th class="align-middle">WIP</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>'.$tbody.'</tbody>
                    </table>
                    <table class="table table-hover text-center" style="font-size: 80%;">
                        <thead class="bg-secondary">
                            <tr>
                                <th colspan="7">WIP SHORTAGE</th>
                            </tr>
                            <tr>
                                <th class="align-middle">PO</th>
                                <th class="align-middle">Wide</th>
                                <th class="align-middle">Style</th>
                                <th class="align-middle">Size</th>
                                <th class="align-middle">Qty Input</th>
                                <th class="align-middle">Qty Output</th>
                                <th class="align-middle">WIP</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>'.$tbodyShortage.'</tbody>
                    </table>';
            $data = array('table'=>$table,'nama_pengawas'=>$getNama->nama);
            return json_encode($data);

        }
        public function detail_perline(Request $request)
        {
            $jam = $request->jam;
            $id_pengawas = $request->pengawas;
                $getNama = stf___pengawas::where('nik',$id_pengawas)->first();
            $shift = $request->shift;
            $form = $request->form;
            $when = $request->when;
            if ($form == 'input') {
                $getData = db::table('stf___input_line as a')->select('a.*','b.size as size_name')->join('size_modes as b','a.size','=','b.id')->where(['a.jam'=>$jam,'a.id_pengawas'=>$id_pengawas,'a.shift'=>$shift,'a.date'=>$when])->get();
                $getDataShortage = db::table('stf___shortage_input as a')->select('a.*','b.size as size_name')->join('size_modes as b','a.size','=','b.id')->where(['a.jam'=>$jam,'a.id_pengawas'=>$id_pengawas,'a.shift'=>$shift,'a.date'=>$when])->get();
            }else if($form == 'output'){
                $getData = db::table('stf___output_line as a')->select('a.*','b.size as size_name')->join('size_modes as b','a.size','=','b.id')->where(['a.jam'=>$jam,'a.id_pengawas'=>$id_pengawas,'a.shift'=>$shift,'a.date'=>$when])->get();
                $getDataShortage = db::table('stf___shortage_output as a')->select('a.*','b.size as size_name')->join('size_modes as b','a.size','=','b.id')->where(['a.jam'=>$jam,'a.id_pengawas'=>$id_pengawas,'a.shift'=>$shift,'a.date'=>$when])->get();
            }else{
                $getData = db::table('stf___reject_line as a')->select('a.*')->where(['a.jam'=>$jam,'a.id_pengawas'=>$id_pengawas,'a.shift'=>$shift,'a.date'=>$when])->get();
            }
            $table = '';
            $tbody = '';
            $no = 0;
            if ($form == 'reject') {
                foreach ($getData as $key => $a) {
                    $no++;
                    $tbody.='
                            <tr>
                                <td ><input class="form-check-input checkboxDetailPerline" type="checkbox" value="'.$a->id.'" name="id[]"></td>
                                <td>'.$no.'</td>
                                <td>'.$a->jenis.'</td>
                                <td>'.$a->qty.'</td>
                            </tr>
                            ';
                }
                $table = '
                        <table class="table table-hover text-center" style="font-size: 80%;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th><button type="button" onclick="checkAllDetailPerLine()" class="btn btn-primary">All</button></th>
                                    <th>No</th>
                                    <th>JENIS</th>
                                    <th>QTY <a id="th-modal-detail-perline"></a></th>
                                </tr>
                            </thead>
                            <tbody id="tbody-detail-perline">'.$tbody.'</tbody>
                        </table>
                    ';
            }else{
                foreach ($getData as $key => $a) {
                    $no++;
                    $tbody.='
                            <tr>
                                    <td ><input class="form-check-input checkboxDetailPerline" type="checkbox" value="'.$a->id.'" name="id[]"></td>
                                    <td>'.$no.'</td>
                                    <td>'.$a->line.'</td>
                                    <td>'.$a->po.'</td>
                                    <td>'.$a->style.'</td>
                                    <td>'.$a->wide.'</td>
                                    <td>'.$a->cell.'</td>
                                    <td>'.$a->qty_order.'</td>
                                    <td>'.$a->size_name.'</td>
                                    <td>'.$a->qty.'</td>
                                </tr>
                            ';
                }
                foreach ($getDataShortage as $key => $a) {
                    $tbody.='
                            <tr class="bg-warning">
                                <td ><input class="form-check-input checkboxDetailPerline" type="checkbox" value="'.$a->id.'" name="idShortage[]"></td>
                                <td>'.$no.'</td>
                                <td>'.$a->line.'</td>
                                <td>'.$a->po.'</td>
                                <td>'.$a->style.'</td>
                                <td>'.$a->wide.'</td>
                                <td>'.$a->cell.'</td>
                                <td>'.$a->qty_order.'</td>
                                <td>'.$a->size_name.'</td>
                                <td>'.$a->qty.'</td>
                            </tr>
                            ';
                }
                $table = '
                        <table class="table table-hover text-center" style="font-size: 80%;">
                            <thead class="bg-secondary">
                                <tr>
                                    <th><button type="button" onclick="checkAllDetailPerLine()" class="btn btn-primary">All</button></th>
                                    <th>No</th>
                                    <th>LINE</th>
                                    <th>PO</th>
                                    <th>STYLE</th>
                                    <th>WIDE</th>
                                    <th>CELL</th>
                                    <th>QTY ORDER</th>
                                    <th>SIZE</th>
                                    <th>QTY <a id="th-modal-detail-perline"></a></th>
                                </tr>
                            </thead>
                            <tbody>'.$tbody.'</tbody>
                        </table>
                    ';
            }
            $data = array('table'=>$table,'nama_pengawas'=>$getNama->nama);
            return json_encode($data);
        }
        public function delete_detail_perline(Request $request)
        {
            $id = $request->id;
            $idShortage = $request->idShortage;
            $form= $request->form;
            try {
                if ($form) {
                    if ($form == 'input') {
                        if (isset($id)) {
                            for ($i=0; $i < count($id); $i++) {
                                $getData = stf___input_line::where(['id'=>$id[$i]])->delete();
                            }
                        }
                        if (isset($idShortage)) {
                            for ($i=0; $i < count($idShortage); $i++) {
                                $getData = stf___shortage_input::where(['id'=>$idShortage[$i]])->delete();
                            }
                        }
                    }else if($form == 'output'){
                        if (isset($id)) {
                            for ($i=0; $i < count($id); $i++) {
                                $getData = stf___output_line::where(['id'=>$id[$i]])->delete();
                            }
                        }
                        if (isset($idShortage)) {
                            for ($i=0; $i < count($idShortage); $i++) {
                                $getData = stf___shortage_output::where(['id'=>$idShortage[$i]])->delete();
                            }
                        }
                    }else{
                        if (isset($id)) {
                            for ($i=0; $i < count($id); $i++) {
                            $getData = stf___reject_line::where(['id'=>$id[$i]])->delete();
                            }
                        }
                    }
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
                'alert'=>'sukses',
                'text'=> 'Data Detail '.$form.' berhasil di hapus',
                'form'=>$form,
                'jam'=>$request->jam,
                'when'=>$request->date,
                'pengawas'=>$request->pengawas,
                'shift'=>$request->shift,
                'color'=>'success'
            );
            return json_encode($data);
        }

}
