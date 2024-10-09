<?php

namespace App\Service;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class ObjectSerializerService
{
    public static function normalize(object|array $obj)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $normalizers = [new ObjectNormalizer($classMetadataFactory), new DateTimeNormalizer()];

        $serializer = new Serializer($normalizers, []);

        return $serializer->normalize($obj);
    }
}
