<?php

namespace App\Core\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Core\Traits\ApiResponse;
use App\Core\Traits\Filterable;
use App\Core\Traits\Observable;

abstract class BaseService
{
    use Observable;
    protected Model $model;

    public function __construct(Model $model, protected \App\Core\Services\CacheService $cacheService)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = [], int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        // Basic filtering logic (to be extended by Advanced Query Filter System)
        foreach ($filters as $field => $value) {
            if ($value !== null) {
                $query->where($field, $value);
            }
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): Model
    {
        $key = $this->cacheService->generateKey(
            str_replace('App\\Modules\\', '', get_class($this->model)),
            'record',
            $id
        );

        return $this->cacheService->remember($key, 3600, function() use ($id) {
            return $this->model->findOrFail($id);
        });
    }

    public function create(array $data): Model
    {
        $start = microtime(true);
        return DB::transaction(function () use ($data, $start) {
            try {
                $record = $this->model->create($data);
                $this->logActivity('create', $record);

                // Track Metric
                $this->trackMetric('erp_record_created', [
                    'model' => get_class($this->model),
                ]);
                $this->trackDuration('erp_record_create_time', $start, [
                    'model' => get_class($this->model),
                ]);

                return $record;
            } catch (Exception $e) {
                $this->trackMetric('erp_record_error', [
                    'model' => get_class($this->model),
                    'error' => 'create'
                ]);
                Log::error("Create failed for " . get_class($this->model) . ": " . $e->getMessage());
                throw $e;
            }
        });
    }

    public function update(int $id, array $data): Model
    {
        return DB::transaction(function () use ($id, $data) {
            try {
                $record = $this->model->findOrFail($id);
                $record->update($data);

                $this->cacheService->forget($this->cacheService->generateKey(
                    str_replace('App\\Modules\\', '', get_class($this->model)),
                    'record',
                    $id
                ));

                $this->logActivity('update', $record);
                return $record;
            } catch (Exception $e) {
                Log::error("Update failed for " . get_class($this->model) . ": " . $e->getMessage());
                throw $e;
            }
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            try {
                $record = $this->model->findOrFail($id);
                $record->delete();
                $this->logActivity('delete', $record);
                return true;
            } catch (Exception $e) {
                Log::error("Delete failed for " . get_class($this->model) . ": " . $e->getMessage());
                throw $e;
            }
        });
    }

    protected function logActivity(string $action, Model $model): void
    {
        // This will be linked to the Audit Service in Phase 6, but we define the hook now
        Log::info("Audit Log: {$action} on " . get_class($model) . " ID: " . $model->id);
    }
}
