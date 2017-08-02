window.onload = function() 
{
    var myForm = document.getElementById("myForm");
    var submitButton = document.getElementById("myForm").submitButton;

 
 alert("myForm"sasa);
    myForm.akceptacjaRegulaminu[0].onclick = function()
    {
        submitButton.removeAttr('disabled');
    };
};