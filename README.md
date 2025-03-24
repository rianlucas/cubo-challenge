
# Cubo Challenge



## Requirements

- Docker
- Docker compose
## Run Locally

Clone the project

```bash
  git clone https://github.com/rianlucas/cubo-challenge.git
```

Go to the project directory

```bash
  cd my-project
```

Copy and set env variables

```bash
cp ./api/.env.example ./api/.env
```

Run Docker

```bash
  docker compose up --build
```


Generate key (inside docker container)
```
php artisan key:generate
```

## Running Tests

To run tests, run the following command


```bash
  php artisan test
```

*obs: The tests must be executed inside the laravel_api Docker container*