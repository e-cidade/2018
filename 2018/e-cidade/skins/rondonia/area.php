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

    <?php if(pg_numrows($rsInstituicao) > 0):
      db_fieldsmemory($rsInstituicao,0); ?>

      parent.topo.document.getElementById('infoConfig').innerHTML = '<strong><?php echo $nome; ?></strong> (<?php echo $ender; ?> \| '
                                                                    + 'Fone:&nbsp;<?php echo $telef; ?>&nbsp;&nbsp;-&nbsp;&nbsp;Cep:&nbsp;<?php echo $cep; ?>)';
      parent.topo.document.getElementById('linkprefa').href = '<?php echo $url; ?>/dbpref/';
      parent.topo.document.getElementById('linkprefa').target = '_blank';

    <?php endif; ?>

    function js_iniciar() {
      parent.topo.document.getElementById('linkprefa').target = "";
      parent.topo.document.getElementById('linkprefa').href = "javascript:alert('Instituição não selecionada!')";
    }

    function js_status_area(){
      parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;Selecione a Área clicando na figura ou no nome.';
      parent.bstatus.document.getElementById('dtatual').innerHTML  = '<?=(isset($HTTP_SESSION_VARS["DB_datausu"])?date("d/m/Y",db_getsession("DB_datausu")):date("d/m/Y"))  ?>';
      parent.bstatus.document.getElementById('dtanousu').innerHTML = '<?=(isset($HTTP_SESSION_VARS["DB_anousu"])?db_getsession("DB_anousu"):date("Y"))  ?>';
    }
  </script>
  <style type="text/css">
    <!--
    a {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      font-weight: bold;
      color: #C6770D;
      text-decoration: none;
    }
    a:hover {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      font-weight: bold;
      color: #000000;
      text-decoration: underline;
    }
    .bordas {
      border: 3px outset #666666;
      border-left: 2px outset #333333;
      border-top: 2px outset #333333;
    }
    -->
  </style>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#ffffff leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar();"> <!-- show -->
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="left" valign="top" bgcolor="#f4f4f4" background="skins/img.php?file=bgArea.jpg">
        <center>
          <form name="form1" action="corpo.php" method="post" onSubmit="if(document.form1.selectedIndex == 0) { alert('Escolha uma Área'); return false; }">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="430" align="center" valign="middle" bgcolor="#f4f4f4" background="skins/img.php?file=bgArea.jpg"><br>
                        <table border="0" cellspacing="15" cellpadding="5">
                          <tr>

                            <?php
                              for($i = 0;$i < pg_numrows($rsArea);$i++) {

                                echo "<a href=\"corpo.php?".base64_encode("instit=".db_getsession("DB_instit")."&area_de_acesso="
                                     .pg_result($rsArea,$i,"at26_sequencial"))."\"><img src=\"skins/img.php?file="
                                     .trim(pg_result($rsArea,$i,"at25_figura"))."\" alt=\"".pg_result($rsArea,$i,"at25_descr")
                                     ."\" border=\"0\" width=\"125\" height=\"125\"></a>";

                                if( (($i+1) % 3) == 0 && $i > 1)
                                  echo "<br />\n";
                                }
                            ?>

                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </form>
        </center>
      </td>
    </tr>
  </table>
</body>
<script>
  js_status_area();
</script>