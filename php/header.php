<?php
  $PagAt = ($PagAt == NULL) ? 0 : $PagAt;
?><header>
  <nav class="menu">
    <h1 class="name"><a href="<?php echo $urlPrincipal[0]; ?>"><i class="fi-burst"></i> <?php echo strtoupper($nomeSite); ?></a></h1>
    <ul class="inline-list">
      <li<?php if($PagAt == 0){ ?> class="active"<?php } ?>><a href="<?php echo $urlPrincipal[0]; ?>">Início</a></li>
      <li<?php if($PagAt == 1){ ?> class="active"<?php } ?>><a href="<?php echo $urlPrincipal[1]; ?>vendas/">Venda</a></li>
      <li<?php if($PagAt == 2){ ?> class="active"<?php } ?>><a href="<?php echo $urlPrincipal[1]; ?>CRT/">CRT</a></li>
      <li<?php if($PagAt == 3){ ?> class="active"<?php } ?>><a href="<?php echo $urlPrincipal[1]; ?>empresas/">Empresas</a></li>
      <li<?php if($PagAt == 6){ ?> class="active"<?php } ?>><a href="<?php echo $urlPrincipal[1]; ?>eventos/">Eventos</a></li>
      <li<?php if($PagAt == 4){ ?> class="active"<?php } ?>><a href="<?php echo $urlPrincipal[1]; ?>relatorios/">Relatórios</a></li>
      <li<?php if($PagAt == 5){ ?> class="active"<?php } ?>><a href="<?php echo $urlPrincipal[1]; ?>configuracoes/">Configurações</a></li>
    </ul>
    <ul class="inline-list hide-for-small-only account-action">
      <?php
        if($factory->getObjeto("login")->isLogado()){
          $dados = $factory->getObjeto("login")->getDadosUser();
          ?><li><a href="#" data-open="modalLogin">Olá <?php echo $dados["nome"]; ?>!</a></li>
          <li><a href="#" data-open="modalLogout">Sair</a></li><?php
        }else{
      ?><li><a href="#"  data-open="modalLogin">Login</a></li><?php } ?>
    </ul>
  </nav>

    <!-- modal content -->

    <div class="reveal" id="modalLogin" data-reveal>
      <div class="row">
        <div class="large-48 columns auth-plain">
          <div class="signup-panel left-solid">
            <p class="welcome">Login</p>
            <div class="callout alert displayNone" id="alertaLogin">
              <h5>ERRO!</h5>
              <p></p>
            </div>
            <div class="row collapse">
              <div class="small-2 columns">
                <span class="prefix"><i class="fi-torso-female loginIcons"></i></span>
              </div>
              <div class="small-46 columns">
                <input type="text" name="login" id="loginLogin" placeholder="login">
              </div>
            </div>
            <div class="row collapse">
              <div class="small-2 columns">
                <span class="prefix"><i class="fi-lock loginIcons"></i></span>
              </div>
              <div class="small-46 columns">
                <input type="password" name="senha" id="senhaLogin" placeholder="senha">
              </div>
            </div>
            <a href="#" class="button" id="loginButton">Login </a>
            <input type="hidden" name="urlSiteP" id="urlSiteP" value="<?php echo $urlPrincipal[1]; ?>">
          </div>
        </div>
       </div>
       <small>Para fechar essa caixa, clique em qualquer parte da página fora dela.</small>
    </div>
      <!-- modal content -->

    <div class="reveal" id="modalUsuario" data-reveal>
      <div class="row">
        <div class="large-48 columns auth-plain">
          <div class="signup-panel left-solid">
            <p class="welcome">Login</p>
            <div class="callout alert displayNone" id="alertaLogin">
              <h5>ERRO!</h5>
              <p></p>
            </div>
            <div class="row collapse">
              <div class="small-2 columns">
                <span class="prefix"><i class="fi-torso-female loginIcons"></i></span>
              </div>
              <div class="small-46 columns">
                <input type="text" name="login" id="loginLogin" placeholder="login">
              </div>
            </div>
            <div class="row collapse">
              <div class="small-2 columns">
                <span class="prefix"><i class="fi-lock loginIcons"></i></span>
              </div>
              <div class="small-46 columns">
                <input type="password" name="senha" id="senhaLogin" placeholder="senha">
              </div>
            </div>
            <a href="#" class="button" id="loginButton">Login </a>
          </div>
        </div>
       </div>
       <small>Para fechar essa caixa, clique em qualquer parte da página fora dela.</small>
    </div>

    <div class="reveal" id="modalLogout" data-reveal>
      <div class="row">
        <div class="large-48 columns auth-plain">
          <div class="signup-panel left-solid text-center">
            <p class="welcome">Deseja realmente sair?</p>
            <div class="row">
              <div class="medium-24 columns">
                <a href="#" class="success expanded button" onClick="sair(1,'<?php echo $urlPrincipal[1]; ?>')">Sim </a>
              </div>
              <div class="medium-24 columns">
                <a href="#" class="alert expanded button" onClick="sair(0)">Não </a>
              </div>
            </div>
          </div>
        </div>
       </div>
       <small>Para fechar essa caixa, clique em qualquer parte da página fora dela.</small>
    </div>
</header>
