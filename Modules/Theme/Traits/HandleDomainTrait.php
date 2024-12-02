<?php

namespace Modules\Theme\Traits;

use Illuminate\Support\Facades\Cache;

trait HandleDomainTrait
{
    protected string $cacheKey = 'domain_lookup_configs';
    protected array $configKeys = ['domains', 'form_consulting_domain'];

    protected function updateCachedConfigs(): void
    {
        $cachedConfigs = Cache::get($this->cacheKey, []);
        $hasChanges = false;

        foreach ($this->configKeys as $key) {
            $hasChanges |= $this->updateConfigItem($cachedConfigs, $key);
        }

        if ($hasChanges) {
            Cache::put($this->cacheKey, $cachedConfigs);
        }
    }

    protected function updateConfigItem(array &$cachedConfigs, string $key): bool
    {
        $newValue = $this->configs[$key] ?? null;

        if ($key === 'domains') {
            $newValue = $this->processDomains($newValue);
        }

        if (!isset($cachedConfigs[$key]) || $cachedConfigs[$key] !== $newValue) {
            $cachedConfigs[$key] = $newValue;
            return true;
        }

        return false;
    }

    protected function processDomains($domains): array
    {
        return $domains ? array_map('trim', explode(',', $domains)) : [];
    }

    public function getCachedConfig(string $key, $default = null)
    {
        return Cache::get($this->cacheKey, [])[$key] ?? $default;
    }

    public function clearDomainLookupCache(): void
    {
        Cache::forget($this->cacheKey);
    }
}
