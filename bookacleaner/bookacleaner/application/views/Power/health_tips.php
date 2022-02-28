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
    <div class="panel panel-primary" style="border-radius: 0px; border: 0px;">
        <div class="panel-heading" style="border-radius: 0; line-height: 34px;">
            Health Tips
        </div>
        <div class="panel-body">
            <div class="pull-right">
                <button class="btn btn-primary" onclick="$('#addHealthTips').modal('toggle')">Add Health Tips</button>
            </div>
            <div style="clear:both;"></div>
            <div class="panel panel-default">
                <table class="table table-striped table-hover ">
                    <thead>
                        <tr>
                            <th>
                                Title/Link
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <?php
                    $q = $this->db->query("SELECT * FROM `health_tips_tbl`");
                    foreach($q->result_array() as $row) {
                    ?>
                    <tr>
                        <td>
                            <h5>
                                <?php echo htmlspecialchars($row['health_tips_title']); ?>
                            </h5>
                            <small>
                                <?php echo htmlspecialchars($row['health_tips_link']); ?>
                            </small>
                        </td>
                        <td>
                            <?php
                                if(isset($_GET['delete'])) {
                                    $delete = (is_numeric($_GET['delete']) == 1) ? $_GET['delete']: ""; 
                                    if($delete == "") {
                                        redirect($textss . '/health_tips?error=There\'s something wrong!');
                                    }
                                    $q = $this->db->query("SELECT * FROM `health_tips_tbl` WHERE `health_tips_id` = '".$delete."'");
                                    if($q->num_rows() > 0) {
                                        // exist
                                        $this->db->query("DELETE FROM `health_tips_tbl` WHERE `health_tips_id` = '".$delete."'");
                                        redirect($textss . '/health_tips?success=Successfully deleted!');
                                    } else {
                                        // not existing
                                        redirect($textss . '/health_tips?error=There\'s something wrong!');
                                    }
                                }
                            ?>
                            <a class="btn btn-warning" onclick="edit('<?php echo $row['health_tips_title']; ?>','<?php echo $row['health_tips_link']; ?>','<?php echo $row['health_tips_id']; ?>')">Edit</a>
                            <a href="?delete=<?php echo $row['health_tips_id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
            <script type="text/javascript">
                function edit(title, link, id) {
                    $('#editHealthTips').modal('toggle');

                    $('input[name=title]').val(title);
                    $('input[name=link]').val(link);
                    $('input[name=id]').val(id);
                }
            </script>
            <div class="modal fade" id="editHealthTips" tabindex="-1" role="dialog" aria-labelledby="editHealthTipsLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title pull-left" id="editHealthTipsLabel">Edit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div style="clear:both;"></div>
                        </div>
                        <div class="modal-body">
                            <?php
                            if(isset($_POST['edit'])) {
                                $title = htmlentities($_POST['title']);
                                $link = htmlentities($_POST['link']);
                                $id = htmlentities($_POST['id']);
                                
                                $q = $this->db->query("SELECT * FROM `health_tips_tbl` WHERE `health_tips_id` = '".$id."'");
                                if($q->num_rows() > 0) {
                                    // existing
                                    $this->db->query("UPDATE `health_tips_tbl` SET `health_tips_title` = '".$title."', `health_tips_link` = '".$link."' WHERE `health_tips_id` = '".$id."' ");
                                    redirect($textss . '/health_tips?success=Successfully updated!');
                                } else {
                                    // not existing
                                    redirect($textss . '/health_tips?error=There\'s something wrong!');
                                }
                            }
                            ?>
                            <form action="" method="POST">
                                <div class="form-group">
                                    <input type="text" name="title" placeholder="Title..." required="" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <input type="text" name="link" placeholder="Link..." required="" class="form-control" />
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
            <div class="modal fade" id="addHealthTips" tabindex="-1" role="dialog" aria-labelledby="addHealthTipsLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title pull-left" id="addHealthTipsLabel">Add</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div style="clear:both;"></div>
                        </div>
                        <div class="modal-body">
                            <?php
                            if(isset($_POST['add'])) {
                                $title = htmlspecialchars($_POST['title']);
                                $link = htmlspecialchars($_POST['link']);
                                
                                
                                $this->db->query("INSERT INTO `health_tips_tbl` (`health_tips_title`, `health_tips_link`) VALUES ('".$title."','".$link."')");
                                redirect($textss . '/health_tips?success=Successfully updated!');
                            }
                            ?>
                            <form action="" method="POST">
                                <div class="form-group">
                                    <input type="text" name="title" placeholder="Title..." required="" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <input type="text" name="link" placeholder="Link..." required="" class="form-control" />
                                </div>
                                <button class="btn btn-primary btn-large" name="add" type="submit">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>