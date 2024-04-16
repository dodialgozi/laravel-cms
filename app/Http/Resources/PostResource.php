<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'post_title_id' => $this->post_title_id,
            'post_title_en' => $this->post_title_en,
            'post_slug_id' => $this->post_slug_id,
            'post_slug_en' => $this->post_slug_en,
            'post_content_id' => $this->post_content_id,
            'post_content_en' => $this->post_content_en,
            'post_date' => $this->post_date,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keyword' => $this->meta_keyword,
            'post_status' => $this->post_status,
            'post_type' => $this->post_type,
            'post_view' => $this->post_view,
            'post_video_url' => $this->post_video_url,
            'post_excerpt_id' => $this->post_excerpt_id,
            'post_excerpt_en' => $this->post_excerpt_en,
            'first_image' => $this->first_image,
            'thumbnail' => $this->thumbnail,
            'medium_thumbnail' => $this->medium_thumbnail,
            'post_trending_topic' => $this->post_trending_topic,
            'post_hottopic' => $this->post_hottopic,
            'post_slider' => $this->post_slider,
            'post_malayhomeland' => $this->post_malayhomeland,
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
        ];
    }
}
