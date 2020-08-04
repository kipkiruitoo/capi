<?php

namespace App\Http\Controllers;

use AfricasTalking\SDK\AfricasTalking;

use Illuminate\Support\Facades\Validator;
use App\PhoneNumber;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric',
        ], [



            'phone.required' => ' The phone number field is required.',
            'phone.numeric' => ' The phone number should not contain letters.',
            // 'phone.between' => ' The phone number entered should not be longer than 14  characters and not shorter than 9 characters.',
            // 'phone.min' => ' The phone number entered should not be less than 10 characters.',

        ]);
        $validator->validate();

        // dd($request->phone);
        if ($this->sendOtp($request->phone) == true) {
            $phone = $request->phone;
            session()->flash("message", "An Sms Verification Code has been sent to the Mobile Phone ");
            session()->flash('alert-type', "success");
            return view('recruitment.confirm', compact('phone'))->with(['message' => "An Sms Verification Code has been sent to the Mobile Phone ", 'alert-type' => 'success']);
        } else {
            session()->flash("message", "Please check the phone number and try again");
            session()->flash('alert-type', "error");
            return redirect()->back()->with(['message' => 'Please check the phone number and try again', 'alert-type' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function sendOtp($phone)
    {
        $otp = rand(100000, 999999);

        // store otp for confimation later
        $phonenumber =  new PhoneNumber();
        $phonenumber->phone = $phone;
        $phonenumber->user_id =  Auth::user()->id;
        $phonenumber->otp = $otp;

        if ($phonenumber->save()) {
            // Set your app credentials
            $username   = "tifasms";
            $apiKey     = "dd89d44a8ef0f1d1627180f8316a4661b3a84ebd931789d3270a86887de5b429";


            // Initialize the SDK
            $AT         = new AfricasTalking($username, $apiKey);

            // Get the SMS service
            $sms        = $AT->sms();

            // Set the numbers you want to send to in international format
            $recipients = "+254" . $phone;

            // Set your message
            $message    = "Your Mobile Verification code is: " . $otp;

            // Set your shortCode or senderId
            $from       = "20384";
            $keyword = "Tifa";
            try {
                // Thats it, hit send and we'll take care of the rest
                $result = $sms->send([
                    'to'      => $recipients,
                    'message' => $message,
                    'from'    => $from,
                    'keyword' => $keyword
                ]);

                Log::info($result);

                return true;
                // print_r($result);
            } catch (\Exception $e) {
                // echo "Error: " . $e->getMessage();

                Log::error($e->getMessage());
                return false;
            }
        }
    }
    public function confirm_otp(Request $request)
    {
        $phone = PhoneNumber::where('phone', $request->phone)->get()->last();
        $otp = $request->confirm_code;
        if ($phone->otp == (string) $otp) {
            // # code...dd(
            // dd("confirmed");
            return redirect()->route('survey-manager.run', $phone);
        } else {
            $phone = $phone->phone;
            // dd($phone, $otp);
            session()->flash("message", "Verification code entered is wrong");
            session()->flash('alert-type', "error");
            return view('recruitment.confirm', compact('phone'))->with(['message' => "An Sms Verification Code has been sent to the Mobile Phone ", 'alert-type' => 'success']);
        }
    }

    public function resend_otp(Request $request)
    {
        // dd($request);
        $otp = PhoneNumber::where('phone', $request->phone)->get()->last()->otp;
        // dd($otp);
        $username   = "tifasms";
        $apiKey     = "dd89d44a8ef0f1d1627180f8316a4661b3a84ebd931789d3270a86887de5b429";


        // Initialize the SDK
        $AT         = new AfricasTalking($username, $apiKey);

        // Get the SMS service
        $sms        = $AT->sms();

        // Set the numbers you want to send to in international format
        $recipients = "+254" . $request->phone;

        // Set your message
        $message    = "Your Mobile Verification code is: " . $otp;

        // Set your shortCode or senderId
        $from       = "20384";
        $keyword = "Tifa";
        try {
            // Thats it, hit send and we'll take care of the rest
            $result = $sms->send([
                'to'      => $recipients,
                'message' => $message,
                'from'    => $from,
                'keyword' => $keyword
            ]);

            Log::info($result);

            return "success";
            // print_r($result);
        } catch (\Exception $e) {
            // echo "Error: " . $e->getMessage();

            Log::error($e->getMessage());
            return "error";
        }
    }
}
