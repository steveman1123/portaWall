
var isLeftDown = false;   // Tracks status of Lmouse button
var isRightDown = false;   // Tracks status of Rmouse button

//change the color of the selected light
function changeColor(obj) {
  var color = document.getElementById('colorText').value;  //get color from color input
  //if not formatted as above, set to black
  if(color.length<3)
    color = "000000";
  obj.style.backgroundColor = "#"+color;  //set background color as color
  obj.nextElementSibling.value = color;  //set input value as color
}

//set background color as black
function clearBack(obj) {
  obj.style.backgroundColor = "#000000";   //set input value as black
  obj.nextElementSibling.value = "000000";   //set input value as black
}



//returns the id of the object touched, if it starts with "light", change the color
function touchObj(e) {
  var touchobj = e.changedTouches[0]; // reference first touch point (ie: first finger)
  var curElem = document.elementFromPoint(touchobj.clientX, touchobj.clientY);
  $(curElem).on('touchmove', function() {
  e.preventDefault();});
  if(curElem.id.startsWith("light")) {
    changeColor(curElem);
  }
}



//set color input value to colorText value
function updateColor() {
  document.getElementById("color").value = "#"+document.getElementById("colorText").value;
}

//set colortext value to color input value
function updateColorText() {
  document.getElementById("colorText").value = document.getElementById("color").value.substring(1);
}


$(document).ready(function(){
  $(document)
    .mousedown(function(event) {
      //determine difference between L and R - case 2=middle mouse
      switch(event.which) {
        case 1:
          isLeftDown = true;  //Lmouse is down
          changeColor(this);
          break;
        case 3:
          isRightDown = true; //Rmouse is down
          clearBack(this);
          break;
      }
    })
    .mouseup(function() {
      isLeftDown = false;    // When mouse goes up, set isDown to false
      isRightDown = false;
    });

  $(".light")
    .mouseover(function(){
      if(isLeftDown) {
        changeColor(this);
      } else if(isRightDown) {
        clearBack(this);
      }
    })
    .mousedown(function(){
      //determine difference between L and R - case 2=middle mouse
      switch(event.which) {
        case 1:
          //isLeftDown = true;  //Lmouse is down
          changeColor(this);
          break;
        case 3:
          //isRightDown = true; //Rmouse is down
          clearBack(this);
          break;
      }
    });
  $(".light").dblclick(function() {clearBack(this);});
});



//for(var i=0;i<lightList.length;i++) {
//  lightList[i].addEventListener('dblclick', test, false);
//}


window.addEventListener('load', function(){

    var board = document.getElementById('board');
    board.addEventListener('touchstart', touchObj, false);
    board.addEventListener('touchmove', touchObj, false);
}, false);
