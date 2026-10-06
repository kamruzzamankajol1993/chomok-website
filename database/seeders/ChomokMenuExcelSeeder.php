<?php

namespace Database\Seeders;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;

class ChomokMenuExcelSeeder extends Seeder
{
    public function run(): void
    {
        $this->assertBaseSchema();
        $this->ensureVariationAddonSchema();

        $categories = json_decode(<<<'JSON'
[
  {
    "name": "Pizza"
  },
  {
    "name": "Burgers"
  },
  {
    "name": "Pasta"
  },
  {
    "name": "Broast Chicken"
  },
  {
    "name": "Rice Meals"
  },
  {
    "name": "Rice Bowls"
  },
  {
    "name": "Others"
  },
  {
    "name": "Meatbox"
  },
  {
    "name": "Combo"
  },
  {
    "name": "Shakes & Dessert"
  },
  {
    "name": "Sides"
  },
  {
    "name": "Beverages"
  }
]
JSON, true, 512, JSON_THROW_ON_ERROR);

        $subcategories = json_decode(<<<'JSON'
[
  {
    "category": "Pizza",
    "name": "Regular Pizza"
  },
  {
    "category": "Pizza",
    "name": "Loaded Pizza"
  },
  {
    "category": "Pizza",
    "name": "Overloaded Pizza"
  },
  {
    "category": "Burgers",
    "name": "New Burgers"
  },
  {
    "category": "Burgers",
    "name": "Smashed Burgers"
  },
  {
    "category": "Burgers",
    "name": "Signature Burgers"
  },
  {
    "category": "Broast Chicken",
    "name": "Broast Chicken"
  },
  {
    "category": "Shakes & Dessert",
    "name": "Shakes"
  },
  {
    "category": "Shakes & Dessert",
    "name": "Dessert"
  },
  {
    "category": "Broast Chicken",
    "name": "Broast Meal"
  }
]
JSON, true, 512, JSON_THROW_ON_ERROR);

        $globalAddons = json_decode(<<<'JSON'
[
  {
    "name": "Egg",
    "price": 30.0
  },
  {
    "name": "Mushroom",
    "price": 35.0
  },
  {
    "name": "Caramelized Onions",
    "price": 40.0
  },
  {
    "name": "Cheese",
    "price": 45.0
  },
  {
    "name": "Sausage",
    "price": 35.0
  },
  {
    "name": "Bacon",
    "price": 70.0
  },
  {
    "name": "Chorizo Jam",
    "price": 80.0
  },
  {
    "name": "Chicken Patty",
    "price": 115.0
  },
  {
    "name": "Beef Patty",
    "price": 129.0
  },
  {
    "name": "Fish Patty",
    "price": 109.0
  },
  {
    "name": "Cheese",
    "price": 75.0
  },
  {
    "name": "Melted Cheese",
    "price": 199.0
  }
]
JSON, true, 512, JSON_THROW_ON_ERROR);

        // Global Add-Ons are intentionally mapped only to menu items where they make culinary sense.
        // Pizza uses the variation-specific Add-Ons-Pizza sheet instead of these global addons.
        $globalAddonTargets = [
            'Egg|30' => [
                'Double Trouble', 'Mojar Zinger', 'Ultimate Smashed', 'Bacon Cheddar Smashed',
                'Classic Burger', 'Barbecue Burger', 'All Day Breakfast', 'Cheese Blast',
                'Chick Supremeo', 'Chatgaiya Burger', 'Chorizo Beef', 'Beef Joe',
            ],
            'Mushroom|35' => [
                'Double Trouble', 'Mojar Zinger', 'Ultimate Smashed', 'Bacon Cheddar Smashed',
                'Classic Burger', 'Barbecue Burger', 'All Day Breakfast', 'Cheese Blast',
                'Chick Supremeo', 'Chatgaiya Burger', 'Chorizo Beef', 'Beef Joe',
            ],
            'Caramelized Onions|40' => [
                'Double Trouble', 'Mojar Zinger', 'Ultimate Smashed', 'Bacon Cheddar Smashed',
                'Classic Burger', 'Barbecue Burger', 'All Day Breakfast', 'Cheese Blast',
                'Chick Supremeo', 'Chatgaiya Burger', 'Chorizo Beef', 'Beef Joe',
            ],
            // Standard cheese addon for regular/signature burgers and Fish-O-Filet.
            'Cheese|45' => [
                'Mojar Zinger', 'Classic Burger', 'Barbecue Burger', 'All Day Breakfast',
                'Chick Supremeo', 'Fish-O-Filet',
            ],
            'Sausage|35' => [
                'Double Trouble', 'Mojar Zinger', 'Ultimate Smashed', 'Bacon Cheddar Smashed',
                'Classic Burger', 'Barbecue Burger', 'All Day Breakfast', 'Cheese Blast',
                'Chick Supremeo', 'Chatgaiya Burger', 'Chorizo Beef', 'Beef Joe',
            ],
            'Bacon|70' => [
                'Double Trouble', 'Mojar Zinger', 'Ultimate Smashed', 'Bacon Cheddar Smashed',
                'Classic Burger', 'Barbecue Burger', 'All Day Breakfast', 'Cheese Blast',
                'Chick Supremeo', 'Chatgaiya Burger', 'Chorizo Beef', 'Beef Joe',
            ],
            'Chorizo Jam|80' => [
                'Double Trouble', 'Ultimate Smashed', 'Bacon Cheddar Smashed', 'Classic Burger',
                'Barbecue Burger', 'All Day Breakfast', 'Cheese Blast', 'Chatgaiya Burger',
                'Chorizo Beef', 'Beef Joe',
            ],
            // Protein addons are limited to burgers whose recipe/price options support that protein.
            'Chicken Patty|115' => [
                'Double Trouble', 'Mojar Zinger', 'Classic Burger', 'Barbecue Burger',
                'All Day Breakfast', 'Cheese Blast', 'Chick Supremeo', 'Chatgaiya Burger',
            ],
            'Beef Patty|129' => [
                'Double Trouble', 'Ultimate Smashed', 'Bacon Cheddar Smashed', 'Classic Burger',
                'Barbecue Burger', 'All Day Breakfast', 'Cheese Blast', 'Chatgaiya Burger',
                'Chorizo Beef', 'Beef Joe',
            ],
            'Fish Patty|109' => ['Fish-O-Filet'],
            // The second Cheese row in Excel is kept distinct by price and used on premium/smashed burgers.
            'Cheese|75' => [
                'Double Trouble', 'Ultimate Smashed', 'Bacon Cheddar Smashed', 'Cheese Blast',
                'Chatgaiya Burger', 'Chorizo Beef', 'Beef Joe',
            ],
            // Melted cheese is a large topping and is applied to the Meatbox range.
            'Melted Cheese|199' => ['Classic Meatbox', 'Naga Meatbox', 'Twist Box'],
        ];

        $menuItems = json_decode(<<<'JSON'
[
  {
    "category": "Pizza",
    "subcategory": "Regular Pizza",
    "name": "Margherita",
    "description": "Sauce & cheese",
    "prices": [
      {
        "size_label": "Regular",
        "price": 269.0
      },
      {
        "size_label": "Medium",
        "price": 379.0
      },
      {
        "size_label": "Large",
        "price": 515.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Regular Pizza",
    "name": "Veg & Tez",
    "description": "Green capsicum, onions, green chilli, jalapeno & tomato",
    "prices": [
      {
        "size_label": "Regular",
        "price": 315.0
      },
      {
        "size_label": "Medium",
        "price": 425.0
      },
      {
        "size_label": "Large",
        "price": 629.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Regular Pizza",
    "name": "Beef Maximus",
    "description": "Minced beef, onion",
    "prices": [
      {
        "size_label": "Regular",
        "price": 425.0
      },
      {
        "size_label": "Medium",
        "price": 525.0
      },
      {
        "size_label": "Large",
        "price": 649.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Regular Pizza",
    "name": "Spicy Chicken",
    "description": "Spicy chicken, tomatoes, green chilli",
    "prices": [
      {
        "size_label": "Regular",
        "price": 385.0
      },
      {
        "size_label": "Medium",
        "price": 535.0
      },
      {
        "size_label": "Large",
        "price": 645.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Regular Pizza",
    "name": "Classic Chicken",
    "description": "Green capsicum, onions & spicy chicken",
    "prices": [
      {
        "size_label": "Regular",
        "price": 435.0
      },
      {
        "size_label": "Medium",
        "price": 535.0
      },
      {
        "size_label": "Large",
        "price": 689.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Loaded Pizza",
    "name": "NY BBQ",
    "description": "Green capsicum, mushroom, spicy chicken & BBQ sauce",
    "prices": [
      {
        "size_label": "Regular",
        "price": 435.0
      },
      {
        "size_label": "Medium",
        "price": 515.0
      },
      {
        "size_label": "Large",
        "price": 715.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Loaded Pizza",
    "name": "Peri Peri Chicken",
    "description": "Chicken, paprika, green capsicum, onion, peri-peri sauce",
    "prices": [
      {
        "size_label": "Regular",
        "price": 435.0
      },
      {
        "size_label": "Medium",
        "price": 535.0
      },
      {
        "size_label": "Large",
        "price": 729.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Loaded Pizza",
    "name": "Favorite Feast",
    "description": "Chicken, chicken sausage, mushrooms, onions & green chilli",
    "prices": [
      {
        "size_label": "Regular",
        "price": 435.0
      },
      {
        "size_label": "Medium",
        "price": 545.0
      },
      {
        "size_label": "Large",
        "price": 729.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Loaded Pizza",
    "name": "Pizza Americano",
    "description": "Chicken, chicken pepperoni, chicken sausage & baked with mozzarella",
    "prices": [
      {
        "size_label": "Regular",
        "price": 485.0
      },
      {
        "size_label": "Medium",
        "price": 645.0
      },
      {
        "size_label": "Large",
        "price": 749.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Loaded Pizza",
    "name": "Barbecue Chicken",
    "description": "BBQ chicken, mushroom, green capsicum, onion & black olive",
    "prices": [
      {
        "size_label": "Regular",
        "price": 465.0
      },
      {
        "size_label": "Medium",
        "price": 585.0
      },
      {
        "size_label": "Large",
        "price": 815.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Overloaded Pizza",
    "name": "Kabab Fantasy",
    "description": "Grill chicken rasher, chicken malai boti, chicken sausage, onions, green chilli",
    "prices": [
      {
        "size_label": "Regular",
        "price": 495.0
      },
      {
        "size_label": "Medium",
        "price": 649.0
      },
      {
        "size_label": "Large",
        "price": 849.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Overloaded Pizza",
    "name": "Pizza Supreme",
    "description": "Beef pepperoni, sliced beef, caramelized mushrooms, red onions, green chilli",
    "prices": [
      {
        "size_label": "Regular",
        "price": 549.0
      },
      {
        "size_label": "Medium",
        "price": 699.0
      },
      {
        "size_label": "Large",
        "price": 849.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Overloaded Pizza",
    "name": "Chomok's Special",
    "description": "Mushroom, BBQ chicken, green & red capsicum, chicken sausage, beef bacon, black olives, baked with mozzarella cheese",
    "prices": [
      {
        "size_label": "Regular",
        "price": 435.0
      },
      {
        "size_label": "Medium",
        "price": 599.0
      },
      {
        "size_label": "Large",
        "price": 865.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Overloaded Pizza",
    "name": "Pizza Meatzza",
    "description": "Minced beef, beef pepperoni, onions, jalapeno & baked with mozzarella cheese",
    "prices": [
      {
        "size_label": "Regular",
        "price": 445.0
      },
      {
        "size_label": "Medium",
        "price": 659.0
      },
      {
        "size_label": "Large",
        "price": 849.0
      }
    ]
  },
  {
    "category": "Pizza",
    "subcategory": "Overloaded Pizza",
    "name": "Meatlovers",
    "description": "Beef pepperoni, beef meatballs, chicken, chicken sausage, beef bacon, jalapeno, house special panko crumb",
    "prices": [
      {
        "size_label": "Regular",
        "price": 549.0
      },
      {
        "size_label": "Medium",
        "price": 699.0
      },
      {
        "size_label": "Large",
        "price": 989.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "New Burgers",
    "name": "Double Trouble",
    "description": "Double patty, double cheese slice, chicken rasher, egg, lettuce, house special mayo",
    "prices": [
      {
        "size_label": "Chicken",
        "price": 515.0
      },
      {
        "size_label": "Beef",
        "price": 549.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "New Burgers",
    "name": "Mojar Zinger",
    "description": "Crispy chicken fillet, lettuce, and mayo",
    "prices": [
      {
        "size_label": "Chicken",
        "price": 275.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Smashed Burgers",
    "name": "Ultimate Smashed",
    "description": "Smashed patty, cheese, caramelized mushroom, jalapeno (Single: 1 patty,1 cheese; Double: 2 patties,2 cheese)",
    "prices": [
      {
        "size_label": "Single",
        "price": 435.0
      },
      {
        "size_label": "Double",
        "price": 715.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Smashed Burgers",
    "name": "Bacon Cheddar Smashed",
    "description": "Smashed patty, cheese, egg, bacon, caramelized onion (Single: 1 patty,1 cheese; Double: 2 patties,2 cheese)",
    "prices": [
      {
        "size_label": "Single",
        "price": 535.0
      },
      {
        "size_label": "Double",
        "price": 769.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Signature Burgers",
    "name": "Classic Burger",
    "description": "Patty, house special mayonnaise, toppings",
    "prices": [
      {
        "size_label": "Chicken",
        "price": 245.0
      },
      {
        "size_label": "Beef",
        "price": 275.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Signature Burgers",
    "name": "Barbecue Burger",
    "description": "Patty, BBQ sauce, pickles, roasted garlic aioli, toppings",
    "prices": [
      {
        "size_label": "Chicken",
        "price": 275.0
      },
      {
        "size_label": "Beef",
        "price": 289.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Signature Burgers",
    "name": "All Day Breakfast",
    "description": "Patty, cheese, sausage, pickles, fried egg, roasted garlic aioli, toppings",
    "prices": [
      {
        "size_label": "Chicken",
        "price": 385.0
      },
      {
        "size_label": "Beef",
        "price": 385.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Signature Burgers",
    "name": "Cheese Blast",
    "description": "Cheese stuffed patty, barbecue sauce, house special mayonnaise, toppings",
    "prices": [
      {
        "size_label": "Chicken",
        "price": 399.0
      },
      {
        "size_label": "Beef",
        "price": 459.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Signature Burgers",
    "name": "Chick Supremeo",
    "description": "Chicken patty, BBQ sauce, beef bacon, cheese, and house special spicy mayo",
    "prices": [
      {
        "size_label": "Chicken",
        "price": 385.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Signature Burgers",
    "name": "Chatgaiya Burger",
    "description": "Patty, tamarind sauce, fried mozzarella, mint chatney, roasted garlic aioli, toppings",
    "prices": [
      {
        "size_label": "Chicken",
        "price": 385.0
      },
      {
        "size_label": "Beef",
        "price": 425.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Signature Burgers",
    "name": "Chorizo Beef",
    "description": "Patty, cheese, sausage, egg, chorizo jam, roasted garlic aioli, toppings",
    "prices": [
      {
        "size_label": "Beef",
        "price": 465.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Signature Burgers",
    "name": "Beef Joe",
    "description": "Patty, cheese, baked Joe gravy, bacon, house special mayonnaise, toppings",
    "prices": [
      {
        "size_label": "Beef",
        "price": 485.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Signature Burgers",
    "name": "Fish-O-Filet",
    "description": "Tartar sauce, crispy dory fish, crunchy lettuce, toppings",
    "prices": [
      {
        "size_label": "Fish",
        "price": 379.0
      }
    ]
  },
  {
    "category": "Burgers",
    "subcategory": "Signature Burgers",
    "name": "Make Your Meal",
    "description": "Half fries & soft drink added to any burger to make it a meal",
    "prices": [
      {
        "size_label": "Add",
        "price": 99.0
      }
    ]
  },
  {
    "category": "Pasta",
    "subcategory": null,
    "name": "Arabita Chicken Pasta",
    "description": "Penne pasta cooked in arrabiata sauce & chicken baked with lots of mozzarella cheese",
    "prices": [
      {
        "size_label": null,
        "price": 249.0
      }
    ]
  },
  {
    "category": "Pasta",
    "subcategory": null,
    "name": "Pasta Basta",
    "description": "Penne pasta cooked in bechamel sauce, minced chicken, capsicum, with mozzarella cheese",
    "prices": [
      {
        "size_label": null,
        "price": 275.0
      }
    ]
  },
  {
    "category": "Pasta",
    "subcategory": null,
    "name": "Naga Pasta",
    "description": "Penne pasta cooked with naga chicken & baked with lots of mozzarella cheese",
    "prices": [
      {
        "size_label": null,
        "price": 329.0
      }
    ]
  },
  {
    "category": "Pasta",
    "subcategory": null,
    "name": "Creamy Fussili",
    "description": "Fusilli pasta cooked with cream, chicken & baked with lots of mozzarella cheese",
    "prices": [
      {
        "size_label": null,
        "price": 315.0
      }
    ]
  },
  {
    "category": "Pasta",
    "subcategory": null,
    "name": "Chilli Mac & Cheese",
    "description": "Mac pasta cooked with bechamel sauce & chilli beef/chicken (choice), baked with mozzarella & cheddar cheese",
    "prices": [
      {
        "size_label": null,
        "price": 385.0
      }
    ]
  },
  {
    "category": "Broast Chicken",
    "subcategory": null,
    "name": "Broast Chicken",
    "description": "Broast chicken pieces",
    "prices": [
      {
        "size_label": "2pcs",
        "price": 285.0
      },
      {
        "size_label": "4pcs",
        "price": 549.0
      },
      {
        "size_label": "8pcs",
        "price": 1099.0
      }
    ]
  },
  {
    "category": "Broast Chicken",
    "subcategory": null,
    "name": "20pcs Bucket",
    "description": "4pcs broast, 8pcs strips, 8 wings",
    "prices": [
      {
        "size_label": null,
        "price": 1099.0
      }
    ]
  },
  {
    "category": "Broast Chicken",
    "subcategory": null,
    "name": "Coleslaw",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 85.0
      }
    ]
  },
  {
    "category": "Broast Chicken",
    "subcategory": "Broast Meal",
    "name": "Broast Meal-1",
    "description": "1 pc fried chicken, fried rice, 1 vegetables, 1 soft drink",
    "prices": [
      {
        "size_label": null,
        "price": 385.0
      }
    ]
  },
  {
    "category": "Broast Chicken",
    "subcategory": "Broast Meal",
    "name": "Broast Meal-2",
    "description": "2pcs chicken, 1 fries, 1 soft drink",
    "prices": [
      {
        "size_label": null,
        "price": 499.0
      }
    ]
  },
  {
    "category": "Broast Chicken",
    "subcategory": "Broast Meal",
    "name": "Broast Meal-3",
    "description": "Fried rice, 1 broast chicken, soup, vegetables, 1 soft drink",
    "prices": [
      {
        "size_label": null,
        "price": 439.0
      }
    ]
  },
  {
    "category": "Broast Chicken",
    "subcategory": "Broast Meal",
    "name": "Broast Meal-4",
    "description": "Crispy honey chicken salad, fried rice, 1 soft drink",
    "prices": [
      {
        "size_label": null,
        "price": 385.0
      }
    ]
  },
  {
    "category": "Broast Chicken",
    "subcategory": "Broast Meal",
    "name": "Broast Meal-5",
    "description": "1 fried chicken, coleslaw, bun, 1 soft drink",
    "prices": [
      {
        "size_label": null,
        "price": 385.0
      }
    ]
  },
  {
    "category": "Broast Chicken",
    "subcategory": "Broast Meal",
    "name": "Broast Meal-6",
    "description": "Fried rice, 5 fried prawns, vegetables, 1 soft drink",
    "prices": [
      {
        "size_label": null,
        "price": 329.0
      }
    ]
  },
  {
    "category": "Broast Chicken",
    "subcategory": "Broast Meal",
    "name": "Broast Meal-7",
    "description": "Fried rice, chicken masala, vegetables, 1 soft drink",
    "prices": [
      {
        "size_label": null,
        "price": 329.0
      }
    ]
  },
  {
    "category": "Rice Meals",
    "subcategory": null,
    "name": "Munchurian Chicken",
    "description": "Manchurian chicken, fried rice, vegetables, 2 spring roll",
    "prices": [
      {
        "size_label": null,
        "price": 329.0
      }
    ]
  },
  {
    "category": "Rice Meals",
    "subcategory": null,
    "name": "BBQ Chicken Meal",
    "description": "Quarter BBQ chicken, sauteed vegetables, fried rice",
    "prices": [
      {
        "size_label": null,
        "price": 385.0
      }
    ]
  },
  {
    "category": "Rice Meals",
    "subcategory": null,
    "name": "Chicken Drums",
    "description": "2 chicken drums, Chinese vegetables, fried rice, salads",
    "prices": [
      {
        "size_label": null,
        "price": 385.0
      }
    ]
  },
  {
    "category": "Rice Meals",
    "subcategory": null,
    "name": "Bangkok Chicken",
    "description": "2pcs Bangkok fried chicken, 2 spring roll, chicken chilli, vegetables, fried rice",
    "prices": [
      {
        "size_label": null,
        "price": 419.0
      }
    ]
  },
  {
    "category": "Rice Meals",
    "subcategory": null,
    "name": "Peri-Peri Chicken",
    "description": "Quarter peri-peri chicken, sauteed vegetables, fried rice",
    "prices": [
      {
        "size_label": null,
        "price": 425.0
      }
    ]
  },
  {
    "category": "Rice Meals",
    "subcategory": null,
    "name": "Stuffed Mushroom Chicken",
    "description": "Creamy cheese mushroom stuffed with chicken, served with sauteed vegetables & rice (achari, naga, or fried rice)",
    "prices": [
      {
        "size_label": null,
        "price": 475.0
      }
    ]
  },
  {
    "category": "Rice Bowls",
    "subcategory": null,
    "name": "BBQ Rice Bowl",
    "description": "BBQ chicken, egg, fried rice",
    "prices": [
      {
        "size_label": null,
        "price": 275.0
      }
    ]
  },
  {
    "category": "Rice Bowls",
    "subcategory": null,
    "name": "Achari Rice Bowl",
    "description": "Achari rice, chicken popcorn, egg & yogurt sauce",
    "prices": [
      {
        "size_label": null,
        "price": 275.0
      }
    ]
  },
  {
    "category": "Rice Bowls",
    "subcategory": null,
    "name": "Gyro Chicken Rice Bowl",
    "description": "Gyro chicken served with achari/naga rice, fries/vegetables, yogurt sauce",
    "prices": [
      {
        "size_label": null,
        "price": 385.0
      }
    ]
  },
  {
    "category": "Others",
    "subcategory": null,
    "name": "Vegetables",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 109.0
      }
    ]
  },
  {
    "category": "Others",
    "subcategory": null,
    "name": "Fried Rice",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 149.0
      }
    ]
  },
  {
    "category": "Others",
    "subcategory": null,
    "name": "Soup",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 149.0
      }
    ]
  },
  {
    "category": "Others",
    "subcategory": null,
    "name": "Crispy Honey Chicken Salad",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 329.0
      }
    ]
  },
  {
    "category": "Others",
    "subcategory": null,
    "name": "Honey Fish Salad",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 385.0
      }
    ]
  },
  {
    "category": "Others",
    "subcategory": null,
    "name": "Chicken Masala",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 385.0
      }
    ]
  },
  {
    "category": "Others",
    "subcategory": null,
    "name": "6pcs Fried Prawn / Prawn Masala",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 399.0
      }
    ]
  },
  {
    "category": "Sides",
    "subcategory": null,
    "name": "Stuffed Garlic Bread",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 275.0
      }
    ]
  },
  {
    "category": "Sides",
    "subcategory": null,
    "name": "Fish Strips",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 259.0
      }
    ]
  },
  {
    "category": "Sides",
    "subcategory": null,
    "name": "Chicken Tender",
    "description": null,
    "prices": [
      {
        "size_label": "3pcs",
        "price": 169.0
      },
      {
        "size_label": "6pcs",
        "price": 299.0
      }
    ]
  },
  {
    "category": "Sides",
    "subcategory": null,
    "name": "Naga Wings (6pcs)",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 259.0
      }
    ]
  },
  {
    "category": "Sides",
    "subcategory": null,
    "name": "BBQ Spicy Wings (6pcs)",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 259.0
      }
    ]
  },
  {
    "category": "Sides",
    "subcategory": null,
    "name": "Peri-Peri Wings (6pcs)",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 259.0
      }
    ]
  },
  {
    "category": "Sides",
    "subcategory": null,
    "name": "Cheese Ball (4pcs)",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 209.0
      }
    ]
  },
  {
    "category": "Sides",
    "subcategory": null,
    "name": "Fries",
    "description": "Imported fries tossed with spices",
    "prices": [
      {
        "size_label": null,
        "price": 149.0
      }
    ]
  },
  {
    "category": "Sides",
    "subcategory": null,
    "name": "Beef Tacos (2pcs)",
    "description": "Filler cheese, beef, capsicum, onion",
    "prices": [
      {
        "size_label": null,
        "price": 359.0
      }
    ]
  },
  {
    "category": "Sides",
    "subcategory": null,
    "name": "Peri-Peri Tacos (2pcs)",
    "description": "Filler cheese, capsicum, onion, chicken & peri-peri sauce",
    "prices": [
      {
        "size_label": null,
        "price": 329.0
      }
    ]
  },
  {
    "category": "Meatbox",
    "subcategory": null,
    "name": "Classic Meatbox",
    "description": "Chicken, sausage, fries & sauce",
    "prices": [
      {
        "size_label": null,
        "price": 329.0
      }
    ]
  },
  {
    "category": "Meatbox",
    "subcategory": null,
    "name": "Naga Meatbox",
    "description": "Chicken, chicken krunch, fries & sauce",
    "prices": [
      {
        "size_label": null,
        "price": 349.0
      }
    ]
  },
  {
    "category": "Meatbox",
    "subcategory": null,
    "name": "Twist Box",
    "description": "Chicken, chicken meat ball, chicken krunch, fries, sauce, vegetables & cheese ball",
    "prices": [
      {
        "size_label": null,
        "price": 385.0
      }
    ]
  },
  {
    "category": "Combo",
    "subcategory": null,
    "name": "Classic Box",
    "description": "1 classic burger + 1pc chicken tender + fries + 2pcs wings (any flavour of choice) + 250ml soft drink",
    "prices": [
      {
        "size_label": null,
        "price": 499.0
      }
    ]
  },
  {
    "category": "Combo",
    "subcategory": null,
    "name": "Pizza Lovers",
    "description": "1 regular NY barbecue pizza + fries + 2 soft drinks",
    "prices": [
      {
        "size_label": null,
        "price": 549.0
      }
    ]
  },
  {
    "category": "Combo",
    "subcategory": null,
    "name": "Mama Treat",
    "description": "1 regular pizza, 8pcs chicken strips, 8pcs wings, 4 soft drinks",
    "prices": [
      {
        "size_label": null,
        "price": 1199.0
      }
    ]
  },
  {
    "category": "Shakes & Dessert",
    "subcategory": "Shakes",
    "name": "Lemon Krusher",
    "description": null,
    "prices": [
      {
        "size_label": "Price 1",
        "price": 109.0
      },
      {
        "size_label": "Price 2",
        "price": 139.0
      }
    ]
  },
  {
    "category": "Shakes & Dessert",
    "subcategory": "Shakes",
    "name": "Chocolate Krusher",
    "description": null,
    "prices": [
      {
        "size_label": "Price 1",
        "price": 145.0
      },
      {
        "size_label": "Price 2",
        "price": 219.0
      }
    ]
  },
  {
    "category": "Shakes & Dessert",
    "subcategory": "Shakes",
    "name": "Strawberry",
    "description": null,
    "prices": [
      {
        "size_label": "Price 1",
        "price": 145.0
      },
      {
        "size_label": "Price 2",
        "price": 219.0
      }
    ]
  },
  {
    "category": "Shakes & Dessert",
    "subcategory": "Shakes",
    "name": "Peanut Butter",
    "description": null,
    "prices": [
      {
        "size_label": "Price 1",
        "price": 199.0
      },
      {
        "size_label": "Price 2",
        "price": 249.0
      }
    ]
  },
  {
    "category": "Shakes & Dessert",
    "subcategory": "Shakes",
    "name": "Goa Lemonade",
    "description": null,
    "prices": [
      {
        "size_label": "Price 1",
        "price": 149.0
      },
      {
        "size_label": "Price 2",
        "price": 219.0
      }
    ]
  },
  {
    "category": "Shakes & Dessert",
    "subcategory": "Dessert",
    "name": "Chocolate Lava",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 165.0
      }
    ]
  },
  {
    "category": "Beverages",
    "subcategory": null,
    "name": "Water",
    "description": null,
    "prices": [
      {
        "size_label": "At MRP",
        "price": 0.0
      }
    ]
  },
  {
    "category": "Beverages",
    "subcategory": null,
    "name": "Beverage (Fountain)",
    "description": null,
    "prices": [
      {
        "size_label": null,
        "price": 65.0
      }
    ]
  }
]
JSON, true, 512, JSON_THROW_ON_ERROR);

        $pizzaVariationAddons = json_decode(<<<'JSON'
[
  {
    "name": "Crust(Pan Crust / Hand Toss / Thin Crust / Italian Style)",
    "description": null,
    "prices": [
      0.0,
      0.0,
      0.0
    ]
  },
  {
    "name": "Crust(Sausage Crust)",
    "description": null,
    "prices": [
      110.0,
      169.0,
      219.0
    ]
  },
  {
    "name": "Crust(Cheese Burst Crust)",
    "description": null,
    "prices": [
      199.0,
      249.0,
      345.0
    ]
  },
  {
    "name": "Extra Toppings(Cheese)",
    "description": null,
    "prices": [
      125.0,
      189.0,
      249.0
    ]
  },
  {
    "name": "Extra Toppings(Veg Toppings (Green Capsicum, Onion, Tomato, Mushrooms))",
    "description": null,
    "prices": [
      75.0,
      99.0,
      189.0
    ]
  },
  {
    "name": "Extra Toppings(Meat Toppings (Minced Beef, Grilled Chicken Rasher, Chicken Sausage, Beef Pepperoni))",
    "description": null,
    "prices": [
      125.0,
      185.0,
      235.0
    ]
  }
]
JSON, true, 512, JSON_THROW_ON_ERROR);

        DB::transaction(function () use ($categories, $subcategories, $globalAddons, $globalAddonTargets, $menuItems, $pizzaVariationAddons): void {
            $this->clearCatalog();
            $now = now();

            $categoryIds = [];
            foreach ($categories as $row) {
                $categoryIds[$row['name']] = DB::table('categories')->insertGetId([
                    'name' => $row['name'],
                    'slug' => Str::slug($row['name']),
                    'image' => null,
                    'description' => null,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $subcategoryIds = [];
            foreach ($subcategories as $row) {
                $categoryId = $categoryIds[$row['category']] ?? null;
                if (! $categoryId) {
                    throw new RuntimeException('Missing category for subcategory: '.$row['name']);
                }

                $key = $row['category'].'|'.$row['name'];
                $subcategoryIds[$key] = DB::table('subcategories')->insertGetId([
                    'category_id' => $categoryId,
                    'name' => $row['name'],
                    'slug' => Str::slug($row['name']),
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Keep every Add-Ons sheet row as a distinct global addon, including the two Cheese rows with different prices.
            $globalAddonIds = [];
            foreach ($globalAddons as $row) {
                $addonKey = $row['name'].'|'.rtrim(rtrim(number_format((float) $row['price'], 2, '.', ''), '0'), '.');
                $globalAddonIds[$addonKey] = DB::table('addons')->insertGetId([
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Seeded foods must be available at all existing non-deleted branches.
            $branchQuery = DB::table('branches');
            if (Schema::hasColumn('branches', 'deleted_at')) {
                $branchQuery->whereNull('deleted_at');
            }
            $branchIds = $branchQuery->pluck('id')->all();

            $usedMenuSlugs = [];
            foreach ($menuItems as $menuRow) {
                $categoryId = $categoryIds[$menuRow['category']] ?? null;
                if (! $categoryId) {
                    throw new RuntimeException('Missing category for menu item: '.$menuRow['name']);
                }

                $subcategoryId = null;
                if (! empty($menuRow['subcategory'])) {
                    $subKey = $menuRow['category'].'|'.$menuRow['subcategory'];
                    $subcategoryId = $subcategoryIds[$subKey] ?? null;
                    if (! $subcategoryId) {
                        throw new RuntimeException('Missing subcategory for menu item: '.$menuRow['name']);
                    }
                }

                $menuItemId = DB::table('menu_items')->insertGetId([
                    'category_id' => $categoryId,
                    'subcategory_id' => $subcategoryId,
                    'name' => $menuRow['name'],
                    'slug' => $this->uniqueSlug(Str::slug($menuRow['name']), $usedMenuSlugs),
                    'description' => $menuRow['description'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                foreach ($menuRow['prices'] as $priceIndex => $priceRow) {
                    $priceInsert = [
                        'menu_item_id' => $menuItemId,
                        'size_label' => $priceRow['size_label'],
                        'price' => $priceRow['price'],
                        'sort_order' => $priceIndex,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    if (Schema::hasColumn('menu_item_prices', 'discount_price')) {
                        $priceInsert['discount_price'] = null;
                    }

                    $priceId = DB::table('menu_item_prices')->insertGetId($priceInsert);

                    // Every Add-Ons-Pizza row is added to every Pizza, separately for Regular/Medium/Large prices.
                    if ($menuRow['category'] === 'Pizza') {
                        foreach ($pizzaVariationAddons as $variationIndex => $variationRow) {
                            $variationPrice = $variationRow['prices'][$priceIndex] ?? null;
                            if ($variationPrice === null) {
                                continue;
                            }

                            DB::table('menu_item_price_addons')->insert([
                                'menu_item_price_id' => $priceId,
                                'name' => $variationRow['name'],
                                'description' => $variationRow['description'],
                                'price' => $variationPrice,
                                'sort_order' => $variationIndex,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]);
                        }
                    }
                }

                // Attach only the reviewed global addons that are relevant to this menu item.
                $addonPivotRows = [];
                foreach ($globalAddonTargets as $addonKey => $targetMenuNames) {
                    if (! in_array($menuRow['name'], $targetMenuNames, true)) {
                        continue;
                    }

                    $addonId = $globalAddonIds[$addonKey] ?? null;
                    if (! $addonId) {
                        throw new RuntimeException('Missing global addon mapping: '.$addonKey);
                    }

                    $addonPivotRows[] = [
                        'addon_id' => $addonId,
                        'menu_item_id' => $menuItemId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                if (! empty($addonPivotRows)) {
                    DB::table('addon_menu_item')->insert($addonPivotRows);
                }

                if (! empty($branchIds)) {
                    $branchPivotRows = [];
                    foreach ($branchIds as $branchId) {
                        $branchPivotRows[] = [
                            'branch_id' => $branchId,
                            'menu_item_id' => $menuItemId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                    DB::table('branch_menu_item')->insert($branchPivotRows);
                }

                // Do not insert menu_item_images rows: existing website/admin placeholder/default image logic stays active.
            }
        });
    }

    private function assertBaseSchema(): void
    {
        $required = [
            'categories', 'subcategories', 'addons', 'menu_items', 'menu_item_prices',
            'addon_menu_item', 'branch_menu_item', 'branches',
        ];

        foreach ($required as $table) {
            if (! Schema::hasTable($table)) {
                throw new RuntimeException('Required table is missing: '.$table);
            }
        }
    }

    /**
     * The user requested a single seeder command. This makes the variation-addon storage
     * backward-compatible even when the previous variation-addon migration was not run.
     */
    private function ensureVariationAddonSchema(): void
    {
        if (! Schema::hasTable('menu_item_price_addons')) {
            Schema::create('menu_item_price_addons', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('menu_item_price_id')->constrained('menu_item_prices')->cascadeOnDelete();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 12, 2)->default(0);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        } elseif (! Schema::hasColumn('menu_item_price_addons', 'description')) {
            Schema::table('menu_item_price_addons', function (Blueprint $table): void {
                $table->text('description')->nullable()->after('name');
            });
        }

        // Keep order history support compatible with the variation-addon implementation.
        if (Schema::hasTable('order_item_addons') && ! Schema::hasColumn('order_item_addons', 'menu_item_price_addon_id')) {
            Schema::table('order_item_addons', function (Blueprint $table): void {
                $table->foreignId('menu_item_price_addon_id')
                    ->nullable()
                    ->after('addon_id')
                    ->constrained('menu_item_price_addons')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Hard-delete only the catalog requested by the Excel import.
     * Existing orders are retained; their menu/addon snapshot fields remain intact while nullable FKs become null.
     */
    /**
     * Keep slugs unique even when Excel names differ only by punctuation
     * (for example Peri Peri Chicken and Peri-Peri Chicken).
     *
     * @param array<string, bool> $used
     */
    private function uniqueSlug(string $base, array &$used): string
    {
        $base = $base !== '' ? $base : 'menu-item';
        $slug = $base;
        $suffix = 2;

        while (isset($used[$slug])) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        $used[$slug] = true;

        return $slug;
    }

    private function clearCatalog(): void
    {
        DB::table('addon_menu_item')->delete();
        DB::table('branch_menu_item')->delete();

        // menu_items cascades to menu_item_prices, menu_item_price_addons and menu_item_images.
        DB::table('menu_items')->delete();
        DB::table('addons')->delete();
        DB::table('subcategories')->delete();
        DB::table('categories')->delete();
    }
}
