import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import TwoFactorVerification from "../components/TwoFactorVerification";
import "../styles/two-factor.css";

const TwoFactorPage = () => {
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState("");
  const navigate = useNavigate();

  useEffect(() => {
    // Check if user is in 2FA flow
    const twoFactorUserId = sessionStorage.getItem("two_factor_user_id");
    if (!twoFactorUserId) {
      navigate("/login");
    }
  }, [navigate]);

  const handleVerifyCode = async (code) => {
    setIsLoading(true);
    setError("");
    
    try {
      const twoFactorUserId = sessionStorage.getItem("two_factor_user_id");
      const response = await fetch("http://localhost/IAP_VOLUNTRACK_GRP_A13/backend/verify.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ 
          code,
          user_id: twoFactorUserId 
        }),
      });

      const data = await response.json();

      if (response.ok) {
        // Store auth token if provided
        if (data.token) {
          localStorage.setItem("token", data.token);
        }
        
        // Clear 2FA session data
        sessionStorage.removeItem("two_factor_user_id");
        
        // Redirect to dashboard
        navigate(data.redirect || "/dashboard");
      } else {
        setError(data.errors?.code?.[0] || "Invalid verification code");
      }
    } catch (err) {
      setError("Network error. Please try again.");
    } finally {
      setIsLoading(false);
    }
  };

  const handleResendCode = async () => {
    setIsLoading(true);
    setError("");
    
    try {
      const response = await fetch("http://localhost/IAP_VOLUNTRACK_GRP_A13/backend/resend.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      });

      const data = await response.json();

      if (response.ok) {
        return true; // Success
      } else {
        setError("Failed to resend code. Please try again.");
        return false;
      }
    } catch (err) {
      setError("Network error. Please try again.");
      return false;
    } finally {
      setIsLoading(false);
    }
  };

  const handleBackToLogin = () => {
    sessionStorage.removeItem("two_factor_user_id");
    navigate("/login");
  };

  return (
    <div className="two-factor-container">
      <div className="two-factor-card">
        <div className="two-factor-header">
          <h2>Two-Factor Authentication</h2>
          <p>Enter the 6-digit code sent to your email</p>
        </div>

        {error && <div className="error-message">{error}</div>}

        <TwoFactorVerification
          onVerify={handleVerifyCode}
          onResend={handleResendCode}
          isLoading={isLoading}
        />

        <div className="two-factor-footer">
          <button 
            type="button" 
            className="back-to-login-btn"
            onClick={handleBackToLogin}
            disabled={isLoading}
          >
            Back to Login
          </button>
        </div>
      </div>
    </div>
  );
};

export default TwoFactorPage;
