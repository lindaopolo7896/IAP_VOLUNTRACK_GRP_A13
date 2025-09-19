import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import TwoFactorSettings from "../components/TwoFactorSettings";
import "../styles/dashboard.css";

const Dashboard = () => {
  const navigate = useNavigate();
  const [user, setUser] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [activeTab, setActiveTab] = useState("overview");

  useEffect(() => {
    // Check if user is authenticated
    const token = localStorage.getItem("token");
    if (!token) {
      navigate("/login");
      return;
    }

    // Fetch user data
    fetchUserData();
  }, [navigate]);

  const fetchUserData = async () => {
    try {
      const response = await fetch("/api/auth/user", {
        headers: {
          "Authorization": `Bearer ${localStorage.getItem("token")}`,
        },
      });

      if (response.ok) {
        const data = await response.json();
        setUser(data.user);
      } else {
        // Token might be invalid, redirect to login
        localStorage.removeItem("token");
        navigate("/login");
      }
    } catch (error) {
      console.error("Error fetching user data:", error);
      navigate("/login");
    } finally {
      setIsLoading(false);
    }
  };

  const handleLogout = async () => {
    try {
      await fetch("/api/auth/logout", {
        method: "POST",
        headers: {
          "Authorization": `Bearer ${localStorage.getItem("token")}`,
        },
      });
    } catch (error) {
      console.error("Error during logout:", error);
    } finally {
      localStorage.removeItem("token");
      navigate("/login");
    }
  };

  const handleUserUpdate = (updatedUser) => {
    setUser(updatedUser);
  };

  if (isLoading) {
    return (
      <div className="dashboard-container">
        <div className="loading">Loading...</div>
      </div>
    );
  }

  return (
    <div className="dashboard-container">
      <div className="dashboard-header">
        <h1>Dashboard</h1>
        <div className="user-info">
          <span>Welcome, {user?.name}</span>
          <button onClick={handleLogout} className="logout-btn">
            Logout
          </button>
        </div>
      </div>

      <div className="dashboard-content">
        <div className="sidebar">
          <nav className="dashboard-nav">
            <button
              className={`nav-item ${activeTab === "overview" ? "active" : ""}`}
              onClick={() => setActiveTab("overview")}
            >
              Overview
            </button>
            <button
              className={`nav-item ${activeTab === "security" ? "active" : ""}`}
              onClick={() => setActiveTab("security")}
            >
              Security Settings
            </button>
          </nav>
        </div>

        <div className="main-content">
          {activeTab === "overview" && (
            <div className="overview-tab">
              <h2>Account Overview</h2>
              <div className="info-card">
                <h3>Account Information</h3>
                <div className="info-item">
                  <label>Name:</label>
                  <span>{user?.name}</span>
                </div>
                <div className="info-item">
                  <label>Email:</label>
                  <span>{user?.email}</span>
                </div>
                <div className="info-item">
                  <label>Two-Factor Authentication:</label>
                  <span className={`status ${user?.two_factor_enabled ? "enabled" : "disabled"}`}>
                    {user?.two_factor_enabled ? "Enabled" : "Disabled"}
                  </span>
                </div>
              </div>
            </div>
          )}

          {activeTab === "security" && (
            <div className="security-tab">
              <TwoFactorSettings user={user} onUpdate={handleUserUpdate} />
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
