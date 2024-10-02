<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= URL_BASE_HOST ?>/view/admin/index.php"> <img alt="image" src="<?= URL_BASE_HOST ?>/public/template/assets/img/logo.png" class="header-logo" /> <span class="logo-name">Aegis</span>
            </a>
        </div>
        <div class="sidebar-user">
            <div class="sidebar-user-picture">
                <img alt="image" src="<?= URL_BASE_HOST ?>/public/template/assets/img/userbig.png">
            </div>
            <div class="sidebar-user-details">
                <div class="user-name">
                    <?= $NOMEUSUARIOMODEL ?>
                </div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Principal</li>
            <li class="dropdown <?= (($dados['MenuModulo'] == 'Estoque') ? 'active' : '') ?>">

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

                    <li class="<?= (($dados['NomePagina'] == 'Gerenciar Produtos') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estoque/produtos.php">
                            Produtos
                        </a>
                    </li>

                    <li class="<?= (($dados['NomePagina'] == 'Composição') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/estoque/composicao.php">
                            Composição
                        </a>
                    </li>

                </ul>
            </li>

            <li class="dropdown <?= (($dados['MenuModulo'] == 'Pessoas') ? 'active' : '') ?>">

                <a href="javascript:void(0)" class="nav-link has-dropdown">
                    <i data-feather="users"></i>
                    <span>Pessoas</span>
                </a>

                <ul class="dropdown-menu">
                    <li class="<?= (($dados['NomePagina'] == 'Gerenciamento de Pessoas') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/pessoas/pessoas.php">
                            Pessoas
                        </a>
                    </li>

                </ul>
            </li>

            <li class="dropdown <?= (($dados['MenuModulo'] == 'Financeiro') ? 'active' : '') ?>">

                <a href="javascript:void(0)" class="nav-link has-dropdown">
                    <i data-feather="dollar-sign"></i>
                    <span>Financeiro</span>
                </a>

                <ul class="dropdown-menu">
                    <li class="<?= (($dados['NomePagina'] == 'Formas de Pagamento') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/financeiro/formasPagamento.php">
                            Formas de Pagamento
                        </a>
                    </li>
                </ul>

            </li>

            <li class="dropdown <?= (($dados['MenuModulo'] == 'Comercial') ? 'active' : '') ?>">

                <a href="javascript:void(0)" class="nav-link has-dropdown">
                    <i data-feather="tag"></i>
                    <span>Comercial</span>
                </a>

                <ul class="dropdown-menu">
                    <li class="<?= (($dados['NomePagina'] == 'Pedidos / Orçamentos') ? 'active' : '') ?>">
                        <a class="nav-link" href="<?= URL_BASE_HOST ?>/view/admin/comercial/davs.php">
                            Pedidos / Orçamentos
                        </a>
                    </li>
                </ul>

            </li>

        </ul>
    </aside>
</div>