<?php
$layout = Upside_Lite_Builder_Layout::get_layout_ultimate();
Upside_Lite_Builder::print_page( get_queried_object_id(), $layout );