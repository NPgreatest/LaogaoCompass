import React from 'react';
import { Link } from 'react-router-dom';
import './Home.css'; // 我们稍后写样式

const Home: React.FC = () => {
  return (
    <div className="home-container">
      <div className="overlay" />
      <div className="home-content">
        <h1 className="title">InsightGao · 老高与小茉频道洞察平台</h1>
        <p className="description">
          利用 AI 与数据分析，探索老高视频背后的宇宙。<br />
          可视化选题趋势、系列追踪、高能时刻、关键词图谱...
        </p>
        <Link to="/videos">
          <button className="home-button">进入视频洞察</button>
        </Link>
      </div>
    </div>
  );
};

export default Home;
