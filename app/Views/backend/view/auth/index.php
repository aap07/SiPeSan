<?= $this->extend('backend/template/auth_template'); ?>
<?= $this->section('content'); ?>

<div class="login-box">
    <div class="login-logo">
        <h1><b class="text-white text-auth"><?= $sub_title; ?></b></h1>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <h1 class="text-center text-sign">Sign In</h1>
            <div id="pos-flash-logout" data-flashlogout="<?= session()->getFlashdata('message'); ?>"></div>
            <div id="pos-flashdata-error" data-flashdataerror="<?= session()->getFlashdata('messageerror'); ?>"></div>
            <form action="<?= base_url('login'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="input-group">
                    <input type="text" class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : null ?>" id="username" name="username" value="<?= old('username'); ?>" placeholder="Username" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user text-gray-dark"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?= $validation->getError('username'); ?></div>
                </div>
                <div class="input-group mt-3">
                    <input type="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : null ?>" id="password" name="password" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <a class="fas fa-eye text-gray-dark" id="click-password"></a>
                        </div>
                    </div>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock text-gray-dark"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?= $validation->getError('password'); ?></div>
                </div>
                <button type="submit" class="btn btn-dark btn-block mt-3">Sign In</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>