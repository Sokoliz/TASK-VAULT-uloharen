<?php
namespace App\Views\Modals;

/**
 * DeleteTaskModal class
 * 
 * This class handles rendering of the task deletion confirmation modal
 */
class DeleteTaskModal 
{
    /**
     * Task data
     * @var array
     */
    private $taskData;
    
    /**
     * Index used for modal identifier
     * @var int
     */
    private $index;
    
    /**
     * Constructor
     * 
     * @param array $taskData Task data including id_task, task_name, and id_project
     * @param int $index Index used for modal identifier
     */
    public function __construct(array $taskData, int $index) 
    {
        $this->taskData = $taskData;
        $this->index = $index;
    }
    
    /**
     * Render the modal
     * 
     * @return string Rendered HTML for the modal
     */
    public function render(): string
    {
        ob_start();
        ?>
<!-- Modal for task deletion confirmation -->
<div id="task-delete-<?php echo $this->index; ?>" class="col-sm modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="lead text-primary">Are you sure?</h3>
                <a class="close text-dark btn" data-dismiss="modal">×</a>
            </div>
            <form id="delete-task-form-<?php echo $this->index; ?>" name="task" action="/task/edit" method="POST" role="form">
                <div class="modal-body">
                    <!-- Task name to delete -->
                    <p class="text-dark">Do you want to delete <i class="text-primary"><?php echo $this->taskData['task_name']; ?> </i> ?</p>
                    <p class="text-dark">You won't be able to revert this!</p>
               
                    <div class="form-group">
                        <!-- Hidden input for task ID -->
                        <input type="hidden" name="id_task" value="<?php echo $this->taskData['id_task']; ?>">
                    </div>    
                    <div class="form-group">
                        <!-- Hidden input for project ID - needed for redirect -->
                        <input type="hidden" name="id_project" value="<?php echo $this->taskData['id_project']; ?>">
                        <input type="hidden" name="delete" value="1">
                    </div>                
                </div>
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    <!-- Delete button - red to indicate destructive action -->
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
        <?php
        return ob_get_clean();
    }
} 