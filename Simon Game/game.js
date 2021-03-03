var buttonColors = ['red', 'blue', 'green', 'yellow'];
var gamePattern = [];
var userClickedPattern = [];
var level = 0;
var started = false;

function nextSequence() {
	var randomNumber;
	var randomChosenColor;

	userClickedPattern = [];

	randomNumber = Math.floor( (Math.random() * 4) );
	randomChosenColor = buttonColors[randomNumber];

	gamePattern.push(randomChosenColor);

	$("#" + randomChosenColor).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);

	playSound(randomChosenColor);
	
	level++;
	$("#level-title").text("Level " + level);
}

$(".btn").on("click", function () {
	var userChosenColor = $(this).attr("id");

	playSound(userChosenColor);
	animatePress(userChosenColor);

	userClickedPattern.push(userChosenColor);
	checkAnswer(userClickedPattern.length - 1);
});

function playSound(name) {
	var sound = new Audio("sounds/" + name + ".mp3");
	sound.play();
}

function animatePress(currentColor) {
	$("." + currentColor).addClass("pressed");

	setTimeout(function () {
		$("." + currentColor).removeClass("pressed");
	}, 100);
}

$(document).on("keydown", function (e) {
	if (!started) {
		$("#level-title").text("Level " + level);
		nextSequence();
		started = true;
	}
});

function checkAnswer(currentLevel) {
	if (userClickedPattern[currentLevel] === gamePattern[currentLevel]) {
		if (userClickedPattern.length === gamePattern.length) {
			setTimeout(function () {
				nextSequence();
			}, 1000);
		}
	} else {
		playSound("wrong");

		$("body").addClass("game-over");

		setTimeout(function () {
			$("body").removeClass("game-over");
		}, 200);

		$("#level-title").text("Game Over, Press Any Key to Restart");

		gameOver();
	}
}

function gameOver() {
	level = 0;
	started = false;
	gamePattern = [];
	userClickedPattern = [];
}