<?hh // strict

<<__EntryPoint>>
function main(): void {
  $path = $_SERVER['REQUEST_URI'];

  switch ($path) {
    case '/':
    case '/index.php':
      require __DIR__ . '/pages/home.php';
      \Pages\render_home();
      break;

    case '/chart':
      require __DIR__ . '/pages/chart.php';
      \Pages\render_chart();
      break;

    case '/api/data':
      require __DIR__ . '/pages/api_data.php';
      \Pages\api_data();
      break;

    default:
      \http_response_code(404);
      echo '404 Not Found: ' . \htmlspecialchars($path);
  }
}
