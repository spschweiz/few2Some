from flask import Flask, jsonify

import subprocess

import settings

app = Flask(__name__)


def get_credentials():
    account = settings.ACCOUNTS.pop()
    return account

@app.route('/<request_id>/<username>/<sites>/')
def show_user_profile(username, sites, request_id):
    # show the user profile for that user
    # TODO: Start scraping

    out_file = open('logs/' +  username + '_' + request_id + '.out.log', 'a+')
    err_file = open('logs/' + username + '_' + request_id + '.err.log', 'a+')

    account = get_credentials()

    user = account['username']
    password = account['password']

    subprocess.Popen(['python', 'insta_scrape.py', username, user, password, sites, request_id],
            stdout=out_file, stderr=err_file)

    d = {
            'message': 'Starting for: ' + username
    }

    return jsonify(d)


@app.route('/reset/<username>/<password>/')
def reset_account(username, password):
    settings.ACCOUNTS.append({'username': username, 'password': password})

    return "Password reset"
