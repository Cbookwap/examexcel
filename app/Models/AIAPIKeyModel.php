<?php

namespace App\Models;

use CodeIgniter\Model;

class AIAPIKeyModel extends Model
{
    protected $table = 'ai_api_keys';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'provider',
        'model',
        'api_key',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = false;

    /**
     * Get API key for a provider and model
     */
    public function getApiKey($provider, $model = null)
    {
        $query = $this->where('provider', $provider);
        if ($model) {
            $query = $query->where('model', $model);
        }
        return $query->orderBy('updated_at', 'DESC')->first();
    }

    /**
     * Set (add or update) API key for a provider and model
     */
    public function setApiKey($provider, $model, $apiKey, $createdBy)
    {
        $existing = $this->where('provider', $provider)->where('model', $model)->first();
        $now = date('Y-m-d H:i:s');
        $data = [
            'provider' => $provider,
            'model' => $model,
            'api_key' => $apiKey,
            'created_by' => $createdBy,
            'updated_at' => $now,
        ];
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            $data['created_at'] = $now;
            return $this->insert($data);
        }
    }
} 