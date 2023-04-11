<figure class="baha1493 article-item <? $hover ?>">
    <!--                            <div class="image"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/ls-sample1.jpg" alt="ls-sample1" /></div>-->
    <figcaption>
        <div class="date">
            <span class="day">
                <?= date('d', $article['created_at']) ?>
            </span>
            <span class="month">
                <?= date('m', $article['created_at']) ?>
            </span>
        </div>
        <h3>
            <? $article['id'] . ': ' ?>
            <?= $article['title'] ?>
        </h3>
        <div class="article-text-list" style="height: 100px;">
            <?= $article['text'] ?>
        </div>
        <footer class="article-statistics" style="background-color: white;">
            <div class="views"><i class="ion-eye"></i>2,907</div>
            <div class="love"><i class="ion-heart"></i>623</div>
            <div class="comments"><i class="ion-chatboxes"></i>23</div>
        </footer>
    </figcaption>
    <a href="/articles?id=<?= $article['id'] ?>"></a>
</figure>