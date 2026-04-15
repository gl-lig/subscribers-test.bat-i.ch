<?php

namespace Database\Seeders;

use App\Models\SubscriptionType;
use App\Models\SubscriptionTypeTranslation;
use Illuminate\Database\Seeder;

class SubscriptionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            // ID 1 — Découverte (gratuit, par défaut, non visible en front)
            [
                'status' => 'online',
                'sort_order' => 1,
                'is_free' => true,
                'is_default' => true,
                'price_chf' => 0.00,
                'discount_24_months' => 0,
                'discount_36_months' => 0,
                'parcelles_count' => 1,
                'parcelles_unlimited' => false,
                'alertes_count' => 0,
                'stockage_go' => 0,
                'stockage_unlimited' => false,
                'cloud_externe' => false,
                'lot_sauvegarde' => false,
                'veille_robotisee' => false,
                'veille_count' => null,
                'veille_unlimited' => false,
                'workspace_enabled' => false,
                'workspace_count' => null,
                'workspace_unlimited' => false,
                'translations' => [
                    'fr' => ['name' => 'Découverte', 'description' => "Accès gratuit pour découvrir bat-id"],
                    'de' => ['name' => 'Entdeckung', 'description' => 'Kostenloser Zugang um bat-id zu entdecken'],
                ],
            ],
            // ID 2 — Veille passive (payant, non visible en front car is_free=false mais affiché selon prix)
            [
                'status' => 'online',
                'sort_order' => 2,
                'is_free' => false,
                'is_default' => false,
                'price_chf' => 30.00,
                'discount_24_months' => 15,
                'discount_36_months' => 15,
                'parcelles_count' => null,
                'parcelles_unlimited' => true,
                'alertes_count' => 5,
                'stockage_go' => 5,
                'stockage_unlimited' => false,
                'cloud_externe' => false,
                'lot_sauvegarde' => false,
                'veille_robotisee' => false,
                'veille_count' => null,
                'veille_unlimited' => false,
                'workspace_enabled' => false,
                'workspace_count' => null,
                'workspace_unlimited' => false,
                'translations' => [
                    'fr' => ['name' => 'Veille passive', 'description' => "L'essentiel pour utiliser une veille dormante sans autres fonctionnalités"],
                    'de' => ['name' => 'Passive Überwachung', 'description' => 'Das Wesentliche für eine passive Überwachung ohne weitere Funktionen'],
                ],
            ],
            // ID 3 — Propriétaire
            [
                'status' => 'online',
                'sort_order' => 3,
                'is_free' => false,
                'is_default' => false,
                'price_chf' => 60.00,
                'discount_24_months' => 15,
                'discount_36_months' => 15,
                'parcelles_count' => null,
                'parcelles_unlimited' => true,
                'alertes_count' => 5,
                'stockage_go' => 10,
                'stockage_unlimited' => false,
                'cloud_externe' => true,
                'lot_sauvegarde' => true,
                'veille_robotisee' => true,
                'veille_count' => null,
                'veille_unlimited' => false,
                'workspace_enabled' => false,
                'workspace_count' => null,
                'workspace_unlimited' => false,
                'translations' => [
                    'fr' => ['name' => 'Propriétaire', 'description' => "Pour gérer votre patrimoine immobilier personnel"],
                    'de' => ['name' => 'Eigent��mer', 'description' => 'Für die Verwaltung Ihres persönlichen Immobilienbesitzes'],
                ],
            ],
            // ID 4 — Famille & patrimoine
            [
                'status' => 'online',
                'sort_order' => 4,
                'is_free' => false,
                'is_default' => false,
                'price_chf' => 144.00,
                'discount_24_months' => 15,
                'discount_36_months' => 15,
                'parcelles_count' => null,
                'parcelles_unlimited' => true,
                'alertes_count' => 20,
                'stockage_go' => 60,
                'stockage_unlimited' => false,
                'cloud_externe' => true,
                'lot_sauvegarde' => true,
                'veille_robotisee' => true,
                'veille_count' => null,
                'veille_unlimited' => true,
                'workspace_enabled' => true,
                'workspace_count' => 5,
                'workspace_unlimited' => false,
                'translations' => [
                    'fr' => ['name' => 'Famille & patrimoine', 'description' => "Gestion patrimoniale pour toute la famille"],
                    'de' => ['name' => 'Familie & Vermögen', 'description' => 'Vermögensverwaltung für die ganze Familie'],
                ],
            ],
            // ID 5 — Pro & immobilier
            [
                'status' => 'online',
                'sort_order' => 5,
                'is_free' => false,
                'is_default' => false,
                'price_chf' => 360.00,
                'discount_24_months' => 15,
                'discount_36_months' => 15,
                'parcelles_count' => null,
                'parcelles_unlimited' => true,
                'alertes_count' => 50,
                'stockage_go' => 100,
                'stockage_unlimited' => false,
                'cloud_externe' => true,
                'lot_sauvegarde' => true,
                'veille_robotisee' => true,
                'veille_count' => null,
                'veille_unlimited' => true,
                'workspace_enabled' => true,
                'workspace_count' => null,
                'workspace_unlimited' => true,
                'translations' => [
                    'fr' => ['name' => 'Pro & immobilier', 'description' => "La solution complète pour les professionnels de l'immobilier"],
                    'de' => ['name' => 'Pro & Immobilien', 'description' => 'Die komplette Lösung für Immobilienprofis'],
                ],
            ],
        ];

        foreach ($types as $typeData) {
            $translations = $typeData['translations'];
            unset($typeData['translations']);

            $type = SubscriptionType::create($typeData);

            foreach ($translations as $locale => $trans) {
                SubscriptionTypeTranslation::create([
                    'subscription_type_id' => $type->id,
                    'locale' => $locale,
                    'name' => $trans['name'],
                    'description' => $trans['description'],
                ]);
            }
        }
    }
}
