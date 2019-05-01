from flask import Flask
from flask import render_template
from flask import  request

from pymongo import MongoClient
import json
from bson import json_util, ObjectId

import pandas as pd
from random import sample
from flask import jsonify

# Pedestrian
from datetime import date
import calendar# Find Sensor information and Distance
import geopy
from geopy import distance


mapbox_access_token = 'pk.eyJ1IjoicGx1c21nIiwiYSI6ImNqdGwxb3kxNjAwdmo0YW8xdjM4NG9zZWwifQ.Z9-QBnfpJDefBW7VzvC4mA'

df = pd.read_csv("static/data/pedestrian_temp.csv")
df2 = pd.read_csv('static/data/sensors.csv')
population = pd.read_csv('static/data/population.csv')


# Sensor
# PC: "5cbb067ae39a5cbeb4a82260"
# Mac: "5cc683b30e00cbd80dd912ad"

app = Flask(__name__)

MONGODB_HOST = 'localhost'
MONGODB_PORT = 27017
DBS_NAME = 'mydb'
COLLECTION_NAME = 'projects'
FIELDS = "FeatureCollection"


def within(df, target, radius, g):
    temp = []

    for x in range(len(target)):
        # Use try-except for error handling
        try:
            # Find coordinate1,2 calculate distance in km
            coords_1 = (df["Latitude"][g], df["Longitude"][g])
            coords_2 = (target["latitude"][x], target["longitude"][x])
            dist = geopy.distance.distance(coords_1, coords_2).km

            if dist <= radius:
                temp.append(target.iloc[x])
        except:
            pass
    return temp


def within_d(df, target, radius, g):
    distances = []

    for x in range(len(target)):
        # Use try-except for error handling
        try:
            # Find coordinate1,2 calculate distance in km
            coords_1 = (df["Latitude"][g], df["Longitude"][g])
            coords_2 = (target["latitude"][x], target["longitude"][x])
            dist = geopy.distance.distance(coords_1, coords_2).km

            if dist <= radius:
                distances.append(dist)
        except:
            pass
    return distances


def within_p(population, clicked, radius):
    temp = []
    for x in range(len(population)):
        # Use try-except for error handling
        # Find coordinate1,2 calculate distance in km
        coords_1 = (population["Latitude"][x], population["Longitude"][x])
        coords_2 = (clicked["latitude"][0], clicked["longitude"][0])
        dist = geopy.distance.distance(coords_1, coords_2).km

        if dist <= radius:
            temp.append(population.iloc[x])
        else:
            pass

    return temp


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

    d1 = df_nearest[df_nearest['sensor_id'] == df_temp['sensor_id'][0]]['hourly_counts'] * (
            df_temp['distance'][0] / df_temp['distance'].sum())
    d2 = df_nearest[df_nearest['sensor_id'] == df_temp['sensor_id'][1]]['hourly_counts'] * (
            df_temp['distance'][1] / df_temp['distance'].sum())
    d3 = df_nearest[df_nearest['sensor_id'] == df_temp['sensor_id'][2]]['hourly_counts'] * (
            df_temp['distance'][2] / df_temp['distance'].sum())

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


def get_residential(click, population):
    clicked = pd.DataFrame([{'latitude': click[1], 'longitude': click[0]}])
    filtered = pd.DataFrame(within_p(population, clicked, 0.5))
    household = filtered['Household'].sum()
    return household





# Initialise
# Object ID may differ, need to change
@app.route("/")
def dashboard_projects():
    connection = MongoClient(MONGODB_HOST, MONGODB_PORT)
    collection = connection[DBS_NAME][COLLECTION_NAME]
    projects = collection.find_one({"type": "FeatureCollection"}, {"_id":0})
    sensors = collection.find_one( { "_id": ObjectId("5cbb067ae39a5cbeb4a82260") }, {"_id":0})  # Need to swap this
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


        print('------------------------------------------------------------------------------------------------------------------------------------------------')
        print(click)
        print('------------------------------------------------------------------------------------------------------------------------------------------------')
        print(chart_data)
        print('------------------------------------------------------------------------------------------------------------------------------------------------')
        print(get_ped_any(click, df).head())
        print('------------------------------------------------------------------------------------------------------------------------------------------------')
        print(get_residential(click, population))
        print('------------------------------------------------------------------------------------------------------------------------------------------------')


        return jsonify(chart_data)

    except:
        return ''


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
def send_res():
    try:  # Only activates if click exist
        get_data()  # Get data from click
        res_data = pd.DataFrame([{'resident': get_residential(click, population)}]).to_json(orient='records')
        return jsonify(res_data)

    except:
        return ''


# Send dummy data
@app.route('/data')
def data():
    return jsonify({'results' : sample(range(1,10), 5)})


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

