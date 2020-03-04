function onload(){    
    var xhttp = new XMLHttpRequest;
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {    
            var sqlDataString = this.responseText;
            var sqlData = JSON.parse(sqlDataString);
            for (j=0; j<sqlData.length; j++){
                //create table with ranking numbers from 1 to ...
                li_rk = document.createElement("li");
                tx_rk = document.createTextNode(j+1);
                li_rk.appendChild(tx_rk);
                el_rk = document.getElementById("ranking");
                el_rk.appendChild(li_rk);
                //create table for sorted fruits 
                li_st = document.createElement("li");
                li_st.setAttribute("id", sqlData[j].fruitId);
                li_st.setAttribute("class", "sortedListItem");
                el_st = document.getElementById("sortable");
                el_st.appendChild(li_st);
            }  
                //fill the table with sorted fruits
                //according to their rank in database
            for (k=0; k<sqlData.length; k++){
                li_st_all = document.getElementsByClassName("sortedListItem");
                li_fruitRank = li_st_all[sqlData[k].fruitRank-1];
                tx_fruitName = document.createTextNode(sqlData[k].fruitName);
                li_fruitRank.appendChild(tx_fruitName);
            }
        }
    };
    data = "sqlData";
    xhttp.open("GET", "http://127.0.0.1/jQuery_UI_drag-and-drop/SQL_getDataFromDatabase.php?data=" + data, true);
    xhttp.send();
}


$(window).load(function(){         
    (function($) {
        $('#sortable').sortable({
            stop: function(e, ui) {
                data = [];
                $.map($(this).find('li'), function(el) {
                    var fruit = {
                        fruitId:$(el).attr('id'),
                        fruitRank: $(el).index()+1,
                        fruitName: $(el).html()
                     };
                     data.push(fruit);
                });  
                    var xhttp = new XMLHttpRequest;
                    dataString = JSON.stringify(data);
                    xhttp.open("POST", "http://127.0.0.1/jQuery_UI_drag-and-drop/SQL_postDataToDatabase.php", true);
                    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhttp.send("data=" + dataString);
                    dv = document.getElementById("log");
                    dv.innerHTML = "(AJAX script) client sent to server: <br><br>" + dataString;
            }
        });
    })(jQuery);
});

