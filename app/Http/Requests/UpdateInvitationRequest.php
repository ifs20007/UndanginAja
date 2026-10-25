<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'groom_name' => 'required|string|max:100',
            'bride_name' => 'required|string|max:100',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'venue_name' => 'required|string|max:200',
            'venue_address' => 'required|string',
            'google_maps_url' => 'nullable|url',
            'message' => 'nullable|string|max:1000',
            'template_id' => 'nullable|exists:templates,id',
            'theme_config' => 'nullable|array',
            'live_streaming_url' => 'nullable|url|max:500',
            'music_url' => 'nullable|string|max:500',
            'dresscode' => 'nullable|string|max:1000',
            'custom_font' => 'nullable|string|max:100',
            'love_stories' => 'nullable|array',
            'love_stories.*.date' => 'nullable|string|max:100',
            'love_stories.*.year' => 'nullable|string|max:100',
            'love_stories.*.title' => 'nullable|string|max:150',
            'love_stories.*.story' => 'nullable|string|max:2000',
            'gallery_photos' => 'nullable|array',
            'gallery_photos.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'groom_name.required' => 'Nama mempelai pria wajib diisi.',
            'bride_name.required' => 'Nama mempelai wanita wajib diisi.',
            'event_date.required' => 'Tanggal acara wajib diisi.',
            'event_time.required' => 'Waktu acara wajib diisi.',
            'venue_name.required' => 'Nama tempat / gedung wajib diisi.',
            'venue_address.required' => 'Alamat lengkap wajib diisi.',
            'google_maps_url.url' => 'Format URL Google Maps tidak valid.',
        ];
    }
}
