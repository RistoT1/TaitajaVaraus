import Navbar from './components/includes/Navbar'
import Home from './pages/Home'
import Kirjaudu from './pages/Kirjaudu'
import { Routes, Route } from 'react-router-dom';
import { useAuth } from './context/AuthProvider';
import { useNavigate, Navigate } from 'react-router-dom';
import Rekisteroidy from './pages/Rekisteroidy';
import HallintaPaneeli from './pages/HallintaPaneeli';
import Footer from './components/includes/Footer';
function App() {
  const { user, loading } = useAuth();

  if (loading) return null;
  return (
    <>
      <Navbar />
      <Routes>
        <Route path="/" element={<Home />} />
        <Route
          path="/Kirjaudu"
          element={user ? <Navigate to="/hallintapaneeli" replace /> : <Kirjaudu />}
        />
        <Route path="/Rekisteroidy" element={<Rekisteroidy />} />
        <Route path="/hallintapaneeli" element={<HallintaPaneeli />} />
      </Routes>
      <Footer />
    </>
  )
}

export default App
