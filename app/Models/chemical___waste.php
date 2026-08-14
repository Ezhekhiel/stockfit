<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chemical___waste extends Model
{
    public $timestamps = true;
    protected $table = "chemical___wastes";
    protected $guarded  = [];

    public function chemical() {
        return $this->hasOne(chemical___database::class,'code_chemical', 'code_chemical')
                ->whereColumn('chemical___databases.model','chemical___wastes.model');
    }

}
