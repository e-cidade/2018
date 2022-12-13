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
 if(!session_is_registered("DB_uol_hora")) : ?>

  <head>
    <title>Documento sem t&iacute;tulo</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script>
      function js_iniciar() {
        setTimeout("location.href = 'instit.php'",2000);
      }
    </script>
    <script type="text/javascript" src="skins/js.php?file=jquery-1.7.1.js" ></script>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()">
    <style type="text/css">
      h1{
        color: #666;
        font-weight: normal;
      }
      .progress_container {
          height:25px;
          border-radius: 20px;
          overflow:hidden;
          background:#f9f9f9;
          width: 460px;
          box-shadow:0 0 5px #aaa;
          border:1px solid #ccc;
      }
      .progress_bar {
          height:25px;
          width: 0px;
          -moz-border-radius:20px;
          -webkit-border-radius:20px;
          border-radius:20px;
          background:linear-gradient(to bottom, #F0F0F0, #DBDBDB 70%, #CCCCCC) repeat scroll 0 0 #CCCCCC;
          box-shadow:0 1px rgba(255, 255, 255, 0.3) inset, 0 1px 2px rgba(0, 0, 0, 0.2);
          border:1px solid #ccc;
      }
      .progress_container {
          margin-bottom: 30px;
      }
      .wrapProgress{
        margin: 10px auto;
        width: 460px;
      }
    </style>
    <script type="text/javascript">
      $(function () {
        $('.progress_bar').each(function () {
          $(this).animate({ width: this.title }, 2000);
          setTimeout(sumir, 1700);
        })
      });

      function sumir() {
          $('#geral').fadeOut("slow");
      };
    </script>
    <br><br><br><br><br><br><BR>
    <div id="geral">
      <h1 align="center">Aguarde... </h1>
      <div class="wrapProgress">
        <div class="progress_container">
            <div class="progress_bar tip" title="100%"></div>
        </div>
      </div>
    </div>
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
        parent.topo.document.getElementById('infoConfig').innerHTML = "";

        parent.topo.document.getElementById('linkprefa').target = "";
        parent.topo.document.getElementById('linkprefa').href = "javascript:alert('Instituição não selecionada!')";
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
        <td height="430" align="left" valign="top" bgcolor="#f4f4f4" background="skins/img.php?file=bgArea.jpg">
          <center>
            <form name="form1" action="corpo.php" method="post" onSubmit="if(document.form1.selectedIndex == 0) { alert('Escolha uma instituição'); return false; }">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>

                    <?php if(pg_numrows($rsInstituicoes) == 1 and !$tem_atualizacoes): ?>

                      <input type="hidden" name="instit" value="<?php echo pg_result($rsInstituicoes, 0, "codigo"); ?>">
                      <script>document.form1.submit()</script>

                    <?php else: ?>

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td height="430" align="center" valign="middle" bgcolor="#f4f4f4" background="skins/img.php?file=bgArea.jpg">
                            <table border="0" cellspacing="5" cellpadding="5">
                              <tr>

                              <?php
                                for($i = 0;$i < pg_numrows($rsInstituicoes);$i++) {
                                  echo "<td>
                                        <table width=\"160px\" height=\"125px\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                        <tr><td align=\"center\" valign=\"middle\"><a href=\"corpo.php?".base64_encode("instit=".pg_result($rsInstituicoes,$i,"codigo"))."\"><img src=\"imagens/files/".pg_result($rsInstituicoes,$i,"figura")."\" alt=\"".str_replace(" ", "_", pg_result($rsInstituicoes,$i,"nomeinst"))."\" onmouseover=\"js_msg_status(this.alt)\" onmouseout=\"js_lmp_status()\" border=\"0\" width=\"160\" height=\"125\"></a></td></tr>
                                        <tr><td align=\"center\" valign=\"middle\"><a href=\"corpo.php?".base64_encode("instit=".pg_result($rsInstituicoes,$i,"codigo"))."\" title=\"".pg_result($rsInstituicoes,$i,"nomeinst")."\"  onmouseover=\"js_msg_status(this.title)\" onmouseout=\"js_lmp_status()\">".pg_result($rsInstituicoes,$i,"nomeinst")."</a></td></tr>    
                                        </table>
                                        </td>\n";
                                  if(($i % 3) == 0 && $i > 1)
                                     echo "<br />\n";
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