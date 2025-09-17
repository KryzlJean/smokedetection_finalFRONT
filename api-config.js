// API Configuration
// This picks a sensible default per platform.
// - Android Emulator: 10.0.2.2 maps to host machine's localhost
// - iOS Simulator/Web: localhost works
// - Physical Device: set your PC's LAN IP below

import { Platform } from 'react-native';

// If you're testing on a physical device on the same Wi‑Fi,
// set your Windows PC's LAN IP here (e.g., '192.168.1.5').
// Leave as empty string to auto-pick based on platform.
const LAN_IP = '192.168.1.10';

const DEFAULTS = {
  android: 'http://10.0.2.2/smokedetection-api',
  ios: 'http://localhost/smokedetection-api',
  web: 'http://localhost/smokedetection-api',
};

const resolvedBaseUrl = LAN_IP
  ? `http://${LAN_IP}/smokedetection-api`
  : (DEFAULTS[Platform.OS] || DEFAULTS.android);

export const API_CONFIG = {
  BASE_URL: resolvedBaseUrl,
};

export default API_CONFIG;
