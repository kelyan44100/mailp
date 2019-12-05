<!-- WIDGETS GENERATION -->
<?php $purchasingFairConcerned = $_SESSION['purchasingFairConcerned']; ?>
<div id="pf_<?php echo $purchasingFairConcerned->getIdPurchasingFair(); ?>">
    <div class="widget style1" style="background-color:<?php echo $purchasingFairConcerned->getHexColor(); ?>" title="<?php echo $purchasingFairConcerned->getOneTypeOfPf()->getNameTypeOfPf(); ?>">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 text-center">
                <i class="fa fa-handshake-o fa-4x" aria-hidden="true"></i>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 text-right" style="word-wrap: break-word;">
                <span><?php echo $appService->myFrenchDate($purchasingFairConcerned->getStartDatetime()).' <i class="fa fa-long-arrow-right" aria-hidden="true"></i> '.$appService->myFrenchDate($purchasingFairConcerned->getEndDatetime()); ?></span>
                <h3 class="font-bold"><?php echo $purchasingFairConcerned->getNamePurchasingFair(); ?></h3>
            </div>
        </div>
    </div>
</div>
<!-- ./WIDGETS GENERATION -->