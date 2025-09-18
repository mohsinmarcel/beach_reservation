<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Join Our Platform</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .container {
      background: white;
      padding: 40px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    h1 {
      font-size: 24px;
      margin-bottom: 20px;
      color: #333;
    }
    a {
      display: inline-block;
      padding: 12px 25px;
      background-color: grey;
      color: white;
      font-size: 16px;
      font-weight: bold;
      text-decoration: none;
      border-radius: 8px;
      transition: background 0.3s ease;
    }
    a:hover {
      background-color: blue;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Want to be a part of this Online Beach Seats Reservation Platform?</h1>
    <a href="{{route('tenant.register')}}">Click here to register</a>
  </div>
</body>
</html>
