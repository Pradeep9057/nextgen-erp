<?php

namespace App\Modules\Organization\Services;

use App\Modules\Organization\Models\Organization;
use App\Modules\Organization\Models\Branch;
use Illuminate\Support\Facades\DB;
use Exception;

class OrganizationService
{
    public function createOrganization(array $data): Organization
    {
        return DB::transaction(function () use ($data) {
            $organization = Organization::create($data);

            // Automatically create a default main branch if requested
            if (isset($data['default_branch_name'])) {
                $organization->branches()->create([
                    'name' => $data['default_branch_name'],
                    'branch_code' => 'MAIN',
                    'is_active' => true,
                ]);
            }

            return $organization;
        });
    }

    public function updateOrganization(int $id, array $data): Organization
    {
        $organization = Organization::findOrFail($id);
        $organization->update($data);
        return $organization;
    }

    public function deleteOrganization(int $id): bool
    {
        return Organization::findOrFail($id)->delete();
    }

    public function getOrganizationWithBranches(int $id): Organization
    {
        return Organization::with('branches')->findOrFail($id);
    }
}
