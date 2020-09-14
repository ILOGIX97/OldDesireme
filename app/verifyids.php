<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class verifyids extends Model
{
   protected $tabels = 'verify_ids';

   protected $fillable = ['user_id','photo_id','holding_id','verified'];
   
}
