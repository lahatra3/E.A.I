$(document).ready(function() {
    async function execl() {
       await $.get('../api-EAI/api.php?demande=excel/get', function(data, status) {
            if(status==="success") {
                $('#excelFile').html(data);
            }
            else {
                console.log(status +'\n'+data);
            }
        }, "html");    
    }
    
    async function session() {
        $.get('../api-EAI/api.php?demande=login/getSession', function(data, status) {
            if(status === "success" && data.true === 1) {
                $.get('../api-EAI/api.php?demande=get/etudiants/'+data.prenom_usuel, function(data, status) {
                    if(status === "success") {
                        if(data.photo === null) data.photo = 'user.png';
                        $('#photoSession').attr('src', '../publics/profils/'+data.photo);
                        $('#nomSession').text(data.prenom_usuel);
                        console.table(data);
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
    }

    session();
    execl();

    // ************************* MODIFICATION DE L'EXCEL ************************
    $('#nomFichier, #nombrePage, #nombreExemplaire, #qualiteDocument, #reliure, #descriptions').change(function() {
        $.get('../api-EAI/api.php?demande=login/getSession', function(data, status) {
            if(status === "success" && data.true === 1) {
                $.get('../api-EAI/api.php?demande=get/etudiants/'+data.prenom_usuel, function(data, status) {
                    if(status === "success") {
                        $.post('../api-EAI/api.php?demande=excel/add', {
                            nomEtudiant: data.prenom_usuel,
                            esEtudiant: data.ecole_superieure,
                            promotionsEtudiant: data.promotions,
                            foyerEtudiant: data.foyer,
                            nomFichier: $('#nomFichier').val(),
                            nombrePage: $('#nombrePage').val(),
                            nombreExemplaire: $('#nombreExemplaire').val(),
                            qualiteDocument: $('#qualiteDocument').val(),
                            reliure: $('#reliure').val(),
                            descriptions: $('#descriptions').val()

                            }, function(data, status) {  
                                if(status==="success" && data===1) {
                                    execl();
                                }
                                else {
                                    console.log(status+"\n"+data);
                                }
                        });
                    }
                    else {
                        console.log(status + "\n" + data);
                    }
                });
            }
        });
    });
});
