<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>MEALA - Рецепты</title>
    <style>
            body { margin: 0; font-family: Arial, sans-serif; background: #f9f9f9; }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      background: white;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    nav a {
      margin: 0 10px;
      text-decoration: none;
      color: #333;
      font-weight: bold;
    }
    .btn {
      padding: 8px 16px;
      border-radius: 8px;
      border: 1px solid #28a745;
      background: #28a745;
      color: white;
      cursor: pointer;
    }
    .btn3 {
      padding: 8px 16px;
      border-radius: 8px;
      border: 1px solid #28a745;
      background: red;
      color: white;
      cursor: pointer;
    }
    .btn:hover { background: #218838; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

            header h1 {
                font-size: 22px;
                font-weight: bold;
            }

        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: black;
        }

            nav a:hover {
                text-decoration: underline;
            }

        .btn-login {
            padding: 8px 15px;
            border: 1px solid gray;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            text-decoration: none;
        }

            .btn-login:hover {
                background: #f0f0f0;
            }

        .search {
            text-align: center;
            margin: 20px 0;
        }

            .search input {
                width: 50%;
                padding: 10px;
                border-radius: 8px;
                border: 1px solid gray;
            }

        .container {
            display: flex;
            padding: 20px 30px;
            gap: 20px;
        }

        .filter {
            width: 230px;
            height: 170px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

            .filter h2 {
                font-size: 18px;
                margin-bottom: 10px;
            }

            .filter select {
                width: 100%;
                padding: 8px;
                margin-bottom: 15px;
                border: 1px solid gray;
                border-radius: 6px;
            }

        .recipes {
            width: 80%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.2s;
            position: relative;
        }

            .card:hover {
                transform: translateY(-5px);
            }

            .card img.recipe-img {
                width: 100%;
                height: 180px;
                object-fit: cover;
                border-top-left-radius: 12px;
                border-top-right-radius: 12px;
            }

            .card .info {
                padding: 15px;
            }

            .card h3 {
                margin: 0 0 5px;
                font-size: 18px;
            }

            .card p {
                margin: 3px 0;
                color: gray;
                font-size: 14px;
            }

        .btn-view {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        a{
            text-decoration: none;
            color: black;
        }
            .btn-view:hover {
                background: #218838;
            }
        /* Кнопка "избранное" */
        .favorite {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            padding: 4px;
        }

            .favorite img {
                width: 20px;
                height: 20px;
            }

            .favorite:hover {
                background: #ffefef;
            }
    </style>
</head>
<body>

    <!-- Шапка -->
    <header>
        <h1>MEALA</h1>
        <nav>
            <a href="#">Рецепты</a>
            <a href="#">Кухни</a>
            <a href="#">Авторы</a>
            <a href="#">Избранное</a>
        </nav>
            <div>
      <?php if (isset($_SESSION['username'])): ?>
        👋 Привет, <strong><?php echo $_SESSION['username']; ?></strong> |
        <a href="logout.php" class="btn3">Выйти</a>
      <?php else: ?>
        <a href="login.html" class="btn">Войти</a>
      <?php endif; ?>
    </div>
    </header>

    <!-- Поиск -->
    <div class="search">
        <input type="text" placeholder="Поиск рецепта...">
    </div>

    <div class="container">
        <!-- Фильтр -->
        <aside class="filter">
            <h2>Фильтр</h2>
            <label>Тип рецепта</label>
            <select>
                <option>Все</option>
                <option>Завтрак</option>
                <option>Обед</option>
                <option>Ужин</option>
            </select>

            <label>Кухня</label>
            <select>
                <option>Все</option>
                <option>Японская</option>
                <option>Итальянская</option>
                <option>Казахская</option>
            </select>
        </aside>

        <!-- Карточки рецептов -->
        <section class="recipes">
            <div class="card">
                <button class="favorite">
                    <img src="favorite.png" alt="Избранное">
                </button>
                <img class="recipe-img" src="https://source.unsplash.com/400x300/?salmon,food" alt="Лосось">
                <div class="info">
                    <h3>Миска для лосося терияки</h3>
                    <p>⏱ 30 минут</p>
                    <p>👨‍🍳 Назгуль</p>
                    <a href="#" class="btn-view">Смотреть рецепт</a>
                </div>
            </div>

            <div class="card">
                <button class="favorite">
                    <img src="favorite.png" alt="Избранное">
                </button>
                <img class="recipe-img" src="https://source.unsplash.com/400x300/?eggs,food" alt="Яйца">
                <div class="info">
                    <h3>Спаржа и яйца</h3>
                    <p>⏱ 15 минут</p>
                    <p>👨‍🍳 Назгуль</p>
                    <a href="#" class="btn-view">Смотреть рецепт</a>
                </div>
            </div>

            <div class="card">
                <button class="favorite">
                    <img src="favorite.png" alt="Избранное">
                </button>
                <img class="recipe-img" src="recipe-15145-1.webp" alt="Сэндвич">
                <div class="info">
                    <h3>Сэндвич для бассейна</h3>
                    <p>⏱ 35 минут</p>
                    <p>👨‍🍳 Назгуль</p>
                    <a href="#" class="btn-view">Смотреть рецепт</a>
                </div>
            </div>
        </section>
    </div>

</body>
</html>
