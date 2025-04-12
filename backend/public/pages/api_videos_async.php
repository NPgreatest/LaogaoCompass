<?hh // strict
namespace Pages;

<<__EntryPoint>>
async function api_videos_async(): Awaitable<void> {
  \header("Access-Control-Allow-Origin: *");
  \header("Access-Control-Allow-Headers: Content-Type");
  \header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  \header("Content-Type: application/json");

  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    \http_response_code(200);
    exit();
  }

  try {
    $conn = await \AsyncMysqlClient::connect(
      'mysql',
      3306,
      'insightgao',
      'insight',
      'insight123',
    );

    $result = await $conn->query(
      'SELECT video_id, title, publish_date, views, video_url FROM raw_videos ORDER BY publish_date DESC'
    );

    $rows = $result->mapRowsTyped();
    echo \json_encode($rows);
  } catch (\Exception $e) {
    \http_response_code(500);
    echo \json_encode(dict['error' => $e->getMessage()]);
  }
}
