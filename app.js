const colours = ["red", "green", "rgba(133,122,200)", "#f15025" ];
const btn = document.getElementById("btn");
const colour = document.querySelector(".color");
btn.addEventListener("click",function(){
   //console.log(document.body);
   const randomNumber = getRandomNumber();
  // console.log(randomNumber);
   document.body.style.backgroundColor = colours[randomNumber];
   colour.textContent = colours[randomNumber];
});
function getRandomNumber(){
   return Math.floor(Math.random() * colours.length);
}