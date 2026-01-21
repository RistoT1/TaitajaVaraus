import { useState } from 'react';
import { useAuth } from '../context/AuthProvider';
import { kirjaudu } from '../api/authfetch';
import '../styles/auth.css'; // Tuo yhteinen tyyli

function Kirjaudu() {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const { login } = useAuth();

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setLoading(true);
        try {
            const responseData = await kirjaudu(username, password);
            login(responseData);
        } catch (err) {
            setError('Käyttäjätunnus tai salasana väärin.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="page-wrapper">
            <div className="card">
                <div className="header">
                    <h2 className="title">Tervetuloa takaisin</h2>
                    <p className="subtitle">Kirjaudu sisään tilillesi</p>
                </div>

                {error && <div className="error-banner">⚠️ {error}</div>}

                <form onSubmit={handleSubmit} className="form">
                    <div className="input-wrapper">
                        <label className="label">Käyttäjätunnus</label>
                        <input 
                            className="input" 
                            type="text" 
                            value={username} 
                            onChange={(e) => setUsername(e.target.value)} 
                            required 
                        />
                    </div>

                    <div className="input-wrapper">
                        <label className="label">Salasana</label>
                        <div className="password-container">
                            <input 
                                className="input" 
                                type={showPassword ? "text" : "password"} 
                                value={password} 
                                onChange={(e) => setPassword(e.target.value)} 
                                required 
                            />
                            <button 
                                type="button" 
                                className="eye-button" 
                                onClick={() => setShowPassword(!showPassword)}
                            >
                                {showPassword ? 'Piilota' : 'Näytä'}
                            </button>
                        </div>
                    </div>

                    <button type="submit" className="submit-btn" disabled={loading}>
                        {loading ? 'Kirjaudutaan...' : 'Kirjaudu sisään'}
                    </button>
                </form>

                <div className="footer">
                    <p>Eikö sinulla ole tiliä? <a href="/rekisteroidy" className="link">Luo tili</a></p>
                </div>
            </div>
        </div>
    );
}

export default Kirjaudu;