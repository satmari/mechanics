<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mechanics App</title>

	<!-- <link href="{{ asset('/css/app.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('/css/css.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('/css/custom.css') }}" rel="stylesheet"> -->
	<link href="{{ asset('/css/custom.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/bootstrap.min.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/bootstrap-table.css') }}" rel='stylesheet' type='text/css'>
	<!-- <link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel='stylesheet' type='text/css'> -->
	<link href="{{ asset('/css/jquery-ui.min.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/app.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/choosen.css') }}" rel='stylesheet' type='text/css'>

	<link href="{{ asset('/css/select2.min.css') }}" rel='stylesheet' type='text/css'>

	<!-- DataTables CSS -->
	<!-- <link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel='stylesheet' type='text/css'> -->

	<link rel="manifest" href="{{ asset('/manifest.json') }}">

	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default"> 
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/') }}">Mechanics App</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					
					@if (Auth::guest())
					@else
						@if (Auth::user()->name == 'mechanics' OR Auth::user()->name == 'admin')
							<li><a href="{{ url('/') }}">Home</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Settings<span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{ url('plant') }}">Plant</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('area') }}">Area</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('location') }}">Location</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('gadget') }}">Gadget</a></li>
									<!-- <li role="separator" class="divider"></li> -->
								</ul>
							</li>
							<!-- <li><a href="{{ url('/machines_in_inteos') }}">Machines in Inteos</a></li> -->
							<li><a href="{{ url('/update_from_inteos') }}">Update machines </a></li>
							<li><a href="{{ url('/machines_table') }}">Machines table</a></li>
							<li><a href="{{ url('/advanced_search') }}">Advanced search</a></li>
							<li><a href="{{ url('/class_table') }}">Class table</a></li>
							<li><a href="{{ url('/import') }}">Import machines</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Guides<span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{ url('guides') }}">Guide table</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('guide_type_table') }}">Guide Type</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('guide_location_table') }}">Guide Locations</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('supplier_table') }}">Supplier table</a></li>
									
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Attachments<span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{ url('attachments') }}">Attachments table</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('attachment_type_table') }}">Attachments Type</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('attachment_location_table') }}">Attachment Locations</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="{{ url('supplier_table') }}">Supplier table</a></li>
									
								</ul>
							</li>
						@endif
						@if (Auth::user()->name == 'magacin')
							<li><a href="{{ url('/') }}">Home</a></li>
							<li><a href="{{ url('/machines_table') }}">Machines table</a></li>
						@endif
						@if (Auth::user()->name == 'workstudy')
							<li><a href="{{ url('/') }}">Home</a></li>
							<li><a href="{{ url('/machines_table') }}">Machines table</a></li>
						@endif

					@endif
				</ul>
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- Scripts -->
	<script src="{{ asset('/js/jquery.min.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/jquery-ui.min.js') }}" type="text/javascript" ></script>
	<!-- <script src="{{ asset('/js/jquery-2.1.1.min.js') }}" type="text/javascript" ></script>-->
    <script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/js/bootstrap-table.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/bootstrap-table-export.js') }}" type="text/javascript" ></script>

	<!-- <script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jquery.tablesorter.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/custom.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/tableExport.js') }}" type="text/javascript"></script>
	<!--<script src="{{ asset('/js/jspdf.plugin.autotable.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jspdf.min.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/FileSaver.min.js') }}" type="text/javascript" ></script>
	
	<script src="{{ asset('/js/select2.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/js/choosen.js') }}" type="text/javascript"></script>
	

	<!-- DataTables JS -->
	<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
	
	

	<script type="text/javascript">
	   $.ajaxSetup({
	       headers: {
	           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	       }
	   });
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			
			$("#select2").select2({
			  
			});
			$("#select3").select2({
			  
			});
			$("#select4").select2({
			  
			});
			$("#select5").select2({
			  
			});
			$("#select6").select2({
			  
			});
		});
	</script>



<script type="text/javascript">
$(function() {
// console.log(5 + 6);
	
	
	$(function() {

		$('.box').change(function(){
			// console.log(5 + 6);
	        var total = 0;
	        $('.box:checked').each(function(){
		    	// console.log( ($this).val());
		    	// console.log($(this).parent().parent().find('.amount').text());
		    	// 	console.log($(this).parent().parent().next('td').find('.amount').text());

	            // total=parseFloat($(this).parent().next('tr').find('.amount').text());
	            total+=parseFloat($(this).parent().parent().find('.amount').text());
	            // total+= total;

	        });
	        $('#total').text(total);
	    });
	});
 	
 	/*
	$('#item').autocomplete({
		minLength: 1,
		autoFocus: true,
		source: '{{ URL('getitemdata')}}'
	});
	$('#variant').autocomplete({
		minLength: 1,
		autoFocus: true,
		source: '{{ URL('getvariantdata')}}'
	});
	$('#batch').autocomplete({
		minLength: 1,
		autoFocus: true,
		source: '{{ URL('getbatchdata')}}'
	});
	$('#po').autocomplete({
		minLength: 3,
		autoFocus: true,
		source: '{{ URL('getpodata')}}'
	});
	$('#por').autocomplete({
		minLength: 1,
		autoFocus: true,
		source: '{{ URL('getpordata')}}'
	});
	*/

	$('#filter').keyup(function () {

        var rex = new RegExp($(this).val(), 'i');
        $('.searchable tr').hide();
        $('.searchable tr').filter(function () {
            return rex.test($(this).text());
        }).show();
	});


	// $('#myTabs a').click(function (e) {
    // 		e.preventDefault()
    // 		$(this).tab('show')
	// });
	// $('#myTabs a:first').tab('show') // Select first tab

	// $(function() {
 //    	$( "#datepicker" ).datepicker();
 //  	});
  	
	$('#sort').bootstrapTable({
    	
	});

	$(".chosen").chosen();

	// In your Javascript (external .js resource or <script> tag)
	$(document).ready(function() {
		$('.js-example-basic-single').select2();
	});

	// $(window).scroll(moveScroll);

	$("#checkAll").click(function () {
    	$(".check").prop('checked', $(this).prop('checked'));
	});
	
	$(".sortable2 ul:nth-child(2) li").each(function(index) {
  		console.log("trdt");
	});

	//$('.table tr').each(function(){
  		
  		//$("td:contains('pending')").addClass('pending');
  		//$("td:contains('confirmed')").addClass('confirmed');
  		//$("td:contains('back')").addClass('back');
  		//$("td:contains('error')").addClass('error');
  		//$("td:contains('TEZENIS')").addClass('tezenis');

  		// $("td:contains('TEZENIS')").function() {
  		// 	$(this).index().addClass('tezenis');
  		// }
	//});

	// $('.days').each(function(){
	// 	var qty = $(this).html();
	// 	//console.log(qty);

	// 	if (qty < 7 ) {
	// 		$(this).addClass('zeleno');
	// 	} else if ((qty >= 7) && (qty <= 15)) {
	// 		$(this).addClass('zuto');
	// 	} else if (qty > 15 ) {	
	// 		$(this).addClass('crveno');
	// 	}
	// });


	// $('.status').each(function(){
	// 	var status = $(this).html();
	// 	//console.log(qty);

	// 	if (status == 'pending' ) {
	// 		$(this).addClass('pending');
	// 	} else if (status == 'confirmed') {
	// 		$(this).addClass('confirmed');
	// 	} else {	
	// 		$(this).addClass('back');
	// 	}
	// });

	// $('td').click(function() {
	//    	var myCol = $(this).index();
 	//    	var $tr = $(this).closest('tr');
 	//    	var myRow = $tr.index();

 	//    	console.log("col: "+myCol+" tr: "+$tr+" row:"+ myRow);
	// });

});
</script>
<script>
  // $(document).ready(function() {

  // $("#sortable1 , #sortable2 , #sortable3 , #sortable4 , #sortable5, #sortable6, #sortable7, #sortable7, #sortable8, #sortable9" ).sortable({
  //  	connectWith: ".connectedSortable_ul_1",
  //  	dropOnEmpty: true
  //  }).disableSelection();

  //   var $tabs=$('#table-draggable2')
  //   $( "tbody.connectedSortable_table" )
  //       .sortable({
  //           connectWith: ".connectedSortable_table",
  //           // items: "> tr:not(:first)",
  //           items: "> tr",
  //           appendTo: $tabs,
  //           helper:"clone",
  //           zIndex: 999990
  //       })
  //       .disableSelection()
  //   ;
    
  //   var $tab_items = $( ".nav-tabs > li", $tabs ).droppable({
  //     accept: ".connectedSortable_table tr",
  //     hoverClass: "ui-state-hover",
      
  //     drop: function( event, ui ) {
  //       return false;
  //     }
  //   });


// })
// });

/*

function scrolify(tblAsJQueryObject, height) {
  var oTbl = tblAsJQueryObject;

  // for very large tables you can remove the four lines below
  // and wrap the table with <div> in the mark-up and assign
  // height and overflow property  
  var oTblDiv = $("<div/>");
  oTblDiv.css('height', height);
  oTblDiv.css('overflow', 'scroll');
  oTbl.wrap(oTblDiv);

  // save original width
  oTbl.attr("data-item-original-width", oTbl.width());
  oTbl.find('thead tr td').each(function() {
    $(this).attr("data-item-original-width", $(this).width());
  });
  oTbl.find('tbody tr:eq(0) td').each(function() {
    $(this).attr("data-item-original-width", $(this).width());
  });


  // clone the original table
  var newTbl = oTbl.clone();

  // remove table header from original table
  oTbl.find('thead tr').remove();
  // remove table body from new table
  newTbl.find('tbody tr').remove();

  oTbl.parent().parent().prepend(newTbl);
  newTbl.wrap("<div/>");

  // replace ORIGINAL COLUMN width				
  newTbl.width(newTbl.attr('data-item-original-width'));
  newTbl.find('thead tr td').each(function() {
    $(this).width($(this).attr("data-item-original-width"));
  });
  oTbl.width(oTbl.attr('data-item-original-width'));
  oTbl.find('tbody tr:eq(0) td').each(function() {
    $(this).width($(this).attr("data-item-original-width"));
  });
}

$(document).ready(function() {
  scrolify($('#tblNeedsScrolling'), 160); // 160 is height
});

*/
</script>
<!-- Main global filter -->
<script>
$(document).ready(function() {

    $('#filter').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#sort tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

});
</script>

<!-- Correct fixed column filters -->

<script>
$(document).ready(function() {
    try {
        // 1️⃣ Check if jQuery is loaded
        if (typeof $ === 'undefined') {
            console.error("jQuery is not loaded!");
            return;
        } else {
            console.log("✅ jQuery is loaded.");
        }

        // 2️⃣ Check if table exists
        let table = $('#sort');
        if (table.length === 0) {
            console.error("Table #sort not found!");
            return;
        } else {
            console.log("✅ Table #sort exists with", table.find('tbody tr').length, "rows.");
        }

        // 3️⃣ Check if column filters exist
        let filters = table.find('.column-filter');
        if (filters.length === 0) {
            console.error("No .column-filter inputs found!");
            return;
        } else {
            console.log("✅ Found", filters.length, "column filters.");
        }

        // 4️⃣ Bind delegated keyup for filtering
        table.on('keyup', '.column-filter', function(e) {
            try {
                let input = $(this);
                console.log("Keyup in column-filter:", input.data('column'), input.val());

                table.find('tbody tr').each(function() {
                    let row = $(this);
                    let show = true;

                    filters.each(function() {
                        let colIndex = $(this).data('column');
                        let filterValue = $(this).val().toLowerCase();
                        let td = row.find('td').eq(colIndex);
                        if(filterValue && td.length){
                            if(td.text().toLowerCase().indexOf(filterValue) === -1){
                                show = false;
                                return false; // break inner loop
                            }
                        }
                    });

                    row.toggle(show);
                });

            } catch(innerErr) {
                console.error("Error filtering row:", innerErr);
            }
        });

        console.log("✅ Column filter script initialized successfully.");

    } catch(err) {
        console.error("General script error:", err);
    }
});
</script>

</body>
</html>
