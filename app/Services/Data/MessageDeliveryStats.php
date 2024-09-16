<?php

namespace App\Services\Data;

class MessageDeliveryStats
{
    protected $messageDelivery;

    public function __construct()
    {
        $this->messageDelivery = [
            ResultType::SUCCESS => ['TOTAL' => 0],
            ResultType::ERROR => ['TOTAL' => 0],
        ];
    }

    public function increment(string $messageType, string $resultType, int $number = 1)
    {
        if (!isset($this->messageDelivery[$resultType][$messageType])) {
            $this->messageDelivery[$resultType][$messageType] = $number;
        } else {
            $this->messageDelivery[$resultType][$messageType] += $number;
        }
        $this->messageDelivery[$resultType]['TOTAL'] += $number;
    }

    public function hasSuccess(): bool
    {
        return $this->messageDelivery[ResultType::SUCCESS]['TOTAL'] > 0;
    }

    public function add(MessageDeliveryStats $other)
    {
        if (!$other) {
            return;
        }

        foreach ($other->getMessageDelivery()[ResultType::SUCCESS] as $messageType => $count) {
            if ($messageType !== 'TOTAL') {
                $this->increment($messageType, ResultType::SUCCESS, $count);
            }
        }

        foreach ($other->getMessageDelivery()[ResultType::ERROR] as $messageType => $count) {
            if ($messageType !== 'TOTAL') {
                $this->increment($messageType, ResultType::ERROR, $count);
            }
        }
    }

    public function getMessageDelivery(): array
    {
        return $this->messageDelivery;
    }
}
