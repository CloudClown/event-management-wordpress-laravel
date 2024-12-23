﻿# wordpress-laravel

# Laravel Event Management API

This project is a Laravel-based RESTful API for managing events. It allows an admin to perform CRUD (Create, Read, Update, Delete) operations on events. The API is to be integrated into a WordPress frontend, providing a seamless experience for the site's administrators to manage events directly from the WordPress admin panel.

## API Installation

1. Clone this repository to your server.
2. Navigate to the project directory.
3. Copy `.env.example` to `.env` and configure your environment variables appropriately.
4. Run `composer install` to install the project dependencies.
5. Run `php artisan key:generate` to generate a new application key.
6. Run `php artisan migrate` to migrate your database and create the necessary tables.
7. Run `php artisan serve` to start the Laravel development server.

The API will be accessible at `http://localhost:8000/api/v1` by default.

## WordPress Plugin Installation

The corresponding WordPress plugin should be installed and activated in the WordPress backend to allow it to communicate with this API. 

Please follow the plugin installation instructions provided in the WordPress plugin directory.

## API Endpoints

Below is a list of available API endpoints for event management:

### Events

#### GET /events
- List all events.

#### GET /events/{id}
- Retrieve a specific event by ID.

#### POST /events
- Create a new event.

#### PUT /events/{id}
- Update an existing event.

#### DELETE /events/{id}
- Delete a specific event.

#### GET /events/category/{category}
- List events filtered by category.

## Response Example

The API uses JSON for all responses. Here is an example:
