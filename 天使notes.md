
ISSUE

In cart, change shows negative output, leading to confusing total payment change

INFORMATION

Trigger low stocks notifications:
Run this in tinker

$controller = app(\App\Http\Controllers\StockController::class);
\App\Models\Product::chunk(200, function($products) use($controller) {
    foreach ($products as $p) {
        $controller->checkAndNotifyProductStock($p);
    }
});


JSON Format for adding customizations for products in takeOrder

Milktea
{
  "groups": [
    {
      "key": "size",
      "label": "Size",
      "type": "single",
      "required": true,
      "choices": [
        { "key": "s", "label": "Small", "price": 39 },
        { "key": "m", "label": "Medium", "price": 49 },
        { "key": "l", "label": "Large", "price": 59 }
      ]
    },
    {
      "key": "toppings",
      "label": "Add-ons",
      "type": "multiple",
      "choices": [
        { "key": "pearls", "label": "Tapioca Pearls", "price": 20 },
        { "key": "cheese", "label": "Cheese Foam", "price": 25 }
      ]
    }
  ]
}

Frappe (12oz / 16oz):
{
  "groups": [
    {
      "key": "size",
      "label": "Size (oz)",
      "type": "single",
      "required": true,
      "choices": [
        { "key": "12oz", "label": "12oz", "price": 99 },
        { "key": "16oz", "label": "16oz", "price": 109 }
      ]
    },
    {
      "key": "toppings",
      "label": "Add-ons",
      "type": "multiple",
      "choices": [
        { "key": "whip", "label": "Whipped Cream", "price": 10 },
        { "key": "shot", "label": "Extra Shot", "price": 15 }
      ]
    }
  ]
}

Snack (no sizes — quantity only; keep modal notes):
{
  "groups": [
    {
      "key": "note",
      "label": "Special note",
      "type": "text",
      "placeholder": "Cut in half / extra sauce..."
    }
  ]
}

Simple product with one price tier (base price fallback will also work):
{
  "groups": [
    {
      "key": "size",
      "label": "Size",
      "type": "single",
      "choices": [
        { "key": "default", "label": "Regular", "price": 75 }
      ]
    }
  ]
}

