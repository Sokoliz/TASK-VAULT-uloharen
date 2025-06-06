<?php
if (isset($_SESSION['user'])) {
} else {
	header('Location: login.php');
	die();
}
?>
<!-- Modal na upravu ulohy - toto som spravil aby sa dali menit detaily uloh -->
<div id="task-edit-<?php echo $i; ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="lead text-primary" >Edit your task</h3>
                <a class="close text-dark btn" data-dismiss="modal">×</a>
            </div>
            <form name="task" action="/task/edit" method="POST" role="form">
                <div class="modal-body">				
                    <div class="form-group">
                        <!-- Nazov ulohy - povinny udaj -->
                        <label class="text-dark" for="edit_name">task Name<span class="text-danger pl-1">*</span></label>
                        <input class="form-control" type="text" name="task_name" value="<?php echo $s['task_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <!-- Popis ulohy - nepovinny udaj -->
                        <label class="text-dark" for="edit_description">Description</label>
                        <textarea class="form-control" type="text" name="task_description"><?php echo $s['task_description']; ?></textarea>
                    </div>
					<div class="form-group">
						<!-- Farba ulohy - oznacuje prioritu -->
						<label for="edit_colour" class="text-dark">Colour</label>
						<select name="task_colour" class="form-control" style="color:<?php echo $s['task_colour']; ?>" value="<?php echo $s['task_colour'];?>">
							<option style="color:<?php echo $s['task_colour']; ?>" value="<?php echo $s['task_colour'];?>">&#9724; 
                        <?php 
                         if ($s['task_colour'] == '#5cb85c') {echo "Green";} 
                         elseif ($s['task_colour'] == '#f0ad4e') {echo "Orange";} 
                         elseif ($s['task_colour'] == '#d9534f') {echo "Red";} 
                         else { echo ""; } ?>                      
                        
                        </option>
							<option style="color:#5cb85c" value="#5cb85c">&#9724; Green</option>						  
							<option style="color:#f0ad4e" value="#f0ad4e">&#9724; Orange</option>
							<option style="color:#d9534f" value="#d9534f">&#9724; Red</option>
						</select>
				    </div> 
                    
                    <div class="form-group d-flex justify-content-between mt-2">
                        <div class="col-12 m-0 p-1">  
                            <!-- Deadline ulohy - nepovinny udaj -->
                            <label class="text-dark">Deadline</label>
                            <input type="date" class="form-control" runat="server" name="deadline" value="<?php if( $s['deadline'] !== '1970-01-01'){echo $s['deadline'];} ?>" min="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd"/>
                        </div>                   
                    </div>                    
               
                    <div class="form-group">
                        <!-- Skryty input pre id uzivatela - aby som vedel kto to upravil -->
                        <input hidden id="id_user" name="user_id" value=<?php echo $_SESSION['user_id']; ?> >
                    </div>	

                    <div class="form-group">
                        <!-- Skryty input pre id projektu - aby som vedel kam patri tato uloha -->
                        <input hidden id="id_task_project" name="id_task_project" value="<?php echo $s['id_project']; ?>" >
                    </div>	  

                    <div class="form-group">
                        <!-- Skryty input pre id ulohy - aby som vedel co upravujem -->
                        <input hidden id="id_task" name="id_task" value="<?php echo $s['id_task']; ?>" >
                    </div>					
                </div>
                <div class="modal-footer">					
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!-- Tlacidlo na ulozenie zmien -->
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
