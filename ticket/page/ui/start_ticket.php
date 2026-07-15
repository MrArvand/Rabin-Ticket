<!-- Row starts -->
<link rel="stylesheet" href="assets/vendor/select2/select2.min.css" />
<link rel="stylesheet" href="assets/vendor/select2/select2-theme.css" />
<div class="row gx-3">
  <div class="col-sm-12">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="card-title">ایجاد یک تیکت پشتیبانی جدید </h5>
      </div>
      <div class="card-body">



        <form method="post" action="?page=s_new_ticket" enctype="multipart/form-data">
          <!-- Row starts -->
          <div class="row gx-3">
            <div class="col-lg-3 col-sm-4 col-12">
              <div class="mb-3">
                <label class="form-label" for="karbar_darkhast">درخواست دهنده</label>
                <input type="text" class="form-control" id="karbar_darkhast" name="karbar_darkhast" value="<?php echo $name_karbar_run; ?>" readonly>
              </div>
            </div>
            <div class="col-lg-3 col-sm-4 col-12">
              <div class="mb-3">
                <label class="form-label" for="olaviat">ضرورت</label>
                <select class="form-select" name="olaviat" id="olaviat">
                  <option value="1">ضروری</option>
                  <option selected value="2">متوسط</option>
                  <option value="3">معمولی</option>
                  <option value="4">پایین</option>
                </select>
              </div>
            </div>

            <div class="col-lg-3 col-sm-4 col-12">
              <div class="mb-3">
                <label class="form-label" for="target_sherkat">شرکت گیرنده</label>
                <select class="form-select" name="target_sherkat" id="target_sherkat" onchange="onCompanyChange(this.value)">
                  <?php
                  $Query_sherkat = "SELECT*from sherkatha where (1)ORDER BY name DESC LIMIT 200";
                  if ($Result_sherkat = mysqli_query($Link, $Query_sherkat)) {
                    while ($q_sherkat = mysqli_fetch_array($Result_sherkat)) {
                  ?>
                      <option value="<?php echo $q_sherkat['code']; ?>"><?php echo $q_sherkat['name']; ?></option>
                  <?php }
                  } ?>
                </select>
              </div>
            </div>

            <div class="col-lg-3 col-sm-4 col-12" id="moavenat_wrapper" style="display: none;">
              <div class="mb-3">
                <label class="form-label" for="target_moavenat">معاونت</label>
                <select class="form-select" name="target_moavenat" id="target_moavenat" onchange="onDivisionChange(this.value)">
                </select>
              </div>
            </div>

            <?php
            require_once __DIR__ . '/../../inf/restricted_department_sadgan_fx.php';
            $current_user_code_for_department_visibility = isset($_SESSION['code_p']) ? trim((string) $_SESSION['code_p']) : '';
            $can_view_restricted_department = can_view_restricted_department($current_user_code_for_department_visibility);
            ?>
<div class="col-lg-3 col-sm-4 col-12">
    <div class="mb-3">
        <label class="form-label" for="daste">دپارتمان پشتیبانی</label>
        <select class="form-select" name="daste" id="daste">
            <?php

            $Query_dep = "SELECT*from departman where (vaziat = 'y') ORDER BY name ASC LIMIT 200";
            if ($Result_dep = mysqli_query($Link, $Query_dep)) {
                while ($q_dep = mysqli_fetch_array($Result_dep)) {
                    if (!$can_view_restricted_department && is_restricted_department($q_dep['id'] ?? '')) {
                        continue;
                    }

            ?>
                    <option value="<?php echo $q_dep['id']; ?>"><?php echo $q_dep['name']; ?></option>
            <?php }
            } ?>
        </select>
    </div>
</div>

            <div class="col-lg-3 col-sm-4 col-12">
              <div class="mb-3">
                <label class="form-label" for="sherkat">شرکت درخواست دهنده</label>
                <select class="form-select" name="sherkat" id="sherkat">
                  <?php

                  $Query_sherkat = "SELECT*from sherkatha where (1)ORDER BY name DESC LIMIT 200";
                  if ($Result_sherkat = mysqli_query($Link, $Query_sherkat)) {
                    while ($q_sherkat = mysqli_fetch_array($Result_sherkat)) {

                  ?>
                      <option value="<?php echo $q_sherkat['code']; ?>"><?php echo $q_sherkat['name']; ?></option>
                  <?php }
                  } ?>
                </select>
              </div>
            </div>

            <div class="col-lg-6 col-sm-12 col-12">
              <div class="mb-3">
                <label class="form-label" for="titr">تیتر درخواست</label>
                <input type="text" class="form-control" id="titr" name="titr" placeholder="تیتر درخواست" required>
              </div>
            </div>

            <div class="col-sm-12 col-12">
              <div class="mb-3">
                <label class="form-label" for="matn">پیام</label>
                <textarea type="text" class="form-control" name="matn" id="matn" placeholder="متن درخواست"
                  rows="3" required></textarea>
              </div>
            </div>
            <div class="input-group mb-5">
              <label class="input-group-text" for="file_peyvast">بارگذاری</label>
              <input name="file_peyvast" type="file" class="form-control" id="file_peyvast" />
            </div>
            <!-- Row ends -->

          </div>
          <div class="card-footer">
            <div class="d-flex gap-2 justify-content-end">
              <button type="reset" class="btn btn-outline-secondary">لغو</button>
              <button type="submit" class="btn btn-primary">ثبت</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>


<script>
  var SEARCHABLE_SELECT_IDS = ['olaviat', 'target_sherkat', 'target_moavenat', 'daste', 'sherkat'];

  function initSearchableDropdown(selectEl) {
    if (!selectEl || typeof jQuery === 'undefined' || !jQuery.fn.select2) {
      return;
    }

    var $el = jQuery(selectEl);
    if ($el.hasClass('select2-hidden-accessible')) {
      $el.select2('destroy');
    }

    $el.select2({
      dir: 'rtl',
      width: '100%',
      minimumResultsForSearch: 0,
      language: {
        noResults: function() {
          return 'نتیجه‌ای یافت نشد';
        },
        searching: function() {
          return 'در حال جستجو...';
        }
      }
    });
  }

  function initAllSearchableDropdowns() {
    SEARCHABLE_SELECT_IDS.forEach(function(id) {
      var el = document.getElementById(id);
      if (el) {
        initSearchableDropdown(el);
      }
    });
  }

  function loadSelect2Assets(callback) {
    if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
      callback();
      return;
    }

    var script = document.createElement('script');
    script.src = 'assets/vendor/select2/select2.min.js';
    script.onload = callback;
    document.head.appendChild(script);
  }

  // Cascade: company => division (معاونت) => department
  function onCompanyChange(companyCode) {
    const divisionWrapper = document.getElementById('moavenat_wrapper');
    const divisionSelect = document.getElementById('target_moavenat');

    if (!companyCode) {
      divisionWrapper.style.display = 'none';
      divisionSelect.innerHTML = '';
      initSearchableDropdown(divisionSelect);
      loadDepartments(companyCode, '');
      return;
    }

    fetch(`page/ajax/get_divisions.php?company_code=${encodeURIComponent(companyCode)}`)
      .then(response => response.json())
      .then(divisions => {
        divisionSelect.innerHTML = '';

        if (divisions.length > 0) {
          divisions.forEach(division => {
            const option = document.createElement('option');
            option.value = division.id;
            option.textContent = division.name;
            divisionSelect.appendChild(option);
          });

          divisionWrapper.style.display = '';
          divisionSelect.selectedIndex = 0;
          initSearchableDropdown(divisionSelect);
          loadDepartments(companyCode, divisionSelect.value);
        } else {
          divisionWrapper.style.display = 'none';
          initSearchableDropdown(divisionSelect);
          loadDepartments(companyCode, '');
        }
      })
      .catch(error => {
        console.error('Error loading divisions:', error);
        divisionWrapper.style.display = 'none';
        loadDepartments(companyCode, '');
      });
  }

  function onDivisionChange(divisionCode) {
    const companyCode = document.getElementById('target_sherkat').value;
    loadDepartments(companyCode, divisionCode);
  }

  function loadDepartments(companyCode, divisionCode) {
    if (!companyCode) return;

    let url = `page/ajax/get_departments.php?company_code=${encodeURIComponent(companyCode)}`;
    if (divisionCode) {
      url += `&division_code=${encodeURIComponent(divisionCode)}`;
    }

    fetch(url, { credentials: 'same-origin' })
      .then(response => response.json())
      .then(data => {
        const departmentSelect = document.getElementById('daste');
        departmentSelect.innerHTML = '';

        if (data.length > 0) {
          data.forEach(department => {
            const option = document.createElement('option');
            option.value = department.id;
            option.textContent = department.name;
            departmentSelect.appendChild(option);
          });
        } else {
          const option = document.createElement('option');
          option.value = '';
          option.textContent = 'دپارتمانی برای این انتخاب یافت نشد';
          departmentSelect.appendChild(option);
        }

        initSearchableDropdown(departmentSelect);
      })
      .catch(error => {
        console.error('Error loading departments:', error);
      });
  }

  window.addEventListener('load', function() {
    loadSelect2Assets(function() {
      initAllSearchableDropdowns();

      const defaultCompany = document.getElementById('target_sherkat').value;
      if (defaultCompany) {
        onCompanyChange(defaultCompany);
      }
    });
  });
</script>