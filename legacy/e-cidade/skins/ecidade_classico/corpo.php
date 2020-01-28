<?
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

    <?php if (pg_numrows($rsInstituicao) > 0):
      db_fieldsmemory($rsInstituicao, 0); ?>

      parent.topo.document.getElementById('infoConfig').innerHTML = '<?php echo $nome; ?><br><?php echo $ender; ?><br>Fone:&nbsp;'
                                                                    + '<?php echo $telef; ?>&nbsp;&nbsp;-&nbsp;&nbsp;Cep:&nbsp;<?php echo $cep; ?>';
      parent.topo.document.getElementById('linkprefa').href = '<?php echo $url; ?>/dbpref/';
      parent.topo.document.getElementById('linkprefa').target = '_blank';

    <?php endif; ?>

  </script>
  <style type="text/css">
    <!--
    a {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      font-weight: bold;
      color: #FFFFFF;
      text-decoration: none;
    }
    a:hover {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      font-weight: bold;
      color: #FFFFFF;
      text-decoration: none;
    }
    .bordas {
      border: 1px solid #000000;
    }
    -->
  </style>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script>
  </script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table height="100%" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="center" valign="middle" bgcolor="#CCCCCC">
        <table border="0" cellspacing="1" cellpadding="0">
          <tr>
          <?php

            for($i = 0;$i < $iNumRowsModulos;$i++) {

              $sNomeImagem = trim( pg_result($rsModulos,$i,"imagem") );

              if ( $sNomeImagem == "" ) {

                $sNomeModulo = pg_result( $rsModulos, $i, "nome_modulo" );
                $sNomeImagem = "img.php?nome=" . base64_encode(urlencode( $sNomeModulo ));
              }


              echo "<td align=\"center\" valign=\"top\">
              <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"border: 2px solid #cccccc;\" onMouseOver=\"this.bgColor='#c3c3c3'; this.style.border = '2px outset #666666'; this.style.borderLeft = '2px outset #999999'; this.style.borderTop = '2px outset #999999';\" onMouseOut=\"this.bgColor='#999999'; this.style.border = '2px solid #cccccc'\" bgcolor=\"#999999\">
                      <tr>
                  <td>
                          <table border=\"0\" style=\"border: 3px outset #666666; border-left: 2px outset #333333; border-top: 2px outset #333333\" cellspacing=\"0\" cellpadding=\"0\">
                            <tr><td align=\"center\" valign=\"middle\"><a  title='".pg_result($rsModulos,$i,"help")."' id=\"link\" href=\"modulos.php?".base64_encode("anousu=".pg_result($rsModulos,$i,"anousu")."&modulo=".pg_result($rsModulos,$i,"id_item")."&nomemod=".pg_result($rsModulos,$i,"nome_modulo"))."\"><img src=\"imagens/modulos/". $sNomeImagem ." \" alt=\"".pg_result($rsModulos,$i,"help")."\" onmouseover=\"js_msg_status(this.alt)\" onmouseout=\"js_msg_status('Selecione o módulo clicando na figura.')\" border=\"0\" width=\"100\" height=\"100\"></a></td></tr>
                      <tr><td>
                              <table width=\"100%\" style=\"border: 1px solid #666666;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                          <tr><td align=\"center\" valign=\"middle\"><a href=\"modulos.php?".base64_encode("anousu=".pg_result($rsModulos,$i,"anousu")."&modulo=".pg_result($rsModulos,$i,"id_item")."&nomemod=".pg_result($rsModulos,$i,"nome_modulo"))."\" title=\"".pg_result($rsModulos,$i,"help")."\"  onmouseover=\"js_msg_status(this.title)\" onmouseout=\"js_msg_status('Selecione o módulo clicando na figura.')\">".pg_result($rsModulos,$i,"descr_modulo")."</a></td></tr>
                        </table>
                </td></tr>
              </table>
                  </td>
                </tr>
              </table>\n";
              if((($i + 1) % 9) == 0)
                echo "</tr><tr>\n";
            }

            echo "</tr>\n";
          ?>
        </table>
      </td>
    </tr>
  </table>
  <div id="joao"></div>
</body>
<script>
  parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;Selecione o módulo clicando na figura.';
  parent.bstatus.document.getElementById('dtatual').innerHTML  = '<?=(isset($HTTP_SESSION_VARS["DB_datausu"])?date("d/m/Y",db_getsession("DB_datausu")):date("d/m/Y"))  ?>';
  parent.bstatus.document.getElementById('dtanousu').innerHTML = '<?=(isset($HTTP_SESSION_yARS["DB_anousu"])?db_getsession("DB_anousu"):date("Y"))  ?>';
</script>