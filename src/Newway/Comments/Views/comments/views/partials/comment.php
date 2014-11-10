<div class='comments-list'>
    <?foreach($list as $comment):?>
        <div itemprop="review" itemscope itemtype="http://schema.org/Review" class="comment" id="comment_<?=$comment['id']?>">
            <div class="comment-row comment-header">
                <div class="comment-user"><?=$comment['user_name']?></div>
                <div class="comment-date"><meta itemprop="datePublished" content="<?=$comment['created_at']?>"><?=$comment['created_at']?></div>
                <div class="clr"></div>
            </div>
            <div class="comment-row comment-body">
                <span itemprop="description"><?=$comment['body']?></span>
                <div class="clr"></div>
            </div>
            <div class="comment-row comment-bottom">
                <div class="rating">
                    <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                        <meta itemprop="worstRating" content="<?=$comment['rating']?>">
                        <span itemprop="ratingValue"><?=$comment['rating']?></span>/<span itemprop="bestRating">5</span>
                        <div class="comment-star-rating">
                            <div class="stars"></div>
                            <div class="bg-stars bg-stars-<?=$comment['rating']?>"></div>
                        </div>
                    </div>
                </div>
                <div class="clr"></div>
            </div >
        </div>
    <?endforeach;?>
</div>