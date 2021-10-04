<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'workspace_id'
    ];

    public function workspace(){
        return $this->belongsTo(Workspace::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}
