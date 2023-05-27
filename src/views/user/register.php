<h1>Registrarse</h1>

<?php if( isset($_SESSION['register']) && $_SESSION['register'] == 'completed' ) :?>
    <strong class="alert" >Registro completado correctamente</strong>
<?php elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'failed'): ?>
    <strong class="alert alert-error" >Ocurrio un error al intentar registrar el usuario</strong>
<?php endif; ?>
<?=Utils::deleteSession('register');?>

<form action="<?=base_url?>user/save" method="POST">
    <label for="nombre">Nombre</label>
    <input type="text" name="nombre" />

    <label for="apellidos">Apellidos</label>
    <input type="text" name="apellidos" />

    <label for="email">Email</label>
    <input type="email" name="email" />

    <label for="password">Constraseña</label>
    <input type="password" name="password" />

    <input type="submit" value="Registrarse" />
</form>