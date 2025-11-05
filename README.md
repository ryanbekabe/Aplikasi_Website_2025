# Fresh News Website - HanyaJasa.Com - hanyajasa@gmail.com

A modern news website built with PHP and MySQL.

## Features

- Responsive design using Bootstrap 5
- News articles with categories
- Admin panel for managing content
- Clean and fresh UI/UX design
- Mobile-friendly layout

## Installation

1. Place the files in your web server directory (e.g., htdocs folder in XAMPP)
2. Make sure Apache and MySQL services are running
3. Run the setup script by visiting `http://localhost/Website_Berita/setup.php` in your browser
4. Access the website at `http://localhost/Website_Berita/`

## Admin Panel

- Access the admin panel at `http://localhost/Website_Berita/admin/`
- Login with username: `admin` and password: `admin123`
- From the admin panel, you can:
  - Add new news articles
  - Edit existing articles
  - Delete articles
  - Manage categories

## File Structure

```
Website_Berita/
├── admin/
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── add_news.php
│   ├── edit_news.php
│   └── delete_news.php
├── assets/
│   └── images/
├── css/
│   └── style.css
├── includes/
│   ├── config.php
│   └── database.sql
├── js/
├── about.php
├── category.php
├── contact.php
├── index.php
├── news_detail.php
├── setup.php
└── README.md
```

## Database Structure

The website uses two main tables:

1. `categories` - Stores news categories
2. `news` - Stores news articles with foreign key to categories

## Technologies Used

- PHP 7.4+
- MySQL
- Bootstrap 5
- Font Awesome 6

## Customization

You can customize the website by modifying:
- `css/style.css` - Custom styles
- `includes/config.php` - Database configuration
- Template files (`index.php`, `news_detail.php`, etc.) - Page layouts

## License

This project is open source and available under the MIT License.