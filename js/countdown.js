/*
 * countdown(seconds, duringCounting, afterCountdown)
 * 
 * @param int seconds Seconds to count down to
 * @param function duringCounting Function to execute every second
 * @param function afterCountdown Function to execute after counting down
 */
function countdown(seconds, duringCounting, afterCountdown) {
    var counting = seconds;
    var counter = setInterval(function() {
        counting--;
        if (counting == 0) {
            clearInterval(counter);
            afterCountdown();
        } else {
            duringCounting(counting);
        }
    }, 1000)
}