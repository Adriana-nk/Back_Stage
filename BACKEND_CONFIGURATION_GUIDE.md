# 🛠️ Guide de Configuration Backend Laravel - PATNUC

## 📋 Vue d'ensemble
Ce guide est spécifiquement adapté à la structure actuelle de ton projet PATNUC Backend qui utilise :
- Laravel 11.x avec architecture DDD (Domain-Driven Design)
- API RESTful avec versioning (v1)
- Architecture hexagonale avec séparation des couches
- Sanctum pour l'authentification

## 🚀 Configuration rapide

### 1. Configuration de l'environnement

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Configuration PATNUC spécifique
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=patnuc_db
DB_USERNAME=root
DB_PASSWORD=

# Configuration API et CORS
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:4200
SANCTUM_STATEFUL_DOMAINS=localhost:4200,localhost:8100,127.0.0.1:4200
SESSION_DOMAIN=localhost
```

### 2. Installation et configuration

```bash
# Installation des dépendances
composer install

# Génération de la clé d'application
php artisan key:generate

# Configuration de la base de données
php artisan migrate
php artisan db:seed
```

### 3. Démarrage du serveur

```bash
# Démarrage du serveur de développement
php artisan serve --host=localhost --port=8000
```

## 📊 Structure des routes API actuelles

### Base URL
```
http://localhost:8000/api/v1
```

### Routes d'authentification (implémentées)
| Méthode | Endpoint | Contrôleur | Description |
|---------|----------|------------|-------------|
| POST | `/auth/register` | AuthenticationController@register | Inscription utilisateur |
| POST | `/auth/login` | AuthenticationController@login | Connexion utilisateur |
| POST | `/auth/logout` | AuthenticationController@logout | Déconnexion |
| POST | `/auth/forgot-password` | AuthenticationController@forgotPassword | Mot de passe oublié |
| POST | `/auth/reset-password` | AuthenticationController@resetPassword | Réinitialisation mot de passe |
| POST | `/auth/validate-account` | AuthenticationController@validateAccount | Validation compte |

### Architecture actuelle

#### Structure DTO (Data Transfer Objects)
- `app/Core/Auth/Dto/RegisterDto.php` - Données inscription
- `app/Core/Auth/Dto/LoginDto.php` - Données connexion
- `app/Core/Auth/Dto/ForgotPasswordDto.php` - Données mot de passe oublié
- `app/Core/Auth/Dto/ResetPasswordDto.php` - Données réinitialisation
- `app/Core/Auth/Dto/ValidateAccountDto.php` - Données validation compte

#### Structure des contrôleurs
- `app/Adapter/Http/API/Auth/Controllers/AuthenticationController.php` - Contrôleur principal auth
- `app/Adapter/Http/API/Auth/Controllers/LoginController.php` - Contrôleur login dédié
- `app/Adapter/Http/API/Auth/Services/AuthService.php` - Service d'authentification

#### Middleware CORS
- `app/Http/Middleware/CorsMiddleware.php` - Gestion CORS personnalisée
- Configuration dans `config/cors.php`

## 🔧 Configuration CORS

Le middleware CORS est déjà configuré dans `app/Http/Middleware/CorsMiddleware.php` :

```php
// Origines autorisées
$allowedOrigins = [
    'http://localhost:4200',
    'http://localhost:8100',
    'http://127.0.0.1:4200',
    'http://localhost:3000'
];
```

## 📝 Tests

Les tests unitaires sont déjà implémentés :
- `tests/Unit/Auth/LoginTest.php`
- `tests/Unit/Auth/RegisterTest.php`
- `tests/Unit/Auth/ForgotPasswordTest.php`
- `tests/Unit/Auth/ValidateAccountTest.php`

### Exécution des tests
```bash
# Tests unitaires
php artisan test

# Tests spécifiques auth
php artisan test tests/Unit/Auth/
```

## 🔄 Configuration Frontend

### Proxy Angular (proxy.conf.json)
```json
{
  "/api": {
    "target": "http://localhost:8000",
    "secure": false,
    "changeOrigin": true,
    "logLevel": "debug"
  }
}
```

### Service Angular exemple
```typescript
// Base URL configuration
private apiUrl = 'http://localhost:8000/api/v1';

// Headers configuration
private getHeaders() {
  return new HttpHeaders({
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  });
}
```

## 🐛 Dépannage

### Problèmes courants

1. **Erreur CORS**
   - Vérifier la configuration dans `config/cors.php`
   - S'assurer que les origines sont correctement définies

2. **Erreur 419 (CSRF)**
   - Pour les routes API, utiliser le middleware `api` au lieu de `web`
   - Vérifier que les requêtes incluent le token CSRF si nécessaire

3. **Erreur de connexion base de données**
   - Vérifier les credentials dans `.env`
   - S'assurer que la base de données existe

4. **Port déjà utilisé**
   ```bash
   # Changer le port
   php artisan serve --port=8001
   ```

### Commandes de débogage
```bash
# Vérifier les routes
php artisan route:list

# Vérifier la configuration
php artisan config:cache
php artisan config:clear

# Vider les caches
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 📁 Structure du projet

```
appFonctions/
├── app/
│   ├── Adapter/Http/API/Auth/     # Couche API (DDD)
│   ├── Core/Auth/                 # Logique métier
│   ├── Http/Controllers/          # Contrôleurs Laravel
│   ├── Http/Middleware/           # Middleware personnalisé
│   └── Models/                    # Modèles Eloquent
├── config/
│   ├── cors.php                   # Configuration CORS
│   └── sanctum.php               # Configuration Sanctum
├── routes/
│   ├── api_v1.php                # Routes API v1
│   └── api.php                   # Routes API principales
└── tests/Unit/Auth/              # Tests unitaires
```

## ✅ Checklist de démarrage

- [ ] .env configuré avec les bonnes informations
- [ ] Base de données créée et accessible
- [ ] Migrations exécutées
- [ ] Serveur Laravel démarré sur le bon port
- [ ] Frontend configuré avec le bon proxy
- [ ] Tests unitaires passés

## 🚀 Commandes de démarrage rapide

```bash
# Une seule commande pour tout configurer
composer install && php artisan key:generate && php artisan migrate && php artisan db:seed && php artisan serve --host=localhost --port=8000
