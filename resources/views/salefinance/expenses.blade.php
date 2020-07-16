@extends('layouts.master',['displayDataTables' => TRUE])
@section('title', 'Sales & Finances | Expenses')
@section('styles')
<style type="text/css">
  .active1,.active1:hover{
    background-color: #006BFF;
    border: 0px;

  }
</style>
@stop
@section('content')
  @if (session('status'))
        <div class="alert alert-info">{{session('status')}}</div>
  @endif
<div class="x_panel">
<form action="{{route('expense-template')}}" id="calendar_form" name="calendar_form" class="form-inline"> 
    {{ csrf_field() }}
     
    <div class="groupOptions btn-group btn-group-sm mb-2" role="group" style="margin:auto">
            <button id="btnOne" type="button" data-type="1" name="options" class="btn btn-sm btn-light" autocomplete="off">January</button>
            <button id="btnTwo"  type="button" data-type="2"  name="options" class="btn btn-sm btn-light" autocomplete="off">February</button>
            <button id="btnThree"  type="button" data-type="3"  name="options" class="btn btn-sm btn-light" autocomplete="off">March</button>
            <button id="btnFour"  type="button" data-type="4"  name="options" class="btn btn-sm btn-light" autocomplete="off">April</button>
            <button id="btnFine"  type="button" data-type="5"  name="options" class="btn btn-sm btn-light" autocomplete="off">May</button>
            <button id="btnSix" type="button" data-type="6" name="options" class="btn btn-sm btn-light" autocomplete="off">June</button>
            <button id="btnSeven"  type="button" data-type="7"  name="options" class="btn btn-sm btn-light" autocomplete="off">July</button>
            <button id="btnEight"  type="button" data-type="8"  name="options" class="btn btn-sm btn-light" autocomplete="off">August</button>
            <button id="btnNine"  type="button" data-type="9"  name="options" class="btn btn-sm btn-light" autocomplete="off">September</button>
            <button id="btnTeen"  type="button" data-type="10"  name="options" class="btn btn-sm btn-light" autocomplete="off">October</button>
            <button id="btnEleven"  type="button" data-type="11"  name="options" class="btn btn-sm btn-light" autocomplete="off">November</button>
            <button id="btntwelve"  type="button" data-type="12"  name="options" class="btn btn-sm btn-light" autocomplete="off">December</button>
        </div>
        <!-- <a href="{{route('expense-template')}}"><button class="btn btn-primary">
          <i class="glyphicon glyphicon-plus fa fa-plus"></i><span style="color: white;"> Set Up</span>
        </button></a> -->

</form>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="">
      <div class="add_pe" style="padding:0;display: none">
        <table class="table table-striped table-bordered">
          <tbody>
            <tr>
              <form id="pe_form">
              <td><input type="text" required id="category" class="form-control form-control-sm" placeholder="Category" name=""></td>
              <td><input type="text" required id="amount" onkeypress="return isNumberKey(event)" class="form-control form-control-sm" placeholder="Amount" name=""></td>
              <td><select name="" id="cycle" class="form-control form-control-sm">
                <option value="1">Check</option>
                <option value="2">Cash</option>
                <option value="3">Credit</option>
              </select></td>
              <td><select name="" id="pay" class="form-control form-control-sm">
                <option value="1">Same</option>
                <option value="2">Regular</option>
              </select></td>
              <td><input type="text" required id="bill" class="form-control form-control-sm" placeholder="Bill" name=""></td>
            </form>
            </tr>
          </tbody>
        </table>
      <button  class="btn btn-sm btn-primary float-right btn-submit"  type="button">Submit</button>
      <button class="btn btn-sm btn-danger float-right btn-cancel" type="button">Cancel</button>
      </div>
    </div>

    <div class="x_panel table-responsive">    
      <table id="datatable" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th class="text-center" width="10">No.</th>  
            <th class="text-center">Date</th>
            <th class="text-center">Category</th>
            <th class="text-center">Amount($)</th>
            <th class="text-center">Pay</th>
            <th class="text-center">Cycle</th>
            <th class="text-center">Bill</th>
            <th class="text-center">Total</th>
            <th class="text-center">Aver Month</th>
            <th class="text-center">Last Year</th>
            <th class="text-center">Action</th>        
          </tr>
        </thead>
      </table>  
    </div>
  </div>
 {{--  <div class="col-md-4">
    <div class="x_panel table-responsive">    
      <table id="datatable_aver" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th class="text-center" width="10">Category</th>  
            <th class="text-center">Total</th>
            <th class="text-center">Aver Month</th>
            <th class="text-center">Last Year</th>     
          </tr>
        </thead>
      </table>  
    </div>
  </div> --}}
  
</div>

@stop
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {

   
  $('#order_date').daterangepicker({ 
      startDate: moment().subtract('month').startOf('month'), 
      endDate: moment().subtract('month').endOf('month')
  });

  //code by tri
  var d = new Date();
  var n = d.getMonth();
  n=n+1;
  var exp_month=n;
  $('button[name="options"]').removeClass("active").addClass("btn-light");
  $('button[data-type="'+n+'"]').removeClass("btn-light").addClass("btn-primary").addClass('active');
  checkDataExit(exp_month);//goi ham de luc moi load chay de insert data
    var oTable =  $('#datatable').DataTable({
    dom: 'lBfrtip',
    ordering: false,
    buttons: [
                
                {
                    text: '<i class="glyphicon glyphicon-plus "></i>Add',
                    className: "btn-sm btn add_button",
                },
                {
                    text: '<i class="glyphicon glyphicon-copy "></i>Copy',
                    className: "btn-sm btn copy_button",
                }

        ],
     processing: true,
     serverSide: true,

     searching: false,
     ajax:{ url:"{{ route('get-expenses') }}",
         data: function (d) {
                d.expense_month=$('button[name=options].active').attr('data-type');
            }
          },
          columns: [
              { data: 'pe_id', name: 'pe_id' },
              { data: 'pe_date', name: 'pe_date' },
              { data: 'pe_name', name: 'pe_name' },
              { data: 'pe_cost', name: 'pe_cost' },
              { data: 'pe_pay', name: 'pe_pay' },
              { data: 'pe_cycle', name: 'pe_cycle' },
              { data: 'pe_bill', name: 'pe_bill' },
              { data: 'category_cost', name: 'category_cost' },
              { data: 'aver_month', name: 'aver_month' },
              { data: 'last_year', name: 'last_year' },

              { data: 'action' , name: 'action',  orderable: false, searchable: false }
           ],
           columnDefs: [
              {
                  "targets": 1, 
                  "className": "text-center"
             },
             {
                  "targets": 3,
                  "className": "text-right cost",
             },
             {
                  "targets": 4,
                  "className": "text-center",
             },
             {
                  "targets": 5,
                  "className": "text-center",
             },
             {
                  "targets": 6,
                  "className": "text-center bill",
/*<<<<<<< HEAD
             },
             {
                  "targets": 7,
                  "className": "text-center",
             }
             ],
      });

    sTable =  $('#datatable_aver').DataTable({
    dom: 'lBfrtip',
    buttons: [            
        ],
     processing: true,
     serverSide: true,
     responsive: true,
     autoWidth: true,
     searching: false,
     ajax:{ url:"{{ route('get-expenses-aver') }}",
         data: function (d) {
                d.expense_month=$('button[name=options].active').attr('data-type');
            }
          },
          columns: [
              { data: 'pe_name', name: 'pe_name' },
              { data: 'category_cost', name: 'category_cost' },
              { data: 'aver_month', name: 'aver_month' },
              { data: 'last_year', name: 'last_year' },
           ],
           columnDefs: [
              {
                  "targets": 1, 
                  "className": "text-center"
=======
>>>>>>> origin/thieu*/
             },
             {
                  "targets": 7,
                  "className": "text-right",
             },
             {
                  "targets": 8,
                  "className": "text-right",
             },
             {
                  "targets": 9,
                  "className": "text-right",
             }
             ],
      });

    // sTable =  $('#datatable_aver').DataTable({
    // dom: 'lBfrtip',
    // buttons: [            
    //     ],
    //  processing: true,
    //  serverSide: true,
    //  responsive: true,
    //  autoWidth: true,
    //  searching: false,
    //  ajax:{ url:"{{ route('get-expenses-aver') }}",
    //      data: function (d) {
    //             d.expense_month=$('button[name=options].active').attr('data-type');
    //         }
    //       },
    //       columns: [
    //           { data: 'pe_name', name: 'pe_name' },
    //           { data: 'category_cost', name: 'category_cost' },
    //           { data: 'aver_month', name: 'aver_month' },
    //           { data: 'last_year', name: 'last_year' },
    //        ],
    //        columnDefs: [
    //           {
    //               "targets": 1, 
    //               "className": "text-center"
    //          },
    //          {
    //               "targets": 2, 
    //               "className": "text-center"
    //          },

    //          {
    //               "targets": 3,
    //               "className": "text-center",
    //          },
    //          ],
    //   });

    // code by tri
      $('button[name="options"]').click(function(e){
        $('button[name="options"]').removeClass("active").removeClass('btn-primary').addClass("btn-light");
        $(this).removeClass('btn-light').addClass('active').addClass("btn-primary");
        var checkMonth=$(this).attr('data-type');
        checkDataExit(checkMonth);
        oTable.draw();
        //sTable.draw();
        e.preventDefault();
      });
      
      //function check exit data
        function checkDataExit(checkMonth) {
          $.ajax({
                url:'{{route("check-data-null")}}',
                type:"GET",
                cache:false,
                data:{
                    "checkMonth":checkMonth,
                },
                success:function(data){
                  // alert(typeof(data));
                  if(data=="0")
                  {
                      $.ajax({
                        url:'{{route("insert-expense")}}',
                        type:"GET",
                        cache:false,
                        data:{
                          "checkMonth":checkMonth,
                        },
                        success:function(data1){
                          if(data1!=null)
                          {
                            oTable.draw();
                            //sTable.draw();
                          }
                        }
                      });
                  }
                }
          });
        }
      //end function
      $(document).on('click','.cost', function(){
        var id_exp=$(this).children().attr('id');
        var pe_name=$(this).children().attr('name');
        // console.log(id_exp);
        var amount=$(this).children().attr('value');
        $(this).children().html('<input type="number" value="'+amount+'"/>');
        var num = $(this).children().children().val();        
        $(this).children().children().focus().val('').val(num);
        // $(this).children().children().focus();
        // var pe_cost=$(this).children().children().attr('value');
        var dInput=$(this).children().children().attr('value');
        $(this).find('input[type="number"]').keyup(function() {
            dInput = this.value;
            // $(".dDimension:contains('" + dInput + "')").css("display","block");
        });
        console.log(dInput);
        $(this).click(function(event){
            event.stopPropagation();
        });
        var dateData=$(this).children().attr('date');
        var d= new Date(dateData);
        var monthData=d.getMonth()+1;
        // console.log(monthData);
        $(this).on('focusout',function(e) {
          setAjax(exp_month,monthData,id_exp,dInput,pe_name);
        })
        .on('keypress',function(e){
          var keycode = (e.keyCode ? e.keyCode : e.which);
          if(keycode == '13'){
            $(this).unbind('focusout');//stop focusout
            setAjax(exp_month,monthData,id_exp,dInput,pe_name);
          }
        });
      });

      function setAjax(exp_month,monthData,id_exp,dInput,pe_name){
        if(exp_month<=monthData)
          {
            $.ajax({
              url:'{{route("update-amount")}}',
              type:"GET",
              cache:false,
              data:{
                  "id_exp":id_exp,
                  "pe_cost":dInput,
                  "month_current":monthData,
                  "pe_name":pe_name
              },
              success:function(data){
                  oTable.draw();
                  //sTable.draw();
              }
            });
          }
          else{
            toastr.error('you are not authorized to edit!');
            oTable.draw();
          }
      }

      //check exists and add data
      // $('button[name="options"]').
      // $(document).on('click','button[name="options"]',function(){
      //   var checkMonth=$(this).attr('data-type');
      //   alert(checkMonth);
      // });
      //end
    //end code
      $(document).on('click','.delete-expense', function(){
        var date= $(this).attr('date');
        var d= new Date(date);
        var monthDel=d.getMonth()+1;
        console.log(monthDel);
        var id = $(this).attr('id');
        if(exp_month<=monthDel)
        {
          if(window.confirm("Are you sure you want to delete this item ?"))
          {
            $.ajax({
              url:"{{route('delete-expense')}}",
              method:"get",
              data:{id:id},
              success:function(data)
              {
                  toastr.success("Delete expense Success!");
                  oTable.draw();
                  //sTable.draw();
              }
            })
          }
          else{
            return false;
          }
        }
        else
        {
          toastr.error('you are not authorized to delete!');
        }
      });
      $(document).on('change','.status',function(e){

        var column = $(this).attr('column');
        var pe_name = $(this).attr('name');
        var id = $(this).attr('id');
        var style_id = $(this).val();

        var date= $(this).attr('date');
        var d= new Date(date);
        var monthDel=d.getMonth()+1;
        console.log(monthDel);
        var id = $(this).attr('id');
        if(exp_month<=monthDel){
          $.ajax({
            url: '{{route('change-style')}}',
            type: 'GET',
            dataType: 'html',
            data: {column: column, id:id, style_id:style_id,month_current:monthDel,pe_name:pe_name},
          })
          .done(function(data) {
            oTable.draw();
            //sTable.draw();
            //console.log(data);
          })
          .fail(function() {
            console.log("error");
          });

        }else
        {
          toastr.error('You are not authorized to edit!');
          oTable.draw();
        }
      });
      $(document).on('click','.bill',function(){

        var date= $(this).children().attr('date');
        var d= new Date(date);
        var monthDel=d.getMonth()+1;
        console.log(monthDel);
        var id = $(this).attr('id');
        if(exp_month<=monthDel){

          var id_exp=$(this).children().attr('id');
          // console.log(id_exp);
          var amount=$(this).children().attr('value');
          $(this).children().html('<input type="text" value="'+amount+'"/>');
          var num = $(this).children().children().val();        
          $(this).children().children().focus().val('').val(num);
          // $(this).children().children().focus();
          // var pe_cost=$(this).children().children().attr('value');
          var dInput=$(this).children().children().attr('value');
          $(this).find('input[type="text"]').keyup(function() {
              dInput = this.value;
              // $(".dDimension:contains('" + dInput + "')").css("display","block");
          });
          console.log(dInput);
          $(this).click(function(event){
              event.stopPropagation();
          });
          var dateData=$(this).children().attr('date');
          var d= new Date(dateData);
          var monthData=d.getMonth()+1;
          // console.log(monthData);
          $(this).on('focusout',function(e) {
            setAjaxBill(exp_month,monthData,id_exp,dInput);
          })
          .on('keypress',function(e){
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if(keycode == '13'){
              $(this).unbind('focusout');//stop focusout
              setAjaxBill(exp_month,monthData,id_exp,dInput);
            }
          });
        }
        else{
          toastr.error('You are not authorized to edit!');
        }
      });

      function setAjaxBill(exp_month,monthData,id_exp,dInput){
        if(exp_month<=monthData)
          {
            $.ajax({
              url:'{{route("change-bill")}}',
              type:"GET",
              cache:false,
              data:{
                  "id":id_exp,
                  "pe_bill":dInput
              },
              success:function(data){
                // console.log(data);
                  oTable.draw();
              }
            });
          }
          else{
            alert('you are not authorized to edit!');
            oTable.draw();
          }
      }
      $(document).on('click','.copy_button',function(e){

        var month_selected = $('button[name=options].active').attr('data-type');

        if(month_selected >= exp_month){

          $.ajax({
            url: '{{route('expenses-copy-data')}}',
            type: 'GET',
            dataType: 'html',
            data: {month_selected: month_selected},
          })
          .done(function(data) {
            oTable.draw();
            //sTable.draw();
            toastr.success(data);
            //console.log(data);
          })
          .fail(function() {
            oTable.draw();
            //sTable.draw();
            console.log("error");
          });
        }
        
        
      });
    $(document).on('click','.add_button',function(){
      var month_selected = $('button[name=options].active').attr('data-type');
      if(month_selected >= exp_month){
        $(".add_pe").slideDown();
      }
    });
    $(document).on('click','.btn-cancel',function(){

      $("#pe_form")[0].reset();

      $(".add_pe").slideUp();
    });
    $(".btn-submit").click(function(event) {

      var month_selected = $('button[name=options].active').attr('data-type');
      var category = $("#category").val();
      var pay = $("#pay option:selected").val();
      var amount = $("#amount").val();
      var bill = $("#category").val();
      var cycle = $("#cycle option:selected").val();
      if(month_selected >= exp_month){
        if(category != "" && pay != "" && bill != "" && cycle != "" ){

          $.ajax({
            url: '{{route('add-new-pe')}}',
            type: 'GET',
            dataType: 'html',
            data: {category: category,pay:pay,bill:bill,cycle:cycle,amount:amount,month_selected:month_selected},
          })
          .done(function(data) {

            if(data == 0){
              toastr.error("Name has exist aleady!");
            }else{
              oTable.draw();
              //sTable.draw();
              $("#pe_form")[0].reset();
              $(".add_pe").slideUp();
              toastr.success("Insert Success!");
            }
            
            // console.log(data);
          })
          .fail(function() {
            console.log("error");
          });
        }
      }
    });
    function isNumberKey(evt){
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (charCode > 31 && (charCode < 48 || charCode > 57))
          return false;
      return true;
    }  

}); 
</script>        
@stop

