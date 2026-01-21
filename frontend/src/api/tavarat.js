import api from './client';

export async function haeTavarat() {
  try {
    const response = await api.get('/server.php?hae_tavarat=1');
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe tavaraa haettaessa');
  }
}

export async function haeTavara(tavaraId) {
  try {
    const response = await api.get(`/server.php?hae_tavara=1&TavaraID=${tavaraId}`);
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe tavaraa haettaessa');
  }
}

export async function haeSaatavillaOlevat() {
  try {
    const response = await api.get('/server.php?hae_saatavilla_olevat=1');
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe tavaraa haettaessa');
  }
}

export async function etsiTavara(hakusana) {
  try {
    const response = await api.get(`/server.php?etsi_tavara=1&hakusana=${encodeURIComponent(hakusana)}`);
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe haussa');
  }
}

export async function lisaaTavara(tavara) {
  try {
    const response = await api.post('/server.php', {
      lisaa_tavara: 1,
      Nimi: tavara.nimi,
      Kuvaus: tavara.kuvaus,
      Maara: tavara.maara,
      SaatavillaMaara: tavara.saatavillaMaara
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe lisättäessä');
  }
}

export async function paivitaTavara(tavaraId, tavara) {
  try {
    const response = await api.post('/server.php', {
      paivita_tavara: 1,
      TavaraID: tavaraId,
      Nimi: tavara.nimi,
      Kuvaus: tavara.kuvaus,
      Maara: tavara.maara,
      SaatavillaMaara: tavara.saatavillaMaara
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe päivittäessä');
  }
}

export async function poistaTavara(tavaraId) {
  try {
    const response = await api.post('/server.php', {
      poista_tavara: 1,
      TavaraID: tavaraId
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe poistettaessa');
  }
}