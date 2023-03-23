<?php


namespace App\Http\Controllers;


use App\Models\Transactions;
use App\Swep\Services\JRService;
use App\Swep\Services\TransactionService;

class CancellationRequestController extends Controller
{
    public function create(){
        return view('ppu.cancellation_request.create');
    }

    public function findTransactionByRefNumber($refNumber, $refBook){
        $trans = Transactions::query()
            ->where('ref_book', '=', $refBook)
            ->where('ref_no', '=', $refNumber)
            ->first();
        $trans = $trans??null;
        return $trans?? abort(503,'No record found');
    }
}