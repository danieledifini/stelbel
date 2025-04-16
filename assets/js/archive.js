
let createParams = (array) => {
    let string = '';
  
    if(array.length == 0){
      string += "0";
    }
    else{
      array.forEach((item, index) => {
        
        if(index != 0){
          string += "_";
        }
  
        string += item;
      });
    }
  
    return string;
}
  
export let createStringParams = (...arrays) => {
    let params = "/";
  
    arrays.forEach((array, index) => {
        
      if(index != 0){
        params += "/";
      }
  
      params += createParams(array);
    });

    
  
    return params;
}

export let getCheckedFromFilter = (values, target) => {

    let checkeds = null;
    checkeds = document.getElementById(target).querySelectorAll("button.active");
  
    if(checkeds){
        
        checkeds.forEach(function (input, index) {
            values.push(parseInt(input.getAttribute("data-term")));
        });
    }
  
    return values;
}