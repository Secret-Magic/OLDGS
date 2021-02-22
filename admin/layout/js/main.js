/* jslint plusplus:true , evil:true */
/* global console, alert, prompt */
/* eslint no-undef: "error"*/

function exitInptScrn() {
   "use strict";

   document.getElementById('row').style.display = 'none';
   document.getElementById('dvTbl').style.opacity = 1;
   document.getElementById('dvTbl').style.pointerEvents = "visible";
   //document.getElementById('dvTbl').style.display = "block";
}

function showInptScrn() {
   "use strict";

   document.getElementById('row').style.display = "inline-block";
   document.getElementById('dvTbl').style.opacity = 0.75;
   document.getElementById('dvTbl').style.pointerEvents = "none";
   if (document.getElementById('iId').value === "0") {
      document.getElementById('btnDlt').style.display = "none";
   } else {
      document.getElementById('btnDlt').style.display = "inline";
   }
}

function isVldInpt(inptx, msg) {
   "use strict";
   
   var x = document.getElementById(inptx);
   var f = document.getElementById('myfooter');
   if (x.value === '') {
      f.innerHTML = msg;
      x.setAttribute('placeholder', msg);
      return false;
   }
   else {
      return true;
   }
}
/*
document.forms[1].onsubmit = function (e) {
   "use strict";
   if (FLASE) {
      e.preventDefault () ;
   } else {
      return True
   }
};
*/

function myFiltr() {
   var input, filter, table, tr, td, i, txtValue;
   input = document.getElementById("srch");
   filter = input.value.toUpperCase();
   table = document.getElementById("mTbl");
   tr = table.getElementsByTagName("tr");
   for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0];
      if (td) {
         txtValue = td.textContent || td.innerText;
         if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
         } 
         else {
            tr[i].style.display = "none";
         }
      }       
   }
}
// function validatenum(obj,str) {
//   a=obj.value;
//   if(isNaN(a)){
//     alert(str);
//     obj.value="";
//     obj.focus();
//     return false;
//   }
// }
//---------------------------------------------------------------------------
var nav01 = document.querySelectorAll('nav ul li a') ;
nav01.forEach(element => {
   element.onclick = function () {
      document.getElementsByClassName('wrapper')[0].classList.toggle('hiddenCol');
   }
}); 

var btn01 = document.getElementById("btnClose");
if (btn01 != null) {
   btn01.onclick = function () {
      exitInptScrn();
   }
}

/*
<button onclick="document.location='default.asp'">HTML Tutorial</button>
<button onclick="window.location.href='default.asp'">HTML Tutorial</button>
*/

