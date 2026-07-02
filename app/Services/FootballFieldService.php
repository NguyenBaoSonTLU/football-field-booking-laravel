<?php

namespace App\Services;

use App\Enums\FootballFieldStatus;
use App\Models\FootballField;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FootballFieldService
{
    public function __construct(private readonly ImageUploadService $imageUploadService)
    {
    }

    public function create(array $data): FootballField
    {
        return DB::transaction(function () use ($data): FootballField {
            $images = Arr::pull($data, 'images', []);
            $field = FootballField::query()->create($data);
            $this->appendImages($field, $images, true);

            return $field->fresh('images');
        });
    }

    public function update(FootballField $field, array $data): FootballField
    {
        return DB::transaction(function () use ($field, $data): FootballField {
            $images = Arr::pull($data, 'images', []);
            $removeIds = Arr::pull($data, 'remove_image_ids', []);
            $mainImageId = Arr::pull($data, 'main_image_id');

            $field->update($data);

            foreach ($field->images()->whereIn('id', $removeIds)->get() as $image) {
                $this->imageUploadService->delete($image->image_url);
                $image->delete();
            }

            $this->appendImages($field, $images, ! $field->images()->exists());

            if ($mainImageId && $field->images()->whereKey($mainImageId)->exists()) {
                $field->images()->update(['is_main' => false]);
                $field->images()->whereKey($mainImageId)->update(['is_main' => true]);
            }

            if (! $field->images()->where('is_main', true)->exists()) {
                $field->images()->oldest('id')->first()?->update(['is_main' => true]);
            }

            return $field->fresh('images');
        });
    }

    public function deleteOrDeactivate(FootballField $field): bool
    {
        return DB::transaction(function () use ($field): bool {
            if ($field->bookings()->exists()) {
                $field->update(['status' => FootballFieldStatus::INACTIVE]);

                return false;
            }

            foreach ($field->images as $image) {
                $this->imageUploadService->delete($image->image_url);
            }

            $field->delete();

            return true;
        });
    }

    private function appendImages(FootballField $field, array $images, bool $firstIsMain): void
    {
        foreach ($images as $index => $image) {
            if (! $image instanceof UploadedFile) {
                continue;
            }

            $field->images()->create([
                'image_url' => $this->imageUploadService->storeFieldImage($image),
                'is_main' => $firstIsMain && $index === 0,
            ]);
        }
    }
}
