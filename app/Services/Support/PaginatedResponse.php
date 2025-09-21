<?php

namespace App\Services\Support;

/**
 * A small value object to standardize paginated API responses across repositories.
 */
class PaginatedResponse implements \JsonSerializable
{
    /**
     * @param array<int,array<string,mixed>> $data
     * @param array{current_page:int, per_page:int, total:int, last_page:int} $meta
     */
    public function __construct(
        public array $data,
        public array $meta
    ) {
    }

    /**
     * @return array{data: list<array<string,mixed>>, meta: array{current_page:int, per_page:int, total:int, last_page:int}}
     */
    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'meta' => $this->meta,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
