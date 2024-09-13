<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// $routes->group('', ['hostname' => 'localhost:8080'], function($routes) {
//     $routes->get('/', 'Frontend\Home::index');
//     $routes->get('about', 'Frontend\About::index');
//     $routes->get('article', 'Frontend\About::index');
//     $routes->get('gallery', 'Frontend\Gallery::index');
//     $routes->get('contact', 'Frontend\Contact::index');
//     $routes->get('article/(:segment)', 'Frontend\Article::index/$1');
// });

// $routes->group('', ['hostname' => 'subdomain.localhost:8080'], function($routes) {
$routes->group('', ['hostname' => 'localhost:8080'], function($routes) {
    $routes->get('/', 'Backend\Auth\Auth::index',['filter' => ['checkSession']]);
    $routes->get('blocked', 'Backend\Auth\Auth::blocked', ['filter' => ['checkLogin']]);
    $routes->post('login', 'Backend\Auth\Auth::login');
    $routes->get('logout', 'Backend\Auth\Auth::logout', ['filter' => ['checkLogin']]);

    $routes->get('dashboard', 'Backend\Modules\Dashboard::index', ['filter' => ['checkLogin']]);
    $routes->get('profile', 'Backend\Modules\Dashboard::profile', ['filter' => ['checkLogin', 'checkAccess']]);
    $routes->get('data', 'Backend\Modules\Dashboard::dataUser', ['filter' => ['checkLogin']]);
    $routes->get('edit', 'Backend\Modules\Dashboard::editProfile', ['filter' => ['checkLogin']]);
    $routes->post('edit', 'Backend\Modules\Dashboard::saveProfile', ['filter' => ['checkLogin']]);

    $routes->group('administrator', ['filter' => ['checkLogin', 'checkAccess']], function($routes) {
        $routes->get('user', 'Backend\Modules\Administrator::userManagement');
        $routes->post('listuser', 'Backend\Modules\Administrator::listUser');
        $routes->get('detail', 'Backend\Modules\Administrator::detailUser');
        $routes->get('tambah', 'Backend\Modules\Administrator::tambahUser');
        $routes->post('tambah', 'Backend\Modules\Administrator::saveUser');
        $routes->get('delete', 'Backend\Modules\Administrator::deleteUser');
        $routes->post('delete', 'Backend\Modules\Administrator::hapusUser');
        $routes->get('menu', 'Backend\Modules\Administrator::menuManagement');
        $routes->post('listmenu', 'Backend\Modules\Administrator::listMenu');
        $routes->get('tambahmenu', 'Backend\Modules\Administrator::tambahMenu');
        $routes->post('tambahmenu', 'Backend\Modules\Administrator::saveMenu');
        $routes->get('deletemenu', 'Backend\Modules\Administrator::deleteMenu');
        $routes->post('deletemenu', 'Backend\Modules\Administrator::hapusMenu');
        $routes->get('submenu', 'Backend\Modules\Administrator::submenuManagement');
        $routes->post('listsubmenu', 'Backend\Modules\Administrator::listSubmenu');
        $routes->get('tambahsubmenu', 'Backend\Modules\Administrator::tambahSubmenu');
        $routes->post('tambahsubmenu', 'Backend\Modules\Administrator::saveSubmenu');
        $routes->get('deletesubmenu', 'Backend\Modules\Administrator::deleteSubmenu');
        $routes->post('deletesubmenu', 'Backend\Modules\Administrator::hapusSubmenu');
        $routes->get('rolemenu', 'Backend\Modules\Administrator::roleMenu');
        $routes->post('listrole', 'Backend\Modules\Administrator::listRole');
        $routes->get('tambahrole', 'Backend\Modules\Administrator::tambahRole');
        $routes->post('tambahrole', 'Backend\Modules\Administrator::saveRole');
        $routes->get('deleterole', 'Backend\Modules\Administrator::deleteRole');
        $routes->post('deleterole', 'Backend\Modules\Administrator::hapusRole');
        $routes->get('roleaccess', 'Backend\Modules\Administrator::roleAccess');
        $routes->post('listaccess', 'Backend\Modules\Administrator::listAccess');
        $routes->post('changeaccess', 'Backend\Modules\Administrator::changeAccess');
    });

    $routes->group('recruitment', ['filter' => ['checkLogin', 'checkAccess']], function($routes) {
        $routes->get('jobapplicants', 'Backend\Modules\Recruitment::jobApplicants');
        $routes->post('listapplicants', 'Backend\Modules\Recruitment::listApplicants');
        $routes->get('detailapplicants', 'Backend\Modules\Recruitment::detailApplicants');
        $routes->get('tambahapplicants', 'Backend\Modules\Recruitment::tambahApplicants');
        $routes->post('tambahapplicants', 'Backend\Modules\Recruitment::saveApplicants');
        $routes->get('deleteapplicants', 'Backend\Modules\Recruitment::deleteApplicants');
        $routes->post('deleteapplicants', 'Backend\Modules\Recruitment::hapusApplicants');
        $routes->get('evalApplicants/(:segment)', 'Backend\Modules\Recruitment::evalApplicants/$1');
        $routes->post('saveNilaiApplicants', 'Backend\Modules\Recruitment::saveNilaiApplicants');
        $routes->get('criteria', 'Backend\Modules\Recruitment::managementKriteria');
        $routes->post('listkriteria', 'Backend\Modules\Recruitment::listKriteria');
        $routes->get('tambahkriteria', 'Backend\Modules\Recruitment::tambahKriteria');
        $routes->post('tambahkriteria', 'Backend\Modules\Recruitment::saveKriteria');
        $routes->get('deletekriteria', 'Backend\Modules\Recruitment::deleteKriteria');
        $routes->post('deletekriteria', 'Backend\Modules\Recruitment::hapusKriteria');
        $routes->get('subcriteria/(:segment)', 'Backend\Modules\Recruitment::subkriteriaManagement/$1');
        $routes->post('listsubkriteria', 'Backend\Modules\Recruitment::listSubkriteria');
        $routes->get('tambahsubkriteria', 'Backend\Modules\Recruitment::tambahSubkriteria');
        $routes->post('tambahsubkriteria', 'Backend\Modules\Recruitment::saveSubkriteria');
        $routes->get('deletesubkriteria', 'Backend\Modules\Recruitment::deleteSubkriteria');
        $routes->post('deletesubkriteria', 'Backend\Modules\Recruitment::hapusSubkriteria');
        $routes->get('ratio', 'Backend\Modules\Recruitment::ratioManagement');
        $routes->post('saveNilai', 'Backend\Modules\Recruitment::saveNilai');
        $routes->get('ratioSub/(:segment)', 'Backend\Modules\Recruitment::ratioSubManagement/$1');
        $routes->post('saveSubratio', 'Backend\Modules\Recruitment::saveSubratio');
        $routes->get('results', 'Backend\Modules\Recruitment::resultsApplicant');
        $routes->post('listResult', 'Backend\Modules\Recruitment::listResult');
        $routes->get('joblist', 'Backend\Modules\Recruitment::jobList');
        $routes->post('listJob', 'Backend\Modules\Recruitment::listJob');
        $routes->get('tambahposisi', 'Backend\Modules\Recruitment::tambahPosisi');
        $routes->post('tambahposisi', 'Backend\Modules\Recruitment::savePosisi');
        $routes->get('deleteposisi', 'Backend\Modules\Recruitment::deletePosisi');
        $routes->post('deleteposisi', 'Backend\Modules\Recruitment::hapusPosisi');
    });

});
