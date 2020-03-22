<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $fillable = [
        'title',
        'description',
        'address',
        'complement',
        'number',
        'city',
        'salary',
        'state',
        'zipcode',
    ];
    protected $primaryKey = 'id';
    protected $table = 'vacancies';

    /**
     * Filter by title or description
     */
    public function scopeFilterByTitleOrDescription($q, $keyword = null)
    {
        if (!$keyword) {
            return $q;
        }

        return $q->where('title', 'like', '%' . $keyword . '%')->orWhere('description', 'like', '%' . $keyword . '%');
    }

    /**
     * Filter by open or closed vacancies
     */
    public function scopeFilterClosedVacancy($q, $status = null)
    {
        if (!$status) {
            return $q;
        }

        return $q->whereStatus('closed');
    }
}
