import api from './client';

// Login function
export async function kirjaudu(email, password) {
  try {
    const response = await api.post('/server.php', {
      kirjaudu: 1,
      Sahkoposti: email,
      Salasana: password,
    });

    // Expecting backend to return { token, kayttaja }
    return response.data;
  } catch (error) {
    if (error.response && error.response.data) {
      throw new Error(error.response.data.message || 'Login failed');
    } else {
      throw new Error('Login failed: Server not reachable');
    }
  }
}

export async function rekisteroidy(form) {
  console.log("data",
    form.Nimi,
    form.Sukunimi,
    form.Sahkoposti,
    form.Salasana,
    form.Osoite,
    form.Postinumero,
    form.Kunta
  );

  try {
    const response = await api.post('/server.php', {
      rekisteroidy: 1,
      Nimi: form.Nimi,
      Sukunimi: form.Sukunimi,
      Sahkoposti: form.Sahkoposti,
      Salasana: form.Salasana,
      Osoite: form.Osoite,
      Postinumero: form.Postinumero,
      Kunta: form.Kunta
    });

    console.log(response);
    return response.data;
  } catch (error) {
    if (error.response && error.response.data) {
      throw new Error(error.response.data.message || 'Registration failed');
    } else {
      throw new Error('Registration failed: Server not reachable');
    }
  }
}

