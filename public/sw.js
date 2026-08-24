const CACHE_NAME = 'mater-educatrix-cache-v1';
const urlsToCache = [
    '/',
    '/img/icone512.png'
];

// Instalação do Service Worker e Cache Inicial
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

// Estratégia de Cache: Network First (Rede primeiro, se falhar usa o cache)
// Ideal para sistemas dinâmicos como o Laravel para evitar que o aluno veja dados desatualizados
self.addEventListener('fetch', event => {
    // Ignora requisições que não sejam GET (como POST de assistir aula, login, etc)
    if (event.request.method !== 'GET') return;

    event.respondWith(
        fetch(event.request)
            .catch(() => {
                return caches.match(event.request);
            })
    );
});