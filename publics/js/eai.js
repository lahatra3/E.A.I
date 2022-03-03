$(document).ready(function() {
    $.get('../api-EAI/api.php?demande=excel/get', function(data, status) {
        if(status==="success") {
            $('#excelFile').html(data);
        }
        else {
            console.log(status +'\n'+data);
        }
    }, "html");

    $.get('../api-EAI/api.php?demande=login/getSession', function(data, status) {
        if(status === "success" && data.true === 1) {
            $.get('../api-EAI/api.php?demande=get/etudiants/'+data.prenom_usuel, function(data, status) {
                if(status === "success") {
                    if(data.photo === null) data.photo = 'user.png';
                    $('#photoSession').attr('src', '../publics/profils/'+data.photo);
                    $('#nomSession').text(data.prenom_usuel);
                }
                else {
                    console.log(status + "\n" + data);
                }
            });
        }
        else {
            window.location.replace('../index.html');
        }
    });

    // ************************* MODIFICATION DE L'EXCEL ************************
    $('#nomFichier, #nombrePage, #nombreExemplaire, #qualiteDocument, #reliure, #descriptions').change(function() {
        $.post('../api-EAI/api.php?demande=excel/add', {
                nomFichier: $('#nomFichier').val(),
                nombrePage: $('#nombrePage').val(),
                nombreExemplaire: $('#nombreExemplaire').val(),
                qualiteDocument: $('#qualiteDocument').val()
            }, function(data, status) {

            });
    });
});
