<?php $userAccount = $dataUser ?? $_SESSION['user'] ?>
<section class="h-50" id="register-container">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-10">
                <div class="card card-registration border-0 shadow-sm rounded-4 mt-4 mb-5">
                    <h4 class="mt-4 mb-4 text-uppercase text-center fw-bold" style="color: #1e293b;"><i class="fas fa-user-circle me-2" style="color: #4f46e5;"></i>Thông tin tài khoản</h4>
                    <div class="row g-0 d-flex justify-content-center px-4 pb-4">
                        <form class="col-xl-5 pe-xl-4 mb-4 mb-xl-0" action="?page=profile&act=update" method="post" style="border-right: 1px solid #e2e8f0;">
                            <div class="card-body text-black p-0">
                                <div class="row">
                                    <div class="col-md-6 mb-0">
                                        <div class="form-outline">
                                            <h6 class="form-label mb-0" for="reg-firstname">Họ & tên đệm <span
                                                        class="msg-check-fn text-danger fs-7" style="font-size: 10px"></span></h6>
                                            <input type="text" name="first-name" id="reg-firstname"
                                                   class="form-control form-control-sm mb-1" value="<?= $userAccount['first_name'] ?>" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-0">
                                        <div class="form-outline">
                                            <h6 class="form-label mb-0" for="reg-lastname">Tên <span
                                                        class="msg-check-ln text-danger fs-7" style="font-size: 10px"></span></h6>

                                            <input type="text" name="last-name" id="reg-lastname"
                                                   class="form-control form-control-sm mb-1" value="<?= $userAccount['last_name'] ?>" required/>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-1 py-2">
                                    <h6 class="mb-0 me-2">Giới tính: <span
                                                class="msg-check-gender text-danger fs-7" style="font-size: 10px"></span>
                                    </h6>
                                    <div class="form-check form-check-inline mb-0 me-4">
                                        <input class="form-check-input" type="radio" name="gender" id="femaleGender"
                                               value="0" <?= $userAccount['gender'] === "0" ? "checked" : "" ?> required/>
                                        <label class="form-check-label" for="femaleGender">Nữ</label>
                                    </div>

                                    <div class="form-check form-check-inline mb-0 me-4">
                                        <input class="form-check-input" type="radio" name="gender" id="maleGender"
                                               value="1" <?= $userAccount['gender'] === "1" ? "checked" : "" ?>/>
                                        <label class="form-check-label" for="maleGender">Nam</label>
                                    </div>
                                </div>

                                <div class="form-outline mb-1">
                                    <h6 class="form-label mb-0 " for="reg-username">Tên đăng nhập <span
                                                class="msg-check-username text-danger fs-7" style="font-size: 10px"></span>
                                    </h6>
                                    <input type="text" name="username" id="reg-username"
                                           class="form-control form-control-sm mb-1" value="<?= $userAccount['username'] ?>" disabled minlength="6"/>
                                </div>


                                <div class="form-outline mb-1">
                                    <h6 class="form-label mb-0" for="reg-email">Email <span
                                                class="msg-check-email text-danger fs-7" style="font-size: 10px"></span></h6>
                                    <input type="email" name="email" id="reg-email"
                                           class="form-control form-control-sm mb-2" value="<?= $userAccount['email'] ?>" required/>
                                </div>

                                <div class="form-outline mb-1">
                                    <h6 class="form-label mb-0" for="reg-phone">SĐT <span
                                                class="msg-check-phone text-danger fs-7" style="font-size: 10px"></span></h6>
                                    <input type="tel" name="phone" id="reg-phone"
                                           class="form-control form-control-sm mb-2" required value="<?= $userAccount['phone'] ?>"
                                           pattern="^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$"/>
                                </div>
                                <div class="form-outline mb-1">
                                    <h6 class="form-label mb-0" for="reg-phone">Địa chỉ <span
                                                class="msg-check-address text-danger fs-7" style="font-size: 10px"></span></h6>
                                    <input type="text" name="address" id="reg-address"
                                           class="form-control form-control-sm mb-2" required value="<?= $userAccount['address'] ?>"
                                           />
                                </div>
                                <div class="d-flex justify-content-end pt-4">
                                    <button type="submit" id="button-update-info" class="btn btn-primary ms-2" style="border-radius: 10px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: none; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);">
                                        <i class="fas fa-save me-2"></i>Cập nhập thông tin
                                    </button>
                                </div>


                            </div>
                        </form>
                        <div class="col-xl-5 ps-xl-4 mt-3 mt-xl-0 mb-2">
                            <form action="?page=profile&act=password" method="post">
                                <div class="form-outline mb-1">
                                    <h6 class="form-label mb-0" for="old-password">Mật khẩu cũ <span
                                                class="msg-check-old-password text-danger" style="font-size: 10px"></span></h6>
                                    <input type="password" name="password" id="old-password"
                                           class="form-control form-control-sm mb-2" required/>
                                </div>
                                <div class="form-outline mb-1">
                                    <h6 class="form-label mb-0" for="new-password">Mật khẩu mới <span
                                                class="msg-check-pass text-danger fs-7" style="font-size: 10px"></span></h6>
                                    <input type="password" name="new-password-1" id="new-password"
                                           class="form-control form-control-sm mb-2" required/>
                                </div>
                                <div class="form-outline mb-1">
                                    <h6 class="form-label mb-0" for="confirm-password">Nhập lại mật khẩu mới <span
                                                class="msg-check-retype-pass text-danger fs-7" style="font-size: 10px"></span></h6>
                                    <input type="password" name="new-password-2" id="confirm-password"
                                           class="form-control form-control-sm mb-2" required/>
                                </div>
                                <div class="d-flex justify-content-end pt-4">
                                    <button href="#" type="submit" id="button-change-password" class="btn btn-primary ms-2" style="border-radius: 10px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: none; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);">
                                        <i class="fas fa-key me-2"></i>Thay đổi mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>