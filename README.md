# Chop Shop — labo vulnérable (README.md)

> **Important — usage pédagogique uniquement**  
> Cette application contient volontairement des vulnérabilités (SQLi, stored XSS, XXE). **N’exécute ceci que dans un environnement local et isolé.** Ne déploie jamais cette version sur Internet.

---

## Sommaire

- [But du projet](#but-du-projet)  
- [Structure du dépôt](#structure-du-dépôt)  
- [Ne jamais committer de secrets](#ne-jamais-committer-de-secrets)  
- [Installation & lancement (Docker Compose)](#installation--lancement-docker-compose)  
- [Réinitialiser la base de données](#réinitialiser-la-base-de-données)  
- [Flags pédagogiques](#flags-pédagogiques)  
- [Payloads d’exemple (TP)](#payloads-dexemple-tp)  
- [XXE via SVG (double-extension)](#xxe-via-svg-double-extension)  
- [Fix / mitigation (branch `fixed`)](#fix--mitigation-branch-fixed)  
- [Debug & tips](#debug--tips)  
- [Règles d’usage du labo](#règles-dusage-du-labo)  
- [Fichiers utiles à ajouter au dépôt](#fichiers-utiles-à-ajouter-au-dépôt)  
- [Étapes optionnelles pour l’enseignant](#étapes-optionnelles-pour-lenseignant)

---

## But du projet

`Chop Shop` est une application e-commerce pédagogique conçue pour permettre l’apprentissage pratique des vulnérabilités web courantes et de leurs correctifs :

- **SQL Injection** (barre de recherche)  
- **Stored XSS** (commentaires)  
- **XXE** via parsing de SVG/XML uploadés

L’objectif : exploiter les failles dans un labo local, comprendre l’impact, puis corriger et comparer.

---

## Structure du dépôt

