<?php

namespace iCashpl\Market;

class Service
{
    /** @var array */
    protected $attributes = [];
    
    protected $vat;

    /**
     * @param array $data
     */
    public function __construct($data = [], $vat = 23)
    {
        $this->vat = $vat;
        $this->attributes = $data;
    }
    
    /**
     * @return int
     */
    public function fullCost()
    {
        $cost = (float)$this->cost;
        
        if ($cost <= 0) {
            return 0;
        }
        
        return $cost * (1 + $this->vat / 100);
    }
    
    /**
     * @return bool
     */
    public function hasActive()
    {
        return (bool)$this->active;
    }
    
    /**
     * @return json
     */
    public function toJson()
    {
        return json_encode($this->attributes);
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        return (array)$this->attributes;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
    }
    
    /**
     * @return json
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
