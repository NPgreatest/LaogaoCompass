<?hh // strict

namespace Pages;

function render_home(): void {
  \header('Content-Type: text/html');

  echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome to My Hack Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
      color: #333;
      margin: 0;
      padding: 40px;
      text-align: center;
    }

    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    h1 {
      font-size: 2em;
      margin-bottom: 10px;
    }

    p {
      font-size: 1.1em;
      line-height: 1.6;
    }

    .link {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 24px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      transition: background 0.2s ease-in-out;
    }

    .link:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>👋 Hi, I'm NP_123</h1>
    <h2>🐘 This is a Hack(PHP) website</h2>
    <p>Welcome to my Hack-based dashboard project. I'm exploring full-stack Hack development — from API to visualization, all in a single language.</p>
    <p>This is a lightweight server rendered entirely with Hack running on HHVM in Docker.</p>
    <a class="link" href="/chart">📊 View Chart Dashboard</a>
  </div>
</body>
</html>
HTML;
}
