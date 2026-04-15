<!DOCTYPE html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;font-family:'Helvetica Neue',Arial,sans-serif;background:#f0f2f5;-webkit-font-smoothing:antialiased;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f2f5;"><tr><td align="center" style="padding:40px 16px;">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);">

    {{-- Header --}}
    <tr><td style="background:#00004D;padding:28px 40px;">
        <table width="100%" cellpadding="0" cellspacing="0"><tr>
            <td><img src="{{ asset('assets/brand/BATID_Logo_blanc.svg') }}" alt="bat-id" style="height:32px;display:block;"></td>
            <td style="text-align:right;color:rgba(255,255,255,0.5);font-size:11px;letter-spacing:0.5px;">DOCUMENTATION API</td>
        </tr></table>
    </td></tr>

    {{-- Badge section --}}
    <tr><td style="padding:32px 40px 0;">
        <table cellpadding="0" cellspacing="0"><tr>
            <td style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;padding:6px 16px;">
                @if($section === 'deeplink')
                    <span style="color:#2563eb;font-size:12px;font-weight:700;letter-spacing:0.5px;">&#9679; API DEEPLINK</span>
                @elseif($section === 'register')
                    <span style="color:#2563eb;font-size:12px;font-weight:700;letter-spacing:0.5px;">&#9679; API INSCRIPTION</span>
                @elseif($section === 'webhook')
                    <span style="color:#d97706;font-size:12px;font-weight:700;letter-spacing:0.5px;">&#9679; WEBHOOK SORTANT</span>
                @elseif($section === 'default')
                    <span style="color:#7c3aed;font-size:12px;font-weight:700;letter-spacing:0.5px;">&#9679; ABONNEMENT PAR DEFAUT</span>
                @elseif($section === 'subscriptions')
                    <span style="color:#059669;font-size:12px;font-weight:700;letter-spacing:0.5px;">&#9679; ABONNEMENTS PUBLIQUES</span>
                @endif
            </td>
        </tr></table>
    </td></tr>

    {{-- Salutation --}}
    <tr><td style="padding:24px 40px 8px;">
        <p style="margin:0;font-size:16px;color:#1a1a2e;font-weight:600;">Bonjour,</p>
    </td></tr>
    <tr><td style="padding:0 40px 28px;">
        @if($section === 'deeplink')
            <p style="margin:0;font-size:14px;color:#555;line-height:1.6;">Voici les informations techniques pour integrer l'<strong style="color:#00004D;">API Deeplink</strong> de bat-id Subscribers. Cette API permet de rediriger un utilisateur directement vers le panier d'achat avec un abonnement pre-selectionne.</p>
        @elseif($section === 'register')
            <p style="margin:0;font-size:14px;color:#555;line-height:1.6;">Voici les informations techniques pour integrer l'<strong style="color:#00004D;">API Inscription</strong> de bat-id Subscribers. Cette API permet de creer un abonne dans le systeme subscribers lors de la creation d'un compte bat-id.</p>
        @elseif($section === 'webhook')
            <p style="margin:0;font-size:14px;color:#555;line-height:1.6;">Voici les informations techniques pour implementer la <strong style="color:#00004D;">reception des webhooks</strong> de bat-id Subscribers. Le systeme envoie automatiquement des notifications lors d'evenements importants.</p>
        @elseif($section === 'default')
            <p style="margin:0;font-size:14px;color:#555;line-height:1.6;">Voici les informations pour recuperer l'<strong style="color:#00004D;">abonnement par defaut</strong> configure dans bat-id Subscribers. Cette API publique permet a vos applications de savoir quel abonnement proposer par defaut.</p>
        @elseif($section === 'subscriptions')
            <p style="margin:0;font-size:14px;color:#555;line-height:1.6;">Voici les informations pour recuperer la <strong style="color:#00004D;">liste des abonnements publiques</strong> de bat-id Subscribers. Cette API publique retourne tous les types d'abonnement en ligne avec leurs tarifs, fonctionnalites et traductions.</p>
        @endif
    </td></tr>

    @if($section !== 'default' && $section !== 'subscriptions')
    {{-- Bloc cle secrete --}}
    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#00004D;border-radius:12px;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:1.5px;">Cle secrete partagee (HMAC-SHA256)</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <p style="margin:0;font-family:'Courier New',monospace;font-size:13px;color:#4ade80;word-break:break-all;line-height:1.6;">{{ $secret }}</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table cellpadding="0" cellspacing="0"><tr>
                    <td style="background:rgba(239,68,68,0.15);border-radius:6px;padding:8px 14px;">
                        <p style="margin:0;font-size:11px;color:#fca5a5;">&#9888; Cette cle est confidentielle. Ne la partagez jamais cote client ou dans le code source public.</p>
                    </td>
                </tr></table>
            </td></tr>
        </table>
    </td></tr>
    @endif

    @if($section === 'deeplink')
    {{-- DEEPLINK --}}
    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Endpoint</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:6px 0;color:#888;width:100px;">Methode</td>
                        <td style="padding:6px 0;font-weight:600;color:#1a1a2e;">GET</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">URL</td>
                        <td style="padding:6px 0;color:#1a1a2e;font-family:'Courier New',monospace;font-size:12px;">{{ $baseUrl }}/deeplink?token={TOKEN}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Expiration</td>
                        <td style="padding:6px 0;color:#1a1a2e;">10 minutes</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Payload du token</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:4px 0;color:#888;width:40px;font-family:'Courier New',monospace;font-weight:700;">p</td>
                        <td style="padding:4px 0;color:#1a1a2e;">Numero de telephone (format international, ex: +41791234567)</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0;color:#888;font-family:'Courier New',monospace;font-weight:700;">b</td>
                        <td style="padding:4px 0;color:#1a1a2e;">Identifiant bat-id de l'utilisateur</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0;color:#888;font-family:'Courier New',monospace;font-weight:700;">t</td>
                        <td style="padding:4px 0;color:#1a1a2e;">ID du type d'abonnement (voir tableau ci-dessous)</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0;color:#888;font-family:'Courier New',monospace;font-weight:700;">d</td>
                        <td style="padding:4px 0;color:#1a1a2e;">Duree en mois : 12, 24 ou 36 (defaut: 12)</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0;color:#888;font-family:'Courier New',monospace;font-weight:700;">ts</td>
                        <td style="padding:4px 0;color:#1a1a2e;">Timestamp Unix en secondes</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    {{-- Types disponibles --}}
    @if(count($types) > 0)
    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Types d'abonnement disponibles</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr style="border-bottom:1px solid #e8eaed;">
                        <td style="padding:6px 0;font-weight:700;color:#888;font-size:11px;">ID</td>
                        <td style="padding:6px 0;font-weight:700;color:#888;font-size:11px;">Nom</td>
                        <td style="padding:6px 0;font-weight:700;color:#888;font-size:11px;">Prix/an</td>
                    </tr>
                    @foreach($types as $type)
                    <tr>
                        <td style="padding:6px 0;font-weight:700;color:#2563eb;font-family:'Courier New',monospace;">{{ $type['id'] }}</td>
                        <td style="padding:6px 0;color:#1a1a2e;">{{ $type['name'] }}</td>
                        <td style="padding:6px 0;color:#1a1a2e;">CHF {{ number_format($type['price'], 2) }}</td>
                    </tr>
                    @endforeach
                </table>
            </td></tr>
        </table>
    </td></tr>
    @endif

    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Points importants</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;font-size:13px;color:#555;line-height:1.8;">
                &#8226; Le token doit etre genere cote serveur (backend), jamais dans l'app mobile<br>
                &#8226; Algorithme : base64url(payload) + HMAC-SHA256 avec la cle secrete<br>
                &#8226; Token valide : l'utilisateur arrive directement au panier, abonnement pre-selectionne<br>
                &#8226; Token invalide/expire : redirection vers la page d'accueil, parcours standard<br>
                &#8226; Upgrade impossible : message d'erreur si l'abonnement actuel est egal ou superieur
            </td></tr>
        </table>
    </td></tr>

    @elseif($section === 'register')
    {{-- REGISTER --}}
    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Endpoint</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:6px 0;color:#888;width:100px;">Methode</td>
                        <td style="padding:6px 0;font-weight:600;color:#1a1a2e;">GET</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">URL</td>
                        <td style="padding:6px 0;color:#1a1a2e;font-family:'Courier New',monospace;font-size:12px;">{{ $baseUrl }}/api/register?token={TOKEN}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Reponse</td>
                        <td style="padding:6px 0;color:#1a1a2e;">JSON</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Payload du token</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:4px 0;color:#888;width:40px;font-family:'Courier New',monospace;font-weight:700;">a</td>
                        <td style="padding:4px 0;color:#1a1a2e;">"register" (valeur fixe, obligatoire)</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0;color:#888;font-family:'Courier New',monospace;font-weight:700;">p</td>
                        <td style="padding:4px 0;color:#1a1a2e;">Numero de telephone (format international)</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0;color:#888;font-family:'Courier New',monospace;font-weight:700;">b</td>
                        <td style="padding:4px 0;color:#1a1a2e;">Identifiant bat-id de l'utilisateur</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0;color:#888;font-family:'Courier New',monospace;font-weight:700;">ts</td>
                        <td style="padding:4px 0;color:#1a1a2e;">Timestamp Unix en secondes</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Codes de reponse</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:6px 0;width:80px;"><span style="background:#dcfce7;color:#16a34a;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;">201</span></td>
                        <td style="padding:6px 0;color:#1a1a2e;">Abonne cree avec succes</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;"><span style="background:#fef2f2;color:#dc2626;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;">409</span></td>
                        <td style="padding:6px 0;color:#1a1a2e;">Conflit — bat-ID ou telephone deja existant</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;"><span style="background:#fef2f2;color:#dc2626;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;">401</span></td>
                        <td style="padding:6px 0;color:#1a1a2e;">Token invalide, expire ou signature incorrecte</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;"><span style="background:#fef2f2;color:#dc2626;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;">400</span></td>
                        <td style="padding:6px 0;color:#1a1a2e;">Token manquant</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Points importants</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;font-size:13px;color:#555;line-height:1.8;">
                &#8226; Meme cle secrete et meme algorithme que le deeplink<br>
                &#8226; Le champ "a":"register" empeche la reutilisation croisee avec un token deeplink<br>
                &#8226; Verification des doublons sur bat-ID ET telephone (y compris abonnes supprimes)<br>
                &#8226; Token a usage unique implicite : le 2e appel echouera avec bat_id_exists<br>
                &#8226; A appeler lors de chaque creation de compte dans l'app bat-id
            </td></tr>
        </table>
    </td></tr>

    @elseif($section === 'webhook')
    {{-- WEBHOOK --}}
    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Endpoint a implementer</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:6px 0;color:#888;width:100px;">Methode</td>
                        <td style="padding:6px 0;font-weight:600;color:#1a1a2e;">GET</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Direction</td>
                        <td style="padding:6px 0;color:#1a1a2e;">Subscribers &#8594; bat-id (sortant)</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Votre endpoint</td>
                        <td style="padding:6px 0;color:#1a1a2e;font-family:'Courier New',monospace;font-size:12px;">https://demo.bat-id.ch/api/subscribers/webhook?token={TOKEN}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Retries</td>
                        <td style="padding:6px 0;color:#1a1a2e;">5 tentatives (1min, 5min, 15min, 1h, 24h)</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Evenements</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:6px 0;font-family:'Courier New',monospace;font-size:12px;color:#16a34a;width:240px;">subscription_activated</td>
                        <td style="padding:6px 0;color:#1a1a2e;">Nouvelle commande validee</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;font-family:'Courier New',monospace;font-size:12px;color:#2563eb;">subscription_upgraded</td>
                        <td style="padding:6px 0;color:#1a1a2e;">Upgrade d'abonnement</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;font-family:'Courier New',monospace;font-size:12px;color:#d97706;">subscription_expiring_soon</td>
                        <td style="padding:6px 0;color:#1a1a2e;">Expire dans 30 jours</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;font-family:'Courier New',monospace;font-size:12px;color:#dc2626;">subscription_expired</td>
                        <td style="padding:6px 0;color:#1a1a2e;">Abonnement expire</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Points importants</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;font-size:13px;color:#555;line-height:1.8;">
                &#8226; Meme cle secrete et meme algorithme que les API Deeplink et Inscription<br>
                &#8226; Le champ "a":"webhook" empeche la reutilisation d'un token deeplink ou register<br>
                &#8226; Votre endpoint doit verifier la signature HMAC avant de traiter l'evenement<br>
                &#8226; Repondre HTTP 200 pour confirmer la reception<br>
                &#8226; Le token contient : commande, features, facture PDF, dates debut/expiration
            </td></tr>
        </table>
    </td></tr>

    @elseif($section === 'default')
    {{-- DEFAULT SUBSCRIPTION --}}
    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Endpoint</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:6px 0;color:#888;width:120px;">Methode</td>
                        <td style="padding:6px 0;font-weight:600;color:#1a1a2e;">GET</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">URL</td>
                        <td style="padding:6px 0;color:#1a1a2e;font-family:'Courier New',monospace;font-size:12px;">{{ $baseUrl }}/api/default-subscription</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Authentification</td>
                        <td style="padding:6px 0;color:#1a1a2e;">Aucune (endpoint public)</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Reponse</td>
                        <td style="padding:6px 0;color:#1a1a2e;">JSON (200) ou erreur (404)</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Points importants</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;font-size:13px;color:#555;line-height:1.8;">
                &#8226; Endpoint public — aucune cle secrete ni authentification requise<br>
                &#8226; Retourne toutes les donnees du type d'abonnement par defaut (prix, features, traductions)<br>
                &#8226; Retourne HTTP 404 si aucun type par defaut n'est configure<br>
                &#8226; Le type par defaut est gere par l'administrateur dans le back-office<br>
                &#8226; Utilisez cette API pour afficher dynamiquement l'offre par defaut dans vos interfaces
            </td></tr>
        </table>
    </td></tr>

    @elseif($section === 'subscriptions')
    {{-- PUBLIC SUBSCRIPTIONS --}}
    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Endpoint</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                    <tr>
                        <td style="padding:6px 0;color:#888;width:120px;">Methode</td>
                        <td style="padding:6px 0;font-weight:600;color:#1a1a2e;">GET</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">URL</td>
                        <td style="padding:6px 0;color:#1a1a2e;font-family:'Courier New',monospace;font-size:12px;">{{ $baseUrl }}/api/subscriptions</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Authentification</td>
                        <td style="padding:6px 0;color:#1a1a2e;">Aucune (endpoint public)</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#888;">Reponse</td>
                        <td style="padding:6px 0;color:#1a1a2e;">JSON Array (200) ou erreur (404)</td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </td></tr>

    <tr><td style="padding:0 40px 20px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f9fb;border-radius:12px;border:1px solid #e8eaed;overflow:hidden;">
            <tr><td style="padding:14px 20px 10px;">
                <p style="margin:0;font-size:10px;font-weight:700;color:#00004D;text-transform:uppercase;letter-spacing:1.5px;">Points importants</p>
            </td></tr>
            <tr><td style="padding:0 20px 16px;font-size:13px;color:#555;line-height:1.8;">
                &#8226; Endpoint public — aucune cle secrete ni authentification requise<br>
                &#8226; Retourne un tableau JSON de tous les types d'abonnement en ligne<br>
                &#8226; Chaque type inclut : prix, rabais, fonctionnalites et traductions multilingues<br>
                &#8226; Tries par ordre d'affichage (sort_order) tel que configure dans le back-office<br>
                &#8226; Retourne HTTP 404 si aucun abonnement n'est en ligne
            </td></tr>
        </table>
    </td></tr>
    @endif

    {{-- Bouton documentation --}}
    <tr><td style="padding:0 40px 12px;" align="center">
        <a href="{{ $baseUrl }}/admin/api/documentation" style="display:inline-block;background:#0051FF;color:#fff;text-decoration:none;padding:12px 32px;border-radius:8px;font-size:13px;font-weight:600;letter-spacing:0.3px;">Consulter la documentation complete</a>
    </td></tr>
    <tr><td style="padding:0 40px 32px;" align="center">
        <p style="margin:0;font-size:11px;color:#999;">La documentation en ligne contient les exemples de code (PHP, Node.js, Dart) et les generateurs de test.</p>
    </td></tr>

    {{-- Footer --}}
    <tr><td style="padding:20px 40px;background:#f8f9fb;border-top:1px solid #e8eaed;">
        <table width="100%" cellpadding="0" cellspacing="0"><tr>
            <td style="font-size:11px;color:#999;line-height:1.5;">
                <strong style="color:#00004D;">bat-id Subscribers</strong><br>
                {{ $baseUrl }}
            </td>
            <td style="text-align:right;font-size:11px;color:#bbb;">
                Bat-i SA<br>
                Rue de l'Hopital 1, 1920 Martigny
            </td>
        </tr></table>
    </td></tr>

</table>
</td></tr></table>
</body></html>
