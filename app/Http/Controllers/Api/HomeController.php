<?php

namespace PockDoc\Http\Controllers\Api;

use Illuminate\Http\Request;
use PockDoc\Http\Controllers\Controller;
use PockDoc\Models\Faq;

class HomeController extends Controller
{

    public function faq() {
        return Faq::query()
            ->orderBy('question')
            ->get();
    }

}
