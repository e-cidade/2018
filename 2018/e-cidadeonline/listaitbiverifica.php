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

session_start();
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitaitbi.php'
                   ORDER BY m_descricao
                   ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
mens_help();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$cod_matricula = 0 + $matricula;
if ( !is_int($cod_matricula) or $cod_matricula == "" ){
   db_msgbox2("Código Matrícula Inválido.");
   db_logs("","",0,"Matrícula Inválida.");
   redireciona('index.php');
}
$result = db_query("select * from db_itbi where matricula = $cod_matricula and libpref = '1'");
if (pg_numrows($result) > 0){
   db_msgbox2("Socilitação de Guia de ITBI está em processo de avaliação. Volte mais tarde.");
   db_logs("$cod_matricula","",0,"Socilitação de Guia de ITBI está em processo de avaliação. Volte mais tarde. Numero: $cod_matricula");
   redireciona("opcoesitbi.php?".base64_encode("matricula=".$cod_matricula));
}

$result = db_query("select * from db_itbi where matricula = $cod_matricula and ( datavencimento >= CURRENT_DATE or datavencimento is null)");
if (pg_numrows($result) == 0){
   db_msgbox2("Socilitação de Guia de ITBI não Efetuada ou Vencida. Solicite Novamente.");
   db_logs("$cod_matricula","",0,"Socilitação de Guia de ITBI não Efetuada ou Vencida. Solicite Novamente. Numero: $cod_matricula");
   redireciona("opcoesitbi.php?".base64_encode("matricula=".$cod_matricula));
}
db_fieldsmemory($result,0);
db_logs("$cod_matricula","",0,"Verifica Socilitação. Numero: $id_itbi");
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("opcoesitbi.php");
function js_emiteboletoitbi() {
  window.open("reciboitbi.php?itbi=" + document.form1.iditbi.value + "&itbinumpre=" + document.form1.itbinumpre.value,"","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height="+(screen.height-100)+",width="+(screen.width-100));
}

</script>
<style type="text/css">
<?db_estilosite();
?>
font {
     color: black;
     }
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?
mens_div();
?>
<center>
<table width="766" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%" align="left" valign="top"><img src="imagens/cabecalho.jpg"></td>
</tr>
      </table></td>
  </tr>
  <tr>
    <td>
      <table class="bordas" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td nowrap width="90%">
            &nbsp;<a href="index.php" class="links">Principal &gt;</a>
             &nbsp;<a href="digitaitbi.php" class="links">I.T.B.I &gt;</a>
             &nbsp;<a href="javascript:history.back()" class="links">Opções I.T.B.I &gt;</a>
          </td>
          <td align="center" width="10%" onClick="MM_showHideLayers('<?=$nome_help?>','',(document.getElementById('<?=$nome_help?>').style.visibility == 'visible'?'hide':'show'));">
            <a href="#" class="links">Ajuda</a>
          </td>
       </tr>
     </table>  
   </td>
  </tr>
  <tr>
    <td align="left" valign="top">
          <table width="100%" height="313" border="0" cellpadding="0" cellspacing="0">
      <tr>
            <td width="90" align="left" valign="top"> 
          <?    db_montamenus();        
          ?>
                </td>
            <td align="left" valign="top"> 
                          
                                        <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" background="imagens/azul_ceu_O.jpg">
                <tr> 
                  <td height="206" valign="top"> <table width="100%" border="1" cellspacing="0" cellpadding="0">
                      <?
                for ($contador = 0;$contador <= pg_numrows($result)-1;$contador ++ ){
          db_fieldsmemory($result,$contador);
                  if( $numpre <> null ){
                    $imprimeguia = "Reemite Guia de ITBI";
                  }else{
                    $imprimeguia = "Emite Guia de ITBI";
                  }
                ?>
                      <tr> 
                        <td width="24%"><strong><font size="2" color="black" face="Arial, Helvetica, sans-serif">Guia: 
                          </font></strong></td>
                        <td width="76%"> <font size="2" color="black" face="Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$id_itbi?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td width="24%"><strong><font size="2" color="black" face="Arial, Helvetica, sans-serif">Data:</font></strong></td>
                        <td width="76%"><font size="2" color="black" face="Arial, Helvetica, sans-serif">&nbsp; 
                          <?=db_date($dataliber,'/')?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td width="24%"><strong><font size="2" color="black" face="Arial, Helvetica, sans-serif">Valor 
                          da Transa&ccedil;&atilde;o:</font></strong></td>
                        <td width="76%"><font size="2" color="black" face="Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$valortransacao?>
                          </font></td>
                      </tr>
                      <?
                  if( $datavencimento != null ) {
                  ?>
                      <tr> 
                        <td width="24%"><strong><font size="2" color="black" face="Arial, Helvetica, sans-serif">Valor 
                          de Avalia&ccedil;&atilde;o:</font></strong></td>
                        <td width="76%"><font size="2" color="black" face="Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$valoravaliacao?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td width="24%"><strong><font size="2" color="black" face="Arial, Helvetica, sans-serif">Al&iacute;quota:</font></strong></td>
                        <td width="76%"><font size="2" color="black" face="Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$aliquota?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td width="24%"><strong><font size="2" color="black" face="Arial, Helvetica, sans-serif">Valor 
                          &agrave; Recolher:</font></strong></td>
                        <td width="76%"><font size="2" color="black" face="Arial, Helvetica, sans-serif">&nbsp; 
                          <?=$valorpagamento?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td width="24%"><strong><font size="2" color="black" face="Arial, Helvetica, sans-serif">Data 
                          vencimento:</font></strong></td>
                        <td width="76%"><font size="2" color="black" face="Arial, Helvetica, sans-serif">&nbsp; 
                          <?=db_date($datavencimento,'/')?>
                          </font></td>
                      </tr>
                      <tr> 
                        <td width="24%" valign="top"><strong><font size="2" color="black" face="Arial, Helvetica, sans-serif">Observa&ccedil;&otilde;es:</font></strong></td>
                        <td width="76%"> <font size="2" color="black" face="Arial, Helvetica, sans-serif">&nbsp; 
                          <?=substr($obsliber,0,40)?>
                          <br>
                          &nbsp; 
                          <?=substr($obsliber,40,40)?>
                          <br>
                          &nbsp; 
                          <?=substr($obsliber,80,40)?>
                          <br>
                          &nbsp; 
                          <?=substr($obsliber,120,40)?>
                          <br>
                          &nbsp; 
                          <?=substr($obsliber,160,40)?>
                          <br>
                          </font></td>
                      </tr>
                      <?
                  }else{
                  ?>
                      <td width="24%" valign="top"><strong><font size="2" color="black" face="Arial, Helvetica, sans-serif">Observa&ccedil;&otilde;es:</font></strong></td>
                      <td width="76%"> <font size="2" color="black" face="Arial, Helvetica, sans-serif"> 
                        <?
            if( $liberado == null ){
                ?>
                        <font color="black">
                        &nbsp;Enquanto não entrar em processo de Avaliação, os 
                        dados desta Guia de &nbsp;ITBI poderão ser modificados 
                        na seção de SOLICITAÇÃO DE ITBI. 
                        </font>
            <?
                    }
                  }
                  /*
          if($liberado != null) {
                    ?><font color="black">&nbsp;Guia em Processo de Avaliação. Aguarde liberação.</font><?
          }
                  */
                  ?>
           </font>
                   </td width="73%">
           <tr align="center" valign="middle"> 
           <td colspan="2">
                   <font size="2 color="black"" face="Arial, Helvetica, sans-serif"> 
            <?
                        if($datavencimento != null) {
                        ?>
                        <form name="form1" method="post">
              <input type="hidden" name="iditbi" value="<?=$id_itbi?>">
              <input type="hidden" name="itbinumpre" value="<?=$numpre?>">
              <input class="botao" type="button" name="imprimeguia" value="<?=$imprimeguia?>" class="botaoconfirma" onclick="js_emiteboletoitbi()">
                      </form>
            <?
                        }
                        ?>
            <input type="button" class="botao" name="retorna" value="Retorna" class="botaoconfirma" onclick="history.go(-1)">
            </font>
                        </td>
            </tr>
            <?
                }

                ?>
                    </table></td>
                </tr>
              </table>

                          
                          <!-- InstanceEndEditable -->        
            </td>
      </tr>
      </table>
        </td>
  </tr>
</table>
</center>
<?
db_rodape();
?>
</body>
<!-- InstanceEnd --></html>