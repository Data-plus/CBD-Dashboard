from flask import Flask
from flask import render_template
from flask import  request

from pymongo import MongoClient
import json
from bson import ObjectId
import pprint


mapbox_access_token = 'pk.eyJ1IjoicGx1c21nIiwiYSI6ImNqdGwxb3kxNjAwdmo0YW8xdjM4NG9zZWwifQ.Z9-QBnfpJDefBW7VzvC4mA'


app = Flask(__name__)

MONGODB_HOST = 'localhost'
MONGODB_PORT = 27017
DBS_NAME = 'mydb'
COLLECTION_NAME = 'projects'
FIELDS = "FeatureCollection"

@app.route("/")
def dashboard_projects():
    connection = MongoClient(MONGODB_HOST, MONGODB_PORT)
    collection = connection[DBS_NAME][COLLECTION_NAME]
    projects = collection.find_one({"type": "FeatureCollection"}, {"_id":0})
    sensors = collection.find_one( { "_id": ObjectId('5cc683b30e00cbd80dd912ad') }, {"_id":0})
    connection.close()
    return render_template("test-1.php", geojson_data = projects, sensors = sensors)


@app.route("/test", methods=['GET', 'POST'])
def get_data():
    global ss_list # Make it accessible from other function
    if request.method == 'POST':
        ss = json.loads(request.data)
        ss = json.loads(ss['data'])
        click = [ss['lng'], ss['lat']]
        print(click)

        pipeline = [
            {'$geoNear': {
                'near': {'type': "Point", 'coordinates': click},
                'distanceField': "dist.calculated",
                'maxDistance': 500,
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

        connection = MongoClient(MONGODB_HOST, MONGODB_PORT)
        collection = connection[DBS_NAME][COLLECTION_NAME]
        pprint.pprint(list(collection.aggregate(pipeline)))
        connection.close()

        return 'OK'

@app.route("/test1", methods=['GET', 'POST'])
def get_search():
    if request.method == 'POST':
        s1 = json.loads(request.data)
        s1 = json.loads(s1['data'])
        lng,lat = s1[0], s1[1]
        print("Longitude", lng, "Longitude",lat)

        return 'OK'




if __name__ == "__main__":
    app.run(host='localhost',port=5000)