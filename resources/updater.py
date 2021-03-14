# This script updates the light strip from a json file

# be sure to install the library - see link for details
# https://learn.adafruit.com/neopixels-on-raspberry-pi/python-usage

import board
import neopixel as np
import sys
import json

pin = board.D18
numLights = int(sys.argv[1])

# define pixels
order = np.GRB
pixels = np.NeoPixel(pin, numLights, brightness=0.5, auto_write=False, pixel_order=order)

# file to read from
#filename = './resources/lights.txt'
filename = sys.argv[2]

# read from that file
f = open(filename, 'r')
# load data to json object
jsonData = json.load(f)
# initialize color arrays
r = [0] * numLights
g = [0] * numLights
b = [0] * numLights
lightList = [0] * numLights

# fill color arrays
for key, val in jsonData.items():
  i = int(key[9:])
  r[i] = int(val[0:2], 16) # r is first two chars of hex code
  g[i] = int(val[2:4], 16) # g is second two
  b[i] = int(val[4:6], 16) # b is third two
  #lightList[i] = (r[i], g[i], b[i]) # define light objects to get sucked into pixel strand
  pixels[i] = (g[i],r[i],b[i])

#print(lightList)

#print("Showing "+str(numLights)+" lights.")
pixels.show()
