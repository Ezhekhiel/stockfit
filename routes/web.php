<?php

use App\Events\DashboardUpdated;
use App\Http\Controllers\ChemicalWasteController;
use App\Http\Controllers\labChemicalController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\padPressController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\registerPengawasController;
use App\Http\Controllers\settingTargetController;
use App\Http\Controllers\stfBalanceController;
use App\Http\Controllers\stfOutputController;
use App\Http\Controllers\stfOutputControllerErp;
use App\Http\Controllers\stfSettingLine;
use App\Http\Controllers\stfSettingLineDashboardController;
use App\Http\Controllers\toolingManageController;
use App\Http\Controllers\VisualController;
use App\Models\WhatsappSchedule;
use App\Services\ReportService;
use App\Services\WhatsappScheduleService;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Route;
use Spatie\Browsershot\Browsershot;

Route::get('/', function () {
    return view('welcome');
})->name('index');
Route::get('/hello', function () {
    return 'HELLO';
});
// })->middleware(['auth'])->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Setting Stockfit

    Route::get('/setting_target',[settingTargetController::class,'index']);
    Route::get('/setting_target/search/target',[settingTargetController::class,'search_target'])->name('setting_target.search.target');
    Route::post('/setting_target/getData/editTarget',[settingTargetController::class,'editTarget'])->name('setting_target.getData.editTarget');

// Register Pengawas
    Route::get('/register_pengawas',[registerPengawasController::class,'index']);
    Route::get('/register_pengawas/data_pengawas',[registerPengawasController::class,'data_pengawas'])->name('register_pengawas.data_pengawas');
    Route::POST('/register_pengawas/save_pengawas',[registerPengawasController::class,'save_pengawas'])->name('register_pengawas.save_pengawas');
    Route::POST('/register_pengawas/update_pengawas',[registerPengawasController::class,'update_pengawas'])->name('register_pengawas.update_pengawas');
    Route::get('/register_pengawas/getData/Pengawas',[registerPengawasController::class,'get_data_pengawas'])->name('register_pengawas.getData.Pengawas');
    Route::get('/register_pengawas/delete/Pengawas',[registerPengawasController::class,'delete_data_pengawas'])->name('register_pengawas.delete.Pengawas');

// Pre-paration stockfit
    //setting line
        Route::get('/setting_line',[stfSettingLine::class,'index']);
        Route::get('/setting_line/main',[stfSettingLine::class,'main'])->name('setting_line.main');
        Route::get('/setting_line/getPo_list',[stfSettingLine::class,'getPo_list'])->name('setting_line.getPo_list');
        Route::get('/setting_line/change_data',[stfSettingLine::class,'change_data'])->name('setting_line.change_data');
        Route::POST('/setting_line/input/save',[stfSettingLine::class,'save_input'])->name('setting_line.input.save');
        Route::GET('/setting_line/detail_line',[stfSettingLine::class,'detail_line'])->name('setting_line.detail_line');
        Route::get('/setting_line/change_progress_bar',[stfSettingLine::class,'change_progress_bar'])->name('setting_line.change_progress_bar');
        Route::get('/setting_line/delete_input',[stfSettingLine::class,'delete_input'])->name('setting_line.delete_input');
        Route::get('/setting_line/delete_input/detail',[stfSettingLine::class,'delete_input_detail'])->name('setting_line.delete_input.detail');
        Route::get('/setting_line/transfer',[stfSettingLine::class,'transfer'])->name('setting_line.transfer');
        Route::POST('/setting_line/update',[stfSettingLine::class,'update'])->name('setting_line.update');
        Route::POST('/setting_line/print',[stfSettingLine::class,'print']);
    //Setting Line Dashboard
        //dashboard
            Route::get('/setting_line/dashboard',[stfSettingLineDashboardController::class,'index']);
            Route::get('/setting_line/dashboard/main',[stfSettingLineDashboardController::class,'main'])->name('setting_line.dashboard.main');
            Route::get('/setting_line/dashboard/show_modal',[stfSettingLineDashboardController::class,'show_modal'])->name('setting_line.dashboard.show_modal');
        //data
            Route::get('/setting_line/data',[stfSettingLineDashboardController::class,'data'])->name('setting_line.data');
            Route::POST('/setting_line/data/export',[stfSettingLineDashboardController::class,'data_export']);

// Stockfit Line
    Route::get('/output_stf',[stfOutputControllerErp::class,'index']);
    Route::POST('/output_stf/export',[stfOutputControllerErp::class,'export']);
    Route::get('/output_stf/main',[stfOutputControllerErp::class,'main'])->name('output_stf.main');
    Route::get('/output_stf/detailBTS',[stfOutputControllerErp::class,'detailBTS'])->name('output_stf.detailBTS');
    Route::POST('/output_stf/print',[stfOutputControllerErp::class,'print']);
    Route::get('/output_stf/getPo_list',[stfOutputControllerErp::class,'getPo_list'])->name('output_stf.getPo_list');
    Route::get('/output_stf/data_pengawas',[stfOutputControllerErp::class,'data_pengawas'])->name('output_stf.data_pengawas');
    Route::get('/output_stf/change_bm',[stfOutputControllerErp::class,'change_bm'])->name('output_stf.change_bm');
    Route::get('/output_stf/showDetail',[stfOutputControllerErp::class,'showDetail'])->name('output_stf.showDetail');
    Route::get('/output_stf/selectLine',[stfOutputControllerErp::class,'selectLine'])->name('output_stf.selectLine');
    Route::get('/output_stf/change/po',[stfOutputControllerErp::class,'change_po'])->name('output_stf.change.po');
    Route::get('/output_stf/change/data',[stfOutputControllerErp::class,'change_data'])->name('output_stf.change.data');
    Route::post('/output_stf/delete/byPerline',[stfOutputControllerErp::class,'delete_detail_perline'])->name('output_stf.delete.byPerline');
    Route::get('/output_stf/trackingPO',[stfOutputControllerErp::class,'trackingPO'])->name('output_stf.trackingPO');

    //yang mau di hapus
        Route::POST('/output_stf/save_pengawas',[stfOutputControllerErp::class,'save_pengawas'])->name('output_stf.save_pengawas');
        Route::POST('/output_stf/update_pengawas',[stfOutputControllerErp::class,'update_pengawas'])->name('output_stf.update_pengawas');
        Route::get('/output_stf/getData/Pengawas',[stfOutputControllerErp::class,'get_data_pengawas'])->name('output_stf.getData.Pengawas');
        Route::get('/output_stf/delete/Pengawas',[stfOutputControllerErp::class,'delete_data_pengawas'])->name('output_stf.delete.Pengawas');

        Route::get('/output_stf/getData/wip',[stfOutputControllerErp::class,'get_wip'])->name('output_stf.getData.wip');
        Route::get('/output_stf/getData/detail_gabungan',[stfOutputControllerErp::class,'detail_gabungan'])->name('output_stf.getData.detail_gabungan');
        Route::get('/output_stf/getData/detail_perline',[stfOutputControllerErp::class,'detail_perline'])->name('output_stf.getData.detail_perline');
        Route::get('/output_stf/getData/detail_perDay',[stfOutputControllerErp::class,'detail_perDay'])->name('output_stf.getData.detail_perDay');


    Route::POST('/output_stf/input/save',[stfOutputControllerErp::class,'save_input'])->name('output_stf.input.save');
    Route::POST('/output_stf/output/save',[stfOutputControllerErp::class,'save_output'])->name('output_stf.output.save');
    Route::POST('/output_stf/shortage_input/save',[stfOutputControllerErp::class,'save_shortage_input'])->name('output_stf.shortage_input.save');
    Route::POST('/output_stf/shortage_output/save',[stfOutputControllerErp::class,'save_shortage_output'])->name('output_stf.shortage_output.save');
    Route::POST('/output_stf/reject/save',[stfOutputControllerErp::class,'save_reject'])->name('output_stf.reject.save');

    Route::get('/output_stf/balance',[stfBalanceController::class,'balance']);
    Route::get('/output_stf/balance/main',[stfBalanceController::class,'main'])->name('output_stf.balance.main');
    Route::get('/output_stf/balance/search',[stfBalanceController::class,'balance_search'])->name('output_stf.balance.search');
    Route::POST('/output_stf/balance/print_balance',[stfBalanceController::class,'print_balance']);



    Route::get('/printQR',[stfOutputControllerErp::class,'printQR']);

//BPFC
    Route::get('/bpfc', function () {
        return view('bpfc.Content.home');
    });
    Route::GET('/bpfc/show', [VisualController::class, 'searchImage'])->name('show');

// Chemical
    Route::get('/lab/chemical',[labChemicalController::class,'index']);
    Route::get('/lab/chemical/main',[labChemicalController::class,'main'])->name('lab.chemical.main');
    Route::POST('/lab/chemical/update',[labChemicalController::class,'update'])->name('lab.chemical.update');
    Route::get('/lab/chemical/update_status',[labChemicalController::class,'update_status'])->name('lab.chemical.update_status');
    Route::POST('/lab/chemical/print_barcode',[labChemicalController::class,'print_barcode']);
    Route::POST('/lab/chemical/custome_print_barcode',[labChemicalController::class,'custome_print_barcode']);
    Route::POST('/lab/chemical/mixing',[labChemicalController::class,'mixing'])->name('lab.chemical.mixing');
    Route::get('/lab/chemical/search_model_mixing',[labChemicalController::class,'search_model_mixing'])->name('lab.chemical.search_model_mixing');
    Route::get('/lab/chemical/search_model_mixing_act',[labChemicalController::class,'search_model_mixing_act'])->name('lab.chemical.search_model_mixing_act');
    Route::get('/lab/chemical/search_model',[labChemicalController::class,'search_model'])->name('lab.chemical.search_model');
    Route::get('/save_id_telegram',[labChemicalController::class,'test_connection_telegram']);
    Route::get('/tesChanel',[labChemicalController::class,'tesChanel']);
    Route::get('/testKirimPesan/{cell}',[labChemicalController::class,'kirim_pesan']);


    Route::get('/lab/chemical_waste/',[ChemicalWasteController::class,'index']);
    Route::POST('/lab/chemical_waste/',[ChemicalWasteController::class,'store'])->name('lab.chemical.waste');
    Route::put('/lab/chemical_waste/{id}', [ChemicalWasteController::class, 'update'])->name('lab.chemical.waste.update');
    Route::delete('/lab/chemical_waste/{id}',[ChemicalWasteController::class,'destroy'])->name('lab.chemical.waste.destroy');

    Route::get('/lab/chemical_waste/main',[ChemicalWasteController::class,'main'])->name('lab.chemical.waste.main');
    Route::get('/lab/chemical_waste/export',[ChemicalWasteController::class,'export'])->name('lab.chemical.waste.export');


    //database chemical
        Route::get('/lab/chemical/database',[labChemicalController::class,'index_database'])->name('lab.chemical.database');
        Route::get('/lab/chemical/database/main',[labChemicalController::class,'main_database'])->name('lab.chemical.database.main');
        Route::get('/lab/chemical/database/get_data_delete',[labChemicalController::class,'get_data_delete'])->name('lab.chemical.database.get_data_delete');
        Route::post('/lab/chemical/database/save',[labChemicalController::class,'save_database'])->name('lab.chemical.database.save');
        Route::get('/lab/chemical/database/getDataByArr',[labChemicalController::class,'getDataByArr'])->name('lab.chemical.database.getDataByArr');
        Route::get('/lab/chemical/database/update',[labChemicalController::class,'update_database'])->name('lab.chemical.database.update');
        Route::get('/lab/chemical/scan/{id_barcode}',[labChemicalController::class,'scan_id_barcode']);
        Route::get('/lab/chemical/database/deleteDatabase',[labChemicalController::class,'deleteDatabase'])->name('lab.chemical.database.deleteDatabase');
        Route::POST('/lab/chemical/export',[labChemicalController::class,'export'])->name('lab.chemical.export');

require __DIR__.'/auth.php';
