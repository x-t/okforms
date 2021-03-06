// ! Ugly code ahead, was used for generation of human-readable indents.

/*
 * download(link, content)
 * 
 * Generate a download with content
 * 
 * @param str name The filename
 * @param str content The file's contents
 */
function download(name, content) {
    var el = document.createElement('a');
    el.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(content));
    el.setAttribute('download', name);
    el.style.display = 'none';
    document.body.appendChild(el);
    el.click();
    document.body.removeChild(el);
}

/*
 * export_json(id)
 * 
 * Exports answers as JSON
 * 
 * @param str id Form ID
 */
function export_json(id) {
    var t = document.getElementsByTagName("table")[0];
    var tel = t.getElementsByTagName("tr");
    var h = tel[0].getElementsByTagName("th");
    var buf = '{\n';

    buf += '    "id": "' + id + '",\n';
    buf += '    "ans": [\n';
    for (var index = 1; index < tel.length; index++) {
    buf += '        {\n';
        var x = tel[index].getElementsByTagName("td");
    buf += '            "person": "' + x[0].innerHTML.substr(1) + '",\n';
    buf += '            "time": ' + x[1].getAttribute("data-unix") + ',\n';
    buf += '            "prettytime": "' + x[1].innerHTML + '",\n';
    buf += '            "question": [\n';
        for (var index1 = 2; index1 < x.length; index1++) {
            var element = x[index1];
    buf += '                {\n';
    buf += '                    "q": "' + h[index1].innerHTML + '",\n';
    buf += '                    "a": "' + element.innerHTML + '"\n';
            if (index1 == x.length - 1)
    buf += '                }\n';
            else
    buf += '                },\n';
        }
    buf += '            ]\n';    

        if (index == tel.length - 1)
    buf += '        }\n';
        else
    buf += '        },\n';
    }
    buf += '    ]\n';
    buf += '}\n';

    download("formData.json", buf);
}

/*
 * export_xml(id)
 * 
 * Exports answers as XML
 * 
 * @param str id Form ID 
 */
function export_xml(id) {
    var t = document.getElementsByTagName("table")[0];
    var tel = t.getElementsByTagName("tr");
    var h = tel[0].getElementsByTagName("th");
    var buf = '<?xml version="1.0" encoding="UTF-8"?>\n';

    buf += '<form>\n';
    buf += '    <id>' + id + '</id>\n';
    for (var index = 1; index < tel.length; index++) {
    buf += '    <ans>\n';
        var x = tel[index].getElementsByTagName("td");
    buf += '        <person>' + x[0].innerHTML.substr(1) + '</person>\n';
    buf += '        <time>' + x[1].getAttribute("data-unix") + '</time>\n';
    buf += '        <prettytime>' + x[1].innerHTML + '</prettytime>\n';
        for (var index1 = 2; index1 < x.length; index1++) {
            var element = x[index1];
    buf += '        <question>\n';
    buf += '            <q>' + h[index1].innerHTML + '</q>\n';
    buf += '            <a>' + element.innerHTML + '</a>\n';
    buf += '        </question>\n';
        }
    buf += '    </ans>\n';
    }
    buf += '</form>\n';

    download("formData.xml", buf);
}