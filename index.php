<!DOCTYPE html>
<html>
    <!--Got reallyeasybib.com so we can put it up whenever we're done-->
    <head>
        <!--<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->

        <link rel ="stylesheet" href="/css/bootstrap.min.css">
        <script src="/jquery-2.2.2.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        
    </head>
    <body>
        <!--<nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/index.php">REB</a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="/help.php">Help</a></li>
                </ul>
            </div>
        </nav>-->
        
        <div class="container">
            <img src="logolong.png" style="height:auto; max-width:50%; ">
            <p>An efficient research tool!</p> 
            <br>
       
            Add phrases here: <input type ="text" id="keyword-input" placeholder="Keyword"> 
            
            <button id="add-keyword" data-toggle="tooltip" title="Add key search words here.">Add</button>
        
            <ul class="keywords"></ul>
        
            <button id="submit-button" data-toggle="tooltip" title="Click to search. May take a while :(">Get Bibliographies</button>
        
            <div id="results">
                

            </div>
            
        </div>
        
        <script>
            function getLinks() {
                var words = [];
                $(".keyword").each(function() {
                    words.push($(this).text());
                });
                var query = words.join();

                $.get("search.php?q="+query, function(data) {
                    console.log(data);
                    $("#results").html(createBib(data));
                });
            }
            
            function createBib(data) {
                return jQuery.parseJSON(data);
            }
            
            $(document).ready(function() {
                $('#add-keyword').click(function() {
                    var input = $('#keyword-input');
                    var text = input.val().trim();
                    if (text.length > 0) {
                        $('.keywords').append("<li class=\"keyword\" data-toggle='tooltip' title='Click to remove'>"+text+"</li>");
                        input.val("");
                    }
                });
                $('.keywords').on('click', '.keyword', function() {
                    $(this).remove();
                });
                $('#submit-button').click(function() {
                    getLinks();
                });
                $(document).ready(function(){
                    $('[data-toggle="tooltip"]').tooltip(); 
                });


            });
        </script>
    </body>
</html>
