# Nightingale Shop - E-Commerce Platform

## Overview
Nightingale Shop is a modern e-commerce platform built using Laravel PHP framework. This platform provides a seamless shopping experience for users and includes robust features for administrators and vendors to manage the online store efficiently.

## Table of Contents
- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Screenshots](#screenshots)
- [Technologies Used](#technologies-used)
- [License](#license)

## Features
- **User Authentication:** Secure registration and login for users.
- **Product Management:** Add, edit, delete, and view products.
- **Shopping Cart:** Add products to the cart, update quantities, and proceed to checkout.
- **Order Management:** Users can view order history, and admins can manage orders.
- **Search and Filter:** Users can search for products and apply filters.
- **Responsive Design:** Mobile-friendly interface using Bootstrap.
- **Payment Integration:** (Placeholder for payment gateway details).

## Installation
Follow these steps to set up the project locally:

1. Clone the repository:
   ```bash
   git clone https://github.com/frknslck/ip-2.git
   cd ip-2
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Set up the environment file:
   ```bash
   cp .env.example .env
   ```
   Configure the `.env` file with your database, mail, and other settings.

4. Generate the application key:
   ```bash
   php artisan key:generate
   ```

5. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

6. Start the development server:
   ```bash
   php artisan serve
   ```
   Visit `http://127.0.0.1:8000` in your browser.

## Configuration
- **Environment Variables:** Ensure all necessary variables in `.env` are set correctly (e.g., database credentials, mail configurations).
- **Storage Link:** Create a symbolic link for file storage:
   ```bash
   php artisan storage:link
   ```

## Usage
- Navigate to the admin panel at `/admin` to manage products, categories, and orders.
- Users can browse products, add them to the cart, and checkout.

## Screenshots
(Add screenshots here to showcase your project. Use Markdown image syntax.)

Example:
```
![Home Page](screenshots/home_page.png)
![Product Page](screenshots/product_page.png)
```

## Technologies Used
- **Backend:** Laravel 10
- **Frontend:** Bootstrap 5, Blade Templates
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **Version Control:** Git

## License
This project is licensed under the MIT License. See the LICENSE file for more details.

