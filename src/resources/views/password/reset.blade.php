<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Reset | {{ config('app.name', 'Laravel') }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .container {
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      max-width: 400px;
      width: 100%;
    }

    h1 {
      color: #333;
      font-size: 28px;
      margin-bottom: 10px;
      text-align: center;
    }

    .subtitle {
      color: #666;
      font-size: 14px;
      text-align: center;
      margin-bottom: 30px;
      line-height: 1.5;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      color: #333;
      font-size: 14px;
      margin-bottom: 8px;
      display: block;
      font-weight: 600;
    }

    .password-wrapper {
      position: relative;
      width: 100%;
    }

    input[type="password"],
    input[type="text"] {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 6px;
      font-size: 15px;
      transition: all 0.3s ease;
    }

    input[type="password"]:focus,
    input[type="text"]:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      padding: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      color: #999;
    }

    .password-toggle:hover {
      color: #667eea;
    }

    .password-toggle svg {
      width: 22px;
      height: 22px;
    }

    .btn {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn:active {
      transform: translateY(0);
    }

    .back-link {
      text-align: center;
      margin-top: 25px;
    }

    .back-link a {
      color: #667eea;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.3s ease;
    }

    .back-link a:hover {
      color: #764ba2;
    }

    .success-message {
      display: none;
      background: #d4edda;
      color: #155724;
      padding: 15px;
      border-radius: 6px;
      margin-top: 20px;
      text-align: center;
      font-size: 14px;
    }

    .error-message {
      display: none;
      background: #f8d7da;
      color: #721c24;
      padding: 15px;
      border-radius: 6px;
      margin-top: 20px;
      text-align: center;
      font-size: 14px;
    }

    @media (max-width: 480px) {
      .container {
        padding: 30px 20px;
      }

      h1 {
        font-size: 24px;
      }

      .subtitle {
        font-size: 13px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Password Reset</h1>
    <p class="subtitle">
      Enter your new password to restore access to your account.
    </p>

    <form id="resetForm" action="/password/reset" method="POST">
      <input type="hidden" name="token" id="tokenInput">
      <input type="hidden" name="email" id="emailInput">

      <div class="form-group">
        <label for="password">New password</label>
        <div class="password-wrapper">
          <input 
            type="password" 
            id="password" 
            name="password" 
            placeholder="Enter new password" 
            required
            minlength="8"
          >
          <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Toggle password visibility">
            <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.286c0-.172.02-.342.06-.507C3.11 7.59 6.495 4.5 12 4.5s8.89 3.09 9.904 7.28c.04.164.06.334.06.506v.428c0 .172-.02.342-.06.507C20.89 16.41 17.505 19.5 12 19.5s-8.89-3.09-9.904-7.28a2.54 2.54 0 0 1-.06-.507v-.427Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <svg id="eye-off-password" xmlns="http://www.w3.org/2000/svg" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.478 2.382c.95-.539 1.979-.905 3.067-1.11a2.583 2.583 0 0 1 3.245 1.577l.19.55a1.497 1.497 0 0 0 .898.909l1.545.568a1.5 1.5 0 0 0 1.024-.038l.552-.21a2.582 2.582 0 0 1 3.325 1.227c.785.444 1.56.96 2.313 1.544a.75.75 0 0 1-.912 1.196c-.67-.514-1.365-.982-2.075-1.407a1.5 1.5 0 0 0-1.024.038l-.552.21a2.582 2.582 0 0 1-3.325-1.227l-.19-.55a1.497 1.497 0 0 0-.898-.909l-1.545-.568a1.5 1.5 0 0 0-1.024.038l-.552.21a2.582 2.582 0 0 1-3.325-1.227Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.92 14.92a3 3 0 0 1-4.24-4.24Zm3.18 3.18a5.99 5.99 0 0 0-1.28-1.28l-.55-.55a.75.75 0 0 0-1.06 0l-.55.55a.75.75 0 0 0 0 1.06l.55.55a3 3 0 0 0 1.28 1.28Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.08 8.08a.75.75 0 0 0 0 1.06l.55.55a3 3 0 0 0 1.28 1.28l.55.55a.75.75 0 0 0 1.06 0l.55-.55a.75.75 0 0 0 0-1.06l-.55-.55a3 3 0 0 0-1.28-1.28l-.55-.55a.75.75 0 0 0-1.06 0Z" />
            </svg>
          </button>
        </div>
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirm password</label>
        <div class="password-wrapper">
          <input 
            type="password" 
            id="password_confirmation" 
            name="password_confirmation" 
            placeholder="Repeat password" 
            required
            minlength="8"
          >
          <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')" aria-label="Toggle password visibility">
            <svg id="eye-password_confirmation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.286c0-.172.02-.342.06-.507C3.11 7.59 6.495 4.5 12 4.5s8.89 3.09 9.904 7.28c.04.164.06.334.06.506v.428c0 .172-.02.342-.06.507C20.89 16.41 17.505 19.5 12 19.5s-8.89-3.09-9.904-7.28a2.54 2.54 0 0 1-.06-.507v-.427Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <svg id="eye-off-password_confirmation" xmlns="http://www.w3.org/2000/svg" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.478 2.382c.95-.539 1.979-.905 3.067-1.11a2.583 2.583 0 0 1 3.245 1.577l.19.55a1.497 1.497 0 0 0 .898.909l1.545.568a1.5 1.5 0 0 0 1.024-.038l.552-.21a2.582 2.582 0 0 1 3.325 1.227c.785.444 1.56.96 2.313 1.544a.75.75 0 0 1-.912 1.196c-.67-.514-1.365-.982-2.075-1.407a1.5 1.5 0 0 0-1.024.038l-.552.21a2.582 2.582 0 0 1-3.325-1.227l-.19-.55a1.497 1.497 0 0 0-.898-.909l-1.545-.568a1.5 1.5 0 0 0-1.024.038l-.552.21a2.582 2.582 0 0 1-3.325-1.227Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.92 14.92a3 3 0 0 1-4.24-4.24Zm3.18 3.18a5.99 5.99 0 0 0-1.28-1.28l-.55-.55a.75.75 0 0 0-1.06 0l-.55.55a.75.75 0 0 0 0 1.06l.55.55a3 3 0 0 0 1.28 1.28Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.08 8.08a.75.75 0 0 0 0 1.06l.55.55a3 3 0 0 0 1.28 1.28l.55.55a.75.75 0 0 0 1.06 0l.55-.55a.75.75 0 0 0 0-1.06l-.55-.55a3 3 0 0 0-1.28-1.28l-.55-.55a.75.75 0 0 0-1.06 0Z" />
            </svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn">Change password</button>

      <div class="success-message" id="successMessage">
        Your password has been successfully changed! You can now log in.
      </div>

      <div class="error-message" id="errorMessage">
        Unable to change password. Please check your data.
      </div>
    </form>

    <div class="back-link">
      <a href="{{ url('/') }}">Back to login</a>
    </div>
  </div>

  <script>
    // Get token and email from URL
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    const email = urlParams.get('email');

    // If no token or email, show error
    if (!token || !email) {
      document.getElementById('errorMessage').textContent = 
        'Invalid password recovery link.';
      document.getElementById('errorMessage').style.display = 'block';
    } else {
      // Fill hidden fields
      document.getElementById('tokenInput').value = token;
      document.getElementById('emailInput').value = email;
    }

    // Toggle password visibility
    function togglePassword(inputId) {
      const input = document.getElementById(inputId);
      const eyeIcon = document.getElementById('eye-' + inputId);
      const eyeOffIcon = document.getElementById('eye-off-' + inputId);
      
      if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.style.display = 'none';
        eyeOffIcon.style.display = 'block';
      } else {
        input.type = 'password';
        eyeIcon.style.display = 'block';
        eyeOffIcon.style.display = 'none';
      }
    }

    document.getElementById('resetForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const password = document.getElementById('password').value;
      const passwordConfirmation = document.getElementById('password_confirmation').value;
      
      // Check if passwords match
      if (password !== passwordConfirmation) {
        document.getElementById('errorMessage').textContent = 
          'Passwords do not match. Please check your input.';
        document.getElementById('errorMessage').style.display = 'block';
        return;
      }
      
      // Check password length
      if (password.length < 8) {
        document.getElementById('errorMessage').textContent = 
          'Password must contain at least 8 characters.';
        document.getElementById('errorMessage').style.display = 'block';
        return;
      }
      
      // You can add server submission via AJAX here
      // For demonstration, show success message
      document.getElementById('successMessage').style.display = 'block';
      
      console.log('Token:', token);
      console.log('Email:', email);
      console.log('Password:', password);
    });
  </script>
</body>
</html>