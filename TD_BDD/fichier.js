//Grille Pain
var xGp = 0;
var totalGpPrice = 0;

var gpPlus = document.getElementById("addGp");
var gpMinus = document.getElementById("decGp");
var gpNumTxt = document.getElementById("numGp");
var gpPrice = document.getElementById("gpPrice");

const GpIncrement = () => {
    xGp++;
    GpPriceUpdate();
  };

  const GpDecrement = () => {
    xGp--;
    if(xGp < 0) xGp = 0;
    GpPriceUpdate();
  };

function GpPriceUpdate()
{
    gpNumTxt.value = xGp;
    totalGpPrice = (24.9 * xGp).toFixed(2);;
    gpPrice.value = totalGpPrice;
    Total();    
}

  gpPlus.addEventListener("click", GpIncrement);
  gpMinus.addEventListener("click", GpDecrement);

//Seche cheveux
var xSc = 0;
var totalScPrice = 0;

var ScPlus = document.getElementById("addSc");
var ScMinus = document.getElementById("decSc");
var ScNumTxt = document.getElementById("numSc");
var ScPrice = document.getElementById("sCPrice");

const ScIncrement = () => {
    xSc++;
    ScPriceUpdate();
  };

  const ScDecrement = () => {
    xSc--;
    if(xSc < 0) xSc = 0;
    ScPriceUpdate();
  };

  function ScPriceUpdate()
  {
      ScNumTxt.value = xSc;
      totalScPrice = (29.9 * xSc).toFixed(2);
      ScPrice.value = totalScPrice;
      Total();
  }

  ScPlus.addEventListener("click", ScIncrement);
  ScMinus.addEventListener("click", ScDecrement);

//Aspirateur voiture
var xAv = 0;
var totalAvPrice = 0;

var avPlus = document.getElementById("addAv");
var avMinus = document.getElementById("decAv");
var avNumTxt = document.getElementById("numAv");
var avPrice = document.getElementById("aVPrice");

const AvIncrement = () => {
    xAv++;
    AvPriceUpdate();
  };

  const AvDecrement = () => {
    xAv--;
    if(xSc < 0) xSc = 0;
    AvPriceUpdate();
  };

  function AvPriceUpdate()
  {
      avNumTxt.value = xAv;
      totalAvPrice = (19.9 * xAv).toFixed(2);;
      avPrice.value = totalAvPrice;
      Total();
  }

  avPlus.addEventListener("click", AvIncrement);
  avMinus.addEventListener("click", AvDecrement);


  //
  var totalprice;
  var totalPriceTxt = document.getElementById("totalPrice");

  function Total()
  {
      totalprice = (totalScPrice*1 + totalAvPrice*1 + totalGpPrice*1).toFixed(2);
      totalPriceTxt.value = totalprice;
  }