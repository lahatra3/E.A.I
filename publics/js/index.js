$(document).ready(function() {
    // ****************** AUTHENTIFICATION **********************
    $('#connexion').click(function(event) {
        event.preventDefault();
        if($('#identifiant').val().trim().length!==0 && $('#keyword').val().trim().length!==0) {
            $.post('./api-EAI/api.php?demande=login/api', {
                identifiant: $('#identifiant').val(),
                keyword: $('#keyword').val()
            }, function(data, status) {
                if(status === "success" && data.true === 1) {
                    window.location.href="./views/eai.html";
                }
                else {
                    $('#erreur').html('Identifiant et/ou mot de passe incorrect !');    
                }
            });
        }
    });

    // *********************** INSCRIPTION ***************************
    $('#enregistrerInscription').click(function(event) {
        event.preventDefault();
        if($('#nom').val().trim().length!==0 && $('#prenoms').val().trim().length!==0 &&
            $('#prenom_usuel').val().trim().length!==0 && $('#email').val().trim().length!==0 &&
            $('#es').val().trim().length!==0 && $('#filiere').val().trim().length!==0 &&
            $('#promotion').val().trim().length!==0 && $('#password').val().trim().length!==0) {

            $.post('./api-EAI/api.php?demande=add/etudiants', {
                nom: $('#nom').val(),
                prenoms: $('#prenoms').val(),
                prenom_usuel: $('#prenom_usuel').val(),
                email: $('#email').val(),
                promotion: $('#promotion').val(),
                es: $('#es').val(),
                filiere: $('#filiere').val(),
                password: $('#password').val()
            }, function(data, status) {
                if(status === "success" && data === "1") {
                    $.post('./api-EAI/api.php?demande=login/api', {
                        identifiant: $('#email').val(),
                        keyword: $('#password').val()
                    }, function(data, status) {
                        if(status === "success" && data.true === 1) {
                            window.location.href="./views/eai.html";
                        }
                        else {
                            console.log(data);    
                        }
                    }); 
                }
                else {
                    $('#erreurInscription').html('Cet email ou ce prenom usuel existe déjà. Merci !')
                    console.log(status+"\n"+data);
                }
            });
        }
    });

    // ****************************** ANNULER INSCRIPTION *****************************
    $('.annulerInscription').click(function(event) {
        event.preventDefault();
        $('#nom, #prenoms, #prenom_usuel, #email, #es, #filiere, #password').val('');
        $('#promotion').val('2018');
        $('#erreurInscription').html('');
    });
});

// ************** FOR SHOWING PASSWORD ***************
let keyword = document.getElementById('keyword');
let checkboxLogin = document.getElementById('checkboxLogin');

if(checkboxLogin.checked===true) {
    keyword.type = 'text';
}
else {
    keyword.type = 'password';
}

checkboxLogin.addEventListener('click', function(event) {
    event.stopPropagation();
    if(this.checked===true) {
        keyword.type = 'text';
    }
    else {
        keyword.type = 'password';
    }
});

// ******************** SELECT FOR PROMOTIONS **********************
let promotion = document.getElementById('promotion');
promotion.innerHtml = '<option selected>2018</option>';
for (let annee of ['2018', '2019', '2020', '2021', '2022']) {
    promotion.innerHTML += '<option>'+annee+'</option>';
}
promotion.style.color = '#057885';


// ****************** SHOW & HIDE PASSWORD ************************
let showPassword = document.getElementById('showPassword');
let passwordInscription = document.getElementById('password');
showPassword.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    if(passwordInscription.type === "password") {
        passwordInscription.type = "text";
        showPassword.innerHTML = '<i class="fa-solid fa-eye"></i>';
    }
    else {
        passwordInscription.type = "password"
        showPassword.innerHTML = '<i class="fa-solid fa-eye-slash"></i>'
    }
});
