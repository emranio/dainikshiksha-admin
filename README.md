## Installation

```bash
  git clone https://github.com/emranio/dainikshiksha-admin
```

Go to your project directory
setup your .env file
Then move auth.json file in your .composer directory
example:

```bash
mv auth.json ~/.composer/auth.json
```

next paste following command in your terminal

```bash
composer install --ignore-platform-reqs 
```

```bash
npm install
```

```bash
npm run build
```

```bash
php artisan migrate
```

```bash
php artisan db:seed
```

To clear cache
```bash
php artisan optimize:clear
```
