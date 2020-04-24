<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gift;
use App\Repository\GiftRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GiftController extends BaseController
{
    private GiftRepository $repository;
    private ValidatorInterface $validator;

    public function __construct(GiftRepository $repository, ValidatorInterface $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @Route("/add-gift.php")
     */
    public function add(Request $request): JsonResponse
    {
        $title = $request->request->get('gift-name');
        $url = $request->request->get('gift-url');
        $description = $request->request->get('gift-description');
        $userId = $request->request->getInt('gift-user');
        $gift = new Gift(
            $userId,
            $title,
            $url,
            $description,
            false
        );

        $errors = $this->validator->validate($gift);
        if (count($errors) > 0) {
            return $this->failed((string) $errors, 400);
        }

        $giftId = $this->repository->create($gift);
        return $this->success([
            'gift_title' => $title,
            'gift_url' => $url,
            'gift_description' => $description,
            'gift_id' => $giftId,
        ]);
    }

    /**
     * @Route("/delete-gift.php")
     */
    public function delete(Request $request): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');

        $this->repository->delete($giftId);

        return $this->success();
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
        $gift->cancelBooking();
        $this->repository->update($gift);
        return $this->success([
            'gift_id' => $giftId,
        ]);
    }

    /**
     * @Route("/gift-reservation.php")
     */
    public function book(Request $request): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');
        if ($giftId <= 0) {
            return $this->failed('Invalid gift-id', 400);
        }

        $gift = $this->repository->findById((int)$giftId);
        if ($gift === null) {
            return $this->failed( 'gift not found', 404);
        }

        // TODO manage connected user
        $currentUserId = $request->getSession()->get('user');
        $gift->book($currentUserId);
        $this->repository->update($gift);

        return $this->success([
            'gift_id' => $giftId,
        ]);
    }

    /**
     * @Route("/update-gift.php")
     */
    public function update(Request $request): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');

        $gift = $this->repository->findById($giftId);
        if ($gift === null) {
            return $this->failed('Gift not found', 404);
        }

        $giftTitle = $request->request->get('gift-name');
        $gift->setTitle($giftTitle);
        $giftUrl = $request->request->get('gift-url');
        $gift->setLink($giftUrl);
        $giftDescription = $request->request->get('gift-description');
        $gift->setDescription($giftDescription);

        $errors = $this->validator->validate($gift);
        if (count($errors) > 0) {
            return $this->failed((string) $errors, 400);
        }

        if (empty($giftTitle)) {
            return $this->failed('Invalid gift-name', 400);
        }

        $this->repository->update($gift);

        return $this->success([
            'gift_title' => $giftTitle,
            'gift_url' => $giftUrl,
            'gift_description' => $giftDescription,
        ]);
    }
}
