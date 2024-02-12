<?php

function toast($message, $bgColor, $textColor, $secondsToDisappear){
	echo "<script> toast('$message', '$bgColor', '$textColor', '$secondsToDisappear'); </script>";
}
