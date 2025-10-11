<?php
return [
  'alert_recipients' => env('INVENTORY_ALERT_RECIPIENTS'),
  'alert_cooldown_hours' => env('INVENTORY_ALERT_COOLDOWN_HOURS', 24),
];