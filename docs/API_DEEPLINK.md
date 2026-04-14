# API — bat-id Subscribers

> Documentation pour le développeur bat-id
> Dernière mise à jour : 15 avril 2026

---

## Principe

L'application mobile bat-id peut rediriger un utilisateur directement vers le panier d'achat d'abonnement subscribers, **sans qu'il ait besoin de choisir un abonnement ni de saisir son numéro de téléphone**.

Le lien contient un **token signé** (HMAC-SHA256) qui encode le bat-ID de l'utilisateur, son numéro de téléphone, le type d'abonnement et la durée souhaitée. Aucun appel API intermédiaire n'est nécessaire — les informations du token signé font foi. Le token expire après **10 minutes** par défaut.

---

## URL

```
GET https://subscribers-test.apcom.app/deeplink?token={TOKEN}
```

Production (quand prêt) :
```
GET https://subscribers.bat-i.ch/deeplink?token={TOKEN}
```

---

## Construction du token

Le token se compose de deux parties séparées par un point :

```
base64url(payload).hmac_sha256(base64url(payload), secret)
```

### Payload JSON

```json
{
  "p": "+41791234567",   // Numéro de téléphone (format international, avec +)
  "b": "@iGgUwLLc",   // Identifiant bat-id de l'utilisateur
  "t": 2,                // ID du type d'abonnement (voir ci-dessous)
  "d": 12,               // Durée en mois : 12, 24 ou 36 (optionnel, défaut: 12)
  "ts": 1713200000       // Timestamp Unix en secondes
}
```

### Algorithme

1. Construire le payload JSON (compact, sans espaces)
2. Encoder en **base64url** :
   - Base64 standard
   - Remplacer `+` par `-`
   - Remplacer `/` par `_`
   - Supprimer le padding `=`
3. Calculer le **HMAC-SHA256** de la chaîne base64url avec la **clé secrète partagée**
4. Concaténer : `{base64url}.{signature_hex}`

### Clé secrète

La clé secrète est partagée entre le backend bat-id et subscribers. Elle est configurée dans la variable d'environnement `DEEPLINK_SECRET` côté subscribers.

**Important** : le token doit être généré **côté serveur** (backend bat-id). La clé secrète ne doit jamais être exposée dans le code client ou l'application mobile.

---

## Types d'abonnement

| ID (valeur `t`) | Nom | Prix CHF/an |
|:---:|---|---:|
| 1 | Starter | 49.00 |
| 2 | Premium | 149.00 |
| 3 | Enterprise | 349.00 |

> Ces IDs peuvent évoluer. La page de documentation dans le back-office admin affiche toujours les valeurs à jour.

---

## Exemples d'implémentation

### PHP

```php
$secret = 'VOTRE_CLE_SECRETE_PARTAGEE';

$payload = json_encode([
    'p' => '+41791234567',
    'b' => '@iGgUwLLc',
    't' => 2,    // Premium
    'd' => 12,   // 12 mois
    'ts' => time(),
]);

$encoded = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
$signature = hash_hmac('sha256', $encoded, $secret);
$token = $encoded . '.' . $signature;

$url = 'https://subscribers-test.apcom.app/deeplink?token=' . $token;
```

### JavaScript / Node.js

```javascript
const crypto = require('crypto');

const secret = 'VOTRE_CLE_SECRETE_PARTAGEE';

const payload = JSON.stringify({
  p: '+41791234567',
  b: '@iGgUwLLc',
  t: 2,
  d: 12,
  ts: Math.floor(Date.now() / 1000)
});

const encoded = Buffer.from(payload)
  .toString('base64')
  .replace(/\+/g, '-')
  .replace(/\//g, '_')
  .replace(/=+$/, '');

const signature = crypto
  .createHmac('sha256', secret)
  .update(encoded)
  .digest('hex');

const url = `https://subscribers-test.apcom.app/deeplink?token=${encoded}.${signature}`;
```

### Dart / Flutter

```dart
import 'dart:convert';
import 'package:crypto/crypto.dart';

final secret = 'VOTRE_CLE_SECRETE_PARTAGEE';

final payload = jsonEncode({
  'p': '+41791234567',
  'b': '@iGgUwLLc',
  't': 2,
  'd': 12,
  'ts': DateTime.now().millisecondsSinceEpoch ~/ 1000,
});

final encoded = base64Url.encode(utf8.encode(payload)).replaceAll('=', '');
final hmac = Hmac(sha256, utf8.encode(secret));
final signature = hmac.convert(utf8.encode(encoded)).toString();

final url = 'https://subscribers-test.apcom.app/deeplink?token=$encoded.$signature';
```

---

## Comportement

| Scénario | Résultat |
|---|---|
| Token valide | Redirection directe vers le panier avec abonnement pré-sélectionné |
| Upgrade impossible (abonnement actif de valeur >= sélectionné) | Message d'erreur standard, choix d'un autre abonnement |
| Token invalide ou expiré | Redirection vers la page d'accueil (parcours standard) |
| Type d'abonnement inexistant ou hors ligne | Redirection vers la page d'accueil |

---

## Sécurité

- **Signature HMAC-SHA256** — toute modification du payload invalide la signature
- **Expiration** — le token expire après 10 minutes (configurable)
- **Données du token** — le bat-ID et le téléphone proviennent du token signé, aucun appel API intermédiaire
- **Clé secrète** — à transmettre de manière sécurisée, ne jamais exposer côté client
- **Génération côté serveur uniquement** — le token doit être généré par le backend bat-id, pas par l'application mobile

---

## Test

Un générateur de token de test est disponible dans le back-office admin :
**Admin > Logs & API > Documentation API > Générateur de test**

---

# API 2 — Inscription (création d'abonné)

## Principe

Le backend bat-id peut créer un abonné dans le système subscribers en appelant une URL signée. Le système vérifie qu'aucun abonné avec le même bat-ID ou le même numéro de téléphone n'existe déjà. La réponse est un JSON structuré.

---

## URL

```
GET https://subscribers-test.apcom.app/api/register?token={TOKEN}
```

Production :
```
GET https://subscribers.bat-i.ch/api/register?token={TOKEN}
```

---

## Payload JSON

```json
{
  "a": "register",       // Action (obligatoire, valeur fixe)
  "p": "+41791234567",   // Numéro de téléphone (format international)
  "b": "@iGgUwLLc",     // Identifiant bat-id de l'utilisateur
  "ts": 1713200000       // Timestamp Unix en secondes
}
```

Le champ `"a": "register"` distingue ce token d'un token deeplink. Un token deeplink ne peut pas être utilisé pour inscrire un abonné et vice-versa.

L'algorithme de signature est identique au deeplink (base64url + HMAC-SHA256 avec la même clé secrète).

---

## Exemples

### PHP

```php
$secret = 'VOTRE_CLE_SECRETE_PARTAGEE';

$payload = json_encode([
    'a' => 'register',
    'p' => '+41791234567',
    'b' => '@iGgUwLLc',
    'ts' => time(),
]);

$encoded = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
$signature = hash_hmac('sha256', $encoded, $secret);
$token = $encoded . '.' . $signature;

$response = file_get_contents('https://subscribers-test.apcom.app/api/register?token=' . $token);
$result = json_decode($response, true);
```

### JavaScript / Node.js

```javascript
const crypto = require('crypto');

const secret = 'VOTRE_CLE_SECRETE_PARTAGEE';

const payload = JSON.stringify({
  a: 'register',
  p: '+41791234567',
  b: '@iGgUwLLc',
  ts: Math.floor(Date.now() / 1000)
});

const encoded = Buffer.from(payload)
  .toString('base64')
  .replace(/\+/g, '-')
  .replace(/\//g, '_')
  .replace(/=+$/, '');

const signature = crypto
  .createHmac('sha256', secret)
  .update(encoded)
  .digest('hex');

const url = `https://subscribers-test.apcom.app/api/register?token=${encoded}.${signature}`;
const res = await fetch(url);
const result = await res.json();
```

---

## Réponses JSON

### Succès — HTTP 201

```json
{
  "status": "success",
  "message": "Abonné créé avec succès.",
  "subscriber": {
    "id": 42,
    "bat_id": "@iGgUwLLc",
    "phone": "+41791234567",
    "created_at": "2026-04-15T14:30:00+02:00"
  }
}
```

### Conflit bat-ID — HTTP 409

```json
{
  "status": "error",
  "code": "bat_id_exists",
  "message": "Un abonné avec ce bat-ID existe déjà.",
  "bat_id": "@iGgUwLLc"
}
```

### Conflit téléphone — HTTP 409

```json
{
  "status": "error",
  "code": "phone_exists",
  "message": "Un abonné avec ce numéro de téléphone existe déjà.",
  "phone": "+41791234567"
}
```

### Token invalide — HTTP 401

```json
{
  "status": "error",
  "code": "invalid_token",
  "message": "Token invalide, expiré ou signature incorrecte."
}
```

### Token manquant — HTTP 400

```json
{
  "status": "error",
  "code": "missing_token",
  "message": "Le paramètre token est requis."
}
```

---

## Sécurité

- Même clé secrète et même algorithme que le deeplink
- Le champ `"a": "register"` empêche la réutilisation d'un token deeplink
- Vérification des doublons sur bat-ID ET téléphone (y compris les abonnés supprimés)
- Token à usage unique implicite : le deuxième appel échouera avec `bat_id_exists`

---

## Contact

Pour obtenir la clé secrète partagée ou toute question technique :
**Gregory Liand** — gregory.liand@apcom.ch
