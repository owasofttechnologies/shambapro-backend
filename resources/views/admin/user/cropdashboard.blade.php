@extends('brackets/admin-ui::admin.layout.default')

<title>Dashboard</title> 

<!-- App css -->
   <!--  <link href="https://shambapro.lynked.com.ng/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://shambapro.lynked.com.ng/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="https://shambapro.lynked.com.ng/assets/css/app.min.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">

@section('body')

                   


                    

<div class="card">

<div class="card-header">
    <h3 class="card-title">Crop Enterprises</h3>
</div>
<div class="card-body"> 
    <table id="example4" class="table table-bordered table-striped">
      <thead>
      </thead>
      <tbody>
        <tr>
          <th style="text-align: left;">#</th>
          <th style="text-align: left;">Enterprise Name</th>
          <th style="text-align: left;">Farm Name</th>
          <th style="text-align: left;">Created At</th>
        </tr>
        @if(isset($cropenterprise))
        @foreach($cropenterprise as $k => $ce)
        <tr>
          <td style="color: green;text-align: left;"> {{ $k+1 }} </td>
          <td style="color: green;text-align: left;"><a href="{{ url('admin/users/crop/detail/'.$ce->id) }}" style="color: green;"> {{$ce->enterprise_name}}</a></td>
          <td style="color: green;text-align: left;"><a href="{{ url('admin/users/crop/detail/'.$ce->id) }}" style="color: green;">{{$ce->farm_name}}</a></td>
          <td style="color: green;text-align: left;"><a href="{{ url('admin/users/crop/detail/'.$ce->id) }}" style="color: green;">{{ date('j F Y', strtotime($ce->created_at)) }}</a></td>
        </tr>
        <?php $k++;?>
        @endforeach
        @else
        <tr>
          <td>No data found</td>
        </tr>
        @endif
        
        
      </tbody>
    </table>
</div>            
</div>



@endsection