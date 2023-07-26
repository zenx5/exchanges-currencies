<style>
    .list{
        width:100%;
    }
    .list tr{
        text-align:center;
    }
</style>

<h2>Crear Moneda</h2>
<table>
    <tr>
        <th>Nombre</th>
        <td>
            <input type="text" name="name_currency"/>
        </td>
    </tr>
    <tr>
        <th>Código</th>
        <td>
            <input type="text" name="code_currency"/>
        </td>
    </tr>
    <tr>
        <th>Símbolo</th>
        <td>
            <input type="text" name="symbol_currency"/>
        </td>
    </tr>
    <tr>
        <td colspan="2"><button class="button button-create-currency">Guardar</button></td>
    </tr>
</table>
<h2>Monedas</h2>
<table class="list">
    <tr>
        <th>Nombre</th>
        <th>Código</th>
        <th>Símbolo</th>
        <th>Fondos</th>
        <th>Acción</th>
    </tr>
    <?php foreach($currencies as $currency): ?>
        <tr>
            <td><?=$currency->name?></td>
            <td><?=$currency->code?></td>
            <td><?=$currency->symbol?></td>
            <td>
                <input type="number" name="found-<?=$currency->id?>" value="<?=$currency->founds?>" />
            </td>
            <td>
                <button class="button button-update-currency" type="button" data-id="<?=$currency->id?>">Actualizar</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    jQuery('.button-create-currency').click( function(){
        const name = jQuery(`input[name='name_currency']`).val()
        const code = jQuery(`input[name='code_currency']`).val()
        const symbol = jQuery(`input[name='symbol_currency']`).val()
        fetch(ajaxurl, {
            method:'post',
            headers:{
                'Content-Type':'application/x-www-form-urlencoded'
            },
            body:`action=add_currency&name=${name}&code=${code}&symbol=${symbol}&founds=0`,
        })
            .then( response => response.json() )
            .then( json => console.log( json ) )
    })
    jQuery('.button-update-currency').click( function(){
        const id = jQuery(this).data('id')
        const founds = jQuery(`input[name='found-${id}']`).val()
        fetch(ajaxurl, {
            method:'post',
            headers:{
                'Content-Type':'application/x-www-form-urlencoded'
            },
            body:`action=update_founds&currency_id=${id}&founds=${founds}`,
        })
            .then( response => response.json() )
            .then( json => console.log( json ) )
    })
    
</script>