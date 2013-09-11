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
