# Bilemo
BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.

Vous êtes en charge du développement de la vitrine de téléphones mobiles de l’entreprise BileMo. Le business modèle de BileMo n’est pas de vendre directement ses produits sur le site web, mais de fournir à toutes les plateformes qui le souhaitent l’accès au catalogue via une API (Application Programming Interface). Il s’agit donc de vente exclusivement en B2B (business to business).

Il va falloir que vous exposiez un certain nombre d’API pour que les applications des autres plateformes web puissent effectuer des opérations.

Il doit être possible de :

- consulter la liste des produits BileMo
- consulter les détails d’un produit BileMo
- consulter la liste des utilisateurs inscrits liés à un client sur le site web
- consulter le détail d’un utilisateur inscrit lié à un client
- ajouter un nouvel utilisateur lié à un client
- supprimer un utilisateur ajouté par un client.

Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent être authentifiés via OAuth ou JWT.

## Run Locally

Clone the project

```bash
  git@github.com:Redpanda2/P7-Bilemo-Apip.git
```

Run the docker-compose

```bash
  docker-compose up -d --build
```

### Install the project
```
composer install
```

Configure database connexion(no password required)
```yaml
  DATABASE_URL="mysql://127.0.0.1:3308/bilemo?sslmode=disable&charset=utf8mb4"
```

Create database and install it (in www container)
```yaml
  php bin/console doctrine:database:create
```

Update schema
```yaml
  php bin/console doctrine:schema:update -f
```

Load Fixtures
```yaml
  php bin/console doctrine:fixtures:load --no-interaction
```

Reload Fixtures after change
```yaml
  composer prepare
```

Start the docker container
```yaml
  docker start <container_name>
```

Start the HTTP server in daemon
```yaml
  symfony serve -d
```

*Application is available at http://127.0.0.1:8000

Credentials to login
```yaml
  username: client1@test.fr
  password: '12345678'
```

## Requirements
- PHP 8.0
- Docker
- Docker-compose