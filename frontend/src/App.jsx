import "./App.css";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import LandingPage from "./pages/LandingPage";
import Login from "./pages/Login";
import Signup from "./pages/Signup";
import TwoFactorPage from "./pages/TwoFactorPage";
import Dashboard from "./pages/Dashboard";

function App() {
  return (
    <div className="main">
      <Router>
        <Routes>
          <Route path="/" element={<LandingPage />} />
          <Route path="/login" element={<Login />} />
          <Route path="/signup" element={<Signup />} />
          <Route path="/two-factor" element={<TwoFactorPage />} />
          <Route path="/dashboard" element={<Dashboard />} />
        </Routes>
      </Router>
    </div>
  );
}

export default App;
