<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email confirmation failed | {{ config('app.name', 'Laravel') }}</title>
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
      text-align: center;
    }

    h1 {
      color: #333;
      font-size: 28px;
      margin-bottom: 10px;
    }

    .subtitle {
      color: #666;
      font-size: 14px;
      margin-bottom: 30px;
    }

    .error-icon {
      width: 80px;
      height: 80px;
      margin: 20px auto 30px;
      background: #f8d7da;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .error-icon svg {
      width: 45px;
      height: 45px;
      color: #721c24;
    }

    .error-message {
      background: #f8d7da;
      color: #721c24;
      padding: 20px;
      border-radius: 8px;
      margin: 20px 0 30px;
      font-size: 16px;
      font-weight: 600;
    }

    .message-text {
      color: #666;
      font-size: 14px;
      margin-top: 10px;
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
      text-decoration: none;
      display: inline-block;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .back-link {
      text-align: center;
      margin-top: 25px;
    }

    .back-link a {
      color: #667eea;
      text-decoration: none;
      font-size: 14px;
    }

    .back-link a:hover {
      color: #764ba2;
    }

    @media (max-width: 480px) {
      .container {
        padding: 30px 20px;
      }

      h1 {
        font-size: 24px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="error-icon">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.73 4.03c-.77-1.34-2.771-1.34-3.54 0L4.73 15.03c-.77 1.34.192 3 1.732 3z" />
      </svg>
    </div>

    <h1>Failed!</h1>
    <p class="subtitle">
      We could not confirm your email address.
    </p>

    <div class="error-message">
      Email confirmation failed!
      <div class="message-text">
        {{ $message ?? 'Invalid or expired link. Please log in and try again.' }}
      </div>
    </div>

    <a href="{{ url('/') }}" class="btn">Back to login</a>

    <div class="back-link">
      <a href="{{ url('/') }}">Back to login</a>
    </div>
  </div>
</body>
</html>