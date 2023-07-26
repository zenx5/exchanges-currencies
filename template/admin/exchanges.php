
<style>
    .list{
        width:100%;
    }
    .list tr{
        text-align:center;
    }
</style>

<h2>Crear Cambio</h2>
<table>
    <tr>
        <th>Moneda de Origen</th>
        <td>
            <select name="currency_from_exchanges">
                <?php foreach($currencies as $currency) : ?>
                    <option value="<?=$currency->id?>"><?=$currency->code?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <th>Moneda de Destino</th>
        <td>
            <select name="currency_to_exchanges">
                <?php foreach($currencies as $currency) : ?>
                    <option value="<?=$currency->id?>"><?=$currency->code?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <th>Relacion</th>
        <td>
            <input type="number" name="rate_exchanges"/>
        </td>
    </tr>
    <tr>
        <td colspan="2"><button class="button button-create-exchange">Guardar</button></td>
    </tr>
</table>
<h2>Cambios</h2>
<table class="list">
    <tr>
        <th>Moneda de Origen</th>
        <th>Moneda de Destino</th>
        <th>Relacion</th>
        <th>Acción</th>
    </tr>
    <?php foreach($changes as $change): ?>
        <tr>
            <td><?=name_currency( $currencies, $change->currency_from )?></td>
            <td><?=name_currency( $currencies, $change->currency_to )?></td>
            <td>
                <input type="number" name="rate_exchange_<?=$change->id?>" value="<?=$change->rate?>"/>
            </td>
            <td>
                <button class="button button-update-exchange" type="button" data-id="<?=$change->id?>">Actualizar</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    jQuery('.button-create-exchange').click( function(){
        const currency_from = jQuery(`select[name='currency_from_exchanges']`).val()
        const currency_to = jQuery(`select[name='currency_to_exchanges']`).val()
        const rate = jQuery(`input[name='rates_exchanges']`).val()
        fetch(ajaxurl, {
            method:'post',
            headers:{
                'Content-Type':'application/x-www-form-urlencoded'
            },
            body:`action=add_change&currency_from=${currency_from}&currency_to=${currency_to}&rate=${rate}`,
        })
            .then( response => response.json() )
            .then( json => console.log( json ) )
    })
    jQuery('.button-update-exchange').click( function(){
        const id = jQuery(this).data('id')
        const rate = jQuery(`input[name='rate_exchange_${id}']`).val()
        fetch(ajaxurl, {
            method:'post',
            headers:{
                'Content-Type':'application/x-www-form-urlencoded'
            },
            body:`action=update_rate&exchange_id=${id}&rate=${rate}`,
        })
            .then( response => response.json() )
            .then( json => console.log( json ) )
    })
    
</script>