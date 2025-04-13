<?hh // strict

namespace Pages;

use namespace HH\Lib\Str;
use namespace HH\Lib\Vec;
use namespace HH\Lib\IO;

function get_all_transcript_files(string $path): vec<string> {
  $files = vec[];
  foreach (\scandir($path) as $file) {
    if (\str_ends_with($file, '.json')) {
      $files[] = $path . '/' . $file;
    }
  }
  return $files;
}

function normalize_title(string $title): string {
  return \preg_replace('/[^\\x{4e00}-\\x{9fa5}a-zA-Z0-9]/u', '', $title) ?? $title;
}

<<__EntryPoint>>
function import_videos(): void {
  \header("Content-Type: application/json");
  \header("Access-Control-Allow-Origin: *");

  $conn = new \mysqli('mysql', 'insight', 'insight123', 'insightgao');
  if ($conn->connect_error) {
    echo \json_encode(dict['error' => 'DB connection failed']);
    return;
  }

  $video_json = \file_get_contents('/var/www/videodata/video_url.json');
  $video_data = \json_decode($video_json, true) as vec<array<string, mixed>>;
  $title_to_url = dict[];
  foreach ($video_data as $item) {
    $title_to_url[normalize_title($item['title'])] = $item['url'];
  }

  $imported = vec[];
  $transcript_files = get_all_transcript_files('/var/www/transcript');

  foreach ($transcript_files as $file_path) {
    $basename = \basename($file_path, '.json');
    $json_str = \file_get_contents($file_path);
    $json = \json_decode($json_str, true) as dict<string, mixed>;
    $title_raw = $basename;
    $title = normalize_title($title_raw);

    if (!\array_key_exists($title, $title_to_url)) {
      continue;
    }

    $video_url = $title_to_url[$title];
    $transcript = $json['text'] ?? '';

    // 去重逻辑
    $stmt = $conn->prepare('SELECT COUNT(*) FROM raw_videos WHERE title = ?');
    $stmt->bind_param('s', $title_raw);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if ($count > 0) {
      continue;
    }

    // 插入
    $stmt = $conn->prepare('INSERT INTO raw_videos (video_id, title, publish_date, duration, views, video_url, transcript, metadata) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $video_id = \uniqid('vid_');
    $publish_date = '2024-01-01';
    $duration = 0;
    $views = 0;
    $metadata = \json_encode(dict['source' => 'imported']);

    $stmt->bind_param(
      'sssissss',
      $video_id,
      $title_raw,
      $publish_date,
      $duration,
      $views,
      $video_url,
      $transcript,
      $metadata,
    );
    $stmt->execute();
    $stmt->close();

    $imported[] = $title_raw;
  }

  echo \json_encode(dict['imported' => $imported]);
}
