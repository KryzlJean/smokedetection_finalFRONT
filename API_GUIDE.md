# NVR System API Guide for Mobile Integration

This guide explains how to authenticate with the NVR system API and manage cameras from a mobile application.

## Authentication

The NVR system uses token-based authentication. You'll need to obtain a token before making API requests.

### Sign Up

```
POST /api/auth/signup/
```

**Request:**
```json
{
  "username": "your_username",
  "email": "your_email@example.com",
  "password": "your_password"
}
```

**Response:**
```json
{
  "token": "your_auth_token",
  "user_id": 1,
  "username": "your_username",
  "email": "your_email@example.com"
}
```

### Login

```
POST /api/auth/login/
```

**Request:**
```json
{
  "username": "your_username",
  "password": "your_password"
}
```

**Response:**
```json
{
  "token": "your_auth_token",
  "user_id": 1,
  "username": "your_username",
  "email": "your_email@example.com"
}
```

## Making Authenticated Requests

For all API requests, include the authentication token in the header:

```
Authorization: Token your_auth_token
```

## Camera Management

### List Cameras

```
GET /api/camera-list/
```

**Response:**
```json
[
  {
    "id": 1,
    "name": "Front Door",
    "location": "Entrance",
    "ip_address": "192.168.1.100:8000",
    "is_active": true,
    "created_at": "2025-04-13T10:00:00.000000Z",
    "updated_at": "2025-04-13T10:00:00.000000Z",
    "owner_username": "your_username"
  }
]
```

### Add Camera

```
POST /api/camera-create/
```

**Request:**
```json
{
  "name": "Back Door",
  "location": "Backyard",
  "ip_address": "192.168.1.101:8000",
  "is_active": true
}
```

**Response:**
```json
{
  "id": 2,
  "name": "Back Door",
  "location": "Backyard",
  "ip_address": "192.168.1.101:8000",
  "is_active": true,
  "created_at": "2025-04-13T11:00:00.000000Z",
  "updated_at": "2025-04-13T11:00:00.000000Z",
  "owner_username": "your_username"
}
```

### Delete Camera

```
POST /api/camera-delete/
```

**Request:**
```json
{
  "name": "Back Door",
  "ip_address": "192.168.1.101:8000"
}
```

**Response:**
```json
{
  "detail": "Camera deleted successfully"
}
```

### Get User Info

```
GET /api/auth/user/
```

**Response:**
```json
{
  "user_id": 1,
  "username": "your_username",
  "email": "your_email@example.com",
  "camera_count": 2
}
```

## Error Handling

All API endpoints return appropriate HTTP status codes:

- 200: Success
- 201: Created successfully
- 400: Bad request (check error details in response)
- 401: Unauthorized (invalid or missing token)
- 404: Resource not found
- 500: Server error

Error responses include details about what went wrong:

```json
{
  "detail": "Error message describing what went wrong"
}
```

## Implementation Example (JavaScript)

```javascript
// Login example
async function login(username, password) {
  try {
    const response = await fetch('http://your-server.com/api/auth/login/', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ username, password }),
    });
    
    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.detail || 'Login failed');
    }
    
    const data = await response.json();
    // Store token securely
    localStorage.setItem('authToken', data.token);
    return data;
  } catch (error) {
    console.error('Login error:', error);
    throw error;
  }
}

// Fetch cameras example
async function getCameras() {
  try {
    const token = localStorage.getItem('authToken');
    
    if (!token) {
      throw new Error('Not authenticated');
    }
    
    const response = await fetch('http://your-server.com/api/camera-list/', {
      headers: {
        'Authorization': `Token ${token}`,
      },
    });
    
    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.detail || 'Failed to fetch cameras');
    }
    
    return await response.json();
  } catch (error) {
    console.error('Error fetching cameras:', error);
    throw error;
  }
}
``` 