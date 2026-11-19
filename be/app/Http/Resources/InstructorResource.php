<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,

            'avatar' => $this->avatar
                ? asset('storage/' . $this->avatar)
                : null,

            'pengalaman' => $this->pengalaman,
            'biografi' => $this->biografi,

            'vendors_categories' => $this->vendors_categories->map(function ($vendors_category) {
                return [
                    'uuid' => $vendors_category->uuid,
                    'name' => $vendors_category->name,
                    'slug' => $vendors_category->slug,
                ];
            }),
        ];
    }
}
