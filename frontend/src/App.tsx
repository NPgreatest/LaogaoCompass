import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import WelcomePage from './pages/WelcomePage';
import ProjectSelector from './pages/ProjectSelector';
import ProjectPage from './pages/ProjectPage'

// Main App
function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<WelcomePage />} />
        <Route path="/projects" element={<ProjectSelector />} />
        <Route path="/project/:project_name" element={<ProjectPage />} />
      </Routes>
    </Router>
  );
}
export default App;
