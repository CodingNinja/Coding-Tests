<div class="results" id="resultImages">
    <?php foreach($results as $result): ?>
        <div class="result">
            <a href="<?php echo \App\API\Flikr::getUriToImage($result, 'z'); ?>" target="_blank"><img src="<?php echo \App\API\Flikr::getUriToImage($result); ?>" /></a>
        </div>
    <?php endforeach; ?>
</div>