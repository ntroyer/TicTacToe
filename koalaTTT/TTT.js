

window.p_id = "";
window.gameID="";
var loop;



$(document).ready(function(){
	$("#leaderboard").click(function(){
		$(".play").hide();
		$("#addfriend").hide();
		$("#home").show();
		$("#friendslist").hide();
		$("#leadertable").show();
		$.ajax({
			url: "showLeaderboard.php",
			type: "POST",
			success: function(data){
					$("#leadertable").html(data);
				}
		});
		
	});
});
	
	
$(document).ready(function(){
	$("#friendslist").click(function(){
		$p_id = window.p_id;
		$(".play").hide();	
		$("#friendslist").hide();
		$("#addfriend").hide();
		$("#home").show();
		$("#friendstable").show();
		$.ajax({
			url: "showFriends.php",
			type: "POST",
			data: {id: $p_id},
			success: function(data){
					$("#friendstable").html(data);
				}
		});
		
	});
});	
			
$(document).ready(function(){
	$("#home").click(function(){
		$("#grid").hide();
		$("#leadertable").hide();
		$(".image").hide();
		$("#addFriendEmail").hide();
		$("#addfriend").show();
		$(".play").show();
		$("#friendslist").show();
		$("#home").hide();
		$("#friendstable").hide();
		
		$.ajax({
			url: "dropGame.php",
			type:"POST",
			data:{gameID:window.gameID},
		});
		clearInterval(loop);
		
	});
	
});


$(document).ready(function(){
	$("#addfriend").click(function(){
		$(".play").hide();
		$("#addfriend").hide();
		$("#friendslist").hide();
		$("#home").show();
		$("#addFriendEmail").show();
	});
});


$(document).ready(function(){
	$("#submitbutton").click(function(){

	$.ajax({
		url: "checkLogin.php",
    	type: "POST",
    	data: $("#signin").serialize(),
    	success: function(msg){
        			if(msg=="0"){
        			alert("Incorrect email/password combination");
        			}
        			else{
        				window.p_id=msg;
        				$("#signin").hide();
	 					$(".play").show();
	 					$("#addfriend").show();
	 					$("#home").hide();
	 					$("#friendslist").show();
        				
        			}
    	 		}
		});
	
	return false;	
	
	});

});

$(document).ready(function(){
	$("#submitAddFriend").click(function(){
	$.ajax({
		url: "addFriend.php",
    	type: "POST",
    	data: $("#addFriendEmail").serialize() + "&id="+ window.p_id.toString(),
    	success: function(msg){
        			alert(msg);

    	 		}
		});
	
	return false;	
	
	});

});


$(document).ready(function(){
	$("#playComp").click(function(){
		
		$(".play").hide();
		$("#addfriend").hide();
		$("#friendslist").hide();
		$("#home").show();
		$("#grid").show();
		
		$.ajax({
			url:"createGameComp.php",
			type:"POST",
			data:{id: window.p_id},
			success:function(msg){
				info = JSON.parse(msg)
		
				window.gameID=info[0];
				window.playerTurn=info[1];
				loop = setInterval(function(){updateGameState()}, 1000);
			}
		});
	});

});

$(document).ready(function(){
	$("#playFriend").click(function(){
		
		$(".play").hide();
		$("#addfriend").hide();
		$("#friendslist").hide();
		$("#home").show();
		$("#grid").show();
		
		$.ajax({
			url:"createGameFriend.php",
			type:"POST",
			data:{id: window.p_id},
			success:function(msg){
				info = JSON.parse(msg)
		
				window.gameID=info[0];
				window.playerTurn=info[1];
				loop = setInterval(function(){updateGameState()}, 1000);
			}
		});
	});

});

$(document).ready(function(){
	$("#playRandom").click(function(){
		$(".play").hide();
		$("#addfriend").hide();
		$("#friendslist").hide();
		$("#home").show();
		$("#grid").show();
		$p_id = window.p_id;
		$.ajax({
			url:"createGameRandom.php", 
			type:"POST",
			data:{id: $p_id},    //send your id, get the gameID back from server
			success:function(msg){
				info = JSON.parse(msg)
		
				window.gameID=info[0];
				window.playerTurn=info[1];
				loop = setInterval(function(){updateGameState()}, 1000);
			}
		});
		
		
	});

});



$(document).ready(function(){
	$(".box").on("click",function(event){
    	boxClick(event.delegateTarget);
	});
});

function boxClick(box){
//every time user clicks box, we send a request to do that move
//Server decides if move is valid and whether to execute or not
	$num = $(box).attr("id").charCodeAt(0) - 48;
	$.ajax({
	url: "boxClick.php",
    type: "POST",
    data: {box:$num, gameId:window.gameID, userID:window.p_id},
    });
}

function playGameRandom(){
	window.loop = setInterval(updateGameState(), 1000);	
}

function gameOverAction(outcome){
	
	var r;
	if(outcome=="W"){
		r=confirm("You win! Do you want to play again?");
	}
	else if(outcome=="L"){
		r=confirm("You lose! Do you want to play again?");
	}
	else{
		r=confirm("DRAW! Do you want to play again?");
	}
	$(".image").hide();
	if(r==true){
		
		$.ajax({
			url: "restartGame.php",
			type:"POST",
			data: {gameId:window.gameID},
			success: function(){
					loop = setInterval(function(){updateGameState()}, 1000);
					}
			});
		
	}
	else{
		$.ajax({
			url: "dropGame.php",
			type:"POST",
			data:{gameID:window.gameID},
		});
		
		$("#grid").hide();
		$(".play").show();
		$("#addfriend").show();
		$("#friendslist").show();
		$("#home").hide();
		
		
		
	}
}
			
	

function updateGameState(){
	$gameID = window.gameID;
	$.ajax({
		url:"getGameState.php",
		type:"POST",
		data: {gameID:$gameID}, 
		success: function(msg){
				var update = JSON.parse(msg);
				if(update.length==1){
					clearInterval(loop);
					if(update[0]=="X"){
						if(window.playerTurn=="X"){
							gameOverAction("W");
						}
						else{
							gameOverAction("L");
						}					
					}
					else if(update[0]=="O"){
						if(window.playerTurn=="O"){
							gameOverAction("W");
						}
						else{
							gameOverAction("L");
						}
					}
					else if(update[0]=="T"){
						gameOverAction("T");
					}
				}
				else{
					drawGameState(update);
				}
			}
		});
	
}


function drawGameState(gameState){
			
	if(gameState[0]=="X"){
		drawX("#0");
	}
	
	if(gameState[0]=="O"){
		drawO("#0");
	}
	if(gameState[1]=="X"){
		drawX("#1");
	}
	
	if(gameState[1]=="O"){
		drawO("#1");
	}
	if(gameState[2]=="X"){
		drawX("#2");
	}
		
	if(gameState[2]=="O"){
		drawO("#2");
	}
	if(gameState[3]=="X"){
		drawX("#3");
	}
	
	if(gameState[3]=="O"){
		drawO("#3");
	}	
	if(gameState[4]=="X"){
		drawX("#4");
	}
	
	if(gameState[4]=="O"){
		drawO("#4");
	}
	if(gameState[5]=="X"){
		drawX("#5");
	}
	
	if(gameState[5]=="O"){
		drawO("#5");
	}
	if(gameState[6]=="X"){
		drawX("#6");
	}
		
	if(gameState[6]=="O"){
		drawO("#6");
	}
	if(gameState[7]=="X"){
		drawX("#7");
	}
	
	if(gameState[7]=="O"){
		drawO("#7");
	}
	if(gameState[8]=="X"){
		drawX("#8");
	}
	
	if(gameState[8]=="O"){
		drawO("#8");
	}		
	
}

function drawX(box){

	var setTop = $(box).offset().top+25;
	var setLeft = $(box).offset().left+25;

	$newX = $("#X").clone().appendTo("body");
	$newX.css({"position":"absolute","top":setTop, "left":setLeft});
	$newX.fadeIn(1000);	
		
				
}

function drawO(box){

	var setTop = $(box).offset().top+25;
	var setLeft = $(box).offset().left+25;

	$newO = $("#O").clone().appendTo("body");
	$newO.css({"position":"absolute","top":setTop, "left":setLeft});
	$newO.fadeIn(1000);	
					
}	
