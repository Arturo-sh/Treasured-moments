function alert_delete_message(id_msg) {
    if (confirm("¿Realmente desea borrar este mensaje?")) {
        window.location.href = "messages.php?id_msg_delete=" + id_msg;
    }
}