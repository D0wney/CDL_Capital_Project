import sys
import json
import os
import math

data =  sys.argv[1]
#data = sys.argv[2]
output = []
output.append(data)
out = json.dumps(output)
print(out)

'''
#get json data in the format json_data[closing_prices][dates]
json_data = json.loads(sys.argv[1])
expiration_time = json.loads(sys.argv[2]))
strike_price = json.loads(sys.argv[3]))

data = []
for i in range(len(json_data)):
    data.append(float(json_data[i][1]))

def avg(data):
    sum = 0
    for i in range(len(data)):
        sum = sum + float(data[i])
    return sum

output = []
average = avg(data)
output.append(average)
output.append(expiration_time)
out = json.dumps(output)
print(out)
'''

'''
#for loop to only get the closing prices
data = []
for i in range(len(json_data)):
    data.append(float(json_data[i][1]))

#average
def avg(data):
    sum = 0
    for i in range(len(data)):
        sum = sum + float(data[i])
    sum = sum / len(data)
    #result = json.dumps(sum)
    return sum

#standard deviation
def sd(avg, data):
    var = 0
    for e in data:
        var = var + (e-avg)**2
    var = var/len(data)
    return math.sqrt(var)

output = []
average = avg(data)
standard_deviation = sd(average, data)
output.append(average)
output.append(standard_deviation)
out = json.dumps(output)
print(out)
'''
