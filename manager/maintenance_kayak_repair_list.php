<?php include "header.php"; ?>
<?php include "navbar.php"; ?>
<?php include "sidebar.php"; ?>
<?php include "dbconnection.php"; ?>

<body class="app sidebar-mini rtl">

  <main class="app-content">
    <div class="app-title">
      <div>
        <h4>MAINTENANCE</h4><small><span id="date_time"></span></small>
      </div>
      <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">Maintenance</li>
        <li class="breadcrumb-item active">Repair Kayak</li>
      </ul>
    </div>
    <?php include "maintenance_kayak_bar.php"; ?>
    <div class="row">
      <div class="col-md-12">
        <div class="bs-component">
          <div class="card">
            <h5 class="card-header">Repair Kayak</h5>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-9">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                <div class="col-lg-1">
                  Search:
                </div>
                <div class="col-lg-2">
                  <script src="../js/argiepolicarpio.js"></script>
                  <script src="../js/application.js"></script>
                  <input type="search" class="form-control form-control-sm" name="filter" value="" id="filter">
                </div>
              </div><br />
              <table class="table table-hover table-bordered">
                <thead bgcolor=#f8f8f8>
                  <tr align="center">
                    <th>No.</th>
                    <th>Boat Code</th>
                    <th>Damage Date</th>
                    <th>Repair Date</th>
                    <th>Report</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  $repStatus = 1;
                  $doneStatus = 0;
                  $v = 0;

                  $stmt = $conn->prepare("SELECT  damage.dam_kyk_id as dam_id, damage.dam_kyk_date as dam_date, damage.rep_kyk_status as rep_status, damage.rep_kyk_date as rep_date,
                    facility.new_id as new_id, facility.new_code as new_code
                    FROM `damage_kayak` AS damage
                    JOIN `facility_new` AS facility ON damage.new_id=facility.new_id
                    AND  damage.rep_kyk_status=? AND damage.done_kyk_status=? ORDER BY damage.rep_kyk_date ASC");
                    $stmt->bind_param("ss", $repStatus, $doneStatus);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while($row = $result->fetch_assoc())
                    {
                      $new_id= $row['new_id'];
                      $new_code = $row['new_code'];
                      $dam_id = $row['dam_id'];
                      $dam_date = date_format(date_create($row['dam_date']), 'd/m/Y');
                      $rep_date = date_format(date_create($row['rep_date']), 'd/m/Y');
                      $rep_status = $row['rep_status'];
                      $v++;
                      ?>
                      <tr align="center">
                        <td><?php echo $v; ?></td>
                        <td><?php echo $new_code; ?></td>
                        <td><?php echo $dam_date ?></td>
                        <td><?php echo $rep_date ?></td>
                        <td>
                          <?php include('maintenance_kayak_repair_edit.php'); ?>
                          <a href="#edit<?php echo $dam_id; ?>" data-toggle="modal" class="btn btn-warning btn-sm"  style="color:#ffffff;"  id="popoverInfo" rel="popover" data-trigger="hover" data-placement="top" data-original-title="" data-content="Record the facilities that complete repaired!"><i class="fa fa-fw fa-lg fa-check-circle"></i>Complete</a>
                        </td>
                        <td>
                          <form action='maintenance_kayak_status.php' method='post' >
                            <input type="hidden" name="dam_id" value="<?php echo $dam_id?>">
                            <?php
                            if($rep_status==1){
                              echo "<a onclick='return confirm(\"Are You Sure?\");'><button class='btn btn-danger btn-sm' name='cancel'><i class='fa fa-trash'></i></button></a>";
                            }
                            else{
                              echo "<a onclick='return confirm(\"Are You Sure?\");'><button class='btn btn-warning btn-sm' style='color:#ffffff;' name='repair'><i class='fa fa-fw fa-lg fa-check-circle'></i>Repair</button></a>";
                            }
                            ?>
                          </form>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <div class="card-footer">
                &nbsp;&nbsp;
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include "footer.php"; ?>

  </body>
  </html>
