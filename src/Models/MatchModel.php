<?php

namespace Redline\League\Models;

class MatchModel
{
    /**
     * @var int
     */
    protected $matchId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $steamId;

    /**
     * @var int
     */
    protected $team;

    /**
     * @var int
     */
    protected $alive;

    /**
     * @var int
     */
    protected $ping;

    /**
     * @var int
     */
    protected $account;

    /**
     * @var int
     */
    protected $kills;

    /**
     * @var int
     */
    protected $assists;

    /**
     * @var int
     */
    protected $deaths;

    /**
     * @var int
     */
    protected $mvps;

    /**
     * @var int
     */
    protected $score;

    /**
     * @return int
     */
    public function getMatchId(): int
    {
        return $this->matchId;
    }

    /**
     * @param int $matchId
     */
    public function setMatchId(int $matchId): void
    {
        $this->matchId = $matchId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getSteamId(): int
    {
        return $this->steamId;
    }

    /**
     * @param int $steamId
     */
    public function setSteamId(int $steamId): void
    {
        $this->steamId = $steamId;
    }

    /**
     * @return int
     */
    public function getTeam(): int
    {
        return $this->team;
    }

    /**
     * @param int $team
     */
    public function setTeam(int $team): void
    {
        $this->team = $team;
    }

    /**
     * @return int
     */
    public function getAlive(): int
    {
        return $this->alive;
    }

    /**
     * @param int $alive
     */
    public function setAlive(int $alive): void
    {
        $this->alive = $alive;
    }

    /**
     * @return int
     */
    public function getPing(): int
    {
        return $this->ping;
    }

    /**
     * @param int $ping
     */
    public function setPing(int $ping): void
    {
        $this->ping = $ping;
    }

    /**
     * @return int
     */
    public function getAccount(): int
    {
        return $this->account;
    }

    /**
     * @param int $account
     */
    public function setAccount(int $account): void
    {
        $this->account = $account;
    }

    /**
     * @return int
     */
    public function getKills(): int
    {
        return $this->kills;
    }

    /**
     * @param int $kills
     */
    public function setKills(int $kills): void
    {
        $this->kills = $kills;
    }

    /**
     * @return int
     */
    public function getAssists(): int
    {
        return $this->assists;
    }

    /**
     * @param int $assists
     */
    public function setAssists(int $assists): void
    {
        $this->assists = $assists;
    }

    /**
     * @return int
     */
    public function getDeaths(): int
    {
        return $this->deaths;
    }

    /**
     * @param int $deaths
     */
    public function setDeaths(int $deaths): void
    {
        $this->deaths = $deaths;
    }

    /**
     * @return int
     */
    public function getMvps(): int
    {
        return $this->mvps;
    }

    /**
     * @param int $mvps
     */
    public function setMvps(int $mvps): void
    {
        $this->mvps = $mvps;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score): void
    {
        $this->score = $score;
    }
}