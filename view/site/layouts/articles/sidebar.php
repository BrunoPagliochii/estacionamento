<div class="main-sidebar sidebar-style-2">
    <!-- Centralizar -->
    <aside id="sidebar-wrapper">
        <ul class="sidebar-menu">
            <li class="menu-header">Principal</li>
            <li class="dropdown active">

                <a href="javascript:void(0)" class="nav-link has-dropdown">
                    <i data-feather="box"></i>
                    <span>Estoque</span>
                </a>

                <ul class="dropdown-menu">
                    <li class="<?= (($dados['NomePagina'] == 'Cadastro de Cores') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estoque/cores.php">
                            Cores
                        </a>
                    </li>

                    <li class="<?= (($dados['NomePagina'] == 'Cadastro de Grupos') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estoque/grupos.php">
                            Grupos
                        </a>
                    </li>

                    <li class="<?= (($dados['NomePagina'] == 'Cadastro de Unidades de Medida') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estoque/unidadesMedida.php">
                            Unidades de Medida
                        </a>
                    </li>

                    <li class="<?= (($dados['NomePagina'] == 'Cadastro de Tipos de Mercadoria') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estoque/tiposMercadoria.php">
                            Tipos de Mercadoria
                        </a>
                    </li>

                    <li class="<?= (($dados['NomePagina'] == 'Cadastro de Tamanhos') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estoque/tamanhos.php">
                            Tamanhos
                        </a>
                    </li>

                </ul>
            </li>

        </ul>
    </aside>
</div>