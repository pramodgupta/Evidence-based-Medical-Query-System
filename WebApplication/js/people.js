$(function(){	
	
	getUsers('faculty');
	$("#facultyTabLabel").click(function(e) {
        getUsers('faculty');
    });
	$("#staffTabLabel").click(function(e) {
        getUsers('staff');
    });
	$("#adminTabLabel").click(function(e) {
        getUsers('admin');
    });
	
	if($("#bio").length > 0){
	   $("#bio").ckeditor();	
	}	
	
	$('form').validation({
	   required:[
	     {
	        name:'firstName'
		 }
	   
	   ]
	});
	
});

function getUsers(role){
	var res = $.ajax({
	                  type:"POST",
			          url:"cfc/people.cfc",
			          beforeSend: function(){
                          $("#loading").show();
                      },
                      complete: function(){
                          $("#loading").hide();
                      },
			          data:{method:'getusernames',role:role},
				      success: function(res){ 
					  res = $.parseJSON(res);
						   var htmlInsert = "";
						   $.each(res.DATA, function(index,item) {        
							  htmlInsert += "<tr>"+
							                     "<td>" + item[1] + " " + item[2] + "</td>" +
												 "<td>" + item[3] + "</td>" +
												 "<td><div class='small metro rounded btn secondary'><a href='people.cfm?view="+item[0]+"'>VIEW</a></div></td>" +
												 "<td><div class='small metro rounded btn warning'><a href='people.cfm?edit="+item[0]+"'>EDIT</a></div></td>" +
												 "<td><div class='small metro rounded btn danger'><a href='people.cfm?del="+item[0]+"'>DELETE</a></div></td>" +
							                 "</tr>";
                           });
						   $("#"+role+"Data").html(htmlInsert);
						   
				      }
	
	});
}