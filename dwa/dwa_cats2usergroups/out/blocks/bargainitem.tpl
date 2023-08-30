[{if !$_product->isNotBuyable() && !( $_product->getVariantsCount() || $_product->hasMdVariants() || ($oViewConf->showSelectListsInList() && $_product->getSelections(1)) )}]
    <a href="[{oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=start" params="fnc=tobasket&amp;aid=`$_product->oxarticles__oxid->value`&amp;am=1"}]" class="toCart button" title="[{oxmultilang ident="TO_CART" }]">[{oxmultilang ident="TO_CART" }]</a>
[{else}]
    <a href="[{$_product->getMainLink()}]" class="toCart button">[{ oxmultilang ident="MORE_INFO" }]</a>
[{/if}]