<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContactCustomValue;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    public function contactCustomValues() {
        return $this->hasMany(ContactCustomValue::class);
    }

    public function mergedInto() {
        return $this->belongsTo(Contact::class, 'merged_into');
    }

    public function mergedContacts() {
        return $this->hasMany(Contact::class, 'merged_into');
    }
}
