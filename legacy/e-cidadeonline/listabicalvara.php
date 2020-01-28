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
                   WHERE m_arquivo = 'digitainscricao.php'
                   ORDER BY m_descricao
                   ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
  echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
mens_help();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

if (!isset($inscricao) or empty($inscricao)){
   msgbox("Inscrição Inválida.");
   db_logs("","$inscricao",0,"Inscricao Invalida. Numero: $inscricao ");
   redireciona("digitainscricao.php");
}
db_logs("","$inscricao",0,"Inscricao Pesquisada. Numero: $inscricao ");

$result = db_query("select * from empresa where q02_inscr = $inscricao");
if (pg_numrows($result) == 0 ){
   msgbox("Verifique Cadastro com a Prefeitura. (1)");
   db_logs("","$inscricao",0,"Inscricao nao Cadastrada. Numero: $inscricao ");
   redireciona("opcoesalvara.php?".base64_encode("inscricao=".$inscricao));
}
db_fieldsmemory($result,0);
if (empty($escritorio)){
   $escritorio = 'O PRÓPRIO';
}
if(!isset($DB_LOGADO) && $m_publico !='t'){
  $sql = "select fc_permissaodbpref(".db_getsession("DB_login").",2,$inscricao)";
  $resultteste = db_query($sql);
  if(pg_numrows($resultteste)==0){
    db_redireciona("centro_pref.php?".base64_encode('erroscripts=Acesso a rotina inválido.'));
    exit;
  }
  $resultteste = pg_result($result,0,0);
  if($resultteste=="0"){
    db_redireciona("centro_pref.php?".base64_encode('erroscripts=Acesso a rotina inválido.'));
    exit;
  }
} 

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("opcoesdebitospendentes.php");
</script>
<style type="text/css">
<?db_estilosite();
echo"
.tabfonte {
     	  font-family: $w01_fontesite;
	  font-size: $w01_tamfontesite;
	  color: $w01_corfontesite;
          }
    ";
?>
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
		 &nbsp;<a href="digitainscricao.php" class="links">Alvará &gt;</a>
	     &nbsp;<a href="javascript:history.back()" class="links">Opções Alvará &gt;</a>
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
               <table width="100%" height="100%" border="1" bordercolor="#000000" cellpadding="3" cellspacing="0" dwcopytype="CopyTableRow">
                <tr> 
                  <td class="tabfonte" height="422" valign="top">
				  <form name="form1" method="post" action="">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td class="tabfonte" height="25" width="8%"> Inscri&ccedil;&atilde;o:</td>
                          <td  class="tabfonte" height="25" width="55%">&nbsp; <?=$inscricao?> - 
                            <script>
                            var x = CalculaDV("<?=$inscricao?>",11);
			                document.write(x);
                            </script>
                          </td>
                          <td class="tabfonte" height="25" colspan="2"> CGC/CPF:&nbsp;&nbsp;&nbsp;&nbsp; 
                            <?=$z01_cgccpf?>
                          </td>
                        </tr>
                        <tr> 
                          <td class="tabfonte" width="8%" height="24"> Nome:</td>
                          <td class="tabfonte" colspan="2" height="24"> &nbsp; 
                            <?=$z01_nome?>
                          </td>
                          <td class="tabfonte" width="9%" height="24">&nbsp;</td>
                        </tr>
                        <tr valign="top"> 
                          <td class="tabfonte" colspan="4" height="76">
						    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr> 
                                <td class="tabfonte" width="44%" height="38"> 
                                   Endereco do Contribuinte:<br>
                                  &nbsp; 
                                  <?=$z01_ender?>
                                  <br>
                                  &nbsp; 
                                  <?=$z01_bairro?>
                                  <br>
                                  </td>
                                <td class="tabfonte" width="15%" valign="top" height="38" align="left"> N&uacute;mero:</strong><br>
                                    &nbsp;<br>
                                    &nbsp; 
                                    <?=$z01_cep?>
                                </td>
                                <td class="tabfonte" width="3%" valign="bottom" height="38" align="left">&nbsp;</td>
                                <td class="tabfonte" width="38%" height="38" valign="top"> Complemento:<br>
                                  &nbsp;<br>
                                  &nbsp; 
                                  <?=$z01_uf?>
                                  </td>
                              </tr>
                            </table>
                            <br>
                             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr> 
                                <td class="tabfonte" width="44%">  Endere&ccedil;o 
                                  da Inscri&ccedil;&atilde;o:<br>
                                  &nbsp; 
                                  <?=$z01_nome?>
                                  <br>
                                  &nbsp; 
                                  <?=$q03_descr?>
                                  </td>
                                <td class="tabfonte" width="15%" valign="top" align="left"> N&uacute;mero:<br>
                                    &nbsp; 
                                    <?=$z01_numero?>
                                    <br>
                                    &nbsp; 
                                    <?=$z01_cep?>
                                </td>
                                <td class="tabfonte" width="3%" valign="bottom" align="left">&nbsp;</td>
                                <td class="tabfonte" width="38%" valign="top"> Complemento:<br>
                                  &nbsp; 
                                  <?=$z01_compl?>
                                  <br>
                                  &nbsp;RS </td>
                              </tr>
                            </table></td>
                        </tr>
                      </table>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td class="tabfonte" colspan="4"> <hr> </td>
                        </tr>
                        <tr> 
                          <td class="tabfonte" width="18%"> Data Inicio 
                            Atividades:</td>
                          <td class="tabfonte" width="21%"> &nbsp; 
                            <?=db_date($q02_dtinic,'/')?>
                            </td>
                          <td class="tabfonte" width="14%"> Data Baixa:</td>
                          <td class="tabfonte" width="47%">&nbsp; 
                            <?=db_date($q02_dtbaix,'/')?>
                            </td>
                        </tr>
                        <tr> 
                          <td class="tabfonte" width="18%">&nbsp;</td>
                          <td class="tabfonte" width="21%">&nbsp;</td>
                          <td class="tabfonte" width="14%">&nbsp;</td>
                          <td class="tabfonte" width="47%">&nbsp;</td>
                        </tr>
                        <tr> 
                          <td class="tabfonte" width="18%"> Inscri&ccedil;&atilde;o 
                            Estadual:</td>
                          <td class="tabfonte" width="21%"> &nbsp; 
                            <?=$z01_incest?>
                            </td>
                          <td class="tabfonte" width="14%">&nbsp;</td>
                          <td class="tabfonte" width="47%">&nbsp;</td>
                        </tr>
                      </table>
                      <hr>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" height="49">
                        <tr> 
                          <td class="tabfonte" height="62"> Escrit&oacute;rio 
                            Contabil Respons&aacute;vel: 
                            <?=$escritorio?>
                          </td>
                        </tr>
                      </table>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td class="tabfonte" colspan="3"> <hr> </td>
                        </tr>
                        <tr> 
                          <td class="tabfonte" width="9%"> <div align="right"> Codigo</div></td>
                          <td class="tabfonte" width="81%">&nbsp;&nbsp;&nbsp; Descri&ccedil;&atilde;o</td>
                          <td class="tabfonte" width="10%" align="center"> Tipo</td>
                        </tr>
                        <?
		                for($contador=0; $contador < pg_numrows($result); $contador ++){
		                  db_fieldsmemory($result,$contador);
		                  ?>
                          <tr> 
                          <td class="tabfonte" width="9%" align="right"><?=$q07_ativ?></td>
                          <td class="tabfonte" width="81%"> &nbsp;&nbsp; 
                            <?=$q03_descr?>
                          </td>
                          <td class="tabfonte" width="10%" align="center">
                              <?=$j14_tipo?>
                          </td>
                        </tr>
                        <?
		              }
		                ?>
                      </table>
                    </form>
				  </td>
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