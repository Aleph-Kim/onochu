const logo = document.getElementById("logo");
const searchFormWrap = document.getElementById("searchFormWrap");
const searchFormHideBtn = document.getElementById("searchFormHideBtn");
const searchForm = document.getElementById("searchForm");
const rightBtnBox = document.getElementById("rightBtnBox");

function showSearchForm(){
    logo.style.display = "none";
    rightBtnBox.style.display = "none";
    searchForm.style.display = "block";
    searchFormHideBtn.style.display = "block";
    searchFormWrap.style.width = "100%";

}

function hiddenSearchForm(){
    logo.style.display = "flex";
    rightBtnBox.style.display = "flex";
    searchForm.style.display = "none";
    searchFormHideBtn.style.display = "none";
    searchFormWrap.style.width = "auto";
}