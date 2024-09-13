<?= $this->extend('backend/template/auth_template'); ?>
<?= $this->section('content'); ?>

<section class="content mt-5">
    <div class="error-page mt-5">
        <h2 class="headline text-danger">403</h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-danger mt-4"></i> Oops! Access Forbidden.</h3>
            <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
            <a href="<?= base_url('dashboard'); ?>">&larr; Back to Home</a>
        </div>
    </div>
</section>

<?= $this->endSection('content'); ?>