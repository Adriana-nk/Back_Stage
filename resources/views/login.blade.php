<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form id="loginForm">
  <label for="email">Email :</label><br>
  <input type="email" id="email" name="email" required /><br><br>

  <label for="password">Mot de passe :</label><br>
  <input type="password" id="password" name="password" required /><br><br>

  <button type="submit">Se connecter</button>
</form>
<script>
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
  event.preventDefault();

  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;

  const payload = {
    email,
    password,
  };

  try {
    const response = await fetch('/api/auth/login', {  // adapte l’URL à ton endpoint API
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    const data = await response.json();

    const messageDiv = document.getElementById('message');

    if (response.ok) {
      // Connexion réussie
      messageDiv.style.color = 'green';
      messageDiv.textContent = data.message || 'Connexion réussie !';
      // Ici tu peux par ex. stocker token, rediriger, etc.
    } else {
      // Erreur (mauvais mot de passe, email inconnu, validation manquante, etc.)
      messageDiv.style.color = 'red';
      messageDiv.textContent = data.message || 'Erreur lors de la connexion';
    }
  } catch (error) {
    console.error('Erreur fetch:', error);
    document.getElementById('message').style.color = 'red';
    document.getElementById('message').textContent = 'Erreur réseau ou serveur.';
  }
});
</script>

<div id="message"></div>

</body>
</html>