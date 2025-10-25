
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
Add an Account

$user = App\Models\User::factory()->create([
    'name' => 'Admin Sample',
    'email' => 'admin@gmail.com',
    'usertype' => 'admin',
    'password' => Hash::make('12345678'),
]);

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
NOTES

Trigger low stocks notifications:
Run this in tinker

$controller = app(\App\Http\Controllers\StockController::class);
\App\Models\Product::chunk(200, function($products) use($controller) {
    foreach ($products as $p) {
        $controller->checkAndNotifyProductStock($p);
    }
});

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
HOW TO CALL FORMATTED EMAIL 

<?php
use App\Mail\FormattedMail;
use Illuminate\Support\Facades\Mail;

$subject = 'Inventory alert';
$bodyHtml = '<p>Product <strong>Milk</strong> is low in stock.</p><p><a class="btn" href="https://example.com">View product</a></p>';
Mail::to('user@example.com')->send(new FormattedMail($subject, $bodyHtml));



# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
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


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
## How to Pull in Domain

Connect to droplet in PuTTy

# go to project
cd /var/www/librewhan

# 0) ensure you're the right user & repo owner (if you run git as 'atesh')
sudo chown -R atesh:atesh /var/www/librewhan   # one-time if needed

# 1) show current commit & stash local changes if any
git status --porcelain
git rev-parse --abbrev-ref HEAD
git log -1 --oneline
git stash push -m "wip before deploy-$(date +%F-%T)" || true

# 2) fetch & preview what will change (review before applying)
git fetch origin
git --no-pager log --oneline HEAD..origin/main   # see new commits
git --no-pager diff --name-status HEAD..origin/main   # see which files

# 3) Optional: BACKUP DB if changes include migrations or data is important
mkdir -p /root/backups
mysqldump -u tenshi -p'072103' kalibrewhan > /root/backups/kalibrewhan-$(date +%F-%T).sql

# 4) Put the app into maintenance mode (prevents user writes during migrate)
php artisan down --message="Maintenance: deploying updates"

# 5) Pull code (fast-forward or hard reset for full sync)
git pull --ff-only origin main || (git fetch --all && git reset --hard origin/main)

# 6) If composer.json or composer.lock changed, install/update PHP deps
if git diff --name-only HEAD@{1} HEAD | egrep -q 'composer.json|composer.lock'; then
  composer install --no-dev --optimize-autoloader
fi

# 7) If front-end assets changed and you build on server:
if [ -f package.json ] && git diff --name-only HEAD@{1} HEAD | egrep -q 'package.json|vite|resources/|assets/'; then
  npm ci
  npm run build
fi

# 8) Run migrations if included (you already backed up DB)
php artisan migrate --force

# 9) Clear & warm caches
php artisan config:clear
php artisan route:clear   # only run route:cache if you fixed duplicate route names
php artisan config:cache || true
php artisan view:cache || true

# 10) Bring app back up
php artisan up

# 11) Restart services & workers if used
sudo systemctl reload php8.2-fpm nginx
sudo supervisorctl restart all || true

# 12) Quick smoke test and logs
curl -I http://127.0.0.1
sudo tail -n 80 /var/www/librewhan/storage/logs/laravel.log
sudo tail -n 80 /var/log/nginx/error.log

# Precautions — what to always remember

Never push .env to Git. Keep production secrets on the server only.

Backup DB before running migrations or any destructive DB change. A quick mysqldump is fast and lifesaving.

Use maintenance mode while migrating — avoids partial writes and user confusion.

Check diffs first (git log/git diff) — this tells you what to expect (migrations, composer changes, assets).

If composer OOMs, create swap (sudo fallocate -l 2G /swapfile ...) or run composer locally and upload vendor/.

Route caching warning: php artisan route:cache fails if your code uses route closures or duplicate names. Use route:clear if it breaks.

Ownership/Permissions: ensure the repo files are owned by the user you pull with (atesh) or add safe.directory in git config. Prefer chown to safe.directory workaround.

Small, frequent changes > big merges — easier to debug and rollback.

Quick rollback options (if something breaks)

Undo code to previous commit:


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# SSL Certificate

Renew
sudo certbot renew --dry-run


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# Expenses

Digital Ocean
After Payment Method: ₱812.20
Namecheap Domain Name: ₱69
Mailing Service: 


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# Pull Changes, safe ordered list to run

A — Quick one-off (copy–paste and run now)

Run these commands from the droplet (replace main with your branch if needed). They will safely stash local changes, pull, install composer deps, run migrations, refresh caches and fix permissions.

# 0. variables (edit if different)
APP_DIR="/var/www/librewhan"
GIT_BRANCH="main"
WEB_USER="www-data"
REMOTE="origin"

# 1. go to app
cd "$APP_DIR" || { echo "ERROR: $APP_DIR not found"; exit 1; }

# 2. fix 'dubious ownership' error if Git complains
#    this makes Git trust this repo path for the current system user
git config --global --add safe.directory "$APP_DIR" || true

# 3. check status & stash any local work safely
git fetch "$REMOTE" --prune || true
git status --porcelain
git stash push -m "auto-stash pre-deploy $(date +%F-%T)" || true

# 4. pull latest (fast-forward preferred). If it fails, we fallback to a reset.
if git pull --ff-only "$REMOTE" "$GIT_BRANCH"; then
  echo "Pulled successfully (fast-forward)."
else
  echo "Fast-forward pull failed — attempting safe reset to remote."
  git fetch "$REMOTE" --all
  git reset --hard "$REMOTE/$GIT_BRANCH"
fi

# 5. composer install (non-interactive, optimized)
#    adjust memory/env if composer needs more memory
if command -v composer >/dev/null 2>&1; then
  composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-progress
else
  echo "composer not found; please install composer first."
fi

# 6. run migrations (ONLY if you want DB changes applied)
#    If you're using migrations, run them; otherwise skip.
php artisan migrate --force || echo "Migrations failed or none to run."

# 7. clear & warm caches (avoid route:cache if you have duplicate route names)
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
# php artisan route:clear
# optionally: php artisan config:cache || true

# 8. ownership & permissions
chown -R $WEB_USER:$WEB_USER "$APP_DIR"
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

# 9. reload services
systemctl reload php8.2-fpm nginx || { systemctl restart php8.2-fpm nginx || true; }

# 10. quick smoke test & logs
echo "------------ HTTP (local) --------------"
curl -I -H "Host: librewhan.site" http://127.0.0.1 | sed -n '1,40p' || true
echo "------------ laravel log tail ---------"
tail -n 40 "$APP_DIR/storage/logs/laravel.log" || true
echo "------------ nginx error tail ---------"
tail -n 40 /var/log/nginx/librewhan.error.log 2>/dev/null || true
