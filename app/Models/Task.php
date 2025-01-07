<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Task extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images'); // Colección para tus imágenes
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb') // Conversión para imágenes
            ->width(130)
            ->height(130)
            ->format('jpg')
            ->nonQueued(); // Opcional: forzar que las conversiones se ejecuten de forma síncrona en desarrollo.
    }
}
