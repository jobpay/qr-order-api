<?php

namespace App\Layers\Domain\Entity\Shop\Seat;

use App\Layers\Domain\Entity\Shop\Order\OrderEntityList;
use App\Layers\Domain\ValueObject\SeatStatus;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Config;

class SeatEntity
{
    /**
     * @param int|null $id
     * @param int $store_id
     * @param string $number
     * @param int $order
     * @param SeatStatus $status
     * @param string|null $qr_code
     * @param Carbon|null $start_at
     * @param Carbon|null $end_at
     * @param OrderEntityList|null $order_entity
     */
    private function __construct(
        private readonly ?int             $id,
        private readonly int              $store_id,
        private readonly string           $number,
        private readonly int              $order,
        private readonly SeatStatus       $status,
        private ?string                   $qr_code,
        private readonly ?Carbon          $start_at,
        private readonly ?Carbon          $end_at,
        private readonly ?OrderEntityList $order_entity,
    ) {
    }

    /**
     * @param int|null $id
     * @param int $store_id
     * @param string $number
     * @param int $order
     * @param SeatStatus $status
     * @param string|null $qr_code
     * @param Carbon|null $start_at
     * @param Carbon|null $end_at
     * @return self
     */
    public static function make(
        ?int    $id,
        int    $store_id,
        string  $number,
        int     $order,
        SeatStatus $status,
        ?string $qr_code = null,
        ?Carbon $start_at = null,
        ?Carbon $end_at = null,
        ?OrderEntityList $order_entity = null,
    ): self {
        return new self(
            id: $id,
            store_id: $store_id,
            number: $number,
            order: $order,
            status: $status,
            qr_code: $qr_code,
            start_at: $start_at,
            end_at: $end_at,
            order_entity: $order_entity,
        );
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->store_id;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @return Carbon|null
     */
    public function getStartAt(): ?Carbon
    {
        return $this->start_at;
    }

    /**
     * @return Carbon|null
     */
    public function getEndAt(): ?Carbon
    {
        return $this->end_at;
    }

    /**
     * @return OrderEntityList|null
     */
    public function getOrderEntityList(): ?OrderEntityList
    {
        return $this->order_entity;
    }

    /**
     * @return SeatStatus
     */
    public function getStatus(): SeatStatus
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getQrCode(): string
    {
        return $this->qr_code;
    }

    /**
     * @return string
     */
    public function generateQrCode(): string
    {
        $qr_data = QrCode::create(Config::get('app.frontend_customer_url') .
            "/customer-seats/{$this->getId()}")
            ->setSize(300) // QRコードのサイズ
            ->setMargin(10); // 余白のサイズ

        // PNG 形式で出力
        $writer = new PngWriter();
        $result = $writer->write($qr_data);

        // base64エンコードして返却
        return 'data:image/png;base64,' . base64_encode($result->getString());
    }

    /**
     * @param int $store_id
     * @return bool
     */
    public function isOtherStore(int $store_id): bool
    {
        return $this->getStoreId() !== $store_id;
    }
}
