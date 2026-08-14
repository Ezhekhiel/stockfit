<?php

namespace App\Http\Controllers;

use App\Events\DashboardUpdated;
use App\Exports\potlifeReport;
use App\Jobs\SendTelegramReminderJob;
use App\Models\chemical___barcode;
use App\Models\chemical___database;
use App\Models\chemical___move;
use App\Models\database___chanel_telegram;
use App\Services\TelegramService;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Telegram;

class labChemicalController extends Controller
{
    protected $ipAddess;

    public function __construct()
    {
        $this->ipAddess =  \Request::ip();
    }
    public function index() {
        Cache::forget('chemical___database');
        Cache::forget('chemical___moves');
        return view('lab.chemical');
    }
    public function export(Request $request) {
        $month = $request->month;
        $expl = explode('-',$month);
        $month = $expl[1];
        $year = $expl[0];
        return (new potlifeReport($month, $year))->download($month.'-'.$year.'-MixingRoom.xlsx');
    }
    function getLine() {
        return $getLine = Cache::remember('list_line', now()->addMinutes(600), function () {
            return DB::connection('mysql_2')->table('cell_targets')
                ->select('cell')
                ->where('cell', 'like', 'Line-%')
                ->get();
        });
    }
    public function getMove($search, $option) {
        // 2. Query Utama (Gunakan penulisan DB yang konsisten)
        $getMoveQuery = DB::table('chemical___moves as a')
            ->select('a.*', 'a.created_at as time_mixing', 'b.code_chemical', 'b.model', 'b.supplier', 'b.component', 'b.type', 'b.adhesive_kind')
            ->leftJoin('chemical___databases as b', function($join) {
                $join->on('a.code_chemical', '=', 'b.code_chemical')
                    ->on('a.model', '=', 'b.model');
            })
            ->where('a.status', 'Not Yet')
            ->orderByRaw("CASE WHEN minutes = '-' THEN 1 ELSE 0 END ASC")
            ->orderBy('a.created_at', 'asc');

        if (!empty($search)) {
            $getMoveQuery->where('a.id_barcode', $search);
        }

        if ($option == "" || $option == "Stockfit") {
            $getMoveQuery->where('a.line', 'like', '%Line%');
        } else {
            $getMoveQuery->where(function ($query) {
                $query->where('a.line', 'like', '%B1%')
                    ->orWhere('a.line', 'like', '%B2%');
            });
        }

        return $getMoveQuery->get();
    }
    public function main(Request $request) {
        $role = Auth::user() ? Auth::user()->role_id : 0;
        $option = $request->option;
        $search = $request->search;

        $getLine =  $this->getLine();

        $getMove = $this->getMove($search, $option);

        $getDatabase = Cache::remember('chemical___databases', now()->addMinutes(60), function () {
            return DB::table('chemical___databases')->get();
        });
        return response()->json([
            'lines' => $getLine,
            'moves' => $getMove,
            'databases' => $getDatabase,
            'canEdit'    => Auth::check()
        ]);
    }
    public function update(Request $request) {
        $line = $request->line;
        $code_chemical = $request->code_chemical;
        return $line;
        if ($line == '') {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> 'You havent selected a data line!',
                'color'=>'danger'
            );
            return json_encode($data);
        }
        try{

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
            'text'=> 'Update Data Successfull!',
            'color'=>'success'
        );

        Cache::forget('chemical___database');
        Cache::forget('chemical___moves');
        return json_encode($data);
    }
    public function mixing(Request $request) {
        $id_barcode = $request->id_barcode;
        $line = $request->line;
        $minutes = (int)$request->minutes;
        $code_chemical = $request->code_chemical;
        $option = $request->option;
        $expl_code_chemical = explode('|',$code_chemical);
        $code_chemical = $expl_code_chemical[0];
        $model = $expl_code_chemical[1];
        $code_chemical_act = $request->code_chemical_act;
        $expiredAt = now()->addMinutes($minutes);
        $reminderAt = $expiredAt->copy()->subMinutes(20);

        if ($code_chemical_act=='') {
            $code_chemical_act='-';
        }
        $gram = $request->gram;
        $lot_number = $request->lot_number;
        if ($id_barcode == '' || $line == '' || $code_chemical == '') {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> 'Data must be filled in completely!',
                'color'=>'danger'
            );
            return json_encode($data);
        }
        try{
            $chemical = chemical___move::create([
                'code_chemical'=>$code_chemical,
                'model'=>$model,
                'code_chemical_act'=>$code_chemical_act,
                'id_barcode'=>$id_barcode,
                'option'=>$option,
                'line'=>$line,
                'minutes'=>$minutes,
                'gram'=>$gram,
                'status'=>'Not Yet',
                'lot_number'=>$lot_number,
                'expired_at' => $expiredAt,
                'reminder_at' => $reminderAt,
            ]);
            Cache::forget('chemical___database');
            Cache::forget('chemical___moves');
            //ini test
                $delay = app()->environment('local') ? now()->addSeconds(1) : $chemical->reminder_at;
                SendTelegramReminderJob::dispatch($chemical->id)
                                        ->delay($delay);
            //ini asli
            // SendTelegramReminderJob::dispatch($chemical->id)
            //     ->delay($chemical->reminder_at);
            broadcast(new DashboardUpdated());
        }catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
        return response()->json([
            'alert' => 'Sukses!',
            'text' => 'Update Data Successful!',
            'color' => 'success'
        ]);
    }

    public function update_status(Request $request, TelegramService $telegramService) {
        $id = $request->id;
        $last_message_id = chemical___move::where('id',$id)->first();
        try{
            $data = chemical___move::findOrFail($id);
            $text = $data->line;
            if (str_contains($text, 'LINE')) {
                $area = 'POTLIFE STOCKFIT PWI2';
            } elseif (str_contains($text, 'B1')) {
                $area = 'POTLIFE B1 PWI2';
            } elseif (str_contains($text, 'B2')) {
                $area = 'POTLIFE B2 PWI2';
            } else {
                $area = null; // atau default lainnya
            }
            $id_chanel = Cache::remember(
                'telegram_channel_'.$area,
                now()->addDays(30),
                function () use ($area) {
                    return database___chanel_telegram::where('system', $area)->first();
                }
            );


            $data->update([
                'status' => 'Done',
            ]);
            $data->refresh();

            Cache::forget('chemical___database');
            Cache::forget('chemical___moves');
            broadcast(new DashboardUpdated());
        }catch (\Exception $e) {
            $data = array(
                'alert'=>'Gagal!',
                'text'=> $e->getMessage(),
                'color'=>'danger'
            );
            return json_encode($data);
        }
        if ($data->message_id && $id_chanel) {
            try {
                $telegramService->deleteMessage(
                    $id_chanel->id_chanel,
                    $data->message_id
                );
            } catch (\Exception $e) {
                // Tidak melakukan apa-apa, biarkan proses tetap sukses
                // Log::warning($e->getMessage());
            }
        }
        $data = array(
            'alert'=>'Sukses!',
            'text'=> 'Update Data Successfull!',
            'color'=>'success'
        );


        return json_encode($data);
    }

    function search_model(Request $request) {
        if (Auth::user()) {
            $role = Auth::user()->role_id;
        }else{
            $role = 0;
        }
        $get = db::table('chemical___moves as a')
            ->select('a.*','a.created_at as time_mixing','b.code_chemical','b.model','b.supplier','b.component','b.type')
            ->leftJoin('chemical___databases as b','a.code_chemical','=','b.code_chemical')
            ->where('a.status','Not Yet');
            if ($request->model != '') {
                $get = $get->where('b.model','like','%'.$request->model.'%')->get();
            }else{
                $get = $get->get();
            }
        if (count($get) == 0) {
            if ($role != 1) {
                $colspan_table = '12';
            }else{
                $colspan_table = '13';
            }
            $table = '
                <tr>
                    <td colspan="'.$colspan_table.'">Data Not Found</td>
                </tr>
            ';
            $data = array('table'=>$table);
            return json_encode($data);
        }
        $table='';
        $no = 0;
        $arrDatabase = [];
        $cek = [];
        foreach ($get as $key => $a) {
            $no++;
            $arrDatabase[$a->code_chemical]=$a;
            if ($role != 1) {
                $button_action = '';
            }else{
                if ($a->id_barcode != '-') {
                    $button_action = '
                                <td>
                                    <button type="button" class="btn btn-primary" onclick="updateMain(\''.$a->code_chemical.'\')">Change</button>
                                </td>
                            ';
                }else{
                    $button_action = '<td>Notyet Print Barcode</td>';
                }
            }
            $convert = strtotime($a->time_mixing);
            if ($a->time_mixing) {
                $time_mixing = date('Y-m-d H:i', $convert);
                $datenow = strtotime(date('Y-m-d H:i'));

                $expire_on = date('Y-m-d H:i', $convert + 60*60);
                $convert_expire_on = strtotime($expire_on);
                if ($datenow >= $convert_expire_on) {
                    $status = 'bg-danger';
                }else{
                    $status = '';
                }
            }else{
                $time_mixing = '';
                $expire_on = '';
                $status = '';
            }
            if (!$a->line) {
                $classNew = ' class-new';
            }else{
                $classNew = '';
            }
            $id_barcode = $a->id_barcode;
            if ($a->id_barcode == '-') {
                $id_barcode = 'notYet-'.$a->code_chemical;
            }
            $table.='
                <tr class="'.$status.'">
                    <td><input type="checkbox" class="form-check-input'.$classNew.'" name="id_barcode[]" value="'.$id_barcode.'"></td>
                    <td>'.$no.'</td>
                    <td>'.$a->id_barcode.'</td>
                    <td>'.$a->line.'</td>
                    <td>'.$a->model.'</td>
                    <td>'.$a->supplier.'</td>
                    <td>'.$a->component.'</td>
                    <td>'.$a->type.'</td>
                    <td>'.$a->code_chemical.'</td>
                    <td>'.$a->gram.'g</td>
                    <td>'.$time_mixing.'</td>
                    <td>'.$expire_on.'</td>
                    '.$button_action.'
                </tr>
            ';
        }
        $data=array('table'=>$table);
        return json_encode($data);
    }
    function search_id_barcode(Request $request) {
        if (Auth::user()) {
            $role = Auth::user()->role_id;
        }else{
            $role = 0;
        }
        $get = db::table('chemical___moves as a')
            ->select('a.*','a.created_at as time_mixing','b.code_chemical','b.model','b.supplier','b.component','b.type')
            ->leftJoin('chemical___databases as b','a.code_chemical','=','b.code_chemical')
            ->where('a.status','Not Yet');
            if ($request->id_barcode != '') {
                $get = $get->where('a.id_barcode','like','%'.$request->id_barcode.'%')->get();
            }else{
                $get = $get->get();
            }
        if (count($get) == 0) {
            if ($role != 1) {
                $colspan_table = '12';
            }else{
                $colspan_table = '13';
            }
            $table = '
                <tr>
                    <td colspan="'.$colspan_table.'">Data Not Found</td>
                </tr>
            ';
            $data = array('table'=>$table);
            return json_encode($data);
        }
        $table='';
        $no = 0;
        $arrDatabase = [];
        $cek = [];
        foreach ($get as $key => $a) {
            $no++;
            $arrDatabase[$a->code_chemical]=$a;
            if ($role != 1) {
                $button_action = '';
            }else{
                if ($a->id_barcode != '-') {
                    $button_action = '
                                <td>
                                    <button type="button" class="btn btn-primary" onclick="updateMain(\''.$a->code_chemical.'\')">Change</button>
                                </td>
                            ';
                }else{
                    $button_action = '<td>Notyet Print Barcode</td>';
                }
            }
            $convert = strtotime($a->time_mixing);
            if ($a->time_mixing) {
                $time_mixing = date('Y-m-d H:i', $convert);
                $datenow = strtotime(date('Y-m-d H:i'));

                $expire_on = date('Y-m-d H:i', $convert + 60*60);
                $convert_expire_on = strtotime($expire_on);
                if ($datenow >= $convert_expire_on) {
                    $status = 'bg-danger';
                }else{
                    $status = '';
                }
            }else{
                $time_mixing = '';
                $expire_on = '';
                $status = '';
            }
            if (!$a->line) {
                $classNew = ' class-new';
            }else{
                $classNew = '';
            }
            $id_barcode = $a->id_barcode;
            if ($a->id_barcode == '-') {
                $id_barcode = 'notYet-'.$a->code_chemical;
            }
            $table.='
                <tr class="'.$status.'">
                    <td><input type="checkbox" class="form-check-input'.$classNew.'" name="id_barcode[]" value="'.$id_barcode.'"></td>
                    <td>'.$no.'</td>
                    <td>'.$a->id_barcode.'</td>
                    <td>'.$a->line.'</td>
                    <td>'.$a->model.'</td>
                    <td>'.$a->supplier.'</td>
                    <td>'.$a->component.'</td>
                    <td>'.$a->type.'</td>
                    <td>'.$a->code_chemical.'</td>
                    <td>'.$a->gram.'g</td>
                    <td>'.$time_mixing.'</td>
                    <td>'.$expire_on.'</td>
                    '.$button_action.'
                </tr>
            ';
        }
        $data=array('table'=>$table);
        return json_encode($data);
    }
    function search_model_mixing(Request $request) {
        $getDatabase = db::table('chemical___databases as a')->where('model','like','%'.$request->model.'%')->get();
        $tbody_database = '';
        if (count($getDatabase)!=0) {
            foreach ($getDatabase as $key => $a) {
                $tbody_database.='
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="code_chemical" value="'.$a->code_chemical.'|'.$a->model.'">
                            </div>
                        </td>
                        <td>'.$a->code_chemical.'</td>
                        <td>'.$a->model.'</td>
                        <td>'.$a->supplier.'</td>
                        <td>'.$a->component.'</td>
                        <td>'.$a->type.'</td>
                    </tr>
                ';
            }
        }else{
            $tbody_database.='<tr>
                                <td colspan="6">Data Not Found!</td>
                            </tr>';
        }
        $data = array('tbody_database'=>$tbody_database);
        return json_encode($data);
    }
    function search_model_mixing_act(Request $request) {
        $getDatabase = db::table('chemical___databases as a')->where('model','like','%'.$request->model.'%')->get();
        $tbody_database = '';
        if (count($getDatabase)!=0) {
            foreach ($getDatabase as $key => $a) {
                $tbody_database.='
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="code_chemical_act" value="'.$a->code_chemical.'">
                            </div>
                        </td>
                        <td>'.$a->code_chemical.'</td>
                        <td>'.$a->model.'</td>
                        <td>'.$a->supplier.'</td>
                        <td>'.$a->component.'</td>
                        <td>'.$a->type.'</td>
                    </tr>
                ';
            }
        }else{
            $tbody_database.='<tr>
                                <td colspan="6">Data Not Found!</td>
                            </tr>';
        }
        $data = array('tbody_database'=>$tbody_database);
        return json_encode($data);
    }

    public function scan_id_barcode($id_barcode) {
        $getLine =  DB::CONNECTION('mysql_2')->table('cell_targets')->select('cell')->where('cell','like','Line-%')->get();

        $getMove = db::table('chemical___moves as a')
                ->select('a.*','a.created_at as time_mixing','b.code_chemical','b.model','b.supplier','b.component','b.type')
                ->leftJoin('chemical___databases as b',function($join)
                {
                  $join->on('a.code_chemical', '=', 'b.code_chemical');
                  $join->on('a.model', '=', 'b.model');

                })
                ->where('a.id_barcode',$id_barcode)
                ->where('a.status','Not Yet')
                ->orderBy('a.created_at','desc')
                ->get()->toArray();
        $keys = array_search($id_barcode,array_column($getMove, 'id_barcode'));
        $select_option = '<option value="">Select Option</option>';
        foreach ($getLine as $key => $a) {
            $select_option.='
                <option value="'.$a->cell.'">'.$a->cell.'</option>
            ';
        }
        if (count($getMove) == 0) {
            return view('lab.scan_chemical',['data'=>[],
                                            'expire_on'=>'',
                                            'select_option'=>$select_option]);
        }else{
            if ($getMove[$keys]->time_mixing) {
                 if ($getMove[$keys]->minutes == '-') {
                    $durasiExpire=0;
                }else{
                    $durasiExpire=$getMove[$keys]->minutes;
                }
                $convert = strtotime($getMove[$keys]->time_mixing);
                $time_mixing = date('Y-m-d H:i', $convert);
                $datenow = strtotime(date('Y-m-d H:i'));

                $expire_on = date('Y-m-d H:i', (int) $convert + ((int)$durasiExpire*60));
                $convert_expire_on = strtotime($expire_on);
                if ($datenow >= $convert_expire_on) {
                    $status = 'bg-danger';
                }else{
                    $status = '';
                }
            }
            return view('lab.scan_chemical',['data'=>$getMove[$keys],
                                            'expire_on'=>$expire_on,
                                            'select_option'=>$select_option]);
        }
    }
    public function test_connection_telegram() {
        $response = Telegram::getUpdates();
        $arr_channel=[];
        foreach ($response as $key => $a) {
            if (array_key_exists("channel_post",$a->toArray())) {
                $data= $a->toArray();
                if ($data['channel_post']['text'] == 'daftar_chanel') {
                    $arr_channel[]=[
                                    'id'=>$data['channel_post']['sender_chat']['id'],
                                    'title'=>$data['channel_post']['sender_chat']['title']
                                ];
                }
            }
        }
        if (empty($arr_channel)) {
            return 'Tidak ada yang mengetik daftar chanel sampai saat ini';
        }
        foreach ($arr_channel as $key => $a) {
            $data = ['id_chanel'=>$a['id']];
            $values = ['system'=>$a['title']];
            database___chanel_telegram::firstOrCreate($data,$values);
        }


    }
    public function tesChanel() {
        $response = Telegram::getUpdates();
        dd($response);
    }
    public function print_barcode(Request $request) {
        $total = $request->total;
        $qty = $request->qty;
        $cek = chemical___barcode::get();

        if ($cek) {
            $start = count($cek)+1;
        }else{
            $start = 1;
        }
        $no = $halaman = 0;
        for ($i=$start; $i < $start+$total; $i++) {
            chemical___barcode::create(['id_barcode'=>'Chemical_'.$i]) ;
            if ($no == 40) {
                $no=0;
                $halaman++;
            }
            for ($y=0; $y < $qty; $y++) {
                $arr_hal[$halaman][]='Chemical_'.$i;
            }
            $no++;
        }
        return view('lab.print.barcode')->with(['data_hal'=>$arr_hal]);
    }
    function custome_print_barcode(Request $request) {
        $total = $request->total_custome;
        $qty = $request->qty;
        $cek = chemical___barcode::orderBy('id','desc')->first();
        $arr_hal =  explode('-',$total);
        $exp_cek = explode("_",$cek->id_barcode);
        $no = $halaman = 0;
        $arr_ = [];
        for ($i=0; $i < count($arr_hal); $i++) {
            if ($no == 40) {
                $no=0;
                $halaman++;
            }
                if (max($arr_hal) > $exp_cek[1]) {
                    return Redirect::back()->withErrors(['msg' => 'Unregistered Barcode ID, register first!']);
                }
            for ($y=0; $y < $qty; $y++) {
                $arr_[$halaman][]='Chemical_'.$arr_hal[$i];
            }
            $no++;
        }
        return view('lab.print.barcode')->with(['data_hal'=>$arr_]);
    }

    public function deleteDatabase(Request $request) {
        $id = $request->id;
        try {
            chemical___database::where('id',$id)->delete();
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
            'text'=> 'Update Data Successfull!',
            'color'=>'success'
        );
        Cache::forget('chemical___database');
        Cache::forget('chemical___moves');
        return json_encode($data);
    }
    //DATABASE
        public function index_database(){
            // put cache
            Cache::forget('chemical___database_arr'.$this->ipAddess);
            Cache::forget('chemical___database');
            return view('lab.database_chemical');
        }
        public function get_data_delete(Request $request) {
            $model = $request->model;
            $adhesive_supplier = $request->a_supplier;
            $data = chemical___database::where(['model'=>$model,'supplier'=>$adhesive_supplier])->get();

            $table = '';
            $no = 0;
            foreach ($data as $key => $a) {
                $no++;
                $table.='
                    <tr>
                        <th scope="row">'.$no.'</th>
                        <td>'.$a->component.'</td>
                        <td>'.$a->type.'</td>
                        <td>'.$a->code_chemical.'</td>
                        <td><span class="badge bg-danger text-dark" onclick="deleteData('.$a->id.')">Delete</span></td>
                    </tr>
                ';
            }
            $data = array('table'=>$table);
            return $data;
        }
        public function main_database(Request $request) {
            if (Auth::user()) {
                $role = Auth::user()->role_id;
            }else{
                $role = 0;
            }
            $model = $request->model;
            if ($model == '') {
                $getDatabase = chemical___database::get();
            }else{
                $getDatabase = chemical___database::where('model','like','%'.$model.'%')->get();
            }
            $arr = [];
            foreach ($getDatabase as $key => $a) {
                $arr[$a->model.'|'.$a->supplier][$a->component][$a->type]['code_chemical']=$a->code_chemical;
                $arr[$a->model.'|'.$a->supplier][$a->component][$a->type]['kind']=$a->adhesive_kind;
                $arr[$a->model.'|'.$a->supplier][$a->component]['id']=$a->id;
            }
            $tbody = '';
            $no = 0;
            foreach ($arr as $key => $a) {
                $no ++;
                $tdData = '';
                //action
                    if (!$role) {
                        $button_action = '';
                    }else{
                        $button_action = '
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick="updateDatabase(\''.$key.'\')">Change</button>
                                    </td>
                                ';
                    }
                //rubber
                    if (!array_key_exists('RUBBER',$a)) {
                        $tdData.='
                                    <td>-</td>
                                    <td>-</td>
                                ';
                    }else{
                        if (array_key_exists('PRIMER',$a['RUBBER'])) {
                            $tdData.='
                                <td>'.$a['RUBBER']['PRIMER']['code_chemical'].' ('.$a['RUBBER']['PRIMER']['kind'].')</td>
                            ';
                        }else{
                            $tdData.='<td>-</td>';
                        }
                        if (array_key_exists('CEMENT',$a['RUBBER'])) {
                            $tdData.='
                                <td>'.$a['RUBBER']['CEMENT']['code_chemical'].' ('.$a['RUBBER']['CEMENT']['kind'].')</td>
                            ';
                        }else{
                            $tdData.='<td>-</td>';
                        }
                    }
                //midsole
                    if (!array_key_exists('MIDSOLE (EVA/PHYLON)',$a)) {
                        $tdData.='
                                    <td>-</td>
                                    <td>-</td>
                                ';
                    }else{
                        if (array_key_exists('PRIMER',$a['MIDSOLE (EVA/PHYLON)'])) {
                            $tdData.='
                                <td>'.$a['MIDSOLE (EVA/PHYLON)']['PRIMER']['code_chemical'].' ('.$a['MIDSOLE (EVA/PHYLON)']['PRIMER']['kind'].')</td>
                            ';
                        }else{
                            $tdData.='<td>-</td>';
                        }
                        if (array_key_exists('CEMENT',$a['MIDSOLE (EVA/PHYLON)'])) {
                            $tdData.='
                                <td>'.$a['MIDSOLE (EVA/PHYLON)']['CEMENT']['code_chemical'].' ('.$a['MIDSOLE (EVA/PHYLON)']['CEMENT']['kind'].')</td>
                            ';
                        }else{
                            $tdData.='<td>-</td>';
                        }
                    }
                //-

                $keys = explode('|',$key);
                $model = $keys[0];
                $supplier = $keys[1];
                $tbody .= '
                    <tr>
                        <td>'.$no.'</td>
                        <td>'.$model.'</td>
                        <td>'.$supplier.'</td>
                        '.$tdData.'
                        '.$button_action.'
                    </tr>
                ';
            }
            $data = array(
                'tbody'=>$tbody
            );
            // put cache
                Cache::put('chemical___database_arr'.$this->ipAddess, $arr);
            return json_encode($data);
        }
        public function getDataByArr(Request $request) {
            $data = $request->data;
            $explode_data = explode('|',$data);
            $getArrCache = Cache::get('chemical___database_arr'.$this->ipAddess);
            $div = '';
            $baris = 0;
            foreach ($getArrCache[$data] as $key => $a) {
                if ($baris > 0) {
                    $div.='<div class="mt-4"></div>';
                }
                if (array_key_exists('PRIMER',$a)) {
                    $primer = $a['PRIMER']['code_chemical'];
                    $primer_kind = $a['PRIMER']['kind'];
                }else{
                    $primer = '-';
                    $primer_kind = '-';
                }
                if (array_key_exists('CEMENT',$a)) {
                    $cement = $a['CEMENT']['code_chemical'];
                    $cement_kind = $a['CEMENT']['kind'];
                }else{
                    $cement = '-';
                    $cement_kind = '-';
                }
                $keys = explode('|',$key);
                $div .= '
                    <div class="card">
                        <div class="col-12 text-center h4 font-italic font-weight-bold mt-2">
                            '.$key.'
                        </div>
                        <div class="row">
                            <!-- Bagian Primer & Cement -->
                            <div class="col-md-6">
                                <div class="card m-1">
                                    <div class="row align-items-center">
                                        <!-- Radio -->
                                        <div class="col-1 text-center">
                                            <input type="radio"
                                                id="radio-'.$key.'"
                                                onchange="changeRadioModal('.$a['id'].')"
                                                name="radio-code"
                                                value="'.$a['id'].'">
                                        </div>

                                        <!-- Last Code -->
                                        <div class="col-4 text-center">
                                            <div class="form-group">
                                                <label class="text-info">LAST CODE PRIMER</label>
                                                <input type="text" disabled
                                                    class="form-control text-center last_code"
                                                    value="'.$primer.'"
                                                    id="last_code_primer_'.$a['id'].'">
                                            </div>
                                            <div class="form-group">
                                                <label class="text-success">LAST CODE CEMENT</label>
                                                <input type="text" disabled
                                                    class="form-control text-center last_code"
                                                    value="'.$cement.'"
                                                    id="last_code_cement_'.$a['id'].'">
                                            </div>
                                        </div>

                                        <!-- Panah -->
                                        <div class="col-2 text-center">
                                            <i class="fa fa-arrow-right" style="font-size:200%"></i>
                                        </div>

                                        <!-- Update Code -->
                                        <div class="col-4 text-center">
                                            <div class="form-group">
                                                <label class="text-info">UPDATE CODE PRIMER</label>
                                                <input type="text" disabled
                                                    class="form-control text-center class_code"
                                                    id="update_code_primer_'.$a['id'].'">
                                            </div>
                                            <div class="form-group">
                                                <label class="text-success">UPDATE CODE CEMENT</label>
                                                <input type="text" disabled
                                                    class="form-control text-center class_code"
                                                    id="update_code_cement_'.$a['id'].'">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian Adhesive -->
                            <div class="col-md-6">
                                <div class="card m-1">
                                    <div class="row align-items-center justify-content-center">
                                        <!-- Last Adhesive -->
                                        <div class="col-4 text-center">
                                            <div class="form-group">
                                                <label class="text-info">LAST ADHESIVE KIND PRIMER</label>
                                                <input type="text" disabled
                                                    class="form-control text-center"
                                                    value="'.$primer_kind.'"
                                                    id="last_code_primer_'.$a['id'].'">
                                            </div>
                                            <div class="form-group">
                                                <label class="text-success">LAST ADHESIVE KIND CEMENT</label>
                                                <input type="text" disabled
                                                    class="form-control text-center"
                                                    value="'.$cement_kind.'"
                                                    id="last_code_cement_'.$a['id'].'">
                                            </div>
                                        </div>

                                        <!-- Panah -->
                                        <div class="col-2 text-center">
                                            <i class="fa fa-arrow-right" style="font-size:200%"></i>
                                        </div>

                                        <!-- Update Adhesive -->
                                        <div class="col-4 text-center">
                                            <div class="form-group">
                                                <label class="text-info">UPDATE ADHESIVE PRIMER</label>
                                                <select id="update_adhesive_primer_'.$a['id'].'" disabled="disabled" class="form-control class_adhesive">
                                                    <option value="-">-</option>
                                                    <option value="Water Base">Water Base</option>
                                                    <option value="Solvent Base">Solvent Base</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-success">UPDATE ADHESIVE CEMENT</label>
                                                <select id="update_adhesive_cement_'.$a['id'].'" disabled="disabled" class="form-control class_adhesivel">
                                                    <option value="-">-</option>
                                                    <option value="Water Base">Water Base</option>
                                                    <option value="Solvent Base">Solvent Base</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
                $baris ++;
            }
            $data = array('div'=>$div);
            return json_encode($data);
        }
        public function save_database(Request $request) {
            $code = $request->code;
            $model = $request->model;
            $supplier = $request->supplier;
            $component = $request->component;
            $adhesive_kind = $request->adhesive_kind;
            $type = $request->type;

            if ($code == '' && $model == '' && $supplier == '' && $component == '' && $type == '') {
                $data = array(
                    'alert'=>'Gagal!',
                    'text'=> 'Data must be filled in completely!',
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            // //chek if model supplier, component and type same
                // $getDatabase = Cache::remember('chemical___database', now()->addMinutes(60), function () {
                //     $get = chemical___database::get();
                //     return $get;
                // })->toArray();
            //     $arrayfilter = [
            //         'code_chemical'=>$code,
            //         'model'=>$model,
            //         'supplier'=>$supplier,
            //         'component'=>$component,
            //         'type'=>$type
            //     ];
            //     $cek = array_filter($getDatabase, function ($data) use ($arrayfilter) {
            //         return count(array_intersect_assoc($arrayfilter, $data)) == count($arrayfilter);
            //     });
            //     if ($cek) {
            //         $data = array(
            //             'alert'=>'Gagal!',
            //             'text'=> 'Data Item and Model Already Exist!',
            //             'color'=>'danger'
            //         );
            //         return json_encode($data);
            //     }
            // ----checkf
            // $cek = array_filter($getDatabase, function ($data) use ($arrayfilter) {
            //     return count(array_intersect_assoc($arrayfilter, $data)) == count($arrayfilter);
            // });
            try{
                chemical___database::create(['code_chemical'=>$code,'model'=>$model,'supplier'=>$supplier,'component'=>$component,'type'=>$type,'adhesive_kind'=>$adhesive_kind]);
            }catch (\Exception $e) {
                $data = array(
                    'alert'=>'Gagal!',
                    'text'=> $e->getMessage(),
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            Cache::forget('chemical___database');
            $data = array(
                'alert'=>'Sukses!',
                'text'=> 'Save Data Successfull!',
                'color'=>'success'
            );
            Cache::forget('chemical___database');
            return json_encode($data);
        }
        public function update_database(Request $request) {
            $id = $request->id;
            $model_update = $request->model_update;
            $arr_model = explode('|',$model_update);
            $model = $arr_model[0];
            $supplier = $arr_model[1];

            $code_primer = $request->code_primer;
            $code_cement = $request->code_cement;

            $last_code_primer = $request->last_code_primer;
            $last_code_cement = $request->last_code_cement;

            $adhesive_kind_primer = $request->adhesive_kind_primer;
            $adhesive_kind_cement = $request->adhesive_kind_cement;

            if ($code_primer == '' && $code_cement == ''&& $adhesive_kind_primer == '-' && $adhesive_kind_cement == '-') {
                $data = array(
                    'alert'=>'Gagal!',
                    'text'=> 'Nothing to update!',
                    'color'=>'danger'
                );
                return json_encode($data);
            }
            $getDetail = chemical___database::where('id',$id)->first();
            if ($code_primer != '' || $code_cement != ''){
                //cek update
                    if ($code_primer != '') {
                        $where[]['type'] = 'PRIMER';
                        $update[]['code_chemical']=$code_primer;
                        $cek = chemical___database::where(['code_chemical'=>$code_primer,'model'=>$model,'supplier'=>$supplier])->where('type','PRIMER')->first();
                        //cek code available or not
                        if ($cek) {
                            $data = array(
                                'alert'=>'Gagal!',
                                'text'=> 'Code already registered!',
                                'color'=>'danger'
                            );
                            return json_encode($data);
                        }
                        $last[]=$last_code_primer;
                    }
                    if ($code_cement != '') {
                        $where[]['type'] = 'CEMENT';
                        $update[]['code_chemical']=$code_cement;
                        $cek = chemical___database::where(['code_chemical'=>$code_cement,'model'=>$model,'supplier'=>$supplier])->where('type','CEMENT')->first();
                        //cek code available or not
                        if ($cek) {
                            $data = array(
                                'alert'=>'Gagal!',
                                'text'=> 'Code already registered!',
                                'color'=>'danger'
                            );
                            return json_encode($data);
                        }
                        $last[]=$last_code_cement;
                    }
                foreach ($update as $key => $a) {
                    $where[$key]['model']=$getDetail->model;
                    $where[$key]['supplier']=$getDetail->supplier;
                    $where[$key]['component']=$getDetail->component;
                }
                try{
                    foreach ($update as $key => $a) {
                        if ($last[$key] == '-') {
                            chemical___database::create([
                                'model'=>$where[$key]['model'],
                                'supplier'=>$where[$key]['supplier'],
                                'component'=>$where[$key]['component'],
                                'type'=>$where[$key]['type'],
                                'code_chemical'=>$a['code_chemical'],
                            ]);
                        }else{
                            chemical___database::where($where[$key])->update($update[$key]);
                        }
                    }
                }catch (\Exception $e) {
                    $data = array(
                        'alert'=>'Gagal!',
                        'text'=> $e->getMessage(),
                        'color'=>'danger'
                    );
                    return json_encode($data);
                }
            }
            if ($adhesive_kind_primer != '-') {
                    try{
                        chemical___database::where(
                                    ['model'=>$getDetail->model,
                                    'supplier'=>$getDetail->supplier,
                                    'component'=>$getDetail->component,
                                    'type'=>'PRIMER'])
                                    ->update(['adhesive_kind'=>$adhesive_kind_primer]);
                    }catch (\Exception $e) {
                        $data = array(
                            'alert'=>'Gagal!',
                            'text'=> $e->getMessage(),
                            'color'=>'danger'
                        );
                        return json_encode($data);
                    }
            }
            if ($adhesive_kind_cement != '-') {
                    try{
                        chemical___database::where(
                                    ['model'=>$getDetail->model,
                                    'supplier'=>$getDetail->supplier,
                                    'component'=>$getDetail->component,
                                    'type'=>'CEMENT'])
                                    ->update(['adhesive_kind'=>$adhesive_kind_cement]);
                    }catch (\Exception $e) {
                        $data = array(
                            'alert'=>'Gagal!',
                            'text'=> $e->getMessage(),
                            'color'=>'danger'
                        );
                        return json_encode($data);
                    }
            }
            $data = array(
                'alert'=>'Sukses!',
                'text'=> 'Save Data Successfull!',
                'color'=>'success'
            );
            Cache::forget('chemical___database');
            return json_encode($data);
        }

}

