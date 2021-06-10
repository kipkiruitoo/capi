@extends('voyager::master')
@section('head')
<script src="https://unpkg.com/jquery@3.3.1/dist/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
@endsection

@section('content')
@error('phone')

<script>
    toastr["error"]('Please check the phone number you entered');
</script>

@enderror
<div class="container">
    <div class="row">
        <h3>Welcome to the recruitment tool</h3>
    </div>
    @if ($errors->any())
		    <div class="alert alert-danger">
		    	<strong>Whoops!</strong> Please correct errors and try again!.
						<br/>
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif
    <div class="row">
        <div class="well">
            <h4>Introduce yourself to the respodent</h4>
            <hr>
            <p>
                Habari ya asubuhi/ mchana. Jina langu ni ___________. Nimetoka TIFA kampuni huru ya utafiti. Hivi sasa tunachanga watu
                 kama wewe kuwa miongoni mwa jopo ambayo tunajihusisha nao wakati moja hadi mwingine ili kupata maoni kuhusu maswala
                    yanayoathiri
                  jamii.Ukikubali kujiunga na jopo hili leo, utajumuishwa katika utafiti wetu wa kwanza ambayo utapewa Shilingi hamsini kama mjazo wa
                   simu. Kutakuwa na utafiti zingine mingi ambayo utazawadiwa kwa njia tofauti. Tafadhali kumbuka kuwa habari yote itawekwa kwa
                    siri. <br> <hr>
                Good morning/ afternoon.  My name is ____________.  I am from TIFA an independent research organization. We are currently recruiting people like you to be part of a panel that we engage time to time to get opinion on matters affecting society.\nIf you accept to join this panel today, you will be involved in our first survey in which you will be compensated Kshs. 50 in the form of airtime. There will
                 be many other studies in which you will be compensated differently. Please note all information will be kept confidential.
            </p>
        </div>
    <form action="{{route('confirm_phone')}}" method="post">
        @csrf
            <div class="form-group">
                <label for="consent">Would you like to be part of the panel? </label> <br>
                <label for="1">Yes </label>
                <input type="radio" name="consent" id="1" value="yes" id="yes" />
                <br>

                <label for="0">No</label>
                <input type="radio" checked name="consent" id="0" value="no" id="no" />
            </div>
            <div id="phone-group" class="form-group @error('phone') has-error @enderror">
                <label for="phone">What is your primary phone number?</label>
                <input class="form-control" placeholder=" 07xxxxxxxx" type="tel" value="{{ old('phone') }}" name="phone">
                @error('phone')
					<span class="text-danger">{{ $message }}</span>
				@enderror
            </div>
            <div class="form-group">
                <button id="continue" class="btn btn-primary"type="submit">Continue</button>
            </div>

        </form>


    </div>
    <div class="row" style="background-color: red; color:white; height: 40px; width:100%; text-align:center; padding-top: 10px;">Any Fraudelent behaviour will be penalized</div>
</div>
<script>
var radios = document.getElementsByName("consent");
var phone_group = document.getElementById("phone-group");

console.log(radios)
for(var i = 0; i < radios.length; i++) {
    if (radios[i].checked) {
        phone_group.style.display = 'none';
        document.getElementById("continue").setAttribute('disabled', true)
    }
   radios[i].onclick = function() {
     var val = this.value;
     if(val == 'yes' ){  // Assuming your value for radio buttons is radio1, radio2 and radio3.
        phone_group.style.display = 'block';
        document.getElementById("continue").removeAttribute('disabled')
        console.log(val) // show
        // phone_group.style.display = 'none';// hide
     }
     else if(val == 'no'){
          console.log(val)
         phone_group.style.display = 'none';
         document.getElementById("continue").setAttribute('disabled', true)
        //  internetpayment.style.display = 'block';
     }

  }
}
</script>
@endsection
