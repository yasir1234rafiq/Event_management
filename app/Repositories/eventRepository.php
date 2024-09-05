<?php
namespace App\Repositories;

use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;

class EventRepository implements eventRepositoryInterface
{
    protected $model;

    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Event
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $event = $this->model->find($id);
        if ($event) {
            return $event->update($data);
        }
        return false;
    }

    public function find(int $id): ?Event
    {
        return $this->model->find($id);
    }

    public function delete(int $id): bool
    {
        $event = $this->model->find($id);
        if ($event) {
            return $event->delete();
        }
        return false;
    }

    public function all(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        // Apply filters if any
        if (isset($filters['from_date'])) {
            $query->where('date', '>=', $filters['from_date']);
        }
        if (isset($filters['to_date'])) {
            $query->where('date', '<=', $filters['to_date']);
        }
        if (isset($filters['place'])) {
            $query->where('place', 'like', "%{$filters['place']}%");
        }
        return $query->paginate(6);
    }
}
