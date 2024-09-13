<?= $this->extend('backend/template/template'); ?>
<?= $this->section('content'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?= $title; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><?= $title; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-5">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" id="dt-img" src="" alt="User profile picture">
                        </div>
                        <h3 id="dt-nama" class="profile-username text-center"></h3>
                        <h6 id="dt-nik" class="text-muted text-center"></h6>
                        <h6 id="dt-role" class="text-muted text-center"></h6>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Username</b> <a id="dt-username" class="float-right"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a id="dt-email" class="float-right"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Telepon</b> <a id="dt-tlp" class="float-right"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Acount Created</b> <a id="created" class="float-right"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Acount Updated</b> <a id="updated" class="float-right"></a>
                            </li>
                        </ul>
                        <div class="row">
                            <div class="col-sm-6">
                                <button id="edit-prof" type="button" class="btn btn-outline-success float-left">Edit Profile</button>
                            </div>
                            <div class="col-sm-6">
                                <button id="change-pass" type="button" class="btn btn-outline-info float-right">Change Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="view-modal"></div>

<?= $this->endSection('content'); ?>
<?= $this->section('scripts'); ?>
<script src="<?= base_url('assets/js/backend/dashboard/profile.js'); ?>"></script>
<?= $this->endSection('scripts'); ?>