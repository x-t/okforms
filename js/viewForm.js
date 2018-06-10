
/*
 * admin_creds(id)
 *
 * Create a form to redirect to the form answers page
 *
 * @param str id The form's ID
 */
function admin_creds(id) {
    var a = document.getElementById("answers");
    var newel = document.createElement("form");
    newel.setAttribute('action', '/viewForm/answers.php');
    newel.setAttribute('method', 'post');
    var b =     '<input type="hidden" name="form_id" value="' + id + '" />' +
                '<input type="text" name="password" maxlength="' + MAX_P_LEN + '" placeholder="Form password" />' +
                '<input type="submit" value="view" />';
    newel.innerHTML = b;
    a.innerHTML = "";
    a.appendChild(newel);
}

/*
 * report_form(id)
 * 
 * Create a form to report a form
 * 
 * @param str id The form's ID
 */
function report_form(id) {
    var br = document.createElement("br");
    var a = document.getElementById("report");
    var newel = document.createElement("form");
    newel.setAttribute("action", "/viewForm/report.php");
    newel.setAttribute("method", "post");
    newel.setAttribute("id", "reportform");
    var texta = document.createElement("textarea");
    texta.setAttribute("form", "reportform");
    texta.setAttribute("rows", 4);
    texta.setAttribute("cols", 50);
    texta.setAttribute("maxlength", MAX_R_LEN);
    var submitb = document.createElement("input");
    submitb.setAttribute("type", "submit");
    submitb.setAttribute("value", "Submit report");
    texta.setAttribute("placeholder", "Explain why you wish to report this");
    texta.setAttribute("name", "reason");
    var b =     '<input type="hidden" name="form_id" value="' + id + '" />';
    newel.innerHTML = b;
    a.innerHTML = "";
    a.appendChild(newel);
    newel.appendChild(texta);
    newel.appendChild(br);
    newel.appendChild(submitb);
}
