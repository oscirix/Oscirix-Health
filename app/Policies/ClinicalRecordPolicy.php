<?php

namespace App\Policies;

use App\Models\User;

class ClinicalRecordPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, mixed $model): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, mixed $model): bool { return true; }
}

