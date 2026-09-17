<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = ['key', 'name', 'description', 'subject', 'body', 'is_enabled'];

    protected $casts = ['is_enabled' => 'boolean'];

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Replace {{ token }} placeholders with the supplied values. Unknown
     * tokens are stripped so half-rendered braces never reach a recipient.
     */
    public function render(string $field, array $data): string
    {
        $text = (string) $this->{$field};

        foreach ($data as $key => $value) {
            $text = preg_replace('/\{\{\s*' . preg_quote($key, '/') . '\s*\}\}/', (string) $value, $text);
        }

        return preg_replace('/\{\{\s*[a-z0-9_]+\s*\}\}/i', '', $text);
    }
}
