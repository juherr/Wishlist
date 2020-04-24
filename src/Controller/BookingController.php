<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\GiftRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends BaseController
{
    private GiftRepository $repository;

    public function __construct(GiftRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @Route("/delete_reservation.php")
     */
    public function cancelBooking(Request $request): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');
        if ($giftId <= 0) {
            return $this->failed('Invalid gift-id', 400);
        }

        $gift = $this->repository->findById($giftId);
        if ($gift === null) {
            return $this->failed('Gift not found', 404);
        }
        if (!$gift->isBooked()) {
            return $this->failed('Gift not booked', 400);
        }
        $gift->cancelBooking();
        $this->repository->update($gift);
        return $this->success([
            'gift_id' => $giftId,
        ]);
    }

    /**
     * @Route("/gift-reservation.php")
     */
    public function createBooking(Request $request): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');
        if ($giftId <= 0) {
            return $this->failed('Invalid gift-id', 400);
        }

        $gift = $this->repository->findById($giftId);
        if ($gift === null) {
            return $this->failed( 'Gift not found', 404);
        }
        if ($gift->isBooked()) {
            return $this->failed('Already booked', 400);
        }

        // TODO manage connected user
        $currentUserId = $request->getSession()->get('user');
        $gift->book($currentUserId);
        $this->repository->update($gift);

        return $this->success([
            'gift_id' => $giftId,
        ]);
    }
}
