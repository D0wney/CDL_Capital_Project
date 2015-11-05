# Alexander Lee
# cs337 Final Project
# 4/1/15
'''
This program is my final project for cs337 (Performance Modeling in Computing). The program simulates the average fair price of the European Call Option and compares it to the Black-Scholes Formula price. The program also simulates the average fair price of an Asian Call Option, and uses the European Call Option and Black-Scholes Formula as a control variate to reduce the variance of the Asian Call Option fair price.
'''

import sys
import numpy as np
import math
import random

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
    for e in range(len(data)):
        var = var + (data[e]-avg)**2
    var = var/len(data)
    return math.sqrt(var)

def erfcc(x):
    """Complementary error function."""
    z = abs(x)
    t = 1. / (1. + 0.5*z)
    r = t * math.exp(-z*z-1.26551223+t*(1.00002368+t*(.37409196+
    	t*(.09678418+t*(-.18628806+t*(.27886807+
    	t*(-1.13520398+t*(1.48851587+t*(-.82215223+
    	t*.17087277)))))))))
    if (x >= 0.):
    	return r
    else:
    	return 2. - r

def normcdf(x, mu, sigma):
    t = x-mu;
    y = 0.5*erfcc(-t/(sigma*math.sqrt(2.0)));
    if y>1.0:
        y = 1.0;
    return y

def compute_mean_CI(data):
    average = avg(data)
    std = sd(average,data)
    m = (std/(math.sqrt(len(data))))
    h = (1.96*m)
    return average, average-h, average+h, h

def asian_euro_common_random(sd, init_price, r, K, m):
    l=[]
    l.append(init_price)
    for i in range(1,m+1):
        Z=random.gauss(0,1)
        s=(l[i-1])*math.exp((r-((sd**2)/2.0))*float(1/250.0)+(sd*(math.sqrt(float(1/250.0)))*Z))
        l.append(s)
    X=(l[m]-K)
    if X<0.0:
        X=0.0
    Y=((sum(l)/len(l))-K)
    if Y<0.0:
        Y=0.0
    return ((math.exp((-r)*m/250))*Y), ((math.exp((-r)*m/250))*X)

def european_option(sd, init_price, r, K, m):
    d1=((math.log(init_price/K))+(r+(sd*sd/2))*(float(m)/250))*(1/(sd*math.sqrt(float(m)/250)))
    d2=d1-(sd*math.sqrt(float(m)/250))
    n1 = normcdf(d1,0,1)
    n2 = normcdf(d2,0,1)
    return (init_price*n1) - ((K*math.exp(-r*(float(m)/250)))*n2)

def simulate1(trials,sd, St, K, r, m):
    fp_a=[]
    fp_e=[]
    for x in range(trials):
        a, e = asian_euro_common_random(sd, St, r, K, m)
        fp_a.append(a)
        fp_e.append(e)
    return (fp_a), (fp_e)

def get_c(a,e):
    ctemp=-(np.cov(a, e))/(np.var(e))
    return ctemp[0][1]
    
def simulate2(trials,c,n,sd, St, K, r, m):
    f=[]
    for x in range(trials):
        a, e = asian_euro_common_random(sd, St, r, K, m)
        p=a+c*(e-n)
        if p < 0.0:
            p = 0.0
        f.append(p)
    return f

def main():
    trials = int(sys.argv[1])
    sd=.45 # standard deviation
    St=644.0 # starting price
    K=490.0 # strike price
    r=0.0003 # riskless rate
    m=250 # days
    
    # Run a simulation without the control variate or command random numbers
    a, e = simulate1(trials, sd, St, K, r, m)
    print("First Simulation: (Mean CI HW)")
    print(compute_mean_CI(a))
    print(compute_mean_CI(e))
    
    # Run a simulation with the control variate
    n=european_option(sd, St, r, K, m)
    print(n)
    c=get_c(a,e)
    f=simulate2(trials,c,n,sd, St, K, r, m)
    print("Second Simulation: (Mean CI HW)")
    print(compute_mean_CI(f))
    #print(n)
    
    #plt.plot(f,color='red')
    #plt.plot(e,color='blue')
    #plt.show()
    
if __name__ == "__main__":
    main()
