import { useState, useEffect } from "react";
import { haeSaatavillaOlevat, etsiTavara } from "../api/tavarat";
import { haeVaraukset, luoVaraus, peruutaVaraus } from "../api/varaukset";
import { haeLuokat } from "../api/luokat";
import { haeKaapit } from "../api/kaapit";
import '../styles/hallintapaneeli.css';

export default function HallintaPaneeli() {
  const [kayttaja, setKayttaja] = useState(null);
  const [hakusana, setHakusana] = useState("");
  const [sijaintiHaku, setSijaintiHaku] = useState("");
  const [valittuLuokka, setValittuLuokka] = useState("");
  const [laitteet, setLaitteet] = useState([]);
  const [varaukset, setVaraukset] = useState([]);
  const [luokat, setLuokat] = useState([]);
  const [kaapit, setKaapit] = useState([]);
  const [ladataan, setLadataan] = useState(true);
  const [virhe, setVirhe] = useState("");
  const [onnistui, setOnnistui] = useState("");
  const [naytaVarausModal, setNaytaVarausModal] = useState(false);
  const [valittuLaite, setValittuLaite] = useState(null);
  const [varausPaattymispaiva, setVarausPaattymispaiva] = useState("");
  const [varausMaara, setVarausMaara] = useState(1);
  const [valittuKaappi, setValittuKaappi] = useState("");

  useEffect(() => {
    const kayttajaData = JSON.parse(localStorage.getItem('kayttaja'));
    setKayttaja(kayttajaData);
    lataaAlkudata();
  }, []);

  const lataaAlkudata = async () => {
    try {
      setLadataan(true);
      const [laiteetRes, varauksetRes, luokatRes, kaapitRes] = await Promise.all([
        haeSaatavillaOlevat(),
        haeVaraukset(1),
        haeLuokat(),
        haeKaapit()
      ]);

      if (laiteetRes.success) setLaitteet(laiteetRes.tavarat || []);
      if (varauksetRes.success) setVaraukset(varauksetRes.varaukset || []);
      if (luokatRes.success) setLuokat(luokatRes.luokat || []);
      if (kaapitRes.success) setKaapit(kaapitRes.kaapit || []);
    } catch (error) {
      setVirhe(error.message);
    } finally {
      setLadataan(false);
    }
  };

  const hae = async () => {
    try {
      setLadataan(true);
      setVirhe("");
      
      if (hakusana.trim()) {
        const res = await etsiTavara(hakusana);
        if (res.success) {
          setLaitteet(res.tulokset || []);
        }
      } else {
        const res = await haeSaatavillaOlevat();
        if (res.success) {
          let tulokset = res.tavarat || [];
          
          if (sijaintiHaku.trim()) {
            tulokset = tulokset.filter(t => 
              t.Sijainnit?.toLowerCase().includes(sijaintiHaku.toLowerCase())
            );
          }
          
          if (valittuLuokka) {
            const luokka = luokat.find(l => l.LuokkaID === parseInt(valittuLuokka));
            if (luokka) {
              tulokset = tulokset.filter(t => 
                t.Sijainnit?.includes(luokka.Nimi)
              );
            }
          }
          
          setLaitteet(tulokset);
        }
      }
    } catch (error) {
      setVirhe(error.message);
    } finally {
      setLadataan(false);
    }
  };

  const avaVarausModal = (laite) => {
    setValittuLaite(laite);
    setNaytaVarausModal(true);
    setValittuKaappi("");
    const huomenna = new Date();
    huomenna.setDate(huomenna.getDate() + 1);
    setVarausPaattymispaiva(huomenna.toISOString().split('T')[0]);
    setVarausMaara(1);
  };

  const luoUusiVaraus = async () => {
    if (!valittuLaite || !varausPaattymispaiva || !valittuKaappi) {
      setVirhe("Valitse sijainti ja päättymispäivä");
      return;
    }

    try {
      setLadataan(true);
      setVirhe("");
      setOnnistui("");

      const res = await luoVaraus({
        tavaraId: valittuLaite.TavaraID,
        kaappiId: parseInt(valittuKaappi),
        maara: varausMaara,
        varauspaiva: new Date().toISOString().split('T')[0],
        paattymispaiva: varausPaattymispaiva
      });

      if (res.success) {
        setOnnistui("Varaus luotu onnistuneesti!");
        setNaytaVarausModal(false);
        setValittuLaite(null);
        setValittuKaappi("");
        setTimeout(() => setOnnistui(""), 3000);
        lataaAlkudata();
      } else {
        setVirhe(res.message);
      }
    } catch (error) {
      setVirhe(error.message);
    } finally {
      setLadataan(false);
    }
  };

  const peruutaVarausClick = async (varausId) => {
    if (!confirm("Haluatko varmasti merkitä tämän laitteen palautetuksi?")) return;

    try {
      setLadataan(true);
      setVirhe("");
      setOnnistui("");
      
      const res = await peruutaVaraus(varausId);
      if (res.success) {
        setOnnistui("Laite merkitty palautetuksi!");
        setTimeout(() => setOnnistui(""), 3000);
        lataaAlkudata();
      } else {
        setVirhe(res.message);
      }
    } catch (error) {
      setVirhe(error.message);
    } finally {
      setLadataan(false);
    }
  };

  const muotoilePaiva = (pvm) => {
    const paiva = new Date(pvm);
    return paiva.toLocaleDateString('fi-FI', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  // Hae kaapit jotka sisältävät valitun laitteen
  const getLaitteenKaapit = () => {
    if (!valittuLaite || !valittuLaite.Sijainnit) return [];
    
    return kaapit.filter(kaappi => 
      valittuLaite.Sijainnit.includes(kaappi.Nimi)
    );
  };

  if (ladataan && laitteet.length === 0) {
    return <div className="container"><p>Ladataan...</p></div>;
  }

  return (
    <div className="container">
      <h1>Opettajan hallintapaneeli</h1>
      <p>Tervetuloa, {kayttaja?.nimi} {kayttaja?.sukunimi}!</p>

      {virhe && <div className="virhe">{virhe}</div>}
      {onnistui && <div className="onnistui">{onnistui}</div>}

      <div className="haku">
        <input
          type="text"
          placeholder="Hae laitetta (esim. HP, webkamera, VR)"
          value={hakusana}
          onChange={(e) => setHakusana(e.target.value)}
          onKeyPress={(e) => e.key === 'Enter' && hae()}
        />
        <input
          type="text"
          placeholder="Varasto / luokkahuone (esim. A2TS16)"
          value={sijaintiHaku}
          onChange={(e) => setSijaintiHaku(e.target.value)}
          onKeyPress={(e) => e.key === 'Enter' && hae()}
        />
        <select value={valittuLuokka} onChange={(e) => setValittuLuokka(e.target.value)}>
          <option value="">Kaikki luokat</option>
          {luokat.map(luokka => (
            <option key={luokka.LuokkaID} value={luokka.LuokkaID}>
              {luokka.Nimi}
            </option>
          ))}
        </select>
        <button onClick={hae} disabled={ladataan}>
          {ladataan ? 'Haetaan...' : 'Hae'}
        </button>
      </div>

      <div className="varaukset">
        <h2>Omat varaukset ({varaukset.length})</h2>
        {varaukset.length === 0 ? (
          <p className="ei-tuloksia">Ei aktiivisia varauksia</p>
        ) : (
          varaukset.map(varaus => (
            <div key={varaus.VarausID} className="varaus">
              <div>
                <p className="varaus-nimi">{varaus.TavaraNimi}</p>
                <p className="varaus-pvm">
                  {muotoilePaiva(varaus.Varauspaiva)} – {muotoilePaiva(varaus.Paattymispaiva)}
                </p>
                <p className="varaus-sijainti">Sijainti: {varaus.KaappiNimi}</p>
                <p className="varaus-maara">Määrä: {varaus.VarausMaara} kpl</p>
              </div>
              <div className="varaus-toiminnot">
                <button 
                  onClick={() => peruutaVarausClick(varaus.VarausID)} 
                  className="btn-danger"
                  disabled={ladataan}
                >
                  Merkitse palautetuksi
                </button>
              </div>
            </div>
          ))
        )}
      </div>

      <div className="laitteet">
        <h2>Vapaat laitteet ({laitteet.length})</h2>
        {laitteet.length === 0 ? (
          <p className="ei-tuloksia">Ei vapaita laitteita hakuehdoilla</p>
        ) : (
          laitteet.map(laite => (
            <div key={laite.TavaraID} className="laite">
              <div>
                <p className="laite-nimi">{laite.Nimi}</p>
                {laite.Kuvaus && <p className="laite-kuvaus">{laite.Kuvaus}</p>}
                <p className="laite-sijainti">Sijainti: {laite.Sijainnit || 'Ei sijaintia'}</p>
                <p className="laite-maara">Saatavilla: {laite.SaatavillaMaara} kpl</p>
              </div>
              <button onClick={() => avaVarausModal(laite)} disabled={laite.SaatavillaMaara <= 0}>
                Varaa
              </button>
            </div>
          ))
        )}
      </div>

      {naytaVarausModal && valittuLaite && (
        <div className="modal-overlay" onClick={() => setNaytaVarausModal(false)}>
          <div className="modal" onClick={(e) => e.stopPropagation()}>
            <h2>Varaa laite</h2>
            <div className="modal-content">
              <p><strong>{valittuLaite.Nimi}</strong></p>
              <p>Saatavilla: {valittuLaite.SaatavillaMaara} kpl</p>
              
              <label>
                Valitse sijainti *
                <select
                  value={valittuKaappi}
                  onChange={(e) => setValittuKaappi(e.target.value)}
                >
                  <option value="">Valitse sijainti</option>
                  {getLaitteenKaapit().map(kaappi => (
                    <option key={kaappi.KaappiID} value={kaappi.KaappiID}>
                      {kaappi.Nimi} - {kaappi.LuokkaNimi}
                    </option>
                  ))}
                </select>
              </label>

              <label>
                Määrä *
                <input
                  type="number"
                  min="1"
                  max={valittuLaite.SaatavillaMaara}
                  value={varausMaara}
                  onChange={(e) => setVarausMaara(parseInt(e.target.value) || 1)}
                />
              </label>

              <label>
                Palautuspäivä *
                <input
                  type="date"
                  value={varausPaattymispaiva}
                  min={new Date().toISOString().split('T')[0]}
                  onChange={(e) => setVarausPaattymispaiva(e.target.value)}
                />
              </label>

              {virhe && <p style={{color: '#e74c3c', fontSize: '14px'}}>{virhe}</p>}

              <div className="modal-toiminnot">
                <button onClick={luoUusiVaraus} disabled={ladataan}>
                  {ladataan ? 'Varataan...' : 'Vahvista varaus'}
                </button>
                <button onClick={() => setNaytaVarausModal(false)} className="btn-secondary">
                  Peruuta
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {kayttaja?.rooli === 'admin' && (
        <div className="admin-navigation">
          <h3>Admin-toiminnot</h3>
          <nav>
            <a href="/admin/luokat">Hallinnoi luokkia</a>
            <a href="/admin/kaapit">Hallinnoi kaappeja</a>
            <a href="/admin/tavarat">Hallinnoi tavaroita</a>
            <a href="/admin/varasto">Hallinnoi varastoa</a>
            <a href="/admin/kayttajat">Hallinnoi käyttäjiä</a>
          </nav>
        </div>
      )}
    </div>
  );
}