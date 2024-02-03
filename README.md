## Installation

Install my-project with npm

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
php artisan cache:clear
```

```bash
php artisan optimize:clear
```

```bash
npm install
```

```bash
npm run build
```

```bash
php artisan storage:link
```

```bash
php artisan migrate
```

```bash
php artisan db:seed
```
