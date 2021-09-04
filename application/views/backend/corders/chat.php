<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-app.js"></script>
<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-firestore.js"></script>





<div class="main-wrapper">
	
	<div class="flex-box">
		
		<div class="box-2">
			<div class="chat-container">
				<!-- <div class="heading"><i class="fa fa-user"></i>&nbsp;<span class="name"></span></div> -->
				<div class="messages">
					<div class="chats">
						<div class="message-container">
							

							<!-- <div class="message-block">
								<div class="user-icon"></div>
								<div class="message">Hi, Govardhan hhow are you..?</div>
							</div>
							<div class="message-block received-message" style="">
								<div class="user-icon"></div>
								<div class="message">Hi, Govardhan hhow are you..?</div>
							</div> -->
						

						</div>
					</div>
					<div class="write-message">
						<div class="message-area"> 
							<textarea class="message-input" placeholder="Type a message"></textarea>
							<button class="send-btn"><i class="fab fa-telegram-plane"></i>&nbsp;Send</button>
						</div>
					</div>
					
				</div>
				
			</div>
		</div>
	</div>
</div>



<script type="text/javascript" src="<?php echo base_url(); ?>resources/uploads/firestore-config.js"></script>
<script type="text/javascript">

	var chat_data = {}, user_uid,other_uid, chatHTML = '', chat_uuid = "", my_pic = "",other_pic="", userList = [], project_id = <?php echo $project_id; ?>;

	function startNow()
	{

	
		$.ajax({
			url : '<?php echo base_url()."admin/corders/loginUser"; ?>',
			method : 'POST',
			data : {project_id:project_id},
			success : function(resp){

				
				var response = JSON.parse(resp);

				console.log(response);

				if (response.status == 200) {
					var token = response.message.token;
					firebase.auth().signInWithCustomToken(token).catch(function(error) {
					  // Handle Errors here.
					  var errorCode = error.code;
					  var errorMessage = error.message;
					  
					  alert(errorMessage);

					}).then(function(data){
						if (data.user.uid != "") {
							user_uid = response.message.user_uid;
							other_uid = response.message.other_uid;
							my_pic = response.message.my_pic;
							other_pic = response.message.other_pic;

							
							fireauthChange();
							// window.location.href = "chat.php?user_2="+response.message.other_uid+"&project_id=50";
						}
					});
				}else{
					alert(response.message);
				}

				

				
			}
		})


	}
	startNow();





	function fireauthChange()
	{


		firebase.auth().onAuthStateChanged(function(user) {
		  if (user) {
		    console.log(user);
		    // user_uid = user.uid;

			// getUsers();
		    console.log(user_uid);
		    fireChat();
		  } else {
		    console.log("Not sign in");
		  }
		});
	}


		// function logout(){
			
		// 	$.ajax({
		// 		url : 'process.php',
		// 		method : 'POST',
		// 		data : {logoutUser:1},
		// 		success : function(response){
		// 			console.log(response);
		// 			firebase.auth().signOut().then(function() {
		// 			  console.log('Logout');

		// 			  window.location.href = "index.php";

		// 			}).catch(function(error) {
		// 			  // An error happened.
		// 			  console.log('Logout Fail');
		// 			});
		// 		}
		// 	});
			
		
		// }

		// function getUsers(){
		// 	userList.push({user_uid: 500, username: "mahev"});
		// }


		

		

		function fireChat(){
			console.log($(this).attr('uuid'));
			
			var name = "Mahev";
			var user_1 = user_uid;
			var user_2 = other_uid;
			$('.message-container').html('Connecting...!');

			// $(".name").text(name);

			$.ajax({
				url : '<?php echo base_url()."admin/corders/connectUser"; ?>',
				method : 'POST',
				data : {project_id:project_id},
				success : function(resposne){
					console.log(resposne);
					var resp = JSON.parse(resposne);
					chat_data = {
						chat_uuid : resp.message.chat_uuid,
						user_1_uuid : user_uid,
						user_2_uuid : other_uid,
						user_1_name : '',
						user_2_name : name
					};
					$('.message-container').html('Say Hi Designer');
					db.collection('chat').where('chat_uuid', '==', chat_data.chat_uuid)
					.orderBy("time")
					.get().then(function(querySnapshot){
						chatHTML = '';
						querySnapshot.forEach(function(doc){
							console.log(doc.data());
							if (doc.data().user_1_uuid == user_uid) {
								var src = '<img src="'+my_pic+'" />';
								chatHTML += '<div class="message-block received-message">'+
												'<div class="user-icon">'+src+'</div>'+
												'<div class="message">'+ doc.data().message +'</div>'+
											'</div>';

							}else{
								var src = '<img src="'+other_pic+'" />';
								chatHTML += '<div class="message-block">'+
												'<div class="user-icon">'+src+'</div>'+
												'<div class="message">'+ doc.data().message +'</div>'+
											'</div>';
							}

						});

						$(".message-container").html(chatHTML);

					});

					if (chat_uuid == "") {
						chat_uuid = chat_data.chat_uuid;
						realTime();
					}

				}
			});


		}


		


		$(".send-btn").on('click', function(){
			var message = $(".message-input").val();
			if(message != ""){
				db.collection("chat").add({
				    message : message,
				    user_1_uuid : user_uid,
				    user_2_uuid : other_uid,
				    chat_uuid : chat_data.chat_uuid,
				   	user_1_isView : 0,
				   	user_2_isView : 0,
				    time : new Date(),
				})
				.then(function(docRef) {
					$(".message-input").val("");
				    console.log("Document written with ID: ", docRef.id);
				})
				.catch(function(error) {
				    console.error("Error adding document: ", error);
				});
			}


		});
		var newMessage = '';
		function realTime(){
			db.collection('chat').where('chat_uuid', '==', chat_data.chat_uuid)
			.orderBy('time')
			.onSnapshot(function(snapshot) {
				newMessage = '';
		        snapshot.docChanges().forEach(function(change) {
		            if (change.type === "added") {
		                
		                console.log(change.doc.data());
		                
		                if (change.doc.data().user_1_uuid == user_uid) {
		                		var src = '<img src="'+my_pic+'" />';
								newMessage += '<div class="message-block received-message">'+
												'<div class="user-icon">'+src+'</div>'+
												'<div class="message">'+ change.doc.data().message +'</div>'+
											'</div>';

							}else{
								var src = '<img src="'+other_pic+'" />';
								newMessage += '<div class="message-block">'+
												'<div class="user-icon">'+src+'</div>'+
												'<div class="message">'+ change.doc.data().message +'</div>'+
											'</div>';
							}



		            }
		            if (change.type === "modified") {
		               
		            }
		            if (change.type === "removed") {
		                
		            }
		        });

		        if (chatHTML != newMessage) {
		        	$('.message-container').append(newMessage);
		        }
		        
		        $(".chats").scrollTop($(".chats")[0].scrollHeight);

		    });
	}
</script>
<style type="text/css">
	.main-wrapper{
	width: 100%;
	height: 100%;
	background: #ddd;
	position: relative;
	max-height: 500px;
	/*top: -50px;
	box-sizing: border-box;
	padding-top: 50px;*/
}
.flex-box{
	display: flex; 
	background: #ccc;
	justify-content: flex-start;
	align-items: stretch;
	height: 100%;
}
.flex-box .box-1{
	flex: 2;
	border-right: 1px solid #cecece;
	background: #fff;
}
.flex-box .box-2{
	flex: 5;
	background: #fff;
}
.messenger{
	height: 100%;
}
.chat-container{
	height: 100%;
	background: transparent;
	position: relative;
	padding-bottom: 100px;
	box-sizing: border-box;
}
.heading{
	width: 100%;
	font-weight: bold;
	text-align: center;
	font-size: 16px;
	height: 40px;
	line-height: 40px;
	background: #fff;
	border-bottom: 1px solid #cecece;
}
.users{
	height: 100%;
	overflow-x: hidden;
}
.users .user{
	display: flex;
	background: transparent;
	height: 50px;
	padding: 10px;
	cursor: pointer;
	transition: all 0.2s linear;
}
.users .user:hover{
	background: #cecece;
}
.users .user .user-image{
	width: 50px;
	height: 50px;
	background: #cecece;
	border-radius: 50px;
	padding: ;
}
.users .user:hover .user-image{
	background: #fff;
}
.users .user .user-details{
	height: 50px;
	padding: 10px;
}
.user-details span{
	display: block;
}
.messages{
	display: flex;
	flex-direction: column;
	height: 100%;
	justify-content: flex-end;

}
.chats{
	display: flex;
	flex: 1;
	justify-content: flex-start;
	overflow-x: hidden;
}
.write-message{
}
.message-area{
	height: 50px;
	bottom: 0;
	display: flex;
}
.message-area .message-input{
	flex: 10;
	height: 60px;
	box-sizing: border-box;
	padding: 20px 10px;
	font-family: 'Open Sans';
	resize: none;
	border: 0;
	border-top: 1px solid #cecece;  
	outline: none;
}
.message-area .send-btn{
	flex: 1;
	height: 60px;
	border: 0;
	background: #0084ff;
	color: #fff;
	font-weight: bold;
	font-size: 16px;
}
.message-container{
	width: 100%;
}
.message-block{
	position: relative;
	display: flex;
	margin:5px;
}
.message-block .user-icon, .message-block .user-icon img{
	width: 30px;
	height: 30px;
	background: #cecece;
	border-radius: 25px;
	display: inline-block;
}
.message-block .message{
	background: #0084ff;
	color: #fff;
	border-radius: 10px;
	padding:5px;
	display: inline-block;
	margin: 10px 5px;
}
.received-message{
	display: flex;
	flex: 1;
	justify-content: flex-end;
}
.received-message > .user-icon{
	order: 2;
}

.received-message > .message{
	order: 1;
}

/* Login / Register */

.content.active{
	display: block;
}
.login-register-btn > span{
	position: absolute;
	opacity: 0;
	transition: all 0.5s linear;
}

.login-register-btn > span.active{
	opacity: 1;
}
/* Login / Register */

#loader{
	height: 30px;
}
</style>