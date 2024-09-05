<?php
namespace App\Services;

use App\Repositories\EventRepositoryInterface;
use App\Models\Event;

class EventService
{
    protected $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function createEvent(array $data): Event
    {
        return $this->eventRepository->create($data);
    }

    public function updateEvent(int $id, array $data): bool
    {
        return $this->eventRepository->update($id, $data);
    }

    public function getEvent(int $id): ?Event
    {
        return $this->eventRepository->find($id);
    }

    public function deleteEvent(int $id): bool
    {
        return $this->eventRepository->delete($id);
    }

    public function getAllEvents(array $filters = [])
    {
        return $this->eventRepository->all($filters);
    }
}
