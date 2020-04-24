<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gift;
use App\Repository\GiftRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GiftController extends AbstractController
{
    /**
     * @Route("/add-gift.php")
     */
    public function add(Request $request): JsonResponse
    {
        $title = $request->request->get('gift-name');
        $url = $request->request->get('gift-url');
        $description = $request->request->get('gift-description');
        $userId = $request->request->getInt('gift-user');

        if (!isset($title) || $title === '') {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'empty title',
            ], 400);
        }

        /** @var GiftRepository $giftRepository */
        $repository = $this->getDoctrine()->getRepository(Gift::class);
        $gift_id = $repository->create(new Gift(
            $userId,
            $title,
            $url,
            $description,
            false
        ));
        return $this->json([
            'reponse' => 'success',
            'gift_title' => $title,
            'gift_url' => $url,
            'gift_description' => $description,
            'gift_id' => $gift_id,
        ]);
    }

    /**
     * @Route("/delete-gift.php")
     */
    public function delete(Request $request): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');

        /** @var GiftRepository $giftRepository */
        $repository = $this->getDoctrine()->getRepository(Gift::class);
        $repository->delete($giftId);

        return $this->json([
            'reponse' => 'success',
        ]);
    }

    /**
     * @Route("/delete_reservation.php")
     */
    public function cancelBooking(Request $request): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');
        if ($giftId <= 0) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'invalid gift-id',
            ], 400);
        }

        /** @var GiftRepository $giftRepository */
        $repository = $this->getDoctrine()->getRepository(Gift::class);
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
    public function book(Request $request): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');
        if ($giftId <= 0) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'invalid gift-id',
            ], 400);
        }

        /** @var GiftRepository $giftRepository */
        $repository = $this->getDoctrine()->getRepository(Gift::class);
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
    public function update(Request $request): JsonResponse
    {
        $giftTitle = $request->request->get('gift-name');
        $giftId = $request->request->getInt('gift-id');
        $giftUrl = $request->request->get('gift-url');
        $giftDescription = $request->request->get('gift-description');

        if (empty($giftTitle)) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'Invalid gift-name',
            ], 400);
        }

        /** @var GiftRepository $giftRepository */
        $repository = $this->getDoctrine()->getRepository(Gift::class);
        $gift = $repository->findById((int)$giftId);
        if ($gift === null) {
            return $this->json([
                'reponse' => 'failed',
                'message' => 'Gift not found'
            ], 404);
        }

        $gift->setTitle($giftTitle);
        $gift->setLink($giftUrl);
        $gift->setDescription($giftDescription);
        $repository->update($gift);

        return $this->json([
            'reponse' => 'success',
            'gift_title' => $giftTitle,
            'gift_url' => $giftUrl,
            'gift_description' => $giftDescription,
        ]);
    }
}
