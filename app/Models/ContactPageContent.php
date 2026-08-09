<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPageContent extends Model
{
    protected $fillable = [
        'meta_title', 'meta_description',
        'hero_eyebrow_text', 'hero_title', 'hero_image',
        'address_icon', 'address_heading', 'address',
        'phone_icon', 'phone_heading', 'phone_number',
        'email_icon', 'email_heading', 'email_address',
        'hours_icon', 'hours_heading', 'opening_hours',
        'form_heading', 'name_label', 'name_placeholder', 'email_label', 'email_placeholder',
        'subject_label', 'subject_placeholder', 'message_label', 'message_placeholder',
        'submit_button_text', 'notify_admin_by_email',
        'map_address', 'map_embed_url',
    ];

    protected function casts(): array
    {
        return ['notify_admin_by_email' => 'boolean'];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'meta_title' => 'Contact Us',
            'meta_description' => 'Contact Chomok for location, phone, opening hours and support.',
            'hero_eyebrow_text' => "We'd Love To Hear From You",
            'hero_title' => 'Contact Us',
            'address_icon' => '📍',
            'address_heading' => 'Our Address',
            'address' => '394 Brothers Mansion, East Rampur, Halishahar, Chittagong.',
            'phone_icon' => '📞',
            'phone_heading' => 'Call Us',
            'phone_number' => '+880 XXX-XXXXXX',
            'email_icon' => '✉️',
            'email_heading' => 'Email Us',
            'email_address' => 'hello@chomok.com',
            'hours_icon' => '🕐',
            'hours_heading' => 'Opening Hours',
            'opening_hours' => 'Monday to Saturday, 10am – 7pm',
            'form_heading' => 'Send Us A Message',
            'name_label' => 'Full Name',
            'name_placeholder' => 'Your name',
            'email_label' => 'Email Address',
            'email_placeholder' => 'you@example.com',
            'subject_label' => 'Subject',
            'subject_placeholder' => 'What is this about?',
            'message_label' => 'Message',
            'message_placeholder' => 'Write your message...',
            'submit_button_text' => 'Send Message',
            'map_address' => '394 Brothers Mansion, East Rampur, Halishahar, Chittagong, Bangladesh',
            'map_embed_url' => 'https://www.google.com/maps?q=394%20Brothers%20Mansion&output=embed',
        ]);
    }
}
