var vert = 255;
var bleu = 0;
var rouge = 255;
var result = document.getElementById("Square");

//#region Catalog Color Presets
var presetChoice = document.getElementById("presetChoice");
presetChoice.onchange = function () {
    catalogColorSet();
};

function catalogColorSet() {
    switch (presetChoice.options[presetChoice.selectedIndex].value) {
        case "red": rouge = 255; vert = 0; bleu = 0; break;
        case "yellow": rouge = 255; vert = 255; bleu = 0; break;
        case "green": rouge = 0; vert = 255; bleu = 0; break;
        case "blue": rouge = 0; vert = 0; bleu = 255; break;
    }

    calcul();
}
//#endregion

//#region SlidersDeclaration
var sliderRouge = document.getElementById("rangeRouge");
var valRouge = document.getElementById("valeurRouge");
sliderRouge.onclick = function () {
    GetSlidersColorsValue();
};

var sliderBleu = document.getElementById("rangeBleu");
var valbleu = document.getElementById("valeurBleu");
sliderBleu.onclick = function () {
    GetSlidersColorsValue();
};

var sliderVert = document.getElementById("rangeVert");
var valVert = document.getElementById("valeurVert");
sliderVert.onclick = function () {
    GetSlidersColorsValue();
};
//#endregion

//#region Disable/Enable zones & Colors
var action = true;
var radioCatalog = document.getElementById("radioCatalog");
var radioPersonnel = document.getElementById("radioPersonnel");
radioCatalog.onchange = function () {
    action = true;
    catalogColorSet();
    ActivateDesactivateZones();
}
radioPersonnel.onchange = function () {
    action = false;
    ActivateDesactivateZones();
}

function ActivateDesactivateZones() {
    sliderBleu.disabled = action;
    sliderRouge.disabled = action;
    sliderVert.disabled = action;
    presetChoice.disabled = !action;
}
//#endregion

//Start
calcul();
ActivateDesactivateZones();

//#region Color Maths
function GetSlidersColorsValue() {
    rouge = sliderRouge.value;
    bleu = sliderBleu.value;
    vert = sliderVert.value;
    calcul();
}

function calcul() {

    valVert.value = vert;
    valbleu.value = bleu;
    valRouge.value = rouge;

    sliderRouge.value = rouge;
    sliderVert.value = vert;
    sliderBleu.value = bleu;

    result.style.backgroundColor = "rgba(" + rouge + "," + vert + "," + bleu + "," + 255 + ")";
    //#endregion
};