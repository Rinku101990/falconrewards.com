<style>
    .table-row{
        cursor:pointer;
    }
</style>
<div class="app-content">
    <div class="section">
        <!--  Page-header opened -->
        <div class="page-header">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url('dashboard');?>"><i class="fe fe-home mr-1"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Mails</li>
            </ol>
            <div class="mt-3 mt-lg-0">
                <div class="d-flex align-items-center flex-wrap text-nowrap">
                    <button type="button" onclick="history.go(-1)" class="btn btn-secondary btn-icon-text mr-2 mb-2 mb-md-0"> <i class="fa fa-arrow-left"></i> Go Back </button>
                </div>
            </div>
        </div>
        <!--  Page-header closed -->
        <!-- row opened -->
        <div class="row">
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="list-group list-group-transparent mb-0 mail-inbox">
                        <div class="mt-4 mb-4 ml-4 mr-4 text-center"> 
                        <a href="<?php echo site_url('mails/compose');?>" class="btn btn-primary btn-lg btn-block">Compose</a> </div>
                        <a href="<?php echo site_url('mails');?>" class="list-group-item list-group-item-action d-flex align-items-center"> <span class="icon mr-3"><i class="fe fe-inbox"></i></span>Inbox <span class="ml-auto badge-pill badge badge-success"><?php if($newMsg->new!='0'){echo $newMsg->new;}?></span> </a>
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex align-items-center"> <span class="icon mr-3"><i class="fe fe-send"></i></span>Sent Mail </a>
                        <a href="<?php echo site_url('mails/starred');?>" class="list-group-item list-group-item-action d-flex align-items-center"> <span class="icon mr-3"><i class="fe fe-star"></i></span>Starred </a>
                        <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex align-items-center"> <span class="icon mr-3"><i class="fe fe-trash-2"></i></span>Trash </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12  col-xl-9 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Starred</h3>
                        <div class="card-options">
                            <form>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" placeholder="Search.." name="s"> <span class="input-group-btn ml-0"> <button class="btn btn-sm btn-primary" type="submit"> <span class="fe fe-search text-white"></span> </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="inbox-body">
                            <div class="mail-option">
                                <div class="btn-group hidden-phone"> <a data-toggle="dropdown" style="color: #000;" href="#" class="btn mini blue" aria-expanded="false"> More <i class="fa fa-angle-down "></i> </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0)" class="btnMakeAsRead"><i class="fas fa-pencil-alt"></i> Mark as Read</a></li>
                                        <li class="divider"></li>
                                        <li><a href="javascript:void(0)" class="btnRemoveMessage"><i class="fa fa-trash"></i> Delete</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group">
                                    <a data-original-title="Refresh" data-placement="top" data-toggle="dropdown" style="color: #000;" href="javascript:void(0)" data-href="<?php echo site_url('mails')?>" class="btn mini tooltips pageReload"> <i class="fa fa-refresh"></i> </a>
                                </div>
                                <ul class="unstyled inbox-pagination mt-3">
                                    <li><span>1-50 of 234</span></li>
                                </ul>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-inbox table-hover table-vcenter mail-body text-nowrap">
                                    <tbody>
                                        <?php if(!empty($starredList)){ foreach($starredList as $slist){ ?>
                                            <tr class="unread">
                                                <input type="hidden" name="rowid" id="rowid" class="rowid" value="<?php echo encode($slist->msg_id);?>">
                                                <input type="hidden" name="rowStarId" id="rowStarId" class="rowStarId<?php echo encode($slist->msg_id);?>" value="<?php echo $slist->msg_star;?>">
                                                <td class="inbox-small-cells" style="padding: .5rem; !important">
                                                    <label class="custom-control custom-checkbox" style="min-height: 1.1rem !important;padding-left: 0.8rem !important;">
                                                    <input type="checkbox" class="custom-control-input" name="checkbox[]" value="<?php echo $slist->msg_id;?>"> <span class="custom-control-label"></span> </label>
                                                </td>
                                                
                                                <td class="inbox-small-cells starAction" id="rowStar<?php echo encode($slist->msg_id);?>" style="padding: .5rem; !important">
                                                    <i class='fa fa-star<?php if($slist->msg_star=='1'){echo' inbox-started';}else{echo "-o";}?>' <?php if($slist->msg_star=='1'){echo "style='color: #ffab00;'";}?>></i>
                                                </td>
                                                
                                                <td class="view-message dont-show table-row" data-href="<?php echo site_url('mails/read/'.encode($slist->msg_id))?>" style="padding: .5rem; !important">
                                                    <?php if($slist->msg_type==0){echo "<span class='badge badge-danger badge-pill'>new</span>";}else{echo "";}?> 
                                                    <?php $textSubject = strlen($slist->msg_subject); if($textSubject > 20){echo substr($slist->msg_subject,0,20).'..';}else{echo substr($slist->msg_subject,0,20);}?>
                                                </td>
                                                
                                                <td class="view-message table-row" data-href="<?php echo site_url('mails/read/'.encode($slist->msg_id))?>" style="padding: .5rem; !important">
                                                    <?php $textMessage = strlen($slist->msg_message); if($textMessage > 45){echo substr($slist->msg_message,0,45).'..';}else{echo substr($slist->msg_message,0,45);}?>
                                                </td>
                                                <!-- <td class="view-message  inbox-small-cells"><i class="fa fa-paperclip"></i></td> -->
                                                <td class="view-message text-right" style="padding: .5rem; !important">
                                                    <?php echo nicetime($slist->msg_created);?>
                                                </td>
                                            </tr>
                                        <?php } }else{ ?>
                                            <tr>
                                                <td colspan="5"><center>no message list</center></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="pagination mb-5">
                    <li class="page-item page-prev disabled"> <a class="page-link" href="#" tabindex="-1">Prev</a> </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item page-next"> <a class="page-link" href="#">Next</a> </li>
                </ul>
            </div>
        </div>
        <!-- row closed -->
    </div>
</div>