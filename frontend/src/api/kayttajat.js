import api from './client';

export async function haeKayttajat() {
  try {
    const response = await api.get('/server.php?hae_kayttajat=1');
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe käyttäjiä haettaessa');
  }
}

export async function paivitaKayttaja(kayttajaId, kayttaja) {
  try {
    const response = await api.post('/server.php', {
      paivita_kayttaja: 1,
      KayttajaID: kayttajaId,
      Nimi: kayttaja.nimi,
      Sukunimi: kayttaja.sukunimi,
      Osoite: kayttaja.osoite,
      Postinumero: kayttaja.postinumero,
      Kunta: kayttaja.kunta
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe päivittäessä');
  }
}