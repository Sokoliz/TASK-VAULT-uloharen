<?php
if (isset($_SESSION['user'])) {
} else {
	header('Location: login.php');
	die();
}
?>
<!-- Modal na vytvorenie novej ulohy - toto som spravil aby sa dali pridavat ulohy do projektov -->
<div id="new-task-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="lead text-primary" >Create a New Task</h3>
                <a class="close text-dark btn" data-dismiss="modal">×</a>
            </div>
            <form name="task_todo" action="/task/create" method="POST" role="form">
                <div class="modal-body">
                    <label class="text-dark">Status<span class="text-danger pl-1">*</span></label>
                    <!-- Radio buttony na status ulohy - toto som pridal aby sa dalo hned nastavit v akom stave je uloha -->
                    <div class="form-group d-flex justify-content-around">                    
                        <div class="form-check">                        
                            <input class="form-check-input btn" type="radio" name="task_status" id="task_status_1" value="1" checked>
                            <label class="form-check-label" for="task_status_1">
                                To do
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input btn" type="radio" name="task_status" id="task_status_2" value="2">
                            <label class="form-check-label" for="task_status_2">
                                In progress
                            </label>
                        </div>
                        <div class="form-check" >
                            <input class="form-check-input btn" type="radio" name="task_status" id="task_status_3" value="3">
                            <label class="form-check-label" for="task_status_3">
                                Complete
                            </label>
                        </div>
                    </div>				
                    <div class="form-group">
                        <!-- Nazov ulohy - povinny udaj -->
                        <label class="text-dark" for="task_name">Task Name<span class="text-danger pl-1">*</span></label>
                        <input class="form-control" type="text" name="task_name" required>
                    </div>
                    <div class="form-group">
                        <!-- Popis ulohy - nepovinny udaj -->
                        <label class="text-dark" for="task_description">Description</label>
                        <textarea class="form-control" type="text" name="task_description"></textarea>
                    </div>
					<div class="form-group">
						<!-- Priorita ulohy - farebne oznacenie dolezitosti -->
						<label for="task_colour" class="text-dark">Priority</label>
						<select name="task_colour" class="form-control">
							<option value=""></option>
							<option style="color:#5cb85c" value="#5cb85c">&#9724; Low</option>						  
							<option style="color:#f0ad4e" value="#f0ad4e">&#9724; Medium</option>
							<option style="color:#d9534f" value="#d9534f">&#9724; High</option>													  
						</select>
				    </div>         
                    <div class="form-group d-flex justify-content-between mt-2">
                        <div class="col-12 m-0 p-1">  
                            <!-- Deadline ulohy - nepovinny udaj, ale dobry na sledovanie terminov -->
                            <label class="text-dark">Deadline</label>
                            <input type="date" class="form-control" runat="server" name="deadline" min="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd"/>
                        </div>                   
                    </div>                    
                    <div class="form-group">
                        <!-- Skryty input pre id projektu - aby som vedel kam patri tato uloha -->
                        <input hidden id="id_task_project" name="id_project" value=<?php echo $id_project_for_task; ?> >
                    </div>
                    <div class="form-group">
                        <!-- Skryty input pre id uzivatela - aby som vedel kto vytvoril ulohu -->
                        <input hidden id="id_user" name="id_user" value=<?php echo $_SESSION['user_id']; ?> >
                    </div>						
                </div>
                <div class="modal-footer">					
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!-- Tlacidlo na vytvorenie ulohy -->
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
