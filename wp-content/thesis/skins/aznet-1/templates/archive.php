<?php

/**
 * TEMPLATE HOOK ( PAGE )
 */

if (is_shop()){
    tnc_theme_container_before();
    tnc_theme_archive_product();
    tnc_theme_container_after();
}else{
    tnc_theme_container_before();
    tnc_theme_archive();
    tnc_theme_container_after();
}

