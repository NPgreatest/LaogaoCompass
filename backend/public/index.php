<?hh // strict

<<__EntryPoint>>
async function main(): Awaitable<void> {
  \header("Access-Control-Allow-Origin: *");
  \header("Access-Control-Allow-Headers: Content-Type");
  \header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    \http_response_code(200);
    exit();
  }

  $path = \parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

  switch ($path) {
    case '/api/videos':
      require __DIR__ . '/pages/api_videos_async.php';
      await \Pages\api_videos_async();
      break;

    case '/api/import-videos':
      require __DIR__ . '/pages/api_import_videos.php';
      await \Pages\import_videos();
      break;

    default:
      \http_response_code(404);
      echo '404 Not Found: ' . \htmlspecialchars($path);
  }
}
