<?php
/**
 * @var View $this
 */
use Cake\View\View;
use League\CommonMark\CommonMarkConverter;

?>

<a href="/matches?year=<?= $scorecard["date"]->format("Y") ?>">&lt;&lt; back to match list</a>

<h2>
    <?= $scorecard["date"]->format("D jS M Y") ?> vs. <?= h($scorecard["opposition"]) ?> (<?= $scorecard["venue"] ?>)
    <?php if ($scorecard["venue_name"]): ?>
        @ <?= h($scorecard["venue_name"]) ?>
    <?php endif; ?>
</h2>

<?php if ($this->Authorization->isAdmin()): ?>
    [<a href="/admin/matches/edit/<?= $scorecard["id"] ?>">edit</a>]
<?php endif; ?>

<div class="columns break">
    <div class="column one-third">

        <p>
            <strong>Format:</strong> <?= $scorecard["format"]["name"] ?>
            <br>
            <strong>Result:</strong> <?= $scorecard["result"] ?>
            <?php if (isset($scorecard["result_more"]) && !empty($scorecard["result_more"])): ?>
                by <?= h($scorecard["result_more"]) ?>
            <?php endif; ?>

            <?php if ($scorecard["match_manager"]): ?>
                <br>
                <strong>Match Manager:</strong>
                <?= $this->Player->name($scorecard["match_manager"]) ?>
            <?php endif; ?>

            <?php if ($scorecard["man_of_the_match"]): ?>
                <br>
                <strong>MOTM:</strong>
                <?= $this->Player->name($scorecard["man_of_the_match"]) ?>
            <?php endif; ?>
        </p>

        <?php if ($scorecard["ivcc_batted_first"] == 1): ?>
            <?= $this->Element("batting_card") ?>
            <?= $this->Element("bowling_card") ?>
        <?php else: ?>
            <?= $this->Element("bowling_card") ?>
            <?= $this->Element("batting_card") ?>
        <?php endif; ?>

    </div>

    <?php if (!empty($scorecard["match_report"])): ?>
        <div class="column two-thirds">
            <h4>Match report</h4>
            <?= (new CommonMarkConverter())->convertToHtml($scorecard["match_report"]) ?>
        </div>
    <?php endif; ?>

</div>

<div id="disqus_thread"></div>
<script>
    var PAGE_URL = '<?= $this->Url->build(null, true) ?>';
    var PAGE_IDENTIFIER = 'ivcc_match_<?= $scorecard["id"] ?>';

    var disqus_config = function () {
        this.page.url = PAGE_URL;
        this.page.identifier = PAGE_IDENTIFIER;
    };

    (function() {
        var d = document, s = d.createElement('script');

        s.src = '//iffleyvillagecricketclub.disqus.com/embed.js';

        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
