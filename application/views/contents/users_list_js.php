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
				url: '<?=base_url()?>user/load_users/' + pagenum,
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
					"<td class='align-middle'>" + value.username + "</td>" +
					"<td class='align-middle'>" + value.name + "</td>" +
					"<td class='align-middle'>" + value.nip + "</td>";
					
					if (value.status === 'TRUE') {
						tr += "<td class='align-middle text-success'>" + value.status + "</td>";
					}

					if (value.status === 'FALSE') {
						tr += "<td class='align-middle text-danger'>" + value.status + "</td>";
					}

					tr += "<td align='align-middle' class='text-right'>" +
						"<div class='btn-group dropdown'>" +
							"<button type='button' class='btn btn-outline-dark btn-light dropdown-toggle pl-1 pr-1 pt-0 pb-0' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='far fa-ellipsis-h'></i></button>" +
							"<div class='dropdown-menu'>" +
								"<a class='dropdown-item disableuser' href='#'><i class='fas fa-user-times iconsize1'></i> Disable</a>" +
								"<a class='dropdown-item userroles' href='#' uid="+value.user_id+"><i class='fas fa-user-tag iconsize1'></i> Set Roles</a>" +
								"<a class='dropdown-item edituser' href='#'><i class='fas fa-user-edit iconsize1'></i> Edit</a>" +
								"<div class='dropdown-divider'></div>" +
								"<a class='dropdown-item text-danger deleteuser' href='#'><i class='far fa-trash-alt iconsize1'></i>&nbsp; Delete</a>" +
							"</div>" +
						"</div>" +
					"</td>" +
				"</tr>";
				$('#usersx').append(tr);
			})
		}

		var SelectApp = tail.select("#select-apps", {
            search: true,
            deselect: true,
            placeholder: 'Select an application'
        });

        var SelectRoles = tail.select("#select-roles", {
            search: true,
            // deselect: true,
            placeholder: 'Select roles',
            multiSelectAll: true
        });

        SelectApp.on("close", function() {
		    for(var l = this.options.selected.length, i = 0; i < l; i++) {
		        var x = this.options.selected[i].value;
		        console.log(x);
		        var user_id = $("#userid").val();
		        $.ajax({
					url: '<?=base_url()?>user/user_roles2',
					type: 'post',
					dataType: 'json',
					data: 'aid=' + x + '&user_id=' + user_id,
					success: function(response) {
						console.log(response);
						SelectRoles.options.all("unselect");
        				SelectRoles.options.handle("select", "all", "#", true);
        				SelectRoles.config("items", response);
					}
				});
		    }
		});

		$(document).on('click', '.userroles', function(e) {
			e.preventDefault();
			$("#modal_add_roles").modal("show");
			var uid = $(this).attr('uid');
			SelectRoles.options.all("unselect");
    		SelectRoles.options.handle("select", "all", "#", true);
			// load all apps
			$.ajax({
				url: '<?=base_url()?>user/user_roles1',
				type: 'post',
				dataType: 'json',
				data: 'uid=' + uid,
				success: function(response) {
					SelectApp.options.all("unselect");
    				SelectApp.options.handle("select", "all", "#", true);
    				SelectApp.config("items", response);
				},
				error: function(response) {
					console.log('Error!');
				}
			});
			// load data user
			$.ajax({
				url: '<?=base_url()?>user/user_roles3',
				type: 'post',
				dataType: 'json',
				data: 'uid=' + uid,
				success: function(response) {
					$("#username").val(response[0].username);
					$("#name").val(response[0].name);
					$("#nip").val(response[0].nip);
					$("#userid").val(response[0].user_id);
				},
				error: function(response) {
					console.log('Error!');
				}
			});
		});

		$("#roles-submit").on("click", function(e) {
			e.preventDefault();
			var uid = $("#userid").val();
			var username = $("#username").val();
			var password = $("#password").val();
			var nip = $("#nip").val();
			var name = $("#name").val();
			var aid = $("#select-apps").val();
			var roles = $("#select-roles").val();
			var datax = 'uid=' + uid + '&username=' + username + '&password=' + password + '&name=' + name + '&nip=' + nip + '&aid=' + aid + '&roles=' + roles;
			// console.log();
			if (uid === '' || username === '' || nip === '' || name === '' || SelectApp.options.selected.length === 0) {
				console.log('empty..');
				return false;
			}
			$.ajax({
				url: '<?=base_url()?>user/set_roles',
				type: 'post',
				dataType: 'json',
				data: datax,
				success: function (response) {
					console.log(response);
				}
			});
		});
	});

</script>