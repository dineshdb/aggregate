# Aggregate

## Components
### Reader
This contains a web application written in php. The app is hosted in apache/nginx and reads data from mysql/postgresql database and displays on frontend.

#### Backend api
- /login.php
   * GET		- Show login page
   * POST		- get form data, validate it and save the signed session in a cookie in client.
- /signup.php
   * GET		- Show signup page
   * POST		- get user data, validate and then save the user info in database
- /index.php
   * GET		- get user session from cookie, validate, and then serve the index page otherwise redirect to login.
- /categories.php
   * GET		- get user session from cookie, validate, and then serve the page otherwise redirect to login.
   * POST		- validate user, get form data, validate form data, create new category
   * DELETE		- validate user, get form data, validate form data, delete the category
- /feed.php		- similar to categories
- /news.php		- show only the new items since last checked.
- /archive.php	- All the items fetched and saved in the database till now except unread items.
- /later.php	- Items saved to be read later. This actually is just an api and saves [Pocket](https://getpocket.com) or [wallabag](https://wallabag.org).
- /search.php	- Search page and results.
- /settings.php

#### Frontend
The state, pages and routing is all handled from backend by apache/nginx. We include required html/css/javacript in the respective php pages.
We start with simple pages, vannilla javascript and when we see redundancy, we componentize them using pure web-components made with [lit-element](https://lit-element.polymer-project.org/) so the complexity is not there. Whenever we need some data, we fetch it directly, unlike redux like state pattern. For AJAX, we use the fetch API. Bootstrap is used for the design and layout.

### Crawler
It runs continuously or through cron jobs to pull the sitemap from each website configured. And then it compares their modified date with local cache and pulls new articles from each website. After pulling the articles, it tries to identify the category and then saves it to the database. This component is network heavy and long running.

## Getting started
The docker-compose.yml is configured with **nginx** server, **php-fpm** module and **MySQL** server. You don't need to install anything besides docker and docker-compose.
- Install ``docker`` and ``docker-compose``
- Start ``docker`` daemon.
- Run ``docker-compose up`` and the services will start after downloading required app images.
- Edit your code inside ``app-php`` and see live changes at [http://localhost:8080/](http://localhost:8080/)

## Access bash within docker
```
docker exec -it aggregator-database bash
```

## Using the bash login to the MySQL database
```
mysql -h localhost -P 3308 -u root -p example
```

## Select the database and view the table schema
```
use db;
```
```
select column_name from information_schema.columns where table_name='pages'
```
```
select column_name from information_schema.columns where table_name='subscriptions'
```

## Queries used to create tables
```
$users_create_table_query = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";
```

```
$subscriptions_create_table_query = "CREATE TABLE IF NOT EXISTS subscriptions (
    subId INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
    userId INT NOT NULL, 
    pageId INT NOT NULL, 
    FOREIGN KEY (userId) REFERENCES users(id),
    FOREIGN KEY (pageId) REFERENCES pages(pageId)
);";
```

```
$pages_create_table_query = "CREATE TABLE IF NOT EXISTS pages (
    pageId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    url VARCHAR(2083),
    title VARCHAR(255)
);";
```



