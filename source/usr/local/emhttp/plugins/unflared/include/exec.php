<?php
// exec.php — AJAX handler for unflared plugin actions
// Note: CSRF validation is handled globally by local_prepend.php (Unraid core)

header('Content-Type: application/json');

$cfg = "/boot/config/plugins/unflared/unflared.cfg";
$rc  = "/etc/rc.d/rc.unflared";

$action = $_POST['action'] ?? '';

switch ($action) {
  case 'save':
    $token = preg_replace('/[\r\n]/', '', trim($_POST['token'] ?? ''));
    if ($token === '') {
      echo json_encode(['status' => 'error', 'message' => 'Token cannot be empty']);
      exit;
    }
    $autostart = ($_POST['autostart'] ?? 'yes') === 'yes' ? 'yes' : 'no';
    file_put_contents($cfg, "TOKEN={$token}\nAUTOSTART={$autostart}\n");
    echo json_encode(['status' => 'ok', 'message' => 'Token saved']);
    break;

  case 'autostart':
    $autostart = ($_POST['autostart'] ?? 'yes') === 'yes' ? 'yes' : 'no';
    $token = '';
    if (file_exists($cfg)) {
      foreach (file($cfg) as $line) {
        if (str_starts_with($line, 'TOKEN=')) { $token = trim(substr($line, 6)); }
      }
    }
    file_put_contents($cfg, "TOKEN={$token}\nAUTOSTART={$autostart}\n");
    echo json_encode(['status' => 'ok', 'message' => 'Autostart ' . $autostart]);
    break;

  case 'start':
    exec("$rc start 2>&1", $out, $ret);
    echo json_encode(['status' => $ret === 0 ? 'ok' : 'error', 'message' => implode("\n", $out)]);
    break;

  case 'stop':
    exec("$rc stop 2>&1", $out);
    echo json_encode(['status' => 'ok', 'message' => implode("\n", $out)]);
    break;

  case 'restart':
    exec("$rc restart 2>&1", $out, $ret);
    echo json_encode(['status' => $ret === 0 ? 'ok' : 'error', 'message' => implode("\n", $out)]);
    break;

  case 'status':
    exec("$rc status 2>&1", $out, $ret);
    $running   = ($ret === 0);
    $installed = trim(shell_exec("/usr/local/bin/cloudflared --version 2>/dev/null | grep -oP '\d+\.\d+\.\d+' | head -1") ?? '');
    $latest    = trim(shell_exec("curl -sf --max-time 5 https://api.github.com/repos/cloudflare/cloudflared/releases/latest | grep -oP '\"tag_name\": \"\\K[^\"]+' | head -1") ?? '');
    echo json_encode([
      'running'   => $running,
      'installed' => $installed,
      'latest'    => $latest,
      'update'    => ($installed !== '' && $latest !== '' && $installed !== $latest),
    ]);
    break;

  default:
    echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
}
