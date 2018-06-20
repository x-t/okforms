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
    var cd = document.getElementById('choiceDrop');
    var cl = document.getElementById('choiceLinear');
    var t = q.getAttribute('data-qtype');
    if (t == 's') {
        cs.style.backgroundColor = "#dbd9d9";
    } else if (t == 't') {
        ct.style.backgroundColor = "#dbd9d9";
    } else if (t == 'm') {
        cm.style.backgroundColor = "#dbd9d9";
    } else if (t == 'd') {
        cd.style.backgroundColor = "#dbd9d9";
    } else if (t == 'l') {
        cl.style.backgroundColor = "#dbd9d9";
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
    document.getElementById('choiceDrop').style.backgroundColor = "#f1f1f1";
    document.getElementById('choiceLinear').style.backgroundColor = "#f1f1f1";
}

/*
 * poll_light(type)
 * 
 * Highlight the type of question in poll
 * 
 * @param str type The type of question
 */
function poll_light(type) {
    if (type == 's') {
        var t = 'single';
    } else if (type == 't') {
        var t = 'text';
    } else if (type == 'm') {
        var t = 'multiple';
    } else if (type == 'd') {
        var t = 'dropdown';
    } else if (type == 'l') {
        var t = 'linear';
    }

    document.getElementById('pollAnsType').value = t;
    if (type === 't' || type === 'l')
        document.getElementById('addChoiceLi').style.display = "none";
    else
        document.getElementById('addChoiceLi').style.display = "block";

    delete_light();

    if (type == 's') {
        document.getElementById('choiceSingle').style.backgroundColor = "#dbd9d9";
    } else if (type == 't') {
        document.getElementById('choiceText').style.backgroundColor = "#dbd9d9";
    } else if (type == 'm') {
        document.getElementById('choiceMultiple').style.backgroundColor = "#dbd9d9";
    } else if (type == 'd') {
        document.getElementById('choiceDrop').style.backgroundColor = "#dbd9d9";
    } else if (type == 'l') {
        document.getElementById('choiceLinear').style.backgroundColor = "#dbd9d9";
    }

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
    } else if (type == 'd') {
        var t = 'dropdown';
        newel.setAttribute('data-qtype', 'd');
    } else if (type == 'l') {
        var t = 'linear';
        newel.setAttribute('data-qtype', 'l');
    }

var b =     '<table><tr>' +
            '<td style="box-sizing: border-box;width: 100%"><input class="formQuestion" type="text" name="question[' + q + '][q]" maxlength="' + MAX_Q_LEN + '" placeholder="Question, type: ' + t + '" title="Type: ' + t + '" /></td>' +
            '<td><button class="deleteQuestion" type="button" onclick="del_question(' + q + ')">&times;</button></td>';

    if (type == 's' || type == 'm' || type == 'd')
        b += '<td><button class="choiceAdd" type="button" onclick="add_choice(' + q + ')">+</button></td></tr></table>';
    else
        b += '</tr></table>';

    b +=        '<table style="display: table;margin-bottom:3px;">' +
                    '<tr>' +
                        '<td>Required to answer?</td>' +
                        '<td><div class="droplist"><select name="question[' + q + '][req]">' + 
                        '<option value="yes">Yes</option><option value="no">No</option></select>' +
                        '<span class="fas fa-angle-down"></span></div></td>' +
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
            poll_light('s');
        }
        b += "<div id=\"q" + qid + "-col\">" +
                '<div class="formChoices" id="formChoices0">' +
                    '<div class="deleteChoiceDiv"><button class="deleteQuestion" type="button" onclick="del_choice(0)">&times;</button></div>' +
                    '<div class="formChoiceDiv"><input class="formTitle" style="width:100%;" type="text" name="question[' + qid + '][choices][] maxlength="' + MAX_C_LEN + '" placeholder="Choice text"></div>' +
                '</div>' + 
             "</div>";
    } else if (type === 'd') {
        if (POLL_MODE === true) {
            poll_light('d');
        }
        b += "<div id=\"q" + qid + "-col\">" +
                '<div class="formChoices" id="formChoices0">' +
                    '<div class="deleteChoiceDiv"><button class="deleteQuestion" type="button" onclick="del_choice(0)">&times;</button></div>' +
                    '<div class="formChoiceDiv"><input class="formTitle" style="width:100%;" type="text" name="question[' + qid + '][choices][] maxlength="' + MAX_C_LEN + '" placeholder="Choice text"></div>' +
                '</div>' + 
             "</div>";
    } else if (type === 'm') {
        if (POLL_MODE === true) {
            poll_light('m');
        }
        b += "<div id=\"q" + qid + "-col\">" +
                '<div class="formChoices" id="formChoices0">' +
                    '<div class="deleteChoiceDiv"><button class="deleteQuestion" type="button" onclick="del_choice(0)">&times;</button></div>' +
                    '<div class="formChoiceDiv"><input class="formTitle" style="width:100%;" type="text" name="question[' + qid + '][choices][] maxlength="' + MAX_C_LEN + '" placeholder="Choice text"></div>' +
                '</div>' +
             "</div>";
    } else if (type === 't') {
        if (POLL_MODE === true) {
            poll_light('t');
        }
        b += "<p>" +
             "<input class=\"formTitle\" style=\"width:100%;\" type=\"text\" name=\"question[" + qid + "][maxlen]\" maxlength=\"" + MAX_T_LEN + "\" placeholder=\"Max length (>512 is considered a paragraph, max is " + strrepeat("9", MAX_T_LEN) + ")\"/>" +
             "</p>";
    } else if (type === 'l') {
        if (POLL_MODE === true) {
            poll_light('l');
        }
        b +=    '<table>' +
                    '<tr>' +
                        '<td><input class="formTitle" style="width:100%;padding-bottom:12px;" type="text" name="question[' + qid + '][lowlab]" placeholder="Label (optional)" maxlength="' + MAX_C_LEN + '" /></td>' +
                        '<td><div class="droplist"><select name="question[' + qid + '][lowval]"><option value="0">0</option><option value="1" selected="selected">1</option></select></div></td>' +
                        '<td>to</td>' +
                        '<td><div class="droplist"><select name="question[' + qid + '][maxval]"><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>' + 
                        '<option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10" selected="selected">10</option></select></div></td>' +
                        '<td><input class="formTitle" style="width:100%;padding-bottom:12px;" type="text" name="question[' + qid + '][maxlab]" placeholder="Label (optional)" maxlength="' + MAX_C_LEN + '" /></td>' +
                    '</tr>' +
                '</table>';
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
