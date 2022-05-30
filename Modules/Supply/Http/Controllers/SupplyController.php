<?php

namespace Modules\Supply\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Supply\Entities\Orders;
use App\Models\Person;
use App\Models\Hrddepartment;
use App\Models\Mpaysetupassetpieces;
class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $dataPrivider = DB::table('mpay_withdrow') 
        ->leftJoin('hrd_department_sub_sub','mpay_withdrow.MPAY_WITHDROW_DEP_SUB_SUB_ID','=','hrd_department_sub_sub.HR_DEPARTMENT_SUB_SUB_ID')
        ->leftJoin('hrd_person','mpay_withdrow.MPAY_WITHDROW_HR_ID','=','hrd_person.ID')
        ->orderBy('MPAY_WITHDROW_ID', 'desc')
        ->get();
        $budget = DB::table('budget_year')->orderBy('LEAVE_YEAR_ID', 'desc')->get();

        $m_budget = date("m");
        if($m_budget>9){
        $yearbudget = date("Y")+544;
        }else{
        $yearbudget = date("Y")+543;
        }     
        

        
        $displaydate_bigen = ($yearbudget-544).'-10-01';
        $displaydate_end = ($yearbudget-543).'-09-30';
        $status = '';
        $search = '';
        $year_id = $yearbudget;
        return view('supply::index',[
            'dataPrivider' => $dataPrivider,
            'budgets' =>  $budget,
            'displaydate_bigen'=> $displaydate_bigen,
            'displaydate_end'=> $displaydate_end,
            'status_check'=> $status,
            'search'=> $search,
            'year_id'=>$year_id,
        ]);
    }


    public function order(){
        return View('supply::order.index');
    }

    public function create()
    {
        $model = new Orders;
        $person = Person::findOrFail(Auth::user()->PERSON_ID);
        $mpaysetupassetpiecesList = Mpaysetupassetpieces::all();

        return view('supply::orders.create',[
            'model' => $model,
            'person' => $person,
            'mpaysetupassetpiecesList' => $mpaysetupassetpiecesList
            ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('supply::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('supply::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function demo(){
        return response()->json(Hrddepartment::all());

    }



}
