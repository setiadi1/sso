<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/app/tail.select-light.min.css'); ?>">
<div class="col-sm-9">
	<div class="main-content">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb shadow-sm bg-light">
				<li class="breadcrumb-item"><a href="<?php echo base_url('user/list'); ?>">Users</a></li>
				<li class="breadcrumb-item active" aria-current="page">list</li>
			</ol>
		</nav>
		<ul class="nav justify-content-end pb-3">
			<li class="nav-item mr-2">
				<button id="add_roles" class="btn-tools btn-tools-height" type="button" class="btn btn-outline-dark">
					<i class="fas fa-user-plus text-info"></i>
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
		            <th>Username</th>
		            <th>Name</th>
		            <th>NIP</th>
		            <th>Status</th>
		            <th class="text-right"><em class="fa fa-cog text-info"></em></th>
		        </tr>
		    </thead>
		    <tbody id="usersx">
		    </tbody>
		</table>
		<div class="pagelinks"></div>
	</div>
</div>

<div class="modal fade" id="modal_add_roles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
							<label for="name">Username</label>
							<input type="text" class="form-control" id="username" placeholder="Enter name">
							<input type="hidden" id="userid">
						</div>
						<div class="col-md-6 mb-3">
							<label for="nip">Reset Password</label>
							<input type="text" class="form-control" id="password" placeholder="Enter new password">
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-6 mb-3">
							<label for="name">Name</label>
						<input type="text" class="form-control" id="name" placeholder="Enter name">
						</div>
						<div class="col-md-6 mb-3">
							<label for="nip">NIP</label>
							<input type="text" class="form-control" id="nip" placeholder="Enter NIP">
						</div>
					</div>

					<div class="form-row">
						<div class="col-md-6 mb-3">
							<div class="form-group">
								<label for="select-apps">Application</label>
								<br>
								<select id="select-apps" single></select>
							</div>
						</div>
						<div class="col-md-6 mb-3">
							<div class="form-group">
								<label for="select-roles">Roles</label>
								<br>
								<select id="select-roles" multiple></select>
							</div>
						</div>
						
					</div>
				</form>

				<div class="card">
					<div class="card-body">
						<h6>Petunjuk Edit Permission</h6>
						<div class="container">
							<div class="row">
								<div class="col-md-12">
									<!-- <ol>
										<li>Field-field yang <u>harus diisi</u>: <b>permission</b>, <b>permission name</b>, <b>permission group</b>, <b>aplication</b>.</li>
										<li>Field <u>permission</u> <u>harus unik</u>, belum ada di database.</li>
										<li>Karakter field permission <u>tidak boleh ada spasi, bila lebih dari 1 suku kata, hubungkan antar kata dengan tanda underscore (_)</u>.</li>
										<li>Karakter field permission name <u>tidak harus unik, boleh menggunakan spasi, harus singkat dan jelas</u>.</li>
										<li>Karakter field permission group <u>tidak harus unik, bila lebih dari 1 suku kata, hubungkan antar kata dengan tanda underscore (_)</u>.</li>
										<li>Field aplikasi bisa diedit hanya apabila status permission tidak digunakan oleh role.</li>
										<li>Pastikan semua isian valid, kemudian klik button <b>Submit</b>.</li>
									</ol> -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button id="roles-submit" type="submit" class="global-submit-btn btn btn-primary">Submit</button>
			</div>
		</div>
	</div>
</div>
	

	
