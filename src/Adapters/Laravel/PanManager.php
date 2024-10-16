<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel;

final class PanManager
{
    private int $max;

    /**
     * @var array<int, string>
     */
    private array $allowed;

    private int $perMinute;

    public function __construct()
    {
        /** @phpstan-ignore cast.int */
        $this->max = (int) env('PAN_MAX', 50);

        $this->allowed = array_filter(array_map(
            fn ($next): string => trim($next),
            /** @phpstan-ignore cast.string */
            explode(',', (string) env('PAN_ALLOWED', '')),
        ));

        /** @phpstan-ignore cast.int */
        $this->perMinute = (int) env('PAN_PER_MINUTE', 150);
    }

    public function max(?int $value = null): int|self
    {
        if (is_null($value)) {
            return $this->max;
        }

        $this->max = $value;

        return $this;
    }

    public function unlimited(): self
    {
        $this->max = PHP_INT_MAX;

        return $this;
    }

    /**
     * @param  array<int, string>|null  $value
     * @return array<int, string>|PanManager
     */
    public function allowed(?array $value = null): array|self
    {
        if (is_null($value)) {
            return $this->allowed;
        }

        $this->allowed = $value;

        return $this;
    }

    public function perMinute(?int $value = null): int|self
    {
        if (is_null($value)) {
            return $this->perMinute;
        }

        $this->perMinute = $value;

        return $this;
    }
}
