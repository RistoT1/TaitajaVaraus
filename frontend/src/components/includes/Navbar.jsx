import React from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthProvider';

function Navbar() {
  const { user, logout } = useAuth();

  return (
    <div
      style={{
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: 'black',
        padding: 10,
      }}
    >
      <ul
        style={{
          display: 'flex',
          listStyle: 'none',
          gap: '20px',
          margin: 0,
          padding: 0,
          alignItems: 'center',
        }}
      >
        <li>
          <Link to="/" style={{ color: 'white', textDecoration: 'none' }}>
            Home
          </Link>
        </li>

        {/* Show ONLY when NOT logged in */}
        {!user && (
          <>
            <li>
              <Link to="/Kirjaudu" style={{ color: 'white', textDecoration: 'none' }}>
                Kirjaudu
              </Link>
            </li>
            <li>
              <Link to="/Rekisteroidy" style={{ color: 'white', textDecoration: 'none' }}>
                Rekisteröidy
              </Link>
            </li>
          </>
        )}

        {/* Show ONLY when logged in */}
        {user && (
          <>
            <li>
              <Link to="/hallintapaneeli" style={{ color: 'white', textDecoration: 'none' }}>
                Hallintapaneeli
              </Link>
            </li>
            <li>
              <button
                onClick={logout}
                style={{
                  background: 'none',
                  border: 'none',
                  color: 'white',
                  cursor: 'pointer',
                  fontSize: '16px',
                }}
              >
                Kirjaudu ulos
              </button>
            </li>
          </>
        )}
      </ul>
    </div>
  );
}

export default Navbar;
