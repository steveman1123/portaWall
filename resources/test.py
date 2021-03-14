import board
import neopixel as np

lightPin = board.D18
numLights = 198
order = np.GRB

pixels = np.NeoPixel(lightPin, numLights, brightness=0.5, auto_write=False, pixel_order=order)

pixels.fill((255,255,0))

'''
for i in range(0,numLights,11):
  pixels[i] = (255,0,0)
  pixels[i+10] = (0,255,0)
'''

pixels.show()
