import board
import neopixel as np

lightPin = board.D18
numLights = 198
order = np.GRB

pixels = np.NeoPixel(lightPin, numLights, brightness=0.5, auto_write=False, pixel_order=order)

pixels.fill((0,0,0))

pixels.show()
