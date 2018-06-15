/*
 * delAllStyles()
 * 
 * Delete all page's stylesheets
 */
function delAllStyles() {
    var p = document.getElementsByTagName("head")[0];
    var l = p.getElementsByTagName("link");

    [].forEach.call(l, element => {
        // Do not remove the fontawesome CSS
        if (element.id != 'fontawesome')
            p.removeChild(element);
    });
}

/*
 * addStyle(l)
 * 
 * Add a new stylesheet
 * 
 * @param str link href to the new stylesheet
 */
function addStyle(link) {
    var p = document.getElementsByTagName("head")[0];
    var l = document.createElement("link");
    l.setAttribute('rel', 'stylesheet');
    l.setAttribute('href', link);
    l.setAttribute('type', 'text/css');
    p.appendChild(l);
}