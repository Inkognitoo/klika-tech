<?php

namespace TrackCollectionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TrackController extends Controller
{
    public function getAction(Request $request)
    {
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

        $payload = json_decode($request->query->get('payload'), true);

        $tracks = $em->createQueryBuilder()->select('track', 'year', 'genre', 'singer')->from('TrackCollectionBundle:Track', 'track')
            ->innerJoin('track.year', 'year')
            ->innerJoin('track.genre', 'genre')
            ->innerJoin('track.singer', 'singer')
            ->where('1 = 1')
            ->addOrderBy('track.id', 'ASC');

        if (is_array($payload)) {
            if (array_key_exists('filters', $payload)) {
                foreach ($payload['filters'] as $filter) {
                    if (is_array($filter) && array_key_exists('name', $filter) && array_key_exists('value', $filter)) {
                        switch ($filter['name']) {
                            case 'singer':
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


            if (array_key_exists('pages', $payload)) {
                if (is_array($payload['pages']) && array_key_exists('tracks_count', $payload['pages'])) {
                    $track_count = ((int)$payload['pages']['tracks_count'] > 0) ? (int)$payload['pages']['tracks_count'] : $track_count;
                }

                if (is_array($payload['pages']) && array_key_exists('current', $payload['pages'])) {
                    $current_page = ((int)$payload['pages']['current'] > 0) ? (int)$payload['pages']['current'] : $current_page;
                }
            }
        }

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
