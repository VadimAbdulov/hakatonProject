from flask import Flask, render_template, request, redirect, url_for, send_from_directory, flash, session
import json
import os
from werkzeug.utils import secure_filename
from werkzeug.security import generate_password_hash, check_password_hash

app = Flask(__name__)
app.secret_key = 'your_secret_key'

DATA_FILE = 'data.json'
UPLOAD_FOLDER = 'uploads'  # Folder for uploaded images
USER_HTML_DIR = 'user_html'  # Folder for user-specific HTML files
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Ensure directories exist
os.makedirs(UPLOAD_FOLDER, exist_ok=True)
os.makedirs(USER_HTML_DIR, exist_ok=True)

# Load users data from the JSON file
def load_data():
    if os.path.exists(DATA_FILE):
        with open(DATA_FILE, 'r') as f:
            try:
                return json.load(f)
            except json.JSONDecodeError:
                return {"users": []}
    return {"users": []}

# Save data to the JSON file
def save_data(data):
    with open(DATA_FILE, 'w') as f:
        json.dump(data, f)

# Save new user to the JSON file
def save_user(username, password):
    data = load_data()
    users = data.get('users', [])

    # Check if the username already exists
    for user in users:
        if user['username'] == username:
            raise ValueError("Username already exists")
    
    # Hash the password before saving
    hashed_password = generate_password_hash(password)

    # Add the new user
    users.append({"username": username, "password": hashed_password})

    # Save updated data
    data['users'] = users
    save_data(data)
    print(f"User {username} has been successfully added!")

# Validate user credentials (username and password)
def validate_user(username, password):
    data = load_data()
    users = data.get('users', [])

    # Check if user exists and password matches
    for user in users:
        if user['username'] == username and check_password_hash(user['password'], password):
            return True  # User is valid
    return False  # Invalid credentials

@app.route('/')
def index():
    txt = session["username"] if session.__contains__("username") else "Регистрация"
    return render_template('main.html', text=txt)

@app.route('/admin')
def admin():
    data = load_data()
    return render_template('admin.html', users=data['users'])

@app.route('/form', methods=['GET', 'POST'])
def form():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        
        # Check credentials
        if validate_user(username, password):
            # Store user information in the session
            session['username'] = username  # Store username in session
            # You can store more user data if needed (e.g., full name, email)
            flash("Login successful!")
            return redirect(url_for('admin'))  # Redirect to success page or dashboard
        
        # If credentials are wrong
        flash("Invalid credentials. Please try again.")
        return redirect(url_for('form'))

    return render_template('form.html')


@app.route('/map')
def map():
    return render_template('map.html')

@app.route('/card1')
def card1():
    return render_template('card1.html')

@app.route('/form2', methods=['GET', 'POST'])
def form2   ():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        confirm_password = request.form['confirm_password']
        
        # Check if passwords match
        if password != confirm_password:
            flash("Пароли не совпадают. Пожалуйста, попробуйте снова.")
            return redirect(url_for('register'))

        # Check if the username already exists
        data = load_data()
        users = data.get('users', [])
        for user in users:
            if user['username'] == username:
                flash("Этот пользователь уже существует. Пожалуйста, выберите другое имя.")
                return redirect(url_for('register'))

        # Save the new user
        save_user(username, password)  # Save user with hashed password

        # Redirect to login page after successful registration
        flash("Регистрация успешна. Теперь вы можете войти!")
        return redirect(url_for('form'))  # Redirect to the login page

    return render_template('form2.html')


@app.route('/main')
def main():
    return render_template('main.html')

@app.route('/add_user', methods=['POST'])
def add_user():
    username = request.form.get('username')
    password = request.form.get('password')
    surname = request.form.get('surname')
    patronymic = request.form.get('patronymic')
    birth_date = request.form.get('birth_date')
    death_date = request.form.get('death_date')
    description = request.form.get('description')
    
    try:
        # Save user with hashed password
        save_user(username, password)
    except ValueError as e:
        flash(str(e))  # If username already exists
        return redirect(url_for('admin'))

    data = load_data()
    new_user = {
        "username": username,
        "surname": surname,
        "patronymic": patronymic,
        "birth_date": birth_date,
        "death_date": death_date,
        "description": description
    }
    data['users'].append(new_user)
    save_data(data)
    
    return redirect(url_for('admin'))

@app.route('/delete_user/<username>')
def delete_user(username):
    data = load_data()
    user_to_delete = next((user for user in data['users'] if user['username'] == username), None)

    if user_to_delete:
        # If the user has an image, delete it
        if "image" in user_to_delete:
            image_path = os.path.join(app.config['UPLOAD_FOLDER'], user_to_delete['image'])
            if os.path.exists(image_path):
                os.remove(image_path)  # Delete the image

        # Remove the user from the list
        data['users'] = [user for user in data['users'] if user['username'] != username]
        save_data(data)
    
    return redirect(url_for('admin'))

@app.route('/upload_image/<username>', methods=['GET', 'POST'])
def upload_image(username):
    if request.method == 'POST':
        # Check if the post request has the file part
        if 'file' not in request.files:
            flash('No file part')
            return redirect(request.url)

        file = request.files['file']

        if file.filename == '':
            flash('No selected file')
            return redirect(request.url)

        if file:
            filename = secure_filename(file.filename)
            file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))

            # Update user data with the image filename
            data = load_data()
            for user in data['users']:
                if user['username'] == username:
                    user['image'] = filename
                    break
            save_data(data)

            return redirect(url_for('admin'))

    return render_template('upload_image.html', username=username)

@app.route('/logout')
def logout():
    session.pop('username', None)  # Remove the username from the session
    flash("You have been logged out.")
    return redirect(url_for('index'))  # Redirect to the home page or login page


if __name__ == '__main__':
    app.run(debug=True, port=17091)
