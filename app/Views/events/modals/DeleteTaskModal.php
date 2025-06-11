<?php
namespace App\Views\Modals;

/**
 * Trieda DeleteTaskModal
 * 
 * Táto trieda sa stará o vytvorenie modálneho okna na potvrdenie
 * vymazania úlohy. Je to dôležité, lebo nechceme, aby užívatelia
 * len tak omylom mazali úlohy bez potvrdenia.
 */
class DeleteTaskModal 
{
    
    private $taskData;
    
    
    private $index;
    
    
    public function __construct(array $taskData, int $index) 
    {
        $this->taskData = $taskData;
        $this->index = $index;
    }
    
    
    public function render(): string
    {
        // Zapneme output buffering, aby sme mohli vrátiť celý HTML kód ako reťazec
        ob_start();
        ?>
<!-- Modálne okno pre potvrdenie vymazania úlohy -->
<div id="task-delete-<?php echo $this->index; ?>" class="col-sm modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="lead text-primary">Are you sure?</h3>
                <a class="close text-dark btn" data-dismiss="modal">×</a>
            </div>
            <form id="delete-task-form-<?php echo $this->index; ?>" name="task" action="/task/edit" method="POST"
                role="form">
                <div class="modal-body">
                    <!-- Názov úlohy, ktorá sa má vymazať -->
                    <p class="text-dark">Do you want to delete <i
                            class="text-primary"><?php echo $this->taskData['task_name']; ?> </i> ?</p>
                    <p class="text-dark">You won't be able to revert this!</p>

                    <div class="form-group">
                        <!-- Skryté pole pre ID úlohy - toto potrebujeme, aby controller vedel, ktorú úlohu má vymazať -->
                        <input type="hidden" name="id_task" value="<?php echo $this->taskData['id_task']; ?>">
                    </div>
                    <div class="form-group">
                        <!-- Skryté pole pre ID projektu - potrebné pre presmerovanie po vymazaní späť na projekt -->
                        <input type="hidden" name="id_project" value="<?php echo $this->taskData['id_project']; ?>">
                        <input type="hidden" name="delete" value="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    <!-- Tlačidlo vymazania - červené pre zdôraznenie, že ide o deštruktívnu akciu -->
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
        // Vrátime výstup z output bufferingu ako reťazec
        return ob_get_clean();
    }
} 