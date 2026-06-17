<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email confirmed successfully | {{ config('app.name', 'Laravel') }}</title>
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

    .success-icon {
      width: 80px;
      height: 80px;
      margin: 20px auto 30px;
      background: #d4edda;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .success-icon svg {
      width: 45px;
      height: 45px;
      color: #155724;
    }

    .success-message {
      background: #d4edda;
      color: #155724;
      padding: 20px;
      border-radius: 8px;
      margin: 20px 0 30px;
      font-size: 16px;
      font-weight: 600;
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
    <div class="success-icon">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
      </svg>
    </div>

    <h1>Success!</h1>
    <p class="subtitle">
      You have successfully confirmed your email.
    </p>

    <div class="success-message">
      {{ $message ?? ' Email confirmed successfully!' }}
    </div>

    <a href="{{ url('/') }}" class="btn">Go to login</a>

    <div class="back-link">
      <a href="{{ url('/') }}">Back to login</a>
    </div>
  </div>
</body>
</html>