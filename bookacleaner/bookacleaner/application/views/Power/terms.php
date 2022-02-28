<?php $this->load->view('Power/navigation'); ?>
<?php 
$textss = "";
if($this->session->selection == "doctor") {
    $textss = "doctor";
} else if($this->session->selection == "receptionist") {
    $textss = "receptionist";
} else if($this->session->selection == "administrator") {
    $textss = "administrator";
} 
?>
    <h4 class="pull-left">
        Pre-defined terms
    </h4>
    <div style="clear:both;"></div>
    <div class="row">
        <div class="col-md-6">
            <div style="border: 1px solid #eee;padding:10px;">
                <h4 style="margin-bottom: 30px;">
                    Common Illness
                    <div class="pull-right">
                        <button class="btn btn-primary" onclick="$('#addPreDefinedTerms').modal('toggle')" style="font-size: 14px;">Add Common Illness</button>
                    </div>
                </h4>
                
                <div style="clear:both;"></div>
                <div class="panel panel-default">
                    <table class="table table-striped table-hover ">
                        <thead>
                            <tr>
                                <th>
                                    Title/Description
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <?php
                        $q = $this->db->query("SELECT * FROM `terms_tbl`");
                        foreach($q->result_array() as $row) {
                        ?>
                        <tr>
                            <td>
                                <h6>
                                    <?php echo htmlspecialchars($row['terms_title']); ?>
                                </h6>
                                <small>
                                    <?php echo htmlspecialchars($row['terms_description']); ?>
                                </small>
                            </td>
                            <td>
                                <?php
                                    if(isset($_GET['delete'])) {
                                        $delete = (is_numeric($_GET['delete']) == 1) ? $_GET['delete']: ""; 
                                        if($delete == "") {
                                            redirect($textss . '/terms?error=There\'s something wrong!');
                                        }
                                        $q = $this->db->query("SELECT * FROM `terms_tbl` WHERE `terms_id` = '".$delete."'");
                                        if($q->num_rows() > 0) {
                                            // exist
                                            $this->db->query("DELETE FROM `terms_tbl` WHERE `terms_id` = '".$delete."'");
                                            redirect($textss . '/terms?success=Successfully deleted!');
                                        } else {
                                            // not existing
                                            redirect($textss . '/terms?error=There\'s something wrong!');
                                        }
                                    }
                                ?>
                                <a class="btn btn-warning" onclick="edit('<?php echo $row['terms_title']; ?>','<?php echo trim($row['terms_description']); ?>','<?php echo $row['terms_id']; ?>')">Edit</a>
                                <a href="?delete=<?php echo $row['terms_id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
                
                <div class="modal fade" id="editPreDefinedTerms" tabindex="-1" role="dialog" aria-labelledby="editPreDefinedTermsLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title pull-left" id="editPreDefinedTermsLabel">Edit</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div style="clear:both;"></div>
                            </div>
                            <div class="modal-body">
                                <?php
                                if(isset($_POST['edit'])) {
                                    $title = htmlentities($_POST['title']);
                                    $description = htmlentities($_POST['description']);
                                    $id = htmlentities($_POST['id']);
                                    
                                    if(strlen($title) == 0) {
                                        redirect($textss . '/terms?error=Please input the fields!');
                                    }

                                    if(strlen($description) == 0) {
                                        redirect($textss . '/terms?error=Please input the fields!');
                                    }

                                    if(strlen($id) == 0) {
                                        redirect($textss . '/terms?error=Please input the fields!');
                                    }


                                    $q = $this->db->query("SELECT * FROM `terms_tbl` WHERE `terms_id` = '".$id."'");
                                    if($q->num_rows() > 0) {
                                        // existing
                                        $this->db->query("UPDATE `terms_tbl` SET `terms_title` = '".$title."', `terms_description` = '".trim($description)."' WHERE `terms_id` = '".$id."' ");
                                        redirect($textss . '/terms?success=Successfully updated!');
                                    } else {
                                        // not existing
                                        redirect($textss . '/terms?error=There\'s something wrong!');
                                    }
                                }
                                ?>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="title" placeholder="Title..." required="" class="form-control"  require=""/>
                                    </div>
                                    <div class="form-group">
                                        <textarea type="text" name="description" placeholder="Description..." required="" class="form-control" require=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="id" required=""  />
                                    </div>
                                    <button class="btn btn-primary btn-large" name="edit" type="submit">Edit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="addPreDefinedTerms" tabindex="-1" role="dialog" aria-labelledby="addPreDefinedTermsLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title pull-left" id="addPreDefinedTermsLabel">Add</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div style="clear:both;"></div>
                            </div>
                            <div class="modal-body">
                                <?php
                                if(isset($_POST['add'])) {
                                    $title = htmlspecialchars($_POST['title']);
                                    $description = htmlspecialchars($_POST['description']);
                                    
                                    if(strlen($title) == 0) {
                                        redirect($textss . '/terms?error=Please input the fields!');
                                    }

                                    if(strlen($description) == 0) {
                                        redirect($textss . '/terms?error=Please input the fields!');
                                    }
                                    
                                    $this->db->query("INSERT INTO `terms_tbl` (`terms_title`, `terms_description`) VALUES ('".$title."','".trim($description)."')");
                                    redirect($textss . '/terms?success=Successfully updated!');
                                }
                                ?>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="title" placeholder="Title..." required="" class="form-control"  required=""/>
                                    </div>
                                    <div class="form-group">
                                        <textarea type="text" name="description" placeholder="Description..." required="" class="form-control" required=""></textarea>
                                    </div>
                                    <button class="btn btn-primary btn-large" name="add" type="submit">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div style="border: 1px solid #eee;padding:10px;">
                <h4 style="margin-bottom: 30px;">
                    Vaccine

                    <div class="pull-right">
                        <button class="btn btn-primary" onclick="$('#addVaccine').modal('toggle')" style="font-size: 14px;">Add Vaccine</button>
                    </div>
                </h4>
                
                <div style="clear:both;"></div>
                <div class="panel panel-default">
                    <table class="table table-striped table-hover ">
                        <thead>
                            <tr>
                                <th>
                                    Title/Description
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <?php
                        $q = $this->db->query("SELECT * FROM `vaccine_terms_tbl`");
                        foreach($q->result_array() as $row) {
                        ?>
                        <tr>
                            <td>
                                <h6>
                                    <?php echo htmlspecialchars($row['vaccine_terms_title']); ?>
                                </h6>
                                <small>
                                    <?php echo htmlspecialchars($row['vaccine_terms_description']); ?>
                                </small>
                            </td>
                            <td>
                                <?php
                                    if(isset($_GET['deleteVaccine'])) {
                                        $delete = (is_numeric($_GET['deleteVaccine']) == 1) ? $_GET['deleteVaccine']: ""; 
                                        if($delete == "") {
                                            redirect($textss . '/terms?error=There\'s something wrong!');
                                        }
                                        $q = $this->db->query("SELECT * FROM `vaccine_terms_tbl` WHERE `vaccine_terms_id` = '".$delete."'");
                                        if($q->num_rows() > 0) {
                                            // exist
                                            $this->db->query("DELETE FROM `vaccine_terms_tbl` WHERE `vaccine_terms_id` = '".$delete."'");
                                            redirect($textss . '/terms?success=Successfully deleted!');
                                        } else {
                                            // not existing
                                            redirect($textss . '/terms?error=There\'s something wrong!');
                                        }
                                    }
                                ?>
                                <a class="btn btn-warning" onclick="edit2('<?php echo $row['vaccine_terms_title']; ?>','<?php echo trim($row['vaccine_terms_description']); ?>','<?php echo $row['vaccine_terms_id']; ?>')">Edit</a>
                                <a href="?deleteVaccine=<?php echo $row['vaccine_terms_id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
                
                <div class="modal fade" id="editVaccine" tabindex="-1" role="dialog" aria-labelledby="editVaccineLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title pull-left" id="editVaccineLabel">Edit</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div style="clear:both;"></div>
                            </div>
                            <div class="modal-body">
                                <?php
                                if(isset($_POST['edit2'])) {
                                    $title = htmlspecialchars($_POST['vaccine_title']);
                                    $description = htmlspecialchars($_POST['vaccine_description']);
                                    $id = htmlspecialchars($_POST['vaccine_id']);
                                    
                                    if(strlen($title) == 0) {
                                        redirect($textss . '/terms?error=Please input the fields!');
                                    }

                                    if(strlen($description) == 0) {
                                        redirect($textss . '/terms?error=Please input the fields!');
                                    }

                                    if(strlen($id) == 0) {
                                        redirect($textss . '/terms?error=Please input the fields!');
                                    }


                                    $q = $this->db->query("SELECT * FROM `vaccine_terms_tbl` WHERE `vaccine_terms_id` = '".$id."'");
                                    if($q->num_rows() > 0) {
                                        // existing
                                        $this->db->query("UPDATE `vaccine_terms_tbl` SET 
                                        `vaccine_terms_title` = '".$title."', 
                                        `vaccine_terms_description` = '".trim($description)."'
                                        
                                        WHERE `vaccine_terms_id` = '".$id."'");
                                        redirect($textss . '/terms?success=Successfully updated!');
                                    } else {
                                        // not existing
                                        redirect($textss . '/terms?error=There\'s something wrong!');
                                    }
                                }
                                ?>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="vaccine_title" placeholder="Title..." required="" class="form-control"  require=""/>
                                    </div>
                                    <div class="form-group">
                                        <textarea type="text" name="vaccine_description" placeholder="Description..." required="" class="form-control" require=""></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="vaccine_id" required=""  />
                                    </div>
                                    <button class="btn btn-primary btn-large" name="edit2" type="submit">Edit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="addVaccine" tabindex="-1" role="dialog" aria-labelledby="addVaccineLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title pull-left" id="addVaccineLabel">Add</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div style="clear:both;"></div>
                            </div>
                            <div class="modal-body">
                                <?php
                                if(isset($_POST['add2'])) {
                                    $title = htmlentities($_POST['vaccine_title']);
                                    $description = htmlentities($_POST['vaccine_description']);
                                    
                                    if(strlen($title) == 0) {
                                        redirect($textss . '/terms?error=Please input the fields!');
                                    }

                                    if(strlen($description) == 0) {
                                        redirect($textss . '/terms?error=Please input the fields!');
                                    }
                                    
                                    $this->db->query("INSERT INTO `vaccine_terms_tbl` (`vaccine_terms_title`, `vaccine_terms_description`) VALUES ('".$title."','".trim($description)."')");
                                    redirect($textss . '/terms?success=Successfully updated!');
                                }
                                ?>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <input type="text" name="vaccine_title" placeholder="Title..." required="" class="form-control"  required=""/>
                                    </div>
                                    <div class="form-group">
                                        <textarea type="text" name="vaccine_description" placeholder="Description..." required="" class="form-control" required=""></textarea>
                                    </div>
                                    <button class="btn btn-primary btn-large" name="add2" type="submit">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function edit(title, description, id) {
            $('#editPreDefinedTerms').modal('toggle');

            $('input[name=title]').val(title);
            $('textarea[name=description]').val(description);
            $('input[name=id]').val(id);
        }

        function edit2(title, description, id) {
            $('#editVaccine').modal('toggle');

            $('input[name=vaccine_title]').val(title);
            $('textarea[name=vaccine_description]').val(description);
            $('input[name=vaccine_id]').val(id);
        }
    </script>
