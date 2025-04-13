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

    // Parse pagination params
    $pn = (int)($_GET['pn'] ?? '1');
    $ps = (int)($_GET['ps'] ?? '5');
    $offset = ($pn - 1) * $ps;

    // Get total count
    $count_result = await $conn->query('SELECT COUNT(*) as total FROM raw_videos');
    $total = (int)($count_result->mapRowsTyped()[0]['total'] ?? 0);

    // Get paginated data
    $query = 'SELECT video_id, title, publish_date, views, video_url FROM raw_videos ORDER BY publish_date DESC LIMIT %d OFFSET %d';
    $result = await $conn->queryf($query, $ps, $offset);
    $rows = $result->mapRowsTyped();

    echo \json_encode(dict[
      'total' => $total,
      'page_size' => $ps,
      'page_num' => $pn,
      'videos' => $rows,
    ]);
  } catch (\Exception $e) {
    \http_response_code(500);
    echo \json_encode(dict['error' => $e->getMessage()]);
  }
}
