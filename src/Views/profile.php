<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <style>
            .edit-mode {
                display: none;
                margin-top: 5px;
            }

            .edit-input {
                padding: 5px 10px;
                border: 1px solid #007bff;
                border-radius: 4px;
                font-size: 16px;
                width: 200px;
            }

            .global-edit-controls {
                display: none;
                margin-top: 20px;
                text-align: center;
            }

            .save-all-btn, .cancel-all-btn {
                padding: 10px 25px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 16px;
                font-weight: bold;
                margin: 0 10px;
                transition: all 0.3s;
            }

            .save-all-btn {
                background-color: #28a745;
                color: white;
            }

            .save-all-btn:hover {
                background-color: #218838;
                transform: translateY(-2px);
            }

            .cancel-all-btn {
                background-color: #dc3545;
                color: white;
            }

            .cancel-all-btn:hover {
                background-color: #c82333;
                transform: translateY(-2px);
            }

            .edit-mode-btn {
                position: absolute;
                right: 14%;
                background: none;
                border: none;
                color: #007bff;
                cursor: pointer;
                font-size: 16px;
                padding: 5px 15px;
                border-radius: 4px;
                transition: all 0.3s;
            }

            .edit-mode-btn:hover {
                background-color: #007bff;
                color: white;
            }

        </style>
</head>
<body>

<div class="navbar-top">
    <div class="title">
        <h1>Profile</h1>
    </div>
</div>


<div class="sidenav">
    <div class="profile">
        <img src="https://imdezcode.files.wordpress.com/2020/02/imdezcode-logo.png" alt="" width="100" height="100">

        <div class="name">
            <?php require_once '../Controllers/UserController.php'; ?>
            <?php echo $user['name'];?>
        </div>
    </div>

    <div class="sidenav-url">
        <div class="url">
            <div class="menu-item">
                <a href="/profile" class="active">Profile</a>
            </div>
            <hr align="center">

            <div class="menu-item">
                <a href="/catalog" class="catalog">
                    <i class="fa fa fa-xs"></i> Перейти в каталог
                </a>
            </div>
            <hr align="center">
            <div class="menu-item">
                <a href="/cart" class="cart">
                    <i class="fa fa fa-xs"></i> Корзина
                </a>
            </div>
            <hr align="center">
            <div class="menu-item">
                <a href="/user-orders" class="user-orders">
                    <i class="fa fa fa-xs"></i> Мои заказы
                </a>
            </div>

            <hr align="center">

            <div class="menu-item">
                <a href="/logout" class="edit-mode-btn">
                    <i class="fa fa fa-xs"></i> Выйти из профиля
                </a>
            </div>

        </div>
    </div>
</div>

<div class="main">
    <h2>IDENTITY</h2>
    <div class="card">
        <div class="card-body">
                <a href="/profile-edit" class="edit-mode-btn" >
                    <i class="fa fa-pen fa-xs"></i> Редактировать профиль
                </a>
            <table>
                <tbody>
                <tr>
                    <td>Name</td>
                    <td>:</td>
                    <td>
                            <?php echo $user['name'] ?>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td>
                            <?php echo $user['email'] ?>
                </tr>
                <tr>
                    <td>password</td>
                    <td>:</td>
                    <td>
                            ********
                </tr>
                </tbody>
            </table>



        </div>
    </div>
</div>
</body>
</html>

<style>
/* Import Font Dancing Script */
@import url(https://fonts.googleapis.com/css?family=Dancing+Script);

.menu-item {
    margin: 8px 0;
    padding: 5px 0;
}

.menu-item a {
    display: block;
    padding: 8px 15px;
}

* {
margin: 0;
}

body {
background-color: #e8f5ff;
font-family: Arial;
overflow: hidden;
}

/* NavbarTop */
.navbar-top {
background-color: #fff;
color: #333;
box-shadow: 0px 4px 8px 0px grey;
height: 70px;
}

.title {
font-family: 'Dancing Script', cursive;
padding-top: 15px;
position: absolute;
left: 45%;
}

.navbar-top ul {
float: right;
list-style-type: none;
margin: 0;
overflow: hidden;
padding: 18px 50px 0 40px;
}

.navbar-top ul li {
float: left;
}

.navbar-top ul li a {
color: #333;
padding: 14px 16px;
text-align: center;
text-decoration: none;
}

.icon-count {
background-color: #ff0000;
color: #fff;
float: right;
font-size: 11px;
left: -25px;
padding: 2px;
position: relative;
}

/* End */

/* Sidenav */
.sidenav {
background-color: #fff;
color: #333;
border-bottom-right-radius: 25px;
height: 86%;
left: 0;
overflow-x: hidden;
padding-top: 20px;
position: absolute;
top: 70px;
width: 250px;
}

.profile {
margin-bottom: 20px;
margin-top: -12px;
text-align: center;
}

.profile img {
border-radius: 50%;
box-shadow: 0px 0px 5px 1px grey;
}

.name {
font-size: 20px;
font-weight: bold;
padding-top: 20px;
}


.url, hr {
text-align: center;
}

.url hr {
margin-left: 20%;
width: 60%;
}

.url a {
color: #818181;
display: block;
font-size: 20px;
margin: 10px 0;
padding: 6px 8px;
text-decoration: none;
}

.url a:hover, .url .active {
background-color: #e8f5ff;
border-radius: 28px;
color: #000;
margin-left: 14%;
width: 65%;
}

/* End */

/* Main */
.main {
margin-top: 2%;
margin-left: 29%;
font-size: 28px;
padding: 0 10px;
width: 58%;
}

.main h2 {
color: #333;
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
font-size: 24px;
margin-bottom: 10px;
}

.main .card {
background-color: #fff;
border-radius: 18px;
box-shadow: 1px 1px 8px 0 grey;
height: auto;
margin-bottom: 20px;
padding: 20px 0 20px 50px;
}

.main .card table {
border: none;
font-size: 16px;
height: 270px;
width: 80%;
}

.edit {
position: absolute;
color: #e7e7e8;
right: 14%;
}

</style>