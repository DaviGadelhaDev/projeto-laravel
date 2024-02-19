<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusConta extends Model
{
    use HasFactory;
    protected $table = 'status_contas';
    protected $fillable = ['nome', 'cor'];

    //uma situação pode ter várias contas
    public function conta(){
        return $this->hasMany(Conta::class);
    }
}
