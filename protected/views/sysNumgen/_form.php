<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sys-numgen-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        <table border="0">
            <tbody>
                <tr>
                    <td>	
                        <div class="row">
                                <?php echo $form->labelEx($model,'cdnumgen'); ?>
                                <?php echo $form->textField($model,'cdnumgen',array('size'=>13,'maxlength'=>13)); ?>
                                <?php echo $form->error($model,'cdnumgen'); ?>
                        </div>

                        <div class="row">
                                <?php echo $form->labelEx($model,'prefix'); ?>
                                <?php echo $form->textField($model,'prefix',array('size'=>8,'maxlength'=>8)); ?>
                                <?php echo $form->error($model,'prefix'); ?>
                        </div>

                        <div class="row">
                                <?php echo $form->labelEx($model,'pattern'); ?>
                                <?php echo $form->textField($model,'pattern'); ?>
                                <?php echo $form->error($model,'pattern'); ?>
                        </div>

                        <div class="row">
                                <?php echo $form->labelEx($model,'startnum'); ?>
                                <?php echo $form->textField($model,'startnum',array('size'=>13,'maxlength'=>13)); ?>
                                <?php echo $form->error($model,'startnum'); ?>
                        </div>
                    </td>
                    <td>
                            <div class="row">
                                <?php echo $form->labelEx($model,'year'); ?>
                                <?php echo $form->textField($model,'year',array('size'=>2,'maxlength'=>2)); ?>
                                <?php echo $form->error($model,'year'); ?>
                            </div>

                            <div class="row">
                                    <?php echo $form->labelEx($model,'date'); ?>
                                    <?php echo $form->textField($model,'date'); ?>
                                    <?php echo $form->error($model,'date'); ?>
                            </div>

                            <div class="row">
                                    <?php echo $form->labelEx($model,'last_value'); ?>
                                    <?php echo $form->textField($model,'last_value'); ?>
                                    <?php echo $form->error($model,'last_value'); ?>
                            </div>

                            <div class="row">
                                    <?php echo $form->labelEx($model,'dscrp'); ?>
                                    <?php echo $form->textField($model,'dscrp',array('size'=>32,'maxlength'=>32)); ?>
                                    <?php echo $form->error($model,'dscrp'); ?>
                            </div>

                    </td>
                </tr>
            </tbody>
        </table>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->