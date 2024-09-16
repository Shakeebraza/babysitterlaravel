<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Request $request){
        $ipAddress = $request->ip();
        $location = GeoIP::getLocation($ipAddress);
        $country = $location['country'];
        if (in_array($country, ['Germany', 'Switzerland', 'Austria'])) {
            $locale='de';
        } else {
            $locale='en';
        }
        app()->setLocale($locale);
        return redirect('/' . $locale . '/home');
    }

    public function home(Request $request){
        $locale = $request->segment(1); // 'de' or 'en'
        if (in_array($locale, ['de', 'en'])) {
            app()->setLocale($locale);
        }
        return view('home');
    }

    public function my_applies(){
        return view('my_applies');
    }

    public function sendContactUs(Request $request){
        $data = $request->all();
        $data['language'] = app()->getLocale();
        $returnCode = $this->sendMail($data, trans('messages.new_inquiry_subject'),'contact_us_form_template', true);
        return $returnCode;
    }

    public function showPrivacyPolicy(Request $request){
        $locale = $request->segment(1); // 'de' or 'en'
        if (in_array($locale, ['de', 'en'])) {
            app()->setLocale($locale);
        } else {
            app()->setLocale('en');
        }
        return view('privacy_policy');
    }

    public function showGtc(Request $request){
        $locale = $request->segment(1); // 'de' or 'en'
        if (in_array($locale, ['de', 'en'])) {
            app()->setLocale($locale);
        } else {
            app()->setLocale('en');
        }
        return view('gtc');
    }
}
