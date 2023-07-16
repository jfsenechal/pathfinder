<?php

namespace AfmLibre\Pathfinder\Character;

use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SearchHelper
{
    public function __construct(private readonly SessionInterface $session, private readonly CharacterClassRepository $characterClassRepository)
    {
    }

    public function getArgs(string $key): array
    {
        $data = [];
        if ($this->session->has($key)) {
            $data = json_decode((string) $this->session->get($key), true, 512, JSON_THROW_ON_ERROR);

            return $this->resolveObjects($data);
        }

        return $data;
    }

    public function setArgs(string $key, $data): void
    {
        $this->session->set($key, json_encode($data, JSON_THROW_ON_ERROR));
    }

    public function removeArgs(string $key): void
    {
        $this->session->remove($key);
    }

    public function resolveObjects(array $data): array
    {
        $key = 'class';
        if ($this->isset($data, $key)) {
            $data[$key] = $this->characterClassRepository->find($data[$key]);
        }
        return $data;
    }

    private function isset(iterable $data, $key): bool
    {
        if (isset($data[$key]) && null != $data[$key]) {
            if (isset($data[$key]['id']) && (int) $data[$key]['id'] > 0) {
                return true;
            }
        }

        return false;
    }
}
