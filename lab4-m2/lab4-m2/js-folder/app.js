$(document).ready(function () {

  // give action to the modal for update to show data on show

  $('#addModal2').on('shown.bs.modal', function (e) {
    alert("hello");
    var element = $(e.relatedTarget);
    $(this).find("[name='id']").val(element.data("id"));
    $(this).find("[name='username1']").val(element.data("username"));
    $(this).find("[name='password1']").val(element.data("password"));
    $(this).find("[name='cpassword1']").val(element.data("cpassword"));
    $(this).find("[name='email1']").val(element.data("email"));
    $(this).find("[name='avatar1']").val(element.data("avatar"));

  });






  var username = $("input[name ='username']");
  var password = $("input[name ='password']");
  var cpassword = $("input[name ='cpassword']");
  var email = $("input[name ='email']");

  $("#form_register").submit(function (event) {


    isValidate1 = true;
    var formValue = "";

    //username validation
    if ($(username).val().length < 5 && $(username).val().length != 0) {
      $(username).next().text("your username could not be less than 5 chars!");
      $(username).next().show("slow");

      isValidate1 = false;
    } else if ($(username).val().length == 0) {
      $(username).next().text("your user name could not be empty!");
      $(username).next().show("slow");

      isValidate1 = false;
    }



    //password validation
    if ($(password).val().length < 6 && $(password).val().length != 0) {
      $(password).next().text("your password could not be less than 6 chars!");
      $(password).next().show("slow");

      isValidate1 = false;
    } else if ($(password).val().length == 0) {
      $(password).next().text("your password could not be empty!");
      $(password).next().show("slow");

      isValidate1 = false;
    }

    //cpassword validation
    if ($(cpassword).val().length < 6 && $(cpassword).val().length != 0) {
      $(cpassword).next().text("your password could not be less than 6 chars!");
      $(cpassword).next().show("slow");

      isValidate1 = false;
    } else if ($(cpassword).val().length == 0) {
      $(cpassword).next().text("your password could not be empty!");
      $(cpassword).next().show("slow");

      isValidate1 = false;
    }


    //check for email

    if ($(email).val().length == 0) {
      $(email).next().text("your email could not be empty!");
      $(email).next().show("slow");

      isValidate1 = false;
    }


    // check if avatar is chosen
    var fileName = $("#avatar").val();
    if (!fileName) {
      $("#avatar").next().text("avatar should be chosen.");
      $("#avatar").next().show("slow");

      isValidate1 = false;
    }


    // ///////if there is no error/////
    if (isValidate1) {
      formValue += "Form submitted" + "\n";
      formValue += " " + "\n";

      formValue += "username: " + $(username).val() + "\n";
      isValidate1 = confirm(
        formValue + "\n are you sure you want to submit this form? \n"
      );


      //     //   }
    }

    return isValidate1;



  });




});