# Guide de Connexion Front-End - Back-End

Ce guide vous aidera à configurer correctement la connexion entre votre application front-end et le back-end Laravel.

## 🚀 Configuration Rapide

### 1. Configuration des Variables d'Environnement

Créez un fichier `.env` à partir de `.env.example` :

```bash
cp .env.example .env
```

### 2. Variables d'Environnement Essentielles

```env
# Configuration CORS
FRONTEND_URL=http://localhost:8100
SANCTUM_STATEFUL_DOMAINS=localhost:8100,localhost:3000,localhost:8080,127.0.0.1:8100,127.0.0.1:3000,127.0.0.1:8080
SESSION_DOMAIN=localhost

# Configuration de l'API
APP_URL=http://localhost:8000
```

### 3. Configuration CORS

Le fichier `config/cors.php` est déjà configuré pour accepter les requêtes depuis :
- `http://localhost:8100` (Ionic/Angular)
- `http://localhost:3000` (React)
- `http://localhost:8080` (Vue.js)
- `http://localhost:4200` (Angular)

### 4. Configuration Sanctum

Le fichier `config/sanctum.php` est configuré pour les domaines stateful.

## 🔧 Installation et Configuration

### Étape 1: Installation des Dépendances

```bash
composer install
npm install
```

### Étape 2: Configuration de la Base de Données

```bash
php artisan migrate
php artisan db:seed
```

### Étape 3: Génération de la Clé d'Application

```bash
php artisan key:generate
```

### Étape 4: Démarrage du Serveur

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## 📡 Endpoints API

### Routes Publiques (Sans Authentification)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/api/v1/auth/register` | Inscription d'un nouvel utilisateur |
| POST | `/api/v1/auth/login` | Connexion utilisateur |
| POST | `/api/v1/auth/forgot-password` | Mot de passe oublié |
| POST | `/api/v1/auth/reset-password` | Réinitialisation du mot de passe |
| POST | `/api/v1/auth/validate-account` | Validation du compte |
| GET | `/api/v1/test` | Test de l'API |

### Routes Protégées (Avec Authentification)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/v1/auth/me` | Informations de l'utilisateur connecté |
| POST | `/api/v1/auth/logout` | Déconnexion |
| POST | `/api/v1/auth/refresh` | Rafraîchir le token |
| GET | `/api/v1/user/profile` | Profil utilisateur |
| PUT | `/api/v1/user/profile` | Mise à jour du profil |

## 🎯 Configuration Front-End

### 1. Configuration de la Base URL

Dans votre application front-end, configurez la base URL :

```javascript
// JavaScript/TypeScript
const API_BASE_URL = 'http://localhost:8000/api/v1';
```

### 2. Configuration des Headers

```javascript
// Pour les requêtes API
const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
};
```

### 3. Gestion des Tokens

```javascript
// Stocker le token
localStorage.setItem('auth_token', response.data.token);

// Utiliser le token dans les requêtes
const config = {
    headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
    }
};
```

### 4. Exemple de Requête avec Axios

```javascript
import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api/v1',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
});

// Intercepteur pour ajouter le token
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Exemple d'utilisation
api.post('/auth/login', {
    email: 'user@example.com',
    password: 'password'
})
.then(response => {
    console.log(response.data);
})
.catch(error => {
    console.error(error.response.data);
});
```

## 🔍 Dépannage

### Problème: CORS bloqué
**Solution**: Vérifiez que `FRONTEND_URL` dans `.env` correspond à l'URL de votre front-end.

### Problème: Token non valide
**Solution**: 
1. Vérifiez que le token est bien envoyé dans les headers
2. Vérifiez que le middleware `auth:sanctum` est appliqué

### Problème: Session expirée
**Solution**: Implémentez un système de rafraîchissement de token côté front-end.

### Problème: Requête OPTIONS bloquée
**Solution**: Le middleware CORS gère automatiquement les requêtes OPTIONS.

## 📊 Structure des Réponses API

### Réponse de Succès
```json
{
    "success": true,
    "message": "Operation successful",
    "data": { ... }
}
```

### Réponse d'Erreur
```json
{
    "success": false,
    "message": "Error message",
    "data": null
}
```

### Réponse de Validation
```json
{
    "success": false,
    "message": "Validation Error",
    "errors": { ... }
}
```

## 🧪 Tests

### Test de l'API avec cURL

```bash
# Test de connexion
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Test avec token
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 📋 Checklist de Configuration

- [ ] Fichier `.env` créé et configuré
- [ ] Base de données migrée et seedée
- [ ] Serveur Laravel démarré
- [ ] CORS configuré pour votre domaine front-end
- [ ] Tests de connexion réussis
- [ ] Configuration du token dans le front-end
- [ ] Tests d'authentification réussis

## 🆘 Support

Si vous rencontrez des problèmes :
1. Vérifiez les logs Laravel : `storage/logs/laravel.log`
2. Testez les endpoints avec Postman ou cURL
3. Vérifiez la configuration CORS
4. Assurez-vous que le serveur est accessible depuis le front-end

## 📞 Contact

Pour toute question ou problème, créez une issue sur le repository ou contactez l'équipe de développement.
