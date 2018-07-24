# -*- coding: utf-8 -*-
from flask import Flask, request, jsonify
from pprint import pprint

app = Flask(__name__)
app.debug = True

@app.route("/token", methods=['GET', 'POST'])
def hello():
    print("\n\nTOKEN REQUEST")
    print request.headers
    print request.form
    return jsonify({"access_token": "e31a726c4b90462ccb7619e1b51f3d0068bf8006","expires_in": 99999999999,"token_type": "Bearer","scope":"TheForce"})

@app.route("/reactor/exhaust/1", methods=['DELETE'])
def delete():
    print ("\n\nREACTOR REQUEST")
    print(request.headers)
    return "works"

@app.route('/prisoner/leia')
def leia():
    print("\n\nPRISONER REQUEST")
    print(request.headers)
    return "works"