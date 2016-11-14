$(document).ready(function () {


    /* ----------------- upload ----------------------- */

    //je récupère l'action où sera traité l'upload en PHP
    var _actionToDropZone = $("#dropzone-upload").attr('action');

    //je définis ma zone de drop grâce à l'ID de ma div citée plus haut.
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#dropzone-upload", {
        url: _actionToDropZone,
        filesizeBase : 5000,
        maxFilesize: 3,
        maxFiles : 30,
        acceptedFiles : "image/jpeg,image/png,image/gif",
        init : function(){
            this.on('addedfile', function(file){
                console.log("Added file.");
            });
            this.on('complete', function(file){
                $('#photoView').removeClass('photoView').show();
            });

            this.on('maxfilesexceeded', function(){
                console.log("Limite de fichier simultannée attient");
            });
        }
    });
    
    
});