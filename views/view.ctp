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
    <li><?php echo "<?php echo \$this->Html->link(__('Edit " . $singularHumanName . "'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>"; ?></li>            
    <li><?php echo "<?php echo \$this->Html->confirm(__('Delete " . $singularHumanName . "'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array(), __('Are you sure you want to delete {$singularHumanName} #%s?', \${$singularVar}['{$modelClass}']['{$primaryKey}']), __('Delete {$singularHumanName}?')); ?></li>"; ?></li>
<?php echo "<?php \$this->end(); ?>\n"?>
<?php echo "<?php \$this->start('hook_related_actions'); ?>\n"; ?>
    <li><?php echo "<?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index')); ?>"; ?></li>            
    <li><?php echo "<?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('action' => 'add')); ?>"; ?></li>            
<?php
            $done = array();
            foreach ($associations as $type => $data) {
                foreach ($data as $alias => $details) {
                    if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
                        echo "\t<li><?php echo \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "'), array('controller' => '{$details['controller']}', 'action' => 'index')); ?></li>\n";
                        echo "\t<li><?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add')); ?></li>\n";
                        $done[] = $details['controller'];
                    }
                }
            }
?>
<?php echo "<?php \$this->end(); ?>\n"?>

<h2 class="content-header"><?php echo "<?php  echo __('{$singularHumanName}');?>"; ?></h2>
<?php echo "<?php echo \$this->Session->flash(); ?>\n" ?>

<dl class="attribute-list row">
<?php
    foreach ($fields as $field) {
        $isKey = false;
        if (!empty($associations['belongsTo'])) {
            foreach ($associations['belongsTo'] as $alias => $details) {
                if ($field === $details['foreignKey']) {
                    $isKey = true;
                    echo "\t<dt class=\"span2\"><?php echo __('" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></dt>\n";
                    echo "\t<dd class=\"span8\"><?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>&nbsp;</dd>\n";
                    break;
                }
            }
        }
        if($isKey !== true) {
            echo "\t<dt class=\"span2\"><?php echo __('" . Inflector::humanize($field) . "'); ?></dt>\n";
            if(in_array($field, array('created', 'modified', 'updated'))){                
                echo "\t<dd class=\"span8\"><?php echo \$this->Time->niceShort(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</dd>\n";
            }else{
                echo "\t<dd class=\"span8\"><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</dd>\n";
            }            
            
        }
    }
?>
</dl>
<?php
if (!empty($associations['hasOne'])) :
foreach ($associations['hasOne'] as $alias => $details):
    ?>
    <div class="related">
        <h3><?php echo "<?php echo __('" . $alias . "');?>"; ?></h3>
            <?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])):?>\n"; ?>
        <dl class="attribute-list row">
            <?php
            foreach ($details['fields'] as $field) {
                echo "\t<dt class=\"span2\"><?php echo __('" . Inflector::humanize($field) . "');?></dt>\n";
                echo "\t<dd class=\"span8\">\n\t<?php echo \${$singularVar}['{$alias}']['{$field}'];?>\n&nbsp;</dd>\n";
            }
            ?>
        </dl>
    <?php echo "<?php endif; ?>\n"; ?>
    </div>
    <?php
endforeach;
endif;
if (empty($associations['hasMany'])) {
$associations['hasMany'] = array();
}
if (empty($associations['hasAndBelongsToMany'])) {
$associations['hasAndBelongsToMany'] = array();
}
$relations = array_merge($associations['hasMany'], $associations['hasAndBelongsToMany']);
$i = 0;
foreach ($relations as $alias => $details):
$otherSingularVar = Inflector::variable($alias);
$otherPluralHumanName = Inflector::humanize($details['controller']);
?>
<div class="related">
    <h3><?php echo "<?php echo __('" . $otherPluralHumanName . "');?>"; ?></h3>
    <?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])):?>\n"; ?>
    <table class="table table-striped ">
        <thead>
            <tr>
<?php
                foreach ($details['fields'] as $field) {
                    echo "\t\t\t\t<th><?php echo __('" . Inflector::humanize($field) . "'); ?></th>\n";
                }
?>
                <th class="actions"><?php echo "<?php echo __('Actions');?>"; ?></th>
            </tr>
        </thead>
        <tbody> 
<?php
            echo "\t\t\t<?php\n";
            echo "\t\t\t\$i = 0;\n";
            echo "\t\t\tforeach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}): ?>\n";
            echo "\t\t\t<tr>\n";
            foreach ($details['fields'] as $field) {
                if(in_array($field, array('created', 'modified', 'updated'))){
                    echo "\t\t\t\t<td><?php echo \$this->Time->niceShort(\${$otherSingularVar}['{$field}']);?></td>\n";
                }else{
                    echo "\t\t\t\t<td><?php echo \${$otherSingularVar}['{$field}'];?></td>\n";
                }
            }

            echo "\t\t\t\t<td class=\"actions\">\n";
            echo "\t\t\t\t\t<?php echo \$this->Html->link(__('<i class=\"icon-share\"></i>View'), array('controller' => '{$details['controller']}', 'action' => 'view', \${$otherSingularVar}['{$details['primaryKey']}']), array('escape' => false)); ?>\n";
            echo "\t\t\t\t\t<?php echo \$this->Html->link(__('<i class=\"icon-edit\"></i>Edit'), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$otherSingularVar}['{$details['primaryKey']}']), array('escape' => false)); ?>\n";
            echo "\t\t\t\t\t<?php echo \$this->Html->confirm(__('<i class=\"icon-remove-circle\"></i>Delete'), array('controller' => '{$details['controller']}', 'action' => 'delete', \${$otherSingularVar}['{$details['primaryKey']}']), array('escape' => false), __('Are you sure you want to delete {$singularHumanName} #%s?', \${$otherSingularVar}['{$details['primaryKey']}']), __('Delete {$singularHumanName}?')); ?>\n";
            echo "\t\t\t\t</td>\n";
            echo "\t\t\t</tr>\n";

            echo "\t\t\t<?php endforeach; ?>\n";
?>
        </tbody>
    </table>
<?php echo "<?php endif; ?>\n"; ?>
</div>
<?php endforeach; ?>