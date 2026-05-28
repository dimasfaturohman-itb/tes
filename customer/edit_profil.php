<form method="POST">

    <div class="mb-3">

        <label>Nama</label>

        <input type="text"
            class="form-control"
            value="<?= $_SESSION['user']['nama']; ?>">

    </div>

    <div class="mb-3">

        <label>No HP</label>

        <input type="text"
            class="form-control">

    </div>

    <button class="btn btn-danger rounded-pill">

        Simpan Perubahan

    </button>

</form>