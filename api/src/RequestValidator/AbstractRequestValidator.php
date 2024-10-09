<?php

namespace App\RequestValidator;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequestValidator
{
    public function __construct(protected ValidatorInterface $validator)
    {
    }
    public function fill(array $reqData): static
    {
        foreach ($reqData as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }

        return $this;
    }

    public function validate(): static
    {
        $errors = $this->validator->validate($this);

        $validationResult = ['message' => 'Request validation failed', 'errors' => []];

        foreach ($errors as $error) {
            $validationResult['errors'][] = [
                'property' => $error->getPropertyPath(),
                'value' => $error->getInvalidValue(),
                'message' => $error->getMessage()
            ];
        }

        if (count($validationResult['errors']) > 0) {
            throw new \Exception(json_encode($validationResult), Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        return $this;
    }
}
