<?php
require_once "../UserSetting/user_auth.php";
$title = "Contact Messages";
require_once "../Dashboard/header.php";
require_once '../Database/db.php';

$messages_result = $dbcon->query("SELECT * FROM contact_messages ORDER BY received_at DESC");
?>

<style>
    .message-item { border-left: 4px solid #ccc; }
    .message-item.unread { border-left-color: #007bff; font-weight: bold; }
    .message-body { background-color: #f8f9fa; border-top: 1px solid #dee2e6; }
</style>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Inbox</h4>
        <p class="text-muted">Click on a message to read it and mark it as read.</p>
    </div>
    <div class="card-body">
        <div id="message-accordion">
            <?php if ($messages_result->num_rows > 0) : ?>
                <?php foreach ($messages_result as $message) : ?>
                    <div class="message-item card mb-1 <?= ($message['status'] == 0) ? 'unread' : '' ?>">
                        <div class="card-header" id="heading-<?= $message['id'] ?>">
                            <h5 class="mb-0">
                                <button class="btn btn-link text-left w-100" data-toggle="collapse" data-target="#collapse-<?= $message['id'] ?>" aria-expanded="false" aria-controls="collapse-<?= $message['id'] ?>" data-message-id="<?= $message['id'] ?>">
                                    <div class="d-flex justify-content-between">
                                        <span>
                                            <i class="bi bi-person-fill"></i> <?= htmlspecialchars($message['name']) ?> - <strong><?= htmlspecialchars($message['subject']) ?></strong>
                                        </span>
                                        <small><?= date("M j, Y, g:i a", strtotime($message['received_at'])) ?></small>
                                    </div>
                                </button>
                            </h5>
                        </div>

                        <div id="collapse-<?= $message['id'] ?>" class="collapse" aria-labelledby="heading-<?= $message['id'] ?>" data-parent="#message-accordion">
                            <div class="message-body card-body">
                                <p><strong>From:</strong> <?= htmlspecialchars($message['name']) ?> &lt;<?= htmlspecialchars($message['email']) ?>&gt;</p>
                                <hr>
                                <p><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                                <hr>
                                <a href="message_delete.php?id=<?= $message['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to permanently delete this message?');">
                                    <i class="bi bi-trash-fill"></i> Delete Message
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted">You have no messages.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once "../Dashboard/footer.php";
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var messageToggles = document.querySelectorAll('[data-toggle="collapse"]');

    messageToggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var messageId = this.getAttribute('data-message-id');
            var messageItem = this.closest('.message-item');

            if (messageItem.classList.contains('unread')) {
                messageItem.classList.remove('unread');

                fetch('message_mark_read.php?id=' + messageId)
                    .then(response => {
                        if (!response.ok) {
                           console.error('Failed to mark message as read.');
                           messageItem.classList.add('unread');
                        }
                    });
            }
        });
    });
});
</script>