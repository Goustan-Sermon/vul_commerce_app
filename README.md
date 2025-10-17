# Chop Shop — Site de vente de bière
**Chop Shop** est un projet pédagogique créé dans le cadre scolaire pour illustrer des vulnérabilités web courantes.  
Cette application e-commerce volontairement vulnérable est destinée à des exercices pratiques.  
**Usage : uniquement en labo isolé / réseau local. Ne jamais exposer ce projet en production.**

## Fonctionnalités clés

- Listing de produits & page produit détaillée.  
- Système de commentaires par produit.  
- Upload d'images dans les commentaires.  
- Gestion simple des utilisateurs et des rôles (`user`, `admin`).  
- Dashboard admin (visuel / démonstration — pas d’actions sensibles en prod).  
- Mécanismes pédagogiques pour détecter les failles (flag).

## Structure du depot

```
.
├── docker-compose.yml
├── db/
│   └── init.sql
├── web/
│   └── vuln-app/
│       ├── index.php
│       ├── product.php
│       ├── post_comment.php
│       ├── db.php
│       ├── admin_dashboard.php
│       ├── dashboard.php
│       ├── login.php
│       ├── logout.php
│       ├── profile.php
│       ├── register.php
│       ├── session.php
│       ├── styles.css
│       └── uploads/ (généré)
└── .env.example
```

## Structure du réseau

                        +------------------+
                        |     vuln-net     |
                        |     (bridge)     |
                        +------------------+
                          |      |      |
                          |      |      |
                          |      |      |
              +-----------+      |      +--------------+
              |                  |                     | 
      +--------------+    +--------------+    +-----------------+
      |   vuln-web   |    |              |    | vuln-phpmyadmin |
      | (Apache+PHP) |    |    vuln-db   |    |   PHPMyAdmin    |
      | Port 8080→80 |    |              |    |  Port 8081→80   |
      +--------------+    +--------------+    +-----------------+
      
## Installation & lancement 
Cloner le projet et accéder au depot
```bash
  git clone https://github.com/Goustan-Sermon/vul_commerce_app.git
  cd vul_commerce_app
```

Copier .env.example dans .env 
```bash
  cp .env.example .env
  # modifier .env si besoin
```
Démmarer les services
```bash
 docker compose up -d --build
```
On accède alors à la page web `http://localhost:8080`

## Redémarrer proprement le projet

Pour redémarrer le lab et réinitialiser la base de données, utilisez toujours la ligne de commande suivante depuis la racine du projet :

```bash
# Arrêter les services et supprimer les volumes (réinitialise la BDD)
docker compose down -v

# Relancer et rebuild les services
docker compose up -d --build
```
<p style="color:red; font-weight:700; font-size:1.05em;">⚠️ GROSSE ATTENTION — NE PAS SUPPRIMER LES CONTENEURS VIA DOCKER DESKTOP (🗑️)</p> <p>Ne supprimez **jamais** les conteneurs directement depuis l'interface Docker Desktop en cliquant sur l'icône poubelle : cela peut laisser les volumes attachés ou entraîner un état incohérent entre conteneurs et volumes. Utilisez toujours les commandes ci‑dessus pour arrêter proprement l'environnement et réinitialiser la base de données.<code>docker compose down -v</code>.</p> 


**Attention**

• phpMyAdmin (port 8081) est fourni pour faciliter le debug ; il n’est pas concerné par le challenge — les vulnérabilités à analyser se trouvent uniquement sur l’interface web (port 8080).

• Si vous utilisez WSL2/Windows, assurez-vous que Docker est actif et que votre shell a accès au daemon Docker.

## Réinitialiser la base de données

Les scripts db/init.sql ne sont exécutés QUE au premier démarrage si le volume de données est vide. Pour forcer la réinitialisation :

```bash
docker compose down -v        # supprime le volume de données
docker compose up -d --build  # recrée tout et exécute init.sql
```
Alternative manuel (via phpmyadmin `localhost:8081`) : supprimer la base vulnshop puis réimporter db/init.sql
