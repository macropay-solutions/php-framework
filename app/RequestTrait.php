<?php

namespace App;

trait RequestTrait
{
    public function forceOffsetUnset(string $offset): static
    {
        $this->query->remove($offset);
        $this->request->remove($offset);
        $this->offsetUnset($offset);

        return $this;
    }

    public function forceReplace(array $data): static
    {
        $this->query->replace();
        $this->request->replace();
        $this->replace($data);

        return $this;
    }

    /**
     * @param string|int $filter can be 'alpha', 'alnum', 'digits' or an int flag
     * @see ParameterBag::filter(), \filter_var() for $filter and $options
     * @see ParameterBag::getAlpha()
     * @see ParameterBag::getAlnum()
     * @see ParameterBag::getDigits()
     * DOES NOT RETURN DEFAULT NULL with FILTER_DEFAULT but empty string
     */
    public function getFiltered(
        string $key,
        mixed $default = null,
        string|int $filter = \FILTER_DEFAULT,
        array|int $options = [],
    ): mixed {
        if (\is_int($filter)) {
            try {
                if ($this->attributes->has($key)) {
                    return $this->attributes->filter($key, $default, $filter, $options);
                }

                if (\in_array(\strtoupper($this->getRealMethod()), ['POST', 'PUT', 'PATCH'], true)) {
                    if ($this->request->has($key)) {
                        return $this->request->filter($key, $default, $filter, $options);
                    }

                    if ($this->query->has($key)) {
                        return $this->query->filter($key, $default, $filter, $options);
                    }

                    return $default;
                }

                if ($this->query->has($key)) {
                    return $this->query->filter($key, $default, $filter, $options);
                }

                if ($this->request->has($key)) {
                    return $this->request->filter($key, $default, $filter, $options);
                }
            } catch (\UnexpectedValueException $e) {
                if (\config('crufd_wizard.REQUEST_GET_FILTERED_MACRO_SHOULD_THROW_ON_FAILURE', false)) {
                    throw $e;
                }

                if (!\is_array($options) && $options) {
                    $options = ['flags' => $options];
                }

                return ($options['flags'] ?? 0) & \FILTER_NULL_ON_FAILURE ? null : false;
            }

            return $default;
        }

        if ($filter !== '') {
            $filter = \ucfirst(\strtolower($filter));

            if (!\in_array($filter, ['Alpha', 'Alnum', 'Digits'], true)) {
                $filter = '';
            }
        }

        try {
            if ($this->attributes->has($key)) {
                return $this->attributes->{'get' . $filter}($key, (string)$default);
            }

            if (\in_array(\strtoupper($this->getRealMethod()), ['POST', 'PUT', 'PATCH'], true)) {
                if ($this->request->has($key)) {
                    return $this->request->{'get' . $filter}($key, (string)$default);
                }

                if ($this->query->has($key)) {
                    return $this->query->{'get' . $filter}($key, (string)$default);
                }

                return $default;
            }

            if ($this->query->has($key)) {
                return $this->query->{'get' . $filter}($key, (string)$default);
            }

            if ($this->request->has($key)) {
                return $this->request->{'get' . $filter}($key, (string)$default);
            }
        } catch (\UnexpectedValueException) {
        } catch (\Throwable $e) {
            \app('log')->error(__FILE__ . ':' . __LINE__ . ' Request::getFiltered error: ' .
                $e->getMessage() . ' for: ' . \json_encode(\func_get_args()), $e->getTrace());
        }

        return $default;
    }
}
