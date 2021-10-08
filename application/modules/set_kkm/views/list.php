<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h4 class="title">Set KKM</h4>

            </div>
            <div class="content">

                <?php echo $this->session->flashdata('k'); ?>

                <form method="post" action="<?php echo base_url() . $url; ?>/edit">
                    <div class="row">
                        <div class="col-md-12">
                            <?php foreach ($kkm as $k) : ?>
                                <div class="form-group form-inline" style="padding-top: 10px;">
                                    <label style="padding-top: 5px;">Kelas <?= $k['kelas'] ?></label>
                                    <input type="text" class="form-control" style="width:75%!important; float:right" name="<?= $k['kelas'] ?>" value="<?= $k['kkm'] ?>">
                                </div>
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-success" id="edit" style="margin-top: 10px;">EDIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).on("ready", function() {
        pagination("datatabel", base_url + "<?php echo $url; ?>/datatable", []);

        $("#<?php echo $nama_form; ?>").on("submit", function() {

            var data = $(this).serialize();

            $.ajax({
                type: "POST",
                data: data,
                url: base_url + "<?php echo $url; ?>/simpan",
                success: function(r) {
                    if (r.status == "gagal") {
                        noti("danger", r.data);
                    } else {
                        $("#modal_data").modal('hide');
                        noti("success", r.data);
                        pagination("datatabel", base_url + "<?php echo $url; ?>/datatable", []);
                    }
                }
            });

            return false;
        });
    });

    function hapus(id) {
        if (id == 0) {
            noti("danger", "Silakan pilih datanya..!");
        } else {
            if (confirm('Anda yakin...?')) {
                $.ajax({
                    type: "GET",
                    url: base_url + "<?php echo $url; ?>/hapus/" + id,
                    success: function(data) {
                        noti("success", "Berhasil dihapus...!");
                        pagination("datatabel", base_url + "<?php echo $url; ?>/datatable", []);
                    }
                });
            }
        }

        return false;
    }
</script>