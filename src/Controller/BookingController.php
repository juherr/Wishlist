<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\GiftRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends BaseController
{
    private GiftRepository $giftRepository;
    private UserRepository $userRepository;

    public function __construct(GiftRepository $giftRepository, UserRepository $userRepository)
    {
        $this->giftRepository = $giftRepository;
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/delete_reservation.php", name="booking_delete")
     */
    public function delete(Request $request): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');
        if ($giftId <= 0) {
            return $this->failed('Invalid gift-id', 400);
        }

        $gift = $this->giftRepository->findById($giftId);
        if ($gift === null) {
            return $this->failed('Gift not found', 404);
        }
        if (!$gift->isBooked()) {
            return $this->failed('Gift not booked', 400);
        }
        $gift->cancelBooking();
        $this->giftRepository->update($gift);
        return $this->success([
            'gift_id' => $giftId,
        ]);
    }

    /**
     * @Route("/gift-reservation.php", name="booking_create")
     */
    public function create(Request $request): JsonResponse
    {
        /** @var int|null $currentUserId */
        $currentUserId = $request->getSession()->get('user');
        if ($currentUserId === null) {
            return $this->failed('Not connected', 403);
        }

        $giftId = $request->request->getInt('gift-id');
        if ($giftId <= 0) {
            return $this->failed('Invalid gift-id', 400);
        }

        $gift = $this->giftRepository->findById($giftId);
        if ($gift === null) {
            return $this->failed( 'Gift not found', 404);
        }
        if ($gift->isBooked()) {
            return $this->failed('Already booked', 400);
        }

        $currentUser = $this->userRepository->findById($currentUserId);
        $gift->book($currentUser);
        $this->giftRepository->update($gift);

        return $this->success([
            'gift_id' => $giftId,
        ]);
    }
}
