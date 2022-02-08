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
		
		<div class="chat-container">
			<div class="col-sm-4">
				<div class="search">
					<input type="text" id="search" class="form-control">
				</div>
				<div class="people-list">
					
				</div>
				<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
				<script type="text/javascript">
					$(document).ready(function() {
						getUsers("");
						setInterval(check, 3000);

						$("#search").on('keyup',function(){
							getUsers($('#search').val());
						});
					});
					function check() {
						if($('#search').val().length != 0) {

						} else {
							getUsers();
						}
					}
					function trancateTitle(title) {
						var length = 15;
						if (title.length > length) {
						title = title.substring(0, length)+'...';
						}
						return title;
					}
					function getUsers(search = "") {
						$.ajax({
							type: "get",
							url:"<?php echo base_url().$textss; ?>/displayUsers/"+search,
							dataType: 'json',
							success:function(data) {
								//console.log("Done!" + moment.unix(Date.now()).utc().fromNow());
								$('.people-list').html("");
								$count = 0;
								for(var i = 0; i < data.length; i++) {
									$count++;
									var x = '';
									if(data[i].code == "") {
										// not exist
										x = x +'<a href="<?php echo base_url() . $textss; ?>/addChat/'+data[i].id+'/'+data[i].selection+'"><div class="people-info">';
											var profile_picture = (data[i].profile_picture != "") ? 'data:image/png;base64,'+data[i].profile_picture : '<?php echo base_url(); ?>assets/img/whealth.png';
											x = x +'<img src="'+profile_picture+'" class="img-circle img-thumbnail img-responsive" style="height: 55px;width:55px;">';
											x = x +'<p style="margin-top:4px;"><b>'+trancateTitle(data[i].name)+'</b></p>';
										x = x + '</div></a>';
									} else {
										//exist
										var dateString = data[i].time == "" ? "" : moment.unix(data[i].time).utc().fromNow();
										var s = "";
										if(data[i].msg.length != 0) {

											s = trancateTitle(data[i].msg);
										}
									
										var profile_picture = (data[i].profile_picture != "") ? 'data:image/png;base64,'+data[i].profile_picture : '<?php echo base_url(); ?>assets/img/whealth.png';
										x = x +'<a href="<?php echo base_url() . $textss; ?>/messages/'+data[i].code+'"><div class="people-info">';
											x = x +'<img src="'+profile_picture+'" class="img-circle img-thumbnail img-responsive" style="height: 55px;width:55px;">';
											x = x +'<p style="margin-top:4px;"><b style="display:block;">'+(data[i].name)+'</b><span style="display:block;">'+s+'</span><i style="font-size:10px;">'+dateString+'</i></p>';
										x = x + '</div></a>';
									}
									$('.people-list').append(x);
								}
								if($count == 0) {
									var x='<div class="people-info"><center><b>No results found.</b></center></div>';
									$('.people-list').append(x);
								}
							}
						});
					}
				</script>
				<div style="clear:both;"></div>
			</div>
			<div class="col-sm-8" style="position: relative; height: 100%;">
				<div class="chat" style="height: 100%; position: relative;">
					<div class="chat-heading">
						<img src="<?php echo base_url(). 'assets/img/whealth.png';?>" class="img-circle img-thumbnail img-responsive">
						<div class="chat-info">
							<span id="name">...</span><br/>
							<span id="active">...</span>
						</div>
						<div style="clear:both;"></div>
					</div>
					<div class="chat-history">
					</div>
					<div class="chat-footer">
					</div>
				</div>
			</div>
		</div>
		<style type="text/css">
			.chat-container{height: 873px; background: #2c3e50;}
			.chat-heading { height: 99px; background: #fff; padding: 20px; position: absolute; margin: -15px; top: 15px; left: 0; right: 0; z-index: 1; }
			.chat-heading img{float: left;height: 100%;}
			.chat-info{margin-top: 10px; margin-left: 20px; display: block; float: left;}
			.chat-history { padding: 0px; padding-top: 112px!important; border-bottom: 2px solid white; overflow-y: scroll; overflow-x: hidden; margin: -15px; margin-top: 0px; height: 728px; background: #E6EAEA; }
			.chat-footer { background: #eee; margin: -15px; padding: 15px; margin-top: 15px; }
			.user-messages li div {display: inline-block; line-height: 130%; color: #fff;}
			.user-messages li div span { margin-left: 11px; text-transform: uppercase; color: #2c3e50; font-weight: bold; font-size: 9px; text-align: left; display: inherit; }
			.user-messages li.replies div span { margin-right: 11px; text-transform: uppercase; color: #2c3e50; font-weight: bold; font-size: 9px; text-align: right; display: inherit; }
			.user-messages li p { max-width: 300px; background: #2c3e50; padding: 9px 13px; border-radius: 20px; max-width: 205px;  word-break: break-word; }
			.user-messages li img { width: 37px; border-radius: 50%; float: left; }
			.user-messages li { display: inline-block; clear: both; float: left; margin: 0 0 5px 0; width: calc(100% - 25px); font-size: 0.9em; }
			.user-messages li.replies img { width: 37px; border-radius: 50%; float: right; }
			.user-messages li.sent p {margin-left:10px;}
			.user-messages li.replies div { float: right; } 
			.user-messages li.replies div p {margin-right: 10px; background: #fff; color: #2c3e50; text-align: right;}
			@media screen and (min-width: 768px) {
				.user-messages li p {
					max-width: 300px;
				}
			}
			
			.search {padding: 10px; margin: -15px; margin-top: 0px; margin-bottom: 0px; background: #1b2631;}
			
			
			@media (min-width: 769px) {
				.people-list { height: 819px; overflow-y: scroll; margin: -15px; margin-top: 0; padding: 10px;}
				.people-info {
					height: 77px;
					padding: 11px;
					color: #fff;
				}
				.people-info img {
					height: 100%;
					float: left;
				}
				.people-info p { margin-left: 13px; margin-top: 10px; display: inline-block; }	
			}
			@media (max-width: 768px)  {
				.people-list { width: 100%; word-break: break-all; height: 117px; overflow-x: scroll; overflow-y: hidden; white-space: nowrap; margin: 0px; padding-top: 10px; margin-bottom: 0px; margin-top: 0px; }
				.people-info { width: 185px; display: inline-block; height: inherit; padding: 12px 22px; word-break: break-all; text-align: center; }
				.people-info:hover {transition:0.5s; opacity: 0.5;}
				.people-info img { display: block; height: 39px; text-align: center; margin: 0 auto; }
				.people-info p { color: white; text-align: center; margin-top: 6px; font-size: 12px; word-break: break-word; }
				.people-info p span {display: none!important;}
				.people-info p i {display: none!important;}
				.people-info p br {display: none;}
			}
		</style>