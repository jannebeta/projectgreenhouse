# This Raspberry Pi code was developed by newbiely.com
# This Raspberry Pi code is made available for public use without any restriction
# For comprehensive instructions and wiring diagrams, please visit:
# https://newbiely.com/tutorials/raspberry-pi/raspberry-pi-dht11


import Adafruit_DHT
import time

# Set the sensor type and GPIO pin
sensor = Adafruit_DHT.DHT11
pin = 17  # Change this to the GPIO pin you used for DATA


# Try to read the temperature and humidity from the sensor
humidity, temperature = Adafruit_DHT.read_retry(sensor, pin)

# If the reading was successful, display the values on the same line
if humidity is not None and temperature is not None:
  print(f"{temperature:.1f};{humidity:.1f}")
else:
  print("SENSOR_ERROR")
