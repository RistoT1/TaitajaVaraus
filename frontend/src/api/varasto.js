import api from './client';

export async function haeVarastoRivit(kaappiId = null) {
  try {
    const params = new URLSearchParams({ hae_varasto_rivit: 1 });
    if (kaappiId) {
      params.append('KaappiID', kaappiId);
    }
    const response = await api.get(`/server.php?${params.toString()}`);
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe rivejä haettaessa');
  }
}

export async function lisaaVarastoRivi(rivi) {
  try {
    const response = await api.post('/server.php', {
      lisaa_varasto_rivi: 1,
      KaappiID: rivi.kaappiId,
      TavaraID: rivi.tavaraId,
      Maara: rivi.maara,
      Hylly: rivi.hylly
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe lisättäessä');
  }
}

export async function paivitaVarastoRivi(riviId, rivi) {
  try {
    const response = await api.post('/server.php', {
      paivita_varasto_rivi: 1,
      VarastoRiviID: riviId,
      Maara: rivi.maara,
      Hylly: rivi.hylly
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe päivittäessä');
  }
}

export async function poistaVarastoRivi(riviId) {
  try {
    const response = await api.post('/server.php', {
      poista_varasto_rivi: 1,
      VarastoRiviID: riviId
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe poistettaessa');
  }
}