<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-light  bg-dark">
            <a class="navbar-brand text-white">Home</a>
            <form class="form-inline" action="{{ url("/user-logout") }}" method="POST"> @csrf
                <button  class="btn btn-outline-white my-2 my-sm-0"
                    type="submit" id="logoutButton">Logout</button>
            </form>
        </nav>
    </div>
    <div class="container-fluid">
        <span>Welcome {{Session::get('uname')}}</span>
       
        <div class="row mt-5">

           
            <div class="col-md-8">
                <div class="mt-5 mb-5">
                    <select name="searchdata" id="searchdata">
                        <option value="">Please Select</option>
                        {{-- <option value="updated_date">Update Date</option> --}}
                        <option value="by_descending_year">Descending Year</option>
                    </select>
                    <button class="btn btn-success btn-sm" id ="search">Search</button>
                </div>
                <table id="vahicle_name" class="display" style="width:100%" border="1px solid gray">
                    <thead>
                        <tr>
                            <th>SR No</th>
                            <th>Vehicle Name</th>
                            <th>Vehicle Type</th>
                            <th>Year of Manufacture</th>
                            <th>Date of Purchase</th>
                            <th>Created_at (timestamp)</th>
                            <th>Updated_at (timestamp)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="vehicleBody">
                        @foreach ($vehicles as $da=>$vehicleRow)
                        <tr>
                            <td>{{$da+1}}</td>
                            <td>
                                @if($vehicleRow->vehicle_type ==1)
                                {{"Car"}}
                                @elseif($vehicleRow->vehicle_type ==2)
                                {{"Bike"}}
                                @elseif($vehicleRow->vehicle_type ==3)
                                {{"Bus"}}
                                @endif
                            </td>
                            <td>{{$vehicleRow->vehicle_type}}</td>
                            <td>{{(date('d-m-Y',strtotime($vehicleRow->yom)))}}</td>
                            <td>{{(date('d-m-Y',strtotime($vehicleRow->dop)))}}</td>
                            <td>{{$vehicleRow->created_at}}</td>
                            <td>{{$vehicleRow->updated_at}}</td>
                            <td>
                                <button class=" btn btn-primary editVehicle mb-2" id="editVehicle" value="{{$vehicleRow->id}}" data-vehicle_type="{{$vehicleRow->vehicle_type}}" data-yom="{{$vehicleRow->yom}}" data-dop="{{$vehicleRow->dop}}">Edit</button>

                                <button class=" btn btn-danger deleteVehicle" id="deleteVehicle" value="{{$vehicleRow->id}}">Delete</button>
                            </td>
                        </tr>   
                        @endforeach
                       
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <form action="add-vehicle" id="addVehicle" method="POST"> @csrf
                    <div class="form-group">
                        <input type="hidden" value="" name="vehicleId" id="vehicleId">
                        <label for="exampleInputEmail1">Vehicle Type</label>
                        <select class="custom-select" id="vehicle_type" name="vehicle_type">
                            <option value="">Choose...</option>
                            <option value="1">Car</option>
                            <option value="2">Bike</option>
                            <option value="3">Bus</option>
                        </select>
                        <span id="vehicle_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">year of Manufacture</label>
                        <input type="date" name="yom" class="form-control" id="yom" placeholder="yom">
                        <span id="yom_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Date of Purchase</label>
                        <input type="date" name="dop" class="form-control" id="dop" placeholder="dop">
                        <span id="dop_error" class="text-danger"></span>
                    </div>

                    <button type="button" id="vehicleAdd" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#vahicle_name').DataTable();
        });

        $('body').on('click', '#vehicleAdd', function() {
            var registerForm = $("#addVehicle");
            var formData = registerForm.serialize();

            $('#vehicle_error').html("");
            $('#yom_error').html("");
            $('#dop_error').html("");

            $.ajax({
                url: '{{ url('/add-vehicle') }}',
                type: 'POST',
                data: formData,
                success: function(data) {
                    console.log(data);
                    //alert(data);
                    if (data.errors) {
                        if (data.errors.vehicle_type) {
                            $('#vehicle_error').html(data.errors.vehicle_type[0]);
                        }
                        if (data.errors.yom) {
                            $('#yom_error').html(data.errors.yom);
                        }
                        if (data.errors.dop) {
                            $('#dop_error').html(data.errors.dop);
                        }

                    }
                    if (data.success) {
                       
                           
                      
                         $('#vehicleBody').html(data.totalvahicle);
                        $('#addVehicle')[0].reset();
                    }
                },
            });
        });
        //delete data 
        $('body').on('click', '.deleteVehicle', function(){
       
            var vehicleid = $(this).val();
            $.ajax({
                url: '{{ url("/delete-vehicle") }}/' +vehicleid,
                type: 'POST',
                data: {vehicleid,"_token": "{{ csrf_token() }}"},
                success: function(data)
                 {
                    
                    if (data.success)
                     {
                         $('#vehicleBody').html(data.totalvahicle);
                    }
                },
            });
        });
        
        // edit data 
    $('body').on('click', '.editVehicle', function(){
       
       var vehicleid = $(this).val();
       var yom = $(this).data('yom');
       var dop = $(this).data('dop');
       var vehicle_type = $(this).data('vehicle_type');
       $("#vehicleId").val(vehicleid);
       $("#vehicle_type").val(vehicle_type);
       $("#yom").val(yom);
       $("#dop").val(dop);
       

      
   });

   $('body').on('click', '#search', function(){
       
       var searchdata = $("#searchdata").val();
       if(searchdata==null || searchdata=="")
       {
            alert('Please select filter');
            return false;
       }
       $.ajax({
           url: '{{ url("/search-data") }}/',
           type: 'POST',
           data: {searchdata,"_token": "{{ csrf_token() }}"},
           success: function(data)
            {
               
               if (data.success)
                {
                    $('#vehicleBody').html(data.totalvahicle);
               }
           },
       });
   });
      
    </script>
</body>

</html>
