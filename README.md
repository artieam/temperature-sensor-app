# temperature-sensor-app

Read and manage data from temperature sensors

<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

It is a test task application to read and manage data from temperature sensors. Sensors are sending information to your application via API #1 on a regular basis.
Your application also is also simulating sensors pull model via API #2 which returns random temperature in range of [-10, +80] Celsius.

### Built With

* [PHP 8.2.8](https://www.php.net/downloads.php#v8.2.8)



<!-- GETTING STARTED -->
## Getting Started

To run this application locally do steps.

### Prerequisites

For more convenient development, install Docker Desktop.
* [Docker](https://www.docker.com/get-started)

### Installation

1. Clone the repo
   ```bash
   git clone https://github.com/artieam/temperature-sensor-app.git
   ```
2. Run docker-compose.yml from root project
   ```bash
   docker-compose up -d 
   ```
3. Run composer install
   ```bash
   docker-compose exec app COMPOSER_ALLOW_SUPERUSER=1 composer install --optimize-autoloader
   ```
4. Run migrations
   ```bash
   docker-compose exec app php src/Console/CreateTablesMigrationCommand.php
   ```


<!-- USAGE EXAMPLES -->
## Usage
This app gives api functionality:
1. API #1: API saves received information to the implemented storage and expects a JSON object in a POST request body with the following structure
2. API #2: this API is a sensor API that simulates information read from a censor. Sensor information is expected to be random. Endpoint expects a GET request
3. API #3: Average temperature from all sensors, during last X days. X is sent as a parameter in submitted number of days range in JSON format
4. API #4: Average temperature for a particular sensor reading, in one-hour range in JSON format

### List of http requests:
- 1 
   ```bash
   curl --location 'http://127.0.0.1/api/push' \
     --header 'Content-Type: application/json' \
     --data '{
       "reading": {
         "sensor_uuid": "123e4567-e89b-12d3-a456-426655440000",
         "temperature": "211.33"
       }
     }'
   ```
- 2 
   ```bash
   curl --location 'http://127.0.0.1/sensor/read/192.168.0.11'
   ```
- 3 
   ```bash
   curl --location 'http://127.0.0.1/sensor/average/all' \
    --header 'Content-Type: application/json' \
    --data '{
      "day_range": 50
    }'
   ```
- 4
   ```bash
   curl --location 'http://127.0.0.1/sensor/average/bySensor/123e4567-e89b-12d3-a456-426655440000'
   ```

<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.


<!-- CONTACT -->
## Contact

Artem Sorokin - arteam91@gmail.com

Project Link: [https://github.com/artieam/temperature-sensor-app](https://github.com/artieam/temperature-sensor-app)
