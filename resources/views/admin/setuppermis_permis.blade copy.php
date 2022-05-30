@extends('layouts.backend_admin')
<link href="{{ asset('select2/select2.min.css') }}" rel="stylesheet" />

@section('content')
   
<style>
.center {
  margin: auto;
  width: 100%;
  padding: 10px;
}
body {
      font-family: 'Kanit', sans-serif;
      font-size: 13px;
  
      }

label{
            font-family: 'Kanit', sans-serif;
            font-size: 13px;
        
      } 

      .text-pedding{
   padding-left:10px;
                    }

        .text-font {
    font-size: 13px;
                  }   
</style>
<script>
  function checklogin(){
   window.location.href = '{{route("index")}}'; 
  }
  </script>
<?php
if(Auth::check()){
    $status = Auth::user()->status;
    $id_user = Auth::user()->PERSON_ID;   
}else{
    
    echo "<body onload=\"checklogin()\"></body>";
    exit();
} 
$url = Request::url();
$pos = strrpos($url, '/') + 1;
$user_id = substr($url, $pos); 

if($status=='USER' and $user_id != $id_user  ){
    echo "You do not have access to data.";
    exit();
}
?>
<?php
function RemoveDateThai($strDate)
{
  $strYear = date("Y",strtotime($strDate))+543;
  $strMonth= date("n",strtotime($strDate));
  $strDay= date("j",strtotime($strDate));

  $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  $strMonthThai=$strMonthCut[$strMonth];
  return "$strDay $strMonthThai $strYear";
  }


  function Removeformate($strDate)
{
  $strYear = date("Y",strtotime($strDate));
  $strMonth= date("m",strtotime($strDate));
  $strDay= date("d",strtotime($strDate));

  
  return $strDay."/".$strMonth."/".$strYear;
  }

  
?>

           
                    <!-- Advanced Tables -->
                  
                <div class="content">
                <div class="block block-rounded block-bordered">

               
                <div class="block-content">    
                <h2 class="content-heading pt-0" style="font-family: 'Kanit', sans-serif;">
                <div class="row">
                <div class="col-md-5">
                กำหนดข้อมูลสิทธิ์ของ {{ $useid->HR_PREFIX_NAME }}{{$useid->HR_FNAME}} {{$useid->HR_LNAME}}
                </div>
                
             
               <div class="col-md-5">
               </div>
               <div class="col-md-2">
               <a href="{{ url('admin_permis/setupinfopermis') }}"  class="btn btn-success btn-lg"  >ย้อนกลับ</a>
               <!--<a href="{{ url('admin_leave/setupinfovacation/export_pdf') }}"  class="btn btn-hero-sm btn-hero-danger"  target="_blank">Print</a>-->
               </div>
               </div>
                
                
                </h2> 
                        {{-- <a href="" class="btn btn-hero-sm btn-hero-info" data-toggle="modal" data-target=".add" ><i class="fas fa-plus"></i> เพิ่มข้อมูลสิทธิ์</a> --}}
                        <div class="row">
                            <div class="col-lg-8">
                                <a href="" class="btn btn-hero-sm btn-hero-info" data-toggle="modal" data-target=".add" ><i class="fas fa-plus"></i> เพิ่มข้อมูลสิทธิ์</a>
                            </div>
                      
                      </div> 
                       
                        <div class="mt-3">
                        <div class="table-responsive">      
                
                  <table class="gwt-table table-striped table-vcenter js-dataTable-full" width="100%">
                  <thead style="background-color: #FFEBCD;">
                  
        <tr height="40">
        <th class="text-font"  width="15%" style="border-color:#F0FFFF;text-align: center;">รหัส</th>
        <th class="text-font"  style="border-color:#F0FFFF;text-align: center;">สิทธิ์</th>
            
        <th class="text-font"  width="5%" style="border-color:#F0FFFF;text-align: center;">ลบ</th>
        
        
      </tr>
                   </tr>
                   </thead>
                   <tbody id="myTable">
                   @foreach ($infoapprovpermiss as $infoapprovpermis)
                   <tr height="40">
                     <td class="text-font"  align="center">{{ $infoapprovpermis-> PERMIS_ID }} </td>                 
                     <td class="text-font text-pedding">{{ $infoapprovpermis-> PERMIS_NAME }}</td> 
                   
                   
                     <td align="center">
                     <a href="{{ url('admin_permis/setupinfopermis/destroypermis/'. $infoapprovpermis-> ID.'/'.$infoapprovpermis-> PERSON_ID)  }}" class="btn btn-danger" onclick="return confirm('ต้องการที่จะลบข้อมูล {{ $infoapprovpermis-> PERMIS_ID }}?')"><i class="fas fa-times"></i></a>
                     </td>   
                    
                   </tr> 
 
                   @endforeach 
                   </tbody>
                  </table>

                  


                  <div class="modal fade add" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

    <div class="modal-header">
          
          <h2 class="modal-title" style="font-family: 'Kanit', sans-serif; font-size:15px;font-size: 1.5rem;font-weight:normal;">เพิ่มข้อมูลสิทธิ์เจ้าหน้าที่</h2>
        </div>
        <div class="modal-body">
        <body>
        <form  method="post" action="{{ route('setup.saveapprovpermis') }}" >
        @csrf
        <div class="row push">
      <div >&nbsp;</div>
      <div class="col-sm-2">
      <label >สิทธิ์</label>
      </div>
      <div class="col-sm-8">
      <select name="PERMIS_ID" id="PERMIS_ID" class="form-control input-sm js-example-basic-single {{ $errors->has('PERMIS_ID') ? 'is-invalid' : '' }}" style=" font-family: 'Kanit', sans-serif;font-size: 13px;">
                    <option value="" selected>--กรุณาเลือกสิทธิ์เจ้าหน้าที่--</option>
                   
                        @foreach ($inforpermisselects as $inforpermisselect)
                        <?php $count=0; ?>
                                        @foreach ($infoapprovpermiss as $infoapprovpermis)
                                            
                                            @if($inforpermisselect->PERMIS_ID === $infoapprovpermis-> PERMIS_ID)
                                            <?php $count++; ?>
                                            @endif
                                        @endforeach 
                           @if($count == 0)                                                    
                               <option value="{{ $inforpermisselect->PERMIS_ID  }}">{{ $inforpermisselect->PERMIS_NAME}}</option>
                           @endif
                        @endforeach 
    </select>

  



    </div>
      </div>

     <input type="hidden" name = "USE_ID"  id="USE_ID"  value="{{  $useid->ID }} ">
   
      

      </div>
        <div class="modal-footer">
        <div align="right">
        <button type="submit"  class="btn btn-hero-sm btn-hero-info" >บันทึกข้อมูล</button>
        <button type="button" class="btn btn-hero-sm btn-hero-danger" data-dismiss="modal" >ยกเลิก</button>
        </div>
        </div>
        </form>  
</body>
     
     
    </div>
  </div>
</div>

<br>
                       
                   </div>
               
                </div>
                 
                  
      
                      

@endsection

@section('footer')

<script src="{{ asset('select2/select2.min.js') }}"></script>

<script src="{{ asset('datepicker/bootstrap-3.3.7-dist/js/bootstrap.js') }}"></script>
<script src="{{ asset('datepicker/dist/js/bootstrap-datepicker-custom.js') }}"></script>
<script src="{{ asset('datepicker/dist/locales/bootstrap-datepicker.th.min.js') }}" charset="UTF-8"></script>



<script>
  
  $(document).ready(function() {
    $('select').select2({
    width: '100%'
});

    });


   $(document).ready(function () {
            
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true,
                autoclose: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", 0);  //กำหนดเป็นวันปัจุบัน
    });

    $(document).ready(function () {
            
            $('.datepicker2').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true,
                autoclose: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", 0);  //กำหนดเป็นวันปัจุบัน
    });

    $(document).ready(function () {
            
            $('.datepicker3').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true,
                autoclose: true              //Set เป็นปี พ.ศ.
            });  //กำหนดเป็นวันปัจุบัน
    });

    function chkmunny(ele){
var vchar = String.fromCharCode(event.keyCode);
if ((vchar<'0' || vchar>'9' )&& (vchar != '.')) return false;
ele.onKeyPress=vchar;
}
    

    $('body').on('keydown', 'input, select, textarea', function(e) {
    var self = $(this)
      , form = self.parents('form:eq(0)')
      , focusable
      , next
      ;
    if (e.keyCode == 13) {
        focusable = form.find('input,a,select,button,textarea').filter(':visible');
        next = focusable.eq(focusable.index(this)+1);
        if (next.length) {
            next.focus();
        } else {
            form.submit();
        }
        return false;
    }
});

  
</script>

        <script src="{{ asset('asset/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('asset/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Page JS Code -->
        <script src="{{ asset('asset/js/pages/be_tables_datatables.min.js') }}"></script>

        <script>
            $(document).ready(function(){
              $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
              });
            });
        </script>

@endsection