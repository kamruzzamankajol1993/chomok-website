<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\Branch;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariationAddonDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $catalog = $this->prepareCatalog();

            $branchIds = Branch::query()
                ->where('status', 'active')
                ->pluck('id')
                ->all();

            if (empty($branchIds)) {
                $branchIds = Branch::query()->pluck('id')->all();
            }

            $products = [
                [
                    'name' => 'Variation Demo Pizza',
                    'slug' => 'variation-demo-pizza',
                    'category' => 'pizza',
                    'subcategory' => 'classic-pizza',
                    'description' => 'Demo pizza for testing price/size specific variation add-ons.',
                    'global_addons' => ['Extra Cheese', 'Soft Drink', 'Garlic Mayo'],
                    'prices' => [
                        [
                            'size_label' => '6 Inch',
                            'price' => 800,
                            'variation_addons' => [
                                ['name' => 'AA', 'price' => 40],
                                ['name' => 'AAA', 'price' => 40],
                            ],
                        ],
                        [
                            'size_label' => '8 Inch',
                            'price' => 900,
                            'variation_addons' => [
                                ['name' => 'AA b', 'price' => 40],
                                ['name' => 'AAAbb', 'price' => 40],
                            ],
                        ],
                        [
                            'size_label' => '10 Inch',
                            'price' => 1050,
                            'variation_addons' => [
                                ['name' => 'Extra Mozzarella 10 Inch', 'price' => 90],
                                ['name' => 'Chicken Topping 10 Inch', 'price' => 120],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Variation Demo Beef Burger',
                    'slug' => 'variation-demo-beef-burger',
                    'category' => 'burgers',
                    'subcategory' => 'beef-chicken-burgers',
                    'description' => 'Demo burger with variation-specific add-ons.',
                    'global_addons' => ['French Fries', 'Soft Drink', 'Garlic Mayo'],
                    'prices' => [
                        [
                            'size_label' => 'Single Patty',
                            'price' => 350,
                            'variation_addons' => [
                                ['name' => 'Single Cheese Slice', 'price' => 35],
                                ['name' => 'Single Fried Egg', 'price' => 30],
                            ],
                        ],
                        [
                            'size_label' => 'Double Patty',
                            'price' => 520,
                            'variation_addons' => [
                                ['name' => 'Double Cheese', 'price' => 65],
                                ['name' => 'Extra Beef Patty', 'price' => 140],
                                ['name' => 'Caramelized Onion', 'price' => 35],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Variation Demo Hot Wings',
                    'slug' => 'variation-demo-hot-wings',
                    'category' => 'fried-chicken',
                    'subcategory' => 'crispy-chicken',
                    'description' => 'Demo wings with pack-size specific add-ons.',
                    'global_addons' => ['French Fries', 'Soft Drink', 'BBQ Sauce'],
                    'prices' => [
                        [
                            'size_label' => '6 Pieces',
                            'price' => 420,
                            'variation_addons' => [
                                ['name' => '1 Extra Dip', 'price' => 30],
                                ['name' => 'Small Coleslaw', 'price' => 45],
                            ],
                        ],
                        [
                            'size_label' => '12 Pieces',
                            'price' => 780,
                            'variation_addons' => [
                                ['name' => '2 Extra Dips', 'price' => 55],
                                ['name' => 'Large Coleslaw', 'price' => 80],
                                ['name' => 'Loaded Fries', 'price' => 120],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Variation Demo Alfredo Pasta',
                    'slug' => 'variation-demo-alfredo-pasta',
                    'category' => 'pasta-noodles',
                    'subcategory' => 'pasta-noodles-items',
                    'description' => 'Demo pasta with portion-specific add-ons.',
                    'global_addons' => ['Extra Cheese', 'Garlic Mayo', 'Soft Drink'],
                    'prices' => [
                        [
                            'size_label' => 'Regular',
                            'price' => 440,
                            'variation_addons' => [
                                ['name' => 'Regular Extra Chicken', 'price' => 80],
                                ['name' => 'Regular Mushroom', 'price' => 55],
                            ],
                        ],
                        [
                            'size_label' => 'Large',
                            'price' => 590,
                            'variation_addons' => [
                                ['name' => 'Large Extra Chicken', 'price' => 120],
                                ['name' => 'Large Mushroom', 'price' => 80],
                                ['name' => 'Parmesan Topping', 'price' => 60],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Variation Demo Chocolate Shake',
                    'slug' => 'variation-demo-chocolate-shake',
                    'category' => 'beverages',
                    'subcategory' => 'cold-drinks-shakes',
                    'description' => 'Demo shake with cup-size specific add-ons.',
                    'global_addons' => ['Soft Drink'],
                    'prices' => [
                        [
                            'size_label' => '250 ml',
                            'price' => 180,
                            'variation_addons' => [
                                ['name' => '1 Ice Cream Scoop', 'price' => 50],
                                ['name' => 'Oreo Crush Small', 'price' => 35],
                            ],
                        ],
                        [
                            'size_label' => '400 ml',
                            'price' => 260,
                            'variation_addons' => [
                                ['name' => '2 Ice Cream Scoops', 'price' => 90],
                                ['name' => 'Oreo Crush Large', 'price' => 55],
                                ['name' => 'Whipped Cream', 'price' => 40],
                            ],
                        ],
                    ],
                ],
            ];

            foreach ($products as $product) {
                $item = MenuItem::withTrashed()->where('slug', $product['slug'])->first() ?? new MenuItem();
                $item->fill([
                    'category_id' => $catalog[$product['category']]['category']->id,
                    'subcategory_id' => $catalog[$product['category']]['subcategories'][$product['subcategory']]->id,
                    'name' => $product['name'],
                    'slug' => $product['slug'],
                    'description' => $product['description'],
                    'is_active' => true,
                ]);
                $item->deleted_at = null;
                $item->save();

                // Deleting price rows also deletes their variation add-ons through cascadeOnDelete().
                $item->prices()->delete();

                foreach ($product['prices'] as $priceIndex => $priceData) {
                    $price = $item->prices()->create([
                        'size_label' => $priceData['size_label'],
                        'price' => $priceData['price'],
                        'sort_order' => $priceIndex,
                    ]);

                    foreach ($priceData['variation_addons'] as $addonIndex => $addonData) {
                        $price->variationAddons()->create([
                            'name' => $addonData['name'],
                            'price' => $addonData['price'],
                            'sort_order' => $addonIndex,
                        ]);
                    }
                }

                // Existing global add-on master is NOT changed. Only already-existing global add-ons are attached.
                $globalAddonIds = Addon::query()
                    ->whereIn('name', $product['global_addons'])
                    ->where('is_active', true)
                    ->pluck('id')
                    ->all();

                $item->addons()->sync($globalAddonIds);

                if (! empty($branchIds)) {
                    $item->branches()->sync($branchIds);
                }
            }
        });
    }

    /**
     * Ensure the demo products have valid categories/subcategories without touching global add-ons.
     *
     * @return array<string, array{category: Category, subcategories: array<string, Subcategory>}>
     */
    private function prepareCatalog(): array
    {
        $definitions = [
            'pizza' => [
                'name' => 'Pizza',
                'subcategories' => ['classic-pizza' => 'Classic Pizza'],
            ],
            'burgers' => [
                'name' => 'Burgers',
                'subcategories' => ['beef-chicken-burgers' => 'Beef & Chicken Burgers'],
            ],
            'fried-chicken' => [
                'name' => 'Fried Chicken',
                'subcategories' => ['crispy-chicken' => 'Crispy Chicken'],
            ],
            'pasta-noodles' => [
                'name' => 'Pasta & Noodles',
                'subcategories' => ['pasta-noodles-items' => 'Pasta & Noodles Items'],
            ],
            'beverages' => [
                'name' => 'Beverages',
                'subcategories' => ['cold-drinks-shakes' => 'Cold Drinks & Shakes'],
            ],
        ];

        $result = [];

        foreach ($definitions as $categorySlug => $definition) {
            $category = Category::withTrashed()->where('slug', $categorySlug)->first() ?? new Category();
            $category->fill([
                'name' => $definition['name'],
                'slug' => $categorySlug,
                'is_active' => true,
            ]);
            $category->deleted_at = null;
            $category->save();

            $subcategories = [];
            foreach ($definition['subcategories'] as $subcategorySlug => $subcategoryName) {
                $subcategory = Subcategory::withTrashed()->where('slug', $subcategorySlug)->first() ?? new Subcategory();
                $subcategory->fill([
                    'category_id' => $category->id,
                    'name' => $subcategoryName,
                    'slug' => $subcategorySlug,
                    'is_active' => true,
                ]);
                $subcategory->deleted_at = null;
                $subcategory->save();

                $subcategories[$subcategorySlug] = $subcategory;
            }

            $result[$categorySlug] = [
                'category' => $category,
                'subcategories' => $subcategories,
            ];
        }

        return $result;
    }
}
