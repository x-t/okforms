function show_diff() {
    var d = document.getElementById('diff');
    d.innerHTML = "Polls can only have 1 question and the answers are public, while forms need a password to see the answers and can have more than one question.";
}

window.onload = function() {
    var diffOverlay = document.getElementById('diffOverlay');
    var overlayShow = document.getElementById('overlayShow');
    var overlayClose = document.getElementsByClassName('overlayClose')[0];
    var privacyOverlay = document.getElementById('privacyOverlay');
    var privacyOverlayShow = document.getElementById('gdprShow');
    var privacyClose = document.getElementsByClassName('privacyClose')[0];

    overlayShow.onclick = function() {
        diffOverlay.style.display = "block";
    }
    overlayClose.onclick = function() {
        diffOverlay.style.display = "none";
    }
    privacyClose.onclick = function() {
        privacyOverlay.style.display = "none";
    }
    privacyOverlayShow.onclick = function() {
        privacyOverlay.style.display = "block";
    }
    window.onclick = function(event) {
        if (event.target == diffOverlay) {
            diffOverlay.style.display = "none";
        } else if (event.target == privacyOverlay) {
            privacyOverlay.style.display = "none";            
        }
    }
}