// Question number
var q = -1;

/*
 * strrepeat(s, l)
 * 
 * Repeat string s l times
 * 
 * @param str s String
 * @param int l Times to be repeated
 * 
 * @return str Repeated string
 */
function strrepeat(s, l) {
    var str = '';
    for (var index = 0; index < l; index++) {
        str += s;
    }
    return str;
}

/*
 * light_type(q)
 * 
 * Highlight the type of the question on the sidebar
 * 
 * @param element q The question
 */
function light_type(q) {
    var cs = document.getElementById('choiceSingle');
    var cm = document.getElementById('choiceMult');
    var ct = document.getElementById('choiceText');
    var t = q.getAttribute('data-qtype');
    if (t == 's') {
        cs.style.backgroundColor = "#dbd9d9";
    } else if (t == 't') {
        ct.style.backgroundColor = "#dbd9d9";
    } else if (t == 'm') {
        cm.style.backgroundColor = "#dbd9d9";
    }
}
/*
 * delete_light(q)
 * 
 * Remove sidebar highlights
 */
function delete_light() {
    document.getElementById('choiceSingle').style.backgroundColor = "#f1f1f1";
    document.getElementById('choiceMult').style.backgroundColor = "#f1f1f1";
    document.getElementById('choiceText').style.backgroundColor = "#f1f1f1";
}

/*
 * add_question()
 *
 * Adds a question to <div id="formelem">
 * 
 * @param str type Type of question
 */
function add_question(type) {
    q++;

    var el = document.getElementById("formelem");
    var newel = document.createElement("div");
    var n1 = document.createElement("br");
    var n3 = document.createElement("br");
    newel.setAttribute('id', 'q' + q);
    newel.setAttribute('onmouseover', 'light_type(this)');
    newel.setAttribute('onmouseout', 'delete_light()');
    if (type == 's') {
        var t = 'single';
        newel.setAttribute('data-qtype', 's');
    } else if (type == 't') {
        var t = 'text';
        newel.setAttribute('data-qtype', 't');
    } else if (type == 'm') {
        var t = 'multiple';
        newel.setAttribute('data-qtype', 'm');
    }

var b =     '<table><tr>' +
            '<td style="box-sizing: border-box;width: 100%"><input class="formQuestion" type="text" name="question[' + q + '][q]" maxlength="' + MAX_Q_LEN + '" placeholder="Question, type: ' + t + '" title="Type: ' + t + '" /></td>' +
            '<td><button class="deleteQuestion" type="button" onclick="del_question(' + q + ')">&times;</button></td>';

    if (type == 's' || type == 'm')
        b += '<td><button class="choiceAdd" type="button" onclick="add_choice(' + q + ')">+</button></td></tr></table>';
    else
        b += '</tr></table>';

    b +=        '<table style="display: table">' +
                    '<tr>' +
                        '<td>Required to answer?</td>' +
                        '<td><input class="formRadio" type="radio" name="question[' + q + '][req]" value="yes" /> Yes <input class="formRadio" type="radio" name="question[' + q + '][req]" value="no" /> No</td>' +
                    '</tr>' +
                '</table>' +
                '<input name="question[' + q + '][type]" type="hidden" value="' + t + '"/>' +
                '<div class="formContent" id="q' + q + '-c"></div>';

    newel.innerHTML = b;
    el.appendChild(n1);
    el.appendChild(n3);
    el.appendChild(newel);
    gen_choices(q, type);
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
        if (POLL_MODE === true) {
            document.getElementById('pollAnsType').value = "single";
            document.getElementById('addChoiceLi').style.display = "block";
            document.getElementById('choiceSingle').style.backgroundColor = "#dbd9d9";
            document.getElementById('choiceText').style.backgroundColor = "#f1f1f1";
            document.getElementById('choiceMult').style.backgroundColor = "#f1f1f1";
        }
        b += "<div id=\"q" + qid + "-col\">" +
                '<div class="formChoices" id="formChoices0">' +
                    '<div class="deleteChoiceDiv"><button class="deleteQuestion" type="button" onclick="del_choice(0)">&times;</button></div>' +
                    '<div class="formChoiceDiv"><input class="formTitle" style="width:100%;" type="text" name="question[' + qid + '][choices][] maxlength="' + MAX_C_LEN + '" placeholder="Choice text"></div>' +
                '</div>' + 
             "</div>";
    } else if (type === 'm') {
        if (POLL_MODE === true) {
            document.getElementById('pollAnsType').value = "multiple";
            document.getElementById('addChoiceLi').style.display = "block";
            document.getElementById('choiceMult').style.backgroundColor = "#dbd9d9";
            document.getElementById('choiceSingle').style.backgroundColor = "#f1f1f1";
            document.getElementById('choiceText').style.backgroundColor = "#f1f1f1";
        }
        b += "<div id=\"q" + qid + "-col\">" +
                '<div class="formChoices" id="formChoices0">' +
                    '<div class="deleteChoiceDiv"><button class="deleteQuestion" type="button" onclick="del_choice(0)">&times;</button></div>' +
                    '<div class="formChoiceDiv"><input class="formTitle" style="width:100%;" type="text" name="question[' + qid + '][choices][] maxlength="' + MAX_C_LEN + '" placeholder="Choice text"></div>' +
                '</div>' +
             "</div>";
    } else if (type === 't') {
        if (POLL_MODE === true) {
            document.getElementById('pollAnsType').value = "text";
            document.getElementById('addChoiceLi').style.display = "none";
            document.getElementById('choiceText').style.backgroundColor = "#dbd9d9";
            document.getElementById('choiceMult').style.backgroundColor = "#f1f1f1";
            document.getElementById('choiceSingle').style.backgroundColor = "#f1f1f1";
        }
        b += "<p>" +
             "<input class=\"formTitle\" style=\"width:100%;\" type=\"text\" name=\"question[" + qid + "][maxlen]\" maxlength=\"" + MAX_T_LEN + "\" placeholder=\"Max length (>512 is considered a paragraph, max is " + strrepeat("9", MAX_T_LEN) + ")\"/>" +
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
    var a = c.getElementsByClassName('formChoices').length;
    var z = document.createElement("div");
    var d = document.createElement("div");
    var f = document.createElement("div");
    d.setAttribute('class', 'deleteChoiceDiv');
    f.setAttribute('class', 'formChoiceDiv');
    var x = document.createElement("button");
    var y = document.createElement("input");
    x.setAttribute('class', 'deleteQuestion');
    x.setAttribute('onclick', 'del_choice(' + a + ')');
    x.setAttribute('type', 'button');
    x.innerHTML = "&times;";
    y.setAttribute('class', 'formTitle');
    y.setAttribute('type', 'text');
    y.setAttribute('name', 'question[' + qid + '][choices][]');
    y.setAttribute('maxlength', MAX_C_LEN);
    y.setAttribute('style', 'width:100%;')
    y.setAttribute('placeholder', 'Choice text');
    z.setAttribute('class', 'formChoices');
    z.setAttribute('id', 'formChoices' + a);
    d.appendChild(x);
    f.appendChild(y);
    z.appendChild(d);
    z.appendChild(f);
    c.appendChild(z);
}

/*
 * del_choice(qid, cid)
 *
 * Delete a choice with given ID
 *
 * @param int qid Question ID
 * @param int cid Choice ID to delete
 */
function del_choice(cid) {
    var b = document.getElementById('formChoices' + cid).remove();
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
