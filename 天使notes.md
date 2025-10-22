
ISSUES

Stock Update button not working


/***********************************************************************************************************************/
NOTES

Trigger low stocks notifications:
Run this in tinker

$controller = app(\App\Http\Controllers\StockController::class);
\App\Models\Product::chunk(200, function($products) use($controller) {
    foreach ($products as $p) {
        $controller->checkAndNotifyProductStock($p);
    }
});


/***********************************************************************************************************************/
HOW TO CALL FORMATTED EMAIL 

<?php
use App\Mail\FormattedMail;
use Illuminate\Support\Facades\Mail;

$subject = 'Inventory alert';
$bodyHtml = '<p>Product <strong>Milk</strong> is low in stock.</p><p><a class="btn" href="https://example.com">View product</a></p>';
Mail::to('user@example.com')->send(new FormattedMail($subject, $bodyHtml));



/***********************************************************************************************************************/
JSON Format for adding customizations for products in takeOrder, put in description column

For Milktea
/**/
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
/**/

For Frappe (12oz / 16oz):
/**/
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
/**/

For Snack (no sizes — quantity only; keep modal notes):
/**/
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
/**/

For Simple product with one price tier (base price fallback will also work):
/**/
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
/**/


/***********************************************************************************************************************/
How to Pull in Domain

Connect to droplet in PuTTy

cd /var/www/librewhan





/***********************************************************************************************************************/
SSL Certificate

Renew
sudo certbot renew --dry-run


/***********************************************************************************************************************/
Expenses

Digital Ocean
After Payment Method: ₱812.20

Namecheap Domain Name: ₱69