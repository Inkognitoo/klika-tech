<?php

namespace TrackCollectionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TrackController extends Controller
{
    public function getAction(Request $request)
    {
        //В процессе выполнения скрипта, полностью подготавливаем состояние для клиента
        $em = $this->getDoctrine()->getManager();
        $out = [
            'tracks' => [],
            'sorts' => [],
            'filters' => [
                'contents' => [],
                'variables' => []
            ],
            'pages' => []
        ];
        //Собираем для клиента все возможные вариации фильтров
        $singers = $em->getRepository('TrackCollectionBundle:Singer')->findAll();
        $out['filters']['contents']['singers'][] = ['internal_name' => 'all', 'name' => 'Все'];
        $out['filters']['variables']['singer'] = 'all';
        foreach ($singers as $singer) {
            $out['filters']['contents']['singers'][] = $singer->serialize();
        }
        $genres = $em->getRepository('TrackCollectionBundle:Genre')->findAll();
        $out['filters']['contents']['genres'][] = ['internal_name' => 'all', 'name' => 'Все'];
        $out['filters']['variables']['genre'] = 'all';
        foreach ($genres as $genre) {
            $out['filters']['contents']['genres'][] = $genre->serialize();
        }
        $years = $em->getRepository('TrackCollectionBundle:Year')->findAll();
        $out['filters']['contents']['years'][] = ['internal_name' => 'all', 'name' => 'Все'];
        $out['filters']['variables']['year'] = 'all';
        foreach ($years as $year) {
            $out['filters']['contents']['years'][] = $year->serialize();
        }

        $track_count = 5;
        $current_page = 1;
        $count = $tracks = $em->createQueryBuilder()->select('COUNT(track)')->from('TrackCollectionBundle:Track', 'track')
            ->getQuery()->getSingleScalarResult();

        /*
         * Я долго думал, как передавать всё многобразие команд от клиента одним запросом. По логике, запрос - типа GET,
         * не самый удобный транспорт для множества параметров, особенно ассоциативных массивов. В итоге я решил, что
         * буду просто отдавать один единственный параметр payload, но содержищай в себе json со всеми необходимыми
         * командами.
         */
        $payload = json_decode($request->query->get('payload'), true);

        $tracks = $em->createQueryBuilder()->select('track', 'year', 'genre', 'singer')->from('TrackCollectionBundle:Track', 'track')
            ->innerJoin('track.year', 'year')
            ->innerJoin('track.genre', 'genre')
            ->innerJoin('track.singer', 'singer')
            ->where('1 = 1'); //это нужно для привязки последующих andWhere()

        if (is_array($payload)) {
            /*
             * Я решил не разбивать обработку запроса на отдельные методы, так как код получился не слишком длинным,
             * визуально разделённым и в процессе модифицирующим один и тот же sql запрос
             */

            /*__ Обработка фильров __________________________________________________________________________________ */
            if (array_key_exists('filters', $payload)) {
                foreach ($payload['filters'] as $filter) {
                    if (is_array($filter) && array_key_exists('name', $filter) && array_key_exists('value', $filter)) {
                        switch ($filter['name']) {
                            case 'singer':
                                /* Я не успел найти способа обозначить через symfony базе данных, что internal_name -
                                 * уникальный индекс, но представим, что он таковым является
                                 */
                                $singer = $em->getRepository('TrackCollectionBundle:Singer')
                                    ->findOneBy(['internal_name' => $filter['value']]);
                                if ($singer) {
                                    $tracks = $tracks->andWhere('track.singer = :singer')
                                        ->setParameter('singer', $singer);
                                    $out['filters']['variables']['singer'] = $singer->getInternalName();
                                }
                                break;
                            case 'genre':
                                $genre = $em->getRepository('TrackCollectionBundle:Genre')
                                    ->findOneBy(['internal_name' => $filter['value']]);
                                if ($genre) {
                                    $tracks = $tracks->andWhere('track.genre = :genre')
                                        ->setParameter('genre', $genre);
                                    $out['filters']['variables']['genre'] = $genre->getInternalName();
                                }
                                break;
                            case 'year':
                                $year = $em->getRepository('TrackCollectionBundle:Year')
                                    ->findOneBy(['internal_name' => $filter['value']]);
                                if ($year) {
                                    $tracks = $tracks->andWhere('track.year = :year')
                                        ->setParameter('year', $year);
                                    $out['filters']['variables']['year'] = $year->getInternalName();
                                }
                                break;
                        }
                    }
                }
                $_count = $tracks->getQuery()
                    ->getResult();
                $count = count($_count);
            }

            /*__ Обработка сортировок _______________________________________________________________________________ */
            if (array_key_exists('sorts', $payload)) {
                foreach ($payload['sorts'] as $sort) {
                    if (is_array($sort) && array_key_exists('internal_name', $sort) && array_key_exists('type', $sort)) {
                        switch ($sort['internal_name']) {
                            case 'name':
                                switch ($sort['type']) {
                                    case 'asc':
                                        $tracks = $tracks->addOrderBy('track.name', 'ASC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                    case 'desc':
                                        $tracks = $tracks->addOrderBy('track.name', 'DESC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                }
                                break;
                            case 'singer':
                                switch ($sort['type']) {
                                    case 'asc':
                                        $tracks = $tracks->addOrderBy('singer.name', 'ASC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                    case 'desc':
                                        $tracks = $tracks->addOrderBy('singer.name', 'DESC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                }
                                break;
                            case 'genre':
                                switch ($sort['type']) {
                                    case 'asc':
                                        $tracks = $tracks->addOrderBy('genre.name', 'ASC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                    case 'desc':
                                        $tracks = $tracks->addOrderBy('genre.name', 'DESC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                }
                                break;
                            case 'year':
                                switch ($sort['type']) {
                                    case 'asc':
                                        $tracks = $tracks->addOrderBy('year.name', 'ASC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                    case 'desc':
                                        $tracks = $tracks->addOrderBy('year.name', 'DESC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                }
                                break;
                        }
                    }
                }
            }
            /*
             * При отсутствии сортировок, в базе данных postgresql doctrine2 как-то странно себя вёл, как будто бы
             * запоминая предыдущие сортировки, не знаю с чем конкретно это связано, но сортировка по умолчанию
             * позволяет этого избежать
             */
            if(count($out['sorts']) == 0) {
                $tracks = $tracks->addOrderBy('track.id', 'ASC');
            }

            /*__ Обработка пагинации ________________________________________________________________________________ */
            if (array_key_exists('pages', $payload)) {
                if (is_array($payload['pages']) && array_key_exists('tracks_count', $payload['pages'])) {
                    $track_count = ((int)$payload['pages']['tracks_count'] > 0) ? (int)$payload['pages']['tracks_count'] : $track_count;
                }

                if (is_array($payload['pages']) && array_key_exists('current', $payload['pages'])) {
                    $current_page = ((int)$payload['pages']['current'] > 0) ? (int)$payload['pages']['current'] : $current_page;
                }
            }
        }

        //Если клиент запрашивает несуществующую страницу, отдаём ему последнюю из имеющихся
        $count = ($count > 0) ? ceil($count / $track_count) : 0;
        if ($current_page > $count) {
            $current_page = $count;
        }

        $tracks = $tracks->setFirstResult(($current_page - 1) * $track_count)
            ->setMaxResults($track_count)
            ->getQuery()
            ->getResult();

        foreach ($tracks as $track) {
            $out['tracks'][] = $track->serialize();
        }

        $out['pages']['count'] = $count;
        $out['pages']['current'] = $current_page;
        $out['pages']['tracks_count'] = $track_count;

        return new JsonResponse($out);
    }

}
