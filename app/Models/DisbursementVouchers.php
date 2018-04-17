<?php

namespace App\Models;


use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;



class DisbursementVouchers extends Model{

    use Sortable;

	protected $table = 'disbursement_vouchers';

	protected $dates = ['created_at', 'updated_at', 'date', 'certified_by_sig_date', 'approved_by_sig_date'];

    public $sortable = ['doc_no', 'dv_no', 'payee', 'account_code', 'date'];
    
	public $timestamps = false;



	protected $fillable = [
		
        'slug',
        'user_id', 
        'doc_no', 
        'dv_no', 
        'date', 
        'project_id', 
        'fund_source', 
        'mode_of_payment', 
        'payee', 
        'address', 
        'tin',
        'bur_no', 
        'department_name', 
        'department_unit_name',
        'account_code',
        'explanation', 
        'amount',
        'certified_by',
        'certified_by_position',
        'certified_by_sig_date',
        'approved_by',
        'approved_by_position',
        'approved_by_sig_date',
        'created_at',
        'updated_at',
        'machine_created',
        'machine_updated',
        'ip_created',
        'ip_updated',

    ];



    protected $attributes = [

        'slug' => '',
        'user_id' => '', 
        'doc_no' => '', 
        'dv_no' => '', 
        'date' => null, 
        'project_id' => '', 
        'fund_source' => '', 
        'mode_of_payment' => '', 
        'payee' => '', 
        'address' => '', 
        'tin' => '',
        'bur_no' => '', 
        'department_name' => '', 
        'department_unit_name' => '',
        'account_code' => '',
        'explanation' => '', 
        'amount' => 0.00,
        'certified_by' => '',
        'certified_by_position' => '',
        'certified_by_sig_date' => null,
        'approved_by' => '',
        'approved_by_position' => '',
        'approved_by_sig_date' => null,
        'created_at' => null,
        'updated_at' => null,
        'machine_created' => '',
        'machine_updated' => '',
        'ip_created' => '',
        'ip_updated' => '',
        'user_created' => '',
        'user_updated' => '',

    ];



    //RELATIONSHIPS
    public function user(){
        return $this->hasOne('App\Models\User', 'user_id', 'user_id');
    }


    public function project(){
        return $this->hasOne('App\Models\Projects', 'project_id', 'project_id');
    }


    public function fundSource(){
        return $this->hasOne('App\Models\FundSource', 'fund_source_id', 'fund_source');
    }


    public function modeOfPayment(){
        return $this->hasOne('App\Models\ModeOfPayment', 'mode_of_payment_id', 'mode_of_payment');
    }



    // SCOPES
    public function scopeSearch($query, $key){

        $query->where(function ($query) use ($key) {
            $query->where('payee', 'LIKE', '%'. $key .'%')
                 ->orwhere('dv_no', 'LIKE', '%'. $key .'%')
                 ->orwhere('doc_no', 'LIKE', '%'. $key .'%')
                 ->orwhere('department_name', 'LIKE', '%'. $key .'%')
                 ->orwhere('department_unit_name', 'LIKE', '%'. $key .'%')
                 ->orwhere('account_code', 'LIKE', '%'. $key .'%')
                 ->orwhere('fund_source', 'LIKE', '%'. $key .'%')
                 ->orwhereHas('user', function ($query) use ($key) {
                    $query->where('firstname', 'LIKE', '%'. $key .'%')
                          ->orwhere('middlename', 'LIKE', '%'. $key .'%')
                          ->orwhere('lastname', 'LIKE', '%'. $key .'%')
                          ->orwhere('username', 'LIKE', '%'. $key .'%');
                });
        });

    }


    public function scopeFindSlug($query, $slug){

        return $query->where('slug', $slug)->firstOrFail();

    }

    
    public function scopePopulate($query){

        return $query->sortable()->paginate(10);

    }


    public function scopePopulateByUser($query, $id){

        return $query->where('user_id', $id)->sortable()->paginate(10);

    }


}
