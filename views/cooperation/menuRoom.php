<style>
	body .load-coop-data .progress{
		display: none;
	}
	#menu-room .load-coop-data .progress{
		display: block;
	}
	body .load-coop-data,
	#menu-room .load-coop-data{
		/*border-left: 3px solid #3C545D;*/
		padding:8px;
		margin-top:5px;
		font-size: 15px;
		line-height: 17px;
	}

	#menu-room .sub-resolutions .load-coop-data{
		line-height: 14px;
	}
</style>

<?php 
	$auth = false;
	if(@$room){
		$auth = Authorisation::canParticipate(Yii::app()->session['userId'], @$room["parentType"], @$room["parentId"]);
		//$menuCoopData = Cooperation::getCoopData(@$room["parentType"], @$room["parentId"], "room");
		$parentId = @$room["parentId"]; $parentType = @$room["parentType"];
		
	}
	else if(@$post["parentType"]){
		$auth = Authorisation::canParticipate(Yii::app()->session['userId'], @$post["parentType"], @$post["parentId"]);	
		//$menuCoopData = Cooperation::getCoopData(@$post["parentType"], @$post["parentId"], "room");
		$parentId = @$post["parentId"]; $parentType = @$post["parentType"];
	}

	if(isset(Yii::app()->session['userId'])){
		$me = Element::getByTypeAndId("citoyens", Yii::app()->session['userId']);
		$myRoles = @$me["links"]["memberOf"][@$parentId]["roles"] ? 
				   @$me["links"]["memberOf"][@$parentId]["roles"] : array();
	}else{
		$myRoles = array();
	}	
	
	$auth = Authorisation::canParticipate(Yii::app()->session['userId'], @$parentType, @$parentId);	

	$isAdmin = Authorisation::isElementAdmin(@$parentId, @$parentType, Yii::app()->session['userId']);

	$thisType = @$parentType; //@$room ? @$room["parentType"] : @$post["parentType"];
?>



<?php $accessRoom = @$room ? Room::getAccessByRole($room, $myRoles) : ""; ?>

<div class="col-lg-12 col-md-12 col-sm-12 no-padding bg-white text-dark" id="coop-container">
	
	<?php 
		if(isset($roomList) && empty(@$roomList)){ ?>
		<div class="col-lg-12 col-md-12 col-sm-12" id="menu-room">
			<?php $this->renderPartial('../cooperation/pod/home', array("type"=>$thisType)); ?>
		</div>
	<?php }else{ ?>

		<div class="col-lg-12 col-md-12 col-sm-12 bg-white" id="menu-room">
			
			<?php if(@$room){ ?>
				
				<?php if(@$auth && $accessRoom != "lock"){ ?>
					<button class="btn btn-default pull-right btn-sm margin-top-10 hidden-min tooltips" 
							data-target="#modalDeleteRoom" data-toggle="modal" id="btn-open-modal-delete">
						<i class="fa fa-trash"></i> <?php echo Yii::t("common", "Delete"); ?>
					</button>
					<button class="btn btn-default pull-right btn-sm margin-top-10 hidden-min tooltips margin-right-5" 
							id="btn-edit-room" data-placement="bottom" 
							data-original-title="<?php echo Yii::t("cooperation", "Edit this space"); ?> : <?php echo @$room["name"]; ?>"
							data-id-room="<?php echo @$room["_id"]; ?>">
						<i class="fa fa-pencil"></i> <?php echo Yii::t("common", "Update"); ?>
					</button>
				<?php } ?>
				
				<button class="btn btn-link text-dark pull-right btn-sm margin-top-5 hidden-min tooltips margin-right-5" 
						id="btn-edit-room" data-placement="bottom" 
						data-original-title="<?php echo Yii::t("common", "Help"); ?>"
						data-toggle="modal" data-target="#modalHelpCOOP">
					<i class="fa fa-2x fa-info-circle"></i>
				</button>

				<h3 class="margin-top-15 letter-turq">
					<i class="fa fa-connectdevelop"></i> 
					<i class="fa fa-hashtag"></i> <?php echo @$room["name"]; ?>
				</h3>

				
				<h4 class="room-desc"><small><?php echo @$room["description"]; ?></small></h4>

				<?php if(@$room["roles"] && @$room["roles"] != ""){ ?>
					<?php
						$roomRoles = @$room["roles"]; 	
						if(!is_array(@$room["roles"])) 
							$roomRoles = explode(",", @$room["roles"]); 	
					?>
					<h5 class="room-desc">
						<small class="letter-blue">
							<b><i class="fa fa-unlock-alt"></i> <?php echo Yii::t("cooperation","Access restricted only for"); ?> : </b>
							<?php $r = ""; foreach ($roomRoles as $role) {
								if($r!="") $r.=", "; $r.=$role;
							} 	echo $r; ?>
						</small>
					</h5>
				<?php } ?>

				<hr>

			<?php }else{ ?>
				<h3 class="margin-top-15 elipsis">
					<i class="fa fa-search"></i> 
					<?php $thisStatus = @$post["status"] ? @$post["status"] : "all status"; ?>
					<?php echo Yii::t("cooperation", @$post["type"])." <small>".
							   Yii::t("cooperation", @$thisStatus)."</small>"; ?>
				</h3>
				<hr>

			<?php } ?>

			
			<?php if(@$access=="deny"){ ?>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<h5 class="padding-left-10 letter-red">
						<i class="fa fa-ban"></i> <?php echo Yii::t("cooperation", "You are not allowed to access this content"); ?>		  	
					</h5>

					<?php if(!isset(Yii::app()->session['userId'])){ ?>
						<h5 class="padding-left-10">
							<small class="letter-orange"><i class="fa fa-user-circle"></i> 
							<?php echo Yii::t("cooperation", "You are not logged"); ?>
							</small>  	
						</h5>
					<?php } ?>
					
					<h5 class="padding-left-10 letter-red">
						<small><?php echo Yii::t("cooperation", "You must be member or contributor"); ?></small>  	
					</h5>
				</div>
			<?php exit; } ?>

			<?php if(@$accessRoom=="lock"){ ?>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h5 class="padding-left-10 letter-red">
							<i class="fa fa-ban"></i> <?php echo Yii::t("cooperation", "You are not allowed to access this content"); ?>		  	
						</h5>
						
						<?php 
							$rolesLabel = "";
							if(!is_array(@$room["roles"])) $rolesLabel = @$room["roles"]; 
							else foreach (@$room["roles"] as $r) $rolesLabel .= $rolesLabel == "" ? $r : ", ".$r; 
						?>
						<h5 class="padding-left-10 letter-red">
							<small>
								<?php echo Yii::t("cooperation", "This space is open only for this roles"); ?> : 
								<?php echo Yii::t("cooperation", $rolesLabel); ?>	
							</small>  	
						</h5>
					</div>
			<?php exit; } ?>

			<ul class="menuCoop margin-bottom-50">
				
				<?php if(@$post["type"] == Proposal::CONTROLLER || @$post["type"] == Room::CONTROLLER){ ?>
					<div class="margin-top-25 title-section">
						<!-- <a href="javascript:" 
							class="pull-left open elipsis btn-hide-data-room visible-sm visible-xs" 
							style="margin-left:-10px;" data-key="proposals">
					  		<i class="fa fa-caret-down"></i>
					  	</a> -->
						<i class="fa fa-inbox"></i> 
				  		<?php echo Yii::t("cooperation", "Proposals") ?>

				  		<?php 
				  			if(@$post["type"] == Room::CONTROLLER && $auth){ 
				  		?>
							<a href="javascript:dyFObj.openForm('proposal')" class="letter-green btn-add">
						  		<i class="fa fa-plus-circle tooltips"  data-placement='top'  data-toogle='tooltips'
						  			data-original-title="<?php echo Yii::t("cooperation", "Add proposal") ?>"></i> 
						  		<span class="hidden-min hidden-sm"><?php echo Yii::t("cooperation", "Add proposal") ?></span>
						  	</a>
					  	<?php }else if(@$post["type"] == Room::CONTROLLER){ ?>
					  		<label class="text-black tooltips" 
					  			   data-position="top" data-original-title="<?php echo Yii::t("cooperation", "You must be member or contributor to contribuate"); ?>	">
					  			<i class="fa fa-lock"></i>
					  		</label>
					  	<?php } ?>

					  	<input type="text" class="inputSearchInMenuRoom pull-right form-input hidden-min hidden-xs" 
					  			data-type-search="proposals" 
					  			placeholder="<?php echo Yii::t("cooperation", "Search in proposals") ?>..." />

				  		
				  	</div>
					


					<?php if(@$proposalList){
							foreach(array("tovote", "amendable", "closed", "disabled", "resolved") as $thisStatus){ 
								foreach($proposalList as $key => $proposal){ ?>
								<?php $totalVotant = Proposal::getTotalVoters($proposal); ?>
								<?php $isAuthor = Yii::app()->session['userId'] == $proposal["creator"]; ?>
									<?php if(@$proposal["status"] == $thisStatus){ ?>
										<li class="submenucoop sub-proposals no-padding col-lg-4 col-md-6 col-sm-6 " 
											data-name-search="<?php echo str_replace('"', '', @$proposal["title"]); ?>">
										<a href="javascript:;" class="load-coop-data " data-type="proposal" 
											data-status="<?php echo @$proposal["status"]; ?>" 
										   	data-dataid="<?php echo (string)@$proposal["_id"]; ?>">
									  		
									  			<?php if((@$proposal["status"] == "amendable" || 
										  				  @$proposal["status"] == "tovote") && 
										  				  ($isAdmin || $isAuthor)){ ?>
										  			<span class="elipsis draggable" 
										  					data-dataid="<?php echo (string)@$proposal["_id"]; ?>"
											  				data-type="proposals" >
											  			<i class="fa fa-arrows-alt letter-light tooltips"  
										   					data-original-title="<?php echo Yii::t("cooperation", "Drag / drop to an other space") ?>" 
											  				data-placement="right"></i> 
											  			<i class="fa fa-hashtag"></i> 
											  			<?php if(@$proposal["title"]) 
											  					   echo @$proposal["title"]; 
											  				  else echo "<small><b>".
											  				  		substr(@$proposal["description"], 0, 150).
											  				  		   "</b></small>";
											  			?>
										  			</span>
										  			
									  		<?php }else{ ?> 
										  		<small class="elipsis"><b>
										  			<i class="fa fa-hashtag"></i> 
										  			<?php echo substr(@$proposal["description"], 0, 150); ?></b>
										  		</small>
									  		<?php } ?>
									  		
										  	<?php if(@$post["status"]) { 
										  		$parentRoom = Room::getById(@$proposal["idParentRoom"]); ?>
										  	<br>
										  	<small class="elipsis">
									  			<i class="fa fa-connectdevelop"></i> <?php echo @$parentRoom["name"]; ?>
									  		</small>
										  	<?php  } ?>

										  	<br>
										  	
										  	<small class="letter-light lbl-status">
										  		<i class="fa fa-<?php echo Cooperation::getIconCoop(@$proposal["status"]); ?>"></i> 
										  		<b><?php echo Yii::t("cooperation", @$proposal["status"]); ?></b>
										  	</small>
											
											<?php if(@$proposal["status"] == "tovote"){ ?>
											  	<small class="letter-light margin-left-10 tooltips" 
											  			data-original-title="<?php echo Yii::t("cooperation", "number of voters") ?>">
											  		<i class="fa fa-group"></i> 
											  		<?php echo $totalVotant; ?>
											  	</small>
											  	
										  	<?php } ?>
										  	<?php if(@$proposal["status"] == "amendable" || @$proposal["status"] == "tovote"){ ?>
											  	<small class="letter-light margin-left-10">
											  		<i class="fa fa-clock-o"></i> 
											  		<?php 	if(@$proposal["amendementDateEnd"] && @$proposal["status"] == "amendable")
												  				echo Yii::t("cooperation", "end") ." ".
												  				//$proposal["amendementDateEnd"];
												  				//date("Y-m-d H:i:s", $proposal["amendementDateEnd"]);
												  				Translate::pastTime($proposal["amendementDateEnd"], "date"); 

												  			else if(@$proposal["voteDateEnd"] && @$proposal["status"] == "tovote" )
												  				echo Yii::t("cooperation", "end") ." ". 
												  				Translate::pastTime($proposal["voteDateEnd"], "date"); 
											  		?>
											  	</small>
										  	<?php } ?>

									  	  	<div class="progress <?php if($proposal["status"] != "tovote") echo "hidden-min"; ?>">
									  	  		<?php 
									  	  			$voteRes = Proposal::getAllVoteRes($proposal);
										  	  		foreach($voteRes as $key => $value){ 
										  	  			if($totalVotant > 0 && $value["percent"] > 0){ 
									  	  		?>
														  <div class="progress-bar bg-<?php echo $value["bg-color"]; ?>" role="progressbar" 
														  		style="width:<?php echo $value["percent"]; ?>%">
														    <?php echo $value["percent"]; ?>%
														  </div>
											  			<?php } ?>
												<?php } ?>

											  <?php if($totalVotant == 0 && @$proposal["status"] == "tovote"){ ?>
											  			<div class="progress-bar bg-turq" 
											  				 role="progressbar" style="width:100%">
													    	 <?php echo Yii::t("cooperation", "Be the first to vote"); ?>
													  </div>
											  <?php } ?>

											  <?php if($totalVotant == 0 && @$proposal["status"] == "amendable"){ ?>
											  			<div class="progress-bar bg-lightpurple text-dark" 
											  				 role="progressbar" style="width:100%">
													    	 <?php echo Yii::t("cooperation", "Processing amendements"); ?>
													  </div>
											  <?php } ?>

											  <?php if($totalVotant == 0 && @$proposal["status"] == "closed"){ ?>
											  			<div class="progress-bar bg-white text-dark" 
											  				 role="progressbar" style="width:100%">
													    	 <?php echo Yii::t("cooperation", "No vote"); ?>
													  </div>
											  <?php } ?>

											</div> 
									  	</a>
									</li>
									<?php } //end if ?>
								<?php } //end foreach ?>
							<?php } //end foreach ?>
					<?php }else{ ?>
							<li class="submenucoop sub-proposals col-lg-12 col-md-12 col-sm-12">
								<i class="fa fa-ban margin-left-15"></i> <?php echo Yii::t("cooperation", "No proposal") ?>
							</li>
					<?php } ?>

					<hr class="col-lg-12 col-md-12 col-sm-12 no-padding margin-bottom-25">

				<?php } ?>


				<?php //var_dump($proposalList); ?>
				<?php if(@$resolutionList || @$room){ ?>
					<div class="margin-top-25 title-section col-lg-12 col-md-12 col-sm-12">
						<i class="fa fa-inbox"></i> 
				  		<?php echo Yii::t("cooperation", "Resolutions") ?>

				  		<input type="text" class="inputSearchInMenuRoom pull-right form-input hidden-min hidden-xs" 
				  				data-type-search="resolutions" 
					  			placeholder="<?php echo Yii::t("cooperation", "Search in resolution") ?>..." />
				  	</div>
					
					<?php  	if(@$resolutionList)
							foreach($resolutionList as $key => $resolution){ ?>
								<li class="submenucoop sub-resolutions no-padding col-lg-4 col-md-6 col-sm-6"
									data-name-search="<?php echo str_replace('"', '', @$resolution["title"]); ?>">
									<a href="javascript:;" class="load-coop-data" data-type="resolution" 
									   data-status="<?php echo @$resolution["status"]; ?>" 
									   data-dataid="<?php echo (string)@$resolution["_id"]; ?>">

								  		<span class="elipsis">
								  			<i class="fa fa-hashtag"></i> 
								  			<?php if(@$resolution["title"]) 
								  					   echo @$resolution["title"]; 
								  				  else echo "<small><b>".
								  				  		substr(@$resolution["description"], 0, 150).
								  				  		   "</b></small>";
								  			?>
								  		</span>
								  	</a>
								</li>
					<?php }else{ ?>
							<li class="submenucoop sub-resolutions col-lg-12 col-md-12 col-sm-12">
								<i class="fa fa-ban margin-left-15"></i> <?php echo Yii::t("cooperation", "No resolution") ?>
							</li>
					<?php } ?>

					<hr class="col-md-12 no-padding margin-bottom-25">

				<?php } ?>


				<?php if(@$post["type"] == Action::CONTROLLER || @$post["type"] == Room::CONTROLLER){ ?>
					<div class="margin-top-25 title-section col-lg-12 col-md-12 col-sm-12">
						<!-- <a href="javascript:" class="open elipsis pull-left btn-hide-data-room visible-sm visible-xs" 
							style="margin-left:-10px;" data-key="actions">
					  		<i class="fa fa-caret-down"></i>
					  	</a> -->
						<i class="fa fa-inbox"></i> 
				  		<?php echo Yii::t("cooperation", "Actions") ?>

				  		<?php if(@$post["type"] == Room::CONTROLLER && $auth){ ?>
							  	<a href="javascript:dyFObj.openForm('action')" class="letter-green btn-add">
							  		<i class="fa fa-plus-circle tooltips"
							  	  		data-placement='top'  data-toogle='tooltips'
						  				data-original-title="<?php echo Yii::t("cooperation", "Add action") ?>"></i> 
							  		<span class="hidden-min hidden-sm"><?php echo Yii::t("cooperation", "Add action") ?></span>
							  	</a>
						<?php }elseif(@$post["type"] == Room::CONTROLLER){ ?>
						  		<label class="text-black tooltips" 
						  			   data-position="top" data-original-title="Devenez membre pour contribuer">
						  			<i class="fa fa-lock"></i>
						  		</label>
						<?php } ?>

				  		<input type="text" class="inputSearchInMenuRoom pull-right form-input hidden-min hidden-xs" 
				  				data-type-search="actions" 
					  			placeholder="<?php echo Yii::t("cooperation", "Search in actions") ?>..." />
					  			
				  		
				  	</div>
					
					<?php   if(@$actionList)
							foreach($actionList as $key => $action){ ?>
								<li class="submenucoop sub-actions no-padding col-lg-4 col-md-6 col-sm-6"
									data-name-search="<?php echo str_replace('"', '', @$action["name"]); ?>">
									<a href="javascript:;" 
										class="load-coop-data" data-type="action"
										data-status="<?php echo @$action["status"]; ?>" 
								   		data-dataid="<?php echo (string)@$action["_id"]; ?>">
								  		<?php if(@$action["status"] == "todo" && $auth){ ?>
										  			<span class="elipsis draggable" 
										  					data-dataid="<?php echo (string)@$action["_id"]; ?>"
											  				data-type="actions" >
											  			<i class="fa fa-arrows-alt letter-light tooltips"  
										   					data-original-title="<?php echo Yii::t("cooperation", "Drag / drop to an other space") ?>" 
											  				data-placement="right"></i> 
											  			<i class="fa fa-hashtag"></i> 
											  			<?php echo @$action["name"]; ?>
										  			</span>
										  		<?php }else{ ?>
										  			<span class="elipsis">
											  			<i class="fa fa-hashtag"></i> 
											  			<?php echo @$action["name"]; ?>
										  			</span>
										  		<?php } ?>
								  		<?php if(@$post["status"]) { $parentRoom = Room::getById($action["idParentRoom"]); ?>
									  	<br>
									  	<small class="elipsis">
								  			<i class="fa fa-connectdevelop"></i> <?php echo @$parentRoom["name"]; ?>
								  		</small>
									  	<?php  } ?>
									  	<br>
									  	<small class="letter-light lbl-status">
									  		<i class="fa fa-pencil"></i> <b><?php echo Yii::t("cooperation", @$action["status"]); ?></b>
									  	</small>
									  	<small class="letter-light margin-left-10">
									  		<i class="fa fa-clock-o"></i> 
									  		<?php 
									  			if(@$action["endDate"])
									  				echo Yii::t("cooperation", "end") ." ". 
									  				Translate::pastTime($action["endDate"], "date"); 

									  		?>
									  	</small>
								  	</a>
								</li>
					<?php }else{ ?>
							<li class="submenucoop sub-actions col-lg-12 col-md-12 col-sm-12">
								<i class="fa fa-ban margin-left-15"></i> <?php echo Yii::t("cooperation", "No action") ?>
							</li>
					<?php } ?>

					<hr class="col-lg-12 col-md-12 col-sm-12 no-padding margin-bottom-25">

				<?php } ?>

			</ul>

		</div>

	<?php } ?>

	<div class="hidden" id="coop-data-container">
		
	</div>

</div>




<script type="text/javascript">
	var currentRoomId = "<?php echo @$room["_id"] ? $room["_id"] : ""; ?>";
	var currentRoomName = "<?php echo @$room["name"] ? $room["name"] : ""; ?>";

	console.log("currentRoomId", currentRoomId);

	jQuery(document).ready(function() { 
	
		$("#btn-edit-room").click(function(){

		});
		
		$("#btn-open-modal-delete").off().click(function(){
			$("#modalDeleteRoom #btn-delete-room").attr("data-id-room", currentRoomId);
			$("#modalDeleteRoom #space-name").html(currentRoomName);
		});

		$("#btn-edit-room").click(function(){
			var idRoom = $(this).data("id-room");
			dyFObj.editElement('rooms', idRoom);
		});

		$("#btn-delete-room").off().click(function(){
			var idRoom = $(this).data("id-room");
			uiCoop.deleteByTypeAndId("rooms", currentRoomId);
		});
		//alert("initDrag");
		//uiCoop.initDragAndDrop();

		uiCoop.initDragAndDrop();
		uiCoop.initSearchInMenuRoom();

		if(currentRoomId != ""){
			addCoopHash=".view.coop.room." + currentRoomId;
			if(typeof hashUrlPage != "undefined")
				location.hash = hashUrlPage +addCoopHash;
			else if(notNull(contextData) && typeof contextData.slug != "undefined")
				location.hash = "#" + contextData.slug + addCoopHash;
			else
				location.hash = "#page.type." + parentTypeElement + ".id." + parentIdElement +addCoopHash;
		}

	});

</script>