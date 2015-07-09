	
	<ul>
	<?
	
	// Uses $poll, which should be a PollObject object.
	
		if (Utility::is_loopable($poll->answers)){
			
			$rescols = array('#ffa418', '#4274a5', '#72b241', '#e94a4a', '#c3f');
			$i = 0;
			$widths = array();
			
			// Loop through all answers first, to get the total number of votes
			$total = 0;
			foreach ($poll->answers as $answer) { 
				$total += $answer->votes;
			}
			// Loop through all answers again, to get the the maximum percentage
			$maximum = -1;
			foreach ($poll->answers as $answer){
				$perc = round(($answer->votes/$total)*100,0);
				if ($maximum < $perc) $maximum = $perc;
			}
			
			// Then iterate through and calculate (and display) each bar with its width.
			foreach ($poll->answers as $answer) {
				
				$perc = round(($answer->votes/$total)*100,0);
				$width = (($perc/$maximum)*100);
				$widths[] = $width;
				?>
					<li>
						<div class="text-content"><?=$answer->text;?></div>
						<div style="width: 98%">
							<div class="pollbar" style="visibility:hidden;background:<?=$rescols[($i%count($rescols))]?>;padding:5px;color:#eee;width:<?=$width?>%;text-align:center;font-weight:bold;">
								<? if($width < 8) { ?> <span style="margin-left:<?=(10+($width*6))?>px;color:#666;"> <? } ?>
									<? echo $perc."%"; ?>
								<? if($width < 8) { ?> </span> <? } ?>
							</div>
						</div>
					</li>
				<? 
				
				$i++;
				
			}
			
		}
		
	?>
	</ul>
	<div class="clear"></div>