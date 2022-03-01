$(document).ready(function() {
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
