<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'legacy_code' => 'HO',
                'code' => 'HO',
                'name' => 'Head Office',
                'address' => '394 Brothers Mansion, East Rampur, Halishahar, Chittagong.',
                'city' => 'Chittagong',
                'phone' => '+880 XXX-XXXXXX',
                'email' => null,
                'image' => 'public/uploads/branches/seed-chomok-shop.jpg',
                'google_map_link' => 'https://www.google.com/maps?q=394%20Brothers%20Mansion%2C%20East%20Rampur%2C%20Halishahar%2C%20Chittagong%2C%20Bangladesh',
            ],
            [
                'legacy_code' => 'AGR',
                'code' => 'OUT1',
                'name' => '1st Outlet',
                'address' => '2nd Floor Avenue Center, CDA Avenue, GEC Circle, Chittagong; Bangladesh.',
                'city' => 'Chittagong',
                'phone' => '+880 XXX-XXXXXX',
                'email' => null,
                'image' => 'public/uploads/branches/seed-chomok-shop.jpg',
                'google_map_link' => 'https://www.google.com/maps?q=2nd%20Floor%20Avenue%20Center%2C%20CDA%20Avenue%2C%20GEC%20Circle%2C%20Chittagong%2C%20Bangladesh',
            ],
            [
                'legacy_code' => 'GEC',
                'code' => 'OUT2',
                'name' => '2nd Outlet',
                'address' => '448/500, Arakan Street, Samsurnahar Villa, Olekha Masjid Circle, Chawkbazar, Panchlaish PS; Chittagong-4211; Bangladesh.',
                'city' => 'Chittagong',
                'phone' => '+880 XXX-XXXXXX',
                'email' => null,
                'image' => 'public/uploads/branches/seed-chomok-shop.jpg',
                'google_map_link' => 'https://www.google.com/maps?q=448%2F500%2C%20Arakan%20Street%2C%20Samsurnahar%20Villa%2C%20Olekha%20Masjid%20Circle%2C%20Chawkbazar%2C%20Panchlaish%2C%20Chittagong%204211%2C%20Bangladesh',
            ],
            [
                'legacy_code' => 'NSB',
                'code' => 'CK',
                'name' => 'Cloud Kitchen',
                'address' => '394 Brothers Mansion, East Rampur, Halishahar, Chittagong.',
                'city' => 'Chittagong',
                'phone' => '+880 XXX-XXXXXX',
                'email' => null,
                'image' => 'public/uploads/branches/seed-chomok-shop.jpg',
                'google_map_link' => 'https://www.google.com/maps?q=394%20Brothers%20Mansion%2C%20East%20Rampur%2C%20Halishahar%2C%20Chittagong%2C%20Bangladesh',
            ],
            [
                'legacy_code' => 'HLH',
                'code' => 'WH',
                'name' => 'Ware House',
                'address' => '394 Brothers Mansion, East Rampur, Halishahar, Chittagong.',
                'city' => 'Chittagong',
                'phone' => '+880 XXX-XXXXXX',
                'email' => null,
                'image' => 'public/uploads/branches/seed-chomok-shop.jpg',
                'google_map_link' => 'https://www.google.com/maps?q=394%20Brothers%20Mansion%2C%20East%20Rampur%2C%20Halishahar%2C%20Chittagong%2C%20Bangladesh',
            ],
            [
                'legacy_code' => null,
                'code' => 'RAJ',
                'name' => 'Chomok Rajshahi',
                'address' => '3rd Floor, M.R.M. Mobile Market (Opposite New Market), Saheb Bazar, Boalia, Rajshahi-6100, Bangladesh.',
                'city' => 'Rajshahi',
                'phone' => '+880 XXX-XXXXXX',
                'email' => null,
                'image' => 'public/uploads/branches/seed-chomok-shop.jpg',
                'google_map_link' => 'https://www.google.com/maps?q=M.R.M.%20Mobile%20Market%2C%20Saheb%20Bazar%2C%20Boalia%2C%20Rajshahi%206100%2C%20Bangladesh',
            ],
        ];

        foreach ($rows as $row) {
            $legacyCode = $row['legacy_code'];
            unset($row['legacy_code']);

            $branch = Branch::withTrashed()->where('code', $row['code'])->first();

            if (! $branch && $legacyCode) {
                $branch = Branch::withTrashed()->where('code', $legacyCode)->first();
            }

            $branch ??= new Branch();
            $branch->fill($row + [
                'accepting_orders' => true,
                'status' => 'active',
            ]);
            $branch->deleted_at = null;
            $branch->save();
        }
    }
}
