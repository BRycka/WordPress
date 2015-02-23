/**
 * Created by Ricardas on 20/02/15.
 */
function mypoll_add_field() {
    var table = document.getElementById("newPollTable");
    var row = table.insertRow(-1);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var hidden = document.getElementById("hidden");
    var value = hidden.value++;
    var input = document.createElement("input");
    input.type = "text";
    input.name = "mypoll_answer"+(value + 1);
    cell1.innerHTML = "Answer #" + (value + 1) + " :";
    cell2.appendChild(input);
}

function mypoll_add_field_to_edit() {
    var table = document.getElementById("editPollTable");
    var row = table.insertRow(-1);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var hidden = document.getElementById("hidden");
    var value = hidden.value++;
    var input = document.createElement("input");
    input.type = "text";
    input.name = "mypoll_answer"+(value + 1);
    cell2.appendChild(input);
}
