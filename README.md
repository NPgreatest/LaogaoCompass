# LaogaoCompass - Analysis Platform for Mr & Mrs Gao's Channel

**LaogaoCompass** is a full-stack content analysis platform built with Hack (HHVM), React, MySQL, and Docker. It is designed to analyze the popular knowledge-sharing channel *Mr & Mrs Gao (老高与小茉)* by extracting subtitle data, matching video metadata, and providing visualization and querying capabilities.

---

## 🔧 Tech Stack

- **Frontend**: React + TypeScript (Relay ready)
- **Backend**: Hack (HHVM) + `AsyncMysqlClient` (async/await MySQL)
- **Database**: MySQL 8.0
- **Container**: Docker + docker-compose
- **Data Source**: Subtitles in `/transcript` folder + metadata in `video_url.json`

---

## 📁 Project Structure

```
project-root/
├── backend/                 # Hack server
│   └── public/
│       ├── index.php       # Router entry
│       └── pages/
│           ├── api_videos_async.php      # List videos
│           └── api_import_videos.php     # Import subtitle data
├── frontend/                # React client
├── transcript/              # Subtitle JSON files per video
├── videodata/
│   └── video_url.json       # Title → URL mappings
├── mysql-init/
│   └── init.sql             # DB schema and mock data
├── docker-compose.yml
```

---

## 🚀 Getting Started

### 1. Prepare subtitle and metadata

- Place all subtitle JSON files in `/transcript/`, named after video titles.
  Example: `transcript/Alien Mysteries Explained.json`

- Fill `videodata/video_url.json` like this:

```json
[
  {
    "title": "Alien Mysteries Explained",
    "url": "https://www.youtube.com/watch?v=DVORkf8OWcc"
  },
  ...
]
```

### 2. Start the system

```bash
docker-compose down -v   # (optional) clear previous DB
docker-compose up --build
```

This initializes MySQL tables and starts the fullstack system.

---

## 🌐 Available API Endpoints

### `/api/import-videos` — Batch import subtitles

- Loads files from `/transcript/` and maps them to `video_url.json`
- Detects and skips duplicates
- Stores data in `raw_videos` table

**Example:**
```bash
curl http://localhost:8080/api/import-videos
```

**Response:**
```json
{ "imported": ["Alien Mysteries Explained", "Human Fasting Power"] }
```

---

### `/api/videos` — Query all video records

Returns basic video info:

```json
[
  {
    "video_id": "vid_001",
    "title": "Alien Mysteries Explained",
    "publish_date": "2024-01-01",
    "views": 1200000,
    "video_url": "https://...",
  },
  ...
]
```

---

## 🔍 Future Features

- Chart visualizations (video trends, keyword clouds)
- AI-powered summary generation
- Segment-based highlight extraction
- GraphQL search / subtitle query interface

---

## 🧠 Developer Tips

- Use Hack async/await and `AsyncMysqlClient` for all data access
- Normalize titles with slug-based mapping
- Prevent duplicates using title-based existence check
- Extend schema to include computed analytics (e.g. `video_metrics`)

---

## 👨‍💻 Author

**Yuchen Liu** · 2025  
Intern @ Meta Health Compass Team  
Focus: AI automation, developer tools, and domain-specific agents
