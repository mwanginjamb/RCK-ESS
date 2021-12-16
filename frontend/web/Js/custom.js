/**
 * Created by HP ELITEBOOK 840 G5 on 1/6/2021.
 */

function closeInput(elm) {
  var td = elm.parentNode;
  var value = elm.value;
  


  /** Handle Checkbox state */
  var child = td.children[0];

  if(child.type == 'checkbox'){
    value = (child.checked)? true: false;
  }

  /** Finish handling checkbox state */



  td.removeChild(elm);
  td.innerHTML = value;

  const data = td.dataset;
  // console.table(data);

  // Post Changes
  field = document.querySelector(`#${data.validate}`);
  $.post('./commit',{'key':data.key,'name': data.name, 'no': data.no,'filterKey': data.filterField,'service': data.service, 'value': value }).done(function(msg){
    //console.log(data.validate);

    if(data.validate)
    {
      const DataKey = data.validate;
      field.innerText = typeof(msg) === 'string'? msg : msg[data.name];
      console.log('Baby we getting here..');
      console.log(msg);
    }

  });
}

function addInput(elm,type = false, field = false ) {
  if (elm.getElementsByTagName('input').length > 0) return;

   

  var value = elm.innerHTML;
   elm.innerHTML = '';

  var input = document.createElement('input');
  if(type){
    input.setAttribute('type', type);
  }else{
    input.setAttribute('type', 'text');
  }

  input.setAttribute('value', value);

  if(type === 'checkbox')
  {
    input.checked = event.target.value;  
    
  }


  
  input.setAttribute('class','form-control');
  input.setAttribute('onBlur', 'closeInput(this)');
  elm.appendChild(input);
  input.focus();
}

async function addDropDown(elm,resource) {
  if (elm.getElementsByTagName('input').length > 0) return;

  var value = elm.innerHTML;
  elm.innerHTML = '';

  const ddContent = await getData(resource);

  console.table(ddContent);


  var select = document.createElement('select');
  const InitialOption = document.createElement('option');

  InitialOption.innerText = 'Select ...';
  select.appendChild(InitialOption);

  // Prepare the returned data and append it on the select

  for(const[key, value] of Object.entries(ddContent)){
        // console.log(`${key}: ${value}`);
        const option = document.createElement('option');
        option.value = key;
        option.text = value;

        select.appendChild(option);
  }

  select.setAttribute('class','form-control');
  select.setAttribute('onChange', 'closeInput(this)');
  elm.appendChild(select);
  select.focus();
}


async function getData(resource)
{
  const res = await fetch(`./${resource}`,{
  headers: new Headers({
    Origin: 'http://localhost:2026/'
  })
});
  const data = await res.json();

  return data;
}



// Function to do ajax field level updating

function globalFieldUpdate(entity,controller = false, fieldName, ev) {
  console.log('was invoked');
  const model = entity.toLowerCase();
  const field = fieldName.toLowerCase();
  const formField = '.field-'+model+'-'+fieldName.toLowerCase();
  const keyField ='#'+model+'-key'; 
  const targetField = '#'+model+'-'.field;
  const tget = '#'+model+'-'+field;

  
  const fieldValue = ev.target.value;
  const Key = $(keyField).val();

  console.log(`My Key is ${Key}`);
 
  // If controller is falsy use the model value (entity) as the route
  if(!controller) {
    controller = model;
  }

  if(Key.length){
      const url = $('input[name=absolute]').val()+controller+'/setfield?field='+fieldName;
      $.post(url,{ fieldValue:fieldValue,'Key': Key}).done(function(msg){
          
              // Populate relevant Fields
                                         
              $(keyField).val(msg.Key);
              $(targetField).val(msg[fieldName]);

             
              if((typeof msg) === 'string') { // A string is an error
                  console.log(formField);
                  const parent = document.querySelector(formField);
                  const helpbBlock = parent.children[2];
                  helpbBlock.innerText = msg;
                  
              }else{ // An object represents correct details

                  const parent = document.querySelector(formField);
                  const helpbBlock = parent.children[2];
                  helpbBlock.innerText = '';
                  
              }   
          },'json');
  }

}         
function disableSubmit(){
document.getElementById('submit').setAttribute("disabled", "true");
}

function enableSubmit(){
document.getElementById('submit').removeAttribute("disabled");

}





