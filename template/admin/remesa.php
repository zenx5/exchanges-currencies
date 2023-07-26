<?php
    $tabs = [
        'currencies.php',
        'exchanges.php',
        'operations.php'
    ];
    $tab=1;

    $Change = new ExchangesDB('ex_exchanges');
    $Currency = new ExchangesDB('ex_currencies');
    $currencies = $Currency->get();
    $changes = $Change->get();

    function name_currency($currencies, $id) {
        foreach( $currencies as $currency ) {
            if( $currency->id==$id ) return $currency->name;
        }
        return "-";
    }
?>

<style>
    .nav-container{
        display: flex;
    }
    .nav-item{
        cursor: pointer;
        padding: 10px;
        border: 1px solid black;
        border-radius: 10px 10px 0 0;
        background-color:#e0e0e1;
    }
    .nav-item.active {
        border-bottom: 0px;
        background-color:#f0f0f1;
        font-weight: bold;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }
</style>

<h1>Configuracion</h1>
<ul class="nav-container">
    <li class="nav-item <?=$tab==1?'active':''?>" data-tab="1">Monedas</li>
    <li class="nav-item <?=$tab==2?'active':''?>" data-tab="2">Cambios</li>
    <li class="nav-item <?=$tab==3?'active':''?>" data-tab="3">Operaciones</li>
</ul>
<?php for($i=1; $i<=3; $i++): ?>
    <div id="tab-<?=$i?>" class="tab-content <?=$tab==$i?'active':''?>">
        <?php include $tabs[$i-1]; ?>
    </div>
<?php endfor; ?>
<script>
    jQuery('.nav-item').click(function(){
        const item = jQuery(this);
        const tab = item.data('tab')
        item.parent().children().removeClass('active')
        item.addClass('active')
        jQuery('.tab-content').removeClass('active')
        jQuery('#tab-'+tab).addClass('active')
    })
</script>