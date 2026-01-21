import { createContext, useState, useContext, useEffect } from 'react';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  // Check for existing token on startup
  useEffect(() => {
    const savedUser = localStorage.getItem('kayttaja');
    const token = localStorage.getItem('token');

    if (savedUser && savedUser !== 'undefined' && token) {
      try {
        setUser(JSON.parse(savedUser));
      } catch (err) {
        console.error('Failed to parse savedUser:', err);
        localStorage.removeItem('kayttaja'); // clean up corrupted value
      }
    }

    setLoading(false);
  }, []);


  const login = (responseData) => {
    const { token, kayttaja } = responseData;

    // 1. Save to Local Storage (so it persists on refresh)
    localStorage.setItem('token', token);
    localStorage.setItem('kayttaja', JSON.stringify(kayttaja));

    // 2. Update State
    setUser(kayttaja);
  };

  const logout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('kayttaja');
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, login, logout, loading }}>
      {!loading && children}
    </AuthContext.Provider>
  );
}

// Custom hook for easy access
export const useAuth = () => useContext(AuthContext);