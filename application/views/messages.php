<div class="container">
    <h2>Inbox</h2>
    <table class="table table-hover">
        <tr>
            <th>Date</th>
            <th>Group</th>
            <th>Latest Message</th>
        </tr>
        <?php if (count($threads) == 0) : ?>
        <tr>
            <td colspan="3" style="text-align: center;">
                No messages received!
            </td>
        </tr>
        <?php endif; ?>
        <?php foreach ($threads as $thread) : ?>
        <tr>
            <td><?=$thread->timestamp?></td>
            <td><?=$thread->code?></td>
            <td><?=$thread->message?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>