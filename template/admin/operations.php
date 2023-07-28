<?php 

echo "<ul>";
foreach( $operations as $operation ) {
    echo "<li>".$operation->exchange_id." - ".$operation->id."</li>";
}
echo "</ul>";