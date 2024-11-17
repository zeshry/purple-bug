<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseCategory extends Model
{
    use HasFactory;

    // protected $table = 'expense_categories';
    protected $fillable = [
        'expense_name',
        'description',
    ];
}
