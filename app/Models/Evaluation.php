<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;


    protected $fillable = [
        'rating',
        'review',
        'employee_id',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($query) use ($keyword) {
            $query->orWhereHas('employee', function ($query) use ($keyword) {
                $query->where('firstname', 'like', '%' . $keyword . '%')
                    ->orWhere('middlename', 'like', '%' . $keyword . '%')
                    ->orWhere('lastname', 'like', '%' . $keyword . '%')
                    ->orWhere('designation', 'like', '%' . $keyword . '%')
                    ->orWhereHas('department', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        });
    }
}
