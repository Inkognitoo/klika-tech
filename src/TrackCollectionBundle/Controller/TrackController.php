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
        $out['filters']['contents']['singer'][] = ['internal_name' => 'all', 'name' => 'Все'];
        foreach ($singers as $singer) {
            $out['filters']['contents']['singer'][] = $singer->serialize();
        }
        $genres = $em->getRepository('TrackCollectionBundle:Genre')->findAll();
        $out['filters']['contents']['genre'][] = ['internal_name' => 'all', 'name' => 'Все'];
        foreach ($genres as $genre) {
            $out['filters']['contents']['genre'][] = $genre->serialize();
        }
        $years = $em->getRepository('TrackCollectionBundle:Year')->findAll();
        $out['filters']['contents']['year'][] = ['internal_name' => 'all', 'name' => 'Все'];
        foreach ($years as $year) {
            $out['filters']['contents']['year'][] = $year->serialize();
        }

        $track_count = 5;
        $current_page = 1;
        $count = $tracks = $em->createQueryBuilder()->select('COUNT(track)')->from('TrackCollectionBundle:Track', 'track')
            ->getQuery()->getSingleScalarResult();

        $payload = json_decode($request->query->get('payload'), true);

        $tracks = $em->createQueryBuilder()->select('track')->from('TrackCollectionBundle:Track', 'track')
            ->where('1 = 1');

        if (is_array($payload)) {
            if (array_key_exists('filters', $payload)) {
                foreach ($payload['filters'] as $filter) {
                    if (array_key_exists('name', $filter) && array_key_exists('value', $filter)) {
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
            }

            if (array_key_exists('sorts', $payload)) {
                foreach ($payload['sorts'] as $sort) {
                    if (array_key_exists('internal_name', $sort) && array_key_exists('type', $sort)) {
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
                                        $tracks = $tracks->addOrderBy('track.singer', 'ASC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                    case 'desc':
                                        $tracks = $tracks->addOrderBy('track.singer', 'DESC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                }
                                break;
                            case 'genre':
                                switch ($sort['type']) {
                                    case 'asc':
                                        $tracks = $tracks->addOrderBy('track.genre', 'ASC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                    case 'desc':
                                        $tracks = $tracks->addOrderBy('track.genre', 'DESC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                }
                                break;
                            case 'year':
                                switch ($sort['type']) {
                                    case 'asc':
                                        $tracks = $tracks->addOrderBy('track.year', 'ASC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                    case 'desc':
                                        $tracks = $tracks->addOrderBy('track.year', 'DESC');
                                        $out['sorts'][] = ['internal_name' => $sort['internal_name'], 'type' => $sort['type']];
                                        break;
                                }
                                break;
                        }
                    }
                }
            }


            if (array_key_exists('pages', $payload)) {
                if (array_key_exists('track_count', $payload['pages'])) {
                    $track_count = ((int)$payload['pages']['track_count'] > 0) ? (int)$payload['pages']['track_count'] : $track_count;
                }

                if (array_key_exists('current', $payload['pages'])) {
                    $current_page = ((int)$payload['pages']['current'] > 0) ? (int)$payload['pages']['current'] : $current_page;
                }
            }
        }

        $tracks = $tracks->setFirstResult(($current_page - 1) * $track_count)
            ->setMaxResults($track_count)
            ->getQuery()
            ->getResult();

        foreach ($tracks as $track) {
            $out['tracks'][] = $track->serialize();
        }

        $out['pages']['count'] = ($count > 0) ? ceil($count / $track_count) : 0;
        $out['pages']['current'] = $current_page;
        $out['pages']['track_count'] = $track_count;

        return new JsonResponse($out);
    }

}
