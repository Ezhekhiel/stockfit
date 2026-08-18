<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chemical___waste extends Model
{
    public $timestamps = true;
    protected $table = "chemical___wastes";
    protected $guarded  = [];

    public function chemical()
    {
        return $this->belongsTo(
            chemical___database::class,
            'id_chemical',
            'id'
        );
    }

}
