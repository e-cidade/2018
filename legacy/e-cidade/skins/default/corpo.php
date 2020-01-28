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
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script>

    <?php
     if (pg_numrows($rsInstituicao) > 0){

      db_fieldsmemory($rsInstituicao, 0);

      $sInstituicaoNome  = '';
      $sDadosInstituicao = '';

      if( isset($nome) ){
        $sInstituicaoNome  = '<span class="textoNomeInstituicao">' . $nome . '</span>';
      }
      if( isset($ender) ){
        $sDadosInstituicao .= '<span class="textoEnderecoInstituicao">' . $ender . '</span>';
      }
      if( isset($telef) ){
        $sDadosInstituicao .= '<span class="textoDadosInstituicao">Fone: ' . $telef . '</span>';
      }
      if( isset($cep) ){
        $sDadosInstituicao .= '<span class="textoDadosInstituicao">Cep: ' . db_formatar($cep,'cep') . '</span>';
      }

    ?>
      parent.topo.document.getElementById('infoConfig').innerHTML = '<?php echo $sInstituicaoNome . $sDadosInstituicao ?>';
      parent.topo.document.getElementById('linkprefa').href       = '<?php echo $url; ?>/dbpref/';
      parent.topo.document.getElementById('linkprefa').target     = '_blank';

    <?php } ?>

  </script>
  <style type="text/css">
    <!--
    a {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      font-weight: bold;
      color: #00598D;
      text-decoration: none;
    }
    a:hover {
      color: #929196;
    }
    a.headerLink {
      color:#E1DEDE;
    }
    a.headerLink:hover{
      color:#FFFFFF;
    }
    .bordas {
      width: 151px !important;
      padding: 10px;
    }
    .bordas a img {
      padding-bottom:5px;
    }
    -->
  </style>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="backgroundColorDefault" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table height="100%" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="center" valign="middle" class="backgroundColorDefault">
        <table border="0" cellspacing="1" cellpadding="0">
          <tr>
          <?php

            /**
             * Define path do diretorio de imagens
             */
            $aCaminhoImagem = pathinfo( $_SERVER["SCRIPT_FILENAME"] );
            $sCaminhoImagem = $aCaminhoImagem["dirname"] . "/";

            for($i = 0;$i < $iNumRowsModulos; $i++) {

              /**
               * Carrega ou gera imagem do módulo
               */
              if ( trim( pg_result($rsModulos,$i,"imagem") ) == "" ){

                $sNomeImagem = pg_result($rsModulos,$i,"id_item").".png";
                $sLinkImagem = "skins/img.php?file=Modulos/" . $sNomeImagem;

                /**
                 * Busca pelo caminho relativo do arquivo
                 */
                $sLogoImagem = "skins/default/img/Modulos/" . $sNomeImagem ;

                /**
                 * Quando não encontrar imagem configurada deve setar a imagem default
                 */
                if( !file_exists( $sCaminhoImagem . $sLogoImagem ) ){
                  $sLinkImagem = "skins/img.php?file=logoFallBack.png";
                }
              }else{
                $sLinkImagem = pg_result($rsModulos,$i,"imagem");
              }

              echo "<td align=\"center\" class=\"bordas\">
                      <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr>
                          <td align=\"center\" valign=\"middle\"><a title='".pg_result($rsModulos,$i,"help")."' id=\"link\" href=\"modulos.php?".base64_encode("anousu=".pg_result($rsModulos,$i,"anousu")."&modulo=".pg_result($rsModulos,$i,"id_item")."&nomemod=".pg_result($rsModulos,$i,"nome_modulo"))."\"><img src=\"$sLinkImagem\" alt=\"".pg_result($rsModulos,$i,"help")."\" onmouseover=\"js_msg_status(this.alt)\" onmouseout=\"js_msg_status('Selecione o módulo clicando na figura.')\" border=\"0\" ></a></td>
                        </tr>
                        <tr>
                          <td align=\"center\" valign=\"middle\"><a href=\"modulos.php?".base64_encode("anousu=".pg_result($rsModulos,$i,"anousu")."&modulo=".pg_result($rsModulos,$i,"id_item")."&nomemod=".pg_result($rsModulos,$i,"nome_modulo"))."\" title=\"".pg_result($rsModulos,$i,"help")."\"  onmouseover=\"js_msg_status(this.title)\" onmouseout=\"js_msg_status('Selecione o módulo clicando na figura.')\">".pg_result($rsModulos,$i,"descr_modulo")."</a></td>
                        </tr>
                      </table>
                    </td>\n";

              if((($i + 1) % 5) == 0){
                echo "</tr><tr>\n";
              }
            }

            echo "</tr>\n";
          ?>
        </table>
      </td>
    </tr>
  </table>
</body>
<script type="text/javascript">
  parent.bstatus.document.getElementById('st').innerHTML       = '&nbsp;&nbsp;Selecione o módulo clicando na figura.';
  parent.bstatus.document.getElementById('dtatual').innerHTML  = '<?php echo (isset($HTTP_SESSION_VARS["DB_datausu"])?date("d/m/Y",db_getsession("DB_datausu")):date("d/m/Y")) ?>';
  parent.bstatus.document.getElementById('dtanousu').innerHTML = '<?php echo (isset($HTTP_SESSION_VARS["DB_anousu"])?db_getsession("DB_anousu"):date("Y")) ?>';
</script>