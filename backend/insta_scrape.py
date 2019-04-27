import settings

from bs4 import BeautifulSoup
import json, random, re, requests
from datetime import timedelta
import time
import traceback
import sys, traceback


import csv


def get_user_id_list(sites):

    followers = set()

    sites = sites.split(',')

    for site in sites:
        path = settings.CAMPAIGN_INFO[site]
        f = open(path)
        reader = csv.reader(f, delimiter=',', quotechar='"')
        for row in reader:
            followers.add(int(row[0]))

    return followers

def reset_password(username, password):
    requests.get('http://localhost/' + username + '/' + password )

def match_followers(user, username, password, sites, request_id):
    session = login_user(username, password)

    url = 'https://www.instagram.com/' + user

    request = session.get(url)
    soup = BeautifulSoup(request.content, 'html.parser')
    body = soup.find('body')
    pattern = re.compile('window._sharedData')

    script = body.find("script", text=pattern)

    script = script.get_text().replace('window._sharedData = ', '')[:-1]
    data = json.loads(script)

    links = soup.find_all('link')
    consumer_link = ""

    for link in links:
        if 'Consumer.js' in link['href']:
            consumer_link = 'https://www.instagram.com' + link['href']

    script = session.get(consumer_link).text

    m = re.search(r"t=\"(?P<t>\w+)\",n=\"(?P<n>\w+)\"", script)

    following_hash = m.group('t')

    current_id = data['entry_data']['ProfilePage'][0]['graphql']['user']['id']

    has_next_page = True
    url = 'https://www.instagram.com/graphql/query/'

    counter = 0

    follower_list = get_user_id_list(sites)

    cursor = None

    while has_next_page and counter < 5:
        variables = json.dumps({
            'id': str(current_id),
            'after': cursor or '',
            'first': "40",
            'include_reel': False
        })
        data = {
            'query_hash': following_hash, 
            'variables': variables
        }

        r = session.get(url, params=data)

        if r.status_code != 200:
            pass

        page_info = r.json()['data']['user']['edge_followed_by']['page_info']
        has_next_page = page_info['has_next_page']
        users = r.json()['data']['user']['edge_followed_by']['edges']

        for user in users:
            # Compare user to database
            if int(user['node']['id']) in follower_list and counter < 5:
                counter += 1
                r = requests.get(settings.FRONTEND_URL, params = {
                    'participation[__identity]': request_id,
                    'username': user['node']['username'],
                    'image': user['node']['profile_pic_url'].replace('https://', ''),
                })

                print(r.url)


        if has_next_page:
            cursor = page_info['end_cursor']
            time.sleep(5)

    return


HEADERS_LIST = [
    "Mozilla/5.0 (Windows NT 5.1; rv:41.0) Gecko/20100101"\
    " Firefox/41.0",
    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2)"\
    " AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2"\
    " Safari/601.3.9",
    "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:15.0)"\
    " Gecko/20100101 Firefox/15.0.1",
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36"\
    " (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36"\
    " Edge/12.246"
]

BASE_URL = 'https://www.instagram.com/accounts/login/'
LOGIN_URL = BASE_URL + 'ajax/'

def login_user(username, password):
    useragent = HEADERS_LIST[random.randint(0, len(HEADERS_LIST)-1)]

    session = requests.Session()
    session.headers = {'user-agent': useragent}
    session.headers.update({'Referer': BASE_URL})    
    req = session.get(BASE_URL)    
    soup = BeautifulSoup(req.content, 'html.parser')    
    body = soup.find('body')

    pattern = re.compile('window._sharedData')
    script = body.find("script", text=pattern)

    script = script.get_text().replace('window._sharedData = ', '')[:-1]
    data = json.loads(script)

    csrf = data['config'].get('csrf_token')
    login_data = {'username': username, 'password': password}
    session.headers.update({'X-CSRFToken': csrf})
    login = session.post(LOGIN_URL, data=login_data, allow_redirects=True)

    return session


if __name__ == '__main__':
    # python insta_scrape.py username user password campaign
    import sys
    user = sys.argv[1]
    username = sys.argv[2]
    password = sys.argv[3]
    sites = sys.argv[4]
    request_id = sys.argv[5]

    try: 
        match_followers(user, username, password, sites, request_id)
    except:
        traceback.print_exc(file=sys.stderr)

    print(requests.get('http://localhost:5000/reset/' + username + '/' + password + '/').text )


