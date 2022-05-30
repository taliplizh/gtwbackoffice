$(document).ready(function () {  
    function detail(id){
    $.ajax({
            url:"{{route('suplies.detailapp')}}",
            method:"GET",
            data:{id:id},
            success:function(result){
                $('#detail').html(result);
            }            
        })    
    }
    $('.program').change(function(){
         if($(this).val()!=''){
         var select=$(this).val();
         var _token=$('input[name="_token"]').val();
         $.ajax({
                 url:"{{route('mrisk.fectprogram')}}",
                 method:"GET",
                 data:{select:select,_token:_token},
                 success:function(result){
                    $('.programsub').html(result);
                 }
         })
        // console.log(select);
         }        
    });

    $('.programsub').change(function(){
            if($(this).val()!=''){
            var select=$(this).val();
            var _token=$('input[name="_token"]').val();
            $.ajax({
                    url:"{{route('mrisk.fectprogramsub')}}",
                    method:"GET",
                    data:{select:select,_token:_token},
                    success:function(result){
                        $('.programsubsub').html(result);
                    }
            })
            // console.log(select);
            }        
    });
    $('.fectteam').change(function(){
         if($(this).val()!=''){
         var select=$(this).val();
         var _token=$('input[name="_token"]').val();
         $.ajax({
                 url:"{{route('mrisk.fectteam')}}",
                 method:"GET",
                 data:{select:select,_token:_token},
                 success:function(result){
                    $('.teamdetial').html(result);
                 }
         })      
         }        
    });
    $('.typelocation').change(function(){
            if($(this).val()!=''){
            var select=$(this).val();
            var _token=$('input[name="_token"]').val();
            $.ajax({
                    url:"{{route('mrisk.fecttypelocation')}}",
                    method:"GET",
                    data:{select:select,_token:_token},
                    success:function(result){
                        $('.typelocationdetail').html(result);
                    }
            })
            // console.log(select);
            }        
    });
   

    $('.addRow1').on('click',function(){
        addRow1();
        });
    
        function addRow1(){
        var count = $('.tbody1').children('tr').length;
            var tr =  '<tr>'+                    
                    '<td>'+              
                    '<input class="form-control input-sm fo13" >'+
                    '</td>'+ 
                    '<td>'+              
                    '<input name="RISKREP_USEREFFECT_BA[]" id="RISKREP_USEREFFECT_BA" class="form-control input-sm fo13" >'+
                    '</td>'+ 
                    '<td>'+                                
                    '<input name="RISKREP_AGE_BA[]" id="RISKREP_AGE_BA" class="form-control input-sm fo13">' +          
                    '</td>'+ 
                    '<td>'+                                
                    '<input name="RISKREP_SEX_BA[]" id="RISKREP_SEX_BA" class="form-control input-sm fo13" >' +          
                    '</td>'+
                    '<td>'+                                
                    '<input name="RISKREP_HN_BA[]" id="RISKREP_HN_BA" class="form-control input-sm fo13" >' +          
                    '</td>'+
                    '<td>'+                                
                    '<input name="RISKREP_DATEIN_BA[]" id="RISKREP_DATEIN_BA" class="form-control datepicker fo13" data-date-format="mm/dd/yyyy">' +          
                    '</td>'+
                    '<td>'+                                
                    '<input name="RISKREP_AN_BA[]" id="RISKREP_AN_BA" class="form-control input-sm fo13" >' +          
                    '</td>'+
                    '<td>'+                                
                    '<input name="RISKREP_DATEADD_BA[]" id="RISKREP_DATEADD_BA" class="form-control datepicker fo13" data-date-format="mm/dd/yyyy">' +          
                    '</td>'+
                    '<td style="text-align: center;"><a class="btn btn-hero-sm btn-hero-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt"></i></a></td>'+ 
                '</tr>';
            $('.tbody1').append(tr);
            };

            $('.tbody1').on('click','.remove', function(){
                $(this).parent().parent().remove();
    });
        
    $('.addRow4').on('click',function(){
            addRow4();
        });
    
        function addRow4(){
        var count = $('.tbody4').children('tr').length;
            var tr =   '<tr>'+                                        
                    '<td>'+
                    '<select name="HR_TEAM_ID[]" id="HR_TEAM_ID[]" class="form-control input-sm js-example-basic-single fo13" style="width: 100%">'+
                    '<option value="">--กรุณาเลือก--</option>'+
                    '@foreach ($infopers as $infoper)'+
                    '<option value="{{ $infoper ->ID  }}">{{ $infoper-> HR_FNAME }} {{ $infoper-> HR_LNAME }}</option>'+  
                    '@endforeach '+
                    '</select> '+
                    '</td>'+
                    '<td style="text-align: center;"><a class="btn btn-hero-sm btn-hero-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt"></i></a></td>'+
                    '</tr>';
        $('.tbody4').append(tr);
        };
    
        $('.tbody4').on('click','.remove', function(){
            $(this).parent().parent().remove();
    });

    $('.addRow5').on('click',function(){
            addRow5();
        });            
        function addRow5(){
        var count = $('.tbody5').children('tr').length;
            var tr =   '<tr>'+                                         
                    '<td>'+
                    '<select name="HR_DEPARTMENT_ID[]" id="HR_DEPARTMENT_ID" class="form-control input-sm fo13">'+
                    '<option value="">--กรุณาเลือกกลุ่มงาน--</option>'+
                    '@foreach ($departmentsubs as $departmentsub) '+                                                  
                    '<option value="{{ $departmentsub->HR_DEPARTMENT_ID }}" >{{$departmentsub->HR_DEPARTMENT_NAME}}</option> '+                           
                    '@endforeach '+
                    '</select> '+
                    '</td>'+
                    '<td style="text-align: center;"><a class="btn btn-hero-sm btn-hero-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt"></i></a></td>'+
                    '</tr>';
        $('.tbody5').append(tr);
        };            
        $('.tbody5').on('click','.remove', function(){
            $(this).parent().parent().remove();
    });

    $('.addRow6').on('click',function(){
            addRow6();
        });            
        function addRow6(){
        var count = $('.tbody6').children('tr').length;
            var tr =   '<tr>'+ 
                    '<td>'+              
                    '<select name="RISKREP_DEPART_SUB[]" id="RISKREP_DEPART_SUB" class="form-control input-sm fo13">'+
                    '<option value="">--กรุณาเลือกฝ่าย/แผนก--</option>'+
                    '@foreach ($infordepartmentsubs as $infordepartmentsub)'+                                                   
                    '<option value="{{ $infordepartmentsub->HR_DEPARTMENT_SUB_ID }}" >{{$infordepartmentsub->HR_DEPARTMENT_SUB_NAME}}</option>='+                               
                    '@endforeach '+
                    '</select>'+
                    '</td>'+               
                   
                    '<td style="text-align: center;"><a class="btn btn-hero-sm btn-hero-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt"></i></a></td>'+
                    '</tr>';
        $('.tbody6').append(tr);
        };            
        $('.tbody6').on('click','.remove', function(){
            $(this).parent().parent().remove();
    });

    $('.addRow7').on('click',function(){
            addRow7();
        });            
        function addRow7(){
        var count = $('.tbody7').children('tr').length;
            var tr =   '<tr>'+                                        
                    '<td>'+
                    '<select name="RISKREP_DEPART_SUBSUB[]" id="RISKREP_DEPART_SUBSUB" class="form-control input-sm fo13">'+
                    '<option value="">--กรุณาเลือกหน่วยงาน--</option>'+
                    '@foreach ($infordepartmentsubsubs as $infordepartmentsubsub)'+                                                   
                    '<option value="{{ $infordepartmentsubsub->HR_DEPARTMENT_SUB_SUB_ID }}">{{$infordepartmentsubsub->HR_DEPARTMENT_SUB_SUB_NAME}}</option>'+             
                    '@endforeach'+
                    '</select> '+
                    '</td>'+
                    '<td style="text-align: center;"><a class="btn btn-hero-sm btn-hero-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt"></i></a></td>'+
                    '</tr>';
        $('.tbody7').append(tr);
        };            
        $('.tbody7').on('click','.remove', function(){
            $(this).parent().parent().remove();
    });

    $('.addRow8').on('click',function(){
            addRow8();
        });
    
        function addRow8(){
        var count = $('.tbody8').children('tr').length;
            var tr =   '<tr>'+  
                    '<td>'+
                    '<input name="RISKREP_PEROUT[]" id="RISKREP_PEROUT" class="form-control input-sm fo13" >'+
                    '</td>'+
                    '<td style="text-align: center;"><a class="btn btn-hero-sm btn-hero-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt"></i></a></td>'+
                    '</tr>';
        $('.tbody8').append(tr);
        };
    
        $('.tbody8').on('click','.remove', function(){
            $(this).parent().parent().remove();
    });

    $('.addRow9').on('click',function(){
            addRow9();
        });
    
        function addRow9(){
        var count = $('.tbody9').children('tr').length;
            var tr =   '<tr>'+ 
                    '<td>'+              
                    '<input class="form-control input-sm fo13" >'+
                    '</td>'+                
                    '<td>'+
                    '<input name="RISKREP_REPEAT[]" id="RISKREP_REPEAT" class="form-control input-sm fo13">'+
                    '</td>'+
                    '<td style="text-align: center;"><a class="btn btn-hero-sm btn-hero-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt"></i></a></td>'+
                    '</tr>';
        $('.tbody9').append(tr);
        };
    
        $('.tbody9').on('click','.remove', function(){
            $(this).parent().parent().remove();
    });
    
});