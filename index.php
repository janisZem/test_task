<?php include_once 'header.php'; ?>
<div class="container">
    <h2>Import payments</h2>
    <hr>
    <form class="form-inline" action="process.php" method="POST" enctype= "multipart/form-data">
        <div class="form-group">
            <label for="bank_chooser">Banka:</label>
            <select id="bank_chooser" name="file_type" class="form-control">
                <option value="DAT">BRE Bank (DAT)</option>
                <option value="XML">ING Bank (XML)</option>
            </select>
            <label for="payment_file">File input</label>
            <input type="file" class="form-control" name="payment_file" id="payment_file">
            <button type="submit" class="btn btn-default">Upload file</button>
        </div>
    </form>
</div>
<?php include_once 'footer.php' ?>;
