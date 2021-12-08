# BP-youtube-scraper
Simple scraper-type application built on Symfony 6. Comes with a Symfony CLI command to use as a cronjob and a simple UI to compare and search for video' first hour perfomance.

## What was used
PHP 8.0.13, Symfony 6, MySQL 8

### Installing
```
git clone https://github.com/mkalinauskas/bp-youtube-scraper.git
cd bp-youtube-scraper
composer install
```
Set up your environments variables in .env (DATABASE_URL and GOOGLE_API_KEY).
If needed, create your api key here: https://console.cloud.google.com/apis/credentials

Run:
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Scraping
Run this command to fetch Youtube channel data (videos, tags, statistics):
```
php bin/console app:scrape-youtube-channel channel_id
```
Creates Channel, Video entities on the first run and on every other run adds a new video if it has been uploaded to channel since. Statistics are added on every run.
