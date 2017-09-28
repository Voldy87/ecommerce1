function printHtmlCouriers (courArr) {
    var div = document.getElementById('divList');
    var len = courArr.length;
    if (len==0) {
        div.innerHTML ="<div id='zeroCour'>No courier service match these filter parameters!</div>";
        return;
    }
    var htmlList="";
    for (let i=0; i<len; i++ ){
            htmlList+=('<div class="courDiv"><i>'+ courArr[i].name + '</i>');
            htmlList+=('<ul><li>Countries reached: <span>'+ courArr[i].place + '</span></li>');
            htmlList+=('<li>Price: <b>'+ courArr[i].price + '</b></li>');
            htmlList+=('</ul></div>');
    }
    div.innerHTML = htmlList;   
}

function updateFilter(){
    var filObj={}; 
    filObj.text = document.getElementById('txt').value;
    filObj.maxPrice = parseInt(document.getElementById('int').value.trim());
    if (isNaN(filObj.maxPrice))
        filObj.maxPrice=0;
    filObj.orderAlpha = document.getElementById('dir').checked;
    filObj.orderReverseAlpha = document.getElementById('rev').checked;
    return filObj;
}

function txtFilter(arr, string) {
  return arr.filter(function(el) {
      return el.place.toLowerCase().indexOf(string.trim()) > -1;
  })
}

function maxFilter(arr, num) {
  return arr.filter(function(el) {
      return el.price <= num;
  })
}

function compareNames (a,b){ 
    if (a.name > b.name)
        return 1;
    if (a.name < b.name)
        return -1;
    return 0;
}

function courierFilter (courArrBase, filterObj){  
    var courArr = courArrBase.slice();
    if ( (filterObj.hasOwnProperty('text'))&&(filterObj.text.length>0) ){
        var filterStr = filterObj.text.toLowerCase();
        courArr = txtFilter(courArr,filterStr).slice();  
    }  console.log(courArr);
    if ( (filterObj.hasOwnProperty('maxPrice')) && (filterObj.maxPrice>0) ){
        var minVal = filterObj.maxPrice;
        courArr = maxFilter(courArr, minVal).slice();
    }
    if ( filterObj.hasOwnProperty('orderAlpha') && filterObj.orderAlpha ){ 
        let arr = courArr.sort(compareNames);
        courArr = arr.slice(); console.log(courArr);
    }
    if ( filterObj.hasOwnProperty('orderReverseAlpha') && filterObj.orderReverseAlpha ){
        let arr = courArr.sort(compareNames);
        arr.reverse();
        courArr = arr.slice(); console.log(courArr);
    } 
    return courArr;    
}

function changeHandler() {
    printHtmlCouriers( courierFilter( utilsObj.international_couriers,updateFilter() ) );
}

function resetHandler() {
    printHtmlCouriers( courierFilter( utilsObj.international_couriers,{} ) );
}

function bodyLoad(){
    printHtmlCouriers( courierFilter (utilsObj.international_couriers, updateFilter() ) );
}
