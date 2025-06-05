<?php
if (isset($_SESSION['user'])) {
} else {
	header('Location: login.php');
	die();
}
 ?>
<!-- Modal na vymazanie projektu - toto som spravil aby sa uzivatel este raz opýtal ci chce vymazat projekt -->
<div id="project-delete-<?php echo $i; ?>" class="col-sm modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="lead text-primary" >Are you sure?</h3>
                <a class="close text-dark btn" data-dismiss="modal">×</a>
            </div>
            <form name="project" action="/project/edit" method="POST" role="form">
                <div class="modal-body">
                    <!-- Text na potvrdenie vymazania - nech vie co maze -->
                    <p class="text-dark">Do you want to delete <i class="text-primary"><?php echo $p['project_name']; ?> </i> ?</p>
                    <p class="text-dark">You won't be able to revert this!</p>
               
                    <div class="form-group">
                        <!-- Skryty input na id projektu - potrebujem ho poslat na server -->
                        <input hidden type="int" name="id_project" value="<?php echo $p['id_project']; ?>">
                        <input hidden type="int" name="delete" value="true">
                    </div>					
                </div>
                <div class="modal-footer">					
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    <!-- Tlacidlo na vymazanie - cervene aby bolo vyrazne -->
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
