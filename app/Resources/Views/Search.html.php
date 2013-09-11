<h2>Searched for <?php echo $term; ?> - <?php echo count($results); ?> Results</h2>

<p>Page <span id="currentPage"><?php echo $page; ?></span> of <?php echo $lastPage; ?></p>
<div class="results" id="resultImages">
    <?php foreach($results as $i => $result): ?>
        <div class="result">
            <a href="#modal-result-<?php echo $i; ?>" data-toggle="modal">
                <img src="<?php echo \App\API\Flikr::getUriToImage($result); ?>" alt="<?php echo \App\API\Flikr::getTitleToImage($result); ?>" title="<?php echo \App\API\Flikr::getTitleToImage($result); ?>" />
            </a>
            <div class="modal-auto-size modal hide fade" id="modal-result-<?php echo $i; ?>">
              <div class="modal-body">
                <p>
                    <img src="<?php echo \App\API\Flikr::getUriToImage($result, 'b'); ?>" alt="<?php echo \App\API\Flikr::getTitleToImage($result); ?>">
                </p>
              </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="pagination page-<?php echo $page; ?>" id="pagination-container">
    <a href="index.php?c=search&a=search&term=<?php echo $term; ?>&page=<?php echo $prevPage; ?>" class="previous">Previous Page </a>
    <a href="index.php?c=search&a=search&term=<?php echo $term; ?>&page=<?php echo $nextPage; ?>" class="next">Next Page</a>
</div>

<style type="text/css">
    .pagination.page-1 .previous {
        display: none;
    }
    .pagination.page-<?php echo $lastPage; ?> .next {
        display: none;
    }
</style>

<script type="text/javascript">
;;;(function() {
    var nextPage = parseInt(<?php echo $nextPage; ?>),
        prevPage = parseInt(<?php echo $prevPage; ?>),
        totalPages = parseInt(<?php echo $lastPage; ?>),
        url      = 'index.php?c=search&a=search&term=<?php echo $term; ?>&page=',
        ajax;

    if(history.replaceState) {
        history.replaceState({page: <?php echo $page; ?>}, null, location.href);
    }

    function updatePage(toPage, updateHistory) {
        nextPage = toPage+1;
        prevPage = toPage-1;
        if(toPage > totalPages || toPage <= 0) {
            return false;
        }

        ajax = $.ajax(url + (toPage), { }).done(function(data) {
            $('#resultImages').html(data);
            if(updateHistory !== false) {
                pushState(url, toPage);
            }
            $('#currentPage').text(toPage);
            $('#pagination-container').prop('class', 'pagination page-' + toPage);
        });

        return false;
    }

    function pushState(url, page) {
        if(!history.pushState) {
            return;
        }

        history.pushState({page: page}, null, url + (page));
    }

    function popState(e) {
        if(!e.state) {
            return;
        }

        updatePage(e.state.page, false);
    }

    $(function() {
        $('.pagination').delegate('a', 'click', function() {
            return updatePage($(this).is('.next') ? nextPage : prevPage);
        });
        window.addEventListener("popstate", function(e) {
            popState(e);
        });

    });
}());
</script>
