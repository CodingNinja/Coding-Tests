<h2>Searched for <?php echo $term; ?> - <?php echo count($results); ?> Results</h2>

<p>Page <span id="currentPage"><?php echo $page; ?></span> of <?php echo $lastPage; ?></p>
<div class="results" id="resultImages">
    <?php foreach($results as $result): ?>
        <div class="result">
            <a href="<?php echo \App\API\Flikr::getUriToImage($result, 'z'); ?>" target="_blank"><img src="<?php echo \App\API\Flikr::getUriToImage($result); ?>" /></a>
        </div>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?php if($prevPage < $page): ?>
    <a href="index.php?c=search&a=search&term=<?php echo $term; ?>&page=<?php echo $prevPage; ?>" class="previous">Previous Page </a>
    <?php endif;?>

    <?php if($nextPage > $page): ?>
    <a href="index.php?c=search&a=search&term=<?php echo $term; ?>&page=<?php echo $nextPage; ?>" class="next">Next Page</a>
    <?php endif;?>
</div>
<script type="text/javascript">
    var nextPage = parseInt(<?php echo $nextPage; ?>),
        prevPage = parseInt(<?php echo $prevPage; ?>),
        ajax     = false,
        url      = 'index.php?c=search&a=search&term=<?php echo $term; ?>&page=';

    if(hashPage = parseInt(location.hash.substr(1))) {
        updatePage(hashPage);
    }
    $('.pagination').delegate('a', 'click', function() {
        if(typeof(ajax.abort) == 'function') {
            ajax.abort();
        }

        return updatePage($(this).is('.next') ? nextPage : prevPage);
    });

    function updatePage(toPage) {
        $('#currentPage').text(toPage);

        nextPage = toPage+1;
        prevPage = toPage-1;

        ajax = $('#resultImages').load(url + (toPage));
        location.hash = toPage;
        return false;
    }
</script>