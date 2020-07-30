@extends('voyager::master')
@section('head')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" type="text/css" rel="stylesheet" />
{{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> --}}
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js" type="text/javascript"></script>
<script src="https://unpkg.com/easy-autocomplete"></script>
        <link rel="stylesheet" href="https://unpkg.com/easy-autocomplete@1.3.5/dist/easy-autocomplete.css"/>
          <script src="https://unpkg.com/emotion-ratings@2.0.1/dist/emotion-ratings.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

@stop

@section('content')
@php
// $respondent = json_decode($respondent)
@endphp

    <div class="container">
      <div class="row">
      {{-- <button  onclick="window.history.back()" class="btn btn-dark"> <span><i class="fa fa-chevron-left"></i> Go Back</span> </button> --}}
      </div>
        {{-- <div class="row">
            <h4>You Are interviewing:</h4>
            <div class="well">
                Name: <p>{{$respondent[0]->name}}</p>
                Phone Number: <p>{{$selectedphone}}</p>
                County: <p>{{$respondent[0]->county}}</p>
            </div>
        </div> --}}


        <div class="row">
                    <div class="panel-body" id="surveyElement">
                    <survey-show :selectedphone = "{{$selectedphone}}":survey-data="{{ json_encode($survey) }}" ></survey-show>
                    </div>

            </div>
        </div>
    <script src="{{asset('js/app.js')}}"></script>
    <script> window.SurveyConfig = {!! json_encode(config('survey-manager')) !!};</script>

    <script src="{{ asset('vendor/survey-manager/js/survey-front.js') }}"></script>

    <script>
        // var primaryphone = document.getElementById("sq_108i");
        //     primaryphone.addEventListener("change", function check(e) {
        //     console.log(e);
        //     alert(e);
        // });
    </script>
@endsection
