<?php

namespace frontend\service;

class CityDTO
{
    private ?string $geo_lat;

    private ?string $geo_lon;

    private ?string $region;

    private ?string $city;

    private ?string $settlement;

    private ?string $region_kladr_id;

    private ?string $city_kladr_id;

    private ?string $settlement_kladr_id;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key } = $value;
            }
        }
    }

    public function getLatitude(): ?string
    {
        return $this->geo_lat;
    }

    public function getLongitude(): ?string
    {
        return $this->geo_lon;
    }

    public function getCityName(): ?string
    {
        if (!$this->city && !$this->settlement) {
            return $this->region;
        }

        return $this->city ?: $this->settlement;
    }

    public function getCityKladrId(): ?string
    {
        if (!$this->city_kladr_id && !$this->settlement_kladr_id) {
            return $this->region_kladr_id;
        }

        return $this->city_kladr_id ?: $this->settlement_kladr_id;
    }
}
