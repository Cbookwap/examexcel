<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('page_content') ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">My Profile</p>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible text-white" role="alert">
                            <span class="text-sm"><?= session()->getFlashdata('success') ?></span>
                            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible text-white" role="alert">
                            <span class="text-sm"><?= session()->getFlashdata('error') ?></span>
                            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('class-teacher/profile') ?>">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="<?= old('first_name', $user['first_name']) ?>" required>
                                </div>
                                <?php if ($validation->hasError('first_name')): ?>
                                    <div class="text-danger text-sm"><?= $validation->getError('first_name') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="<?= old('last_name', $user['last_name']) ?>" required>
                                </div>
                                <?php if ($validation->hasError('last_name')): ?>
                                    <div class="text-danger text-sm"><?= $validation->getError('last_name') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?= $user['email'] ?>" disabled>
                                </div>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" value="<?= old('phone', $user['phone']) ?>">
                                </div>
                                <?php if ($validation->hasError('phone')): ?>
                                    <div class="text-danger text-sm"><?= $validation->getError('phone') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Employee ID</label>
                                    <input type="text" class="form-control" value="<?= $user['employee_id'] ?>" disabled>
                                </div>
                                <small class="text-muted">Employee ID cannot be changed</small>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth" value="<?= old('date_of_birth', $user['date_of_birth']) ?>">
                                </div>
                                <?php if ($validation->hasError('date_of_birth')): ?>
                                    <div class="text-danger text-sm"><?= $validation->getError('date_of_birth') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Gender</label>
                                    <select class="form-control" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" <?= old('gender', $user['gender']) === 'male' ? 'selected' : '' ?>>Male</option>
                                        <option value="female" <?= old('gender', $user['gender']) === 'female' ? 'selected' : '' ?>>Female</option>
                                        <option value="other" <?= old('gender', $user['gender']) === 'other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                                <?php if ($validation->hasError('gender')): ?>
                                    <div class="text-danger text-sm"><?= $validation->getError('gender') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Department</label>
                                    <input type="text" class="form-control" name="department" value="<?= old('department', $user['department']) ?>">
                                </div>
                                <?php if ($validation->hasError('department')): ?>
                                    <div class="text-danger text-sm"><?= $validation->getError('department') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Qualification</label>
                                    <input type="text" class="form-control" name="qualification" value="<?= old('qualification', $user['qualification']) ?>">
                                </div>
                                <?php if ($validation->hasError('qualification')): ?>
                                    <div class="text-danger text-sm"><?= $validation->getError('qualification') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" rows="3"><?= old('address', $user['address']) ?></textarea>
                                </div>
                                <?php if ($validation->hasError('address')): ?>
                                    <div class="text-danger text-sm"><?= $validation->getError('address') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="horizontal dark">
                        <h6>Change Password</h6>
                        <p class="text-sm">Leave blank if you don't want to change your password</p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <?php if ($validation->hasError('password')): ?>
                                    <div class="text-danger text-sm"><?= $validation->getError('password') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="confirm_password">
                                </div>
                                <?php if ($validation->hasError('confirm_password')): ?>
                                    <div class="text-danger text-sm"><?= $validation->getError('confirm_password') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-primary">
                                <i class="material-symbols-rounded opacity-5 me-2">save</i>
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
