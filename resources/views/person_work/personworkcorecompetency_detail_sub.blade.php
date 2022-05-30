@extends('layouts.general.person_work')
@section('css_after_infowork')
<style>
    .center {
        margin: auto;
        width: 100%;
        padding: 10px;
    }

    body {
        font-family: 'Kanit', sans-serif;
        font-size: 14px;

    }

    label {
        font-family: 'Kanit', sans-serif;
        font-size: 14px;

    }

    .text-pedding {
        padding-left: 10px;
        padding-right: 10px;
    }

    .text-font {
        font-size: 13px;
    }

    body {
        font-family: 'Kanit', sans-serif;
        font-size: 14px;
    }

    .form-control {
        font-family: 'Kanit', sans-serif;
        font-size: 13px;
    }
</style>
@endsection
@section('content_infowork')
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
    // date_default_timezone_set("Asia/Bangkok");
?>
<div class="block" style="width: 95%;">
    <div class="block-content">
        <div class="block-header block-header-default">
            <h3 class="block-title" style="font-family: 'Kanit', sans-serif;"><B>ประเมิน CORE COMPETENCY
                    {{$inforpersonuser->HR_FNAME}} {{$inforpersonuser->HR_LNAME}}</B></h3>
            <a href="{{ url('person_work/personworkcorecompetency_detail/'.$id_user)}}"
                class="btn btn-success">ย้อนกลับ</a>
        </div>
        <?php /* 
        <div class="block-content block-content-full">
            <form method="post" action="" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="IDHR" id="IDHR" value="{{$id_user}}">
                <input type="hidden" name="COR_RESULT_PERSON_ID" id="COR_RESULT_PERSON_ID" value="{{$inforpersonuser->ID}}">
                <div class="row">
                    <div class="col-md-2">
                        ปีงบประมาณ
                    </div>
                    <div class="col-md-2">
                        {{$infocorresult->COR_RESULT_YEAR}}
                    </div>
                    <div class="col-md-2">
                        ครั้งที่
                    </div>
                    <div class="col-md-2">
                        {{$infocorresult->COR_RESULT_NO}}
                    </div>
                    <div class="col-md-2">
                        ผู้ประเมิน
                    </div>
                    <div class="col-md-2">
                        {{$infocorresult->HR_FNAME}} {{$infocorresult->HR_LNAME}}
                    </div>
                </div>
                <br>
                <table class="gwt-table table-striped table-vcenter" width="100%">
                    <thead style="background-color: #FFEBCD;">

                        <tr height="40">
                            <th class="text-font" width="5%" style="border-color:#F0FFFF; text-align: center;">ลำดับ
                            </th>
                            <th class="text-font" style="border-color:#F0FFFF; text-align: center;">รายการ</th>
                            <th class="text-font" width="25%" style="border-color:#F0FFFF; text-align: center;">
                                ระดับ</th>
                            <th class="text-font" width="7%" style="border-color:#F0FFFF; text-align: center;">คะแนน
                            </th>
                            <th class="text-font" width="15%" style="border-color:#F0FFFF; text-align: center;">
                                หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($infoworkcorcoms as $infoworkcorcom)
                        <tr height="20">
                            <td class="text-font text-pedding" colspan="5" style="background-color: #FFF8DC;">
                                {{$infoworkcorcom->COR_COM_NAME}} :: {{$infoworkcorcom->COR_COM_DETAIL}}</td>
                        </tr>
                        <?php 
                            $infoworkcorcomlevels = DB::table('infowork_cor_com_level')->where('COR_COM_ID','=',$infoworkcorcom->COR_COM_ID)->get();
                            ?>
                        @foreach ($infoworkcorcomlevels as $infoworkcorcomlevel)
                        <tr height="20">
                            <td class="text-font text-pedding" colspan="5" style="background-color: #F0F8FF">
                                {{$infoworkcorcomlevel->COR_COM_LEVEL_DETAIL}}</td>
                        </tr>
                        <?php 
                                    $infoworkcorcomlevelsubs = DB::table('infowork_cor_com_level_sub')->where('COR_COM_LEVEL_ID','=',$infoworkcorcomlevel->COR_COM_LEVEL_ID)->get();
                                    $idhead = $infoworkcorcomlevel->COR_COM_LEVEL_ID;
                                    $count = DB::table('infowork_cor_com_level_sub')->where('COR_COM_LEVEL_ID','=',$infoworkcorcomlevel->COR_COM_LEVEL_ID)->count();
                                ?>
                        <input type="hidden" name="count{{$idhead}}" id="count{{$idhead}}" class="form-control input-sm"
                            style=" font-family: \'Kanit\', sans-serif;" value="{{$count}}">
                        <?php $number= 0; ?>
                        @foreach ($infoworkcorcomlevelsubs as $infoworkcorcomlevelsub)
                        <?php $number++; 
                                            $infoworktypescoresubs = DB::table('infowork_type_score_sub')->where('TYPE_SCORE_ID','=',$infoworkcorcomlevelsub->TYPE_SCORE_ID)->get();
                                            
                                            $infocorresultsub = DB::table('infowork_cor_result_sub')    
                                            ->leftjoin('infowork_type_score_sub','infowork_cor_result_sub.SCORE_ID','=','infowork_type_score_sub.TYPE_SCORE_SUB_ID')
                                            ->where('COR_COM_NUMBER','=',$number)->where('COR_COM_LEVEL_ID','=',$infoworkcorcomlevel->COR_COM_LEVEL_ID)->first();
                                            ?>
                        <tr height="20">
                            <td class="text-font text-pedding" style="text-align: center;">{{$number}}</td>
                            <td class="text-font text-pedding">{{$infoworkcorcomlevelsub->COR_COM_LEVEL_SUB_DETAIL}}
                            </td>
                            <td class="text-font text-pedding">
                                {{$infocorresultsub->TYPE_SCORE_SUB_NAME}}
                            </td>
                            <td class="text-font text-pedding" style="text-align: center;">
                                <div class="showscore{{$idhead}}{{$number}}">
                                    {{$infocorresultsub->TYPE_SCORE_SUB_TOTAL}}
                                    </div</td> <td class="text-font text-pedding"> {{$infocorresultsub->COMMENT}}
                            </td>
                        </tr>
                        <input type="hidden" name="COR_COM_NUMBER[]" id="COR_COM_NUMBER" value="{{$number}}">
                        <input type="hidden" name="COR_COM_ID[]" id="COR_COM_ID"
                            value="{{$infoworkcorcom->COR_COM_ID}}">
                        <input type="hidden" name="COR_COM_LEVEL_ID[]" id="COR_COM_LEVEL_ID"
                            value="{{$infoworkcorcomlevel->COR_COM_LEVEL_ID}}">
                        <input type="hidden" name="TYPE_SCORE_ID[]" id="TYPE_SCORE_ID"
                            value="{{$infoworkcorcomlevelsub->TYPE_SCORE_ID}}">
                        @endforeach
                        <tr height="20">
                            <td class="text-font text-pedding" colspan="3" style="background-color: #FBE2FA">คะแนนรวม
                            </td>
                            <td class="text-font text-pedding" style="background-color: #FBE2FA"><input
                                    class="form-control input-sm "
                                    style="text-align: center;background-color: #E0FFFF;font-size: 13px;" type="text"
                                    name="showscoresum{{$idhead}}" id="showscoresum{{$idhead}}" readonly></td>
                            <td class="text-font text-pedding" style="background-color: #FBE2FA"></td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
        */
        ?>
    </div>
</div>
<!--  <div align="right">
                <button type="submit"  class="btn btn-hero-sm btn-hero-info" >บันทึกข้อมูล</button>
                <a href="{{-- url('person_headdep/headdep_corecompetency/'.$id_user)  --}}" class="btn btn-hero-sm btn-hero-danger" onclick="return confirm('ต้องการที่จะยกเลิกการบันทึกข้อมูล ?')" >ยกเลิก</a> 
            </div>    -->
@endsection
@section('footer_infowork')
    <script>
        function checklogin(){
            window.location.href = '{{route("index")}}';
        }
    </script>
<script>
    jQuery(function () {
        Dashmix.helpers(['easy-pie-chart', 'sparkline']);
    });
</script>
<script>
    $(document).ready(function () {
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            todayBtn: true,
            language: 'th', //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
            thaiyear: true,
            autoclose: true //Set เป็นปี พ.ศ.
        }); //กำหนดเป็นวันปัจุบัน
    });

    function chkmunny(ele) {
        var vchar = String.fromCharCode(event.keyCode);
        if ((vchar < '0' || vchar > '9') && (vchar != '.')) return false;
        ele.onKeyPress = vchar;
    }
</script>
<script>
    function checkscore(idhead, number, count) {
        var SCORE = document.getElementById("SCORE" + idhead + number).value;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{route('mperson.checkscore')}}",
            method: "GET",
            data: {
                SCORE: SCORE,
                idhead: idhead,
                _token: _token
            },
            success: function (result) {
                $('.showscore' + idhead + number).html(result);
                scoreTotal(idhead, count);
            }
        })
    }

    function scoreTotal(idhead, count) {
        var arr = document.getElementsByName('sum' + idhead);
        var tot = 0;
        for (var i = 0; i < count; i++) {
            tot += parseFloat(arr[i].value);
        }
        document.getElementById('showscoresum' + idhead).value = tot;
    }
</script>
@endsection
