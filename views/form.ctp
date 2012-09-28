<?php
/**
*
* PHP 5
*
* CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
* Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
* @link          http://cakephp.org CakePHP(tm) Project
* @package       Cake.Console.Templates.default.views
* @since         CakePHP(tm) v 1.2.0.5234
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
?>
<?php echo "<?php \$this->start('hook_current_actions'); ?>\n"; ?>
<?php 
    if (strpos($action, 'add') === false): 
        echo "\t<li><?php echo \$this->Html->confirm(__('Delete " . $singularHumanName . "'), array('action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')), array(), __('Are you sure you want to delete {$singularHumanName} #%s?', \$this->Form->value('{$modelClass}.{$primaryKey}')), __('Delete {$singularHumanName}?')); ?></li>\n"; 
    elseif (strpos($action, 'edit') === false):
        echo "\t<li><?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index'));?></li>\n";
    endif;
?>   
<?php echo "<?php \$this->end(); ?>\n"?>
<?php echo "<?php \$this->start('hook_related_actions'); ?>\n"; ?>
<?php
        if (strpos($action, 'add') === false): 
            echo "\t<li><?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index'));?></li>\n";
            echo "\t<li><?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('action' => 'add')); ?></li>\n";
        endif;
        $done = array();
        foreach ($associations as $type => $data) {
            foreach ($data as $alias => $details) {
                if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
                    echo "\t<li><?php echo \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "'), array('controller' => '{$details['controller']}', 'action' => 'index')); ?> </li>\n";
                    echo "\t<li><?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add')); ?> </li>\n";
                    $done[] = $details['controller'];
                }
            }
        }
?>
<?php echo "<?php \$this->end(); ?>\n"?>

<?php echo "<?php echo \$this->Form->create('{$modelClass}');?>\n"; ?>
    <fieldset>
        <legend><?php 
            if(strpos($action, 'admin_') === 0){
                printf("<?php echo __('%s %s'); ?>", Inflector::humanize(substr($action, 6)), $singularHumanName);
            }else{            
                printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); 
            }
        ?></legend>
    <?php echo "<?php echo \$this->Session->flash(); ?>\n" ?>
<?php
    echo "\t\t<?php\n";
    foreach ($fields as $field) {
        if (strpos($action, 'add') !== false && $field == $primaryKey) {
            continue;
        } elseif (!in_array($field, array('created', 'modified', 'updated'))) {
            echo "\t\techo \$this->Form->input('{$field}');\n";
        }
    }
    if (!empty($associations['hasAndBelongsToMany'])) {
        foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
            echo "\t\techo \$this->Form->input('{$assocName}');\n";
        }
    }
    echo "\t\t?>\n";
?>
    </fieldset>
    <div class="form-actions">
        <?php echo "<?php echo \$this->Form->button(sprintf('<i class=\"icon-white icon-ok-sign\"></i> %s', __('Save $singularHumanName')), array('type' => 'submit', 'escape' => false, 'class' => 'btn btn-primary')); ?>\n"; ?>
    </div>    
<?php
echo "<?php echo \$this->Form->end();?>\n";
?>
