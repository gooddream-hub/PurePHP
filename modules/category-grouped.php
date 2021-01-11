<? if ( $_GET['type'] == 'Busks' OR $_GET['type'] == 'Spiral-boning' OR $_GET['type'] == 'Flat-boning' OR $_GET['type'] == 'Polyester-boning-precut'): ?>
    <? if ($prods): ?>
        <? foreach ($prods as $row): ?>
            <? if ($color != $row['color']): ?>
                <? $color = $row['color'] ?>
                <div class="col <?= $class ?>" id="pid-<?= $row['invid'] ?>" itemscope
                     itemtype="http://schema.org/IndividualProduct">
                    <a class="catImg" href="products.<?= $row['color'] ?>-<?= str_replace(' ', '-', $row['length']) ?>,<?= $row['type'] ?>,<?= $row['cat'] ?>">
                        <img itemprop="image" class="catProd" src="http://mjtrends-672530.c.cdn77.org/images/product/<?= $row['invid'] ?>/<?= $row['img'] ?>_370x280.jpg" alt="<?= $row['alt'] ?>"/></a>
                    <a href="products.<?= $row['color'] ?>,<?= $row['type'] ?>,<?= $row['cat'] ?>" itemprop="url">
                        <h4 itemprop="name">
                            <?if($_GET['type'] == 'Spiral-boning' OR $_GET['type'] == 'Flat-boning' OR $_GET['type'] == 'Polyester-boning-precut'): ?>
                                <?="6 Pack Of "?>
                            <?endif;?>
                            <?= str_replace("-", " ", $row['color']) . " " . str_replace("-", " ", $row['type']) ?>        
                        </h4>
                    </a>
                    <select class="group-param">
                        <? foreach ($prods as $l): ?>
                            <? if ($l['color'] == $row['color']): ?>
                                <option data-price="List Price <?= $l['price'] ?>" data-title="<?= $l['title'] ?>"
                                        data-href="products.<?= $l['color'] ?>-<?= str_replace(' ', '-', $l['length']) ?>,<?= $l['type'] ?>,<?= $l['cat'] ?>" <?= ($row['invid'] == $l['invid']) ? 'selected="selected"' : ''; ?>
                                        value="<?= $l['invid'] ?>"><?php echo $l['length'] . ' $' . $l['price']; ?></option>
                            <? endif; ?>
                        <? endforeach; ?>
                    </select>
                    <? if ($row['sale'] != ''): ?>
                        <img class="imgSale" alt="<?= $row['color'] ?> <?= $row['type'] ?> currently on sale"
                             src="http://mjtrends-672530.c.cdn77.org/images/sale-star.png">
                    <? endif; ?>

                </div>
            <? endif; ?>
        <? endforeach; ?>
    <? endif; ?>
<? endif; ?>

