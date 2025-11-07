import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { Bell, Moon } from "lucide-react";
import profileIcon from "../../assets/profile-icon.png";
import "../../styles/organization/topbarOrg.css";

export default function TopbarOrg() {
  const [darkMode, setDarkMode] = useState(false);
  const navigate = useNavigate();

  const toggleDarkMode = () => {
    setDarkMode(!darkMode);
    document.body.classList.toggle("dark-mode");
  };

  const handleCreateOpportunity = () => {
    // Navigate to your create opportunity form
    navigate("/dashboard/organization/opportunities/create");
  };

  return (
    <header className="topbar">
      <button className="create-btn" onClick={handleCreateOpportunity}>
        Create New Opportunity
      </button>

      <div className="topbar-right">
        <button className="icon-btn" onClick={toggleDarkMode}>
          <Moon className="icon" />
        </button>

        <button className="notification-btn">
          <Bell className="icon" />
          <span className="notif-dot"></span>
        </button>

        <button
          className="profile-btn"
          onClick={() => navigate("/dashboard/organization/settings")}
        >
          <img src={profileIcon} alt="Profile" className="profile-img" />
        </button>
      </div>
    </header>
  );
}