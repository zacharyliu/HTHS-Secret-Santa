<div class="container">
    <?php if ($this->session->flashdata('admin')) echo $this->session->flashdata('admin'); //if there's an admin result, echo it?>

    <h2>Send a Bulk Email</h2>

    <p>You may use the following variables in PHP format (i.e. $name) in the subject and message of your form email:</p>

    <p>
    <ul>
        <?php foreach ($varNames as $varName): ?>
            <li><?=$varName?></li>
        <?php endforeach; ?>
    </ul>
    </p>

    <p>
        <form method="post" action="">
        <label>To: <?=($code == null) ? "All Secret Santa members of year $year" : "Members of group code $code in year $year"?></label>
        <br/>
        <label>Subject Template: <input class="form-control" type="text" style="width: 500px;" name="subject" required></label>
        <br/>
        <label>Message Template: <textarea class="form-control" style="width: 500px; height: 500px;" name="message" required></textarea></label>
        <br/>
        <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </p>
</div>
