<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app/tail.select-light.min.css'); ?>">

<style type="text/css">
	#selectApp {
		width: 260px;
	}
	#role-input, #role-name-input {
		height: 33px !important;
	}
	#editSelectApp {
		height: 34px !important;
	}

</style>


<div class="col-sm-9">
	<div class="main-content">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb shadow-sm bg-light">
				<li class="breadcrumb-item"><a href="<?php echo base_url('user/list'); ?>">Users</a></li>
				<li class="breadcrumb-item active" aria-current="page">roles</li>
			</ol>
		</nav>
		<ul class="nav justify-content-end pb-3">
			<li class="nav-item mr-2">
				<button id="add-role" class="btn-tools btn-tools-height" type="button" class="btn btn-outline-dark">
					<i class="fas fa-plus text-darker"></i>
				</button>
			</li>
			<li class="nav-item">
				<div class="input-group">
					<input id="user-search" type="text" class="form-control btn-tools-height" placeholder="User search">
					<div class="input-group-append">
						<button id="icon-user-search" class="btn btn-light btn-tools-height border" type="button">
							<i class="fad fa-search iconsize3"></i>
						</button>
					</div>
				</div>
			</li>
		</ul>
		<table class="table table-hover">
		    <thead class="">
		        <tr>
		            <th>Role</th>
		            <th>Application</th>
		            <th>Active User</th>
		            <th class="text-right"><em class="fa fa-cog text-info"></em></th>
		        </tr>
		    </thead>
		    <tbody id="usersx">
		    </tbody>
		</table>
		<div class="pagelinks"></div>
	</div>
</div>

<!-- Model add new role -->
<div class="modal fade" id="addrole" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold text-muted"><i class="fas fa-user-cog iconsize2"></i></h5>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true"><i class="far fa-times-square text-darker"></i></span>
					<span class="sr-only">Close</span>
				</button>
			</div>
			<div class="modal-body">   
				<form>
					<div class="form-row">
						<div class="col-md-6 mb-3">
							<label for="role-input">Role</label>
						<input type="text" class="form-control" id="role-input" placeholder="Enter role">
						</div>
						<div class="col-md-6 mb-3">
							<label for="role-input">Role name</label>
							<input type="text" class="form-control" id="role-name-input" placeholder="Enter role name">
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6 mb-3">
							<div class="form-group">
								<label for="selectApp">Application</label>
								<br>
								<select id="selectApp" single>
							        <option value="1">SSO</option>
									<option value="2">APPFOTO</option>
									<option value="3">AKANG</option>
									<option value="4">ADEK</option>
								</select>
							</div>
						</div>
						<div class="col-md-6 mb-3">
							<div class="form-group">
								<label for="selectPerms">Permissions</label>
								<br>
								<select id="selectPerms" multiple></select>
							</div>
						</div>
					</div>
				</form>

				<div class="card">
					<div class="card-body">
						<h6>Petunjuk dan Informasi Menambah Roles</h6>
						<div class="container">
							<div class="row">
								<div class="col-md-12">
									<ol>
										<li>Field-field yang <u>harus diisi</u>: <b>Role</b>, <b>Role Name</b>, <b>Aplication</b>.</li>
										<li>Field Role <u>harus unik</u>, belum ada di database.</li>
										<li>Karakter Role <u>tidak boleh ada spasi, bila lebih dari 1 suku kata, hubungkan antar kata dengan tanda underscore (_)</u>.</li>
										<li>Field Role Name <u>tidak harus unik, boleh menggunakan spasi, harus singkat dan jelas</u>.</li>
										<li>Pilih salah satu aplikasi.</li>
										<li>List permissions otomatis di-load sesuai pilihan aplikasi.</li>
										<li>Pastikan semua isian valid, kemudian klik button <b>Submit</b>.</li>
									</ol>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button id="role-submit" type="submit" class="global-submit-btn btn btn-primary">Submit</button>
			</div>
		</div>
	</div>
</div>


<!-- Model add new role -->
<div class="modal fade" id="editrole" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold text-muted"><i class="fas fa-user-cog text-info"></i></h5>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true"><i class="far fa-times-square text-darker"></i></span>
					<span class="sr-only">Close</span>
				</button>
			</div>
			<div class="modal-body">   
				<form>
					<div class="form-row">
						<div class="col-md-6 mb-3">
							<label for="role-input">Role</label>
							<input type="text" class="form-control" id="edit-role-input">
							<input type="hidden" id="rid">
						</div>
						<div class="col-md-6 mb-3">
							<label for="role-input">Role name</label>
							<input type="text" class="form-control" id="edit-role-name-input">
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6 mb-3">
							<label for="editSelectApp">Application</label>
							<input type="text" class="form-control" id="editSelectApp" disabled="disabled">
							<input type="hidden" id="aid">
						</div>
						<div class="col-md-6 mb-3">
							<div class="form-group">
								<label for="editSelectPerms">Permissions</label>
								<br>
								<select id="editSelectPerms" multiple></select>
							</div>
						</div>
					</div>
				</form>

				<div class="card">
					<div class="card-body">
						<h6>Petunjuk dan Informasi Edit Roles</h6>
						<div class="container">
							<div class="row">
								<div class="col-md-12">
									<ol>
										<li>Field-field yang <u>harus diisi</u>: <b>Role</b>, <b>Role Name</b>, <b>Aplication</b>.</li>
										<li>Field Role <u>harus unik</u>, belum ada di database.</li>
										<li>Karakter Role <u>tidak boleh ada spasi, bila lebih dari 1 suku kata, hubungkan antar kata dengan tanda underscore (_)</u>.</li>
										<li>Field Role Name <u>tidak harus unik, boleh menggunakan spasi, harus singkat dan jelas</u>.</li>
										<li>Pilih salah satu aplikasi.</li>
										<li>List permissions otomatis di-load sesuai pilihan aplikasi.</li>
										<li>Pastikan semua isian valid, kemudian klik button <b>Submit</b>.</li>
									</ol>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button id="edit-role-submit" type="submit" class="global-submit-btn btn btn-primary">Submit</button>
			</div>
		</div>
	</div>
</div>


