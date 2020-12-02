<?php
require_once  '../../../server/classes/class.searchFair.php';


$search = new SearchFair();
print_r($search->searchSortFairsByLocation("3500", true));
print_r($search->searchSortFairsByLocation("3500", false));
