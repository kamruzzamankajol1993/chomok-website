<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Client;
use App\Models\MenuItem;
use App\Models\Setting;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $branches = $this->seedBranches();
        $this->seedClients($branches);

        [$categories, $subcategories] = $this->seedCategories();
        $addons = $this->seedAddons();
        $this->seedMenuItems($categories, $subcategories, $addons, $branches);

        $setting = Setting::current();
        if (blank($setting->address)) {
            $setting->address = '394 Brothers Mansion, East Rampur, Halishahar, Chattogram, Bangladesh';
            $setting->save();
        }
    }

    /** @return array<string, Branch> */
    private function seedBranches(): array
    {
        // These rows mirror the locations currently shown in the website shop.php.
        // legacy_code lets this seeder update the original five demo branches in-place
        // (preserving their IDs/relationships) instead of inserting duplicates.
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

        $result = [];
        foreach ($rows as $row) {
            $legacyCode = $row['legacy_code'];
            unset($row['legacy_code']);

            $branch = Branch::withTrashed()->where('code', $row['code'])->first();

            if (! $branch && $legacyCode) {
                $branch = Branch::withTrashed()->where('code', $legacyCode)->first();
            }

            $branch ??= new Branch();
            $branch->fill($row + ['accepting_orders' => true, 'status' => 'active']);
            $branch->deleted_at = null;
            $branch->save();
            $result[$row['code']] = $branch;
        }

        return $result;
    }

    /** @param array<string, Branch> $branches */
    private function seedClients(array $branches): void
    {
        $adminId = User::query()->where('email', 'admin@chomok.com')->value('id');
        $rows = [
            ['code' => 'CL-0001', 'branch' => 'HO', 'name' => 'Rahim Ahmed', 'email' => 'rahim@example.test', 'phone' => '01711000001', 'address' => 'Halishahar, Chattogram'],
            ['code' => 'CL-0002', 'branch' => 'OUT1', 'name' => 'Nusrat Jahan', 'email' => 'nusrat@example.test', 'phone' => '01711000002', 'address' => 'Agrabad, Chattogram'],
            ['code' => 'CL-0003', 'branch' => 'OUT2', 'name' => 'Tanvir Hasan', 'email' => 'tanvir@example.test', 'phone' => '01711000003', 'address' => 'Khulshi, Chattogram'],
            ['code' => 'CL-0004', 'branch' => 'CK', 'name' => 'Mim Akter', 'email' => 'mim@example.test', 'phone' => '01711000004', 'address' => 'Nasirabad, Chattogram'],
            ['code' => 'CL-0005', 'branch' => 'WH', 'name' => 'Fahim Chowdhury', 'email' => 'fahim@example.test', 'phone' => '01711000005', 'address' => 'Halishahar, Chattogram'],
        ];

        foreach ($rows as $row) {
            $client = Client::withTrashed()->where('code', $row['code'])->first() ?? new Client();
            $client->fill([
                'branch_id' => $branches[$row['branch']]->id,
                'created_by' => $adminId,
                'code' => $row['code'],
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'address' => $row['address'],
                'notes' => 'Demo customer seeded for testing.',
                'can_login' => false,
                'password' => null,
                'status' => 'active',
            ]);
            $client->deleted_at = null;
            $client->save();
        }
    }

    /** @return array{0: array<string, Category>, 1: array<string, Subcategory>} */
    private function seedCategories(): array
    {
        $rows = [
            ['name' => 'Burgers', 'slug' => 'burgers', 'subcategory' => 'Beef & Chicken Burgers', 'sub_slug' => 'beef-chicken-burgers'],
            ['name' => 'Pizza', 'slug' => 'pizza', 'subcategory' => 'Classic Pizza', 'sub_slug' => 'classic-pizza'],
            ['name' => 'Fried Chicken', 'slug' => 'fried-chicken', 'subcategory' => 'Crispy Chicken', 'sub_slug' => 'crispy-chicken'],
            ['name' => 'Rice & Biryani', 'slug' => 'rice-biryani', 'subcategory' => 'Biryani & Tehari', 'sub_slug' => 'biryani-tehari'],
            ['name' => 'Pasta & Noodles', 'slug' => 'pasta-noodles', 'subcategory' => 'Pasta & Noodles', 'sub_slug' => 'pasta-noodles-items'],
            ['name' => 'Sandwiches', 'slug' => 'sandwiches', 'subcategory' => 'Club Sandwich', 'sub_slug' => 'club-sandwich'],
            ['name' => 'Salads', 'slug' => 'salads', 'subcategory' => 'Fresh Salad', 'sub_slug' => 'fresh-salad'],
            ['name' => 'Desserts', 'slug' => 'desserts', 'subcategory' => 'Sweet Desserts', 'sub_slug' => 'sweet-desserts'],
            ['name' => 'Beverages', 'slug' => 'beverages', 'subcategory' => 'Cold Drinks & Shakes', 'sub_slug' => 'cold-drinks-shakes'],
            ['name' => 'Combos', 'slug' => 'combos', 'subcategory' => 'Family Combos', 'sub_slug' => 'family-combos'],
        ];

        $categories = [];
        $subcategories = [];

        foreach ($rows as $row) {
            $category = Category::withTrashed()->where('slug', $row['slug'])->first() ?? new Category();
            $category->fill([
                'name' => $row['name'],
                'slug' => $row['slug'],
                'image' => 'public/uploads/categories/seed-'.$row['slug'].'.jpg',
                'description' => 'Demo '.$row['name'].' category for restaurant menu.',
                'is_active' => true,
            ]);
            $category->deleted_at = null;
            $category->save();
            $categories[$row['slug']] = $category;

            $subcategory = Subcategory::withTrashed()->where('slug', $row['sub_slug'])->first() ?? new Subcategory();
            $subcategory->fill([
                'category_id' => $category->id,
                'name' => $row['subcategory'],
                'slug' => $row['sub_slug'],
                'is_active' => true,
            ]);
            $subcategory->deleted_at = null;
            $subcategory->save();
            $subcategories[$row['sub_slug']] = $subcategory;
        }

        return [$categories, $subcategories];
    }

    /** @return array<string, Addon> */
    private function seedAddons(): array
    {
        $rows = [
            ['name' => 'Extra Cheese', 'price' => 50],
            ['name' => 'Extra Beef Patty', 'price' => 120],
            ['name' => 'Extra Chicken Patty', 'price' => 90],
            ['name' => 'French Fries', 'price' => 80],
            ['name' => 'Coleslaw', 'price' => 60],
            ['name' => 'Fried Egg', 'price' => 35],
            ['name' => 'BBQ Sauce', 'price' => 30],
            ['name' => 'Garlic Mayo', 'price' => 30],
            ['name' => 'Soft Drink', 'price' => 60],
            ['name' => 'Extra Rice', 'price' => 70],
        ];

        $result = [];
        foreach ($rows as $row) {
            $addon = Addon::withTrashed()->where('name', $row['name'])->first() ?? new Addon();
            $addon->fill($row + ['is_active' => true]);
            $addon->deleted_at = null;
            $addon->save();
            $result[$row['name']] = $addon;
        }

        return $result;
    }

    /**
     * @param array<string, Category> $categories
     * @param array<string, Subcategory> $subcategories
     * @param array<string, Addon> $addons
     * @param array<string, Branch> $branches
     */
    private function seedMenuItems(array $categories, array $subcategories, array $addons, array $branches): void
    {
        $rows = [
            ['name' => 'Classic Beef Burger', 'slug' => 'classic-beef-burger', 'cat' => 'burgers', 'sub' => 'beef-chicken-burgers', 'price' => 320, 'addons' => ['Extra Cheese','Extra Beef Patty','French Fries','Fried Egg','BBQ Sauce']],
            ['name' => 'Cheese Burger', 'slug' => 'cheese-burger', 'cat' => 'burgers', 'sub' => 'beef-chicken-burgers', 'price' => 350, 'addons' => ['Extra Cheese','Extra Beef Patty','French Fries','Garlic Mayo']],
            ['name' => 'Chicken Burger', 'slug' => 'chicken-burger', 'cat' => 'burgers', 'sub' => 'beef-chicken-burgers', 'price' => 280, 'addons' => ['Extra Cheese','Extra Chicken Patty','French Fries','Garlic Mayo']],
            ['name' => 'Margherita Pizza', 'slug' => 'margherita-pizza', 'cat' => 'pizza', 'sub' => 'classic-pizza', 'price' => 480, 'addons' => ['Extra Cheese','BBQ Sauce','Garlic Mayo']],
            ['name' => 'BBQ Chicken Pizza', 'slug' => 'bbq-chicken-pizza', 'cat' => 'pizza', 'sub' => 'classic-pizza', 'price' => 620, 'addons' => ['Extra Cheese','BBQ Sauce','Soft Drink']],
            ['name' => 'Crispy Fried Chicken', 'slug' => 'crispy-fried-chicken', 'cat' => 'fried-chicken', 'sub' => 'crispy-chicken', 'price' => 260, 'addons' => ['French Fries','Coleslaw','Garlic Mayo','Soft Drink']],
            ['name' => 'Spicy Chicken Wings', 'slug' => 'spicy-chicken-wings', 'cat' => 'fried-chicken', 'sub' => 'crispy-chicken', 'price' => 340, 'addons' => ['French Fries','BBQ Sauce','Garlic Mayo','Soft Drink']],
            ['name' => 'Chicken Biryani', 'slug' => 'chicken-biryani', 'cat' => 'rice-biryani', 'sub' => 'biryani-tehari', 'price' => 290, 'addons' => ['Fried Egg','Extra Rice','Soft Drink']],
            ['name' => 'Beef Tehari', 'slug' => 'beef-tehari', 'cat' => 'rice-biryani', 'sub' => 'biryani-tehari', 'price' => 330, 'addons' => ['Fried Egg','Extra Rice','Soft Drink']],
            ['name' => 'Creamy Alfredo Pasta', 'slug' => 'creamy-alfredo-pasta', 'cat' => 'pasta-noodles', 'sub' => 'pasta-noodles-items', 'price' => 390, 'addons' => ['Extra Cheese','Extra Chicken Patty','Garlic Mayo']],
            ['name' => 'Spicy Chicken Noodles', 'slug' => 'spicy-chicken-noodles', 'cat' => 'pasta-noodles', 'sub' => 'pasta-noodles-items', 'price' => 340, 'addons' => ['Extra Chicken Patty','Fried Egg','Soft Drink']],
            ['name' => 'Club Sandwich', 'slug' => 'club-sandwich', 'cat' => 'sandwiches', 'sub' => 'club-sandwich', 'price' => 270, 'addons' => ['Extra Cheese','French Fries','Fried Egg','Garlic Mayo']],
            ['name' => 'Caesar Salad', 'slug' => 'caesar-salad', 'cat' => 'salads', 'sub' => 'fresh-salad', 'price' => 250, 'addons' => ['Extra Chicken Patty','Extra Cheese']],
            ['name' => 'Chocolate Brownie', 'slug' => 'chocolate-brownie', 'cat' => 'desserts', 'sub' => 'sweet-desserts', 'price' => 180, 'addons' => []],
            ['name' => 'Mango Shake', 'slug' => 'mango-shake', 'cat' => 'beverages', 'sub' => 'cold-drinks-shakes', 'price' => 190, 'addons' => []],
            ['name' => 'Family Feast Combo', 'slug' => 'family-feast-combo', 'cat' => 'combos', 'sub' => 'family-combos', 'price' => 1290, 'addons' => ['Extra Cheese','French Fries','Coleslaw','Soft Drink']],

            // Extra demo foods: every item has multiple prices and multiple addons.
            // Several items intentionally have discount_price on more than one price option.
            [
                'name' => 'Double Beef Stack Burger', 'slug' => 'double-beef-stack-burger', 'cat' => 'burgers', 'sub' => 'beef-chicken-burgers',
                'prices' => [
                    ['size_label' => 'Small', 'price' => 390, 'discount_price' => 360],
                    ['size_label' => 'Regular', 'price' => 490, 'discount_price' => 450],
                    ['size_label' => 'Large', 'price' => 590, 'discount_price' => 540],
                ],
                'addons' => ['Extra Cheese','Extra Beef Patty','French Fries','Fried Egg','BBQ Sauce','Garlic Mayo'],
            ],
            [
                'name' => 'Chicken Supreme Pizza', 'slug' => 'chicken-supreme-pizza', 'cat' => 'pizza', 'sub' => 'classic-pizza',
                'prices' => [
                    ['size_label' => '8 Inch', 'price' => 550, 'discount_price' => 499],
                    ['size_label' => '10 Inch', 'price' => 750, 'discount_price' => 699],
                    ['size_label' => '12 Inch', 'price' => 950, 'discount_price' => 849],
                ],
                'addons' => ['Extra Cheese','Extra Chicken Patty','BBQ Sauce','Garlic Mayo','Soft Drink'],
            ],
            [
                'name' => 'Meat Lovers Pizza', 'slug' => 'meat-lovers-pizza', 'cat' => 'pizza', 'sub' => 'classic-pizza',
                'prices' => [
                    ['size_label' => '8 Inch', 'price' => 650, 'discount_price' => null],
                    ['size_label' => '10 Inch', 'price' => 850, 'discount_price' => 799],
                    ['size_label' => '12 Inch', 'price' => 1100, 'discount_price' => 999],
                ],
                'addons' => ['Extra Cheese','Extra Beef Patty','Extra Chicken Patty','BBQ Sauce','Soft Drink'],
            ],
            [
                'name' => 'Hot Wings Bucket', 'slug' => 'hot-wings-bucket', 'cat' => 'fried-chicken', 'sub' => 'crispy-chicken',
                'prices' => [
                    ['size_label' => '6 Pieces', 'price' => 420, 'discount_price' => null],
                    ['size_label' => '12 Pieces', 'price' => 780, 'discount_price' => 720],
                    ['size_label' => '18 Pieces', 'price' => 1100, 'discount_price' => 999],
                ],
                'addons' => ['French Fries','Coleslaw','BBQ Sauce','Garlic Mayo','Soft Drink'],
            ],
            [
                'name' => 'Kacchi Biryani Platter', 'slug' => 'kacchi-biryani-platter', 'cat' => 'rice-biryani', 'sub' => 'biryani-tehari',
                'prices' => [
                    ['size_label' => 'Half', 'price' => 380, 'discount_price' => 350],
                    ['size_label' => 'Full', 'price' => 650, 'discount_price' => 599],
                    ['size_label' => 'Family', 'price' => 1650, 'discount_price' => 1499],
                ],
                'addons' => ['Fried Egg','Extra Rice','Coleslaw','Soft Drink'],
            ],
            [
                'name' => 'Loaded Alfredo Pasta', 'slug' => 'loaded-alfredo-pasta', 'cat' => 'pasta-noodles', 'sub' => 'pasta-noodles-items',
                'prices' => [
                    ['size_label' => 'Regular', 'price' => 440, 'discount_price' => null],
                    ['size_label' => 'Large', 'price' => 590, 'discount_price' => 550],
                    ['size_label' => 'Family', 'price' => 990, 'discount_price' => 899],
                ],
                'addons' => ['Extra Cheese','Extra Chicken Patty','Fried Egg','Garlic Mayo','Soft Drink'],
            ],
            [
                'name' => 'Grilled Club Sandwich', 'slug' => 'grilled-club-sandwich', 'cat' => 'sandwiches', 'sub' => 'club-sandwich',
                'prices' => [
                    ['size_label' => 'Regular', 'price' => 320, 'discount_price' => null],
                    ['size_label' => 'Double', 'price' => 450, 'discount_price' => null],
                    ['size_label' => 'Combo', 'price' => 590, 'discount_price' => 549],
                ],
                'addons' => ['Extra Cheese','Extra Chicken Patty','French Fries','Fried Egg','Garlic Mayo','Soft Drink'],
            ],
            [
                'name' => 'Brownie Sundae', 'slug' => 'brownie-sundae', 'cat' => 'desserts', 'sub' => 'sweet-desserts',
                'prices' => [
                    ['size_label' => 'Single', 'price' => 220, 'discount_price' => null],
                    ['size_label' => 'Double', 'price' => 360, 'discount_price' => null],
                    ['size_label' => 'Family', 'price' => 650, 'discount_price' => null],
                ],
                'addons' => ['French Fries','Soft Drink'],
            ],
            [
                'name' => 'Premium Mango Shake', 'slug' => 'premium-mango-shake', 'cat' => 'beverages', 'sub' => 'cold-drinks-shakes',
                'prices' => [
                    ['size_label' => '250 ml', 'price' => 180, 'discount_price' => 165],
                    ['size_label' => '400 ml', 'price' => 260, 'discount_price' => 235],
                    ['size_label' => '600 ml', 'price' => 350, 'discount_price' => 315],
                ],
                'addons' => ['French Fries','Soft Drink'],
            ],
            [
                'name' => 'Mega Family Combo', 'slug' => 'mega-family-combo', 'cat' => 'combos', 'sub' => 'family-combos',
                'prices' => [
                    ['size_label' => 'For 2 Persons', 'price' => 990, 'discount_price' => 899],
                    ['size_label' => 'For 4 Persons', 'price' => 1790, 'discount_price' => 1599],
                    ['size_label' => 'For 6 Persons', 'price' => 2490, 'discount_price' => 2199],
                ],
                'addons' => ['Extra Cheese','Extra Beef Patty','Extra Chicken Patty','French Fries','Coleslaw','BBQ Sauce','Garlic Mayo','Soft Drink'],
            ],
        ];

        $branchIds = collect($branches)->pluck('id')->all();

        foreach ($rows as $index => $row) {
            $item = MenuItem::withTrashed()->where('slug', $row['slug'])->first() ?? new MenuItem();
            $item->fill([
                'category_id' => $categories[$row['cat']]->id,
                'subcategory_id' => $subcategories[$row['sub']]->id,
                'name' => $row['name'],
                'slug' => $row['slug'],
                'description' => 'Freshly prepared '.$row['name'].' demo menu item.',
                'is_active' => true,
            ]);
            $item->deleted_at = null;
            $item->save();

            $item->prices()->delete();

            $priceRows = $row['prices'] ?? [[
                'size_label' => 'Regular',
                'price' => $row['price'],
                'discount_price' => $index % 4 === 0 ? max($row['price'] - 20, 1) : null,
            ]];

            foreach ($priceRows as $priceIndex => $priceRow) {
                $item->prices()->create([
                    'size_label' => $priceRow['size_label'] ?? 'Regular',
                    'price' => $priceRow['price'],
                    'discount_price' => $priceRow['discount_price'] ?? null,
                    'sort_order' => $priceIndex,
                ]);
            }

            $item->images()->update(['is_main' => false]);
            $item->images()->updateOrCreate(
                ['image' => 'public/uploads/menu-items/seed-'.$row['slug'].'.jpg'],
                ['is_main' => true, 'sort_order' => 0],
            );

            $addonIds = collect($row['addons'])
                ->map(fn (string $name) => $addons[$name]->id)
                ->all();
            $item->addons()->sync($addonIds);
            $item->branches()->sync($branchIds);
        }
    }
}
