<?php

namespace App\Livewire\Concerns;

use Livewire\Attributes\On;
use ReflectionProperty;

/**
 * Selects the record just added in the quick-create modal (App\Livewire\QuickCreate) in the
 * field that asked for it: the button sends this component's id as "owner" and the field
 * path (e.g. "items.0.color_id") as "target".
 */
trait AcceptsQuickCreate
{
    #[On('quick-created')]
    public function acceptQuickCreated(string $type, int $id, ?string $owner = null, ?string $target = null): void
    {
        if ($owner !== $this->getId() || $target === null || $target === '') {
            return;
        }

        $this->applyQuickCreated($type, $id, $target);
    }

    protected function applyQuickCreated(string $type, int $id, string $target): void
    {
        $this->setQuickPath($target, $id);

        // A new brand has no models yet: clear the model chosen for the previous brand.
        if ($type === 'brand' && str_ends_with($target, 'brand_id')) {
            $this->setQuickPath(substr($target, 0, -strlen('brand_id')).'model_id', null);
        }
    }

    private function setQuickPath(string $path, mixed $value): void
    {
        $segments = explode('.', $path);
        $root = array_shift($segments);

        if (! property_exists($this, $root) || ! (new ReflectionProperty($this, $root))->isPublic()) {
            return;
        }

        if ($segments === []) {
            $this->{$root} = $value;

            return;
        }

        $data = $this->{$root};
        if (! is_array($data)) {
            return;
        }
        data_set($data, implode('.', $segments), $value);
        $this->{$root} = $data;
    }
}
