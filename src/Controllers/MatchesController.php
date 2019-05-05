<?php

namespace Redline\League\Controllers;

use Redline\League\Helpers\MatchesHelper;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
     * @param null|string $page
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getIndex(?string $page = null): string
    {
        $page = $page ?? 1;
        $matches = $this->matchesHelper->getMatches($page);
        $totalMatches = $this->matchesHelper->getMatchesCount();

        return $this->twig->render('matches.twig', [
            'matches' => $matches,
            'currentPage' => $page,
            'totalPages' => ceil($totalMatches / env('LIMIT'))
        ]);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function postIndex(): string
    {
        $search = input()->post('search')->getValue();

        if (!$search) {
            response()->redirect('/matches');
        }

        $matches = $this->matchesHelper->searchMatches($search);

        return $this->twig->render('matches.twig', [
            'matches' => $matches,
        ]);
    }
}