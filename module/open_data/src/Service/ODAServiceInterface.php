<?php

namespace GrEduLabs\open_data\Service;

interface ODAServiceInterface
{
    public function getSchools();
    
    public function getLabs();
    
    public function getAssets();
    
    public function getAppForms();
    
    public function getAppFormsItems();
    
    public function getSoftwareItems();

    public function getItemsNewapplication();
}
