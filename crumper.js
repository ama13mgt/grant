$(document).ready(function(){
    $("form").submit(function(event){
        var experimentResults = {
            description: $("#description").val(),
            analysis: $("#analysis").val(),
        };

        $.ajax({
            type: "POST",
            url: "dripper.php",
            data: experimentResults,
            dataType: "json",
            encode: true,
        }).done(function(data){
            console.log(data);

            if(!data.success) {
                $('#errorer').removeClass("hidden");
            } else {
                
            
        });
        event.preventDefault();
    });
});