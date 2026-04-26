<?php
declare(strict_types=1);

function current_user(): ?array { return $_SESSION['user'] ?? null; }
function login_user(array $u): void { $_SESSION['user'] = $u; }
function logout_user(): void { unset($_SESSION['user']); }
