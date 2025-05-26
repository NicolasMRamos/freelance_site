import sqlite3
from faker import Faker
import random
import bcrypt

# Initialize Faker
faker = Faker()

# Connect to SQLite database
conn = sqlite3.connect('freelance_w_data.db')
cur = conn.cursor()

# Insert fake categories
categories = ['Design', 'Writing', 'Programming', 'Marketing', 'Music']
for cat in categories:
    cur.execute('INSERT INTO Categories (name) VALUES (?)', (cat,))

# Insert fake users
for _ in range(19):
    name = faker.name()
    username = faker.user_name()
    raw_password = faker.password()
    hashed_password = bcrypt.hashpw(raw_password.encode('utf-8'), bcrypt.gensalt()).decode('utf-8')
    email = faker.email()
    is_fl = random.choice([0, 1])
    is_cl = random.choice([0, 1])
    cur.execute('''
        INSERT INTO Users (name, username, password, email, is_fl, is_cl)
        VALUES (?, ?, ?, ?, ?, ?)
    ''', (name, username, hashed_password, email, is_fl, is_cl))

# Fetch user IDs
cur.execute('SELECT user_id FROM Users')
user_ids = [row[0] for row in cur.fetchall()]

# Insert fake services
for _ in range(30):
    price = round(random.uniform(5, 500), 2)
    delivery_time = random.randint(1, 30)
    title = faker.sentence(nb_words=4)
    desc = faker.text()
    category = random.choice(categories)
    freelancer_id = random.choice(user_ids)
    cur.execute('''
        INSERT INTO Services (price, delivery_time, service_title, service_desc, category, freelancer_id)
        VALUES (?, ?, ?, ?, ?, ?)
    ''', (price, delivery_time, title, desc, category, freelancer_id))

# Fetch service IDs
cur.execute('SELECT service_id FROM Services')
service_ids = [row[0] for row in cur.fetchall()]

# Insert fake media
for _ in range(50):
    media_url = faker.image_url()
    media_type = random.choice(['image', 'video'])
    service_id = random.choice(service_ids)
    cur.execute('''
        INSERT INTO Media (media_url, media_type, service_id)
        VALUES (?, ?, ?)
    ''', (media_url, media_type, service_id))

# Insert fake reviews
for _ in range(40):
    rating = random.randint(1, 5)
    review_title = faker.sentence(nb_words=3)
    review_text = faker.text()
    client_id = random.choice(user_ids)
    service_id = random.choice(service_ids)
    cur.execute('''
        INSERT INTO Reviews (rating, review_title, review_text, client_id, service_id)
        VALUES (?, ?, ?, ?, ?)
    ''', (rating, review_title, review_text, client_id, service_id))

# Insert fake orders
for _ in range(40):
    status = random.choice(['pending', 'accepted', 'rejected', 'completed'])
    client_id = random.choice(user_ids)
    service_id = random.choice(service_ids)
    cur.execute('''
        INSERT INTO Orders (status, client_id, service_id)
        VALUES (?, ?, ?)
    ''', (status, client_id, service_id))

# Insert fake custom orders
for _ in range(20):
    custom_title = faker.sentence(nb_words=4)
    custom_desc = faker.text()
    custom_price = round(random.uniform(10, 1000), 2)
    custom_delivery_time = random.randint(1, 60)
    status = random.choice(['pending', 'accepted', 'rejected', 'completed'])
    client_id = random.choice(user_ids)
    service_id = random.choice(service_ids)
    cur.execute('''
        INSERT INTO CustomOrders (custom_title, custom_desc, custom_price, custom_delivery_time, status, client_id, service_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ''', (custom_title, custom_desc, custom_price, custom_delivery_time, status, client_id, service_id))

# Insert fake messages
for _ in range(30):
    message_title = faker.sentence(nb_words=5)
    message_text = faker.text()
    client_id = random.choice(user_ids)
    service_id = random.choice(service_ids)
    cur.execute('''
        INSERT INTO Messages (message_title, message_text, client_id, service_id)
        VALUES (?, ?, ?, ?)
    ''', (message_title, message_text, client_id, service_id))

# Commit and close
conn.commit()
conn.close()

print("Database successfully populated with fake data.")
