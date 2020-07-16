
<div class="container">

  <!-- The Modal BOOKING -->
  <div class="modal fade" id="bookingModal">
    <div class="modal-dialog modal-lg ">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Booking List</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class=" modal-body">
            <table id="booking-datatable" class="table table-striped table-bordered" style="width: 100%">
                <thead>
                  <tr>  
                    <th>Booking ID</th>
                    <th >Booking Time</th>
                    <th>Client Name</th>     
                    <th>Booking Status</th> 
                  </tr>
                </thead>
            </table>  
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

    <!-- The Modal -->
  <div class="modal fade" id="promotionsModal">
    <div class="modal-dialog modal-lg ">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Promotions</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class=" modal-body">
            <div class="container">
            <div class="promotion-list row">
            </div>
          </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  <!-- The Modal -->
  <div class="modal fade" id="selectClientModal">
    <div class="modal-dialog modal-lm">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Select Client</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
          <table id="client-datatable" class="table table-striped table-bordered" style="width: 100%">
              <thead>
                <tr>  
                  <th>Name</th>
                  <th class="text-right">Cellphone</th>
                  <th>Email</th>      
                </tr>
              </thead>
          </table>   
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>



  <!-- The Modal -->
  <div class="modal fade" id="addClientModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Client</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <form class="form-horizontal label-date" method="post" id="customer_form" action="{{route('save-customer')}}" name="frm" custom-submit="" novalidate="novalidate">
          @csrf
        <div class="modal-body">
          <div class="row form-group">
               <label class="col-xs-3 col-sm-3 col-md-2 offset-md-2">Full name</label>
               <div class="col-xs-6 col-sm-6 col-md-6 no-padding">
                  <input type='text' id="customer_fullname" required name="customer_fullname" value="" class="form-control form-control-sm"/>
               </div>            
          </div>    
          <div class="row form-group">
              <label class="col-xs-3 col-sm-3 col-md-2 offset-md-2">Cellphone</label>
              <div class="col-xs-6 col-sm-6 col-md-6 no-padding input-group-country-phone">
                  <div class="btn-group btn-group-sm btn-countrycode">
                   <button  id="current_country_selected" data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle" type="button" aria-expanded="false"><span class="caret">{{Session::get('selected_country_code')}}</span></button>
                   <ul role="menu" id="dropdown_country" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 31px, 0px);">
                       <li value ="1"><a value ="1" href="#">1</a></li>
                       <li value ="84" ><a value ="84" href="#">84</a></li>
                       <li value ="61"><a value ="61" href="#">61</a></li>                        
                   </ul>
                   <input type="hidden" name="country_code" id="country_code" value="">
                   </div>                   
                   <input class="form-control form-control-sm maskphone" required placeholder="Phone Number" id="customer_phone" name="customer_phone" value="" type="number" data-inputmask="'mask' : '(999) 999-9999'">                
              </div>
          </div>    
          <div class="row form-group">
               <label class="col-xs-3 col-sm-3 col-md-2 offset-md-2">Email</label>
               <div class="col-xs-6 col-sm-6 col-md-6 no-padding">
                  <input type="email" id="customer_email" required value="" name="customer_email" class="form-control form-control-sm"/>
               </div>            
          </div>    
          <div class="row form-group">
               <label class="col-xs-3 col-sm-3 col-md-2 offset-md-2">Date of Birth</label>
               <div class="col-xs-6 col-sm-6 col-md-6 no-padding">
                   <div class="input-group input-group-sm form-inline">
                      <input type='text' value="" id="customer_dateofbirth" name="customer_dateofbirth" class="form-control form-control-sm dateofbirth" data-format="DD-MM-YYYY" data-template="D MMM YYYY"/>
                  </div>
               </div>            
          </div>    
          <div class="row form-group">
              <label class="col-xs-3 col-sm-3 col-md-2 offset-md-2">Gender</label>
              <div class="col-xs-6 col-sm-6 col-md-6  form-group form-inline">

                    <div class="radio">
                      <label>
                        <input type="radio" class="flat checkGender" value="2" id="check2" checked name="gender">&nbsp;Female
                      </label>
                    </div>
                  <div class="radio" style="margin-left:10px;">
                      <label>
                        <input type="radio" class="flat checkGender" value="1" id="check1" name="gender">&nbsp;Male
                      </label>
                  </div>
              </div>            
          </div> 
          <div class="row form-group">
               <label class="col-xs-3 col-sm-3 col-md-2 offset-md-2">Address</label>
               <div class="col-xs-6 col-sm-6 col-md-6 no-padding">
                  <input type='text' required="required" value="" id="customer_address" name="customer_address" class="form-control form-control-sm"/>
               </div>            
          </div> 
          <div class="row form-group">
             <label class="col-xs-3 col-sm-3 col-md-2 offset-md-2">Group</label>
             <div class="col-xs-4 col-sm-4 col-md-4 no-padding">
                 <select id="customertag_id" required="required" name="customertag_id" class="form-control form-control-sm{{ $errors->has('customertag_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Client Group -- </option>
                    @foreach($list_customertag as $customertag)
                      <option 
                      @if(isset($customer_item->customer_customertag_id))
                        @if($customer_item->customer_customertag_id == $customertag->customertag_id) 
                            selected 
                        @endif
                      @endif
                    value ="{{$customertag->customertag_id}}">{{$customertag->customertag_name}}
                     </option>
                   @endforeach
                  </select>
             </div>            
          </div>    
        </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" name="submit-add-client" id="submit-add-client" class="btn btn-primary">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
</div>