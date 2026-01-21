import { useState } from 'react';
import { rekisteroidy } from '../api/authfetch';
import '../styles/auth.css';

const EMPTY_FORM = {
    Nimi: '',
    Sukunimi: '',
    Sahkoposti: '',
    Salasana: '',
    Osoite: '',
    Postinumero: '',
    Kunta: ''
};

function Rekisteroidy() {
    const [form, setForm] = useState(EMPTY_FORM);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');

    const handleChange = (e) => {
        const { name, value } = e.target;
        setForm(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError('');
        setSuccess('');
        console.log(form)
        try {
            await rekisteroidy(form);
            setSuccess('Rekisteröinti onnistui 🎉');
            setForm(EMPTY_FORM);
        } catch (err) {
            setError('Rekisteröinti epäonnistui. Yritä uudelleen.');
        } finally {
            setLoading(false);
        }
    };
    return (
        <div className="page-wrapper">
            <div className="card">
                <div className="header">
                    <h2 className="title">Luo tili</h2>
                    <p className="subtitle">Täytä tiedot rekisteröityäksesi</p>
                </div>

                {error && <div className="error-banner">{error}</div>}
                {success && <div className="success-banner">{success}</div>}

                <form onSubmit={handleSubmit} className="form">
                    <div className="row">
                        <div className="input-wrapper">
                            <label className="label">Etunimi</label>
                            <input
                                className="input"
                                name="Nimi"
                                value={form.Nimi}
                                onChange={handleChange}
                                required
                            />
                        </div>

                        <div className="input-wrapper">
                            <label className="label">Sukunimi</label>
                            <input
                                className="input"
                                name="Sukunimi"
                                value={form.Sukunimi}
                                onChange={handleChange}
                                required
                            />
                        </div>
                    </div>

                    <div className="input-wrapper">
                        <label className="label">Sähköposti</label>
                        <input
                            className="input"
                            name="Sahkoposti"
                            type="email"
                            value={form.Sahkoposti}
                            onChange={handleChange}
                            required
                        />
                    </div>

                    <div className="input-wrapper">
                        <label className="label">Salasana</label>
                        <input
                            className="input"
                            name="Salasana"
                            type="password"
                            value={form.Salasana}
                            onChange={handleChange}
                            required
                        />
                    </div>

                    <div className="input-wrapper">
                        <label className="label">Osoite</label>
                        <input
                            className="input"
                            name="Osoite"
                            value={form.Osoite}
                            onChange={handleChange}
                        />
                    </div>

                    <div className="row">
                        <div className="input-wrapper" style={{ flex: 1 }}>
                            <label className="label">Postinumero</label>
                            <input
                                className="input"
                                name="Postinumero"
                                value={form.Postinumero}
                                onChange={handleChange}
                            />
                        </div>

                        <div className="input-wrapper" style={{ flex: 2 }}>
                            <label className="label">Kunta</label>
                            <input
                                className="input"
                                name="Kunta"
                                value={form.Kunta}
                                onChange={handleChange}
                            />
                        </div>
                    </div>

                    <button
                        type="submit"
                        className="submit-btn"
                        disabled={loading}
                    >
                        {loading ? 'Tallennetaan...' : 'Luo tili'}
                    </button>
                </form>

                <div className="footer">
                    <p>
                        Onko sinulla jo tili?{' '}
                        <a href="/kirjaudu" className="link">
                            Kirjaudu sisään
                        </a>
                    </p>
                </div>
            </div>
        </div>
    );
}

export default Rekisteroidy;
