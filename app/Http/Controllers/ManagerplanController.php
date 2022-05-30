<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Models\Person;

use App\Models\Planvision;
use App\Models\Planmission;
use App\Models\Planstrategic;
use App\Models\Plantarget;
use App\Models\Plankpi;
use App\Models\Plantype;

use App\Models\Planproject;
use App\Models\Planhumandev;
use App\Models\Plandurable;
use App\Models\Plankpilevel;
use App\Models\Plankpiperson;

use App\Models\Planyear;
use App\Models\Planrepair;
use App\Models\Permislist;
use Cookie;
use Session;
date_default_timezone_set("Asia/Bangkok");

class ManagerplanController extends Controller
{
    public function dashboard(Request $request)
    {
        if($request->method() === 'POST'){
            $yearbudget = $request->BUDGET_YEAR;
            Cookie::queue('yearbudget', $yearbudget, 120,$request->server('REQUEST_URI'));
        }elseif(!empty(Cookie::get('yearbudget'))){
            $yearbudget = Cookie::get('yearbudget');
        }else{
            $yearbudget = getBudgetYear();
        }
        $budgettype     = DB::table('supplies_budget')->where('ACTIVE',true)->get();
        // เริ่มหาแผนกิจกรรมโครงการ
         $countplan_1 = DB::table('plan_project')->where('PRO_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->count();
         $sumpiceplan_1  =  DB::table('plan_project')->where('PRO_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->sum('BUDGET_PICE');
         $countsucces_1  =  DB::table('plan_project')->where('PRO_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->count();
         $sumpicesucces_1  =  DB::table('plan_project')->where('PRO_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->sum('BUDGET_PICE_REAL');
        $countplan = DB::table('plan_project')
                       ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
                       ->where('.BUDGET_YEAR','=',$yearbudget)
                       ->where('PRO_STATUS','APP')
                       ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_project.BUDGET_ID')
                       ->groupBy('supplies_budget.BUDGET_ID')
                       ->get();
        $sumpiceplan  =  DB::table('plan_project')
                       ->select(DB::raw('sum(plan_project.BUDGET_PICE) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
                       ->where('BUDGET_YEAR','=',$yearbudget)
                       ->where('PRO_STATUS','APP')
                       ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_project.BUDGET_ID')
                       ->groupBy('supplies_budget.BUDGET_ID')
                       ->get();
        $countsuccesplan  =  DB::table('plan_project')
                       ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
                       ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
                       ->where('PRO_STATUS','APP')
                       ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_project.BUDGET_ID')
                       ->groupBy('supplies_budget.BUDGET_ID')
                       ->get();
        $sumpicesuccesplan  =  DB::table('plan_project')
                       ->select(DB::raw('sum(plan_project.BUDGET_PICE_REAL) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
                       ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
                       ->where('PRO_STATUS','APP')
                       ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_project.BUDGET_ID')
                       ->groupBy('supplies_budget.BUDGET_ID')
                       ->get();
     
         $plan_activity_sub = [];
         foreach($budgettype as $row){
             $plan_activity_sub[$row->BUDGET_ID]['budget_id']             = $row->BUDGET_ID; 
             $plan_activity_sub[$row->BUDGET_ID]['budget_name']           = $row->BUDGET_NAME; 
             $plan_activity_sub[$row->BUDGET_ID]['budget_countall']       = 0; 
             $plan_activity_sub[$row->BUDGET_ID]['budget_budgetall']      = 0; 
             $plan_activity_sub[$row->BUDGET_ID]['budget_countsuccess']   = 0; 
             $plan_activity_sub[$row->BUDGET_ID]['budget_budgetsuccess']  = 0; 
         }
         $plan_activity_sub[null]['budget_id']             = null; 
         $plan_activity_sub[null]['budget_name']             = 'ไม่ระบุประเภทงบ'; 
         $plan_activity_sub[null]['budget_countall']       = 0; 
         $plan_activity_sub[null]['budget_budgetall']      = 0; 
         $plan_activity_sub[null]['budget_countsuccess']   = 0; 
         $plan_activity_sub[null]['budget_budgetsuccess']  = 0; 
         foreach($plan_activity_sub as $key => $plan){
             foreach($countplan as $row){
                 if($plan['budget_id'] == $row->BUDGET_ID){
                     $plan_activity_sub[$key]['budget_countall'] = $row->BUDGET_COUNT;
                     break;
                 }
             }
         }
         foreach($plan_activity_sub as $key => $plan){
             foreach($sumpiceplan as $row){
                 if($plan['budget_id'] == $row->BUDGET_ID){
                     $plan_activity_sub[$key]['budget_budgetall'] = $row->BUDGET_SUM;
                     break;
                 }
             }
         }
         foreach($plan_activity_sub as $key => $plan){
             foreach($countsuccesplan as $row){
                 if($plan['budget_id'] == $row->BUDGET_ID){
                     $plan_activity_sub[$key]['budget_countsuccess'] = $row->BUDGET_COUNT;
                     break;
                 }
             }
         }
         foreach($plan_activity_sub as $key => $plan){
             foreach($sumpicesuccesplan as $row){
                 if($plan['budget_id'] == $row->BUDGET_ID){
                     $plan_activity_sub[$key]['budget_budgetsuccess'] = $row->BUDGET_SUM;
                     break;
                 }
             }
         }
         $plan_countall      = 0;
         $plan_countsuccess  = 0;
         foreach($plan_activity_sub as $row){
             $plan_countall      += $row['budget_countall'];
             $plan_countsuccess  += $row['budget_countsuccess'];
         }
         $plan_activity_sub[null]['budget_countall']      = $countplan_1 - $plan_countall; //หาเนื่องจาก sql count ไม่นับแถวค่า null ให้
         $plan_activity_sub[null]['budget_countsuccess']  = $countsucces_1 - $plan_countsuccess;
        // จบหาแผนกิจกรรมโครงการ 
        // เริ่มหาแผนพัฒนาบุคลากรโครงการ
         $countplan_2 = DB::table('plan_humandev')->where('HUM_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->count();
         $sumpiceplan_2  =  DB::table('plan_humandev')->where('HUM_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->sum('BUDGET_PICE');
         $countsucces_2  =  DB::table('plan_humandev')->where('HUM_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->count();
         $sumpicesucces_2  =  DB::table('plan_humandev')->where('HUM_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->sum('BUDGET_PICE_REAL');
         $countplan_humandev = DB::table('plan_humandev')
                    ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
                    ->where('.BUDGET_YEAR','=',$yearbudget)
                    ->where('HUM_STATUS','APP')
                    ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_humandev.BUDGET_ID')
                    ->groupBy('supplies_budget.BUDGET_ID')
                    ->get();
        $sumpiceplan_humandev  =  DB::table('plan_humandev')
                ->select(DB::raw('sum(plan_humandev.BUDGET_PICE) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
                ->where('BUDGET_YEAR','=',$yearbudget)
                ->where('HUM_STATUS','APP')
                ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_humandev.BUDGET_ID')
                ->groupBy('supplies_budget.BUDGET_ID')
                ->get();
        $countsuccesplan_humandev  =  DB::table('plan_humandev')
                ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
                ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
                ->where('HUM_STATUS','APP')
                ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_humandev.BUDGET_ID')
                ->groupBy('supplies_budget.BUDGET_ID')
                ->get();
        $sumpicesuccesplan_humandev  =  DB::table('plan_humandev')
                ->select(DB::raw('sum(plan_humandev.BUDGET_PICE_REAL) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
                ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
                ->where('HUM_STATUS','APP')
                ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_humandev.BUDGET_ID')
                ->groupBy('supplies_budget.BUDGET_ID')
                ->get();
        $plan_humandev_sub = [];
        foreach($budgettype as $row){
        $plan_humandev_sub[$row->BUDGET_ID]['budget_id']             = $row->BUDGET_ID; 
        $plan_humandev_sub[$row->BUDGET_ID]['budget_name']           = $row->BUDGET_NAME; 
        $plan_humandev_sub[$row->BUDGET_ID]['budget_countall']       = 0; 
        $plan_humandev_sub[$row->BUDGET_ID]['budget_budgetall']      = 0; 
        $plan_humandev_sub[$row->BUDGET_ID]['budget_countsuccess']   = 0; 
        $plan_humandev_sub[$row->BUDGET_ID]['budget_budgetsuccess']  = 0; 
        }
        $plan_humandev_sub[null]['budget_id']             = null; 
        $plan_humandev_sub[null]['budget_name']             = 'ไม่ระบุประเภทงบ'; 
        $plan_humandev_sub[null]['budget_countall']       = 0; 
        $plan_humandev_sub[null]['budget_budgetall']      = 0; 
        $plan_humandev_sub[null]['budget_countsuccess']   = 0; 
        $plan_humandev_sub[null]['budget_budgetsuccess']  = 0; 
        foreach($plan_humandev_sub as $key => $plan_humandev){
            foreach($countplan_humandev as $row){
                if($plan_humandev['budget_id'] == $row->BUDGET_ID){
                    $plan_humandev_sub[$key]['budget_countall'] = $row->BUDGET_COUNT;
                    break;
                }
            }
        }
        foreach($plan_humandev_sub as $key => $plan_humandev){
            foreach($sumpiceplan_humandev as $row){
                if($plan_humandev['budget_id'] == $row->BUDGET_ID){
                    $plan_humandev_sub[$key]['budget_budgetall'] = $row->BUDGET_SUM;
                    break;
                }
            }
        }
        foreach($plan_humandev_sub as $key => $plan_humandev){
            foreach($countsuccesplan_humandev as $row){
                if($plan_humandev['budget_id'] == $row->BUDGET_ID){
                    $plan_humandev_sub[$key]['budget_countsuccess'] = $row->BUDGET_COUNT;
                    break;
                }
            }
        }
        foreach($plan_humandev_sub as $key => $plan_humandev){
            foreach($sumpicesuccesplan_humandev as $row){
                if($plan_humandev['budget_id'] == $row->BUDGET_ID){
                    $plan_humandev_sub[$key]['budget_budgetsuccess'] = $row->BUDGET_SUM;
                    break;
                }
            }
        }
        $plan_humandev_countall      = 0;
        $plan_humandev_countsuccess  = 0;
        foreach($plan_humandev_sub as $row){
        $plan_humandev_countall      += $row['budget_countall'];
        $plan_humandev_countsuccess  += $row['budget_countsuccess'];
        }
        $plan_humandev_sub[null]['budget_countall']      = $countplan_2 - $plan_humandev_countall; //หาเนื่องจาก sql count ไม่นับแถวค่า null ให้
        $plan_humandev_sub[null]['budget_countsuccess']  = $countsucces_2 - $plan_humandev_countsuccess;
        // จบหาแผนพัฒนาบุคลากรโครงการ  
        // เริ่มหาแผนจัดซื้อโครงการ
         $countplan_3 = DB::table('plan_durable')->where('DUR_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->count();
         $sumpiceplan_3  =  DB::table('plan_durable')->where('DUR_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->sum('DUR_PICE_SUM');
         $countsucces_3  =  DB::table('plan_durable')->where('DUR_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->count();
         $sumpicesucces_3  =  DB::table('plan_durable')->where('DUR_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->sum('BUDGET_PICE_REAL');
         $countplan_purchase = DB::table('plan_durable')
         ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
         ->where('.BUDGET_YEAR','=',$yearbudget)
         ->where('DUR_STATUS','APP')
         ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_durable.BUDGET_ID')
         ->groupBy('supplies_budget.BUDGET_ID')
         ->get();
        $sumpiceplan_purchase  =  DB::table('plan_durable')
            ->select(DB::raw('sum(plan_durable.DUR_PICE_SUM) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)
            ->where('DUR_STATUS','APP')
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_durable.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $countsuccesplan_purchase  =  DB::table('plan_durable')
            ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
            ->where('DUR_STATUS','APP')
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_durable.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $sumpicesuccesplan_purchase  =  DB::table('plan_durable')
            ->select(DB::raw('sum(plan_durable.BUDGET_PICE_REAL) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
            ->where('DUR_STATUS','APP')
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_durable.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $plan_purchase_sub = [];
        foreach($budgettype as $row){
        $plan_purchase_sub[$row->BUDGET_ID]['budget_id']             = $row->BUDGET_ID; 
        $plan_purchase_sub[$row->BUDGET_ID]['budget_name']           = $row->BUDGET_NAME; 
        $plan_purchase_sub[$row->BUDGET_ID]['budget_countall']       = 0; 
        $plan_purchase_sub[$row->BUDGET_ID]['budget_budgetall']      = 0; 
        $plan_purchase_sub[$row->BUDGET_ID]['budget_countsuccess']   = 0; 
        $plan_purchase_sub[$row->BUDGET_ID]['budget_budgetsuccess']  = 0; 
        }
        $plan_purchase_sub[null]['budget_id']             = null; 
        $plan_purchase_sub[null]['budget_name']             = 'ไม่ระบุประเภทงบ'; 
        $plan_purchase_sub[null]['budget_countall']       = 0; 
        $plan_purchase_sub[null]['budget_budgetall']      = 0; 
        $plan_purchase_sub[null]['budget_countsuccess']   = 0; 
        $plan_purchase_sub[null]['budget_budgetsuccess']  = 0; 
        foreach($plan_purchase_sub as $key => $plan_purchase){
            foreach($countplan_purchase as $row){
                if($plan_purchase['budget_id'] == $row->BUDGET_ID){
                $plan_purchase_sub[$key]['budget_countall'] = $row->BUDGET_COUNT;
                break;
                }
            }
        }
        foreach($plan_purchase_sub as $key => $plan_purchase){
            foreach($sumpiceplan_purchase as $row){
                if($plan_purchase['budget_id'] == $row->BUDGET_ID){
                $plan_purchase_sub[$key]['budget_budgetall'] = $row->BUDGET_SUM;
                break;
                }
            }
        }
        foreach($plan_purchase_sub as $key => $plan_purchase){
            foreach($countsuccesplan_purchase as $row){
                if($plan_purchase['budget_id'] == $row->BUDGET_ID){
                $plan_purchase_sub[$key]['budget_countsuccess'] = $row->BUDGET_COUNT;
                break;
                }
            }
        }
        foreach($plan_purchase_sub as $key => $plan_purchase){
            foreach($sumpicesuccesplan_purchase as $row){
                if($plan_purchase['budget_id'] == $row->BUDGET_ID){
                $plan_purchase_sub[$key]['budget_budgetsuccess'] = $row->BUDGET_SUM;
                break;
                }
            }
        }
        $plan_purchase_countall      = 0;
        $plan_purchase_countsuccess  = 0;
        foreach($plan_purchase_sub as $row){
        $plan_purchase_countall      += $row['budget_countall'];
        $plan_purchase_countsuccess  += $row['budget_countsuccess'];
        }
        $plan_purchase_sub[null]['budget_countall']      = $countplan_3 - $plan_purchase_countall; //หาเนื่องจาก sql count ไม่นับแถวค่า null ให้
        $plan_purchase_sub[null]['budget_countsuccess']  = $countsucces_3 - $plan_purchase_countsuccess;
        // จบหาแผนจัดซื้อโครงการ
        // เริ่มหาแผนบำรุงรักษาโครงการ
         $countplan_4 = DB::table('plan_repair')->where('REPAIR_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->count();
         $sumpiceplan_4  =  DB::table('plan_repair')->where('REPAIR_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->sum('REPAIR_PICE_SUM');
         $countsucces_4  =  DB::table('plan_repair')->where('REPAIR_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->where('REPAIR_PICE_REAL','<>',0)->count();
         $sumpicesucces_4  =  DB::table('plan_repair')->where('REPAIR_STATUS','APP')->where('BUDGET_YEAR','=',$yearbudget)->where('REPAIR_PICE_REAL','<>',0)->sum('REPAIR_PICE_REAL');
         $countplan_maintenance = DB::table('plan_repair')
         ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
         ->where('.BUDGET_YEAR','=',$yearbudget)
         ->where('REPAIR_STATUS','APP')
         ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_repair.BUDGET_ID')
         ->groupBy('supplies_budget.BUDGET_ID')
         ->get();
        $sumpiceplan_maintenance  =  DB::table('plan_repair')
            ->select(DB::raw('sum(plan_repair.REPAIR_PICE_SUM) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)
            ->where('REPAIR_STATUS','APP')
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_repair.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $countsuccesplan_maintenance  =  DB::table('plan_repair')
            ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)->where('REPAIR_PICE_REAL','<>',0)
            ->where('REPAIR_STATUS','APP')
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_repair.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $sumpicesuccesplan_maintenance  =  DB::table('plan_repair')
            ->select(DB::raw('sum(plan_repair.REPAIR_PICE_REAL) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)->where('REPAIR_PICE_REAL','<>',0)
            ->where('REPAIR_STATUS','APP')
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_repair.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $plan_maintenance_sub = [];
        foreach($budgettype as $row){
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_id']             = $row->BUDGET_ID; 
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_name']           = $row->BUDGET_NAME; 
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_countall']       = 0; 
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_budgetall']      = 0; 
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_countsuccess']   = 0; 
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_budgetsuccess']  = 0; 
        }
        $plan_maintenance_sub[null]['budget_id']             = null; 
        $plan_maintenance_sub[null]['budget_name']             = 'ไม่ระบุประเภทงบ'; 
        $plan_maintenance_sub[null]['budget_countall']       = 0; 
        $plan_maintenance_sub[null]['budget_budgetall']      = 0; 
        $plan_maintenance_sub[null]['budget_countsuccess']   = 0; 
        $plan_maintenance_sub[null]['budget_budgetsuccess']  = 0; 
        foreach($plan_maintenance_sub as $key => $plan_maintenance){
            foreach($countplan_maintenance as $row){
                if($plan_maintenance['budget_id'] == $row->BUDGET_ID){
                $plan_maintenance_sub[$key]['budget_countall'] = $row->BUDGET_COUNT;
                break;
                }
            }
        }
        foreach($plan_maintenance_sub as $key => $plan_maintenance){
            foreach($sumpiceplan_maintenance as $row){
                if($plan_maintenance['budget_id'] == $row->BUDGET_ID){
                $plan_maintenance_sub[$key]['budget_budgetall'] = $row->BUDGET_SUM;
                break;
                }
            }
        }
        foreach($plan_maintenance_sub as $key => $plan_maintenance){
            foreach($countsuccesplan_maintenance as $row){
                if($plan_maintenance['budget_id'] == $row->BUDGET_ID){
                $plan_maintenance_sub[$key]['budget_countsuccess'] = $row->BUDGET_COUNT;
                break;
                }
            }
        }
        foreach($plan_maintenance_sub as $key => $plan_maintenance){
            foreach($sumpicesuccesplan_maintenance as $row){
                if($plan_maintenance['budget_id'] == $row->BUDGET_ID){
                $plan_maintenance_sub[$key]['budget_budgetsuccess'] = $row->BUDGET_SUM;
                break;
                }
            }
        }
        $plan_maintenance_countall      = 0;
        $plan_maintenance_countsuccess  = 0;
        foreach($plan_maintenance_sub as $row){
            $plan_maintenance_countall      += $row['budget_countall'];
            $plan_maintenance_countsuccess  += $row['budget_countsuccess'];
        }
        $plan_maintenance_sub[null]['budget_countall']      = $countplan_4 - $plan_maintenance_countall; //หาเนื่องจาก sql count ไม่นับแถวค่า null ให้
        $plan_maintenance_sub[null]['budget_countsuccess']  = $countsucces_4 - $plan_maintenance_countsuccess;
        // จบหาแผนบำรุงรักษาโครงการ

         if($countplan_1 != 0 && $countplan_1 != null){
            $persen_1 = ($countsucces_1 /$countplan_1)*100;
        }else{
            $persen_1 = 0;
        }

        if($countplan_2 != 0 && $countplan_2 != null){
            $persen_2 = ($countsucces_2 /$countplan_2)*100; 
        }else{
            $persen_2 = 0;
        }

        if($countplan_3 != 0 && $countplan_3 != null){
            $persen_3 = ($countsucces_3 /$countplan_3)*100;
        }else{
            $persen_3 = 0;
        }


        
        if($countplan_4 != 0 && $countplan_4 != null){
            $persen_4 = ($countsucces_4 /$countplan_4)*100;
        }else{
            $persen_4 = 0;
        }


         $sum_1 = $countplan_1 + $countplan_2 + $countplan_3 + $countplan_4;
         $sum_2 = $sumpiceplan_1 + $sumpiceplan_2 + $sumpiceplan_3 + $sumpiceplan_4;
         $sum_3 = $countsucces_1 + $countsucces_2 + $countsucces_3 + $countsucces_4;
         $sum_4 = $sumpicesucces_1 + $sumpicesucces_2 + $sumpicesucces_3 + $sumpicesucces_4;  
         
         if($sum_1 != 0 && $sum_1 != null){
         $sum_5 = ($sum_3 /$sum_1)*100;
         }else{
            $sum_5 = 0;
        }

         if($sum_2 != 0 && $sum_2 != null){
         $sum_6 = ($sum_4 /$sum_2)*100;
         }else{
            $sum_6 = 0;
        }
         $year_id = $yearbudget;
         $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        return view('manager_plan.dashboard_plan',[
            'plan_activity_sub'  => $plan_activity_sub,
            'plan_humandev_sub' => $plan_humandev_sub,
            'plan_purchase_sub' => $plan_purchase_sub,
            'plan_maintenance_sub' => $plan_maintenance_sub,
            'countplan_1'=> $countplan_1,
            'sumpiceplan_1'=> $sumpiceplan_1,
            'countsucces_1'=> $countsucces_1,
            'sumpicesucces_1'=> $sumpicesucces_1,
            'countplan_2'=> $countplan_2,
            'sumpiceplan_2'=> $sumpiceplan_2,
            'countsucces_2'=> $countsucces_2,
            'sumpicesucces_2'=> $sumpicesucces_2,
            'countplan_3'=> $countplan_3,
            'sumpiceplan_3'=> $sumpiceplan_3,
            'countsucces_3'=> $countsucces_3,
            'sumpicesucces_3'=> $sumpicesucces_3,
            'countplan_4'=> $countplan_4,
            'sumpiceplan_4'=> $sumpiceplan_4,
            'countsucces_4'=> $countsucces_4,
            'sumpicesucces_4'=> $sumpicesucces_4,
            'persen_1'=> $persen_1,
            'persen_2'=> $persen_2,
            'persen_3'=> $persen_3,
            'persen_4'=> $persen_4,
            'sum_1'=> $sum_1,
            'sum_2'=> $sum_2,
            'sum_3'=> $sum_3,
            'sum_4'=> $sum_4,
            'sum_5'=> $sum_5,
            'sum_6'=> $sum_6,
            'budgets' =>  $budget,
            'year_id'=>$year_id,
        ]);
    }



    public function dashboard_search(Request $request)
    {
    

        $yearbudget = $request->BUDGET_YEAR;


        $budgettype     = DB::table('supplies_budget')->where('ACTIVE',true)->get();
        // เริ่มหาแผนกิจกรรมโครงการ
         $countplan_1 = DB::table('plan_project')->where('BUDGET_YEAR','=',$yearbudget)->count();
         $sumpiceplan_1  =  DB::table('plan_project')->where('BUDGET_YEAR','=',$yearbudget)->sum('BUDGET_PICE');
         $countsucces_1  =  DB::table('plan_project')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->count();
         $sumpicesucces_1  =  DB::table('plan_project')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->sum('BUDGET_PICE_REAL');
        $countplan = DB::table('plan_project')
                       ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
                       ->where('.BUDGET_YEAR','=',$yearbudget)
                       ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_project.BUDGET_ID')
                       ->groupBy('supplies_budget.BUDGET_ID')
                       ->get();
        $sumpiceplan  =  DB::table('plan_project')
                       ->select(DB::raw('sum(plan_project.BUDGET_PICE) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
                       ->where('BUDGET_YEAR','=',$yearbudget)
                       ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_project.BUDGET_ID')
                       ->groupBy('supplies_budget.BUDGET_ID')
                       ->get();
        $countsuccesplan  =  DB::table('plan_project')
                       ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
                       ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
                       ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_project.BUDGET_ID')
                       ->groupBy('supplies_budget.BUDGET_ID')
                       ->get();
        $sumpicesuccesplan  =  DB::table('plan_project')
                       ->select(DB::raw('sum(plan_project.BUDGET_PICE_REAL) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
                       ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
                       ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_project.BUDGET_ID')
                       ->groupBy('supplies_budget.BUDGET_ID')
                       ->get();
     
         $plan_activity_sub = [];
         foreach($budgettype as $row){
             $plan_activity_sub[$row->BUDGET_ID]['budget_id']             = $row->BUDGET_ID; 
             $plan_activity_sub[$row->BUDGET_ID]['budget_name']           = $row->BUDGET_NAME; 
             $plan_activity_sub[$row->BUDGET_ID]['budget_countall']       = 0; 
             $plan_activity_sub[$row->BUDGET_ID]['budget_budgetall']      = 0; 
             $plan_activity_sub[$row->BUDGET_ID]['budget_countsuccess']   = 0; 
             $plan_activity_sub[$row->BUDGET_ID]['budget_budgetsuccess']  = 0; 
         }
         $plan_activity_sub[null]['budget_id']             = null; 
         $plan_activity_sub[null]['budget_name']             = 'ไม่ระบุประเภทงบ'; 
         $plan_activity_sub[null]['budget_countall']       = 0; 
         $plan_activity_sub[null]['budget_budgetall']      = 0; 
         $plan_activity_sub[null]['budget_countsuccess']   = 0; 
         $plan_activity_sub[null]['budget_budgetsuccess']  = 0; 
         foreach($plan_activity_sub as $key => $plan){
             foreach($countplan as $row){
                 if($plan['budget_id'] == $row->BUDGET_ID){
                     $plan_activity_sub[$key]['budget_countall'] = $row->BUDGET_COUNT;
                     break;
                 }
             }
         }
         foreach($plan_activity_sub as $key => $plan){
             foreach($sumpiceplan as $row){
                 if($plan['budget_id'] == $row->BUDGET_ID){
                     $plan_activity_sub[$key]['budget_budgetall'] = $row->BUDGET_SUM;
                     break;
                 }
             }
         }
         foreach($plan_activity_sub as $key => $plan){
             foreach($countsuccesplan as $row){
                 if($plan['budget_id'] == $row->BUDGET_ID){
                     $plan_activity_sub[$key]['budget_countsuccess'] = $row->BUDGET_COUNT;
                     break;
                 }
             }
         }
         foreach($plan_activity_sub as $key => $plan){
             foreach($sumpicesuccesplan as $row){
                 if($plan['budget_id'] == $row->BUDGET_ID){
                     $plan_activity_sub[$key]['budget_budgetsuccess'] = $row->BUDGET_SUM;
                     break;
                 }
             }
         }
         $plan_countall      = 0;
         $plan_countsuccess  = 0;
         foreach($plan_activity_sub as $row){
             $plan_countall      += $row['budget_countall'];
             $plan_countsuccess  += $row['budget_countsuccess'];
         }
         $plan_activity_sub[null]['budget_countall']      = $countplan_1 - $plan_countall; //หาเนื่องจาก sql count ไม่นับแถวค่า null ให้
         $plan_activity_sub[null]['budget_countsuccess']  = $countsucces_1 - $plan_countsuccess;
        // จบหาแผนกิจกรรมโครงการ 
        // เริ่มหาแผนพัฒนาบุคลากรโครงการ
         $countplan_2 = DB::table('plan_humandev')->where('BUDGET_YEAR','=',$yearbudget)->count();
         $sumpiceplan_2  =  DB::table('plan_humandev')->where('BUDGET_YEAR','=',$yearbudget)->sum('BUDGET_PICE');
         $countsucces_2  =  DB::table('plan_humandev')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->count();
         $sumpicesucces_2  =  DB::table('plan_humandev')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->sum('BUDGET_PICE_REAL');
         $countplan_humandev = DB::table('plan_humandev')
                    ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
                    ->where('.BUDGET_YEAR','=',$yearbudget)
                    ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_humandev.BUDGET_ID')
                    ->groupBy('supplies_budget.BUDGET_ID')
                    ->get();
        $sumpiceplan_humandev  =  DB::table('plan_humandev')
                ->select(DB::raw('sum(plan_humandev.BUDGET_PICE) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
                ->where('BUDGET_YEAR','=',$yearbudget)
                ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_humandev.BUDGET_ID')
                ->groupBy('supplies_budget.BUDGET_ID')
                ->get();
        $countsuccesplan_humandev  =  DB::table('plan_humandev')
                ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
                ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
                ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_humandev.BUDGET_ID')
                ->groupBy('supplies_budget.BUDGET_ID')
                ->get();
        $sumpicesuccesplan_humandev  =  DB::table('plan_humandev')
                ->select(DB::raw('sum(plan_humandev.BUDGET_PICE_REAL) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
                ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
                ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_humandev.BUDGET_ID')
                ->groupBy('supplies_budget.BUDGET_ID')
                ->get();
        $plan_humandev_sub = [];
        foreach($budgettype as $row){
        $plan_humandev_sub[$row->BUDGET_ID]['budget_id']             = $row->BUDGET_ID; 
        $plan_humandev_sub[$row->BUDGET_ID]['budget_name']           = $row->BUDGET_NAME; 
        $plan_humandev_sub[$row->BUDGET_ID]['budget_countall']       = 0; 
        $plan_humandev_sub[$row->BUDGET_ID]['budget_budgetall']      = 0; 
        $plan_humandev_sub[$row->BUDGET_ID]['budget_countsuccess']   = 0; 
        $plan_humandev_sub[$row->BUDGET_ID]['budget_budgetsuccess']  = 0; 
        }
        $plan_humandev_sub[null]['budget_id']             = null; 
        $plan_humandev_sub[null]['budget_name']             = 'ไม่ระบุประเภทงบ'; 
        $plan_humandev_sub[null]['budget_countall']       = 0; 
        $plan_humandev_sub[null]['budget_budgetall']      = 0; 
        $plan_humandev_sub[null]['budget_countsuccess']   = 0; 
        $plan_humandev_sub[null]['budget_budgetsuccess']  = 0; 
        foreach($plan_humandev_sub as $key => $plan_humandev){
            foreach($countplan_humandev as $row){
                if($plan_humandev['budget_id'] == $row->BUDGET_ID){
                    $plan_humandev_sub[$key]['budget_countall'] = $row->BUDGET_COUNT;
                    break;
                }
            }
        }
        foreach($plan_humandev_sub as $key => $plan_humandev){
            foreach($sumpiceplan_humandev as $row){
                if($plan_humandev['budget_id'] == $row->BUDGET_ID){
                    $plan_humandev_sub[$key]['budget_budgetall'] = $row->BUDGET_SUM;
                    break;
                }
            }
        }
        foreach($plan_humandev_sub as $key => $plan_humandev){
            foreach($countsuccesplan_humandev as $row){
                if($plan_humandev['budget_id'] == $row->BUDGET_ID){
                    $plan_humandev_sub[$key]['budget_countsuccess'] = $row->BUDGET_COUNT;
                    break;
                }
            }
        }
        foreach($plan_humandev_sub as $key => $plan_humandev){
            foreach($sumpicesuccesplan_humandev as $row){
                if($plan_humandev['budget_id'] == $row->BUDGET_ID){
                    $plan_humandev_sub[$key]['budget_budgetsuccess'] = $row->BUDGET_SUM;
                    break;
                }
            }
        }
        $plan_humandev_countall      = 0;
        $plan_humandev_countsuccess  = 0;
        foreach($plan_humandev_sub as $row){
        $plan_humandev_countall      += $row['budget_countall'];
        $plan_humandev_countsuccess  += $row['budget_countsuccess'];
        }
        $plan_humandev_sub[null]['budget_countall']      = $countplan_2 - $plan_humandev_countall; //หาเนื่องจาก sql count ไม่นับแถวค่า null ให้
        $plan_humandev_sub[null]['budget_countsuccess']  = $countsucces_2 - $plan_humandev_countsuccess;
        // จบหาแผนพัฒนาบุคลากรโครงการ  
        // เริ่มหาแผนจัดซื้อโครงการ
         $countplan_3 = DB::table('plan_durable')->where('BUDGET_YEAR','=',$yearbudget)->count();
         $sumpiceplan_3  =  DB::table('plan_durable')->where('BUDGET_YEAR','=',$yearbudget)->sum('DUR_PICE_SUM');
         $countsucces_3  =  DB::table('plan_durable')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->count();
         $sumpicesucces_3  =  DB::table('plan_durable')->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)->sum('BUDGET_PICE_REAL');
         $countplan_purchase = DB::table('plan_durable')
         ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
         ->where('.BUDGET_YEAR','=',$yearbudget)
         ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_durable.BUDGET_ID')
         ->groupBy('supplies_budget.BUDGET_ID')
         ->get();
        $sumpiceplan_purchase  =  DB::table('plan_durable')
            ->select(DB::raw('sum(plan_durable.DUR_PICE_SUM) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_durable.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $countsuccesplan_purchase  =  DB::table('plan_durable')
            ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_durable.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $sumpicesuccesplan_purchase  =  DB::table('plan_durable')
            ->select(DB::raw('sum(plan_durable.BUDGET_PICE_REAL) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)->where('BUDGET_PICE_REAL','<>',0)
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_durable.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $plan_purchase_sub = [];
        foreach($budgettype as $row){
        $plan_purchase_sub[$row->BUDGET_ID]['budget_id']             = $row->BUDGET_ID; 
        $plan_purchase_sub[$row->BUDGET_ID]['budget_name']           = $row->BUDGET_NAME; 
        $plan_purchase_sub[$row->BUDGET_ID]['budget_countall']       = 0; 
        $plan_purchase_sub[$row->BUDGET_ID]['budget_budgetall']      = 0; 
        $plan_purchase_sub[$row->BUDGET_ID]['budget_countsuccess']   = 0; 
        $plan_purchase_sub[$row->BUDGET_ID]['budget_budgetsuccess']  = 0; 
        }
        $plan_purchase_sub[null]['budget_id']             = null; 
        $plan_purchase_sub[null]['budget_name']             = 'ไม่ระบุประเภทงบ'; 
        $plan_purchase_sub[null]['budget_countall']       = 0; 
        $plan_purchase_sub[null]['budget_budgetall']      = 0; 
        $plan_purchase_sub[null]['budget_countsuccess']   = 0; 
        $plan_purchase_sub[null]['budget_budgetsuccess']  = 0; 
        foreach($plan_purchase_sub as $key => $plan_purchase){
            foreach($countplan_purchase as $row){
                if($plan_purchase['budget_id'] == $row->BUDGET_ID){
                $plan_purchase_sub[$key]['budget_countall'] = $row->BUDGET_COUNT;
                break;
                }
            }
        }
        foreach($plan_purchase_sub as $key => $plan_purchase){
            foreach($sumpiceplan_purchase as $row){
                if($plan_purchase['budget_id'] == $row->BUDGET_ID){
                $plan_purchase_sub[$key]['budget_budgetall'] = $row->BUDGET_SUM;
                break;
                }
            }
        }
        foreach($plan_purchase_sub as $key => $plan_purchase){
            foreach($countsuccesplan_purchase as $row){
                if($plan_purchase['budget_id'] == $row->BUDGET_ID){
                $plan_purchase_sub[$key]['budget_countsuccess'] = $row->BUDGET_COUNT;
                break;
                }
            }
        }
        foreach($plan_purchase_sub as $key => $plan_purchase){
            foreach($sumpicesuccesplan_purchase as $row){
                if($plan_purchase['budget_id'] == $row->BUDGET_ID){
                $plan_purchase_sub[$key]['budget_budgetsuccess'] = $row->BUDGET_SUM;
                break;
                }
            }
        }
        $plan_purchase_countall      = 0;
        $plan_purchase_countsuccess  = 0;
        foreach($plan_purchase_sub as $row){
        $plan_purchase_countall      += $row['budget_countall'];
        $plan_purchase_countsuccess  += $row['budget_countsuccess'];
        }
        $plan_purchase_sub[null]['budget_countall']      = $countplan_3 - $plan_purchase_countall; //หาเนื่องจาก sql count ไม่นับแถวค่า null ให้
        $plan_purchase_sub[null]['budget_countsuccess']  = $countsucces_3 - $plan_purchase_countsuccess;
        // จบหาแผนจัดซื้อโครงการ
        // เริ่มหาแผนบำรุงรักษาโครงการ
         $countplan_4 = DB::table('plan_repair')->where('BUDGET_YEAR','=',$yearbudget)->count();
         $sumpiceplan_4  =  DB::table('plan_repair')->where('BUDGET_YEAR','=',$yearbudget)->sum('REPAIR_PICE_SUM');
         $countsucces_4  =  DB::table('plan_repair')->where('BUDGET_YEAR','=',$yearbudget)->where('REPAIR_PICE_REAL','<>',0)->count();
         $sumpicesucces_4  =  DB::table('plan_repair')->where('BUDGET_YEAR','=',$yearbudget)->where('REPAIR_PICE_REAL','<>',0)->sum('REPAIR_PICE_REAL');
         $countplan_maintenance = DB::table('plan_repair')
         ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
         ->where('.BUDGET_YEAR','=',$yearbudget)
         ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_repair.BUDGET_ID')
         ->groupBy('supplies_budget.BUDGET_ID')
         ->get();
        $sumpiceplan_maintenance  =  DB::table('plan_repair')
            ->select(DB::raw('sum(plan_repair.REPAIR_PICE_SUM) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_repair.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $countsuccesplan_maintenance  =  DB::table('plan_repair')
            ->select(DB::raw('count(supplies_budget.BUDGET_ID) as BUDGET_COUNT,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)->where('REPAIR_PICE_REAL','<>',0)
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_repair.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $sumpicesuccesplan_maintenance  =  DB::table('plan_repair')
            ->select(DB::raw('sum(plan_repair.REPAIR_PICE_REAL) as BUDGET_SUM,supplies_budget.BUDGET_ID'))
            ->where('BUDGET_YEAR','=',$yearbudget)->where('REPAIR_PICE_REAL','<>',0)
            ->leftJoin('supplies_budget','supplies_budget.BUDGET_ID','=','plan_repair.BUDGET_ID')
            ->groupBy('supplies_budget.BUDGET_ID')
            ->get();
        $plan_maintenance_sub = [];
        foreach($budgettype as $row){
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_id']             = $row->BUDGET_ID; 
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_name']           = $row->BUDGET_NAME; 
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_countall']       = 0; 
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_budgetall']      = 0; 
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_countsuccess']   = 0; 
        $plan_maintenance_sub[$row->BUDGET_ID]['budget_budgetsuccess']  = 0; 
        }
        $plan_maintenance_sub[null]['budget_id']             = null; 
        $plan_maintenance_sub[null]['budget_name']             = 'ไม่ระบุประเภทงบ'; 
        $plan_maintenance_sub[null]['budget_countall']       = 0; 
        $plan_maintenance_sub[null]['budget_budgetall']      = 0; 
        $plan_maintenance_sub[null]['budget_countsuccess']   = 0; 
        $plan_maintenance_sub[null]['budget_budgetsuccess']  = 0; 
        foreach($plan_maintenance_sub as $key => $plan_maintenance){
            foreach($countplan_maintenance as $row){
                if($plan_maintenance['budget_id'] == $row->BUDGET_ID){
                $plan_maintenance_sub[$key]['budget_countall'] = $row->BUDGET_COUNT;
                break;
                }
            }
        }
        foreach($plan_maintenance_sub as $key => $plan_maintenance){
            foreach($sumpiceplan_maintenance as $row){
                if($plan_maintenance['budget_id'] == $row->BUDGET_ID){
                $plan_maintenance_sub[$key]['budget_budgetall'] = $row->BUDGET_SUM;
                break;
                }
            }
        }
        foreach($plan_maintenance_sub as $key => $plan_maintenance){
            foreach($countsuccesplan_maintenance as $row){
                if($plan_maintenance['budget_id'] == $row->BUDGET_ID){
                $plan_maintenance_sub[$key]['budget_countsuccess'] = $row->BUDGET_COUNT;
                break;
                }
            }
        }
        foreach($plan_maintenance_sub as $key => $plan_maintenance){
            foreach($sumpicesuccesplan_maintenance as $row){
                if($plan_maintenance['budget_id'] == $row->BUDGET_ID){
                $plan_maintenance_sub[$key]['budget_budgetsuccess'] = $row->BUDGET_SUM;
                break;
                }
            }
        }
        $plan_maintenance_countall      = 0;
        $plan_maintenance_countsuccess  = 0;
        foreach($plan_maintenance_sub as $row){
            $plan_maintenance_countall      += $row['budget_countall'];
            $plan_maintenance_countsuccess  += $row['budget_countsuccess'];
        }
        $plan_maintenance_sub[null]['budget_countall']      = $countplan_4 - $plan_maintenance_countall; //หาเนื่องจาก sql count ไม่นับแถวค่า null ให้
        $plan_maintenance_sub[null]['budget_countsuccess']  = $countsucces_4 - $plan_maintenance_countsuccess;
        // จบหาแผนบำรุงรักษาโครงการ

        if($countplan_1 != 0 && $countplan_1 != null){
           $persen_1 = ($countsucces_1 /$countplan_1)*100;
       }else{
           $persen_1 = 0;
       }

       if($countplan_2 != 0 && $countplan_2 != null){
           $persen_2 = ($countsucces_2 /$countplan_2)*100; 
       }else{
           $persen_2 = 0;
       }

       if($countplan_3 != 0 && $countplan_3 != null){
           $persen_3 = ($countsucces_3 /$countplan_3)*100;
       }else{
           $persen_3 = 0;
       }


       
       if($countplan_4 != 0 && $countplan_4 != null){
           $persen_4 = ($countsucces_4 /$countplan_4)*100;
       }else{
           $persen_4 = 0;
       }


        $sum_1 = $countplan_1 + $countplan_2 + $countplan_3 + $countplan_4;
        $sum_2 = $sumpiceplan_1 + $sumpiceplan_2 + $sumpiceplan_3 + $sumpiceplan_4;
        $sum_3 = $countsucces_1 + $countsucces_2 + $countsucces_3 + $countsucces_4;
        $sum_4 = $sumpicesucces_1 + $sumpicesucces_2 + $sumpicesucces_3 + $sumpicesucces_4;  
         
         if($sum_1 != 0 && $sum_1 != null){
         $sum_5 = ($sum_3 /$sum_1)*100;
         }else{
            $sum_5 = 0;
        }

         if($sum_2 != 0 && $sum_2 != null){
         $sum_6 = ($sum_4 /$sum_2)*100;
         }else{
            $sum_6 = 0;
        }

         $year_id = $yearbudget;

         $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        return view('manager_plan.dashboard_plan',[
            'plan_activity_sub'  => $plan_activity_sub,
            'plan_humandev_sub' => $plan_humandev_sub,
            'plan_purchase_sub' => $plan_purchase_sub,
            'plan_maintenance_sub' => $plan_maintenance_sub,
              'countplan_1'=> $countplan_1,
              'sumpiceplan_1'=> $sumpiceplan_1,
              'countsucces_1'=> $countsucces_1,
              'sumpicesucces_1'=> $sumpicesucces_1,
              'countplan_2'=> $countplan_2,
              'sumpiceplan_2'=> $sumpiceplan_2,
              'countsucces_2'=> $countsucces_2,
              'sumpicesucces_2'=> $sumpicesucces_2,
              'countplan_3'=> $countplan_3,
              'sumpiceplan_3'=> $sumpiceplan_3,
              'countsucces_3'=> $countsucces_3,
              'sumpicesucces_3'=> $sumpicesucces_3,
              'countplan_4'=> $countplan_4,
              'sumpiceplan_4'=> $sumpiceplan_4,
              'countsucces_4'=> $countsucces_4,
              'sumpicesucces_4'=> $sumpicesucces_4,
              'persen_1'=> $persen_1,
              'persen_2'=> $persen_2,
              'persen_3'=> $persen_3,
              'persen_4'=> $persen_4,
              'sum_1'=> $sum_1,
              'sum_2'=> $sum_2,
              'sum_3'=> $sum_3,
              'sum_4'=> $sum_4,
              'sum_5'=> $sum_5,
              'sum_6'=> $sum_6,
              'budgets' =>  $budget,
              'year_id'=>$year_id,



        ]);
    }

    public function plan_setstory()
    {
    
        return view('manager_plan.plan_setstory');
    }




    public function plan_vision()
    {
         $infovision = Planvision::orderBy('VISION_ID', 'desc')->get();
    
        return view('manager_plan.plan_vision',[
            'infovisions' => $infovision
        ]);
    }
    
    public function addplanvision()
    {
    
        return view('manager_plan.plan_visionadd');
    }


    public function saveplanvision(Request $request)
    {
            $add = new Planvision(); 
            $add->VISION_NO = $request->VISION_NO;
            $add->VISION_NAME = $request->VISION_NAME;
            $add->save();
    
    
            return redirect()->route('mplan.plan_vision'); 
        }
    

        public function editplanvision(Request $request,$id)
        {
        
            $infovision = Planvision::where('VISION_ID','=',$id)->first();

            return view('manager_plan.plan_visionedit',[

                'infovision' => $infovision
            ]);
        }
    
    
        public function updateplanvision(Request $request)
        {

            $ID = $request->VISION_ID;

                $update = Planvision::find($ID);
                $update->VISION_NO = $request->VISION_NO;
                $update->VISION_NAME = $request->VISION_NAME;
                $update->save();
        
        
                return redirect()->route('mplan.plan_vision'); 
            }
        




    public function plan_mission()
    {
        $usevision = Planvision::where('ACTIVE','=','TRUE')->first();
    

        $infomission = Planmission::where('VISION_ID','=',$usevision->VISION_ID)->get();

        return view('manager_plan.plan_mission',[

           'usevision'=> $usevision,
           'infomissions'=> $infomission,

        ]);
    }
    
    public function addplanmission()
    {
        $usevision = Planvision::where('ACTIVE','=','TRUE')->first();
    
        return view('manager_plan.plan_missionadd',[

            'usevision'=> $usevision
           
 
         ]);
    }


    public function saveplanmission(Request $request)
    {
              
        $usevision = Planvision::where('ACTIVE','=','TRUE')->first();
            
            $add = new Planmission(); 
            $add->VISION_ID = $usevision->VISION_ID;
            $add->MISSION_DETAIL = $request->MISSION_DETAIL;
            $add->save();
    
    
            return redirect()->route('mplan.plan_mission'); 
        }



        public function editplanmission(Request $request,$id)
        {
            $usevision = Planvision::where('ACTIVE','=','TRUE')->first();

            $infomission = Planmission::where('MISSION_ID','=',$id)->first();
        
            return view('manager_plan.plan_missionedit',[
    
                'usevision'=> $usevision,
                'infomission'=> $infomission
               
     
             ]);
        }
    
    
        public function updateplanmission(Request $request)
        {
                  
                $ID = $request->MISSION_ID;

                $update = Planmission::find($ID);
              $update->MISSION_DETAIL = $request->MISSION_DETAIL;
                $update->save();
        
        
                return redirect()->route('mplan.plan_mission'); 
            }





    public function plan_strategic()
    {
        $usevision = Planvision::where('ACTIVE','=','TRUE')->first();
    
       
        $infostrategic = Planstrategic::where('VISION_ID','=',$usevision->VISION_ID)->get();

        return view('manager_plan.plan_strategic',[
            'usevision'=> $usevision,
            'infostrategics'=> $infostrategic
           
 
         ]);
    }

    public function plan_strategic_active(Request $request){
        $data = Planstrategic::find($request->idfunc);
        if(!empty($data)){
                $data->ACTIVE = $request->onoff;
                $data->save();
            $result = 1;
        }else{
            $result = 0;
        }
        return $result;
    }

    public function addplanstrategic()
    {
        $usevision = Planvision::where('ACTIVE','=','TRUE')->first();

        $infomission = Planmission::get();
    
        return view('manager_plan.plan_strategicadd',[

            'usevision'=> $usevision,
            'infomissions'=> $infomission
           
 
         ]);
    }


    public function saveplanstrategic(Request $request)
    {
              
        $usevision = Planvision::where('ACTIVE','=','TRUE')->first();
            
            $add = new Planstrategic(); 
            $add->MISSION_ID = $request->MISSION_ID;
            $add->VISION_ID = $usevision->VISION_ID;
            $add->STRATEGIC_NAME = $request->STRATEGIC_NAME;
            $add->STRATEGIC_BEGIN_YEAR = $request->STRATEGIC_BEGIN_YEAR;
            $add->STRATEGIC_END_YEAR = $request->STRATEGIC_END_YEAR;
            $add->save();
    
    
            return redirect()->route('mplan.plan_strategic'); 
        }


        
    public function editplanstrategic(Request $request,$id)
    {
        $usevision = Planvision::where('ACTIVE','=','TRUE')->first();
        
        $infostrategic = Planstrategic::where('STRATEGIC_ID','=',$id)->first();

        $infomission = Planmission::get();
    
        return view('manager_plan.plan_strategicedit',[

            'usevision'=> $usevision,
            'infostrategic'=> $infostrategic,
            'infomissions'=> $infomission
 
         ]);
    }


    public function updateplanstrategic(Request $request)
    {
        $ID = $request->STRATEGIC_ID;

            $update = Planstrategic::find($ID);
            $update->MISSION_ID = $request->MISSION_ID;
            $update->STRATEGIC_NAME = $request->STRATEGIC_NAME;
            $update->STRATEGIC_BEGIN_YEAR = $request->STRATEGIC_BEGIN_YEAR;
            $update->STRATEGIC_END_YEAR = $request->STRATEGIC_END_YEAR;
            $update->save();
    
    
            return redirect()->route('mplan.plan_strategic'); 
    }




//====================================เป้าาประสงค์
 

    public function plan_target(Request $request,$id)
    {
        $infostrategic = Planstrategic::where('STRATEGIC_ID','=',$id)->first();
    
        $infoplantarget = Plantarget::where('STRATEGIC_ID','=',$id)->get();

        return view('manager_plan.plan_target',[
            'infostrategic'=> $infostrategic,
            'infoplantargets'=> $infoplantarget,
         ]);
    }

    public function plan_targetadd(Request $request,$id)
    {
        $infostrategic = Planstrategic::where('STRATEGIC_ID','=',$id)->first();

        return view('manager_plan.plan_targetadd',[
            'infostrategic'=> $infostrategic   
         ]);
    
    
    }

    public function saveplan_target(Request $request)
    {

         $add = new Plantarget();
        $add->STRATEGIC_ID = $request->STRATEGIC_ID;
        $add->TARGET_CODE = $request->TARGET_CODE;
        $add->TARGET_NAME = $request->TARGET_NAME;
        $add->save();

    
        return redirect()->route('mplan.plan_target',[
            'id'=> $request->STRATEGIC_ID 
         ]);
 


    }


    public function plan_targetedit(Request $request,$id,$idref)
    {
        $infostrategic = Planstrategic::where('STRATEGIC_ID','=',$id)->first();
        $infotarget = Plantarget::where('TARGET_ID','=',$idref)->first();

        return view('manager_plan.plan_targetedit',[
            'infostrategic'=> $infostrategic,
            'infotarget'=> $infotarget,
         ]);
    
    }


    public function updateplan_target(Request $request)
    {

        $ID = $request->idref; 

        $update = Plantarget::find($ID);
        $update->TARGET_CODE = $request->TARGET_CODE;
        $update->TARGET_NAME = $request->TARGET_NAME;
        $update->save();

    
        return redirect()->route('mplan.plan_target',[
            'id'=> $request->STRATEGIC_ID 
         ]);

    }


    public function destroyplan_target(Request $request,$id,$idref)
    {

        Plantarget::destroy($idref);  

        return redirect()->route('mplan.plan_target',[
            'id'=> $id
         ]);


    }


//====================================


    public function plan_kpi(Request $request,$id,$idtarget)
    {
        $infoyear = DB::table('plan_year')->first();

        $infostrategic = DB::table('plan_strategic')->where('STRATEGIC_ID','=',$id)->first();
        $infotarget = DB::table('plan_target')->where('TARGET_ID','=',$idtarget)->first();
        $infokpi = DB::table('plan_kpi')->where('TARGET_ID','=',$idtarget)
        ->where('KPI_YEAR','=',$infoyear->PLAN_YEAR)
        ->get();
        
        return view('manager_plan.plan_kpi',[
            'infostrategic'=> $infostrategic,
            'infotarget'=> $infotarget,
            'infokpis'=> $infokpi,
         ]);
    }

    public function plan_kpiadd(Request $request,$id,$idtarget)
    {
        $infostrategic = DB::table('plan_strategic')->where('STRATEGIC_ID','=',$id)->first();
        $infotarget = DB::table('plan_target')->where('TARGET_ID','=',$idtarget)->first();
        $infoplanyear = DB::table('plan_year')->first();

        return view('manager_plan.plan_kpiadd',[
            'infostrategic'=> $infostrategic,
            'infotarget'=> $infotarget,
            'infoplanyear'=> $infoplanyear,
         ]);
    }


    public function saveplan_kpi(Request $request)
    {

         $add = new Plankpi();
         $add->STRATEGIC_ID = $request->STRATEGIC_ID;
         $add->TARGET_ID = $request->TARGET_ID;
         $add->KPI_CODE = $request->KPI_CODE;
         $add->KPI_NAME = $request->KPI_NAME;
         $add->KPI_YEAR = $request->KPI_YEAR;
         $add->save();

    
        return redirect()->route('mplan.plan_kpi',[
            'id'=> $request->STRATEGIC_ID,
            'idtarget' => $request->TARGET_ID
         ]);
 

    }

    public function plan_kpiedit(Request $request,$id,$idtarget,$idref)
    {
        $infostrategic = Planstrategic::where('STRATEGIC_ID','=',$id)->first();
        $infotarget = Plantarget::where('TARGET_ID','=',$idtarget)->first();
        $infokpi = Plankpi::where('KPI_ID','=',$idref)->first();

        return view('manager_plan.plan_kpiedit',[
            'infostrategic'=> $infostrategic,
            'infotarget'=> $infotarget,
            'infokpi'=> $infokpi,
         ]);
    
    }


    public function updateplan_kpi(Request $request)
    {


         $ID = $request->idref; 

         $update = Plankpi::find($ID);
         $update->KPI_CODE = $request->KPI_CODE;
         $update->KPI_NAME = $request->KPI_NAME;
         $update->KPI_YEAR = $request->KPI_YEAR;
         $update->save();

    
        return redirect()->route('mplan.plan_kpi',[
            'id'=> $request->STRATEGIC_ID,
            'idtarget' => $request->TARGET_ID
         ]);

    }


    public function destroyplan_kpi(Request $request,$id,$idtarget,$idref)
    {

        Plankpi::destroy($idref);  

        return redirect()->route('mplan.plan_kpi',[
            'id'=> $id,
            'idtarget' => $idtarget
         ]);


    }

    //====================================


    public function plan_strategic_detail(Request $request,$id)
    {

        $infostrategic = DB::table('plan_strategic')
        ->leftJoin('plan_mission','plan_mission.MISSION_ID','=','plan_strategic.MISSION_ID')
        ->where('STRATEGIC_ID','=',$id)->first();
        $infotarget = DB::table('plan_target')->where('STRATEGIC_ID','=',$id)->get();

    
        return view('manager_plan.plan_strategic_detail',[
            'infostrategic'=>$infostrategic,
            'infotargets'=> $infotarget
        ]);
    }


    
    


    //============================ตัวชี้วัด============
    public function plan_kpiadddetail()
    {

        $infoyear = DB::table('plan_year')->first();

        $infokpi = DB::table('plan_kpi')
        ->leftJoin('plan_strategic','plan_kpi.STRATEGIC_ID','=','plan_strategic.STRATEGIC_ID')
        ->leftJoin('plan_target','plan_kpi.TARGET_ID','=','plan_target.TARGET_ID')
        ->where('plan_kpi.KPI_YEAR','=', $infoyear->PLAN_YEAR)
        ->orderBy('plan_strategic.STRATEGIC_ID', 'asc')
        ->orderBy('plan_target.TARGET_ID', 'asc')
        ->orderBy('plan_kpi.KPI_ID', 'asc')
        ->get();


        $infotarget = DB::table('plan_target')->where('ACTIVE','=','TRUE')->get();
        $infobudgetyear = DB::table('budget_year')->get();
        $planyear = Planyear::first();

        $planyear_check = $planyear->PLAN_YEAR;
        $planyear_use = $planyear->PLAN_YEAR;
        
        return view('manager_plan.plan_kpiadddetail',[
            'infokpis'=> $infokpi,
            'infotargets'=> $infotarget,
            'infobudgetyears'=> $infobudgetyear,
            'planyear'=> $planyear,
            'planyear_check'=> $planyear_check,
            'planyear_use'=> $planyear_use,
          
         ]);
    }
    

    public function plan_kpiadddetail_search(request $request)
    {

         
        $planyear_use = $request->RESULT_YEAR; 

        $infoyear = DB::table('plan_year')->first();

        $infokpi = DB::table('plan_kpi')
        ->leftJoin('plan_strategic','plan_kpi.STRATEGIC_ID','=','plan_strategic.STRATEGIC_ID')
        ->leftJoin('plan_target','plan_kpi.TARGET_ID','=','plan_target.TARGET_ID')
        ->where('plan_kpi.KPI_YEAR','=',  $planyear_use)
        ->orderBy('plan_strategic.STRATEGIC_ID', 'asc')
        ->orderBy('plan_target.TARGET_ID', 'asc')
        ->orderBy('plan_kpi.KPI_ID', 'asc')
        ->get();


        $infotarget = DB::table('plan_target')->where('ACTIVE','=','TRUE')->get();
        $infobudgetyear = DB::table('budget_year')->get();
        $planyear = Planyear::first();

        $planyear_check = $planyear->PLAN_YEAR;
        
        
        return view('manager_plan.plan_kpiadddetail',[
            'infokpis'=> $infokpi,
            'infotargets'=> $infotarget,
            'infobudgetyears'=> $infobudgetyear,
            'planyear'=> $planyear,
            'planyear_check'=> $planyear_check,
            'planyear_use'=> $planyear_use,
          
         ]);
    }
    


    public function  plan_kpidetailfull(Request $request,$idkpi)
    {

        $infokpi = DB::table('plan_kpi')
        ->leftJoin('plan_strategic','plan_kpi.STRATEGIC_ID','=','plan_strategic.STRATEGIC_ID')
        ->leftJoin('plan_target','plan_kpi.TARGET_ID','=','plan_target.TARGET_ID')
        ->where('plan_kpi.KPI_ID', '=',$idkpi)
        ->first();

    
        return view('manager_plan.plan_kpidetailfull',[
            'infokpi'=> $infokpi
          
         ]);
    }

   

    public function plan_kpiupdatedetail(Request $request,$idkpi)
    {

        $infokpi = DB::table('plan_kpi')
        ->leftJoin('plan_strategic','plan_kpi.STRATEGIC_ID','=','plan_strategic.STRATEGIC_ID')
        ->leftJoin('plan_target','plan_kpi.TARGET_ID','=','plan_target.TARGET_ID')
        ->where('plan_kpi.KPI_ID', '=',$idkpi)
        ->first();
    
        $countkpilevel = Plankpilevel::where('KPI_ID','=',$idkpi)->count();
        $infoplankpilevel = Plankpilevel::where('KPI_ID','=',$idkpi)->get();

        $PERSONALL =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
        ->where('HR_STATUS_ID','=',1)
        ->get();


        $countkpiperson = DB::table('plan_kpi_person')->where('KPI_ID','=',$idkpi)->count();
        $infokpiperson = DB::table('plan_kpi_person')->where('KPI_ID','=',$idkpi)->get();
        $planyear = Planyear::first();

        $planyear_check = $planyear->PLAN_YEAR;
        $planyear_use = $planyear->PLAN_YEAR;
        
        return view('manager_plan.plan_kpiupdatedetail',[
            'infokpi'=> $infokpi,
            'countkpilevel'=> $countkpilevel,
            'infoplankpilevels'=> $infoplankpilevel,
            'PERSONALLs'=> $PERSONALL,
            'countkpiperson'=> $countkpiperson,
            'infokpipersons'=> $infokpiperson,
            'planyear'=> $planyear,
            'planyear_check'=> $planyear_check,
            'planyear_use'=> $planyear_use,
         ]);
    }
    

    public function plan_kpisaveupdatedetail(Request $request)
    {





          $ID = $request->KPI_ID;

         $update = Plankpi::find($ID);
         $update->KPI_DETAIL = $request->KPI_DETAIL;
         $update->KPI_UNIT = $request->KPI_UNIT;
         $update->KPI_WEIGHT = $request->KPI_WEIGHT;
         $update->KPI_GOAL = $request->KPI_GOAL;
         $update->KPI_ACCEP = $request->KPI_ACCEP;
         $update->KPI_CRITICAL = $request->KPI_CRITICAL;

         $update->KPI_RESULTS = $request->KPI_RESULTS;
         $update->KPI_SCORE = $request->KPI_SCORE;

         $update->KPI_SCORE_M1 = $request->KPI_SCORE_M1;
         $update->KPI_SCORE_M2 = $request->KPI_SCORE_M2;
         $update->KPI_SCORE_M3 = $request->KPI_SCORE_M3;
         $update->KPI_SCORE_M4 = $request->KPI_SCORE_M4;
         $update->KPI_SCORE_M5 = $request->KPI_SCORE_M5;
         $update->KPI_SCORE_M6 = $request->KPI_SCORE_M6;
         $update->KPI_SCORE_M7 = $request->KPI_SCORE_M7;
         $update->KPI_SCORE_M8 = $request->KPI_SCORE_M8;
         $update->KPI_SCORE_M9 = $request->KPI_SCORE_M9;
         $update->KPI_SCORE_M10 = $request->KPI_SCORE_M10;
         $update->KPI_SCORE_M11 = $request->KPI_SCORE_M11;
         $update->KPI_SCORE_M12 = $request->KPI_SCORE_M12;


         $update->REYEAR_1 = $request->REYEAR_1;
         $update->REYEAR_2 = $request->REYEAR_2;
         $update->REYEAR_3 = $request->REYEAR_3;

         $update->save();


         $KPI_ID = $request->KPI_ID;

         Plankpilevel::where('KPI_ID','=',$KPI_ID)->delete();

         if($request->KPI_LEVEL_NUM[0] != '' || $request->KPI_LEVEL_NUM[0] != null){
            $KPI_LEVEL = $request->KPI_LEVEL;
            $KPI_LEVEL_NUM = $request->KPI_LEVEL_NUM;
            $number =count($KPI_LEVEL);
            $count = 0;
            for($count = 0; $count < $number; $count++)
            {
               $updat = new Plankpilevel();
               $updat->KPI_ID = $KPI_ID;
               $updat->KPI_LEVEL_NUM = $KPI_LEVEL_NUM[$count];
               $updat->KPI_LEVEL = $KPI_LEVEL[$count];
               $updat->save();
            }

         }



         Plankpiperson::where('KPI_ID','=',$KPI_ID)->delete();

         if($request->PERSON_ID[0] != '' || $request->PERSON_ID[0] != null){
            $PERSON_ID = $request->PERSON_ID;
           
            $number =count($PERSON_ID);
            $count = 0;
            for($count = 0; $count < $number; $count++)
            {
               $updat = new Plankpiperson();
               $updat->KPI_ID = $KPI_ID;
               $updat->KPI_PERSON_HR_ID = $PERSON_ID[$count];
               $updat->save();
            }

         }

         

    
        return redirect()->route('mplan.plan_kpiadddetail');
 

    }




    public function plan_setstory_add(Request $request)
    {      

        return view('manager_plan.plan_setstory_add');
    }


    //=============================

    public function project(Request $request)
    {      
        if($request->method() === 'POST'){
            $search         = $request->get('search');
            $yearbudget     = $request->BUDGET_YEAR;
            $datebigin      = $request->get('DATE_BIGIN');
            $dateend        = $request->get('DATE_END');  
            $type           = $request->SEND_TYPE;
            $statusinfo     = $request->SEND_STATUS;
            $data_search = json_encode_u([
                'search' => $search,
                'yearbudget' => $yearbudget,
                'datebigin' => $datebigin,
                'dateend' => $dateend,
                'type' => $type,
                'statusinfo' => $statusinfo,
            ]);
            Cookie::queue('data_search', $data_search, 120,$request->server('REQUEST_URI'));
        }elseif(!empty(Cookie::get('data_search'))){
            $data_search    = json_decode(Cookie::get('data_search'));
            $search     = $data_search->search;
            $yearbudget     = $data_search->yearbudget;
            $datebigin     = $data_search->datebigin;
            $dateend     = $data_search->dateend;
            $type     = $data_search->type;
            $statusinfo     = $data_search->statusinfo;
        }else{
            $search     = '';
            $yearbudget = getBudgetYear();
            $datebigin  = date('01/10/'.($yearbudget-1));
            $dateend    = date('30/09/'.$yearbudget);
            $type       = '';
            $statusinfo       = '';
        }

       
        $date_bigen_c = Carbon::createFromFormat('d/m/Y', $datebigin)->format('Y-m-d');
        $date_arrary=explode("-",$date_bigen_c);
        $y_sub_st = $date_arrary[0];

        if($y_sub_st >= 2500){
            $y = $y_sub_st-543;
        }else{
            $y = $y_sub_st;
        }

        $m = $date_arrary[1];
        $d = $date_arrary[2];  
        $displaydate_bigen= $y."-".$m."-".$d;
  
        $date_end_c = Carbon::createFromFormat('d/m/Y', $dateend)->format('Y-m-d');
        $date_arrary_e=explode("-",$date_end_c); 

        $y_sub_e = $date_arrary_e[0];

        if($y_sub_e >= 2500){
            $y_e = $y_sub_e-543;
        }else{
            $y_e = $y_sub_e;
        }
        $m_e = $date_arrary_e[1];
        $d_e = $date_arrary_e[2];  
        $displaydate_end= $y_e."-".$m_e."-".$d_e;
     

        $from = date($displaydate_bigen);
        $to = date($displaydate_end);

   

         

            $infoproject = DB::table('plan_project')
            ->leftJoin('plan_target','plan_project.TARGET_ID','=','plan_target.TARGET_ID')
            ->leftJoin('plan_kpi','plan_project.KPI_ID','=','plan_kpi.KPI_ID')
            ->leftJoin('plan_type','plan_project.PLAN_TYPE_ID','=','plan_type.PLAN_TYPE_ID')
            ->leftJoin('supplies_budget','plan_project.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->leftJoin('plan_tracking','plan_project.PLAN_TRACKING_ID','=','plan_tracking.PLAN_TRACKING_ID');
            if(!empty($type)){
                $infoproject = $infoproject->where('PRO_TYPE','=',$type);
              }
              if(!empty($statusinfo)){
                $infoproject = $infoproject->where('PRO_STATUS','=',$statusinfo);
              }
                
              $infoproject = $infoproject->where('BUDGET_YEAR','=', $yearbudget)
                ->where(function($q) use ($search){
                 $q->where('PRO_NUMBER','like','%'.$search.'%');
                 $q->orwhere('PRO_TEAM_NAME','like','%'.$search.'%');
                 $q->orwhere('TARGET_CODE','like','%'.$search.'%');
                 $q->orwhere('KPI_CODE','like','%'.$search.'%');
                 $q->orwhere('PLAN_TYPE_NAME','like','%'.$search.'%');
                 $q->orwhere('PRO_NAME','like','%'.$search.'%');
                 $q->orwhere('PRO_TEAM_HR_NAME','like','%'.$search.'%');
                 $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
                 $q->orwhere('PLAN_TRACKING_NAME','like','%'.$search.'%');
            })
            ->WhereBetween('PRO_BEGIN_DATE',[$from,$to]) 
            ->orderBy('PRO_ID', 'asc')->get();
            
            $loopsum = 0;
            foreach ($infoproject as $info){ 
               
                 $loopsum = $loopsum + $info->BUDGET_PICE;
                
                }

            $sumbudget = $loopsum;
            

   

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id =  $yearbudget;
      
        return view('manager_plan.plan_project',[
            'infoprojects' => $infoproject,
            'sumbudget' => $sumbudget,
            'budgets' =>  $budget,
            'search'=> $search,
            'year_id'=>$year_id,
            'type'=>$type, 
            'statusinfo'=>$statusinfo,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            
        ]);
    }



    public function project_search(Request $request)
    {      

        $search = $request->get('search');
        $yearbudget = $request->BUDGET_YEAR;
        $datebigin = $request->get('DATE_BIGIN');
        $dateend = $request->get('DATE_END');  
        $type = $request->SEND_TYPE;
    
      
        if($search==''){
            $search="";
        }


        $date_bigen_c = Carbon::createFromFormat('d/m/Y', $datebigin)->format('Y-m-d');
        $date_arrary=explode("-",$date_bigen_c);
        $y_sub_st = $date_arrary[0];

        if($y_sub_st >= 2500){
            $y = $y_sub_st-543;
        }else{
            $y = $y_sub_st;
        }

        $m = $date_arrary[1];
        $d = $date_arrary[2];  
        $displaydate_bigen= $y."-".$m."-".$d;
  
        $date_end_c = Carbon::createFromFormat('d/m/Y', $dateend)->format('Y-m-d');
        $date_arrary_e=explode("-",$date_end_c); 

        $y_sub_e = $date_arrary_e[0];

        if($y_sub_e >= 2500){
            $y_e = $y_sub_e-543;
        }else{
            $y_e = $y_sub_e;
        }
        $m_e = $date_arrary_e[1];
        $d_e = $date_arrary_e[2];  
        $displaydate_end= $y_e."-".$m_e."-".$d_e;
     

        $from = date($displaydate_bigen);
        $to = date($displaydate_end);

        if($type == null || $type == ''){

          

            $infoproject = DB::table('plan_project')
            ->leftJoin('plan_target','plan_project.TARGET_ID','=','plan_target.TARGET_ID')
            ->leftJoin('plan_kpi','plan_project.KPI_ID','=','plan_kpi.KPI_ID')
            ->leftJoin('plan_type','plan_project.PLAN_TYPE_ID','=','plan_type.PLAN_TYPE_ID')
            ->leftJoin('supplies_budget','plan_project.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->leftJoin('plan_tracking','plan_project.PLAN_TRACKING_ID','=','plan_tracking.PLAN_TRACKING_ID')
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where(function($q) use ($search){
                 $q->where('PRO_NUMBER','like','%'.$search.'%');
                 $q->orwhere('PRO_TEAM_NAME','like','%'.$search.'%');
                 $q->orwhere('TARGET_CODE','like','%'.$search.'%');
                 $q->orwhere('KPI_CODE','like','%'.$search.'%');
                 $q->orwhere('PLAN_TYPE_NAME','like','%'.$search.'%');
                 $q->orwhere('PRO_NAME','like','%'.$search.'%');
                 $q->orwhere('PRO_TEAM_HR_NAME','like','%'.$search.'%');
                 $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
                 $q->orwhere('PLAN_TRACKING_NAME','like','%'.$search.'%');
            })
            ->WhereBetween('PRO_BEGIN_DATE',[$from,$to]) 
            ->orderBy('PRO_ID', 'asc')->get();
                
            
            $sumbudget  = DB::table('plan_project')
            ->leftJoin('plan_target','plan_project.TARGET_ID','=','plan_target.TARGET_ID')
            ->leftJoin('plan_kpi','plan_project.KPI_ID','=','plan_kpi.KPI_ID')
            ->leftJoin('plan_type','plan_project.PLAN_TYPE_ID','=','plan_type.PLAN_TYPE_ID')
            ->leftJoin('supplies_budget','plan_project.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->leftJoin('plan_tracking','plan_project.PLAN_TRACKING_ID','=','plan_tracking.PLAN_TRACKING_ID')
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where(function($q) use ($search){
                $q->where('PRO_NUMBER','like','%'.$search.'%');
                $q->orwhere('PRO_TEAM_NAME','like','%'.$search.'%');
                $q->orwhere('TARGET_CODE','like','%'.$search.'%');
                $q->orwhere('KPI_CODE','like','%'.$search.'%');
                $q->orwhere('PLAN_TYPE_NAME','like','%'.$search.'%');
                $q->orwhere('PRO_NAME','like','%'.$search.'%');
                $q->orwhere('PRO_TEAM_HR_NAME','like','%'.$search.'%');
                $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
                $q->orwhere('PLAN_TRACKING_NAME','like','%'.$search.'%');
           })
           ->WhereBetween('PRO_BEGIN_DATE',[$from,$to]) 
            ->sum('BUDGET_PICE');



        }else{

            $infoproject = DB::table('plan_project')
            ->leftJoin('plan_target','plan_project.TARGET_ID','=','plan_target.TARGET_ID')
            ->leftJoin('plan_kpi','plan_project.KPI_ID','=','plan_kpi.KPI_ID')
            ->leftJoin('plan_type','plan_project.PLAN_TYPE_ID','=','plan_type.PLAN_TYPE_ID')
            ->leftJoin('supplies_budget','plan_project.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->leftJoin('plan_tracking','plan_project.PLAN_TRACKING_ID','=','plan_tracking.PLAN_TRACKING_ID')
            ->where('PRO_TYPE','=',$type)
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where(function($q) use ($search){
                $q->where('PRO_NUMBER','like','%'.$search.'%');
                $q->orwhere('PRO_TEAM_NAME','like','%'.$search.'%');
                $q->orwhere('TARGET_CODE','like','%'.$search.'%');
                $q->orwhere('KPI_CODE','like','%'.$search.'%');
                $q->orwhere('PLAN_TYPE_NAME','like','%'.$search.'%');
                $q->orwhere('PRO_NAME','like','%'.$search.'%');
                $q->orwhere('PRO_TEAM_HR_NAME','like','%'.$search.'%');
                $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
                $q->orwhere('PLAN_TRACKING_NAME','like','%'.$search.'%');
           })
           ->WhereBetween('PRO_BEGIN_DATE',[$from,$to]) 
            ->orderBy('PRO_ID', 'asc')->get();
                
            
            $sumbudget  = DB::table('plan_project')
            ->leftJoin('plan_target','plan_project.TARGET_ID','=','plan_target.TARGET_ID')
            ->leftJoin('plan_kpi','plan_project.KPI_ID','=','plan_kpi.KPI_ID')
            ->leftJoin('plan_type','plan_project.PLAN_TYPE_ID','=','plan_type.PLAN_TYPE_ID')
            ->leftJoin('supplies_budget','plan_project.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->leftJoin('plan_tracking','plan_project.PLAN_TRACKING_ID','=','plan_tracking.PLAN_TRACKING_ID')
            ->where('PRO_TYPE','=',$type)
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where(function($q) use ($search){
                $q->where('PRO_NUMBER','like','%'.$search.'%');
                $q->orwhere('PRO_TEAM_NAME','like','%'.$search.'%');
                $q->orwhere('TARGET_CODE','like','%'.$search.'%');
                $q->orwhere('KPI_CODE','like','%'.$search.'%');
                $q->orwhere('PLAN_TYPE_NAME','like','%'.$search.'%');
                $q->orwhere('PRO_NAME','like','%'.$search.'%');
                $q->orwhere('PRO_TEAM_HR_NAME','like','%'.$search.'%');
                $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
                $q->orwhere('PLAN_TRACKING_NAME','like','%'.$search.'%');
           })
           ->WhereBetween('PRO_BEGIN_DATE',[$from,$to]) 
            ->sum('BUDGET_PICE');

        }

        

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id =  $yearbudget;
      
        return view('manager_plan.plan_project',[
            'infoprojects' => $infoproject,
            'sumbudget' => $sumbudget,
            'budgets' =>  $budget,
            'search'=> $search,
            'year_id'=>$year_id,
            'type'=>$type, 
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
        ]);
    }




    public function project_add(Request $request)
    {      


        $infoyear = DB::table('plan_year')->first();

         if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
            $yearbudget = $infoyear->PLAN_YEAR;
         }else{
            $m_budget = date("m");
            if($m_budget>9){
            $yearbudget = date("Y")+544;
            }else{
            $yearbudget = date("Y")+543;
            }
    
         }

      

        
        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id = $yearbudget;


        $infoplantype =  DB::table('plan_type')->get();

        $infobudgettype =  DB::table('supplies_budget')->get();

        $infotream =  DB::table('hrd_team')->get();

        $infotreamperson =  DB::table('hrd_team_list')->get();

        $infotracking = DB::table('plan_tracking')->get();

        $infostrategic =  DB::table('plan_strategic')->where('ACTIVE','=','TRUE')->get();

        return view('manager_plan.plan_project_add',[
            'budgets' =>  $budget,
            'year_id'=>$year_id,
            'infoplantypes'=>$infoplantype,
            'infobudgettypes'=>$infobudgettype,
            'infotreams'=>$infotream,
            'infotreampersons'=>$infotreamperson,
            'infostrategics'=>$infostrategic,
            'infotrackings'=>$infotracking,

        ]);
    }


    public function project_save(Request $request)
    {

                        $BEGIN_DATE = $request->BEGIN_DATE;


                        if($BEGIN_DATE != ''){
                        $STARTDAY = Carbon::createFromFormat('d/m/Y', $BEGIN_DATE)->format('Y-m-d');
                        $date_arrary_st=explode("-",$STARTDAY);  
                        $y_sub_st = $date_arrary_st[0]; 
                        
                        if($y_sub_st >= 2500){
                            $y_st = $y_sub_st-543;
                        }else{
                            $y_st = $y_sub_st;
                        }
                        $m_st = $date_arrary_st[1];
                        $d_st = $date_arrary_st[2];  
                        $BEGINDATE= $y_st."-".$m_st."-".$d_st;
                        }else{
                        $BEGINDATE= null;
                    }

                    $END_DATE = $request->END_DATE;


                    if($END_DATE != ''){
                    $STARTDAY = Carbon::createFromFormat('d/m/Y', $END_DATE)->format('Y-m-d');
                    $date_arrary_st=explode("-",$STARTDAY);  
                    $y_sub_st = $date_arrary_st[0]; 
                    
                    if($y_sub_st >= 2500){
                        $y_st = $y_sub_st-543;
                    }else{
                        $y_st = $y_sub_st;
                    }
                    $m_st = $date_arrary_st[1];
                    $d_st = $date_arrary_st[2];  
                    $ENDDATE= $y_st."-".$m_st."-".$d_st;
                    }else{
                    $ENDDATE= null;
                }


                $PRO_BEGIN_DATE = $request->PRO_BEGIN_DATE;


                if($PRO_BEGIN_DATE != ''){
                $STARTDAY = Carbon::createFromFormat('d/m/Y', $PRO_BEGIN_DATE)->format('Y-m-d');
                $date_arrary_st=explode("-",$STARTDAY);  
                $y_sub_st = $date_arrary_st[0]; 
                
                if($y_sub_st >= 2500){
                    $y_st = $y_sub_st-543;
                }else{
                    $y_st = $y_sub_st;
                }
                $m_st = $date_arrary_st[1];
                $d_st = $date_arrary_st[2];  
                $PROBEGINDATE= $y_st."-".$m_st."-".$d_st;
                }else{
                $PROBEGINDATE= null;
                }

                $PRO_END_DATE = $request->PRO_END_DATE;


                if($PRO_END_DATE != ''){
                $STARTDAY = Carbon::createFromFormat('d/m/Y', $PRO_END_DATE)->format('Y-m-d');
                $date_arrary_st=explode("-",$STARTDAY);  
                $y_sub_st = $date_arrary_st[0]; 
                
                if($y_sub_st >= 2500){
                    $y_st = $y_sub_st-543;
                }else{
                    $y_st = $y_sub_st;
                }
                $m_st = $date_arrary_st[1];
                $d_st = $date_arrary_st[2];  
                $PROENDDATE= $y_st."-".$m_st."-".$d_st;
                }else{
                $PROENDDATE= null;
                }


                //================สร้ารหัสโปรเจค==================================

                $infoyear = DB::table('plan_year')->first();

                if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
                   $yearbudget = $infoyear->PLAN_YEAR;
                }else{
                   $m_budget = date("m");
                   if($m_budget>9){
                   $yearbudget = date("Y")+544;
                   }else{
                   $yearbudget = date("Y")+543;
                   }
           
                }
        
                $maxnumber = DB::table('plan_project')->where('BUDGET_YEAR','=',$yearbudget)->max('PRO_ID');  
        
             
        
                if($maxnumber != '' ||  $maxnumber != null){
                    
                    $refmax = DB::table('plan_project')->where('PRO_ID','=',$maxnumber)->first();  
        
        
                    if($refmax->PRO_NUMBER != '' ||  $refmax->PRO_NUMBER != null){
                        $maxref = substr($refmax->PRO_NUMBER, -4)+1;
                     }else{
                        $maxref = 1;
                     }
        
                    $ref = str_pad($maxref, 4, "0", STR_PAD_LEFT);
               
                }else{
                    $ref = '0001';
                }
        
                
                $y = substr($yearbudget, -2);
               
        
                $PRO_NUMBER ='P-'.$y.''.$ref;
        

                //==================================================


                    $add = new Planproject();
                    $add->PRO_SAVE_HR_ID = $request->PRO_SAVE_HR_ID;
                    $add->PRO_TYPE = $request->PRO_TYPE;

                    $SAVE_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                    ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                    ->where('hrd_person.ID','=',$request->PRO_SAVE_HR_ID)->first();
                    $add->PRO_SAVE_HR_NAME   = $SAVE_HR_NAME->HR_PREFIX_NAME.''.$SAVE_HR_NAME->HR_FNAME.' '.$SAVE_HR_NAME->HR_LNAME;
        
                    //----------------------------------

                    $add->BUDGET_YEAR = $request->BUDGET_YEAR;
                   
                    if($request->STRATEGIC_ID !== '' || $request->STRATEGIC_ID !== null){
                        $add->STRATEGIC_ID = $request->STRATEGIC_ID;
                    }

                    if($request->TARGET_ID !== '' || $request->TARGET_ID !== null){
                        $add->TARGET_ID = $request->TARGET_ID;
                    }

                    if($request->KPI_ID !== '' || $request->KPI_ID !== null){
                        $add->KPI_ID = $request->KPI_ID;
                    }
                   
                    $add->PRO_NUMBER = $PRO_NUMBER;
                    $add->PRO_NAME = $request->PRO_NAME;
                    $add->PLAN_TYPE_ID = $request->PLAN_TYPE_ID;
                    $add->BUDGET_ID = $request->BUDGET_ID;
                    $add->BUDGET_PICE = $request->BUDGET_PICE;
                    $add->BUDGET_PICE_REAL = $request->BUDGET_PICE_REAL;

                    $add->PRO_BEGIN_DATE = $PROBEGINDATE;
                    $add->PRO_END_DATE = $PROENDDATE;

                    $add->PRO_TEAM_NAME = $request->PRO_TEAM_NAME;

                    $add->PRO_TEAM_HR_ID = $request->PRO_TEAM_HR_ID;
                    $TEAM_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                    ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                    ->where('hrd_person.ID','=',$request->PRO_TEAM_HR_ID)->first();
                    $add->PRO_TEAM_HR_NAME   = $TEAM_HR_NAME->HR_PREFIX_NAME.''.$TEAM_HR_NAME->HR_FNAME.' '.$TEAM_HR_NAME->HR_LNAME;
        
                    //----------------------------------

                    $add->PRO_COMMENT = $request->PRO_COMMENT;
                    $add->PRO_STATUS = 'WAIT';
                    $add->PLAN_TRACKING_ID = $request->PLAN_TRACKING_ID;
                    $add->save();

                
                    return redirect()->route('mplan.project');
 


    }



    //-----------------------------



    public function humandev(Request $request)
    {      
        if($request->method() === 'POST'){
            $search = $request->get('search');
            $yearbudget = $request->BUDGET_YEAR;
            $datebigin = $request->get('DATE_BIGIN');
            $dateend = $request->get('DATE_END');
            $type = $request->SEND_TYPE;
            $statusinfo     = $request->SEND_STATUS;
            $data_search = json_encode_u([
                'search' => $search,
                'yearbudget' => $yearbudget,
                'datebigin' => $datebigin,
                'dateend' => $dateend,
                'type' => $type,
                'statusinfo' => $statusinfo,
            ]);
            Cookie::queue('data_search', $data_search, 120,$request->server('REQUEST_URI'));
        }elseif(!empty(Cookie::get('data_search'))){
            $data_search    = json_decode(Cookie::get('data_search'));
            $search     = $data_search->search;
            $yearbudget     = $data_search->yearbudget;
            $datebigin     = $data_search->datebigin;
            $dateend     = $data_search->dateend;
            $type     = $data_search->type;
            $statusinfo     = $data_search->statusinfo;
        }else{
            $search     = '';
            $yearbudget = getBudgetYear();
            $datebigin  = date('01/10/'.($yearbudget-1));
            $dateend    = date('30/09/'.$yearbudget);
            $type       = '';
            $statusinfo       = '';
        }
    
        $date_bigen_c = Carbon::createFromFormat('d/m/Y', $datebigin)->format('Y-m-d');
        $date_arrary=explode("-",$date_bigen_c);
        $y_sub_st = $date_arrary[0];
        if($y_sub_st >= 2500){
            $y = $y_sub_st-543;
        }else{
            $y = $y_sub_st;
        }
        $m = $date_arrary[1];
        $d = $date_arrary[2];  
        $displaydate_bigen= $y."-".$m."-".$d;
        $date_end_c = Carbon::createFromFormat('d/m/Y', $dateend)->format('Y-m-d');
        $date_arrary_e=explode("-",$date_end_c); 
        $y_sub_e = $date_arrary_e[0];

        if($y_sub_e >= 2500){
            $y_e = $y_sub_e-543;
        }else{
            $y_e = $y_sub_e;
        }
        $m_e = $date_arrary_e[1];
        $d_e = $date_arrary_e[2];  
        $displaydate_end= $y_e."-".$m_e."-".$d_e;
        $from = date($displaydate_bigen);
        $to = date($displaydate_end);

 


            $infohumandev = DB::table('plan_humandev')
            ->leftjoin('plan_humandev_type','plan_humandev.HUM_TYPE_NAME','=','plan_humandev_type.PLAN_HUMANDEV_TYPE_ID')
            ->leftJoin('supplies_budget','plan_humandev.BUDGET_ID','=','supplies_budget.BUDGET_ID');
            if(!empty($type)){
                $infohumandev = $infohumandev->where('HUM_TYPE','=',$type);
              }
              if(!empty($statusinfo)){
                $infohumandev = $infohumandev->where('HUM_STATUS','=',$statusinfo);
              }
                
            $infohumandev = $infohumandev->where('BUDGET_YEAR','=', $yearbudget)
            ->where(function($q) use ($search){
                 $q->where('HUM_NUMBER','like','%'.$search.'%');
                 $q->orwhere('HUM_TEAM_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_TYPE_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_TEAM_HR_NAME','like','%'.$search.'%');
                 $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
            })
            ->WhereBetween('HUM_BEGIN_DATE',[$from,$to]) 
            ->orderBy('HUM_ID', 'asc')->get();
     

            $loopsum = 0;
            foreach ($infohumandev as $info){ 
               
                 $loopsum = $loopsum + $info->BUDGET_PICE;
                
                }

            $sumbudget = $loopsum;

           


        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id =  $yearbudget;
        return view('manager_plan.plan_humandev',[
                    'infohumandevs' => $infohumandev, 
                    'sumbudget' => $sumbudget,
                    'budgets' =>  $budget,
                    'search'=> $search,
                    'displaydate_bigen'=> $displaydate_bigen,
                    'displaydate_end'=> $displaydate_end,
                    'year_id'=>$year_id,
                    'type'=>$type,
                    'statusinfo'=>$statusinfo, 
        ]);
    }


    public function humandev_search(Request $request)
    {      
        $search = $request->get('search');
        $yearbudget = $request->BUDGET_YEAR;
        $datebigin = $request->get('DATE_BIGIN');
        $dateend = $request->get('DATE_END');
        $type = $request->SEND_TYPE;
    
      
        if($search==''){
            $search="";
        }


        $date_bigen_c = Carbon::createFromFormat('d/m/Y', $datebigin)->format('Y-m-d');
        $date_arrary=explode("-",$date_bigen_c);
        $y_sub_st = $date_arrary[0];

        if($y_sub_st >= 2500){
            $y = $y_sub_st-543;
        }else{
            $y = $y_sub_st;
        }

        $m = $date_arrary[1];
        $d = $date_arrary[2];  
        $displaydate_bigen= $y."-".$m."-".$d;
  
        $date_end_c = Carbon::createFromFormat('d/m/Y', $dateend)->format('Y-m-d');
        $date_arrary_e=explode("-",$date_end_c); 

        $y_sub_e = $date_arrary_e[0];

        if($y_sub_e >= 2500){
            $y_e = $y_sub_e-543;
        }else{
            $y_e = $y_sub_e;
        }
        $m_e = $date_arrary_e[1];
        $d_e = $date_arrary_e[2];  
        $displaydate_end= $y_e."-".$m_e."-".$d_e;
     

        $from = date($displaydate_bigen);
        $to = date($displaydate_end);


        if($type == null || $type == ''){

            $infohumandev = DB::table('plan_humandev')
            ->leftjoin('plan_humandev_type','plan_humandev.HUM_TYPE_NAME','=','plan_humandev_type.PLAN_HUMANDEV_TYPE_ID')
            ->leftJoin('supplies_budget','plan_humandev.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where(function($q) use ($search){
                 $q->where('HUM_NUMBER','like','%'.$search.'%');
                 $q->orwhere('HUM_TEAM_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_TYPE_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_TEAM_HR_NAME','like','%'.$search.'%');
                 $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
            })
            ->WhereBetween('HUM_BEGIN_DATE',[$from,$to]) 
            ->orderBy('HUM_ID', 'asc')->get();
     
            $sumbudget = DB::table('plan_humandev')
            ->leftjoin('plan_humandev_type','plan_humandev.HUM_TYPE_NAME','=','plan_humandev_type.PLAN_HUMANDEV_TYPE_ID')
            ->leftJoin('supplies_budget','plan_humandev.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where(function($q) use ($search){
                 $q->where('HUM_NUMBER','like','%'.$search.'%');
                 $q->orwhere('HUM_TEAM_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_TYPE_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_TEAM_HR_NAME','like','%'.$search.'%');
                 $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
               
            })
            ->WhereBetween('HUM_BEGIN_DATE',[$from,$to]) 
            ->SUM('BUDGET_PICE');
    

         


        }else{



             
            $infohumandev = DB::table('plan_humandev')
            ->leftjoin('plan_humandev_type','plan_humandev.HUM_TYPE_NAME','=','plan_humandev_type.PLAN_HUMANDEV_TYPE_ID')
            ->leftJoin('supplies_budget','plan_humandev.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where('HUM_TYPE','=',$type)
            ->where(function($q) use ($search){
                 $q->where('HUM_NUMBER','like','%'.$search.'%');
                 $q->orwhere('HUM_TEAM_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_TYPE_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_TEAM_HR_NAME','like','%'.$search.'%');
                 $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
            })
            ->WhereBetween('HUM_BEGIN_DATE',[$from,$to]) 
            ->orderBy('HUM_ID', 'asc')->get();
     
            $sumbudget = DB::table('plan_humandev')
            ->leftjoin('plan_humandev_type','plan_humandev.HUM_TYPE_NAME','=','plan_humandev_type.PLAN_HUMANDEV_TYPE_ID')
            ->leftJoin('supplies_budget','plan_humandev.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where('HUM_TYPE','=',$type)
            ->where(function($q) use ($search){
                 $q->where('HUM_NUMBER','like','%'.$search.'%');
                 $q->orwhere('HUM_TEAM_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_TYPE_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_NAME','like','%'.$search.'%');
                 $q->orwhere('HUM_TEAM_HR_NAME','like','%'.$search.'%');
                 $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
               
            })
            ->WhereBetween('HUM_BEGIN_DATE',[$from,$to]) 
            ->SUM('BUDGET_PICE');
        
        }

        

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id =  $yearbudget;
        
        
        return view('manager_plan.plan_humandev',[
                    'infohumandevs' => $infohumandev, 
                    'sumbudget' => $sumbudget,
                    'budgets' =>  $budget,
                    'search'=> $search,
                    'displaydate_bigen'=> $displaydate_bigen,
                    'displaydate_end'=> $displaydate_end,
                    'year_id'=>$year_id,
                    'type'=>$type, 
        ]);
    }

    public function humandev_add(Request $request)
    {      

        $infoyear = DB::table('plan_year')->first();

        if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
           $yearbudget = $infoyear->PLAN_YEAR;
        }else{
           $m_budget = date("m");
           if($m_budget>9){
           $yearbudget = date("Y")+544;
           }else{
           $yearbudget = date("Y")+543;
           }
   
        }
        
        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id = $yearbudget;


        $infoplantype =  DB::table('plan_type')->get();

        $infobudgettype =  DB::table('supplies_budget')->get();

        $infotream =  DB::table('hrd_team')->get();

        $infotreamperson =  DB::table('hrd_team_list')->get();

        $infostrategic =  DB::table('plan_strategic')->where('ACTIVE','=','TRUE')->get();


        $infohumandevtype =  DB::table('plan_humandev_type')->get();
        
        return view('manager_plan.plan_humandev_add',[
            'budgets' =>  $budget,
            'year_id'=>$year_id,
            'infoplantypes'=>$infoplantype,
            'infobudgettypes'=>$infobudgettype,
            'infotreams'=>$infotream,
            'infotreampersons'=>$infotreamperson,
            'infostrategics'=>$infostrategic,
            'infohumandevtypes'=>$infohumandevtype,


        ]);
    }



    public function humandev_save(Request $request)
    {

                        $BEGIN_DATE = $request->BEGIN_DATE;


                        if($BEGIN_DATE != ''){
                        $STARTDAY = Carbon::createFromFormat('d/m/Y', $BEGIN_DATE)->format('Y-m-d');
                        $date_arrary_st=explode("-",$STARTDAY);  
                        $y_sub_st = $date_arrary_st[0]; 
                        
                        if($y_sub_st >= 2500){
                            $y_st = $y_sub_st-543;
                        }else{
                            $y_st = $y_sub_st;
                        }
                        $m_st = $date_arrary_st[1];
                        $d_st = $date_arrary_st[2];  
                        $BEGINDATE= $y_st."-".$m_st."-".$d_st;
                        }else{
                        $BEGINDATE= null;
                    }

                    $END_DATE = $request->END_DATE;


                    if($END_DATE != ''){
                    $STARTDAY = Carbon::createFromFormat('d/m/Y', $END_DATE)->format('Y-m-d');
                    $date_arrary_st=explode("-",$STARTDAY);  
                    $y_sub_st = $date_arrary_st[0]; 
                    
                    if($y_sub_st >= 2500){
                        $y_st = $y_sub_st-543;
                    }else{
                        $y_st = $y_sub_st;
                    }
                    $m_st = $date_arrary_st[1];
                    $d_st = $date_arrary_st[2];  
                    $ENDDATE= $y_st."-".$m_st."-".$d_st;
                    }else{
                    $ENDDATE= null;
                }


                $HUM_BEGIN_DATE = $request->HUM_BEGIN_DATE;


                if($HUM_BEGIN_DATE != ''){
                $STARTDAY = Carbon::createFromFormat('d/m/Y', $HUM_BEGIN_DATE)->format('Y-m-d');
                $date_arrary_st=explode("-",$STARTDAY);  
                $y_sub_st = $date_arrary_st[0]; 
                
                if($y_sub_st >= 2500){
                    $y_st = $y_sub_st-543;
                }else{
                    $y_st = $y_sub_st;
                }
                $m_st = $date_arrary_st[1];
                $d_st = $date_arrary_st[2];  
                $HUMBEGINDATE= $y_st."-".$m_st."-".$d_st;
                }else{
                $HUMBEGINDATE= null;
                }

                $HUM_END_DATE = $request->HUM_END_DATE;


                if($HUM_END_DATE != ''){
                $STARTDAY = Carbon::createFromFormat('d/m/Y', $HUM_END_DATE)->format('Y-m-d');
                $date_arrary_st=explode("-",$STARTDAY);  
                $y_sub_st = $date_arrary_st[0]; 
                
                if($y_sub_st >= 2500){
                    $y_st = $y_sub_st-543;
                }else{
                    $y_st = $y_sub_st;
                }
                $m_st = $date_arrary_st[1];
                $d_st = $date_arrary_st[2];  
                $HUMENDDATE= $y_st."-".$m_st."-".$d_st;
                }else{
                $HUMENDDATE= null;
                }


                //==============================สร้างรหัส=====

                $infoyear = DB::table('plan_year')->first();

                if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
                   $yearbudget = $infoyear->PLAN_YEAR;
                }else{
                   $m_budget = date("m");
                   if($m_budget>9){
                   $yearbudget = date("Y")+544;
                   }else{
                   $yearbudget = date("Y")+543;
                   }
           
                }
        
                $maxnumber = DB::table('plan_humandev')->where('BUDGET_YEAR','=',$yearbudget)->max('HUM_ID');  
        
             
        
                if($maxnumber != '' ||  $maxnumber != null){
                    
                    $refmax = DB::table('plan_humandev')->where('HUM_ID','=',$maxnumber)->first();  
        
        
                    if($refmax->HUM_NUMBER != '' ||  $refmax->HUM_NUMBER != null){
                        $maxref = substr($refmax->HUM_NUMBER, -4)+1;
                     }else{
                        $maxref = 1;
                     }
        
                    $ref = str_pad($maxref, 4, "0", STR_PAD_LEFT);
               
                }else{
                    $ref = '0001';
                }
        
                $y = substr($yearbudget, -2);
               
        
             $HUM_NUMBER ='H-'.$y.''.$ref;

                //==========================================


                    $add = new Planhumandev();
                    $add->HUM_SAVE_HR_ID = $request->HUM_SAVE_HR_ID;
                    $add->PLAN_TYPE_ID = $request->PLAN_TYPE_ID;
                    $add->HUM_TYPE = $request->HUM_TYPE;

                    $SAVE_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                    ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                    ->where('hrd_person.ID','=',$request->HUM_SAVE_HR_ID)->first();
                    $add->HUM_SAVE_HR_NAME   = $SAVE_HR_NAME->HR_PREFIX_NAME.''.$SAVE_HR_NAME->HR_FNAME.' '.$SAVE_HR_NAME->HR_LNAME;
        
                    //----------------------------------

                    $add->BUDGET_YEAR = $request->BUDGET_YEAR;


                    if($request->STRATEGIC_ID !== '' || $request->STRATEGIC_ID !== null){
                        $add->STRATEGIC_ID = $request->STRATEGIC_ID;
                    }

                    if($request->TARGET_ID !== '' || $request->TARGET_ID !== null){
                        $add->TARGET_ID = $request->TARGET_ID;
                    }

                    if($request->KPI_ID !== '' || $request->KPI_ID !== null){
                        $add->KPI_ID = $request->KPI_ID;
                    }
                   


                    $add->HUM_NUMBER = $HUM_NUMBER;
                    $add->HUM_NAME = $request->HUM_NAME;
                    $add->HUM_TYPE_NAME = $request->HUM_TYPE_NAME;
                    $add->BUDGET_ID = $request->BUDGET_ID;
                    $add->BUDGET_PICE = $request->BUDGET_PICE;
                    $add->BUDGET_PICE_REAL = $request->BUDGET_PICE_REAL;

                    $add->BEGIN_DATE = $BEGINDATE;
                    $add->END_DATE = $ENDDATE;
                    $add->HUM_BEGIN_DATE = $HUMBEGINDATE;
                    $add->HUM_END_DATE = $HUMENDDATE;

                    $add->HUM_GROUP = $request->HUM_GROUP;
                    $add->HUM_LOCATION = $request->HUM_LOCATION;
                    $add->HUM_EXPERT = $request->HUM_EXPERT;
                    
                    $add->HUM_TEAM_NAME = $request->HUM_TEAM_NAME;

                    $add->HUM_TEAM_HR_ID = $request->HUM_TEAM_HR_ID;
                    $TEAM_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                    ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                    ->where('hrd_person.ID','=',$request->HUM_TEAM_HR_ID)->first();
                    $add->HUM_TEAM_HR_NAME   = $TEAM_HR_NAME->HR_PREFIX_NAME.''.$TEAM_HR_NAME->HR_FNAME.' '.$TEAM_HR_NAME->HR_LNAME;
        
                    //----------------------------------

                    $add->HUM_COMMENT = $request->HUM_COMMENT;
                    $add->HUM_STATUS = 'WAIT';
                    $add->save();

                
                    return redirect()->route('mplan.humandev');
 


    }





    public function durable(Request $request)
    {     
        if($request->method() === 'POST'){
            $search = $request->get('search');
            $yearbudget = $request->BUDGET_YEAR;
            $datebigin = $request->get('DATE_BIGIN');
            $dateend = $request->get('DATE_END');
            $type = $request->SEND_TYPE;
            $statusinfo     = $request->SEND_STATUS;
            $data_search = json_encode_u([
                'search' => $search,
                'yearbudget' => $yearbudget,
                'datebigin' => $datebigin,
                'dateend' => $dateend,
                'type' => $type,
                'statusinfo' => $statusinfo,
            ]);
            Cookie::queue('data_search', $data_search, 120,$request->server('REQUEST_URI'));
        }elseif(!empty(Cookie::get('data_search'))){
            $data_search    = json_decode(Cookie::get('data_search'));
            $search     = $data_search->search;
            $yearbudget     = $data_search->yearbudget;
            $datebigin     = $data_search->datebigin;
            $dateend     = $data_search->dateend;
            $type     = $data_search->type;
            $statusinfo     = $data_search->statusinfo;
        }else{
            $search     = '';
            $yearbudget = getBudgetYear();
            $datebigin  = date('01/10/'.($yearbudget-1));
            $dateend    = date('30/09/'.$yearbudget);
            $type       = '';
            $statusinfo       = '';
        }
    
        $date_bigen_c = Carbon::createFromFormat('d/m/Y', $datebigin)->format('Y-m-d');
        $date_arrary=explode("-",$date_bigen_c);
        $y_sub_st = $date_arrary[0];
        if($y_sub_st >= 2500){
            $y = $y_sub_st-543;
        }else{
            $y = $y_sub_st;
        }
        $m = $date_arrary[1];
        $d = $date_arrary[2];  
        $displaydate_bigen= $y."-".$m."-".$d;
        $date_end_c = Carbon::createFromFormat('d/m/Y', $dateend)->format('Y-m-d');
        $date_arrary_e=explode("-",$date_end_c); 
        $y_sub_e = $date_arrary_e[0];
        if($y_sub_e >= 2500){
            $y_e = $y_sub_e-543;
        }else{
            $y_e = $y_sub_e;
        }
        $m_e = $date_arrary_e[1];
        $d_e = $date_arrary_e[2];  
        $displaydate_end= $y_e."-".$m_e."-".$d_e;
        $from = date($displaydate_bigen);
        $to = date($displaydate_end);

    
            $infodurable = DB::table('plan_durable')
            ->leftJoin('supplies_budget','plan_durable.BUDGET_ID','=','supplies_budget.BUDGET_ID');
            if(!empty($type)){
                $infodurable = $infodurable->where('DUR_TYPE','=',$type);
              }
              if(!empty($statusinfo)){
                $infodurable = $infodurable->where('DUR_STATUS','=',$statusinfo);
              }
                
              $infodurable = $infodurable->where('BUDGET_YEAR','=', $yearbudget)
            ->where(function($q) use ($search){
                 $q->where('DUR_NUMBER','like','%'.$search.'%');
                 $q->orwhere('DUR_TEAM_NAME','like','%'.$search.'%');
                 $q->orwhere('DUR_ASS_NAME','like','%'.$search.'%');
                 $q->orwhere('DUR_TEAM_HR_NAME','like','%'.$search.'%');
                 $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
            })
            ->WhereBetween('DUR_ASS_BEGIN_DATE',[$from,$to]) 
            ->orderBy('DUR_ID', 'asc')->get();
          


            $loopsum = 0;
            foreach ($infodurable as $info){ 
               
                 $loopsum = $loopsum + $info->DUR_PICE_SUM;
                
                }

            $sumbudget = $loopsum;

            
      
       
        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id =  $yearbudget;
        return view('manager_plan.plan_durable',[
            'infodurables' =>  $infodurable,
            'sumbudget' => $sumbudget,
            'budgets' =>  $budget,
            'search'=> $search,
            'year_id'=>$year_id,  
            'statusinfo'=>$statusinfo,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'type'=>$type,   
        ]);
    }

    

    public function durable_search(Request $request)
    {      
        $search = $request->get('search');
        $yearbudget = $request->BUDGET_YEAR;
        $datebigin = $request->get('DATE_BIGIN');
        $dateend = $request->get('DATE_END');
        $type = $request->SEND_TYPE;
    
      
        if($search==''){
            $search="";
        }


        $date_bigen_c = Carbon::createFromFormat('d/m/Y', $datebigin)->format('Y-m-d');
        $date_arrary=explode("-",$date_bigen_c);
        $y_sub_st = $date_arrary[0];

        if($y_sub_st >= 2500){
            $y = $y_sub_st-543;
        }else{
            $y = $y_sub_st;
        }

        $m = $date_arrary[1];
        $d = $date_arrary[2];  
        $displaydate_bigen= $y."-".$m."-".$d;
  
        $date_end_c = Carbon::createFromFormat('d/m/Y', $dateend)->format('Y-m-d');
        $date_arrary_e=explode("-",$date_end_c); 

        $y_sub_e = $date_arrary_e[0];

        if($y_sub_e >= 2500){
            $y_e = $y_sub_e-543;
        }else{
            $y_e = $y_sub_e;
        }
        $m_e = $date_arrary_e[1];
        $d_e = $date_arrary_e[2];  
        $displaydate_end= $y_e."-".$m_e."-".$d_e;
     

        $from = date($displaydate_bigen);
        $to = date($displaydate_end);


        if($type == null || $type == ''){


            $infodurable = DB::table('plan_durable')
            ->leftJoin('supplies_budget','plan_durable.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where(function($q) use ($search){
                 $q->where('DUR_NUMBER','like','%'.$search.'%');
                 $q->orwhere('DUR_TEAM_NAME','like','%'.$search.'%');
                 $q->orwhere('DUR_ASS_NAME','like','%'.$search.'%');
                 $q->orwhere('DUR_TEAM_HR_NAME','like','%'.$search.'%');
                 $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
            })
            ->WhereBetween('DUR_ASS_BEGIN_DATE',[$from,$to]) 
            ->orderBy('DUR_ID', 'asc')->get();

            $sumbudget = DB::table('plan_durable')
            ->leftJoin('supplies_budget','plan_durable.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where(function($q) use ($search){
                 $q->where('DUR_NUMBER','like','%'.$search.'%');
                 $q->orwhere('DUR_TEAM_NAME','like','%'.$search.'%');
                 $q->orwhere('DUR_ASS_NAME','like','%'.$search.'%');
                 $q->orwhere('DUR_TEAM_HR_NAME','like','%'.$search.'%');
                 $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
            })
            ->WhereBetween('DUR_ASS_BEGIN_DATE',[$from,$to]) 
            ->SUM('DUR_PICE_SUM');


        }else{

           

            $infodurable = DB::table('plan_durable')
            ->leftJoin('supplies_budget','plan_durable.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where('DUR_TYPE','=',$type)
            ->where(function($q) use ($search){
                 $q->where('DUR_NUMBER','like','%'.$search.'%');
                 $q->orwhere('DUR_TEAM_NAME','like','%'.$search.'%');
                 $q->orwhere('DUR_ASS_NAME','like','%'.$search.'%');
                 $q->orwhere('DUR_TEAM_HR_NAME','like','%'.$search.'%');
                 $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
            })
            ->WhereBetween('DUR_ASS_BEGIN_DATE',[$from,$to]) 
            ->orderBy('DUR_ID', 'asc')->get();

            $sumbudget = DB::table('plan_durable')
            ->leftJoin('supplies_budget','plan_durable.BUDGET_ID','=','supplies_budget.BUDGET_ID')
            ->where('BUDGET_YEAR','=', $yearbudget)
            ->where('DUR_TYPE','=',$type)
            ->where(function($q) use ($search){
                $q->where('DUR_NUMBER','like','%'.$search.'%');
                $q->orwhere('DUR_TEAM_NAME','like','%'.$search.'%');
                $q->orwhere('DUR_ASS_NAME','like','%'.$search.'%');
                $q->orwhere('DUR_TEAM_HR_NAME','like','%'.$search.'%');
                $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
           })
            ->WhereBetween('DUR_ASS_BEGIN_DATE',[$from,$to]) 
            ->SUM('DUR_PICE_SUM');

        
        }

        

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id =  $yearbudget;
        
        
        return view('manager_plan.plan_durable',[
            'infodurables' =>  $infodurable,
            'sumbudget' => $sumbudget,
            'budgets' =>  $budget,
            'search'=> $search,
            'year_id'=>$year_id,  
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'type'=>$type,   
        ]);
    }

    public function durable_add(Request $request)
    {      

        $infoyear = DB::table('plan_year')->first();

        if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
           $yearbudget = $infoyear->PLAN_YEAR;
        }else{
           $m_budget = date("m");
           if($m_budget>9){
           $yearbudget = date("Y")+544;
           }else{
           $yearbudget = date("Y")+543;
           }
   
        }
        
        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id = $yearbudget;



        $infobudgettype =  DB::table('supplies_budget')->get();

        $infotream =  DB::table('hrd_team')->get();

        $infotreamperson =  DB::table('hrd_team_list')->get();
        $infostrategic =  DB::table('plan_strategic')->where('ACTIVE','=','TRUE')->get();

        $infoplantype =  DB::table('plan_type')->get();

        $suppliesprop = DB::table('supplies_prop')->get();

        $assetarticle = DB::table('asset_article')->get();


        return view('manager_plan.plan_durable_add',[
            'budgets' =>  $budget,
            'year_id'=>$year_id,
            'infoplantypes'=>$infoplantype,
            'infobudgettypes'=>$infobudgettype,
            'infotreams'=>$infotream,
            'infotreampersons'=>$infotreamperson,
            'infostrategics'=>$infostrategic,
            'suppliesprops'=>$suppliesprop,
            'assetarticles'=>$assetarticle



        ]);
    }


    

    public function durable_save(Request $request)
    {


                    $DUR_ASS_BEGIN_DATE = $request->DUR_ASS_BEGIN_DATE;


                    if($DUR_ASS_BEGIN_DATE != ''){
                    $STARTDAY = Carbon::createFromFormat('d/m/Y', $DUR_ASS_BEGIN_DATE)->format('Y-m-d');
                    $date_arrary_st=explode("-",$STARTDAY);  
                    $y_sub_st = $date_arrary_st[0]; 
                    
                    if($y_sub_st >= 2500){
                        $y_st = $y_sub_st-543;
                    }else{
                        $y_st = $y_sub_st;
                    }
                    $m_st = $date_arrary_st[1];
                    $d_st = $date_arrary_st[2];  
                    $DURASSBEGINDATE= $y_st."-".$m_st."-".$d_st;
                    }else{
                    $DURASSBEGINDATE= null;
                    }

                    $DUR_ASS_END_DATE = $request->DUR_ASS_END_DATE;


                    if($DUR_ASS_END_DATE != ''){
                    $STARTDAY = Carbon::createFromFormat('d/m/Y', $DUR_ASS_END_DATE)->format('Y-m-d');
                    $date_arrary_st=explode("-",$STARTDAY);  
                    $y_sub_st = $date_arrary_st[0]; 
                    
                    if($y_sub_st >= 2500){
                        $y_st = $y_sub_st-543;
                    }else{
                        $y_st = $y_sub_st;
                    }
                    $m_st = $date_arrary_st[1];
                    $d_st = $date_arrary_st[2];  
                    $DURASSENDDATE= $y_st."-".$m_st."-".$d_st;
                    }else{
                    $DURASSENDDATE= null;
                    }

                    //========================สร้างรหัส======================
                    $infoyear = DB::table('plan_year')->first();

                    if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
                       $yearbudget = $infoyear->PLAN_YEAR;
                    }else{
                       $m_budget = date("m");
                       if($m_budget>9){
                       $yearbudget = date("Y")+544;
                       }else{
                       $yearbudget = date("Y")+543;
                       }
               
                    }
            
                    $maxnumber = DB::table('plan_durable')->where('BUDGET_YEAR','=',$yearbudget)->max('DUR_ID');  
            
                 
            
                    if($maxnumber != '' ||  $maxnumber != null){
                        
                        $refmax = DB::table('plan_durable')->where('DUR_ID','=',$maxnumber)->first();  
            
            
                        if($refmax->DUR_NUMBER != '' ||  $refmax->DUR_NUMBER != null){
                            $maxref = substr($refmax->DUR_NUMBER, -4)+1;
                         }else{
                            $maxref = 1;
                         }
            
                        $ref = str_pad($maxref, 4, "0", STR_PAD_LEFT);
                   
                    }else{
                        $ref = '0001';
                    }
            
        
                    $y = substr($yearbudget, -2);
                   
            
                 $DUR_NUMBER ='A-'.$y.''.$ref;

                    //==================================================

                    $add = new Plandurable();
                    $add->DUR_SAVE_HR_ID = $request->DUR_SAVE_HR_ID;
                    $add->PLAN_TYPE_ID = $request->PLAN_TYPE_ID;
                    $add->DUR_TYPE = $request->DUR_TYPE;

                    

                    $SAVE_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                    ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                    ->where('hrd_person.ID','=',$request->DUR_SAVE_HR_ID)->first();
                    $add->DUR_SAVE_HR_NAME   = $SAVE_HR_NAME->HR_PREFIX_NAME.''.$SAVE_HR_NAME->HR_FNAME.' '.$SAVE_HR_NAME->HR_LNAME;
        
                    //----------------------------------

                    $add->BUDGET_YEAR = $request->BUDGET_YEAR;
              
                    if($request->STRATEGIC_ID !== '' || $request->STRATEGIC_ID !== null){
                        $add->STRATEGIC_ID = $request->STRATEGIC_ID;
                    }

                    if($request->TARGET_ID !== '' || $request->TARGET_ID !== null){
                        $add->TARGET_ID = $request->TARGET_ID;
                    }

                    if($request->KPI_ID !== '' || $request->KPI_ID !== null){
                        $add->KPI_ID = $request->KPI_ID;
                    }

                    $add->DUR_NUMBER = $DUR_NUMBER;
                    $add->DUR_NAME = $request->DUR_NAME;
                    $add->DUR_ASS_NAME = $request->DUR_ASS_NAME;
                    $add->DUR_ASS_PICE_UNIT = $request->DUR_ASS_PICE_UNIT;


                    $add->BUDGET_ID = $request->BUDGET_ID;
                    $add->BUDGET_PICE = $request->BUDGET_PICE;

                    $add->DUR_ASS_BEGIN_DATE = $DURASSBEGINDATE;
                    $add->DUR_ASS_END_DATE = $DURASSENDDATE; 
                    
                    $add->DUR_TEAM_NAME = $request->DUR_TEAM_NAME;

                    $add->DUR_TEAM_HR_ID = $request->DUR_TEAM_HR_ID;
                    $TEAM_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                    ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                    ->where('hrd_person.ID','=',$request->DUR_TEAM_HR_ID)->first();
                    $add->DUR_TEAM_HR_NAME   = $TEAM_HR_NAME->HR_PREFIX_NAME.''.$TEAM_HR_NAME->HR_FNAME.' '.$TEAM_HR_NAME->HR_LNAME;
        
                    //----------------------------------

                    $add->DUR_COMMENT = $request->DUR_COMMENT;


                    $add->DUR_FSN = $request->DUR_FSN;
                    $add->DUR_REF = $request->DUR_REF;
                    $add->DUR_REF_CODE = $request->DUR_REF_CODE;
                    $add->DUR_SERVICE = $request->DUR_SERVICE;
                    $add->DUR_HASTE = $request->DUR_HASTE;
                    $add->DUR_REASON_ID = $request->DUR_REASON_ID;
                    $add->DUR_ASS_OLD = $request->DUR_ASS_OLD;
                    $add->DUR_ASS_AMOUNT = $request->DUR_ASS_AMOUNT;
                    $add->BUDGET_PICE_REAL = $request->BUDGET_PICE_REAL;

                    $add->DUR_PICE_SUM = $request->DUR_ASS_AMOUNT*$request->DUR_ASS_PICE_UNIT;
                    $add->DUR_STATUS = 'WAIT';
                    $add->save();

                
                    return redirect()->route('mplan.durable');
 


    }

    //===============================

    public function project_edit(Request $request,$idref)
    {      


        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }
        
        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id = $yearbudget;


        $infoplantype =  DB::table('plan_type')->get();

        $infobudgettype =  DB::table('supplies_budget')->get();

        $infotream =  DB::table('hrd_team')->get();

        $infotreamperson =  DB::table('hrd_team_list')->get();

        $infostrategic =  DB::table('plan_strategic')->where('ACTIVE','=','TRUE')->get();


        $infoproject =  Planproject::leftjoin('plan_target','plan_target.TARGET_ID','=','plan_project.TARGET_ID')
        ->leftjoin('plan_kpi','plan_kpi.KPI_ID','=','plan_project.KPI_ID')
        ->leftjoin('hrd_person','plan_project.PRO_TEAM_HR_ID','=','hrd_person.ID')
        ->where('PRO_ID','=',$idref)
        ->first();
 
        $infotracking = DB::table('plan_tracking')->get();

        return view('manager_plan.plan_project_edit',[
            'budgets' =>  $budget,
            'year_id'=>$year_id,
            'infoplantypes'=>$infoplantype,
            'infobudgettypes'=>$infobudgettype,
            'infotreams'=>$infotream,
            'infotreampersons'=>$infotreamperson,
            'infostrategics'=>$infostrategic,
            'infoproject'=>$infoproject,
            'infotrackings'=>$infotracking,


        ]);
    }


    public function project_update(Request $request)
    {

                        $BEGIN_DATE = $request->BEGIN_DATE;


                        if($BEGIN_DATE != ''){
                        $STARTDAY = Carbon::createFromFormat('d/m/Y', $BEGIN_DATE)->format('Y-m-d');
                        $date_arrary_st=explode("-",$STARTDAY);  
                        $y_sub_st = $date_arrary_st[0]; 
                        
                        if($y_sub_st >= 2500){
                            $y_st = $y_sub_st-543;
                        }else{
                            $y_st = $y_sub_st;
                        }
                        $m_st = $date_arrary_st[1];
                        $d_st = $date_arrary_st[2];  
                        $BEGINDATE= $y_st."-".$m_st."-".$d_st;
                        }else{
                        $BEGINDATE= null;
                    }

                    $END_DATE = $request->END_DATE;


                    if($END_DATE != ''){
                    $STARTDAY = Carbon::createFromFormat('d/m/Y', $END_DATE)->format('Y-m-d');
                    $date_arrary_st=explode("-",$STARTDAY);  
                    $y_sub_st = $date_arrary_st[0]; 
                    
                    if($y_sub_st >= 2500){
                        $y_st = $y_sub_st-543;
                    }else{
                        $y_st = $y_sub_st;
                    }
                    $m_st = $date_arrary_st[1];
                    $d_st = $date_arrary_st[2];  
                    $ENDDATE= $y_st."-".$m_st."-".$d_st;
                    }else{
                    $ENDDATE= null;
                }


                $PRO_BEGIN_DATE = $request->PRO_BEGIN_DATE;


                if($PRO_BEGIN_DATE != ''){
                $STARTDAY = Carbon::createFromFormat('d/m/Y', $PRO_BEGIN_DATE)->format('Y-m-d');
                $date_arrary_st=explode("-",$STARTDAY);  
                $y_sub_st = $date_arrary_st[0]; 
                
                if($y_sub_st >= 2500){
                    $y_st = $y_sub_st-543;
                }else{
                    $y_st = $y_sub_st;
                }
                $m_st = $date_arrary_st[1];
                $d_st = $date_arrary_st[2];  
                $PROBEGINDATE= $y_st."-".$m_st."-".$d_st;
                }else{
                $PROBEGINDATE= null;
                }

                $PRO_END_DATE = $request->PRO_END_DATE;


                if($PRO_END_DATE != ''){
                $STARTDAY = Carbon::createFromFormat('d/m/Y', $PRO_END_DATE)->format('Y-m-d');
                $date_arrary_st=explode("-",$STARTDAY);  
                $y_sub_st = $date_arrary_st[0]; 
                
                if($y_sub_st >= 2500){
                    $y_st = $y_sub_st-543;
                }else{
                    $y_st = $y_sub_st;
                }
                $m_st = $date_arrary_st[1];
                $d_st = $date_arrary_st[2];  
                $PROENDDATE= $y_st."-".$m_st."-".$d_st;
                }else{
                $PROENDDATE= null;
                }


                  $id = $request->PRO_ID;


                    $add = Planproject::find($id);
                    $add->PRO_SAVE_HR_ID = $request->PRO_SAVE_HR_ID;
                    $add->PRO_TYPE = $request->PRO_TYPE;

                    $SAVE_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                    ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                    ->where('hrd_person.ID','=',$request->PRO_SAVE_HR_ID)->first();
                    $add->PRO_SAVE_HR_NAME   = $SAVE_HR_NAME->HR_PREFIX_NAME.''.$SAVE_HR_NAME->HR_FNAME.' '.$SAVE_HR_NAME->HR_LNAME;
        
                    //----------------------------------

                    $add->BUDGET_YEAR = $request->BUDGET_YEAR;
                   
                    if($request->STRATEGIC_ID !== '' || $request->STRATEGIC_ID !== null){
                        $add->STRATEGIC_ID = $request->STRATEGIC_ID;
                    }

                    if($request->TARGET_ID !== '' || $request->TARGET_ID !== null){
                        $add->TARGET_ID = $request->TARGET_ID;
                    }

                    if($request->KPI_ID !== '' || $request->KPI_ID !== null){
                        $add->KPI_ID = $request->KPI_ID;
                    }
                   
                    $add->PRO_NUMBER = $request->PRO_NUMBER;
                    $add->PRO_NAME = $request->PRO_NAME;
                    $add->PLAN_TYPE_ID = $request->PLAN_TYPE_ID;
                    $add->BUDGET_ID = $request->BUDGET_ID;
                    $add->BUDGET_PICE = $request->BUDGET_PICE;
                    $add->BUDGET_PICE_REAL = $request->BUDGET_PICE_REAL;

                    $add->PRO_BEGIN_DATE = $PROBEGINDATE;
                    $add->PRO_END_DATE = $PROENDDATE;

                    $add->PRO_TEAM_NAME = $request->PRO_TEAM_NAME;

                    $add->PRO_TEAM_HR_ID = $request->PRO_TEAM_HR_ID;
                    $TEAM_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                    ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                    ->where('hrd_person.ID','=',$request->PRO_TEAM_HR_ID)->first();
                    $add->PRO_TEAM_HR_NAME   = $TEAM_HR_NAME->HR_PREFIX_NAME.''.$TEAM_HR_NAME->HR_FNAME.' '.$TEAM_HR_NAME->HR_LNAME;
        
                    //----------------------------------

                    $add->PRO_COMMENT = $request->PRO_COMMENT;
                    $add->PLAN_TRACKING_ID = $request->PLAN_TRACKING_ID;
                
                    $add->save();

                
                    return redirect()->route('mplan.project');
 


    }



    //-------------------------

    
    public function humandev_edit(Request $request,$idref)
    {      

        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }
        
        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id = $yearbudget;


        $infoplantype =  DB::table('plan_type')->get();

        $infobudgettype =  DB::table('supplies_budget')->get();

        $infotream =  DB::table('hrd_team')->get();

        $infotreamperson =  DB::table('hrd_team_list')->get();

        $infostrategic =  DB::table('plan_strategic')->where('ACTIVE','=','TRUE')->get();

        $infohum =  Planhumandev::leftjoin('plan_target','plan_target.TARGET_ID','=','plan_humandev.TARGET_ID')
        ->leftjoin('plan_kpi','plan_kpi.KPI_ID','=','plan_humandev.KPI_ID')
        ->leftjoin('hrd_person','plan_humandev.HUM_TEAM_HR_ID','=','hrd_person.ID')
        ->where('HUM_ID','=',$idref)
        ->first();
        $person = DB::table('hrd_person')->orWhere('HR_STATUS_ID',1)->get(); //1 = ทำงานปกติ    

        $infohumandevtype =  DB::table('plan_humandev_type')->get();
         
        return view('manager_plan.plan_humandev_edit',[
            'person' => $person,
            'budgets' =>  $budget,
            'year_id'=>$year_id,
            'infoplantypes'=>$infoplantype,
            'infobudgettypes'=>$infobudgettype,
            'infotreams'=>$infotream,
            'infotreampersons'=>$infotreamperson,
            'infostrategics'=>$infostrategic,
            'infohum'=>$infohum,
            'infohumandevtypes'=>$infohumandevtype,



        ]);
    }



    public function humandev_update(Request $request)
    {
        $BEGIN_DATE = $request->BEGIN_DATE;
        if($BEGIN_DATE != ''){
            $STARTDAY = Carbon::createFromFormat('d/m/Y', $BEGIN_DATE)->format('Y-m-d');
            $date_arrary_st=explode("-",$STARTDAY);  
            $y_sub_st = $date_arrary_st[0];
            if($y_sub_st >= 2500){
                $y_st = $y_sub_st-543;
            }else{
                $y_st = $y_sub_st;
            }
            $m_st = $date_arrary_st[1];
            $d_st = $date_arrary_st[2];  
            $BEGINDATE= $y_st."-".$m_st."-".$d_st;
        }else{
            $BEGINDATE= null;
        }
        $END_DATE = $request->END_DATE;
        if($END_DATE != ''){
            $STARTDAY = Carbon::createFromFormat('d/m/Y', $END_DATE)->format('Y-m-d');
            $date_arrary_st=explode("-",$STARTDAY);  
            $y_sub_st = $date_arrary_st[0];      
            if($y_sub_st >= 2500){
                $y_st = $y_sub_st-543;
            }else{
                $y_st = $y_sub_st;
            }
            $m_st = $date_arrary_st[1];
            $d_st = $date_arrary_st[2];  
            $ENDDATE= $y_st."-".$m_st."-".$d_st;
        }else{
            $ENDDATE= null;
        }
        $HUM_BEGIN_DATE = $request->HUM_BEGIN_DATE;
        if($HUM_BEGIN_DATE != ''){
            $STARTDAY = Carbon::createFromFormat('d/m/Y', $HUM_BEGIN_DATE)->format('Y-m-d');
            $date_arrary_st=explode("-",$STARTDAY);  
            $y_sub_st = $date_arrary_st[0];
            if($y_sub_st >= 2500){
                $y_st = $y_sub_st-543;
            }else{
                $y_st = $y_sub_st;
            }
            $m_st = $date_arrary_st[1];
            $d_st = $date_arrary_st[2];  
            $HUMBEGINDATE= $y_st."-".$m_st."-".$d_st;
        }else{
            $HUMBEGINDATE= null;
        }
        $HUM_END_DATE = $request->HUM_END_DATE;
        if($HUM_END_DATE != ''){
            $STARTDAY = Carbon::createFromFormat('d/m/Y', $HUM_END_DATE)->format('Y-m-d');
            $date_arrary_st=explode("-",$STARTDAY);  
            $y_sub_st = $date_arrary_st[0]; 
            if($y_sub_st >= 2500){
                $y_st = $y_sub_st-543;
            }else{
                $y_st = $y_sub_st;
            }
            $m_st = $date_arrary_st[1];
            $d_st = $date_arrary_st[2];  
            $HUMENDDATE= $y_st."-".$m_st."-".$d_st;
        }else{
            $HUMENDDATE= null;
        }
        $id = $request->HUM_ID;
        $add = Planhumandev::find($id);
        $add->HUM_SAVE_HR_ID = $request->HUM_SAVE_HR_ID;
        $add->PLAN_TYPE_ID = $request->PLAN_TYPE_ID;
        $add->HUM_TYPE = $request->HUM_TYPE;
        $SAVE_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
        ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
        ->where('hrd_person.ID','=',$request->HUM_SAVE_HR_ID)->first();
        $add->HUM_SAVE_HR_NAME   = $SAVE_HR_NAME->HR_PREFIX_NAME.''.$SAVE_HR_NAME->HR_FNAME.' '.$SAVE_HR_NAME->HR_LNAME;         
        //----------------------------------
        $add->BUDGET_YEAR = $request->BUDGET_YEAR;
        if($request->STRATEGIC_ID !== '' || $request->STRATEGIC_ID !== null){
            $add->STRATEGIC_ID = $request->STRATEGIC_ID;
        }
        if($request->TARGET_ID !== '' || $request->TARGET_ID !== null){
            $add->TARGET_ID = $request->TARGET_ID;
        }
        if($request->KPI_ID !== '' || $request->KPI_ID !== null){
            $add->KPI_ID = $request->KPI_ID;
        }
        $add->HUM_NUMBER = $request->HUM_NUMBER;
        $add->HUM_NAME = $request->HUM_NAME;
        $add->HUM_TYPE_NAME = $request->HUM_TYPE_NAME;
        $add->BUDGET_ID = $request->BUDGET_ID;
        $add->BUDGET_PICE = $request->BUDGET_PICE;
        $add->BUDGET_PICE_REAL = $request->BUDGET_PICE_REAL;
        $add->BEGIN_DATE = $BEGINDATE;
        $add->END_DATE = $ENDDATE;
        $add->HUM_BEGIN_DATE = $HUMBEGINDATE;
        $add->HUM_END_DATE = $HUMENDDATE;
        $add->HUM_GROUP = $request->HUM_GROUP;
        $add->HUM_LOCATION = $request->HUM_LOCATION;
        $add->HUM_EXPERT = $request->HUM_EXPERT;
        $add->HUM_TEAM_NAME = $request->HUM_TEAM_NAME;
        $add->HUM_TEAM_HR_ID = $request->HUM_TEAM_HR_ID;
        $TEAM_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
        ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
        ->where('hrd_person.ID','=',$request->HUM_TEAM_HR_ID)->first();
        $add->HUM_TEAM_HR_NAME   = $TEAM_HR_NAME->HR_PREFIX_NAME.''.$TEAM_HR_NAME->HR_FNAME.' '.$TEAM_HR_NAME->HR_LNAME;
        $add->HUM_COMMENT = $request->HUM_COMMENT;    
        $add->save();
        return redirect()->route('mplan.humandev');
    }
    
     public function durable_edit(Request $request,$idref)
     {      
 
         $m_budget = date("m");
         if($m_budget>9){
         $yearbudget = date("Y")+544;
         }else{
         $yearbudget = date("Y")+543;
         }
         
         $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
         $year_id = $yearbudget;
 
 
 
         $infobudgettype =  DB::table('supplies_budget')->get();
 
         $infotream =  DB::table('hrd_team')->get();
 
         $infotreamperson =  DB::table('hrd_team_list')->get();
         $infostrategic =  DB::table('plan_strategic')->where('ACTIVE','=','TRUE')->get();
 
         $infoplantype =  DB::table('plan_type')->get();
 
         $suppliesprop = DB::table('supplies_prop')->get();
 
         $assetarticle = DB::table('asset_article')->get();


         $infodurable = Plandurable::leftjoin('plan_target','plan_target.TARGET_ID','=','plan_durable.TARGET_ID')
         ->leftjoin('plan_kpi','plan_kpi.KPI_ID','=','plan_durable.KPI_ID')
         ->leftjoin('hrd_person','plan_durable.DUR_TEAM_HR_ID','=','hrd_person.ID')    
         ->where('DUR_ID','=',$idref)    
         ->first();
 
         return view('manager_plan.plan_durable_edit',[
             'budgets' =>  $budget,
             'year_id'=>$year_id,
             'infoplantypes'=>$infoplantype,
             'infobudgettypes'=>$infobudgettype,
             'infotreams'=>$infotream,
             'infotreampersons'=>$infotreamperson,
             'infostrategics'=>$infostrategic,
             'suppliesprops'=>$suppliesprop,
             'assetarticles'=>$assetarticle,
             'infodurable'=>$infodurable,
             'idref'=>$idref
 
 
 
         ]);
     }
 
 
     
 
     public function durable_update(Request $request)
     {

        $DUR_ASS_BEGIN_DATE = $request->DUR_ASS_BEGIN_DATE;


        if($DUR_ASS_BEGIN_DATE != ''){
        $STARTDAY = Carbon::createFromFormat('d/m/Y', $DUR_ASS_BEGIN_DATE)->format('Y-m-d');
        $date_arrary_st=explode("-",$STARTDAY);  
        $y_sub_st = $date_arrary_st[0]; 
        
        if($y_sub_st >= 2500){
            $y_st = $y_sub_st-543;
        }else{
            $y_st = $y_sub_st;
        }
        $m_st = $date_arrary_st[1];
        $d_st = $date_arrary_st[2];  
        $DURASSBEGINDATE= $y_st."-".$m_st."-".$d_st;
        }else{
        $DURASSBEGINDATE= null;
        }

        $DUR_ASS_END_DATE = $request->DUR_ASS_END_DATE;


        if($DUR_ASS_END_DATE != ''){
        $STARTDAY = Carbon::createFromFormat('d/m/Y', $DUR_ASS_END_DATE)->format('Y-m-d');
        $date_arrary_st=explode("-",$STARTDAY);  
        $y_sub_st = $date_arrary_st[0]; 
        
        if($y_sub_st >= 2500){
            $y_st = $y_sub_st-543;
        }else{
            $y_st = $y_sub_st;
        }
        $m_st = $date_arrary_st[1];
        $d_st = $date_arrary_st[2];  
        $DURASSENDDATE= $y_st."-".$m_st."-".$d_st;
        }else{
        $DURASSENDDATE= null;
        }

        $id = $request->DUR_ID;
        $add =  Plandurable::find($id);
        $add->DUR_SAVE_HR_ID = $request->DUR_SAVE_HR_ID;
        $add->PLAN_TYPE_ID = $request->PLAN_TYPE_ID;
        $add->DUR_TYPE = $request->DUR_TYPE;

        

        $SAVE_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
        ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
        ->where('hrd_person.ID','=',$request->DUR_SAVE_HR_ID)->first();
        $add->DUR_SAVE_HR_NAME   = $SAVE_HR_NAME->HR_PREFIX_NAME.''.$SAVE_HR_NAME->HR_FNAME.' '.$SAVE_HR_NAME->HR_LNAME;

        //----------------------------------

        $add->BUDGET_YEAR = $request->BUDGET_YEAR;
  
        if($request->STRATEGIC_ID !== '' || $request->STRATEGIC_ID !== null){
            $add->STRATEGIC_ID = $request->STRATEGIC_ID;
        }

        if($request->TARGET_ID !== '' || $request->TARGET_ID !== null){
            $add->TARGET_ID = $request->TARGET_ID;
        }

        if($request->KPI_ID !== '' || $request->KPI_ID !== null){
            $add->KPI_ID = $request->KPI_ID;
        }

        $add->DUR_NUMBER = $request->DUR_NUMBER;
        $add->DUR_NAME = $request->DUR_NAME;
        $add->DUR_ASS_NAME = $request->DUR_ASS_NAME;
        $add->DUR_ASS_PICE_UNIT = $request->DUR_ASS_PICE_UNIT;


        $add->BUDGET_ID = $request->BUDGET_ID;
        $add->BUDGET_PICE = $request->BUDGET_PICE;

        $add->DUR_ASS_BEGIN_DATE = $DURASSBEGINDATE;
        $add->DUR_ASS_END_DATE = $DURASSENDDATE; 
        
        $add->DUR_TEAM_NAME = $request->DUR_TEAM_NAME;

        $add->DUR_TEAM_HR_ID = $request->DUR_TEAM_HR_ID;
        $TEAM_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
        ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
        ->where('hrd_person.ID','=',$request->DUR_TEAM_HR_ID)->first();
        $add->DUR_TEAM_HR_NAME   = $TEAM_HR_NAME->HR_PREFIX_NAME.''.$TEAM_HR_NAME->HR_FNAME.' '.$TEAM_HR_NAME->HR_LNAME;

        //----------------------------------

        $add->DUR_COMMENT = $request->DUR_COMMENT;


        $add->DUR_FSN = $request->DUR_FSN;
        $add->DUR_REF = $request->DUR_REF;
        $add->DUR_REF_CODE = $request->DUR_REF_CODE;
        $add->DUR_SERVICE = $request->DUR_SERVICE;
        $add->DUR_HASTE = $request->DUR_HASTE;
        $add->DUR_REASON_ID = $request->DUR_REASON_ID;
        $add->DUR_ASS_OLD = $request->DUR_ASS_OLD;
        $add->DUR_ASS_AMOUNT = $request->DUR_ASS_AMOUNT;
        $add->BUDGET_PICE_REAL = $request->BUDGET_PICE_REAL;

        $add->DUR_PICE_SUM = $request->DUR_ASS_AMOUNT*$request->DUR_ASS_PICE_UNIT;
    
        $add->save();
                 
                     return redirect()->route('mplan.durable');
  
 
 
     }
 


    //===============================

    


    public function project_destroy($idref) {

        Planproject::destroy($idref);
          return redirect()->route('mplan.project');
      }


      
    public function humandev_destroy($idref) {

        Planhumandev::destroy($idref);
          return redirect()->route('mplan.humandev');
      }



      
    public function durable_destroy($idref) {

        Plandurable::destroy($idref);
          return redirect()->route('mplan.durable');
      }


    //===============================


    public function project_app($idref) {

      
            $add =  Planproject::find($idref);
            $add->PRO_STATUS = 'APP';
            $add->save();

            return redirect()->route('mplan.project');
      }

      public function project_notapp($idref) {

        $add =  Planproject::find($idref);
        $add->PRO_STATUS = 'NOTAPP';
        $add->save();


            return redirect()->route('mplan.project');
      }

      //-----------------------------
      
    public function humandev_app($idref) {

      
        $add =  Planhumandev::find($idref);
        $add->HUM_STATUS = 'APP';
        $add->save();

        return redirect()->route('mplan.humandev');
    }

    public function humandev_notapp($idref) {

        $add =  Planhumandev::find($idref);
        $add->HUM_STATUS = 'NOTAPP';
        $add->save();


            return redirect()->route('mplan.humandev');
    }

            //-----------------------------
        public function durable_app($idref) {

            
            $add =  Plandurable::find($idref);
            $add->DUR_STATUS = 'APP';
            $add->save();

            return redirect()->route('mplan.durable');
        }

        public function durable_notapp($idref) {

        $add =  Plandurable::find($idref);
        $add->DUR_STATUS = 'NOTAPP';
        $add->save();


            return redirect()->route('mplan.durable');
        }

  //-----------------------------
  public function repair_app($idref) {

            
    $add =  Planrepair::find($idref);
    $add->REPAIR_STATUS = 'APP';
    $add->save();

    return redirect()->route('mplan.repair');
    }

    public function repair_notapp($idref) {

    $add =  Planrepair::find($idref);
    $add->REPAIR_STATUS = 'NOTAPP';
    $add->save();


        return redirect()->route('mplan.repair');
    }

      //===============================

    public function employ(Request $request)
    {      

        return view('manager_plan.plan_employ');
    }

    
    public function employ_add(Request $request)
    {      

        return view('manager_plan.plan_employ_add');
    }

//==================================================ตั้งค่าประเภทแผนงาน
    public function plantype(Request $request)
    {      

        $plantype = Plantype::get();

        return view('manager_plan.plantype',[
            'plantypes'=> $plantype
        ]);
    }


    public function plantype_add(Request $request)
    {      

      
        return view('manager_plan.plantype_add');
    }


    public function saveplantype(Request $request)
    {      

        $add = new Plantype();
        $add->PLAN_TYPE_NAME = $request->PLAN_TYPE_NAME;    
        $add->save();

    
        return redirect()->route('mplan.plantype');
    }



    public function plantype_edit(Request $request,$id)
    {      
        $infoplan = Plantype::where('PLAN_TYPE_ID','=',$id)->first();
      
        return view('manager_plan.plantype_edit',[
            'infoplan' => $infoplan
        ]);
    }


    public function updateplantype(Request $request)
    {      
        $id =  $request->id;

        $update =  Plantype::find($id);
        $update->PLAN_TYPE_NAME = $request->PLAN_TYPE_NAME;    
        $update->save();

    
        return redirect()->route('mplan.plantype');
    }

    

    

    public function destroyevent($id) { 
                
        Plantype::destroy($id);         
        //return redirect()->action('ChangenameController@infouserchangename');  
        return redirect()->route('mplan.plantype');
    }

    //======================ฟังชั่น

    function dropdownstrategic(Request $request)
    {
       
      $id = $request->get('select');
      $result=array();
      $query= DB::table('plan_target')->where('STRATEGIC_ID',$id)->get();
      $output='<option value="">--กรุณาเลือกเป้าประสงค์--</option>';
      
      foreach ($query as $row){

            $output.= '<option value="'.$row->TARGET_ID.'">'.$row->TARGET_NAME.'</option>';
    }

    echo $output;
        
    }

    function dropdowngoal(Request $request)
    {
       
      $id = $request->get('select');
      $result=array();
      $query= DB::table('plan_kpi')->where('TARGET_ID',$id)->get();
      $output='<option value="">--กรุณาเลือกตัวชี้วัด--</option>';
      
      foreach ($query as $row){

            $output.= '<option value="'.$row->KPI_ID.'">'.$row->KPI_CODE.' :: '.$row->KPI_NAME.'</option>';
    }

    echo $output;
        
    }



    function dropdownplantype(Request $request)
    {
       
      $id = $request->get('select');
      $result=array();

      if($id == 'dep'){
        $query= DB::table('hrd_department_sub_sub')->get();
      }else{
        $query= DB::table('hrd_team')->get();
      }
    

      $output='<option value="">กรุณาเลือก</option>';
      
      foreach ($query as $row){

        if($id == 'dep'){
            $output.= '<option value="'.$row->DEP_CODE.'">'.$row->DEP_CODE.' : '.$row->HR_DEPARTMENT_SUB_SUB_NAME.'</option>';    
          }else{
            $output.= '<option value="'.$row->HR_TEAM_NAME.'">'.$row->HR_TEAM_NAME.' : '.$row->HR_TEAM_DETAIL.'</option>';
          }

        }

    echo $output;
        
    }

    function dropdownteamunit(Request $request)
    {
       
      $id = $request->get('select');
      $PROTYPE = $request->get('PROTYPE');
      $result=array();

        if($PROTYPE == 'dep'){
            $dep= DB::table('hrd_department_sub_sub')
            ->where('DEP_CODE','=',$id)
            ->first();

            $query =DB::table('hrd_person')
            ->where('hrd_person.HR_DEPARTMENT_SUB_SUB_ID','=',$dep->HR_DEPARTMENT_SUB_SUB_ID)->get();

        }else{
            $team= DB::table('hrd_team')
            ->where('HR_TEAM_NAME','=',$id)
            ->first();

           

            $query =DB::table('hrd_team_list')
            ->leftjoin('hrd_person','hrd_team_list.PERSON_ID','=','hrd_person.ID')
            ->where('TEAM_ID','=',$team->HR_TEAM_ID)->get();
        }

             $output='<option value="">กรุณาเลือก</option>';
      
            foreach ($query as $row){

                    $output.= '<option value="'.$row->ID.'">'.$row->HR_FNAME.' '.$row->HR_LNAME.'</option>';
            }

    echo $output;
        
    }


//===========================================================
    public static function levelkpi($id,$number)
    {
         $total  =  DB::table('plan_kpi_level')->where('KPI_ID','=',$id)->where('KPI_LEVEL_NUM','=',$number)->first();
         $count  =  DB::table('plan_kpi_level')->where('KPI_ID','=',$id)->where('KPI_LEVEL_NUM','=',$number)->count();
         
         if($count == 0){
            $result =  '';
         }else{
            $result =  $total->KPI_LEVEL; 
         }
        
       return $result ;
    }

 //------------------------

 public function selectasset(Request $request)
 {
 
     $detail = DB::table('supplies_prop')->where('PROPOTIES_ID','=',$request->id)->first();
 
 
     $output='<input name="DUR_ASS_NAME" id="DUR_ASS_NAME" class="form-control input-sm" style=" font-family: \'Kanit\', sans-serif;" value="'.$detail->PROPOTIES_NAME.'">';
      
     echo $output;
 
 }


 public function selectfsn(Request $request)
 {
 
     $detail = DB::table('supplies_prop')->where('PROPOTIES_ID','=',$request->id)->first();
 
 
     $output='<input name="DUR_FSN" id="DUR_FSN" class="form-control input-sm" style=" font-family: \'Kanit\', sans-serif;"  value="'.$detail->NUM.'">';
      
     echo $output;
 
 }


 public function selectass(Request $request)
 {
 
     $detail = DB::table('asset_article')->where('ARTICLE_ID','=',$request->id)->first();
 
 
     $output=' <input name="DUR_ASS_OLD" id="DUR_ASS_OLD" class="form-control input-sm" style=" font-family: \'Kanit\', sans-serif;" value="'.$detail->ARTICLE_NUM.' : '.$detail->ARTICLE_NAME.'"  >';
      
     echo $output;
 
 }



 


 public function repair(Request $request)
 {     
    if($request->method() === 'POST'){
        $search = $request->get('search');
        $yearbudget = $request->BUDGET_YEAR;
        $datebigin = $request->get('DATE_BIGIN');
        $dateend = $request->get('DATE_END');
        $type = $request->SEND_TYPE;
        $statusinfo     = $request->SEND_STATUS;
        $data_search = json_encode_u([
            'search' => $search,
            'yearbudget' => $yearbudget,
            'datebigin' => $datebigin,
            'dateend' => $dateend,
            'type' => $type,
            'statusinfo' => $statusinfo,
        ]);
        Cookie::queue('data_search', $data_search, 120,$request->server('REQUEST_URI'));
    }elseif(!empty(Cookie::get('data_search'))){
        $data_search    = json_decode(Cookie::get('data_search'));
        $search     = $data_search->search;
        $yearbudget     = $data_search->yearbudget;
        $datebigin     = $data_search->datebigin;
        $dateend     = $data_search->dateend;
        $type     = $data_search->type;
        $statusinfo     = $data_search->statusinfo;
    }else{
        $search     = '';
        $yearbudget = getBudgetYear();
        $datebigin  = date('01/10/'.($yearbudget-1));
        $dateend    = date('30/09/'.$yearbudget);
        $type       = '';
        $statusinfo       = '';
    }
        $date_bigen_c = Carbon::createFromFormat('d/m/Y', $datebigin)->format('Y-m-d');
        $date_arrary=explode("-",$date_bigen_c);
        $y_sub_st = $date_arrary[0];

        if($y_sub_st >= 2500){
            $y = $y_sub_st-543;
        }else{
            $y = $y_sub_st;
        }

        $m = $date_arrary[1];
        $d = $date_arrary[2];  
        $displaydate_bigen= $y."-".$m."-".$d;
    
        $date_end_c = Carbon::createFromFormat('d/m/Y', $dateend)->format('Y-m-d');
        $date_arrary_e=explode("-",$date_end_c); 

        $y_sub_e = $date_arrary_e[0];

        if($y_sub_e >= 2500){
            $y_e = $y_sub_e-543;
        }else{
            $y_e = $y_sub_e;
        }
        $m_e = $date_arrary_e[1];
        $d_e = $date_arrary_e[2];  
        $displaydate_end= $y_e."-".$m_e."-".$d_e;
        $from = date($displaydate_bigen);
        $to = date($displaydate_end);
 

        $inforepair  = DB::table('plan_repair')
            ->leftJoin('supplies_budget','plan_repair.BUDGET_ID','=','supplies_budget.BUDGET_ID');   
            if(!empty($type)){
                $inforepair = $inforepair->where('REPAIR_TYPE','=',$type);
              }
              if(!empty($statusinfo)){
                $inforepair = $inforepair->where('REPAIR_STATUS','=',$statusinfo);
              }
                
              $inforepair = $inforepair->where('BUDGET_YEAR','=', $yearbudget)
            ->where(function($q) use ($search){
                $q->where('REPAIR_NUMBER','like','%'.$search.'%');
                $q->orwhere('REPAIR_TEAM_NAME','like','%'.$search.'%');
                $q->orwhere('REPAIR_PLAN_DETAIL','like','%'.$search.'%');
                $q->orwhere('REPAIR_PLAN_FROM','like','%'.$search.'%');
                $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
            })
            ->WhereBetween('REPAIR_BEGIN_DATE',[$from,$to]) 
            ->orderBy('REPAIR_PLAN_ID', 'asc')->get();
           
           

        $loopsum = 0;
        foreach ($inforepair as $info){ 
           
             $loopsum = $loopsum + $info->REPAIR_PICE_SUM;
            
            }

        $sumbudget = $loopsum;

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $year_id =  $yearbudget;
        return view('manager_plan.plan_repair',[
        'budgets' =>  $budget,
        'search'=> $search,
        'year_id'=>$year_id,  
        'type'=>$type, 
        'inforepairs'=>$inforepair, 
        'sumbudget'=>$sumbudget,
        'statusinfo'=>$statusinfo,  
        'displaydate_bigen'=> $displaydate_bigen,
        'displaydate_end'=> $displaydate_end,     
    ]);
 }


 public function repair_search(Request $request)
 {      
     $search = $request->get('search');
     $yearbudget = $request->BUDGET_YEAR;
     $datebigin = $request->get('DATE_BIGIN');
    $dateend = $request->get('DATE_END');
     $type = $request->SEND_TYPE;
 
   
     if($search==''){
         $search="";
     }

     $date_bigen_c = Carbon::createFromFormat('d/m/Y', $datebigin)->format('Y-m-d');
        $date_arrary=explode("-",$date_bigen_c);
        $y_sub_st = $date_arrary[0];

        if($y_sub_st >= 2500){
            $y = $y_sub_st-543;
        }else{
            $y = $y_sub_st;
        }

        $m = $date_arrary[1];
        $d = $date_arrary[2];  
        $displaydate_bigen= $y."-".$m."-".$d;
  
        $date_end_c = Carbon::createFromFormat('d/m/Y', $dateend)->format('Y-m-d');
        $date_arrary_e=explode("-",$date_end_c); 

        $y_sub_e = $date_arrary_e[0];

        if($y_sub_e >= 2500){
            $y_e = $y_sub_e-543;
        }else{
            $y_e = $y_sub_e;
        }
        $m_e = $date_arrary_e[1];
        $d_e = $date_arrary_e[2];  
        $displaydate_end= $y_e."-".$m_e."-".$d_e;
     

        $from = date($displaydate_bigen);
        $to = date($displaydate_end);


     if($type == null || $type == ''){


        $inforepair  = DB::table('plan_repair')
     ->leftJoin('supplies_budget','plan_repair.BUDGET_ID','=','supplies_budget.BUDGET_ID')
         ->where('BUDGET_YEAR','=', $yearbudget)
         ->where(function($q) use ($search){
              $q->where('REPAIR_NUMBER','like','%'.$search.'%');
              $q->orwhere('REPAIR_TEAM_NAME','like','%'.$search.'%');
              $q->orwhere('REPAIR_PLAN_DETAIL','like','%'.$search.'%');
              $q->orwhere('REPAIR_PLAN_FROM','like','%'.$search.'%');
              $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
         })
         ->WhereBetween('REPAIR_BEGIN_DATE',[$from,$to]) 
         ->orderBy('REPAIR_PLAN_ID', 'asc')->get();

         $sumbudget  = DB::table('plan_repair')
         ->leftJoin('supplies_budget','plan_repair.BUDGET_ID','=','supplies_budget.BUDGET_ID')
         ->where('BUDGET_YEAR','=', $yearbudget)
         ->where(function($q) use ($search){
            $q->where('REPAIR_NUMBER','like','%'.$search.'%');
            $q->orwhere('REPAIR_TEAM_NAME','like','%'.$search.'%');
            $q->orwhere('REPAIR_PLAN_DETAIL','like','%'.$search.'%');
            $q->orwhere('REPAIR_PLAN_FROM','like','%'.$search.'%');
            $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
         })
         ->WhereBetween('REPAIR_BEGIN_DATE',[$from,$to]) 
         ->SUM('REPAIR_PICE_SUM');


     }else{

        

        $inforepair  = DB::table('plan_repair')
     ->leftJoin('supplies_budget','plan_repair.BUDGET_ID','=','supplies_budget.BUDGET_ID')
         ->where('BUDGET_YEAR','=', $yearbudget)
         ->where('REPAIR_TYPE','=',$type)
         ->where(function($q) use ($search){
            $q->where('REPAIR_NUMBER','like','%'.$search.'%');
            $q->orwhere('REPAIR_TEAM_NAME','like','%'.$search.'%');
            $q->orwhere('REPAIR_PLAN_DETAIL','like','%'.$search.'%');
            $q->orwhere('REPAIR_PLAN_FROM','like','%'.$search.'%');
            $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
         })
         ->WhereBetween('REPAIR_BEGIN_DATE',[$from,$to]) 
         ->orderBy('REPAIR_PLAN_ID', 'asc')->get();

         $sumbudget  = DB::table('plan_repair')
     ->leftJoin('supplies_budget','plan_repair.BUDGET_ID','=','supplies_budget.BUDGET_ID')
         ->where('BUDGET_YEAR','=', $yearbudget)
         ->where('REPAIR_TYPE','=',$type)
         ->where(function($q) use ($search){
            $q->where('REPAIR_NUMBER','like','%'.$search.'%');
            $q->orwhere('REPAIR_TEAM_NAME','like','%'.$search.'%');
            $q->orwhere('REPAIR_PLAN_DETAIL','like','%'.$search.'%');
            $q->orwhere('REPAIR_PLAN_FROM','like','%'.$search.'%');
            $q->orwhere('BUDGET_NAME','like','%'.$search.'%');
         })
         ->WhereBetween('REPAIR_BEGIN_DATE',[$from,$to]) 
         ->SUM('REPAIR_PICE_SUM');

     
 }

     

     $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
     $year_id =  $yearbudget;
     
     
     return view('manager_plan.plan_repair',[
        'budgets' =>  $budget,
        'search'=> $search,
        'year_id'=>$year_id,  
        'type'=>$type, 
        'inforepairs'=>$inforepair, 
        'sumbudget'=>$sumbudget,  
        'displaydate_bigen'=> $displaydate_bigen,
        'displaydate_end'=> $displaydate_end,     
    ]);
 }





 public function repair_add(Request $request)
 {      

     $infoyear = DB::table('plan_year')->first();

     if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
        $yearbudget = $infoyear->PLAN_YEAR;
     }else{
        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }

     }
     
     $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
     $year_id = $yearbudget;



     $infobudgettype =  DB::table('supplies_budget')->get();

     $infotream =  DB::table('hrd_team')->get();

     $infotreamperson =  DB::table('hrd_team_list')->get();
     $infostrategic =  DB::table('plan_strategic')->where('ACTIVE','=','TRUE')->get();

     $infoplantype =  DB::table('plan_type')->get();

     $suppliesprop = DB::table('supplies_prop')->get();

     $assetarticle = DB::table('asset_article')->get();


     return view('manager_plan.plan_repair_add',[
         'budgets' =>  $budget,
         'year_id'=>$year_id,
         'infoplantypes'=>$infoplantype,
         'infobudgettypes'=>$infobudgettype,
         'infotreams'=>$infotream,
         'infotreampersons'=>$infotreamperson,
         'infostrategics'=>$infostrategic,
         'suppliesprops'=>$suppliesprop,
         'assetarticles'=>$assetarticle



     ]);
 }


 

 public function repair_save(Request $request)
 {


                 $REPAIR_BEGIN_DATE = $request->REPAIR_BEGIN_DATE;


                 if($REPAIR_BEGIN_DATE != ''){
                 $STARTDAY = Carbon::createFromFormat('d/m/Y', $REPAIR_BEGIN_DATE)->format('Y-m-d');
                 $date_arrary_st=explode("-",$STARTDAY);  
                 $y_sub_st = $date_arrary_st[0]; 
                 
                 if($y_sub_st >= 2500){
                     $y_st = $y_sub_st-543;
                 }else{
                     $y_st = $y_sub_st;
                 }
                 $m_st = $date_arrary_st[1];
                 $d_st = $date_arrary_st[2];  
                 $REPAIRBEGINDATE= $y_st."-".$m_st."-".$d_st;
                 }else{
                 $REPAIRBEGINDATE= null;
                 }

                 $REPAIR_END_DATE = $request->REPAIR_END_DATE;


                 if($REPAIR_END_DATE != ''){
                 $STARTDAY = Carbon::createFromFormat('d/m/Y', $REPAIR_END_DATE)->format('Y-m-d');
                 $date_arrary_st=explode("-",$STARTDAY);  
                 $y_sub_st = $date_arrary_st[0]; 
                 
                 if($y_sub_st >= 2500){
                     $y_st = $y_sub_st-543;
                 }else{
                     $y_st = $y_sub_st;
                 }
                 $m_st = $date_arrary_st[1];
                 $d_st = $date_arrary_st[2];  
                 $REPAIRENDDATE= $y_st."-".$m_st."-".$d_st;
                 }else{
                 $REPAIRENDDATE= null;
                 }

                 //=================สร้างรหัส========
                 $infoyear = DB::table('plan_year')->first();

                 if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
                    $yearbudget = $infoyear->PLAN_YEAR;
                 }else{
                    $m_budget = date("m");
                    if($m_budget>9){
                    $yearbudget = date("Y")+544;
                    }else{
                    $yearbudget = date("Y")+543;
                    }
            
                 }
         
                 $maxnumber = DB::table('plan_repair')->where('BUDGET_YEAR','=',$yearbudget)->max('REPAIR_PLAN_ID');  
         
              
         
                 if($maxnumber != '' ||  $maxnumber != null){
                     
                     $refmax = DB::table('plan_repair')->where('REPAIR_PLAN_ID','=',$maxnumber)->first();  
         
         
                     if($refmax->REPAIR_NUMBER != '' ||  $refmax->REPAIR_NUMBER != null){
                         $maxref = substr($refmax->REPAIR_NUMBER, -4)+1;
                      }else{
                         $maxref = 1;
                      }
         
                     $ref = str_pad($maxref, 4, "0", STR_PAD_LEFT);
                
                 }else{
                     $ref = '0001';
                 }
         
     
                 $y = substr($yearbudget, -2);
                
         
                $REPAIR_NUMBER ='B-'.$y.''.$ref;

                 //===============================

                 $add = new Planrepair();
                 $add->REPAIR_SAVE_HR_ID = $request->REPAIR_SAVE_HR_ID;
                 $add->PLAN_TYPE_ID = $request->PLAN_TYPE_ID;
              

                 $SAVE_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                 ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                 ->where('hrd_person.ID','=',$request->REPAIR_SAVE_HR_ID)->first();
                 $add->REPAIR_SAVE_HR_NAME   = $SAVE_HR_NAME->HR_PREFIX_NAME.''.$SAVE_HR_NAME->HR_FNAME.' '.$SAVE_HR_NAME->HR_LNAME;
     
                 //----------------------------------

                 $add->BUDGET_YEAR = $request->BUDGET_YEAR;
           
                 if($request->STRATEGIC_ID !== '' || $request->STRATEGIC_ID !== null){
                     $add->STRATEGIC_ID = $request->STRATEGIC_ID;
                 }

                 if($request->TARGET_ID !== '' || $request->TARGET_ID !== null){
                     $add->TARGET_ID = $request->TARGET_ID;
                 }

                 if($request->KPI_ID !== '' || $request->KPI_ID !== null){
                     $add->KPI_ID = $request->KPI_ID;
                 }

                 $add->REPAIR_NUMBER = $REPAIR_NUMBER;
                 $add->REPAIR_SERVICE = $request->REPAIR_SERVICE;
                 $add->BUDGET_ID = $request->BUDGET_ID;
                 $add->REPAIR_PLAN_TYPE = $request->REPAIR_PLAN_TYPE;

                 $add->REPAIR_PLAN_DETAIL = $request->REPAIR_PLAN_DETAIL;
                 $add->REPAIR_PLAN_REASON = $request->REPAIR_PLAN_REASON;
                 $add->REPAIR_PLAN_FROM = $request->REPAIR_PLAN_FROM;
                 $add->REPAIR_AMOUNT = $request->REPAIR_AMOUNT;
                 $add->REPAIR_PICE_UNIT = $request->REPAIR_PICE_UNIT;
                 $add->REPAIR_PICE_REAL = $request->REPAIR_PICE_REAL;
                 $add->REPAIR_TYPE = $request->REPAIR_TYPE;
                 $add->REPAIR_BEGIN_DATE = $REPAIRBEGINDATE;
                 $add->REPAIR_END_DATE = $REPAIRENDDATE;   
                 $add->REPAIR_TEAM_NAME = $request->REPAIR_TEAM_NAME;

                 $add->REPAIR_TEAM_HR_ID = $request->REPAIR_TEAM_HR_ID;
                 $TEAM_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                 ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                 ->where('hrd_person.ID','=',$request->REPAIR_TEAM_HR_ID)->first();
                 $add->REPAIR_TEAM_HR_NAME   = $TEAM_HR_NAME->HR_PREFIX_NAME.''.$TEAM_HR_NAME->HR_FNAME.' '.$TEAM_HR_NAME->HR_LNAME;
     
             
                 //----------------------------------

                 $add->REPAIR_COMMENT = $request->REPAIR_COMMENT;
                 $add->REPAIR_PICE_SUM = $request->REPAIR_AMOUNT*$request->REPAIR_PICE_UNIT;      
                 $add->REPAIR_STATUS = 'WAIT';
                 $add->save();

             
                 return redirect()->route('mplan.repair');



 }

 


 public function repair_edit(Request $request,$idref)
 {      

    
    $infoyear = DB::table('plan_year')->first();

    if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
       $yearbudget = $infoyear->PLAN_YEAR;
    }else{
       $m_budget = date("m");
       if($m_budget>9){
       $yearbudget = date("Y")+544;
       }else{
       $yearbudget = date("Y")+543;
       }

    }
    
    $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
    $year_id = $yearbudget;



    $infobudgettype =  DB::table('supplies_budget')->get();

    $infotream =  DB::table('hrd_team')->get();

    $infotreamperson =  DB::table('hrd_team_list')->get();
    $infostrategic =  DB::table('plan_strategic')->where('ACTIVE','=','TRUE')->get();

    $infoplantype =  DB::table('plan_type')->get();

    $suppliesprop = DB::table('supplies_prop')->get();

    $assetarticle = DB::table('asset_article')->get();


     $infoplanrepair = Planrepair::leftjoin('plan_target','plan_target.TARGET_ID','=','plan_repair.TARGET_ID')
     ->leftjoin('plan_kpi','plan_kpi.KPI_ID','=','plan_repair.KPI_ID')
     ->leftjoin('hrd_person','plan_repair.REPAIR_TEAM_HR_ID','=','hrd_person.ID')    
     ->where('REPAIR_PLAN_ID','=',$idref)    
     ->first();



     return view('manager_plan.plan_repair_edit',[
        'budgets' =>  $budget,
        'year_id'=>$year_id,
        'infoplantypes'=>$infoplantype,
        'infobudgettypes'=>$infobudgettype,
        'infotreams'=>$infotream,
        'infotreampersons'=>$infotreamperson,
        'infostrategics'=>$infostrategic,
        'suppliesprops'=>$suppliesprop,
        'assetarticles'=>$assetarticle,
        'infoplanrepair'=>$infoplanrepair,

     ]);
 }


 

 public function repair_update(Request $request)
 {

                $id = $request->REPAIR_PLAN_ID;
                $REPAIR_BEGIN_DATE = $request->REPAIR_BEGIN_DATE;


                if($REPAIR_BEGIN_DATE != ''){
                $STARTDAY = Carbon::createFromFormat('d/m/Y', $REPAIR_BEGIN_DATE)->format('Y-m-d');
                $date_arrary_st=explode("-",$STARTDAY);  
                $y_sub_st = $date_arrary_st[0]; 
                
                if($y_sub_st >= 2500){
                    $y_st = $y_sub_st-543;
                }else{
                    $y_st = $y_sub_st;
                }
                $m_st = $date_arrary_st[1];
                $d_st = $date_arrary_st[2];  
                $REPAIRBEGINDATE= $y_st."-".$m_st."-".$d_st;
                }else{
                $REPAIRBEGINDATE= null;
                }

                $REPAIR_END_DATE = $request->REPAIR_END_DATE;


                if($REPAIR_END_DATE != ''){
                $STARTDAY = Carbon::createFromFormat('d/m/Y', $REPAIR_END_DATE)->format('Y-m-d');
                $date_arrary_st=explode("-",$STARTDAY);  
                $y_sub_st = $date_arrary_st[0]; 
                
                if($y_sub_st >= 2500){
                    $y_st = $y_sub_st-543;
                }else{
                    $y_st = $y_sub_st;
                }
                $m_st = $date_arrary_st[1];
                $d_st = $date_arrary_st[2];  
                $REPAIRENDDATE= $y_st."-".$m_st."-".$d_st;
                }else{
                $REPAIRENDDATE= null;
                }

                $add = Planrepair::find($id);
                $add->REPAIR_SAVE_HR_ID = $request->REPAIR_SAVE_HR_ID;
                $add->PLAN_TYPE_ID = $request->PLAN_TYPE_ID;
             

                $SAVE_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                ->where('hrd_person.ID','=',$request->REPAIR_SAVE_HR_ID)->first();
                $add->REPAIR_SAVE_HR_NAME   = $SAVE_HR_NAME->HR_PREFIX_NAME.''.$SAVE_HR_NAME->HR_FNAME.' '.$SAVE_HR_NAME->HR_LNAME;
    
                //----------------------------------

                $add->BUDGET_YEAR = $request->BUDGET_YEAR;
          
                if($request->STRATEGIC_ID !== '' || $request->STRATEGIC_ID !== null){
                    $add->STRATEGIC_ID = $request->STRATEGIC_ID;
                }

                if($request->TARGET_ID !== '' || $request->TARGET_ID !== null){
                    $add->TARGET_ID = $request->TARGET_ID;
                }

                if($request->KPI_ID !== '' || $request->KPI_ID !== null){
                    $add->KPI_ID = $request->KPI_ID;
                }

                $add->REPAIR_NUMBER = $request->REPAIR_NUMBER;
                $add->REPAIR_SERVICE = $request->REPAIR_SERVICE;
                $add->BUDGET_ID = $request->BUDGET_ID;
                $add->REPAIR_PLAN_TYPE = $request->REPAIR_PLAN_TYPE;

                $add->REPAIR_PLAN_DETAIL = $request->REPAIR_PLAN_DETAIL;
                $add->REPAIR_PLAN_REASON = $request->REPAIR_PLAN_REASON;
                $add->REPAIR_PLAN_FROM = $request->REPAIR_PLAN_FROM;
                $add->REPAIR_AMOUNT = $request->REPAIR_AMOUNT;
                $add->REPAIR_PICE_UNIT = $request->REPAIR_PICE_UNIT;
                $add->REPAIR_PICE_REAL = $request->REPAIR_PICE_REAL;
                $add->REPAIR_TYPE = $request->REPAIR_TYPE;
                $add->REPAIR_BEGIN_DATE = $REPAIRBEGINDATE;
                $add->REPAIR_END_DATE = $REPAIRENDDATE;   
                $add->REPAIR_TEAM_NAME = $request->REPAIR_TEAM_NAME;

                $add->REPAIR_TEAM_HR_ID = $request->REPAIR_TEAM_HR_ID;
                $TEAM_HR_NAME =  Person::leftJoin('hrd_prefix','hrd_person.HR_PREFIX_ID','=','hrd_prefix.HR_PREFIX_ID')
                ->leftJoin('hrd_position','hrd_person.HR_POSITION_ID','=','hrd_position.HR_POSITION_ID')
                ->where('hrd_person.ID','=',$request->REPAIR_TEAM_HR_ID)->first();
                $add->REPAIR_TEAM_HR_NAME   = $TEAM_HR_NAME->HR_PREFIX_NAME.''.$TEAM_HR_NAME->HR_FNAME.' '.$TEAM_HR_NAME->HR_LNAME;
    
            
                //----------------------------------

                $add->REPAIR_COMMENT = $request->REPAIR_COMMENT;
                $add->REPAIR_PICE_SUM = $request->REPAIR_AMOUNT*$request->REPAIR_PICE_UNIT;      
                $add->save();

            
                return redirect()->route('mplan.repair');




 }


 
 public function repair_destroy(Request $request,$idref)
 {

    Planrepair::destroy($idref);  

     return redirect()->route('mplan.repair');


 }



 //===============================


 
        //-------------------------------------ฟังชั่นรันเลขอ้างอิงแผนงานโครงการ--------------------


        public static function refnumberWj()
        {
            $infoyear = DB::table('plan_year')->first();

            if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
               $yearbudget = $infoyear->PLAN_YEAR;
            }else{
               $m_budget = date("m");
               if($m_budget>9){
               $yearbudget = date("Y")+544;
               }else{
               $yearbudget = date("Y")+543;
               }
       
            }
    
            $maxnumber = DB::table('plan_work')->where('PLANWORK_BUDGET','=',$yearbudget)->max('PLANWORK_ID');  
    
         
    
            if($maxnumber != '' ||  $maxnumber != null){
                
                $refmax = DB::table('plan_work')->where('PLANWORK_ID','=',$maxnumber)->first();  
    
    
                if($refmax->PLANWORK_CODE != '' ||  $refmax->PLANWORK_CODE != null){
                    $maxref = substr($refmax->PLANWORK_CODE, -4)+1;
                 }else{
                    $maxref = 1;
                 }
    
                $ref = str_pad($maxref, 4, "0", STR_PAD_LEFT);
           
            }else{
                $ref = '0001';
            }
    
            
            $y = substr($yearbudget, -2);
           
    
         $refnumber ='W-'.$y.''.$ref;
    
    
    
         return $refnumber;
        }
    
        public static function refnumberPj()
        {
            $infoyear = DB::table('plan_year')->first();

            if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
               $yearbudget = $infoyear->PLAN_YEAR;
            }else{
               $m_budget = date("m");
               if($m_budget>9){
               $yearbudget = date("Y")+544;
               }else{
               $yearbudget = date("Y")+543;
               }
       
            }
    
            $maxnumber = DB::table('plan_project')->where('BUDGET_YEAR','=',$yearbudget)->max('PRO_ID');  
    
         
    
            if($maxnumber != '' ||  $maxnumber != null){
                
                $refmax = DB::table('plan_project')->where('PRO_ID','=',$maxnumber)->first();  
    
    
                if($refmax->PRO_NUMBER != '' ||  $refmax->PRO_NUMBER != null){
                    $maxref = substr($refmax->PRO_NUMBER, -4)+1;
                 }else{
                    $maxref = 1;
                 }
    
                $ref = str_pad($maxref, 4, "0", STR_PAD_LEFT);
           
            }else{
                $ref = '0001';
            }
    
            
            $y = substr($yearbudget, -2);
           
    
         $refnumber ='P-'.$y.''.$ref;
    
    
    
         return $refnumber;
        }



        public static function refnumberPh()
        {
            $infoyear = DB::table('plan_year')->first();

            if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
               $yearbudget = $infoyear->PLAN_YEAR;
            }else{
               $m_budget = date("m");
               if($m_budget>9){
               $yearbudget = date("Y")+544;
               }else{
               $yearbudget = date("Y")+543;
               }
       
            }
    
            $maxnumber = DB::table('plan_humandev')->where('BUDGET_YEAR','=',$yearbudget)->max('HUM_ID');  
    
         
    
            if($maxnumber != '' ||  $maxnumber != null){
                
                $refmax = DB::table('plan_humandev')->where('HUM_ID','=',$maxnumber)->first();  
    
    
                if($refmax->HUM_NUMBER != '' ||  $refmax->HUM_NUMBER != null){
                    $maxref = substr($refmax->HUM_NUMBER, -4)+1;
                 }else{
                    $maxref = 1;
                 }
    
                $ref = str_pad($maxref, 4, "0", STR_PAD_LEFT);
           
            }else{
                $ref = '0001';
            }
    
            $y = substr($yearbudget, -2);
           
    
         $refnumber ='H-'.$y.''.$ref;
    
    
    
         return $refnumber;
        }



        public static function refnumberPt()
        {
     
            $infoyear = DB::table('plan_year')->first();

            if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
               $yearbudget = $infoyear->PLAN_YEAR;
            }else{
               $m_budget = date("m");
               if($m_budget>9){
               $yearbudget = date("Y")+544;
               }else{
               $yearbudget = date("Y")+543;
               }
       
            }
    
            $maxnumber = DB::table('plan_durable')->where('BUDGET_YEAR','=',$yearbudget)->max('DUR_ID');  
    
         
    
            if($maxnumber != '' ||  $maxnumber != null){
                
                $refmax = DB::table('plan_durable')->where('DUR_ID','=',$maxnumber)->first();  
    
    
                if($refmax->DUR_NUMBER != '' ||  $refmax->DUR_NUMBER != null){
                    $maxref = substr($refmax->DUR_NUMBER, -4)+1;
                 }else{
                    $maxref = 1;
                 }
    
                $ref = str_pad($maxref, 4, "0", STR_PAD_LEFT);
           
            }else{
                $ref = '0001';
            }
    

            $y = substr($yearbudget, -2);
           
    
         $refnumber ='A-'.$y.''.$ref;
    
    
    
         return $refnumber;
        }



        


        public static function refnumberPb()
        {
     
            $infoyear = DB::table('plan_year')->first();

            if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
               $yearbudget = $infoyear->PLAN_YEAR;
            }else{
               $m_budget = date("m");
               if($m_budget>9){
               $yearbudget = date("Y")+544;
               }else{
               $yearbudget = date("Y")+543;
               }
       
            }
    
            $maxnumber = DB::table('plan_repair')->where('BUDGET_YEAR','=',$yearbudget)->max('REPAIR_PLAN_ID');  
    
         
    
            if($maxnumber != '' ||  $maxnumber != null){
                
                $refmax = DB::table('plan_repair')->where('REPAIR_PLAN_ID','=',$maxnumber)->first();  
    
    
                if($refmax->REPAIR_NUMBER != '' ||  $refmax->REPAIR_NUMBER != null){
                    $maxref = substr($refmax->REPAIR_NUMBER, -4)+1;
                 }else{
                    $maxref = 1;
                 }
    
                $ref = str_pad($maxref, 4, "0", STR_PAD_LEFT);
           
            }else{
                $ref = '0001';
            }
    

            $y = substr($yearbudget, -2);
           
    
         $refnumber ='B-'.$y.''.$ref;
    
    
    
         return $refnumber;
        }

        public function planyear()
        {       
            $info = Planyear::first();
            $budget = DB::table('budget_year')->get();

            return view('manager_plan.plan_year',[
                'info' => $info,
                'budgets' =>$budget
            ]);
        }
        
        function planyearupdate(Request $request)
        {  
        
            $id = $request->PLAN_YEAR_ID;
            $planactive = Planyear::find($id);
            $planactive->PLAN_YEAR = $request->PLAN_YEAR;
            $planactive->save();
    
            return redirect()->route('mplan.planyear');
        }

        //==================เลือกสิทธ์
        public static function checkappplan($id_user)
        {
            $count =  Permislist::where('PERSON_ID','=',$id_user)
            ->where('PERMIS_ID','=','GMP003')
            ->count();    
        
         return $count;
        }

        //=======excel=====


        public function projectexcel(Request $request)
    {
        $infoyear = DB::table('plan_year')->first();

         if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
            $yearbudget = $infoyear->PLAN_YEAR;
         }else{
            $m_budget = date("m");
            if($m_budget>9){
            $yearbudget = date("Y")+544;
            }else{
            $yearbudget = date("Y")+543;
            }
    
         }

        $infoproject = DB::table('plan_project')
        ->leftJoin('plan_target','plan_project.TARGET_ID','=','plan_target.TARGET_ID')
        ->leftJoin('plan_kpi','plan_project.KPI_ID','=','plan_kpi.KPI_ID')
        ->leftJoin('plan_type','plan_project.PLAN_TYPE_ID','=','plan_type.PLAN_TYPE_ID')
        ->leftJoin('supplies_budget','plan_project.BUDGET_ID','=','supplies_budget.BUDGET_ID')
        ->leftJoin('plan_tracking','plan_project.PLAN_TRACKING_ID','=','plan_tracking.PLAN_TRACKING_ID')
        ->where('BUDGET_YEAR','=', $yearbudget)
        ->orderBy('PRO_ID', 'asc')->get();


        
        $sumbudget  = DB::table('plan_project')
        ->leftJoin('plan_target','plan_project.TARGET_ID','=','plan_target.TARGET_ID')
        ->leftJoin('plan_kpi','plan_project.KPI_ID','=','plan_kpi.KPI_ID')
        ->leftJoin('plan_type','plan_project.PLAN_TYPE_ID','=','plan_type.PLAN_TYPE_ID')
        ->where('BUDGET_YEAR','=', $yearbudget)
        ->sum('BUDGET_PICE');

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $search = '';
        $year_id =  $yearbudget;
        $type ='';
        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
      
        return view('manager_plan.plan_project_excel',[
            'infoprojects' => $infoproject,
            'sumbudget' => $sumbudget,
            'budgets' =>  $budget,
            'search'=> $search,
            'year_id'=>$year_id,  
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'type'=>$type,  
        ]);
      
    }

    public function humandevexcel(Request $request)
    {

        $infoyear = DB::table('plan_year')->first();

        if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
           $yearbudget = $infoyear->PLAN_YEAR;
        }else{
           $m_budget = date("m");
           if($m_budget>9){
           $yearbudget = date("Y")+544;
           }else{
           $yearbudget = date("Y")+543;
           }
   
        }

        $infohumandev = DB::table('plan_humandev')->where('BUDGET_YEAR','=', $yearbudget)
        ->leftjoin('plan_humandev_type','plan_humandev.HUM_TYPE_NAME','=','plan_humandev_type.PLAN_HUMANDEV_TYPE_ID')
        ->leftJoin('supplies_budget','plan_humandev.BUDGET_ID','=','supplies_budget.BUDGET_ID')
        ->orderBy('HUM_ID', 'asc')->get();
        
        $sumbudget = DB::table('plan_humandev')->where('BUDGET_YEAR','=', $yearbudget)->SUM('BUDGET_PICE');

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $search = '';
        $year_id =  $yearbudget;
        $type ='';
        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
      
        return view('manager_plan.plan_humandev_excel',[
            'infohumandevs' => $infohumandev,
            'sumbudget' => $sumbudget,
            'budgets' =>  $budget,
            'search'=> $search,
            'year_id'=>$year_id,  
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'type'=>$type,  
        ]);
      
      
    }

    public function durableexcel(Request $request)
    {
        $infoyear = DB::table('plan_year')->first();

        if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
           $yearbudget = $infoyear->PLAN_YEAR;
        }else{
           $m_budget = date("m");
           if($m_budget>9){
           $yearbudget = date("Y")+544;
           }else{
           $yearbudget = date("Y")+543;
           }
   
        }
        
        $infodurable = DB::table('plan_durable')
        ->leftJoin('supplies_budget','plan_durable.BUDGET_ID','=','supplies_budget.BUDGET_ID')
        ->where('BUDGET_YEAR','=', $yearbudget)
        ->orderBy('DUR_ID', 'asc')->get();

        $sumbudget = DB::table('plan_durable')->SUM('DUR_PICE_SUM');

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
        $search = '';
        $year_id =  $yearbudget;
        $type ='';
        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';

        return view('manager_plan.plan_durable_excel',[
            'infodurables' =>  $infodurable,
            'sumbudget' => $sumbudget,
            'budgets' =>  $budget,
            'search'=> $search,
            'year_id'=>$year_id,  
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'type'=>$type,   
        ]);
      
    }

    public function repairexcel(Request $request)
    {
      
        
     $infoyear = DB::table('plan_year')->first();

     if($infoyear->PLAN_YEAR !== '' && $infoyear->PLAN_YEAR !== null){
        $yearbudget = $infoyear->PLAN_YEAR;
     }else{
        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }

     }
     
     $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();
     $search = '';
     $year_id =  $yearbudget;
     $type ='';
     $displaydate_bigen = ($yearbudget-544).'-10-01';
    $displaydate_end = ($yearbudget-543).'-09-30';


     $inforepair  = DB::table('plan_repair')
     ->leftJoin('supplies_budget','plan_repair.BUDGET_ID','=','supplies_budget.BUDGET_ID')
     ->where('BUDGET_YEAR','=',$year_id )->get();
   
       
     
     $sumbudget  = DB::table('plan_repair')
     ->leftJoin('supplies_budget','plan_repair.BUDGET_ID','=','supplies_budget.BUDGET_ID')
     ->where('BUDGET_YEAR','=',$year_id )->SUM('REPAIR_PICE_SUM');


     return view('manager_plan.plan_repair_excel',[
         'budgets' =>  $budget,
         'search'=> $search,
         'year_id'=>$year_id,  
         'type'=>$type, 
         'inforepairs'=>$inforepair, 
         'sumbudget'=>$sumbudget, 
         'displaydate_bigen'=> $displaydate_bigen,
        'displaydate_end'=> $displaydate_end,   
     ]);
      
    }




}

