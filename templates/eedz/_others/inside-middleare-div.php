<?php if ($this->countModules('mainBanner')) { ?>

<div class="sliderarea"><jdoc:include type="modules" name="mainBanner" /></div>

<?php }else{ ?>

<div id="breadcrumbs">

<jdoc:include type="modules" name="breadcrumbs" />

</div>

<?php } ?>

<div><jdoc:include type="message" /><jdoc:include type="component" /></div>

</div>



<?php if ($this->countModules('mainBanner')) { ?>