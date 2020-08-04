@extends('voyager::master')
@section('head')

@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="well">
        <form action="{{route('confirm_otp')}}" method="post">
            @csrf
        <input type="hidden" name="phone" value="{{$phone}}">
            <div class="form-group">
                <label for="confirm_code">
                    Enter Confirmation Code Sent to the Respondents primary cell phone
                </label>
                <input type="number" name="confirm_code"   id="form_control" class="form-control">
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Confirm Phone code</button>
            </div>
            <div class="form-group">
                <label for="resend"><div> <span id="timer"></span> Remaining to resend code</div></label>
                <button type="button" id="resendbtn" class="btn btn-dark" disabled onclick="resend()">Resend</button>
            </div>


        </form>
        </div>

    </div>
</div>
<script src="https://unpkg.com/axios@0.2.1/dist/axios.min.js"></script>
<script>
    let timerOn = true;

let resendbtn = document.getElementById("resendbtn");



function timer(remaining) {
  var m = Math.floor(remaining / 60);
  var s = remaining % 60;

  m = m < 10 ? '0' + m : m;
  s = s < 10 ? '0' + s : s;
  document.getElementById('timer').innerHTML = m + ':' + s;
  remaining -= 1;

  if(remaining >= 0 && timerOn) {
    setTimeout(function() {
        timer(remaining);
    }, 1000);
    return;
  }

  if(!timerOn) {
    // Do validate stuff here
    return;
  }

  // Do timeout stuff here
//   alert('Timeout for otp');
  resendbtn.removeAttribute("disabled");
}

timer(30);


function resend(){
    let url = "{{route('resend_otp')}}";
    let phone = "{{$phone}}";
    // alert("resending");

    axios.post(url, {phone:phone}).then(result=>{
        console.log(result)
        if (
            result === 'success'
        ) {
            toastr['success']("successfully resent the code, please check the phone number if you don't receive this code")
        }
    }).catch(error=>{
        console.log(error)
    });
}
</script>

@endsection
