dynForm = {
    jsonSchema : {
	    title : typeObj.filter.title,
	    icon : typeObj.filter.title,
	    debug:true,
      save : function () { 
        //alert("filter save "+dyFObj.activeModal);
        if( typeof typeObj.network.filters == "undefined" )
          typeObj.network.filters = [];
        var formData = $("#openModal #ajaxFormModal").serializeFormJSON();
        if(formData.key){
            delete formData.key;
            delete formData.collection;
            delete formData.id;
        }
        formData.tags = formData.tags.split(",");
          
        typeObj.network.filters.push( formData );
        //mylog.log(typeObj.network.filters);
        $(".filterList").html("");
        $.each(typeObj.network.filters,function(k,v) { 
          $(".filterList").append(v.name+" <a href='javascript:;' onclick='typeObj.network.dynForm.removeFilter("+k+")'><i class='fa fa-times text-red'></i> </a><br/>");
        })
        $("#openModal").modal("hide");
        dyFObj.activeModal = "#ajax-modal";
        dyFObj.activeElem = "elementObj";
      },
	    properties : {
            info : {
                inputType : "custom",
                html:"<p class='text-red'>Les Filtres controle le menu de gauche et les tags que vous voulez présenter aux utilisateurs<hr></p>",
            },
            name : dyFInputs.name(),
            tags : dyFInputs.tags()
	    }
	}
};