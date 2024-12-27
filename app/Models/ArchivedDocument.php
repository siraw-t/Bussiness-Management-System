<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedDocument extends Model
{
    protected $table = 'archived_documents'; // Define the table name
    protected $primaryKey = 'document_id'; // Define the primary key
    public $incrementing = true; // This should be true for auto-incrementing IDs
    protected $keyType = 'int'; // Assuming you're using integers for IDs

    protected $fillable = [
        'document_name', 'document_type', 'document_owner', 'description', 'company_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'document_owner', 'user_id'); // Relate to the document owner
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id'); // Relate to the Company model
    }
}