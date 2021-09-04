$(function(){
	'use strict';
    $.ajax({

        type : "POST",
        url  : base_url+'admin/get-states',
        data :{country_id:country,selected:state},
        success:function(data){
            data = JSON.parse(data);
            var $select = $('#state');
            $select.html(data.states);
        }
    });
    $.ajax({

        type : "POST",
        url  : base_url+'admin/get-cities',
        data :{state_id:state,selected:city},
        success:function(data){
            data = JSON.parse(data);
            var $select = $('#city');
            $select.html(data.cities);
        }
    });
	$(document).on('change','#country',function(){
		var id  = $(this).val();
		if(id.length == 0){
			id = 0;
		}

		$.ajax({

			type : "POST",
			url  : base_url+'admin/get-states',
			data :{country_id:id,selected:0},
			success:function(data){
				data = JSON.parse(data);
                var $select = $('#state');
                $select.html(data.states);
			}
		});
	});
    $(document).on('change','#state',function(){
        var id  = $(this).val();
        if(id.length == 0){
            id = 0;
        }

        $.ajax({

            type : "POST",
            url  : base_url+'admin/get-cities',
            data :{state_id:id,selected:0},
            success:function(data){
                data = JSON.parse(data);
                var $select = $('#city');
                $select.html(data.cities);
            }
        });
    });
});