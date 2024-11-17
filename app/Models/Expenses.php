<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $fillable = [
        'expense_type',
        'amount',
        'entry_date',
    ];}
