@extends('voyager::master')
@section('head')
<script src="https://unpkg.com/jquery@3.3.1/dist/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
@endsection

@section('content')

<div class="container">
    <div class="row">
        <h3>Projects assigned</h3>
    </div>

    <div class="row">

        @foreach($projects as $project)
         <div class="col col-md-12">
                  <div class="card">
    <div class="card-header">
    Project {{$project->name}}
  </div>
  <div class="card-body">
  <h5 class="card-title">Project started on {{$project->start_date }}</h5>
  <p class="card-text">Project ends on {{$project->end_date}}</p>
   <a href="{{ route('agentchoseproject', $project->project_id)}}"><button type="button"
                                class="btn btn-dark">Start Interview</button></a>
  </div>
</div>
        </div>

@endforeach

    </div>
</div>
<script>
 $(document).ready(function () {
        $('#projects').DataTable();
    });
</script>
@endsection
