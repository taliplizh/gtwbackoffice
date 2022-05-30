<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Person;



date_default_timezone_set("Asia/Bangkok");

class ManagerevnController extends Controller
{
    public function dashboard()
    {
    
        return view('manager_evn.dashboard_evn');
    }

    public function detail()
    {
    
        return view('manager_evn.evndetail');
    }


    public function electrical()
    {    
        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
        $status = '';
        $search = '';
        $year_id = $yearbudget;

        return view('manager_evn.electrical',[
            'budgets' =>  $budget,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'status_check'=> $status,
            'search'=> $search,
            'year_id'=>$year_id, 
        ]);
    }
    public function electrical_add()
    {    
        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
        $status = '';
        $search = '';
        $year_id = $yearbudget;
        return view('manager_evn.electrical_add',[
            'budgets' =>  $budget,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'status_check'=> $status,
            'search'=> $search,
            'year_id'=>$year_id, 
        ]);
    }
    public function electrical_edit()
    {    
        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
        $status = '';
        $search = '';
        $year_id = $yearbudget;

        return view('manager_evn.electrical_edit',[
            'budgets' =>  $budget,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'status_check'=> $status,
            'search'=> $search,
            'year_id'=>$year_id, 
        ]);
    }

    
    public function plumbing()
    {   
        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
        $status = '';
        $search = '';
        $year_id = $yearbudget; 
        return view('manager_evn.plumbing',[
            'budgets' =>  $budget,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'status_check'=> $status,
            'search'=> $search,
            'year_id'=>$year_id,
        ]);
    }
    public function plumbing_add()
    {   
        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
        $status = '';
        $search = '';
        $year_id = $yearbudget; 
        return view('manager_evn.plumbing_add',[
            'budgets' =>  $budget,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'status_check'=> $status,
            'search'=> $search,
            'year_id'=>$year_id,
        ]);
    }
    public function plumbing_edit()
    {    
        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
        $status = '';
        $search = '';
        $year_id = $yearbudget;
        return view('manager_evn.plumbing_edit',[
            'budgets' =>  $budget,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'status_check'=> $status,
            'search'=> $search,
            'year_id'=>$year_id,
        ]);
    }  

  
    public function watertreatment()
    {    
        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
        $status = '';
        $search = '';
        $year_id = $yearbudget;
        return view('manager_evn.watertreatment',[
            'budgets' =>  $budget,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'status_check'=> $status,
            'search'=> $search,
            'year_id'=>$year_id,
        ]);
    }
    public function watertreatment_add()
    {   
        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
        $status = '';
        $search = '';
        $year_id = $yearbudget; 
        return view('manager_evn.watertreatment_add',[
            'budgets' =>  $budget,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'status_check'=> $status,
            'search'=> $search,
            'year_id'=>$year_id,
        ]);
    }
    public function watertreatment_edit()
    {    
        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }

        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
        $status = '';
        $search = '';
        $year_id = $yearbudget;
        return view('manager_evn.watertreatment_edit',[
            'budgets' =>  $budget,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'status_check'=> $status,
            'search'=> $search,
            'year_id'=>$year_id,
        ]);
    }  


}

