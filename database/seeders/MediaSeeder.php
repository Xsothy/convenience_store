<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaSeeder extends Seeder
{
    public function run()
    {
        $presetImages = [
            'https://images.unsplash.com/photo-1549490349-8643362247b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=500&q=80',
            'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=500&q=80',
            'https://images.unsplash.com/photo-1550745165-9bc0b252726f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&h=500&q=80',
        ];

        foreach ($presetImages as $imageUrl) {
            $tempPath = Storage::disk('local')->put('temp', file_get_contents($imageUrl));

            $media = new Media;
            $media->file_name = basename($tempPath);
            $media->collection_name = 'preset_images';
            $media->disk = 'public';
            $media->model_type = 'media';
            $media->model_id = 1;
            $media->name = 'Preset Image';
            $media->size = 0;
            $media->manipulations = '{}';
            $media->custom_properties = '{}';

            $media->save();
        }
    }
}
