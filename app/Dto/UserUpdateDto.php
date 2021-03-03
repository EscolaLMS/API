<?php

namespace App\Dto;

use EscolaSoft\EscolaLms\Dtos\Contracts\InstantiateFromRequest;
use Illuminate\Http\Request;

class UserUpdateDto implements InstantiateFromRequest
{
    private string $firstName;
    private string $lastName;
    private int $age;
    private int $gender;
    private ?string $country;
    private ?string $city;
    private ?string $street;
    private ?string $postcode;

    public function __construct(string $firstName, string $lastName, int $age, int $gender, ?string $country, ?string $city, ?string $street, ?string $postcode)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
        $this->gender = $gender;
        $this->country = $country;
        $this->city = $city;
        $this->street = $street;
        $this->postcode = $postcode;
    }

    public static function instantiateFromRequest(Request $request): self
    {
        return new self(
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('age'),
            $request->input('gender'),
            $request->input('country'),
            $request->input('city'),
            $request->input('street'),
            $request->input('postcode'),
        );
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'age' => $this->getAge(),
            'gender' => $this->getGender(),
            'country' => $this->getCountry(),
            'city' => $this->getCity(),
            'street' => $this->getStreet(),
            'postcode' => $this->getPostcode(),
        ];
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getGender(): int
    {
        return $this->gender;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }
}
