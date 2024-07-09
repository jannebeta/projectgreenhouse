# Project Greenhouse

Written with mostly PHP and Java. Measures enviroment values from outdoor and in greenhouse.

1) Send Measurements JavaTool

Running on Raspberry Pi 2 Model B. Mainly running hourly via cronjobs. 
Project using DHT 11 sensor (in greenhouse) for temperature and humidity AND BMP280 (outdoor) for temperature and pressure.
Sending data to API which adds measurement data to database (SQL).

2) Project Greenhouse Website

Displays measurements results from database. And allow display from-to results with ordering. Also show last measurement and average values.

Site: https://kasvihuone.ankkaverkko.com
