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
    <li><?php echo "<?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('action' => 'add')); ?>"; ?></li>            
<?php echo "<?php \$this->end(); ?>\n"?>
<?php echo "<?php \$this->start('hook_related_actions'); ?>\n"; ?>
<?php
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

<h2 class="content-header"><?php echo "<?php echo __('" . $pluralHumanName . "'); ?>"; ?></h2>
<?php echo "<?php echo \$this->Session->flash(); ?>\n" ?>

<table class="table table-striped ">
    <thead>
        <tr>
<?php foreach ($fields as $field): ?>
            <th><?php echo "<?php echo \$this->Paginator->sort('{$field}');?>"; ?></th>
<?php endforeach; ?>
            <th class="actions"><?php echo "<?php echo __('Actions');?>"; ?></th>
        </tr>
    </thead>
    <tbody> 
        <?php
        echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
        echo "\t\t<tr>\n";
        foreach ($fields as $field) {
            $isKey = false;
            if (!empty($associations['belongsTo'])) {
                foreach ($associations['belongsTo'] as $alias => $details) {
                    if ($field === $details['foreignKey']) {
                        $isKey = true;
                        echo "\t\t\t<td><?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>&nbsp;</td>\n";
                        break;
                    }
                }
            }
            if(in_array($field, array('created', 'modified', 'updated'))){
                echo "\t\t\t<td><?php echo \$this->Time->niceShort(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
            }elseif($isKey !== true){
                echo "\t\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
            }
        }
        echo "\t\t\t<td class=\"actions\">\n";
        echo "\t\t\t\t<?php echo \$this->Html->link(__('<i class=\"icon-share\"></i>View'), array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('escape' => false)); ?>\n";
        echo "\t\t\t\t<?php echo \$this->Html->link(__('<i class=\"icon-edit\"></i>Edit'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('escape' => false)); ?>\n";
        echo "\t\t\t\t<?php echo \$this->Html->confirm(__('<i class=\"icon-remove-circle\"></i>Delete'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('escape' => false), __('Are you sure you want to delete {$singularHumanName} #%s?', \${$singularVar}['{$modelClass}']['{$primaryKey}']), __('Delete {$singularHumanName}?')); ?>\n";
        echo "\t\t\t</td>\n";
        echo "\t\t</tr>\n";

        echo "\t\t<?php endforeach; ?>\n";
?>
    </tbody>
</table>
<div class="pagination pagination-centered">
    <ul>
<?php
        echo "\t\t<?php\n";
        echo "\t\techo \$this->Paginator->prev('«', array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled'));\n";
        echo "\t\techo \$this->Paginator->numbers(array('separator' => '', 'tag' => 'li'));\n";
        echo "\t\techo \$this->Paginator->next('»', array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled'));\n";
        echo "\t\t?>\n";
?>
    </ul>
</div>
<p class="pagination-summary">
    <?php echo "<?php echo \$this->Paginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} of {:count} total'))); ?>\n"; ?>
</p>