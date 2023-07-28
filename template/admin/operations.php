<?php 

    function get_change_name($changes, $currencies, $id) {
        foreach( $changes as $change ){
            if( $change->id==$id ) {
                $from = get_currency_name( $currencies, $change->currency_from);
                $to = get_currency_name( $currencies, $change->currency_to);
                return "De <b>$from</b> a <b>$to</b>";
            }
        }
        return "";
    }

    function get_currency_name( $currencies, $id ) {
        foreach( $currencies as $currency ) {
            if( $currency->id==$id ) {
                return $currency->code;
            }
        }
        return "";
    }

?>


<table class="list">
    <tr>
        <th>Tipo de Cambio</th>
        <th>Monto</th>
        <th>Referencia</th>
        <th>Aprobado por</th>
    </tr>
    <?php foreach( $operations as $operation ): ?> 
        <tr>
        <tr>
            <td><?=get_change_name($changes, $currencies, $operation->exchange_id)?></td>
            <td><?=$operation->mount?></td>
            <td><?=$operation->reference?></td>
            <td><?=$operation->approved_by?></td>
            <td>
                <button class="button action-approve" type="button" data-id="<?=$operation->id?>" <?=$operation->approved_by?"disabled":""?>>Aprobar</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>


<script>
    jQuery(".action-approve").click(function(){
        const id = jQuery(this).data("id")
        if( userSettings.uid ) {
            fetch(ajaxurl, {
                method:'post',
                headers:{
                    'Content-Type':'application/x-www-form-urlencoded'
                },
                body:`action=update_operation&id=${id}&approved_by=${userSettings.uid}`,
            })
                .then( response => response.json() )
                .then( json => console.log( json ) )

        }
    })

</script>