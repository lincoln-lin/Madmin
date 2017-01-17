
<div class="row mz-message">
    <div class="col-xs-12">
        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
            <?php if (in_array($type, ['_success_', '_danger_', '_warning_', '_info_'])): ?>
                <div class="alert-dismissible alert alert-<?= trim($type,'_') ?>">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?= $message ?>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>
