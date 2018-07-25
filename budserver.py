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
    return jsonify({"cell": "01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 00110111",
                    "block": "01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111 01101110 00100000 01000010 01101100 01101111 01100011 01101011 00100000 01000001 01000001 00101101 00110010 00110011 00101100"})