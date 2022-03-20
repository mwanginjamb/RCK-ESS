/**
 * Created by HP ELITEBOOK 840 G5 on 1/6/2021.
 * Written with love by @francnjamb -- Twitter
 */

//Initialize Sweet Alert

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

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

  console.log(`The Data Set.........................................................`);
  console.table(data);
 
  // Post Changes
  field = document.querySelector(`#${data.validate}`);
  $.post('./commit',{'key':data.key,'name': data.name, 'no': data.no,'filterKey': data.filterField,'service': data.service, 'value': value }).done(function(msg){
    
     // Update all key dataset values for the row
     if(msg.Key)
     {
      let parent = td.parentNode;
      parent.querySelectorAll('[data-key]').forEach(elem => elem.dataset.key = msg.Key);
     }
   


    console.log(`Committing Data.... Result`);
    console.table(msg);


    if(data.validate) // Custom Grid Error Reporting
    {
      let parent = td.parentNode;
      console.log(`Parent to be validated ....`);
      let ClassName =  data.validate;
      let validatedElement = parent.querySelector('.'+ClassName);
      const DataKey = data.validate;
      validatedElement.innerText = typeof(msg) === 'string'? msg : msg[DataKey];
    }

   
    // Toasting The Outcome
    typemsg = typeof msg;
    console.log(typemsg);
    console.log('');
    if(typeof(msg) === 'string')
    {
      console.log(msg);
       // Fire a sweet alert if you can
          Toast.fire({
            type: 'error',
            title: msg
          })
    }else{

      console.log(msg);
          Toast.fire({
            type: 'success',
            title: msg[data.name]+' Saved Successfully.'
          })
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

  input.setAttribute('placeholder', value);// use placeholder instead of value attribute

  if(type === 'checkbox')
  {
    input.checked = event.target.value;  
    
  }


  
  input.setAttribute('class','form-control');
  input.setAttribute('onBlur', 'closeInput(this)');
  elm.appendChild(input);
  input.focus();
}

// Get Drop Down Filters

function extractFilters(elm,ClassName)
{
  let parent = elm.parentNode;
  let filterValue = parent.querySelector('.'+ClassName).innerText;
  console.log(`Subject Parent Value`);
  console.log(filterValue);
  return filterValue;
}

async function addDropDown(elm,resource,filters={}) {
  if (elm.getElementsByTagName('input').length > 0) return;

 
  let processedFilters = null;
  if(Object.entries(filters).length)
  {
    const content = Object.entries(filters);
    processedFilters = Object.assign(...content.map(([key, val]) => ({[key]: extractFilters(elm,val)})));

    console.log('Extracted Filters.....................');
    console.log(processedFilters); 
    console.log(typeof processedFilters);  
  }
  

  elm.innerHTML = 'Processing ......';

  const ddContent = await getData(resource,processedFilters);
  console.log(`DD Content:`);
  console.log(ddContent);
  elm.innerHTML = '';

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


async function getData(resource, filters)
{
  payload = JSON.stringify({... filters});
  const res = await fetch(`./${resource}`,{
    method: 'POST',
    headers: new Headers({
      Origin: 'http://localhost:2026/',
      "Content-Type": 'application/json',
      //'Content-Type': 'application/x-www-form-urlencoded'
    }),
    body: payload
  });
  const data = await res.json();
  return data;
}


function JquerifyField(model, fieldName) {
  field = fieldName.toLowerCase();
  const selector =   '#'+model+'-'+field;
  return selector;
}

// Function to do ajax field level updating

function globalFieldUpdate(entity,controller = false, fieldName, ev, autoPopulateFields = []) {
  console.log('Global Field Update was invoked');
  const model = entity.toLowerCase();
  const field = fieldName.toLowerCase();
  const formField = '.field-'+model+'-'+fieldName.toLowerCase();
  const keyField ='#'+model+'-key'; 
  const targetField = '#'+model+'-'+field;
  const tget = '#'+model+'-'+field;

  console.log(targetField);
  const fieldValue = ev.target.value;
  const Key = $(keyField).val();

  console.log(`My Key is ${Key}`);
  console.log(autoPopulateFields);
 
  let route = '';
  // If controller is falsy use the model value (entity) as the route
  if(!controller) {
    route = model;
  }else {
    route = controller;
  }

  console.log(`route to use is : ${route}`);
  

  if(Key.length){
      const url = $('input[name=absolute]').val()+route+'/setfield?field='+fieldName;
      $.post(url,{ fieldValue:fieldValue,'Key': Key}).done(function(msg){
          
              // Populate relevant Fields
                                         
              $(keyField).val(msg.Key);
              $(targetField).val(msg[fieldName]);

              // Update DOM values for fields specified in autoPopulatedFields: fields in this array should be exact case and name as specified in Nav Web Service 

              if(autoPopulateFields.length > 0) {
                autoPopulateFields.forEach((field) => {
                  let domSelector = JquerifyField(model,field);

                  console.log(`Field of Corncern is ${field}`);
                  console.log(`Model of Corncern is ${model}`);
                  console.log(`Jquerified field is ${domSelector}`);
                  $(domSelector).val(msg[field]);
                });
              }

             /*******End Field auto Population  */
              if((typeof msg) === 'string') { // A string is an error
                  console.log(`Form Field is: ${formField}`);
                  const parent = document.querySelector(formField);

                  // Update Request Status from Server
                  requestStateUpdater(parent,'error', msg);

                  // Fire a sweet alert if you can

                  Toast.fire({
                    type: 'error',
                    title: msg
                  })
                  
              }else{ // An object represents correct details

                  const parent = document.querySelector(formField);
                 
                  // Update Request Status from Server
                  requestStateUpdater(parent,'success');

                  // If you can Fire a sweet alert                  

                  Toast.fire({
                    type: 'success',
                    title: field+' Saved Successfully.'
                  })
                  
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

function requestStateUpdater(fieldParentNode, notificationType, msg = '' ) {
  let inputParentNode = fieldParentNode.children[1]; // This is in boostrap 5

  if(notificationType === 'success' ){
    let successElement = document.createElement('span');
    successElement.innerText = 'Data Saved Successfully.';
    successElement.setAttribute('class', 'text-success small');
    inputParentNode.append(successElement);

    // clean up the notification elements after 3 seconds
    setTimeout(() => {
      successElement.remove();
    }, 3000);

  } else if(notificationType === 'error' && msg){

    let errorElement = document.createElement('span');
    errorElement.innerText = `Message: ${msg}`;
    errorElement.setAttribute('class', 'text-danger small');
    inputParentNode.append(errorElement);

    // clean up the notification elements after 3 seconds
    setTimeout(() => {
      errorElement.remove();
      location.reload(true);
    }, 7000);

  }
  

}

// Global Uploader

async function globalUpload(attachmentService, entity, fieldName, documentService = false) {
 
 const model = entity.toLowerCase(); 
  const key = document.querySelector(`#${model}-key`).value;
  const fileInput = document.querySelector(`#${model}-${fieldName}`);
  let endPoint = './upload/';
  const navPayload = {
    "Key" : key,
    "Service": attachmentService,
    "documentService": documentService
  }

  const formData = new FormData();
  formData.append("attachment", fileInput.files[0]);
  formData.append("Key", key);
  formData.append("DocumentService", documentService);


  console.log(fileInput.files);


  try{
    const Request = await fetch(endPoint,
      {
        method: "POST",
        body: formData,
        headers: new Headers({
          Origin: 'http://localhost:2026/'
        })
      });

    const Response = await Request.json();
    console.log(`File Upload Request`);
    console.log(Response);

    let filePath = Response.filePath;



    // Do a Nav Request
   endPoint = `${endPoint}?Key=${navPayload.Key}&Service=${navPayload.Service}&filePath=${filePath}&documentService=${navPayload.documentService}`
    const navReq = await  fetch(endPoint,{
      method: "GET",
      headers: new Headers({
        Origin: 'http://localhost:80/'
      })
    });

    const NavResp = await navReq.json();
    console.log(`Nav Request Response`);
    console.log(NavResp);
    console.info(navReq.ok);
    if(navReq.ok)
    {
      Toast.fire({
        type: 'success',
        title: 'Attachment uploaded Successfully.'
      });
    }else {
      Toast.fire({
        type: 'error',
        title: 'Attachment could not be uploaded.'
      })
    }


  }catch(error)
  {
    console.log(error);
  }
}




