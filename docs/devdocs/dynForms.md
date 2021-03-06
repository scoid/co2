# DynForm : Dynamic Formulares
> builds forms on the fly based on a json opbject description
> many input types are available 

## dyF 
> List of methods used to manipulate dynForms 

## dyF.specs
> Any dynFormForm definition 
- onLoad methods 
- init implementations
- 

## dyF.inputs
> is a generic list of types definitions
anyone can neverthe less create their own definition

## type : image 
Upload Process in dynforms 
	- uses (FineUploader)[https://fineuploader.com/] instance  
	-generic definition
	```
	dyF.inputs.image :function(str) { 
	    	gotoUrl = (str) ? str : location.hash;
	    	return {
		    	inputType : "uploader",
		    	label : "Images de profil et album", 
		    	afterUploadComplete : function(){
			    	dyFObj.closeForm();
			    	//alert(gotoUrl+uploadObj.id);
		            urlCtrl.loadByHash( gotoUrl+uploadObj.id );	
			    	}
	    	}
	    },
	```
	- uses 
	```
	var uploadObj = {
		type : null,
		id : null,
		folder : "communecter", //on force pour pas casser toutes les vielles images
		set : function(type,id){
			uploadObj.type = type;
			uploadObj.id = id;
		}
	};
	```
	- this information is generated when open the dynForm 
	```
	beforeBuild : function(){
		    	dyFObj.setMongoId('classified');
		    },

	//this launches the image upload process 
	afterSave : function(){
				if( $('.fine-uploader-manual-trigger').fineUploader('getUploads').length > 0 )
			    	$('.fine-uploader-manual-trigger').fineUploader('uploadStoredFiles');
			    else {
			    	dyFObj.closeForm();
			    	urlCtrl.loadByHash( location.hash );	
			    }
		    },
	```
	- the fineUploader.uploadStoredFiles event 
	```
	request: {
	    endpoint: baseUrl+"/"+moduleId+"/document/uploadSave/dir/"+uploadObj.folder+"/folder/"+uploadObj.type+"/ownerId/"+uploadObj.id+"/input/qqfile"
    //params : uploadObj
},

- Controller : ctk/document/uploadSaveAction 
	- prepares folders
	- checks size and extension
	- returns path
	```
	Document::checkFileRequirements($file, $dir, $folder, $ownerId, $input);
	```
	
- uploads to corresponding folder
	```
	Document::uploadDocument($file, $res["uploadDir"],$input,$rename);
	add COLLECTION generateProfilImages::$allowedElements
	```
	
- saves to DB document collection 
	* can canEdit permissions :: Authorisation::canEditItem ($type)
	* add the the COLLECTION canEdit test 
	```
	Document::save($params)
	```

	-errors : 
	```
	Vous n'êtes pas autorisé à modifier et/ou ajouter un document ici
	```
