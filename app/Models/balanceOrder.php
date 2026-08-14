<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class balanceOrder extends Model
{
    public $timestamps = true;
    protected $table = "dc__balance_order";
    // protected $guarded  = [];
    protected $fillable = [
        'buymonth','cell','g','style','wide','po','xfd','qty','batch_date','sts','factory','style_1','sbu','code','customer_order_no','region','customer','market','current_customer_target_xfd','orig_req_xfd_crd_tt','callot_no','rta',
        'size_1','size_2','size_3','size_4','size_5','size_6','size_7','size_8','size_9','size_10','size_11','size_12','size_13','size_14','size_15','size_16','size_17','size_18','size_19','size_20','size_21','size_22','size_23','size_24','size_25','size_26','size_27','size_28','size_29',
    ];
}
