<table id="datatableReceiver" class="table table-striped table-bordered">
    <thead>
    <tr>        
        <th class="text-center">Phone</th>
        <th class="text-center">Name</th>   
        <th class="text-center">Birthday</th>        
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>{Name}</td>            
            <td>{Birthday}</td>
            <td>{Phone}</td>
        </tr>
    </tbody>
</table>
<script type="text/javascript">
$(document).ready(function() {
   $('#datatableReceiver').DataTable({
        dom: "lBfrtip",
        buttons: [],
  });   
}); 
</script> 