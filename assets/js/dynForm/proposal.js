dynForm = {
    jsonSchema : {
	    title : "Faire une proposition",
	    icon : "gavel",
	    type : "object",
	    onLoads : {
	    	
	    	sub : function(){
	    		$("#ajax-modal .modal-header").removeClass("bg-dark bg-purple bg-red bg-azure bg-green bg-green-poi bg-orange bg-yellow bg-blue bg-turq bg-url")
						  					  .addClass("bg-dark");
    		 	
    		 	
	    		dataHelper.activateMarkdown("#ajaxFormModal #message");
    			$("#ajaxFormModal #survey").val( contextDataDDA.id );
    			if (typeof contextDataDDA.name != "undefined" && contextDataDDA.name != "")
    		 	$("#ajax-modal-modal-title").html($("#ajax-modal-modal-title").html()+" dans :<br><small class='text-white'>"+contextDataDDA.name+"</small>" );
	    	}
	    },
	    beforeBuild : function(){
           /* dyFObj.setMongoId('survey',function(){
            	
            });*/
        },
	    afterSave : function(){
            if( $('.fine-uploader-manual-trigger').length &&  $('.fine-uploader-manual-trigger').fineUploader('getUploads').length > 0 )
                $('.fine-uploader-manual-trigger').fineUploader('uploadStoredFiles');
            else 
            { 
                dyFObj.closeForm(); 
                uiCoop.getCoopData(contextData.type, contextData.id, "proposal");
                //urlCtrl.loadByHash( (uploadObj.gotoUrl) ? uploadObj.gotoUrl : location.hash );
            }
	    },
	    properties : {
	    	info : {
                inputType : "custom",
                html:"<br><p><i class='fa fa-info-circle'></i> Une proposition sert à discuter et demander l'avis d'une communauté sur une idée ou une question donnée</p>",
            },
	        id : dyFInputs.inputHidden(),
	        idParentRoom : dyFInputs.inputHidden(currentRoomId),
            /*idParentRoom :{
            	inputType : "select",
            	label : "Choisir un espace",
            	init : function(){
            		if( userId )
            		{
            			/*filling the seclect* /
	            		if(notNull(window.myVotesList)){
	            			html = buildSelectGroupOptions( window.myVotesList);
	            			$("#survey").append(html); 
	            		} else {
	            			getAjax( null , baseUrl+"/" + moduleId + "/rooms/index/type/citoyens/id/"+userId+"/view/data/fields/votes" , function(data){
	            			    window.myVotesList = {};
	            			    $.each( data.votes , function( k,v ) 
	            			    { 
	            			    	parentName = "";
		            			    if(!window.myVotesList[ v.parentType]){
		            			    	var label = ( v.parentType == "cities" && cpCommunexion && v.parentId.indexOf(cpCommunexion) ) ? cityNameCommunexion : v.parentType;
		            			    	window.myVotesList[ v.parentType] = {"label":label};
		            			    	window.myVotesList[ v.parentType].options = {}
		            			    } /*else{
		            			    	//if(notNull(myContactsById[v.parentType]) && notNull(myContactsById[v.parentType][v['_id']['$id']]))
		            			    	//parentName = myContactsById[v.parentType][v['_id']['$id']].name;
		            			    }* /
	            			    	window.myVotesList[ v.parentType].options[v['_id']['$id'] ] = v.name+parentName; 
	            			    }); 
	            			    //run through myContacts to fill parent names 
	            			    mylog.dir(window.myVotesList);
	            			    
	            			    html = buildSelectGroupOptions(window.myVotesList);
								$("#survey").append(html);
								if(contextDataDDA && contextDataDDA.id)
									$("#ajaxFormModal #survey").val( contextDataDDA.id );
						    } );
	            		}
	            		/*$("#survey").change(function() { 
	            			mylog.dir( $(this).val().split("_"));
	            		});* /

            		}
            	},
            	//custom : "<br/><span class='text-small'>Une thématique est un espace de décision lié à une ville, une organisation ou un projet <br/>Vous pouvez créer des espaces coopératifs sur votre commune, organisation et projet</span>"
            },*/
            title : dyFInputs.name("vote"),
            description : dyFInputs.textarea(tradDynForm.longDescription, "..."),
            amendementActivated : dyFInputs.inputHidden( true ),
            amendementDateEnd : dyFInputs.amendementDateEnd,
            voteActivated : dyFInputs.inputHidden( true ),
            voteDateEnd : dyFInputs.voteDateEnd,
            tags : dyFInputs.tags(),
            //image : dyFInputs.image(),
            urls : dyFInputs.urls,
            //email: dyFInputs.inputHidden( ( (userId!=null && userConnected!=null) ? userConnected.email : "") ),
            idUserAuthor : dyFInputs.inputHidden( ( (userId!=null && userConnected!=null) ? userId : "") ),
            status: dyFInputs.inputHidden( "amendable" ),
            canModify: dyFInputs.inputHidden( true ),
            //organizer : dyFInputs.inputHidden("currentUser"),
            //type : dyFInputs.inputHidden("entry")
                        
	    }
	}
};