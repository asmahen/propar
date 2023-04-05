<h1> Lancer le projet </h1>

- Installer chocolatery pour le terminal powershell
    - cela permet d'utiliser le fichier makefile pour aller plus vite

- Installation de chocolatey et make :
    -> Ouvrir powershell en administrateur
    -> taper : Set-ExecutionPolicy Bypass -Scope Process -Force; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
    -> taper : choco
        -> si on voit la version de chocolatey c'est good
    ->choco install make
    ->vérifier la version de make en tapant make --version

- taper dans le terminal (attention lancer le serveur de BDD avec xammp ou wammp et configurer le fichier .env pour la base de données)
    - make init 
        -> cela lance composer, update, et npm
    - make start
        -> lance le serveur de symfony et lance npm
    - make db
        -> permet de créer la base de donnèes, créer les tables et les fixtures
    

- Si on ne souhaite pas passer par le makeFile dans le terminal (attention lancer le serveur xampp, wammp de bdd et configurer le .env avant pour lma bdd)
    taper la liste des commande suivantes:
        - composer install
        - composer update
        - npm install
        - php bin/console server:start
        - php bin/console doctrine:database:create
        - php bin/console doctrine:migrations:migrate (yes)
        - php bin/console doctrine:fixtures:load (yes)
        - npm run dev

normalement tout devrait s'être bien passé. 


<h1> Ouvrir le projet sur le navigateur </h1>

    - https://127.0.0.1:8000 ou bien localhost:8000
    - on arrive sur la page d'accueil

    - connection Administrateur : l'admin à accès à tout (operations, chiffres d'affaires, créer des seniors et apprenti)
        email : admin@gmail.com
        mdp : Password
    - connecction senior : le sénior peut voir la liste des opérations, créer, modifier, terminer des operations 
        email: senior@gmail.com
        mdp: Password
    - connection apprenti: l'apprenti n epeux que consulter les operations
        email: apprenti@gmail.com
        mdp: Password