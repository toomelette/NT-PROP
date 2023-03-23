<?php


namespace App\Http\Controllers;


class CancellationRequestController extends Controller
{
    public function create(){
        return view('ppu.cancellation_request.create');
    }
}