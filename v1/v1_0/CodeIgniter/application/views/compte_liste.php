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
                echo "Nombre de comptes : ".$nombre->NbCpt;
                echo ("<br/>");
                if($pseudos != NULL) {
                    foreach ($pseudos as $login) {
                        echo "<br/>";
                        echo " -- ";
                        echo $login["USR_PSEUDO"];
                        echo " -- ";
                        echo "<br/>";
                    }
                }
                else {
                    echo "<br/>";
                    echo "Aucun compte !";
                }
            ?>
        </div>
    </div>

</div>
</section>