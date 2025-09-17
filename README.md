<<<<<<< HEAD
# Web NVR System with Supabase Authentication

A Django-based Network Video Recorder (NVR) system with Supabase authentication integration.

## Features

- **User Authentication**: Secure login and signup functionality integrated with Supabase
- **Camera Management**: Add and manage IP cameras
- **Live Video Streaming**: View live feeds from connected cameras
- **Modern Interface**: Sleek, responsive dashboard with dark mode design

## System Requirements

- Python 3.8+
- Django 5.1+
- Supabase account

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/CCTV_NVR.git
   cd CCTV_NVR
   ```

2. Set up a virtual environment (optional but recommended):
   ```
   python -m venv venv
   source venv/bin/activate  # On Windows: venv\Scripts\activate
   ```

3. Install dependencies:
   ```
   pip install -r requirements.txt
   ```

4. Configure Supabase:
   - Create a `.env` file in the project root with your Supabase credentials:
     ```
     SUPABASE_URL=your_supabase_url
     SUPABASE_ANON_KEY=your_supabase_anon_key
     ```

5. Initialize the database:
   ```
   python manage.py migrate
   ```

6. Collect static files:
   ```
   python manage.py collectstatic
   ```

7. Run the server:
   ```
   python manage.py runserver
   ```

## Usage

- Access the login page at: http://localhost:8000/login
- Create a new account at: http://localhost:8000/signup  
- After login, you'll be redirected to the NVR dashboard
- Add new cameras using the "Add Camera" button

## Security Notes

- This application uses Supabase for authentication, which is secure and scalable
- User passwords are never stored directly in the application
- All authentication tokens are stored in Django sessions with proper security measures

## License

This project is licensed under the MIT License - see the LICENSE file for details.

# About the Application

Open the `App.js` file to start writing some code. You can preview the changes directly on your phone or tablet by scanning the **QR code** or use the iOS or Android emulators.

When you're ready to see everything that Expo provides (or if you want to use your own editor) you can **Download** your project and use it with [expo cli](https://docs.expo.dev/get-started/installation/#expo-cli)).

All projects created in Snack are publicly available, so you can easily share the link to this project via link, or embed it on a web page with the `<>` button.

If you're having problems, you can tweet to us [@expo](https://twitter.com/expo) or ask in our [forums](https://forums.expo.dev/c/expo-dev-tools/61) or [Discord](https://chat.expo.dev/).

Snack is Open Source. You can find the code on the [GitHub repo](https://github.com/expo/snack).
=======
# smoke-frontend
>>>>>>> b960d326e15032dae36dfbd388719b8a4888dc84
