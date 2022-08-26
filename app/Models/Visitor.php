<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model {
  
  protected $table = "data_tamu";

  protected $fillable = [
    'nama', 'alamat', 'user_id', 'updated_at', 'status'
  ];
  
  public $timestamps = false;
}