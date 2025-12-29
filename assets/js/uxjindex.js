function sendformk(name_div, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10,m11) {
			document.getElementById(`${name_div}`).innerHTML = '<div class="overlay text-center"><i class="fa fa-refresh fa-spin"></i></div>';
    var request
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest()
    } else {
        request = new ActiveXObject('Microsoft.XMLHTTP')
    }
    request.open("POST", "uxindex.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(`page=${m1}&mpost2=${m2}&mpost3=${m3}&mpost4=${m4}&mpost5=${m5}&mpost6=${m6}&mpost7=${m7}&mpost8=${m8}&mpost9=${m9}&mpost10=${m10}&mpost11=${m11}`); 
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            let responeData = request.responseText;
            document.getElementById(`${name_div}`).innerHTML = responeData; 
			
        }else{
 //alert("قطعی ارتباط شما با سرور");	
		}
    }
  //  request.send();
}


function sendformm(name_div, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10,m11) {
			document.getElementById(`${name_div}`).innerHTML = '<div class="overlay text-center"><i class="fa fa-refresh fa-spin"></i></div>';

    var request
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest()
    } else {
        request = new ActiveXObject('Microsoft.XMLHTTP')
    }
    request.open("POST", "uxindex.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(`page=${m1}&mpost2=${m2}&mpost3=${m3}&mpost4=${m4}&mpost5=${m5}&mpost6=${m6}&mpost7=${m7}&mpost8=${m8}&mpost9=${m9}&mpost10=${m10}&mpost11=${m11}`); 
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            let responeData = request.responseText;
            document.getElementById(`${name_div}`).innerHTML = responeData; 
			
        }else{
 //alert("قطعی ارتباط شما با سرور");	
		}
    }
  //  request.send();
}

function sendformb(name_div, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10,m11,m12,m13,m14,m15,m16,m17,m18,m19,m20) {
			document.getElementById(`${name_div}`).innerHTML = '<div class="overlay text-center"><i class="fa fa-refresh fa-spin"></i></div>';

    var request
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest()
    } else {
        request = new ActiveXObject('Microsoft.XMLHTTP')
    }
    request.open("POST", "uxindex.php");
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



function sendform30(name_div, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10,m11,m12,m13,m14,m15,m16,m17,m18,m19,m20,m21,m22,m23,m24,m25,m26,m27,m28,m29,m30) {
			document.getElementById(`${name_div}`).innerHTML = '<div class="overlay text-center"><i class="fa fa-refresh fa-spin"></i></div>';

    var request
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest()
    } else {
        request = new ActiveXObject('Microsoft.XMLHTTP')
    }
    request.open("POST", "uxindex.php");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.send(`page=${m1}&mpost2=${m2}&mpost3=${m3}&mpost4=${m4}&mpost5=${m5}&mpost6=${m6}&mpost7=${m7}&mpost8=${m8}&mpost9=${m9}&mpost10=${m10}&mpost11=${m11}&mpost12=${m12}&mpost13=${m13}&mpost14=${m14}&mpost15=${m15}&mpost16=${m16}&mpost17=${m17}&mpost18=${m18}&mpost19=${m19}&mpost20=${m20}&mpost21=${m21}&mpost22=${m22}&mpost23=${m23}&mpost24=${m24}&mpost25=${m25}&mpost26=${m26}&mpost27=${m27}&mpost28=${m28}&mpost29=${m29}&mpost30=${m30}`); 
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






function sendfilem(name_div, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10,m11,form_id, file_id){
	if(document.getElementById(file_id).value != ''){
			document.getElementById(`${name_div}`).innerHTML = '<div class="overlay text-center"><i class="fa fa-refresh fa-spin"></i>upload file </div>';
    var request
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest()
    } else {
        request = new ActiveXObject('Microsoft.XMLHTTP')
    }
	var form = document.getElementById(form_id);
	var fd = new FormData(form);
   request.open("POST", "uxindex.php");
   //request.setRequestHeader('Content-Type', 'multipart/form-data');
   fd.append('page',m1);
   fd.append('mpost2',m2);
   fd.append('mpost3',m3);
   fd.append('mpost4',m4);
   fd.append('mpost5',m5);
   fd.append('mpost6',m6);
   fd.append('mpost7',m7);
   fd.append('mpost8',m8);
   fd.append('mpost9',m9);
   fd.append('mpost10',m10);
   fd.append('mpost11',m11);
   request.send(fd); 
   request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            let responeData = request.responseText;
            document.getElementById(`${name_div}`).innerHTML = responeData; 
			
        }else{
 //alert("قطعی ارتباط شما با سرور");	
		}
    }
   // request.send();
}else{
    alert("یک فایل مناسب انتخاب نمایید");	
}
}





function send1file(name_div,m1,m2,m3,form_id, file_id){
	if(document.getElementById(file_id).value !== ''){
			document.getElementById(`${name_div}`).innerHTML = '<div class="overlay text-center"><i class="fa fa-refresh fa-spin"></i></div>';
    var request
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest()
    } else {
        request = new ActiveXObject('Microsoft.XMLHTTP')
    }
	var form = document.getElementById(form_id);
	var fd = new FormData(form);
    request.open("POST", "uxindex.php");
    request.setRequestHeader('Content-Type', 'multipart/form-data');
	fd.append('page',m1);
	fd.append('mpost2',m2);
    fd.append('mpost3',m3);
    request.send(fd); 
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            let responeData = request.responseText;
            document.getElementById(`${name_div}`).innerHTML = responeData; 
			
        }else{
 //alert("قطعی ارتباط شما با سرور");	
		}
    }
    request.send();
}else{
    alert("فایل مناسب انتخاب کنید ");	
}
}

function setfild(meghdar,idfild){
document.getElementById(`${idfild}`).value=meghdar;	
}

function printdiv(printpage) {
            var headstr = "<html><head><title></title></head><body>";
            var footstr = "</body>";
            var newstr = document.all.item(printpage).innerHTML;
            var oldstr = document.body.innerHTML;
            document.body.innerHTML = headstr + newstr + footstr;
            window.print();
            document.body.innerHTML = oldstr;
            return true;
        }