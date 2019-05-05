<?php

namespace Redline\League\Helpers;


use B3none\SteamIDConverter\Client as Converter;

class PlayersHelper extends BaseHelper
{
    const TABLE = 'sql_matches';

    /**
     * @var Converter
     */
    protected $converter;

    public function __construct()
    {
        parent::__construct();

        $this->converter = Converter::create();

        // This is a filthy hack to make sure that all of our players have a steam64 id
        $this->updatePlayers();
    }

    /**
     * @param int $page
     * @return array
     */
    public function getPlayers(int $page): array
    {
        try {
            $limit = env('LIMIT');
            $offset = ($page - 1) * $limit;

            $query = $this->db->query("SELECT * FROM ". self::TABLE ." ORDER BY sql_matches_scoretotal.timestamp DESC LIMIT :offset, :limit", [
                ':offset' => $offset,
                ':limit' => (int)$limit
            ]);

            $response = $query->fetchAll();

            foreach ($response as $key => $match) {
                $response[$key] = $this->formatMatch($match);
            }

            return $response;
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }

    /**
     * @param int $players
     * @return array
     */
    public function getTopPlayers(int $players): array
    {
        try {
            $query = $this->db->query("SELECT * FROM ". self::TABLE ." ORDER BY sql_matches_scoretotal.timestamp DESC LIMIT :limit", [
                ':limit' => $players
            ]);

            $response = $query->fetchAll();

            foreach ($response as $key => $match) {
                $response[$key] = $this->formatMatch($match);
            }

            return $response;
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }

    public function updatePlayers()
    {
        try {
            $query = $this->db->query("SELECT * FROM rankme WHERE steamid64 IS NULL AND steam IS NOT NULL");

            $response = $query->fetchAll();

            foreach ($response as $player) {
                $steam = $this->converter->createFromSteamID($player['steam']);
                $steam64 = $steam->getSteamID64();

                $this->db->update('rankme', [
                    'steamid64' => $steam64
                ], [
                    'steam' => $player['steam']
                ]);
            }
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode([
                'status' => 500
            ]);

            die;
        }
    }
}