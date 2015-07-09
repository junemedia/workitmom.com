                <div id="featuredentrepreneur">
                        <div style="float:left; width:125px;"><a href="#"><img src="../../userimages/122/<?=$hblog['userImage']?$hblog['userImage']:'default.gif';?>" border="0" class="imgbrd3" /></a></div>
                        <div id="featentreptext" style="width:365px;">
                                <div class="font14b"><?=$hblog['firstname']." ".$hblog['lastname'];?>: <?=$hblog['blogTitle'];?></div>
                                <div class="martb2"><? if ($hblog['employerName']){?><?=$hblog['jobTitle']?$hblog['JobTitle']:'Works';?><?=$hblog['employerName']?" at ".$hblog['employerName']:'';?><? } ?></div>
                                <div style="line-height:14px;"><?=$hblog['blogDescription'];?></div>
                        </div>
                </div>
