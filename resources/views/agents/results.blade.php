@extends('voyager::master')
@section('head')
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="https://unpkg.com/jquery"></script>
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/survey-manager/css/survey.css') }}" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/easy-autocomplete"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css" type="text/css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://unpkg.com/easy-autocomplete@1.3.5/dist/easy-autocomplete.css" />

    <link rel="stylesheet" href="{{asset('css/app.css')}}">


    <script src="https://unpkg.com/emotion-ratings@2.0.1/dist/emotion-ratings.js"></script>
@endsection
@section('content')

<div class="container">
    <div class="row">
        <div class="col col-md-12">
            <div class="well">

            <h4>This interview was conducted by : </h4><span>{{ $agent->name}}</span>
        <h4>On:</h4> <span>{{ $interview->created_at}}</span>
            <h4>The date of consumption was: </h4> <span>{{$interview->date}}</span>

        </div>
        </div>



    </div>
    <div class="row">
        <div class="well">
            Below are the answers you filled
        </div>
    </div>
    <hr><hr>
    <div id="results">
    <div class="row">

        <survey-result></survey-result>

    </div>
    <hr><hr>
    </div>

</div>


<script>
        window.SurveyConfig = {!!json_encode(config('survey-manager')) !!};

    </script>

@endsection
@section('javascript')

<script src="{{asset('js/app.js')}}"></script>
@endsection
