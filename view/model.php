<?php

class Model
{
    private $db;

    public function __construct()
    {
        $host = 'localhost';
        $username = 'romario';
        $password = '1234567';
        $dbname = 'sena';

        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }

    public function getUserById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener usuario por ID: " . $e->getMessage());
        }
    }

    public function createUser($username, $email, $password, $birth_day, $direccion)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password, birth_day, direccion) VALUES (:username, :email, :password, :birth_day, :direccion)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':birth_day', $birth_day);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error al crear usuario: " . $e->getMessage());
        }
    }

    public function updateUser($id, $username, $email, $password)
    {
        try {
            $stmt = $this->db->prepare("UPDATE users SET email = :email, username = :username ,password = :password WHERE username = :$id");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error al crear usuario: " . $e->getMessage());
        }
    }

    public function getAllUsers()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener usuarios: " . $e->getMessage());
        }
    }
}

class Controller
{
    public function handleViewRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = new Model();
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $birth_day = $_POST['birth_day'];
            $direccion = $_POST['direccion'];
            $model->createUser($username, $email, $password, $birth_day, $direccion);
        }
        $model = new Model();
        $users = $model->getAllUsers();
        $viewUsers = new ViewUsers();
        $viewUsers->render($users);
    }

    public function handleCreateRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = new Model();
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $birth_day = $_POST['birth_day'];
            $direccion = $_POST['direccion'];
            $model->createUser($username, $email, $password, $birth_day, $direccion);
        }
        $viewCreateUser = new ViewCreateUser();
        $viewCreateUser->render();
    }

    public function handleEditRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
            $model = new Model();
            $user_id = $_POST['edit_user'];
            $user = $model->getUserById($user_id);
            $viewEditUser = new ViewEditUser();
            $viewEditUser->render($user);
        }
    }
}

class ViewUsers
{
    public function render($users)
    {
        echo "<h3>Usuarios:</h3>";
        foreach ($users as $user) : ?>
            <div class="container_table">
                <div class="content_user">
                    <div><?php echo $user['username']; ?></div>
                    <div><?php echo $user['email'] ?></div>
                    <div><?php echo $user['direccion']; ?></div>
                    <div><?php echo $user['birth_day'] ?></div>
                </div>
                <div>
                    <form method="post" action="edit.php"> <!-- Ajusta la acción a tu controlador -->
                        <input type="hidden" name="edit_user" value="<?php echo $user['id']; ?>">
                        <button type="submit">editar</button>
                    </form>
                </div>
            </div>
        <?php
        endforeach;
    }
}

class ViewCreateUser
{
    public function render()
    {
        ?>
        <div class="form">
            <h3>Crear Usuario:</h3>
            <form method="post">
                <div class="form_create">
                    <div class="content_input">
                        <input placeholder="ingrese usuario" type="text" name="username"><br>
                        <input placeholder="ingrese email" type="email" name="email"><br>
                        <input placeholder="ingrese password" type="password" name="password"><br>
                    </div>
                    <div class="content_input">
                        <input placeholder="ingrese fecha de nacimiento" type="date" name="birth_day"><br>
                        <input placeholder="ingrese direccion" type="text" name="direccion"><br>
                    </div>
                </div>
                <input type="submit" value="Crear Usuario">
            </form>
        </div>

    <?php
    }
}

class ViewEditUser
{
    public function render($user)
    {
    ?>
        <div class="form">
            <h3>Editar Usuario:</h3>
            <form method="post" action="update.php"> <!-- Ajusta la acción a tu controlador -->
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                <div class="form_create">
                    <div class="content_input">
                        <input placeholder="ingrese usuario" type="text" name="username" value="<?php echo $user['username']; ?>"><br>
                        <input placeholder="ingrese email" type="email" name="email" value="<?php echo $user['email']; ?>"><br>
                        <input placeholder="ingrese password" type="password" name="password"><br>
                    </div>
                    <div class="content_input">
                        <input placeholder="ingrese fecha de nacimiento" type="date" name="birth_day" value="<?php echo $user['birth_day']; ?>"><br>
                        <input placeholder="ingrese direccion" type="text" name="direccion" value="<?php echo $user['direccion']; ?>"><br>
                    </div>
                </div>
                <input type="submit" value="Actualizar Usuario">
            </form>
        </div>
<?php
    }
}
