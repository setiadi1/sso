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
				url: '<?=base_url()?>user/load_permissions/' + pagenum,
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
					"<td class='align-middle'>" + value.perm_name + "</td>" +
					"<td class='align-middle'>" + value.app_name + "</td>";
					
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
								"<a class='dropdown-item editpermission' href='#' pid="+value.perm_id+"><i class='fas fa-user-edit iconsize1'></i> Edit</a>" +
								"<div class='dropdown-divider'></div>" +
								"<a class='dropdown-item text-danger deleteuser' href='#'><i class='far fa-trash-alt iconsize1'></i>&nbsp; Delete</a>" +
							"</div>" +
						"</div>" +
					"</td>" +
				"</tr>";
				$('#usersx').append(tr);
			})
		}

		$('#btn_add_permission').on('click', function(e) {
			e.preventDefault();
			$('#modal_add_permission').modal('show');
		});

		var SelectApp = tail.select("#selectApp", {
            search: true,
            deselect: true,
            placeholder: 'Select an application'
        });

        $('#permission-submit').on('click', function(e) {
			e.preventDefault();
			var perm = $('#permission-input').val();
			var perm_name = $('#permission-name-input').val();
			var perm_group = $('#permission-group').val();
			var aid = $("#selectApp").val();

			if (perm === '' || perm_group === '' || perm_group === '' || aid === null) {
				console.log('empty..');
				return false;
			}
			if (perm !== '' && perm_group !== '' && perm_group !== '' && aid !== null) {
				var datasend = 'perm=' + perm + '&perm_name=' + perm_name + '&perm_group=' + perm_group + '&aid=' + aid;
				$.ajax({
					url: '<?=base_url()?>user/add_permission',
					type: 'post',
					data: datasend,
					success: function (response) {
						console.log(response);
					}
				});
			}
		});

		var EditSelectApp = tail.select("#editSelectApp", {
            search: true,
            placeholder: 'Select an application'
        });

		$(document).on('click', '.editpermission', function(e) {
			e.preventDefault();
			$('#modal_edit_permission').modal('show');

			var pid = $(this).attr('pid');

			$.ajax({
				url: '<?=base_url()?>user/edit_perm1',
				type: 'post',
				dataType: 'json',
				data: 'pid=' + pid,
				success: function(response) {
					// console.log(response);
					var datax = [];
					$.each(response, function(key, value) {
						if (value.selected == true) {
							datax = value.data;
						}
					});
					console.log(datax);
					$.each(datax, function(key, value) {
						if (value.perm_id === pid) {
							$("#edit-permission-input").val(value.perm);
							$("#edit-permission-name-input").val(value.perm_name);
							$("#edit-permission-group").val(value.perm_group);
						}
					});
					EditSelectApp.options.all("unselect");
    				EditSelectApp.options.handle("select", "all", "#", true);
    				EditSelectApp.config("items", response);
				},
				error: function(response) {
					// console.log(response);
					console.log('Error!');
				}
			});
		});

		$("#edit-permission-submit").on("click", function(e) {
			e.preventDefault();
			console.log('1');
		});
	});

</script>