<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title>e-Cidade</title>
    <meta charset="iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <link href="imagens/ecidade/favicon.png" rel="icon"  type="image/png" />
    <link href="estilos/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css"/>
    <link href="estilos/login.css" rel="stylesheet" type="text/css"/>

    <script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/jquery-2.1.1.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/jquery-ui-1.10.4.custom.min.js"></script>
  </head>

  <body class="<?php echo $sClassAtiva;?>">

    <div class="container primeiroAcesso">

      <a href="http://www.softwarepublico.gov.br/ver-comunidade?community_id=15315976" title="Entre na comunidade e-Cidade no Portal do Software Público." target="_blank"><img class="logo-ecidade" src="imagens/ecidade/login/logotipo_ecidade.png"/></a>

      <form method="post" name="form1">

        <div class="access-fields">
          <?php
            include_once( $sFormulario );
          ?>
        </div>

        <div class="link-acesso">
          <a href="login.php">Voltar ao Login</a>
        </div>

        <img class="logo-db" src="imagens/ecidade/login/logotipo_dbseller.png">

        <div class="social-midia">
          <p><a href="http://www.dbseller.com.br">www.dbseller.com.br</a><br/>Porto Alegre RS/Brasil</p>
          <a href="http://twitter.com/#!/DBSeller" target="_blank" title="Siga-nos no Twitter" class="icon-twitter"><img src="imagens/ecidade/login/icon_twitter.png"></a>
          <a href="http://www.facebook.com/pages/DBSeller-Sistemas-Integrados/168429383219644" target="_blank" title="Conheça nossa página no Facebook"><img src="imagens/ecidade/login/icon_facebook.png"></a>
        </div>

      </form>

    </div>
  </body>
</html>