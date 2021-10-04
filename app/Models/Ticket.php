<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable=[
        'title',
        'description',
        'color',
        'label_id'
    ];

    public function label(){
        return $this->belongsTo(Label::class);
    }
}
