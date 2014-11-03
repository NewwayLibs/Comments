<?if($paginator->getLastPage() > 1):?>
<div class="col-md-12 text-center">
    <?if($paginator->getLastPage() > 8 && $paginator->getLastPage() - $paginator->getCurrentPage() < 3):?>
    <a href="<?=$paginator->getUrlToPage(1)?>" class="btn btn-default">&#x21da;</a>
    <a href="<?=$paginator->getUrlToPage(1)?>" class="btn btn-default">1</a>
    <a href="<?=$paginator->getUrlToPage(2)?>" class="btn btn-default">2</a>
    <span>...</span>
    <?for ($i = 2; $i >= 0; $i++):?>
    <a href="<?=$paginator->getUrlToPage($paginator->getLastPage() - $i)?>" class="btn <?if($paginator->getLastPage() - $i == $paginator->getCurrentPage()):?>btn-primary<?else:?> btn-default <?endif;?>"><?=$paginator->getLastPage() - $i?></a>
    <?endfor;?>
    <a href="<?=$paginator->getUrlToPage($paginator->getLastPage())?>" class="btn btn-default">&#x21db;</a>
    <?elseif($paginator->getLastPage() > 8 && $paginator->getCurrentPage() < 3):?>
    <a href="<?=$paginator->getUrlToPage(1)?>" class="btn btn-default">&#x21da;</a>
    <?for ($i = 1; $i < 4; $i++):?>
    <a href="<?=$paginator->getUrlToPage($i)?>" class="btn <?if($i == $paginator->getCurrentPage()):?> btn-primary <?else:?> btn-default <?endif;?>"><?=$i?></a>
    <?endfor;?>
    <span>...</span>
    <a href="<?=$paginator->getUrlToPage($paginator->getLastPage() - 1)?>" class="btn btn-default"><?=$paginator->getLastPage() - 1?></a>
    <a href="<?=$paginator->getUrlToPage($paginator->getLastPage())?>" class="btn btn-default"><?=$paginator->getLastPage()?></a>
    <a href="<?=$paginator->getUrlToPage($paginator->getLastPage())?>" class="btn btn-default">&#x21db;</a>
    <?elseif($paginator->getLastPage() > 8):?>
    <a href="<?=$paginator->getUrlToPage(1)?>" class="btn btn-default">&#x21da;</a>
    <a href="<?=$paginator->getUrlToPage(1)?>" class="btn btn-default">1</a>
    <a href="<?=$paginator->getUrlToPage(2)?>" class="btn btn-default">2</a>
    <span>...</span>
    <a href="<?=$paginator->getUrlToPage($paginator->getCurrentPage() - 1)?>" class="btn btn-primary"><?=$paginator->getCurrentPage() - 1?></a>
    <a href="<?=$paginator->getUrlToPage($paginator->getCurrentPage())?>" class="btn btn-primary"><?=$paginator->getCurrentPage()?></a>
    <a href="<?=$paginator->getUrlToPage($paginator->getCurrentPage() + 1)?>" class="btn btn-primary"><?=$paginator->getCurrentPage() + 1?></a>
    <span>...</span>
    <a href="<?=$paginator->getUrlToPage($paginator->getLastPage() - 1)?>" class="btn btn-default"><?=$paginator->getLastPage() - 1?></a>
    <a href="<?=$paginator->getUrlToPage($paginator->getLastPage())?>" class="btn btn-default"><?=$paginator->getLastPage()?></a>
    <a href="<?=$paginator->getUrlToPage($paginator->getLastPage())?>" class="btn btn-default">&#x21db;</a>
    <?else:?>
    <a href="<?=$paginator->getUrlToPage(1)?>" class="btn btn-default">&#x21da;</a>
    <?for($i = 1; $i <= $paginator->getLastPage(); $i++):?>
    <a href="<?=$paginator->getUrlToPage($i)?>" class="btn <?if($i == $paginator->getCurrentPage()):?>btn-primary<?else:?> btn-default <?endif;?>"><?=$i?></a>
    <?endfor;?>
    <a href="<?=$paginator->getUrlToPage($paginator->getLastPage())?>" class="btn btn-default">&#x21db;</a>
    <?endif;?>
</div>
<?endif;?>