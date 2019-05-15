# Estismail api helper

### Init class
```php
require 'estismail.php';

use Estismail\Estismail;

$estismail = Estismail::init('api_key');
```

### Add email to list

```php
//required fields for this method
$fields = [
    'list_id' => 11111,
    'email' => 'admin@admin.ru',
];

//return email id inserted
$email_id = $estismail->addListEmail($fields);
```

### Get emails from list id
```php
//required fields for this method
$fields = [
    'list_id' => 11111
];

$response = $estismail->getListEmails($fields);
```