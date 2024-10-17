<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        <div class="sidebar-brand h-100">
            <a href="<?= URL_BASE_HOST ?>/view/admin/index.php">
                <div class="sidebar-user-picture">
                    <img width="150" alt="image" class="img-fluid" src="<?= URL_BASE_HOST ?>/public/assets/img/logo.png" />
                </div>
            </a>
        </div>

        <div class="sidebar-user">
            <div class="sidebar-user-details">
                <div class="user-name">
                    <?= $NOMEUSUARIOMODEL ?>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="dropdown <?= (($dados['MenuModulo'] == 'Geral') ? 'active' : '') ?>">

                <a href="javascript:void(0)" class="nav-link has-dropdown">
                    <i data-feather="settings"></i>
                    <span>Geral</span>
                </a>

                <ul class="dropdown-menu">

                    <li class="<?= (($dados['NomePagina'] == 'Estacionamentos') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/index.php">
                            Estacionamentos
                        </a>
                    </li>

                    <li class="<?= (($dados['NomePagina'] == 'Cadastro de Usuários') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/usuarios.php">
                            Usuários
                        </a>
                    </li>

                    <li class="<?= (($dados['NomePagina'] == 'Estacionamentos finalizados') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estacionamentos.php">
                            Estacionamentos finalizados
                        </a>
                    </li>

                </ul>
            </li>

        </ul>
    </aside>
</div>