<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gift;
use App\Repository\GiftRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GiftController extends AbstractController
{
    /**
     * @Route("/add-gift.php")
     */
    public function add(Request $request, ValidatorInterface $validator, GiftRepository $repository): JsonResponse
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

        $errors = $validator->validate($gift);
        if (count($errors) > 0) {
            return $this->json([
                'reponse' => 'failed',
                'message' => (string) $errors,
            ], 400);
        }

        $giftId = $repository->create($gift);
        return $this->json([
            'reponse' => 'success',
            'gift_title' => $title,
            'gift_url' => $url,
            'gift_description' => $description,
            'gift_id' => $giftId,
        ]);
    }

    /**
     * @Route("/delete-gift.php")
     */
    public function delete(Request $request, GiftRepository $repository): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');

        $repository->delete($giftId);

        return $this->json([
            'reponse' => 'success',
        ]);
    }

    /**
     * @Route("/delete_reservation.php")
     */
    public function cancelBooking(Request $request, GiftRepository $repository): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');
        if ($giftId <= 0) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'invalid gift-id',
            ], 400);
        }

        $gift = $repository->findById($giftId);
        if ($gift === null) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'gift not found',
            ], 404);
        }
        $gift->cancelBooking();
        $repository->update($gift);
        return $this->json([
            'reponse' => 'success',
            'gift_id' => $giftId,
        ]);
    }

    /**
     * @Route("/gift-reservation.php")
     */
    public function book(Request $request, GiftRepository $repository): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');
        if ($giftId <= 0) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'invalid gift-id',
            ], 400);
        }

        $gift = $repository->findById((int)$giftId);
        if ($gift === null) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'gift not found',
            ], 404);
        }

        $currentUserId = $request->getSession()->get('user');
        $gift->book($currentUserId);
        $repository->update($gift);

        return $this->json([
            'reponse' => 'success',
            'gift_id' => $giftId,
        ]);
    }

    /**
     * @Route("/update-gift.php")
     */
    public function update(Request $request, GiftRepository $repository, ValidatorInterface $validator): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');

        $gift = $repository->findById($giftId);
        if ($gift === null) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'Gift not found'
            ], 404);
        }

        $giftTitle = $request->request->get('gift-name');
        $gift->setTitle($giftTitle);
        $giftUrl = $request->request->get('gift-url');
        $gift->setLink($giftUrl);
        $giftDescription = $request->request->get('gift-description');
        $gift->setDescription($giftDescription);

        $errors = $validator->validate($gift);
        if (count($errors) > 0) {
            return $this->json([
                'reponse' => 'failed',
                'message' => (string) $errors,
            ], 400);
        }

        if (empty($giftTitle)) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'Invalid gift-name',
            ], 400);
        }

        $repository->update($gift);

        return $this->json([
            'reponse' => 'success',
            'gift_title' => $giftTitle,
            'gift_url' => $giftUrl,
            'gift_description' => $giftDescription,
        ]);
    }
}
