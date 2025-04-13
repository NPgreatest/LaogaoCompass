CREATE DATABASE IF NOT EXISTS insightgao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE insightgao;


CREATE TABLE IF NOT EXISTS raw_videos (
  video_id VARCHAR(64) PRIMARY KEY,
  title TEXT,
  publish_date DATE,
  duration INT,
  views INT,
  video_url TEXT,
  transcript MEDIUMTEXT,
  metadata JSON,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 创建表：video_metrics
CREATE TABLE IF NOT EXISTS video_metrics (
  video_id VARCHAR(64) PRIMARY KEY,
  summary TEXT,
  main_topics JSON,
  keywords JSON,
  series VARCHAR(128),
  is_pit BOOLEAN,
  highlight_points JSON,
  analyzed_at TIMESTAMP
);

-- 创建表：analysis_tasks
CREATE TABLE IF NOT EXISTS analysis_tasks (
  task_id VARCHAR(64) PRIMARY KEY,
  video_id VARCHAR(64),
  status ENUM('pending','running','done','failed'),
  log TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



