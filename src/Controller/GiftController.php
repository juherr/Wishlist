<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gift;
use App\Repository\GiftRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GiftController extends BaseController
{
    private GiftRepository $repository;
    private UserRepository $userRepository;
    private ValidatorInterface $validator;

    public function __construct(
        GiftRepository $repository,
        UserRepository $userRepository,
        ValidatorInterface $validator
    )
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * @Route("/add-gift.php", name="gift_add")
     */
    public function add(Request $request): JsonResponse
    {
        /** @var int|null $currentUserId */
        $currentUserId = $request->getSession()->get('user');
        if ($currentUserId === null) {
            return $this->failed('Not connected', 403);
        }
        $title = $request->request->get('gift-name');
        $url = $request->request->get('gift-url');
        $description = $request->request->get('gift-description');
        $userId = $request->request->getInt('gift-user');
        if ($userId !== $currentUserId) {
            return $this->failed('Invalid user', 401);
        }
        $currentUser = $this->userRepository->findById($userId);
        $gift = new Gift(
            $currentUser,
            $title,
            $url,
            $description,
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
     * @Route("/delete-gift.php", name="gift_delete")
     */
    public function delete(Request $request): JsonResponse
    {
        $giftId = $request->request->getInt('gift-id');
        $gift = $this->repository->findById($giftId);
        if ($gift === null) {
            return $this->failed('Gift not found', 404);
        }

        $this->repository->delete($gift);

        return $this->success();
    }

    /**
     * @Route("/update-gift.php", name="gift_update")
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
