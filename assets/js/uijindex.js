function openpage(name_div, m1, m2, m3, m4, m5) {
			document.getElementById(`${name_div}`).innerHTML = '<div class="spinner"></div>';

    var request
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest()
    } else {
        request = new ActiveXObject('Microsoft.XMLHTTP')
    }
   
    request.open("POST", "uiindex.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(`page=${m1}&mpost2=${m2}&mpost3=${m3}&mpost4=${m4}&mpost5=${m5}`); 
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            let responeData = request.responseText;
      //     $( "#jshapage" ).load( "js_page/for_table.txt" );
            document.getElementById(`${name_div}`).innerHTML = responeData; 

			
        }else{
 //alert("قطعی ارتباط شما با سرور");	
		}
    }
   // request.send();
   
}


function jsload(name_js) {
   $( "#jshapage" ).load( "js_page/for_table.txt" );
   //  $( "#jshapage" ).load( "js_page/${name_js}.txt" );
}

function checkonline() {
var request;
var sh_time;
if (window.XMLHttpRequest) {
request = new XMLHttpRequest()
} else {
request = new ActiveXObject('Microsoft.XMLHTTP')
}
request.open("POST", "okonline.txt");
request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");


request.send(); 
request.onreadystatechange = function () {
if ( request.status === 200) {
    document.getElementById(`okonline`).innerHTML = "ONLINE";

 //   
}else{
    document.getElementById(`okonline`).innerHTML = "DISCONNECT"; 
}

}
}


function search_modal(name_div, m1, m2, m3, m4) {
			document.getElementById(`${name_div}`).innerHTML = '<div class="overlay text-center"><i class="fa fa-refresh fa-spin"></i>   پردازش   </div>';

    var request
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest()
    } else {
        request = new ActiveXObject('Microsoft.XMLHTTP')
    }
    request.open("POST", "uimodal.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(`pfg=${m1}&mpost2=${m2}&mpost3=${m3}&mpost4=${m4}`); 
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            let responeData = request.responseText;
            document.getElementById(`${name_div}`).innerHTML = responeData; 
			
        }else{
 //alert("قطعی ارتباط شما با سرور");	
		}
    }
   // request.send();
}

function openmodal(name_div, m1, m2, m3, m4, m5) {
			document.getElementById(`${name_div}`).innerHTML = '<div class="overlay text-center"><i class="fa fa-refresh fa-spin"></i>   پردازش   </div>';

    var request
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest()
    } else {
        request = new ActiveXObject('Microsoft.XMLHTTP')
    }
    request.open("POST", "uimodal.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(`pfg=${m1}&mpost2=${m2}&mpost3=${m3}&mpost4=${m4}&mpost5=${m5}`); 
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            let responeData = request.responseText;
            document.getElementById(`${name_div}`).innerHTML = responeData; 
			
        }else{
 //alert("قطعی ارتباط شما با سرور");	
		}
    }
   // request.send();
}


function opengozaresh(name_div, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10 ,m11, m12, m13, m14, m15, m16, m17, m18, m19, m20) {
			document.getElementById(`${name_div}`).innerHTML = '<div class="overlay text-center"><i class="fa fa-refresh fa-spin"></i>   پردازش   </div>';
    var request
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest()
    } else {
        request = new ActiveXObject('Microsoft.XMLHTTP')
    }
    request.open("POST", "uiindex.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(`page=${m1}&mpost2=${m2}&mpost3=${m3}&mpost4=${m4}&mpost5=${m5}&mpost6=${m6}&mpost7=${m7}&mpost8=${m8}&mpost9=${m9}&mpost10=${m10}&mpost11=${m11}&mpost12=${m12}&mpost13=${m13}&mpost14=${m14}&mpost15=${m15}&mpost16=${m16}&mpost17=${m17}&mpost18=${m18}&mpost19=${m19}&mpost20=${m20}`); 
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            let responeData = request.responseText;
            document.getElementById(`${name_div}`).innerHTML = responeData; 
			
        }else{
 //alert("قطعی ارتباط شما با سرور");	
		}
    }
   // request.send();
}



        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
        }
		
