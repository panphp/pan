<?php

declare(strict_types=1);

namespace Pan\ValueObjects;

/**
 * @internal
 */
final readonly class Analytic
{
    /**
     * Returns all analytics.
     *
     * @return array<int, Analytic>
     */
    public function __construct(
        public int $id,
        public string $name,
        public int $impressions,
        public int $hovers,
        public int $clicks,
    ) {
        //
    }

    /**
     * Returns the analytic as an array.
     *
     * @return array{id: int, name: string, impressions: int, hovers: int, clicks: int}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'impressions' => $this->impressions,
            'hovers' => $this->hovers,
            'clicks' => $this->clicks,
        ];
    }
}
