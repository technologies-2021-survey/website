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
											x = x +'<p style="margin-top:4px;"><b style="display:block;">'+(data[i].name)+'</b><span class="text-'+i+'" style="display:block;"></span><i style="font-size:10px;">'+dateString+'</i></p>';
										x = x + '</div></a>';
									}
									$('.people-list').append(x);
									$('span.text-'+i).text(s);
								}
								if($count == 0) {
									var x='<div class="people-info"><center style="color:white;"><b>No results found.</b></center></div>';
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
							<span id="name"></span><br/>
							<span id="active"></span>
						</div>
						<div style="clear:both;"></div>
					</div>
					<div class="chat-history">
						<ul class="user-messages">
							
						</ul>
					</div>
					<div class="chat-footer">
						<form id="send">
							<textarea id="messages" class="form-control" placeholder="Type your message" rows="3" style="margin-bottom: 10px;"></textarea>
							<div class="pull-right">
								<button class="btn btn-primary" id="send">Send</button>
							</div>
						</form>
						<div style="clear:both;"></div>
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
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
		<script type="text/javascript">
			var myid = <?php echo $this->session->id; ?>;
			var selection = '<?php echo $this->session->selection; ?>';
			selection = selection.toLowerCase().replace(/\b[a-z]/g, function(letter) {
				return letter.toUpperCase();
			});
			var last_previously_chat_id = 0;
			<?php 

				$last_chat_id_query = $this->db->query("SELECT * FROM chats_history_tbl WHERE `chats_info_code` = '".$code."' ORDER BY chats_history_id DESC LIMIT 1")->result_array();
			?>
			var last_chat_id = <?php if($last_chat_id_query[0]['chat_next_id'] != "") {echo $last_chat_id_query[0]['chat_next_id'];} else {echo '0';} ?>;
			$(document).ready(function() {
				setInterval(getChats, 3000);
				$("form#send").submit(function(e) {
					e.preventDefault();
					var messages = $("#messages").val();
		
					$.ajax({
						type:"post",
						url: "<?php echo base_url().$textss;?>/send",
						data:{messages:messages,code:'<?php echo $code; ?>'},
						success:function(data) {
							if(data == "noexists") {
								console.log(data);
								Swal.fire({
								title: 'Ooops!',
								html: "This user is not exist.",
								icon: 'error'
								});
							} else if(data == "") {
								console.log(data);
								$("#messages").val("");
								
								const Toast2 = Swal.mixin({
									toast: true,
									position: 'bottom-start',
									showConfirmButton: false,
									timerProgressBar: false,
									didOpen: (toast) => {
										var promise = document.getElementById('my_audio').play();

										if (promise !== undefined) {
										promise.then(_ => {
											// Autoplay started!
										}).catch(error => {
											// Autoplay was prevented.
											// Show a "Play" button so that user can start playback.
										});
										}
										toast.addEventListener('mouseenter', Swal.stopTimer)
										toast.addEventListener('mouseleave', Swal.resumeTimer)
									}
								})

								Toast2.fire({
									icon: 'success',
									title: 'Successfully sent!'
								})
							} else {
								const Toast3 = Swal.mixin({
									toast: true,
									position: 'bottom-start',
									showConfirmButton: false,
									timerProgressBar: false,
									didOpen: (toast) => {
										var promise = document.getElementById('my_audio').play();

										if (promise !== undefined) {
										promise.then(_ => {
											// Autoplay started!
										}).catch(error => {
											// Autoplay was prevented.
											// Show a "Play" button so that user can start playback.
										});
										}
										toast.addEventListener('mouseenter', Swal.stopTimer)
										toast.addEventListener('mouseleave', Swal.resumeTimer)
									}
								})

								Toast3.fire({
									icon: 'error',
									title: data
								})
							}
						}
					});
				});

				$.ajax({
					type: "get",
					url:"<?php echo base_url().$textss; ?>/getNameandActive/<?php echo $code; ?>",
					dataType: 'json',
					success:function(data) {
						$('#name').text(data.full_name);
						$('#active').text(data.active);
					}
				});
			});
			function scrollTop() {
				$('div.chat-history').scrollTop(0);
			}
			function scrollDown() {
				$('div.chat-history').scrollTop($("div.chat-history")[0].scrollHeight);
				//$("div.chat-history").animate({ scrollTop: $("div.chat-history")[0].scrollHeight }, 2000);
			}
			function addChat(data, words, choice) {
				if(words != "") {
					var dateString = moment.unix(data.timestamp).utc().fromNow();
					var x = '<li class="replies" id="chats-'+data.chats_next_id+'" style="display: none;">';
						var profile_picture = (data.profile_picture != "") ? 'data:image/png;base64,'+data.profile_picture : '<?php echo base_url(); ?>assets/img/whealth.png';
						x = x +'<img src="'+profile_picture+'" class="img-circle img-thumbnail img-responsive" style="width: 36.99px;height:36.99px;">';
						x = x +'<div>';
							x = x +'<span>'+data.full_name+' - '+data.chats_account_type_text+'</span>';
							x = x +'<p><span class="text-chats-'+data.chats_next_id+'" style="font-size: 12px;margin-right: 0px; color: none; text-transform: none; font-weight: 400;"></span><label style="display: block; font-size: 9px; margin-top: 3px; margin: 0px;"><i class="far fa-clock"></i>&nbsp;&nbsp;'+dateString+'</label></p>';
							
						x = x +'</div>';
					x = x +'</li>';
					if(choice != "") {
						$('ul.user-messages').append(x);
						$("li#chats-"+data.chats_next_id).toggle();
					} else {
						$('ul.user-messages').prepend(x);
						$("li#chats-"+data.chats_next_id).toggle();
					}
					$("span.text-chats-"+data.chats_next_id).text(data.chats_message);
				} else {
					var dateString = moment.unix(data.timestamp).utc().fromNow();
					
					var x = '<li class="sent" id="chats-'+data.chats_next_id+'" style="display: none;">';
							var profile_picture = (data.profile_picture != "") ? 'data:image/png;base64,'+data.profile_picture : '<?php echo base_url(); ?>assets/img/whealth.png';
							x = x +'<img src="'+profile_picture+'" class="img-circle img-thumbnail img-responsive" style="width: 36.99px;height:36.99px;">';
							x = x +'<div>';
								x = x +'<span>'+data.full_name+' - '+data.chats_account_type_text+'</span>';
								x = x +'<p><span class="text-chats-'+data.chats_next_id+'" style="font-size: 12px; color: #fff; margin-left: 4px;text-transform: none; font-weight: 400;"></span> <label style="display: block; font-size: 9px; margin-top: 3px; margin: 0px;"><i class="far fa-clock"></i>&nbsp;&nbsp;'+dateString+'</label></p>';
								
							x = x +'</div>';
						x = x +'</li>';
					if(choice != "") {
						if($("li#chats-" + data.chats_next_id).length == 1) {
						} else {
							$('ul.user-messages').append(x);
							$("li#chats-"+data.chats_next_id).toggle();
						}
					} else {
						if($("li#chats-" + data.chats_next_id).length == 1) {
						} else {
							$('ul.user-messages').prepend(x);
							$("li#chats-"+data.chats_next_id).toggle();
						}
					}
					$("span.text-chats-"+data.chats_next_id).text(data.chats_message);
				}

				if(choice == "") {
					scrollTop();
					
				} else {
					scrollDown();
				}
				
			}

			function getChats() {
				$.ajax({
					type: "GET",
					url: '<?php echo base_url().$textss.'//getChats/';?><?php echo $code; ?>/'+last_chat_id, 
					dataType: 'json',
					success: function(data) {
						if(data.chats.length >= 1) {
							data.chats.reverse();
							for(var i = 0;i < data.chats.length;i++) {
								last_chat_id = data.chats[i].chats_next_id;
								if(data.chats[i].chats_id == myid && data.chats[i].chats_account_type_text == selection) {
									addChat(data.chats[i], "myself","hehe");
									last_previously_chat_id = last_chat_id;
								} else {
									addChat(data.chats[i], "", "hehe");
									last_previously_chat_id = last_chat_id;
								}
								
							}
							if($('li#seePreviously').length == 0) {
								$('ul.user-messages').prepend('<li id="seePreviously"><a onclick="getPreviouslyChats()" style="cursor:pointer;text-align: center; display: block; margin: -40px; margin-top: -13px; margin-bottom: 15px; padding: 16px; background: #7f96ad; color: #FFFF;font-weight:bold;">See more</a></li>');
							}
							if(last_previously_chat_id <= 0) {
								$('li#seePreviously').slideToggle(1000).remove();
							}
						}
					}
				});
			}

			function getPreviouslyChats() {
				if(last_previously_chat_id >= 1) {
					$('li#seePreviously').slideToggle(1000).remove();
					last_previously_chat_id -= 10;
					$.ajax({
						type: "GET",
						url: '<?php echo base_url().$textss.'//getPreviouslyChats/';?><?php echo $code; ?>/'+last_previously_chat_id,
						dataType: 'json',
						success: function(data) {
							if(data.chats.length >= 1) {
								for(var i = 0;i < data.chats.length;i++) {
									if(data.chats[i].chats_id != myid && data.chats[i].chats_account_type_text != selection) {
										addChat(data.chats[i], "myself", "");
										last_previously_chat_id = data.chats[0].chats_next_id;
									} else {
										addChat(data.chats[i], "","");
										last_previously_chat_id = data.chats[0].chats_next_id;
									}
								}
								if($('li#seePreviously').length == 0) {
									$('ul.user-messages').prepend('<li id="seePreviously"><a onclick="getPreviouslyChats()" style="cursor:pointer;text-align: center; display: block; margin: -40px; margin-top: -13px; margin-bottom: 15px; padding: 16px; background: #7f96ad; color: #FFFF; font-weight:bold;">See more</a></li>');
								}	
								if(last_previously_chat_id <= 0) {
									$('li#seePreviously').slideToggle(1000).remove();
								}
							}
						}
					});
				} else {
					$('li#seePreviously').slideToggle(1000).remove();
				}
			}
		</script>