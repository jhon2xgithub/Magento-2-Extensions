<?php

namespace Shop\AbandonedCart\Model;

class Config{
    
    const MAXTIMES_NUM              = 1;
    const IN_DAYS                   = 0;
    const IN_HOURS                  = 1;
    const ACTIVE                    = "abandonedcart/general/active";
   
    const MAXTIMES                  = "abandonedcart/general/max";
    const PAGE                      = 'abandonedcart/general/page';
    const AUTOLOGIN                 = "abandonedcart/general/autologin";

    const CUSTOMER_GROUPS           = "abandonedcart/general/customer";
    const UNIT                      = "abandonedcart/general/unit";
    const SENDER                    = "abandonedcart/general/identity";
 
    const FIRST_EMAIL_TEMPLATE_XML_PATH     = 'abandonedcart/general/template1';
    const FIRST_SUBJECT             = "abandonedcart/general/subject1"; 
    const DAYS_1                    = "abandonedcart/general/days1";
    const FIRST_DATE                = "abandonedcart/general/firstdate";
 
}