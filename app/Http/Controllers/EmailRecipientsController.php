<?php


namespace App\Http\Controllers;


use App\Http\Requests\EmailRecipients\EmailRecipientsFormRequest;
use App\Models\EmailRecipients;
use App\Models\PPURespCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmailRecipientsController extends Controller
{
    public function index(Request $request){
        if($request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.email_recipients.index');
    }

    private function dataTable(Request $request){
        $rc = PPURespCodes::query()
            ->with(['emailRecipients']);
        return \DataTables::of($rc)
            ->addColumn('action',function($data){
               return view('ppu.email_recipients.dtActions')->with([
                   'data' => $data,
               ]);
            })
            ->addColumn('email_addresses',function($data){
                return view('ppu.email_recipients.emailRecipients')->with([
                    'data' => $data,
                ]);
            })
            ->escapeColumns([])
            ->setRowId('rc_code')
            ->toJson();
    }

    public function edit($rcCode){
        $emails = EmailRecipients::query()
            ->where('rc_code','=',$rcCode)
            ->get();
        $rc = PPURespCodes::query()
            ->where('rc_code','=',$rcCode)
            ->first();
        return view('ppu.email_recipients.edit')->with([
            'emails' => $emails,
            'rc' => $rc,
        ]);
    }

    public function update(EmailRecipientsFormRequest $request,$rcCode){
        if(!empty($request->email)){
            $emails  = collect($request->toArray()['email']);
            $emails = $emails->map(function ($data) use ($rcCode){
                return [
                    'slug' => Str::random(),
                    'rc_code' => $rcCode,
                    'email_address' => $data,
                    'user_created' => Auth::user()->user_id,
                    'ip_created' => \request()->ip(),
                    'created_at' => Carbon::now(),
                ];
            });
            $existingEmails = EmailRecipients::query()
                ->where('rc_code','=',$rcCode)
                ->delete();

            if(EmailRecipients::insert($emails->toArray())){
                return [
                    'slug' => $rcCode,
                ];
            }
        }else{
            $existingEmails = EmailRecipients::query()
                ->where('rc_code','=',$rcCode)
                ->delete();
            return [
                'slug' => $rcCode,
            ];
        }


        abort(503,'Error saving email address.');
    }
}