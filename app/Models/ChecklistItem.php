<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'is_completed', 'checklist_id'
    ];

    protected $casts = [
        'is_completed'=> 'boolean'
    ];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }
}
