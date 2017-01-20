<?php

namespace iCashpl\Market;

use iCashpl\ApiPhp\iCash;

class Market
{
    /** @var iCash */
    protected $icash;
    
    /** @var array */
    protected $services = [];
    
    /** @var Service */
    protected $service;

    protected $user;
    
    protected $vat = 23;

    /**
     * @param string $app_key
     */
    public function __construct($app_key = null)
    {
        $this->icash = new iCash($app_key);
    }
    
    /**
     * @return iCash
     */
    public function icash()
    {
        return $this->icash;
    }
    
    /**
     * @param array $data
     *
     * @return $this
     */
    public function setService(array $data = [])
    {
        $data['uid'] = md5($data['id'].$data['cost']);
        
        if (empty($this->services)) {
            $data['active'] = true;
        }
        
        $this->services[$data['uid']] = new Service($data, $this->vat);
        
        return $this;
    }
    
    /**
     * @param $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        
        return $this;
    }
    
    /**
     * @param $vat
     *
     * @return $this
     */
    public function setVat($vat)
    {
        $this->vat = (float)$vat;
        
        return $this;
    }

    /**
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }
    
    /**
     * @param string $uid
     *
     * @return Service|null
     */
    public function getService($uid)
    {
        $services = $this->getServices();
        
        if (isset($services[$uid])) {
            return $services[$uid];
        }
    }
    
    /**
     * @return Service|null
     */
    public function getCurrentService()
    {
        return $this->service;
    }

    /**
     * @return json
     */
    public function getServicesToJson()
    {
        $services = [];
        
        foreach ($this->getServices() as $uid => $service) {
            $services[$uid] = $service->toArray();
            $services[$uid]['full_cost'] = $service->fullCost();
        }
        
        return json_encode($services);
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * @param array $data
     */
    public function getStatusCode(array $data)
    {
        $this->service = $this->getService($data['service']);
        
        if (isset($data['user'])) {
            $this->setUser($data['user']);
        }
        
        if ($this->getCurrentService()) {
            $this->icash->getStatusCode([
                'service' => $this->service->id,
                'number' => $this->service->number,
                'code' => $data['code'],
            ]);
        }
    }
}
