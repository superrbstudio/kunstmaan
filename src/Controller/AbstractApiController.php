<?php

namespace Superrb\KunstmaanAddonsBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

class AbstractApiController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(TranslatorInterface $translator, EntityManagerInterface $em)
    {
        $this->translator = $translator;
        $this->em         = $em;
    }

    protected function formErrorResponse(FormInterface $form, ?string $message = null): JsonResponse
    {
        $response = ['errors' => [], 'success' => false, 'events' => []];

        foreach ($form->getErrors(true) as $error) {
            $names = [];
            $field = $error->getOrigin();

            while ($field) {
                $names[] = $field->getName();
                $field   = $field->getParent();
            }

            $response['errors'][join(array_reverse($names), '_')] = $error->getMessage();
        }

        if ($message) {
            $response['errors'][$form->getName()] = $this->translator->trans($message, [], 'validators');
        }

        if (count($response['errors']) > 0) {
            $response['events'][] = 'validation:fail';
        }

        return $this->json($response, 400);
    }
}
