# Laravel Job Scraper

## Overview
This application is a Laravel-based backend for managing scraping jobs. Users can create scraping jobs by providing URLs and CSS selectors, retrieve job statuses, and delete jobs. The application utilizes Redis for data storage and Laravel queues for background processing.

## Requirements
- PHP 8.2 or higher
- Composer
- Docker and Docker Compose
- Redis

# Installation
1. Clone the repository:
   ```
   git clone <repository-url>
   cd <repository-directory>
   ```

2. Create a .env file:
   ```
   cp .env.example .env
   ```
   Copy the .env.example to .env and configure your environment variables as needed.

3. Build and run the application using Docker:

   Make sure you have Docker installed. Run the following command to build and start the containers:
   ```
   docker-compose up --build
   ```

4. Install Composer dependencies:

   Access the PHP container and install dependencies:
   ```
   docker-compose exec app composer install
   ```

5. Run migrations (optional):

   If you decide to use a database (not mandatory as Redis is used), run the migrations:
   ```
   docker-compose exec app php artisan migrate
   ```

## Usage
### Create a Job
Endpoint: `POST /api/jobs`

- Request Body:
  ```
  {
  "urls": ["http://example.com"],
  "selectors": [".title"]
  }
  ```
- Response:
  ```
  {
  "id": "generated_job_id"
  }
  ```

### Retrieve Job by ID
Endpoint: `GET /api/jobs/{id}`

- Response:
  ```
  {
  "id": "generated_job_id",
  "urls": ["http://example.com"],
  "selectors": [".title"],
  "status": "completed",
  "scraped_data": {
    "http://example.com": ["Title 1", "Title 2"]
  }
  }
  ```

### Delete Job by ID
Endpoint: `DELETE /api/jobs/{id}`

- Response:
  ```
  {
  "message": "Job deleted"
  }
  ```

# Queue Management

To process jobs in the background, you need to run the queue worker.
In a separate terminal window, execute the following command:
   ```
   docker-compose exec app php artisan queue:work
   ```
This command will listen for new jobs on the queue and process them as they come in.
Ensure that this command is running while you are creating jobs to see the scraping
tasks executed asynchronously.
