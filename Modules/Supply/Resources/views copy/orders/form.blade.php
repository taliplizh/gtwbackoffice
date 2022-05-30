
   <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


 <select class="select2" name="state">
  <option value="AL">Alabama</option>
  <option value="WY">Wyoming</option>
</select>

    <div class="block" style="width: 95%;">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title" style="font-family: 'Kanit', sans-serif;"><B>รายการเบิก</B></h3>
                <div>
    <form id="form-search" action="" method="post">
                      <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-alt" id="search" name="barcode" placeholder="Scan Barcode 12345" style="background-color: #e0e1e2;">
                            <div class="input-group-append">
                                <button type="submit" class="btn-hero-sm btn-hero-primary ml-2 loadscreen"><i class="fas fa-search"></i> ค้นหา</button>
                            </div>
                        </div>
                    </div>
                </form>
                   
                  
                    </div>
                </div>
                </div>
                

                <div class="block-content block-content-full" style="width: 95%;">


    
                <form  method="post" action="{{route('mpay.mpay_pay_update')}}" enctype="multipart/form-data">
        @csrf
<div class="row">
        <div class="col-lg-12">

            <input type="hidden" name = "MPAY_LISTPAY_ID"  id="MPAY_LISTPAY_ID" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;">
        <div class="row push">
                <div class="col-lg-2">
                    <label >เลขที่จ่ายของ</label>
                </div>
                    <div class="col-lg-2">
                        <input  name = "MPAY_LISTPAY_NO"  id="MPAY_LISTPAY_NO" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;">
                    </div>
   
                <div class="col-lg-1">
                    <label >วันที่</label>
                </div>
                    <div class="col-lg-2">
                        <input  name = "MPAY_LISTPAY_DATE"  id="MPAY_LISTPAY_DATE" class="form-control input-lg datepicker" style=" font-family: 'Kanit', sans-serif;" value="{{formate(date('Y-m-d'))}}" readonly>
                    </div>
                <div class="col-lg-2">
                    <label >เวลา</label>
                </div>
                    <div class="col-lg-2">
                        <input  name = "MPAY_LISTPAY_TIME"  id="MPAY_LISTPAY_TIME" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;" value="{{date('H:i')}}">
                    </div>


      </div>
      <div class="row push">
                    <div class="col-lg-2">
                        <label >รายละเอียด</label>
                    </div>
                    <div class="col-lg-5">
                        <input   name = "MPAY_LISTPAY_DETAIL"  id="MPAY_LISTPAY_DETAIL" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;" >
                    </div>

                    <div class="col-lg-2">
                    <label >เจ้าหน้าที่ผู้จ่าย</label>
                </div>
                    <div class="col-lg-2">
                    {{-- <input type="text" value="{{$id_user}}" name="MPAY_LISTPAY_USER" id="MPAY_LISTPAY_USER" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;" > --}}
                        <select name="MPAY_LISTPAY_USER" id="MPAY_LISTPAY_USER" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;" >
                            <option value="">--เลือก--</option>
                                {{-- @foreach ($infopers as $infoper)
                                @if($infoper ->ID == $sticpays->MPAY_LISTPAY_USER)
                                    <option value="{{ $infoper ->ID }}" selected>{{ $infoper-> HR_FNAME}} {{ $infoper-> HR_LNAME}}</option>
@else
                                    <option value="{{ $infoper ->ID }}">{{ $infoper-> HR_FNAME}} {{ $infoper-> HR_LNAME}}</option>
                                    @endif
                                @endforeach --}}
                        </select>
                    
                    </div>


      </div>

      <div class="row push">
          
               
<div class="col-lg-2">
    <label >หน่วยงานที่รับ</label>
</div>
<div class="col-lg-5">
    <select name="MPAY_LISTPAY_DEPARTMENT" id="MPAY_LISTPAY_DEPARTMENT" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;" >
        <option value="">--เลือก--</option>
            {{-- @foreach ($departsubs as $departsub)
            @if($departsub ->HR_DEPARTMENT_SUB_SUB_ID == $sticpays->MPAY_LISTPAY_DEPARTMENT)
                <option value="{{ $departsub ->HR_DEPARTMENT_SUB_SUB_ID }}" selected>{{ $departsub->HR_DEPARTMENT_SUB_SUB_NAME}}</option>
                @else
                <option value="{{ $departsub ->HR_DEPARTMENT_SUB_SUB_ID }}">{{ $departsub->HR_DEPARTMENT_SUB_SUB_NAME}}</option>
                @endif --}}
            {{-- @endforeach --}}
    </select>
</div>

<div class="col-lg-2">
    <label >เจ้าหน้าที่ผู้รับ</label>
</div>
    <div class="col-lg-2">
        <select name="MPAY_LISTPAY_USERREC" id="MPAY_LISTPAY_USERREC" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;" >
            <option value="">--เลือก--</option>
                {{-- @foreach ($infopers as $infoper)
                @if($infoper->ID == $sticpays->MPAY_LISTPAY_USERREC)
                    <option value="{{ $infoper ->ID }}" selected>{{ $infoper-> HR_FNAME}} {{ $infoper-> HR_LNAME}}</option>
                    @else
                    <option value="{{ $infoper ->ID }}">{{ $infoper-> HR_FNAME}} {{ $infoper-> HR_LNAME}}</option>
                    @endif
                @endforeach --}}
        </select>
    
    </div>


</div>

                        
                    <table class="table-bordered table-striped table-vcenter" style="width: 100%;">
                                        <thead style="background-color: #F0F8FF;">
                                            <tr>
                                            <td style="text-align: center;">ลำดับ</td>
                                            <td style="text-align: center;">เลขที่</td>
                                                <td style="text-align: center;">รายการสิ่งของ</td>
                                                <td style="text-align: center;" width="10%">Lot</td>
                                                <td style="text-align: center;" width="15%">จำนวน</td>
                                                <td style="text-align: center;" width="15%">บาร์โค็ด</td>  
                                                <td style="text-align: center;" width="12%"><a  class="btn btn-success addRow" style="color:#FFFFFF;"><i class="fa fa-plus"></i></a></td>
                                            </tr>
                                        </thead> 
                                        <tbody class="tbody1"> 
                                     
                                           <div>
                                            <tr>   
                                                    <td style="text-align: center;">  </td>    
                                                        <td> 
                                                        <select class="select2 form-control" name="MPAY_NO[]" id="MPAY_NO[]">
                                                          <option value="AL">Alabama</option>
                                                          <option value="WY">Wyoming</option>
                                                        </select>
                                                        </td>
                                                        <td> 
                                                        <input value="" name="MPAY_LIST[]" id="MPAY_LIST[]" class="form-control input-sm">
                                                        </td>
                                                        <td> 
                                                            <input value="" name="MPAY_LOT[]" id="MPAY_LOT[]" class="form-control input-sm">
                                                            </td>
                                                        <td> 
                                                        <input value="" name="MPAY_QTY[]" id="MPAY_QTY[]" class="form-control input-sm">
                                                        </td>    
                                                        <td> 
                                                        <input value=""t name="MPAY_BARCODE[]" id="MPAY_BARCODE[]" class="form-control input-sm">
                                                        </td>       
                                                        <td style="text-align: center;"><a class="btn btn-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt"></i></a></td>
                                                    </tr>
                                         </div>

                                        </tbody>   
                    </table>

                </div>
            
        </div>                     
              
      </div>
   

        <div class="modal-footer">
        <div class="col-lg-12" align="right">
               
                        <button type="submit"  class="btn btn-hero-sm btn-hero-info" >บันทึก</button>
                        <a href="{{ url('manager_mpay/mpay_pay')  }}" class="btn btn-hero-sm btn-hero-danger" onclick="return confirm('ต้องการที่จะยกเลิกการเพิ่มข้อมูล ?')" >ยกเลิก</a>   
             
              
         </div>    
       
        </div>
        </form>  


        '<select class="select2 form-control" name="statex[]" id="MPAY_LISTxx[]">'+
          '<option value="AL">Alabama</option>'+
          '<option value="WY">Wyoming</option>'+
        '</select>'+


  <script>
$('.select2').select2();
$('body').on('submit', 'form#form-search', function (e) {
    e.preventDefault();
    // console.log($(this).serialize());
    $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
    $.ajax({
        type: "post",
        url: "{{ route('mpay.readbarcode') }}",
        data:$('#form-search').serialize(),
        dataType: "json",
        success: function (res) {
            addRow(res.data);
            e.target.reset();
        }
    });


    return false;
});


    $('.addRow').on('click',function(){
            addRow();
    });
    
        function addRow(data){
            
        
        var count = $('.tbody1').children('tr').length;
        
        var tr =    '<tr>'+
        '<td style="text-align: center;">'+
                    (count+1)+
                    '</td>'+
                    '<td>'+ 
                    '<select class="select2 form-control" name="statex[]" id="MPAY_LISTxx[]">'+
                      '<option value="AL">Alabama</option>'+
                      '<option value="WY">Wyoming</option>'+
                    '</select>'+
                    '</td>'+
                    '<td>'+ 
                    '<input name="MPAY_LIST[]" id="MPAY_LIST[]" class="form-control input-sm">'+
                    '</td>'+  
                    '<td>'+ 
                    '<input name="MPAY_LOT[]" id="MPAY_LOT[]" class="form-control input-sm">'+
                    '</td>'+   
                    '<td>'+ 
                    '<input name="MPAY_QTY[]" id="MPAY_QTY[]" class="form-control input-sm">'+
                    '</td>'+
                    '<td>'+ 
                    '<input name="MPAY_BARCODE[]" id="MPAY_BARCODE[]" class="form-control input-sm" value="'+data.barcode+'">'+
                    '</td>'+
                    '<td style="text-align: center;"><a class="btn btn-danger remove" style="color:#FFFFFF;"><i class=" fa fa-trash-alt"></i></a></td>'+
                    '</tr>';
        $('.tbody1').append(tr).select2();

        };
    
        $('.tbody1').on('click','.remove', function(){
            $(this).parent().parent().remove();
    });
    </script>

