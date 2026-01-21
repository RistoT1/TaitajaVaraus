import axios from 'axios';

const api = axios.create({
  baseURL: 'https://tvt-linux.tvtedu.fi/~213603/TaitajaVaraus/backend', 
  headers: {
    'Content-Type': 'application/json',
  },
});

// Attach token automatically to all requests
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Handle 401 Unauthorized globally
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('kayttaja');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
