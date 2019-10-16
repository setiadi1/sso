<script src="<?php echo base_url('assets/app/tail.select-full.min.js'); ?>"></script>

<script>
	$(document).ready(function() {
		$('.pagelinks').on('click', 'a', function (e) {
			e.preventDefault();
			var pagenum = $(this).attr('data-ci-pagination-page');
			loadUsers(pagenum);
		});
		
		loadUsers(0);

		function loadUsers(pagenum) {
			$.ajax({
				url: '<?=base_url()?>user/load_roles/' + pagenum,
				type: 'get',
				dataType: 'json',
				beforeSend: function() {
					showLoader();
				},
				success: function (response) {
					$('.pagelinks').html(response.pagination);
					showUsersInfo(response.result, response.row);
				}
			});
		}

		function showLoader() {
			$('#usersx').empty();
			$('#usersx').html("<div class='mt-2 ml-2'>Loading...</div>");
		}

		function showUsersInfo(result, num) {
			$('#usersx').empty();
			num = Number(num);
			$.each(result, function(key, value) {
				num += 1;
				var tr = "";
				tr 	+= "<tr>" +
					"<td class='align-middle'>" + value.role_name + "</td>" +
					"<td class='align-middle'>" + value.app_name + "</td>" +
					"<td class='align-middle'>" + value.active_users + "</td>" + 
					"<td align='align-middle' class='text-right'>" +
						"<div class='btn-group dropdown'>";
							if (value.status === '1') {
								tr += "<button type='button' class='btn btn-outline-dark btn-success dropdown-toggle pl-1 pr-1 pt-0 pb-0' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='far fa-ellipsis-h'></i></button>";
							}
							if (value.status === '0') {
								tr += "<button type='button' class='btn btn-outline-dark btn-danger dropdown-toggle pl-1 pr-1 pt-0 pb-0' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='far fa-ellipsis-h'></i></button>";
							}
							tr += "<div class='dropdown-menu'>";
								if (value.status === '1') {
									tr += "<a class='dropdown-item disablerole' href='#' rid="+value.role_id+"><i class='fas fa-user-times iconsize1 text-danger'></i> Disable</a>";
								}
								if (value.status === '0') {
									tr += "<a class='dropdown-item enablerole' href='#' rid="+value.role_id+"><i class='fas fa-user-tag iconsize1 text-success'></i> Enable</a>";
								}
								tr += "<a class='dropdown-item editrole' href='#' rid="+value.role_id+" aid="+value.app_id+"><i class='fas fa-user-edit iconsize1 text-info'></i> Edit</a>" +
								"<div class='dropdown-divider'></div>" +
								"<a class='dropdown-item text-danger deleterole' href='#'><i class='far fa-trash-alt iconsize1'></i>&nbsp; Delete</a>" +
							"</div>" +
						"</div>" +
					"</td>" +
				"</tr>";
				$('#usersx').append(tr);
			})
		}

		$(document).on('click', '.disablerole', function(e) {
			e.preventDefault();
			var rid = $(this).attr('rid');
			$.ajax({
				url: '<?=base_url()?>user/disablerole',
				type: 'post',
				data: 'rid=' + rid,
				success: function (response) {
					console.log(response);
				}
			});
			$(this).parent().prev().removeClass('btn-success').addClass('btn-danger');
			$(this).removeClass('disablerole').addClass('enablerole');
			$(this).html("<i class='fas fa-user-tag text-success'></i> Enable");
		});

		$(document).on('click', '.enablerole', function(e) {
			e.preventDefault();
			var rid = $(this).attr('rid');
			$(this).parent().prev().removeClass('btn-danger').addClass('btn-success');
			$(this).removeClass('enablerole').addClass('disablerole');
			$(this).html("<i class='fas fa-user-tag text-danger'></i> Disable");
		});


		$('#add-role').on('click', function(e) {
			e.preventDefault();
			$('#addrole').modal('show');
		});

		$('#role-submit').on('click', function(e) {
			e.preventDefault();
			var role = $('#role-input').val();
			var role_name = $('#role-name-input').val();
			var aid = $('#selectApp').val();
			var perms = $("#selectPerms").val();

			if (role === '' || role_name === '' || aid === null) {
				console.log('empty..');
				return false;
			}
			if (role !== '' && role_name !== '') {
				var datasend = 'role=' + role + '&role_name=' + role_name + '&aid=' + aid + '&perms=' + perms;
				
				$.ajax({
					url: '<?=base_url()?>user/add_role',
					type: 'post',
					data: datasend,
					success: function (response) {
						console.log(response);
					}
				});
			}
		});

		var selectPerms = tail.select("#selectPerms", {
            search: true,
            deselect: true,
            placeholder: 'Select permissions',
            multiSelectAll: true
        });

  		// selectPerms.on("close", function() {
		//     for(var l = this.options.selected.length, i = 0; i < l; i++){
		//         var x = this.options.selected[i].value;
		//     }
		// });

		var SelectApp = tail.select("#selectApp", {
            search: true,
            deselect: true,
            placeholder: 'Select an application'
        });

        SelectApp.on("close", function() {
		    for(var l = this.options.selected.length, i = 0; i < l; i++) {
		        var x = this.options.selected[i].value;
		        $.ajax({
					url: '<?=base_url()?>user/permis_by_aid',
					type: 'post',
					dataType: 'json',
					data: 'aid=' + x,
					success: function(response) {
			            // unselect all
						selectPerms.options.all("unselect");
        				selectPerms.options.handle("select", "all", "#", true);
        				// selectPerms.options.add(response);
        				selectPerms.config("items", response);
					}
				});
		    }
		});

		// Edit roles
        var editSelectPerms = tail.select("#editSelectPerms", {
            search: true,
            placeholder: 'Select permissions',
            multiSelectAll: true
        });

		$(document).on('click', '.editrole', function(e) {
			e.preventDefault();
			var rid = $(this).attr('rid');
			var aid = $(this).attr('aid');
			// console.log('rid=' + rid + ', ' + 'aid=' + aid);
			$('#editrole').modal('show');

			// load permissions
			$.ajax({
				url: '<?=base_url()?>user/edit_role1',
				type: 'post',
				dataType: 'json',
				data: 'aid=' + aid + '&rid=' + rid,
				success: function(response) {
					// console.log(response);
		            // unselect all
					editSelectPerms.options.all("unselect");
    				editSelectPerms.options.handle("select", "all", "#", true);
    				editSelectPerms.config("items", response);
				},
				error: function(response) {
					// console.log(response);
					console.log('Error!');
				}
			});

			// load apps & roles
			$.ajax({
				url: '<?=base_url()?>user/edit_role2',
				type: 'post',
				dataType: 'json',
				data: 'aid=' + aid + '&rid=' + rid,
				success: function(response) {
					$("#edit-role-input").val(response[0].role);
					$("#edit-role-name-input").val(response[0].role_name);
					$("#editSelectApp").val(response[0].app_name);
					$("#aid").val(response[0].app_id);
					$("#rid").val(response[0].role_id);
				},
				error: function(response) {
					console.log('Error!');
				}
			});
		});

		$('#edit-role-submit').on('click', function(e) {
			e.preventDefault();
			var rid = $('#rid').val();
			var role = $('#edit-role-input').val();
			var role_name = $('#edit-role-name-input').val();
			var aid = $('#aid').val();
			var app_name = $('#editSelectApp').val();
			var perms = $('#editSelectPerms').val();
			if (role === '' || role_name === '' || aid === null) {
				console.log('empty..');
				return false;
			}
			if (role !== '' && role_name !== '') {
				var datasend = 'role=' + role + '&role_name=' + role_name + '&rid=' + rid + '&aid=' + aid + '&perms=' + perms + '&app_name=' + app_name;
				$.ajax({
					url: '<?=base_url()?>user/edit_role3',
					type: 'post',
					dataType: 'json',
					data: datasend,
					success: function (response) {
						console.log(response);
					}
				});
			}
		});
	});

</script>