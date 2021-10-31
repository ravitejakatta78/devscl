<div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-12 d-flex justify-content-end pt-4" style="align-items:center">
                <ol class="breadcrumb" style="margin-right:auto" >
                <?php if(!empty($breadcrumbtitles)){ ?>
                    <li class="breadcrumb-item">
                        <a href="index.html"><?= $pagetitle; ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong><?= $subtitle ?? $pagetitle ; ?></strong>
                    </li>
                    <?php } ?>
                    <?php if(!empty($breadarrow)){ ?>
                    <li class="breadcrumb-item">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    </li>
                    <?php } ?>
                </ol>
                <?php if(!empty($toggleaddbutton)){ ?>
                    <button class="btn btn-w-m  btn-primary"  data-toggle="modal" data-target="#exampleModal">Add</button>
                <?php } ?>
            </div>
</div>