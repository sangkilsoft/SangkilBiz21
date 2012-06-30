/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * create by mujib masyhudi 10-01-2012
 */

var ldb = {}
ldb.db = {}

ldb.open = function(){
    var dbSize = 5 * 1024 * 1024; // 5MB
    ldb.db = openDatabase("DBTemp", "1.0", "Temp Item manager", dbSize);
}

ldb.createTable = function() {
    var db = ldb.db;
    db.transaction(function(tx) {
        tx.executeSql("CREATE TABLE IF NOT EXISTS items(ID INTEGER PRIMARY KEY ASC, cditem TEXT UNIQUE, lnitem INTEGER, dscrp TEXT, cduom TEXT, cdgroup TEXT, cdicat INTEGER, added_on DATETIME)", []);
    });
}

ldb.addItem = function(cditem, lnitem, dscrp, cduom, cdgroup, cdicat) {
    var db = ldb.db;
    db.transaction(function(tx){
        var addedOn = new Date();
        tx.executeSql("INSERT INTO items(cditem, lnitem, dscrp, cduom, cdgroup, cdicat, added_on) VALUES (?,?,?,?,?,?,?)",
            [cditem, lnitem, dscrp, cduom, cdgroup, cdicat, addedOn],
            ldb.onSuccess
//            ldb.onError,
        );
    });
}
    
ldb.onError = function(tx, e) {
    //alert("There has been an error: " + e.message);
}

ldb.onSuccess = function(tx, r) {
    ldb.getAllTodoItems(loadTodoItems);
}

ldb.getSelectedItem = function(renderFunc, cditem) {
    var db = ldb.db;
    db.transaction(function(tx) {
        tx.executeSql("SELECT * FROM todo WHERE cditem = "+cditem+"", [], renderFunc,
            ldb.onError);
    });
    
}

ldb.getAllTodoItems = function(renderFunc) {
    var db = ldb.db;
    db.transaction(function(tx) {
        tx.executeSql("SELECT ID, cditem, lnitem, dscrp, cduom, cdgroup, cdicat, added_on FROM items ORDER BY cditem, lnitem ASC", [], renderFunc,
            ldb.onError);
    });
}

ldb.deleteItems = function(ID) {
    var db = ldb.db;
    db.transaction(function(tx){
        tx.executeSql("DELETE FROM items WHERE ID=?", [ID],
            ldb.onSuccess,
            ldb.onError);
    });
}

function loadSelectedItems(tx, rs) {
    var rowOutput = "";
    var Items = document.getElementById("MasterItems");
    rowOutput += "<table border=1 width='100%'>";
    rowOutput += "<tr><td>Cd Item</td>\n\
                <td>Ln</td>\n\
                <td>Description</td>\n\
                <td>Uom</td>\n\
                <td>FG</td>\n\
                <td>Category</td>\n\
                <td width='20%'>Action</td>\n\
            </tr>";
    for (var i=0; i < rs.rows.length; i++) {
        rowOutput += renderTodo(rs.rows.item(i));
    }
    rowOutput += "</table>";
    Items.innerHTML = rowOutput;
}

function loadTodoItems(tx, rs) {
    var rowOutput = "";
    var Items = document.getElementById("MasterItems");
    rowOutput += "<table border=1 width='100%'>";
    rowOutput += "<tr style=\"background-color: #DDD;\"><td>Cd Item</td>\n\
                <td>Ln</td>\n\
                <td>Description</td>\n\
                <td>Uom</td>\n\
                <td>FG</td>\n\
                <td>Category</td>\n\
                <td width='20%'>Action</td>\n\
            </tr>";
    for (var i=0; i < rs.rows.length; i++) {
        rowOutput += renderTodo(rs.rows.item(i));
    }
    rowOutput += "</table>";
    Items.innerHTML = rowOutput;
}

function loadDataItems(tx, rs) {
    var dataitems = "[";
    for (var i=0; i < rs.rows.length; i++) {
        dataitems += "\'";
        dataitems += renderData(rs.rows.item(i));
        dataitems += "\', ";
    }
    dataitems += "]";
}

function renderData(row) {
    return ""+row.cditem+" | " + row.dscrp  + "";
}

function getDataItems() {
    ldb.open();
    ldb.getAllTodoItems(loadDataItems);
}

function renderTodo(row) {
    return "<tr><td>"+row.cditem+"</td>\n\
                <td>" + row.lnitem  + "</td>\n\
                <td>" + row.dscrp  + "</td>\n\
                <td>" + row.cduom  + "</td>\n\
                <td>" + row.cdgroup  + "</td>\n\
                <td>" + row.cdicat  + "</td>\n\
                <td width='20%'>\n\
                    <a href='javascript:void(0);' class='easyui-linkbutton' \n\
                    plain='false' id='dltBtn' \n\
                    onclick='ldb.deleteItems(" + row.ID +");'>Del</a>\n\
                </td>\n\
            </tr>";
}

function init() {
    ldb.open();
    ldb.createTable();
    ldb.getAllTodoItems(loadTodoItems);
}

function addItems(cditem, lnitem, dscrp, cduom, cdgroup, cdicat) {
    ldb.addItem(cditem, lnitem, dscrp, cduom, cdgroup, cdicat);
}

