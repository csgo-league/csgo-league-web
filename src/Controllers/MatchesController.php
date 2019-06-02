<?php

namespace B3none\League\Controllers;

use B3none\League\Helpers\ExceptionHelper;
use B3none\League\Helpers\MatchesHelper;
use Exception;

class MatchesController extends BaseController
{
    /**
     * @var MatchesHelper
     */
    protected $matchesHelper;

    /**
     * MatchController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->matchesHelper = new MatchesHelper();
    }

    /**
     * @param string|null $page
     * @return string
     */
    public function getIndex(?string $page = null): string
    {
        try {
            $page = $page ?? 1;

            if ($page != (int)$page) {
                throw new Exception('Please only pass an integer to the page parameter');
            }

            if ($page < 1) {
                response()->redirect('/matches');
            }

            $totalMatches = $this->matchesHelper->getMatchesCount();
            $totalPages = ceil($totalMatches / env('MATCHES_PAGE_LIMIT'));

            if ($page > $totalPages && $totalPages > 0) {
                response()->redirect('/matches/' . $totalPages);
            }

            $matches = $this->matchesHelper->getMatches($page);

            return $this->twig->render('matches.twig', [
                'nav' => [
                    'active' => 'matches',
                    'loggedIn' => $this->steam->loggedIn(),
                    'user' => $this->authorisedUser
                ],
                'baseTitle' => env('BASE_TITLE'),
                'title' => 'Matches',
                'matches' => $matches,
                'pagination' => [
                    'currentPage' => $page,
                    'totalPages' => $totalPages,
                    'link' => 'matches'
                ]
            ]);
        } catch (Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }

    /**
     * @return string
     */
    public function postIndex(): string
    {
        try {
            $search = input()->post('search')->getValue();

            if (!$search) {
                response()->redirect('/matches');
            }

            $matches = $this->matchesHelper->searchMatches($search);

            return $this->twig->render('matches.twig', [
                'nav' => [
                    'active' => 'matches'
                ],
                'baseTitle' => env('BASE_TITLE'),
                'title' => 'Matches',
                'description' => env('DESCRIPTION'),
                'matches' => $matches,
                'searchedValue' => $search
            ]);
        } catch (Exception $exception) {
            ExceptionHelper::handle($exception);
        }
    }
}
