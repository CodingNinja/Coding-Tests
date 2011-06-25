<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Flikr Search</title>
    <style type="text/css">
        body {
        	text-align: center;
        }
        .result {
        	width: 20%;
            float: left;
            text-align: center;
        }
        .results {
        	clear: both;
            height: 140px;
        }
        .results a {
        	border: none;
        }
        .pagination .next {
        	float: right;
        }
        .pagination .previous {
            float: left;
        }
        #Container {
        	width: 90%;
            margin: 0 auto;
        }
    </style>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
</head>
<body>
    <div id="Container">
        <form action="index.php?c=search&a=search" method="post">
            <label for="searchTerm">Search Term: </label>
            <input type="text" name="term" id="searchTerm" />
            <button id="searchButton">Search!</button>
        </form>
        <?php echo $content; ?>
    </div>
</body>
</html>