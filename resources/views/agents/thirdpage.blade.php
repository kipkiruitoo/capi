@extends('voyager::master')
@section('head')
<script src="https://unpkg.com/jquery@3.3.1/dist/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .call{
        background: #293138;
    }
    .call-animation {
    background: #fff;
    width: 140px;
    height: 140px;
    position: relative;
    margin: 0 auto;
    border-radius: 100%;
    border: solid 5px #fff;
    animation: play 2s ease infinite;
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
    -ms-backface-visibility: hidden;
    backface-visibility: hidden;

}
 img {
        width: 130px;
        height: 130px;
        border-radius: 100%;
        position: absolute;
        left: 0px;
        top: 0px;
    }
@keyframes play {

    0% {
        transform: scale(1);
    }
    15% {
        box-shadow: 0 0 0 5px rgba(255, 255, 255, 0.4);
    }
    25% {
        box-shadow: 0 0 0 10px rgba(255, 255, 255, 0.4), 0 0 0 20px rgba(255, 255, 255, 0.2);
    }
    25% {
        box-shadow: 0 0 0 15px rgba(255, 255, 255, 0.4), 0 0 0 30px rgba(255, 255, 255, 0.2);
    }

}


</style>
@endsection
@section('content')

<div class="container">
    <div class="row">
        <button class="btn btn-dark" onclick="location.reload()"> <span><i class="fa fa-refresh" aria-hidden="true"></i></span> Get New Respondent</button>
    </div>
    <br>

    <div class="row">
        <div class="col-md-6">
<form action="{{route('survey-manager.run', $survey)}}" method="POST">
                <div class="form-group">

                    <div class="well">
                        <label for="respondent">Selected Respondent</label>
                        <h5 id="resname">{{$respondent[0]->name}}</h5>
                        <h6>County:<span id="rescounty"> {{$respondent[0]->county}}</span></h6>
                        <h5>Call the Phone Numbers in the order and select which one was picked before proceeding</h5>
                        <div class="form-group">
                        <label for="resphone">First Phone Number: </label>
                        <input id="resphone" type="radio" name="phonenumber" required value="{{$respondent[0]->phone}}"><span> {{$respondent[0]->phone}}</span>
                        </div>
                        @if ($respondent[0]->phone1)
                        <div class="form-group">
                        <label for="phone1">Second Phone Number: </label>
                        <input id="phone1" type="radio" name="phonenumber" required value="{{$respondent[0]->phone1}}"><span> {{$respondent[0]->phone1}}</span>
                        </div>
                        @endif
                        @if ($respondent[0]->phone2)
                        <div class="form-group">
                        <label for="phone1">Third Phone Number: </label>
                        <input id="phone1" type="radio" name="phonenumber" required value="{{$respondent[0]->phone2}}"><span> {{$respondent[0]->phone2}}</span>
                        </div>
                        @endif



                        @php
                        if (App\Interview::where('respondent', $respondent[0]->id)->latest()->first()) {
                        $lastinterviewdate = App\Interview::where('respondent',
                        $respondent[0]->id)->latest()->first()->date;
                        }else {
                        $lastinterviewdate = "Seems No interview yet";
                        }
                        @endphp
                        <h6>Last Interview : <span id="lin">{{ $lastinterviewdate }}</span></h6>
                        {{-- <button class="btn btn-dark btn-small">Manage Phone Numbers</button> --}}



                    </div>


                </div>


                @csrf
                <input type="hidden" name="respondent" class="respondent" value="{{$respondent[0]->id}}">
                <input type="hidden" name="callsession" required id="callsession"/>
                <button type="submit"  id="startsurvey" class="btn btn-dark"> Start
                    Survey  <span><i class="fa fa-play-circle"></i></span></button>
            </form>
        {{-- <form action="{{route('makecall')}}" method="POST"> --}}
        {{-- <input type="hidden" name="respondent" value="{{$respondent[0]->id}}"> --}}
            {{-- @csrf --}}
            @if ($respondent[0]->project != 51)
                 <button class="btn btn-dark" type="button" data-toggle="modal" data-target="#calling" onclick="make_call()" > <span><i class="fa fa-phone"> </i> </span>Make Call</button>
            @endif

            {{-- </form> --}}
        </div>
        <div class="col-md-6">
            <div class="well">
                <h5>Last Feedback:</h5>
                <button class="btn btn-dark btn-small" data-toggle="modal" data-target="#feedback"> <span><i class="fa fa-plus"></i></i></span> Add
                    Feedback</button>
                <div class="new-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedback as $feedback)
                            <tr>
                                <td>
                                    {{ $feedback->created_at}}
                                </td>
                                <td>
                                    {{ $feedback->feedback}}
                                </td>
                                <td>
                                    {{ $feedback->other }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
    <div class="row">


    </div>

</div>
<!-- feedback modal -->
<!-- Modal -->
<div class="modal fade" id="feedback" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Feedback for this Respondent:
                    {{$respondent[0]->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="feedbackform" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="fback">Choose Feedback</label>
                        <select class=" form-control" required name="feedback" id="fback">
                            @foreach ($feedbacks as $item)
                            <option value="{{$item->name}}">
                                {{$item->name}}
                            </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="project" value="{{ $project }}">
                    </div>
                    <div class="form-group">
                        <label for="other">Additional Feedback</label>
                        <textarea class="form-control" id="other" cols="10" rows="10" name="other"></textarea>
                    </div>
                    <input type="hidden" id="respondent" name="respondent" value="{{$respondent[0]->id}}">
                    <input type="hidden" name="survey" value={{$survey}}>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button onclick="addfeedback()" class="btn btn-dark"><span><i class="fa fa-save"></i> Save</span> </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Calling Card --}}
<div class="modal fade" id="calling" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  " role="document">
        <div class="modal-content modal-dialog-centered  call">
            <div class="modal-header">
                <h5 class="modal-title" id="callstatus">Initiating Call</h5>
            </div>
            <div class="modal-body">
                <div class="call-animation">
               <img class="img-circle" src="{{env('APP_URL') . '/calling.png'}}" alt="Icon" width="135">
            </div>
            </div>


        </div>
    </div>
</div>
{{-- Calling Card --}}
<script src="{{asset('js\app.js')}}"></script>
<script>
    window.onload = function () {
        var count = localStorage.getItem("count");

    }
    var timesRun = 0;
    interval = window.setInterval(function () {
        timesRun += 1;
        if (timesRun === 3) {
            clearInterval(interval);
        }
        refreshuser({{$respondent[0]->id}});
    }, 2500);

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.js"></script>
<script type="text/javascript">
    // For adding the token to axios header (add this only one time).
    var token = document.head.querySelector('meta[name="csrf-token"]');
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

    function addfeedback() {
        var feedback = document.getElementById("fback").value
        var respondent = document.getElementById("respondent").value
        var additionalfeedback = document.getElementById("other").value
        var feedbackform = document.getElementById("feedbackform")
        feedbackform.addEventListener("submit", function (event) {
            event.preventDefault();
        })
        console.log(feedback)

        // send contact form data.
        axios.post('/add-feedback', {
            feedback: feedback,
            respondent: respondent,
            other: additionalfeedback
        }).then((response) => {
            console.log(other)
            $('.modal').modal('hide');
            var timesRun = 0;
            interval = window.setInterval(function () {
                timesRun += 1;
                if (timesRun === 3) {
                    clearInterval(interval);
                }
                refreshuser({{$respondent[0]->id}});
            }, 1000);
        }).catch((error) => {
            console.log(error.response.data)
        });
    }

</script>
<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
        $('#fbacktb').dataTable();
        // getnewuser({{ $project }}, {{$survey}})
    });


    function getnewuser(project, survey) {
        var feedback;
        var tbhtml = document.querySelector('.new-table');
        var thead;
        var tbody;
        var tlast;
        axios.get('/get-user/' + project + '/' + survey).then((response) => {
            console.log(response)
            document.querySelector('#resname').innerHTML = response.data[0][0].name;
            document.querySelector('#resphone').innerHTML = response.data[0][0].phone;
            document.querySelector('#rescounty').innerHTML = response.data[0][0].county;
            document.querySelector(".respondent").value = response.data[0][0].id;
            document.querySelector("#respondent").value = response.data[0][0].id;
            if (response.data[2][0] === undefined || response.data[2][0].length < 1) {
                document.querySelector("#lin").innerHTML = "Seems No interview yet";

            } else {
                document.querySelector("#lin").innerHTML = response.data[2][0].created_at
            }


            feedback = response.data[1];




            thead =
                "<table id='fbacktb' class='table'><thead><tr><th>Date</th>  <th>Type</th><th>Comment</th></tr></thead><tbody>";

            feedback.forEach(element => {
                // console.log(typeof element.other)
                // if (element.other ) {

                // }
                date = new Date(element.created_at)
                tbody += "<tr><td>" + date.toDateString() + "</td><td>" + element.feedback + "</td>" +
                    element.other + "</tr>";
            });


            tlast = "</tbody></table>";

            tbhtml.innerHTML = thead + tbody + tlast;


            console.log(feedback);


            // $('#fbacktb').DataTable({
            //     "data": feedback,
            //     columns: [
            //         {"data": "feedback"},
            //         {"data": "created_at"}
            //     ]
            // });
        }).catch((error) => {
            console.log(error)
        });
    }

    function refreshuser(id) {
        var feedback;
        var tbhtml = document.querySelector('.new-table');
        axios.get('/refresh-user/' + id).then((response) => {
            console.log(response);
            document.querySelector('#resname').innerHTML = response.data[0].name;
            document.querySelector('#resphone').innerHTML = response.data[0].phone;
            document.querySelector('#rescounty').innerHTML = ' ' + response.data[0].county;
            document.querySelector(".respondent").value = response.data[0].id;
            document.querySelector("#respondent").value = response.data[0].id;
            if (response.data[2][0] === undefined || response.data[2][0].length < 1) {
                document.querySelector("#lin").innerHTML = "Seems No interview yet";

            } else {
                let date = new Date(response.data[2][0].created_at)
                document.querySelector("#lin").innerHTML = date.toDateString()
            }


            feedback = response.data[1];

            let tbody = '';


            let thead =
                "<table id='fbacktb' class='table'><thead><tr><th>Date</th>  <th>Type</th><th>Comment</th></tr></thead><tbody>";

            feedback.forEach(element => {
                console.log( typeof element.other)
                date = new Date(element.created_at)
                tbody += "<tr><td>" + date.toDateString() + "</td><td>" + element.feedback + "</td>" +
                    "<td>" + element.other + "</td>" + "</tr>";
            });


            let tlast = "</tbody></table>";

            tbhtml.innerHTML = thead + tbody + tlast;


            console.log(feedback);
        }).catch((error) => {
            console.log(error)
        });
    }

    function make_call() {
        // console.log("i have been called");
            var respondent = {{$respondent[0]->id}};
            // console.log(respondent);
            var sessionInput = document.querySelector("#callsession");
            let hellobar = document.querySelector('#hellobar-bar');
            var url = '{{route('makecall')}}';
            hellobar.style.display = "table";
            var barmessage = document.querySelector('#barmessage');
            var startsurvey = document.querySelector("#startsurvey");

        axios.post(url, {respondent: respondent}).then(response=>{
            console.log(response);
            let sessionId = response.data.sessionId;
            console.log(sessionId);
            sessionInput.value = sessionId;
            startsurvey.disabled = false;
            Echo.channel('call_status' + sessionId).listen('ChangeCallStatus', (e)=>{
                // alert(e)
                let statusmessage = document.querySelector("#callstatus");
                let animationdiv = document.querySelector(".call-animation");
                console.log(e)
                toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-bottom-full-width",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};
                // statusmessage.innerHTML = e.callstatus
                if(e.callstatus === "NotAnswered"){
                    statusmessage.style.cssText += 'color: red;';
                    animationdiv.style.background = "red";
                    hellobar.style.background = "#55040F";
                    toastr["error"]("Phone Was not Answered . Please try again")
                    barmessage.innerHTML = "Phone not Answered . Please try again";
                     animationdiv.style.border = "solid 5px red";
                    statusmessage.innerHTML = "Phone not Answered"
                }else if(e.callstatus === "Dialing"){
                    statusmessage.style.cssText += 'color: yellow;';
                    animationdiv.style.border = "solid 5px #57a9c1";
                    hellobar.style.background = "#61045A";
                    toastr["info"]("Dialing the Respondent");
                    barmessage.innerHTML = "Dialing the Respondent";
                    animationdiv.style.background = "#57a9c1";
                    statusmessage.innerHTML = "Dialing the Respondent"
                }else if (e.callstatus === "Bridged") {
                    statusmessage.style.cssText += 'color: green;';
                    animationdiv.style.border = "solid 5px green";
                    toastr["success"]("Connected with  the Respondent");
                   hellobar.style.background = "#030B37";
                    barmessage.innerHTML = "Connected with the Respondent";
                    animationdiv.style.background = "green";
                    statusmessage.innerHTML = "Connected with the Respondent"
                }else if (e.callstatus === "Completed") {
                    statusmessage.style.cssText += 'color: white;';
                    animationdiv.style.border = "solid 5px green";
                     hellobar.style.background = "#033018";
                    toastr["success"]("Phone Call Completed");
                    barmessage.innerHTML = "Call Completed";
                    hellobar.style.display = "None";
                    animationdiv.style.background = "green";
                    statusmessage.innerHTML = "Connected with the Respondent"
                }
            })
        })
    }

</script>
@endsection
