<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function privacy()
    {
        return view('legal.privacy');
    }

    public function terms()
    {
        return view('legal.terms');
    }

    public function faq()
    {
        return view('support.faq');
    }

    public function manual()
    {
        return view('support.manual');
    }
}
