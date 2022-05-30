@extends('supply::layouts.master')

@section('content')

        {{-- This view is loaded from module: {!! config('supply.name') !!} --}}
        
        
    <div class="block">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title" style="font-family: 'Kanit', sans-serif;"><B><i class="fas fa-list-ul"></i> รายละเอียดการขอเบิก</B></h3>
            </div>

            <div class="block-content block-content-full">
                <form action="{{ route('mpay.mpay_withdraw_search') }}" method="post">
                    @csrf

                    <div class="row">

                        <div class="col-sm-0.5">
                            &nbsp;&nbsp; ปีงบ &nbsp;
                        </div>
                        <div class="col-sm-1.5">
                            <span>
                                <select name="BUDGET_YEAR" id="BUDGET_YEAR" class="form-control input-lg budget"
                                    style=" font-family: 'Kanit', sans-serif;font-size: 13px;font-weight:normal;">
                                    @foreach ($budgets as $budget)
                                        @if ($budget->LEAVE_YEAR_ID == $year_id)
                                            <option value="{{ $budget->LEAVE_YEAR_ID }}" selected>
                                                {{ $budget->LEAVE_YEAR_ID }}</option>
                                        @else
                                            <option value="{{ $budget->LEAVE_YEAR_ID }}">{{ $budget->LEAVE_YEAR_ID }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </span>
                        </div>

                        <div class="col-sm-4 date_budget">
                            <div class="row mb-4">
                                <div class="col-sm">
                                    วันที่
                                </div>
                                <div class="col-md-4">

                                    <input name="DATE_BIGIN" id="DATE_BIGIN" class="form-control input-lg datepicker"
                                        data-date-format="mm/dd/yyyy" value="{{ formate($displaydate_bigen) }}" readonly>

                                </div>
                                <div class="col-sm">
                                    ถึง
                                </div>
                                <div class="col-md-4">

                                    <input name="DATE_END" id="DATE_END" class="form-control input-lg datepicker"
                                        data-date-format="mm/dd/yyyy" value="{{ formate($displaydate_end) }}" readonly>

                                </div>
                            </div>

                        </div>
                        <div class="col-sm-0.5">
                            สถานะ
                        </div>
                        <div class="col-sm-2">
                            <span>

                                <select name="STATUS_CODE" id="STATUS_CODE" class="form-control">
                                    <option value="">--ทั้งหมด--</option>
                                    @if ($status_check == 'Request')
                                    <option value="Request" selected>ร้องขอ</option> @else<option value="Request">ร้องขอ
                                        </option>
                                    @endif
                                    @if ($status_check == 'Success')
                                    <option value="Success" selected>จัดสรร</option> @else<option value="Success">จัดสรร
                                        </option>
                                    @endif
                                </select>
                            </span>
                        </div>

                        <div class="col-sm-0.5">
                            &nbsp;ค้นหา &nbsp;
                        </div>

                        <div class="col-sm-2">
                            <span>

                                <input type="search" name="search" class="form-control" value="{{ $search }}">
                            </span>
                        </div>



                        <div class="col-md-30">
                            &nbsp;
                        </div>
                        <div class="col-md-1">
                            <span>
                                <button type="submit" class="btn-hero-sm btn-hero-primary ml-2 loadscreen">ค้นหา</button>
                            </span>
                        </div>


                    </div>
                </form>

                <div class="table-responsive">
                    <table class="gtw-table js-dataTable-simple" style="width: 100%;">
                        <thead style="background-color: #FFEBCD;">
                            <tr height="40">
                                <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="5%">ลำดับ</th>
                                <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="5%">สถานะ</th>
                                <th class="text-font" style="border-color:#F0FFFF;text-align: center;">รหัส</th>
                                <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="8%">วันที่
                                </th>
                                <th class="text-font" style="border-color:#F0FFFF;text-align: center;">เวลา</th>
                                <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="15%">
                                    เหตุผลขอเบิก</th>

                                <th class="text-font" style="border-color:#F0FFFF;text-align: center;">หน่วยงาน</th>
                                <th class="text-font" style="border-color:#F0FFFF;text-align: center;">เจ้าหน้าที่</th>
                                <th class="text-font" style="border-color:#F0FFFF;text-align: center" width="7%">คำสั่ง</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 0; ?>
                            @foreach ($dataPrivider as $model)

                                <?php $number++; ?>

                                <tr height="20">
                                    <td class="text-font" align="center">{{ $number }}</td>
                                    <td class="text-font" align="center">
                                        @if ($model->MPAY_WITHDROW_STATUS == 'Request')
                                            <span class="badge badge-warning">รัองขอ</span>
                                        @else
                                            <span class="badge badge-success">จัดสรร</span>
                                        @endif
                                    </td>
                                    <td class="text-font text-pedding" align="center">{{ $model->MPAY_WITHDROW_CODE }}</td>
                                    <td class="text-font text-pedding" align="center">
                                        {{ DateThai($model->MPAY_WITHDROW_DATE) }}</td>
                                    <td class="text-font text-pedding" align="center">{{ $model->MPAY_WITHDROW_TIME }}</td>
                                    <td class="text-font text-pedding">{{ $model->MPAY_WITHDROW_COMMENT }}</td>
                                    <td class="text-font text-pedding">{{ $model->HR_DEPARTMENT_SUB_SUB_NAME }}</td>
                                    <td class="text-font text-pedding">{{ $model->HR_FNAME }} {{ $model->HR_LNAME }}</td>

                                    <td class="text-font" align="center">

                                        {{-- <button type="button" class="btn btn-outline-info dropdown-toggle"
                                            id="dropdown-align-outline-info" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"
                                            style="font-family: 'Kanit', sans-serif; font-size: 10px;font-weight:normal;">
                                            ทำรายการ
                                        </button> --}}
                                        <a href="{{ url('supply/order') }}"><i class="far fa-edit"></i></a>


                                        <div class="dropdown-menu" style="width:10px">
                                            <a class="dropdown-item" href="#detail_appall{{ $model->MPAY_WITHDROW_ID }}"
                                                data-toggle="modal"
                                                style="font-family: 'Kanit', sans-serif; font-size: 13px;font-weight:normal;"
                                                onclick="">รายละเอียด</a>
                                            @if ($model->MPAY_WITHDROW_STATUS == 'Request')
                                                <a class="dropdown-item"
                                                    href="{{ url('manager_mpay/mpay_withdraw_edit/' . $model->MPAY_WITHDROW_ID) }}"
                                                    style="font-family: 'Kanit', sans-serif; font-size: 13px;font-weight:normal;">แก้ไข</a>
                                                <a class="dropdown-item"
                                                    href="{{ url('manager_mpay/mpay_withdraw_allocate/' . $model->MPAY_WITHDROW_ID) }}"
                                                    style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;"
                                                    style="font-family: 'Kanit', sans-serif; font-size: 13px;font-weight:normal;"
                                                    onclick="return confirm('คุณต้องการที่จะยืนยันการจัดสรรใช่หรือไม่ ?')">ยืนยันการจัดสรร</a>
                                            @endif
                                        </div>

                                    </td>

                                </tr>

                                <div id="detail_appall{{ $model->MPAY_WITHDROW_ID }}" class="modal fade" tabindex="-1"
                                    role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">

                                                <div class="row">
                                                    <div>
                                                        <h3 style="font-family: 'Kanit', sans-serif;">
                                                            &nbsp;&nbsp;&nbsp;&nbsp;รายละเอียดการขอเบิก&nbsp;&nbsp;</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row push">
                                                    <div class="col-md-2">
                                                        <label>รหัส</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        {{ $model->MPAY_WITHDROW_CODE }}
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>วันที่</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        {{ DateThai($model->MPAY_WITHDROW_DATE) }}
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>เวลา</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        {{ $model->MPAY_WITHDROW_TIME }}
                                                    </div>


                                                </div>
                                                <div class="row push">
                                                    <div class="col-md-2">
                                                        <label>เหตุผล</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        {{ $model->MPAY_WITHDROW_COMMENT }}
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>หน่วยงาน</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        {{ $model->HR_DEPARTMENT_SUB_SUB_NAME }}
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>เจ้าหน้าที่</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        {{ $model->HR_FNAME }} {{ $model->HR_LNAME }}
                                                    </div>

                                                </div>




                                            </div>
                                            <div class="modal-footer">
                                                <div align="right">
                                                    <button type="button" class="btn btn-secondary btn-lg"
                                                        data-dismiss="modal"
                                                        >ปิดหน้าต่าง</button>

                                                </div>
                                            </div>
                                            </form>
                                            </body>


                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
