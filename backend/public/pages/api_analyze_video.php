
<?hh // strict
namespace Pages;

use namespace HH\Lib\{C, Str};
use type Facebook\Experimental\AsyncMysql\{AsyncMysqlClient, AsyncMysqlRow};
use function HH\Asio\{join, va};

async function analyze_video(): Awaitable<void> {
  \header('Content-Type: application/json');

  $videoId = $_GET['video_id'] ?? null;
  if ($videoId === null) {
    \http_response_code(400);
    echo \json_encode(dict['error' => 'Missing video_id']);
    return;
  }

  // 1. Connect to DB
  $conn = await AsyncMysqlClient::connect('localhost', 3306, 'root', '', 'your_db');

  // 2. Get transcript from DB
  $transcriptRes = await $conn->queryf('SELECT transcript FROM videos WHERE video_id = %s', $videoId);
  if (!$transcriptRes->numRows()) {
    \http_response_code(404);
    echo \json_encode(dict['error' => 'Transcript not found']);
    return;
  }
  $row = $transcriptRes->mapRows()[0];
  $transcript = $row['transcript'] as string;

  // 3. Send to LLM API
  $response = await call_llm_api_async($transcript);

  // 4. Store result
  await $conn->queryf(
    'INSERT INTO analysis_results (video_id, result, created_at) VALUES (%s, %s, NOW())
     ON DUPLICATE KEY UPDATE result = VALUES(result), created_at = NOW()',
    $videoId,
    $response,
  );

  echo \json_encode(dict['result' => $response]);
}


async function call_llm_api_async(string $transcript): Awaitable<string> {
  $body = \json_encode(dict[
    'model' => 'Qwen/QwQ-32B',
    'messages' => vec[
      dict[
        'role' => 'user',
        'content' => '请对以下视频内容进行分析，提取关键点、话题、情绪变化等：' . \substr($transcript, 0, 4000), // 可裁剪长度
      ]
    ],
    'stream' => false,
    'max_tokens' => 512,
    'temperature' => 0.7,
    'top_p' => 0.7,
    'top_k' => 50,
    'frequency_penalty' => 0.5,
    'n' => 1,
    'response_format' => dict['type' => 'text'],
  ]);

  $headers = vec[
    'Authorization: Bearer YOUR_TOKEN_HERE',
    'Content-Type: application/json',
  ];

  $ch = \curl_init('https://api.siliconflow.cn/v1/chat/completions');
  \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
  \curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);
  \curl_setopt($ch, \CURLOPT_POSTFIELDS, $body);

  $response = \curl_exec($ch);
  \curl_close($ch);

  $data = \json_decode($response ?? '', true);
  return $data['choices'][0]['message']['content'] ?? '分析失败';
}
