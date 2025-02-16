from flask import Flask, render_template, request, redirect, url_for, send_from_directory
import json
import os
from werkzeug.utils import secure_filename

app = Flask(__name__)
app.secret_key = 'your_secret_key'

DATA_FILE = 'data.json'
UPLOAD_FOLDER = 'uploads'  # Папка для загруженных изображений
USER_HTML_DIR = 'user_html'  # Папка для HTML-файлов пользователей
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Проверка, что папки существуют
os.makedirs(UPLOAD_FOLDER, exist_ok=True)
os.makedirs(USER_HTML_DIR, exist_ok=True)

def load_data():
    if os.path.exists(DATA_FILE):
        with open(DATA_FILE, 'r') as f:
            try:
                return json.load(f)
            except json.JSONDecodeError:
                return {"users": []}
    return {"users": []}

def save_data(data):
    with open(DATA_FILE, 'w') as f:
        json.dump(data, f)

@app.route('/')
def index():
    data = load_data()
    return render_template('index.html', users=data['users'])   

@app.route('/admin')
def admin():
    data = load_data()
    return render_template('admin.html', users=data['users'])

@app.route('/add_user', methods=['POST'])
def add_user():
    username = request.form.get('username')
    surname = request.form.get('surname')
    patronymic = request.form.get('patronymic')
    birth_date = request.form.get('birth_date')
    death_date = request.form.get('death_date')
    description = request.form.get('description')
    image = request.files.get('image')

    if username and surname and patronymic:
        data = load_data()
        user_data = {
            'username': username,
            'surname': surname,
            'patronymic': patronymic,
            'birth_date': birth_date,
            'death_date': death_date,
            'description': description,
            'image': ''
        }

        if image:
            image_filename = secure_filename(image.filename)
            image.save(os.path.join(app.config['UPLOAD_FOLDER'], image_filename))
            user_data['image'] = image_filename

        data['users'].append(user_data)
        save_data(data)

        generate_user_html(user_data)

    return redirect(url_for('admin'))

def generate_user_html(user_data):
    user_html_content = render_template('user_template.html',
                                         username=user_data['username'],
                                         surname=user_data['surname'],
                                         patronymic=user_data['patronymic'],
                                         birth_date=user_data['birth_date'],
                                         death_date=user_data['death_date'],
                                         description=user_data['description'],
                                         image=user_data['image'])

    file_name = f"{user_data['username']}_{user_data['surname']}.html"
    file_path = os.path.join(USER_HTML_DIR, file_name)

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(user_html_content)

@app.route('/delete_user/<username>')
def delete_user(username):
    data = load_data()
    user_to_delete = next((user for user in data['users'] if user['username'] == username), None)
    if user_to_delete and user_to_delete['image']:
        image_path = os.path.join(app.config['UPLOAD_FOLDER'], user_to_delete['image'])
        if os.path.exists(image_path):
            os.remove(image_path)  # Удаляем изображение
    data['users'] = [user for user in data['users'] if user['username'] != username]
    save_data(data)
    return redirect(url_for('admin'))

@app.route('/user/<username>/<surname>')
def user_profile(username, surname):
    file_name = f"{username}_{surname}.html"
    return send_from_directory(USER_HTML_DIR, file_name)

if __name__ == '__main__':
    app.run(debug=True, port=17091)
