<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\balanceOrder;
use App\Models\stf___input_line;
use App\Models\stf___shortage_input;
use App\Models\stf___output_line;
use App\Models\stf___shortage_output;
use App\Exports\balanceStockfit;

class stfBalanceController extends Controller
{
    public function balance(){
        return view('stockfit.stockfit_line.balance');
    }

    function main() {
        $dataBalance = balanceOrder::groupby('po','wide','qty')->orderBy('cell')->orderBy('xfd')->get();
        $dataInputStockfit = $this->dataStockfit([]);
        $bm_list=$cell_list=$style_list=$article_list=$wide_list=$g_list=$po_list=$xfd_list=[];
        $list_bm=$list_cell=$list_style=$list_article=$list_wide=$list_g=$list_po=$list_xfd='';
        $table = '';
        foreach ($dataBalance as $key => $a) {
            //DATABASE
                $bm_list[$a->buymonth]=$a->buymonth;
                $cell_list[$a->cell]=$a->cell;
                $style_list[$a->style]=$a->style;
                $article_list[$a->article]=$a->article;
                $wide_list[$a->wide]=$a->wide;
                $g_list[$a->g]=$a->g;
                $po_list[$a->po]=$a->po;
                $xfd_list[$a->xfd]=$a->xfd;
                //STOCKFIT
                    //input
                        if (array_key_exists($a->po.'-'.$a->wide.'-'.$a->qty,$dataInputStockfit['input'])) {
                            $input_stf = $dataInputStockfit['input'][$a->po.'-'.$a->wide.'-'.$a->qty]['qty'];
                            $color_input_stf = $this->getColor($input_stf,$a->qty);

                        }else{
                            $color_input_stf = '';
                            $color_wip = '';
                            $input_stf=0;
                        }
                    //output
                        if (array_key_exists($a->po.'-'.$a->wide.'-'.$a->qty,$dataInputStockfit['output'])) {
                            $output_stf = $dataInputStockfit['output'][$a->po.'-'.$a->wide.'-'.$a->qty]['qty'];
                            $color_output_stf = $this->getColor($output_stf,$a->qty);

                        }else{
                            $color_output_stf = '';
                            $output_stf=0;
                        }
                    //wip
                    if ($input_stf!=0) {
                        $wip = $input_stf-$output_stf;
                        $color_wip = $this->getColorwip($wip, $input_stf);
                    }else{
                        $color_wip = '';
                        $wip=0;
                    }
                    $cell = $a->cell;
                    if (strlen($a->cell)<5 && strlen($a->cell)!=2) {
                        $cell = substr($a->cell,0,3).'0'.substr($a->cell,3);
                    }
                    $data_table[]=[
                                        'buymonth'=>$a->buymonth,
                                        'cell'=>$cell,
                                        'style'=>$a->style,
                                        'article'=>$a->article,
                                        'wide'=>$a->wide,
                                        'g'=>$a->g,
                                        'po'=>$a->po,
                                        'xfd'=>$a->xfd,
                                        'qty'=>$a->qty,
                                        'input_stf'=>$input_stf,
                                        'output_stf'=>$output_stf,
                                        'wip'=>$input_stf-$output_stf,
                                        'color_wip'=>$color_wip,
                                        'color_input_stf'=>$color_input_stf,
                                        'color_output_stf'=>$color_output_stf,
                                    ];
        }
        foreach ($data_table as $k => $v) {
            $b[]=strtolower($v['cell']);
        }
        natsort($b);
        foreach ($b as $k => $v) {
            $c[]=$data_table[$k];
        }
        $cell_last = '';
        // set variable total qty
            $tot_qty=['qty_order'=>0,'input_stf'=>0,'output_stf'=>0,'wip'=>0,'balance_input'=>0,'balance_output'=>0];
        foreach ($c as $key => $value) {
            if ($cell_last != $value['cell'] && $cell_last!='') {
                $color_tot_input_stf = $this->getColor($tot_qty['input_stf'],$tot_qty['qty_order']);
                $color_tot_output_stf = $this->getColor($tot_qty['output_stf'],$tot_qty['qty_order']);
                $color_tot_wip_stf = $this->getColorwip($tot_qty['wip'],$tot_qty['input_stf']);
                $color_tot_balance_input = $this->getColor($tot_qty['input_stf'],$tot_qty['qty_order']);
                $color_tot_balance_output = $this->getColor($tot_qty['output_stf'],$tot_qty['qty_order']);
                $table .= '<tr>
                            <td class="bg-secondary" colspan="8">Total '.$cell_last.'</td>
                            <td class="bg-secondary">'.number_format($tot_qty['qty_order']).'</td>
                            <td style="'.$color_tot_input_stf.'">'.number_format($tot_qty['input_stf']).'</td>
                            <td style="'.$color_tot_output_stf.'">'.number_format($tot_qty['output_stf']).'</td>
                            <td style="'.$color_tot_wip_stf.'">'.number_format($tot_qty['wip']).'</td>
                            <td style="'.$color_tot_balance_input.'">'.number_format($tot_qty['balance_input']).'</td>
                            <td style="'.$color_tot_balance_output.'">'.number_format($tot_qty['balance_output']).'</td>
                        </tr>
                        <tr>
                            <td colspan="14" style="border:0"></td>
                        </tr>';
                $tot_qty['qty_order']=0;
                $tot_qty['input_stf']=0;
                $tot_qty['output_stf']=0;
                $tot_qty['wip']=0;
                $tot_qty['balance_input']=0;
                $tot_qty['balance_output']=0;
            }else{
                $balance_input = $value['qty']-$value['input_stf'];
                $balance_output = $value['qty']-$value['output_stf'];
                $color_balance_input = $this->getColorBalance($balance_input,$value['qty']);
                $color_balance_output = $this->getColorBalance($balance_output,$value['qty']);
                $table .= '<tr>
                                <td>'.$value['buymonth'].'</td>
                                <td>'.$value['cell'].'</td>
                                <td>'.$value['style'].'</td>
                                <td>'.$value['article'].'</td>
                                <td>'.$value['wide'].'</td>
                                <td>'.$value['g'].'</td>
                                <td>'.$value['po'].'</td>
                                <td>'.$value['xfd'].'</td>
                                <td>'.number_format($value['qty']).'</td>
                                <td style="'.$value['color_input_stf'].'">'.number_format($value['input_stf']).'</td>
                                <td style="'.$value['color_output_stf'].'">'.number_format($value['output_stf']).'</td>
                                <td style="'.$value['color_wip'].'">'.number_format($value['wip']).'</td>
                                <td style="'.$color_balance_input.'">'.number_format($balance_input).'</td>
                                <td style="'.$color_balance_output.'">'.number_format($balance_output).'</td>
                            </tr>';
                $tot_qty['qty_order']=$tot_qty['qty_order']+$value['qty'];
                $tot_qty['input_stf']=$tot_qty['input_stf']+$value['input_stf'];
                $tot_qty['output_stf']=$tot_qty['output_stf']+$value['output_stf'];
                $tot_qty['wip']=$tot_qty['wip']+$value['wip'];
                $tot_qty['balance_input']=$tot_qty['balance_input']+$balance_input;
                $tot_qty['balance_output']=$tot_qty['balance_output']+$balance_output;
            }
            $cell_last = $value['cell'];
        }
        //get List
            foreach ($bm_list as $key => $a) {
                $list_bm.='<option>'.$a.'</option>';
            }
            foreach ($cell_list as $key => $a) {
                $list_cell.='<option>'.$a.'</option>';
            }
            foreach ($style_list as $key => $a) {
                $list_style.='<option>'.$a.'</option>';
            }
            foreach ($article_list as $key => $a) {
                $list_article.='<option>'.$a.'</option>';
            }
            foreach ($wide_list as $key => $a) {
                $list_wide.='<option>'.$a.'</option>';
            }
            foreach ($g_list as $key => $a) {
                $list_g.='<option>'.$a.'</option>';
            }
            foreach ($po_list as $key => $a) {
                $list_po.='<option>'.$a.'</option>';
            }
            foreach ($xfd_list as $key => $a) {
                $list_xfd.='<option>'.$a.'</option>';
            }
        $data=array(
            'table'=>$table,
            'list_bm'=>$list_bm,
            'list_cell'=>$list_cell,
            'list_style'=>$list_style,
            'list_article'=>$list_article,
            'list_wide'=>$list_wide,
            'list_g'=>$list_g,
            'list_po'=>$list_po,
            'list_xfd'=>$list_xfd,
        );
        return json_encode($data);
    }
    function balance_search(Request $request) {
        //get where
            $where=[];
            if (isset($request->bm)) {
                $where['buymonth']=$request->bm;
            }
            if (isset($request->cell)) {
                $where['cell']=$request->cell;
            }
            if (isset($request->no_urut)) {
                $where['no_urut']=$request->no_urut;
            }
            if (isset($request->style)) {
                $where['style']=$request->style;
            }
            if (isset($request->article)) {
                $where['article']=$request->article;
            }
            if (isset($request->wide)) {
                $where['wide']=$request->wide;
            }
            if (isset($request->g)) {
                $where['g']=$request->g;
            }
            if (isset($request->po)) {
                $where['po']=$request->po;
            }
            if (isset($request->xfd)) {
                $where['xfd']=$request->xfd;
            }
        $dataBalance = balanceOrder::where($where)->groupby('po','wide','qty')->orderBy('cell')->orderBy('xfd')->get();
        $dataInputStockfit = $this->dataStockfit($where);
        $bm_list=$cell_list=$style_list=$article_list=$wide_list=$g_list=$po_list=$xfd_list=[];
        $list_bm=$list_cell=$list_style=$list_article=$list_wide=$list_g=$list_po=$list_xfd='';
        $table = '';
        foreach ($dataBalance as $key => $a) {
            //DATABASE
                $bm_list[$a->buymonth]=$a->buymonth;
                $cell_list[$a->cell]=$a->cell;
                $style_list[$a->style]=$a->style;
                $article_list[$a->article]=$a->article;
                $wide_list[$a->wide]=$a->wide;
                $g_list[$a->g]=$a->g;
                $po_list[$a->po]=$a->po;
                $xfd_list[$a->xfd]=$a->xfd;
                //STOCKFIT
                    //input
                        if (array_key_exists($a->po.'-'.$a->wide.'-'.$a->qty,$dataInputStockfit['input'])) {
                            $input_stf = $dataInputStockfit['input'][$a->po.'-'.$a->wide.'-'.$a->qty]['qty'];
                            $color_input_stf = $this->getColor($input_stf,$a->qty);

                        }else{
                            $color_input_stf = '';
                            $color_wip = '';
                            $input_stf=0;
                        }
                    //output
                        if (array_key_exists($a->po.'-'.$a->wide.'-'.$a->qty,$dataInputStockfit['output'])) {
                            $output_stf = $dataInputStockfit['output'][$a->po.'-'.$a->wide.'-'.$a->qty]['qty'];
                            $color_output_stf = $this->getColor($output_stf,$a->qty);

                        }else{
                            $color_output_stf = '';
                            $output_stf=0;
                        }
                    //wip
                    if ($input_stf!=0) {
                        $wip = $input_stf-$output_stf;
                        $color_wip = $this->getColorwip($wip, $input_stf);
                    }else{
                        $color_wip = '';
                        $wip=0;
                    }
                    $cell = $a->cell;
                    if (strlen($a->cell)<5 && strlen($a->cell)!=2) {
                        $cell = substr($a->cell,0,3).'0'.substr($a->cell,3);
                    }
                    $data_table[]=[
                                        'buymonth'=>$a->buymonth,
                                        'cell'=>$cell,
                                        'style'=>$a->style,
                                        'article'=>$a->article,
                                        'wide'=>$a->wide,
                                        'g'=>$a->g,
                                        'po'=>$a->po,
                                        'xfd'=>$a->xfd,
                                        'qty'=>$a->qty,
                                        'input_stf'=>$input_stf,
                                        'output_stf'=>$output_stf,
                                        'wip'=>$input_stf-$output_stf,
                                        'color_wip'=>$color_wip,
                                        'color_input_stf'=>$color_input_stf,
                                        'color_output_stf'=>$color_output_stf,
                                    ];
        }
        foreach ($data_table as $k => $v) {
            $b[]=strtolower($v['cell']);
        }
        natsort($b);
        foreach ($b as $k => $v) {
            $c[]=$data_table[$k];
        }
        $cell_last = '';
        // set variable total qty
            $tot_qty=['qty_order'=>0,'input_stf'=>0,'output_stf'=>0,'wip'=>0,'balance_input'=>0,'balance_output'=>0];
        foreach ($c as $key => $value) {
            if ($cell_last != $value['cell'] && $cell_last!='') {
                $color_tot_input_stf = $this->getColor($tot_qty['input_stf'],$tot_qty['qty_order']);
                $color_tot_output_stf = $this->getColor($tot_qty['output_stf'],$tot_qty['qty_order']);
                $color_tot_wip_stf = $this->getColorwip($tot_qty['wip'],$tot_qty['input_stf']);
                $color_tot_balance_input = $this->getColor($tot_qty['input_stf'],$tot_qty['qty_order']);
                $color_tot_balance_output = $this->getColor($tot_qty['output_stf'],$tot_qty['qty_order']);
                $table .= '<tr>
                            <td class="bg-secondary" colspan="8">Total '.$cell_last.'</td>
                            <td class="bg-secondary">'.number_format($tot_qty['qty_order']).'</td>
                            <td style="'.$color_tot_input_stf.'">'.number_format($tot_qty['input_stf']).'</td>
                            <td style="'.$color_tot_output_stf.'">'.number_format($tot_qty['output_stf']).'</td>
                            <td style="'.$color_tot_wip_stf.'">'.number_format($tot_qty['wip']).'</td>
                            <td style="'.$color_tot_balance_input.'">'.number_format($tot_qty['balance_input']).'</td>
                            <td style="'.$color_tot_balance_output.'">'.number_format($tot_qty['balance_output']).'</td>
                        </tr>
                        <tr>
                            <td colspan="14" style="border:0"></td>
                        </tr>';
                $tot_qty['qty_order']=0;
                $tot_qty['input_stf']=0;
                $tot_qty['output_stf']=0;
                $tot_qty['wip']=0;
                $tot_qty['balance_input']=0;
                $tot_qty['balance_output']=0;
            }else{
                $balance_input = $value['qty']-$value['input_stf'];
                $balance_output = $value['qty']-$value['output_stf'];
                $color_balance_input = $this->getColorBalance($balance_input,$value['qty']);
                $color_balance_output = $this->getColorBalance($balance_output,$value['qty']);
                $table .= '<tr>
                                <td>'.$value['buymonth'].'</td>
                                <td>'.$value['cell'].'</td>
                                <td>'.$value['style'].'</td>
                                <td>'.$value['article'].'</td>
                                <td>'.$value['wide'].'</td>
                                <td>'.$value['g'].'</td>
                                <td>'.$value['po'].'</td>
                                <td>'.$value['xfd'].'</td>
                                <td>'.number_format($value['qty']).'</td>
                                <td style="'.$value['color_input_stf'].'">'.number_format($value['input_stf']).'</td>
                                <td style="'.$value['color_output_stf'].'">'.number_format($value['output_stf']).'</td>
                                <td style="'.$value['color_wip'].'">'.number_format($value['wip']).'</td>
                                <td style="'.$color_balance_input.'">'.number_format($balance_input).'</td>
                                <td style="'.$color_balance_output.'">'.number_format($balance_output).'</td>
                            </tr>';
                $tot_qty['qty_order']=$tot_qty['qty_order']+$value['qty'];
                $tot_qty['input_stf']=$tot_qty['input_stf']+$value['input_stf'];
                $tot_qty['output_stf']=$tot_qty['output_stf']+$value['output_stf'];
                $tot_qty['wip']=$tot_qty['wip']+$value['wip'];
                $tot_qty['balance_input']=$tot_qty['balance_input']+$balance_input;
                $tot_qty['balance_output']=$tot_qty['balance_output']+$balance_output;
            }
            $cell_last = $value['cell'];
        }
        //get List
                foreach ($bm_list as $key => $a) {
                    $list_bm.='<option>'.$a.'</option>';
                }
                foreach ($cell_list as $key => $a) {
                    $list_cell.='<option>'.$a.'</option>';
                }
                foreach ($style_list as $key => $a) {
                    $list_style.='<option>'.$a.'</option>';
                }
                foreach ($article_list as $key => $a) {
                    $list_article.='<option>'.$a.'</option>';
                }
                foreach ($wide_list as $key => $a) {
                    $list_wide.='<option>'.$a.'</option>';
                }
                foreach ($g_list as $key => $a) {
                    $list_g.='<option>'.$a.'</option>';
                }
                foreach ($po_list as $key => $a) {
                    $list_po.='<option>'.$a.'</option>';
                }
                foreach ($xfd_list as $key => $a) {
                    $list_xfd.='<option>'.$a.'</option>';
                }
        $data=array(
                'table'=>$table,
                'list_bm'=>$list_bm,
                'list_cell'=>$list_cell,
                'list_style'=>$list_style,
                'list_article'=>$list_article,
                'list_wide'=>$list_wide,
                'list_g'=>$list_g,
                'list_po'=>$list_po,
                'list_xfd'=>$list_xfd,
        );
        return json_encode($data);
    }
    public function dataStockfit($where)
    {
        $input = stf___input_line::select('*',db::raw('sum(qty) as sum_qty'))->groupby('po','wide','cell')->get();
        $shortage_input = stf___shortage_input::select('*',db::raw('sum(qty) as sum_qty'))->groupby('po','wide','cell')->get();
        $output = stf___output_line::select('*',db::raw('sum(qty) as sum_qty'))->groupby('po','wide','cell')->get();
        $shortage_output = stf___shortage_output::select('*',db::raw('sum(qty) as sum_qty'))->groupby('po','wide','cell')->get();
        if ($where) {
            if (array_key_exists('buymonth', $where)) {unset($where['buymonth']);}
            if (array_key_exists('style', $where)) {unset($where['style']);}
            if (array_key_exists('article', $where)) {unset($where['article']);}
            if (array_key_exists('g', $where)) {unset($where['g']);}
            if (array_key_exists('xfd', $where)) {unset($where['xfd']);}
            $input = stf___input_line::select('*',db::raw('sum(qty) as sum_qty'))->where($where)->groupby('po','wide','cell')->get();
            $shortage_input = stf___shortage_input::select('*',db::raw('sum(qty) as sum_qty'))->where($where)->groupby('po','wide','cell')->get();
            $output = stf___output_line::select('*',db::raw('sum(qty) as sum_qty'))->where($where)->groupby('po','wide','cell')->get();
            $shortage_output = stf___shortage_output::select('*',db::raw('sum(qty) as sum_qty'))->where($where)->groupby('po','wide','cell')->get();
        }
        //input
            $dataInput=[];
            foreach ($input as $key => $a) {
                $sum_qty = $a->sum_qty;
                foreach ($shortage_input as $key => $b) {
                    if ($a->po==$b->po && $a->wide == $b->wide) {
                        $sum_qty = $a->sum_qty+$b->sum_qty;
                    }
                }
                $dataInput[$a->po.'-'.$a->wide.'-'.$a->qty_order]=array(
                                        'po'=>$a->po,
                                        'wide'=>$a->wide,
                                        'cell'=>$a->cell,
                                        'qty_order'=>$a->qty_order,
                                        'qty'=>$sum_qty
                );
            }
        //output
            $dataOutput=[];
            foreach ($output as $key => $a) {
                $sum_qty = $a->sum_qty;
                foreach ($shortage_output as $key => $b) {
                    if ($a->po==$b->po && $a->wide == $b->wide) {
                        $sum_qty = $a->sum_qty+$b->sum_qty;
                    }
                }
                $dataOutput[$a->po.'-'.$a->wide.'-'.$a->qty_order]=array(
                                        'po'=>$a->po,
                                        'wide'=>$a->wide,
                                        'cell'=>$a->cell,
                                        'qty_order'=>$a->qty_order,
                                        'qty'=>$sum_qty
                );
            }
        $data=array('input'=>$dataInput,'output'=>$dataOutput);
        return $data;
    }
    public function getColor($val1, $val2)
    {
        if ($val1 == 0){
            $color = '';
        }else if ($val1==$val2) {
            $color = 'background-color: #2ab521; color:white';
        }else if($val1<$val2) {
            $color = 'background-color: #3480eb; color:white';
        }else{
            $color = 'background-color: #e83f3f; color:white';
        }
        return $color;
    }
    public function getColorBalance($val1, $val2)
    {
        if ($val1 == 0){
            $color = 'background-color: #2ab521; color:white';
        }else if ($val1==$val2) {
            $color = '';
        }else if($val1<$val2) {
            $color = 'background-color: #3480eb; color:white';
        }else{
            $color = 'background-color: #e83f3f; color:white';
        }
        return $color;
    }
    function getColorWip($wip, $input) {
        if ($wip == 0){
            $color = 'background-color: #2ab521; color:white';
        }else if ($wip==$input) {
            $color = 'background-color: #e83f3f; color:white';
        }else if($wip<$input) {
            $color = 'background-color: #3480eb; color:white';
        }else{
            $color = '';
        }
        return $color;
    }
    public function print_balance(Request $request)
    {
        //get where
            $where=[];
            if (isset($request->bm)) {
                $where['buymonth']=$request->bm;
            }
            if (isset($request->cell)) {
                $where['cell']=$request->cell;
            }
            if (isset($request->style)) {
                $where['style']=$request->style;
            }
            if (isset($request->article)) {
                $where['article']=$request->article;
            }
            if (isset($request->wide)) {
                $where['wide']=$request->wide;
            }
            if (isset($request->g)) {
                $where['g']=$request->g;
            }
            if (isset($request->po)) {
                $where['po']=$request->po;
            }
            if (isset($request->xfd)) {
                $where['xfd']=$request->xfd;
            }
            $dataBalance = balanceOrder::where($where)->groupby('po','wide','qty')->orderBy('cell')->orderBy('xfd')->get();
            $dataInputStockfit = $this->dataStockfit($where);
            $table = '';
            foreach ($dataBalance as $key => $a) {
                //DATABASE
                    $bm_list[$a->buymonth]=$a->buymonth;
                    $cell_list[$a->cell]=$a->cell;
                    $style_list[$a->style]=$a->style;
                    $article_list[$a->article]=$a->article;
                    $wide_list[$a->wide]=$a->wide;
                    $g_list[$a->g]=$a->g;
                    $po_list[$a->po]=$a->po;
                    $xfd_list[$a->xfd]=$a->xfd;
                    //STOCKFIT
                        //input
                            if (array_key_exists($a->po.'-'.$a->wide.'-'.$a->qty,$dataInputStockfit['input'])) {
                                $input_stf = $dataInputStockfit['input'][$a->po.'-'.$a->wide.'-'.$a->qty]['qty'];
                                $color_input_stf = $this->getColor($input_stf,$a->qty);

                            }else{
                                $color_input_stf = '';
                                $input_stf=0;
                            }
                        //output
                            if (array_key_exists($a->po.'-'.$a->wide.'-'.$a->qty,$dataInputStockfit['output'])) {
                                $output_stf = $dataInputStockfit['output'][$a->po.'-'.$a->wide.'-'.$a->qty]['qty'];
                                $color_output_stf = $this->getColor($output_stf,$a->qty);
                            }else{
                                $color_output_stf = '';
                                $output_stf=0;
                            }
                            $balance_output = $a->qty-$output_stf;
                            $color_balance_output = $this->getColor($output_stf,$a->qty);
                            //wip
                        if ($input_stf!=0) {
                            $wip = $input_stf-$output_stf;
                            $color_wip = $this->getColorwip($wip, $input_stf);
                        }else{
                            $color_wip = '';
                            $wip=0;
                        }
                        $balance_input = $a->qty-$input_stf;
                        $color_balance_input = $this->getColor($output_stf,$a->qty);

                        $cell = $a->cell;
                        if (strlen($a->cell)<5 && strlen($a->cell)!=2) {
                            $cell = substr($a->cell,0,3).'0'.substr($a->cell,3);
                        }
                        $data_table[]=[
                                            'buymonth'=>$a->buymonth,
                                            'cell'=>$cell,
                                            'style'=>$a->style,
                                            'article'=>$a->article,
                                            'wide'=>$a->wide,
                                            'g'=>$a->g,
                                            'po'=>$a->po,
                                            'xfd'=>$a->xfd,
                                            'qty'=>$a->qty,
                                            'input_stf'=>$input_stf,
                                            'output_stf'=>$output_stf,
                                            'wip'=>$input_stf-$output_stf,
                                            'balance_input'=>$balance_input,
                                            'balance_output'=>$balance_output,
                                            'color_wip'=>$color_wip,
                                            'color_input_stf'=>$color_input_stf,
                                            'color_output_stf'=>$color_output_stf,
                                            'color_balance_input'=>$color_balance_input,
                                            'color_balance_output'=>$color_balance_output,
                                        ];
            }
            foreach ($data_table as $k => $v) {
                $b[]=strtolower($v['cell']);
            }
            natsort($b);
            foreach ($b as $k => $v) {
                $c[]=$data_table[$k];
            }
            $data=array(
                'c'=>$c,
            );
            if (array_key_exists('buymonth',$where)) {
                return (new balanceStockfit($data))->download('DC-BALANCE-'.$where['buymonth'].'.xlsx');
            }else{
                return (new balanceStockfit($data))->download('DC-BALANCE.xlsx');
            }
    }

}
