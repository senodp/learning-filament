<?php

namespace App\Models;

class Section extends BaseModel
{
    /**
     * Relationships
     */
    public function taxonomy()
    {
        return $this->belongsTo(SectionTaxonomy::class, 'section_taxonomy_id', 'id');
    }
}
