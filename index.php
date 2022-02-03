<?php
    // variables to hold error messages
    $nameErr = $descriptionErr = '';

    // variables to hold user input
    $name = $description = '';

    // variable to keep track of input errors
    // 0 => success, 1 => failure
    // only criteria for this form is that all fields are filled in
    $submitOk = 0;

    // run if form is submitted
    if(isset($_POST['submit'])) {
        // check name input
        if(!empty($_POST['name'])) {
            $name = htmlspecialchars($_POST['name']);
        } else {
            // error
            $nameErr = 'Please enter a name.';
            $submitOk = 1;
        }

        // check description input
        if(!empty($_POST['description'])) {
            $description = htmlspecialchars($_POST['description']);
        } else {
            // error
            $descriptionErr = "Don't forget a description!";
            $submitOk = 1;
        }

        // if submision was successful, create a todo
        if($submitOk == 0) {
            createTodo($name, $description);
            $name = '';
            $description = '';
        }
    } elseif (isset($_POST['delete'])) { // check if user deleted todos
        deleteAllCookies();
    }

    function createTodo($todoName, $todoDesc) {
        // create todo array
        $newTodo = array(
            'name' => $todoName,
            'description' => $todoDesc
        );
        // serialize array so it can be stored as a cookie
        $newTodo = serialize($newTodo);

        // remove spaces in todo name so it can be stored as cookie name
        $todoName = str_replace(' ', '', $todoName);

        // store todo
        setcookie($todoName, $newTodo, time() + (3600));

        // refresh the page so todos disappear
        header("Refresh:0");
    }

    function deleteAllCookies() {
        // loop through all cookies and delete them
        foreach( $_COOKIE AS $cookie) {
            $cookie = unserialize($cookie);
            setcookie($cookie['name'], '', time() - 3600);
        }
        // refresh the page so todos appear
        header("Refresh:0");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
</head>
<body>
    <header>
        <h1>Todo List</h1>
        <hr>
    </header>

    <section class="form-section">
        <h2>Create a todo</h2>
        <form action="<?php $_SERVER['PHP_SELF']; ?>" method='POST'>
            <div>
                <label for="name">Todo name: </label>
                <input type="text" name="name" value="<?php echo $name ?>">
                <span><?php echo $nameErr; ?></span>
            </div>
            <div>
                <label for="description">Description: </label>
                <textarea name="description"><?php echo $description ?></textarea>
                <span><?php echo $descriptionErr; ?></span>
            </div>
            <input type="submit" value="submit" name="submit">
        </form>
    </section>

    <section class="todo-section">
        <h2>Todos</h2>
        <?php
            if ($submitOk == 0) {
                $index = 1;
                foreach($_COOKIE as $todo) {
                    echo '<h3>Todo ' .$index. '</h3>';
                    $todo = unserialize($todo);
                    echo 'Name: ' .$todo['name']. ' ';
                    echo 'Description: ' .$todo['description']. '<br>';
                    $index++;
                }
            }
        ?>
        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
            <button type="submit" name="delete">Delete all todos</button>
        </form>
    </section>
    
</body>
</html>