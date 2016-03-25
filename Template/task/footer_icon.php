<?php 
$metadata = $this->task->taskMetadata->getAll($task['id']);
if (! empty($metadata)):
?>
        <span title="<?= t('Metadata') ?>" class="tooltip" data-href="<?= $this->url->href('metadata', 'task_footer', array('plugin' => 'metadata', 'task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>">
            <i class="fa fa-plus-square-o fa-fw"></i><?= count($metadata) ?>
        </span>
<?php endif ?>