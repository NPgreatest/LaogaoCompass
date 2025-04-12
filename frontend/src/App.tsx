import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Home from './pages/Home';
import VideosPage from './pages/VideosPage';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/videos" element={<VideosPage />} />
      </Routes>
    </Router>
  );
}

export default App;
