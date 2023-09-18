## How to setup the project in local/test environment

1. Make sure the the prerequisites for a Laravel project is already fullfiled in the machine
 - For this task, docker containers using Laravel Sail was used in local dev environment
2. Clone the repo
3. Run "composer install" command
4. Fill the values in .env file. If file is already not present, then copy the contents of .env.example and create the new file in the same directory 
4. Run "php artisan migrate" command to generate the Database tables
5. There are 2 apis
 - 1. To create a city, use the below
  curl --location --request POST 'http://localhost/api/cities' \
    --header 'Content-Type: application/json' \
    --header 'Accept: application/json' \
    --data '{
        "name": "Mumbai",
        "country": "IN"
    }'
 - 2. To get the weather forecast for a city, use the below
    curl --location --request GET 'http://localhost/api/forecast?city_id=1' \
    --header 'Accept: application/json' \
    --header 'Content-Type: application/json'


## Possible Improvements

- Currently, the application accepts duplicate city names. However, it will be difficult to identify the exact result data from open weather api which identifies user's desired city coordinates. Because, the open weather api returns multiple records if there are multiple cities with the same name (and country too). Since the application is designed to latitude and longitude based forecasting, it be better to have another optimal stragey to handle duplicate cities.
- Applications currently accepts only city_id as an query string param to identify the city for which forecasting is needed. This works if the api consumer has knowldge of the city_id. However, it be more convenient if we allow the api consumer to send city name or other similar city related data. With more time investment, a better system can be designed where more city identifiers will be accepted in the forecast api.
- Currently authentication is not present in the api. In real world though, having authentication is almost always needed. We can add authentication to it
- Add rate limiter