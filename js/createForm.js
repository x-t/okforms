// Question number
var q = -1;

/*
 * add_question()
 *
 * Adds a question to <div id="formelem">
 */
function add_question() {
    q++;

    var el = document.getElementById("formq");
    var newel = document.createElement("li");
    newel.setAttribute('id', 'q' + q);

    var b =     '<button type="button" onclick="del_question(' + q + ')">delete</button><br />' +
                '<p>Question: <input type="text" name="question[' + q + '][q]" maxlength="' + MAX_Q_LEN + '" /></p>' +
                '<p>Required to answer?<br />' +
                '<table style="display: table">' +
                '<tbody>' +
                    '<tr>' +
                        '<td>Yes</td>' +
                        '<td><input type="radio" name="question[' + q + '][req]" value="yes" /></td>' +
                    '</tr>' +
                    '<tr>' +
                        '<td>No</td> ' +
                        '<td><input type="radio" name="question[' + q + '][req]" value="no" /></td>' +
                    '</tr>' +
                '</tbody>' +
                '</table>' +
                '</p>' +
                '<p>Type:<br />' +
                '<table style="display: table"> ' +
                '<tbody>' +
                    '<tr>' +
                        '<td>Single choice</td>' +
                        '<td><input type="radio" name="question[' + q + '][type]" value="single" onclick="gen_choices(' + q + ', \'s\')" /></td>' +
                    '</tr>' +
                    '<tr>' +
                        '<td>Multiple choices</td>' +
                        '<td><input type="radio" name="question[' + q + '][type]" value="multiple" onclick="gen_choices(' + q + ', \'m\')" /></td>' +
                    '</tr>' +
                    '<tr>' +
                        '<td>Text field</td>' +
                        '<td><input type="radio" name="question[' + q + '][type]" value="text" onclick="gen_choices(' + q + ', \'t\')" /></td>' +
                    '</tr>' +
                '</tbody>' +
                '</table>' +
                '</p>' +
                '<div id="q' + q + '-c"></div>';

    newel.innerHTML = b;
    el.appendChild(newel);
}

/*
 * gen_choices(qid, type)
 *
 * Generate choices for question_id with question_type
 *
 * @param int qid Question ID to generate choices for
 * @param str type Type of choices to generate
 */
function gen_choices(qid, type) {
    var content = document.getElementById("q" + qid + "-c");
    var b = "";

    // s - single selection
    // m - multiple selections
    // t - text field

    if (type === 's') {
        b += "<table id=\"q" + qid + "-col\">" +
             "<tbody>" +
             "<tr>" +
             "<td><button type=\"button\" onclick=\"del_choice(" + qid + ", 0)\">delete</button></td>" +
             "<td><input type=\"text\" name=\"question[" + qid + "][choices][]\" maxlength=\"" + MAX_C_LEN + "\" /></td>" +
             "</tr>" +
             "</tbody>" +
             "</table>" +
             "<button type=\"button\" onclick=\"add_choice(" + qid + ")\">Add a choice</button>";
    } else if (type === 'm') {
        b += "<table id=\"q" + qid + "-col\">" +
             "<tbody>" +
             "<tr>" +
             "<td><button type=\"button\" onclick=\"del_choice(" + qid + ", 0)\">delete</button></td>" +
             "<td><input type=\"text\" name=\"question[" + qid + "][choices][]\" maxlength=\""+ MAX_C_LEN + "\"/></td>" +
             "</tr>" +
             "</tbody>" +
             "</table>" +
             "<button type=\"button\"onclick=\"add_choice(" + qid + ")\">Add a choice</button>";
    } else if (type === 't') {
        b += "<p>" +
             "<div style=\"display: inline\" title=\"&gt;512 characters is a paragraph. Max is " + '9'.repeat(MAX_T_LEN) + "\">Maximum length</div>: <input type=\"text\" name=\"question[" + qid + "][maxlen]\" maxlength=\"" + MAX_T_LEN + "\"><br>" +
             "</p>";
    }

    content.innerHTML = b;
}

/*
 * add_choice(qid)
 *
 * Add a choice for single and multiple selection questions
 *
 * @param int qid Question ID
 */
function add_choice(qid) {
    var c = document.getElementById('q' + qid + '-col');
    var n = document.createElement("tr");
    var a = c.getElementsByTagName('tr').length;
    var i = "<td><button type=\"button\" onclick=\"del_choice("+ qid + ', ' + a + ")\">delete</button></td>" +
            "<td><input type=\"text\" name=\"question[" + qid + "][choices][]\" maxlength=\"" + MAX_C_LEN + "\" /></td>";
    n.innerHTML = i;
    c.appendChild(n);
}

/*
 * del_choice(qid, cid)
 *
 * Delete a choice with given ID
 *
 * @param int qid Question ID
 * @param int cid Choice ID to delete
 */
function del_choice(qid, cid) {
    var a = document.getElementById("q" + qid + "-col");
    a.removeChild(a.getElementsByTagName('tr')[cid]);
}

/*
 * del_question(qid)
 *
 * Delete a question with given ID
 *
 * @param int qid Question ID to delete
 */
function del_question(qid) {
    var q = document.getElementById("q" + qid);
    q.parentNode.removeChild(q);
}

