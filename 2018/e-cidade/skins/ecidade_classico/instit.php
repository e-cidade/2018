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
 
  /**
   * Aguarda at� que o topo seja carregado e registre esta variavel na sess�o
   */
  if(!session_is_registered("DB_uol_hora")): 
?>

  <head>
    <title>Documento sem t&iacute;tulo</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script>
      function js_iniciar() {
        setTimeout("location.href = 'instit.php'",2000);
      }
    </script>
  </head>
  <body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()">
    <br><br><br><br><br><br><BR>
    <h1 align="center">Aguarde... </h1>
  </body>

<?php else: ?>

  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
        border: 1px solid #000000;
      }
      -->
    </style>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script>
      function js_iniciar() {
        parent.topo.document.getElementById('linkprefa').target = "";
        parent.topo.document.getElementById('linkprefa').href = "javascript:alert('Institui��o n�o selecionada!')";
      }
    </script>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar();">
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
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
          <center>
            <form name="form1" action="corpo.php" method="post" onSubmit="if(document.form1.selectedIndex == 0) { alert('Escolha uma institui��o'); return false; }">
              <table border="0" cellspacing="0" cellpadding="0">
               <tr>
                 <td>

                  <?php if(pg_numrows($rsInstituicoes) == 1 and !$tem_atualizacoes) : ?>

                    <input type="hidden" name="instit" value="<?php echo pg_result($rsInstituicoes, 0, "codigo"); ?>">
                    <script>document.form1.submit()</script>

                  <?php else: ?>

                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td height="430" align="center" valign="middle" bgcolor="#CCCCCC">
                          <table border="1" cellspacing="5" cellpadding="5">
                            <tr>
                            <?php
                              for($i = 0;$i < pg_numrows($rsInstituicoes);$i++) {
                                echo "<td class=\"bordas\">
                                     <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                     <tr><td align=\"center\" valign=\"middle\"><a href=\"corpo.php?".base64_encode("instit=".pg_result($rsInstituicoes,$i,"codigo"))."\"><img src=\"imagens/files/".pg_result($rsInstituicoes,$i,"figura")."\" alt=\"".pg_result($rsInstituicoes,$i,"nomeinst")."\" onmouseover=\"js_msg_status(this.alt)\" onmouseout=\"js_lmp_status()\" border=\"0\" width=\"100\" height=\"100\"></a></td></tr>
                                     <tr><td align=\"center\" valign=\"middle\"><a href=\"corpo.php?".base64_encode("instit=".pg_result($rsInstituicoes,$i,"codigo"))."\" title=\"".pg_result($rsInstituicoes,$i,"nomeinst")."\"  onmouseover=\"js_msg_status(this.title)\" onmouseout=\"js_lmp_status()\">".pg_result($rsInstituicoes,$i,"nomeinst")."</a></td></tr>    
                                     </table>
                                     </td>\n";

                                if(($i % 5) == 0 && $i > 1)
                                   echo "</tr><tr>\n";
                                }
                              echo "</tr>\n";
                            ?>
                          </table>
                        </td>
                      </tr>
                    </table>

                  <?php endif; ?>

                </td>
              </tr>
            </table>
          </form>
        </center>
      </td>
    </tr>
  </table>
</body>

<?php endif; ?>