from flask import Flask
from flask import render_template
from flask import  request

from pymongo import MongoClient
import json
from bson import json_util, ObjectId

import pandas as pd
from random import sample
from flask import jsonify

mapbox_access_token = 'pk.eyJ1IjoicGx1c21nIiwiYSI6ImNqdGwxb3kxNjAwdmo0YW8xdjM4NG9zZWwifQ.Z9-QBnfpJDefBW7VzvC4mA'

# Sensor
# PC: "5cbb067ae39a5cbeb4a82260"
# Mac: "5cc683b30e00cbd80dd912ad"

app = Flask(__name__)

MONGODB_HOST = 'localhost'
MONGODB_PORT = 27017
DBS_NAME = 'mydb'
COLLECTION_NAME = 'projects'
FIELDS = "FeatureCollection"


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


# Send it to "/test"
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
        print(chart_data)

        return jsonify(chart_data)
    except:
        return ''

# Get geocode from search
@app.route("/test1", methods=['GET', 'POST'])
def get_search():
    if request.method == 'POST':
        s1 = json.loads(request.data)
        s1 = json.loads(s1['data'])
        lng,lat = s1[0], s1[1]

        print("Longitude", lng, "Longitude",lat)

    return ''


# Send data
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

