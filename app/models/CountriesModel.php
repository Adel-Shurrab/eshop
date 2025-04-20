<?php

namespace App\Models;

use App\Core\Database;
use Exception;

class CountriesModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getCountries(): array
    {
        try {
            return $this->db->read("SELECT * FROM countries WHERE disabled = 0 ORDER BY country ASC") ?? [];
        } catch (Exception $e) {
            error_log("Error fetching countries: " . $e->getMessage());
            return [];
        }
    }

    public function getStates(int $country_id): array
    {
        try {
            return $this->db->read(
                "SELECT * FROM states 
                 WHERE country_id = :country_id AND disabled = 0 
                 ORDER BY state ASC",
                ['country_id' => $country_id]
            ) ?? [];
        } catch (Exception $e) {
            error_log("Error fetching states: " . $e->getMessage());
            return [];
        }
    }

    public function getCountryName(int $id): ?string
    {
        try {
            return $this->db->read(
                "SELECT country FROM countries 
                 WHERE id = :id AND disabled = 0",
                ['id' => $id]
            )[0]['country'] ?? null;
        } catch (Exception $e) {
            error_log("Error fetching country with ID $id: " . $e->getMessage());
            return null;
        }
    }

    public function getStateName(int $id): ?string
    {
        try {
            return $this->db->read(
                "SELECT state FROM states 
                 WHERE id = :id AND disabled = 0",
                ['id' => $id]
            )[0]['state'] ?? null;
        } catch (Exception $e) {
            error_log("Error fetching state with ID $id: " . $e->getMessage());
            return null;
        }
    }
}
