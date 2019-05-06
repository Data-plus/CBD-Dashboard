from flask import Flask
from flask import render_template
from flask import request
from flask import jsonify
from flask import send_file

from pymongo import MongoClient
from bson import json_util, ObjectId
import json

import pandas as pd
from random import sample

# Pedestrian
import calendar  # Find Sensor information and Distance
import geopy
from geopy import distance
import time


"""
Import Required Files and Tokens
"""

mapbox_access_token = 'pk.eyJ1IjoicGx1c21nIiwiYSI6ImNqdGwxb3kxNjAwdmo0YW8xdjM4NG9zZWwifQ.Z9-QBnfpJDefBW7VzvC4mA'

df = pd.read_csv("static/data/pedestrian.csv")  # 10-5 data
#  df = pd.read_csv("static/data/pedestrian.csv")  # Full time data
df2 = pd.read_csv('static/data/sensors.csv')
df_cafe = pd.read_csv('static/data/cafe.csv')
population = pd.read_csv('static/data/population.csv')
df_office = pd.read_csv('static/data/office.csv')
on_street = pd.read_csv('static/data/on_street.csv')
off_street = pd.read_csv('static/data/off_street.csv')
df_accessible = pd.read_csv('static/data/accessible.csv')
df_gallery = pd.read_csv('static/data/gallery.csv')

# Sensor
# PC: "5cbb067ae39a5cbeb4a82260"
# Mac: "5cc683b30e00cbd80dd912ad"

"""
Databse and Flask Set up
"""

app = Flask(__name__)

MONGODB_HOST = 'localhost'
MONGODB_PORT = 27017
DBS_NAME = 'mydb'
COLLECTION_NAME = 'projects'
FIELDS = "FeatureCollection"


"""
Within Functions
"""

def within(df, target, radius, g):
    temp = []
    dist = [(geopy.distance.distance((df["Latitude"][g], df["Longitude"][g]),
                                    (target["latitude"][x], target["longitude"][x])).km) for x in range(len(target))]
    for x in range(len(target)):
        if dist[x] < radius:
            temp.append(target.iloc[x])
        else:
            pass

    return temp

def within_d(df, target, radius, g):
    distances = []
    dist = [(geopy.distance.distance((df["Latitude"][g], df["Longitude"][g]),
                                    (target["latitude"][x], target["longitude"][x])).km) for x in range(len(target))]
    for x in range(len(target)):
        if dist[x] < radius:
            distances.append(dist[x])
        else:
            pass

    return distances


def within_p(population, clicked, radius):
    temp = []
    dist = [(geopy.distance.distance((clicked["latitude"][0], clicked["longitude"][0]),
                                    (population["Latitude"][x], population["Longitude"][x])).km) for x in range(len(population))]
    for x in range(len(population)):
        if dist[x] < radius:
            temp.append(population.iloc[x])
        else:
            pass

    return temp


"""
Get Functions
"""


def get_ped_any(click, pedestrian):
    click_sensor = pd.DataFrame([{'Latitude': click[1], 'Longitude': click[0]}])

    # Find Nearest 3 sensors
    df_temp = pd.concat([pd.DataFrame(within(click_sensor, df2, 1.5, 0)).reset_index(drop=True),
                         pd.DataFrame(within_d(click_sensor, df2, 1.5, 0)).reset_index(drop=True)], axis=1)
    df_temp.rename(columns={0: 'distance'}, inplace=True)

    # Get Nearest 3 sensors
    df_temp = df_temp.sort_values('distance').iloc[0:3, :]
    df_temp = df_temp.reset_index(drop=True)

    # DF Nearest
    df_nearest = pedestrian[(pedestrian['sensor_id'] == df_temp['sensor_id'][0]) |
                            (pedestrian['sensor_id'] == df_temp['sensor_id'][1]) |
                            ((pedestrian['sensor_id'] == df_temp['sensor_id'][2]))]

    divider = ((1 / df_temp['distance'][0]) + (1 / df_temp['distance'][1]) + (1 / df_temp['distance'][2]))

    d1 = df_nearest[df_nearest['sensor_id'] == df_temp['sensor_id'][0]]['hourly_counts'] * (
            (1 / df_temp['distance'][0]) / divider)
    d2 = df_nearest[df_nearest['sensor_id'] == df_temp['sensor_id'][1]]['hourly_counts'] * (
            (1 / df_temp['distance'][1]) / divider)
    d3 = df_nearest[df_nearest['sensor_id'] == df_temp['sensor_id'][2]]['hourly_counts'] * (
            (1 / df_temp['distance'][2]) / divider)

    ## Change sensor
    sensor_interest = pd.concat([pd.DataFrame(
        df_nearest[df_nearest['sensor_id'] == df_temp['sensor_id'][0]]['date_time'].reset_index(drop=True)),
                                 pd.DataFrame({'hourly_counts': (d1.values + d2.values + d3.values).round()})], axis=1)

    ## Change sensor
    sensor_interest['latitude'] = click_sensor['Latitude'][0]
    sensor_interest['longitude'] = click_sensor['Longitude'][0]

    my_day_list = []

    sensor_interest['date_time'] = pd.to_datetime(sensor_interest['date_time'])

    for i in range(len(sensor_interest)):
        my_date = sensor_interest['date_time'][i]
        my_day = calendar.day_name[my_date.weekday()]
        my_day_list.append(my_day)

    sensor_interest['Day'] = my_day_list

    return sensor_interest


def for_eachday(df):
    each = []
    day_list = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']

    # Change time to int
    time = df['date_time'].apply(lambda x: int(x.strftime('%H')))
    df['time'] = time

    # Loop over days
    for i in range(7):
        # 10AM - 5PM
        dday = round(df[(df['Day'] == day_list[i]) & (df['time'] < 18) & (df['time'] > 9)]['hourly_counts'].mean())
        each.append(dday)

    return each


def get_residential(click, population):
    clicked = pd.DataFrame([{'latitude': click[1], 'longitude': click[0]}])
    filtered = pd.DataFrame(within_p(population, clicked, 0.5))
    if len(filtered) > 0:
        household = filtered['Household'].sum()
        return household
    else:
        household = 0
        return household


def get_cafe(click, df_cafe):
    clicked = pd.DataFrame([{'latitude':click[1],'longitude': click[0]}])
    filtered = pd.DataFrame(within_p(df_cafe, clicked, 0.5))
    if len(filtered) > 0:
        n_cr = len(filtered['Street address'].unique()) # unique
        return n_cr
    else:
        n_cr = 0
        return n_cr


def get_office(click, df_office):
    clicked = pd.DataFrame([{'latitude': click[1], 'longitude': click[0]}])
    filtered = pd.DataFrame(within_p(df_office, clicked, 0.5))

    if len(filtered) > 0:
        n_of = len(filtered['Trading name'].unique())  # unique
        return n_of
    else:
        n_of = 0
        return n_of


def get_carpark(click, off_street, on_street):
    clicked = pd.DataFrame([{'latitude': click[1], 'longitude': click[0]}])
    filtered1 = pd.DataFrame(within_p(off_street, clicked, 0.5))
    filtered2 = pd.DataFrame(within_p(on_street, clicked, 0.5))

    if len(filtered1) > 0:
        f1 = filtered1['Parking spaces'].sum()  # number of parking
    else:
        f1 = 0

    if len(filtered2) > 0:
        f2 = len(filtered2)
    else:
        f2 = 0

    return f1 + f2


def get_accessible(click, df_accessible):
    clicked = pd.DataFrame([{'latitude': click[1], 'longitude': click[0]}])
    filtered = pd.DataFrame(within_p(df_accessible, clicked, 0.5))

    if len(filtered) > 0:
        n_wheelchair = len(filtered[filtered['wheelchair'] == 'yes'])  # unique
        return n_wheelchair
    else:
        n_wheelchair = 0
        return n_wheelchair

def get_gallery(click, df_gallery):
    clicked = pd.DataFrame([{'latitude': click[1], 'longitude': click[0]}])
    filtered = pd.DataFrame(within_p(df_gallery, clicked, 0.5))

    if len(filtered) > 0:
        n_gal = len(filtered)  # unique
        return n_gal
    else:
        n_gal = 0
        return n_gal


# Initialise
# Object ID may differ, need to change
@app.route("/")
def dashboard_projects():
    connection = MongoClient(MONGODB_HOST, MONGODB_PORT)
    collection = connection[DBS_NAME][COLLECTION_NAME]
    projects = collection.find_one({"features.properties.Gallery Name" : { "$exists" : True }}, {"_id":0})
    sensors = collection.find_one( { "_id": ObjectId("5cc683b30e00cbd80dd912ad") }, {"_id":0})  # Need to swap this
    connection.close()
    return render_template("test-1.php", geojson_data = projects, sensors = sensors)


# Get geocode from click
@app.route("/", methods=['GET', 'POST'])
def get_data():
    global click

    if request.method == 'POST':
        ss = json.loads(request.data)
        ss = json.loads(ss['data'])
        click = [ss['lng'], ss['lat']]
        return click

# Get geocode from search bar
@app.route("/test1", methods=['GET', 'POST'])
def get_search():
    if request.method == 'POST':
        s1 = json.loads(request.data)
        s1 = json.loads(s1['data'])
        lng,lat = s1[0], s1[1]

        print("Longitude", lng, "Longitude",lat)

    return ''


# Send everything within 500 to "/test"
@app.route("/test", methods=['GET', 'POST'])
def send_data():
    try:  # Only activates if click exist
        get_data()  # Get data from click

        pipeline = [
            {'$geoNear': {
                'near': {'type': "Point", 'coordinates': click},  # Assign click location
                'distanceField': "dist.calculated",
                'maxDistance': 500,  # 500m
                'includeLocs': "dist.location",
                'key': 'features.geometry.coordinates',
                'uniqueDocs': False,
                'spherical': True}},
            {"$unwind": "$features"},
            {"$redact": {
                "$cond": {
                    "if": {"$eq": [{"$cmp": ["$features.geometry.coordinates", "$dist.location"]}, 0]},
                    "then": "$$KEEP",
                    "else": "$$PRUNE"
                }
            }
            }
        ]
        # Connect DB
        connection = MongoClient(MONGODB_HOST, MONGODB_PORT)
        collection = connection[DBS_NAME][COLLECTION_NAME]
        # pprint.pprint(list(collection.aggregate(pipeline)))
        chart_data = {'chart_data': json_util.dumps(collection.aggregate(pipeline))}
        connection.close()

        print('-'*320)
        start = time.time()

        weekly_ped = for_eachday(get_ped_any(click, df))
        #print(chart_data)
        #print('-'*300)
        print(click)
        print('-'*300)
        print(get_ped_any(click, df).head())
        print('-'*300)
        print('Number of Residents Nearby: ', get_residential(click, population))
        print('-'*300)
        print("Number of Cafes nearby: ", get_cafe(click, df_cafe))
        print('-'*300)
        print("Number of Car Parks Nearby: ", get_carpark(click, on_street, off_street))
        print('-'*300)
        print("Number of Accessible toilets Nearby: ", get_accessible(click, df_accessible))
        print('-'*300)
        print("NUmber of Art Galleries Nearby: ", get_gallery(click, df_gallery))
        print('-'*300)
        print('Weekly Pedestrian: ', weekly_ped)
        print('-'*300)
        print("Number of Average Pedestrian between 10AM ~ 5PM: ", round(sum(weekly_ped) / 7))
        print('-' * 300)
        # print("Number of Offices nearby: ", get_office(click, df_office))  # This takes most of the time, 4.5sec should we keep it? or drop it?
        # print('-' * 300)
        
        end = time.time()
        print(end - start)
        print('-'*300)

        return jsonify(chart_data)

    except:
        return ''


"""
Data To Client
"""

@app.route("/pedestrian", methods=['GET', 'POST'])
def send_ped():
    try:  # Only activates if click exist
        get_data()  # Get data from click
        ped_data = get_ped_any(click, df).to_json(orient='index')
        print(ped_data)
        return jsonify(ped_data)

    except:
        return ''

@app.route("/resident", methods=['GET', 'POST'])
def send_data_resident():
    try:  # Only activates if click exist
        get_data()  # Get data from click
        res_data = pd.DataFrame([{'resident': get_residential(click, population)}]).to_json(orient='records')
        return jsonify(res_data)

    except:
        return ''

"""
Imange To Client
"""

@app.route('/image/house')
def send_image_resident():
    try:
        get_data()  # Get data from click
        if get_residential(click, population) > 10000:
            filename = './static/image/1.png'
            return send_file(filename, mimetype='image/png')
        else:
            filename = './static/image/2.png'
            return send_file(filename, mimetype='image/png')
    except:
        return ""


@app.route('/image/pedestrian')
def send_image_pedestrian():
    try:
        get_data()  # Get data from click
        # If Large number of 10-5 pedestrian movements
        if round(sum(for_eachday(get_ped_any(click, df))) / 7) > 1200:
            filename = './static/image/pedestrian1.png'
            return send_file(filename, mimetype='image/png')
        else:
        # If Not
            filename = './static/image/pedestrian2.png'
            return send_file(filename, mimetype='image/png')
    except:
        return ""

@app.route('/image/cafe')
def send_image_cafe():
    try:
        get_data()  # Get data from click
        # If Large number of 10-5 pedestrian movements
        if get_cafe(click, df_cafe) > 150:
            filename = './static/image/cafe1.png'
            return send_file(filename, mimetype='image/png')
        else:
        # If Not
            filename = './static/image/cafe2.png'
            return send_file(filename, mimetype='image/png')
    except:
        return ""

@app.route('/image/carpark')
def send_image_carpark():
    try:
        get_data()  # Get data from click
        # If Large number of carparks 5k
        if get_carpark(click, on_street, off_street) > 5000:
            filename = './static/image/carpark1.png'
            return send_file(filename, mimetype='image/png')
        else:
        # If Not
            filename = './static/image/carpark2.png'
            return send_file(filename, mimetype='image/png')
    except:
        return ""

@app.route('/image/accessible')
def send_image_accessible():
    try:
        get_data()  # Get data from click
        # If Exists
        if get_accessible(click, df_accessible) > 0:
            filename = './static/image/accessible1.png'
            return send_file(filename, mimetype='image/png')
        else:
        # If Not
            filename = './static/image/accessible2.png'
            return send_file(filename, mimetype='image/png')
    except:
        return ""

@app.route('/image/gallery')
def send_image_gallery():
    try:
        get_data()  # Get data from click
        # If exists
        if get_gallery(click, df_gallery) > 0:
            filename = './static/image/gallery1.png'
            return send_file(filename, mimetype='image/png')
        else:
        # If Not
            filename = './static/image/gallery2.png'
            return send_file(filename, mimetype='image/png')
    except:
        return ""


# Send dummy data
@app.route('/data')
def data():
    try:
        get_data()
        each_day = for_eachday(get_ped_any(click, df))
        return jsonify({'results': each_day, 'click2': each_day})

    except:
        return jsonify({'results':  sample(range(1,10), 7) })




if __name__ == "__main__":
    app.run(host='localhost',port=5000)



    ########################################################################################

    # # Get geocode from click
    # @app.route("/test", methods=['GET', 'POST'])
    # def get_data():
    #     global ss_list # Make it accessible from other function
    #     global results
    #
    #     if request.method == 'POST':
    #         ss = json.loads(request.data)
    #         ss = json.loads(ss['data'])
    #         click = [ss['lng'], ss['lat']]
    #         print(click)
    #
    #         pipeline = [
    #             {'$geoNear': {
    #                 'near': {'type': "Point", 'coordinates': click},
    #                 'distanceField': "dist.calculated",
    #                 'maxDistance': 500,
    #                 'includeLocs': "dist.location",
    #                 'key': 'features.geometry.coordinates',
    #                 'uniqueDocs': False,
    #                 'spherical': True}},
    #             {"$unwind": "$features"},
    #             {"$redact": {
    #                 "$cond": {
    #                     "if": {"$eq": [{"$cmp": ["$features.geometry.coordinates", "$dist.location"]}, 0]},
    #                     "then": "$$KEEP",
    #                     "else": "$$PRUNE"
    #                 }
    #             }
    #             }
    #         ]
    #
    #         connection = MongoClient(MONGODB_HOST, MONGODB_PORT)
    #         collection = connection[DBS_NAME][COLLECTION_NAME]
    #         # pprint.pprint(list(collection.aggregate(pipeline)))
    #         chart_data = {'chart_data': json_util.dumps(collection.aggregate(pipeline))}
    #         connection.close()
    #         return jsonify(chart_data)
    #
    #     return jsonify(chart_data)

    ########################################################################################

