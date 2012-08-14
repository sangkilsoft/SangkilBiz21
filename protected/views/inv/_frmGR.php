<table border="0" width="300px">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>S</td>
            <td>S</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>S</td>
            <td>S</td>
        </tr>
        <tr>
            <td></td>
            <td>S</td>
            <td>S</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>W</td>
            <td></td>
            <td>W</td>
        </tr>
    </tbody>
</table>

<?php
    Yii::app()->clientScript->registerScript('formx', " 
        $('#grnum').click(function(){ 
                $('#InvgrHdr_gr_num').val('76588');
                $('#InvgrHdr_gr_num').attr('readonly','true');
                $('#cancelBtn').click();
                $('#dlg').dialog('close');
            });
        ");
    echo "</br>";
    echo CHtml::button('Click Me', array('id' => 'grnum'));
    ?>
