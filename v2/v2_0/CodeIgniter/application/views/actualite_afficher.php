<section class="problem_section layout_padding">
<div class="container">
    <div class="custom_heading-container">
    <h2>
    <?php echo $titre; ?>
    </h2>
    </div>
    <div class="layout_padding2">
        <div class="detail-box">
            <?php
                if (isset($actu)) {
                    echo $actu->ACT_ID;
                    echo (" -- ");
                    echo $actu->ACT_CONTENU;
                } else {
                    echo "<br/>";
                    echo "Cette actualité n'existe pas !";
                }
            ?>
        </div>
    </div>

</div>
</section>

