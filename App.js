// ========================================
// IMPORTS
// ========================================
import React, { useState, useEffect } from 'react';
import { 
  View, 
  StyleSheet, 
  SafeAreaView, 
  StatusBar, 
  Alert
} from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';

// Custom Components
import LoginForm from './components/auth/LoginForm';
import RegisterForm from './components/auth/RegisterForm';
import MainDashboard from './components/dashboard/MainDashboard';

// Services
import { AuthService } from './services/AuthService';

// Styles
import { AppStyles } from './styles/AppStyles';

// ========================================
// NAVIGATION SETUP
// ========================================
// Note: This app uses simple conditional rendering for navigation
// For more complex apps, you would set up React Navigation here:
// import { NavigationContainer } from '@react-navigation/native';
// import { createStackNavigator } from '@react-navigation/stack';
// import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';

// ========================================
// PROVIDERS
// ========================================
// Note: This app uses local state management
// For more complex apps, you would set up context providers here:
// import { AuthProvider } from './contexts/AuthContext';
// import { ThemeProvider } from './contexts/ThemeContext';
// import { CameraProvider } from './contexts/CameraContext';

// ========================================
// MAIN APP COMPONENT
// ========================================
const App = () => {
  // ========================================
  // STATE MANAGEMENT
  // ========================================
  const [users, setUsers] = useState([]);
  const [currentUser, setCurrentUser] = useState(null);
  const [showLogin, setShowLogin] = useState(true);

  // ========================================
  // EFFECTS
  // ========================================
  // Load users from AsyncStorage on app start
  useEffect(() => {
    AuthService.loadUsers(setUsers);
  }, []);

  // Save users to AsyncStorage whenever the users state changes
  useEffect(() => {
    AuthService.saveUsers(users);
  }, [users]);

  // ========================================
  // AUTHENTICATION HANDLERS
  // ========================================
  const handleLogin = async (username, password) => {
    const success = await AuthService.handleLogin(username, password, users, setCurrentUser);
    
    if (!success) {
      Alert.alert('Login Failed', 'Invalid credentials or server unavailable.');
    }
    
    return success;
  };

  // Add bypass method to login function
  handleLogin.bypass = (user) => AuthService.handleBypassLogin(setCurrentUser);

  const handleRegister = async (newUser) => {
    const serverSucceeded = await AuthService.handleRegister(newUser, users, setUsers);

    if (serverSucceeded) {
      Alert.alert('Success', 'Registration successful! You can now log in.');
    } else {
      Alert.alert('Saved Locally', 'Server unreachable; account saved locally for login.');
    }
    
    // Always switch to login after successful local/server registration
    setShowLogin(true);
    return true;
  };

  const handleLogout = () => {
    AuthService.handleLogout(
      setCurrentUser, 
      () => {}, // setIsConnected - not needed in main app
      () => {}, // setSocketUrl - not needed in main app
      () => {}, // setVideoConfig - not needed in main app
      () => {}, // setFrameQueue - not needed in main app
      () => {}, // setSelectedCamera - not needed in main app
      () => {}, // setSelectedCameras - not needed in main app
      () => {}  // setIsSelectionMode - not needed in main app
    );
  };

  // ========================================
  // NAVIGATION HANDLERS
  // ========================================
  const handleSwitchToRegister = () => {
    setShowLogin(false);
  };

  const handleSwitchToLogin = () => {
    setShowLogin(true);
  };

  // ========================================
  // RENDER FUNCTION
  // ========================================
  return (
    <SafeAreaView style={AppStyles.container}>
      <StatusBar barStyle="light-content" backgroundColor="#121212" />
      
      {/* Authentication Flow */}
      {!currentUser ? (
        showLogin ? (
          <LoginForm
            onLogin={handleLogin}
            onSwitchToRegister={handleSwitchToRegister}
            users={users}
          />
        ) : (
          <RegisterForm
            onRegister={handleRegister}
            onSwitchToLogin={handleSwitchToLogin}
            users={users}
          />
        )
      ) : (
        /* Main Application */
        <MainDashboard
              currentUser={currentUser}
          setCurrentUser={setCurrentUser}
          users={users}
          setUsers={setUsers}
              onLogout={handleLogout}
        />
      )}
    </SafeAreaView>
  );
};

export default App;