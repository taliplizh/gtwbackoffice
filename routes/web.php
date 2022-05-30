<?php
use App\Http\Middleware\Activate;
use App\Http\Controllers\ManagerenvController;
use App\Http\Controllers\ManagerpersonController;
use App\Http\Controllers\Setupinfo_publicizeController;
use App\Http\Controllers\Setupinfo_pagefacebookController;
use App\Http\Controllers\FormpdfController;
use App\Http\Controllers\PersonRegaliaController;
use App\Http\Controllers\MeetingroomStyleroomController;
use App\Http\Controllers\ManagerrepairnomalController;
use App\Http\Controllers\AbilityController;
use App\Http\Controllers\PerdevController;
use App\Http\Controllers\InfoworkDocController;
use App\Http\Controllers\PersonInfoworkDocController;
use App\Http\Controllers\LaunderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!

*/

Auth::routes();
//checklogin
Route::middleware(['checklogin'])->group(function(){ 
Route::group(["namespace"=>"App\Http\Controllers",'middleware' => ['activate']],function() {
Route::get('/login', 'Auth\LoginController@login')->name('login')->withoutMiddleware('checklogin');
Route::post('login', 'Auth\LoginController@authenticate')->withoutMiddleware('checklogin');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('datacheck', 'DatacheckController@index');

//ย้าย user Email ไปที่ username
Route::get('/settings','SettingsController@index');
Route::post('/settings/save','SettingsController@save')->name('settings.save');
Route::get('/moveuser','SettingsController@moveuser');

// ฺbackup Database
Route::get('backups','BackupController@index');
Route::get('backupnow','BackupController@backupnow');
Route::get('backups/getbody','BackupController@listbody');
Route::get('backups/total-unit','BackupController@totalUnit');
Route::get('backup/download/{name}','BackupController@download')->name('backup.download');
Route::delete('backup/delete','BackupController@delete')->name('backup.delete');

//ส่งข้อมุลไป Datacenter
Route::get('datacentersync','DatacentersyncController@index');
Route::get('datacentersync/summary','DatacentersyncController@summary');
Route::get('datacentersync/getasset','DatacentersyncController@getasset');
Route::get('datacentersync/getperson','DatacentersyncController@getPerson');

Route::get('to-utf8',function(){

                $dbname = env('DB_DATABASE');
                $sql = "SELECT CONCAT('ALTER TABLE ', tbl.TABLE_SCHEMA, '.', tbl.TABLE_NAME, ' CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;') as name FROM information_schema.TABLES tbl WHERE tbl.TABLE_SCHEMA = :dbname limit 1000";
                $querys = DB::select($sql,[
                    'dbname' => $dbname,
                ]);
            
                foreach($querys as $query){
                    DB::statement($query->name);
                }
});


Route::get('update','UpdateController@index');
Route::get('check-for-update','UpdateController@checkforupdate');
Route::get('update/download','UpdateController@download');
Route::get('update/install','UpdateController@install');
Route::get('export','UpdateController@export');
Route::get('move','UpdateController@move');
Route::get('clone','UpdateController@clone');
// Route::any('move',function(){
//     return Artisan::call('ls');
// });
Route::get('versioncheck','VersioncheckController@index');
Route::get('listtable','VersioncheckController@listtable');
Route::get('countmaster','VersioncheckController@countmaster');



Route::get('/home',  function () {

    if (Auth::check()) {

        return redirect()->route('select.dashboard');

    }else{

        return view('auth.login');
    }

})->name('home');


Route::get('barcode','BarcodeController@makeBarcode');


Route::get('/', function () {

    if (Auth::check()) {

        return redirect()->route('select.dashboard');

    }else{

        return view('auth.login');
    }

})->name('index');


Route::get('/table', function () {
    return view('table_view');
});

Route::get('/checkdashboard', function(){
    if (Auth::check()) {
        $status = Auth::user()->status;
        $id_user = Auth::user()->PERSON_ID;
            if($status=='ADMIN'){
                return redirect()->route('admin.dashboard');
            }else if($status=='SMALL'){
                return redirect()->route('small.dashboard', [
                    'iduser' => $id_user]);
            }else if($status=='NOTUSER'){
                return view('notuser'); 
            }else{
               return redirect()->route('user.dashboard', [
                'iduser' => $id_user]);
            }
    }else{
        return redirect()->route('index');
    }

})->name('select.dashboard');


Route::get('/carcalendar','DashboardController@carcalendar')->name('้home.carcalendar')->withoutMiddleware('checklogin');
Route::get('/meetcalendar','DashboardController@meetcalendar')->name('้home.meetcalendar')->withoutMiddleware('checklogin');

Route::get('/dashboardadmin','HomeController@addmindashboard')->name('admin.dashboard');
Route::get('/dashboard/{iduser}','HomeController@userdashboard')->name('user.dashboard');
Route::get('/smalldashboard','HomeController@smalldashboard')->name('small.dashboard');


Route::get('welcome/name' ,'Auth\HelloController@showHello');

Route::get('prefix' ,'Auth\HelloController@prefix');
Route::get('about','SiteController@index')->name('adout');


//module person

Route::get('person/all','AdminPersonController@index')->name('person.all');
Route::get('person/excelall','AdminPersonController@excelperson');
Route::get('person/adduser','AdminPersonController@create');
Route::get('search','AdminPersonController@search')->name('addperson.search');
Route::post('addpersoninfouser','AdminPersonController@store')->name('addperson.store');
Route::post('updatepass','AdminPersonController@updatepass')->name('updatepass.store');
Route::post('updatestatus','AdminPersonController@updatestatususer')->name('updatestatusuer.store');
Route::get('changpassword','AdminPersonController@changpassword')->name('addperson.changpassword');
Route::post('updatechangpassword','AdminPersonController@updatechangpassword')->name('updatestatusuer.changpassword');
Route::get('person/changstatususer/{id}','AdminPersonController@changstatususer')->name('person.changstatususer');
Route::get('person/changpass/{id}','AdminPersonController@changpass')->name('person.changpass');


Route::get('person/module','AdminPersonController@module')->name('mo.module');
Route::post('person/module_save','AdminPersonController@module_save')->name('mo.module_save');

//=======เพิ่มแบบเร่งรัด

Route::post('person/adduserintensive','AdminPersonController@adduserintensive')->name('adduserintensive.save');


Route::get('person/personinfouser/{iduser}','PersonController@infouser')->name('person.info');
Route::post('editpersoninfouser/{iduser}','PersonController@update');
Route::get('person/personinfouser/edit/{iduser}','PersonController@edit');
Route::get('/editpersoninfouser/fetch','PersonController@fetch')->name('dropdown.fetch');
Route::get('/editpersoninfouser/fetchsub','PersonController@fetchsub')->name('dropdown.fetchsub');
Route::get('/editpersoninfouser/checkemail','PersonController@checkemail')->name('check.checkemail');
Route::get('/editpersoninfouser/checkhrid','PersonController@checkhrid')->name('check.checkhrid');
Route::get('changpassworduser/{iduser}','PersonController@changpassworduser')->name('person.changpassworduser');
Route::post('updatechangpassworduser','PersonController@updatechangpassworduser')->name('person.updatechangpassworduser');

Route::get('/editpersoninfouser/department','PersonController@department')->name('dropdown.department');
Route::get('/editpersoninfouser/departmenthsub','PersonController@departmenthsub')->name('dropdown.departmenthsub');

Route::get('person/personinfousereducat/{iduser}','EducationController@infousereducat')->name('person.infoedu');
Route::post('addpersoninfousereducat','EducationController@save')->name('addedu.save');
Route::post('addpersoninfousereducat/edit','EducationController@edit')->name('addedu.edit');
Route::get('addpersoninfousereducat/destroy/{id}/{iduser}','EducationController@destroy');

Route::get('person/personinfouserwork/{iduser}','WorkController@infouserwork')->name('person.infowork');
Route::post('addpersoninfouserwork','WorkController@save')->name('addwork.save');
Route::post('addpersoninfouserwork/edit','WorkController@edit')->name('addwork.edit');
Route::get('addpersoninfouserwork/destroy/{id}/{iduser}','WorkController@destroy');

Route::get('person/personinfouserreward/{iduser}','RewardController@infouserreward')->name('person.inforeward');
Route::post('addpersoninfouserreward','RewardController@save')->name('addreward.save');
Route::post('addpersoninfouserreward/edit','RewardController@edit')->name('addreward.edit');
Route::get('addpersoninfouserreward/destroy/{id}/{iduser}','RewardController@destroy');

Route::get('person/personinfouserexpert/{iduser}','ExpertController@infouserexpert')->name('person.inforexp');
Route::post('addpersoninfouserexpert','ExpertController@save')->name('addexpert.save');
Route::post('addpersoninfouserexpert/edit','ExpertController@edit')->name('addexpert.edit');
Route::get('addpersoninfouserexpert/destroy/{id}/{iduser}','ExpertController@destroy');

Route::get('person/personinfouserchangename/{iduser}','ChangenameController@infouserchangename')->name('person.inforchangname');
Route::post('addpersoninfouserchangename','ChangenameController@save')->name('addchangname.save');
Route::post('addpersoninfouserchangename/edit','ChangenameController@edit')->name('addchangname.edit');
Route::get('addpersoninfouserchangename/destroy/{id}/{iduser}','ChangenameController@destroy');

Route::get('person/personinfousersalary/{iduser}','SalaryController@infousersalary')->name('person.inforsalary');
Route::post('addpersoninfousersalary','SalaryController@save')->name('addsalary.save');
Route::post('addpersoninfousersalary/edit','SalaryController@edit')->name('addsalary.edit');
Route::get('addpersoninfousersalary/destroy/{id}/{iduser}','SalaryController@destroy');


Route::get('person/personinfouserteamlist/{iduser}','TeamlistController@infouserteamlist')->name('person.inforteamlist');

Route::get('person/personinfouseretrainspacial/{iduser}','TrainspacialController@infouseretrainspacial')->name('person.infortrain');
Route::post('addpersoninfousertrainspacial','TrainspacialController@save')->name('addtrain.save');
Route::post('addpersoninfousertrainspacial/edit','TrainspacialController@edit')->name('addtrain.edit');
Route::get('addpersoninfousertrainspacial/destroy/{id}/{iduser}','TrainspacialController@destroy');

Route::get('person/personinfouseroccu/{iduser}','OccuController@infouseroccu')->name('person.inforoccu');
Route::post('addpersoninfouseroccu','OccuController@save')->name('addoccu.save');
Route::post('addpersoninfouseroccu/edit','OccuController@edit')->name('addoccu.edit');
Route::get('addpersoninfouseroccu/destroy/{id}/{iduser}','OccuController@destroy');

Route::get('person/personinfousercid/{iduser}','CidController@infousercid')->name('person.inforcid');
Route::get('person/addpersoninfousercid/{iduser}','CidController@create')->name('person.addcid');
Route::get('person/editpersoninfousercid/{id}/{iduser}','CidController@edit')->name('person.editid');
Route::post('addpersoninfousercid','CidController@save')->name('addcid.save');
Route::post('addpersoninfousercid/update','CidController@update')->name('addcid.update');
Route::get('addpersoninfousercid/destroy/{id}/{iduser}','CidController@destroy');

Route::get('person/personinfouserofficial/{iduser}','OfficialController@infouserofficial')->name('person.inforoff');
Route::get('person/addpersoninfouserofficial/{iduser}','OfficialController@create')->name('person.addoff');
Route::get('person/editpersoninfouserofficial/{id}/{iduser}','OfficialController@edit')->name('person.editoff');
Route::post('addpersoninfouserofficial','OfficialController@save')->name('addofficial.save');
Route::post('addpersoninfouserofficial/update','OfficialController@update')->name('addofficial.update');
Route::get('addpersoninfouserofficial/destroy/{id}/{iduser}','OfficialController@destroy');

Route::get('person/personinfouserother/{iduser}','OtherController@infouserother')->name('person.inforother');;
Route::post('addpersoninfouserfamily','OtherController@save')->name('addfamily.save');
Route::post('addpersoninfouserfamily/edit','OtherController@edit')->name('addfamily.edit');
Route::get('addpersoninfouserfamily/destroy/{id}/{iduser}','OtherController@destroy');

Route::get('person/personinfouserdisciplinary/{iduser}','DisciplinaryController@infouserdiscip')->name('person.infouserdiscip');;
Route::post('addpersoninfouserdisciplinary','DisciplinaryController@save')->name('adddiscip.save');
Route::post('addpersoninfouserdisciplinary/edit','DisciplinaryController@edit')->name('adddiscip.edit');
Route::get('addpersoninfouserdisciplinary/destroy/{id}/{iduser}','DisciplinaryController@destroy');


Route::get('person/personinfousersignature/{iduser}','SignatureController@infousersignature')->name('person.inforsignature');
Route::get('person/addpersoninfousersignature/{iduser}','SignatureController@create')->name('person.addsignature');
Route::get('person/editpersoninfousersignature/{id}/{iduser}','SignatureController@edit')->name('person.editsignature');
Route::post('addpersoninfousersignature','SignatureController@save')->name('addsignature.save');
Route::post('addpersoninfousersignature/update','SignatureController@update')->name('addsignature.update');
Route::get('addpersoninfousersignature/destroy/{id}/{iduser}','SignatureController@destroy');




//=========================================================================================================
// Job description
Route::match(['get','post'],'person_work/personworkjobdescription/{iduser}','AbilityController@jobdescription')->name('person.infowork.jobdescriptions');
Route::post('person_work/personworkjobdescription/update_be_informed/{iduser}','AbilityController@jobdescription_update_be_informed')->name('person.infowork.update_be_informed');

Route::get('person_work/personworkjobdescription/data_estimate_6/{id_jobperson}/{iduser}','AbilityController@jobdescription_data_estimate_6')->name('person.infowork.data_estimate_6');
Route::post('person_work/personworkjobdescription/data_estimate_6/save/{iduser}','AbilityController@jobdescription_data_estimate_6_save')->name('person.infowork.data_estimate_6_save');
Route::get('person_work/personworkjobdescription/data_estimate_12/{id_jobperson}/{iduser}','AbilityController@jobdescription_data_estimate_12')->name('person.infowork.data_estimate_12');
Route::post('person_work/personworkjobdescription/data_estimate_12/save/{iduser}','AbilityController@jobdescription_data_estimate_12_save')->name('person.infowork.data_estimate_12_save');
// ประเมิน Job description
Route::match(['get','post'],'person_work/personwork_estimate_jobdescription/{iduser}','AbilityController@jobdescription_estimate')->name('person.infowork.jobdes_estimate');
Route::get('person_work/personwork_estimate_kpi_person/{budgetyear}/{person_estimate_id}/{iduser}','AbilityController@personwork_estimate_kpi_person')->name('person.infowork.personwork_estimate_kpi_person');
Route::post('person_work/personwork_estimate_kpi_person/update/{iduser}','AbilityController@personwork_estimate_kpi_person_update')->name('person.infowork.personwork_estimate_kpi_person_update');

// Core competency
Route::get('person_work/personworkcorecompetency_detail/{iduser}','AbilityController@corecompetency_detail');
Route::get('person_work/personworkcorecompetency_setup/{iduser}','AbilityController@personworkcorecompetency_setup');
Route::post('person_work/personworkcorecompetency_setupupdate','AbilityController@personworkcorecompetency_setupupdate')->name('abi.personworkcorecompetency_setupupdate');
// ไม่มีลิ้งหน้าเว็บ คอมเม้นเนื้อหาไว้ ใช้ไม่ได้ error
Route::get('person_work/personworkcorecompetency_detail_sub/{idref}/{iduser}','AbilityController@personworkcorecompetency_detail_sub');
// จบไม่มีลิ้งหน้าเว็บ คอมเม้นเนื้อหาไว้ ใช้ไม่ได้ error
// ไม่มีลิ้งหน้าเว็บ
Route::get('person_work/personworkcorecompetency/{iduser}','AbilityController@corecompetency');
// จบไม่มีลิ้งหน้าเว็บ

// Funtional competency
Route::get('person_work/personworkfuntionalcompetency_detail/{iduser}','AbilityController@funtionalcompetency_detail');
Route::get('person_work/personworkfuntionalcompetency_setup/{iduser}','AbilityController@personworkfuntionalcompetency_setup');
Route::post('person_work/personworkfuntionalcompetency_setupupdate','AbilityController@personworkfuntionalcompetency_setupupdate')->name('abi.personworkfuntionalcompetency_setupupdate');
// ไม่มีลิ้งหน้าเว็บ
Route::get('person_work/personworkfuntionalcompetency_detail_sub/{idref}/{iduser}','AbilityController@personworkfuntionalcompetency_detail_sub');
Route::get('person_work/personworkfuntionalcompetency/{iduser}','AbilityController@funtionalcompetency');
// จบไม่มีลิ้งหน้าเว็บ
// KPI
Route::get('person_work/personworkkpi/{iduser}','AbilityController@personworkkpi')->name('pwork.kpi');
Route::post('person_work/personworkkpi/user_update_kpi/{iduser}','AbilityController@personworkkpi_user_update_kpi')->name('pwork.user_update_kpi');
Route::get('person_work/personworkkpis/{iduser}','AbilityController@kpis');
Route::get('person_work/personworkkpis_detail/{iduser}','AbilityController@personworkkpis_detail');
// E-Leanning





//----------------------------ตรวจสุขภาพ-----------------------



Route::get('person_work/personworkability/{iduser}','AbilityController@infoability');
Route::get('person_work/personworkability/detail/{iduser}/{idref}','AbilityController@detail');

Route::get('person_work/carcalendarhealth/{iduser}','AbilityController@carcalendarhealth')->name('health.carcalendarhealth');
Route::get('person_work/personworkscreening/checkup/{iduser}','AbilityController@checkup')->name('health.checkup');
Route::get('person_work/personworkscreening/screen_add/{iduser}','AbilityController@screen_add')->name('health.screen_add');
Route::post('person_work/personworkscreening/screen_save','AbilityController@screen_save')->name('health.screen_save');

Route::get('person_work/personworkscreening/screen/{idref}/{iduser}','AbilityController@screen')->name('health.screen');
Route::post('person_work/personworkscreening/screen_sub_save','AbilityController@screen_sub_save')->name('health.screen_sub_save');

Route::get('person_work/healthpdf/{idref}','AbilityController@healthpdf')->name('mperson.healthpdf'); //ไฟล์สุขภาพ pdf

//---bmi
Route::get('person_work/personworkscreening/calbmi','AbilityController@calbmi')->name('health.calbmi');
Route::get('person_work/personworkscreening/bodysize','AbilityController@bodysize')->name('health.bodysize');
Route::get('person_work/personworkscreening/checkdateyear','AbilityController@checkdateyear')->name('health.checkdateyear');
//----------------จัดการข้อมูลสุขภาพ
Route::get('manager_person/healthdashboard','ManagerpersonController@healthdashboard')->name('health.healthdashboard');
Route::post('manager_person/healthdashboard_search','ManagerpersonController@healthdashboard_search')->name('health.healthdashboard_search');
Route::match(['get','post'],'manager_person/healthdashboard/Illness_history','ManagerpersonController@healthdashboard_Illness_history')->name('health.healthdashboard_Illness_history');
Route::match(['get','post'],'manager_person/healthdashboard/physical_examination_results','ManagerpersonController@healthdashboard_physical_examination_results')->name('health.healthdashboard_physical_examination_results');
Route::get('manager_person/carcalendarhealth','ManagerpersonController@carcalendarhealth')->name('health.carcalendarhealth');
Route::match(['get','post'],'manager_person/summarize/health_check_history','ManagerpersonController@summarize_health_check_history')->name('health.summarize_health_check_history');

Route::match(['get','post'],'manager_person/inforpersonhealth','ManagerpersonController@inforpersonhealth')->name('health.inforpersonhealth');
Route::post('manager_person/inforpersonhealthsearch','ManagerpersonController@inforpersonhealth_search')->name('health.inforpersonhealth_search');
Route::get('manager_person/excelinforpersonhealth','ManagerpersonController@excelinforpersonhealth');

Route::post('manager_person/personworkscreening/mana_screen_save','ManagerpersonController@mana_screen_save')->name('health.mana_screen_save');

Route::get('manager_person/health/{iduser}','ManagerpersonController@health')->name('mperson.health');

Route::get('manager_person/health_add/{idref}/{iduser}','ManagerpersonController@health_add')->name('mperson.health_add');
Route::post('manager_person/health_save','ManagerpersonController@health_save')->name('mperson.health_save');

Route::get('manager_person/destroy_screen/{idref}/{iduser}','ManagerpersonController@destroy_screen')->name('mperson.destroy_screen');

Route::get('manager_person/destroy_capacity/{idref}/{iduser}','ManagerpersonController@destroy_capacity')->name('mperson.destroy_capacity');

//----------------ทดสอบสมรรถภาพ

Route::get('manager_person/capacity_main','ManagerpersonController@capacity_main')->name('mperson.capacity_main');

Route::get('manager_person/capacity/{iduser}','ManagerpersonController@capacity')->name('mperson.capacity');
Route::get('manager_person/capacity_add/{iduser}','ManagerpersonController@capacity_add')->name('mperson.capacity_add');
Route::post('manager_person/capacity_save','ManagerpersonController@capacity_save')->name('mperson.capacity_save');

Route::get('manager_person/capacity_detail/{iduser}/{idref}','ManagerpersonController@capacity_detail')->name('mperson.capacity_detail');
Route::post('manager_person/capacity_update','ManagerpersonController@capacity_update')->name('mperson.capacity_update');



Route::get('manager_person/healthConfirm/{idref}/{iduser}','ManagerpersonController@healthConfirm')->name('mperson.healthConfirm'); //ยืนยันแลป
Route::post('manager_person/healthConfirmsave','ManagerpersonController@healthConfirmsave')->name('mperson.healthConfirmsave');

Route::get('manager_person/healthConfirm_edit/{idref}/{iduser}','ManagerpersonController@healthConfirm_edit')->name('mperson.healthConfirm_edit');
Route::post('manager_person/healthConfirmupdate','ManagerpersonController@healthConfirmupdate')->name('mperson.healthConfirmupdate');

Route::get('manager_person/checkcode','ManagerpersonController@checkcode')->name('mperson.checkcode');
Route::get('manager_person/checkpice','ManagerpersonController@checkpice')->name('mperson.checkpice');
Route::get('manager_person/checksummoney','ManagerpersonController@checksummoney')->name('mperson.checksummoney');


Route::get('manager_person/healthBody/{idref}/{iduser}','ManagerpersonController@healthBody')->name('mperson.healthBody'); //ตรวจร่างกาย
Route::get('manager_person/avgbloodlower','ManagerpersonController@avgbloodlower')->name('mperson.healthavgbloodlower');
Route::get('manager_person/avgbloodtop','ManagerpersonController@avgbloodtop')->name('mperson.healthavgbloodtop');
Route::post('manager_person/healthbodysave','ManagerpersonController@healthbodysave')->name('mperson.healthbodysave');
Route::get('manager_person/healthBody_edit/{idref}/{iduser}','ManagerpersonController@healthBody_edit')->name('mperson.healthBody_edit');
Route::post('manager_person/healthbodyupdate','ManagerpersonController@healthbodyupdate')->name('mperson.healthbodyupdate');

Route::get('manager_person/reportinforpersonhealth/','ManagerpersonController@reportinforpersonhealth')->name('health.reportinforpersonhealth');


Route::get('manager_person/health_destroy/{idref}/{iduser}','ManagerpersonController@health_destroy')->name('mperson.health_destroy');//ลบข้อมูล





// ================= ระบบสุขภาพ ทดสอบสมรรถภาพ Manager person health check By Oat_dev =======================
Route::prefix('manager_person/health-check')->name('manager_person.health-check.')->group(function () {

    Route::get('/main', [ManagerpersonController::class, 'healthCheck'])->name('main');
    Route::get('/form', [ManagerpersonController::class, 'healthCheckForm'])->name('form');
    Route::get('/form-backup', [ManagerpersonController::class, 'healthCheckFormBackup'])->name('form.backup');
    Route::get('/results', [ManagerpersonController::class, 'healthCheckResults'])->name('results');
    Route::get('/report', [ManagerpersonController::class, 'healthCheckReport'])->name('report');
    Route::get('/report-table/{type}', [ManagerpersonController::class, 'healthCheckReportTable'])->name('report-table');

});



//==================================================== ระบบเครื่องราช Person Regalia By Oat_dev =======================================================================
Route::get('person/inforegalia/main/{id}', [PersonRegaliaController::class, 'viewMain'])->name('person.inforegalia.main');
Route::post('person/inforegalia/add/config', [PersonRegaliaController::class, 'addConfig'])->name('person.inforegalia.add_config');
Route::get('person/info_regalia/view_edit/{id}', [PersonregaliaController::class, 'viewEdit'])->name('person.inforegalia.view_edit');
Route::post('person/inforegalia/edit/config', [PersonregaliaController::class, 'editConfig'])->name('person.inforegalia.edit_config');
Route::get('person/inforegalia/delete/config/{id}', [PersonregaliaController::class, 'deleteConfig'])->name('person.inforegalia.delete_config');



// ================= ระบบบุคลากร ข้อมูลบุคลากร Manager person infor person By Oat_dev =============================
Route::prefix('manager_person/inforperson')->name('manager_person.inforperson.')->group(function () {

    Route::get('/view-information/{id}', [ManagerpersonController::class, 'viewInforMation'])->name('view-information');
    Route::get('/view-work-history/{id}', [ManagerpersonController::class, 'viewWorkHistory'])->name('view-work-history');
    Route::get('/view-award/{id}', [ManagerpersonController::class, 'viewAward'])->name('view-award');
    Route::get('/view-regalia/{id}', [ManagerpersonController::class, 'viewRegalia'])->name('view-regalia');
    Route::get('/view-expertise/{id}', [ManagerpersonController::class, 'viewExpertise'])->name('view-expertise');
    Route::get('/view-name-change/{id}', [ManagerpersonController::class, 'viewNameChange'])->name('view-name-change');
    Route::get('/view-up-money/{id}', [ManagerpersonController::class, 'viewUpMoney'])->name('view-up-money');
    Route::get('/view-leadership-team/{id}', [ManagerpersonController::class, 'viewLeadershipTeam'])->name('view-leadership-team');
    Route::get('/view-specialized-training/{id}', [ManagerpersonController::class, 'viewSpecializedTraining'])->name('view-specialized-training');
    Route::get('/view-assembly-sheet/{id}', [ManagerpersonController::class, 'viewAssemblySheet'])->name('view-assembly-sheet');
    Route::get('/view-id-card/{id}', [ManagerpersonController::class, 'viewIdCard'])->name('view-id-card');
    Route::get('/view-vehicle/{id}', [ManagerpersonController::class, 'viewVehicle'])->name('view-vehicle');
    Route::get('/view-family-history/{id}', [ManagerpersonController::class, 'viewFamilyHistory'])->name('view-family-history');
    Route::get('/view-discipline/{id}', [ManagerpersonController::class, 'viewDiscipline'])->name('view-discipline');
    Route::get('/view-signature/{id}', [ManagerpersonController::class, 'viewSignature'])->name('view-signature');
    // Route::get('/view-main-test', [ManagerpersonController::class, 'viewMainTest']);

});

// ==================== ระบบของห้องประชุม รูปแบบห้องประชุม admin meeting style room By Oat_dev ==================================
Route::prefix('admin_meeting/style-room')->name('admin_meeting.style-room.')->group(function () {
    Route::get('/main', [MeetingroomStyleroomController::class, 'mainStyleroom'])->name('main');
    Route::get('setup-style-room', [MeetingroomStyleroomController::class, 'setupStyleRoom'])->name('setup.style.room');
    Route::get('/add-view', [MeetingroomStyleroomController::class, 'addView'])->name('add-view');
    Route::post('/add-config', [MeetingroomStyleroomController::class, 'addConfig'])->name('add-config');
    Route::get('/edit-view/{id}', [MeetingroomStyleroomController::class, 'editView'])->name('edit-view');
    Route::post('/edit-config', [MeetingroomStyleroomController::class, 'editConfig'])->name('edit-config');
    Route::get('/delete-config/{id}', [MeetingroomStyleroomController::class, 'deleteConfig'])->name('delete-config');

    Route::get('/modal-select-style-room', [MeetingroomStyleroomController::class, 'modalSelectStyleRoom'])->name('modal-select-style-room');

});

// ================================= ระบบจองห้องประชุม หน้าสีฟ้า เลือกรูปแบบห้องประชุม ===========================================
Route::prefix('general_meet/genmeet-room')->name('general_meet.genmeet-room.')->group(function () {

    Route::get('/select-style-room-add', [MeetingroomStyleroomController::class, 'selectStyleRoomAdd'])->name('select-style-room-add');
    Route::get('/select-style-room-edit', [MeetingroomStyleroomController::class, 'selectStyleRoomEdit'])->name('select-style-room-edit');

});





//=================================ผู้อำนวยการ================================
Route::get('person_headorg/dashboard','HeadorgController@dashboard')->name('horg.dashboard');

Route::get('person_headorg/infobook','HeadorgController@infobook')->name('horg.infobook');
Route::post('person_headorg/infobook/search','HeadorgController@infobooksearch')->name('horg.infobooksearch');

Route::get('person_headorg/infobookreceipt/control/{id}','HeadorgController@infobookreceiptcontrol')->name('horg.infobookreceiptcontrol');
Route::post('person_headorg/infobookreceipt/send','HeadorgController@sendreceipt')->name('horg.sendreceipt');
Route::post('person_headorg/infobookreceipt/saveretire','HeadorgController@saveretire')->name('horg.saveretire');


Route::get('person_headorg/infobookinside/control/{id}','HeadorgController@infobookentinsidecontrol')->name('horg.infobookentinsidecontrol');
Route::post('person_headorg/bookinside/send','HeadorgController@sendinside')->name('horg.sendinside');
Route::post('person_headorg/bookinside/saveretireinside','HeadorgController@saveretireinside')->name('horg.saveretireinside');


Route::get('person_headorg/infoleave','HeadorgController@infoleave')->name('horg.infoleave');
Route::get('person_headorg/infoleave/calender','HeadorgController@infoleavecalender')->name('horg.infoleave.calender');
Route::get('person_headorg/headorg_leave_app/{idref}','HeadorgController@headorg_leave_app')->name('horg.headorg_leave_app');

Route::post('person_headorg/infoleavesearch','HeadorgController@searchinfoleave')->name('horg.infoleavesearch');
Route::post('person_headorg/infoleaveupdatelastapp','HeadorgController@updatelastapp')->name('horg.updatelastapp');
Route::get('person_headorg/infoleaveupdatelastappall','HeadorgController@updatelastappall')->name('horg.updatelastappall');

Route::get('person_headorg/infodevapp','HeadorgController@infodevapp')->name('horg.infodevapp');
Route::get('person_headorg/infodevapp_app/{idref}','HeadorgController@infodevapp_app')->name('horg.infodevapp_app');

Route::post('person_headorg/searchinfoapp','HeadorgController@searchinfoapp')->name('horg.searchinfoapp');
Route::post('person_headorg/updateinfodevapp','HeadorgController@updateinfodevapp')->name('horg.updateinfodevapp');
Route::get('person_headorg/updateinfodevappall','HeadorgController@updateinfodevappall')->name('horg.updateinfodevappall');

Route::get('person_headorg/infocar','HeadorgController@infocar')->name('horg.infocar');
Route::get('person_headorg/infocar_app/{idref}','HeadorgController@infocar_app')->name('horg.infocar_app');

Route::post('person_headorg/infocarnomalsearch/','HeadorgController@infocarnomalsearch')->name('horg.infocarnomalsearch');
Route::post('person_headorg/updateinfocarnomalapp','HeadorgController@updateinfocarnomalapp')->name('horg.updateinfocarnomalapp');
Route::get('person_headorg/updateinfocarnomalappall','HeadorgController@updateinfocarnomalappall')->name('horg.updateinfocarnomalappall');

Route::get('person_headorg/supplier','HeadorgController@supplier')->name('horg.supplier');
Route::get('person_headorg/supplier_app/{idref}','HeadorgController@supplier_app')->name('horg.supplier_app');

Route::post('person_headorg/inforequestlastappsearch/','HeadorgController@inforequestlastappsearch')->name('horg.inforequestlastappsearch');
Route::post('person_headorg/updateinforequestlastapp','HeadorgController@updateinforequestlastapp')->name('horg.updateinforequestlastapp');

Route::get('person_headorg/meet','HeadorgController@meet')->name('horg.meet');
Route::get('person_headorg/meet_app/{idref}','HeadorgController@meet_app')->name('horg.meet_app');

Route::post('person_headorg/meet/search','HeadorgController@informeetsearch')->name('horg.informeetsearch');
Route::post('person_headorg/updateinfomeetnomalapp','HeadorgController@updateinfomeetnomalapp')->name('horg.updateinfomeetnomalapp');
Route::get('person_headorg/updateinfomeetnomalappall','HeadorgController@updateinfomeetnomalappall')->name('horg.updateinfomeetnomalappall');


// Route::get('person_headorg/borrow','HeadorgController@borrow')->name('horg.borrow');
Route::match(['get','post'],'person_headorg/borrow','HeadorgController@borrow')->name('horg.borrow');
Route::post('person_headorg/borrow_lastapp','HeadorgController@borrow_lastapp')->name('horg.borrow_lastapp');

//=================================หัวหน้ากลุ่มงาน================================
Route::get('person_headgroupdep/dashboard','HeadgroupdepController@dashboard')->name('hgroupdep.dashboard');
Route::get('person_headgroupdep/headgroupdep_calendarleave','HeadgroupdepController@headgroupdep_calendarleave')->name('hgroupdep.headgroupdep_calendarleave');

Route::get('person_headgroupdep/headgroupdep_leave','HeadgroupdepController@headgroupdep_leave')->name('hgroupdep.headgroupdep_leave');
Route::get('person_headgroupdep/headgroupdep_leave_app/{idref}','HeadgroupdepController@headgroupdep_leave_app')->name('hgroupdep.headgroupdep_leave_app');

Route::post('person_headgroupdep/headgroupdep_leavesearch','HeadgroupdepController@headgroupdep_leavesearch')->name('hgroupdep.headgroupdep_leavesearch');
Route::post('person_headgroupdep/headgroupdep_updateapp','HeadgroupdepController@headgroupdep_updateapp')->name('hgroupdep.headgroupdep_updateapp');

//=================================หัวหน้างาน================================
Route::get('person_headdep/dashboard','HeaddepController@dashboard')->name('hdep.dashboard');

Route::get('person_headdep/headdep_book','HeaddepController@headdep_book')->name('hdep.headdep_book');
Route::post('person_headdep/headdep_booksearch','HeaddepController@headdep_booksearch')->name('hdep.headdep_booksearch');
Route::get('person_headdep/headdep_receipt/control/{id}','HeaddepController@infobookreceiptcontrol')->name('hdep.infobookreceiptcontrol');
Route::post('person_headdep/headdep_receipt/send','HeaddepController@sendreceipt')->name('hdep.sendreceipt');
Route::post('person_headdep/headdep_saverpresent','HeaddepController@headdep_saverpresent')->name('hdep.headdep_saverpresent');

Route::get('person_headdep/headdep_leave_calendar','HeaddepController@headdep_leave_calendar')->name('hdep.headdep_leave_calendar');

Route::get('person_headdep/headdep_leave','HeaddepController@headdep_leave')->name('hdep.headdep_leave');
Route::get('person_headdep/headdep_leave_app/{idref}','HeaddepController@headdep_leave_app')->name('hdep.headdep_leave_app');

Route::post('person_headdep/headdep_leavesearch','HeaddepController@headdep_leavesearch')->name('hdep.headdep_leavesearch');
Route::post('person_headdep/headdep_updateapp','HeaddepController@headdep_updateapp')->name('hdep.headdep_updateapp');

Route::get('person_headdep/headdep_leave_report','HeaddepController@headdep_leave_report')->name('hdep.headdep_leave_report');
Route::post('person_headdep/headdep_leave_reportsearch','HeaddepController@headdep_leave_reportsearch')->name('hdep.headdep_leave_reportsearch');

Route::get('person_headdep/headdep_persondev','HeaddepController@headdep_persondev')->name('hdep.headdep_persondev');
Route::get('person_headdep/headdep_persondev_app/{idref}','HeaddepController@headdep_persondev_app')->name('hdep.headdep_persondev_app');

Route::post('person_headdep/headdep_persondev_update','HeaddepController@headdep_persondev_update')->name('hdep.headdep_persondev_update');
Route::post('person_headdep/headdep_persondev_search','HeaddepController@headdep_persondev_search')->name('hdep.headdep_persondev_search');

Route::get('person_headdep/headdep_supplier','HeaddepController@headdep_supplier')->name('hdep.headdep_supplier');
Route::get('person_headdep/headdep_supplier_app/{idref}','HeaddepController@headdep_supplier_app')->name('hdep.headdep_supplier_app');

Route::post('person_headdep/headdep_suppliersearch','HeaddepController@headdep_suppliersearch')->name('hdep.headdep_suppliersearch');
Route::post('person_headdep/headdep_updateinforequestapp','HeaddepController@headdep_updateinforequestapp')->name('hdep.headdep_updateinforequestapp');


Route::get('person_headdep/headdep_car','HeaddepController@headdep_car')->name('hdep.headdep_car');

Route::get('person_headdep/headdep_warehouse','HeaddepController@headdep_warehouse')->name('hdep.headdep_warehouse');
Route::get('person_headdep/headdep_warehouse_app/{idref}','HeaddepController@headdep_warehouse_app')->name('hdep.headdep_warehouse_app');

Route::post('person_headdep/headdep_warehousesearch','HeaddepController@headdep_warehousesearch')->name('hdep.headdep_warehousesearch');
Route::post('person_headdep/headdep_warehouseupdate','HeaddepController@headdep_warehouseupdate')->name('hdep.headdep_warehouseupdate');
Route::get('general_suplies/headdep_warehousedetail','HeaddepController@headdep_warehousedetail')->name('hdep.headdep_warehousedetail');

Route::get('person_headdep/headdep_setscore','HeaddepController@setscore')->name('hdep.setscore');


Route::get('person_headdep/headdep_kpis','HeaddepController@headdep_kpis')->name('hdep.headdep_kpis');
Route::get('person_headdep/headdep_kpis_detail','HeaddepController@headdep_kpis_detail')->name('hdep.headdep_kpis_detail');

Route::get('person_headdep/headdep_funtionalcompetency/{idhr}','HeaddepController@headdep_funtionalcompetency')->name('hdep.headdep_funtionalcompetency');
Route::get('person_headdep/headdep_funtionalcompetency_add/{idhr}','HeaddepController@headdep_funtionalcompetency_add')->name('hdep.headdep_funtionalcompetency_add');
Route::post('person_headdep/headdep_funtionalcompetency_save','HeaddepController@headdep_funtionalcompetency_save')->name('hdep.headdep_funtionalcompetency_save');
Route::get('person_headdep/headdep_funtionalcompetency_detail/{idhr}/{idref}','HeaddepController@headdep_funtionalcompetency_detail')->name('hdep.headdep_funtionalcompetency_detail');



Route::get('person_headdep/headdep_corecompetency/{idhr}','HeaddepController@headdep_corecompetency')->name('hdep.headdep_corecompetency');
Route::get('person_headdep/headdep_corecompetency_add/{idhr}','HeaddepController@headdep_corecompetency_add')->name('hdep.headdep_corecompetency_add');
Route::post('person_headdep/headdep_corecompetency_save','HeaddepController@headdep_corecompetency_save')->name('hdep.headdep_corecompetency_save');
Route::get('person_headdep/headdep_corecompetency_detail/{idhr}/{idref}','HeaddepController@headdep_corecompetency_detail')->name('hdep.headdep_corecompetency_detail');


Route::get('person_headdep/headdepplan_project','HeaddepController@headdepplan_project')->name('hdep.headdepplan_project');
Route::get('person_headdep/project_add','HeaddepController@project_add')->name('hdep.project_add');


Route::get('person_headdep/headdepplan_humandev','HeaddepController@headdepplan_humandev')->name('hdep.headdepplan_humandev');
Route::get('person_headdep/humandev_add','HeaddepController@humandev_add')->name('hdep.humandev_add');

Route::get('person_headdep/headdepplan_durable','HeaddepController@headdepplan_durable')->name('hdep.headdepplan_durable');
Route::get('person_headdep/durable_add','HeaddepController@durable_add')->name('hdep.durable_add');

//==================================ระบบลงเวลา======================================================
Route::get('person_checkin/personcheckin/{iduser}','CheckinController@selectcheck')->name('check.selectcheck');
Route::get('person_checkin/personcheckin_check/{idtype}/{iduser}','CheckinController@checkin')->name('check.checkin');
Route::get('person_checkin/personcheckininfo/{iduser}','CheckinController@infocheck')->name('check.infocheck');
Route::post('person_checkin/personcheckininfo/save','CheckinController@save')->name('checkin.save');

Route::post('person_checkin/personcheckininfosearch/{iduser}','CheckinController@search')->name('checkin.searchinfo');

Route::get('person_checkin/excel_checkin/{iduser}','CheckinController@excel_checkin')->name('checkin.excel_checkin'); // EXcel

//==================================ระบบลา=======================================================================
Route::get('person_leave/personleaveindex/{iduser}','LeaveController@infoindex')->name('leave.inforindex');
Route::get('person_leave/personleavetype/{iduser}','LeaveController@infotype')->name('leave.infortype');
Route::get('person_leave/personleaveinfo/{iduser}','LeaveController@infouser')->name('leave.inforuser');
Route::post('person_leave/personleavesearch/{iduser}','LeaveController@search')->name('leave.searchinfo');
Route::get('person_leave/excel_personleaveinfo/{iduser}','LeaveController@excel_personleaveinfo')->name('leave.excel_personleaveinfo');  // Excel

Route::get('person_leave/personleavecalendar/{iduser}','LeaveController@infocalendar')->name('leave.infocalendar'); //ปฎิทินการลา
Route::get('person_leave/addpersonleavesick/{leavetype}/{iduser}','LeaveController@createsick')->name('addleave.inforsick');

Route::get('person_leave/personleavecalldate/calldate','LeaveController@calldate')->name('leave.calldate');
Route::post('person_leave/personleavecalldate/save','LeaveController@save')->name('leave.save');
Route::get('person_leave/editpersonleavecalldate/{id}/{iduser}','LeaveController@editsick')->name('editleave.inforsick');
Route::post('person_leave/personleavecalldate/update','LeaveController@update')->name('leave.update');
Route::get('person_leave/cancelpersonleavecalldate/{id}/{iduser}','LeaveController@cancel')->name('cancelleave.inforsick');
Route::post('person_leave/personleaveinfoappcheck/updatecancel','LeaveController@updatecancel')->name('leaveuser.updatecancel');
Route::get('person_leave/personleavecalldate/checkdatebegin','LeaveController@checkdatebegin')->name('leave.checkdatebegin');
Route::get('person_leave/personleavecalldate/checkdateend','LeaveController@checkdateend')->name('leave.checkdateend');
Route::get('person_leave/personleavecalldate/checkall','LeaveController@checkall')->name('leave.checkall');

Route::get('person_leave/personleaveinfoapp/{iduser}','LeaveController@infoapp')->name('leave.inforapp');
Route::post('person_leave/personleaveinfoappsearch/{iduser}','LeaveController@searchapp')->name('leave.searchapp');
Route::get('person_leave/personleaveinfoappcheck/{id}/{iduser}','LeaveController@app')->name('leave.app');
Route::post('person_leave/personleaveinfoappcheck/updateapp','LeaveController@updateapp')->name('leave.updateapp');

Route::get('person_leave/personleaveinfover/{iduser}','LeaveController@infover')->name('leave.inforver');
Route::post('person_leave/personleaveinfoversearch/{iduser}','LeaveController@searchver')->name('leave.searchver');
Route::get('person_leave/personleaveinfovercheck/{id}/{iduser}','LeaveController@ver')->name('leave.ver');
Route::post('person_leave/personleaveinfovercheck/updatever','LeaveController@updatever')->name('leave.updatever');
Route::get('person_leave/cancelpersonleavever/{id}/{iduser}','LeaveController@cancelver')->name('cancelleave.inforver');
Route::post('person_leave/personleaveinfovercheck/updatecancel','LeaveController@updatecancelver')->name('leave.updatecancelver');

Route::get('person_leave/personleaveinfolastapp/{iduser}','LeaveController@infolastapp')->name('leave.inforlastapp');
Route::post('person_leave/personleaveinfolastappsearch/{iduser}','LeaveController@searchlastapp')->name('leave.searchlastapp');
Route::get('person_leave/personleaveinfolastappcheck/{id}/{iduser}','LeaveController@lastapp')->name('leave.lastapp');
Route::post('person_leave/personleaveinfolastappcheck/updatelastapp','LeaveController@updatelastapp')->name('leave.updatelastapp');
Route::get('person_leave/personleaveinfolastappcheckall/updatelastappall/{iduser}','LeaveController@updatelastappall');

Route::get('person_leave/personleaveinfoaccept/{iduser}','LeaveController@leaveinfoaccept')->name('leave.leaveinfoaccept');
Route::post('person_leave/personleaveinfoacceptsearch/{iduser}','LeaveController@leaveinfoacceptsearch')->name('leave.leaveinfoacceptsearch');
Route::post('person_leave/personleaveinfoaccept/updateappsend','LeaveController@updateappsend')->name('leave.updateappsend');

Route::get('person_leave/testnotify','LeaveController@testnotify');
Route::get('person_leave/personleavecalander/{iduser}','LeaveController@personleavecalander')->name('leave.personleavecalander');
Route::get('person_leave/personleavesendwork/{iduser}','LeaveController@personleavesendwork')->name('leave.personleavesendwork');

//---เอกสาร
Route::get('manager_leave/leavecertificate/{id}/{iduser}','LeaveController@leavecertificate')->name('leave.leavecertificate'); //แนบใบรับรองแพทย์
Route::post('manager_leave/leavecertificate_save','LeaveController@leavecertificate_save')->name('leave.leavecertificate_save');

//=================================== file PDF ======================================================================
Route::get('person_leave/personleavesick/export_pdfsick/{id}', 'LeaveController@pdfsick')->name('leave.pdfsick');
Route::get('person_leave/personleaveout/export_pdfout/{id}', 'LeaveController@pdfout')->name('leave.pdfout');  //ลาออกจากราชการของลูกจ้างชั่วคราว
Route::get('person_leave/ordain/pdfordain/{id}', 'LeaveController@pdfordain')->name('leave.pdfordain'); // ลาอุปสมบท

Route::get('person_leave/give/pdfgive/{id}', 'LeaveController@pdfgive')->name('leave.pdfgive');  //ไปช่วยเหลือภริยาคลอดบุร
Route::get('person_leave/cancelleave/pdfcancelleave/{id}', 'LeaveController@pdfcancelleave')->name('leave.pdfcancelleave');  //ใบขอยกเลิกใบลา
Route::get('person_leave/train/pdftrain/{id}', 'LeaveController@pdftrain')->name('leave.pdftrain');  //ใบลาไปศึกษา ฝึกอบรม

Route::get('person_leave/personleavesoldier/export_pdfsoldier/{id}', 'LeaveController@pdfsoldier')->name('leave.pdfsoldier'); //ใบลาเกณทหาร
Route::get('person_leave/personleavefollow/export_pdffollow/{id}', 'LeaveController@pdffollow')->name('leave.pdffollow'); //ใบลาติดตามคู่สมรส

Route::get('person_leave/personleaveout/export_pdfsicklow/{id}', 'LeaveController@pdfsicklow')->name('leave.pdfsicklow');  //ลาป่วยตามกดหมาย

Route::get('person_leave/personleavework/export_pdfwork/{id}', 'LeaveController@pdfwork')->name('leave.pdfwork');
Route::get('person_leave/personleavespawn/export_pdfspawn/{id}', 'LeaveController@pdfspawn')->name('leave.pdfspawn');

Route::get('person_leave/personleaverest/export_pdfrest/{id}', 'LeaveController@pdfrest')->name('leave.pdfrest');
//=========================================================================================================

//====================================ระบบพัฒนาบุคลากร======================================================


Route::get('person_dev/persondevpdfout01/export_pdfout01', 'PerdevController@pdfout01')->name('perdev.pdfout01'); //ฟร์อม1

Route::get('person_dev/persondevpdfout02/export_pdfout02', 'PerdevController@pdfout02')->name('perdev.pdfout02'); //ฟร์อม2 ตอบรับวิทยากร

Route::get('person_dev/persondevpdfout03/export_pdfout03', 'PerdevController@pdfout03')->name('perdev.pdfout03'); //ฟร์อม3 ขอใช้รถส่วนตัว




Route::get('person_dev/persondevindex/{iduser}','PerdevController@infoindex')->name('perdev.inforindex');
Route::get('person_dev/personmeetinginside/{iduser}','PerdevController@personmeetinginside')->name('perdev.personmeetinginside');
Route::get('person_dev/personmeetinginside_add/{iduser}','PerdevController@personmeetinginside_add')->name('perdev.personmeetinginside_add');
Route::post('person_dev/personmeetinginside_type','PerdevController@personmeetinginside_type')->name('perdev.personmeetinginside_type');
Route::post('person_dev/personmeetinginside_save','PerdevController@personmeetinginside_save')->name('perdev.personmeetinginside_save');
Route::get('person_dev/personmeetinginside_edit/{iduser}/{id}','PerdevController@personmeetinginside_edit')->name('perdev.personmeetinginside_edit');
Route::post('person_dev/personmeetinginside_update','PerdevController@personmeetinginside_update')->name('perdev.personmeetinginside_update');
Route::post('person_dev/personmeetinginside_search/{iduser}','PerdevController@personmeetinginside_search')->name('perdev.personmeetinginside_search');

Route::get('person_dev/addtype','PerdevController@addtype')->name('perdev.addtype');

Route::get('person_dev/personmeetinginside_cancel/{iduser}/{id}','PerdevController@personmeetinginside_cancel')->name('perdev.personmeetinginside_cancel');
Route::post('person_dev/personmeetinginside_updatecancel','PerdevController@personmeetinginside_updatecancel')->name('perdev.personmeetinginside_updatecancel');

Route::get('person_dev/persondevinfo/{iduser}','PerdevController@infouser')->name('perdev.inforuser');
Route::get('person_dev/persondevreport/{iduser}','PerdevController@persondevreport')->name('perdev.persondevreport');
Route::post('person_dev/persondevreport_search/{iduser}','PerdevController@persondevreport_search')->name('perdev.persondevreport_search');
Route::get('person_dev/persondevreport_excel/{iduser}','PerdevController@persondevreport_excel')->name('perdev.persondevreport_excel');

Route::post('person_dev/persondevinfosearch/{iduser}','PerdevController@searchinfo')->name('perdev.searchinfo');
Route::post('person_dev/persondevinfoversearch/{iduser}','PerdevController@searchinfover')->name('perdev.searchinfover');
Route::post('person_dev/persondevinfoappsearch/{iduser}','PerdevController@searchinfoapp')->name('perdev.searchinfoapp');

Route::get('person_dev/persondevpdfgovernment_outside/{id}/{iduser}', 'PerdevController@persondevpdfgovernment_outside')->name('perdev.persondevpdfgovernment_outside'); //ฟร์อม ขอไปนอกสถานที่


Route::get('person_dev/persondevadd/{iduser}','PerdevController@create')->name('perdev.create');
Route::get('person_dev/persondevehicle/','PerdevController@vehicle')->name('perdev.vehicle');
Route::get('person_dev/persondevehicleedit/','PerdevController@vehicle_edit')->name('perdev.vehicle_edit');
Route::get('person_dev/persondevaddlocation/','PerdevController@addlocation')->name('perdev.addlocation');
Route::get('person_dev/persondevaddorg/','PerdevController@addorg')->name('perdev.addorg');
Route::get('person_dev/persondevcheckposition/','PerdevController@checkposition')->name('perdev.checkposition');
Route::get('person_dev/persondevchecklevel/','PerdevController@checklevel')->name('perdev.checklevel');
Route::post('person_dev/persondevehicle/save','PerdevController@save')->name('perdev.save');

Route::get('person_dev/persondevedit/{id}/{iduser}','PerdevController@edit')->name('perdev.edit');
Route::post('person_dev/persondevehicle/update','PerdevController@update')->name('perdev.update');

Route::get('person_dev/persondevaccept/{id}/{iduser}','PerdevController@persondevaccept')->name('perdev.persondevaccept');//บันทึกตอบรับวิทยากร
Route::post('person_dev/persondevaccept_update','PerdevController@persondevaccept_update')->name('perdev.persondevaccept_update');



Route::get('person_dev/persondevpdfallow/{id}/{iduser}','PerdevController@persondevpdfallow')->name('perdev.persondevpdfallow');
Route::get('person_dev/persondevpdfaccept/{id}/{iduser}','PerdevController@persondevpdfaccept')->name('perdev.persondevpdfaccept');

Route::get('person_dev/persondevver/{iduser}','PerdevController@infover')->name('perdev.inforver');
Route::get('person_dev/persondevvercheck/{id}/{iduser}','PerdevController@ver')->name('perdev.ver');
Route::post('person_dev/persondevvercheck/updatever','PerdevController@updatever')->name('perdev.updatever');

Route::get('person_dev/persondevapp/{iduser}','PerdevController@infoapp')->name('perdev.inforapp');
Route::get('person_dev/persondevappcheck/{id}/{iduser}','PerdevController@appove')->name('perdev.app');
Route::post('person_dev/persondevappcheck/updateapp','PerdevController@updateapp')->name('perdev.updateapp');

Route::get('person_dev/persondevcancel/{id}/{iduser}','PerdevController@cancel')->name('perdev.cancel');
Route::post('person_dev/persondevcancel/updatecancel','PerdevController@updatecancel')->name('perdev.updatecancel');

Route::get('person_dev/persondevconclude/{id}/{iduser}','PerdevController@conclude')->name('perdev.conclude');
Route::post('person_dev/persondevconclude/saveconclude','PerdevController@saveconclude')->name('perdev.saveconclude');

Route::get('person_dev/persondevconcludeedit/{id}/{iduser}','PerdevController@editconclude')->name('perdev.editconclude');
Route::post('person_dev/persondevconcludeupdate','PerdevController@updateconclude')->name('perdev.updateconclude');



//===========================ระบบบ้านพัก=======

Route::get('person_guesthouse/guesthouse_info/{iduser}','GuesthousController@guesthouse_info')->name('guest.guesthouse_info');
Route::get('person_guesthouse/guesthouse_infohome/{id}/{iduser}','GuesthousController@guesthouse_infohome')->name('guest.guesthouse_infohome');
Route::get('person_guesthouse/guesthouse_infohotel/{id}/{iduser}','GuesthousController@guesthouse_infohotel')->name('guest.guesthouse_infohotel');

Route::get('person_guesthouse/guesthouse_dashboard/{iduser}','GuesthousController@guesthouse_dashboard')->name('guest.guesthouse_dashboard');


Route::get('person_guesthouse/guesthouse_petition/{iduser}','GuesthousController@guesthouse_petition')->name('guest.guesthouse_petition');
Route::post('person_guesthouse/guesthouse_petitionsearch/{iduser}','GuesthousController@guesthouse_petitionsearch')->name('guest.guesthouse_petitionsearch');

Route::get('person_guesthouse/guesthouse_petition_add/{iduser}','GuesthousController@guesthouse_petition_add')->name('guest.guesthouse_petition_add');//ฟร์อมร้องขอ
Route::post('person_guesthouse/guesthouse_petition_save','GuesthousController@guesthouse_petition_save')->name('guest.guesthouse_petition_save');
Route::get('person_guesthouse/guesthouse_petition_edit/{idref}/{iduser}','GuesthousController@guesthouse_petition_edit')->name('guest.guesthouse_petition_edit');//ฟร์อมแก้ใขร้องขอ
Route::post('person_guesthouse/guesthouse_petition_update','GuesthousController@guesthouse_petition_update')->name('guest.guesthouse_petition_update');


Route::get('person_guesthouse/guesthouse_problem/{iduser}','GuesthousController@guesthouse_problem')->name('guest.guesthouse_problem');
Route::post('person_guesthouse/guesthouse_problemsearch/{iduser}','GuesthousController@guesthouse_problemsearch')->name('guest.guesthouse_problemsearch');

Route::get('person_guesthouse/guesthouse_problem_add/{iduser}','GuesthousController@guesthouse_problem_add')->name('guest.guesthouse_problem_add');//ฟร์อมแจ้งปัญหา
Route::post('person_guesthouse/guesthouse_problem_save','GuesthousController@guesthouse_problem_save')->name('guest.guesthouse_problem_save');
Route::get('person_guesthouse/guesthouse_problem_edit/{idref}/{iduser}','GuesthousController@guesthouse_problem_edit')->name('guest.guesthouse_problem_edit');//ฟร์อมแจ้งปัญหา
Route::post('person_guesthouse/guesthouse_problem_update','GuesthousController@guesthouse_problem_update')->name('guest.guesthouse_problem_update');
Route::get('person_guesthouse/guesthouse_problem_cancel/{idref}/{iduser}','GuesthousController@guesthouse_problem_cancel')->name('guest.guesthouse_problem_cancel');//ฟร์อมยกเลิกปัญหา
Route::post('person_guesthouse/guesthouse_problem_updatecancel','GuesthousController@guesthouse_problem_updatecancel')->name('guest.guesthouse_problem_updatecancel');
//=========================================================================================================
//====================================แผนงานโครงการ======================================================
Route::match(['get','post'],'general_plan/plan_dashboard/{iduser}','PlanController@geninfoplanindex')->name('guest.geninfoplanindex');

Route::match(['get','post'],'general_plan/planwork/{iduser}','PlanController@geninfoplanwork')->name('guest.geninfoplanwork');
Route::get('general_plan/geninfoplanwork_add/{iduser}','PlanController@geninfoplanwork_add')->name('guest.geninfoplanwork_add');
Route::post('general_plan/geninfoplanwork_save','PlanController@geninfoplanwork_save')->name('guest.geninfoplanwork_save');
Route::get('general_plan/geninfoplanwork_edit/{idref}/{iduser}','PlanController@geninfoplanwork_edit')->name('guest.geninfoplanwork_edit');
Route::post('general_plan/geninfoplanwork_update','PlanController@geninfoplanwork_update')->name('guest.geninfoplanwork_update');
Route::get('general_plan/Updatestatus','PlanController@Updatestatus')->name('guest.geninfoplanwork_updatestatus');


Route::get('general_plan/plan_project/{iduser}','PlanController@geninfoplan_project')->name('guest.geninfoplan_project');
Route::post('general_plan/project_search/{iduser}','PlanController@project_search')->name('guest.geninfoproject_search');
Route::get('general_plan/project_add/{iduser}','PlanController@project_add')->name('guest.geninfoproject_add');
Route::post('general_plan/project_save','PlanController@project_save')->name('guest.geninfoproject_save');
Route::get('general_plan/project_edit/{idref}/{iduser}','PlanController@project_edit')->name('guest.geninfoproject_edit');
Route::post('general_plan/project_update','PlanController@project_update')->name('guest.geninfoproject_update');
Route::get('general_plan/project_destroy/{idref}/{iduser}','PlanController@project_destroy')->name('guest.geninfoproject_destroy');


Route::get('general_plan/plan_humandev/{iduser}','PlanController@geninfoplan_humandev')->name('guest.geninfoplan_humandev');
Route::post('general_plan/humandev_search/{iduser}','PlanController@humandev_search')->name('guest.geninfohumandev_search');
Route::get('general_plan/humandev_add/{iduser}','PlanController@humandev_add')->name('guest.geninfohumandev_add');
Route::post('general_plan/humandev_save','PlanController@humandev_save')->name('guest.geninfohumandev_save');
Route::get('general_plan/humandev_edit/{idref}/{iduser}','PlanController@humandev_edit')->name('guest.geninfohumandev_edit');
Route::post('general_plan/humandev_update','PlanController@humandev_update')->name('guest.geninfohumandev_update');
Route::get('general_plan/humandev_destroy/{idref}/{iduser}','PlanController@humandev_destroy')->name('guest.geninfohumandev_destroy');

Route::get('general_plan/plan_durable/{iduser}','PlanController@geninfoplan_durable')->name('guest.geninfoplan_durable');
Route::post('general_plan/durable_search/{iduser}','PlanController@durable_search')->name('guest.geninfodurable_search');
Route::get('general_plan/durable_add/{iduser}','PlanController@durable_add')->name('guest.geninfodurable_add');
Route::post('general_plan/durable_save','PlanController@durable_save')->name('guest.geninfodurable_save');
Route::get('general_plan/durable_edit/{idref}/{iduser}','PlanController@durable_edit')->name('guest.geninfodurable_edit');
Route::post('general_plan/durable_update','PlanController@durable_update')->name('guest.geninfodurable_update');
Route::get('general_plan/durable_destroy/{idref}/{iduser}','PlanController@durable_destroy')->name('guest.geninfodurable_destroy');


Route::get('general_plan/plan_repair/{iduser}','PlanController@geninfoplan_repair')->name('guest.geninfoplan_repair');
Route::post('general_plan/repair_search/{iduser}','PlanController@repair_search')->name('guest.geninforepair_search');
Route::get('general_plan/repair_add/{iduser}','PlanController@repair_add')->name('guest.geninforepair_add');
Route::post('general_plan/repair_save','PlanController@repair_save')->name('guest.geninforepair_save');
Route::get('general_plan/repair_edit/{idref}/{iduser}','PlanController@repair_edit')->name('guest.geninforepair_edit');
Route::post('general_plan/repair_update','PlanController@repair_update')->name('guest.geninforepair_update');
Route::get('general_plan/repair_destroy/{idref}/{iduser}','PlanController@repair_destroy')->name('guest.geninforepair_destroy');



Route::get('general_plan/dropdownplantypeforuser','PlanController@dropdownplantypeforuser')->name('plandropdown.plantypeforuser');

//====================================ระบบสารบรรณ======================================================
Route::get('general_document/genodocdocumententer/{iduser}','DocumentController@enterdoc')->name('document.enterdoc');
Route::get('general_document/genodocdocumententer/control/{id}/{iduser}','DocumentController@infobookenterdoccontrol')->name('document.infobookenterdoccontrol');
Route::post('general_document/genodocdocumententer/send','DocumentController@sendenterdoc')->name('document.sendenterdoc');
Route::post('general_document/genodocdocumententer/saverpresententerdoc','DocumentController@saverpresententerdoc')->name('document.saverpresententerdoc');
Route::post('general_document/genodocdocumententer/saveretireenterdoc','DocumentController@saveretireenterdoc')->name('document.saveretireenterdoc');
Route::post('general_document/genodocdocumententersearch/{iduser}','DocumentController@enterdocsearch')->name('mbook.enterdocsearch');
Route::get('general_document/genodocdocument/checkreadenter','DocumentController@checkreadenter')->name('document.checkreadenter');

Route::get('general_document/gendocumen_updateall/{iduser}','DocumentController@gendocumen_updateall')->name('document.gendocumen_updateall');   /// อ่านหนังสือทั้งหมด

Route::get('general_document/genodocdocumentcom/{iduser}','DocumentController@comdoc')->name('document.comdoc');
Route::get('general_document/genodocdocumentcom/control/{id}/{iduser}','DocumentController@infobookentcomdoccontrol')->name('document.infobookentcomdoccontrol');
Route::post('general_document/genodocdocumentcom/send','DocumentController@sendcomdoc')->name('document.sendcomdoc');
Route::post('general_document/genodocdocumentcom/saverpresentcomdoc','DocumentController@saverpresentcomdoc')->name('document.saverpresentcomdoc');
Route::post('general_document/genodocdocumentcom/saveretirecomdoc','DocumentController@saveretirecomdoc')->name('document.saveretirecomdoc');
Route::post('general_document/genodocdocumentcomsearch/{iduser}','DocumentController@comdocsearch')->name('mbook.comdocsearch');
Route::get('general_document/genodocdocument/checkreadcomdoc','DocumentController@checkreadcomdoc')->name('document.checkreadcomdoc');

Route::get('general_document/genodocdocumentinside/{iduser}','DocumentController@insidedoc')->name('document.insidedoc');
Route::get('general_document/genodocdocumentinside/control/{id}/{iduser}','DocumentController@infobookentinsidecontrol')->name('document.infobookentinsidecontrol');
Route::post('general_document/genodocdocumentinside/send','DocumentController@sendinside')->name('document.sendinside');
Route::post('general_document/genodocdocumentinside/saverpresentinside','DocumentController@saverpresentinside')->name('document.saverpresentinside');
Route::post('general_document/genodocdocumentinside/saveretireinside','DocumentController@saveretireinside')->name('document.saveretireinside');
Route::post('general_document/genodocdocumentinsidesearch/{iduser}','DocumentController@insidedocsearch')->name('mbook.insidedocsearch');
Route::get('general_document/genodocdocument/checkreadinside','DocumentController@checkreadinside')->name('document.checkreadinside');

Route::get('general_document/genodocdocumentannounce/{iduser}','DocumentController@announcedoc')->name('document.announcedoc');
Route::get('general_document/genodocdocumentannounce/control/{id}/{iduser}','DocumentController@infobookentannouncecontrol')->name('document.infobookentannouncecontrol');
Route::post('general_document/genodocdocumentannounce/send','DocumentController@sendannounce')->name('document.sendannounce');
Route::post('general_document/genodocdocumentannounce/saverpresentannounce','DocumentController@saverpresentannounce')->name('document.saverpresentannounce');
Route::post('general_document/genodocdocumentannounce/saveretireannounce','DocumentController@saveretireannounce')->name('document.saveretireannounce');
Route::post('general_document/genodocdocumentannouncesearch/{iduser}','DocumentController@announcedocsearch')->name('document.announcedocsearch');
Route::get('general_document/genodocdocument/checkreadannounce','DocumentController@checkreadannounce')->name('document.checkreadannounce');

Route::get('general_document/genodocdocument/departmentrow3','DocumentController@departmentrow3')->name('document.departmentrow3');
Route::get('general_document/genodocdocument/departmentrow4','DocumentController@departmentrow4')->name('document.departmentrow4');
Route::get('general_document/genodocdocument/departmentrow5','DocumentController@departmentrow5')->name('document.departmentrow5');


//----------------------ฟังชั่นความเห็น ผอ.
Route::get('general_document/checkcomment','DocumentController@checkcomment')->name('document.checkcomment');
//====================================ระบบจองห้องประชุม======================================================
Route::get('general_meet/genmeetindex/{iduser}','MeetingController@infoindex')->name('meeting.inforindex');
Route::get('general_meet/carcalendar/detail','MeetingController@deatailcalendar')->name('meeting.deatailcalendar');

Route::get('general_meet/genmeetroom/{iduser}','MeetingController@inforoom')->name('meeting.inforroom');
Route::get('general_meet/genmeetroomadd/{idroom}/{iduser}','MeetingController@create')->name('meeting.create');
Route::post('general_meet/genmeetroom/save','MeetingController@save')->name('meeting.save');

Route::get('general_meet/checkroom','MeetingController@checkroom')->name('meeting.checkroom');

Route::get('general_meet/genmeetroomedit/{id}/{iduser}','MeetingController@edit')->name('meeting.edit');
Route::post('general_meet/genmeetroomedit/updateedit','MeetingController@updateedit')->name('meeting.updateedit');

Route::get('general_meet/genmeetroominfo/{iduser}','MeetingController@infobook')->name('meeting.infobook');
Route::post('general_meet/genmeetroominfosearch/{iduser}','MeetingController@search')->name('meeting.searchinfo');

Route::get('general_meet/genmeetroominfoinform/{id}/{iduser}','MeetingController@inform')->name('meeting.inform');
Route::post('general_meet/genmeetroominfoinform/updateinform','MeetingController@updateinform')->name('meeting.updateinform');

Route::get('general_meet/genmeetroomver/{iduser}','MeetingController@infover')->name('meeting.infover');
Route::get('general_meet/genmeetroomvercheck/{id}/{iduser}','MeetingController@ver')->name('meeting.ver');
Route::post('general_meet/genmeetroomvercheck/updatever','MeetingController@updatever')->name('meeting.updatever');
Route::post('general_meet/genmeetroominfosearchver/{iduser}','MeetingController@searchver')->name('meeting.searchver');

Route::get('general_meet/genmeetroomvercancel/{id}/{iduser}','MeetingController@cancel')->name('meeting.cancel');
Route::post('general_meet/genmeetroomvercancel/updatecancel','MeetingController@updatecancel')->name('meeting.updatecancel');

Route::get('general_meet/genmeetroominfocallcheckdate','MeetingController@callcheckdate')->name('meeting.callcheckdate');


//=========================================================================================================

//====================================ระบบยานพาหนะ======================================================
Route::get('general_car/gencarindex/{iduser}','CarController@infoindex')->name('car.inforindex');
Route::get('general_car/gencartype/{iduser}','CarController@infotype')->name('car.infortype');


Route::match(['get','post'],'general_car/gencarinfonomal/{iduser}','CarController@infonomal')->name('car.infonomal');

// Route::post('general_car/gencarnomalsearch/{iduser}','CarController@infocarnomalsearch')->name('car.infocarnomalsearch');

Route::get('general_car/gencarnomaladd/{iduser}','CarController@createcarnomal')->name('car.createcarnomal');
Route::get('general_car/gencarnomalselectcar','CarController@selectcarno')->name('car.selectcarno');
Route::post('general_car/gencarnomalsave','CarController@carnomalsave')->name('car.carnomalsave');
Route::post('general_car/gencarnomalsavefan','CarController@carfansave')->name('car.savefan');






Route::get('general_car/gencarnomaledit/{id}/{iduser}','CarController@editcarnomal')->name('car.editcarnomal');
Route::post('general_car/gencarnomalupdate','CarController@carnomalupdate')->name('car.carnomalupdate');

Route::get('general_car/gencarnomalcancel/{id}/{iduser}','CarController@cancelnomal')->name('car.cancelnomal');
Route::post('general_car/gencarnomalcancel/updatecancel','CarController@updatecancelnomal')->name('car.updatecancelnomal');


// Route::match(['get','post'],'general_car/gencarinforefer/{iduser}','CarController@inforefer')->name('car.inforefer');
Route::match(['get','post'],'general_car/gencarinforefer/{iduser}','CarController@inforefer')->name('car.inforefer');
Route::get('general_car/gencarreferadd/{iduser}','CarController@createcarrefer')->name('car.createcarrefer');
Route::post('general_car/gencarrefersave','CarController@carrefersave')->name('car.carrefersave');
Route::post('general_car/gencarrefersearch/{iduser}','CarController@infocarrefersearch')->name('car.infocarrefersearch');


Route::get('general_car/gencarreferselectcar','CarController@selectcarrefer')->name('car.selectcarrefer');

Route::get('general_car/gencarreferselectbookname','CarController@selectbookname')->name('car.selectbookname');
Route::get('general_car/gencarreferselectbooknum','CarController@selectbooknum')->name('car.selectbooknum');
Route::get('general_car/gencarreferselectbookdate','CarController@selectbookdate')->name('car.selectbookdate');


Route::get('general_car/gencarreferedit/{id}/{iduser}','CarController@editcarrefer')->name('car.editcarrefer');
Route::post('general_car/gencarreferupdate','CarController@carreferupdate')->name('car.carreferupdate');

Route::get('general_car/gencarrefercancel/{id}/{iduser}','CarController@cancelrefer')->name('car.cancelrefer');
Route::post('general_car/gencarrefercancel/updatecancel','CarController@updatecancelrefer')->name('car.updatecancelrefer');

Route::get('general_car/gencarnomaldetailcar','CarController@detailcar')->name('car.detailcar');

Route::get('general_car/gencarnomallocation','CarController@addorglocation')->name('car.addorglocation');

Route::get('general_car/gencarcallcheckdate','CarController@carcallcheckdate')->name('car.carcallcheckdate');

//==================ไฟล์ PDF 

Route::get('general_car/gencarnomallocation/export_pdf3/{id}/{iduser}', 'CarController@pdf3')->name('car.pdf3');

//==================================งานบริหารทรัพย์สิน=======================================================================
Route::get('general_asset/dashboard/{iduser}','AssetController@dashboard')->name('asset.dashboard');
Route::post('general_asset/dashboardsearch/{iduser}','AssetController@dashboardsearch')->name('asset.dashboardsearch');

Route::get('general_asset/genassetindex/{iduser}','AssetController@infoindex')->name('asset.inforindex');
Route::post('general_asset/genassetindexsearch/{iduser}','AssetController@infoindexsearch')->name('asset.infoindexsearch');

Route::get('general_asset/inforeceive/{iduser}/{id}','AssetController@inforeceive')->name('asset.inforeceive');

Route::get('general_asset/infoasset/{iduser}/{id}','AssetController@infoasset')->name('asset.infoasset');
//--------การเบิกจ่าย
Route::get('general_asset/genassetdisburseindex/{iduser}','AssetController@infodisburseindex')->name('asset.infodisburseindex');
Route::post('general_asset/genassetdisburseindexsearch/{iduser}','AssetController@infodisburseindexsearch')->name('asset.infodisburseindexsearch');

Route::get('general_asset/genassetdisburseadd/{iduser}','AssetController@infodisburseadd')->name('asset.infodisburseadd');
Route::post('general_asset/genassetdisburseindexsave','AssetController@infodisbursesave')->name('asset.infodisbursesave');
//---------------------------------------
Route::get('general_asset/checkunitname','AssetController@checkunitname')->name('asset.checkunitname');
Route::get('general_asset/checkpice','AssetController@checkpice')->name('asset.checkpice');
//-------------------------------------------

Route::get('general_asset/genassetdisburseindexdetail/{iduser}/{id}','AssetController@detaildisburseindex')->name('asset.detaildisburseindex');//---รายละเอียด

Route::get('general_asset/genassetdisburseedit/{iduser}/{id}','AssetController@infodisburseedit')->name('asset.infodisburseedit');//---แก้ไข
Route::post('general_asset/genassetdisburseindexupdate','AssetController@infodisburseupdate')->name('asset.infodisburseupdate');

Route::get('general_asset/genassetdisburseindexcancel/{iduser}/{id}','AssetController@canceldisburseindex')->name('asset.canceldisburseindex');//---แจ้งยกเลิก
Route::post('general_asset/genassetdisburseindexcancelupdate','AssetController@canceldisburseindexupdate')->name('asset.canceldisburseindexupdate');



Route::get('general_asset/genassetdisbursepdf/export_pdfdisburse/{id}', 'AssetController@pdfdisburse')->name('asset.pdfdisburse');//---พิมพ์ใบเบิก

//-------ยืมคืน
Route::get('general_asset/infolendindex/{iduser}','AssetController@infolendindex')->name('asset.infolendindex');
Route::post('general_asset/infolendindexsearch/{iduser}','AssetController@infolendindexsearch')->name('asset.infolendindexsearch');


Route::get('general_asset/infolendindexdetail/{iduser}/{id}','AssetController@detaillendindex')->name('asset.detaillendindex');//---รายละเอียด

Route::get('general_asset/infolendindexedit/{iduser}/{id}','AssetController@infolendedit')->name('asset.infolendedit');//---แก้ไข
Route::post('general_asset/infolendindexupdate','AssetController@infolendupdate')->name('asset.infolendupdate');

Route::get('general_asset/infolendindexcancel/{iduser}/{id}','AssetController@cancelinfolendindex')->name('asset.cancelinfolendindex');//---แจ้งยกเลิก
Route::post('general_asset/infolendindexcancelupdate','AssetController@cancelinfolendupdate')->name('asset.cancelinfolendupdate');



//Route::get('general_asset/infolendselectdep/{iduser}','AssetController@infolendindexselectdep')->name('asset.infolendindexselectdep');//เลือกหน่วยงาน
Route::post('general_asset/infolendsenddep/{iduser}','AssetController@infolendindexsenddep')->name('asset.infolendindexsenddep');
Route::post('general_asset/infolendsenddepsave','AssetController@infolendsenddepsave')->name('asset.infolendsenddepsave');
//Route::get('general_asset/infolendadd/{iduser}','AssetController@infolendadd')->name('asset.infolendadd');



//-------ถูกยืม
Route::get('general_asset/infogiveindex/{iduser}','AssetController@infogiveindex')->name('asset.infogiveindex');
Route::post('general_asset/infogiveindexsearch/{iduser}','AssetController@infogiveindexsearch')->name('asset.infogiveindexsearch');

Route::get('general_asset/infogiveapp/{iduser}/{id}','AssetController@infogiveapp')->name('asset.infogiveapp');
Route::post('general_asset/infogiveapp/updategiveapp','AssetController@updategiveapp')->name('asset.updategiveapp');
Route::get('general_asset/infogiveapp/destroy/{iduser}/{id}/{idlist}','AssetController@giveappdestroy');



//==================================งานบริหารพัสดุ=======================================================================
Route::get('general_suplies/dashboard/{iduser}','SupliesController@dashboard')->name('suplies.dashboard');
Route::post('general_suplies/dashboardsearch/{iduser}','SupliesController@dashboardsearch')->name('supplies.dashboardsearch');

//-------ขอซื้อขอจ้าง
Route::get('general_suplies/inforequest/{iduser}','SupliesController@inforequest')->name('suplies.inforequest');
Route::post('general_suplies/inforequestsearch/{iduser}','SupliesController@inforequestsearch')->name('suplies.inforequestsearch');////////16.12.62=========
//-------------------P'ดิษฐ์
Route::get('general_suplies/inforequestindexdetail/{id}/{iduser}','SupliesController@detailrequestindex')->name('suplies.detailrequestindex');//---รายละเอียด

Route::get('general_suplies/inforequestedit/{id}/{iduser}','SupliesController@inforequestedit')->name('suplies.inforequestedit');//---แก้ไข
Route::post('general_suplies/inforequestindexupdate','SupliesController@inforequestupdate')->name('suplies.inforequestupdate');

Route::get('general_suplies/inforequestindexcancel/{id}/{iduser}','SupliesController@cancelrequestindex')->name('suplies.cancelrequestindex');//---แจ้งยกเลิก
Route::post('general_suplies/inforequestindexcancelupdate','SupliesController@cancelrequestindexupdate')->name('suplies.cancelrequestindexupdate');
//-------------------
Route::get('general_suplies/selectsup','SupliesController@selectsup')->name('suplies.selectsup');
Route::get('general_suplies/inforequestadd/{iduser}','SupliesController@inforequestadd')->name('suplies.inforequestadd');
Route::post('general_suplies/inforequestsave','SupliesController@saveinforequest')->name('suplies.saveinforequest');
Route::get('general_suplies/inforequestchecksummoney','SupliesController@checksummoney')->name('suplies.checksummoney');
Route::get('general_suplies/supselect','SupliesController@supselect')->name('suplies.supselect');
Route::get('general_suplies/supre','SupliesController@supre')->name('suplies.supre');
Route::get('general_suplies/suptpu','SupliesController@suptpu')->name('suplies.suptpu');
Route::get('general_suplies/supunitname','SupliesController@supunitname')->name('suplies.supunitname');

Route::get('general_suplies/fetchdetail','SupliesController@fetchdetail')->name('suplies.fetchdetail');

Route::get('general_suplies/inforequestapp/{iduser}','SupliesController@inforequestapp')->name('suplies.inforequestadd');
Route::post('general_suplies/inforequestappsearch/{iduser}','SupliesController@inforequestappsearch')->name('suplies.inforequestappsearch');//////16.12.62=======
Route::post('general_suplies/inforequestappupdate','SupliesController@updateinforequestapp')->name('suplies.updateinforequestapp');

Route::get('general_suplies/inforequestver/{iduser}','SupliesController@inforequestver')->name('suplies.inforequestver');
Route::post('general_suplies/inforequestverupdate','SupliesController@updateinforequestver')->name('suplies.updateinforequestver');

Route::get('general_suplies/inforequestlastapp/{iduser}','SupliesController@inforequestlastapp')->name('suplies.inforequestlastapp');
Route::post('general_suplies/inforequestlastappsearch/{iduser}','SupliesController@inforequestlastappsearch')->name('suplies.inforequestlastappsearch');//////16.12.62========-
Route::post('general_suplies/inforequestlastappupdate','SupliesController@updateinforequestlastapp')->name('suplies.updateinforequestlastapp');

Route::get('general_suplies/detailapp','SupliesController@detailapp')->name('suplies.detailapp');


//-------ขอเบิกครุภัณฑ์
Route::get('general_suplies/infowithdrawarticleindex/{iduser}','SupliesController@infowithdrawarticleindex')->name('suplies.infowithdrawarticleindex');




//====================================ระบบแจ้งซ่อม======================================================
Route::get('general_repair/genrepairindex/{iduser}','RepairController@infoindex')->name('repair.inforindex');
Route::get('general_repair/genrepairtype/{iduser}','RepairController@infotype')->name('repair.infortype');
//---------ซ่อมทั่วไป
Route::get('general_repair/genrepairnomal/{iduser}','RepairController@inforepairnomal')->name('repair.inforepairnomal');
Route::post('general_repair/genrepairnomalsearch/{iduser}','RepairController@inforepairnomalsearch')->name('repair.inforepairnomalsearch');


Route::get('general_repair/genrepairnomaladd/{iduser}','RepairController@createinforepairnomal')->name('repair.createinforepairnomal');
Route::post('general_repair/genrepairnomaladd/save','RepairController@saveinforepairnomal')->name('repair.saveinforepairnomal');

Route::post('general_repair/genrepairnomalsavefan','RepairController@repairnomalfansave')->name('repair.repairnomalfansave');

Route::get('general_repair/genrepairnomal/edit/{id}/{iduser}','RepairController@editinforepairnomal')->name('repair.editinforepairnomal');
Route::post('general_repair/genrepairnomalupdate/update','RepairController@updateinforepairnomal')->name('repair.updateinforepairnomal');

Route::get('general_repair/genrepairnomalcancel/{id}/{iduser}','RepairController@cancel')->name('repair.cancel');
Route::post('general_repair/genrepairnomalcancel/updatecancel','RepairController@updatecancel')->name('repair.updatecancel');

Route::get('general_repair/genrepairnomaldetailrepairnomal','RepairController@detailrepairnomal')->name('repair.detailrepairnoma');


Route::get('general_repair/repairnomal','RepairController@repairnomal')->name('dropdown.repairnomal');
Route::get('general_repair/repairnomalsub','RepairController@repairnomalsub')->name('dropdown.repairnomalsub');
Route::get('general_repair/repairnomalasset','RepairController@repairnomalasset')->name('dropdown.repairnomalasset');
Route::get('general_repair/repairnomaladdother/','RepairController@addother')->name('repair.addother');

//---------ซ่อมคอม
Route::get('general_repair/genrepaircom/{iduser}','RepairController@inforepaircom')->name('repair.inforepaircom');
Route::get('general_repair/genrepaircomdetailrepaircom','RepairController@detailrepaircom')->name('repair.detailrepaircom');
Route::post('general_repair/genrepaircomsearch/{iduser}','RepairController@inforepaircomsearch')->name('repair.inforepaircomsearch');

Route::get('general_repair/genrepaircomadd/{iduser}','RepairController@createinforepaircom')->name('repair.createinforepaircom');
Route::post('general_repair/genrepaircomadd/save','RepairController@saveinforepaircom')->name('repair.saveinforepaircom');

Route::post('general_repair/genrepaircomsavefan','RepairController@repaircomfansave')->name('repair.repaircomfansave');

Route::get('general_repair/genrepaircom/edit/{id}/{iduser}','RepairController@editinforepaircom')->name('repair.editinforepaircom');
Route::post('general_repair/genrepaircomupdate/update','RepairController@updateinforepaircom')->name('repair.updateinforepaircom');

Route::get('general_repair/genrepaircomcancel/{id}/{iduser}','RepairController@cancelcom')->name('repair.cancelcom');
Route::post('general_repair/genrepaircomcancel/updatecancel','RepairController@updatecancelcom')->name('repair.updatecancelcom');

//---------ซ่อมเครื่องมือแพทย์
Route::get('general_repair/genrepairmedical/{iduser}','RepairController@inforepairmedical')->name('repair.inforepairmedical');
Route::get('general_repair/genrepairmedicalcommedical','RepairController@detailrepairmedical')->name('repair.detailrepairmedical');
Route::post('general_repair/genrepairmedicalsearch/{iduser}','RepairController@inforepairmedicalsearch')->name('repair.inforepairmedicalsearch');

Route::get('general_repair/genrepairmedicaladd/{iduser}','RepairController@createinforepairmedical')->name('repair.createinforepairmedical');
Route::post('general_repair/genrepairmedicaladd/save','RepairController@saveinforepairmedical')->name('repair.saveinforepairmedical');

Route::post('general_repair/genrepairmedicalsavefan','RepairController@repairmedicalfansave')->name('repair.repairmedicalfansave');

Route::get('general_repair/genrepairmedical/edit/{id}/{iduser}','RepairController@editinforepairmedical')->name('repair.editinforepairmedical');
Route::post('general_repair/genrepairmedicalupdate/update','RepairController@updateinforepairmedical')->name('repair.updateinforepairmedical');

Route::get('general_repair/genrepairmedicalcancel/{id}/{iduser}','RepairController@cancelmedical')->name('repair.cancelmedical');
Route::post('general_repair/genrepairmedicalcancel/updatecancel','RepairController@updatecancelmedical')->name('repair.updatecancelmedical');

//====================================ระบบตารางเวร======================================================
Route::get('general_operate/genoperateindex/{iduser}','OperateController@infoindex')->name('operate.inforindex');
Route::post('general_operate/infoindexsearch/{iduser}','OperateController@infoindexsearch')->name('operate.infoindexsearch');
Route::get('general_operate/genoperateindexadd/{iduser}','OperateController@createoperate')->name('operate.createoperate');
Route::get('general_operate/genoperateindexset/{iduser}','OperateController@setoperate')->name('operate.setoperate');
Route::get('general_operate/genoperateindexsetactivity/{idactivity}/{iduser}','OperateController@setactivity')->name('operate.setactivity');
Route::post('general_operate/genoperateindex/save','OperateController@saveoperate')->name('operate.save');
Route::post('general_operate/genoperateindex/updateactivity','OperateController@updateactivity')->name('operate.updateactivity');

//ฟร์อมแลกเวร
Route::get('general_operate/infogenoperatechange_pdf/{idref}','OperateController@infogenoperatechange_pdf')->name('operate.infogenoperatechange_pdf'); // ใบแลกเวร
Route::get('general_operate/infogenoperateswap_pdf/{idref}','OperateController@infogenoperateswap_pdf')->name('operate.infogenoperateswap_pdf'); // ใบเปลี่ยนเวร

//====================================ระบบOT=====================
Route::get('general_ot/geotindex/{iduser}','OtController@geotindex')->name('ot.iduser');
Route::get('general_ot/geotsetdetail_add/{iduser}','OtController@geotsetdetail_add')->name('ot.geotsetdetail_add');
Route::post('general_ot/geotsetdetail_save','OtController@geotsetdetail_save')->name('ot.geotsetdetail_save');
Route::get('general_ot/geotsetdetail_edit/{idref}/{iduser}','OtController@geotsetdetail_edit')->name('ot.geotsetdetail_edit');
Route::post('general_ot/geotsetdetail_update','OtController@geotsetdetail_update')->name('ot.geotsetdetail_update');

Route::get('general_ot/geotselectoper','OtController@selectoper')->name('ot.selectoper');
Route::get('general_ot/detailoperofot','OtController@detailoperofot')->name('ot.detailoperofot');

Route::get('general_ot/geotsetdetail_app/{idref}/{iduser}','OtController@geotsetdetail_app')->name('ot.geotsetdetail_app');

Route::get('general_ot/geot_savemessage_pdf/{idref}/{iduser}','OtController@geot_savemessage_pdf')->name('ot.geot_savemessage_pdf');


Route::get('general_ot/geotsetdetail_com/{idref}/{iduser}', 'OtController@geotsetdetail_com')->name('ot.geotsetdetail_com');
Route::post('general_ot/geotsetdetail_comupdate/', 'OtController@geotsetdetail_comupdate')->name('ot.geotsetdetail_comupdate');
Route::get('general_ot/pdfcommand_1/{idref}', 'OtController@pdfcommand_1')->name('ot.pdfcommand_1');//ประกาศคำสั่ง
Route::get('general_ot/pdfcommand_2/{idref}', 'OtController@pdfcommand_2')->name('ot.pdfcommand_2');
Route::get('general_ot/pdfpersonwork/{idref}', 'OtController@pdfpersonwork')->name('ot.pdfpersonwork');

Route::get('general_ot/otexcel/{idref}/{iduser}','OtController@otexcel')->name('ot.otexcel');
//===============================แลกเวร ======================================================
Route::get('general_operate/genoperateswap/{iduser}','OperateController@genoperateswap')->name('operate.genoperateswap');
Route::get('general_operate/genoperateswap_add/{iduser}','OperateController@genoperateswap_add')->name('operate.genoperateswap_add');
Route::post('general_operate/genoperateswap_save','OperateController@genoperateswap_save')->name('operate.genoperateswap_save');
Route::get('general_operate/genoperateswap_edit/{iduser}','OperateController@genoperateswap_edit')->name('operate.genoperateswap_edit');
Route::post('general_operate/genoperateswap_update','OperateController@genoperateswap_update')->name('operate.genoperateswap_update');

Route::get('general_operate/genoperatetrade/{iduser}','OperateController@genoperatetrade')->name('operate.genoperatetrade');
Route::get('general_operate/genoperatetrade_add/{iduser}','OperateController@genoperatetrade_add')->name('operate.genoperatetrade_add');
Route::post('general_operate/genoperatetrade_save','OperateController@genoperatetrade_save')->name('operate.genoperatetrade_save');
Route::get('general_operate/genoperatetrade_edit/{idref}/{iduser}','OperateController@genoperatetrade_edit')->name('operate.genoperatetrade_edit');
Route::post('general_operate/genoperatetrade_update','OperateController@genoperatetrade_update')->name('operate.genoperatetrade_update');
Route::get('general_operate/genoperatetrade_destroy/{idref}/{iduser}','OperateController@genoperatetrade_destroy')->name('operate.genoperatetrade_destroy');
Route::get('general_operate/genoperatetrade_cancel/{idref}/{iduser}','OperateController@genoperatetrade_cancel')->name('operate.genoperatetrade_cancel');
Route::post('general_operate/genoperatetrade_updatecancel','OperateController@genoperatetrade_updatecancel')->name('operate.genoperatetrade_updatecancel');

Route::post('general_operate/genoperatetrade_search/{iduser}','OperateController@genoperatetrade_search')->name('operate.genoperatetrade_search');
Route::get('general_operate/genoperatetrade_pdf/{idref}/{iduser}','OperateController@genoperatetrade_pdf')->name('operate.genoperatetrade_pdf');
///////----พึ่งเพิ่ม 25.9.2562
Route::post('general_operate/genoperatesearch/{iduser}','OperateController@operatesearch')->name('operate.operatesearch');
//////


Route::get('general_operate/genoperateindexedit/{id}/{iduser}','OperateController@editoperate')->name('operate.editoperate');
Route::post('general_operate/genoperateindex/updateoperate','OperateController@updateoperate')->name('operate.updateoperate');

Route::get('general_operate/checkpositionver1','OperateController@checkpositionver1')->name('operate.checkposition1');
Route::get('general_operate/checkpositionver2','OperateController@checkpositionver2')->name('operate.checkposition2');
Route::get('general_operate/member','OperateController@member')->name('operate.member');
Route::get('general_operate/checkposition','OperateController@checkposition')->name('operate.checkposition');

Route::get('general_operate/genoperateindexver/{iduser}','OperateController@veroperate')->name('operate.veroperate');
Route::get('general_operate/genoperateindexvercheck/{id}/{iduser}','OperateController@vercheckoperate')->name('operate.vercheckoperate');
Route::post('general_operate/genoperateindexvercheck/updatever','OperateController@updatever')->name('operate.updatever');

//*----------------------26.5.2562-----------*/
Route::post('general_operate/genoperateversearch/{iduser}','OperateController@operateversearch')->name('operate.operateversearch');

Route::get('general_operate/genoperateindexapp/{iduser}','OperateController@appoperate')->name('operate.appoperate');
Route::get('general_operate/genoperateindexappcheck/{id}/{iduser}','OperateController@appcheckoperate')->name('operate.appcheckoperate');
Route::post('general_operate/genoperateindexappcheck/updatever','OperateController@updateapp')->name('operate.updateapp');
//*----------------------26.5.2562----2-------*/
Route::post('general_operate/genoperateappsearch/{iduser}','OperateController@operateappsearch')->name('operate.operateappsearch');


Route::get('general_operate/genoperateindexexcelactivity/{idactivity}/{iduser}','OperateController@excelactivity')->name('operate.excelactivity');
Route::get('general_operate/genoperateindexexcelactivityot/{idactivity}/{iduser}','OperateController@excelactivity_ot')->name('operate.excelactivityot');


Route::get('general_operate/genoperateindexinform/{id}/{iduser}','OperateController@inform')->name('operate.inform');
Route::post('general_operate/genoperateindexinform/updateinform','OperateController@updateinform')->name('operate.updateinform');

Route::get('general_operate/genoperateindexcancel/{id}/{iduser}','OperateController@cancel')->name('operate.cancel');
Route::post('general_operate/genoperateindexcancel/updatecancel','OperateController@updatecancel')->name('operate.updatecancel');


Route::get('general_operate/geot_savemessage_pdf/{idref}/{iduser}','OperateController@geot_savemessage_pdf')->name('operate.geot_savemessage_pdf');
Route::get('general_operate/operatesignature_excel/{idref}/{iduser}','OperateController@operatesignature_excel')->name('operate.operatesignature_excel');

Route::get('general_operate/geotsetdetail_com/{idref}/{iduser}', 'OperateController@geotsetdetail_com')->name('operate.geotsetdetail_com');
Route::post('general_operate/geotsetdetail_comupdate/', 'OperateController@geotsetdetail_comupdate')->name('operate.geotsetdetail_comupdate');
Route::get('general_operate/pdfcommand_1/{idref}', 'OperateController@pdfcommand_1')->name('operate.pdfcommand_1');//ประกาศคำสั่ง
Route::get('general_operate/pdfcommand_2/{idref}', 'OperateController@pdfcommand_2')->name('operate.pdfcommand_2');
Route::get('general_operate/pdfpersonwork/{idref}', 'OperateController@pdfpersonwork')->name('operate.pdfpersonwork');

//===============รายงายตารางเวรแบบรวม

Route::get('general_operate/genoperateindex_allreport/{year}/{month}/{iduser}','OperateController@genoperateindex_allreport')->name('operate.genoperateindex_allreport');

//===============================งานซักฟอก===============================

Route::match(['get','post'],'general_launder/dashboard_launder/{iduser}',[LaunderController::class,'dashboard_launder'])->name('gen_launder.dashboard_launder');//
Route::get('general_launder/stockcard_launder/{iduser}','LaunderController@stockcard_launder')->name('gen_launder.stockcard_launder');
Route::post('general_launder/stockcard_laundersearch/{iduser}','LaunderController@stockcard_laundersearch')->name('gen_launder.stockcard_laundersearch');
Route::get('general_launder/stockcard_launder_sub/{idtype}/{iddep}/{iduser}','LaunderController@stockcard_launder_sub')->name('gen_launder.stockcard_launder_sub');

Route::get('general_launder/withdraw_launder/{iduser}','LaunderController@withdraw_launder')->name('gen_launder.withdraw_launder');
Route::get('general_launder/withdrowlaunder_add/{iduser}','LaunderController@withdrowlaunder_add')->name('gen_launder.withdrowlaunder_add');
Route::post('general_launder/withdrowlaunder_save','LaunderController@withdrowlaunder_save')->name('gen_launder.withdrowlaunder_save');
Route::post('general_launder/withdrowlaundersearch/{iduser}','LaunderController@withdrowlaundersearch')->name('gen_launder.withdrowlaundersearch');

Route::get('general_launder/pay_launder/{iduser}','LaunderController@pay_launder')->name('gen_launder.pay_launder');
Route::get('general_launder/pay_launder_add/{iduser}','LaunderController@pay_launder_add')->name('gen_launder.pay_launder_add');
Route::post('general_launder/pay_launder_save','LaunderController@pay_launder_save')->name('gen_launder.pay_launder_save');
Route::post('general_launder/pay_laundersearch/{iduser}','LaunderController@pay_laundersearch')->name('gen_launder.pay_laundersearch');

//-------รายละเอียดขอเบิกผ้า
Route::get('general_launder/detaillaunder','LaunderController@detaillaunder')->name('gen_launder.detaillaunder');
//-------รายละเอียดคลังย่อย
Route::get('general_launder/detail_launder_checktreasury','LaunderController@detail_launder_checktreasury')->name('gen_launder.detail_launder_checktreasury');

//====================================งานพัสดุ===2.10.2562===================================================

//===============================งานจ่ายกลาง===============================
Route::match(['get','post'],'general_cpay/dashboard/{iduser}','CpayController@dashboard')->name('gencpay.dashboard');
Route::get('general_cpay/quota_equpment/{iduser}','CpayController@quota_equpment')->name('gencpay.quota_equpment');

Route::get('general_mpay/dashboard_mpay/{iduser}','MpayController@dashboard_mpay')->name('gen_mpay.dashboard_mpay');

Route::get('general_mpay/stockcard_mpay/{iduser}','MpayController@stockcard_mpay')->name('gen_mpay.stockcard_mpay');

Route::get('general_mpay/withdraw_mpay','MpayController@withdraw_mpay')->name('gen_mpay.withdraw_mpay');
Route::post('general_mpay/withdraw_mpay_search/{iduser}','MpayController@withdraw_mpay_search')->name('gen_mpay.withdraw_mpay_search');

Route::get('general_mpay/withdrowmpay_add/{iduser}','MpayController@withdrowmpay_add')->name('gen_mpay.withdrowmpay_add');
Route::post('general_mpay/withdrowmpay_save','MpayController@withdrowmpay_save')->name('gen_mpay.withdrowmpay_save');
Route::get('general_mpay/withdrowmpay_edit/{id}/{iduser}','MpayController@withdrowmpay_edit')->name('gen_mpay.withdrowmpay_edit');
Route::post('general_mpay/withdrowmpay_update','MpayController@withdrowmpay_update')->name('gen_mpay.withdrowmpay_update');
Route::get('general_mpay/withdrowmpay_destroy/{id}/{iduser}','MpayController@withdrowmpay_destroy')->name('gen_mpay.withdrowmpay_destroy');

Route::get('general_mpay/pay_mpay/{iduser}','MpayController@pay_mpay')->name('gen_mpay.pay_mpay');
Route::post('general_mpay/pay_mpay_search/{iduser}','MpayController@pay_mpay_search')->name('gen_mpay.pay_mpay_search');
Route::get('general_mpay/paympay_add/{iduser}','MpayController@paympay_add')->name('gen_mpay.paympay_add');
Route::post('general_mpay/paympay_save','MpayController@paympay_save')->name('gen_mpay.paympay_save');
Route::get('general_mpay/paympay_edit/{id}/{iduser}','MpayController@paympay_edit')->name('gen_mpay.paympay_edit');
Route::post('general_mpay/paympay_update','MpayController@paympay_update')->name('gen_mpay.paympay_update');
Route::get('general_mpay/paympay_destroy/{id}/{iduser}','MpayController@paympay_destroy')->name('gen_mpay.paympay_destroy');

Route::get('general_mpay/return_mpay/{iduser}','MpayController@return_mpay')->name('gen_mpay.return_mpay');
Route::get('general_mpay/returnmpay_add/{iduser}','MpayController@returnmpay_add')->name('gen_mpay.returnmpay_add');
Route::post('general_mpay/returnmpay_save','MpayController@returnmpay_save')->name('gen_mpay.returnmpay_save');

//====================================ระบบความเสี่ยง======================================================
/** แท็บ Dashboard*/
Route::get('general_risk/dashboard_risk/{iduser}','RiskController@dashboard_risk')->name('gen_risk.dashboard_risk');
/** จบแท็บ Dashboard*/

/** แท็บบันทึกความเสี่ยง */
Route::get('general_risk/risk_notify/{iduser}','RiskController@risk_notify')->name('gen_risk.risk_notify');
Route::get('general_risk/risk_notify_add/{iduser}','RiskController@risk_notify_add')->name('gen_risk.risk_notify_add');
Route::post('general_risk/risk_notify_save','RiskController@risk_notify_save')->name('gen_risk.risk_notify_save');
Route::get('general_risk/risk_notify_edit/{id}/{iduser}','RiskController@risk_notify_edit')->name('gen_risk.risk_notify_edit');
Route::post('general_risk/risk_notify_update','RiskController@risk_notify_update')->name('gen_risk.risk_notify_update');
Route::get('general_risk/risk_notify_destroy/{id}/{iduser}','RiskController@risk_notify_destroy')->name('gen_risk.risk_notify_destroy');
Route::get('general_risk/risk_notify_recheck/{idrisk}/{iduser}','RiskController@risk_notify_recheck')->name('gen_risk.risk_notify_recheck');
Route::get('general_risk/risk_notify_recheck_add/{idrisk}/{iduser}','RiskController@risk_notify_recheck_add')->name('gen_risk.risk_notify_recheck_add');
Route::post('general_risk/risk_notify_recheck_save','RiskController@risk_notify_recheck_save')->name('gen_risk.risk_notify_recheck_save');
Route::get('general_risk/risk_notify_recheck_edit/{idrisk_recheck}/{iduser}','RiskController@risk_notify_recheck_edit')->name('gen_risk.risk_notify_recheck_edit');
Route::post('general_risk/risk_notify_recheck_update','RiskController@risk_notify_recheck_update')->name('gen_risk.risk_notify_recheck_update');
/** จบแท็บบันทึกความเสี่ยง */

/** แท็บหน้าตรวจสอบ*/
Route::get('general_risk/risk_notify_checkinfo/{iduser}','RiskController@risk_notify_checkinfo')->name('gen_risk.risk_notify_checkinfo');
Route::post('general_risk/risk_notify_checkinfo_search/{iduser}','RiskController@risk_notify_checkinfo_search')->name('gen_risk.risk_notify_checkinfo_search'); 
Route::get('general_risk/risk_notify_check/{id}/{iduser}','RiskController@risk_notify_check')->name('gen_risk.risk_notify_check');
Route::post('general_risk/risk_notify_check_update','RiskController@risk_notify_check_update')->name('gen_risk.risk_notify_check_update');
Route::get('general_risk/risk_notify_checkinfo_recheck/{idrisk}/{iduser}','RiskController@risk_notify_checkinfo_recheck')->name('gen_risk.risk_notify_checkinfo_recheck');
Route::get('general_risk/risk_notify_checkinfo_recheck_add/{idrisk}/{iduser}','RiskController@risk_notify_checkinfo_recheck_add')->name('gen_risk.risk_notify_checkinfo_recheck_add');
Route::post('general_risk/risk_notify_checkinfo_recheck_save','RiskController@risk_notify_checkinfo_recheck_save')->name('gen_risk.risk_notify_checkinfo_recheck_save');
Route::get('general_risk/risk_notify_checkinfo_recheck_edit/{idrisk_recheck}/{iduser}','RiskController@risk_notify_checkinfo_recheck_edit')->name('gen_risk.risk_notify_checkinfo_recheck_edit');
Route::post('general_risk/risk_notify_checkinfo_recheck_update','RiskController@risk_notify_checkinfo_recheck_update')->name('gen_risk.risk_notify_checkinfo_recheck_update');
/** จบแท็บหน้าตรวจสอบ*/

/** แท็บความเสี่ยงที่เกี่ยวข้อง*/
Route::get('general_risk/risk_notify_deal/{iduser}','RiskController@risk_notify_deal')->name('gen_risk.risk_notify_deal');
Route::post('general_risk/risk_notify_deal_search/{iduser}','RiskController@risk_notify_deal_search')->name('gen_risk.risk_notify_deal_search');
Route::get('general_risk/risk_notify_deal_detail/{idref}/{iduser}','RiskController@risk_notify_deal_detail')->name('gen_risk.risk_notify_deal_detail');
Route::get('general_risk/risk_notify_deal_recheck/{idrisk}/{iduser}','RiskController@risk_notify_deal_recheck')->name('gen_risk.risk_notify_deal_recheck');
Route::get('general_risk/risk_notify_deal_recheck_add/{idrisk}/{iduser}','RiskController@risk_notify_deal_recheck_add')->name('gen_risk.risk_notify_deal_recheck_add');
Route::post('general_risk/risk_notify_deal_recheck_save','RiskController@risk_notify_deal_recheck_save')->name('gen_risk.risk_notify_deal_recheck_save');
Route::get('general_risk/risk_notify_deal_recheck_edit/{idrisk_recheck}/{iduser}','RiskController@risk_notify_deal_recheck_edit')->name('gen_risk.risk_notify_deal_recheck_edit');
Route::post('general_risk/risk_notify_deal_recheck_update','RiskController@risk_notify_deal_recheck_update')->name('gen_risk.risk_notify_deal_recheck_update');
/** จบแท็บความเสี่ยงที่เกี่ยวข้อง*/

Route::get('general_risk/risk_notify_detail/{id}/{iduser}','RiskController@risk_notify_detail')->name('gen_risk.risk_notify_detail');
Route::get('general_risk/risk_notify_cancel/{id}/{iduser}','RiskController@risk_notify_cancel')->name('gen_risk.risk_notify_cancel');
Route::post('general_risk/risk_notify_updatecancel','RiskController@risk_notify_updatecancel')->name('gen_risk.risk_notify_updatecancel');
Route::get('general_risk/risk_notify_internalcontrol/{iduser}','RiskController@risk_notify_internalcontrol')->name('gen_risk.risk_notify_internalcontrol'); 
Route::post('general_risk/risk_notify_internalcontrol_search/{iduser}','RiskController@risk_notify_internalcontrol_search')->name('gen_risk.risk_notify_internalcontrol_search'); 
Route::get('general_risk/risk_notify_internalcontrol_add/{iduser}','RiskController@risk_notify_internalcontrol_add')->name('gen_risk.risk_notify_internalcontrol_add'); 
Route::post('general_risk/risk_notify_internalcontrol_save','RiskController@risk_notify_internalcontrol_save')->name('gen_risk.risk_notify_internalcontrol_save');
Route::get('general_risk/risk_notify_internalcontrol_edit/{idref}/{iduser}','RiskController@risk_notify_internalcontrol_edit')->name('gen_risk.risk_notify_internalcontrol_edit'); 
Route::get('general_risk/risk_notify_internalcontrol_detail/{idref}/{iduser}','RiskController@risk_notify_internalcontrol_detail')->name('gen_risk.risk_notify_internalcontrol_detail'); 
Route::post('general_risk/risk_notify_internalcontrol_update','RiskController@risk_notify_internalcontrol_update')->name('gen_risk.risk_notify_internalcontrol_update');
Route::get('general_risk/selectbudget','RiskController@selectbudget')->name('mrisk.selectbudget');

Route::get('general_risk/risk_notify_report5/{iduser}','RiskController@risk_notify_report5')->name('gen_risk.risk_notify_report5'); //รายงาน ปค.5
Route::get('general_risk/risk_notify_report5_edit/{idref}/{iduser}','RiskController@risk_notify_report5_edit')->name('gen_risk.risk_notify_report5_edit');
Route::post('general_risk/risk_notify_report5_save','RiskController@risk_notify_report5_save')->name('gen_risk.risk_notify_report5_save');
Route::post('general_risk/risk_notify_report5_update','RiskController@risk_notify_report5_update')->name('gen_risk.risk_notify_report5_update');

Route::get('general_risk/risk_notify_report5_sub/{idref}/{iduser}','RiskController@risk_notify_report5_sub')->name('gen_risk.risk_notify_report5_sub');
Route::post('general_risk/risk_notify_report5_sub_save','RiskController@risk_notify_report5_sub_save')->name('gen_risk.risk_notify_report5_sub_save');
Route::post('general_risk/risk_notify_report5_sub_update','RiskController@risk_notify_report5_sub_update')->name('gen_risk.risk_notify_report5_sub_update');

Route::get('general_risk/risk_notify_report5_sub_excel/{idref}/{iduser}','RiskController@risk_notify_report5_sub_excel')->name('gen_risk.risk_notify_report5_sub_excel'); //excel ปค.5

Route::get('general_risk/risk_notify_report4/{iduser}','RiskController@risk_notify_report4')->name('gen_risk.risk_notify_report4'); //รายงาน ปค.5

Route::get('general_risk/risk_notify_report4_add/{iduser}','RiskController@risk_notify_report4_add')->name('gen_risk.risk_notify_report4_add');
Route::post('general_risk/risk_notify_report4_save','RiskController@risk_notify_report4_save')->name('gen_risk.risk_notify_report4_save');
Route::get('general_risk/risk_notify_report4_edit/{idref}/{iduser}','RiskController@risk_notify_report4_edit')->name('gen_risk.risk_notify_report4_edit');
Route::post('general_risk/risk_notify_report4_update','RiskController@risk_notify_report4_update')->name('gen_risk.risk_notify_report4_update');


Route::get('general_risk/risk_notify_yearly/{iduser}','RiskController@risk_notify_yearly')->name('gen_risk.risk_notify_yearly'); //หน้าวิเคราะรายปี
Route::get('general_risk/risk_notify_analysis/{idref}/{iduser}','RiskController@risk_notify_analysis')->name('gen_risk.risk_notify_analysis'); //หน้าบันทึกความเสียง

Route::post('general_risk/risk_notify_analysis_save','RiskController@risk_notify_analysis_save')->name('gen_risk.risk_notify_analysis_save');
Route::post('general_risk/risk_notify_analysis_update','RiskController@risk_notify_analysis_update')->name('gen_risk.risk_notify_analysis_update');
Route::get('general_risk/risk_notify_analysis_destroy/{idref}/{iduser}','RiskController@risk_notify_analysis_destroy')->name('gen_risk.risk_notify_analysis_destroy');


Route::post('general_risk/risk_account_detail_save','RiskController@risk_account_detail_save')->name('gen_risk.risk_account_detail_save');
Route::post('general_risk/risk_account_detail_update','RiskController@risk_account_detail_update')->name('gen_risk.risk_account_detail_update');
Route::get('general_risk/risk_account_detail_destroy/{idref}/{iduser}','RiskController@risk_account_detail_destroy')->name('gen_risk.risk_account_detail_destroy');

Route::post('general_risk/risk_account_detail_level_save','RiskController@risk_account_detail_level_save')->name('gen_risk.risk_account_detail_level_save');
Route::post('general_risk/risk_account_detail_level_update','RiskController@risk_account_detail_level_update')->name('gen_risk.risk_account_detail_level_update');
Route::get('general_risk/risk_account_detail_level_destroy/{idref}/{iduser}','RiskController@risk_account_detail_level_destroy')->name('gen_risk.risk_account_detail_level_destroy');


Route::get('general_risk/risk_notify_account/{iduser}','RiskController@risk_notify_account')->name('gen_risk.risk_notify_account'); 
Route::get('general_risk/risk_notify_account_add/{iduser}','RiskController@risk_notify_account_add')->name('gen_risk.risk_notify_account_add'); 
Route::post('general_risk/risk_notify_account_save','RiskController@risk_notify_account_save')->name('gen_risk.risk_notify_account_save');
Route::get('general_risk/risk_notify_account_edit/{idref}/{iduser}','RiskController@risk_notify_account_edit')->name('gen_risk.risk_notify_account_edit'); 
Route::get('general_risk/risk_notify_account_detail/{iduser}','RiskController@risk_notify_account_detail')->name('gen_risk.risk_notify_account_detail');
Route::post('general_risk/risk_notify_account_update','RiskController@risk_notify_account_update')->name('gen_risk.risk_notify_account_update');
Route::get('general_risk/risk_notify_account_cancel/{idref}/{iduser}','RiskController@risk_notify_account_cancel')->name('gen_risk.risk_notify_account_cancel');
Route::post('general_risk/risk_notify_account_updatecancel','RiskController@risk_notify_account_updatecancel')->name('gen_risk.risk_notify_account_updatecancel');

Route::get('general_risk/risk_notify_account_level/{idref}/{iduser}','RiskController@risk_notify_account_level')->name('gen_risk.risk_notify_account_level'); 
Route::get('general_risk/risk_notify_account_incidence/{idref}/{iduser}','RiskController@risk_notify_account_incidence')->name('gen_risk.risk_notify_account_incidence'); 

Route::get('general_risk/risk_notify_interest/{iduser}','RiskController@risk_notify_interest')->name('gen_risk.risk_notify_interest'); 
Route::get('general_risk/risk_notify_interest_add/{iduser}','RiskController@risk_notify_interest_add')->name('gen_risk.risk_notify_interest_add'); 
Route::post('general_risk/risk_notify_interest_save','RiskController@risk_notify_interest_save')->name('gen_risk.risk_notify_interest_save');
Route::get('general_risk/risk_notify_interest_edit/{idref}/{iduser}','RiskController@risk_notify_interest_edit')->name('gen_risk.risk_notify_interest_edit'); 
Route::post('general_risk/risk_notify_interest_update','RiskController@risk_notify_interest_update')->name('gen_risk.risk_notify_interest_update');


Route::get('general_risk/risk_notify_reportsub/{iduser}','RiskController@risk_notify_reportsub')->name('mrisk.risk_notify_reportsub'); 



//============================================ ค้นหา  ====================================================//

Route::post('general_risk/risk_notify_search/{iduser}','RiskController@risk_notify_search')->name('gen_risk.risk_notify_search');
Route::post('general_risk/risk_refteam_search/{iduser}','RiskController@risk_refteam_search')->name('gen_risk.risk_refteam_search');
Route::post('general_risk/risk_refdep_search/{iduser}','RiskController@risk_refdep_search')->name('gen_risk.risk_refdep_search');
Route::post('general_risk/risk_refdepsub_search/{iduser}','RiskController@risk_refdepsub_search')->name('gen_risk.risk_refdepsub_search');
Route::post('general_risk/risk_refdepsubsub_search/{iduser}','RiskController@risk_refdepsubsub_search')->name('gen_risk.risk_refdepsubsub_search');
Route::post('general_risk/risk_refperson_search/{iduser}','RiskController@risk_refperson_search')->name('gen_risk.risk_refperson_search');

//=============================== ============================================= ====================//

Route::get('general_risk/risk_refteam/{iduser}','RiskController@risk_refteam')->name('gen_risk.risk_refteam');
Route::get('general_risk/risk_refdep/{iduser}','RiskController@risk_refdep')->name('gen_risk.risk_refdep');
Route::get('general_risk/risk_refdepsub/{iduser}','RiskController@risk_refdepsub')->name('gen_risk.risk_refdepsub');
Route::get('general_risk/risk_refdepsubsub/{iduser}','RiskController@risk_refdepsubsub')->name('gen_risk.risk_refdepsubsub');
Route::get('general_risk/risk_refperson/{iduser}','RiskController@risk_refperson')->name('gen_risk.risk_refperson');

//=============================== 29.06.63 ====================//

// Route::get('general_risk/risk_notify_add/{iduser}','RiskController@risk_notify_add')->name('gen_risk.risk_notify_add');


//==========start========== 02.07.63 ============================//

Route::get('general_risk/risk_notify_repeat_u/{id}/{iduser}','RiskController@risk_notify_repeat_u')->name('gen_risk.risk_notify_repeat_u');
Route::get('general_risk/risk_notify_repeat_sub_u/{id}/{iduser}','RiskController@risk_notify_repeat_sub_u')->name('gen_risk.risk_notify_repeat_sub_u');
Route::post('general_risk/risk_notify_repeat_sub_u_save','RiskController@risk_notify_repeat_sub_u_save')->name('gen_risk.risk_notify_repeat_sub_u_save'); // ตอบรับ Detail
Route::get('general_risk/risk_notify_repeat_sub_u_edit/{id}/{iduser}/{idrig}','RiskController@risk_notify_repeat_sub_u_edit')->name('gen_risk.risk_notify_repeat_sub_u_edit');
Route::post('general_risk/risk_notify_repeat_sub_u_update','RiskController@risk_notify_repeat_sub_u_update')->name('gen_risk.risk_notify_repeat_sub_u_update'); // ตอบรับ Detail
Route::get('general_risk/risk_notify_repeat_sub_u_destroy/{id}/{iduser}/{idrig}','RiskController@risk_notify_repeat_sub_u_destroy')->name('gen_risk.risk_notify_repeat_sub_u_destroy'); // ตอบรับ Detail


Route::get('general_risk/risk_notify_accept_u/{id}/{iduser}','RiskController@risk_notify_accept_u')->name('gen_risk.risk_notify_accept_u');
Route::get('general_risk/risk_notify_accept_sub_u/{id}/{iduser}','RiskController@risk_notify_accept_sub_u')->name('gen_risk.risk_notify_accept_sub_u');
Route::post('general_risk/risk_notify_accept_sub_u_save','RiskController@risk_notify_accept_sub_u_save')->name('gen_risk.risk_notify_accept_sub_u_save');
Route::get('general_risk/risk_notify_accept_sub_u_edit/{id}/{iduser}/{idrig}','RiskController@risk_notify_accept_sub_u_edit')->name('gen_risk.risk_notify_accept_sub_u_edit');
Route::post('general_risk/risk_notify_accept_sub_u_update','RiskController@risk_notify_accept_sub_u_update')->name('gen_risk.risk_notify_accept_sub_u_update');
Route::get('general_risk/risk_notify_accept_sub_u_destroy/{id}/{iduser}/{idrig}','RiskController@risk_notify_accept_sub_u_destroy')->name('gen_risk.risk_notify_accept_sub_u_destroy'); // ตอบรับ Detail


//============end======== 02.07.63 ============================//
//===================================ADMIN======================================================================
Route::get('admin/selectbudget','SetupbudgetController@selectbudget')->name('admin.selectbudget');
Route::get('admin/selectyear','SetupbudgetController@selectyear')->name('admin.selectyear');
//===============================================
Route::get('admin/setupinfoorg','SetuporgController@infoorg')->name('setup.indexhoorg');
Route::post('admin/setupupdate','SetuporgController@updatedate')->name('setuporg.updatedata');

Route::get('admin/setupinfobudget','SetupbudgetController@infobudget')->name('setup.indexbudget');
Route::get('admin/setupinfobudget/switchbudget','SetupbudgetController@switchactive')->name('setup.budget');
Route::get('admin/setupinfobudget/add','SetupbudgetController@create')->name('setup.add');
Route::post('admin/setupinfobudget/save','SetupbudgetController@save')->name('admin.savebudget');
Route::get('admin/setupinfobudget/edit/{id}','SetupbudgetController@edit')->name('setup.edit');
Route::post('admin/setupinfobudget/update','SetupbudgetController@update')->name('admin.updatebudget');
Route::get('admin/setupinfobudget/destroy/{id}','SetupbudgetController@destroy');

Route::get('admin/setupinfoposition','SetuppositionController@infoposition')->name('setup.indexposition');
Route::get('admin/setupinfoposition/switchposition','SetuppositionController@switchposition')->name('setup.position');
Route::get('admin/setupinfoposition/add','SetuppositionController@create')->name('setup.addposition');
Route::post('admin/setupinfoposition/save','SetuppositionController@save')->name('admin.saveposition');
Route::get('admin/setupinfoposition/edit/{id}','SetuppositionController@edit')->name('setup.editposition');
Route::post('admin/setupinfoposition/update','SetuppositionController@update')->name('admin.updateposition');
Route::get('admin/setupinfoposition/destroy/{id}','SetuppositionController@destroy');

Route::get('admin_permis/setupinfopermis','SetuppermisController@infopermis')->name('setup.indexpermis');
Route::get('admin_permis/setupinfopermis_add','SetuppermisController@setupinfopermis_add')->name('setup.setupinfopermis_add');
Route::get('admin_permis/setupinfopermis_addsearch','SetuppermisController@setupinfopermis_addsearch')->name('setup.setupinfopermis_addsearch');
Route::post('admin_permis/setupinfopermis/save','SetuppermisController@save')->name('setup.savepermis');
Route::get('admin_permis/setupinfopermis/destroy/{id}','SetuppermisController@destroy');
Route::get('admin_permis/setupinfopermis/permis/{id}','SetuppermisController@permis')->name('setup.permis');
Route::post('admin_permis/setupinfopermis/savepermis','SetuppermisController@savepermis')->name('setup.saveapprovpermis');
Route::get('admin_permis/setupinfopermis/destroypermis/{id}/{iduse}','SetuppermisController@destroypermis');

Route::get('admin/setupinfodaytype','SetupdaytypeController@infodaytype')->name('setup.indexdaytype');
Route::get('admin/setupinfodaytype/add','SetupdaytypeController@create')->name('setup.adddaytype');
Route::post('admin/setupinfodaytype/save','SetupdaytypeController@save')->name('admin.savedaytype');
Route::get('admin/setupinfodaytype/edit/{id}','SetupdaytypeController@edit')->name('setup.editdaytype');
Route::post('admin/setupinfodaytype/update','SetupdaytypeController@update')->name('admin.updatedaytype');
Route::get('admin/setupinfodaytype/destroy/{id}','SetupdaytypeController@destroy');

Route::get('admin_other/setupinfopublicityimage','SetuppublicityimageController@infopublicityimage')->name('setup.infopublicityimage');
Route::get('admin_other/setupinfopublicityimage/add','SetuppublicityimageController@createpublicityimage')->name('setup.createpublicityimage');
Route::post('admin_other/setupinfopublicityimage/save','SetuppublicityimageController@savepublicityimage')->name('admin.savepublicityimage');
Route::get('admin_other/setupinfopublicityimage/destroy/{id}','SetuppublicityimageController@destroypublicityimage');
Route::get('admin_other/setupinfopublicityimage/switch','SetuppublicityimageController@switchactive')->name('setup.publicityimage');

Route::get('admin_other/setuplinetoken','SetuplineController@infolinetoken')->name('setup.infolinetoken');
Route::post('admin_other/setuplinetoken/update','SetuplineController@updateinfolinetoken')->name('admin.updateinfolinetoken');

// ลิ้งประชาสัมพัน
Route::prefix('admin_other/setupinformation/')->group(function(){
    Route::get('publicize',[Setupinfo_publicizeController::class,'publicize'])->name('admin.setupinfo.publicize');
    Route::get('publicize/add',[Setupinfo_publicizeController::class,'publicize_add'])->name('admin.setupinfo.publicize_add');
    Route::post('publicize/save',[Setupinfo_publicizeController::Class,'publicize_save'])->name('admin.setupinfo.publicize_save');
    Route::get('publicize/edit/{id}',[Setupinfo_publicizeController::Class,'publicize_edit'])->name('admin.setupinfo.publicize_edit');
    Route::post('publicize/update',[Setupinfo_publicizeController::Class,'publicize_update'])->name('admin.setupinfo.publicize_update');
    Route::get('publicize/delete/{id}',[Setupinfo_publicizeController::Class,'publicize_delete'])->name('admin.setupinfo.publicize_delete');
});

// page facebook
Route::prefix('admin_other/setupinformation/')->group(function(){
    Route::get('pagefacebook',[Setupinfo_pagefacebookController::Class,'pagefacebook'])->name('admin.setupinfo.pagefacebook');
    Route::get('pagefacebook/add',[Setupinfo_pagefacebookController::Class,'pagefacebook_add'])->name('admin.setupinfo.pagefacebook_add');
    Route::post('pagefacebook/save',[Setupinfo_pagefacebookController::Class,'pagefacebook_save'])->name('admin.setupinfo.pagefacebook_save');
    Route::get('pagefacebook/edit/{id}',[Setupinfo_pagefacebookController::Class,'pagefacebook_edit'])->name('admin.setupinfo.pagefacebook_edit');
    Route::post('pagefacebook/update',[Setupinfo_pagefacebookController::Class,'pagefacebook_update'])->name('admin.setupinfo.pagefacebook_update');
    Route::get('pagefacebook/delete/{id}',[Setupinfo_pagefacebookController::Class,'pagefacebook_delete'])->name('admin.setupinfo.pagefacebook_delete');
});

//=========================================================================================================

Route::get('admin_person/setupinfoworkgroup','SetuppersonController@infodepartment')->name('setup.indexdepartment');
Route::get('admin_person/setupinfoworkgroup/switchdepartment','SetuppersonController@switchactive_1')->name('setup.department');
Route::get('admin_person/setupinfoworkgroup/add','SetuppersonController@createdepartment')->name('setup.addpersondepartment');
Route::post('admin_person/setupinfoworkgroup/save','SetuppersonController@savedepartment')->name('admin.savepersondepartment');
Route::get('admin_person/setupinfoworkgroup/edit/{id}','SetuppersonController@editdepartment')->name('setup.editpersondepartment');
Route::post('admin_person/setupinfoworkgroup/update','SetuppersonController@updatedepartment')->name('admin.updatepersondepartment');
Route::get('admin_person/setupinfoworkgroup/destroy/{id}','SetuppersonController@destroydepartment');

Route::get('admin_person/setupinfodepartment','SetuppersonController@infodepartmentsub')->name('setup.indexdepartmentsub');
Route::get('admin_person/setupinfodepartment/switchdepartment','SetuppersonController@switchactive_2')->name('setup.departmentsub');
Route::get('admin_person/setupinfodepartment/add','SetuppersonController@createdepartmentsub')->name('setup.addpersondepartmentsub');
Route::post('admin_person/setupinfodepartment/save','SetuppersonController@savedepartmentsub')->name('admin.savepersondepartmentsub');
Route::get('admin_person/setupinfodepartment/edit/{id}','SetuppersonController@editdepartmentsub')->name('setup.editpersondepartmentsub');
Route::post('admin_person/setupinfodepartment/update','SetuppersonController@updatedepartmentsub')->name('admin.updatepersondepartmentsub');
Route::get('admin_person/setupinfodepartment/destroy/{id}','SetuppersonController@destroydepartmentsub');

Route::get('admin_person/setupinfoinstitution','SetuppersonController@infodepartmentsubsub')->name('setup.indexdepartmentsubsub');
Route::get('admin_person/setupinfoinstitution/switchdepartment','SetuppersonController@switchactive_3')->name('setup.departmentsubsub');
Route::get('admin_person/setupinfoinstitution/add','SetuppersonController@createdepartmentsubsub')->name('setup.addpersondepartmentsubsub');
Route::post('admin_person/setupinfoinstitution/save','SetuppersonController@savedepartmentsubsub')->name('admin.savepersondepartmentsubsub');
Route::get('admin_person/setupinfoinstitution/edit/{id}','SetuppersonController@editdepartmentsubsub')->name('setup.editpersondepartmentsubsub');
Route::post('admin_person/setupinfoinstitution/update','SetuppersonController@updatedepartmentsubsub')->name('admin.updatepersondepartmentsubsub');
Route::get('admin_person/setupinfoinstitution/destroy/{id}','SetuppersonController@destroydepartmentsubsub');

//============================ตั้งหัวหน้างาน==================

Route::get('admin_person_H/setupinfoworkgroup_H','SetuppersonController@infodepartment_H')->name('setup.indexdepartment_H');
Route::get('admin_person/setupinfoworkgroup_H/updatedepartment_h','SetuppersonController@updatedepartment_h')->name('setup.updatedepartment_h');

Route::get('admin_person_H/setupinfodepartment_H','SetuppersonController@infodepartmentsub_H')->name('setup.indexdepartmentsub_H');
Route::get('admin_person/setupinfodepartment_H/updatedepartsubment_h','SetuppersonController@updatedepartsubment_h')->name('setup.updatedepartsubment_h');

Route::get('admin_person_H/setupinfoinstitution_H','SetuppersonController@infodepartmentsubsub_H')->name('setup.indexdepartmentsubsub_H');
Route::get('admin_person/setupinfoinstitution_H/selectupdatedepartsubsubment_h','SetuppersonController@selectupdatedepartsubsubment_h')->name('setup.selectupdatedepartsubsubment_h');
Route::post('admin_person/setupinfoinstitution_H/updatedepartsubsubment_h','SetuppersonController@updatedepartsubsubment_h')->name('setup.updatedepartsubsubment_h');


Route::get('admin_person_H/setupinfopersonteam','SetuppersonController@infoteam')->name('setup.infoteam');
Route::get('admin_person_H/setupinfopersonteam/add','SetuppersonController@createteam')->name('setup.addteam');
Route::post('admin_person_H/setupinfopersonteam/save','SetuppersonController@saveteam')->name('admin.saveteam');
Route::get('admin_person_H/setupinfopersonteam/edit/{id}','SetuppersonController@editteam')->name('setup.editteam');
Route::post('admin_person_H/setupinfopersonteam/update','SetuppersonController@updateteam')->name('admin.updateteam');
Route::get('admin_person_H/setupinfopersonteam/destroy/{id}','SetuppersonController@destroyteam');

Route::get('admin_person_H/setupinfopersonteam/committee/{id}','SetuppersonController@committee')->name('setup.committee');
Route::get('admin_person_H/setupinfopersonteam/addcommittee/{id}','SetuppersonController@createcommittee')->name('setup.addcommittee');
Route::post('admin_person_H/setupinfopersonteam/savecommittee','SetuppersonController@savecommittee')->name('admin.savecommittee');
Route::get('admin_person_H/setupinfopersonteam/editcommittee/{id}/{idteam}','SetuppersonController@editcommittee')->name('setup.editcommittee');
Route::post('admin_person_H/setupinfopersonteam/updatecommittee','SetuppersonController@updatecommittee')->name('admin.updatecommittee');
Route::get('admin_person_H/setupinfopersonteam/destroypcommittee/{id}/{idteam}','SetuppersonController@destroycommittee');

//========================================================




Route::get('admin_person/setupinfolevel','SetuppersonController@infolevel')->name('setup.indexpersonlevel');
Route::get('admin_person/setupinfolevel/add','SetuppersonController@createlevel')->name('setup.addpersonlevel');
Route::post('admin_person/setupinfolevel/save','SetuppersonController@savelevel')->name('admin.savepersonlevel');
Route::get('admin_person/setupinfolevel/edit/{id}','SetuppersonController@editlevel')->name('setup.editpersonlevel');
Route::post('admin_person/setupinfolevel/update','SetuppersonController@updatelevel')->name('admin.updatepersonlevel');
Route::get('admin_person/setupinfolevel/destroy/{id}','SetuppersonController@destroylevel');


Route::get('admin_person/setupinfostatus','SetuppersonController@infostatus')->name('setup.indexpersonstatus');
Route::get('admin_person/setupinfostatus/add','SetuppersonController@createstatus')->name('setup.addpersonstatus');
Route::post('admin_person/setupinfostatus/save','SetuppersonController@savestatus')->name('admin.savepersonstatus');
Route::get('admin_person/setupinfostatus/edit/{id}','SetuppersonController@editstatus')->name('setup.editpersonstatus');
Route::post('admin_person/setupinfostatus/update','SetuppersonController@updatestatus')->name('admin.updatepersonstatus');
Route::get('admin_person/setupinfostatus/destroy/{id}','SetuppersonController@destroystatus');


Route::get('admin_person/setupinfokind','SetuppersonController@infokind')->name('setup.indexpersonkind');
Route::get('admin_person/setupinfokind/add','SetuppersonController@createkind')->name('setup.addpersonkind');
Route::post('admin_person/setupinfokind/save','SetuppersonController@savekind')->name('admin.savepersonkind');
Route::get('admin_person/setupinfokind/edit/{id}','SetuppersonController@editkind')->name('setup.editpersonkind');
Route::post('admin_person/setupinfokind/update','SetuppersonController@updatekind')->name('admin.updatepersonkind');
Route::get('admin_person/setupinfokind/destroy/{id}','SetuppersonController@destroykind');

Route::get('admin_person/setupinfokindtype','SetuppersonController@infokindtype')->name('setup.indexpersonkindtype');
Route::get('admin_person/setupinfokindtype/add','SetuppersonController@createkindtype')->name('setup.addpersonkindtype');
Route::post('admin_person/setupinfokindtype/save','SetuppersonController@savekindtype')->name('admin.savepersonkindtype');
Route::get('admin_person/setupinfokindtype/edit/{id}','SetuppersonController@editkindtype')->name('setup.editpersonkindtype');
Route::post('admin_person/setupinfokindtype/update','SetuppersonController@updatekindtype')->name('admin.updatepersonkindtype');
Route::get('admin_person/setupinfokindtype/destroy/{id}','SetuppersonController@destroykindtype');

Route::get('admin_person/setupinfopersongroup','SetuppersonController@infopersontype')->name('setup.indexpersontype');
Route::get('admin_person/setupinfopersongroup/add','SetuppersonController@createpersontype')->name('setup.addpersontype');
Route::post('admin_person/setupinfopersongroup/save','SetuppersonController@savepersontype')->name('admin.savepersontype');
Route::get('admin_person/setupinfopersongroup/edit/{id}','SetuppersonController@editpersontype')->name('setup.editpersontype');
Route::post('admin_person/setupinfopersongroup/update','SetuppersonController@updatepersontype')->name('admin.updatepersontype');
Route::get('admin_person/setupinfopersongroup/destroy/{id}','SetuppersonController@destroypersontype');

Route::get('admin_person/setupinfopersonteam','SetuppersonController@infoteam')->name('setup.infoteam');
Route::get('admin_person/setupinfopersonteam/add','SetuppersonController@createteam')->name('setup.addteam');
Route::post('admin_person/setupinfopersonteam/save','SetuppersonController@saveteam')->name('admin.saveteam');
Route::get('admin_person/setupinfopersonteam/edit/{id}','SetuppersonController@editteam')->name('setup.editteam');
Route::post('admin_person/setupinfopersonteam/update','SetuppersonController@updateteam')->name('admin.updateteam');
Route::get('admin_person/setupinfopersonteam/destroy/{id}','SetuppersonController@destroyteam');

Route::get('admin_person/setupinfopersonteam/committee/{id}','SetuppersonController@committee')->name('setup.committee');
Route::get('admin_person/setupinfopersonteam/addcommittee/{id}','SetuppersonController@createcommittee')->name('setup.addcommittee');
Route::post('admin_person/setupinfopersonteam/savecommittee','SetuppersonController@savecommittee')->name('admin.savecommittee');
Route::get('admin_person/setupinfopersonteam/editcommittee/{id}/{idteam}','SetuppersonController@editcommittee')->name('setup.editcommittee');
Route::post('admin_person/setupinfopersonteam/updatecommittee','SetuppersonController@updatecommittee')->name('admin.updatecommittee');
Route::get('admin_person/setupinfopersonteam/destroypcommittee/{id}/{idteam}','SetuppersonController@destroycommittee');


Route::get('admin_person/setupinfopersonteamposition','SetuppersonController@teamposition')->name('setup.teamposition');
Route::get('admin_person/setupinfopersonteamposition/add','SetuppersonController@createteamposition')->name('setup.addteamposition');
Route::post('admin_person/setupinfopersonteamposition/save','SetuppersonController@saveteamposition')->name('admin.saveteamposition');
Route::get('admin_person/setupinfopersonteamposition/edit/{id}','SetuppersonController@editteamposition')->name('setup.editteamposition');
Route::post('admin_person/setupinfopersonteamposition/update','SetuppersonController@updateteamposition')->name('admin.updateteamposition');
Route::get('admin_person/setupinfopersonteamposition/destroy/{id}','SetuppersonController@destroyteamposition');
//=========================================================================================================
Route::get('admin_leave/setupinfoholiday','SetupholidayController@infoholiday')->name('setup.indexholiday');
Route::post('admin_leave/setupinfoholidaysearch','SetupholidayController@infoholidaysearch')->name('setup.infoholidaysearch');
Route::get('admin_leave/setupinfoholiday/add','SetupholidayController@create')->name('setup.addholiday');
Route::post('admin_leave/setupinfoholiday/save','SetupholidayController@save')->name('admin.saveholiday');
Route::get('admin_leave/setupinfoholiday/edit/{id}','SetupholidayController@edit')->name('setup.editholiday');
Route::post('admin_leave/setupinfoholiday/update','SetupholidayController@update')->name('admin.updateholiday');
Route::get('admin_leave/setupinfoholiday/destroy/{id}','SetupholidayController@destroy');


Route::post('admin_leave/setupinfoholiday/callnewholiday','SetupholidayController@callnewholiday')->name('admin.callnewholiday');

Route::get('admin_leave/setupinfoleavetype','SetupleavetypeController@infoleavetype')->name('setup.indexleavetype');
Route::get('admin_leave/setupinfoleavetype/add','SetupleavetypeController@create')->name('setup.addleavetype');
Route::post('admin_leave/setupinfoleavetype/save','SetupleavetypeController@save')->name('admin.saveleavetype');
Route::get('admin_leave/setupinfoleavetype/edit/{id}','SetupleavetypeController@edit')->name('setup.editleavetype');
Route::post('admin_leave/setupinfoleavetype/update','SetupleavetypeController@update')->name('admin.updateleavetype');
Route::get('admin_leave/setupinfoleavetype/destroy/{id}','SetupleavetypeController@destroy');

Route::get('admin_leave/setupinfocondition','SetupconditionController@infocondition')->name('setup.indexcondition');


Route::get('admin_leave/setupinfovacation','SetupvacationController@infovacation')->name('setup.indexvacation');
Route::post('admin_leave/setupinfovacation/selectbudget','SetupvacationController@selectbudget')->name('setup.selectbudget');
Route::get('admin_leave/setupinfovacation/calleaveday/{yearbudget}','SetupvacationController@calleaveday')->name('setup.calleaveday');
Route::post('admin_leave/setupinfovacation/search','SetupvacationController@search')->name('setup.search');
Route::get('admin_leave/setupinfovacation/export_excel/excel/{budget}', 'SetupvacationController@excel')->name('export_excel.vacation');
Route::get('admin_leave/setupinfovacation/export_pdf', 'SetupvacationController@pdf')->name('export_pdf.vacation');
Route::get('admin_leave/setupinfovacation/create','SetupvacationController@create')->name('setup.createvacation');
Route::post('admin_leave/setupinfovacation/save','SetupvacationController@save')->name('setup.savevacation');
Route::get('admin_leave/setupinfovacation/edit/{id}','SetupvacationController@edit')->name('setup.editvacation');
Route::post('admin_leave/setupinfovacation/update','SetupvacationController@update')->name('setup.updatevacation');
Route::get('admin_leave/setupinfovacation/destroy/{id}','SetupvacationController@destroy');
Route::get('admin_leave/setupinfovacation/checkolds','SetupvacationController@checkolds')->name('setup.checkolds');
Route::get('admin_leave/setupinfovacation/checkolds_input','SetupvacationController@checkolds_input')->name('setup.checkolds_input');
Route::get('admin_leave/setupinfovacation/caldate','SetupvacationController@caldate')->name('setup.caldate');

Route::get('admin_leave_H/setupinfoapprov','SetupapprovController@infoapprov')->name('setup.indexapprov');
Route::post('admin_leave_H/setupinfoapprov/save','SetupapprovController@save')->name('setup.saveapprov');
Route::get('admin_leave_H/setupinfoapprov/destroy/{id}','SetupapprovController@destroy');
Route::get('admin_leave_H/setupinfoapprov/person/{id}','SetupapprovController@person')->name('setup.person');
Route::post('admin_leave_H/setupinfoapprov/saveperson','SetupapprovController@saveperson')->name('setup.saveapprovperson');
Route::get('admin_leave_H/setupinfoapprov/destroyperson/{id}/{leaderid}','SetupapprovController@destroyperson');

Route::get('admin_leave_H/setupinfomenu','SetuppermisController@setupinfomenu')->name('setup.setupinfomenu');
Route::get('admin_leave_H/setupinfomenu/switchinfomenu','SetuppermisController@switchinfomenu')->name('setup.switchinfomenu');

Route::get('admin_leave/setupinfofunction','SetupleaveController@infofunction')->name('setup.infofunction');
Route::get('admin_leave/setupinfofunction/switchfunction','SetupleaveController@switchactive')->name('setup.leavefunction');


Route::get('admin_leave/setupinfolevelgroup','SetupleaveController@setupinfolevelgroup')->name('setup.infolevelgroup');
Route::get('admin_leave/setupinfolevelgroup/add','SetupleaveController@setupinfolevelgroup_add')->name('setup.infolevelgroup_add');
Route::post('admin_leave/setupinfolevelgroup/save','SetupleaveController@setupinfolevelgroup_save')->name('setup.infolevelgroup_save');
Route::get('admin_leave/setupinfolevelgroup/edit/{id}','SetupleaveController@setupinfolevelgroup_edit')->name('setup.infolevelgroup_edit');
Route::post('admin_leave/setupinfolevelgroup/update','SetupleaveController@setupinfolevelgroup_update')->name('setup.infolevelgroup_update');
Route::get('admin_leave/setupinfolevelgroup/destroy/{id}','SetupleaveController@setupinfolevelgroup_destroy');

Route::post('admin_leave/setupinfolevelgroupdep_save/save','SetupleaveController@setupinfolevelgroupdep_save')->name('setup.setupinfolevelgroupdep_save');
//=========================================================================================================
Route::get('admin_dev/setupinfobranch','SetupbranchController@infobranch')->name('setup.indexbranch');
Route::get('admin_dev/setupinfobranch/add','SetupbranchController@create')->name('setup.addbranch');
Route::post('admin_dev/setupinfobranch/save','SetupbranchController@save')->name('admin.savebranch');
Route::get('admin_dev/setupinfobranch/edit/{id}','SetupbranchController@edit')->name('setup.editbranch');
Route::post('admin_dev/setupinfobranch/update','SetupbranchController@update')->name('admin.updatebranch');
Route::get('admin_dev/setupinfobranch/destroy/{id}','SetupbranchController@destroy');

Route::get('admin_dev/setupinfocapacity','SetupcapacityController@infocapacity')->name('setup.indexcapacity');
Route::get('admin_dev/setupinfocapacity/add','SetupcapacityController@create')->name('setup.addcapacity');
Route::post('admin_dev/setupinfocapacity/save','SetupcapacityController@save')->name('admin.savecapacity');
Route::get('admin_dev/setupinfocapacity/edit/{id}','SetupcapacityController@edit')->name('setup.editcapacity');
Route::post('admin_dev/setupinfocapacity/update','SetupcapacityController@update')->name('admin.updatecapacity');
Route::get('admin_dev/setupinfocapacity/destroy/{id}','SetupcapacityController@destroy');

Route::get('admin_dev/setupinfogo','SetupgoController@infogo')->name('setup.indexgo');
Route::get('admin_dev/setupinfogo/add','SetupgoController@create')->name('setup.addgo');
Route::post('admin_dev/setupinfogo/save','SetupgoController@save')->name('admin.savego');
Route::get('admin_dev/setupinfogo/edit/{id}','SetupgoController@edit')->name('setup.editgo');
Route::post('admin_dev/setupinfogo/update','SetupgoController@update')->name('admin.updatego');
Route::get('admin_dev/setupinfogo/destroy/{id}','SetupgoController@destroy');

Route::get('admin_dev/setupinfoorg','SetuprecordorgController@infoorg')->name('setup.indexorg');
Route::get('admin_dev/setupinfoorg/add','SetuprecordorgController@create')->name('setup.addorg');
Route::post('admin_dev/setupinfoorg/save','SetuprecordorgController@save')->name('admin.saveorg');
Route::get('admin_dev/setupinfoorg/edit/{id}','SetuprecordorgController@edit')->name('setup.editorg');
Route::post('admin_dev/setupinfoorg/update','SetuprecordorgController@update')->name('admin.updateorg');
Route::get('admin_dev/setupinfoorg/destroy/{id}','SetuprecordorgController@destroy');

Route::get('admin_dev/setupinfoorglocation','SetuporglocationController@infoorglocation')->name('setup.indexorglocation');
Route::get('admin_dev/setupinfoorglocation/add','SetuporglocationController@create')->name('setup.addorglocation');
Route::post('admin_dev/setupinfoorglocation/save','SetuporglocationController@save')->name('admin.saveorglocation');
Route::get('admin_dev/setupinfoorglocation/edit/{id}','SetuporglocationController@edit')->name('setup.editorglocation');
Route::post('admin_dev/setupinfoorglocation/update','SetuporglocationController@update')->name('admin.updateorglocation');
Route::get('admin_dev/setupinfoorglocation/destroy/{id}','SetuporglocationController@destroy');

Route::get('admin_dev/setupinfotype','SetuprecordtypeController@inforecordtype')->name('setup.indextype');
Route::get('admin_dev/setupinfotype/add','SetuprecordtypeController@create')->name('setup.addtype');
Route::post('admin_dev/setupinfotype/save','SetuprecordtypeController@save')->name('admin.savetype');
Route::get('admin_dev/setupinfotype/edit/{id}','SetuprecordtypeController@edit')->name('setup.edittype');
Route::post('admin_dev/setupinfotype/update','SetuprecordtypeController@update')->name('admin.updatetype');
Route::get('admin_dev/setupinfotype/destroy/{id}','SetuprecordtypeController@destroy');

Route::get('admin_dev/setupinfolevel','SetuprecordlevelController@inforecordlevel')->name('setup.indexlevel');
Route::get('admin_dev/setupinfolevel/add','SetuprecordlevelController@create')->name('setup.addlevel');
Route::post('admin_dev/setupinfolevel/save','SetuprecordlevelController@save')->name('admin.savelevel');
Route::get('admin_dev/setupinfolevel/edit/{id}','SetuprecordlevelController@edit')->name('setup.editlevel');
Route::post('admin_dev/setupinfolevel/update','SetuprecordlevelController@update')->name('admin.updatelevel');
Route::get('admin_dev/setupinfolevel/destroy/{id}','SetuprecordlevelController@destroy');

Route::get('admin_dev/setupinfoknow','SetuprecordknowController@inforecordknow')->name('setup.indexknow');
Route::get('admin_dev/setupinfoknow/add','SetuprecordknowController@create')->name('setup.addknow');
Route::post('admin_dev/setupinfoknow/save','SetuprecordknowController@save')->name('admin.saveknow');
Route::get('admin_dev/setupinfoknow/edit/{id}','SetuprecordknowController@edit')->name('setup.editknow');
Route::post('admin_dev/setupinfoknow/update','SetuprecordknowController@update')->name('admin.updateknow');
Route::get('admin_dev/setupinfoknow/destroy/{id}','SetuprecordknowController@destroy');

Route::get('admin_dev/setupinfodoctype','SetuprecorddoctypeController@inforecorddoctype')->name('setup.indexdoctype');
Route::get('admin_dev/setupinfodoctype/add','SetuprecorddoctypeController@create')->name('setup.adddoctype');
Route::post('admin_dev/setupinfodoctype/save','SetuprecorddoctypeController@save')->name('admin.savedoctype');
Route::get('admin_dev/setupinfodoctype/edit/{id}','SetuprecorddoctypeController@edit')->name('setup.editdoctype');
Route::post('admin_dev/setupinfodoctype/update','SetuprecorddoctypeController@update')->name('admin.updatedoctype');
Route::get('admin_dev/setupinfodoctype/destroy/{id}','SetuprecorddoctypeController@destroy');


Route::get('admin_dev/openform_perdev','SetuprecorddoctypeController@openform_perdev')->name('setup.openform_perdev'); // เปิดฟังก์ชั่นฟอร์ม
Route::get('admin_dev/openform_perdev_add','SetuprecorddoctypeController@openform_perdev_add')->name('setup.openform_perdev_add');
Route::post('admin_dev/openform_perdev_save','SetuprecorddoctypeController@openform_perdev_save')->name('admin.openform_perdev_save');
Route::get('admin_dev/openform_perdev_edit/{id}','SetuprecorddoctypeController@openform_perdev_edit')->name('setup.openform_perdev_edit');
Route::post('admin_dev/openform_perdev_update','SetuprecorddoctypeController@openform_perdev_update')->name('admin.openform_perdev_update');
Route::get('admin_dev/openform_perdev_destroy/{id}','SetuprecorddoctypeController@openform_perdev_destroy');
Route::get('admin_dev/openform_perdev_switchactive','SetuprecorddoctypeController@openform_perdev_switchactive')->name('admin.openform_perdev_switchactive');

//=========================================================================================================

Route::get('admin_meeting/setupinforoom','SetupmeetingController@inforoom')->name('setup.indexroom');
Route::get('admin_meeting/setupinforoom/switch','SetupmeetingController@switchactive')->name('setup.room');
Route::get('admin_meeting/setupinforoom/add','SetupmeetingController@create')->name('setup.addroom');
Route::post('admin_meeting/setupinforoom/save','SetupmeetingController@save')->name('admin.saveroom');
Route::get('admin_meeting/setupinforoom/edit/{id}','SetupmeetingController@edit')->name('setup.editroom');
Route::post('admin_meeting/setupinforoom/update','SetupmeetingController@update')->name('admin.updateroom');
Route::get('admin_meeting/setupinforoom/destroy/{id}','SetupmeetingController@destroy');

Route::get('admin_meeting/setupinforoom_equipment/{id}','SetupmeetingController@infoequ')->name('setup.indexequ');
Route::post('admin_meeting/setupinforoom_equipment/save','SetupmeetingController@saveequ')->name('admin.saveequ');
Route::post('admin_meeting/setupinforoom_equipment/update','SetupmeetingController@updateequ')->name('admin.updateequ');
Route::get('admin_meeting/setupinforoom_equipment/destroy/{idroom}/{id}','SetupmeetingController@destroyequ');

Route::get('admin_meeting/setupinforoomfood','SetupmeetingController@inforoomfood')->name('setup.indexroomfood');
Route::get('admin_meeting/setupinforoomfood/add','SetupmeetingController@createfood')->name('setup.addroomfood');
Route::post('admin_meeting/setupinforoomfood/save','SetupmeetingController@savefood')->name('admin.saveroomfood');
Route::get('admin_meeting/setupinforoomfood/edit/{id}','SetupmeetingController@editfood')->name('setup.editroomfood');
Route::post('admin_meeting/setupinforoomfood/update','SetupmeetingController@updatefood')->name('admin.updateroomfood');
Route::get('admin_meeting/setupinforoomfood/destroy/{id}','SetupmeetingController@destroyfood');

Route::get('admin_meeting/setupinforoomfoodtype','SetupmeetingController@inforoomfoodtype')->name('setup.indexroomfoodtype');
Route::get('admin_meeting/setupinforoomfoodtype/add','SetupmeetingController@createfoodtype')->name('setup.addroomfoodtype');
Route::post('admin_meeting/setupinforoomfoodtype/save','SetupmeetingController@savefoodtype')->name('admin.saveroomfoodtype');
Route::get('admin_meeting/setupinforoomfoodtype/edit/{id}','SetupmeetingController@editfoodtype')->name('setup.editroomfoodtype');
Route::post('admin_meeting/setupinforoomfoodtype/update','SetupmeetingController@updatefoodtype')->name('admin.updateroomfoodtype');
Route::get('admin_meeting/setupinforoomfoodtype/destroy/{id}','SetupmeetingController@destroyfoodtype');

Route::get('admin_meeting/setupinforoomarticle','SetupmeetingController@inforoomarticle')->name('setup.indexroomarticle');
Route::get('admin_meeting/setupinforoomarticle/add','SetupmeetingController@createarticle')->name('setup.addroomarticle');
Route::post('admin_meeting/setupinforoomarticle/save','SetupmeetingController@savearticle')->name('admin.saveroomarticle');
Route::get('admin_meeting/setupinforoomarticle/edit/{id}','SetupmeetingController@editarticle')->name('setup.editroomarticle');
Route::post('admin_meeting/setupinforoomarticle/update','SetupmeetingController@updatearticle')->name('admin.updateroomarticle');
Route::get('admin_meeting/setupinforoomarticle/destroy/{id}','SetupmeetingController@destroyarticle');

Route::get('admin_meeting/setupinforoomtime','SetupmeetingController@inforoomtime')->name('setup.indexroomtime');
Route::get('admin_meeting/setupinforoomtime/add','SetupmeetingController@createtime')->name('setup.addroomtime');
Route::post('admin_meeting/setupinforoomtime/save','SetupmeetingController@savetime')->name('admin.saveroomtime');
Route::get('admin_meeting/setupinforoomtime/edit/{id}','SetupmeetingController@edittime')->name('setup.editroomtime');
Route::post('admin_meeting/setupinforoomtime/update','SetupmeetingController@updatetime')->name('admin.updateroomtime');
Route::get('admin_meeting/setupinforoomtime/destroy/{id}','SetupmeetingController@destroytime');
//=========================================================================================================

Route::get('admin_checkin/setupcheckintype','SetupcheckinController@infocheckintype')->name('setup.indexcheckintype');
Route::get('admin_checkin/setupcheckintype/add','SetupcheckinController@createcheckintype')->name('setup.addcheintype');
Route::post('admin_checkin/setupcheckintype/save','SetupcheckinController@savecheckintype')->name('admin.savecheintype');
Route::get('admin_checkin/setupcheckintype/edit/{id}','SetupcheckinController@editcheckintype')->name('setup.editcheintype');
Route::post('admin_checkin/setupcheckintype/update','SetupcheckinController@updatecheckintype')->name('admin.updatecheintype');
Route::get('admin_checkin/setupcheckintype/destroy/{id}','SetupcheckinController@destroycheckintype');
//=============================================================================================

Route::get('admin_operate/setupoperatetype','SetupoperateController@infooperatetype')->name('setup.indexoperatetype');
Route::get('admin_operate/setupoperatetype/add','SetupoperateController@createoperatetype')->name('setup.addoperatetype');
Route::post('admin_operate/setupoperatetype/save','SetupoperateController@saveoperatetype')->name('admin.saveoperatetype');
Route::get('admin_operate/setupoperatetype/edit/{id}','SetupoperateController@editoperatetype')->name('setup.editoperatetype');
Route::post('admin_operate/setupoperatetype/update','SetupoperateController@updateoperatetype')->name('admin.updateoperatetype');
Route::get('admin_operate/setupoperatetype/destroy/{id}','SetupoperateController@destroyoperatetype');

Route::get('admin_operate/setupoperatejob','SetupoperateController@infooperatejob')->name('setup.indexoperatejob');
Route::get('admin_operate/setupoperatejob/add','SetupoperateController@createoperatejob')->name('setup.addoperatejob');
Route::post('admin_operate/setupoperatejob/save','SetupoperateController@saveoperatejob')->name('admin.saveoperatejob');
Route::get('admin_operate/setupoperatejob/edit/{id}','SetupoperateController@editoperatejob')->name('setup.editoperatejob');
Route::post('admin_operate/setupoperatejob/update','SetupoperateController@updateoperatejob')->name('admin.updateoperatejob');
Route::get('admin_operate/setupoperatejob/destroy/{id}','SetupoperateController@destroyoperatejob');

//================================================================================================

Route::get('admin_book/setupbooktype','SetupbookController@infobooktype')->name('setup.infobooktype');
Route::get('admin_book/setupbooktype/add','SetupbookController@createbooktype')->name('setup.createbooktype');
Route::post('admin_book/setupbooktype/save','SetupbookController@savebooktype')->name('admin.savebooktype');
Route::get('admin_book/setupbooktype/edit/{id}','SetupbookController@editbooktype')->name('setup.editbooktype');
Route::post('admin_book/setupbooktype/update','SetupbookController@updatebooktype')->name('admin.updatebooktype');
Route::get('admin_book/setupbooktype/destroy/{id}','SetupbookController@destroybooktype');


Route::get('admin_book/setupbooksecret','SetupbookController@infobooksecret')->name('setup.infobooksecret');
Route::get('admin_book/setupbooksecret/add','SetupbookController@createbooksecret')->name('setup.createbooksecret');
Route::post('admin_book/setupbooksecret/save','SetupbookController@savebooksecret')->name('admin.savebooksecret');
Route::get('admin_book/setupbooksecret/edit/{id}','SetupbookController@editbooksecret')->name('setup.editbooksecret');
Route::post('admin_book/setupbooksecret/update','SetupbookController@updatebooksecret')->name('admin.updatebooksecret');
Route::get('admin_book/setupbooksecret/destroy/{id}','SetupbookController@destroybooksecret');

Route::get('admin_book/setupbookinstant','SetupbookController@infobookinstant')->name('setup.infobookinstant');
Route::get('admin_book/setupbookinstant/add','SetupbookController@createbookinstant')->name('setup.createbookinstant');
Route::post('admin_book/setupbookinstant/save','SetupbookController@savebookinstant')->name('admin.savebookinstant');
Route::get('admin_book/setupbookinstant/edit/{id}','SetupbookController@editbookinstant')->name('setup.editbookinstant');
Route::post('admin_book/setupbookinstant/update','SetupbookController@updatebookinstant')->name('admin.updatebookinstant');
Route::get('admin_book/setupbookinstant/destroy/{id}','SetupbookController@destroybookinstant');

Route::get('admin_book/setupbookfile','SetupbookController@infobookfile')->name('setup.infobookfile');
Route::get('admin_book/setupbookfile/add','SetupbookController@createbookfile')->name('setup.createbookfile');
Route::post('admin_book/setupbookfile/save','SetupbookController@savebookfile')->name('admin.savebookfile');
Route::get('admin_book/setupbookfile/edit/{id}','SetupbookController@editbookfile')->name('setup.editbookfile');
Route::post('admin_book/setupbookfile/update','SetupbookController@updatebookfile')->name('admin.updatebookfile');
Route::get('admin_book/setupbookfile/destroy/{id}','SetupbookController@destroybookfile');

Route::get('admin_book/setupbooktypeout','SetupbookController@infobooktypeout')->name('setup.infobooktypeout');
Route::get('admin_book/setupbooktypeout/add','SetupbookController@createbooktypeout')->name('setup.createbooktypeout');
Route::post('admin_book/setupbooktypeout/save','SetupbookController@savebooktypeout')->name('admin.savebooktypeout');
Route::get('admin_book/setupbooktypeout/edit/{id}','SetupbookController@editbooktypeout')->name('setup.editbooktypeout');
Route::post('admin_book/setupbooktypeout/update','SetupbookController@updatebooktypeout')->name('admin.updatebooktypeout');
Route::get('admin_book/setupbooktypeout/destroy/{id}','SetupbookController@destroybooktypeout');

Route::get('admin_book/setupbookdepartmentadmin','SetupbookController@infobookdepartmentadmin')->name('setup.infobookdepartmentadmin');
Route::get('admin_book/setupbookdepartmentadmin/edit/{id}','SetupbookController@editbookdepartmentadmin')->name('setup.editbookdepartmentadmin');
Route::post('admin_book/setupbookdepartmentadmin/update','SetupbookController@updatebookdepartmentadmin')->name('admin.updatebookdepartmentadmin');

Route::get('admin_book/setupbookdepartmentadminsub','SetupbookController@infobookdepartmentadminsub')->name('setup.infobookdepartmentadminsub');
Route::get('admin_book/setupbookdepartmentadminsub/edit/{id}','SetupbookController@editbookdepartmentadminsub')->name('setup.editbookdepartmentadminsub');
Route::post('admin_book/setupbookdepartmentadminsub/update','SetupbookController@updatebookdepartmentadminsub')->name('admin.updatebookdepartmentadminsub');

Route::get('admin_book/setupbookorgin','SetupbookController@infobookorgin')->name('setup.infobookorgin');
Route::get('admin_book/setupbookorgin/add','SetupbookController@createbookorgin')->name('setup.createbookorgin');
Route::post('admin_book/setupbookorgin/save','SetupbookController@savebookorgin')->name('admin.savebookorgin');
Route::get('admin_book/setupbookorgin/edit/{id}','SetupbookController@editbookorgin')->name('setup.editbookorgin');
Route::post('admin_book/setupbookorgin/update','SetupbookController@updatebookorgin')->name('admin.updatebookorgin');
Route::get('admin_book/setupbookorgin/destroy/{id}','SetupbookController@destroybookorgin');
Route::get('admin_book/setupbookorgin/switchorgin','SetupbookController@switchactive')->name('setup.orgin');


Route::get('admin_book/setupbookorgout','SetupbookController@infobookorgout')->name('setup.infobookorgout');
Route::get('admin_book/setupbookorgout/add','SetupbookController@createbookorgout')->name('setup.createbookorgout');
Route::post('admin_book/setupbookorgout/save','SetupbookController@savebookorgout')->name('admin.savebookorgout');
Route::get('admin_book/setupbookorgout/edit/{id}','SetupbookController@editbookorgout')->name('setup.editbookorgout');
Route::post('admin_book/setupbookorgout/update','SetupbookController@updatebookorgout')->name('admin.updatebookorgout');
Route::get('admin_book/setupbookorgout/destroy/{id}','SetupbookController@destroybookorgout');
Route::get('admin_book/setupbookorgout/switchorgout','SetupbookController@switchactiveorgout')->name('setup.orgout');
//=============================================================================================


Route::get('admin_car/setupcartype','SetupcarController@infocartype')->name('setup.infocartype');
Route::get('admin_car/setupcartype/add','SetupcarController@createcartype')->name('setup.createcartype');
Route::post('admin_car/setupcartype/save','SetupcarController@savecartype')->name('admin.savecartype');
Route::get('admin_car/setupcartype/edit/{id}','SetupcarController@editcartype')->name('setup.editcartype');
Route::post('admin_car/setupcartype/update','SetupcarController@updatecartype')->name('admin.updatecartype');
Route::get('admin_car/setupcartype/destroy/{id}','SetupcarController@destroycartype');


Route::get('admin_car/setupcarstatus','SetupcarController@infocarstatus')->name('setup.infocarstatus');
Route::get('admin_car/setupcarstatus/add','SetupcarController@createcarstatus')->name('setup.createcarstatus');
Route::post('admin_car/setupcarstatus/save','SetupcarController@savecarstatus')->name('admin.savecarstatus');
Route::get('admin_car/setupcarstatus/edit/{id}','SetupcarController@editcarstatus')->name('setup.editcarstatus');
Route::post('admin_car/setupcarstatus/update','SetupcarController@updatecarstatus')->name('admin.updatecarstatus');
Route::get('admin_car/setupcarstatus/destroy/{id}','SetupcarController@destroycarstatus');

Route::get('admin_car/setupcarstyle','SetupcarController@infocarstyle')->name('setup.infocarstyle');
Route::get('admin_car/setupcarstyle/add','SetupcarController@createcarstyle')->name('setup.createcarstyle');
Route::post('admin_car/setupcarstyle/save','SetupcarController@savecarstyle')->name('admin.savecarstyle');
Route::get('admin_car/setupcarstyle/edit/{id}','SetupcarController@editcarstyle')->name('setup.editcarstyle');
Route::post('admin_car/setupcarstyle/update','SetupcarController@updatecarstyle')->name('admin.updatecarstyle');
Route::get('admin_car/setupcarstyle/destroy/{id}','SetupcarController@destroycarstyle');


Route::get('admin_car/setupcarbrand','SetupcarController@infocarbrand')->name('setup.infocarbrand');
Route::get('admin_car/setupcarbrand/add','SetupcarController@createcarbrand')->name('setup.createcarbrand');
Route::post('admin_car/setupcarbrand/save','SetupcarController@savecarbrand')->name('admin.savecarbrand');
Route::get('admin_car/setupcarbrand/edit/{id}','SetupcarController@editcarbrand')->name('setup.editcarbrand');
Route::post('admin_car/setupcarbrand/update','SetupcarController@updatecarbrand')->name('admin.updatecarbrand');
Route::get('admin_car/setupcarbrand/destroy/{id}','SetupcarController@destroycarbrand');

Route::get('admin_car/setupcarmachinbrand','SetupcarController@infocarmachinbrand')->name('setup.infocarmachinbrand');
Route::get('admin_car/setupcarmachinbrand/add','SetupcarController@createcarmachinbrand')->name('setup.createcarmachinbrand');
Route::post('admin_car/setupcarmachinbrand/save','SetupcarController@savecarmachinbrand')->name('admin.savecarmachinbrand');
Route::get('admin_car/setupcarmachinbrand/edit/{id}','SetupcarController@editcarmachinbrand')->name('setup.editcarmachinbrand');
Route::post('admin_car/setupcarmachinbrand/update','SetupcarController@updatecarmachinbrand')->name('admin.updatecarmachinbrand');
Route::get('admin_car/setupcarmachinbrand/destroy/{id}','SetupcarController@destroycarmachinbrand');


Route::get('admin_car/setupcarpower','SetupcarController@infocarpower')->name('setup.infocarpower');
Route::get('admin_car/setupcarpower/add','SetupcarController@createcarpower')->name('setup.createcarpower');
Route::post('admin_car/setupcarpower/save','SetupcarController@savecarpower')->name('admin.savecarpower');
Route::get('admin_car/setupcarpower/edit/{id}','SetupcarController@editcarpower')->name('setup.editcarpower');
Route::post('admin_car/setupcarpower/update','SetupcarController@updatecarpower')->name('admin.updatecarpower');
Route::get('admin_car/setupcarpower/destroy/{id}','SetupcarController@destroycarpower');

Route::get('admin_car/setupcardriver','SetupcarController@infocardriver')->name('setup.infocardriver');
Route::get('admin_car/setupcardriver/add','SetupcarController@createcardriver')->name('setup.createcardriver');
Route::post('admin_car/setupcardriver/save','SetupcarController@savecardriver')->name('admin.savecardriver');
Route::get('admin_car/setupcardriver/edit/{id}','SetupcarController@editcardriver')->name('setup.editcardriver');
Route::post('admin_car/setupcardriver/update','SetupcarController@updatecardriver')->name('admin.updatecardriver');
Route::get('admin_car/setupcardriver/destroy/{id}','SetupcarController@destroycardriver');
Route::get('admin_car/setupcardriver/switchdriver','SetupcarController@switchactive')->name('setup.driver');

Route::get('admin_car/setupcaraccessory','SetupcarController@infocaraccessory')->name('setup.infocaraccessory');
Route::get('admin_car/setupcaraccessory/add','SetupcarController@createcaraccessory')->name('setup.createcaraccessory');
Route::post('admin_car/setupcaraccessory/save','SetupcarController@savecaraccessory')->name('admin.savecaraccessory');
Route::get('admin_car/setupcaraccessory/edit/{id}','SetupcarController@editcaraccessory')->name('setup.editcaraccessory');
Route::post('admin_car/setupcaraccessory/update','SetupcarController@updatecaraccessory')->name('admin.updatecaraccessory');
Route::get('admin_car/setupcaraccessory/destroy/{id}','SetupcarController@destroycaraccessory');

Route::get('admin_car/setupcarappointlocate','SetupcarController@infoappointlocate')->name('setup.infoappointlocate');
Route::get('admin_car/setupcarappointlocate/add','SetupcarController@createappointlocate')->name('setup.createappointlocate');
Route::post('admin_car/setupcarappointlocate/save','SetupcarController@saveappointlocate')->name('admin.saveappointlocate');
Route::get('admin_car/setupcarappointlocate/edit/{id}','SetupcarController@editappointlocate')->name('setup.editappointlocate');
Route::post('admin_car/setupcarappointlocate/update','SetupcarController@updateappointlocate')->name('admin.updateappointlocate');
Route::get('admin_car/setupcarappointlocate/destroy/{id}','SetupcarController@destroyappointlocate');
//===================================================================================================

Route::get('admin_safe/setupsafeservice','SetupsafeserviceController@setupsafeservice')->name('setup.setupsafeservice');
Route::get('admin_safe/setupsafeservice/add','SetupsafeserviceController@createsafeservice')->name('setup.createsafeservice');
Route::post('admin_safe/setupsafeservice/save','SetupsafeserviceController@savesafeservice')->name('admin.savesafeservice');
Route::get('admin_safe/setupsafeservice/edit/{id}','SetupsafeserviceController@editsafeservice')->name('setup.editsafeservice');
Route::post('admin_safe/setupsafeservice/update','SetupsafeserviceController@updatesafeservice')->name('admin.updatesafeservice');
Route::get('admin_safe/setupsafeservice/destroy/{id}','SetupsafeserviceController@destroysafeservice');

Route::get('admin_safe/setupevent','SetupsafeserviceController@setupevent')->name('setup.setupevent');
Route::get('admin_safe/setupevent/add','SetupsafeserviceController@createevent')->name('setup.createevent');
Route::post('admin_safe/setupevent/save','SetupsafeserviceController@saveevent')->name('admin.saveevent');
Route::get('admin_safe/setupevent/edit/{id}','SetupsafeserviceController@editevent')->name('setup.editevent');
Route::post('admin_safe/setupevent/update','SetupsafeserviceController@updateevent')->name('admin.updateevent');
Route::get('admin_safe/setupevent/destroy/{id}','SetupsafeserviceController@destroyevent');

Route::get('admin_safe/setupsafelocation','SetupsafeserviceController@setupsafelocation')->name('setup.setupsafelocation');
Route::get('admin_safe/setupsafelocation/add','SetupsafeserviceController@createsafelocation')->name('setup.createsafelocation');
Route::post('admin_safe/setupsafelocation/save','SetupsafeserviceController@savesafelocation')->name('admin.savesafelocation');
Route::get('admin_safe/setupsafelocation/edit/{id}','SetupsafeserviceController@editsafelocation')->name('setup.editsafelocation');
Route::post('admin_safe/setupsafelocation/update','SetupsafeserviceController@updatesafelocation')->name('admin.updatesafelocation');
Route::get('admin_safe/setupsafelocation/destroy/{id}','SetupsafeserviceController@destroysafelocation');
//=====================================================================================================
Route::get('admin_asset_supplies/setupsuppliespurchase','SetupassetsupController@infosuppliespurchase')->name('setup.infosuppliespurchase');
Route::post('admin_asset_supplies/setupsuppliespurchase/update','SetupassetsupController@updatesuppliespurchase')->name('setup.updatesuppliespurchase');

Route::get('admin_asset_supplies/setupsuppliestypekind','SetupassetsupController@infosuppliestypekind')->name('setup.infosuppliestypekind');
Route::get('admin_asset_supplies/setupsuppliestypekind/add','SetupassetsupController@createsuppliestypekind')->name('setup.createsuppliestypekind');
Route::post('admin_asset_supplies/setupsuppliestypekind/save','SetupassetsupController@savesuppliestypekind')->name('admin.savesuppliestypekind');
Route::get('admin_asset_supplies/setupsuppliestypekind/edit/{id}','SetupassetsupController@editsuppliestypekind')->name('setup.editsuppliestypekind');
Route::post('admin_asset_supplies/setupsuppliestypekind/update','SetupassetsupController@updatesuppliestypekind')->name('admin.updatesuppliestypekind');
Route::get('admin_asset_supplies/setupsuppliestypekind/destroy/{id}','SetupassetsupController@destroysuppliestypekind');

Route::get('admin_asset_supplies/setupsuppliesunit','SetupassetsupController@infosuppliesunit')->name('setup.infosuppliesunit');
Route::get('admin_asset_supplies/setupsuppliesunit/add','SetupassetsupController@createsuppliesunit')->name('setup.createsuppliesunit');
Route::post('admin_asset_supplies/setupsuppliesunit/save','SetupassetsupController@savesuppliesunit')->name('admin.savesuppliesunit');
Route::get('admin_asset_supplies/setupsuppliesunit/edit/{id}','SetupassetsupController@editsuppliesunit')->name('setup.editsuppliesunit');
Route::post('admin_asset_supplies/setupsuppliesunit/update','SetupassetsupController@updatesuppliesunit')->name('admin.updatesuppliesunit');
Route::get('admin_asset_supplies/setupsuppliesunit/destroy/{id}','SetupassetsupController@destroysuppliesunit');
Route::get('admin_asset_supplies/setupsuppliesunit/switchunit','SetupassetsupController@switchactiveunit')->name('setup.unit');

Route::get('admin_asset_supplies/setupsuppliestypelist','SetupassetsupController@infosuppliestypelist')->name('setup.infosuppliestypelist');
Route::get('admin_asset_supplies/setupsuppliestypelist/add','SetupassetsupController@createsuppliestypelist')->name('setup.createsuppliestypelist');
Route::post('admin_asset_supplies/setupsuppliestypelist/save','SetupassetsupController@savesuppliestypelist')->name('admin.savesuppliestypelist');
Route::get('admin_asset_supplies/setupsuppliestypelist/edit/{id}','SetupassetsupController@editsuppliestypelist')->name('setup.editsuppliestypelist');
Route::post('admin_asset_supplies/setupsuppliestypelist/update','SetupassetsupController@updatesuppliestypelist')->name('admin.updatesuppliestypelist');
Route::get('admin_asset_supplies/setupsuppliestypelist/destroy/{id}','SetupassetsupController@destroysuppliestypelist');

Route::get('admin_asset_supplies/setupsuppliestypebudget','SetupassetsupController@infosuppliestypebudget')->name('setup.infosuppliestypebudget');
Route::get('admin_asset_supplies/setupsuppliestypebudget/add','SetupassetsupController@createsuppliestypebudget')->name('setup.createsuppliestypebudget');
Route::post('admin_asset_supplies/setupsuppliestypebudget/save','SetupassetsupController@savesuppliestypebudget')->name('admin.savesuppliestypebudget');
Route::get('admin_asset_supplies/setupsuppliestypebudget/edit/{id}','SetupassetsupController@editsuppliestypebudget')->name('setup.editsuppliestypebudget');
Route::post('admin_asset_supplies/setupsuppliestypebudget/update','SetupassetsupController@updatesuppliestypebudget')->name('admin.updatesuppliestypebudget');
Route::get('admin_asset_supplies/setupsuppliestypebudget/destroy/{id}','SetupassetsupController@destroysuppliestypebudget');
Route::get('admin_asset_supplies/setupsuppliestypebudget/switchactivebudget','SetupassetsupController@switchactivebudget')->name('setup.typebudget');


//----

Route::get('admin_asset_supplies/setupsuppliesdepsubsup','SetupassetsupController@infosuppliesdepsubsup')->name('setup.infosuppliesdepsubsup');

Route::get('admin_asset_supplies/setupsuppliesbrand','SetupassetsupController@infosuppliesbrand')->name('setup.infosuppliesbrand');
Route::get('admin_asset_supplies/setupsuppliesbrand/add','SetupassetsupController@createsuppliesbrand')->name('setup.createsuppliesbrand');
Route::post('admin_asset_supplies/setupsuppliesbrand/save','SetupassetsupController@savesuppliesbrand')->name('admin.savesuppliesbrand');
Route::get('admin_asset_supplies/setupsuppliesbrand/edit/{id}','SetupassetsupController@editsuppliesbrand')->name('setup.editsuppliesbrand');
Route::post('admin_asset_supplies/setupsuppliesbrand/update','SetupassetsupController@updatesuppliesbrand')->name('admin.updatesuppliesbrand');
Route::get('admin_asset_supplies/setupsuppliesbrand/destroy/{id}','SetupassetsupController@destroysuppliesbrand');


Route::get('admin_asset_supplies/setupsuppliesmodel','SetupassetsupController@infosuppliesmodel')->name('setup.infosuppliesmodel');
Route::get('admin_asset_supplies/setupsuppliesmodel/add','SetupassetsupController@createsuppliesmodel')->name('setup.createsuppliesmodel');
Route::post('admin_asset_supplies/setupsuppliesmodel/save','SetupassetsupController@savesuppliesmodel')->name('admin.savesuppliesmodel');
Route::get('admin_asset_supplies/setupsuppliesmodel/edit/{id}','SetupassetsupController@editsuppliesmodel')->name('setup.editsuppliesmodel');
Route::post('admin_asset_supplies/setupsuppliesmodel/update','SetupassetsupController@updatesuppliesmodel')->name('admin.updatesuppliesmodel');
Route::get('admin_asset_supplies/setupsuppliesmodel/destroy/{id}','SetupassetsupController@destroysuppliesmodel');


Route::get('admin_asset_supplies/setupsuppliesordertype','SetupassetsupController@infosuppliesordertype')->name('setup.infosuppliesordertype');
Route::get('admin_asset_supplies/setupsuppliesordertype/add','SetupassetsupController@createsuppliesordertype')->name('setup.createsuppliesordertype');
Route::post('admin_asset_supplies/setupsuppliesordertype/save','SetupassetsupController@savesuppliesordertype')->name('admin.savesuppliesordertype');
Route::get('admin_asset_supplies/setupsuppliesordertype/edit/{id}','SetupassetsupController@editsuppliesordertype')->name('setup.editsuppliesordertype');
Route::post('admin_asset_supplies/setupsuppliesordertype/update','SetupassetsupController@updatesuppliesordertype')->name('admin.updatesuppliesordertype');
Route::get('admin_asset_supplies/setupsuppliesordertype/destroy/{id}','SetupassetsupController@destroysuppliesordertype');

Route::get('admin_asset_supplies/setupsuppliespriceref','SetupassetsupController@infosuppliespriceref')->name('setup.infosuppliespriceref');
Route::get('admin_asset_supplies/setupsuppliespriceref/add','SetupassetsupController@createsuppliespriceref')->name('setup.createsuppliespriceref');
Route::post('admin_asset_supplies/setupsuppliespriceref/save','SetupassetsupController@savesuppliespriceref')->name('admin.savesuppliespriceref');
Route::get('admin_asset_supplies/setupsuppliespriceref/edit/{id}','SetupassetsupController@editsuppliespriceref')->name('setup.editsuppliespriceref');
Route::post('admin_asset_supplies/setupsuppliespriceref/update','SetupassetsupController@updatesuppliespriceref')->name('admin.updatesuppliespriceref');
Route::get('admin_asset_supplies/setupsuppliespriceref/destroy/{id}','SetupassetsupController@destroysuppliespriceref');

Route::get('admin_asset_supplies/setupsuppliesposition','SetupassetsupController@infosuppliesposition')->name('setup.infosuppliesposition');
Route::get('admin_asset_supplies/setupsuppliesposition/add','SetupassetsupController@createsuppliesposition')->name('setup.createsuppliesposition');
Route::post('admin_asset_supplies/setupsuppliesposition/save','SetupassetsupController@savesuppliesposition')->name('admin.savesuppliesposition');
Route::get('admin_asset_supplies/setupsuppliesposition/edit/{id}','SetupassetsupController@editsuppliesposition')->name('setup.editsuppliesposition');
Route::post('admin_asset_supplies/setupsuppliesposition/update','SetupassetsupController@updatesuppliesposition')->name('admin.updatesuppliesposition');
Route::get('admin_asset_supplies/setupsuppliesposition/destroy/{id}','SetupassetsupController@destroysuppliesposition');

Route::get('admin_asset_supplies/setupsuppliesstatusregis','SetupassetsupController@infosuppliesstatusregis')->name('setup.infosuppliesstatusregis');
Route::get('admin_asset_supplies/setupsuppliesstatusregis/add','SetupassetsupController@createsuppliesstatusregis')->name('setup.createsuppliesstatusregis');
Route::post('admin_asset_supplies/setupsuppliesstatusregis/save','SetupassetsupController@savesuppliesstatusregis')->name('admin.savesuppliesstatusregis');
Route::get('admin_asset_supplies/setupsuppliesstatusregis/edit/{id}','SetupassetsupController@editsuppliesstatusregis')->name('setup.editsuppliesstatusregis');
Route::post('admin_asset_supplies/setupsuppliesstatusregis/update','SetupassetsupController@updatesuppliesstatusregis')->name('admin.updatesuppliesstatusregis');
Route::get('admin_asset_supplies/setupsuppliesstatusregis/destroy/{id}','SetupassetsupController@destroysuppliesstatusregis');

Route::get('admin_asset_supplies/setupsuppliesmethod','SetupassetsupController@infosuppliesmethod')->name('setup.infosuppliesmethod');
Route::get('admin_asset_supplies/setupsuppliesmethod/add','SetupassetsupController@createsuppliesmethod')->name('setup.createsuppliesmethod');
Route::post('admin_asset_supplies/setupsuppliesmethod/save','SetupassetsupController@savesuppliesmethod')->name('admin.savesuppliesmethod');
Route::get('admin_asset_supplies/setupsuppliesmethod/edit/{id}','SetupassetsupController@editsuppliesmethod')->name('setup.editsuppliesmethod');
Route::post('admin_asset_supplies/setupsuppliesmethod/update','SetupassetsupController@updatesuppliesmethod')->name('admin.updatesuppliesmethod');
Route::get('admin_asset_supplies/setupsuppliesmethod/destroy/{id}','SetupassetsupController@destroysuppliesmethod');


Route::get('admin_asset_supplies/setupsuppliesdecline','SetupassetsupController@infosuppliesdecline')->name('setup.infosuppliesdecline');
Route::get('admin_asset_supplies/setupsuppliesdecline/add','SetupassetsupController@createsuppliesdecline')->name('setup.createsuppliesdecline');
Route::post('admin_asset_supplies/setupsuppliesdecline/save','SetupassetsupController@savesuppliesdecline')->name('admin.savesuppliesdecline');
Route::get('admin_asset_supplies/setupsuppliesdecline/edit/{id}','SetupassetsupController@editsuppliesdecline')->name('setup.editsuppliesdecline');
Route::post('admin_asset_supplies/setupsuppliesdecline/update','SetupassetsupController@updatesuppliesdecline')->name('admin.updatesuppliesdecline');
Route::get('admin_asset_supplies/setupsuppliesdecline/destroy/{id}','SetupassetsupController@destroysuppliesdecline');

Route::get('admin_asset_supplies/setupsuppliestrimart','SetupassetsupController@infosuppliestrimart')->name('setup.infosuppliestrimart');
Route::get('admin_asset_supplies/setupsuppliestrimart/add','SetupassetsupController@createsuppliestrimart')->name('setup.createsuppliestrimart');
Route::post('admin_asset_supplies/setupsuppliestrimart/save','SetupassetsupController@savesuppliestrimart')->name('admin.savesuppliestrimart');
Route::get('admin_asset_supplies/setupsuppliestrimart/edit/{id}','SetupassetsupController@editsuppliestrimart')->name('setup.editsuppliestrimart');
Route::post('admin_asset_supplies/setupsuppliestrimart/update','SetupassetsupController@updatesuppliestrimart')->name('admin.updatesuppliestrimart');
Route::get('admin_asset_supplies/setupsuppliestrimart/destroy/{id}','SetupassetsupController@destroysuppliestrimart');
Route::get('admin_asset_supplies/setupsuppliestrimart/switchactivetrimart','SetupassetsupController@switchactivetrimart')->name('setup.trimart');

Route::get('admin_asset_supplies/setupsuppliesbuy','SetupassetsupController@infosuppliesbuy')->name('setup.infosuppliesbuy');
Route::get('admin_asset_supplies/setupsuppliesbuy/add','SetupassetsupController@createsuppliesbuy')->name('setup.createsuppliesbuy');
Route::post('admin_asset_supplies/setupsuppliesbuy/save','SetupassetsupController@savesuppliesbuy')->name('admin.savesuppliesbuy');
Route::get('admin_asset_supplies/setupsuppliesbuy/edit/{id}','SetupassetsupController@editsuppliesbuy')->name('setup.editsuppliesbuy');
Route::post('admin_asset_supplies/setupsuppliesbuy/update','SetupassetsupController@updatesuppliesbuy')->name('admin.updatesuppliesbuy');
Route::get('admin_asset_supplies/setupsuppliesbuy/destroy/{id}','SetupassetsupController@destroysuppliesbuy');
Route::get('admin_asset_supplies/setupsuppliesbuy/switchactivebuy','SetupassetsupController@switchactivebuy')->name('setup.buy');

Route::get('admin_asset_supplies/setupsuppliesbuy','SetupassetsupController@infosuppliesbuy')->name('setup.infosuppliesbuy');
Route::get('admin_asset_supplies/setupsuppliesbuy/add','SetupassetsupController@createsuppliesbuy')->name('setup.createsuppliesbuy');
Route::post('admin_asset_supplies/setupsuppliesbuy/save','SetupassetsupController@savesuppliesbuy')->name('admin.savesuppliesbuy');
Route::get('admin_asset_supplies/setupsuppliesbuy/edit/{id}','SetupassetsupController@editsuppliesbuy')->name('setup.editsuppliesbuy');
Route::post('admin_asset_supplies/setupsuppliesbuy/update','SetupassetsupController@updatesuppliesbuy')->name('admin.updatesuppliesbuy');
Route::get('admin_asset_supplies/setupsuppliesbuy/destroy/{id}','SetupassetsupController@destroysuppliesbuy');

Route::get('admin_asset_supplies/setupsuppliestypemaster','SetupassetsupController@infosuppliestypemaster')->name('setup.infosuppliestypemaster');
Route::get('admin_asset_supplies/setupsuppliestypemaster/add','SetupassetsupController@createsuppliestypemaster')->name('setup.createsuppliestypemaster');
Route::post('admin_asset_supplies/setupsuppliestypemaster/save','SetupassetsupController@savesuppliestypemaster')->name('admin.savesuppliestypemaster');
Route::get('admin_asset_supplies/setupsuppliestypemaster/edit/{id}','SetupassetsupController@editsuppliestypemaster')->name('setup.editsuppliestypemaster');
Route::post('admin_asset_supplies/setupsuppliestypemaster/update','SetupassetsupController@updatesuppliestypemaster')->name('admin.updatesuppliestypemaster');
Route::get('admin_asset_supplies/setupsuppliestypemaster/destroy/{id}','SetupassetsupController@destroysuppliestypemaster');

//---==
Route::get('admin_asset_supplies/setupsuppliestype','SetupassetsupController@infosuppliestype')->name('setup.infosuppliestype');
Route::get('admin_asset_supplies/setupsuppliestype/add','SetupassetsupController@createsuppliestype')->name('setup.createsuppliestype');
Route::post('admin_asset_supplies/setupsuppliestype/save','SetupassetsupController@savesuppliestype')->name('admin.savesuppliestype');
Route::get('admin_asset_supplies/setupsuppliestype/edit/{id}','SetupassetsupController@editsuppliestype')->name('setup.editsuppliestype');
Route::post('admin_asset_supplies/setupsuppliestype/update','SetupassetsupController@updatesuppliestype')->name('admin.updatesuppliestype');
Route::get('admin_asset_supplies/setupsuppliestype/destroy/{id}','SetupassetsupController@destroysuppliestype');
Route::get('admin_asset_supplies/setupsuppliestype/switchactivetype','SetupassetsupController@switchactivetype')->name('setup.suppliestype');

Route::get('admin_asset_supplies/setupsuppliestypesub/{idtype}','SetupassetsupController@infosuppliestypesub')->name('setup.infosuppliestypesub');
Route::get('admin_asset_supplies/setupsuppliestypesub/add/{idtype}','SetupassetsupController@createsuppliestypesub')->name('setup.createsuppliestypesub');
Route::post('admin_asset_supplies/setupsuppliestypesubsave','SetupassetsupController@savesuppliestypesub')->name('admin.savesuppliestypesub');
Route::get('admin_asset_supplies/setupsuppliestypesub/edit/{id}/{idtype}','SetupassetsupController@editsuppliestypesub')->name('setup.editsuppliestypesub');
Route::post('admin_asset_supplies/setupsuppliestypesubupdate','SetupassetsupController@updatesuppliestypesub')->name('admin.updatesuppliestypesub');
Route::get('admin_asset_supplies/setupsuppliestypesub/destroy/{id}/{idtype}','SetupassetsupController@destroysuppliestypesub');
Route::get('admin_asset_supplies/setupsuppliestypesubswitch/switchactivetypesub','SetupassetsupController@switchactivetypesub')->name('setup.suppliestypesub');

//---==
Route::get('admin_asset_supplies/setupsuppliesboardlist','SetupassetsupController@infosuppliesboardlist')->name('setup.infosuppliesboardlist');
Route::get('admin_asset_supplies/setupsuppliesboardlist/add','SetupassetsupController@createsuppliesboardlist')->name('setup.createsuppliesboardlist');
Route::post('admin_asset_supplies/setupsuppliesboardlist/save','SetupassetsupController@savesuppliesboardlist')->name('admin.savesuppliesboardlist');
Route::get('admin_asset_supplies/setupsuppliesboardlist/edit/{id}','SetupassetsupController@editsuppliesboardlist')->name('setup.editsuppliesboardlist');
Route::post('admin_asset_supplies/setupsuppliesboardlist/update','SetupassetsupController@updatesuppliesboardlist')->name('admin.updatesuppliesboardlist');
Route::get('admin_asset_supplies/setupsuppliesboardlist/destroy/{id}','SetupassetsupController@destroysuppliesboardlist');

Route::get('admin_asset_supplies/setupsuppliesboardlistperson/{idlist}','SetupassetsupController@infosuppliesboardlistperson')->name('setup.infosuppliesboardlistperson');
Route::get('admin_asset_supplies/setupsuppliesboardlistperson/add/{idlist}','SetupassetsupController@createsuppliesboardlistperson')->name('setup.createsuppliesboardlistperson');
Route::post('admin_asset_supplies/setupsuppliesboardlistpersonsave','SetupassetsupController@savesuppliesboardlistperson')->name('admin.savesuppliesboardlistperson');
Route::get('admin_asset_supplies/setupsuppliesboardlistperson/edit/{id}/{idlist}','SetupassetsupController@editsuppliesboardlistperson')->name('setup.editsuppliesboardlistperson');
Route::post('admin_asset_supplies/setupsuppliesboardlistpersonupdate','SetupassetsupController@updatesuppliesboardlistperson')->name('admin.updatesuppliesboardlistperson');
Route::get('admin_asset_supplies/setupsuppliesboardlistperson/destroy/{id}/{idlist}','SetupassetsupController@destroysuppliesboardlistperson');

//---==อาคาร
Route::get('admin_asset_supplies/setupsupplieslocation','SetupassetsupController@infosupplieslocation')->name('setup.infosupplieslocation');
Route::get('admin_asset_supplies/setupsupplieslocation/add','SetupassetsupController@createsupplieslocation')->name('setup.createsupplieslocation');
Route::post('admin_asset_supplies/setupsupplieslocation/save','SetupassetsupController@savesupplieslocation')->name('admin.savesupplieslocation');
Route::get('admin_asset_supplies/setupsupplieslocation/edit/{id}','SetupassetsupController@editsupplieslocation')->name('setup.editsupplieslocation');
Route::post('admin_asset_supplies/setupsupplieslocation/update','SetupassetsupController@updatesupplieslocation')->name('admin.updatesupplieslocation');
Route::get('admin_asset_supplies/setupsupplieslocation/destroy/{id}','SetupassetsupController@destroysupplieslocation');

Route::get('admin_asset_supplies/setupsupplieslocationlevel/{idlocation}','SetupassetsupController@infosupplieslocationlevel')->name('setup.infosupplieslocationlevel');
Route::get('admin_asset_supplies/setupsupplieslocationlevel/add/{idlocation}','SetupassetsupController@createsupplieslocationlevel')->name('setup.createsupplieslocationlevel');
Route::post('admin_asset_supplies/setupsupplieslocationlevelsave','SetupassetsupController@savesupplieslocationlevel')->name('admin.savesupplieslocationlevel');
Route::get('admin_asset_supplies/setupsupplieslocationlevel/edit/{id}/{idlocation}','SetupassetsupController@editsupplieslocationlevel')->name('setup.editsupplieslocationlevel');
Route::post('admin_asset_supplies/setupsupplieslocationlevelupdate','SetupassetsupController@updatesupplieslocationlevel')->name('admin.updatesupplieslocationlevel');
Route::get('admin_asset_supplies/setupsupplieslocationlevel/destroy/{id}/{idlocation}','SetupassetsupController@destroysupplieslocationlevel');

Route::get('admin_asset_supplies/setupsupplieslocationlevelroom/{idlocation}/{idlocationlevel}','SetupassetsupController@infosupplieslocationlevelroom')->name('setup.infosupplieslocationlevelroom');
Route::get('admin_asset_supplies/setupsupplieslocationlevelroom/add/{idlocation}/{idlocationlevel}','SetupassetsupController@createsupplieslocationlevelroom')->name('setup.createsupplieslocationlevelroom');
Route::post('admin_asset_supplies/setupsupplieslocationlevelroomsave','SetupassetsupController@savesupplieslocationlevelroom')->name('admin.savesupplieslocationlevelroom');
Route::get('admin_asset_supplies/setupsupplieslocationlevelroom/edit/{id}/{idlocation}/{idlocationlevel}','SetupassetsupController@editsupplieslocationlevelroom')->name('setup.editsupplieslocationlevelroom');
Route::post('admin_asset_supplies/setupsupplieslocationlevelroomupdate','SetupassetsupController@updatesupplieslocationlevelroom')->name('admin.updatesupplieslocationlevelroom');
Route::get('admin_asset_supplies/setupsupplieslocationlevelroom/destroy/{id}/{idlocation}/{idlocationlevel}','SetupassetsupController@destroysupplieslocationlevelroom');
//------------------------------------------------------------D---------------------
Route::get('admin_asset_supplies/setupsuppliesconpremise','SetupassetsupController@infosuppliesconpremise')->name('setup.infosuppliesconpremise');
Route::get('admin_asset_supplies/setupsuppliesconpremise/add','SetupassetsupController@createsuppliesconpremise')->name('setup.createsuppliesconpremise');
Route::post('admin_asset_supplies/setupsuppliesconpremise/save','SetupassetsupController@savesuppliesconpremise')->name('admin.savesuppliesconpremise');
Route::get('admin_asset_supplies/setupsuppliesconpremise/edit/{id}','SetupassetsupController@editsuppliesconpremise')->name('setup.editsuppliesconpremise');
Route::post('admin_asset_supplies/setupsuppliesconpremise/update','SetupassetsupController@updatesuppliesconpremise')->name('admin.updatesuppliesconpremise');
Route::get('admin_asset_supplies/setupsuppliesconpremise/destroy/{id}','SetupassetsupController@destroysuppliesconpremise');

//-------------------------------------------------------------------------------------------

Route::get('admin_asset_supplies/setupsuppliescontypelist','SetupassetsupController@infosuppliescontypelist')->name('setup.infosuppliescontypelist');
Route::get('admin_asset_supplies/setupsuppliescontypelist/add','SetupassetsupController@createsuppliescontypelist')->name('setup.createsuppliescontypelist');
Route::post('admin_asset_supplies/setupsuppliescontypelist/save','SetupassetsupController@savesuppliescontypelist')->name('admin.savesuppliescontypelist');
Route::get('admin_asset_supplies/setupsuppliescontypelist/edit/{id}','SetupassetsupController@editsuppliescontypelist')->name('setup.editsuppliescontypelist');
Route::post('admin_asset_supplies/setupsuppliescontypelist/update','SetupassetsupController@updatesuppliescontypelist')->name('admin.updatesuppliescontypelist');
Route::get('admin_asset_supplies/setupsuppliescontypelist/destroy/{id}','SetupassetsupController@destroysuppliescontypelist');

//-------------------------------------------------------------------------------------------

Route::get('admin_asset_supplies/setupsuppliesexpiretype','SetupassetsupController@infosuppliesexpiretype')->name('setup.infosuppliesexpiretype');
Route::get('admin_asset_supplies/setupsuppliesexpiretype/add','SetupassetsupController@createsuppliesexpiretype')->name('setup.createsuppliesexpiretype');
Route::post('admin_asset_supplies/setupsuppliesexpiretype/save','SetupassetsupController@savesuppliesexpiretype')->name('admin.savesuppliesexpiretype');
Route::get('admin_asset_supplies/setupsuppliesexpiretype/edit/{id}','SetupassetsupController@editsuppliesexpiretype')->name('setup.editsuppliesexpiretype');
Route::post('admin_asset_supplies/setupsuppliesexpiretype/update','SetupassetsupController@updatesuppliesexpiretype')->name('admin.updatesuppliesexpiretype');
Route::get('admin_asset_supplies/setupsuppliesexpiretype/destroy/{id}','SetupassetsupController@destroysuppliesexpiretype');

//-------------------------------------------------------------------------------------------

Route::get('admin_asset_supplies/setupsuppliessendmoneyitem','SetupassetsupController@infosuppliessendmoneyitem')->name('setup.infosuppliessendmoneyitem');
Route::get('admin_asset_supplies/setupsuppliessendmoneyitem/add','SetupassetsupController@createsuppliessendmoneyitem')->name('setup.createsuppliessendmoneyitem');
Route::post('admin_asset_supplies/setupsuppliessendmoneyitem/save','SetupassetsupController@savesuppliessendmoneyitem')->name('admin.savesuppliessendmoneyitem');
Route::get('admin_asset_supplies/setupsuppliessendmoneyitem/edit/{id}','SetupassetsupController@editsuppliessendmoneyitem')->name('setup.editsuppliessendmoneyitem');
Route::post('admin_asset_supplies/setupsuppliessendmoneyitem/update','SetupassetsupController@updatesuppliessendmoneyitem')->name('admin.updatesuppliessendmoneyitem');
Route::get('admin_asset_supplies/setupsuppliessendmoneyitem/destroy/{id}','SetupassetsupController@destroysuppliessendmoneyitem');
//-------------------------------------------------------------------------------------------

Route::get('admin_asset_supplies/setupsuppliesvendor','SetupassetsupController@infosuppliesvendor')->name('setup.infosuppliesvendor');
Route::get('admin_asset_supplies/setupsuppliesvendor/add','SetupassetsupController@createsuppliesvendor')->name('setup.createsuppliesvendor');
Route::post('admin_asset_supplies/setupsuppliesvendor/save','SetupassetsupController@savesuppliesvendor')->name('admin.savesuppliesvendor');
Route::get('admin_asset_supplies/setupsuppliesvendor/edit/{id}','SetupassetsupController@editsuppliesvendor')->name('setup.editsuppliesvendor');
Route::post('admin_asset_supplies/setupsuppliesvendor/update','SetupassetsupController@updatesuppliesvendor')->name('admin.updatesuppliesvendor');
Route::get('admin_asset_supplies/setupsuppliesvendor/destroy/{id}','SetupassetsupController@destroysuppliesvendor');
Route::get('admin_asset_supplies/setupsuppliesvendor/switchactivevendor','SetupassetsupController@switchactivevendor')->name('setup.vendor');

Route::get('admin_asset_supplies/checktax','SetupassetsupController@checktax')->name('check.checktax');
Route::get('admin_asset_supplies/submitbutton','SetupassetsupController@submitbutton')->name('check.submitbutton');
//-------------------------------------------------------------------------------------------

Route::get('admin_warehouse/setupsuppliesinven','SetupassetsupController@infosuppliesinven')->name('setup.infosuppliesinven'); //คลังวัสดุ
Route::get('admin_warehouse/setupsuppliesinven/add','SetupassetsupController@createsuppliesinven')->name('setup.createsuppliesinven');
Route::post('admin_warehouse/setupsuppliesinven/save','SetupassetsupController@savesuppliesinven')->name('admin.savesuppliesinven');
Route::get('admin_warehouse/setupsuppliesinven/edit/{id}','SetupassetsupController@editsuppliesinven')->name('setup.editsuppliesinven');
Route::post('admin_warehouse/setupsuppliesinven/update','SetupassetsupController@updatesuppliesinven')->name('admin.updatesuppliesinven');
Route::get('admin_warehouse/setupsuppliesinven/destroy/{id}','SetupassetsupController@destroysuppliesinven');
Route::get('admin_warehouse/setupsuppliesinven/switchactiveinven','SetupassetsupController@switchactiveinven')->name('setup.inven');

Route::get('admin_warehouse/setupsuppliesinven_permis/{idref}','SetupassetsupController@infosuppliesinvenpermis')->name('setup.infosuppliesinven'); //กำหนดสิทธฺิ์คลัง
Route::post('admin_warehouse/setupsuppliesinven_permis/savepersonpermis','SetupassetsupController@savepersonpermis')->name('setup.savepersonpermis');
Route::get('admin_warehouse/setupsuppliesinven_permis/destroypersonpermis/{id}/{idinven}','SetupassetsupController@destroypersonpermis');


Route::get('admin_asset_supplies/setupsuppliesinven/add','SetupassetsupController@createsuppliesinven')->name('setup.createsuppliesinven');
Route::post('admin_asset_supplies/setupsuppliesinven/save','SetupassetsupController@savesuppliesinven')->name('admin.savesuppliesinven');
Route::get('admin_asset_supplies/setupsuppliesinven/edit/{id}','SetupassetsupController@editsuppliesinven')->name('setup.editsuppliesinven');
Route::post('admin_asset_supplies/setupsuppliesinven/update','SetupassetsupController@updatesuppliesinven')->name('admin.updatesuppliesinven');
Route::get('admin_asset_supplies/setupsuppliesinven/destroy/{id}','SetupassetsupController@destroysuppliesinven');
Route::get('admin_asset_supplies/setupsuppliesinven/switchactiveinven','SetupassetsupController@switchactiveinven')->name('setup.inven');


Route::get('admin_asset_supplies/setupassettypevalue','SetupassetsupController@infoassettypevalue')->name('setup.infoassettypevalue');
Route::get('admin_asset_supplies/setupassettypevalue/add','SetupassetsupController@createassettypevalue')->name('setup.createassettypevalue');
Route::post('admin_asset_supplies/setupassettypevalue/save','SetupassetsupController@saveassettypevalue')->name('admin.saveassettypevalue');
Route::get('admin_asset_supplies/setupassettypevalue/edit/{id}','SetupassetsupController@editassettypevalue')->name('setup.editassettypevalue');
Route::post('admin_asset_supplies/setupassettypevalue/update','SetupassetsupController@updateassettypevalue')->name('admin.updateassettypevalue');
Route::get('admin_asset_supplies/setupassettypevalue/destroy/{id}','SetupassetsupController@destroyassettypevalue');

Route::get('admin_asset_supplies/setupofficer','SetupassetsupController@infoofficer')->name('setup.infoofficer');
Route::get('admin_asset_supplies/setupofficer/add','SetupassetsupController@createofficer')->name('setup.createofficer');
Route::post('admin_asset_supplies/setupofficer/save','SetupassetsupController@saveofficer')->name('admin.saveofficer');
Route::get('admin_asset_supplies/setupofficer/edit/{id}','SetupassetsupController@editofficer')->name('setup.editofficer');
Route::post('admin_asset_supplies/setupofficer/update','SetupassetsupController@updateofficer')->name('admin.updateofficer');
Route::get('admin_asset_supplies/setupofficer/destroy/{id}','SetupassetsupController@destroyofficer');
//----------------------------------------17.9.2562--------------------------------------------------//

Route::get('admin_repair/Setupinformcomtype','SetuprepairController@infoinformcomtype')->name('setup.infoinformcomtype');
Route::get('admin_repair/Setupinformcomtype/add','SetuprepairController@createinformcomtype')->name('setup.createinformcomtype');
Route::post('admin_repair/Setupinformcomtype/save','SetuprepairController@saveinformcomtype')->name('admin.saveinformcomtype');
Route::get('admin_repair/Setupinformcomtype/edit/{id}','SetuprepairController@editinformcomtype')->name('setup.editinformcomtype');
Route::post('admin_repair/Setupinformcomtype/update','SetuprepairController@updateinformcomtype')->name('admin.updateinformcomtype');
Route::get('admin_repair/Setupinformcomtype/destroy/{id}','SetuprepairController@destroyinformcomtype');

Route::get('admin_repair/Setupinformcomengineer','SetuprepairController@infoinformcomengineer')->name('setup.infoinformcomengineer');
Route::get('admin_repair/Setupinformcomengineer/add','SetuprepairController@createinformcomengineer')->name('setup.createinformcomengineer');
Route::post('admin_repair/Setupinformcomengineer/save','SetuprepairController@saveinformcomengineer')->name('admin.saveinformcomengineer');
Route::get('admin_repair/Setupinformcomengineer/edit/{id}','SetuprepairController@editinformcomengineer')->name('setup.editinformcomengineer');
Route::post('admin_repair/Setupinformcomengineer/update','SetuprepairController@updateinformcomengineer')->name('admin.updateinformcomengineer');
Route::get('admin_repair/Setupinformcomengineer/destroy/{id}','SetuprepairController@destroyinformcomengineer');
Route::get('admin_repair/Setupinformcomengineer/switchactiveinformcomengineer','SetuprepairController@switchactiveinformcomengineer')->name('setup.informcomengineer');

Route::get('admin_repair/Setupinformcompriority','SetuprepairController@infoinformcompriority')->name('setup.infoinformcompriority');
Route::get('admin_repair/Setupinformcompriority/add','SetuprepairController@createinformcompriority')->name('setup.createinformcompriority');
Route::post('admin_repair/Setupinformcompriority/save','SetuprepairController@saveinformcompriority')->name('admin.saveinformcompriority');
Route::get('admin_repair/Setupinformcompriority/edit/{id}','SetuprepairController@editinformcompriority')->name('setup.editinformcompriority');
Route::post('admin_repair/Setupinformcompriority/update','SetuprepairController@updateinformcompriority')->name('admin.updateinformcompriority');
Route::get('admin_repair/Setupinformcompriority/destroy/{id}','SetuprepairController@destroyinformcompriority');

Route::get('admin_repair/Setupinformcomprogram','SetuprepairController@infoinformcomprogram')->name('setup.infoinformcomprogram');
Route::get('admin_repair/Setupinformcomprogram/add','SetuprepairController@createinformcomprogram')->name('setup.createinformcomprogram');
Route::post('admin_repair/Setupinformcomprogram/save','SetuprepairController@saveinformcomprogram')->name('admin.saveinformcomprogram');
Route::get('admin_repair/Setupinformcomprogram/edit/{id}','SetuprepairController@editinformcomprogram')->name('setup.editinformcomprogram');
Route::post('admin_repair/Setupinformcomprogram/update','SetuprepairController@updateinformcomprogram')->name('admin.updateinformcomprogram');
Route::get('admin_repair/Setupinformcomprogram/destroy/{id}','SetuprepairController@destroyinformcomprogram');

Route::get('admin_repair/Setupinformcomcolor','SetuprepairController@infoinformcomcolor')->name('setup.infoinformcomcolor');
Route::get('admin_repair/Setupinformcomcolor/add','SetuprepairController@createinformcomcolor')->name('setup.createinformcomcolor');
Route::post('admin_repair/Setupinformcomcolor/save','SetuprepairController@saveinformcomcolor')->name('admin.saveinformcomcolor');
Route::get('admin_repair/Setupinformcomcolor/edit/{id}','SetuprepairController@editinformcomcolor')->name('setup.editinformcomcolor');
Route::post('admin_repair/Setupinformcomcolor/update','SetuprepairController@updateinformcomcolor')->name('admin.updateinformcomcolor');
Route::get('admin_repair/Setupinformcomcolor/destroy/{id}','SetuprepairController@destroyinformcomcolor');

Route::get('admin_repair/Setupinformcomsupplierrepair','SetuprepairController@infoinformcomsupplierrepair')->name('setup.infoinformcomsupplierrepair');
Route::get('admin_repair/Setupinformcomsupplierrepair/add','SetuprepairController@createinformcomsupplierrepair')->name('setup.createinformcomsupplierrepair');
Route::post('admin_repair/Setupinformcomsupplierrepair/save','SetuprepairController@saveinformcomsupplierrepair')->name('admin.saveinformcomsupplierrepair');
Route::get('admin_repair/Setupinformcomsupplierrepair/edit/{id}','SetuprepairController@editinformcomsupplierrepair')->name('setup.editinformcomsupplierrepair');
Route::post('admin_repair/Setupinformcomsupplierrepair/update','SetuprepairController@updateinformcomsupplierrepair')->name('admin.updateinformcomsupplierrepair');
Route::get('admin_repair/Setupinformcomsupplierrepair/destroy/{id}','SetuprepairController@destroyinformcomsupplierrepair');

Route::get('admin_repair/Setupinformcomhardware','SetuprepairController@infoinformcomhardware')->name('setup.infoinformcomhardware');
Route::get('admin_repair/Setupinformcomhardware/add','SetuprepairController@createinformcomhardware')->name('setup.createinformcomhardware');
Route::post('admin_repair/Setupinformcomhardware/save','SetuprepairController@saveinformcomhardware')->name('admin.saveinformcomhardware');
Route::get('admin_repair/Setupinformcomhardware/edit/{id}','SetuprepairController@editinformcomhardware')->name('setup.editinformcomhardware');
Route::post('admin_repair/Setupinformcomhardware/update','SetuprepairController@updateinformcomhardware')->name('admin.updateinformcomhardware');
Route::get('admin_repair/Setupinformcomhardware/destroy/{id}','SetuprepairController@destroyinformcomhardware');

Route::get('admin_repair/Setupinformcomlocation','SetuprepairController@infoinformcomlocation')->name('setup.infoinformcomlocation');
Route::get('admin_repair/Setupinformcomlocation/add','SetuprepairController@createinformcomlocation')->name('setup.createinformcomlocation');
Route::post('admin_repair/Setupinformcomlocation/save','SetuprepairController@saveinformcomlocation')->name('admin.saveinformcomlocation');
Route::get('admin_repair/Setupinformcomlocation/edit/{id}','SetuprepairController@editinformcomlocation')->name('setup.editinformcomlocation');
Route::post('admin_repair/Setupinformcomlocation/update','SetuprepairController@updateinformcomlocation')->name('admin.updateinformcomlocation');
Route::get('admin_repair/Setupinformcomlocation/destroy/{id}','SetuprepairController@destroyinformcomlocation');

Route::get('admin_repair/Setupinformcomsize','SetuprepairController@infoinformcomsize')->name('setup.infoinformcomsize');
Route::get('admin_repair/Setupinformcomsize/add','SetuprepairController@createinformcomsize')->name('setup.createinformcomsize');
Route::post('admin_repair/Setupinformcomsize/save','SetuprepairController@saveinformcomsize')->name('admin.saveinformcomsize');
Route::get('admin_repair/Setupinformcomsize/edit/{id}','SetuprepairController@editinformcomsize')->name('setup.editinformcomsize');
Route::post('admin_repair/Setupinformcomsize/update','SetuprepairController@updateinformcomsize')->name('admin.updateinformcomsize');
Route::get('admin_repair/Setupinformcomsize/destroy/{id}','SetuprepairController@destroyinformcomsize');


Route::get('admin_repair/Setupservicelist','SetuprepairController@infoservicelist')->name('setup.infoservicelist');
Route::get('admin_repair/Setupservicelist/add','SetuprepairController@createservicelist')->name('setup.createservicelist');
Route::post('admin_repair/Setupservicelist/save','SetuprepairController@saveservicelist')->name('admin.saveservicelist');
Route::get('admin_repair/Setupservicelist/edit/{id}','SetuprepairController@editservicelistt')->name('setup.editservicelistt');
Route::post('admin_repair/Setupservicelist/update','SetuprepairController@updateservicelist')->name('admin.updateservicelist');
Route::get('admin_repair/Setupservicelist/destroy/{id}','SetuprepairController@destroyservicelist');

//----------------------------------------------------------18.9.2562----------------------------------//
Route::get('admin_repair/Setupinformcombrand','SetuprepairController@infoinformcombrand')->name('setup.infoinformcombrand');
Route::get('admin_repair/Setupinformcombrand/add','SetuprepairController@createinformcombrand')->name('setup.createinformcombrand');
Route::post('admin_repair/Setupinformcombrand/save','SetuprepairController@saveinformcombrand')->name('admin.saveinformcombrand');
Route::get('admin_repair/Setupinformcombrand/edit/{id}','SetuprepairController@editinformcombrand')->name('setup.editinformcombrand');
Route::post('admin_repair/Setupinformcombrand/update','SetuprepairController@updateinformcombrand')->name('admin.updateinformcombrand');
Route::get('admin_repair/Setupinformcombrand/destroy/{id}','SetuprepairController@destroyinformcombrand');

Route::get('admin_repair/Setupinformcombuilding','SetuprepairController@infoinformcombuilding')->name('setup.infoinformcombuilding');
Route::get('admin_repair/Setupinformcombuilding/add','SetuprepairController@createinformcombuilding')->name('setup.createinformcombuilding');
Route::post('admin_repair/Setupinformcombuilding/save','SetuprepairController@saveinformcombuilding')->name('admin.saveinformcombuilding');
Route::get('admin_repair/Setupinformcombuilding/edit/{id}','SetuprepairController@editinformcombuilding')->name('setup.editinformcombuilding');
Route::post('admin_repair/Setupinformcombuilding/update','SetuprepairController@updateinformcombuilding')->name('admin.updateinformcombuilding');
Route::get('admin_repair/Setupinformcombuilding/destroy/{id}','SetuprepairController@destroyinformcombuilding');

Route::get('admin_repair/SetupInformcomstatus','SetuprepairController@infoInformcomstatus')->name('setup.infoInformcomstatus');
Route::get('admin_repair/SetupInformcomstatus/add','SetuprepairController@createInformcomstatus')->name('setup.createInformcomstatus');
Route::post('admin_repair/SetupInformcomstatus/save','SetuprepairController@saveInformcomstatus')->name('admin.saveInformcomstatus');
Route::get('admin_repair/SetupInformcomstatus/edit/{id}','SetuprepairController@editInformcomstatus')->name('setup.editInformcomstatus');
Route::post('admin_repair/SetupInformcomstatus/update','SetuprepairController@updateInformcomstatus')->name('admin.updateInformcomstatus');
Route::get('admin_repair/SetupInformcomstatus/destroy/{id}','SetuprepairController@destroyInformcomstatus');

Route::get('admin_repair/Setuprepairpriority','SetuprepairController@inforepairpriority')->name('setup.inforepairpriority');
Route::get('admin_repair/Setuprepairpriority/add','SetuprepairController@createrepairpriority')->name('setup.createrepairpriority');
Route::post('admin_repair/Setuprepairpriority/save','SetuprepairController@saverepairpriority')->name('admin.saverepairpriority');
Route::get('admin_repair/Setuprepairpriority/edit/{id}','SetuprepairController@editrepairpriority')->name('setup.editrepairpriority');
Route::post('admin_repair/Setuprepairpriority/update','SetuprepairController@updaterepairpriority')->name('admin.updaterepairpriority');
Route::get('admin_repair/Setuprepairpriority/destroy/{id}','SetuprepairController@destroyrepairpriority');

//----------------------18.9.2562--รอบบ่าย------------------//
Route::get('admin_repair/Setupinformcomrepairtype','SetuprepairController@infoinformcomrepairtype')->name('setup.infoinformcomrepairtype');
Route::get('admin_repair/Setupinformcomrepairtype/add','SetuprepairController@createinformcomrepairtype')->name('setup.createinformcomrepairtype');
Route::post('admin_repair/Setupinformcomrepairtype/save','SetuprepairController@saveinformcomrepairtype')->name('admin.saveinformcomrepairtype');
Route::get('admin_repair/Setupinformcomrepairtype/edit/{id}','SetuprepairController@editinformcomrepairtype')->name('setup.editinformcomrepairtype');
Route::post('admin_repair/Setupinformcomrepairtype/update','SetuprepairController@updateinformcomrepairtype')->name('admin.updateinformcomrepairtype');
Route::get('admin_repair/Setupinformcomrepairtype/destroy/{id}','SetuprepairController@destroyinformcomrepairtype');
//---------------------------------22.00-------------------------//
Route::get('admin_repair/Setupassetpadoctool','SetuprepairController@infoassetpadoctool')->name('setup.infoassetpadoctool');
Route::get('admin_repair/Setupassetpadoctool/add','SetuprepairController@createassetpadoctool')->name('setup.createassetpadoctool');
Route::post('admin_repair/Setupassetpadoctool/save','SetuprepairController@saveassetpadoctool')->name('admin.saveassetpadoctool');
Route::get('admin_repair/Setupassetpadoctool/edit/{id}','SetuprepairController@editassetpadoctool')->name('setup.editassetpadoctool');
Route::post('admin_repair/Setupassetpadoctool/update','SetuprepairController@updateassetpadoctool')->name('admin.updateassetpadoctool');
Route::get('admin_repair/Setupassetpadoctool/destroy/{id}','SetuprepairController@destroyassetpadoctool');

Route::get('admin_repair/Setupassetpaardoctype','SetuprepairController@infoassetpaardoctype')->name('setup.infoassetpaardoctype');
Route::get('admin_repair/Setupassetpaardoctype/add','SetuprepairController@createassetpaardoctype')->name('setup.createassetpaardoctype');
Route::post('admin_repair/Setupassetpaardoctype/save','SetuprepairController@saveassetpaardoctype')->name('admin.saveassetpaardoctype');
Route::get('admin_repair/Setupassetpaardoctype/edit/{id}','SetuprepairController@editassetpaardoctype')->name('setup.editassetpaardoctype');
Route::post('admin_repair/Setupassetpaardoctype/update','SetuprepairController@updateassetpaardoctype')->name('admin.updateassetpaardoctype');
Route::get('admin_repair/Setupassetpaardoctype/destroy/{id}','SetuprepairController@destroyassetpaardoctype');

Route::get('admin_repair/Setupassetpaardocmedical','SetuprepairController@infoassetpaardocmedical')->name('setup.infoassetpaardocmedical');
Route::get('admin_repair/Setupassetpaardocmedical/add','SetuprepairController@createassetpaardocmedical')->name('setup.createassetpaardocmedical');
Route::post('admin_repair/Setupassetpaardocmedical/save','SetuprepairController@saveassetpaardocmedical')->name('admin.saveassetpaardocmedical');
Route::get('admin_repair/Setupassetpaardocmedical/edit/{id}','SetuprepairController@editassetpaardocmedical')->name('setup.editassetpaardocmedical');
Route::post('admin_repair/Setupassetpaardocmedical/update','SetuprepairController@updateassetpaardocmedical')->name('admin.updateassetpaardocmedical');
Route::get('admin_repair/Setupassetpaardocmedical/destroy/{id}','SetuprepairController@destroyassetpaardocmedical');

//--------------------------19.9.2562----9.00------------------------------------//

Route::get('admin_repair/Setupassetpadoccalibration','SetuprepairController@infoassetpadoccalibration')->name('setup.infoassetpadoccalibration');
Route::get('admin_repair/Setupassetpadoccalibration/add','SetuprepairController@createassetpadoccalibration')->name('setup.createassetpadoccalibration');
Route::post('admin_repair/Setupassetpadoccalibration/save','SetuprepairController@saveassetpadoccalibration')->name('admin.saveassetpadoccalibration');
Route::get('admin_repair/Setupassetpadoccalibration/edit/{id}','SetuprepairController@editassetpadoccalibration')->name('setup.editassetpadoccalibration');
Route::post('admin_repair/Setupassetpadoccalibration/update','SetuprepairController@updateassetpadoccalibration')->name('admin.updateassetpadoccalibration');
Route::get('admin_repair/Setupassetpadoccalibration/destroy/{id}','SetuprepairController@destroyassetpadoccalibration');

Route::get('admin_repair/Setupassetpadoccheck','SetuprepairController@infoassetpadoccheck')->name('setup.infoassetpadoccheck');
Route::get('admin_repair/Setupassetpadoccheck/add','SetuprepairController@createassetpadoccheck')->name('setup.createassetpadoccheck');
Route::post('admin_repair/Setupassetpadoccheck/save','SetuprepairController@saveassetpadoccheck')->name('admin.saveassetpadoccheck');
Route::get('admin_repair/Setupassetpadoccheck/edit/{id}','SetuprepairController@editassetpadoccheck')->name('setup.editassetpadoccheck');
Route::post('admin_repair/Setupassetpadoccheck/update','SetuprepairController@updateassetpadoccheck')->name('admin.updateassetpadoccheck');
Route::get('admin_repair/Setupassetpadoccheck/destroy/{id}','SetuprepairController@destroyassetpadoccheck');

Route::get('admin_repair/Setupassetpaardocleader','SetuprepairController@infoassetpaardocleader')->name('setup.infoassetpaardocleader');
Route::get('admin_repair/Setupassetpaardocleader/add','SetuprepairController@createassetpaardocleader')->name('setup.createassetpaardocleader');
Route::post('admin_repair/Setupassetpaardocleader/save','SetuprepairController@saveassetpaardocleader')->name('admin.saveassetpaardocleader');
Route::get('admin_repair/Setupassetpaardocleader/edit/{id}','SetuprepairController@editassetpaardocleader')->name('setup.editassetpaardocleader');
Route::post('admin_repair/Setupassetpaardocleader/update','SetuprepairController@updateassetpaardocleader')->name('admin.updateassetpaardocleader');
Route::get('admin_repair/Setupassetpaardocleader/destroy/{id}','SetuprepairController@destroyassetpaardocleader');

Route::get('admin_repair/Setupinformrepairtech','SetuprepairController@infoinformrepairtech')->name('setup.infoinformrepairtech');
Route::get('admin_repair/Setupinformrepairtech/add','SetuprepairController@createinformrepairtech')->name('setup.createinformrepairtech');
Route::post('admin_repair/Setupinformrepairtech/save','SetuprepairController@saveinformrepairtech')->name('admin.saveinformrepairtech');
Route::get('admin_repair/Setupinformrepairtech/edit/{id}','SetuprepairController@editinformrepairtech')->name('setup.editinformrepairtech');
Route::post('admin_repair/Setupinformrepairtech/update','SetuprepairController@updateinformrepairtech')->name('admin.updateinformrepairtech');
Route::get('admin_repair/Setupinformrepairtech/destroy/{id}','SetuprepairController@destroyinformrepairtech');
Route::get('admin_repair/Setupinformrepairtech/switchactiveinformrepairtech','SetuprepairController@switchactiveinformrepairtech')->name('setup.informrepairtech');

Route::get('admin_repair/Setupinformcomrepairlist','SetuprepairController@infoinformcomrepairlist')->name('setup.infoinformcomrepairlist');
Route::get('admin_repair/Setupinformcomrepairlist/add','SetuprepairController@createinformcomrepairlist')->name('setup.createinformcomrepairlist');
Route::post('admin_repair/Setupinformcomrepairlist/save','SetuprepairController@saveinformcomrepairlist')->name('admin.saveinformcomrepairlist');
Route::get('admin_repair/Setupinformcomrepairlist/edit/{id}','SetuprepairController@editinformcomrepairlist')->name('setup.editinformcomrepairlist');
Route::post('admin_repair/Setupinformcomrepairlist/update','SetuprepairController@updateinformcomrepairlist')->name('admin.updateinformcomrepairlist');
Route::get('admin_repair/Setupinformcomrepairlist/destroy/{id}','SetuprepairController@destroyinformcomrepairlist');


Route::get('admin_repair/Setupassetcareenginer','SetuprepairController@infoassetcareenginer')->name('setup.infoassetcareenginer');
Route::get('admin_repair/Setupassetcareenginer/add','SetuprepairController@createassetcareenginer')->name('setup.createassetcareenginer');
Route::post('admin_repair/Setupassetcareenginer/save','SetuprepairController@saveassetcareenginer')->name('admin.saveassetcareenginer');
Route::get('admin_repair/Setupassetcareenginer/edit/{id}','SetuprepairController@editassetcareenginer')->name('setup.editassetcareenginer');
Route::post('admin_repair/Setupassetcareenginer/update','SetuprepairController@updateassetcareenginer')->name('admin.updateassetcareenginer');
Route::get('admin_repair/Setupassetcareenginer/destroy/{id}','SetuprepairController@destroyassetcareenginer');
Route::get('admin_repair/Setupassetcareenginer/switchactiveassetcareenginer','SetuprepairController@switchactiveassetcareenginer')->name('setup.assetcareenginer');


//============================================================END D=========================================




//==========================ส่วนงานระบบบริหารจัดการ===============================================

//======================================สารบรรณ===============================================
Route::match(['get','post'],'manager_book/dashboard','ManagerbookController@dashboard')->name('mbook.dashboard');
Route::post('manager_book/dashboardsearch','ManagerbookController@dashboardsearch')->name('mbook.dashboardsearch');
Route::get('manager_book/book/addorg','ManagerbookController@addorg')->name('mbook.addorg');
Route::get('manager_book/dashboard_view_orgIn/{year}','ManagerbookController@dashboard_view_orgIn');

Route::get('manager_book/report','ManagerbookController@report')->name('mbook.report');
Route::get('manager_book/report/disposereceipt','ManagerbookController@disposereceipt')->name('mbook.disposereceipt');
Route::get('manager_book/report/disposeout','ManagerbookController@disposeout')->name('mbook.disposeout');

Route::match(['post','get'],'manager_book/bookreceipt','ManagerbookController@infobookreceipt')->name('mbook.infobookreceipt');
Route::get('manager_book/bookreceipt/add','ManagerbookController@infobookreceiptinsert')->name('mbook.infobookreceiptinsert');
Route::get('manager_book/bookreceipt/control/{id}','ManagerbookController@infobookreceiptcontrol')->name('mbook.infobookreceiptcontrol');
Route::post('manager_book/bookreceipt/search','ManagerbookController@infobooksearch')->name('mbook.infobooksearch'); //ค้นหาตัวเดิม
Route::post('manager_book/bookreceipt/saverpresent','ManagerbookController@saverpresent')->name('mbook.saverpresent');
Route::post('manager_book/bookreceipt/saveretire','ManagerbookController@saveretire')->name('mbook.saveretire');
Route::get('manager_book/bookreceipt/edit/{id}','ManagerbookController@infobookreceiptedit')->name('mbook.infobookreceiptedit');
Route::post('manager_book/bookreceipt/update','ManagerbookController@infobookreceiptupdate')->name('mbook.infobookreceiptupdate');
Route::get('manager_book/checkmaxindex','ManagerbookController@checkmaxindex')->name('mbook.checkmax');

Route::get('manager_book/takenotereceipt','ManagerbookController@takenotereceipt')->name('mbook.takenotereceipt');

Route::get('manager_book/bookout','ManagerbookController@infobookout')->name('mbook.infobookout');
Route::get('manager_book/bookout/add','ManagerbookController@infobookoutinsert')->name('mbook.infobookoutinsert');
Route::post('manager_book/bookout/saverout','ManagerbookController@saverout')->name('mbook.saverout');
Route::get('manager_book/bookout/control/{id}','ManagerbookController@infobookoutcontrol')->name('mbook.infobookoutcontrol');
Route::post('manager_book/bookout/search','ManagerbookController@infobookoutsearch')->name('mbook.infobookoutsearch');

Route::match(['get','post'],'manager_book/bookinside','ManagerbookController@infobookinside')->name('mbook.infobookinside');
Route::get('manager_book/bookinside/add','ManagerbookController@infobookinsideinsert')->name('mbook.infobookoutinsert');
Route::post('manager_book/bookinside/saveinside','ManagerbookController@infobookoutsave')->name('mbook.infobookoutsave');
Route::get('manager_book/bookinside/control/{id}','ManagerbookController@infobookentinsidecontrol')->name('mbook.infobookentinsidecontrol');
Route::post('manager_book/bookinside/search','ManagerbookController@infobookinsidesearch')->name('mbook.infobookinsidesearch');
Route::post('manager_book/bookinside/send','ManagerbookController@sendinside')->name('mbook.sendinside');
Route::post('manager_book/bookinside/saverpresentinside','ManagerbookController@saverpresentinside')->name('mbook.saverpresentinside');
Route::post('manager_book/bookinside/saveretireinside','ManagerbookController@saveretireinside')->name('mbook.saveretireinside');
Route::get('manager_book/bookinside/edit/{id}','ManagerbookController@infobookinsideedit')->name('mbook.infobookinsideedit');
Route::post('manager_book/bookinside/update','ManagerbookController@infobookinsideupdate')->name('mbook.infobookinsideupdate');
Route::get('manager_book/checkmaxinside','ManagerbookController@checkmaxinside')->name('mbook.checkmaxinside');
Route::get('manager_book/takenoteinside','ManagerbookController@takenoteinside')->name('mbook.takenoteinside');
Route::match(['get','post'],'manager_book/bookcommand','ManagerbookController@infobookcommand')->name('mbook.infobookcommand');
Route::get('manager_book/bookcommand/add','ManagerbookController@infobookcommandinsert')->name('mbook.infobookcommandinsert');
Route::post('manager_book/bookcommand/savecommand','ManagerbookController@infobookcommandsave')->name('mbook.infobookcommandsave');
Route::get('manager_book/bookcom/control/{id}','ManagerbookController@infobookentcomdoccontrol')->name('mbook.infobookentcomdoccontrol');

Route::post('manager_book/bookcommand/search','ManagerbookController@infobookcommandsearch')->name('mbook.infobookcommandsearch');

Route::post('manager_book/bookcom/send','ManagerbookController@sendcomdoc')->name('mbook.sendcomdoc');
Route::post('manager_book/bookcom/saverpresentcomdoc','ManagerbookController@saverpresentcomdoc')->name('mbook.saverpresentcomdoc');
Route::post('manager_book/bookcom/saveretirecomdoc','ManagerbookController@saveretirecomdoc')->name('mbook.saveretirecomdoc');
Route::get('manager_book/bookcom/edit/{id}','ManagerbookController@infobookcomdocedit')->name('mbook.infobookcomdocedit');
Route::post('manager_book/bookcom/update','ManagerbookController@infobookcomdocupdate')->name('mbook.infobookcomdocupdate');
Route::get('manager_book/checkmaxcommand','ManagerbookController@checkmaxcommand')->name('mbook.checkmaxcommand');

Route::match(['get','post'],'manager_book/bookannounce','ManagerbookController@infobookannounce')->name('mbook.infobookannounce');
Route::get('manager_book/bookannounce/add','ManagerbookController@infobookannounceinsert')->name('mbook.infobookannounceinsert');
Route::post('manager_book/bookannounce/saveannounce','ManagerbookController@infobookannouncesave')->name('mbook.infobookannouncesave');
Route::get('manager_book/bookannounce/control/{id}','ManagerbookController@infobookentannouncecontrol')->name('mbook.infobookentannouncecontrol');
Route::post('manager_book/bookannounce/search','ManagerbookController@infobookannouncesearch')->name('mbook.infobookannouncesearch');
Route::post('manager_book/bookannounce/send','ManagerbookController@sendannounce')->name('mbook.sendannounce');
Route::post('manager_book/bookannounce/saverpresentannounce','ManagerbookController@saverpresentannounce')->name('mbook.saverpresentannounce');
Route::post('manager_book/bookannounce/saveretireannounce','ManagerbookController@saveretireannounce')->name('mbook.saveretireannounce');
Route::get('manager_book/bookannounce/edit/{id}','ManagerbookController@infobookannounceedit')->name('mbook.infobookannounceedit');
Route::post('manager_book/bookannounce/update','ManagerbookController@infobookannounceupdate')->name('mbook.infobookannounceupdate');

Route::get('manager_book/bookreceipt/departmentrow3','ManagerbookController@departmentrow3')->name('mbook.departmentrow3');
Route::get('manager_book/bookreceipt/departmentrow4','ManagerbookController@departmentrow4')->name('mbook.departmentrow4');
Route::get('manager_book/bookreceipt/departmentrow5','ManagerbookController@departmentrow5')->name('mbook.departmentrow5');


Route::post('manager_book/bookreceipt/save','ManagerbookController@savereceipt')->name('mbook.infobookreceiptsave');

Route::get('manager_book/bookreceipt/send/{id}','ManagerbookController@infobookreceiptsend')->name('mbook.infobookreceiptsend');
Route::post('manager_book/bookreceipt/send','ManagerbookController@sendreceipt')->name('mbook.sendreceipt');


Route::get('manager_book/bookpurchase','ManagerbookController@infobookpurchase')->name('mbook.infobookpurchase');

//------excel-------------

Route::get('manager_book/bookreceipt_excel','ManagerbookController@bookreceipt_excel')->name('mbook.bookreceipt_excel');
Route::get('manager_book/bookinside_excel','ManagerbookController@bookinside_excel')->name('mbook.bookinside_excel');
Route::get('manager_book/bookcommand_excel','ManagerbookController@bookcommand_excel')->name('mbook.bookcommand_excel');
Route::get('manager_book/bookannounce_excel','ManagerbookController@bookannounce_excel')->name('mbook.bookannounce_excel');

//======================================ยานพหนะ===============================================

Route::get('manager_car/dashboard','ManagercarController@dashboard')->name('mcar.dashboard');
Route::post('manager_car/dashboardsearch','ManagercarController@dashboardsearch')->name('mcar.dashboardsearch');

Route::get('manager_car/carcalendar','ManagercarController@carcalendar')->name('mcar.carcalendar');
Route::get('manager_car/carcalendar/detail','ManagercarController@deatailcalendar')->name('mcar.deatailcalendar');

Route::match(['get','post'],'manager_car/carinfonomal','ManagercarController@carinfonomal')->name('mcar.carinfonomal');
Route::get('manager_car/carinfonomalapp/{id}','ManagercarController@carinfonomalapp')->name('mcar.carinfonomalapp');
Route::post('manager_car/carinfonomalapp/update','ManagercarController@updateinfonomalapp')->name('mcar.updateinfonomalapp');
Route::post('manager_car/gencarnomalsearch/','ManagercarController@infocarnomalsearch')->name('mcar.infocarnomalsearch'); //ค้นหาตัวเดิม

Route::match(['get','post'],'manager_car/carinforefer','ManagercarController@carinforefer')->name('mcar.carinforefer');
Route::get('manager_car/carinforeferapp/{id}','ManagercarController@carinforeferapp')->name('mcar.carinforeferapp');
Route::post('manager_car/carinforeferapp/update','ManagercarController@updateinforeferapp')->name('mcar.updateinforeferapp');
Route::post('manager_car/gencarrefersearch/','ManagercarController@infocarrefersearch')->name('mcar.infocarrefersearch');

Route::get('manager_car/carinforefercancel/{id}','ManagercarController@cancelrefer')->name('mcar.cancelrefer');
Route::post('manager_car/carinforefercancel/updatecancel','ManagercarController@updatecancelrefer')->name('mcar.updatecancelrefer');


Route::get('manager_car/carinforefer_pdf/{id}','ManagercarController@carinforefer_pdf')->name('mcar.carinforefer_pdf');

Route::match(['get','post'],'manager_car/infomationcar','ManagercarController@infomationcar')->name('mcar.infomationcar');
Route::get('general_car/infomationcar/add','ManagercarController@createinfocar')->name('mcar.createinfocar');
Route::post('manager_car/infomationcar/save','ManagercarController@saveinfocar')->name('mcar.saveinfocar');
Route::get('manager_car/infomationcar/edit/{idcar}','ManagercarController@editinfocar')->name('mcar.editinfocar');
Route::post('manager_car/infomationcar/update','ManagercarController@updateinfocar')->name('mcar.updateinfocar');
Route::get('manager_car/infomationcar/destroy/{idcar}','ManagercarController@destroyinfocar')->name('mcar.destroyinfocar');
Route::get('manager_car/infomationcar/active','ManagercarController@activeinfocar')->name('mcar.activeinfocar');
Route::post('manager_car/infomationcar/infocarindexsearch','ManagercarController@infocarindexsearch')->name('mcar.infocarindexsearch');

Route::get('general_car/infomationcar/addasset','ManagercarController@infocaraddasset')->name('mcar.infocaraddasset');

Route::get('manager_car/infomationcar/repair/{idcar}','ManagercarController@repairinfocar')->name('mcar.repairinfocar');


Route::post('manager_car/infomationcar/saveaccessory/','ManagercarController@saveaccessory')->name('mcar.saveaccessory');
Route::post('manager_car/infomationcar/editaccessory/','ManagercarController@editaccessory')->name('mcar.editaccessory');
Route::get('manager_car/infomationcar/accessory/destroy/{idcar}/{idref}','ManagercarController@destroyaccessory')->name('mcar.destroyaccessory');

Route::post('manager_car/infomationcar/saveasset/','ManagercarController@saveasset')->name('mcar.saveasset');
Route::post('manager_car/infomationcar/editasset/','ManagercarController@editasset')->name('mcar.editasset');
Route::get('manager_car/infomationcar/asset/destroy/{idcar}/{idref}','ManagercarController@destroyasset')->name('mcar.destroyasset');

Route::post('manager_car/infomationcar/saveact/','ManagercarController@saveact')->name('mcar.saveact');
Route::post('manager_car/infomationcar/editact/','ManagercarController@editact')->name('mcar.editact');
Route::get('manager_car/infomationcar/act/destroy/{idcar}/{idref}','ManagercarController@destroyact')->name('mcar.destroyact');

Route::post('manager_car/infomationcar/savetax/','ManagercarController@savetax')->name('mcar.savetax');
Route::post('manager_car/infomationcar/edittax/','ManagercarController@edittax')->name('mcar.edittax');
Route::get('manager_car/infomationcar/tax/destroy/{idcar}/{idref}','ManagercarController@destroytax')->name('mcar.destroytax');

Route::post('manager_car/infomationcar/saveinsu/','ManagercarController@saveinsu')->name('mcar.saveinsu');
Route::post('manager_car/infomationcar/editinsu/','ManagercarController@editinsu')->name('mcar.editinsu');
Route::get('manager_car/infomationcar/insu/destroy/{idcar}/{idref}','ManagercarController@destroyinsu')->name('mcar.destroyinsu');

Route::post('manager_car/infomationcar/saveplan/','ManagercarController@saveplan')->name('mcar.saveplan');
Route::post('manager_car/infomationcar/editplan/','ManagercarController@editplan')->name('mcar.editplan');
Route::get('manager_car/infomationcar/plan/destroy/{idcar}/{idref}','ManagercarController@destroyplan')->name('mcar.destroyplan');

Route::post('manager_car/infomationcar/savecheckcar/','ManagercarController@savecheckcar')->name('mcar.savecheckcar');
Route::post('manager_car/infomationcar/updatupdatecheckcarecheckcar/','ManagercarController@updatecheckcar')->name('mcar.updatecheckcar');
Route::get('manager_car/infomationcar/destroy/{id}/{idcar}','ManagercarController@infomationcar_destroy')->name('mcar.infomationcar_destroy');

Route::get('manager_car/infomationcar/detailcheck/','ManagercarController@detailcheck')->name('mcar.detailcheck');

Route::get('manager_car/report','ManagercarController@report')->name('mcar.report');

//===================รายงานข้อมูลการขอใช้รถ=====
Route::match(['get','post'],'manager_car/carposition','ManagercarController@carposition')->name('mcar.carposition');
Route::match(['get','post'],'manager_car/carposition_save','ManagercarController@carposition_save')->name('mcar.carposition_save');
Route::match(['get','post'],'manager_car/carposition_update','ManagercarController@carposition_update')->name('mcar.carposition_update');
Route::match(['get','post'],'manager_car/carposition_delete/{id}','ManagercarController@carposition_delete')->name('mcar.carposition_delete');
Route::match(['get','post'],'manager_car/carreport','ManagercarController@carreport')->name('mcar.carreport');
Route::match(['get','post'],'manager_car/reportcarposition','ManagercarController@reportcarposition')->name('mcar.reportcarposition');
Route::match(['get','post'],'manager_car/reportcartwo','ManagercarController@reportcartwo')->name('mcar.reportcartwo');
Route::match(['get','post'],'manager_car/reportcarfour','ManagercarController@reportcarfour')->name('mcar.reportcarfour');
Route::match(['get','post'],'manager_car/reportcarfive','ManagercarController@reportcarfive')->name('mcar.reportcarfive');
Route::match(['get','post'],'manager_car/reportcarsix','ManagercarController@reportcarsix')->name('mcar.reportcarsix');
Route::match(['get','post'],'manager_car/reportcarfoursearch','ManagercarController@reportcarfoursearch')->name('mcar.reportcarfoursearch');
Route::match(['get','post'],'manager_car/reportcarsixsearch','ManagercarController@reportcarsixsearch')->name('mcar.reportcarsixsearch');

Route::match(['get','post'],'manager_car/carname_formtree','ManagercarController@carname_formtree')->name('mcar.carname_formtree');
Route::match(['get','post'],'manager_car/carname_formtree_save','ManagercarController@carname_formtree_save')->name('mcar.carname_formtree_save');
Route::match(['get','post'],'manager_car/switchactive_form','ManagercarController@switchactive_form')->name('mcar.switchactive_form');


Route::match(['get','post'],'manager_car/carformfunction_driver','ManagercarController@carformfunction_driver')->name('mcar.carformfunction_driver');
Route::match(['get','post'],'manager_car/carformfunction_driver_save','ManagercarController@carformfunction_driver_save')->name('mcar.carformfunction_driver_save');
Route::match(['get','post'],'manager_car/carformfunction_driver_switchactive','ManagercarController@carformfunction_driver_switchactive')->name('mcar.carformfunction_driver_switchactive');


Route::match(['get','post'],'manager_car/infomationcarreport','ManagercarController@infomationcarreport')->name('mcar.infomationcarreport');
Route::post('manager_car/infomationcarreportsearch','ManagercarController@infomationcarreportsearch')->name('mcar.infomationcarreportsearch');
Route::get('manager_car/excelcar','ManagercarController@excelcar')->name('mcar.excelcar');
Route::get('manager_car/excelcarposition','ManagercarController@excelcarposition')->name('mcar.excelcarposition');
Route::get('manager_car/excelcartwo','ManagercarController@excelcartwo')->name('mcar.excelcartwo');
Route::get('manager_car/excelcarfour/{id}','ManagercarController@excelcarfour')->name('mcar.excelcarfour');


Route::get('manager_car/pdfcarfour/{id}','ManagercarController@pdfcarfour')->name('mcar.pdfcarfour');


Route::get('manager_car/openform_car','ManagercarController@openform_car')->name('mcar.openform_car'); //เปิดใช้ฟอร์ม
Route::get('manager_car/openform_car_add','ManagercarController@openform_car_add')->name('mcar.openform_car_add');
Route::post('manager_car/openform_car_save','ManagercarController@openform_car_save')->name('mcar.openform_car_save');
Route::get('manager_car/openform_car_edit/{id}','ManagercarController@openform_car_edit')->name('mcar.openform_car_edit');
Route::post('manager_car/openform_car_update','ManagercarController@openform_car_update')->name('mcar.openform_car_update');
Route::get('manager_car/openform_car_destroy/{id}','ManagercarController@openform_car_destroy');
Route::match(['get','post'],'manager_car/openform_car_switchactive','ManagercarController@openform_car_switchactive')->name('mcar.openform_car_switchactive');

//===================ฟังชั่นการคำนวณระยะทาง
Route::get('manager_car/amountdistance','ManagercarController@amountdistance')->name('mcar.amountdistance');


//=====================================================================================================

//======================================บุคลากร===============================================

Route::get('manager_person/dashboard','ManagerpersonController@dashboard')->name('mperson.dashboard');
Route::post('manager_person/dashboardsearch','ManagerpersonController@dashboardsearch')->name('mperson.dashboardsearch');
Route::get('/manager_person/dashboard/nw','ManagerpersonController@dashboard_nw')->name('mperson-dash-nw');
Route::get('/manager_person/dashboard/sl','ManagerpersonController@dashboard_sl')->name('mperson-dash-sl');
Route::get('/manager_person/dashboard/hgo','ManagerpersonController@dashboard_hgo')->name('mperson-dash-hgo');
Route::get('/manager_person/dashboard/sis','ManagerpersonController@dashboard_sis')->name('mperson-dash-sis');
Route::get('/manager_person/dashboard/res','ManagerpersonController@dashboard_res')->name('mperson-dash-res');
Route::get('/manager_person/dashboard/go','ManagerpersonController@dashboard_go')->name('mperson-dash-go');
Route::get('/manager_person/dashboard/mo','ManagerpersonController@dashboard_mo')->name('mperson-dash-mo');
Route::get('/manager_person/dashboard/ret','ManagerpersonController@dashboard_ret')->name('mperson-dash-ret');

Route::post('manager_person/dashboard/ajax_getdepartment_sub_by_emptype','ManagerpersonController@ajax_getdepartment_sub_by_emptype');
Route::post('manager_person/dashboard/ajax_getdepartment_sub_sub_by_emptype','ManagerpersonController@ajax_getdepartment_sub_sub_by_emptype');

Route::get('manager_person/carcalendar','ManagerpersonController@carcalendar')->name('mperson.carcalendar');
Route::match(['post','get'],'manager_person/inforperson','ManagerpersonController@inforperson')->name('mperson.inforperson');

Route::match(['GET','POST'],'manager_person/inforperson_meetinginside','ManagerpersonController@inforperson_meetinginside')->name('mperson.inforperson_meetinginside');
Route::get('manager_person/inforperson_meetinginside_add','ManagerpersonController@inforperson_meetinginside_add')->name('mperson.inforperson_meetinginside_add');
Route::post('manager_person/inforperson_meetinginside_save','ManagerpersonController@inforperson_meetinginside_save')->name('mperson.inforperson_meetinginside_save');
Route::get('manager_person/inforperson_meetinginside_edit/{id}','ManagerpersonController@inforperson_meetinginside_edit')->name('mperson.inforperson_meetinginside_edit');
Route::post('manager_person/inforperson_meetinginside_update','ManagerpersonController@inforperson_meetinginside_update')->name('mperson.inforperson_meetinginside_update');
Route::post('manager_person/inforperson_meetinginside_search','ManagerpersonController@inforperson_meetinginside_search')->name('mperson.inforperson_meetinginside_search');
Route::get('manager_person/inforperson_meetinginside_cancel/{id}','ManagerpersonController@inforperson_meetinginside_cancel')->name('mperson.inforperson_meetinginside_cancel');
Route::post('manager_person/inforperson_meetinginside_updatecancel','ManagerpersonController@inforperson_meetinginside_updatecancel')->name('mperson.inforperson_meetinginside_updatecancel');

Route::get('manager_person/setinforperson_meetinginside','ManagerpersonController@setinforperson_meetinginside')->name('setmperson.setinforperson_meetinginside');
Route::post('manager_person/setinforperson_meetinginside_save','ManagerpersonController@setinforperson_meetinginside_save')->name('setmperson.setinforperson_meetinginside_save');
Route::get('manager_person/setinforperson_meetinginside_edit/{id}','ManagerpersonController@setinforperson_meetinginside_edit')->name('setmperson.setinforperson_meetinginside_edit');
Route::post('manager_person/setinforperson_meetinginside_update','ManagerpersonController@setinforperson_meetinginside_update')->name('setmperson.setinforperson_meetinginside_update');
Route::get('manager_person/setinforperson_meetinginside_destroy/{id}','ManagerpersonController@setinforperson_meetinginside_destroy')->name('setmperson.setinforperson_meetinginside_destroy');


Route::get('manager_person/search','ManagerpersonController@search')->name('mperson.search');
Route::get('manager_person/adduser','ManagerpersonController@create')->name('mperson.create');
Route::post('manager_person/save','ManagerpersonController@store')->name('mperson.store');
Route::get('manager_person/detail/{iduser}','ManagerpersonController@infouser')->name('mperson.infouser');
Route::post('manager_person/detail_ajax/{iduser}','ManagerpersonController@get_ajex_infouser');
Route::post('manager_person/infousersalarysave','ManagerpersonController@infousersalarysave')->name('mperson.infousersalarysave');
Route::post('manager_person/infousersalaryupdate','ManagerpersonController@infousersalaryupdate')->name('mperson.infousersalaryupdate');
Route::get('manager_person/infousersalary_destroy/{id}/{iduser}','ManagerpersonController@infousersalary_destroy')->name('mperson.infousersalary_destroy');

Route::get('manager_person/infousersalarynewsave','ManagerpersonController@infousersalarynewsave')->name('mperson.infousersalarynewsave');

Route::get('manager_person/infousersalary','ManagerpersonController@infousersalary')->name('mperson.infousersalary');
Route::get('manager_person/detaliledit/{iduser}','ManagerpersonController@editinfouser')->name('mperson.editinfouser');
Route::post('manager_person/detaliledit_update','ManagerpersonController@editinfouserupdate')->name('mperson.editinfouserupdate');


//แนบไฟล์บุคคลากร
Route::get('person/personinfouserfile/{iduser}','FilepersonController@infouserfile')->name('person.inforfile');
Route::post('person/personinfouserfile/save','FilepersonController@infouserfile_save')->name('person.inforfile_save');
Route::post('person/personinfouserfile/update','FilepersonController@infouserfile_update')->name('person.inforfile_update');
Route::get('person/personinfouserfile/destroy/{id}/{iduser}','FilepersonController@infouserfile_destroy');


//// ข้อมูลการทำงาน
Route::match(['get','post'],'manager_person/jobdescriptions/personnel',[ManagerpersonController::class,'jobdescriptions_personnel'])->name('mperson.jobdescriptions_personnel');   
Route::post('manager_person/jobdescriptions/personnel/save',[ManagerpersonController::class,'jobdescriptions_personnel_save'])->name('mperson.jobdescriptions_personnel_save');
Route::get('manager_person/jobdescriptions/personnel/edit/{id}',[ManagerpersonController::class,'jobdescriptions_personnel_edit'])->name('mperson.jobdescriptions_personnel_edit');
Route::post('manager_person/jobdescriptions/personnel/update',[ManagerpersonController::class,'jobdescriptions_personnel_update'])->name('mperson.jobdescriptions_personnel_update');
Route::get('manager_person/jobdescriptions/personnel/delete/{id}',[ManagerpersonController::class,'jobdescriptions_personnel_delete'])->name('mperson.jobdescriptions_personnel_delete');   
Route::post('manager_person/jobdescriptions/personnel/checkJobPerson',[ManagerpersonController::class,'checkJobPerson'])->name('mperson.checkJobPerson');   
Route::get('manager_person/jobdescriptions/personnel/estimate6/{id}',[ManagerpersonController::Class,'jobdescriptions_personnel_estimate6'])->name('mperson.jobdescriptions_personnel_estimate6');
Route::post('manager_person/jobdescriptions/personnel/estimate6/update',[ManagerpersonController::Class,'jobdescriptions_personnel_estimate6_update'])->name('mperson.jobdescriptions_personnel_estimate6_update');
Route::get('manager_person/jobdescriptions/personnel/estimate12/{id}',[ManagerpersonController::Class,'jobdescriptions_personnel_estimate12'])->name('mperson.jobdescriptions_personnel_estimate12');
Route::post('manager_person/jobdescriptions/personnel/estimate12/update',[ManagerpersonController::Class,'jobdescriptions_personnel_estimate12_update'])->name('mperson.jobdescriptions_personnel_estimate12_update');
Route::match(['get','post'],'manager_person/jobdescriptions/personnel/estimate/{id}',[ManagerpersonController::Class,'jobdescriptions_personnel_estimate'])->name('mperson.jobdescriptions_personnel_estimate');
Route::prefix('manager_person/setup/infowork/')->group(function (){
    Route::get('kpi',[ManagerpersonController::class,'kpi_view'])->name('mperson.setinfo_kpi');
    Route::get('kpi/add',[ManagerpersonController::class,'kpi_add'])->name('mperson.setinfo_kpi_add');
    Route::post('kpi/save',[ManagerpersonController::class,'kpi_save'])->name('mperson.setinfo_kpi_save');
    Route::get('kpi/edit/{id}',[ManagerpersonController::class,'kpi_edit'])->name('mperson.setinfo_kpi_edit');
    Route::post('kpi/update',[ManagerpersonController::class,'kpi_update'])->name('mperson.setinfo_kpi_update');
    Route::get('kpi/delete/{id}',[ManagerpersonController::class,'kpi_delete'])->name('mperson.setinfo_kpi_delete');
    Route::match(['get','post'],'job_descriptions',[ManagerpersonController::class,'job_descriptions_view'])->name('mperson.setinfo_jobd');
    Route::post('ajax_setinfo_jobd_list_kpi',[ManagerpersonController::class,'ajax_setinfo_jobd_list_kpi'])->name('mperson.ajax_setinfo_jobd_list_kpi');
    Route::get('job_descriptions/add',[ManagerpersonController::class,'job_descriptions_add'])->name('mperson.setinfo_jobd_add');
    Route::post('job_descriptions/save',[ManagerpersonController::class,'job_descriptions_save'])->name('mperson.setinfo_jobd_save');
    Route::get('job_descriptions/edit/{id}',[ManagerpersonController::class,'job_descriptions_edit'])->name('mperson.setinfo_jobd_edit');
    Route::post('job_descriptions/update',[ManagerpersonController::class,'job_descriptions_update'])->name('mperson.setinfo_jobd_update');
    Route::get('job_descriptions/delete/{id}',[ManagerpersonController::class,'job_descriptions_delete'])->name('mperson.setinfo_jobd_delete');
    Route::get('job_descriptions/set/{id}',[ManagerpersonController::class,'job_descriptions_set'])->name('mperson.job_descriptions_set');
    Route::post('job_description/set/update',[ManagerpersonController::class,'job_descriptions_set_update'])->name('mperson.setinfo_jobdset_update');
    Route::post('job_description/set/update',[ManagerpersonController::class,'job_descriptions_set_update'])->name('mperson.setinfo_jobdset_update');
    Route::get('permission_job',[ManagerpersonController::class,'permission_job_view'])->name('mperson.permission_job');
    Route::post('permission_job/save',[ManagerpersonController::class,'permission_job_save'])->name('mperson.permission_job_save');
    Route::get('permission_job/delete/{id}',[ManagerpersonController::class,'permission_job_delete'])->name('mperson.permission_job.delete');
    Route::get('permission_job_person/{id}',[ManagerpersonController::class,'permission_job_person_view'])->name('mperson.permission_job_person');
    Route::post('permission_job_person/save',[ManagerpersonController::class,'permission_job_person_save'])->name('mperson.permission_job_person_save');
    Route::get('permission_job_person/delete/{id}',[ManagerpersonController::class,'permission_job_person_delete'])->name('mperson.permission_job_person.delete');
});
//// จบข้อมูลการทำงาน

// ไม่ได้ใช้แล้ว
Route::get('manager_person/setupjobdescription','ManagerpersonController@setupjobdescription')->name('mperson.setupjobdescription');
Route::get('manager_person/setupjobdescription_add','ManagerpersonController@setupjobdescription_add')->name('mperson.setupjobdescription_add');
Route::post('manager_person/setupjobdescription_save','ManagerpersonController@setupjobdescription_save')->name('mperson.setupjobdescription_save');
Route::get('manager_person/setupjobdescription_edit/{idref}','ManagerpersonController@setupjobdescription_edit')->name('mperson.setupjobdescription_edit');
Route::post('manager_person/setupjobdescription_update','ManagerpersonController@setupjobdescription_update')->name('mperson.setupjobdescription_update');
Route::get('manager_person/setupjobdescription_destroy/{idref}','ManagerpersonController@setupjobdescription_destroy')->name('mperson.setupjobdescription_destroy');
Route::get('manager_person/setupjobdescriptionposition/{idref}','ManagerpersonController@setupjobdescriptionposition')->name('mperson.setupjobdescriptionposition');
Route::post('manager_person/setupjobdescriptionposition_save','ManagerpersonController@setupjobdescriptionposition_save')->name('mperson.setupjobdescriptionposition_save');
Route::get('manager_person/setupjobdescriptionposition_destroy/{idref}/{id}','ManagerpersonController@setupjobdescriptionposition_destroy')->name('mperson.setupjobdescriptionposition_destroy');
// จบไม่ได้ใช้แล้ว





Route::get('manager_person/setupcorecompetency','ManagerpersonController@setupcorecompetency')->name('mperson.setupcorecompetency');
Route::get('manager_person/setupcorecompetency_add','ManagerpersonController@setupcorecompetency_add')->name('mperson.setupcorecompetency_add');
Route::post('manager_person/setupcorecompetency_save','ManagerpersonController@setupcorecompetency_save')->name('mperson.setupcorecompetency_save');
Route::get('manager_person/setupcorecompetency_edit/{idref}','ManagerpersonController@setupcorecompetency_edit')->name('mperson.setupcorecompetency_edit');
Route::post('manager_person/setupcorecompetency_update','ManagerpersonController@setupcorecompetency_update')->name('mperson.setupcorecompetency_update');
Route::get('manager_person/setupcorecompetency_destroy/{idref}','ManagerpersonController@setupcorecompetency_destroy')->name('mperson.setupcorecompetency_destroy');

Route::get('manager_person/setupcorecompetencylevel/{idref}','ManagerpersonController@setupcorecompetencylevel')->name('mperson.setupcorecompetencylevel');
Route::get('manager_person/setupcorecompetencylevel_add/{idref}','ManagerpersonController@setupcorecompetencylevel_add')->name('mperson.setupsetscore_add');
Route::post('manager_person/setupcorecompetencylevel_save','ManagerpersonController@setupcorecompetencylevel_save')->name('mperson.setupcorecompetencylevel_save');
Route::get('manager_person/setupcorecompetencylevel_edit/{idref}/{id}','ManagerpersonController@setupcorecompetencylevel_edit')->name('mperson.setupcorecompetencylevel_edit');
Route::post('manager_person/setupcorecompetencylevel_update','ManagerpersonController@setupcorecompetencylevel_update')->name('mperson.setupcorecompetencylevel_update');



Route::get('manager_person/setupfuntionalcompetency','ManagerpersonController@setupfuntionalcompetency')->name('mperson.setupfuntionalcompetency');
Route::get('manager_person/setupfuntionalcompetency_add','ManagerpersonController@setupfuntionalcompetency_add')->name('mperson.setupfuntionalcompetency_add');
Route::post('manager_person/setupfuntionalcompetency_save','ManagerpersonController@setupfuntionalcompetency_save')->name('mperson.setupfuntionalcompetency_save');
Route::get('manager_person/setupfuntionalcompetency_edit/{idref}','ManagerpersonController@setupfuntionalcompetency_edit')->name('mperson.setupfuntionalcompetency_edit');
Route::post('manager_person/setupfuntionalcompetency_update','ManagerpersonController@setupfuntionalcompetency_update')->name('mperson.setupfuntionalcompetency_update');
Route::get('manager_person/setupfuntionalcompetency_destroy/{idref}','ManagerpersonController@setupfuntionalcompetency_destroy')->name('mperson.setupfuntionalcompetency_destroy');
Route::get('manager_person/setupfuntionalcompetencyposition/{idref}','ManagerpersonController@setupfuntionalcompetencyposition')->name('mperson.setupfuntionalcompetencyposition');
Route::post('manager_person/setupfuntionalcompetencyposition_save','ManagerpersonController@setupfuntionalcompetencyposition_save')->name('mperson.setupfuntionalcompetencyposition_save');
Route::get('manager_person/setupfuntionalcompetencyposition_destroy/{idref}/{id}','ManagerpersonController@setupfuntionalcompetencyposition_destroy')->name('mperson.setupfuntionalcompetencyposition_destroy');


Route::get('manager_person/setupfuntionalcompetencylevel/{idref}','ManagerpersonController@setupfuntionalcompetencylevel')->name('mperson.setupfuntionalcompetencylevel');
Route::get('manager_person/setupfuntionalcompetencylevel_add/{idref}','ManagerpersonController@setupfuntionalcompetencylevel_add')->name('mperson.setupfuntionalcompetencylevel_add');
Route::post('manager_person/setupfuntionalcompetencylevel_save','ManagerpersonController@setupfuntionalcompetencylevel_save')->name('mperson.setupfuntionalcompetencylevel_save');
Route::get('manager_person/setupfuntionalcompetencylevel_edit/{idref}/{id}','ManagerpersonController@setupfuntionalcompetencylevel_edit')->name('mperson.setupfuntionalcompetencylevel_edit');
Route::post('manager_person/setupfuntionalcompetencylevel_update','ManagerpersonController@setupfuntionalcompetencylevel_update')->name('mperson.setupfuntionalcompetencylevel_update');



Route::get('manager_person/setupsetscore','ManagerpersonController@setupsetscore')->name('mperson.setupsetscore');
Route::get('manager_person/setupsetscore_add','ManagerpersonController@setupsetscore_add')->name('mperson.setupsetscore_add');
Route::post('manager_person/setupsetscore_save','ManagerpersonController@setupsetscore_save')->name('mperson.setupsetscore_save');
Route::get('manager_person/setupsetscore_edit/{idref}','ManagerpersonController@setupsetscore_edit')->name('mperson.setupsetscore_edit');
Route::post('manager_person/setupsetscore_update','ManagerpersonController@setupsetscore_update')->name('mperson.setupsetscore_update');

Route::get('manager_person/setupsetscoreweight','ManagerpersonController@setupsetscoreweight')->name('mperson.setupsetscoreweight');
Route::get('manager_person/setupsetscoreweight_add','ManagerpersonController@setupsetscoreweight_add')->name('mperson.setupsetscoreweight_add');
Route::get('manager_person/setupsetscoreweight_edit','ManagerpersonController@setupsetscoreweight_edit')->name('mperson.setupsetscoreweight_edit');

Route::get('manager_person/resultability','ManagerpersonController@resultability')->name('mperson.resultability');

//----------------------------อบรมดูงาน

Route::match(['get','post'],'manager_person/persondevreport','ManagerpersonController@persondevreport')->name('mperson.persondevreport');
Route::post('manager_person/persondevreport_search','ManagerpersonController@persondevreport_search')->name('mperson.persondevreport_search'); //ค้นหาตัวเดิม
Route::get('manager_person/persondevreport_excel/{datebegin}/{dateend}/{idperson}/{search}','ManagerpersonController@persondevreport_excel')->name('mperson.persondevreport_excel');
Route::get('manager_person/personmeettinginside','ManagerpersonController@personmeettinginside')->name('mperson.personmeettinginside');

//----------------------------ตั้งค่า KPIs

Route::get('manager_person/setupkpis','ManagerpersonController@setupkpis')->name('mperson.setupkpis');
Route::get('manager_person/setupkpis_add','ManagerpersonController@setupkpis_add')->name('mperson.setupkpis_add');
Route::post('manager_person/setupkpis_save','ManagerpersonController@setupkpis_save')->name('mperson.setupkpis_save');
Route::get('manager_person/setupkpis_edit/{idref}','ManagerpersonController@setupkpis_edit')->name('mperson.setupkpis_edit');
Route::post('manager_person/setupkpis_update','ManagerpersonController@setupkpis_update')->name('mperson.setupkpis_update');
Route::get('manager_person/setupkpis_destroy/{idref}','ManagerpersonController@setupkpis_destroy')->name('mperson.setupkpis_destroy');




//====================ประเมิน
Route::get('manager_person/jobdescription','ManagerpersonController@jobdescription')->name('mperson.jobdescription');

Route::get('manager_person/kpis','ManagerpersonController@kpis')->name('mperson.kpis');
Route::get('manager_person/kpis_detail','ManagerpersonController@kpis_detail')->name('mperson.kpis_detail');

Route::get('manager_person/funtionalcompetency','ManagerpersonController@funtionalcompetency')->name('mperson.funtionalcompetency');
Route::get('manager_person/funtionalcompetency_detail','ManagerpersonController@funtionalcompetency_detail')->name('mperson.funtionalcompetency_detail');

Route::get('manager_person/corecompetency','ManagerpersonController@corecompetency')->name('mperson.corecompetency');
Route::get('manager_person/corecompetency_detail','ManagerpersonController@corecompetency_detail')->name('mperson.corecompetency_detail');

Route::get('manager_person/checkscore','ManagerpersonController@checkscore')->name('mperson.checkscore');


//======================================รปภ===============================================

Route::get('manager_safe/dashboard','ManagersafeController@dashboard')->name('msafe.dashboard');
Route::post('manager_safe/dashboardsearch','ManagersafeController@dashboardsearch')->name('msafe.dashboardsearch');

Route::match(['get','post'],'manager_safe/infosafe','ManagersafeController@infosafe')->name('msafe.infosafe');
Route::post('manager_safe/saveinfosafe','ManagersafeController@save')->name('msafe.save');
Route::post('manager_safe/updateinfosafe','ManagersafeController@update')->name('msafe.update');
Route::get('manager_safe/infosafe/destroy/{id}','ManagersafeController@destroyinfosafe');
Route::post('manager_safe/infosafe/infosafesearch','ManagersafeController@infosafesearch')->name('msafe.infosafesearch'); // ค้นหาตัวเดิม

Route::match(['get','post'],'manager_safe/infocarperson','ManagersafeController@infocarperson')->name('msafe.infocarperson');


//======================================พัสดุ===============================================

Route::get('manager_supplies/usepurchase/{id}','ManagersuppliesController@usepurchase')->name('msupplies.usepurchase');//บันทึกขอซื้อ/ขอจ้าง
Route::post('manager_supplies/usepurchase_update','ManagersuppliesController@usepurchase_update')->name('msupplies.usepurchase_update');
Route::get('manager_supplies/usepurchasepdf01/{id}','ManagersuppliesController@usepurchasepdf01')->name('msupplies.usepurchasepdf01');
Route::get('manager_supplies/usepurchasepdf02/{id}','ManagersuppliesController@usepurchasepdf02')->name('msupplies.usepurchasepdf02');


Route::get('manager_supplies/historybuy/','ManagersuppliesController@historybuy')->name('msupplies.historybuy');
//-------------------------------------

Route::get('manager_supplies/dashboard','ManagersuppliesController@dashboard')->name('msupplies.dashboard');
Route::post('manager_supplies/dashboardsearch','ManagersuppliesController@dashboardsearch')->name('msupplies.dashboardsearch');
//-----รายละเอียดพัสดุ

Route::match(['get','post'],'manager_supplies/suppliesinfosub/{typedetail}','ManagersuppliesController@suppliesinfo')->name('msupplies.suppliesinfo');

Route::post('manager_supplies/suppliesinfosearch/{typedetail}','ManagersuppliesController@suppliesinfosearch')->name('msupplies.suppliesinfosearch'); // ค้นหาตัวเดิม
Route::post('manager_supplies/suppliesinfo/savesuppliesinfo','ManagersuppliesController@savesuppliesinfo')->name('msupplies.savesuppliesinfomation');

//--------------------ฟังชั่นเปิดปิดรายการ
Route::get('manager_supplies/switchactivesup','ManagersuppliesController@switchactivesup')->name('msupplies.switchactivesup');
//----------ฟังชั่นเช็คเลขซ้ำ
Route::get('/manager_supplies/checkfsn','ManagersuppliesController@checkfsn')->name('check.checkfsn');
//---------------------------------------------------------

//---แก้ไข
Route::get('manager_supplies/suppliesinfo/edit/{typedetail}/{id}','ManagersuppliesController@editsuppliesinfo')->name('msupplies.editsuppliesinfo');
Route::post('manager_supplies/suppliesinfo/updatesuppliesinfo','ManagersuppliesController@updatesuppliesinfo')->name('msupplies.updatesuppliesinfo');



//----ลบ
Route::get('manager_supplies/suppliesinfo/destroysuppliesinfo/{typedetail}/{id}','ManagersuppliesController@destroysuppliesinfo')->name('msupplies.destroysuppliesinfo');


Route::get('manager_supplies/suppliesinfo/suppliesinfoinasset/{id}','ManagersuppliesController@suppliesinfoinasset')->name('msupplies.suppliesinfoinasset');

//เพิ่มครุภัณฑ์
Route::get('manager_supplies/suppliesinfo/suppliesinfoinasset_add/{id}','ManagersuppliesController@savesuppliesinfoinasset')->name('msupplies.savesuppliesinfoinasset');
Route::post('manager_supplies/suppliesinfo/savesuppliesinfoinasset','ManagersuppliesController@saveinfosuppliesinfoinasset')->name('msupplies.saveinfosuppliesinfoinasset');

//------สร้างเลขวัสดุ

Route::get('manager_supplies/parcel','ManagersuppliesController@parcel')->name('dropdown.parcel');

//แก้ไขครุภัณฑ์
Route::get('manager_supplies/suppliesinfo/suppliesinfoinasset_edit/{id}/{asstid}','ManagersuppliesController@editsuppliesinfoinasset')->name('msupplies.editsuppliesinfoinasset');
Route::post('manager_supplies/suppliesinfo/updatesuppliesinfoinasset','ManagersuppliesController@updateinfosuppliesinfoinasset')->name('msupplies.updateinfosuppliesinfoinasset');

//ลบครุภัณฑ์
Route::get('manager_supplies/suppliesinfo/destroysuppliesinfoinasset/{id}/{asstid}','ManagersuppliesController@destroysuppliesinfoinasset')->name('msupplies.destroysuppliesinfoinasset');

Route::get('manager_supplies/suppliesinfoinassetbarcodepdf/{id}/','ManagersuppliesController@suppliesinfoinassetbarcodepdf')->name('msupplies.suppliesinfoinassetbarcodepdf');


                    //------2.10.62-------------//
Route::get('manager_supplies/datasuplies','ManagersuppliesController@datasuplies')->name('msupplies.datasuplies');


Route::match(['get','post'],'manager_supplies/purchase','ManagersuppliesController@purchase')->name('msupplies.purchase');
Route::post('manager_supplies/purchasesearch','ManagersuppliesController@searchpurchase')->name('msupplies.searchpurchase'); //ค้นหาเดิม


Route::get('manager_supplies/purchaseregister/{id}','ManagersuppliesController@createpurchaseregister')->name('msupplies.createpurchaseregister'); //ออกทะเบียนคุม


Route::post('manager_supplies/purchaseregister/savepurchaseregister','ManagersuppliesController@savepurchaseregister')->name('msupplies.savepurchaseregister');

Route::get('manager_supplies/purchaseregisteredit/{id}','ManagersuppliesController@editpurchaseregister')->name('msupplies.editpurchaseregister'); //แก้ไขทะเบียนคุม
Route::post('manager_supplies/purchaseregister/updatepurchaseregister','ManagersuppliesController@updatepurchaseregister')->name('msupplies.updatepurchaseregister');

Route::get('manager_supplies/purchasecancel/{id}','ManagersuppliesController@cancelpurchase')->name('msupplies.cancelpurchase');//---แจ้งยกเลิกทะเบียนคุม
Route::post('manager_supplies/purchasecancelupdate','ManagersuppliesController@cancelpurchaseupdate')->name('msupplies.cancelpurchaseupdate');

Route::get('manager_supplies/selectrequest','ManagersuppliesController@selectrequest')->name('msupplies.selectrequest');


Route::get('manager_supplies/purchaselist_add/{idlistref}','ManagersuppliesController@createpurchaselist')->name('msupplies.createpurchaselist'); //เพิ่มรายการวัสดุ
Route::post('manager_supplies/purchaselist_add/savepurchaselistadd','ManagersuppliesController@savepurchaselist')->name('msupplies.savepurchaselist');
Route::get('manager_supplies/checkunitref','ManagersuppliesController@checkunitref')->name('msupplies.checkunitref');
Route::get('manager_supplies/purchaselist_addrequest/{idlistref}','ManagersuppliesController@purchaselist_addrequest')->name('msupplies.purchaselist_addrequest'); //เพิ่มรายการวัสดุจากคำขอ

Route::get('manager_supplies/purchaselistaddsummoney','ManagersuppliesController@checksummoney')->name('msupplies.checksummoney');

Route::get('manager_supplies/purchaseboard_add','ManagersuppliesController@createpurchaseboard')->name('msupplies.createpurchaseboard'); //เพิ่มคณะกรรมการ

Route::get('manager_supplies/purchasequotation_add/{id}','ManagersuppliesController@createpurchasequotation')->name('msupplies.createpurchasequotation'); //ใบเสนอราคา

Route::get('manager_supplies/purchasequotation_addsub/{id}','ManagersuppliesController@createpurchasequotationsub')->name('msupplies.createpurchasequotationsub'); //เพิ่มใบเสนอราคา
Route::post('manager_supplies/purchasequotation_addsub/savepurchaselistaddsub','ManagersuppliesController@savepurchasequotationsub')->name('msupplies.savepurchasequotationsub');
Route::get('manager_supplies/fetchvendor','ManagersuppliesController@fetchvendor')->name('msupplies.fetchvendor');
Route::get('manager_supplies/selectvendor','ManagersuppliesController@selectvendor')->name('msupplies.selectvendor');
Route::get('manager_supplies/purchasequotation_addsubedit/{id}/{idref}','ManagersuppliesController@purchasequotationsubedit')->name('msupplies.purchasequotationsubedit');//---แก้ไขใบเสนอราคา
Route::post('manager_supplies/purchasequotation_addsubupdate','ManagersuppliesController@purchasequotationsubupdate')->name('msupplies.purchasequotationsubupdate');


Route::get('manager_supplies/purchasequotation_deletesub/{id}/{idref}','ManagersuppliesController@purchasequotationsubdelete')->name('msupplies.purchasequotationsubdelete');//---ลบใบเสนอราคา


Route::get('manager_supplies/purchaseorders_add/{idlistref}','ManagersuppliesController@createpurchaseorders')->name('msupplies.createpurchaseorders'); //ใบสั่งซื้อ
Route::get('manager_supplies/fetchtaxcal','ManagersuppliesController@fetchtaxcal')->name('msupplies.fetchtaxcal');
Route::get('manager_supplies/fetchtaxcal_list','ManagersuppliesController@fetchtaxcal_list')->name('msupplies.fetchtaxcal_list');
Route::post('manager_supplies/savepurchaseordersadd','ManagersuppliesController@savepurchaseorders')->name('msupplies.savepurchaseorders');

Route::get('manager_supplies/purchascheck/{idlistref}','ManagersuppliesController@purchascheck')->name('msupplies.purchascheck'); //ตรวจสอบรับ
Route::post('manager_supplies/savepurchascheck','ManagersuppliesController@savepurchascheck')->name('msupplies.savepurchascheck');

Route::get('manager_supplies/calldatesend','ManagersuppliesController@calldatesend')->name('msupplies.calldatesend');

Route::get('manager_supplies/purchasequotation_confirm/{id}','ManagersuppliesController@confirmpurchase')->name('msupplies.confirmpurchase');//---------ยืนยันตรวจรับ


Route::get('manager_supplies/purchasdetailprint','ManagersuppliesController@detailprint')->name('msupplies.detailprint');//สั่งพิมพ์
Route::get('manager_supplies/detailfsn','ManagersuppliesController@detailfsn')->name('msupplies.detailfsn');//โคลนรายการ
Route::post('manager_supplies/detailfsn_save','ManagersuppliesController@detailfsn_save')->name('msupplies.detailfsn_save');

Route::match(['get','post'],'manager_supplies/requestforbuy','ManagersuppliesController@requestforbuy')->name('msupplies.requestforbuy');
Route::post('manager_supplies/requestforbuysearch','ManagersuppliesController@requestforbuysearch')->name('msupplies.requestforbuysearch'); //ค้นหาเดิม

Route::get('manager_supplies/purchase_excel/{yearbudget}/{datebigin}/{dateend}/{status}/{search}','ManagersuppliesController@purchase_excel')->name('msupplies.purchase_excel');//excel สขร.
Route::get('manager_supplies/purchase_excel_5000/{yearbudget}/{datebigin}/{dateend}/{status}/{search}','ManagersuppliesController@purchase_excel_5000')->name('msupplies.purchase_excel_5000');//excel ต่ำกว่า 5000.

Route::get('manager_supplies/requestforbuy_add','ManagersuppliesController@createrequestforbuy')->name('msupplies.createrequestforbuy');

Route::get('manager_supplies/requestforbuyedit/{id}','ManagersuppliesController@requestforbuyedit')->name('msupplies.requestforbuyedit');//---แก้ไขการเบิกจ่าย
Route::post('manager_supplies/requestforbuyupdate','ManagersuppliesController@requestforbuyupdate')->name('msupplies.requestforbuyupdate');

Route::get('manager_supplies/requestforbuycancel/{id}','ManagersuppliesController@cancelrequestforbuy')->name('msupplies.cancelrequestforbuy');//---แจ้งยกเลิกการเบิกจ่าย
Route::post('manager_supplies/requestforbuycancelupdate','ManagersuppliesController@cancelrequestforbuyupdate')->name('msupplies.cancelrequestforbuyupdate');




Route::get('manager_supplies/detailapp','ManagersuppliesController@detailapp')->name('msupplies.detailapp');
Route::post('manager_supplies/inforequestverupdate','ManagersuppliesController@updateinforequestver')->name('msupplies.updateinforequestver');

Route::get('manager_supplies/suppliesinfo_add/{typedetail}','ManagersuppliesController@createsuppliesinfo')->name('msupplies.createsuppliesinfo');
Route::get('manager_supplies/suppliesinfo/save','ManagersuppliesController@savesuppliesinfo')->name('msupplies.savesuppliesinfo');
Route::get('manager_supplies/fetchsubtype','ManagersuppliesController@fetchsubtype')->name('msupplies.fetchsubtype');
Route::get('manager_supplies/checkfetchsubtype','ManagersuppliesController@checkfetchsubtype')->name('msupplies.checkfetchsubtype');
Route::get('manager_supplies/fetchmedicine','ManagersuppliesController@fetchmedicine')->name('msupplies.fetchmedicine');

Route::get('manager_supplies/selectfsn','ManagersuppliesController@selectfsn')->name('msupplies.selectfsn');


//----ส่งข้อมูลไปยังระบบคลัง

Route::get('manager_supplies/send_infomation/{id}','ManagersuppliesController@sendifomation')->name('msupplies.sendifomation');

//===================ขายครุภัฑณ์ที่จำหน่ายออก

Route::get('manager_supplies/infosoldout','ManagersuppliesController@infosoldout')->name('msupplies.infosoldout');
Route::get('manager_supplies/infosoldout_add','ManagersuppliesController@infosoldout_add')->name('msupplies.infosoldout_add');
Route::post('manager_supplies/infosoldout_save','ManagersuppliesController@infosoldout_save')->name('msupplies.infosoldout_save');
Route::get('manager_supplies/infosoldout_edit/{id}','ManagersuppliesController@infosoldout_edit')->name('msupplies.infosoldout_edit');
Route::post('manager_supplies/infosoldout_update','ManagersuppliesController@infosoldout_update')->name('msupplies.infosoldout_update');
Route::get('manager_supplies/infosoldout_delete/{id}','ManagersuppliesController@infosoldout_delete')->name('msupplies.infosoldout_delete');

Route::get('manager_supplies/infosoldout_sub','ManagersuppliesController@infosoldout_sub')->name('msupplies.infosoldout_sub');

//=======ฟังชั่นรายละเอียด
Route::get('manager_supplies/condition','ManagersuppliesController@condition')->name('dropdown.condition');
Route::get('manager_supplies/amountdate','ManagersuppliesController@amountdate')->name('msupplies.amountdate');
Route::get('manager_supplies/theboard','ManagersuppliesController@theboard')->name('msupplies.theboard');

//--ฟังชั่นในการเพิ่มทรัพย์สิน
Route::get('manager_supplies/addlocation/','ManagersuppliesController@addbrand')->name('msupplies.addbrand');
Route::get('manager_supplies/addmodel/','ManagersuppliesController@addmodel')->name('msupplies.addmodel');
Route::get('manager_supplies/addcolor/','ManagersuppliesController@addcolor')->name('msupplies.addcolor');
Route::get('manager_supplies/addsize/','ManagersuppliesController@addsize')->name('msupplies.addsize');





//============================ตั้งค่าfsn

Route::get('manager_supplies/setupfsn','ManagersuppliesController@setupfsn')->name('msupplies.setupfsn'); //ตั้งค่า FSN
Route::get('manager_supplies/setupfsn/switchactivefsn','ManagersuppliesController@switchactivefsn')->name('setup.switchactivefsn');

Route::get('manager_supplies/setupfsnsub/{groupcode}','ManagersuppliesController@setupfsnsub')->name('msupplies.setupfsnsub');
Route::get('manager_supplies/setupfsn/switchactivefsnsub','ManagersuppliesController@switchactivefsnsub')->name('setup.switchactivefsnsub');
Route::post('manager_supplies/setupfsnsub_save/save','ManagersuppliesController@savesetupfsnsub')->name('msupplies.savesetupfsnsub');
Route::post('manager_supplies/setupfsnsub_update/update','ManagersuppliesController@updatesetupfsnsub')->name('msupplies.updatesetupfsnsub');

Route::get('manager_supplies/setupfsnsub_destroy/{groupcode}/{classcode}','ManagersuppliesController@setupfsnsub_destroy')->name('msupplies.setupfsnsub_destroy');//ลบ หมวด
Route::get('manager_supplies/setupfsnsub_destroy/{id}/{groupcode}','ManagersuppliesController@setupfsnsub_destroy')->name('msupplies.setupfsnsub_destroy');//ลบ หมวด
Route::get('manager_supplies/setupfsnsub_destroy/{id}/{groupcode}/{classcode}','ManagersuppliesController@setupfsnsub_destroy')->name('msupplies.setupfsnsub_destroy');//ลบ หมวด

Route::get('manager_supplies/setupfsnsubsub/{groupcode}/{classcode}','ManagersuppliesController@setupfsnsubsub')->name('msupplies.setupfsnsubsub');
Route::get('manager_supplies/setupfsnsubsub/switchactivefsnsubsub','ManagersuppliesController@switchactivefsnsubsub')->name('setup.switchactivefsnsubsub');

Route::post('manager_supplies/setupfsnsubsub_save/save','ManagersuppliesController@setupfsnsubsub_save')->name('msupplies.setupfsnsubsub_save');
Route::post('manager_supplies/setupfsnsubsub_update/update','ManagersuppliesController@setupfsnsubsub_update')->name('msupplies.setupfsnsubsub_update');
Route::get('manager_supplies/setupfsnsubsub_destroy/{id}/{groupcode}/{classcode}','ManagersuppliesController@setupfsnsubsub_destroy')->name('msupplies.setupfsnsubsub_destroy');//ลบ หมวด

Route::get('manager_supplies/setupfsnsubsubfinish/{groupcode}/{classcode}/{typecode}','ManagersuppliesController@setupfsnsubsubfinish')->name('msupplies.setupfsnsubsubfinish');
Route::get('manager_supplies/setupfsnsubsubfinish/switchactivefsnsubsubfinish','ManagersuppliesController@switchactivefsnsubsubfinish')->name('setup.switchactivefsnsubsubfinish');

Route::post('manager_supplies/setupfsnsubsubfinish_save','ManagersuppliesController@savesetupfsnsubsubfinish')->name('msupplies.savesetupfsnsubsubfinish');//เพิ่ม fsn

Route::post('manager_supplies/setupfsnsubsubfinish_update','ManagersuppliesController@updatesetupfsnsubsubfinish')->name('msupplies.updatesetupfsnsubsubfinish');//แก้ไข fsn

Route::get('manager_supplies/setupfsnsubsubfinish_destroy/{groupcode}/{classcode}/{typecode}/{propcode}','ManagersuppliesController@destroysetupfsnsubsubfinish')->name('msupplies.destroysetupfsnsubsubfinish');//ลบ fsn


//===================================ตั้งค่าผู้แทนจำหน่าย

Route::get('manager_supplies/setupsuppliesvendor','ManagersuppliesController@infosuppliesvendor')->name('msupplies.infosuppliesvendor');
Route::get('manager_supplies/setupsuppliesvendor/add','ManagersuppliesController@createsuppliesvendor')->name('msupplies.createsuppliesvendor');
Route::post('manager_supplies/setupsuppliesvendor/save','ManagersuppliesController@savesuppliesvendor')->name('msupplies.savesuppliesvendor');
Route::get('manager_supplies/setupsuppliesvendor/edit/{id}','ManagersuppliesController@editsuppliesvendor')->name('msupplies.editsuppliesvendor');
Route::post('manager_supplies/setupsuppliesvendor/update','ManagersuppliesController@updatesuppliesvendor')->name('msupplies.updatesuppliesvendor');
Route::get('manager_supplies/setupsuppliesvendor/destroy/{id}','ManagersuppliesController@destroysuppliesvendor');
Route::get('manager_supplies/setupsuppliesvendor/switchactivevendor','ManagersuppliesController@switchactivevendor')->name('msupplies.vendor');

//===================================ตั้งค่ากำหนดมูลค่าแผนวัสดุ
Route::get('manager_supplies/setupsupplies_year_material_plan_value','ManagersuppliesController@material_year_plan_value')->name('msupplies.material_year_plan_value');
Route::get('manager_supplies/add_year_setupsupplies_material_plan_value','ManagersuppliesController@add_year_material_plan_value')->name('msupplies.add_year_material_plan_value');
Route::post('manager_supplies/save_year_setupsupplies_material_plan_value','ManagersuppliesController@save_year_material_plan_value')->name('msupplies.save_year_material_plan_value');

Route::get('manager_supplies/setupsupplies_material_plan_value/{id}','ManagersuppliesController@material_plan_value')->name('msupplies.material_plan_value');
Route::get('manager_supplies/add_setupsupplies_material_plan_value/{id}','ManagersuppliesController@add_material_plan_value')->name('msupplies.add_material_plan_value');
Route::post('manager_supplies/save_setupsupplies_material_plan_value','ManagersuppliesController@save_material_plan_value')->name('msupplies.save_material_plan_value');

Route::get('manager_supplies/destroy_setupsupplies_material_plan_value/{ids}/{id}','ManagersuppliesController@destroy_material_plan_value')->name('msupplies.destroy_material_plan_value');
Route::get('manager_supplies/up_setupsupplies_material_plan_value','ManagersuppliesController@up_material_plan_value')->name('msupplies.up_material_plan_value');

Route::get('manager_supplies/setupsupplies_excel_material_plan_value','ManagersuppliesController@excel_material_plan_value')->name('msupplies.excel_material_plan_value');



//===================================ตั้งค่ากำหนดมูลค่าแผนวัสดุ
Route::get('manager_supplies/setupsupplies_year_material_plan_value','ManagersuppliesController@material_year_plan_value')->name('msupplies.material_year_plan_value');
Route::get('manager_supplies/add_year_setupsupplies_material_plan_value','ManagersuppliesController@add_year_material_plan_value')->name('msupplies.add_year_material_plan_value');
Route::post('manager_supplies/save_year_setupsupplies_material_plan_value','ManagersuppliesController@save_year_material_plan_value')->name('msupplies.save_year_material_plan_value');

Route::get('manager_supplies/setupsupplies_material_plan_value/{id}','ManagersuppliesController@material_plan_value')->name('msupplies.material_plan_value');
Route::get('manager_supplies/add_setupsupplies_material_plan_value/{id}','ManagersuppliesController@add_material_plan_value')->name('msupplies.add_material_plan_value');
Route::post('manager_supplies/save_setupsupplies_material_plan_value','ManagersuppliesController@save_material_plan_value')->name('msupplies.save_material_plan_value');

Route::get('manager_supplies/destroy_setupsupplies_material_plan_value/{ids}/{id}','ManagersuppliesController@destroy_material_plan_value')->name('msupplies.destroy_material_plan_value');
Route::get('manager_supplies/up_setupsupplies_material_plan_value','ManagersuppliesController@up_material_plan_value')->name('msupplies.up_material_plan_value');

Route::get('manager_supplies/setupsupplies_excel_material_plan_value','ManagersuppliesController@excel_material_plan_value')->name('msupplies.excel_material_plan_value');


//=================================== file PDF
Route::get('manager_supplies/pdfdirecdetail/export_pdfdirecdetail/{idref}', 'ManagersuppliesController@pdfdirecdetail')->name('msupplies.pdfdirecdetail');//ใบอนุมัติแต่งตั้งคณะกรรมการรายละเอียด
Route::get('manager_supplies/pdfdirecapp/export_pdfdirecapp/{idref}', 'ManagersuppliesController@pdfdirecapp')->name('msupplies.pdfdirecapp');//ใบขอความเห็นรายงานผล
Route::get('manager_supplies/pdftestresult/export_pdftestresult/{idref}', 'ManagersuppliesController@pdftestresult')->name('msupplies.pdftestresult');//รายงานผลการตรวจรับพัสดุ

Route::get('manager_supplies/pdfwant/export_pdfwant/{iduser}', 'ManagersuppliesController@pdfwant')->name('msupplies.pdfwant');//ใบแสดงความตองการพัสด
Route::get('manager_supplies/pdfmemo/export_pdfmemo/{idref}', 'ManagersuppliesController@pdfmemo')->name('msupplies.pdfmemo');//ใบบันทึกข้อความ
Route::get('manager_supplies/pdfcheck/export_pdfcheck/{idref}', 'ManagersuppliesController@pdfcheck')->name('msupplies.pdfcheck');//ใบตรวจรับ
Route::get('manager_supplies/pdfwin/export_pdfwin/{idref}', 'ManagersuppliesController@pdfwin')->name('msupplies.pdfwin');//ใบประกาศผู้ชน่ะ
Route::get('manager_supplies/pdfinnocent/export_pdfinnocent/{idref}', 'ManagersuppliesController@pdfinnocent')->name('msupplies.pdfinnocent');//ใบประกาศบริสุทธืใจ
Route::get('manager_supplies/pdfaccount/export_pdfaccount/{idref}', 'ManagersuppliesController@pdfaccount')->name('msupplies.pdfaccount');//รายการขออนุมัติบัญชี
Route::get('manager_supplies/pdfheadstyle/export_pdfheadstyle/{idref}', 'ManagersuppliesController@pdfheadstyle')->name('msupplies.pdfheadstyle');//ขออนุมัติจัดซื้อจัดจ้าง
Route::get('manager_supplies/pdfstyle/export_pdfstyle/{idref}', 'ManagersuppliesController@pdfstyle')->name('msupplies.pdfstyle');//รายการคุณลักษณะพัสดุ
Route::get('manager_supplies/pdfresult/export_pdfresult/{idref}', 'ManagersuppliesController@pdfresult')->name('msupplies.pdfresult');//รายงานผลการพิจารณา
Route::get('manager_supplies/pdfpuchase/export_pdfpuchase/{idref}', 'ManagersuppliesController@pdfpuchase')->name('msupplies.pdfpuchase');//ใบสั่งซื้อ
Route::get('manager_supplies/pdfboard/export_pdfboard/{idref}', 'ManagersuppliesController@pdfboard')->name('msupplies.pdfboard');//ใบแต่งตั้งคณะกรรมการ
//======================================ยาและเวชภัณฑ์===============================================
Route::match(['get','post'],'manager_medical/dashboard','ManagermedicalController@dashboard')->name('mmedical.dashboard');
Route::get('manager_medical/dashboard_request_status','ManagermedicalController@dashboard_request_status')->name('mmedical.dashboard_request_status');

Route::match(['get','post'],'manager_medical/reportInventory','ManagermedicalController@reportInventory')->name('mmedical.reportInventory');//รายงานคงคลัง
Route::post('manager_medical/reportinventorysave','ManagermedicalController@reportinventorysave');
Route::post('manager_medical/reportInventorysearch','ManagermedicalController@reportInventorysearch')->name('mmedical.reportInventorysearch');

Route::match(['get','post'],'manager_medical/requestforbuy','ManagermedicalController@requestforbuy')->name('mmedical.requestforbuy');
Route::post('manager_medical/requestforbuysearch','ManagermedicalController@requestforbuysearch')->name('mmedical.requestforbuysearch'); // ค้นหาเดิม
Route::get('manager_medical/medical_requestforbuy_add','ManagermedicalController@medical_requestforbuy_add')->name('mmedical.medical_requestforbuy_add');
Route::post('manager_medical/medical_requestforbuy_save','ManagermedicalController@medical_requestforbuy_save')->name('mmedical.medical_requestforbuy_save');
Route::get('manager_medical/medical_requestforbuy_edit/{id}','ManagermedicalController@medical_requestforbuy_edit')->name('mmedical.medical_requestforbuy_edit');
Route::post('manager_medical/medical_requestforbuy_update','ManagermedicalController@medical_requestforbuy_update')->name('mmedical.medical_requestforbuy_update');
Route::get('manager_medical/medical_requestforbuy_cancel/{id}','ManagermedicalController@medical_requestforbuy_cancel')->name('mmedical.medical_requestforbuy_cancel');
Route::post('manager_medical/medical_requestforbuy_update_cancel','ManagermedicalController@medical_requestforbuy_update_cancel')->name('mmedical.medical_requestforbuy_update_cancel');

Route::post('manager_medical/medical_inforequestverupdate','ManagermedicalController@medical_inforequestverupdate')->name('mmedical.medical_inforequestverupdate');


Route::match(['get','post'],'manager_medical/purchase','ManagermedicalController@purchase')->name('mmedical.purchase');
Route::post('manager_medical/purchasesearch','ManagermedicalController@purchasesearch')->name('mmedical.purchasesearch'); // ค้นหาเดิม
Route::post('manager_medical/purchasesetup','ManagermedicalController@purchasesetup')->name('mmedical.purchasesetup');//อัพเดทตัวตั้งค่า

Route::get('manager_medical/medical_purchase_register/{id}','ManagermedicalController@medical_purchase_register')->name('mmedical.medical_purchase_register');
Route::get('manager_medical/medical_purchaseboard_add','ManagermedicalController@medical_purchaseboard_add')->name('mmedical.medical_purchaseboard_add');
Route::post('manager_medical/medical_purchaseregister_save','ManagermedicalController@medical_purchaseregister_save')->name('mmedical.medical_purchaseregister_save');
Route::get('manager_medical/medical_purchaseregister_edit/{id}','ManagermedicalController@medical_purchaseregister_edit')->name('mmedical.medical_purchaseregister_edit'); //แก้ไขทะเบียนคุม
Route::post('manager_medical/medical_purchaseregister_update','ManagermedicalController@medical_purchaseregister_update')->name('mmedical.medical_purchaseregister_update');
Route::get('manager_medical/medical_purchasecancel/{id}','ManagermedicalController@medical_purchasecancel')->name('mmedical.medical_purchasecancel');//---แจ้งยกเลิกทะเบียนคุม
Route::post('manager_medical/medical_purchasecancelupdate','ManagermedicalController@medical_purchasecancelupdate')->name('mmedical.medical_purchasecancelupdate');
Route::get('manager_medical/medical_selectrequest','ManagermedicalController@medical_selectrequest')->name('mmedical.medical_selectrequest');


Route::get('manager_medical/medical_purchaseorders_add/{idlistref}','ManagermedicalController@medical_purchaseorders_add')->name('mmedical.medical_purchaseorders_add'); //ใบสั่งซื้อ
Route::post('manager_medical/medical_purchasequotation_addsub/savepurchaselistaddsub','ManagermedicalController@medical_savepurchasequotationsub')->name('mmedical.savepurchasequotationsub');

Route::get('manager_medical/medical_fetchtaxcal','ManagermedicalController@medical_fetchtaxcal')->name('mmedical.medical_fetchtaxcal');
Route::post('manager_medical/medical_purchaseorders_save','ManagermedicalController@medical_purchaseorders_save')->name('mmedical.medical_purchaseorders_save');

Route::get('manager_medical/medical_purchasequotation_deletesub/{id}/{idref}','ManagermedicalController@medical_purchasequotationsubdelete')->name('mmedical.purchasequotationsubdelete');//---ลบใบเสนอราคา

Route::get('manager_medical/medical_purchascheck/{idlistref}','ManagermedicalController@medical_purchascheck')->name('mmedical.medical_purchascheck');
Route::post('manager_medical/medical_purchascheck_save','ManagermedicalController@medical_purchascheck_save')->name('mmedical.medical_purchascheck_save');

Route::get('manager_medical/purchaselist_addrequest/{idlistref}','ManagermedicalController@purchaselist_addrequest')->name('mmedical.purchaselist_addrequest'); //เพิ่มรายการวัสดุ
Route::post('manager_medical/purchaselist_add/savepurchaselistadd','ManagermedicalController@medical_savepurchaselist')->name('mmedical.medical_savepurchaselist');
Route::get('manager_medical/medical_purchaselist_add/{idlistref}','ManagermedicalController@medical_purchaselist_add')->name('mmedical.medical_purchaselist_add');


Route::get('manager_medical/medical_purchaselist_edit/{idlistref}','ManagermedicalController@medical_purchaselist_edit')->name('mmedical.medical_purchaselist_edit');


Route::get('manager_medical/medical_purchasequotation_add/{id}','ManagermedicalController@medical_purchasequotation_add')->name('mmedical.medical_purchasequotation_add');
Route::get('manager_medical/medical_purchasequotation_addsub/{id}','ManagermedicalController@medical_purchasequotation_addsub')->name('mmedical.medical_purchasequotation_addsub');
Route::get('manager_medical/medical_purchasequotation_editsub/{id}/{idref}','ManagermedicalController@medical_purchasequotation_editsub')->name('mmedical.medical_purchasequotation_editsub');
Route::post('manager_medical/medical_purchasequotation_addsubupdate','ManagermedicalController@medical_purchasequotation_addsubupdate')->name('mmedical.purchasequotationsubupdate');

Route::get('manager_medical/medical_purchasequotation_confirm/{id}','ManagermedicalController@medical_purchasequotation_confirm')->name('mmedical.medical_purchasequotation_confirm');//---------ยืนยันตรวจรับ

Route::get('manager_medical/medical_purchasdetailprint','ManagermedicalController@medical_purchasdetailprint')->name('mmedical.medical_purchasdetailprint');//สั่งพิมพ์

//----ส่งข้อมูลไปยังระบบคลัง

Route::get('manager_medical/medical_send_infomation/{id}','ManagermedicalController@medical_send_infomation')->name('mmedical.medical_send_infomation');


Route::match(['get','post'],'manager_medical/suppliesinfo','ManagermedicalController@suppliesinfo')->name('mmedical.suppliesinfo');
Route::post('manager_medical/suppliesinfosearch','ManagermedicalController@suppliesinfosearch')->name('mmedical.suppliesinfosearch');

Route::get('manager_medical/suppliesinfo_add','ManagermedicalController@createsuppliesinfo')->name('mmedical.createsuppliesinfo');


Route::get('manager_medical/medical_add','ManagermedicalController@medical_add')->name('mmedical.medical_add');
Route::post('manager_medical/medicalinfo/savemedicalinfo','ManagermedicalController@savemedicalinfo')->name('mmedical.savemedicalinfo');

Route::get('manager_medical/medical_edit/{id}','ManagermedicalController@medical_edit')->name('mmedical.medical_edit');
Route::post('manager_medical/medicalinfo/updatemedicalinfo','ManagermedicalController@updatemedicalinfo')->name('mmedical.updatemedicalinfo');

Route::get('manager_medical/medical_suppliesinfo_save','ManagermedicalController@medi_suppliesinfo_save')->name('mmedical.medi_suppliesinfo_save');

//-----------------------คลังยา

Route::get('manager_medical/dashboard','ManagermedicalController@dashboard')->name('mmedical.dashboard');
Route::post('manager_medical/dashboardsearch','ManagermedicalController@dashboardsearch')->name('mmedical.dashboardsearch');



Route::get('manager_medical/addtypeitem','ManagermedicalController@addtypeitem')->name('mmedical.addtypeitem');
Route::get('manager_medical/addgroup','ManagermedicalController@addgroup')->name('mmedical.addgroup');
Route::get('manager_medical/addcategory','ManagermedicalController@addcategory')->name('mmedical.addcategory');
//---ตรวจสอบ
Route::match(['get','post'],'manager_medical/detail','ManagermedicalController@detail')->name('mmedical.detail');
Route::get('manager_medical/detail_edit/{id}','ManagermedicalController@detail_edit')->name('mmedical.detail_edit');
Route::post('manager_medical/detail_update','ManagermedicalController@detail_update')->name('mmedical.detail_update');
Route::get('manager_medical/medicalinfocheckdetali_edit/{id}','ManagermedicalController@medicalinfocheckdetali_edit')->name('mmedical.medicalinfocheckdetali_edit');
Route::post('manager_medical/detail_update','ManagermedicalController@detail_update')->name('mmedical.detail_update');
Route::post('manager_medical/detailsearch','ManagermedicalController@detailsearch')->name('mmedical.detailsearch'); //ค้นหาเดิม


Route::post('manager_medical/saveinfocheckadd','ManagermedicalController@saveinfocheckadd')->name('mmedical.saveinfocheckadd');

Route::post('manager_medical/updateinfochecksup','ManagermedicalController@updateinfochecksup')->name('mmedical.updateinfochecksup');

Route::get('manager_medical/medicalinfocheck_add','ManagermedicalController@infocheckadd')->name('mmedical.infocheckadd');
Route::get('manager_medical/medicalinfochecksup/{idref}','ManagermedicalController@infochecksup')->name('mmedical.infochecksup');

Route::get('manager_medical/medicalinfocheckdetali/{idref}','ManagermedicalController@infocheckdetali')->name('mmedical.infocheckdetali');
Route::get('manager_medical/medicalinfoconfirmdetali/{idref}','ManagermedicalController@infoconfirmdetali')->name('mmedical.infoconfirmdetail'); //ยืนยันรับเข้าคลัง
Route::post('manager_medical/updatemedicalinfoconfirmdetali','ManagermedicalController@updatewarehouseinfoconfirmdetail')->name('mmedical.updatewarehouseinfoconfirmdetail'); 

//---เบิกจ่าย
Route::match(['get','post'],'manager_medical/disburse','ManagermedicalController@disburse')->name('mmedical.disburse');
Route::get('manager_medical/disbursesearch','ManagermedicalController@disbursesearch')->name('mmedical.disbursesearch');

Route::get('manager_medical/medicalinfopayparcel/{id}','ManagermedicalController@infopayparcel')->name('mmedical.infopayparcel'); //จ่ายวัสดุ
Route::post('manager_medical/updateinfopayparcel','ManagermedicalController@updateinfopayparcel')->name('mmedical.updateinfopayparcel');

Route::get('manager_medical/detailsup','ManagermedicalController@detailsup')->name('mmedical.detailsup');

Route::get('manager_medical/selectsup','ManagermedicalController@selectsup')->name('mmedical.selectsup');
Route::get('manager_medical/selectsuplot','ManagermedicalController@selectsuplot')->name('mmedical.selectsuplot');
Route::get('manager_medical/selectsuptotal','ManagermedicalController@selectsuptotal')->name('mmedical.selectsuptotal');
Route::get('manager_medical/selectsupunit','ManagermedicalController@selectsupunit')->name('mmedical.selectsupunit');
Route::get('manager_medical/selectsuppiceunit','ManagermedicalController@selectsuppiceunit')->name('mmedical.selectsuppiceunit');
Route::get('manager_medical/selectsupdatget','ManagermedicalController@selectsupdatget')->name('mmedical.selectsupdatget');
Route::get('manager_medical/selectsupdatexp','ManagermedicalController@selectsupdatexp')->name('mmedical.selectsupdatexp');


Route::post('manager_medical/updatemedicalrequestlastapp','ManagermedicalController@updatewarehouserequestlastapp')->name('mmedical.updatewarehouserequestlastapp');
//---คลังหลัก
Route::match(['get','post'],'manager_medical/storehouse','ManagermedicalController@storehouse')->name('mmedical.storehouse');
Route::get('manager_medical/medicalstorehouse_detail/{id}','ManagermedicalController@medicalstorehouse_detail')->name('mmedical.medicalstorehouse_detail');

Route::get('manager_warehouse/storehouse_detail_excel/{id}','ManagerwarehouseController@storehouse_detail_excel')->name('mwarehouse.storehouse_detail_excel');

Route::post('manager_medical/storehousesearch','ManagermedicalController@storehousesearch')->name('mmedical.storehousesearch'); //ค้นหาเดิม
Route::post('manager_medical/storehousesub_search','ManagermedicalController@storehousesub_search')->name('mmedical.storehousesub_search');
Route::post('manager_medical/treasury_search','ManagermedicalController@treasury_search')->name('mmedical.treasury_search');




Route::get('manager_medical/storehousesub/{id}','ManagermedicalController@storehousesub')->name('mmedical.storehousesub');

Route::get('manager_medical/medical_order/{id}','ManagermedicalController@medical_order')->name('mmedical.medical_order');
Route::get('manager_medical/medical_withdrawal/{id}','ManagermedicalController@medical_withdrawal')->name('mmedical.medical_withdrawal');
//---คลังย่อย
Route::match(['get','post'],'manager_medical/treasury','ManagermedicalController@treasury')->name('mmedical.treasury');
Route::get('manager_medical/treasury_sub/{id}','ManagermedicalController@treasury_sub')->name('mmedical.treasurysub');

Route::get('manager_medical/treasury_sub_detail/{id}','ManagermedicalController@treasury_sub_detail')->name('mmedical.treasury_sub_detail');
Route::post('manager_medical/treasurysub_search','ManagermedicalController@treasurysub_search')->name('mmedical.treasurysub_search');



//---รายงาน

Route::get('manager_medical/reportvalue','ManagermedicalController@reportvalue')->name('mmedical.reportvalue');
Route::post('manager_medical/reportvaluesearch','ManagermedicalController@reportvaluesearch')->name('mmedical.reportvaluesearch');

Route::get('manager_medical/reportvaluestore','ManagermedicalController@reportvaluestore')->name('mmedical.reportvaluestore');
Route::post('manager_medical/reportvaluestoresearch','ManagermedicalController@reportvaluestoresearch')->name('mmedical.reportvaluestoresearch');

Route::get('manager_medical/reportvaluetreasury','ManagermedicalController@reportvaluetreasury')->name('mmedical.reportvaluetreasury');
Route::post('manager_medical/reportvaluetreasurysearch','ManagermedicalController@reportvaluetreasurysearch')->name('mmedical.reportvaluetreasurysearch');


Route::get('manager_medical/pdfwant/export_pdfwant/{iduser}', 'ManagermedicalController@pdfwant')->name('mmedical.pdfwant');//ใบแสดงความตองการ
Route::get('manager_medical/pdfmemo/export_pdfmemo/{idref}', 'ManagermedicalController@pdfmemo')->name('mmedical.pdfmemo');//ใบบันทึกข้อความ
Route::get('manager_medical/pdfcheck/export_pdfcheck/{idref}', 'ManagermedicalController@pdfcheck')->name('mmedical.pdfcheck');//ใบตรวจรับ
Route::get('manager_medical/pdfwin/export_pdfwin/{idref}', 'ManagermedicalController@pdfwin')->name('mmedical.pdfwin');//ใบประกาศผู้ชน่ะ
Route::get('manager_medical/pdfinnocent/export_pdfinnocent/{idref}', 'ManagermedicalController@pdfinnocent')->name('mmedical.pdfinnocent');//ใบประกาศบริสุทธืใจ
Route::get('manager_medical/pdfaccount/export_pdfaccount/{idref}', 'ManagermedicalController@pdfaccount')->name('mmedical.pdfaccount');//รายการขออนุมัติบัญชี
Route::get('manager_medical/pdfheadstyle/export_pdfheadstyle/{idref}', 'ManagermedicalController@pdfheadstyle')->name('mmedical.pdfheadstyle');//ขออนุมัติจัดซื้อจัดจ้าง
Route::get('manager_medical/pdfstyle/export_pdfstyle/{idref}', 'ManagermedicalController@pdfstyle')->name('mmedical.pdfstyle');//รายการคุณลักษณะ
Route::get('manager_medical/pdfresult/export_pdfresult/{idref}', 'ManagermedicalController@pdfresult')->name('mmedical.pdfresult');//รายงานผลการพิจารณา
Route::get('manager_medical/pdfpuchase/export_pdfpuchase/{idref}', 'ManagermedicalController@pdfpuchase')->name('mmedical.pdfpuchase');//ใบสั่งซื้อ
Route::get('manager_medical/pdfboard/export_pdfboard/{idref}', 'ManagermedicalController@pdfboard')->name('mmedical.pdfboard');//ใบแต่งตั้งคณะกรรมการยา

Route::get('manager_medical/setting/inventory/', 'ManagermedicalController@setting_inventory')->name('mmedical.setting_inventory');//ตั้งค่าประเภทคลังยา
Route::get('manager_medical/setting/inventory/add', 'ManagermedicalController@setting_inventory_add')->name('mmedical.setting_inventory_add');//เพิ่มประเภทคลังยา
Route::post('manager_medical/setting/inventory/save', 'ManagermedicalController@setting_inventory_save')->name('mmedical.setting_inventory_save');//บันทึกประเภทคลังยา
Route::get('manager_medical/setting/inventory/edit/{id}', 'ManagermedicalController@setting_inventory_edit')->name('mmedical.setting_inventory_edit');//แก้ไขเภทคลังยา
Route::post('manager_medical/setting/inventory/update', 'ManagermedicalController@setting_inventory_update')->name('mmedical.setting_inventory_update');//แก้ไขเภทคลังยา
Route::get('manager_medical/setting/inventory/delete/{id}', 'ManagermedicalController@setting_inventory_delete')->name('mmedical.setting_inventory_delete');//ลบประเภทคลังยา

Route::get('manager_medical/setting/category/', 'ManagermedicalController@setting_category')->name('mmedical.setting_category');//ตั้งค่าประเภทคลังยา
Route::get('manager_medical/setting/category/add', 'ManagermedicalController@setting_category_add')->name('mmedical.setting_category_add');//เพิ่มประเภทคลังยา
Route::post('manager_medical/setting/category/save', 'ManagermedicalController@setting_category_save')->name('mmedical.setting_category_save');//บันทึกประเภทคลังยา
Route::get('manager_medical/setting/category/edit/{id}', 'ManagermedicalController@setting_category_edit')->name('mmedical.setting_category_edit');//แก้ไขเภทคลังยา
Route::post('manager_medical/setting/category/update', 'ManagermedicalController@setting_category_update')->name('mmedical.setting_category_update');//แก้ไขเภทคลังยา
Route::get('manager_medical/setting/category/delete/{id}', 'ManagermedicalController@setting_category_delete')->name('mmedical.setting_category_delete');//ลบประเภทคลังยา

Route::match(['get','post'],'manager_warehouse/reportquarter','ManagerwarehouseController@reportquarter')->name('mwarehouse.reportquarter');
// Route::get('manager_warehouse/reportquarter','ManagerwarehouseController@reportquarter')->name('mwarehouse.reportquarter'); //รายงานไตรมาส
// Route::post('manager_warehouse/reportquartersearch','ManagerwarehouseController@reportquartersearch')->name('mwarehouse.reportquartersearch');

Route::get('manager_warehouse/reportplan','ManagerwarehouseController@reportplan')->name('mwarehouse.reportplan'); //รายงานแผนการจัดซ์้อวัสดุ

Route::get('manager_warehouse/reportrandom','ManagerwarehouseController@reportrandom')->name('mwarehouse.reportrandom'); //รายงานการสุ่มจำนวนคงเหลืองานพัสดุ
//=====ฟังชั่นเสริม
Route::get('manager_medical/vendorlist_addrequest/{idlistref}','ManagerwarehouseController@vendorlist_addrequest')->name('mmedical.vendorlist_addrequest'); //เพิ่มบริษัทจากคำขอ


//======================================ทรัพย์สิน===============================================
Route::get('manager_asset/assetinfomation/{id}','ManagerassetController@assetinfomation')->name('massete.assetinfomation')->withoutMiddleware('checklogin');;
Route::match(['get','post'],'manager_asset/assetinfo','ManagerassetController@assetinfo')->name('massete.assetinfo'); 
Route::post('manager_asset/assetinfosearch','ManagerassetController@assetinfosearch')->name('massete.assetinfosearch');//ทะเบียนครุภัณค้นหาทั่วไป

Route::get('manager_asset/dashboard','ManagerassetController@dashboard')->name('massete.dashboard');
Route::post('manager_asset/dashboardsearch','ManagerassetController@dashboardsearch')->name('massete.dashboardsearch');
Route::get('manager_asset/assetinfoexcel','ManagerassetController@assetinfoexcel');
Route::get('manager_asset/assetbarcodepdf/{id}','ManagerassetController@assetbarcodepdf')->name('massete.assetbarcodepdf');//ทะเบียนครุภั  barcode
Route::get('manager_asset/assetbarcode/{id}','ManagerassetController@assetbarcode')->name('massete.assetbarcode');//ทะเบียนครุภั  barcode
Route::get('manager_asset/assetqrcode/{id}','ManagerassetController@assetqrcode')->name('massete.assetqrcode');//ทะเบียนครุภั  QRcode
Route::get('manager_asset/assetinfo_switchsearch','ManagerassetController@switchsearchassetinfo')->name('massete.switchsearchassetinfo');
Route::get('manager_asset/detail','ManagerassetController@detail')->name('massete.detail');
Route::get('manager_asset/depreciate','ManagerassetController@depreciate')->name('massete.depreciate');
Route::get('manager_asset/assetinfo/edit/{id}','ManagerassetController@editassetinfo')->name('massete.editassetinfo');
Route::post('manager_asset/assetinfo/update','ManagerassetController@updateassetinfo')->name('massete.updateassetinfo');
Route::match(['post','get'],'manager_asset/assetinfoland','ManagerassetController@assetinfoland')->name('massete.assetinfoland');//ที่ดิน
Route::post('manager_asset/assetinfolandsearch','ManagerassetController@searchassetinfoland')->name('massete.searchassetinfoland'); // ค้นหาเดิม
Route::get('manager_asset/assetinfoland_add','ManagerassetController@createassetinfoland')->name('massete.createassetinfoland');
Route::post('manager_asset/assetinfoland/save','ManagerassetController@saveassetinfoland')->name('massete.saveassetinfoland');
Route::get('manager_asset/assetinfoland/edit/{id}','ManagerassetController@editassetinfoland')->name('massete.editassetinfoland');
Route::post('manager_asset/assetinfoland/update','ManagerassetController@updateassetinfoland')->name('massete.updateassetinfoland');
Route::match(['get','post'],'manager_asset/assetinfobuilding','ManagerassetController@assetinfobuilding')->name('massete.assetinfobuilding');//อาคาร
Route::post('manager_asset/assetinfobuildingsearch','ManagerassetController@searchassetinfobuilding')->name('massete.searchassetinfobuilding');
Route::get('manager_asset/assetinfobuilding_add','ManagerassetController@createassetinfobuilding')->name('massete.createassetinfobuilding');
Route::post('manager_asset/assetinfobuilding/save','ManagerassetController@saveassetinfobuilding')->name('massete.saveassetinfobuilding');
Route::get('manager_asset/assetinfobuilding/edit/{id}','ManagerassetController@editassetinfobuilding')->name('massete.editassetinfobuilding');
Route::post('manager_asset/assetinfobuilding/update','ManagerassetController@updateassetinfobuilding')->name('massete.updateassetinfobuilding');

Route::get('manager_asset/amountdayexp','ManagerassetController@amountdayexp')->name('massete.amountdayexp');

Route::get('manager_asset/durable_search','ManagerassetController@durable_search');
Route::get('manager_asset/buiding_search','ManagerassetController@buiding_search');
Route::get('manager_asset/durable_buiding_search','ManagerassetController@durable_buiding_search');

Route::get('manager_asset/setassetinfolocationlevel/{idlocation}','ManagerassetController@infolocationlevel')->name('massete.infolocationlevel');
Route::post('manager_asset/setassetinfolocationlevelsave','ManagerassetController@savelocationlevel')->name('massete.savelocationlevel');
Route::post('manager_asset/setassetinfolocationlevelupdate','ManagerassetController@updatelocationlevel')->name('massete.updatelocationlevel');
Route::get('manager_asset/setassetinfolocationlevel/destroy/{id}/{idlocation}','ManagerassetController@destroylocationlevel');
Route::get('manager_asset/setassetinfolocationlevelroom/{idlocation}/{idlocationlevel}','ManagerassetController@infolocationlevelroom')->name('massete.infolocationlevelroom');
Route::post('manager_asset/setassetinfolocationlevelroomsave','ManagerassetController@saveinfolocationlevelroom')->name('massete.saveinfolocationlevelroom');
Route::post('manager_asset/setassetinfolocationlevelroomupdate','ManagerassetController@updateinfolocationlevelroom')->name('massete.updateinfolocationlevelroom');
Route::get('manager_asset/setassetinfolocationlevelroom/destroy/{id}/{idlocation}/{idlocationlevel}','ManagerassetController@destroyinfolocationlevelroom');
Route::get('manager_asset/depreciatebuilding','ManagerassetController@depreciatebuilding')->name('massete.depreciatebuilding');
Route::post('manager_asset/searchassetinfocalculate','ManagerassetController@searchassetinfocalculate')->name('massete.searchassetinfocalculate'); // ค้นหาเดิม
Route::match(['get','post'],'manager_asset/assetinfocalculate','ManagerassetController@assetinfocalculate')->name('massete.assetinfocalculate');//คำนวณ
Route::get('manager_asset/assetinfocalculatetestcal','ManagerassetController@assetinfotestcal')->name('massete.assetinfotestcal');//ไว้ทดสอบสูตรการคำนวณ
Route::get('manager_asset/assetinfocalallbuildingcalall','ManagerassetController@assetinfocalallbuilding')->name('massete.assetinfocalallbuilding');//ไว้คำนวนค่าเสื่อมตึกทั้งหมด
Route::get('manager_asset/assetinfocalall','ManagerassetController@assetinfocalall')->name('massete.assetinfocalall');//ไว้คำนวนค่าเสื่อมทั้งหมด

//-------------------------------
Route::match(['get','post'],'manager_asset/assetinfodisburse','ManagerassetController@assetinfodisburse')->name('massete.assetinfodisburse');//การเบิกจ่าย
Route::post('manager_asset/assetinfodisbursesearch','ManagerassetController@searchdisburse')->name('massete.searchdisburse');//=====17.12.62 // ค้นหาเดิม
Route::get('manager_asset/assetinfodisburseedit/{id}','ManagerassetController@assetinfodisburseedit')->name('massete.assetinfodisburseedit');//---แก้ไขการเบิกจ่าย
Route::post('manager_asset/assetinfodisburseindexupdate','ManagerassetController@assetinfodisburseupdate')->name('massete.assetinfodisburseupdate');
Route::get('manager_asset/assetinfodisburseindexcancel/{id}','ManagerassetController@canceldisburseassetinfo')->name('massete.canceldisburseassetinfo');//---แจ้งยกเลิกการเบิกจ่าย
Route::post('manager_asset/assetinfodisburseindexcancelupdate','ManagerassetController@canceldisburseassetinfoupdate')->name('massete.canceldisburseassetinfoupdate');
Route::get('manager_asset/assetinfodisburseapproval/{id}','ManagerassetController@assetinfodisburseapproval')->name('massete.assetinfodisburseapproval');
Route::get('manager_asset/assetinfodisburseapproval/destroy/{id}/{idlist}','ManagerassetController@disburseapprovaldestroy');
Route::post('manager_asset/assetinfodisburseapproval/updateapp','ManagerassetController@updatedisburseapp')->name('massete.updatedisburseapp');
//-------------------------------
Route::match(['get','post'],'manager_asset/assetinfolend','ManagerassetController@assetinfolend')->name('massete.assetinfolend');//การยืมคืน
Route::post('manager_asset/assetinfolendsearch','ManagerassetController@searchinfolend')->name('massete.searchinfolend');//=====17.12.62 // ค้นหาเดิม
Route::get('manager_asset/assetinfolendindexedit/{id}','ManagerassetController@assetinfolendedit')->name('massete.assetinfolendedit');//---แก้ไขยืมคืน
Route::post('manager_asset/assetinfolendindexupdate','ManagerassetController@assetinfolendupdate')->name('massete.assetinfolendupdate');

//----รายละเอียดต่างๆของทรัพย์สิน

Route::get('manager_asset/infoasset/{id}','ManagerassetController@infoasset')->name('massete.infoasset');
Route::get('manager_asset/infoasset_excel/{id}','ManagerassetController@infoasset_excel')->name('massete.infoasset');

//====================================จัดการแจ้งซ่อมทั่วไป======================================================
Route::get('manager_repairnomal/dashboard','ManagerrepairnomalController@dashboard')->name('mrepairnomal.dashboard');
Route::get('manager_repairnomal/repairnomal_search','ManagerrepairnomalController@repairnomal_search');
Route::post('manager_repairnomal/dashboardsearch','ManagerrepairnomalController@dashboardsearch')->name('mrepairnomal.dashboardsearch');
Route::match(['get','post'],'manager_repairnomal/repairnomalinfo','ManagerrepairnomalController@repairnomalinfo')->name('mrepairnomal.repairnomalinfo');
Route::get('manager_repairnomal/repairnomalinfodetailrepairnomal','ManagerrepairnomalController@detailrepairnomal')->name('mrepairnomal.detailrepairnomal');

Route::get('manager_repairnomal/carcalendar/detail','ManagerrepairnomalController@deatailcalendar')->name('mrepairnomal.deatailcalendar');

Route::get('manager_repairnomal/repairnomaledit/edit/{id}','ManagerrepairnomalController@repairnomaledit')->name('mrepairnomal.repairnomaledit');
Route::post('manager_repairnomal/repairnomaleditupdate/update','ManagerrepairnomalController@updateinforepairnomal')->name('mrepairnomal.updateinforepairnomal');

Route::get('manager_repairnomal/repairnomalreceived/{id}','ManagerrepairnomalController@repairnomalreceived')->name('mrepairnomal.repairnomalreceived');
Route::post('manager_repairnomal/repairnomalreceivedupdate','ManagerrepairnomalController@updateinfonomalreceived')->name('mrepairnomal.updateinfonomalreceived');
Route::get('manager_repairnomal/repairnomalreceiveedit/edit/{id}','ManagerrepairnomalController@repairnomalreceiveedit')->name('mrepairnomal.repairnomalreceiveedit');
Route::post('manager_repairnomal/repairnomalreceiveeditupdate/update','ManagerrepairnomalController@updateinforepairnomalreceive')->name('mrepairnomal.updateinforepairnomalreceive');


Route::get('manager_repairnomal/repairnomalamong/{id}','ManagerrepairnomalController@repairnomalamong')->name('mrepairnomal.repairnomalamong');
Route::post('manager_repairnomal/repairnomalamongupdate','ManagerrepairnomalController@updateinfonomalamong')->name('mrepairnomal.updateinfonomalamong');
Route::get('manager_repairnomal/repairnomalamongedit/edit/{id}','ManagerrepairnomalController@repairnomalamongedit')->name('mrepairnomal.repairnomalamongedit');
Route::post('manager_repairnomal/repairnomalamongeditupdate/update','ManagerrepairnomalController@updateinforepairnomalamong')->name('mrepairnomal.updateinforepairnomalamong');

Route::get('manager_repairnomal/repairnomalamongcheck/','ManagerrepairnomalController@checknomalrepair')->name('mrepairnomal.checknomalrepair');

Route::get('manager_repairnomal/repairnomalsuccess/{id}','ManagerrepairnomalController@repairnomalsuccess')->name('mrepairnomal.repairnomalsuccess');
Route::post('manager_repairnomal/repairnomalsuccessupdate','ManagerrepairnomalController@updateinfonomalsuccess')->name('mrepairnomal.updateinfonomalsuccess');
Route::get('manager_repairnomal/repairnomalsuccessedit/edit/{id}','ManagerrepairnomalController@repairnomalsuccessedit')->name('mrepairnomal.repairnomalsuccessedit');
Route::post('manager_repairnomal/repairnomalsuccesseditupdate/update','ManagerrepairnomalController@updateinforepairnomalsuccess')->name('mrepairnomal.updateinforepairnomalsuccess');


Route::get('manager_repairnomal/repairnomalinfoasset','ManagerrepairnomalController@repairnomalinfoasset')->name('mrepairnomal.repairnomalinfoasset');
Route::get('manager_repairnomal/repairnomalinfoassetdetailrepairnomal','ManagerrepairnomalController@detailrepairnomalasset')->name('mrepairnomal.detailrepairnomalasset');
Route::get('manager_repairnomal/repairnomalinfoasset/repair/{idasset}','ManagerrepairnomalController@repairinfoasset')->name('mrepairnomal.repairinfoasset');

Route::post('manager_repairnomal/repairnomalinfoassetsavecheckrepairnomal','ManagerrepairnomalController@savecheckrepairnomal')->name('mcar.savecheckrepairnomal');
Route::post('manager_repairnomal/repairnomalinfoassetcheckrepairnomalall','ManagerrepairnomalController@checkrepairnomalall')->name('mcar.checkrepairnomalall');
Route::post('manager_repairnomal/repairnomalinfoassetsaveplanrepairnomal','ManagerrepairnomalController@saveplanrepairnomal')->name('mcar.saveplanrepairnomal');
Route::post('manager_repairnomal/repairnomalinfoassetsave_carelist','ManagerrepairnomalController@repairnomalinfoassetsave_carelist')->name('mcar.repairnomalinfoassetsave_carelist');
Route::get('manager_repairnomal/repairnomalinfoasset/repair_edit/{idasset}/{id}','ManagerrepairnomalController@repairinfoasset_edit')->name('mrepairnomal.repairinfoasset_edit');
Route::post('manager_repairnomal/repairnomalinfoassetupdate_carelist','ManagerrepairnomalController@repairnomalinfoassetupdate_carelist')->name('mcar.repairnomalinfoassetupdate_carelist');
Route::get('manager_repairnomal/repairnomalinfoassetdelete_carelist/{idasset}/{id}','ManagerrepairnomalController@repairnomalinfoassetdelete_carelist')->name('mcar.repairnomalinfoassetdelete_carelist');

Route::get('manager_repairnomal/repairnomalcancel/{id}','ManagerrepairnomalController@repairnomalinfocancel')->name('mrepairnomal.repairnomalinfocancel');
Route::post('manager_repairnomal/repairnomalcancelupdate','ManagerrepairnomalController@updaterepairnomalcancel')->name('mrepairnomal.updaterepairnomalcancel');
Route::get('manager_repairnomal/repairnomalinforechecksummoney','ManagerrepairnomalController@checksummoney')->name('mrepairnomal.checksummoney');


Route::get('manager_repairnomal/report_repairnomal','ManagerrepairnomalController@report_repairnomal')->name('mrepairnomal.report_repairnomal');


Route::get('manager_repairnomal/repairreinfosetting_typesystem', 'ManagerrepairnomalController@setting_typesystem')->name('mrepairnomal.setting_typesystem');
Route::get('manager_repairnomal/repairreinfosetting_typesystem/add', 'ManagerrepairnomalController@setting_typesystem_add')->name('mrepairnomal.setting_typesystem_add');
Route::post('manager_repairnomal/repairreinfosetting_typesystem/save', 'ManagerrepairnomalController@setting_typesystem_save')->name('mrepairnomal.setting_typesystem_save');
Route::get('manager_repairnomal/repairreinfosetting_typesystem/edit/{id}', 'ManagerrepairnomalController@setting_typesystem_edit')->name('mrepairnomal.setting_typesystem_edit');
Route::post('manager_repairnomal/repairreinfosetting_typesystem/update', 'ManagerrepairnomalController@setting_typesystem_update')->name('mrepairnomal.setting_typesystem_update');
Route::get('manager_repairnomal/repairreinfosetting_typesystem/delete/{id}', 'ManagerrepairnomalController@setting_typesystem_delete')->name('mrepairnomal.setting_typesystem_delete');

//====================================จัดการแจ้งซ่อมคอม======================================================
Route::get('manager_repaircom/dashboard','ManagerrepaircomController@dashboard')->name('mrepaircom.dashboard');
Route::get('manager_repaircom/repaircom_search','ManagerrepaircomController@repaircom_search');
Route::post('manager_repaircom/dashboardsearch','ManagerrepaircomController@dashboardsearch')->name('mrepaircom.dashboardsearch');

Route::match(['get','post'],'manager_repaircom/repaircominfo','ManagerrepaircomController@repaircominfo')->name('mrepaircom.repaircominfo');
Route::get('manager_repaircom/repaircominfodetailrepaircom','ManagerrepaircomController@detailrepaircom')->name('mrepaircom.detailrepaircom');

Route::get('manager_repaircom/repaircomedit/edit/{id}','ManagerrepaircomController@repaircomedit')->name('mrepaircom.repaircomedit');
Route::post('manager_repaircom/repaircomeditupdate/update','ManagerrepaircomController@updateinforepaircom')->name('mrepaircom.updateinforepaircom');


Route::get('manager_repaircom/carcalendar/detail','ManagerrepaircomController@deatailcalendar')->name('mrepaircom.deatailcalendar');

Route::get('manager_repaircom/repaircomreceived/{id}','ManagerrepaircomController@repaircomreceived')->name('mrepaircom.repaircomreceived'); //รับงาน
Route::post('manager_repaircom/repaircomreceivedupdate','ManagerrepaircomController@updateinfocomreceived')->name('mrepaircom.updateinfocomreceived');
Route::get('manager_repaircom/repaircomreceiveedit/edit/{id}','ManagerrepaircomController@repaircomreceiveedit')->name('mrepaircom.repaircomreceiveedit');
Route::post('manager_repaircom/repaircomreceiveeditupdate/update','ManagerrepaircomController@updateinforepaircomreceive')->name('mrepaircom.updateinforepaircomreceive');

Route::get('manager_repaircom/repaircomamong/{id}','ManagerrepaircomController@repaircomamong')->name('mrepaircom.repaircomamong'); //ดำเนินงาน
Route::post('manager_repaircom/repaircomamongupdate','ManagerrepaircomController@updateinfocomamong')->name('mrepaircom.updateinfocomamong');
Route::get('manager_repaircom/repaircomamongedit/edit/{id}','ManagerrepaircomController@repaircomamongedit')->name('mrepaircom.repaircomamongedit');
Route::post('manager_repaircom/repaircomamongeditupdate/update','ManagerrepaircomController@updateinforepaircomamong')->name('mrepaircom.updateinforepaircomamong');

Route::get('manager_repaircom/repaircomamongcheck/','ManagerrepaircomController@checkcomrepair')->name('mrepaircom.checkcomrepair');

Route::get('manager_repaircom/repaircomsuccess/{id}','ManagerrepaircomController@repaircomsuccess')->name('mrepaircom.repaircomsuccess'); //ซ่อมเสร็จ
Route::post('manager_repaircom/repaircomsuccessupdate','ManagerrepaircomController@updateinfocomsuccess')->name('mrepaircom.updateinfocomsuccess');
Route::get('manager_repaircom/repaircomsuccessedit/edit/{id}','ManagerrepaircomController@repaircomsuccessedit')->name('mrepaircom.repaircomsuccessedit');
Route::post('manager_repaircom/repaircomsuccesseditupdate/update','ManagerrepaircomController@updateinforepaircomsuccess')->name('mrepaircom.updateinforepaircomsuccess');

Route::get('manager_repaircom/repaircominfoasset','ManagerrepaircomController@repaircominfoasset')->name('mrepaircom.repairnomalinfoasset');
Route::get('manager_repaircom/repaircominfoassetdetailrepairnomal','ManagerrepaircomController@detailrepaircomasset')->name('mrepaircom.detailrepairnomalasset');
Route::get('manager_repaircom/repaircominfoasset/repair/{idasset}','ManagerrepaircomController@repairinfocomasset')->name('mrepaircom.repairinfocomasset');
Route::post('manager_repaircom/repaircominfoassetsave_carelist','ManagerrepaircomController@repaircominfoassetsave_carelist')->name('mrepaircom.repaircominfoassetsave_carelist');
Route::post('manager_repaircom/repaircominfoassetupdate_carelist','ManagerrepaircomController@repaircominfoassetupdate_carelist')->name('mrepaircom.repaircominfoassetupdate_carelist');
Route::get('manager_repaircom/repaircominfoassetdelete_carelist/{idasset}/{id}','ManagerrepaircomController@repaircominfoassetdelete_carelist')->name('mrepaircom.repaircominfoassetdelete_carelist');

Route::get('manager_repaircom/repaircomdelete_planrepair/{idasset}/{id}','ManagerrepaircomController@repaircomdelete_planrepair')->name('mrepaircom.repaircomdelete_planrepair');

Route::post('manager_repaircom/repaircominfoassetsavecheckrepairnomal','ManagerrepaircomController@savecheckrepaircom')->name('mrepaircom.savecheckrepaircom');
Route::post('manager_repaircom/repaircominfoassetcheckrepairnomalall','ManagerrepaircomController@checkrepaircomall')->name('mrepaircom.checkrepaircomall');
Route::post('manager_repaircom/repaircominfoassetsaveplanrepairnomal','ManagerrepaircomController@saveplanrepaircom')->name('mrepaircom.saveplanrepaircom');

Route::get('manager_repaircom/repaircomcancel/{id}','ManagerrepaircomController@repaircominfocancel')->name('mrepaircom.repaircominfocancel');
Route::post('manager_repaircom/repairnomalcancelupdate','ManagerrepaircomController@updaterepaircomcancel')->name('mrepaircom.updaterepaircomcancel');


Route::get('manager_repaircom/repaircominfosetting_typesystem', 'ManagerrepaircomController@setting_typesystem')->name('mrepaircom.setting_typesystem');
Route::get('manager_repaircom/repaircominfosetting_typesystem/add', 'ManagerrepaircomController@setting_typesystem_add')->name('mrepaircom.setting_typesystem_add');
Route::post('manager_repaircom/repaircominfosetting_typesystem/save', 'ManagerrepaircomController@setting_typesystem_save')->name('mrepaircom.setting_typesystem_save');
Route::get('manager_repaircom/repaircominfosetting_typesystem/edit/{id}', 'ManagerrepaircomController@setting_typesystem_edit')->name('mrepaircom.setting_typesystem_edit');
Route::post('manager_repaircom/repaircominfosetting_typesystem/update', 'ManagerrepaircomController@setting_typesystem_update')->name('mrepaircom.setting_typesystem_update');
Route::get('manager_repaircom/repaircominfosetting_typesystem/delete/{id}', 'ManagerrepaircomController@setting_typesystem_delete')->name('mrepaircom.setting_typesystem_delete');




//====================================จัดการแจ้งซ่อมเครื่องมือแพทย์======================================================

Route::get('manager_repairmedical/dashboard','ManagerrepairmedicalController@dashboard')->name('mrepairmedical.dashboard');
Route::get('manager_repairmedical/repairmedical_search','ManagerrepairmedicalController@repairmedical_search');
Route::post('manager_repairmedical/dashboardsearch','ManagerrepairmedicalController@dashboardsearch')->name('mrepairmedical.dashboardsearch');

Route::match(['get','post'],'manager_repairmedical/repairmedicalinfo','ManagerrepairmedicalController@repairmedicalinfo')->name('mrepairmedical.repairmedicalinfo');
Route::get('manager_repairmedical/repairmedicalinfodetailrepairmedical','ManagerrepairmedicalController@detailrepairmedical')->name('mrepairmedical.detailrepairmedical');

Route::get('manager_repairmedical/carcalendar/detail','ManagerrepairmedicalController@deatailcalendar')->name('mrepairmedical.deatailcalendar');

Route::get('manager_repairmedical/repairmedicaledit/edit/{id}','ManagerrepairmedicalController@repairmedicaledit')->name('mrepairmedical.repairmedicaledit');
Route::post('manager_repairmedical/repairmedicaleditupdate/update','ManagerrepairmedicalController@updateinforepairmedical')->name('mrepairmedical.updateinforepairmedical');

Route::get('manager_repairmedical/repairmedicalreceived/{id}','ManagerrepairmedicalController@repairmedicalreceived')->name('mrepairmedical.repairmedicalreceived');
Route::post('manager_repairmedical/repairmedicalreceivedupdate','ManagerrepairmedicalController@updateinfomedicalreceived')->name('mrepairmedical.updateinfomedicalreceived');
Route::get('manager_repairmedical/repairmedicalreceiveedit/edit/{id}','ManagerrepairmedicalController@repairmedicalreceiveedit')->name('mrepairmedical.repairmedicalreceiveedit');
Route::post('manager_repairmedical/repairmedicalreceiveeditupdate/update','ManagerrepairmedicalController@updateinforepairmedicalreceive')->name('mrepairmedical.updateinforepairmedicalreceive');


Route::get('manager_repairmedical/repairmedicalamong/{id}','ManagerrepairmedicalController@repairmedicalamong')->name('mrepairmedical.repairmedicalamong');
Route::post('manager_repairmedical/repairmedicalamongupdate','ManagerrepairmedicalController@updateinfomedicalamong')->name('mrepairmedical.updateinfomedicalamong');
Route::get('manager_repairmedical/repairmedicalamongedit/edit/{id}','ManagerrepairmedicalController@repairmedicalamongedit')->name('mrepairmedical.repairmedicalamongedit');
Route::post('manager_repairmedical/repairmedicalamongeditupdate/update','ManagerrepairmedicalController@updateinforepairmedicalamong')->name('mrepairmedical.updateinforepairmedicalamong');

Route::get('manager_repairmedical/repairmedicalamongcheck/','ManagerrepairmedicalController@checkmedicalrepair')->name('mrepairmedical.checkmedicalrepair');

Route::get('manager_repairmedical/repairmedicalsuccess/{id}','ManagerrepairmedicalController@repairmedicalsuccess')->name('mrepairmedical.repairmedicalsuccess');
Route::post('manager_repairmedical/repairmedicalsuccessupdate','ManagerrepairmedicalController@updateinfomedicalsuccess')->name('mrepairmedical.updateinfomedicalsuccess');
Route::get('manager_repairmedical/repairmedicalsuccessedit/edit/{id}','ManagerrepairmedicalController@repairmedicalsuccessedit')->name('mrepairmedical.repairmedicalsuccessedit');
Route::post('manager_repairmedical/repairmedicalsuccesseditupdate/update','ManagerrepairmedicalController@updateinforepairmedicalsuccess')->name('mrepairmedical.updateinforepairmedicalsuccess');

Route::get('manager_repairmedical/repairmedicalinfoasset','ManagerrepairmedicalController@repairmedicalinfoasset')->name('mrepairmedical.repairmedicalinfoasset');
Route::get('manager_repairmedical/repairmedicalinfoassetdetailrepairmedical','ManagerrepairmedicalController@detailrepairmedicalasset')->name('mrepairmedical.detailrepairmedicalasset');
Route::get('manager_repairmedical/repairmedicalinfoasset/repair/{idasset}','ManagerrepairmedicalController@repairinfoasset')->name('mrepairmedical.repairinfoasset');





Route::post('manager_repairmedical/repairmedicalassetsave_carelist','ManagerrepairmedicalController@repairmedicalassetsave_carelist')->name('mrepairmedical.repairmedicalassetsave_carelist');
Route::post('manager_repairmedical/repairmedicalassetupdate_carelist','ManagerrepairmedicalController@repairmedicalassetupdate_carelist')->name('mrepairmedical.repairmedicalassetupdate_carelist');
Route::get('manager_repairmedical/repairmedicalinfoasset_carelist_destroy/{idass}/{id}','ManagerrepairmedicalController@repairmedicalinfoasset_carelist_destroy')->name('mrepairmedical.repairmedicalinfoasset_carelist_destroy');


Route::post('manager_repairmedical/repairmedicalinfoasset_calibration_save','ManagerrepairmedicalController@repairmedicalinfoasset_calibration_save')->name('mrepairmedical.repairmedicalinfoasset_calibration_save');
Route::post('manager_repairmedical/repairmedicalinfoasset_calibration_update','ManagerrepairmedicalController@repairmedicalinfoasset_calibration_update')->name('mrepairmedical.repairmedicalinfoasset_calibration_update');
Route::get('manager_repairmedical/repairmedicalinfoasset_calibration_destroy/{idass}/{id}','ManagerrepairmedicalController@repairmedicalinfoasset_calibration_destroy')->name('mrepairmedical.repairmedicalinfoasset_calibration_destroy');

Route::post('manager_repairmedical/repairmedicalinfoasset_calibration_true_save','ManagerrepairmedicalController@repairmedicalinfoasset_calibration_true_save')->name('mrepairmedical.repairmedicalinfoasset_calibration_true_save');
Route::post('manager_repairmedical/repairmedicalinfoasset_calibration_true_update','ManagerrepairmedicalController@repairmedicalinfoasset_calibration_true_update')->name('mrepairmedical.repairmedicalinfoasset_calibration_true_update');
Route::get('manager_repairmedical/repairmedicalinfoasset_calibration_true_destroy/{idass}/{id}','ManagerrepairmedicalController@repairmedicalinfoasset_calibration_true_destroy')->name('mrepairmedical.repairmedicalinfoasset_calibration_true_destroy');

Route::post('manager_repairmedical/repairmedicalinfoasset_calibration_list_save','ManagerrepairmedicalController@repairmedicalinfoasset_calibration_list_save')->name('mrepairmedical.repairmedicalinfoasset_calibration_list_save');
Route::post('manager_repairmedical/repairmedicalinfoasset_calibration_list_update','ManagerrepairmedicalController@repairmedicalinfoasset_calibration_list_update')->name('mrepairmedical.repairmedicalinfoasset_calibration_list_update');
Route::get('manager_repairmedical/repairmedicalinfoasset_calibration_list_destroy/{idass}/{id}','ManagerrepairmedicalController@repairmedicalinfoasset_calibration_list_destroy')->name('mrepairmedical.repairmedicalinfoasset_calibration_list_destroy');




Route::post('manager_repairmedical/repairmedicalinfoassetsavecheckrepairmedical','ManagerrepairmedicalController@savecheckrepairmedical')->name('mrepairmedical.savecheckrepairmedical');
Route::post('manager_repairmedical/repairmedicalinfoassetcheckrepairmedicalall','ManagerrepairmedicalController@checkrepairmedicalall')->name('mrepairmedical.checkrepairmedicalall');
Route::post('manager_repairmedical/repairmedicalinfoassetsaveplanrepairmedical','ManagerrepairmedicalController@saveplanrepairmedical')->name('mrepairmedical.saveplanrepairmedical');

Route::get('manager_repairmedical/repairmedicalinfoasset_planrepair_destroy/{idasset}/{id}','ManagerrepairmedicalController@repairmedicalinfoasset_planrepair_destroy')->name('mrepairmedical.repairmedicalinfoasset_planrepair_destroy');


Route::get('manager_repairmedical/repairmedicalcancel/{id}','ManagerrepairmedicalController@repairmedicalinfocancel')->name('mrepairmedical.repairmedicalinfocancel');
Route::post('manager_repairmedical/repairmedicalcancelupdate','ManagerrepairmedicalController@updaterepairmedicalcancel')->name('mrepairmedical.updaterepairmedicalcancel');
Route::get('manager_repairmedical/repairmedicalinforechecksummoney','ManagerrepairmedicalController@checksummoney')->name('mrepairmedical.checksummoney');


Route::get('manager_repairmedical/repairmedinfosetting_typesystem', 'ManagerrepairmedicalController@setting_typesystem')->name('mrepairmedical.setting_typesystem');
Route::get('manager_repairmedical/repairmedinfosetting_typesystem/add', 'ManagerrepairmedicalController@setting_typesystem_add')->name('mrepairmedical.setting_typesystem_add');
Route::post('manager_repairmedical/repairmedinfosetting_typesystem/save', 'ManagerrepairmedicalController@setting_typesystem_save')->name('mrepairmedical.setting_typesystem_save');
Route::get('manager_repairmedical/repairmedinfosetting_typesystem/edit/{id}', 'ManagerrepairmedicalController@setting_typesystem_edit')->name('mrepairmedical.setting_typesystem_edit');
Route::post('manager_repairmedical/repairmedinfosetting_typesystem/update', 'ManagerrepairmedicalController@setting_typesystem_update')->name('mrepairmedical.setting_typesystem_update');
Route::get('manager_repairmedical/repairmedinfosetting_typesystem/delete/{id}', 'ManagerrepairmedicalController@setting_typesystem_delete')->name('mrepairmedical.setting_typesystem_delete');


//=================================================== dashboard  8.10.2562=======================================
Route::get('manager_checkin/dashboard_checkin','ManagercheckinController@dashboard')->name('mcheckin.dashboard');

Route::match(['post','get'],'manager_checkin/inforpersoncheck_new','ManagercheckinController@inforpersoncheck_new')->name('mcheckin.inforpersoncheck_new');
Route::get('manager_checkin/excel_checkin_new','ManagercheckinController@excel_checkin_new')->name('mcheckin.excel_checkin_new');

Route::get('manager_checkin/inforpersoncheck_new_edit/{idref}','ManagercheckinController@inforpersoncheck_new_edit')->name('mcheckin.inforpersoncheck_new_edit');
Route::post('manager_checkin/inforpersoncheck_new_update','ManagercheckinController@inforpersoncheck_new_update')->name('mcheckin.inforpersoncheck_new_update');
Route::get('manager_checkin/inforpersoncheck_new_destroy/{idref}','ManagercheckinController@inforpersoncheck_new_destroy')->name('mcheckin.inforpersoncheck_new_destroy');

Route::get('manager_leave/dashboard_leave','ManagerleaveController@dashboard')->name('mleave.dashboard');
Route::get('manager_leave/dashboard_leave_type/{type_id}/{year}','ManagerleaveController@dashboard_leave_type');
Route::post('manager_leave/dashboard_leavedashboardsearch','ManagerleaveController@dashboardsearch')->name('mleave.dashboardsearch');

Route::get('manager_meet/dashboard_meet','ManagermeetController@dashboard')->name('mmeet.dashboard');
Route::post('manager_meet/dashboard_meetdashboardsearch','ManagermeetController@dashboardsearch')->name('mmeet.dashboardsearch');


//=========================================9.10.2562==========================ข้อมูลการลา=====//
Route::match(['get','post'],'manager_leave/personleaveinfocheckver','ManagerleaveController@infover')->name('leave.inforvercheck');
Route::post('manager_leave/personleaveinfocheckversearch','ManagerleaveController@searchver')->name('leave.searchvercheck'); //ค้นหาเดิม
Route::get('manager_leave/personleaveinfocheckvercheck/{id}','ManagerleaveController@ver')->name('leave.vercheck');
Route::post('manager_leave/personleaveinfocheckvercheck/updatever','ManagerleaveController@updatever')->name('leave.updatevercheck');

Route::get('manager_leave/personleaveinfocheckcancel/{id}','ManagerleaveController@cancel')->name('leave.cancel');
Route::post('manager_leave/personleaveinfocheckcancel/updatecancel','ManagerleaveController@updatecancel')->name('leave.updatecancel');

Route::get('manager_leave/personleaveinfocheckedit/{id}','ManagerleaveController@edit')->name('leave.edit');
Route::post('manager_leave/personleaveinfocheckedit/updateedit','ManagerleaveController@updateedit')->name('leave.updateedit');

//----นับวันหยุด
Route::match(['get','post'],'manager_leave/checkholiday','ManagerleaveController@checkholiday')->name('mleave.checkholiday');
Route::get('manager_leave/checkholiday/switchleave','ManagerleaveController@switchleave')->name('mleave.idperson');
Route::post('manager_leave/checkholiday/search','ManagerleaveController@checkholidaysearch')->name('mleave.checkholidaysearch'); //ค้นหาเดิม
//----คำนวณจำนวนวันลา
Route::match(['get','post'],'manager_leave/countleave','ManagerleaveController@countleave')->name('mleave.countleave');
Route::get('manager_leave/countleavesearch','ManagerleaveController@countleavesearch')->name('mleave.countleavesearch'); //ค้นหาเดิม
Route::post('manager_leave/countleavesearch','ManagerleaveController@countleavesearch')->name('mleave.countleavesearch'); //ค้นหาเดิม
Route::get('manager_leave/excelleave/{datebegin}/{dateend}/{year_id}/{search}','ManagerleaveController@excelleave');
//----คำนวณวันลา
Route::match(['get','post'],'manager_leave/leaveday','ManagerleaveController@leaveday')->name('mleave.leaveday');
Route::post('manager_leave/leavedaysearch','ManagerleaveController@leavedaysearch')->name('mleave.leavedaysearch'); //ค้นหาเดิม

Route::get('manager_leave/excelcheck/{datebegin}/{dateend}/{year_id}/{search}','ManagerleaveController@excelcheck');
Route::get('manager_leave/excelleaveday','ManagerleaveController@excelleaveday');



//---เอกสาร
Route::get('manager_leave/certificate/{id}','ManagerleaveController@certificate')->name('mleave.certificate'); //แนบใบรับรองแพทย์
Route::post('manager_leave/certificate_save','ManagerleaveController@certificate_save')->name('mleave.certificate_save');


//=========================================9.10.2562===============================//
Route::get('manager_meet/managemeet','ManagermeetController@infomeet')->name('meet.informeet');
Route::get('manager_meet/meetcalendar','ManagermeetController@meetcalendar')->name('meet.informeetcalendar');
Route::match(['get','post'],'manager_meet/managemeetcheck','ManagermeetController@infomeetcheck')->name('meeting.informeetcheck');
Route::match(['get','post'],'manager_meet/managemeet_pdfmeet/{id}','ManagermeetController@managemeet_pdfmeet')->name('meeting.managemeet_pdfmeet');//ใบขอห้องประชุม
Route::get('manager_meet/managemeetchecklast/{id}','ManagermeetController@infomeetchecklast')->name('meeting.informeetchecklast');

Route::get('manager_meet/managemeetcheck/{id}','ManagermeetController@informmeet')->name('meeting.informmeet');
Route::get('manager_meet/managemeetcheckcancel/{id}','ManagermeetController@cancel')->name('meeting.cancel');
Route::post('manager_meet/managemeetcheckcancel/updatecancel','ManagermeetController@updatecancel')->name('meeting.updatecancel');
Route::get('manager_meet/managemeetcheck/{id}','ManagermeetController@meet')->name('meet.meetcheck');
Route::post('manager_meet/managemeetcheck/updatemeet','ManagermeetController@updatemeet')->name('meet.updatemeetcheck');
Route::post('manager_meet/managemeetcheck/infomeetsearch','ManagermeetController@infomeetsearch')->name('msafe.infomeetsearch'); //ค้นหาเดิม

Route::get('manager_meet/managemeet_infomeet/{iduser}','ManagermeetController@managemeet_infomeet')->name('meeting.managemeet_infomeet');
Route::get('manager_meet/managemeet_addmeet/{idroom}/{iduser}','ManagermeetController@managemeet_addmeet')->name('meeting.managemeet_addmeet');
Route::post('manager_meet/managemeet_save','ManagermeetController@managemeet_save')->name('meeting.managemeet_save');
Route::get('manager_meet/managemeet_editmeet/{idroom}','ManagermeetController@managemeet_editmeet')->name('meeting.managemeet_editmeet');
Route::post('manager_meet/managemeet_update','ManagermeetController@managemeet_update')->name('meeting.managemeet_update');





Route::get('manager_meet/meetcalendar/detail','ManagermeetController@deatailcalendar')->name('meet.deatailcalendar');
Route::post('manager_meet/managemeetsearch','ManagermeetController@searchmeet')->name('meet.searchmeetcheck');
Route::get('manager_meet/cancelmanagemeet/{id}','ManagermeetController@cancelmeet')->name('cancelmeet.informeetcheck');
Route::post('manager_meet/managemeetcheck/updatecancel','ManagermeetController@updatecancelver')->name('meet.updatecancelmeetcheck');

//---------------------ค้นหา----------------------//
Route::post('admin_person/setupinfoworkgroupsearch','SetuppersonController@searchinfodepartment')->name('setup.searchinfodepartment');


//==============================การลงเวลาของผู้ใช้งาน========================================
Route::get('manager_personcheck/inforperson','ManagerpersoncheckController@inforpersoncheck')->name('mpersoncheck.inforpersoncheck');

Route::get('manager_personcheck/inforpersoncheck_new','ManagerpersoncheckController@inforpersoncheck_new')->name('mpersoncheck.inforpersoncheck_new');
Route::get('manager_personcheck/excel_checkin_new','ManagerpersoncheckController@excel_checkin_new')->name('mpersoncheck.excel_checkin_new');

Route::get('manager_personcheck/dashboard','ManagerpersoncheckController@dashboard')->name('mpersoncheck.dashboard');
Route::get('manager_personcheck/dashboard_search','ManagerpersoncheckController@dashboard_search')->name('mpersoncheck.dashboard_search');
Route::get('manager_personcheck/search','ManagerpersoncheckController@search')->name('mpersoncheck.search');

Route::get('manager_personcheck/time/{iduser}','ManagerpersoncheckController@infocheck')->name('mpersoncheck.infocheck');



//-----------------------------------------------------------------------------------------
//==================================commercial=================================================
//==================================ตั้งค่า
//======================================คลังสินค้า==============================================

Route::get('admin_warehouse/setupwarehousedepsubsup','SetupwarehouseController@depsubsup')->name('setupwarehouse.depsubsup'); //คลังย่อย

Route::get('admin_warehouse/setupwarehousestatuscheck','SetupwarehouseController@statuscheck')->name('setupwarehouse.statuscheck'); //สถานะตรวจรับ
Route::get('admin_warehouse/setupwarehousestatuscheck_add','SetupwarehouseController@statuscheck_add')->name('setupwarehouse.statuscheck_add');
Route::post('admin_warehouse/setupwarehousestatuscheck_save','SetupwarehouseController@statuscheck_save')->name('setupwarehouse.statuscheck_save');
Route::get('admin_warehouse/setupwarehousestatuscheck_edit/{id}','SetupwarehouseController@statuscheck_edit')->name('setupwarehouse.statuscheck_edit');
Route::post('admin_warehouse/setupwarehousestatuscheck_update','SetupwarehouseController@statuscheck_update')->name('setupwarehouse.statuscheck_update');
Route::post('admin_warehouse/setupwarehousestatuscheck_cancel','SetupwarehouseController@statuscheck_cancel')->name('setupwarehouse.statuscheck_cancel');
Route::get('admin_warehouse/statuscheck_destroy/{id}','SetupwarehouseController@statuscheck_destroy')->name('setupwarehouse.statuscheck_destroy');

Route::get('admin_warehouse/setupwarehousetypereceive','SetupwarehouseController@typereceive')->name('setupwarehouse.typereceive'); //ประเภทการรับ
Route::get('admin_warehouse/setupwarehousetypereceive_add','SetupwarehouseController@typereceive_add')->name('setupwarehouse.typereceive_add');
Route::post('admin_warehouse/setupwarehousetypereceive_save','SetupwarehouseController@typereceive_save')->name('setupwarehouse.typereceive_save');
Route::get('admin_warehouse/setupwarehousetypereceive_edit/{id}','SetupwarehouseController@typereceive_edit')->name('setupwarehouse.typereceive_edit');
Route::post('admin_warehouse/setupwarehousetypereceive_update','SetupwarehouseController@typereceive_update')->name('setupwarehouse.typereceive_update');
Route::post('admin_warehouse/setupwarehousetypereceive_cancel','SetupwarehouseController@typereceive_cancel')->name('setupwarehouse.typereceive_cancel');
Route::get('admin_warehouse/typereceive_destroy/{id}','SetupwarehouseController@typereceive_destroy')->name('setupwarehouse.typereceive_destroy');

Route::get('admin_warehouse/setupwarehousetypecycle','SetupwarehouseController@typecycle')->name('setupwarehouse.typecycle'); //รอบการเบิก
Route::get('admin_warehouse/setupwarehousetypecycle_add','SetupwarehouseController@typecycle_add')->name('setupwarehouse.typecycle_add');
Route::post('admin_warehouse/setupwarehousetypecycle_save','SetupwarehouseController@typecycle_save')->name('setupwarehouse.typecycle_save');
Route::get('admin_warehouse/setupwarehousetypecycle_edit/{id}','SetupwarehouseController@typecycle_edit')->name('setupwarehouse.typecycle_edit');
Route::post('admin_warehouse/setupwarehousetypecycle_update','SetupwarehouseController@typecycle_update')->name('setupwarehouse.typecycle_update');
Route::post('admin_warehouse/setupwarehousetypecycle_cancel','SetupwarehouseController@typecycle_cancel')->name('setupwarehouse.typecycle_cancel');
Route::get('admin_warehouse/typecycle_destroy/{id}','SetupwarehouseController@typecycle_destroy')->name('setupwarehouse.typecycle_destroy');


Route::get('admin_warehouse/setupwarehousestatusdisburse','SetupwarehouseController@statusdisburse')->name('setupwarehouse.statusdisburse'); //สถานะการเบิก
Route::get('admin_warehouse/setupwarehousestatusdisburse_add','SetupwarehouseController@statusdisburse_add')->name('setupwarehouse.statusdisburse_add');
Route::post('admin_warehouse/setupwarehousestatusdisburse_save','SetupwarehouseController@statusdisburse_save')->name('setupwarehouse.statusdisburse_save');
Route::get('admin_warehouse/setupwarehousestatusdisburse_edit/{id}','SetupwarehouseController@statusdisburse_edit')->name('setupwarehouse.statusdisburse_edit');
Route::post('admin_warehouse/setupwarehousestatusdisburse_update','SetupwarehouseController@statusdisburse_update')->name('setupwarehouse.statusdisburse_update');
Route::post('admin_warehouse/setupwarehousestatusdisburse_cancel','SetupwarehouseController@statusdisburse_cancel')->name('setupwarehouse.statusdisburse_cancel');
Route::get('admin_warehouse/statusdisburse_destroy/{id}','SetupwarehouseController@statusdisburse_destroy')->name('setupwarehouse.statusdisburse_destroy');


Route::get('admin_warehouse/setupwarehousetypedisburse','SetupwarehouseController@typedisburse')->name('setupwarehouse.typedisburse'); //ประเภท
Route::get('admin_warehouse/setupwarehousetypedisburse_add','SetupwarehouseController@typedisburse_add')->name('setupwarehouse.typedisburse_add');
Route::post('admin_warehouse/setupwarehousetypedisburse_save','SetupwarehouseController@typedisburse_save')->name('setupwarehouse.typedisburse_save');
Route::get('admin_warehouse/setupwarehousetypedisburse_edit/{id}','SetupwarehouseController@typedisburse_edit')->name('setupwarehouse.typedisburse_edit');
Route::post('admin_warehouse/setupwarehousetypedisburse_update','SetupwarehouseController@typedisburse_update')->name('setupwarehouse.typedisburse_update');
Route::post('admin_warehouse/setupwarehousetypedisburse_cancel','SetupwarehouseController@typedisburse_cancel')->name('setupwarehouse.typedisburse_cancel');
Route::get('admin_warehouse/typedisburse_destroy/{id}','SetupwarehouseController@typedisburse_destroy')->name('setupwarehouse.typedisburse_destroy');


//======================================เงินเดือนค่าตอบแทน==============================================
Route::get('admin_compensation/setupcompensationacc','SetupcompensationController@setupcompensationacc')->name('setupcompensation.setupcompensationacc'); //บัญชีเงินเดือน

Route::get('admin_compensation/setupcompensationlist','SetupcompensationController@setupcompensationlist')->name('setupcompensation.setupcompensationlist'); //รายการรับจ่าย
Route::get('admin_compensation/setupcompensationlistreceipt','SetupcompensationController@setupcompensationlistreceipt')->name('setupcompensation.setupcompensationlistreceipt'); //รายการรับ
Route::get('admin_compensation/setupcompensationlistpay','SetupcompensationController@setupcompensationlistpay')->name('setupcompensation.setupcompensationlist'); //รายการจ่าย

//===================================ผู้ใช้
//======================================เงินเดือนค่าตอบแทน==============================================
Route::match(['get','post'],'person_compensation/dashboard/{iduser}','CompensationController@dashboard')->name('compensation.dashboard');

Route::get('person_compensation/cominfosalary/{iduser}','CompensationController@cominfosalary')->name('compensation.cominfosalary');
Route::post('person_compensation/cominfosalary_search/{iduser}','CompensationController@cominfosalary_search')->name('compensation.cominfosalary_search');

Route::get('person_compensation/certificate/{iduser}','CompensationController@certificate')->name('compensation.certificate');
Route::post('person_compensation/searchcertificate','CompensationController@searchcertificate')->name('compensation.searchcertificate');
Route::get('person_compensation/certificate_add/{iduser}','CompensationController@certificate_add')->name('compensation.certificate_add');//===ฟร์อมขอใบรับรอง
Route::post('person_compensation/infocertificate_save','CompensationController@infocertificate_save')->name('compensation.infocertificate_save');
Route::get('person_compensation/certificate_edit/{id}/{iduser}','CompensationController@certificate_edit')->name('compensation.certificate_edit');//===แก้ไขฟร์อมขอใบรับรอง
Route::post('person_compensation/certificate_update','CompensationController@certificate_update')->name('compensation.certificate_update');
Route::post('person_compensation/certificate_cancel','CompensationController@certificate_cancel')->name('compensation.certificate_cancel');//===Cancel


Route::get('person_compensation/salaryslip/{iduser}','CompensationController@salaryslip')->name('compensation.salaryslip');
Route::post('person_compensation/searchsalaryslip','CompensationController@searchsalaryslip')->name('compensation.searchsalaryslip');
Route::get('person_compensation/salaryslip_add/{iduser}','CompensationController@salaryslip_add')->name('compensation.salaryslip_add');//===ฟร์อมขอสลิป
Route::post('person_compensation/infosalaryslip_save','CompensationController@infosalaryslip_save')->name('compensation.infosalaryslip_save');
Route::get('person_compensation/salaryslip_edit/{id}/{iduser}','CompensationController@salaryslip_edit')->name('compensation.salaryslip_edit');//===แก้ไขฟร์อมขอสลิป
Route::post('person_compensation/salaryslip_update','CompensationController@salaryslip_update')->name('compensation.salaryslip_update');
Route::post('person_compensation/salaryslip_cancel','CompensationController@salaryslip_cancel')->name('compensation.salaryslip_cancel');//===Cancel


Route::get('person_compensation/borrow/{iduser}','CompensationController@borrow')->name('compensation.borrow');
Route::post('person_compensation/searchborrow','CompensationController@searchborrow')->name('compensation.searchborrow');
Route::get('person_compensation/borrow_add/{iduser}','CompensationController@borrow_add')->name('compensation.borrow_add');//===ฟร์อมยืมคืน
Route::post('person_compensation/infoborrow_save','CompensationController@infoborrow_save')->name('compensation.infoborrow_save');
Route::get('person_compensation/borrow_edit/{id}/{iduser}','CompensationController@borrow_edit')->name('compensation.borrow_edit');//===แก้ไขฟร์อมยืมคืน
Route::post('person_compensation/borrow_update','CompensationController@borrow_update')->name('compensation.borrow_update');
Route::post('person_compensation/borrow_cancel','CompensationController@borrow_cancel')->name('compensation.borrow_cancel');//===Cancel



Route::get('person_compensation/borrow_send/{id}/{iduser}','CompensationController@borrow_send')->name('compensation.borrow_send');//===ยืนยันการคืนเงิน

Route::get('person_compensation/borrowapp/{iduser}','CompensationController@borrowapp')->name('compensation.borrowapp');//===ยืมคืนเห็นชอบ

Route::get('person_compensation/borrowlastapp/{iduser}','CompensationController@borrowlastapp')->name('compensation.borrowlastapp');//===ยืมคืนอนุมัติ


Route::get('person_compensation/salarydetail','CompensationController@salarydetail')->name('compensation.salarydetail');


Route::get('person_compensation/selectbooknum','CompensationController@selectbooknum_check')->name('compensation.selectbooknum_check');
//======================================คลังสินค้า==============================================
Route::get('general_warehouse/dashboard/{iduser}','WarehouseController@dashboard')->name('warehouse.dashboard');
Route::post('general_warehouse/dashboardsearch/{iduser}','WarehouseController@dashboardsearch')->name('warehouse.dashboardsearch');


Route::get('general_warehouse/infostockcard/{iduser}','WarehouseController@infostockcard')->name('warehouse.infostockcard');
Route::post('general_warehouse/infostockcardsearch/{iduser}','WarehouseController@infostockcardsearch')->name('warehouse.infostockcardsearch');

Route::get('general_warehouse/infostockcard_sub/{id}/{iduser}','WarehouseController@infostockcardsub')->name('warehouse.infostockcardsub');
Route::post('general_warehouse/infostockcardsubsearch_sub/{id}/{iduser}','WarehouseController@infostockcardsubsearch')->name('warehouse.infostockcardsubsearch');
//-------ขอเบิกวัสดุ
Route::get('general_warehouse/detailappall','WarehouseController@detailappall')->name('warehouse.detailappall');


Route::get('general_warehouse/infowithdrawindex/{iduser}','WarehouseController@infowithdrawindex')->name('warehouse.infowithdrawindex');
Route::post('general_warehouse/infowithdrawindexsearch/{iduser}','WarehouseController@infowithdrawindexsearch')->name('warehouse.infowithdrawindexsearch');

Route::get('general_warehouse/infowithdrawindex_edit/{id}/{iduser}','WarehouseController@infowithdrawindex_edit')->name('warehouse.infowithdrawindex_edit');
Route::post('general_warehouse/infowithdrawindex_update','WarehouseController@infowithdrawindex_update')->name('warehouse.infowithdrawindex_update');
Route::get('general_warehouse/infowithdrawindex_cancel/{id}/{iduser}','WarehouseController@infowithdrawindex_cancel')->name('warehouse.infowithdrawindex_cancel');
Route::post('general_warehouse/infowithdrawindex_updatecancel','WarehouseController@infowithdrawindex_updatecancel')->name('warehouse.infowithdrawindex_updatecancel');




Route::get('general_warehouse/infowithdrawindex_billpaypdf/{id}/{iduser}','WarehouseController@infowithdrawindex_billpaypdf')->name('warehouse.infowithdrawindex_billpaypdf'); // ใบเบิกวัสดุ
Route::get('general_warehouse/infowithdrawindex_stockcardpdf/{id}/{iduser}','WarehouseController@infowithdrawindex_stockcardpdf')->name('warehouse.infowithdrawindex_stockcardpdf'); // สต็อคการ์ด


//------จ่ายวัสดุ
Route::get('general_warehouse/infopayindex/{iduser}','WarehouseController@infopayindex')->name('warehouse.infopayindex');
Route::post('general_warehouse/infopayindexsearch/{iduser}','WarehouseController@infopayindexsearch')->name('warehouse.infopayindexsearch');

Route::get('general_warehouse/infopayindexadd/{iduser}','WarehouseController@infopayindexadd')->name('warehouse.infopayindexadd');
Route::post('general_warehouse/saveinfopay','WarehouseController@saveinfopay')->name('warehouse.saveinfopay');
Route::get('general_warehouse/infopayindexadd_all/{iduser}/{id_all}','WarehouseController@infopayindexall')->name('warehouse.infopayindexall');

Route::get('general_warehouse/reportinfopay/{iduser}','WarehouseController@reportinfopay')->name('warehouse.reportinfopay');//รายงานสรุปยอดคงคลัง
Route::post('general_warehouse/reportinfopaysearch/{iduser}','WarehouseController@reportinfopaysearch')->name('warehouse.reportinfopaysearch');
Route::get('general_warehouse/reportinfopayexcel/{yearbudget}/{displaydate_bigen}/{displaydate_end}/{iduser}','WarehouseController@reportinfopayexcel')->name('warehouse.reportinfopayexcel');
//------จ่ายวัสดุรายคน
Route::get('general_warehouse/infopersonuse/{iduser}','WarehouseController@infopersonuse')->name('warehouse.infopersonuse');
Route::get('general_warehouse/infopersonuse_excel/{iduser}','WarehouseController@infopersonuse_excel')->name('warehouse.infopersonuse_excel');

Route::get('general_warehouse/detailsup','WarehouseController@detailsup')->name('warehouse.detailsup');

Route::get('general_warehouse/selectsup','WarehouseController@selectsup')->name('warehouse.selectsup');
Route::get('general_warehouse/selectsuplot','WarehouseController@selectsuplot')->name('warehouse.selectsuplot');
Route::get('general_warehouse/selectsuptotal','WarehouseController@selectsuptotal')->name('warehouse.selectsuptotal');
Route::get('general_warehouse/selectsupunit','WarehouseController@selectsupunit')->name('warehouse.selectsupunit');
Route::get('general_warehouse/selectsuppiceunit','WarehouseController@selectsuppiceunit')->name('warehouse.selectsuppiceunit');
Route::get('general_warehouse/selectsupdatget','WarehouseController@selectsupdatget')->name('warehouse.selectsupdatget');
Route::get('general_warehouse/selectsupdatexp','WarehouseController@selectsupdatexp')->name('warehouse.selectsupdatexp');

Route::get('general_warehouse/detailpay','WarehouseController@detailpay')->name('warehouse.detailpay');//==รายละเอียดของที่จ่าย

//-------เพิ่มการขอเบิกพัสดุ
Route::get('general_warehouse/infowithdrawindex_add/{iduser}','WarehouseController@infowithdrawindex_add')->name('warehouse.infowithdrawindex_add');
Route::post('general_warehouse/saveinforequestwithdrawindex','WarehouseController@saveinforequestwithdrawindex')->name('warehouse.saveinforequestwithdrawindex');

Route::get('general_warehouse/detailsupselect','WarehouseController@detailsupselect')->name('warehouse.detailsupselect');

Route::get('general_warehouse/selectsupreq','WarehouseController@selectsupreq')->name('warehouse.selectsupreq');
Route::get('general_warehouse/selectsupunitname','WarehouseController@selectsupunitname')->name('warehouse.selectsupunitname');

//-------เห็นชอบ
Route::get('general_warehouse/infoapp/{iduser}','WarehouseController@infoapp')->name('warehouse.infoapp');
Route::post('general_warehouse/infoappsearch/{iduser}','WarehouseController@infoappsearch')->name('warehouse.infoappsearch');

Route::post('general_warehouse/infoappappupdate','WarehouseController@infoappappupdate')->name('warehouse.infoappappupdate');

//-------อนุมัติ
Route::get('general_warehouse/infolastapp/{iduser}','WarehouseController@infolastapp')->name('warehouse.infolastapp');

//===================================เจ้าหน้าที่

//======================================เงินเดือนค่าตอบแทน==============================================
Route::get('manager_compensation/dashboard','ManagercompensationController@dashboard')->name('mcompensation.dashboard');

Route::get('manager_compensation/infolistreceipt/{type}','ManagercompensationController@infolistreceipt')->name('mcompensation.infolistreceipt');
Route::get('manager_compensation/infolistreceipt_excel/{type}','ManagercompensationController@infolistreceipt_excel')->name('mcompensation.infolistreceipt_excel');

Route::post('manager_compensation/infolistreceipt_save','ManagercompensationController@infolistreceipt_save')->name('mcompensation.infolistreceipt_save');
Route::post('manager_compensation/infolistreceipt_edit','ManagercompensationController@infolistreceipt_update')->name('mcompensation.infolistreceipt_update');

Route::get('manager_compensation/infolistreceipt_infoperson/{idlist}','ManagercompensationController@infolistreceipt_infoperson')->name('mcompensation.infolistreceipt_infoperson');
Route::post('manager_compensation/infolistreceipt_infopersonsearch/{idlist}','ManagercompensationController@infolistreceipt_infopersonsearch')->name('mcompensation.infolistreceipt_infopersonsearch');

Route::get('manager_compensation/infolistreceipt_infopersonexcel/{idlist}','ManagercompensationController@infolistreceipt_infopersonexcel')->name('mcompensation.infolistreceipt_infopersonexcel');

Route::post('manager_compensation/infolistreceipt_infopersonsave','ManagercompensationController@infolistreceipt_infopersonsave')->name('mcompensation.infolistreceipt_infopersonsave');
Route::get('manager_compensation/infolistreceipt_infopersondestroy/{id}/{idlist}','ManagercompensationController@infolistreceipt_infopersondestroy');
Route::get('manager_compensation/updateamountreceipt','ManagercompensationController@updateamountreceipt')->name('mcompensation.updateamountreceipt');
Route::get('manager_compensation/summoneyreceipt','ManagercompensationController@summoneyreceipt')->name('mcompensation.summoneyreceipt');
Route::get('manager_compensation/infolistreceipt_infopersonsaveall/{receivid}','ManagercompensationController@infolistreceipt_infopersonsaveall')->name('mcompensation.infolistreceipt_infopersonsaveall');
Route::get('manager_compensation/infolistreceipt_infopersondeleteall/{receivid}','ManagercompensationController@infolistreceipt_infopersondeleteall')->name('mcompensation.infolistreceipt_infopersondeleteall');
Route::post('manager_compensation/infolistreceipt_infovalueall','ManagercompensationController@infolistreceipt_infovalueall')->name('mcompensation.infolistreceipt_infovalueall');
Route::get('manager_compensation/infolistreceipt_destroy/{id}/{type}','ManagercompensationController@infolistreceipt_destroy')->name('mcompensation.infolistreceipt_destroy');

Route::get('manager_compensation/infolistpay/{type}','ManagercompensationController@infolistpay')->name('mcompensation.infolistpay');
Route::get('manager_compensation/infolistpay_excel/{type}','ManagercompensationController@infolistpay_excel')->name('mcompensation.infolistpay_excel');

Route::post('manager_compensation/infolistpay_save','ManagercompensationController@infolistpay_save')->name('mcompensation.infolistpay_save');
Route::post('manager_compensation/infolistpay_edit','ManagercompensationController@infolistpay_update')->name('mcompensation.infolistpay_update');

Route::get('manager_compensation/infolistpay_infoperson/{idlist}','ManagercompensationController@infolistpay_infoperson')->name('mcompensation.infolistpay_infoperson');
Route::post('manager_compensation/infolistpay_infopersonsearch/{idlist}','ManagercompensationController@infolistpay_infopersonsearch')->name('mcompensation.infolistpay_infopersonsearch');

Route::get('manager_compensation/infolistpay_infopersonexcel/{idlist}','ManagercompensationController@infolistpay_infopersonexcel')->name('mcompensation.infolistpay_infopersonexcel');

Route::post('manager_compensation/infolistpay_infopersonsave','ManagercompensationController@infolistpay_infopersonsave')->name('mcompensation.infolistpay_infopersonsave');
Route::get('manager_compensation/infolistpay_infopersondestroy/{id}/{idlist}','ManagercompensationController@infolistpay_infopersondestroy');
Route::get('manager_compensation/updateamountpay','ManagercompensationController@updateamountpay')->name('mcompensation.updateamountpay');
Route::get('manager_compensation/summoneypay','ManagercompensationController@summoneypay')->name('mcompensation.summoneypay');
Route::get('manager_compensation/infolistpay_infopersonsaveall/{receivid}','ManagercompensationController@infolistpay_infopersonsaveall')->name('mcompensation.infolistpay_infopersonsaveall');
Route::get('manager_compensation/infolistpay_infopersondeleteall/{receivid}','ManagercompensationController@infolistpay_infopersondeleteall')->name('mcompensation.infolistpay_infopersondeleteall');
Route::post('manager_compensation/infolistpay_infovalueall','ManagercompensationController@infolistpay_infovalueall')->name('mcompensation.infolistpay_infovalueall');
Route::get('manager_compensation/infolistpay_destroy/{id}/{type}','ManagercompensationController@infolistpay_destroy')->name('mcompensation.infolistpay_destroy');

Route::post('manager_compensation/infolistpay_infovaluecal','ManagercompensationController@infolistpay_infovaluecal')->name('mcompensation.infolistpay_infovaluecal');

Route::get('manager_compensation/salarydetailperson','ManagercompensationController@salarydetailperson')->name('compensation.salarydetailperson');
Route::get('manager_compensation/salarydetailpersonsearch','ManagercompensationController@salarydetailpersonsearch')->name('compensation.salarydetailpersonsearch');

Route::get('manager_compensation/salarydetailperson_process','ManagercompensationController@salarydetailperson_process')->name('compensation.salarydetailperson_process');

Route::get('manager_compensation/callcompensation','ManagercompensationController@callcompensation')->name('mcompensation.callcompensation');
Route::post('manager_compensation/callcompensationprocess','ManagercompensationController@callcompensationprocess')->name('mcompensation.callcompensationprocess');
Route::get('manager_compensation/excelsalarybank/{year}/{month}','ManagercompensationController@excelsalarybank');
Route::get('manager_compensation/excelsalaryslip/{year}/{month}','ManagercompensationController@excelsalaryslip');

Route::get('manager_compensation/callcompensationdetail_sub/{idref}','ManagercompensationController@callcompensationdetail_sub')->name('mcompensation.callcompensationdetail_sub');
Route::post('manager_compensation/callcompensationdetail_subsearch/{idref}','ManagercompensationController@callcompensationdetail_subsearch')->name('mcompensation.callcompensationdetail_subsearch');
Route::get('manager_compensation/callcompensationdetail_subexcel/{idref}','ManagercompensationController@callcompensationdetail_subexcel')->name('mcompensation.callcompensationdetail_subexcel');

Route::get('manager_compensation/reportcallcompensationdetail_excel/{idref}','ManagercompensationController@reportcallcompensationdetail_excel')->name('mcompensation.reportcallcompensationdetail_excel');

Route::get('manager_compensation/infocompensation','ManagercompensationController@infocompensation')->name('mcompensation.infocompensation');

Route::match(['get','post'],'manager_compensation/infocertificate','ManagercompensationController@infocertificate')->name('mcompensation.infocertificate');
Route::post('manager_compensation/searchinfocertificate','ManagercompensationController@searchinfocertificate')->name('mcompensation.searchinfocertificate');

Route::get('manager_compensation/infocertificatelastapp/{idref}','ManagercompensationController@infocertificatelastapp')->name('mcompensation.infocertificatelastapp');
Route::post('manager_compensation/updatecertificatelastapp','ManagercompensationController@updatecertificatelastapp')->name('mcompensation.updatecertificatelastapp');

Route::get('manager_compensation/infocertificateappdoc','ManagercompensationController@Updatestatuscompen')->name('mcompensation.Updatestatuscompen');

Route::match(['get','post'],'manager_compensation/infosalaryslip','ManagercompensationController@infosalaryslip')->name('mcompensation.infosalaryslip');
Route::post('manager_compensation/searchinfosalaryslip','ManagercompensationController@searchinfosalaryslip')->name('mcompensation.searchinfosalaryslip');
Route::post('manager_compensation/updatesliplastapp','ManagercompensationController@updatesliplastapp')->name('mcompensation.updatesliplastapp');


Route::match(['get','post'],'manager_compensation/infoborrow','ManagercompensationController@infoborrow')->name('mcompensation.infoborrow');
Route::post('manager_compensation/searchinfoborrow','ManagercompensationController@searchinfoborrow')->name('mcompensation.searchinfoborrow');
Route::post('manager_compensation/infoborrow_app','ManagercompensationController@infoborrow_app')->name('mcompensation.infoborrow_app');

Route::post('manager_compensation/infoborrow_re','ManagercompensationController@infoborrow_re')->name('mcompensation.infoborrow_re');


Route::get('manager_compensation/pdfcertificate/export_pdfcertificate/{refid}', 'ManagercompensationController@export_pdfcertificate')->name('msupplies.export_pdfcertificate');//ใบรับรอง
Route::get('manager_compensation/pdfcertificate/export_pdfslip/{refid}', 'ManagercompensationController@export_pdfslip')->name('msupplies.export_pdfslip');//สลิปเงินเดือน
Route::get('manager_compensation/pdfborrow/export_pdfborrow/{refid}', 'ManagercompensationController@export_pdfborrow')->name('msupplies.export_pdfborrow');//ใบยืม-คืน
Route::get('manager_compensation/pdfborrow/export_pdfborrowblack/{refid}', 'ManagercompensationController@export_pdfborrowblack')->name('msupplies.export_pdfborrowblack');//ใบยืม-คืนด้านหลัง
Route::get('manager_compensation/pdfborrow/export_pdfreturnborrow/{refid}', 'ManagercompensationController@export_pdfreturnborrow')->name('msupplies.export_pdfreturnborrow');//ใบล้างเงินยืม

Route::get('manager_compensation/pdfborrow/export_pdftrack1/{refid}', 'ManagercompensationController@export_pdftrack1')->name('msupplies.export_pdftrack1');//แบบติดตาม1
Route::get('manager_compensation/pdfborrow/export_pdftrack2/{refid}', 'ManagercompensationController@export_pdftrack2')->name('msupplies.export_pdftrack2');//แบบติดตาม2

Route::get('manager_compensation/pdfcompensation/export_callcompensationdetail_sub/{refid}', 'ManagercompensationController@export_callcompensationdetail_sub')->name('msupplies.export_callcompensationdetail_sub');//  รายละเอียดรายรับ - รายจ่าย

Route::get('manager_compensation/infopersonsalary','ManagercompensationController@infopersonsalary')->name('mcompensation.infopersonsalary');

Route::get('manager_compensation/infopersonsalarydetail/{iduser}','ManagercompensationController@infopersonsalarydetail')->name('mcompensation.infopersonsalarydetail');
Route::post('manager_compensation/infopersonsalarydetail_search/{iduser}','ManagercompensationController@infopersonsalarydetail_search')->name('mcompensation.infopersonsalarydetail_search');

Route::match(['get','post'],'manager_compensation/infodetailcompensation','ManagercompensationController@infodetailcompensation')->name('mcompensation.infodetailcompensation');
Route::post('manager_compensation/infodetailcompensationsearch','ManagercompensationController@infodetailcompensationsearch')->name('mcompensation.infodetailcompensationsearch');
Route::get('manager_compensation/infodetailcompensation_linenotify/{salary_head_id}','ManagercompensationController@infodetailcompensation_linenotify')->name('mcompensation.infodetailcompensation_linenotify');

Route::get('manager_compensation/debtor','ManagercompensationController@debtor')->name('mcompensation.debtor');

Route::get('manager_compensation/staff','ManagercompensationController@staff')->name('mcompensation.staff');//ตั้งค่าเจ้าหน้าที่บัญชีการเงิน
Route::post('manager_compensation/updatestaff','ManagercompensationController@updatestaff')->name('mcompensation.updatestaff');

Route::match(['get','post'],'manager_compensation/purchase','ManagercompensationController@purchase')->name('manager_compensation.purchase');
Route::post('manager_compensation/searchpurchase','ManagercompensationController@searchpurchase')->name('manager_compensation.searchpurchase');


//======
Route::get('manager_compensation/inforeceipt','ManagercompensationController@inforeceipt')->name('mcompensation.inforeceipt');
Route::get('manager_compensation/infopay','ManagercompensationController@infopay')->name('mcompensation.infopay');


//=============================ทะเบียนเช็ค=====

Route::get('manager_compensation/account_check','ManagercompensationController@account_check')->name('mcompensation.account_check');
Route::get('manager_compensation/account_checkpdf/{idref}','ManagercompensationController@account_checkpdf')->name('mcompensation.account_checkpdf');

Route::post('manager_compensation/account_check_search','ManagercompensationController@account_check_search')->name('mcompensation.account_check_search');

Route::get('manager_compensation/account_check_add','ManagercompensationController@account_check_add')->name('mcompensation.account_check_add');
Route::post('manager_compensation/account_check_save','ManagercompensationController@account_check_save')->name('mcompensation.account_check_save');


Route::get('manager_compensation/account_check_list','ManagercompensationController@account_check_list')->name('mcompensation.account_check_list');

Route::get('manager_compensation/account_check_edit/{idref}','ManagercompensationController@account_check_edit')->name('mcompensation.account_check_edit');
Route::post('manager_compensation/account_check_update','ManagercompensationController@account_check_update')->name('mcompensation.account_check_update');

Route::get('manager_compensation/account_check_reseve/{idref}','ManagercompensationController@account_check_reseve')->name('mcompensation.account_check_reseve');


//=============================ทะเบียนบิล=====
Route::get('manager_compensation/account_bill','ManagercompensationController@account_bill')->name('mcompensation.account_bill');//ทะเบียนวางบิล
Route::post('manager_compensation/account_bill_search','ManagercompensationController@account_bill_search')->name('mcompensation.account_bill_search');

Route::get('manager_compensation/account_bill_add','ManagercompensationController@account_bill_add')->name('mcompensation.account_bill_add');
Route::post('manager_compensation/account_bill_save','ManagercompensationController@account_bill_save')->name('mcompensation.account_bill_save');

Route::get('manager_compensation/account_bill_list','ManagercompensationController@account_bill_list')->name('mcompensation.account_bill_list');

Route::get('manager_compensation/account_bill_edit/{idref}','ManagercompensationController@account_bill_edit')->name('mcompensation.account_bill_edit');
Route::post('manager_compensation/account_bill_update','ManagercompensationController@account_bill_update')->name('mcompensation.account_bill_update');

//==============================PDF Account===========================
Route::get('manager_compensation/account_pdfcertificate/{idref}', 'ManagercompensationController@account_pdfcertificate')->name('mcompensation.account_pdfcertificate'); //พิมพ์ใบสำคัญการลงบัญชี
Route::get('manager_compensation/account_pdfbook_1', 'ManagercompensationController@account_pdfbook_1')->name('mcompensation.account_pdfbook_1'); // account
Route::get('manager_compensation/account_pdfbook_2', 'ManagercompensationController@account_pdfbook_2')->name('mcompensation.account_pdfbook_2'); // account
Route::get('manager_compensation/account_pdfbook_type_1', 'ManagercompensationController@account_pdfbook_type_1')->name('mcompensation.account_pdfbook_type_1'); // account_แยกประเภท
Route::get('manager_compensation/account_pdfbook_type_2', 'ManagercompensationController@account_pdfbook_type_2')->name('mcompensation.account_pdfbook_type_2'); // account_แยกประเภท
Route::get('manager_compensation/account_pdf_reportday', 'ManagercompensationController@account_pdf_reportday')->name('mcompensation.account_pdf_reportday'); // account_รายงานรายวัน

Route::get('manager_compensation/account_pdf_paycheck', 'ManagercompensationController@account_pdf_paycheck')->name('mcompensation.account_pdf_paycheck'); // account_ทะเบียนจ่ายเช็ค
Route::get('manager_compensation/account_pdf_paymoney_vat', 'ManagercompensationController@account_pdf_paymoney_vat')->name('mcompensation.account_pdf_paymoney_vat'); // account_ขออนุมัติจ่ายเงิน ณ ที่จ่าย
Route::get('manager_compensation/account_pdf_certificate_vat', 'ManagercompensationController@account_pdf_certificate_vat')->name('mcompensation.account_pdf_certificate_vat'); // account_หนังสือรับรองการหัก ณ ที่จ่าย



//===============================รายละเอียดตาราง OT

Route::get('manager_compensation/otindex','ManagercompensationController@otindex')->name('manager_compensation.otindex');
Route::get('manager_compensation/otsetdetail_edit/{idref}','ManagercompensationController@otsetdetail_edit')->name('manager_compensation.otsetdetail_edit');
Route::post('manager_compensation/otsetdetail_update','ManagercompensationController@otsetdetail_update')->name('manager_compensation.otsetdetail_update');

Route::post('manager_compensation/infolistreceipt_infootsave','ManagercompensationController@infolistreceipt_infootsave')->name('mcompensation.infolistreceipt_infootsave');
//======================================ยาและเวชภัณฑ์===============================================
Route::get('manager_medical/dashboard','ManagermedicalController@dashboard')->name('mmedical.dashboard');

Route::get('manager_medical/requestforbuy','ManagermedicalController@requestforbuy')->name('mmedical.requestforbuy');

Route::get('manager_medical/purchase','ManagermedicalController@purchase')->name('mmedical.purchase');

Route::get('manager_medical/suppliesinfo','ManagermedicalController@suppliesinfo')->name('mmedical.suppliesinfo');
Route::get('manager_medical/suppliesinfo_add','ManagermedicalController@createsuppliesinfo')->name('mmedical.createsuppliesinfo');

//======================================คลังสินค้า==============================================
Route::match(['get','post'],'manager_warehouse/dashboard','ManagerwarehouseController@dashboard')->name('mwarehouse.dashboard');
Route::post('manager_warehouse/dashboardsearch','ManagerwarehouseController@dashboardsearch')->name('mwarehouse.dashboardsearch');
Route::match(['get','post'],'manager_warehouse/dashboard/request/','ManagerwarehouseController@dashboard_Request')->name('mwarehouse.dashboard_request');
Route::get('manager_warehouse/dashboard/min/','ManagerwarehouseController@dashboard_min')->name('mwarehouse.dashboard_min');
Route::get('manager_warehouse/dashboard/max/','ManagerwarehouseController@dashboard_max')->name('mwarehouse.dashboard_max');

Route::post('manager_warehouse/ajax_sum_waherehouse_store_receive_export','ManagerwarehouseController@ajax_sum_waherehouse_store_receive_export');
//---ตรวจสอบ
Route::match(['get','post'],'manager_warehouse/detail','ManagerwarehouseController@detail')->name('mwarehouse.detail');
Route::get('manager_warehouse/detail_edit/{id}','ManagerwarehouseController@detail_edit')->name('mwarehouse.detail_edit');
Route::post('manager_warehouse/detail_update','ManagerwarehouseController@detail_update')->name('mwarehouse.detail_update');
Route::post('manager_warehouse/detailsearch','ManagerwarehouseController@detailsearch')->name('mwarehouse.detailsearch');
Route::get('manager_warehouse/detail_excel/{yearbudget}/{datebigin}/{dateend}/{status_check}/{status}/{search}','ManagerwarehouseController@detail_excel')->name('mwarehouse.detail_excel');

Route::post('manager_warehouse/saveinfocheckadd','ManagerwarehouseController@saveinfocheckadd')->name('mwarehouse.saveinfocheckadd');

Route::post('manager_warehouse/updateinfochecksup','ManagerwarehouseController@updateinfochecksup')->name('mwarehouse.updateinfochecksup');

Route::get('manager_warehouse/warehouseinfocheck_add','ManagerwarehouseController@infocheckadd')->name('mwarehouse.infocheckadd');
Route::get('manager_warehouse/warehouseinfochecksup/{idref}','ManagerwarehouseController@infochecksup')->name('mwarehouse.infochecksup');

Route::get('manager_warehouse/warehouseinfocheckdetali/{idref}','ManagerwarehouseController@infocheckdetali')->name('mwarehouse.infocheckdetali');
Route::get('manager_warehouse/warehouseinfoconfirmdetali/{idref}','ManagerwarehouseController@infoconfirmdetali')->name('mwarehouse.infoconfirmdetail'); //ยืนยันรับเข้าคลัง
Route::post('manager_warehouse/updatewarehouseinfoconfirmdetali','ManagerwarehouseController@updatewarehouseinfoconfirmdetail')->name('mwarehouse.updatewarehouseinfoconfirmdetail'); 

//---เบิกจ่าย
Route::match(['get','post'],'manager_warehouse/disburse','ManagerwarehouseController@disburse')->name('mwarehouse.disburse');
Route::get('manager_warehouse/disburse_detail/{id}','ManagerwarehouseController@disburse_detail')->name('mwarehouse.disburse_detail');
Route::get('manager_warehouse/disbursesearch','ManagerwarehouseController@disbursesearch')->name('mwarehouse.disbursesearch');
Route::get('manager_warehouse/disburse_excel/{displaydate_bigen}/{displaydate_end}/{status_check}/{invenstatus_check}/{search}','ManagerwarehouseController@disburse_excel')->name('mwarehouse.disburse_excel');


Route::get('manager_warehouse/warehouseinfopayparcel/{id}','ManagerwarehouseController@infopayparcel')->name('mwarehouse.infopayparcel'); //จ่ายวัสดุ
Route::post('manager_warehouse/updateinfopayparcel','ManagerwarehouseController@updateinfopayparcel')->name('mwarehouse.updateinfopayparcel');

Route::get('manager_warehouse/detailsup','ManagerwarehouseController@detailsup')->name('mwarehouse.detailsup');

Route::get('manager_warehouse/selectsup','ManagerwarehouseController@selectsup')->name('mwarehouse.selectsup');
Route::get('manager_warehouse/selectsuplot','ManagerwarehouseController@selectsuplot')->name('mwarehouse.selectsuplot');
Route::get('manager_warehouse/selectsuptotal','ManagerwarehouseController@selectsuptotal')->name('mwarehouse.selectsuptotal');
Route::get('manager_warehouse/selectsupunit','ManagerwarehouseController@selectsupunit')->name('mwarehouse.selectsupunit');
Route::get('manager_warehouse/selectsuppiceunit','ManagerwarehouseController@selectsuppiceunit')->name('mwarehouse.selectsuppiceunit');
Route::get('manager_warehouse/selectsupdatget','ManagerwarehouseController@selectsupdatget')->name('mwarehouse.selectsupdatget');
Route::get('manager_warehouse/selectsupdatexp','ManagerwarehouseController@selectsupdatexp')->name('mwarehouse.selectsupdatexp');


Route::get('manager_warehouse/warehouserequestlastapp/{id}','ManagerwarehouseController@warehouserequestlastapp')->name('mwarehouse.warehouserequestlastapp');
Route::post('manager_warehouse/updatewarehouserequestlastapp','ManagerwarehouseController@updatewarehouserequestlastapp')->name('mwarehouse.updatewarehouserequestlastapp');
//---คลังหลัก
Route::match(['get','post'],'manager_warehouse/storehouse','ManagerwarehouseController@storehouse')->name('mwarehouse.storehouse');
Route::get('manager_warehouse/storehouse_detail/{id}','ManagerwarehouseController@storehouse_detail')->name('mwarehouse.storehouse_detail');

Route::get('manager_warehouse/storehouse_detail_excel','ManagerwarehouseController@storehouse_detail_excel')->name('mwarehouse.storehouse_detail_excel');

Route::get('manager_warehouse/storehousesub/{id}','ManagerwarehouseController@storehousesub')->name('mwarehouse.storehousesub');
Route::post('manager_warehouse/storehousesearch','ManagerwarehouseController@storehousesearch')->name('mwarehouse.storehousesearch');

Route::post('manager_warehouse/storehousesubsearch','ManagerwarehouseController@storehousesubsearch')->name('mwarehouse.storehousesubsearch');
Route::get('manager_warehouse/storehouseexcel','ManagerwarehouseController@storehouse_excel')->name('mwarehouse.storehouse_excel');
//---คลังย่อย
Route::match(['get','post'],'manager_warehouse/treasury','ManagerwarehouseController@treasury')->name('mwarehouse.treasury');
Route::get('manager_warehouse/treasury_detail/{id}','ManagerwarehouseController@treasury_detail')->name('mwarehouse.treasury_detail');
Route::get('manager_warehouse/treasury_sub/{id}','ManagerwarehouseController@treasury_sub')->name('mwarehouse.treasurysub');
Route::post('manager_warehouse/treasurysearch','ManagerwarehouseController@treasurysearch')->name('mwarehouse.treasurysearch');


Route::post('manager_warehouse/treasurysubsearch','ManagerwarehouseController@treasurysubsearch')->name('mwarehouse.treasurysubsearch');
Route::get('manager_warehouse/treasuryexcel/{typestore}/{search}','ManagerwarehouseController@treasury_excel')->name('mwarehouse.treasury_excel');

//---รายงาน

Route::get('manager_warehouse/reportvalue','ManagerwarehouseController@reportvalue')->name('mwarehouse.reportvalue');
Route::post('manager_warehouse/reportvaluesearch','ManagerwarehouseController@reportvaluesearch')->name('mwarehouse.reportvaluesearch');
Route::get('manager_warehouse/reportvalueexcel/{yearbudget}/{displaydate_bigen}/{displaydate_end}','ManagerwarehouseController@reportvalueexcel')->name('mwarehouse.reportvalueexcel');

Route::get('manager_warehouse/reportvaluestore','ManagerwarehouseController@reportvaluestore')->name('mwarehouse.reportvaluestore');
Route::post('manager_warehouse/reportvaluestoresearch','ManagerwarehouseController@reportvaluestoresearch')->name('mwarehouse.reportvaluestoresearch');
Route::get('manager_warehouse/reportvaluestoreexcel/{yearbudget}/{displaydate_bigen}/{displaydate_end}/{type_check}','ManagerwarehouseController@reportvaluestoreexcel')->name('mwarehouse.reportvaluestoreexcel');

Route::get('manager_warehouse/reportvaluetreasury','ManagerwarehouseController@reportvaluetreasury')->name('mwarehouse.reportvaluetreasury');
Route::post('manager_warehouse/reportvaluetreasurysearch','ManagerwarehouseController@reportvaluetreasurysearch')->name('mwarehouse.reportvaluetreasurysearch');
Route::get('manager_warehouse/reportvaluetreasutyexcel/{yearbudget}/{displaydate_bigen}/{displaydate_end}','ManagerwarehouseController@reportvaluetreasutyexcel')->name('mwarehouse.reportvaluetreasutyexcel');
//------------------------วัตถุประสงค์ 
Route::get('manager_warehouse/objectivepay', 'ManagerwarehouseController@objectivepay')->name('mwarehouse.objectivepay');
Route::get('manager_warehouse/objectivepay/add', 'ManagerwarehouseController@objectivepay_add')->name('mwarehouse.objectivepay_add');
Route::post('manager_warehouse/objectivepay/save', 'ManagerwarehouseController@objectivepay_save')->name('mwarehouse.objectivepay_save');
Route::get('manager_warehouse/objectivepay/edit/{id}', 'ManagerwarehouseController@objectivepay_edit')->name('mwarehouse.objectivepay_edit');
Route::post('manager_warehouse/objectivepay/update', 'ManagerwarehouseController@objectivepay_update')->name('mwarehouse.objectivepay_update');
Route::get('manager_warehouse/objectivepay/delete/{id}', 'ManagerwarehouseController@objectivepay_delete')->name('mwarehouse.objectivepay_delete');


//-------เพิ่มการขอเบิกพัสดุ
Route::get('manager_warehouse/warehousewithdraw_add','ManagerwarehouseController@warehousewithdraw_add')->name('mwarehouse.warehousewithdraw_add');
Route::post('manager_warehouse/warehousewithdraw_save','ManagerwarehouseController@warehousewithdraw_save')->name('mwarehouse.warehousewithdraw_save');


//============ สรุปงานวัสดุคงคลัง คลังพัสดุ======================//
Route::match(['get','post'],'manager_warehouse/reportstore_main','ReportWarehouseController@reportstore_main')->name('report.reportstore_main');
// Route::get('manager_warehouse/reportstore_main','ReportWarehouseController@reportstore_main')->name('report.reportstore_main');
Route::match(['get','post'],'manager_warehouse/reportstore_main_insert','ReportWarehouseController@reportstore_main_insert')->name('report.reportstore_main_insert');
// Route::post('manager_warehouse/reportstore_main_insert','ReportWarehouseController@reportstore_main_insert')->name('report.reportstore_main_insert');
Route::get('manager_warehouse/reportstore_main_update','ReportWarehouseController@reportstore_main_update')->name('report.reportstore_main_update');

Route::get('manager_warehouse/reportstore_main_totalmain/{displaydate_bigen}/{displaydate_end}','ReportWarehouseController@reportstore_main_totalmain')->name('report.reportstore_main_totalmain');




//======================================บัญชี==============================================
Route::get('manager_account/dashboard','ManageraccountController@dashboard')->name('maccount.dashboard');

Route::get('manager_account/detail','ManageraccountController@detail')->name('maccount.detail');

Route::get('manager_account/account_type','ManageraccountController@account_type')->name('maccount.account_type');

Route::get('manager_account/account_type_sub/{typename}','ManageraccountController@account_type_sub')->name('maccount.account_type_sub');//สมุดบัญชี
Route::post('manager_account/account_type_sub_search/{typename}','ManageraccountController@account_type_sub_search')->name('maccount.account_type_sub_search');

Route::get('manager_account/account_type_subadd/{typename}','ManageraccountController@account_type_subadd')->name('maccount.account_type_subadd');//เพิ่มข้อมูลสมุดบัญชี
Route::get('manager_account/account_type_subadd_theme/{typename}/{infothream}','ManageraccountController@account_type_subadd_theme')->name('maccount.account_type_subadd_theme');//

Route::get('manager_account/account_type_subedit/{typename}/{idref}','ManageraccountController@account_type_subedit')->name('maccount.account_type_subedit');//แก้ไขข้อมูลสมุดบัญชี

Route::get('manager_account/account_report','ManageraccountController@account_report')->name('maccount.account_report');
Route::post('manager_account/account_report_search','ManageraccountController@account_report_search')->name('maccount.account_report_search');

Route::get('manager_account/account_revenue','ManageraccountController@revenue')->name('maccount.revenue');
Route::get('manager_account/account_revenue_add','ManageraccountController@revenue_add')->name('maccount.revenue_add');
Route::get('manager_account/account_revenue_edit/{idref}','ManageraccountController@revenue_edit')->name('maccount.revenue_edit');

Route::get('manager_account/account_expenses','ManageraccountController@expenses')->name('maccount.expenses');
Route::get('manager_account/account_expenses_add','ManageraccountController@expenses_add')->name('maccount.expenses_add');
Route::get('manager_account/account_expenses_edit/{idref}','ManageraccountController@expenses_edit')->name('maccount.expenses_edit');

Route::post('manager_account/revenueexpenses_save','ManageraccountController@revenueexpenses_save')->name('maccount.revenueexpenses_save');
Route::post('manager_account/revenueexpenses_update','ManageraccountController@revenueexpenses_update')->name('maccount.revenueexpenses_update');

Route::get('manager_account/account_decline','ManageraccountController@decline')->name('maccount.decline');
Route::post('manager_account/account_searchdecline','ManageraccountController@searchdecline')->name('maccount.searchdecline');

Route::get('manager_account/account_creditor','ManageraccountController@creditor')->name('maccount.creditor');

Route::get('manager_account/account_creditor_edit/{idref}','ManageraccountController@creditor_edit')->name('maccount.creditor_edit');
Route::post('manager_account/account_creditor_update','ManageraccountController@account_creditor_update')->name('maccount.account_creditor_update');

Route::get('manager_account/account_repoort','ManageraccountController@repoort')->name('maccount.repoort');

Route::get('manager_account/setupaccount_accountschart','ManageraccountController@accountschart')->name('maccount.accountschart');
Route::post('manager_account/setupaccount_accountschart_search','ManageraccountController@accountschart_search')->name('maccount.accountschart_search');
Route::get('manager_account/setupaccount_accountschart_add','ManageraccountController@accountschart_add')->name('maccount.accountschart_add');
Route::post('manager_account/setupaccount_accountschart_save','ManageraccountController@accountschart_save')->name('maccount.accountschart_save');

Route::get('manager_account/setupaccount_accountschart_edit/{idref}','ManageraccountController@accountschart_edit')->name('maccount.accountschart_edit');
Route::post('manager_account/setupaccount_accountschart_update','ManageraccountController@accountschart_update')->name('maccount.accountschart_update');

Route::get('manager_account/setupaccount_accountschart_sub/{idref}','ManageraccountController@accountschart_sub')->name('maccount.accountschart_sub');
Route::get('manager_account/setupaccount_accountschart_sub_add/{idref}','ManageraccountController@accountschart_sub_add')->name('maccount.accountschart_sub_add');
Route::post('manager_account/setupaccount_accountschart_sub_save','ManageraccountController@accountschart_sub_save')->name('maccount.accountschart_sub_save');

Route::get('manager_account/setupaccount_accountschart_sub_edit/{idref}/{idsub}','ManageraccountController@setupaccount_accountschart_sub_edit')->name('maccount.setupaccount_accountschart_sub_edit');
Route::post('manager_account/setupaccount_accountschart_sub_update','ManageraccountController@setupaccount_accountschart_sub_update')->name('maccount.setupaccount_accountschart_sub_update');

Route::get('manager_account/setupaccount_accounttype','ManageraccountController@accounttype')->name('maccount.accounttype');

Route::get('manager_account/setupaccount_accounttype_add','ManageraccountController@accounttype_add')->name('maccount.accounttype_add');
Route::post('manager_account/setupaccount_accounttype_save','ManageraccountController@accounttype_save')->name('maccount.accounttype_save');

Route::get('manager_account/setupaccount_accounttype_edit/{idref}','ManageraccountController@accounttype_edit')->name('maccount.accounttype_edit');
Route::post('manager_account/setupaccount_accounttype_update','ManageraccountController@accounttype_update')->name('maccount.accounttype_update');

Route::get('manager_account/account_group','ManageraccountController@account_group')->name('maccount.account_group');
Route::get('manager_account/account_group_save','ManageraccountController@account_group_save')->name('maccount.account_group_save');
Route::post('manager_account/account_group_update','ManageraccountController@account_group_update')->name('maccount.account_group_update');

Route::get('manager_account/account_group_sub1','ManageraccountController@account_group_sub1')->name('maccount.account_group_sub1');
Route::get('manager_account/account_group_sub1_save','ManageraccountController@account_group_sub1_save')->name('maccount.account_group_sub1_save');
Route::post('manager_account/account_group_sub1_update','ManageraccountController@account_group_sub1_update')->name('maccount.account_group_sub1_update');

Route::get('manager_account/account_group_sub2','ManageraccountController@account_group_sub2')->name('maccount.account_group_sub2');
Route::get('manager_account/account_group_sub2_save','ManageraccountController@account_group_sub2_save')->name('maccount.account_group_sub2_save');
Route::post('manager_account/account_group_sub2_update','ManageraccountController@account_group_sub2_update')->name('maccount.account_group_sub2_update');


Route::get('manager_account/account_group_sub3','ManageraccountController@account_group_sub3')->name('maccount.account_group_sub3');
Route::get('manager_account/account_group_sub3_save','ManageraccountController@account_group_sub3_save')->name('maccount.account_group_sub3_save');
Route::post('manager_account/account_group_sub3_update','ManageraccountController@account_group_sub3_update')->name('maccount.account_group_sub3_update');

Route::get('manager_account/account_group_sub4','ManageraccountController@account_group_sub4')->name('maccount.account_group_sub4');
Route::get('manager_account/account_group_sub4_save','ManageraccountController@account_group_sub4_save')->name('maccount.account_group_sub4_save');
Route::post('manager_account/account_group_sub4_update','ManageraccountController@account_group_sub4_update')->name('maccount.account_group_sub4_update');



Route::get('manager_account/account_board','ManageraccountController@account_board')->name('maccount.account_board'); //ข้อมูลตั้งค่าคฯะกรรมการ
Route::post('manager_account/account_boardupdate','ManageraccountController@account_boardupdate')->name('maccount.account_boardupdate');


Route::get('manager_account/showaccount','ManageraccountController@showaccount')->name('maccount.showaccount'); 

//=============================ทะเบียนเช็ค=====

Route::get('manager_account/account_check','ManageraccountController@account_check')->name('maccount.account_check');
Route::get('manager_account/account_checkpdf/{idref}','ManageraccountController@account_checkpdf')->name('maccount.account_checkpdf');

Route::post('manager_account/account_check_search','ManageraccountController@account_check_search')->name('maccount.account_check_search');

Route::get('manager_account/account_check_add','ManageraccountController@account_check_add')->name('maccount.account_check_add');
Route::post('manager_account/account_check_save','ManageraccountController@account_check_save')->name('maccount.account_check_save');


Route::get('manager_account/account_check_list','ManageraccountController@account_check_list')->name('maccount.account_check_list');

Route::get('manager_account/account_check_edit/{idref}','ManageraccountController@account_check_edit')->name('maccount.account_check_edit');
Route::post('manager_account/account_check_update','ManageraccountController@account_check_update')->name('maccount.account_check_update');

Route::get('manager_account/account_check_reseve/{idref}','ManageraccountController@account_check_reseve')->name('maccount.account_check_reseve');


//=============================ทะเบียนบิล=====
Route::get('manager_account/account_bill','ManageraccountController@account_bill')->name('maccount.account_bill');
Route::post('manager_account/account_bill_search','ManageraccountController@account_bill_search')->name('maccount.account_bill_search');

Route::get('manager_account/account_bill_add','ManageraccountController@account_bill_add')->name('maccount.account_bill_add');
Route::post('manager_account/account_bill_save','ManageraccountController@account_bill_save')->name('maccount.account_bill_save');

Route::get('manager_account/account_bill_list','ManageraccountController@account_bill_list')->name('maccount.account_bill_list');

Route::get('manager_account/account_bill_edit/{idref}','ManageraccountController@account_bill_edit')->name('maccount.account_bill_edit');
Route::post('manager_account/account_bill_update','ManageraccountController@account_bill_update')->name('maccount.account_bill_update');

//==============================PDF Account===========================
Route::get('manager_account/account_pdfcertificate/{idref}', 'ManageraccountController@account_pdfcertificate')->name('maccount.account_pdfcertificate'); //พิมพ์ใบสำคัญการลงบัญชี
Route::get('manager_account/account_pdfbook_1', 'ManageraccountController@account_pdfbook_1')->name('maccount.account_pdfbook_1'); // account
Route::get('manager_account/account_pdfbook_2', 'ManageraccountController@account_pdfbook_2')->name('maccount.account_pdfbook_2'); // account
Route::get('manager_account/account_pdfbook_type_1', 'ManageraccountController@account_pdfbook_type_1')->name('maccount.account_pdfbook_type_1'); // account_แยกประเภท
Route::get('manager_account/account_pdfbook_type_2', 'ManageraccountController@account_pdfbook_type_2')->name('maccount.account_pdfbook_type_2'); // account_แยกประเภท
Route::get('manager_account/account_pdf_reportday', 'ManageraccountController@account_pdf_reportday')->name('maccount.account_pdf_reportday'); // account_รายงานรายวัน

Route::get('manager_account/account_pdf_paycheck', 'ManageraccountController@account_pdf_paycheck')->name('maccount.account_pdf_paycheck'); // account_ทะเบียนจ่ายเช็ค
Route::get('manager_account/account_pdf_paymoney_vat', 'ManageraccountController@account_pdf_paymoney_vat')->name('maccount.account_pdf_paymoney_vat'); // account_ขออนุมัติจ่ายเงิน ณ ที่จ่าย
Route::get('manager_account/account_pdf_certificate_vat', 'ManageraccountController@account_pdf_certificate_vat')->name('maccount.account_pdf_certificate_vat'); // account_หนังสือรับรองการหัก ณ ที่จ่าย

//======================================ศูนย์เปล==============================================
Route::get('manager_cradle/dashboard','ManagercradleController@dashboard')->name('mcradle.dashboard');

Route::get('manager_cradle/infocradle','ManagercradleController@infocradle')->name('mcradle.infocradle'); //แสดงข้อมูลเวลรเปล
Route::get('manager_cradle/infocradle_add','ManagercradleController@infocradle_add')->name('mcradle.infocradle_add');//เพิ่มข้อมูล
Route::post('manager_cradle/infocradle_save','ManagercradleController@infocradle_save')->name('mcradle.infocradle_save');
Route::get('manager_cradle/infocradle_edit/{idref}','ManagercradleController@infocradle_edit')->name('mcradle.infocradle_edit');//แก้ไขข้อมูลเวรเปล
Route::post('manager_cradle/infocradle_update','ManagercradleController@infocradle_update')->name('mcradle.infocradle_update');
Route::get('manager_cradle/infocradle_destroy/{idref}','ManagercradleController@infocradle_destroy');


//-------โภชนาการ
Route::get('manager_food/dashboard_food','ManagerfoodController@dashboard_food')->name('mfood.dashboard');
Route::post('manager_food/dashboard_foodsearch','ManagerfoodController@dashboard_foodsearch')->name('mfood.dashboard_foodsearch');


//=====5.5.63
Route::match(['get','post'],'manager_food/infofoodbill','ManagerfoodController@infofoodbill')->name('mfood.infofoodbill'); //ข้อมูลรายการใบ่ส่งของ
Route::post('manager_food/infofoodbillsearch','ManagerfoodController@infofoodbillsearch')->name('mfood.infofoodbillsearch');


Route::get('manager_food/infofoodbill_edit','ManagerfoodController@infofoodbill_edit')->name('mfood.infofoodbill_edit'); //แก้ไขข้อมูลข้อมูลรายการใบ่ส่งของ


Route::get('manager_food/infofoodbillday_add','ManagerfoodController@infofoodbillday_add')->name('mfood.infofoodbillday_add'); //เพิ่มทะเบียนข้อมูลรายการรายวัน
Route::post('manager_food/infofoodbillday_save','ManagerfoodController@infofoodbillday_save')->name('mfood.infofoodbillday_save'); 
Route::get('manager_food/infofoodbillday_edit/{id}','ManagerfoodController@infofoodbillday_edit')->name('mfood.infofoodbillday_edit'); 
Route::post('manager_food/infofoodbillday_update','ManagerfoodController@infofoodbillday_update')->name('mfood.infofoodbillday_update'); 
Route::get('manager_food/selectrequestfood','ManagerfoodController@selectrequestfood')->name('mfood.selectrequestfood');
Route::get('manager_food/selectdetalivendor','ManagerfoodController@selectdetalivendor')->name('mfood.selectdetalivendor');
Route::get('manager_food/conversion','ManagerfoodController@conversion')->name('mfood.conversion');
Route::get('manager_food/calvalue','ManagerfoodController@calvalue')->name('mfood.calvalue');



Route::get('manager_food/infofoodbill_add/{id}','ManagerfoodController@infofoodbill_add')->name('mfood.infofoodbill_add'); //หน้าแรกประมวลผล
Route::post('manager_food/infofoodbill_process','ManagerfoodController@infofoodbill_process')->name('mfood.infofoodbill_process'); //ประมวลผล

Route::get('manager_food/infofoodbillstaple_add/{id}','ManagerfoodController@infofoodbillstaple_add')->name('mfood.infofoodbillstaple_add'); //จัดการวัตถุดิบ
Route::post('manager_food/infofoodbillstaple_save','ManagerfoodController@infofoodbillstaple_save')->name('mfood.infofoodbillstaple_save'); 


Route::match(['get','post'],'manager_food/infofoodrequert','ManagerfoodController@infofoodrequert')->name('mfood.infofoodrequert'); //ข้อมูลการขอซื้อขอจ้าง
Route::post('manager_food/mfood_inforequestverupdate','ManagerfoodController@mfood_inforequestverupdate')->name('mfood.mfood_inforequestverupdate');
Route::get('manager_food/food_purchase_register/{id}','ManagerfoodController@food_purchase_register')->name('mfood.food_purchase_register'); //อ้างอิงทะเบียนคุม

Route::get('manager_food/food_requestforbuy_edit/{id}','ManagerfoodController@food_requestforbuy_edit')->name('mfood.food_requestforbuy_edit');
Route::post('manager_food/food_requestforbuy_update','ManagerfoodController@food_requestforbuy_update')->name('mfood.food_requestforbuy_update');

Route::get('manager_food/food_requestforbuy_cancel/{id}','ManagerfoodController@food_requestforbuy_cancel')->name('mfood.food_requestforbuy_cancel');
Route::post('manager_food/food_requestforbuy_update_cancel','ManagerfoodController@food_requestforbuy_update_cancel')->name('mfood.food_requestforbuy_update_cancel');



Route::match(['get','post'],'manager_food/infofoodbilltotal','ManagerfoodController@infofoodbilltotal')->name('mfood.infofoodbilltotal'); //ข้อมูลรายการใบ่ส่งของ
Route::post('manager_food/infofoodbilltotal_search','ManagerfoodController@searchinfofoodbilltotal')->name('mfood.searchinfofoodbilltotal'); 
Route::get('manager_food/infofoodbilltotal_add','ManagerfoodController@infofoodbilltotal_add')->name('mfood.infofoodbilltotal_add'); 
Route::post('manager_food/infofoodbilltotal_save','ManagerfoodController@saveinfofoodbilltotal')->name('mfood.saveinfofoodbilltotal'); 

Route::get('manager_food/infofoodbilltotal_edit/{id}','ManagerfoodController@infofoodbilltotal_edit')->name('mfood.infofoodbilltotal_edit');
Route::post('manager_food/infofoodbilltotal_update','ManagerfoodController@infofoodbilltotal_update')->name('mfood.infofoodbilltotal_update');

Route::get('manager_food/infofoodbilltotal_cancel/{id}','ManagerfoodController@infofoodbilltotal_cancel')->name('mfood.infofoodbilltotal_cancel');
Route::post('manager_food/infofoodbilltotal_cancelupdate','ManagerfoodController@infofoodbilltotal_cancelupdate')->name('mfood.infofoodbilltotal_cancelupdate');

Route::get('manager_food/infofoodbilltotal_quotation_add/{id}','ManagerfoodController@infofoodbilltotal_quotation_add')->name('mfood.infofoodbilltotal_quotation_add');
Route::get('manager_food/purchasequotation_addsub/{id}','ManagerfoodController@createpurchasequotationsub')->name('mfood.createpurchasequotationsub'); //เพิ่มใบเสนอราคา
Route::post('manager_food/purchasequotation_addsub/savepurchaselistaddsub','ManagerfoodController@savepurchasequotationsub')->name('mfood.savepurchasequotationsub');
Route::get('manager_food/fetchvendor','ManagerfoodController@fetchvendor')->name('msupplies.fetchvendor');
Route::get('manager_food/purchasequotation_addsubedit/{id}/{idref}','ManagerfoodController@purchasequotationsubedit')->name('mfood.purchasequotationsubedit');//---แก้ไขใบเสนอราคา
Route::post('manager_food/purchasequotation_addsubupdate','ManagerfoodController@purchasequotationsubupdate')->name('mfood.purchasequotationsubupdate');

Route::get('manager_food/purchasequotation_deletesub/{id}/{idref}','ManagerfoodController@purchasequotationsubdelete')->name('mfood.purchasequotationsubdelete');//---ลบใบเสนอราคา

Route::get('manager_food/infofoodfresh','ManagerfoodController@infofoodfresh')->name('mfood.infofoodfresh'); //เพิ่มรายการวัสดุ
Route::post('manager_food/infofoodfresh_update','ManagerfoodController@infofoodfresh_update')->name('mfood.infofoodfresh_update');

Route::get('manager_food/purchaselist_add/{idlistref}','ManagerfoodController@createpurchaselist')->name('mfood.createpurchaselist'); //เพิ่มรายการวัสดุ
Route::post('manager_food/purchaselist_add/savepurchaselistadd','ManagerfoodController@savepurchaselist')->name('mfood.savepurchaselist');

Route::get('manager_food/infofoodbilltotal_orders/{idlistref}','ManagerfoodController@infofoodbilltotal_orders')->name('mfood.infofoodbilltotal_orders');//ใบสั่งซื้อ
Route::post('manager_food/savepurchaseordersadd','ManagerfoodController@savepurchaseorders')->name('mfood.savepurchaseorders');


Route::get('manager_food/dashboard','ManagerfoodController@dashboard')->name('mfood.dashboard');

Route::match(['get','post'],'manager_food/infofood','ManagerfoodController@infofood')->name('mfood.infofood'); //ข้อมูลวัตถุดิบ
Route::post('manager_food/infofoodsearch','ManagerfoodController@infofoodsearch')->name('mfood.infofoodsearch'); 


Route::get('manager_food/infofood_add','ManagerfoodController@infofood_add')->name('mfood.infofood_add'); //เพิ่มข้อมูลวัตถุดิบ
Route::post('manager_food/infofood_save','ManagerfoodController@infofood_save')->name('mfood.infofood_save');
Route::get('manager_food/infofood_edit/{idref}','ManagerfoodController@infofood_edit')->name('mfood.infofood_edit'); //แก้ไขข้อมูลวัตถุดิบ
Route::post('manager_food/infofood_update','ManagerfoodController@infofood_update')->name('mfood.infofood_update'); 
//======//

Route::get('manager_food/infofoodboard','ManagerfoodController@infofoodboard')->name('mfood.infofoodboard'); //ข้อมูลตั้งค่าคฯะกรรมการ
Route::post('manager_food/infofoodboardupdate','ManagerfoodController@infofoodboardupdate')->name('mfood.infofoodboardupdate');

Route::match(['get','post'],'manager_food/infofoodmenu','ManagerfoodController@infofoodmenu')->name('mfood.infofoodmenu'); //แสดงข้อมูลเมนู
Route::post('manager_food/infofoodmenusearch','ManagerfoodController@infofoodmenusearch')->name('mfood.infofoodmenusearch'); //แสดงข้อมูลเมนู

Route::get('manager_food/infofoodmenu_add','ManagerfoodController@infofoodmenu_add')->name('mfood.infofoodmenu_add'); //เพิ่มมูลเมนู
Route::post('manager_food/infofoodmenu_save','ManagerfoodController@infofoodmenu_save')->name('mfood.infofoodmenu_save');
Route::get('manager_food/infofoodmenu_edit/{idref}','ManagerfoodController@infofoodmenu_edit')->name('mfood.infofoodmenu_edit'); //แก้ไขข้อมูลเมนู
Route::post('manager_food/infofoodmenu_update','ManagerfoodController@infofoodmenu_update')->name('mfood.infofoodmenu_update');

Route::get('manager_food/purchascheck/{idlistref}','ManagerfoodController@purchascheck')->name('mfood.purchascheck'); //ตรวจสอบรับ
Route::post('manager_food/savepurchascheck','ManagerfoodController@savepurchascheck')->name('mfood.savepurchascheck');

Route::get('manager_food/purchasequotation_confirm/{id}','ManagerfoodController@confirmpurchase')->name('mfood.confirmpurchase');//---------ยืนยันตรวจรับ




//====================ตั้งค่า โภชนาการ

Route::get('manager_food/infofoodtype','ManagerfoodController@infofoodtype')->name('mfood.infofoodtype');
Route::get('manager_food/infofoodtype_add','ManagerfoodController@infofoodtype_add')->name('mfood.infofoodtype_add');
Route::post('manager_food/infofoodtype_save','ManagerfoodController@infofoodtype_save')->name('mfood.infofoodtype_save');
Route::get('manager_food/infofoodtype_edit/{idref}','ManagerfoodController@infofoodtype_edit')->name('mfood.infofoodtype_edit');
Route::post('manager_food/infofoodtype_update','ManagerfoodController@infofoodtype_update')->name('mfood.infofoodtype_update');
Route::get('manager_food/destroyinfofoodtype_update/{idref}','ManagerfoodController@destroyinfofoodtype_update')->name('mfood.destroyinfofoodtype_update');


Route::get('manager_food/infofoodunit','ManagerfoodController@infofoodunit')->name('mfood.infofoodunit');
Route::get('manager_food/infofoodunit_add','ManagerfoodController@infofoodunit_add')->name('mfood.infofoodunit_add');
Route::post('manager_food/infofoodunit_save','ManagerfoodController@infofoodunit_save')->name('mfood.infofoodunit_save');
Route::get('manager_food/infofoodunit_edit/{idref}','ManagerfoodController@infofoodunit_edit')->name('mfood.infofoodunit_edit');
Route::post('manager_food/infofoodunit_update','ManagerfoodController@infofoodunit_update')->name('mfood.infofoodunit_update');
Route::get('manager_food/destroyinfofoodunit_update/{idref}','ManagerfoodController@destroyinfofoodunit_update')->name('mfood.destroyinfofoodunit_update');
//=========================PDF โภชนาการ

Route::get('manager_food/export_pdfrequest/{idref}', 'ManagerfoodController@export_pdfrequest')->name('mfood.export_pdfrequest');//ใบขออนุมัติ
Route::get('manager_food/export_pdfpay/{idref}', 'ManagerfoodController@export_pdfpay')->name('mfood.export_pdfpay'); //ใบจ่ายเงินค่าอาหาร
Route::get('manager_food/export_pdffrontpage/{idref}', 'ManagerfoodController@export_pdffrontpage')->name('mfood.export_pdffrontpage'); //ใบปะหน้า
Route::get('manager_food/export_pdf_temporary_delivery/{idref}', 'ManagerfoodController@export_pdf_temporary_delivery')->name('mfood.export_pdf_temporary_delivery'); //ใบส่งของชั่วคราว




//======================================ENV==============================================//
//**************************manager_env ************************//
Route::get('manager_env/dashboard','ManagerenvController@dashboard')->name('menv.dashboard');
Route::post('manager_env/dashboard_search','ManagerenvController@dashboard_search')->name('menv.dashboard_search');

Route::get('manager_env/detail','ManagerenvController@detail')->name('menv.detail');

Route::match(['get','post'],'manager_env/electrical','ManagerenvController@electrical')->name('menv.electrical');//ระบบไฟฟ้า
Route::post('manager_env/electrical_search','ManagerenvController@electrical_search')->name('menv.electrical_search');
Route::get('manager_env/electrical_add','ManagerenvController@electrical_add')->name('menv.electrical_add');
Route::post('manager_env/electrical_save','ManagerenvController@electrical_save')->name('menv.electrical_save');
Route::get('manager_env/electrical_edit/{id}','ManagerenvController@electrical_edit')->name('menv.electrical_edit');
Route::post('manager_env/electrical_update','ManagerenvController@electrical_update')->name('menv.electrical_update');
Route::get('manager_env/electrical_destroy/{id}','ManagerenvController@electrical_destroy')->name('menv.electrical_destroy');

// Route::get('manager_env/plumbing', [ManagerenvController::class,'plumbing'])->name('plumbing ');//ระบบประปา
Route::get('manager_env/plumbing_add', [ManagerenvController::class,'plumbing_add'])->name('plumbing_add ');
Route::post('manager_env/plumbing_save',[ManagerenvController::class,'plumbing_save'])->name('plumbing_save');

Route::match(['get','post'],'manager_env/plumbing','ManagerenvController@plumbing')->name('menv.plumbing');
Route::get('manager_env/plumbing_edit/{id}','ManagerenvController@plumbing_edit')->name('menv.plumbing_edit');
Route::get('manager_env/plumbing_delete/{id}','ManagerenvController@plumbing_delete')->name('menv.plumbing_delete');
// Route::get('manager_env/plumbing_add','ManagerenvController@plumbing_add')->name('menv.plumbing_add');
// Route::post('manager_env/plumbing_save','ManagerenvController@plumbing_save')->name('menv.plumbing_save');
Route::post('manager_env/plumbing_serch','ManagerenvController@plumbing_serch')->name('menv.plumbing_serch');
Route::post('manager_env/plumbing_update','ManagerenvController@plumbing_update')->name('menv.plumbing_update');



Route::match(['get','post'],'manager_env/oxigen','ManagerenvController@oxigen')->name('menv.oxigen');//ระบบออกซิเจนเหลว
Route::get('manager_env/oxigenexcel','ManagerenvController@oxigenexcel')->name('menv.oxigenexcel');//ระบบออกซิเจนเหลว excel
Route::post('manager_env/oxigen_search','ManagerenvController@oxigen_search')->name('menv.oxigen_search');
Route::get('manager_env/oxigen_add','ManagerenvController@oxigen_add')->name('menv.oxigen_add');
Route::post('manager_env/oxigen_save','ManagerenvController@oxigen_save')->name('menv.oxigen_save');
Route::get('manager_env/oxigen_edit/{id}','ManagerenvController@oxigen_edit')->name('menv.oxigen_edit');
Route::post('manager_env/oxigen_update','ManagerenvController@oxigen_update')->name('menv.oxigen_update');
Route::get('manager_env/oxigen_destroy/{id}','ManagerenvController@oxigen_destroy')->name('menv.oxigen_destroy');


Route::match(['get','post'],'manager_env/watertreatment','ManagerenvController@watertreatment')->name('menv.watertreatment');//ระบบบำบัดน้ำเสีย
Route::get('manager_env/watertreatmentexcel','ManagerenvController@watertreatmentexcel')->name('menv.watertreatmentexcel');//ระบบบำบัดน้ำเสีย excel
Route::post('manager_env/watertreatment_search','ManagerenvController@watertreatment_search')->name('menv.watertreatment_search');
Route::get('manager_env/watertreatment_add','ManagerenvController@watertreatment_add')->name('menv.watertreatment_add');
Route::post('manager_env/watertreatment_save','ManagerenvController@watertreatment_save')->name('menv.watertreatment_save');
Route::get('manager_env/watertreatment_edit/{id}','ManagerenvController@watertreatment_edit')->name('menv.watertreatment_edit');
Route::post('manager_env/watertreatment_update','ManagerenvController@watertreatment_update')->name('menv.watertreatment_update');
Route::get('manager_env/watertreatment_destroy/{id}','ManagerenvController@watertreatment_destroy')->name('menv.watertreatment_destroy');

Route::match(['get','post'],'manager_env/trash','ManagerenvController@trash')->name('menv.trash');//ระบบขยะติดเชื้อ
Route::post('manager_env/trash_search','ManagerenvController@trash_search')->name('menv.trash_search');
Route::get('manager_env/trash_add','ManagerenvController@trash_add')->name('menv.trash_add');
Route::post('manager_env/trash_save','ManagerenvController@trash_save')->name('menv.trash_save');
Route::get('manager_env/trash_edit/{id}','ManagerenvController@trash_edit')->name('menv.trash_edit');
Route::post('manager_env/trash_update','ManagerenvController@trash_update')->name('menv.trash_update');
Route::get('manager_env/trash_destroy/{id}','ManagerenvController@trash_destroy')->name('menv.trash_destroy');



//*********************admin_env ****************************//

Route::get('manager_env/set_trash','ManagerenvController@set_trash')->name('set_env.set_trash');//ระบบขยะ
Route::get('manager_env/set_trash_add','ManagerenvController@set_trash_add')->name('set_env.set_trash_add');
Route::post('manager_env/set_trash_save','ManagerenvController@set_trash_save')->name('set_env.set_trash_save');
Route::get('manager_env/set_trash_edit/{id}','ManagerenvController@set_trash_edit')->name('set_env.set_trash_edit');
Route::post('manager_env/set_trash_update','ManagerenvController@set_trash_update')->name('set_env.set_trash_update');
Route::get('manager_env/set_trash_destroy/{id}','ManagerenvController@set_trash_destroy')->name('set_env.set_trash_destroy');


Route::get('manager_env/set_oxigen','ManagerenvController@set_oxigen')->name('set_env.set_oxigen');//ระบบออกซิเจนเหลว
Route::get('manager_env/set_oxigen_add','ManagerenvController@set_oxigen_add')->name('set_env.set_oxigen_add');
Route::post('manager_env/set_oxigen_save','ManagerenvController@set_oxigen_save')->name('set_env.set_oxigen_save');
Route::get('manager_env/set_oxigen_edit/{id}','ManagerenvController@set_oxigen_edit')->name('set_env.set_oxigen_edit');
Route::post('manager_env/set_oxigen_update','ManagerenvController@set_oxigen_update')->name('set_env.set_oxigen_update');
Route::get('manager_env/set_oxigen_destroy/{id}','ManagerenvController@set_oxigen_destroy')->name('set_env.set_oxigen_destroy');

Route::get('manager_env/list_check','ManagerenvController@list_check')->name('menv.list_check');//ตั้งค่ารายการตรวจเช็ค ระบบไฟฟ้า
Route::get('manager_env/list_check_add','ManagerenvController@list_check_add')->name('menv.list_check_add');
Route::post('manager_env/list_check_save','ManagerenvController@list_check_save')->name('menv.list_check_save');
Route::get('manager_env/list_check_edit/{id}','ManagerenvController@list_check_edit')->name('menv.list_check_edit');
Route::post('manager_env/list_check_update','ManagerenvController@list_check_update')->name('menv.list_check_update');
Route::get('manager_env/list_check_destroy/{id}','ManagerenvController@list_check_destroy')->name('menv.list_check_destroy');

Route::get('manager_env/list_parameter','ManagerenvController@list_parameter')->name('menv.list_parameter');//ตั้งค่ารายการพารามิเตอร์ ระบบบำบัดน้ำเสีย
Route::get('manager_env/list_parameter_add','ManagerenvController@list_parameter_add')->name('menv.list_parameter_add');
Route::post('manager_env/list_parameter_save','ManagerenvController@list_parameter_save')->name('menv.list_parameter_save');
Route::get('manager_env/list_parameter_edit/{id}','ManagerenvController@list_parameter_edit')->name('menv.list_parameter_edit');
Route::post('manager_env/list_parameter_update','ManagerenvController@list_parameter_update')->name('menv.list_parameter_update');
Route::get('manager_env/list_parameter_destroy/{id}','ManagerenvController@list_parameter_destroy')->name('menv.list_parameter_destroy');
//======================================ENV ENV==============================================//
//======================================บริหารบ้านพัก==============================================
Route::get('manager_guesthouse/dashboard','ManagerguesthouseController@dashboard')->name('mguesthouse.dashboard');
Route::post('manager_guesthouse/dashboardsearch','ManagerguesthouseController@dashboardsearch')->name('mguesthouse.dashboardsearch');

Route::match(['get','post'],'manager_guesthouse/guesthouserequest','ManagerguesthouseController@guesthouserequest')->name('mguesthouse.guesthouserequest');
Route::post('manager_guesthouse/guesthouserequestsearch','ManagerguesthouseController@guesthouserequestsearch')->name('mguesthouse.guesthouserequestsearch');

Route::get('manager_guesthouse/guesthouserequestdetail','ManagerguesthouseController@guesthouserequestdetail')->name('mguesthouse.guesthouserequestdetail');

Route::get('manager_guesthouse/guesthouserequestdetail_flat/{id}/{type_check}','ManagerguesthouseController@guesthouserequestdetail_flat')->name('mguesthouse.guesthouserequestdetail_flat');
Route::get('manager_guesthouse/guesthouserequestdetail_flat_edit/{id}','ManagerguesthouseController@guesthouserequestdetail_flat_edit')->name('mguesthouse.guesthouserequestdetail_flat_edit');
Route::post('manager_guesthouse/guesthouserequestdetail_flat_update','ManagerguesthouseController@guesthouserequestdetail_flat_update')->name('mguesthouse.guesthouserequestdetail_flat_update');
Route::post('manager_guesthouse/guesthouserequestdetail_flat_destroy/{id}','ManagerguesthouseController@guesthouserequestdetail_flat_destroy')->name('mguesthouse.guesthouserequestdetail_flat_destroy');
Route::get('manager_guesthouse/guesthouserequestdetail_flat_room/{id}/{level_id}/{room_id}/{type_check}','ManagerguesthouseController@guesthouserequestdetail_flat_room')->name('mguesthouse.guesthouserequestdetail_flat_room');

Route::get('manager_guesthouse/guesthouserequestdetail_flathome/{id}/{level_id}/{room_id}/{type_check}','ManagerguesthouseController@guesthouserequestdetail_flathome')->name('mguesthouse.guesthouserequestdetail_flathome');

Route::get('manager_guesthouse/guesthouserequestdetail_home/{id}/{type_check}','ManagerguesthouseController@guesthouserequestdetail_home')->name('mguesthouse.guesthouserequestdetail_home');
Route::get('manager_guesthouse/guesthouserequestdetail_home_edit/{id}/{type_check}','ManagerguesthouseController@guesthouserequestdetail_home_edit')->name('mguesthouse.guesthouserequestdetail_home_edit');
Route::post('manager_guesthouse/guesthouserequestdetail_home_update','ManagerguesthouseController@guesthouserequestdetail_home_update')->name('mguesthouse.guesthouserequestdetail_home_update');
Route::post('manager_guesthouse/guesthouserequestdetail_home_destroy/{id}','ManagerguesthouseController@guesthouserequestdetail_home_destroy')->name('mguesthouse.guesthouserequestdetail_home_destroy');

//--------------------------------------

Route::post('manager_guesthouse/guesthouserequestdetail_flat_roomsaveperson','ManagerguesthouseController@guesthouserequestdetail_flat_roomsaveperson')->name('mguesthouse.guesthouserequestdetail_flat_roomsaveperson');
Route::post('manager_guesthouse/guesthouserequestdetail_flat_roomupdateperson','ManagerguesthouseController@guesthouserequestdetail_flat_roomupdateperson')->name('mguesthouse.guesthouserequestdetail_flat_roomupdateperson');
Route::get('manager_guesthouse/guesthouserequestdetail_flat_roomdestroyperson/{id}/{typesave}/{level_id}/{room_id}/{idref}','ManagerguesthouseController@guesthouserequestdetail_flat_roomdestroyperson')->name('mguesthouse.guesthouserequestdetail_flat_roomdestroyperson');




Route::post('manager_guesthouse/guesthouserequestdetail_saveperson','ManagerguesthouseController@guesthouserequestdetail_saveperson')->name('mguesthouse.guesthouserequestdetail_saveperson');
Route::post('manager_guesthouse/guesthouserequestdetail_updateperson','ManagerguesthouseController@guesthouserequestdetail_updateperson')->name('mguesthouse.guesthouserequestdetail_updateperson');
Route::get('manager_guesthouse/guesthouserequestdetail_destroyperson/{id}/{typesave}/{level_id}/{room_id}/{idref}','ManagerguesthouseController@guesthouserequestdetail_destroyperson')->name('mguesthouse.guesthouserequestdetail_destroyperson');


Route::post('manager_guesthouse/guesthouserequestdetail_saveoutsider','ManagerguesthouseController@guesthouserequestdetail_saveoutsider')->name('mguesthouse.guesthouserequestdetail_saveoutsider');
Route::post('manager_guesthouse/guesthouserequestdetail_updateoutsider','ManagerguesthouseController@guesthouserequestdetail_updateoutsider')->name('mguesthouse.guesthouserequestdetail_updateoutsider');
Route::get('manager_guesthouse/guesthouserequestdetail_destroyoutsider/{id}/{typesave}/{level_id}/{room_id}/{idref}','ManagerguesthouseController@guesthouserequestdetail_destroyoutsider')->name('mguesthouse.guesthouserequestdetail_destroyoutsider');

Route::post('manager_guesthouse/guesthouserequestdetail_saveasset','ManagerguesthouseController@guesthouserequestdetail_saveasset')->name('mguesthouse.guesthouserequestdetail_saveasset');
Route::post('manager_guesthouse/guesthouserequestdetail_updateasset','ManagerguesthouseController@guesthouserequestdetail_updateasset')->name('mguesthouse.guesthouserequestdetail_updateasset');
Route::get('manager_guesthouse/guesthouserequestdetail_destroyasset/{id}/{typesave}/{level_id}/{room_id}/{idref}','ManagerguesthouseController@guesthouserequestdetail_destroyasset')->name('mguesthouse.guesthouserequestdetail_destroyasset');

Route::post('manager_guesthouse/guesthouserequestdetail_saverepair','ManagerguesthouseController@guesthouserequestdetail_saverepair')->name('mguesthouse.guesthouserequestdetail_saverepair');
Route::post('manager_guesthouse/guesthouserequestdetail_updaterepair','ManagerguesthouseController@guesthouserequestdetail_updaterepair')->name('mguesthouse.guesthouserequestdetail_updaterepair');
Route::get('manager_guesthouse/guesthouserequestdetail_destroyrepair/{id}/{typesave}/{level_id}/{room_id}/{idref}','ManagerguesthouseController@guesthouserequestdetail_destroyrepair')->name('mguesthouse.guesthouserequestdetail_destroyrepair');


Route::get('manager_guesthouse/guesthouseinfomation_add','ManagerguesthouseController@guesthouseinfomation_add')->name('mguesthouse.guesthouseinfomation_add');//ฟร์อมข้อมูลห้องพัก
Route::post('manager_guesthouse/guesthouseinfomation_save','ManagerguesthouseController@guesthouseinfomation_save')->name('mguesthouse.guesthouseinfomation_save');

Route::match(['get','post'],'manager_guesthouse/guesthouseproblem','ManagerguesthouseController@guesthouseproblem')->name('mguesthouse.guesthouseproblem');
Route::post('manager_guesthouse/guesthouseproblemsearch','ManagerguesthouseController@guesthouseproblemsearch')->name('mguesthouse.guesthouseproblemsearch');
Route::get('manager_guesthouse/guesthouseproblem_edit/{idref}','ManagerguesthouseController@guesthouseproblem_edit')->name('mguesthouse.guesthouseproblem_edit');
Route::post('manager_guesthouse/guesthouseproblem_update','ManagerguesthouseController@guesthouseproblem_update')->name('mguesthouse.guesthouseproblem_update');
Route::get('manager_guesthouse/guesthouseproblem_destroy','ManagerguesthouseController@guesthouseproblem_destroy')->name('mguesthouse.guesthouseproblem_destroy');

Route::get('manager_guesthouse/guesthouseproblem_cancel/{idref}','ManagerguesthouseController@guesthouseproblem_cancel')->name('mguesthouse.guesthouseproblem_cancel');
Route::post('manager_guesthouse/guesthouseproblem_updatecancel','ManagerguesthouseController@guesthouseproblem_updatecancel')->name('mguesthouse.guesthouseproblem_updatecancel');

Route::get('manager_guesthouse/guesthouseproblem_succes/{idref}','ManagerguesthouseController@guesthouseproblem_succes')->name('mguesthouse.guesthouseproblem_succes');
Route::post('manager_guesthouse/guesthouseproblem_updatesucces','ManagerguesthouseController@guesthouseproblem_updatesucces')->name('mguesthouse.guesthouseproblem_updatesucces');


//-----------------------
Route::get('manager_guesthouse/guesthouserequest_edit/{idref}','ManagerguesthouseController@guesthouserequest_edit')->name('mguesthouse.guesthouserequest_edit');
Route::post('manager_guesthouse/guesthouserequest_update','ManagerguesthouseController@guesthouserequest_update')->name('mguesthouse.guesthouserequest_update');
Route::get('manager_guesthouse/guesthouserequest_destroy/{idref}','ManagerguesthouseController@guesthouserequest_destroy')->name('mguesthouse.guesthouserequest_destroy');


//-------------------------------------------------- รายงานบ้านพัก ------------------------------------------------//

Route::get('reportguesthouse/report_guesthouse','ReportguesthouseController@report_guesthouse')->name('report.report_guesthouse');
Route::get('reportguesthouse/report_guesthouse_excel','ReportguesthouseController@report_guesthouse_excel')->name('report.report_guesthouse_excel');

Route::get('reportguesthouse/report_guesthouse_person','ReportguesthouseController@report_guesthouse_person')->name('report.report_guesthouse_person');
Route::post('reportguesthouse/report_guesthouse_person_search','ReportguesthouseController@report_guesthouse_person_search')->name('report.report_guesthouse_person_search');
Route::post('reportguesthouse/report_guesthouse_person_pdf','ReportguesthouseController@report_guesthouse_person_pdf')->name('report.report_guesthouse_person_pdf');
// Route::get('reportguesthouse/report_guesthouse_person_pdf/{datebigin}/{dateend}','ReportguesthouseController@report_guesthouse_person_pdf')->name('report.report_guesthouse_person_pdf');
Route::get('reportguesthouse/report_guesthouse_person_excel','ReportguesthouseController@report_guesthouse_person_excel')->name('report.report_guesthouse_person_excel');





//======================================แผนงาน==============================================
Route::match(['get','post'],'manager_plan/dashboard','ManagerplanController@dashboard')->name('mplan.dashboard');
Route::post('manager_plan/dashboard_search','ManagerplanController@dashboard_search')->name('mplan.dashboard_search');
Route::get('manager_plan/plan_setstory','ManagerplanController@plan_setstory')->name('mplan.plan_setstory');

Route::get('manager_plan/plan_setstory_add','ManagerplanController@plan_setstory_add')->name('mplan.plan_setstory_add');


Route::get('manager_plan/plan_vision','ManagerplanController@plan_vision')->name('mplan.plan_vision');
Route::get('manager_plan/addplanvision','ManagerplanController@addplanvision')->name('mplan.addplanvision');
Route::post('manager_plan/saveplanvision','ManagerplanController@saveplanvision')->name('mplan.saveplanvision');
Route::get('manager_plan/editplanvision/{id}','ManagerplanController@editplanvision')->name('mplan.editplanvision');
Route::post('manager_plan/updateplanvision','ManagerplanController@updateplanvision')->name('mplan.updateplanvision');

Route::get('manager_plan/plan_mission','ManagerplanController@plan_mission')->name('mplan.plan_mission');
Route::get('manager_plan/addplanmission','ManagerplanController@addplanmission')->name('mplan.addplanmission');
Route::post('manager_plan/saveplanmission','ManagerplanController@saveplanmission')->name('mplan.saveplanmission');
Route::get('manager_plan/editplanmission/{id}','ManagerplanController@editplanmission')->name('mplan.editplanmission');
Route::post('manager_plan/updateplanmission','ManagerplanController@updateplanmission')->name('mplan.updateplanmission');

Route::get('manager_plan/plan_strategic','ManagerplanController@plan_strategic')->name('mplan.plan_strategic');
Route::post('manager_plan/plan_strategic_active','ManagerplanController@plan_strategic_active')->name('mplan.plan_strategic_active');
Route::get('manager_plan/addplanstrategic','ManagerplanController@addplanstrategic')->name('mplan.addplanstrategic');
Route::post('manager_plan/saveplanstrategic','ManagerplanController@saveplanstrategic')->name('mplan.saveplanstrategic');

Route::get('manager_plan/plan_strategic_detail/{id}','ManagerplanController@plan_strategic_detail')->name('mplan.plan_strategic_detail');

Route::get('manager_plan/editplanstrategic/{id}','ManagerplanController@editplanstrategic')->name('mplan.editplanstrategic');
Route::post('manager_plan/updateplanstrategic','ManagerplanController@updateplanstrategic')->name('mplan.updateplanstrategic');



Route::get('manager_plan/addplanmission','ManagerplanController@addplanmission')->name('mplan.addplanmission');
Route::post('manager_plan/saveplanmission','ManagerplanController@saveplanmission')->name('mplan.saveplanmission');


Route::get('manager_plan/plan_target/{id}','ManagerplanController@plan_target')->name('mplan.plan_target');
Route::get('manager_plan/plan_targetadd/{id}','ManagerplanController@plan_targetadd')->name('mplan.plan_targetadd');
Route::post('manager_plan/saveplan_target','ManagerplanController@saveplan_target')->name('mplan.saveplan_target');
Route::get('manager_plan/plan_targetedit/{id}/{idref}','ManagerplanController@plan_targetedit')->name('mplan.plan_targetedit');
Route::post('manager_plan/updateplan_target','ManagerplanController@updateplan_target')->name('mplan.updateplan_target');
Route::get('manager_plan/plan_target/destroy/{id}/{idref}','ManagerplanController@destroyplan_target');


Route::get('manager_plan/plan_kpi/{id}/{idtarget}','ManagerplanController@plan_kpi')->name('mplan.plan_kpi');
Route::get('manager_plan/plan_kpiadd/{id}/{idtarget}','ManagerplanController@plan_kpiadd')->name('mplan.plan_kpiadd');
Route::post('manager_plan/saveplan_kpi','ManagerplanController@saveplan_kpi')->name('mplan.saveplan_kpi');
Route::get('manager_plan/plan_kpiedit/{id}/{idtarget}/{idref}','ManagerplanController@plan_kpiedit')->name('mplan.plan_kpiedit');
Route::post('manager_plan/updateplan_kpi','ManagerplanController@updateplan_kpi')->name('mplan.updateplan_kpi');
Route::get('manager_plan/plan_kpi/destroy/{id}/{idtarget}/{idref}','ManagerplanController@destroyplan_kpi');

//------ตัวชี้วัดยุทธศาสตร์
Route::get('manager_plan/plan_kpiadddetail','ManagerplanController@plan_kpiadddetail')->name('mplan.plan_kpiadddetail');
Route::post('manager_plan/plan_kpiadddetail_search','ManagerplanController@plan_kpiadddetail_search')->name('mplan.plan_kpiadddetail_search');

Route::get('manager_plan/plan_kpiupdatedetail/{idkpi}','ManagerplanController@plan_kpiupdatedetail')->name('mplan.plan_kpiupdatedetail');
Route::post('manager_plan/plan_kpisaveupdatedetail','ManagerplanController@plan_kpisaveupdatedetail')->name('mplan.plan_kpisaveupdatedetail');

Route::get('manager_plan/plan_kpidetailfull/{idkpi}','ManagerplanController@plan_kpidetailfull')->name('mplan.plan_kpidetailfull');
//-----------------------------เมนูแผนงานโครงการ
Route::match(['get','post'],'manager_plan/project','ManagerplanController@project')->name('mplan.project');
Route::post('manager_plan/project_search','ManagerplanController@project_search')->name('mplan.project_search');
Route::get('manager_plan/project_add','ManagerplanController@project_add')->name('mplan.project_add');
Route::post('manager_plan/project_save','ManagerplanController@project_save')->name('mplan.project_save');
Route::get('manager_plan/project_edit/{idref}','ManagerplanController@project_edit')->name('mplan.project_edit');
Route::post('manager_plan/project_update','ManagerplanController@project_update')->name('mplan.project_update');
Route::get('manager_plan/project_destroy/{idref}','ManagerplanController@project_destroy')->name('mplan.project_destroy');
Route::get('manager_plan/project_app/{idref}','ManagerplanController@project_app')->name('mplan.project_app');
Route::get('manager_plan/project_notapp/{idref}','ManagerplanController@project_notapp')->name('mplan.project_notapp');

Route::match(['get','post'],'manager_plan/humandev','ManagerplanController@humandev')->name('mplan.humandev');
Route::post('manager_plan/humandev_search','ManagerplanController@humandev_search')->name('mplan.humandev_search');
Route::get('manager_plan/humandev_add','ManagerplanController@humandev_add')->name('mplan.humandev_add');
Route::post('manager_plan/humandev_save','ManagerplanController@humandev_save')->name('mplan.humandev_save');
Route::get('manager_plan/humandev_edit/{idref}','ManagerplanController@humandev_edit')->name('mplan.humandev_edit');
Route::post('manager_plan/humandev_update','ManagerplanController@humandev_update')->name('mplan.humandev_update');
Route::get('manager_plan/humandev_destroy/{idref}','ManagerplanController@humandev_destroy')->name('mplan.humandev_destroy');
Route::get('manager_plan/humandev_app/{idref}','ManagerplanController@humandev_app')->name('mplan.humandev_app');
Route::get('manager_plan/humandev_notapp/{idref}','ManagerplanController@humandev_notapp')->name('mplan.humandev_notapp');

Route::match(['get','post'],'manager_plan/durable','ManagerplanController@durable')->name('mplan.durable');
Route::post('manager_plan/durable_search','ManagerplanController@durable_search')->name('mplan.durable_search');
Route::get('manager_plan/durable_add','ManagerplanController@durable_add')->name('mplan.durable_add');
Route::post('manager_plan/durable_save','ManagerplanController@durable_save')->name('mplan.durable_save');
Route::get('manager_plan/durable_edit/{idref}','ManagerplanController@durable_edit')->name('mplan.durable_edit');
Route::post('manager_plan/durable_update','ManagerplanController@durable_update')->name('mplan.durable_update');
Route::get('manager_plan/durable_destroy/{idref}','ManagerplanController@durable_destroy')->name('mplan.durable_destroy');
Route::get('manager_plan/durable_app/{idref}','ManagerplanController@durable_app')->name('mplan.durable_app');
Route::get('manager_plan/durable_notapp/{idref}','ManagerplanController@durable_notapp')->name('mplan.durable_notapp');

Route::get('manager_plan/selectasset','ManagerplanController@selectasset')->name('mplan.selectasset');
Route::get('manager_plan/selectfsn','ManagerplanController@selectfsn')->name('mplan.selectfsn');

Route::get('manager_plan/selectass','ManagerplanController@selectass')->name('mplan.selectass');

Route::get('manager_plan/employ','ManagerplanController@employ')->name('mplan.employ');
Route::get('manager_plan/employ_add','ManagerplanController@employ_add')->name('mplan.employ_add');


//------------------------ออกแบบแผนงงานโครงการ
Route::get('manager_plan/project_plan_sub/{idref}','ManagerplanController@project_plan_sub')->name('mplan.project_plan_sub');
Route::get('manager_plan/project_plan_sub_add/{idref}','ManagerplanController@project_plan_sub_add')->name('mplan.project_plan_sub_add');
Route::post('manager_plan/project_plan_sub_save','ManagerplanController@project_plan_sub_save')->name('mplan.project_plan_sub_save');
Route::get('manager_plan/project_plan_sub_edit/{idref}/{idrefsub}','ManagerplanController@project_plan_sub_edit')->name('mplan.project_plan_sub_edit');
Route::post('manager_plan/project_plan_sub_update','ManagerplanController@project_plan_sub_update')->name('mplan.project_plan_sub_update');
Route::get('manager_plan/checkpositioninfo','ManagerplanController@checkpositioninfo')->name('mplan.checkpositioninfo');
Route::get('manager_plan/project_plan_sub_app/{idref}/{idrefsub}','ManagerplanController@project_plan_sub_app')->name('mplan.project_plan_sub_app');
Route::get('manager_plan/project_plan_sub_notapp/{idref}/{idrefsub}','ManagerplanController@project_plan_sub_notapp')->name('mplan.project_plan_sub_notapp');


//---งานซ่อม
Route::match(['get','post'],'manager_plan/repair','ManagerplanController@repair')->name('mplan.repair');
Route::post('manager_plan/repair_search','ManagerplanController@repair_search')->name('mplan.repair_search');
Route::get('manager_plan/repair_add','ManagerplanController@repair_add')->name('mplan.repair_add');
Route::post('manager_plan/repair_save','ManagerplanController@repair_save')->name('mplan.repair_save');
Route::get('manager_plan/repair_edit/{idref}','ManagerplanController@repair_edit')->name('mplan.repair_edit');
Route::post('manager_plan/repair_update','ManagerplanController@repair_update')->name('mplan.repair_update');
Route::get('manager_plan/repair_destroy/{idref}','ManagerplanController@repair_destroy')->name('mplan.repair_destroy');
Route::get('manager_plan/repair_app/{idref}','ManagerplanController@repair_app')->name('mplan.repair_app');
Route::get('manager_plan/repair_notapp/{idref}','ManagerplanController@repair_notapp')->name('mplan.repair_notapp');

//=======excel=======
Route::get('manager_plan/projectexcel','ManagerplanController@projectexcel')->name('mplan.projectexcel');
Route::get('manager_plan/humandevexcel','ManagerplanController@humandevexcel')->name('mplan.humandevexcel');
Route::get('manager_plan/durableexcel','ManagerplanController@durableexcel')->name('mplan.durableexcel');
Route::get('manager_plan/repairexcel','ManagerplanController@repairexcel')->name('mplan.repairexcel');

//===================================ฟังชั่นยทธศาสตร์
Route::get('manager_plan/dropdownstrategic','ManagerplanController@dropdownstrategic')->name('plandropdown.strategic');
Route::get('manager_plan/dropdowngoal','ManagerplanController@dropdowngoal')->name('plandropdown.goal');
Route::get('manager_plan/dropdownplantype','ManagerplanController@dropdownplantype')->name('plandropdown.plantype');
Route::get('manager_plan/dropdownteamunit','ManagerplanController@dropdownteamunit')->name('plandropdown.teamunit');


Route::get('manager_plan/plantype','ManagerplanController@plantype')->name('mplan.plantype');
Route::get('manager_plan/plantype_add','ManagerplanController@plantype_add')->name('mplan.plantype_add');
Route::post('manager_plan/saveplantype','ManagerplanController@saveplantype')->name('mplan.saveplantype');
Route::get('manager_plan/plantype_edit/{id}','ManagerplanController@plantype_edit')->name('mplan.plantype_edit');
Route::post('manager_plan/updateplantype','ManagerplanController@updateplantype')->name('mplan.updateplantype');
Route::get('manager_plan/plantype/destroy/{id}','ManagerplanController@destroyevent');

//แผนพัสดุ
Route::get('manager_plan/plansupplies','ManagerplanController@plansupplies')->name('mplan.plansupplies');
Route::get('manager_plan/plansupplies_add_year','ManagerplanController@plansupplies_add_year')->name('mplan.plansupplies_add_year');
Route::post('manager_plan/plansupplies_save_year','ManagerplanController@plansupplies_save_year')->name('mplan.plansupplies_save_year');
Route::get('manager_plan/plansupplies_detail/{id}','ManagerplanController@plansupplies_detail')->name('mplan.plansupplies_detail');
Route::get('manager_plan/plansupplies_add_plan/{id}','ManagerplanController@plansupplies_add_plan')->name('mplan.plansupplies_add_plan');
Route::post('manager_plan/plansupplies_save_plan','ManagerplanController@plansupplies_save_plan')->name('mplan.plansupplies_save_plan');
Route::get('manager_plan/plansupplies_destroy_plan/{ids}/{id}','ManagerplanController@plansupplies_destroy_plan')->name('mplan.plansupplies_destroy_plan');


//กำหนดปีตั้งต้นสำหรับการทำงาน
Route::get('manager_plan/planyear','ManagerplanController@planyear')->name('mplan.planyear');
Route::post('manager_plan/planyearupdate','ManagerplanController@planyearupdate')->name('mplan.planyearupdate');

//======================================ซักฟอก==============================================
Route::get('manager_launder/dashboard','ManagerlaunderController@dashboard')->name('mlaunder.dashboard');
Route::post('manager_launder/dashboardsearch','ManagerlaunderController@dashboardsearch')->name('mlaunder.dashboardsearch');

Route::get('manager_launder/detail','ManagerlaunderController@detail')->name('mlaunder.detail');


Route::get('manager_launder/selectrefnumber','ManagerlaunderController@selectrefnumber')->name('mlaunder.selectrefnumber');
Route::get('manager_launder/selectrefdep','ManagerlaunderController@selectrefdep')->name('mlaunder.selectrefdep');

//====================================== RISK ==============================================

Route::match(['get','post'],'manager_risk/risk_account','ManagerriskController@risk_account')->name('mrisk.risk_account');
Route::get('manager_risk/risk_account_incidence/{idref}','ManagerriskController@risk_account_incidence')->name('mrisk.risk_account_incidence');

Route::post('manager_risk/risk_account_detail_update','ManagerriskController@risk_account_detail_update')->name('mrisk.risk_account_detail_update');

Route::get('manager_risk/risk_notify_account_level/{idref}','ManagerriskController@risk_notify_account_level')->name('mrisk.risk_notify_account_level'); 
Route::post('manager_risk/risk_account_detail_level_save','ManagerriskController@risk_account_detail_level_save')->name('mrisk.risk_account_detail_level_save');
Route::post('manager_risk/risk_account_detail_level_update','ManagerriskController@risk_account_detail_level_update')->name('mrisk.risk_account_detail_level_update');

Route::get('manager_risk/risk_notify_account_incidence/{idref}','ManagerriskController@risk_notify_account_incidence')->name('manager_risk.risk_notify_account_incidence'); 

Route::get('manager_risk/risk_report4','ManagerriskController@risk_report4')->name('mrisk.risk_report4');
Route::get('manager_risk/risk_report4_detail/{idref}','ManagerriskController@risk_report4_detail')->name('mrisk.risk_report4_detail');

Route::get('manager_risk/risk_report5','ManagerriskController@risk_report5')->name('mrisk.risk_report5');
Route::get('manager_risk/risk_report5_sub/{idref}','ManagerriskController@risk_report5_sub')->name('mrisk.risk_report5_sub');


Route::get('manager_risk/dashboard','ManagerriskController@dashboard')->name('mrisk.dashboard');
Route::get('manager_risk/risktime','ManagerriskController@risktime')->name('mrisk.risktime');//ตั้งค่าเวลา
Route::post('manager_risk/risktime_save','ManagerriskController@risktime_save')->name('mrisk.risktime_save');
Route::post('manager_risk/risktime_update','ManagerriskController@risktime_update')->name('mrisk.risktime_update');
Route::get('manager_risk/risktime_destroy/{id}','ManagerriskController@risktime_destroy');

Route::get('manager_risk/risk_account','ManagerriskController@risk_account')->name('mrisk.risk_account');//บัญชีความเสี่ยง
Route::get('manager_risk/risk_account_detail/{id}','ManagerriskController@risk_account_detail')->name('mrisk.risk_account_detail');//บัญชีความเสี่ยง
Route::get('manager_risk/risk_account_add','ManagerriskController@risk_account_add')->name('mrisk.risk_account_add');//บัญชีความเสี่ยง
Route::post('manager_risk/risk_account_save','ManagerriskController@risk_account_save')->name('mrisk.risk_account_save');//บัญชีความเสี่ยง
Route::get('manager_risk/risk_account_edit/{id}','ManagerriskController@risk_account_edit')->name('mrisk.risk_account_edit');//บัญชีความเสี่ยง
Route::post('manager_risk/risk_account_update','ManagerriskController@risk_account_update')->name('mrisk.risk_account_update');//บัญชีความเสี่ยง
Route::get('manager_risk/risk_account_delete/{id}','ManagerriskController@risk_account_delete')->name('mrisk.risk_account_delete');//บัญชีความเสี่ยง

Route::get('manager_risk/risklocation','ManagerriskController@risklocation')->name('mrisk.risklocation');//ชนิดสถานที่
Route::post('manager_risk/risklocation_save','ManagerriskController@risklocation_save')->name('mrisk.risklocation_save');
Route::post('manager_risk/risklocation_update','ManagerriskController@risklocation_update')->name('mrisk.risklocation_update');
Route::get('manager_risk/risklocation_destroy/{id}','ManagerriskController@risklocation_destroy');

Route::get('manager_risk/risklocationuse','ManagerriskController@risklocationuse')->name('mrisk.risklocationuse');//สถานที่
Route::post('manager_risk/risklocationuse_save','ManagerriskController@risklocationuse_save')->name('mrisk.risklocationuse_save');
Route::post('manager_risk/risklocationuse_update','ManagerriskController@risklocationuse_update')->name('mrisk.risklocationuse_update');
Route::get('manager_risk/risklocationuse_destroy/{id}','ManagerriskController@risklocationuse_destroy');

Route::get('manager_risk/riskaffectperson','ManagerriskController@riskaffectperson')->name('mrisk.riskaffectperson');//ผู้ได้รับผลกระทบ
Route::post('manager_risk/riskaffectperson_save','ManagerriskController@riskaffectperson_save')->name('mrisk.riskaffectperson_save');
Route::post('manager_risk/riskaffectperson_update','ManagerriskController@riskaffectperson_update')->name('mrisk.riskaffectperson_update');
Route::get('manager_risk/riskaffectperson_destroy/{id}','ManagerriskController@riskaffectperson_destroy');


Route::get('manager_risk/riskgroup','ManagerriskController@riskgroup')->name('mrisk.riskgroup');//กลุ่มอุบัติการณ์ความเสี่ยง
Route::post('manager_risk/riskgroup_save','ManagerriskController@riskgroup_save')->name('mrisk.riskgroup_save');
Route::post('manager_risk/riskgroup_update','ManagerriskController@riskgroup_update')->name('mrisk.riskgroup_update');
Route::get('manager_risk/riskgroup_destroy/{id}','ManagerriskController@riskgroup_destroy');

Route::get('manager_risk/riskgroupsub','ManagerriskController@riskgroupsub')->name('mrisk.riskgroupsub');//หมวดอุบัติการณ์ความเสี่ยง
Route::post('manager_risk/riskgroupsub_save','ManagerriskController@riskgroupsub_save')->name('mrisk.riskgroupsub_save');
Route::post('manager_risk/riskgroupsub_update','ManagerriskController@riskgroupsub_update')->name('mrisk.riskgroupsub_update');
Route::get('manager_risk/riskgroupsub_destroy/{id}','ManagerriskController@riskgroupsub_destroy');

Route::get('manager_risk/riskgroupsubsub','ManagerriskController@riskgroupsubsub')->name('mrisk.riskgroupsubsub');//ประเภทอุบัติการณ์ความเสี่ยง
Route::post('manager_risk/riskgroupsubsub_save','ManagerriskController@riskgroupsubsub_save')->name('mrisk.riskgroupsubsub_save');
Route::post('manager_risk/riskgroupsubsub_update','ManagerriskController@riskgroupsubsub_update')->name('mrisk.riskgroupsubsub_update');
Route::get('manager_risk/riskgroupsubsub_destroy/{id}','ManagerriskController@riskgroupsubsub_destroy');

Route::get('manager_risk/riskrepdetail','ManagerriskController@riskrepdetail')->name('mrisk.riskrepdetail');//ประเภทอุบัติการณ์ความเสี่ยงย่อย
Route::post('manager_risk/riskrepdetail_save','ManagerriskController@riskrepdetail_save')->name('mrisk.riskrepdetail_save');
Route::post('manager_risk/riskrepdetail_update','ManagerriskController@riskrepdetail_update')->name('mrisk.riskrepdetail_update');
Route::get('manager_risk/riskrepdetail_destroy/{id}','ManagerriskController@riskrepdetail_destroy');

Route::get('manager_risk/riskrepitemsub','ManagerriskController@riskrepitemsub')->name('mrisk.riskrepitemsub');//อุบัติการณ์ความเสี่ยง
Route::post('manager_risk/riskrepitemsub_save','ManagerriskController@riskrepitemsub_save')->name('mrisk.riskrepitemsub_save');
Route::post('manager_risk/riskrepitemsub_update','ManagerriskController@riskrepitemsub_update')->name('mrisk.riskrepitemsub_update');
Route::get('manager_risk/riskrepitemsub_destroy/{id}','ManagerriskController@riskrepitemsub_destroy');

Route::get('manager_risk/riskrepitems','ManagerriskController@riskrepitems')->name('mrisk.riskrepitems');//อุบัติการณ์ความเสี่ยงย่อย
Route::post('manager_risk/riskrepitems_save','ManagerriskController@riskrepitems_save')->name('mrisk.riskrepitems_save');
Route::post('manager_risk/riskrepitems_update','ManagerriskController@riskrepitems_update')->name('mrisk.riskrepitems_update');
Route::get('manager_risk/riskrepitems_destroy/{id}','ManagerriskController@riskrepitems_destroy');

Route::get('manager_risk/riskrepprogram','ManagerriskController@riskrepprogram')->name('mrisk.riskrepprogram');//ลักษณะอุบัติการณ์ความเสี่ยง
Route::post('manager_risk/riskrepprogram_save','ManagerriskController@riskrepprogram_save')->name('mrisk.riskrepprogram_save');
Route::post('manager_risk/riskrepprogram_update','ManagerriskController@riskrepprogram_update')->name('mrisk.riskrepprogram_update');
Route::get('manager_risk/riskrepprogram_destroy/{id}','ManagerriskController@riskrepprogram_destroy');

Route::get('manager_risk/riskrepprogramsub','ManagerriskController@riskrepprogramsub')->name('mrisk.riskrepprogramsub');//รายละเอียดย่อย 1
Route::post('manager_risk/riskrepprogramsub_save','ManagerriskController@riskrepprogramsub_save')->name('mrisk.riskrepprogramsub_save');
Route::post('manager_risk/riskrepprogramsub_update','ManagerriskController@riskrepprogramsub_update')->name('mrisk.riskrepprogramsub_update');
Route::get('manager_risk/riskrepprogramsub_destroy/{id}','ManagerriskController@riskrepprogramsub_destroy');

Route::get('manager_risk/riskrepprogramsubsub','ManagerriskController@riskrepprogramsubsub')->name('mrisk.riskrepprogramsubsub');//รายละเอียดย่อย 2
Route::post('manager_risk/riskrepprogramsubsub_save','ManagerriskController@riskrepprogramsubsub_save')->name('mrisk.riskrepprogramsubsub_save');
Route::post('manager_risk/riskrepprogramsubsub_update','ManagerriskController@riskrepprogramsubsub_update')->name('mrisk.riskrepprogramsubsub_update');
Route::get('manager_risk/riskrepprogramsubsub_destroy/{id}','ManagerriskController@riskrepprogramsubsub_destroy');

Route::get('manager_risk/riskrep_typereason','ManagerriskController@riskrep_typereason')->name('mrisk.riskrep_typereason');//สาเหตุที่ชัดแจ้ง
Route::post('manager_risk/riskrep_typereason_save','ManagerriskController@riskrep_typereason_save')->name('mrisk.riskrep_typereason_save');
Route::post('manager_risk/riskrep_typereason_update','ManagerriskController@riskrep_typereason_update')->name('mrisk.riskrep_typereason_update');
Route::get('manager_risk/riskrep_typereason_destroy/{id}','ManagerriskController@riskrep_typereason_destroy');

Route::get('manager_risk/riskrep_typereason_sys','ManagerriskController@riskrep_typereason_sys')->name('mrisk.riskrep_typereason_sys');//สาเหตุที่ชัดแจ้ง
Route::post('manager_risk/riskrep_typereason_sys_save','ManagerriskController@riskrep_typereason_sys_save')->name('mrisk.riskrep_typereason_sys_save');
Route::post('manager_risk/riskrep_typereason_sys_update','ManagerriskController@riskrep_typereason_sys_update')->name('mrisk.riskrep_typereason_sys_update');
Route::get('manager_risk/riskrep_typereason_sys_destroy/{id}','ManagerriskController@riskrep_typereason_sys_destroy');

Route::get('manager_risk/riskrep_level','ManagerriskController@riskrep_level')->name('mrisk.riskrep_level');//ระดับความรุนแรง
Route::post('manager_risk/riskrep_level_save','ManagerriskController@riskrep_level_save')->name('mrisk.riskrep_level_save');
Route::post('manager_risk/riskrep_level_update','ManagerriskController@riskrep_level_update')->name('mrisk.riskrep_level_update');
Route::get('manager_risk/riskrep_level_destroy/{id}','ManagerriskController@riskrep_level_destroy');

Route::get('manager_risk/riskrep_function','ManagerriskController@riskrep_function')->name('mrisk.riskrep_function');//กำหนดฟังชั่น
Route::get('manager_risk/switchfunction','ManagerriskController@switchfunction')->name('mrisk.riskfunction');

Route::match(['get','post'],'manager_risk/detail','ManagerriskController@detail')->name('mrisk.detail');
Route::get('manager_risk/detail_detail/{id}','ManagerriskController@detail_detail')->name('mrisk.detail_detail');//รายละเอียดการบันทึกข้อมูลความเสี่ยง//05.6.63
Route::get('manager_risk/detail_add','ManagerriskController@detail_add')->name('mrisk.detail_add');
Route::post('manager_risk/detail_save','ManagerriskController@detail_save')->name('mrisk.detail_save');
Route::get('manager_risk/detail_edit/{id}','ManagerriskController@detail_edit')->name('mrisk.detail_edit');
Route::post('manager_risk/detail_update','ManagerriskController@detail_update')->name('mrisk.detail_update');

Route::get('manager_risk/detail_check/{id}','ManagerriskController@detail_check')->name('mrisk.detail_check');
Route::post('manager_risk/detail_checkupdate','ManagerriskController@detail_checkupdate')->name('mrisk.detail_checkupdate');

Route::get('manager_risk/detail_check_infer/{id}/{iduser}','ManagerriskController@detail_check_infer')->name('mrisk.detail_check_infer');
Route::post('manager_risk/detail_check_inferupdate','ManagerriskController@detail_check_inferupdate')->name('mrisk.detail_check_inferupdate');

Route::get('manager_risk/detail_check_recheck/{id}','ManagerriskController@detail_check_recheck')->name('mrisk.detail_check_recheck');
Route::get('manager_risk/detail_check_recheck_add/{id}','ManagerriskController@detail_check_recheck_add')->name('mrisk.detail_check_recheck_add');
Route::post('manager_risk/detail_check_recheck_save','ManagerriskController@detail_check_recheck_save')->name('mrisk.detail_check_recheck_save');

Route::get('manager_risk/detail_check_recheck_edit/{id}/{idref}','ManagerriskController@detail_check_recheck_edit')->name('mrisk.detail_check_recheck_edit');
Route::post('manager_risk/detail_check_recheck_update','ManagerriskController@detail_check_recheck_update')->name('mrisk.detail_check_recheck_update');


Route::get('manager_risk/detail_check_cancel/{id}','ManagerriskController@detail_check_cancel')->name('mrisk.detail_check_cancel'); //แจ้งยกเลิก
Route::post('manager_risk/detail_check_updatecancel','ManagerriskController@detail_check_updatecancel')->name('mrisk.detail_check_updatecancel');

Route::get('fectprogram','FectdataController@fectprogram')->name('mrisk.fectprogram');//fectprogram
Route::get('fectprogramsub','FectdataController@fectprogramsub')->name('mrisk.fectprogramsub');//fectprogramsub
Route::get('fectteam','FectdataController@fectteam')->name('mrisk.fectteam');//fectteam
Route::get('fecttypelocation','FectdataController@fecttypelocation')->name('mrisk.fecttypelocation');//typelocation

Route::get('fectgroup','FectdataController@fectgroup')->name('mrisk.fectgroup');//fectgroup
Route::get('fectgroupsub','FectdataController@fectgroupsub')->name('mrisk.fectgroupsub');//fectgroupsub
Route::get('fectgroupsubsub','FectdataController@fectgroupsubsub')->name('mrisk.fectgroupsubsub');//fectgroupsubsub

Route::get('fectitems','FectdataController@fectitems')->name('mrisk.fectitems');//fectitemsub

Route::get('fectriskdep','FectdataController@fectriskdep')->name('mrisk.fectriskdep');//fectriskdep
Route::get('fectriskdepsub','FectdataController@fectriskdepsub')->name('mrisk.fectriskdepsub');//fectriskdepsub
Route::get('fectriskteam','FectdataController@fectriskteam')->name('mrisk.fectriskteam');//fectriskteam
Route::get('fectscope','FectdataController@fectscope')->name('mrisk.fectscope');//fectriskteam
Route::get('fectriskeffect','FectdataController@fectriskeffect')->name('mrisk.fectriskeffect');//fectriskteam


Route::get('manager_risk/detail_cancel/{id}','ManagerriskController@detail_cancel')->name('mrisk.detail_cancel');
Route::post('manager_risk/detail_updatecancel','ManagerriskController@detail_updatecancel')->name('mrisk.detail_updatecancel');

Route::get('manager_risk/detail_destroy/{id}','ManagerriskController@detail_destroy');
Route::post('manager_risk/detail_search','ManagerriskController@detail_search')->name('mrisk.detail_search');//ค้นหา

Route::get('manager_risk/incidence','ManagerriskController@incidence')->name('mrisk.incidence');//รายงาน
Route::get('manager_risk/incidence_add','ManagerriskController@incidence_add')->name('mrisk.incidence_add');
Route::post('manager_risk/incidence_save','ManagerriskController@incidence_save')->name('mrisk.incidence_save');
Route::get('manager_risk/incidence_edit/{id}','ManagerriskController@incidence_edit')->name('mrisk.incidence_edit');
Route::post('manager_risk/incidence_update','ManagerriskController@incidence_update')->name('mrisk.incidence_update');
Route::get('manager_risk/incidence_destroy/{id}','ManagerriskController@incidence_destroy');

Route::post('alert_save','ManagerriskController@alert_save')->name('mrisk.alert_save');// ทดสอบ Alert
Route::delete('incidence_delete/{id}','ManagerriskController@delete');// ทดสอบ Alert

Route::get('manager_risk/internalcontrol','ManagerriskController@internalcontrol')->name('mrisk.internalcontrol');//ควบคุมภายใน

Route::get('manager_risk/internalcontrol_pk5_depart/{id}/{iduser}/{iddepart}','ManagerriskController@internalcontrol_pk5_depart')->name('mrisk.internalcontrol_pk5_depart');//ทะเบียน ปค.5 หน่วยงาน
Route::get('manager_risk/internalcontrol_pk5_depart_add/{id}/{iduser}/{iddepart}','ManagerriskController@internalcontrol_pk5_depart_add')->name('mrisk.internalcontrol_pk5_depart_add');
Route::post('manager_risk/internalcontrol_pk5_depart_save','ManagerriskController@internalcontrol_pk5_depart_save')->name('mrisk.internalcontrol_pk5_depart_save');
Route::get('manager_risk/internalcontrol_pk5_depart_edit/{id}','ManagerriskController@internalcontrol_pk5_depart_edit')->name('mrisk.internalcontrol_pk5_depart_edit');
Route::post('manager_risk/internalcontrol_pk5_depart_update','ManagerriskController@internalcontrol_pk5_depart_update')->name('mrisk.internalcontrol_pk5_depart_update');
Route::get('manager_risk/internalcontrol_pk5_depart_destroy/{id}/{iduser}/{iddepart}','ManagerriskController@internalcontrol_pk5_depart_destroy')->name('mrisk.internalcontrol_pk5_depart_destroy');

Route::get('manager_risk/excel_risk_depart/{id}','ManagerriskController@excel_risk_depart')->name('mrisk.excel_risk_depart');//รายงานการทะเบียน ปค.5 หน่วยงาน // EXcel
Route::get('manager_risk/excel_risk_organi/{id}','ManagerriskController@excel_risk_organi')->name('mrisk.excel_risk_organi');//รายงานการทะเบียน ปค.5 องค์กร // EXcel



Route::get('manager_risk/internalcontrol_pk5_organi/{id}/{iduser}/{iddepart}','ManagerriskController@internalcontrol_pk5_organi')->name('mrisk.internalcontrol_pk5_organi');//ทะเบียน ปค.5 องค์กร
Route::get('manager_risk/internalcontrol_pk5_organi_add/{id}/{iduser}/{iddepart}','ManagerriskController@internalcontrol_pk5_organi_add')->name('mrisk.internalcontrol_pk5_organi_add');
Route::post('manager_risk/internalcontrol_pk5_organi_save','ManagerriskController@internalcontrol_pk5_organi_save')->name('mrisk.internalcontrol_pk5_organi_save');
Route::get('manager_risk/internalcontrol_pk5_organi_edit/{id}','ManagerriskController@internalcontrol_pk5_organi_edit')->name('mrisk.internalcontrol_pk5_organi_edit');
Route::post('manager_risk/internalcontrol_pk5_organi_update','ManagerriskController@internalcontrol_pk5_organi_update')->name('mrisk.internalcontrol_pk5_organi_update');
Route::get('manager_risk/internalcontrol_pk5_organi_destroy/{id}/{iduser}/{iddepart}','ManagerriskController@internalcontrol_pk5_organi_destroy')->name('mrisk.internalcontrol_pk5_organi_destroy');

Route::get('manager_risk/internalcontrol_detail/{id}','ManagerriskController@internalcontrol_detail')->name('mrisk.internalcontrol_detail');//ควบคุมภายในรายละเอียด//05.6.63
Route::get('manager_risk/internalcontrol_add','ManagerriskController@internalcontrol_add')->name('mrisk.internalcontrol_add');
Route::post('manager_risk/internalcontrol_save','ManagerriskController@internalcontrol_save')->name('mrisk.internalcontrol_save');
Route::get('manager_risk/internalcontrol_edit/{idref}','ManagerriskController@internalcontrol_edit')->name('mrisk.internalcontrol_edit');
Route::post('manager_risk/internalcontrol_update','ManagerriskController@internalcontrol_update')->name('mrisk.internalcontrol_update');
Route::get('manager_risk/internalcontrol_destroy/{id}','ManagerriskController@internalcontrol_destroy')->name('mrisk.internalcontrol_destroy');
Route::get('manager_risk/internalcontrol_sub','ManagerriskController@internalcontrol_sub')->name('mrisk.internalcontrol_sub');
Route::get('manager_risk/internalcontrol_sub_add','ManagerriskController@internalcontrol_sub_add')->name('mrisk.internalcontrol_sub_add');
Route::get('manager_risk/internalcontrol_sub_edit','ManagerriskController@internalcontrol_sub_edit')->name('mrisk.internalcontrol_sub_edit');


//=====start======24.06.63================//

Route::get('manager_risk/internalcontrol_subsub_add/{idref}','ManagerriskController@internalcontrol_subsub_add')->name('mrisk.internalcontrol_subsub_add');//ประเมินควบคุมภายใน



//=====start======18.07.63================//
Route::get('manager_risk/internalcontrol_subsub_detailadd_make/{idref}','ManagerriskController@internalcontrol_subsub_detailadd_make')->name('mrisk.internalcontrol_subsub_detailadd_make');
Route::post('manager_risk/internalcontrol_subsub_detailadd_make_save','ManagerriskController@internalcontrol_subsub_detailadd_make_save')->name('mrisk.internalcontrol_subsub_detailadd_make_save');

Route::get('manager_risk/internalcontrol_subsub_detailadd_risk/{idref}','ManagerriskController@internalcontrol_subsub_detailadd_risk')->name('mrisk.internalcontrol_subsub_detailadd_risk');
Route::post('manager_risk/internalcontrol_subsub_detailadd_risk_save','ManagerriskController@internalcontrol_subsub_detailadd_risk_save')->name('mrisk.internalcontrol_subsub_detailadd_risk_save');

// Route::get('manager_risk/internalcontrol_subsub_detailadd/{idref}/{idsub}','ManagerriskController@internalcontrol_subsub_detailadd')->name('mrisk.internalcontrol_subsub_detailadd');
Route::get('manager_risk/internalcontrol_subsub_detailadd/{idref}','ManagerriskController@internalcontrol_subsub_detailadd')->name('mrisk.internalcontrol_subsub_detailadd');
Route::post('manager_risk/internalcontrol_subsub_detailadd_save','ManagerriskController@internalcontrol_subsub_detailadd_save')->name('mrisk.internalcontrol_subsub_detailadd_save');







Route::get('manager_risk/internalcontrol_subsub_detailadd_sub/{idref}/{idsub}/{idaddsub}','ManagerriskController@internalcontrol_subsub_detailadd_sub')->name('mrisk.internalcontrol_subsub_detailadd_sub');
Route::post('manager_risk/internalcontrol_subsub_detailadd_sub_save','ManagerriskController@internalcontrol_subsub_detailadd_sub_save')->name('mrisk.internalcontrol_subsub_detailadd_sub_save');

Route::post('manager_risk/savesubsub_detail','ManagerriskController@savesubsub_detail')->name('mrisk.savesubsub_detail');
Route::get('manager_risk/subsub_detailedit/{id}/{idref}/{idsub}','ManagerriskController@subsub_detailedit')->name('mrisk.subsub_detailedit');
Route::post('manager_risk/updatesubsub_detail','ManagerriskController@updatesubsub_detail')->name('mrisk.updatesubsub_detail');
Route::get('manager_risk/destroysubsub_detail/{id}/{idref}/{idsub}','ManagerriskController@destroysubsub_detail');

//======end=====24.06.63================//


Route::post('manager_risk/internalcontrolsearch','ManagerriskController@internalcontrolsearch')->name('mrisk.internalcontrolsearch');//ค้นหาควบคุมภายใน

Route::get('manager_risk/dataset','ManagerriskController@dataset')->name('mrisk.dataset');//data set
Route::get('manager_risk/know','ManagerriskController@know')->name('mrisk.know');//บันทึดองค์ความรู้
Route::get('manager_risk/unlock_incidence','ManagerriskController@unlock_incidence')->name('mrisk.unlock_incidence');//ปลดล็อค
Route::get('manager_risk/reportdelete_incidence','ManagerriskController@reportdelete_incidence')->name('mrisk.reportdelete_incidence');//ขอลบรายงาน
Route::get('manager_risk/requestformat_incidence','ManagerriskController@requestformat_incidence')->name('mrisk.requestformat_incidence');//ขอเปลี่ยนแปลงรูปแบบ

Route::get('manager_risk/incidence_group','ManagerriskController@incidence_group')->name('mrisk.incidence_group');//กลุ่มอุบัติการณ์ความเสี่ยง
Route::get('manager_risk/incidence_category','ManagerriskController@incidence_category')->name('mrisk.incidence_category');//ประเภทอุบัติการณ์ความเสี่ยง
Route::get('manager_risk/incidence_setting','ManagerriskController@incidence_setting')->name('mrisk.incidence_setting');//อุบัติการณ์ความเสี่ยง
Route::get('manager_risk/incidence_groupuser','ManagerriskController@incidence_groupuser')->name('mrisk.incidence_groupuser');//กลุ่มผู้ใช้
Route::get('manager_risk/incidence_level','ManagerriskController@incidence_level')->name('mrisk.incidence_level');//ระดับความรุนแรง
Route::get('manager_risk/incidence_location','ManagerriskController@incidence_location')->name('mrisk.incidence_location');//แหล่งที่มา
Route::get('manager_risk/incidence_origin','ManagerriskController@incidence_origin')->name('mrisk.incidence_origin');//สถานที่เกิดเหตุ
Route::get('manager_risk/incidence_listdataset','ManagerriskController@incidence_listdataset')->name('mrisk.incidence_listdataset');//รายการชุดข้อมูลกลางของระบบ
Route::get('manager_risk/incidence_sub','ManagerriskController@incidence_sub')->name('mrisk.incidence_sub');//อุบัติการณ์ความเสี่ยงย่อย


Route::get('manager_risk/report','ManagerriskController@report')->name('mrisk.report');//รายงาน

Route::get('manager_risk/report_riskincedentsprofile','ManagerriskController@report_riskincedentsprofile')->name('mrisk.report_riskincedentsprofile');//รายงานบริหารจัดการความเสี่ยง
Route::post('manager_risk/report_riskincedentsprofile_search','ManagerriskController@report_riskincedentsprofile_search')->name('mrisk.report_riskincedentsprofile_search');
Route::get('manager_risk/report_riskincedentsprofile_excel','ManagerriskController@report_riskincedentsprofile_excel');

Route::get('manager_risk/report_riskupdatefinish','ManagerriskController@report_riskupdatefinish')->name('mrisk.report_riskupdatefinish');//รายงานที่ได้รับการแก้ไขแล้ว

Route::get('manager_risk/report_riskincidencelevel','ManagerriskController@report_riskincidencelevel')->name('mrisk.report_riskincidencelevel');//รายงานระดับความรุนแรง
Route::post('manager_risk/report_riskincidencelevel_search','ManagerriskController@report_riskincidencelevel_search')->name('mrisk.report_riskincidencelevel_search');
Route::get('manager_risk/report_riskincidencelevel_excel','ManagerriskController@report_riskincidencelevel_excel')->name('mrisk.report_riskincidencelevel_excel');

Route::get('manager_risk/report_riskdepartment','ManagerriskController@report_riskdepartment')->name('mrisk.report_riskdepartment');//รายงานความเสี่ยงขององค์กร
Route::post('manager_risk/report_riskdepartment_search','ManagerriskController@report_riskdepartment_search')->name('mrisk.report_riskdepartment_search');
Route::get('manager_risk/report_riskdepartment_excel','ManagerriskController@report_riskdepartment_excel')->name('mrisk.report_riskdepartment_excel');


Route::get('manager_risk/report_riskincidence_group','ManagerriskController@report_riskincidence_group')->name('mrisk.report_riskincidence_group');//รายงานความเสี่ยงของกลุ่ม/หน่วยงาน
Route::get('manager_risk/report_unrisk','ManagerriskController@report_unrisk')->name('mrisk.report_unrisk');//รายงานไม่ใช่ความเสี่ยง
Route::get('manager_risk/report_riskdevelop','ManagerriskController@report_riskdevelop')->name('mrisk.report_riskdevelop');//รายงานระบบที่มีการปรับปรุง/พัฒนา
Route::get('manager_risk/report_riskgroupdepatment','ManagerriskController@report_riskgroupdepatment')->name('mrisk.report_riskgroupdepatment');//รายงานกลุ่ม/หน่วยงานที่แก้ไขความเสี่ยง

Route::get('manager_risk/report_riskdepartment_subsub','ManagerriskController@report_riskdepartment_subsub')->name('mrisk.report_riskdepartment_subsub');//รายงานหน่วยงานที่รายงานอุบัติการณ์ความเสี่ยง
Route::post('manager_risk/report_riskdepartment_subsub_search','ManagerriskController@report_riskdepartment_subsub_search')->name('mrisk.report_riskdepartment_subsub_search');
Route::get('manager_risk/report_riskdepartment_subsub_excel','ManagerriskController@report_riskdepartment_subsub_excel')->name('mrisk.report_riskdepartment_subsub_excel');

Route::get('manager_risk/report_riskdepartment_self_subsub','ManagerriskController@report_riskdepartment_self_subsub')->name('mrisk.report_riskdepartment_self_subsub');//รายงานหน่วยงานที่รายงานอุบัติการณ์ความเสี่ยงของตนเอง
Route::post('manager_risk/report_riskdepartment_self_subsub_search','ManagerriskController@report_riskdepartment_self_subsub_search')->name('mrisk.report_riskdepartment_self_subsub_search');
Route::get('manager_risk/report_riskdepartment_self_subsub_excel','ManagerriskController@report_riskdepartment_self_subsub_excel')->name('mrisk.report_riskdepartment_self_subsub_excel');

Route::get('manager_risk/report_riskincidencecategory','ManagerriskController@report_riskincidencecategory')->name('mrisk.report_riskincidencecategory');//รายงานประเภท/ชนิด/สถานที่
Route::get('manager_risk/report_riskincidencelocation','ManagerriskController@report_riskincidencelocation')->name('mrisk.report_riskincidencelocation');//รายงานแหล่งที่มา
Route::get('manager_risk/report_riskdigtime','ManagerriskController@report_riskdigtime')->name('mrisk.report_riskdigtime');//รายงานช่วงเวลา/เวร
Route::get('manager_risk/report_riskdepartment_sub','ManagerriskController@report_riskdepartment_sub')->name('mrisk.report_riskdepartment_sub');//รายงานหน่วยงานที่รายงาน
Route::get('manager_risk/report_risksub','ManagerriskController@report_risksub')->name('mrisk.report_risksub');//รายงานย่อย
Route::get('manager_risk/report_riskdataset_day','ManagerriskController@report_riskdataset_day')->name('mrisk.report_riskdataset_day');//รายงานบันทึกรายวัน
Route::get('manager_risk/report_riskdataset_month','ManagerriskController@report_riskdataset_month')->name('mrisk.report_riskdataset_month');//รายงานบันทึกรายเดือน
Route::get('manager_risk/report_riskdataset_year','ManagerriskController@report_riskdataset_year')->name('mrisk.report_riskdataset_year');//รายงานบันทึกรายปี
Route::get('manager_risk/report_riskimplement','ManagerriskController@report_riskimplement')->name('mrisk.report_riskimplement');//รายงานวธีการแก้ไขเชิงองค์กร

Route::get('manager_risk/risk_evaluate_a','ManagerriskController@risk_evaluate_a')->name('mrisk.risk_evaluate_a');//รายงานการประเมินผลการควบคุมภายใน
Route::get('manager_risk/risk_evaluate_b','ManagerriskController@risk_evaluate_b')->name('mrisk.risk_evaluate_b');//รายงานการประเมินผลการควบคุมภายใน
Route::get('manager_risk/risk_evaluate_pdf','ManagerriskController@risk_evaluate_pdf')->name('mrisk.risk_evaluate_pdf');//รายงานการประเมินผลการควบคุมภายใน // PDF
Route::get('manager_risk/excel_risk_evaluate','ManagerriskController@excel_risk_evaluate')->name('mrisk.excel_risk_evaluate');//รายงานการประเมินผลการควบคุมภายใน // EXcel


Route::get('manager_risk/risk_notify_accept/{id}','ManagerriskController@risk_notify_accept')->name('mrisk.risk_notify_accept'); // ตอบรับ
Route::get('manager_risk/risk_notify_accept_sub/{id}','ManagerriskController@risk_notify_accept_sub')->name('mrisk.risk_notify_accept_sub'); // ตอบรับ Detail
Route::post('manager_risk/risk_notify_accept_sub_save','ManagerriskController@risk_notify_accept_sub_save')->name('mrisk.risk_notify_accept_sub_save'); // ตอบรับ Detail
Route::get('manager_risk/risk_notify_accept_sub_edit/{id}/{idrig}','ManagerriskController@risk_notify_accept_sub_edit')->name('mrisk.risk_notify_accept_sub_edit'); // ตอบรับ Detail
Route::post('manager_risk/risk_notify_accept_sub_update','ManagerriskController@risk_notify_accept_sub_update')->name('mrisk.risk_notify_accept_sub_update'); // ตอบรับ Detail
Route::get('manager_risk/risk_notify_accept_sub_destroy/{id}/{idrig}','ManagerriskController@risk_notify_accept_sub_destroy')->name('mrisk.risk_notify_accept_sub_destroy'); // ตอบรับ Detail

Route::get('manager_risk/risk_notify_repeat/{id}','ManagerriskController@risk_notify_repeat')->name('mrisk.risk_notify_repeat'); // ทบทวน
Route::get('manager_risk/risk_notify_repeat_sub/{id}','ManagerriskController@risk_notify_repeat_sub')->name('mrisk.risk_notify_repeat_sub'); // ทบทวน Detail
Route::post('manager_risk/risk_notify_repeat_sub_save','ManagerriskController@risk_notify_repeat_sub_save')->name('mrisk.risk_notify_repeat_sub_save'); // ตอบรับ Detail
Route::get('manager_risk/risk_notify_repeat_sub_edit/{id}/{idrig}','ManagerriskController@risk_notify_repeat_sub_edit')->name('mrisk.risk_notify_repeat_sub_edit'); // ตอบรับ Detail
Route::post('manager_risk/risk_notify_repeat_sub_update','ManagerriskController@risk_notify_repeat_sub_update')->name('mrisk.risk_notify_repeat_sub_update'); // ตอบรับ Detail
Route::get('manager_risk/risk_notify_repeat_sub_destroy/{id}/{idrig}','ManagerriskController@risk_notify_repeat_sub_destroy')->name('mrisk.risk_notify_repeat_sub_destroy'); // ตอบรับ Detail

Route::get('manager_risk/riskrep_matrixtable','ManagerriskController@riskrep_matrixtable')->name('mrisk.riskrep_matrixtable');//เพิ่มแมททริค
Route::post('manager_risk/riskrep_matrixtableupdate','ManagerriskController@riskrep_matrixtableupdate')->name('mrisk.riskrep_matrixtableupdate');


Route::get('manager_risk/report_riskNRLS','ManagerriskController@report_riskNRLS')->name('mrisk.report_riskNRLS');//รายงานNRLS
Route::post('manager_risk/report_riskNRLS_search','ManagerriskController@report_riskNRLS_search')->name('mrisk.report_riskNRLS_search');
Route::get('manager_risk/report_riskNRLS_excel/{year_id}/{displaydate_bigen}/{displaydate_end}/{search}','ManagerriskController@report_riskNRLS_excel');



//====================ตั้งค่า ==========================//

//------start-----16.06.63-------------------//
Route::get('admin_risk/setupincidence_reportheader','SetupriskController@setupincidence_reportheader')->name('srisk.setupincidence_reportheader');//รายงานโดยใช้
Route::get('admin_risk/setupincidence_reportheader_add','SetupriskController@setupincidence_reportheader_add')->name('srisk.setupincidence_reportheader_add');
Route::post('admin_risk/setupincidence_reportheader_save','SetupriskController@setupincidence_reportheader_save')->name('srisk.setupincidence_reportheader_save');
Route::get('admin_risk/setupincidence_reportheader_edit/{id}','SetupriskController@setupincidence_reportheader_edit')->name('srisk.setupincidence_reportheader_edit');
Route::post('admin_risk/setupincidence_reportheader_update','SetupriskController@setupincidence_reportheader_update')->name('srisk.setupincidence_reportheader_update');
Route::get('admin_risk/setupincidence_reportheader_destroy/{id}','SetupriskController@setupincidence_reportheader_destroy')->name('srisk.setupincidence_reportheader_destroy');

Route::get('admin_risk/setupincidence_report','SetupriskController@setupincidence_report')->name('srisk.setupincidence_report');//การรายงานอุบัติการณ์
Route::get('admin_risk/setupincidence_report_add','SetupriskController@setupincidence_report_add')->name('srisk.setupincidence_report_add');
Route::post('admin_risk/setupincidence_report_save','SetupriskController@setupincidence_report_save')->name('srisk.setupincidence_report_save');
Route::get('admin_risk/setupincidence_report_edit/{id}','SetupriskController@setupincidence_report_edit')->name('srisk.setupincidence_report_edit');
Route::post('admin_risk/setupincidence_report_update','SetupriskController@setupincidence_report_update')->name('srisk.setupincidence_report_update');
Route::get('admin_risk/setupincidence_report_destroy/{id}','SetupriskController@setupincidence_report_destroy')->name('srisk.setupincidence_report_destroy');


Route::get('admin_risk/setupincidence_modify','SetupriskController@setupincidence_modify')->name('srisk.setupincidence_modify');//การแก้ไขอุบัติการณ์
Route::get('admin_risk/setupincidence_modify_add','SetupriskController@setupincidence_modify_add')->name('srisk.setupincidence_modify_add');
Route::post('admin_risk/setupincidence_modify_save','SetupriskController@setupincidence_modify_save')->name('srisk.setupincidence_modify_save');
Route::get('admin_risk/setupincidence_modify_edit/{id}','SetupriskController@setupincidence_modify_edit')->name('srisk.setupincidence_modify_edit');
Route::post('admin_risk/setupincidence_modify_update','SetupriskController@setupincidence_modify_update')->name('srisk.setupincidence_modify_update');
Route::get('admin_risk/setupincidence_modify_destroy/{id}','SetupriskController@setupincidence_modify_destroy')->name('srisk.setupincidence_modify_destroy');

Route::get('admin_risk/setupincidence_modify_leveldepartsub','SetupriskController@setupincidence_modify_leveldepartsub')->name('srisk.setupincidence_modify_leveldepartsub');//ระดับกลุ่ม/หน่วยงานหลักที่แก้ไข
Route::get('admin_risk/setupincidence_modify_leveldepartsub_add','SetupriskController@setupincidence_modify_leveldepartsub_add')->name('srisk.setupincidence_modify_leveldepartsub_add');
Route::post('admin_risk/setupincidence_modify_leveldepartsub_save','SetupriskController@setupincidence_modify_leveldepartsub_save')->name('srisk.setupincidence_modify_leveldepartsub_save');
Route::get('admin_risk/setupincidence_modify_leveldepartsub_edit/{id}','SetupriskController@setupincidence_modify_leveldepartsub_edit')->name('srisk.setupincidence_modify_leveldepartsub_edit');
Route::post('admin_risk/setupincidence_modify_leveldepartsub_update','SetupriskController@setupincidence_modify_leveldepartsub_update')->name('srisk.setupincidence_modify_leveldepartsub_update');
Route::get('admin_risk/setupincidence_modify_leveldepartsub_destroy/{id}','SetupriskController@setupincidence_modify_leveldepartsub_destroy')->name('srisk.setupincidence_modify_leveldepartsub_destroy');

Route::get('admin_risk/setupincidence_modify_departsub','SetupriskController@setupincidence_modify_departsub')->name('srisk.setupincidence_modify_departsub');//กลุ่ม/หน่วยงานหลักที่แก้ไข
Route::get('admin_risk/setupincidence_modify_departsub_add','SetupriskController@setupincidence_modify_departsub_add')->name('srisk.setupincidence_modify_departsub_add');
Route::post('admin_risk/setupincidence_modify_departsub_save','SetupriskController@setupincidence_modify_departsub_save')->name('srisk.setupincidence_modify_departsub_save');
Route::get('admin_risk/setupincidence_modify_departsub_edit/{id}','SetupriskController@setupincidence_modify_departsub_edit')->name('srisk.setupincidence_modify_departsub_edit');
Route::post('admin_risk/setupincidence_modify_departsub_update','SetupriskController@setupincidence_modify_departsub_update')->name('srisk.setupincidence_modify_departsub_update');
Route::get('admin_risk/setupincidence_modify_departsub_destroy/{id}','SetupriskController@setupincidence_modify_departsub_destroy')->name('srisk.setupincidence_modify_departsub_destroy');

//------end-----16.06.63-------------------//

//------start-----18.06.63-------------------//

Route::get('admin_risk/setupincidence_category_sub','SetupriskController@setupincidence_category_sub')->name('srisk.setupincidence_category_sub');//ประเภทอุบัติการณ์ความเสี่ยงย่อย
Route::get('admin_risk/setupincidence_category_sub_add','SetupriskController@setupincidence_category_sub_add')->name('srisk.setupincidence_category_sub_add');
Route::post('admin_risk/setupincidence_category_sub_save','SetupriskController@setupincidence_category_sub_save')->name('srisk.setupincidence_category_sub_save');
Route::get('admin_risk/setupincidence_category_sub_edit/{id}','SetupriskController@setupincidence_category_sub_edit')->name('srisk.setupincidence_category_sub_edit');
Route::post('admin_risk/setupincidence_category_sub_update','SetupriskController@setupincidence_category_sub_update')->name('srisk.setupincidence_category_sub_update');
Route::get('admin_risk/setupincidence_category_sub_destroy/{id}','SetupriskController@setupincidence_category_sub_destroy')->name('srisk.setupincidence_category_sub_destroy');


Route::get('admin_risk/setupincidence_levelbegin','SetupriskController@setupincidence_levelbegin')->name('srisk.setupincidence_levelbegin');//อันดับของการเกิด
Route::get('admin_risk/setupincidence_levelbegin_add','SetupriskController@setupincidence_levelbegin_add')->name('srisk.setupincidence_levelbegin_add');
Route::post('admin_risk/setupincidence_levelbegin_save','SetupriskController@setupincidence_levelbegin_save')->name('srisk.setupincidence_levelbegin_save');
Route::get('admin_risk/setupincidence_levelbegin_edit/{id}','SetupriskController@setupincidence_levelbegin_edit')->name('srisk.setupincidence_levelbegin_edit');
Route::post('admin_risk/setupincidence_levelbegin_update','SetupriskController@setupincidence_levelbegin_update')->name('srisk.setupincidence_levelbegin_update');
Route::get('admin_risk/setupincidence_levelbegin_destroy/{id}','SetupriskController@setupincidence_levelbegin_destroy')->name('srisk.setupincidence_levelbegin_destroy');

//------end-----18.06.63-------------------//

Route::get('admin_risk/setupincidence_grouplocation','SetupriskController@setupincidence_grouplocation')->name('srisk.setupincidence_grouplocation');//ประเภทสถานที่
Route::get('admin_risk/setupincidence_grouplocation_add','SetupriskController@setupincidence_grouplocation_add')->name('srisk.setupincidence_grouplocation_add');
Route::post('admin_risk/setupincidence_grouplocation_save','SetupriskController@setupincidence_grouplocation_save')->name('srisk.setupincidence_grouplocation_save');
Route::get('admin_risk/setupincidence_grouplocation_edit/{id}','SetupriskController@setupincidence_grouplocation_edit')->name('srisk.setupincidence_grouplocation_edit');
Route::post('admin_risk/setupincidence_grouplocation_update','SetupriskController@setupincidence_grouplocation_update')->name('srisk.setupincidence_grouplocation_update');
Route::get('admin_risk/setupincidence_grouplocation_destroy/{id}','SetupriskController@setupincidence_grouplocation_destroy')->name('srisk.setupincidence_grouplocation_destroy');

Route::get('admin_risk/setupincidence_typelocation','SetupriskController@setupincidence_typelocation')->name('srisk.setupincidence_typelocation');//ชนิดสถานที่
Route::get('admin_risk/setupincidence_typelocation_add','SetupriskController@setupincidence_typelocation_add')->name('srisk.setupincidence_typelocation_add');
Route::post('admin_risk/setupincidence_typelocation_save','SetupriskController@setupincidence_typelocation_save')->name('srisk.setupincidence_typelocation_save');
Route::get('admin_risk/setupincidence_typelocation_edit/{id}','SetupriskController@setupincidence_typelocation_edit')->name('srisk.setupincidence_typelocation_edit');
Route::post('admin_risk/setupincidence_typelocation_update','SetupriskController@setupincidence_typelocation_update')->name('srisk.setupincidence_typelocation_update');
Route::get('admin_risk/setupincidence_typelocation_destroy/{id}','SetupriskController@setupincidence_typelocation_destroy')->name('srisk.setupincidence_typelocation_destroy');

Route::get('admin_risk/setupincidence_status','SetupriskController@setupincidence_status')->name('srisk.setupincidence_status');//สถานะความเสี่ยง
Route::get('admin_risk/setupincidence_status_add','SetupriskController@setupincidence_status_add')->name('srisk.setupincidence_status_add');
Route::post('admin_risk/setupincidence_status_save','SetupriskController@setupincidence_status_save')->name('srisk.setupincidence_status_save');
Route::get('admin_risk/setupincidence_status_edit/{id}','SetupriskController@setupincidence_status_edit')->name('srisk.setupincidence_status_edit');
Route::post('admin_risk/setupincidence_status_update','SetupriskController@setupincidence_status_update')->name('srisk.setupincidence_status_update');
Route::get('admin_risk/setupincidence_status_destroy/{id}','SetupriskController@setupincidence_status_destroy')->name('srisk.setupincidence_status_destroy');

Route::get('admin_risk/setupincidence_group','SetupriskController@setupincidence_group')->name('srisk.setupincidence_group');//กลุ่มอุบัติการณ์ความเสี่ยง
Route::get('admin_risk/setupincidence_group_add','SetupriskController@setupincidence_group_add')->name('srisk.setupincidence_group_add');
Route::post('admin_risk/setupincidence_group_save','SetupriskController@setupincidence_group_save')->name('srisk.setupincidence_group_save');
Route::get('admin_risk/setupincidence_group_edit/{id}','SetupriskController@setupincidence_group_edit')->name('srisk.setupincidence_group_edit');
Route::post('admin_risk/setupincidence_group_update','SetupriskController@setupincidence_group_update')->name('srisk.setupincidence_group_update');
Route::get('admin_risk/setupincidence_group_destroy/{id}','SetupriskController@setupincidence_group_destroy')->name('srisk.setupincidence_group_destroy');

Route::get('admin_risk/setupin_category','SetupriskController@setupin_category')->name('srisk.setupin_category');//หมวดอุบัติการณ์ความเสี่ยง  19.9.63
Route::get('admin_risk/setupin_category_add','SetupriskController@setupin_category_add')->name('srisk.setupin_category_add');
Route::post('admin_risk/setupin_category_save','SetupriskController@setupin_category_save')->name('srisk.setupin_category_save');
Route::get('admin_risk/setupin_category_edit/{id}','SetupriskController@setupin_category_edit')->name('srisk.setupin_category_edit');
Route::post('admin_risk/setupin_category_update','SetupriskController@setupin_category_update')->name('srisk.setupin_category_update');
Route::get('admin_risk/setupin_category_destroy/{id}','SetupriskController@setupin_category_destroy')->name('srisk.setupin_category_destroy');

Route::get('admin_risk/setupincidence_category','SetupriskController@setupincidence_category')->name('srisk.setupincidence_category');//ประเภทอุบัติการณ์ความเสี่ยง
Route::get('admin_risk/setupincidence_category_add','SetupriskController@setupincidence_category_add')->name('srisk.setupincidence_category_add');
Route::post('admin_risk/setupincidence_category_save','SetupriskController@setupincidence_category_save')->name('srisk.setupincidence_category_save');
Route::get('admin_risk/setupincidence_category_edit/{id}','SetupriskController@setupincidence_category_edit')->name('srisk.setupincidence_category_edit');
Route::post('admin_risk/setupincidence_category_update','SetupriskController@setupincidence_category_update')->name('srisk.setupincidence_category_update');
Route::get('admin_risk/setupincidence_category_destroy/{id}','SetupriskController@setupincidence_category_destroy')->name('srisk.setupincidence_category_destroy');

Route::get('admin_risk/setupincidence_setting','SetupriskController@setupincidence_setting')->name('srisk.setupincidence_setting');//อุบัติการณ์ความเสี่ยง
Route::get('admin_risk/setupincidence_setting_add','SetupriskController@setupincidence_setting_add')->name('srisk.setupincidence_setting_add');
Route::post('admin_risk/setupincidence_setting_save','SetupriskController@setupincidence_setting_save')->name('srisk.setupincidence_setting_save');
Route::get('admin_risk/setupincidence_setting_edit/{id}','SetupriskController@setupincidence_setting_edit')->name('srisk.setupincidence_setting_edit');
Route::post('admin_risk/setupincidence_setting_update','SetupriskController@setupincidence_setting_update')->name('srisk.setupincidence_setting_update');
Route::get('admin_risk/setupincidence_setting_destroy/{id}','SetupriskController@setupincidence_setting_destroy')->name('srisk.setupincidence_setting_destroy');

Route::get('admin_risk/setupincidence_groupdepart','SetupriskController@setupincidence_groupdepart')->name('srisk.setupincidence_groupdepart');//กลุ่ม
Route::get('admin_risk/setupincidence_groupdepart_add','SetupriskController@setupincidence_groupdepart_add')->name('srisk.setupincidence_groupdepart_add');
Route::post('admin_risk/setupincidence_groupdepart_save','SetupriskController@setupincidence_groupdepart_save')->name('srisk.setupincidence_groupdepart_save');
Route::get('admin_risk/setupincidence_groupdepart_edit/{id}','SetupriskController@setupincidence_groupdepart_edit')->name('srisk.setupincidence_groupdepart_edit');
Route::post('admin_risk/setupincidence_groupdepart_update','SetupriskController@setupincidence_groupdepart_update')->name('srisk.setupincidence_groupdepart_update');
Route::get('admin_risk/setupincidence_groupdepart_destroy/{id}','SetupriskController@setupincidence_groupdepart_destroy')->name('srisk.setupincidence_groupdepart_destroy');

Route::get('admin_risk/setupincidence_depart','SetupriskController@setupincidence_depart')->name('srisk.setupincidence_depart');//หน่วยงาน
Route::get('admin_risk/setupincidence_depart_add','SetupriskController@setupincidence_depart_add')->name('srisk.setupincidence_depart_add');
Route::post('admin_risk/setupincidence_depart_save','SetupriskController@setupincidence_depart_save')->name('srisk.setupincidence_depart_save');
Route::get('admin_risk/setupincidence_depart_edit/{id}','SetupriskController@setupincidence_depart_edit')->name('srisk.setupincidence_depart_edit');
Route::post('admin_risk/setupincidence_depart_update','SetupriskController@setupincidence_depart_update')->name('srisk.setupincidence_depart_update');
Route::get('admin_risk/setupincidence_depart_destroy/{id}','SetupriskController@setupincidence_depart_destroy')->name('srisk.setupincidence_depart_destroy');


Route::get('admin_risk/setupincidence_typedepart','SetupriskController@setupincidence_typedepart')->name('srisk.setupincidence_typedepart');//ประเภทหน่วยงาน
Route::get('admin_risk/setupincidence_typedepart_add','SetupriskController@setupincidence_typedepart_add')->name('srisk.setupincidence_typedepart_add');
Route::post('admin_risk/setupincidence_typedepart_save','SetupriskController@setupincidence_typedepart_save')->name('srisk.setupincidence_typedepart_save');
Route::get('admin_risk/setupincidence_typedepart_edit/{id}','SetupriskController@setupincidence_typedepart_edit')->name('srisk.setupincidence_typedepart_edit');
Route::post('admin_risk/setupincidence_typedepart_update','SetupriskController@setupincidence_typedepart_update')->name('srisk.setupincidence_typedepart_update');
Route::get('admin_risk/setupincidence_typedepart_destroy/{id}','SetupriskController@setupincidence_typedepart_destroy')->name('srisk.setupincidence_typedepart_destroy');

Route::get('admin_risk/setupincidence_operate','SetupriskController@setupincidence_operate')->name('srisk.setupincidence_operate');//ตารางเวร
Route::get('admin_risk/setupincidence_operate_add','SetupriskController@setupincidence_operate_add')->name('srisk.setupincidence_operate_add');
Route::post('admin_risk/setupincidence_operate_save','SetupriskController@setupincidence_operate_save')->name('srisk.setupincidence_operate_save');
Route::get('admin_risk/setupincidence_operate_edit/{id}','SetupriskController@setupincidence_operate_edit')->name('srisk.setupincidence_operate_edit');
Route::post('admin_risk/setupincidence_operate_update','SetupriskController@setupincidence_operate_update')->name('srisk.setupincidence_operate_update');
Route::get('admin_risk/setupincidence_operate_destroy/{id}','SetupriskController@setupincidence_operate_destroy')->name('srisk.setupincidence_operate_destroy');

Route::get('admin_risk/setupincidence_origindepart','SetupriskController@setupincidence_origindepart')->name('srisk.setupincidence_origindepart');//ชนิดสถานที่
Route::get('admin_risk/setupincidence_origindepart_add','SetupriskController@setupincidence_origindepart_add')->name('srisk.setupincidence_origindepart_add');
Route::post('admin_risk/setupincidence_origindepart_save','SetupriskController@setupincidence_origindepart_save')->name('srisk.setupincidence_origindepart_save');
Route::get('admin_risk/setupincidence_origindepart_edit/{id}','SetupriskController@setupincidence_origindepart_edit')->name('srisk.setupincidence_origindepart_edit');
Route::post('admin_risk/setupincidence_origindepart_update','SetupriskController@setupincidence_origindepart_update')->name('srisk.setupincidence_origindepart_update');
Route::get('admin_risk/setupincidence_origindepart_destroy/{id}','SetupriskController@setupincidence_origindepart_destroy')->name('srisk.setupincidence_origindepart_destroy');


Route::get('admin_risk/setupincidence_groupuser','SetupriskController@setupincidence_groupuser')->name('srisk.setupincidence_groupuser');//กลุ่มผู้ใช้
Route::get('admin_risk/setupincidence_groupuser_add','SetupriskController@setupincidence_groupuser_add')->name('srisk.setupincidence_groupuser_add');
Route::post('admin_risk/setupincidence_groupuser_save','SetupriskController@setupincidence_groupuser_save')->name('srisk.setupincidence_groupuser_save');
Route::get('admin_risk/setupincidence_groupuser_edit/{id}','SetupriskController@setupincidence_groupuser_edit')->name('srisk.setupincidence_groupuser_edit');
Route::post('admin_risk/setupincidence_groupuser_update','SetupriskController@setupincidence_groupuser_update')->name('srisk.setupincidence_groupuser_update');
Route::get('admin_risk/setupincidence_groupuser_destroy/{id}','SetupriskController@setupincidence_groupuser_destroy')->name('srisk.setupincidence_groupuser_destroy');

Route::get('admin_risk/setupincidence_level','SetupriskController@setupincidence_level')->name('srisk.setupincidence_level');//ระดับความรุนแรง
Route::get('admin_risk/setupincidence_level_add','SetupriskController@setupincidence_level_add')->name('srisk.setupincidence_level_add');
Route::post('admin_risk/setupincidence_level_save','SetupriskController@setupincidence_level_save')->name('srisk.setupincidence_level_save');
Route::get('admin_risk/setupincidence_level_edit/{id}','SetupriskController@setupincidence_level_edit')->name('srisk.setupincidence_level_edit');
Route::post('admin_risk/setupincidence_level_update','SetupriskController@setupincidence_level_update')->name('srisk.setupincidence_level_update');
Route::get('admin_risk/setupincidence_level_destroy/{id}','SetupriskController@setupincidence_level_destroy')->name('srisk.setupincidence_level_destroy');

Route::get('admin_risk/setupincidence_location','SetupriskController@setupincidence_location')->name('srisk.setupincidence_location');//แหล่งที่มา
Route::get('admin_risk/setupincidence_location_add','SetupriskController@setupincidence_location_add')->name('srisk.setupincidence_location_add');
Route::post('admin_risk/setupincidence_location_save','SetupriskController@setupincidence_location_save')->name('srisk.setupincidence_location_save');
Route::get('admin_risk/setupincidence_location_edit/{id}','SetupriskController@setupincidence_location_edit')->name('srisk.setupincidence_location_edit');
Route::post('admin_risk/setupincidence_location_update','SetupriskController@setupincidence_location_update')->name('srisk.setupincidence_location_update');
Route::get('admin_risk/setupincidence_location_destroy/{id}','SetupriskController@setupincidence_location_destroy')->name('srisk.setupincidence_location_destroy');

Route::get('admin_risk/setupincidence_origin','SetupriskController@setupincidence_origin')->name('srisk.setupincidence_origin');//สถานที่เกิดเหตุ
Route::get('admin_risk/setupincidence_origin_add','SetupriskController@setupincidence_origin_add')->name('srisk.setupincidence_origin_add');
Route::post('admin_risk/setupincidence_origin_save','SetupriskController@setupincidence_origin_save')->name('srisk.setupincidence_origin_save');
Route::get('admin_risk/setupincidence_origin_edit/{id}','SetupriskController@setupincidence_origin_edit')->name('srisk.setupincidence_origin_edit');
Route::post('admin_risk/setupincidence_origin_update','SetupriskController@setupincidence_origin_update')->name('srisk.setupincidence_origin_update');
Route::get('admin_risk/setupincidence_origin_destroy/{id}','SetupriskController@setupincidence_origin_destroy')->name('srisk.setupincidence_origin_destroy');

Route::get('admin_risk/setupincidence_listdataset','SetupriskController@setupincidence_listdataset')->name('srisk.setupincidence_listdataset');//รายการชุดข้อมูลกลางของระบบ
Route::get('admin_risk/setupincidence_listdataset_add','SetupriskController@setupincidence_listdataset_add')->name('srisk.setupincidence_listdataset_add');
Route::post('admin_risk/setupincidence_listdataset_save','SetupriskController@setupincidence_listdataset_save')->name('srisk.setupincidence_listdataset_save');
Route::get('admin_risk/setupincidence_listdataset_edit/{id}','SetupriskController@setupincidence_listdataset_edit')->name('srisk.setupincidence_listdataset_edit');
Route::post('admin_risk/setupincidence_listdataset_update','SetupriskController@setupincidence_listdataset_update')->name('srisk.setupincidence_listdataset_update');
Route::get('admin_risk/setupincidence_listdataset_destroy/{id}','SetupriskController@setupincidence_listdataset_destroy')->name('srisk.setupincidence_listdataset_destroy');


Route::get('admin_risk/setupincidence_sub','SetupriskController@setupincidence_sub')->name('srisk.setupincidence_sub');//อุบัติการณ์ความเสี่ยงย่อย
Route::get('admin_risk/setupincidence_sub_add','SetupriskController@setupincidence_sub_add')->name('srisk.setupincidence_sub_add');
Route::post('admin_risk/setupincidence_sub_save','SetupriskController@setupincidence_sub_save')->name('srisk.setupincidence_sub_save');
Route::get('admin_risk/setupincidence_sub_edit/{id}','SetupriskController@setupincidence_sub_edit')->name('srisk.setupincidence_sub_edit');
Route::post('admin_risk/setupincidence_sub_update','SetupriskController@setupincidence_sub_update')->name('srisk.setupincidence_sub_update');
Route::get('admin_risk/setupincidence_sub_destroy/{id}','SetupriskController@setupincidence_sub_destroy')->name('srisk.setupincidence_sub_destroy');


Route::get('admin_risk/setupincidence_category_depart','SetupriskController@setupincidence_category_depart')->name('srisk.setupincidence_category_depart');//ประเภทหน่วยงาน
Route::get('admin_risk/setupincidence_category_depart_add','SetupriskController@setupincidence_category_depart_add')->name('srisk.setupincidence_category_depart_add');
Route::post('admin_risk/setupincidence_category_depart_save','SetupriskController@setupincidence_category_depart_save')->name('srisk.setupincidence_category_depart_save');
Route::get('admin_risk/setupincidence_category_depart_edit/{id}','SetupriskController@setupincidence_category_depart_edit')->name('srisk.setupincidence_category_depart_edit');
Route::post('admin_risk/setupincidence_category_depart_update','SetupriskController@setupincidence_category_depart_update')->name('srisk.setupincidence_category_depart_update');
Route::get('admin_risk/setupincidence_category_depart_destroy/{id}','SetupriskController@setupincidence_category_depart_destroy')->name('srisk.setupincidence_category_depart_destroy');





//======================================CRM==============================================
Route::get('manager_crm/dashboard','ManagercrmController@dashboard')->name('mcrm.dashboard');
Route::post('manager_crm/dashboardsearch','ManagercrmController@dashboardsearch')->name('mcrm.dashboardsearch');
Route::get('manager_crm/detail','ManagercrmController@detail')->name('mcrm.detail');
Route::get('manager_crm/congrat/export_pdfcongrat/{idref}', 'ManagercrmController@pdfcongrat')->name('mcrm.pdfcongrat'); //ฟร์อม3 ใบอนุโมทนาบัตร



//===========table ===donate_person=====================//
Route::match(['get','post'],'manager_crm/persondonate','ManagercrmController@persondonate')->name('mcrm.persondonate');
Route::get('manager_crm/persondonate_add','ManagercrmController@persondonate_add')->name('mcrm.persondonate_add');
Route::post('manager_crm/persondonate_save','ManagercrmController@persondonate_save')->name('mcrm.persondonate_save');
Route::get('manager_crm/persondonate_edit/{id}','ManagercrmController@persondonate_edit')->name('mcrm.persondonate_edit');
Route::post('manager_crm/persondonate_update','ManagercrmController@persondonate_update')->name('mcrm.persondonate_update');
Route::get('manager_crm/persondonate_cancel/{id}','ManagercrmController@persondonate_cancel')->name('mcrm.persondonate_cancel');
Route::post('manager_crm/persondonate_savecancel','ManagercrmController@persondonate_savecancel')->name('mcrm.persondonate_savecancel');
Route::post('manager_crm/persondonatesearch','ManagercrmController@persondonatesearch')->name('mcrm.persondonatesearch');


Route::match(['get','post'],'manager_crm/persondonate_list','ManagercrmController@persondonate_list')->name('mcrm.persondonate_list');
Route::get('manager_crm/persondonate_list_edit/{id}','ManagercrmController@persondonate_list_edit')->name('mcrm.persondonate_list_edit');
Route::post('manager_crm/persondonate_list_update','ManagercrmController@persondonate_list_update')->name('mcrm.persondonate_list_update');
Route::post('manager_crm/persondonatelistsearch','ManagercrmController@persondonatelistsearch')->name('mcrm.persondonatelistsearch');

//===========table ===donate_person_sub=====================//

Route::get('manager_crm/detaildonate/{id}','ManagercrmController@detaildonate')->name('mcrm.detaildonate');
Route::get('manager_crm/detaildonate_add/{id}','ManagercrmController@detaildonate_add')->name('mcrm.detaildonate_add');
Route::post('manager_crm/detaildonate_save','ManagercrmController@detaildonate_save')->name('mcrm.detaildonate_save');
Route::get('manager_crm/detaildonate_edit/{id}/{idref}','ManagercrmController@detaildonate_edit')->name('mcrm.detaildonate_edit');
Route::post('manager_crm/detaildonate_update','ManagercrmController@detaildonate_update')->name('mcrm.detaildonate_update');
Route::get('manager_crm/detaildonate_destroy/{idref}/{id}','ManagercrmController@detaildonate_destroy')->name('mcrm.detaildonate_destroy');



Route::get('manager_crm/openform','ManagercrmController@openform')->name('mcrm.openform'); //เปิดใช้ฟอร์ม
Route::get('manager_crm/openform_add','ManagercrmController@openform_add')->name('mcrm.openform_add');
Route::post('manager_crm/openform_save','ManagercrmController@openform_save')->name('mcrm.openform_save');
Route::get('manager_crm/openform_edit/{id}','ManagercrmController@openform_edit')->name('mcrm.openform_edit');
Route::post('manager_crm/openform_update','ManagercrmController@openform_update')->name('mcrm.openform_update');
Route::get('manager_crm/openform_destroy/{id}','ManagercrmController@openform_destroy');
Route::post('manager_car/openform_switchactive','ManagercrmController@openform_switchactive')->name('mcrm.openform_switchactive');



Route::get('manager_crm/donationwealth','ManagercrmController@donationwealth')->name('mcrm.donationwealth'); //ประเภททรัพ
Route::get('manager_crm/donationwealth_add','ManagercrmController@donationwealth_add')->name('mcrm.donationwealth_add');
Route::post('manager_crm/savedonationwealth','ManagercrmController@savedonationwealth')->name('mcrm.savedonationwealth');
Route::get('manager_crm/donationwealth_edit/{id}','ManagercrmController@donationwealth_edit')->name('mcrm.donationwealth_edit');
Route::post('manager_crm/updatedonationwealth','ManagercrmController@updatedonationwealth')->name('mcrm.updatedonationwealth');
Route::get('manager_crm/donationwealth/destroy/{id}','ManagercrmController@destroydonationwealth');

Route::get('manager_crm/donationunit','ManagercrmController@donationunit')->name('mcrm.donationunit');//ประเภทหน่วย
Route::get('manager_crm/donationunit_add','ManagercrmController@donationunit_add')->name('mcrm.donationunit_add');
Route::post('manager_crm/savedonationunit','ManagercrmController@savedonationunit')->name('mcrm.savedonationunit');
Route::get('manager_crm/donationunit_edit/{id}','ManagercrmController@donationunit_edit')->name('mcrm.donationunit_edit');
Route::post('manager_crm/updatedonationunit','ManagercrmController@updatedonationunit')->name('mcrm.updatedonationunit');
Route::get('manager_crm/donationunit/destroy/{id}','ManagercrmController@destroydonationunit');

Route::get('manager_crm/donationtopic','ManagercrmController@donationtopic')->name('mcrm.donationtopic');//หัวข้อการรับ
Route::get('manager_crm/donationtopic_add','ManagercrmController@donationtopic_add')->name('mcrm.donationtopic_add');
Route::post('manager_crm/savedonationtopic','ManagercrmController@savedonationtopic')->name('mcrm.savedonationtopic');
Route::get('manager_crm/donationtopic_edit/{id}','ManagercrmController@donationtopic_edit')->name('mcrm.donationtopic_edit');
Route::post('manager_crm/updatedonationtopic','ManagercrmController@updatedonationtopic')->name('mcrm.updatedonationtopic');
Route::get('manager_crm/donationtopic/destroy/{id}','ManagercrmController@destroydonationtopic');
Route::post('manager_crm/donationtopicsearch','ManagercrmController@donationtopicsearch')->name('mcrm.donationtopicsearch');


Route::get('manager_crm/donation_fund','ManagercrmController@donation_fund')->name('mcrm.donation_fund');//กองทุน
Route::get('manager_crm/donation_fund_add','ManagercrmController@donation_fund_add')->name('mcrm.donation_fund_add');
Route::post('manager_crm/donation_fund_save','ManagercrmController@donation_fund_save')->name('mcrm.donation_fund_save');
Route::get('manager_crm/donation_fund_edit/{id}','ManagercrmController@donation_fund_edit')->name('mcrm.donation_fund_edit');
Route::post('manager_crm/donation_fund_update','ManagercrmController@donation_fund_update')->name('mcrm.donation_fund_update');
Route::get('manager_crm/donation_fund_destroy/{id}','ManagercrmController@donation_fund_destroy');
Route::post('manager_crm/donation_fund_search','ManagercrmController@donation_fund_search')->name('mcrm.donation_fund_search');

Route::get('manager_crm/donation_fund_addajax','ManagercrmController@donation_fund_addajax')->name('mcrm.donation_fund_addajax'); ///// เพิ่มตรงจุดที่ไม่มี
Route::get('manager_crm/donation_fund_addtypeajax','ManagercrmController@donation_fund_addtypeajax')->name('mcrm.donation_fund_addtypeajax'); ///// เพิ่มตรงจุดที่ไม่มี
Route::get('manager_crm/donation_fund_addunitajax','t@donation_fund_addunitajax')->name('mcrm.donation_fund_addunitajax'); ///// เพิ่มตรงจุดที่ไม่มี
//===================== ซักฟอก ================================//



Route::get('manager_launder/launder_stickersmall','ManagerlaunderController@launder_stickersmall')->name('launder.launder_stickersmall');//ชุด1
Route::get('manager_launder/launder_stickerlarge','ManagerlaunderController@launder_stickerlarge')->name('launder.launder_stickerlarge');//
Route::get('manager_launder/launder_stickersmall2','ManagerlaunderController@launder_stickersmall2')->name('launder.launder_stickersmall2');//ชุด2
Route::get('manager_launder/launder_stickerlarge2','ManagerlaunderController@launder_stickerlarge2')->name('launder.launder_stickerlarge2');//
Route::get('manager_launder/launder_stickerset1','ManagerlaunderController@launder_stickerset1')->name('launder.launder_stickerset1');//
Route::get('manager_launder/launder_stickerset2','ManagerlaunderController@launder_stickerset2')->name('launder.launder_stickerset2');//
Route::get('manager_launder/launder_stickernight','ManagerlaunderController@launder_stickernight')->name('launder.launder_stickernight');//คืนสติกเกอร์
Route::get('manager_launder/launder_pay','ManagerlaunderController@launder_pay')->name('launder.launder_pay');//จ่ายของ
Route::get('manager_launder/launder_recieve','ManagerlaunderController@launder_recieve')->name('launder.launder_recieve');//รับคืน
Route::get('manager_launder/launder_dispose','ManagerlaunderController@launder_dispose')->name('launder.launder_dispose');//จำหน่ายทิ้ง
Route::get('manager_launder/launder_static','ManagerlaunderController@launder_static')->name('launder.launder_static');//สถิติ


Route::match(['get','post'],'manager_launder/launder_checkstock','ManagerlaunderController@launder_checkstock')->name('launder.launder_checkstock');//เช็คสต็อค
Route::post('manager_launder/launder_checkstocksearch','ManagerlaunderController@launder_checkstocksearch')->name('launder.launder_checkstocksearch');
Route::get('manager_launder/launder_checkstock_sub/{idref}','ManagerlaunderController@launder_checkstock_sub')->name('launder.launder_checkstock_sub');


Route::match(['get','post'],'manager_launder/launder_checktreasury','ManagerlaunderController@launder_checktreasury')->name('launder.launder_checktreasury');//เช็คคลังย่อย
Route::post('manager_launder/launder_checktreasurysearch','ManagerlaunderController@launder_checktreasurysearch')->name('launder.launder_checktreasurysearch');
Route::get('manager_launder/launder_checktreasury_sub/{idtype}/{iddep}','ManagerlaunderController@launder_checktreasury_sub')->name('launder.launder_checktreasury_sub');


Route::get('manager_launder/launder_checkday','ManagerlaunderController@launder_checkday')->name('launder.launder_checkday');//ตรวจสอบวัน

Route::get('manager_launder/launder_stickercreate','ManagerlaunderController@launder_stickercreate')->name('launder.launder_stickercreate');//หน้าจัดทำสติกเกอร์1
Route::get('manager_launder/launder_stickerselect','ManagerlaunderController@launder_stickerselect')->name('launder.launder_stickerselect');//หน้าเลือกขนาด1

Route::get('manager_launder/launder_stickercreate2','ManagerlaunderController@launder_stickercreate2')->name('launder.launder_stickercreate2');//หน้าจัดทำสติกเกอร์2
Route::get('manager_launder/launder_stickerselect2','ManagerlaunderController@launder_stickerselect2')->name('launder.launder_stickerselect2');//หน้าเลือกขนาด2
//================================================================



Route::get('manager_launder/launder_getre','ManagerlaunderController@launder_getre')->name('launder.launder_getre');
Route::get('manager_launder/launder_getre_dep/{type}','ManagerlaunderController@launder_getre_dep')->name('launder.launder_getre_dep');

Route::match(['get','post'],'manager_launder/launder_getback','ManagerlaunderController@launder_getback')->name('launder.launder_getback');//หน้าจัดรับผ้า
Route::post('manager_launder/launder_getbacksearch','ManagerlaunderController@launder_getbacksearch')->name('launder.launder_getbacksearch');
Route::get('manager_launder/laundergetback_add/{type}/{iddep}','ManagerlaunderController@laundergetback_add')->name('launder.laundergetback_add');
Route::post('manager_launder/laundergetback_update','ManagerlaunderController@laundergetback_update')->name('launder.laundergetback_update');
Route::get('manager_launder/laundergetback_edit/{type}/{iddep}/{idref_l}','ManagerlaunderController@laundergetback_edit')->name('launder.laundergetback_edit');
Route::post('manager_launder/laundergetback_update_edit','ManagerlaunderController@laundergetback_update_edit')->name('launder.laundergetback_update_edit');

Route::match(['get','post'],'manager_launder/launder_check','ManagerlaunderController@launder_check')->name('launder.launder_check');//หน้าตรวจรับผ้า
Route::post('manager_launder/launder_checksearch','ManagerlaunderController@launder_checksearch')->name('launder.launder_checksearch');
Route::get('manager_launder/laundercheck_add/{type}/{iddep}','ManagerlaunderController@laundercheck_add')->name('launder.laundercheck_add');
Route::post('manager_launder/laundercheck_update','ManagerlaunderController@laundercheck_update')->name('launder.laundercheck_update');

Route::get('manager_launder/launder_check_edit/{idref}','ManagerlaunderController@launder_check_edit')->name('launder.launder_check_edit');
Route::post('manager_launder/laundercheck_updateedit','ManagerlaunderController@laundercheck_updateedit')->name('launder.laundercheck_updateedit');

Route::match(['get','post'],'manager_launder/launder_disburse','ManagerlaunderController@launder_disburse')->name('launder.launder_disburse');//หน้าเบิกจ่ายผ้า
Route::post('manager_launder/launder_disbursesearch','ManagerlaunderController@launder_disbursesearch')->name('launder.launder_disbursesearch');

Route::get('manager_launder/launder_withdraw','ManagerlaunderController@launder_withdraw')->name('launder.launder_withdraw');
Route::post('manager_launder/launder_withdraw_launder_save','ManagerlaunderController@launder_withdraw_launder_save')->name('launder.launder_withdraw_launder_save');

Route::get('manager_launder/launderdisburse_add/{type}/{iddep}','ManagerlaunderController@launderdisburse_add')->name('launder.launderdisburse_add');
Route::post('manager_launder/launderdisburse_update','ManagerlaunderController@launderdisburse_update')->name('launder.launderdisburse_update');

Route::get('manager_launder/launder_list','ManagerlaunderController@launder_list')->name('launder.launder_list');

Route::match(['get','post'],'manager_launder/launder_send','ManagerlaunderController@launder_send')->name('launder.launder_send');
Route::post('manager_launder/launder_sendsearch','ManagerlaunderController@launder_sendsearch')->name('launder.launder_sendsearch');
Route::get('manager_launder/launder_send_edit/{idref}','ManagerlaunderController@launder_send_edit')->name('launder.launder_send_edit');
Route::post('manager_launder/launderdisburse_updateedit','ManagerlaunderController@launderdisburse_updateedit')->name('launder.launderdisburse_updateedit');
//====================ตั้งค่า=================

Route::get('manager_launder/launder_clothingtype','ManagerlaunderController@launder_clothingtype')->name('launder.launder_clothingtype');//ตั้งค่าเสื้อผ้า
Route::get('manager_launder/launder_clothingtype_add','ManagerlaunderController@launder_clothingtype_add')->name('launder.launder_clothingtype_add');
Route::post('manager_launder/launder_clothingtype_save','ManagerlaunderController@launder_clothingtype_save')->name('launder.launder_clothingtype_save');
Route::get('manager_launder/launder_clothingtype_edit/{idref}','ManagerlaunderController@launder_clothingtype_edit')->name('launder.launder_clothingtype_edit');
Route::post('manager_launder/launder_clothingtype_update','ManagerlaunderController@launder_clothingtype_update')->name('launder.launder_clothingtype_update');
Route::get('manager_launder/launder_clothingtype_destroy/{idref}','ManagerlaunderController@launder_clothingtype_destroy')->name('launder.launder_clothingtype_destroy');



Route::get('manager_launder/launder_dep','ManagerlaunderController@launder_dep')->name('launder.launder_dep');//เลือกหน่วยงานที่จะส่ง
Route::get('manager_launder/launder_dep_add','ManagerlaunderController@launder_dep_add')->name('launder.launder_dep_add');
Route::post('manager_launder/launder_dep_save','ManagerlaunderController@launder_dep_save')->name('launder.launder_dep_save');
Route::get('manager_launder/launder_dep_destroy/{idref}','ManagerlaunderController@launder_dep_destroy')->name('launder.launder_dep_destroy');

Route::get('manager_launder/launder_dep_edit/{idref}','ManagerlaunderController@launder_dep_edit')->name('launder.launder_dep_edit');
Route::post('manager_launder/launder_dep_update','ManagerlaunderController@launder_dep_update')->name('launder.launder_dep_update');

Route::get('manager_launder/launder_dep_clothingtype/{idref}','ManagerlaunderController@launder_dep_clothingtype')->name('launder.launder_dep_clothingtype');
Route::post('manager_launder/launder_dep_clothingtype_update','ManagerlaunderController@launder_dep_clothingtype_update')->name('launder.launder_dep_clothingtype_update');
//======================================งานจ่ายกลาง==============================================
/// หน้าบ้าน
// ..............
/// จบหน้าบ้าน

/// dashboard
Route::match(['get','post'],'manager_mpay/dashboard','ManagermpayController@dashboard')->name('mpay.dashboard');
Route::get('manager_mpay/mpay_stickerprint','ManagermpayController@mpay_stickerprint')->name('mpay.mpay_stickerprint');
// --------------------------------- service ------------------------------------
// ___ service stock ___________________
Route::get('manager_mpay/mpay_service_stock','ManagermpayController@mpay_service_stock')->name('mpay.mpay_service_stock');
// ________________________________________________ end service stock ___________________
// ___ service receive ___________________
Route::match(['get','post'],'manager_mpay/mpay_service_receive','ManagermpayController@mpay_service_receive')->name('mpay.mpay_service_receive');
Route::post('manager_mpay/ajax_mpay_service_receive_detail','ManagermpayController@ajax_mpay_service_receive_detail')->name('mpay.ajax_mpay_service_receive_detail');
Route::get('manager_mpay/mpay_service_receive_cancel/{receive_id}','ManagermpayController@mpay_service_receive_cancel')->name('mpay.mpay_service_receive_cancel');
Route::get('manager_mpay/mpay_service_receive_add','ManagermpayController@mpay_service_receive_add')->name('mpay.mpay_service_receive_add');
Route::post('manager_mpay/receive_update_select','ManagermpayController@receive_update_select')->name('mpay.receive_update_select');
Route::post('manager_mpay/mpay_service_receive_save','ManagermpayController@mpay_service_receive_save')->name('mpay.mpay_service_receive_save');
// ________________________________________________ end service receive ___________________
// ___ service sticker print ___________________
Route::get('manager_mpay/mpay_service_stickerprint','ManagermpayController@mpay_service_stickerprint')->name('mpay.mpay_service_stickerprint');
Route::get('manager_mpay/mpay_service_stickerprint_cancel/{product_id}','ManagermpayController@mpay_service_stickerprint_cancel')->name('mpay.mpay_service_stickerprint_cancel');
Route::get('manager_mpay/mpay_service_stickerprint_add','ManagermpayController@mpay_service_stickerprint_add')->name('mpay.mpay_service_stickerprint_add');
Route::post('manager_mpay/equpment_update_list','ManagermpayController@equpment_update_list')->name('mpay.equpment_update_list');
Route::post('manager_mpay/add_list_production','ManagermpayController@add_list_production')->name('mpay.add_list_production');
Route::post('manager_mpay/mpay_service_stickerprint_save','ManagermpayController@mpay_service_stickerprint_save')->name('mpay.mpay_service_stickerprint_save');

// ทดสอบปริ้น
Route::get('manager_mpay/test_print','ManagermpayController@test_print')->name('mpay.test_print');
// ________________________________________________ end  sticker print ___________________
// ___ service export ___________________
Route::match(['post','get'],'manager_mpay/mpay_service_export','ManagermpayController@mpay_service_export')->name('mpay.mpay_service_export');
Route::post('manager_mpay/ajax_mpay_service_export_detail','ManagermpayController@ajax_mpay_service_export_detail')->name('mpay.ajax_mpay_service_export_detail');
Route::get('manager_mpay/mpay_service_export_cancel/{export_id}','ManagermpayController@mpay_service_export_cancel')->name('mpay.mpay_service_export_cancel');
Route::get('manager_mpay/mpay_service_export_add','ManagermpayController@mpay_service_export_add')->name('mpay.mpay_service_export_add');
Route::post('manager_mpay/mpay_service_export_save','ManagermpayController@mpay_service_export_save')->name('mpay.mpay_service_export_save');
Route::post('manager_mpay/export_update_list','ManagermpayController@export_update_list')->name('mpay.export_update_list');
Route::post('manager_mpay/add_list_export','ManagermpayController@add_list_export')->name('mpay.add_list_export');
// ________________________________________________ end export ___________________
// ___ service defective ___________________
Route::match(['get','post'],'manager_mpay/mpay_service_defective','ManagermpayController@mpay_service_defective')->name('mpay.mpay_service_defective');
Route::post('manager_mpay/ajax_mpay_service_defective_detail','ManagermpayController@ajax_mpay_service_defective_detail')->name('mpay.ajax_mpay_service_defective_detail');
Route::get('manager_mpay/mpay_service_defective_add','ManagermpayController@mpay_service_defective_add')->name('mpay.mpay_service_defective_add');
Route::post('manager_mpay/mpay_service_defective_save','ManagermpayController@mpay_service_defective_save')->name('mpay.mpay_service_defective_save');
Route::get('manager_mpay/mpay_service_defective_cancel/{defective_id}','ManagermpayController@mpay_service_defective_cancel')->name('mpay.mpay_service_defective_cancel');
// ________________________________________________ end defective ___________________
// ___ service maintenance machine ___________________
Route::get('manager_mpay/mpay_maintenance_machine','ManagermpayController@mpay_maintenance_machine')->name('mpay.mpay_maintenance_machine');
Route::get('manager_mpay/mpay_maintenance_machine_add','ManagermpayController@mpay_maintenance_machine_add')->name('mpay.mpay_maintenance_machine_add');
Route::post('manager_mpay/mpay_maintenance_machine_save','ManagermpayController@mpay_maintenance_machine_save')->name('mpay.mpay_maintenance_machine_save');
Route::get('manager_mpay/mpay_maintenance_machine_edit/{maintenc_id}','ManagermpayController@mpay_maintenance_machine_edit')->name('mpay.mpay_maintenance_machine_edit');
Route::post('manager_mpay/mpay_maintenance_machine_update','ManagermpayController@mpay_maintenance_machine_update')->name('mpay.mpay_maintenance_machine_update');
Route::get('manager_mpay/mpay_maintenance_machine_delete/{maintenc_id}','ManagermpayController@mpay_maintenance_machine_delete')->name('mpay.mpay_maintenance_machine_delete');
// ________________________________________________ end maintenance machine ___________________
/// report
Route::match(['get','post'],'manager_mpay/mpay_report_stickerprint','ManagermpayController@mpay_report_stickerprint')->name('mpay.mpay_report_stickerprint');
Route::match(['get','post'],'manager_mpay/mpay_report_receive','ManagermpayController@mpay_report_receive')->name('mpay.mpay_report_receive');
Route::match(['get','post'],'manager_mpay/mpay_report_export','ManagermpayController@mpay_report_export')->name('mpay.mpay_report_export');
Route::match(['get','post'],'manager_mpay/mpay_report_trash','ManagermpayController@mpay_report_trash')->name('mpay.mpay_report_trash');
// --------------------------------- end service ------------------------------------
// --------------------------------- show ------------------------------------
Route::get('manager_mpay/mpay_show_quota','ManagermpayController@mpay_show_quota')->name('mpay.mpay_show_quota');
// --------------------------------- end show ------------------------------------

// --------------------------------- settings ------------------------------------
// ___ setting printer and stickers ___________________
Route::get('manager_mpay/mpay_setting_defaultsticker','ManagermpayController@mpay_setting_defaultsticker')->name('mpay.mpay_setting_defaultsticker');
Route::post('manager_mpay/ajax_mpay_setting_defaultsticker_updateopen','ManagermpayController@ajax_mpay_setting_defaultsticker_updateopen')->name('mpay.ajax_mpay_setting_defaultsticker_updateopen');
Route::get('manager_mpay/mpay_setting_defaultsticker_add','ManagermpayController@mpay_setting_defaultsticker_add')->name('mpay.mpay_setting_defaultsticker_add');
Route::post('manager_mpay/mpay_setting_defaultsticker_save','ManagermpayController@mpay_setting_defaultsticker_save')->name('mpay.mpay_setting_defaultsticker_save');
Route::get('manager_mpay/mpay_setting_defaultsticker_edit/{id}','ManagermpayController@mpay_setting_defaultsticker_edit')->name('mpay.mpay_setting_defaultsticker_edit');
Route::post('manager_mpay/mpay_setting_defaultsticker_update','ManagermpayController@mpay_setting_defaultsticker_update')->name('mpay.mpay_setting_defaultsticker_update');
Route::get('manager_mpay/mpay_setting_defaultsticker_example/{id}','ManagermpayController@mpay_setting_defaultsticker_example')->name('mpay.mpay_setting_defaultsticker_example');
Route::get('manager_mpay/mpay_setting_defaultsticker_delete/{id}','ManagermpayController@mpay_setting_defaultsticker_delete')->name('mpay.mpay_setting_defaultsticker_delete');

// ________________________________________________ end setting printer and stickers ___________________
// ___ setting department sub sub ___________________
Route::get('manager_mpay/mpay_setting_department_sub_sub','ManagermpayController@mpay_setting_department_sub_sub')->name('mpay.mpay_setting_department_sub_sub');
Route::post('manager_mpay/ajax_mpay_setting_department_sub_sub_updateopen','ManagermpayController@ajax_mpay_setting_department_sub_sub_updateopen')->name('mpay.ajax_mpay_setting_department_sub_sub_updateopen');
Route::get('manager_mpay/mpay_setting_department_sub_sub_add','ManagermpayController@mpay_setting_department_sub_sub_add')->name('mpay.mpay_setting_department_sub_sub_add');
Route::post('manager_mpay/mpay_setting_department_sub_sub_save','ManagermpayController@mpay_setting_department_sub_sub_save')->name('mpay.mpay_setting_department_sub_sub_save');
Route::get('manager_mpay/mpay_setting_department_sub_sub_edit/{dep_subsub_id}','ManagermpayController@mpay_setting_department_sub_sub_edit')->name('mpay.mpay_setting_department_sub_sub_edit');
Route::get('manager_mpay/mpay_setting_department_sub_sub_delete/{dep_subsub_id}','ManagermpayController@mpay_setting_department_sub_sub_delete')->name('mpay.mpay_setting_department_sub_sub_delete');
Route::post('manager_mpay/mpay_setting_department_sub_sub_update','ManagermpayController@mpay_setting_department_sub_sub_update')->name('mpay.mpay_setting_department_sub_sub_update');
// ________________________________________________ end setting department sub sub ___________________
// ___ default setting department sub sub id ___________________
Route::get('manager_mpay/mpay_setting_default_department_sub_sub_id','ManagermpayController@mpay_setting_default_department_sub_sub_id')->name('mpay.mpay_setting_default_department_sub_sub_id');
Route::post('manager_mpay/mpay_setting_default_department_sub_sub_id_update','ManagermpayController@mpay_setting_default_department_sub_sub_id_update')->name('mpay.mpay_setting_default_department_sub_sub_id_update');
// ________________________________________________ end setting default department sub sub id ___________________
// ___ setting type clean machine ___________________
Route::get('manager_mpay/mpay_setting_typecleanmachine','ManagermpayController@mpay_setting_typecleanmachine')->name('mpay.mpay_setting_typecleanmachine');
Route::post('manager_mpay/ajax_mpay_setting_typecleanmachine_update_active','ManagermpayController@ajax_mpay_setting_typecleanmachine_update_active')->name('mpay.ajax_mpay_setting_typecleanmachine_update_active');
Route::get('manager_mpay/mpay_setting_typecleanmachine_add','ManagermpayController@mpay_setting_typecleanmachine_add')->name('mpay.mpay_setting_typecleanmachine_add');
Route::post('manager_mpay/mpay_setting_typecleanmachine_save','ManagermpayController@mpay_setting_typecleanmachine_save')->name('mpay.mpay_setting_typecleanmachine_save');
Route::get('manager_mpay/mpay_setting_typecleanmachine_edit/{typemach_id}','ManagermpayController@mpay_setting_typecleanmachine_edit')->name('mpay.mpay_setting_typecleanmachine_edit');
Route::post('manager_mpay/mpay_setting_typecleanmachine_update','ManagermpayController@mpay_setting_typecleanmachine_update')->name('mpay.mpay_setting_typecleanmachine_update');
Route::get('manager_mpay/mpay_setting_typecleanmachine_delete/{typemach_id}','ManagermpayController@mpay_setting_typecleanmachine_delete')->name('mpay.mpay_setting_typecleanmachine_delete');
// ________________________________________________ end type clean machine ___________________
// ___ setting clean machine ___________________
Route::get('manager_mpay/mpay_setting_cleanmachine','ManagermpayController@mpay_setting_cleanmachine')->name('mpay.mpay_setting_cleanmachine');
Route::post('manager_mpay/ajax_mpay_setting_cleanmachine_update_active','ManagermpayController@ajax_mpay_setting_cleanmachine_update_active')->name('mpay.ajax_mpay_setting_cleanmachine_update_active');
Route::get('manager_mpay/mpay_setting_cleanmachine_add','ManagermpayController@mpay_setting_cleanmachine_add')->name('mpay.mpay_setting_cleanmachine_add');
Route::post('manager_mpay/mpay_setting_cleanmachine_save','ManagermpayController@mpay_setting_cleanmachine_save')->name('mpay.mpay_setting_cleanmachine_save');
Route::get('manager_mpay/mpay_setting_cleanmachine_edit/{mach_id}','ManagermpayController@mpay_setting_cleanmachine_edit')->name('mpay.mpay_setting_cleanmachine_edit');
Route::post('manager_mpay/mpay_setting_cleanmachine_update','ManagermpayController@mpay_setting_cleanmachine_update')->name('mpay.mpay_setting_cleanmachine_update');
Route::get('manager_mpay/mpay_setting_cleanmachine_delete/{mach_id}','ManagermpayController@mpay_setting_cleanmachine_delete')->name('mpay.mpay_setting_cleanmachine_delete');
// ________________________________________________ end type clean machine ___________________
// ___ setting type medequipment ___________________
Route::get('manager_mpay/mpay_setting_typemedequipment','ManagermpayController@mpay_setting_typemedequipment')->name('mpay.mpay_setting_typemedequipment');
Route::post('manager_mpay/ajax_mpay_setting_typemedequipment_update_active','ManagermpayController@ajax_mpay_setting_typemedequipment_update_active')->name('mpay.ajax_mpay_setting_typemedequipment_update_active');
Route::get('manager_mpay/mpay_setting_typemedequipment_add','ManagermpayController@mpay_setting_typemedequipment_add')->name('mpay.mpay_setting_typemedequipment_add');
Route::post('manager_mpay/mpay_setting_typemedequipment_save','ManagermpayController@mpay_setting_typemedequipment_save')->name('mpay.mpay_setting_typemedequipment_save');
Route::get('manager_mpay/mpay_setting_typemedequipment_edit/{typemed_id}','ManagermpayController@mpay_setting_typemedequipment_edit')->name('mpay.mpay_setting_typemedequipment_edit');
Route::post('manager_mpay/mpay_setting_typemedequipment_update','ManagermpayController@mpay_setting_typemedequipment_update')->name('mpay.mpay_setting_typemedequipment_update');
Route::get('manager_mpay/mpay_setting_typemedequipment_delete/{typemed_id}','ManagermpayController@mpay_setting_typemedequipment_delete')->name('mpay.mpay_setting_typemedequipment_delete');
// ________________________________________________ end type medequipment ___________________
// ___ setting unit ___________________
Route::get('manager_mpay/mpay_setting_unit','ManagermpayController@mpay_setting_unit')->name('mpay.mpay_setting_unit');
Route::post('manager_mpay/ajax_mpay_setting_unit_update_active','ManagermpayController@ajax_mpay_setting_unit_update_active')->name('mpay.ajax_mpay_setting_unit_update_active');
Route::get('manager_mpay/mpay_setting_unit_add','ManagermpayController@mpay_setting_unit_add')->name('mpay.mpay_setting_unit_add');
Route::post('manager_mpay/mpay_setting_unit_save','ManagermpayController@mpay_setting_unit_save')->name('mpay.mpay_setting_unit_save');
Route::get('manager_mpay/mpay_setting_unit_edit/{unit_id}','ManagermpayController@mpay_setting_unit_edit')->name('mpay.mpay_setting_unit_edit');
Route::post('manager_mpay/mpay_setting_unit_update','ManagermpayController@mpay_setting_unit_update')->name('mpay.mpay_setting_unit_update');
Route::get('manager_mpay/mpay_setting_unit_delete/{unit_id}','ManagermpayController@mpay_setting_unit_delete')->name('mpay.mpay_setting_unit_delete');
// ________________________________________________ end unit ___________________
// ___ setting subset med ___________________
Route::get('manager_mpay/mpay_setting_subset_medequipment','ManagermpayController@mpay_setting_subset_medequipment')->name('mpay.mpay_setting_subset_medequipment');
Route::post('manager_mpay/ajax_mpay_setting_subset_medequipment_update_active','ManagermpayController@ajax_mpay_setting_subset_medequipment_update_active')->name('mpay.ajax_mpay_setting_subset_medequipment_update_active');
Route::get('manager_mpay/mpay_setting_subset_medequipment_add','ManagermpayController@mpay_setting_subset_medequipment_add')->name('mpay.mpay_setting_subset_medequipment_add');
Route::post('manager_mpay/mpay_setting_subset_medequipment_save','ManagermpayController@mpay_setting_subset_medequipment_save')->name('mpay.mpay_setting_subset_medequipment_save');
Route::get('manager_mpay/mpay_setting_subset_medequipment_edit/{subset_id}','ManagermpayController@mpay_setting_subset_medequipment_edit')->name('mpay.mpay_setting_subset_medequipment_edit');
Route::post('manager_mpay/mpay_setting_subset_medequipment_update','ManagermpayController@mpay_setting_subset_medequipment_update')->name('mpay.mpay_setting_subset_medequipment_update');
Route::get('manager_mpay/mpay_setting_subset_medequipment_delete/{subset_id}','ManagermpayController@mpay_setting_subset_medequipment_delete')->name('mpay.mpay_setting_subset_medequipment_delete');
// ________________________________________________ end subset med ___________________
// ___ setting med ___________________
Route::get('manager_mpay/mpay_setting_medequipment','ManagermpayController@mpay_setting_medequipment')->name('mpay.mpay_setting_medequipment');
Route::post('manager_mpay/ajax_mpay_list_medequipment','ManagermpayController@ajax_mpay_list_medequipment')->name('mpay.ajax_mpay_list_medequipment');
Route::post('manager_mpay/ajax_mpay_setting_medequipment_update_active','ManagermpayController@ajax_mpay_setting_medequipment_update_active')->name('mpay.ajax_mpay_setting_medequipment_update_active');
Route::get('manager_mpay/mpay_setting_medequipment_add','ManagermpayController@mpay_setting_medequipment_add')->name('mpay.mpay_setting_medequipment_add');
Route::post('manager_mpay/mpay_setting_medequipment_save','ManagermpayController@mpay_setting_medequipment_save')->name('mpay.mpay_setting_medequipment_save');
Route::get('manager_mpay/mpay_setting_medequipment_edit/{set_id}','ManagermpayController@mpay_setting_medequipment_edit')->name('mpay.mpay_setting_medequipment_edit');
Route::post('manager_mpay/mpay_setting_medequipment_update','ManagermpayController@mpay_setting_medequipment_update')->name('mpay.mpay_setting_medequipment_update');
Route::get('manager_mpay/mpay_setting_medequipment_delete/{set_id}','ManagermpayController@mpay_setting_medequipment_delete')->name('mpay.mpay_setting_medequipment_delete'); 
// ________________________________________________ end med ___________________
// ___ setting quota ___________________
Route::match(['get','post'],'manager_mpay/mpay_setting_quota','ManagermpayController@mpay_setting_quota')->name('mpay.mpay_setting_quota');
Route::post('manager_mpay/ajax_mpay_setting_quota_update_quantity','ManagermpayController@ajax_mpay_setting_quota_update_quantity')->name('mpay.ajax_mpay_setting_quota_update_quantity');
Route::post('manager_mpay/ajax_mpay_setting_quota_update_active','ManagermpayController@ajax_mpay_setting_quota_update_active')->name('mpay.ajax_mpay_setting_quota_update_active');
Route::get('manager_mpay/mpay_setting_quota_add','ManagermpayController@mpay_setting_quota_add')->name('mpay.mpay_setting_quota_add');
Route::post('manager_mpay/mpay_setting_quota_save','ManagermpayController@mpay_setting_quota_save')->name('mpay.mpay_setting_quota_save');
Route::get('manager_mpay/mpay_setting_quota_edit/{quota_id}','ManagermpayController@mpay_setting_quota_edit')->name('mpay.mpay_setting_quota_edit');
Route::post('manager_mpay/mpay_setting_quota_update','ManagermpayController@mpay_setting_quota_update')->name('mpay.mpay_setting_quota_update');
Route::get('manager_mpay/mpay_setting_quota_delete/{quota_id}','ManagermpayController@mpay_setting_quota_delete')->name('mpay.mpay_setting_quota_delete'); 
// ________________________________________________ end quota ___________________

//========================== end งานจ่ายกลาง ===============================// 

Route::get('qrcode_blade', function () {
    return view('qrCode');
});
Route::get('checkmodule', function () {
    return view('checkmodule');
});

Route::get('manager_warehouse/chectunitpiceupdate','ManagerwarehouseController@chectunitpiceupdate')->name('mpay.chectunitpiceupdate');

///////  PDF   //////////
Route::get('manager_repairnomal/pdf_normal/{idref}', 'ManagerrepairnomalController@pdf_normal')->name('manager_repairnomal.pdf_normal');//ใบแจ้งซ่อมทั่วไป
Route::get('manager_repaircom/pdf_com/{idref}', 'ManagerrepaircomController@pdf_com')->name('manager_repaircom.pdf_com');//ใบแจ้งซ่อมคอมพิวเตอร์
Route::get('manager_repairmedical/pdf_medical/{idref}', 'ManagerrepairmedicalController@pdf_medical')->name('manager_repairmedical.pdf_medical');//ใบซ่อมเครื่องมือแพท์

/////Import Excel 
Route::match(['get','post'],'hrdperson_excel','AdminPersonController@hrdperson_excel')->name('hrdperson_excel');
Route::match(['get','post'],'hrdperson_excel_save','AdminPersonController@hrdperson_excel_save')->name('hrdperson_excel_save');


Route::match(['get','post'],'importusers','ImportExcelController@importusers')->name('importusers');
Route::match(['get','post'],'supplies','ImportExcelController@supplies')->name('supplies');

Route::match(['get','post'],'supplies_excel','ImportExcelController@supplies_excel')->name('supplies_excel');
Route::match(['get','post'],'asset_excel','ImportExcelController@asset_excel')->name('asset_excel');
Route::match(['get','post'],'asset_article','ImportExcelController@asset_article')->name('asset_article');
Route::match(['get','post'],'leaveover_excel','ImportExcelController@leaveover_excel')->name('leaveover_excel');
Route::match(['get','post'],'leaveover','ImportExcelController@leaveover')->name('leaveover');

//============ คู่มือการสอน Youtube  Admin ======================//

// Route::get(uri:'/',  action:'App\Http\Controllers\YouTubeController@index')->name(name:'index');
// Route::get(uri:'/results',  action:'App\Http\Controllers\YouTubeController@results')->name(name:'results');
// Route::get(uri:'/watch',  action:'App\Http\Controllers\YouTubeController@watch')->name(name:'watch');
Route::get('youtube/youtubeindex','YouTubeController@youtubeindex')->name('youtubeindex');
Route::get('youtube/youtuberesults','YouTubeController@youtuberesults')->name('youtuberesults');
Route::get('youtube/youtubewatch','YouTubeController@youtubewatch')->name('youtubewatch');
Route::get('youtube/youtubegleave','YouTubeController@youtubegleave')->name('youtubegleave');

Route::get('youtube/uploadpdf','YouTubeController@uploadpdf')->name('you.uploadpdf');
Route::post('youtube/uploadpdf_file','YouTubeController@uploadpdf_file')->name('you.uploadpdf_file');


//============ คู่มือการสอน Youtube User ======================//

Route::get('youtube/youtubeuser_personcheckinindex/{iduser}','YouTubeController@youtubeuser_personcheckinindex')->name('video.youtubeuser_personcheckinindex');
Route::get('youtube/youtubeuser_gleaveindex/{iduser}','YouTubeController@youtubeuser_gleaveindex')->name('video.youtubeuser_gleaveindex');
Route::get('youtube/youtubeuser_persondevindex/{iduser}','YouTubeController@youtubeuser_persondevindex')->name('video.youtubeuser_persondevindex');
Route::get('youtube/youtubeuser_compensationindex/{iduser}','YouTubeController@youtubeuser_compensationindex')->name('video.youtubeuser_compensationindex');
Route::get('youtube/youtubeuser_guesthouseindex/{iduser}','YouTubeController@youtubeuser_guesthouseindex')->name('video.youtubeuser_guesthouseindex');


Route::get('youtube/youtubeuser_warehouse/{iduser}','YouTubeController@youtubeuser_warehouse')->name('video.youtubeuser_warehouse');
//============ ฟอร์มต่างฯ ======================//


Route::get('formpdf/pdfcongrat_10999/{id}','FormpdfController@pdfcongrat_10999')->name('form.pdfcongrat_10999');
Route::get('formpdf/pdf3_10999/{id}','FormpdfController@pdf3_10999')->name('form.pdf3_10999');
Route::get('formpdf/personperdev_10999/{id}/{iduser}','FormpdfController@personperdev_10999')->name('form.personperdev_10999');

Route::get('formpdf/pdf3_general_10999/{id}','FormpdfController@pdf3_general_10999')->name('form.pdf3_general_10999');
Route::get('formpdf/pdf-repair/{id}', 'FormpdfController@pdfRepair')->name('pdf.repair');
Route::get('formpdf/pdf-pdfRepaircom/{id}', 'FormpdfController@pdfRepaircom')->name('pdf.pdfRepaircom');
Route::get('formpdf/pdf-pdfRepaircommedical/{id}', 'FormpdfController@pdfRepaircommedical')->name('pdf.pdfRepaircommedical');


Route::get('formpdf/pdf3_11120/{id}','FormpdfController@pdf3_11120')->name('form.pdf3_11120');
Route::get('formpdf/pdf3_12251/{id}','FormpdfController@pdf3_12251')->name('form.pdf3_12251');



//=================********ฟอร์มคลังใบเบิก******========================================

Route::get('formpdf/warehouse_function','FormpdfController@warehouse_function')->name('form.warehouse_function');

Route::get('formpdf/warehouse_function_add','FormpdfController@warehouse_function_add')->name('form.warehouse_function_add');
Route::post('formpdf/warehouse_function_save','FormpdfController@warehouse_function_save')->name('form.warehouse_function_save');
Route::get('formpdf/warehouse_function_edit/{id}','FormpdfController@warehouse_function_edit')->name('form.warehouse_function_edit');
Route::post('formpdf/warehouse_function_update','FormpdfController@warehouse_function_update')->name('form.warehouse_function_update');
Route::get('formpdf/warehouse_function_destroy/{id}','FormpdfController@warehouse_function_destroy');
Route::match(['get','post'],'manager_car/openform_switchactive','FormpdfController@openform_switchactive')->name('form.openform_switchactive');


//เร้าฟรอมเฉพาะ
Route::get('formpdf/warehouse_11120/{id}/{iduser}','FormpdfController@warehouse_11120')->name('form.warehouse_11120');

//============ web_meta_data ======================//
Route::post('web_meta_data/update_value_by_name','Web_meta_data_Controller@updateValueByName');


//============ e-learning ======================//
Route::get('e_learning/dashboard','ElearningController@dashboard');
Route::get('e_learning/information_group','ElearningController@information_group');
Route::get('e_learning/information_points','ElearningController@information_points');
Route::post('e_learning/information_points_search','ElearningController@information_points_search')->name('information_points_search');

Route::get('e_learning/information_lesson/{id}','ElearningController@information_lesson');
Route::get('e_learning/lesson_detail/{id}','ElearningController@lesson_detail');

Route::get('e_learning/lesson_pre_exam/{id}','ElearningController@lesson_pre_exam');
Route::post('e_learning/lesson_pre_exam/save/{id}','ElearningController@save_lesson_pre_exam');


Route::get('e_learning/lesson_video/{id}','ElearningController@lesson_video');

Route::get('e_learning/lesson_post_exam/{id}','ElearningController@lesson_post_exam');
Route::post('e_learning/lesson_post_exam/save/{id}','ElearningController@save_lesson_post_exam');

Route::get('e_learning/manage_group','ElearningController@manage_group')->name('manage_lesson_group');
Route::post('e_learning/manage_group/save','ElearningController@savelessongroup')->name('save_lessongroup'); //save lesson group
Route::get('e_learning/manage_group/detail/{id}','ElearningController@detaillessongroup');
Route::get('e_learning/manage_group/edit/{id}','ElearningController@editlessongroup');
Route::post('e_learning/manage_group/update','ElearningController@updatelessongroup')->name('update_lessongroup'); //update lesson group
Route::get('e_learning/manage_group/destroy/{id}','ElearningController@destroylessongroup');
Route::get('e_learning/manage_group/switch_lessongroup','ElearningController@switchlessongroup')->name('switch_lessongroup'); //switch_lessongroup

Route::get('e_learning/manage_lesson','ElearningController@manage_lesson')->name('manage_lesson');
Route::post('e_learning/manage_lesson/save','ElearningController@savelesson')->name('save_lesson'); //save lesson
Route::get('e_learning/manage_lesson/switchlesson','ElearningController@switchlesson')->name('switch_lesson'); //switch lesson
Route::get('e_learning/manage_lesson/edit/{id}','ElearningController@editlesson');
Route::post('e_learning/manage_lesson/update','ElearningController@updatelesson')->name('update_lesson'); //update lesson group

Route::get('e_learning/manage_exam','ElearningController@manage_exam')->name('manage_exam');
Route::post('e_learning/manage_exam/save','ElearningController@saveexamseries')->name('save_exam_series'); //save exam_series
Route::post('e_learning/manage_exam/update','ElearningController@updateexamseries')->name('update_exam_series'); //update exam_series
Route::get('e_learning/manage_exam/switch_examseries','ElearningController@switchexamseries')->name('switch_exams_series'); //switch exams_series

Route::post('e_learning/manage_exam/save_question/{id}','ElearningController@savequestion')->name('save_question'); //save exam_question
Route::get('e_learning/manage_exam/detail_question/{id}','ElearningController@detailquestion')->name('detail_question'); //detail exam_series
Route::get('e_learning/manage_exam/edit_question/{id}','ElearningController@editquestion'); //edit exam_series
Route::post('e_learning/manage_exam/update_question/{id}','ElearningController@updatequestion')->name('update_question'); //update exam_question
Route::get('e_learning/manage_exam/switch_question','ElearningController@switchquestion')->name('switch_exams_question'); //switch exam_question


Route::post('e_learning/manage_exam/save_choice/{id}','ElearningController@savechoice')->name('save_choice'); //save exam_choice
Route::get('e_learning/manage_exam/detail_choice/{id}','ElearningController@detailchoice')->name('detail_choice'); //detail exam_choice
Route::post('e_learning/manage_exam/update_choice/{id}','ElearningController@updatechoice')->name('update_choice'); //update exam_choice
Route::get('e_learning/manage_exam/switch_choice','ElearningController@switchchoice')->name('switch_exams_choice'); //switch exam_choice




Route::get('e_learning/information_report','ElearningController@information_report');
Route::post('e_learning/information_report_search','ElearningController@information_report_search')->name('information_report_search');



//============ web_happy_data ======================//
Route::get('person_happynet/insert_modal_day_user','Happy_Net_Controller@insert_modal_day_user')->name('happy.insert_modal');
Route::get('person_happynet/dashboard_Happy_Net','Happy_Net_Controller@Happy_Net')->name('Happy_Net');

Route::post('person_happynet/dashboardsearch','Happy_Net_Controller@dashboardsearch')->name('happy.dashboardsearch');
Route::get('person_happynet/send_user_Happy_Net_Search','Happy_Net_Controller@send_user_search')->name('happy.send_user_search');
Route::get('person_happynet/send_user_Happy_Net','Happy_Net_Controller@send_user')->name('happy.send_user');

Route::get('person_happynet/rank_coin_Happy_Net','Happy_Net_Controller@rank_coin')->name('happy.rank_coin');
Route::get('person_happynet/rank_ques_Happy_Net','Happy_Net_Controller@rank_ques')->name('happy.rank_ques');
Route::get('person_happynet/rank_ans_Happy_Net','Happy_Net_Controller@rank_ans')->name('happy.rank_ans');

// ประวัติงาน
Route::get('person_happynet/history_send_user_Happy_Net','Happy_Net_Controller@history_send_user')->name('happy.history_send_user');
Route::get('person_happynet/historycon_send_user_Happy_Net/{id}','Happy_Net_Controller@historycon_send_user')->name('happy.historycon_send_user');
Route::get('person_happynet/history_get_user_Happy_Net','Happy_Net_Controller@history_get_user')->name('happy.history_get_user');
Route::get('person_happynet/historycon_get_user_Happy_Net/{id}','Happy_Net_Controller@historycon_get_user')->name('happy.historycon_get_user');

// คำถามปัญหา
Route::get('person_happynet/get_user_Happy_Net','Happy_Net_Controller@get_user')->name('happy.get_user');

Route::get('person_happynet/send_user_problem_Happy_Net/{id}','Happy_Net_Controller@send_user_problem')->name('happy.send_user_problem');
Route::post('person_happynet/save_send_user_problem_Happy_Net','Happy_Net_Controller@save_send_user_problem')->name('happy.save_send_user_problem');

Route::get('person_happynet/edit_problem_Happy_Net/{id}','Happy_Net_Controller@edit_problem')->name('happy.edit_problem');
Route::post('person_happynet/up_problem_Happy_Net','Happy_Net_Controller@up_problem')->name('happy.up_problem');

Route::get('person_happynet/submit_problem_view_Happy_Net/{id}','Happy_Net_Controller@submit_problem_view')->name('happy.submit_problem_view');
Route::post('person_happynet/submit_problem_Happy_Net','Happy_Net_Controller@submit_problem')->name('happy.submit_problem');
Route::post('person_happynet/submit_problem_Happy_Nets','Happy_Net_Controller@submit_problems')->name('happy.submit_problems');

// 

// Route::get('person_happynet/like_Happy_Net','Happy_Net_Controller@like')->name('happy.like');


// คำตอบของแต่ละคำถามปัญหา
Route::get('person_happynet/respond_Happy_Net/{id}','Happy_Net_Controller@respond')->name('happy.respond');
Route::post('person_happynet/respond_ans_Happy_Net','Happy_Net_Controller@respond_ans')->name('happy.respond_ans');
Route::get('person_happynet/respond_ans_get_Happy_Net/{id}','Happy_Net_Controller@respond_ans_get')->name('happy.respond_ans_get');
Route::post('person_happynet/respond_ans_gets_Happy_Net','Happy_Net_Controller@respond_ans_get_Happy_Nets')->name('happy.respond_ans_gets');

// คำชม
// Route::get('person_happynet/send_user_Happy_Net','Happy_Net_Controller@send_user')->name('send_user');
Route::get('person_happynet/send_user_id_Happy_Net/{id}','Happy_Net_Controller@send_user_id')->name('happy.send_user_id');
Route::post('person_happynet/save_send_user_Happy_Net','Happy_Net_Controller@save_send_user')->name('happy.save_send_user');
Route::get('person_happynet/edit_send_user_id_Happy_Net/{id}','Happy_Net_Controller@edit_send_user_id')->name('happy.edit_send_user_id');
Route::post('person_happynet/up_send_user_id_Happy_Net','Happy_Net_Controller@up_send_user_id')->name('happy.up_send_user_id');

// 

// คอยน์
Route::get('person_happynet/get_question_dashboard_Happy_Net','Happy_Net_Controller@get_question_dashboard')->name('happy.get_question_dashboard');

// 

// คำถาม

Route::get('person_happynet/question_dashboard_Happy_Net','Happy_Net_Controller@question_dashboard')->name('happy.question_dashboard');
Route::post('person_happynet/save_question_dashboard_Happy_Net','Happy_Net_Controller@save_question_dashboard')->name('happy.save_question_dashboard');

Route::get('person_happynet/inseartdata','Happy_Net_Controller@inseartdata')->name('happy.inseartdata'); // คำตอบใช่ ไม่ใช่
Route::get('person_happynet/sum_question_dashboard_Happy_Net','Happy_Net_Controller@sum_question_dashboard')->name('happy.sum_question_dashboard');


// 

//ออเดอร์
Route::get('person_happynet/order_Happy_Net','Happy_Net_Controller@order')->name('happy.order');
Route::get('person_happynet/con_order_Happy_Net/{id}','Happy_Net_Controller@con_order')->name('happy.con_order');
Route::post('person_happynet/con_up_order_Happy_Net','Happy_Net_Controller@con_up')->name('happy.con_up');
Route::get('person_happynet/content_order_Happy_Net/{id}','Happy_Net_Controller@content_order')->name('happy.content_order');
Route::get('person_happynet/Econ_order_Happy_Net/{id}','Happy_Net_Controller@Econ_order')->name('happy.Econ_order');
Route::post('person_happynet/Ucon_up_order_Happy_Net','Happy_Net_Controller@Ucon_up')->name('happy.Ucon_up');
// 



// หน้าแสดงสินค้า
Route::get('person_happynet/reward_Happy_Net','Happy_Net_Controller@reward')->name('happy.reward');
Route::get('person_happynet/reward_content_Happy_Net/{id}','Happy_Net_Controller@reward_content')->name('happy.reward_content');
Route::post('person_happynet/send_reward_content_Happy_Net','Happy_Net_Controller@send_reward')->name('happy.send_reward');
// end หน้าแสดงสินค้า

// ส่วนตั้งค่า

// หมวดหมู่คำถาม
Route::get('person_happynet/Equestion_group_Happy_Net','Happy_Net_Controller@Equestion_group')->name('happy.Equestion_group');
Route::get('person_happynet/add_Equestion_group_Happy_Net','Happy_Net_Controller@add_Equestion_group')->name('happy.add_Equestion_group');
Route::post('person_happynet/save_Equestion_group_Happy_Net','Happy_Net_Controller@save_Equestion_group')->name('happy.save_Equestion_group');
Route::get('person_happynet/edit_Equestion_group_Happy_Net/{id}','Happy_Net_Controller@edit_Equestion_group')->name('happy.edit_Equestion_group');
Route::post('person_happynet/update_Equestion_group_Happy_Net','Happy_Net_Controller@update_Equestion_group')->name('happy.update_Equestion_group');
Route::get('person_happynet/destroy_Equestion_group_Happy_Net/{id}','Happy_Net_Controller@destroy_Equestion_group')->name('happy.destroy_Equestion_group');
Route::get('person_happynet/status_Equestion_group_Happy_Net','Happy_Net_Controller@status_Equestion_group')->name('happy.status_Equestion_group');

// 

// คำถาม
Route::get('person_happynet/Equestion_Happy_Net/{id}','Happy_Net_Controller@Equestion')->name('happy.Equestion');
Route::get('person_happynet/add_Equestion_Happy_Net/{id}','Happy_Net_Controller@add_Equestion')->name('happy.add_Equestion');
Route::post('person_happynet/save_Equestion_Happy_Net/{id}','Happy_Net_Controller@save_Equestion')->name('happy.save_Equestion');
Route::get('person_happynet/destroy_Equestion_Happy_Net/{id}','Happy_Net_Controller@destroy_Equestion')->name('happy.destroy_Equestion');

Route::get('person_happynet/edit_Equestion_Happy_Net/{id}','Happy_Net_Controller@edit_Equestion')->name('edit_Equestion');
Route::post('person_happynet/update_Equestion_Happy_Net','Happy_Net_Controller@update_Equestion')->name('update_Equestion');

Route::get('person_happynet/status_Equestion_Happy_Net','Happy_Net_Controller@status_Equestion')->name('status_Equestion');
Route::get('person_happynet/view_status_modal_Happy_Net','Happy_Net_Controller@view_status_modal')->name('view_status_modal');
// Route::get('person_happynet/view_status_modal_Happy_Net','Happy_Net_Controller@view_status_modals')->name('happy.view_status_modal');

Route::get('person_happynet/status_modal_Happy_Net','Happy_Net_Controller@status_modal')->name('status_modal');

// end คำถาม

// ของรางวัล
Route::get('person_happynet/Ereward_Happy_Net','Happy_Net_Controller@Ereward')->name('Ereward');
Route::get('person_happynet/add_Ereward_Happy_Net','Happy_Net_Controller@add_Ereward')->name('add_Ereward');
Route::post('person_happynet/save_Ereward_Happy_Net','Happy_Net_Controller@save_Ereward')->name('save_Ereward');
Route::get('person_happynet/edit_Ereward_Happy_Net/{id}','Happy_Net_Controller@edit_Ereward')->name('edit_Ereward');
Route::post('person_happynet/update_Ereward_Happy_Net','Happy_Net_Controller@update_Ereward')->name('update_Ereward');
Route::get('person_happynet/destroy_Ereward_Happy_Net/{id}','Happy_Net_Controller@destroy_Ereward')->name('destroy_Ereward');
Route::get('person_happynet/status_Ereward_Happy_Net','Happy_Net_Controller@status_Ereward')->name('status_Ereward');
// end ของรางวัล

// จัดการระดับความยาก
Route::get('person_happynet/Ecoin_Happy_Net','Happy_Net_Controller@Ecoin')->name('ecoin');
Route::get('person_happynet/add_ecoin_Happy_Net','Happy_Net_Controller@add_ecoin')->name('add_ecoin');
Route::post('person_happynet/save_ecoin_Happy_Net','Happy_Net_Controller@save_ecoin')->name('save_ecoin');
Route::get('person_happynet/edit_ecoin_Happy_Net/{id}','Happy_Net_Controller@edit_ecoin')->name('edit_ecoin');
Route::post('person_happynet/update_ecoin_Happy_Net','Happy_Net_Controller@update_ecoin')->name('update_ecoin');
Route::get('person_happynet/destroy_ecoin_Happy_Net/{id}','Happy_Net_Controller@destroy_ecoin')->name('destroy_ecoin');
Route::get('person_happynet/status_ecoin_Happy_Net','Happy_Net_Controller@status_ecoin')->name('status_ecoin');

// จัดการจำกัดเหรียญในแต่ละวัน
Route::get('person_happynet/Eset_coin_Happy_Net','Happy_Net_Controller@set_coin')->name('happy.set_coin');
Route::get('person_happynet/Eset_coin_Happy_Net/{id}','Happy_Net_Controller@set_coins')->name('happy.set_coin');
Route::post('person_happynet/update_set_coin_Happy_Net','Happy_Net_Controller@update_set_coin')->name('happy.update_set_coin');
Route::post('person_happynet/update_set_coin_Happy_Nets','Happy_Net_Controller@update_set_coins')->name('happy.update_set_coins');
// จัดการการจำกัดการใช้งาน
Route::get('person_happynet/Eset_compliment_Happy_Net','Happy_Net_Controller@Eset_compliment')->name('happy.Eset_compliment');
Route::get('person_happynet/Eset_compliment_Happy_Net/{id}','Happy_Net_Controller@Eset_compliments')->name('happy.Eset_compliment');
Route::post('person_happynet/update_Eset_compliment_Happy_Net','Happy_Net_Controller@update_Eset_compliment')->name('happy.update_Eset_compliment');
Route::post('person_happynet/update_Eset_compliment_Happy_Nets','Happy_Net_Controller@update_Eset_compliments')->name('happy.update_Eset_compliments');

Route::get('person_happynet/Eset_problem_Happy_Net','Happy_Net_Controller@Eset_problem')->name('happy.Eset_problem');
Route::get('person_happynet/Eset_problem_Happy_Net/{id}','Happy_Net_Controller@Eset_problems')->name('happy.Eset_problem');
Route::post('person_happynet/update_Eset_problem_Happy_Net','Happy_Net_Controller@update_Eset_problem')->name('happy.update_Eset_problem');
Route::post('person_happynet/update_Eset_problem_Happy_Nets','Happy_Net_Controller@update_Eset_problems')->name('happy.update_Eset_problems');
// mpdalประกาศรางวัล
Route::get('person_happynet/view_status_modal_reward_Happy_Net','Happy_Net_Controller@view_status_modal_reward')->name('happy.view_status_modal_reward');

// จัดการค่านิยม
Route::get('person_happynet/Ethics_Happy_Net','Happy_Net_Controller@Ethics')->name('happy.Ethics');
Route::get('person_happynet/add_Ethics_Happy_Net','Happy_Net_Controller@add_Ethics')->name('happy.add_Ethics');
Route::post('person_happynet/save_Ethics_Happy_Net','Happy_Net_Controller@save_Ethics')->name('happy.save_Ethics');
Route::get('person_happynet/edit_Ethics_Happy_Net/{id}','Happy_Net_Controller@edit_Ethics')->name('happy.edit_Ethics');
Route::post('person_happynet/update_Ethics_Happy_Net','Happy_Net_Controller@update_Ethics')->name('happy.update_Ethics');
Route::get('person_happynet/destroy_Ethics_Happy_Net/{id}','Happy_Net_Controller@destroy_Ethics')->name('happy.destroy_Ethics');
Route::get('person_happynet/status_Ethics_Happy_Net','Happy_Net_Controller@status_Ethics')->name('happy.status_ethics');
Route::get('person_happynet/Ets_show_Happy_Net','Happy_Net_Controller@ets_show')->name('happy.ets_show');
//จบ


});
});
