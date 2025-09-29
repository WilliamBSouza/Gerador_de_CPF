<?php
session_start();

// Initialize game state
if (!isset($_SESSION['board']) || isset($_POST['new_game'])) {
    $_SESSION['board'] = array_fill(0, 9, '');
    $_SESSION['current_player'] = 'X';
    $_SESSION['winner'] = null;
    $_SESSION['message'] = "Vez do jogador X";
}

$board = $_SESSION['board'];
$currentPlayer = $_SESSION['current_player'];
$winner = $_SESSION['winner'];
$message = $_SESSION['message'];

function checkWinner($board)
{
    $lines = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8],
        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8],
        [0, 4, 8],
        [2, 4, 6],
    ];

    foreach ($lines as $line) {
        [$a, $b, $c] = $line;
        if ($board[$a] !== '' && $board[$a] === $board[$b] && $board[$b] === $board[$c]) {
            return $board[$a];
        }
    }

    if (!in_array('', $board, true)) {
        return 'Empate';
    }

    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$winner && isset($_POST['cell'])) {
    $cell = (int) $_POST['cell'];

    if ($cell >= 0 && $cell < 9 && $board[$cell] === '') {
        $board[$cell] = $currentPlayer;
        $winner = checkWinner($board);

        if ($winner) {
            if ($winner === 'Empate') {
                $message = 'O jogo terminou em empate!';
            } else {
                $message = "Jogador {$winner} venceu!";
            }
        } else {
            $currentPlayer = $currentPlayer === 'X' ? 'O' : 'X';
            $message = "Vez do jogador {$currentPlayer}";
        }
    }

    $_SESSION['board'] = $board;
    $_SESSION['current_player'] = $currentPlayer;
    $_SESSION['winner'] = $winner;
    $_SESSION['message'] = $message;

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da Velha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            margin-top: 0;
            color: #333;
        }
        .board {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            gap: 10px;
            margin: 20px auto;
        }
        .cell {
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
            font-weight: bold;
            border-radius: 8px;
            background: #fafafa;
            border: 2px solid #ccc;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }
        .cell:hover {
            background: #e2e2e2;
            transform: translateY(-2px);
        }
        .cell:disabled {
            background: #ddd;
            cursor: default;
            transform: none;
        }
        .message {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #555;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        button {
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            background: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }
        button:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }
        .reset {
            background: #dc3545;
        }
        .reset:hover {
            background: #a71d2a;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Jogo da Velha</h1>
    <div class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <form method="post">
        <div class="board">
            <?php foreach ($board as $index => $value): ?>
                <button
                    class="cell"
                    type="submit"
                    name="cell"
                    value="<?php echo $index; ?>"
                    <?php echo $value !== '' || $winner ? 'disabled' : ''; ?>
                ><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></button>
            <?php endforeach; ?>
        </div>
        <div class="actions">
            <button class="reset" type="submit" name="new_game" value="1">Novo jogo</button>
        </div>
    </form>
</div>
</body>
</html>
