<?php

namespace App\Livewire\Concerns;

use App\Exceptions\Accounting\AccountingException;
use App\Exceptions\BusinessRuleException;
use App\Exceptions\MissingExchangeRateException;
use Illuminate\Validation\ValidationException;

/**
 * Runs an Action and turns business-rule refusals into a form error + toast,
 * instead of an error page. Programming errors still bubble up.
 */
trait HandlesBusinessErrors
{
    /**
     * @template T
     *
     * @param  callable(): T  $action
     * @return T|null
     */
    protected function attempt(callable $action, string $field = 'document'): mixed
    {
        try {
            return $action();
        } catch (BusinessRuleException|AccountingException|MissingExchangeRateException $e) {
            $this->addError($field, $e->getMessage());
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');

            return null;
        } catch (ValidationException $e) {
            foreach ($e->errors() as $key => $messages) {
                $this->addError($key === 'document' ? $field : $key, $messages[0]);
            }
            $this->dispatch('notify', message: collect($e->errors())->flatten()->first(), type: 'error');

            return null;
        }
    }
}
