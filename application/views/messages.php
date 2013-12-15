<div class="container">
    <h2>Inbox</h2>
    <table class="table table-hover">
        <tr>
            <th>Date</th>
            <th>Group</th>
            <th>Latest Message</th>
            <td></td>
        </tr>
        <?php if (count($threads) == 0) : ?>
        <tr>
            <td colspan="3" style="text-align: center;">
                No messages received!
            </td>
        </tr>
        <?php endif; ?>
        <?php foreach ($threads as $thread) : ?>
        <tr<?=(!$thread->read) ? ' style="font-weight: bold;"' : ''?>>
            <td><?=$thread->timestamp?></td>
            <td><?=$thread->code?></td>
            <td><?=$thread->message?></td>
            <td>
                <a class="btn btn-primary" href="<?=base_url("messsages/view/{$thread->code}")?>">View Thread</a>
                <a class="btn btn-primary" href="<?=base_url("messages/markRead/{$thread->code}")?>">Mark As Read</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>