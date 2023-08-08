<p align="center" style="color:rgb(10,200,30);font-size:100px;">Laravel-Inertia et Tailwind Css</p><svg align="center" class="block fill-current text-white" viewBox="0 0 275.3 50.5" style="height: 25px;"><path d="M231.2 16.1h-17.8l17.2 17.2-17.2 17.2h17.8l17.2-17.2z"></path><path d="M258.1 16.1h-17.8l17.2 17.2-17.2 17.2h17.8l17.2-17.2z"></path><path d="M6 15.3h10.3l-6 34.2H0l6-34.2zm.6-9.1C7.2 2.9 10.3 0 13.7 0s5.7 2.8 5.2 6.2c-.5 3.4-3.7 6.2-7.2 6.2s-5.6-3-5.1-6.2zM54.3 28.5l-3.7 21H40.4L43.8 30c.8-4.4-1.6-6.2-4.9-6.2-3.4 0-6.5 2-7.5 6.6L28 49.5H17.8l6-34.2h10.3l-.5 3.2c2.3-2.6 6.2-4.2 10.1-4.2 6.9.1 12.2 5.1 10.6 14.2zM94.5 32.4c-.1.8-.5 2.7-1.1 4.1H68.9c.6 3.8 3.8 4.8 7 4.8 2.9 0 5.2-.8 7.2-2.7l7.2 5.9c-4 4-8.7 6-15 6-11.8 0-18-8.5-16.3-18.7a20.7 20.7 0 0 1 20.5-17.4c9.8 0 16.9 7.6 15 18zm-9.7-3.7c-.3-3.8-3-5.3-6.2-5.3a8.9 8.9 0 0 0-8.3 5.3h14.5zM123.9 14.6l-2 11.6c-4-.6-10.5.8-11.7 7.8l.1-.4-2.8 15.9H97.3l6-34.2h10.3l-1.1 6.2c2.1-4.7 6.6-6.9 11.4-6.9zM137.8 37.3c-.5 3.1 2 3.3 6.6 2.9l-1.6 9.3c-12.3 1.4-16.9-2.7-15.2-12.2l2.1-12.1h-5.5l1.8-9.9h5.4l1.2-6.5 10.8-3.1-1.7 9.6h7.1l-1.8 9.9h-7l-2.2 12.1zM155.3 15.3h10.3l-6 34.2h-10.3l6-34.2zm.6-9.1c.5-3.3 3.7-6.2 7.1-6.2s5.7 2.8 5.2 6.2c-.5 3.4-3.7 6.2-7.2 6.2s-5.7-3-5.1-6.2zM208.1 15.3l-6 34.2h-10.3l.4-2.3a15.5 15.5 0 0 1-10.3 3.3c-11.1 0-15.3-9.6-13.5-18.9 1.6-8.8 8.6-17.2 19.2-17.2 4.5 0 7.7 1.8 9.6 4.6l.6-3.6h10.3zm-13.2 17.2c.9-5.2-1.9-8.4-6.6-8.4a9.5 9.5 0 0 0-9.5 8.3c-.9 5.1 1.8 8.3 6.6 8.3 4.6.1 8.6-3.1 9.5-8.2z"></path></svg>
<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
##pourquoi inertia


Si les développeurs Laravel devaient construire des SPA web avec Vue avant Inertia, ils devaient configurer des API et renvoyer des données JSON avec Laravel, puis utiliser quelque chose comme AXIOS pour récupérer les données dans les composants Vue. Ils auraient également besoin de quelque chose comme Vue Router pour gérer les routes, ce qui signifierait perdre le routage Laravel, ainsi que les middlewares et les contrôleurs.

Inertia.js, en revanche, permet aux développeurs de construire des applications modernes à page unique Vue, React et Svelte en utilisant un routage et des contrôleurs classiques côté serveur. Inertia a été conçu pour les développeurs de Laravel, Ruby on Rails et Django afin de leur permettre de créer des applications sans modifier leurs techniques de codage consistant à créer des contrôleurs, à extraire des données d’une base de données et à rendre des vues

Grâce à Inertia.js, les développeurs Laravel se sentiront comme chez eux.

##fonctionnement de inertia

Les applications JavaScript à la manière du monolithe
Inertia est une nouvelle approche pour créer des applications Web classiques pilotées par serveur. Nous l'appelons le monolithe moderne.

Inertia vous permet de créer des applications d'une seule page entièrement rendues côté client, sans la complexité associée aux SPA modernes. Pour ce faire, il exploite les modèles côté serveur existants que vous aimez déjà.

Inertia n'a pas de routage côté client et ne nécessite pas d'API. Construisez simplement des contrôleurs et des pages vues comme vous l'avez toujours fait ! Inertia fonctionne très bien avec n'importe quel framework backend, mais il est affiné pour Laravel.

Pas un cadre
Inertia n'est pas un framework, ni un remplacement pour vos frameworks existants côté serveur ou côté client. Au contraire, il est conçu pour fonctionner avec eux. Considérez l'inertie comme une colle qui relie les deux. Inertia le fait via des adaptateurs. Nous avons actuellement trois adaptateurs officiels côté client (React, Vue et Svelte) et deux adaptateurs côté serveur (Laravel et Rails).

Prochaines étapes
Vous voulez en savoir un peu plus avant de plonger ? Découvrez les pages pour qui est-ce et comment ça marche. Ou, si vous êtes prêt à commencer, passez directement aux instructions d'installation.

##tailwindCss

Un cadre CSS utilitaire d'abord rempli de classes telles que flex, pt-4(padding top), text-center et rotate-90 qui peuvent être composées pour créer n'importe quelle conception, directement dans votre balisage.

Tailwind CSS fonctionne en analysant tous vos fichiers HTML, composants JavaScript et tout autre modèle pour les noms de classe, en générant les styles correspondants, puis en les écrivant dans un fichier CSS statique.
It's fast, flexible, and reliable — with zero-runtime.

