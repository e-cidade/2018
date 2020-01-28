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
   redireciona("index.php");
}
$result = db_query("select * from empresa where q02_inscr = $inscricao");
/*
$result = db_query("select issbase.*,cgm.z01_nome as escritorio 
                   from (select *
                         from issbase
				              inner join cgm on q02_numcgm = z01_numcgm
                              left outer join ruas on q02_lograd = j14_codigo
                              left outer join bairro on q02_bairro = j13_codi
						      left outer join escrito on q02_inscr = q10_inscr
                         where q02_inscr = $inscricao) 
						 as issbase
						    left outer join cgm on  q10_numcgm = cgm.z01_numcgm
				   ") or die("Sql : ".pg_ErrorMessage($result));
*/
if (pg_numrows($result) == 0 ){
   msgbox("Verifique Cadastro com a Prefeitura. (1)");
   db_logs("","$inscricao",0,"Inscricao nao Cadastrada. Numero: $inscricao ");
   db_redireciona("centro_pref.php");
}
db_logs("","$inscricao",0,"Inscricao Pesquisada. Numero: $inscricao ");
db_fieldsmemory($result,0);
if (empty($escritorio)){
   $escritorio = 'O PRÓPRIO';
}
/*
$result = db_query("select v29_cep
                   from logcep
				   where v29_codigo = '$q02_lograd' and
				         '$q02_numero' >= v29_inicio and '$q02_numero' <= v29_final") or die("Sql : ".pg_ErrorMessage($result));
if (pg_numrows($result) == 0 ){
   $v29_cep = '';
}else{
  db_fieldsmemory($result,0);
}
$result = db_query("select *
                   from tabativ
				        left outer join ativid on q07_ativid = q03_ativ
				   where q07_inscr = '$inscricao'") or die("Sql : ".pg_ErrorMessage($result));
if (pg_numrows($result) == 0 ){
   msgbox("Verifique Cadastro com a Prefeitura. (2)");
   redireciona("index.php");
}
*/
?>

<html>
<head>
<title>Segunda Via de Alvar&aacute;</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("opcoesdebitospendentes.php");
</script>
<style>
<!--
.tabfonte {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
}
-->
</style>
</head>

<body leftmargin="5" topmargin="5" marginwidth="0" marginheight="0" onLoad="alert('Clique em ALVARÁ para imprimir, ou digite Ctrl P')">

			   
<table width="644" height="951" border="1" cellpadding="3" cellspacing="0" bordercolor="#000000">
  <tr> 
    <td class="tabfonte" height="733" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0" height="692">
        <tr> 
          <td height="478" align="center" valign="middle" class="tabfonte" style="font-size:70px"> 
            <a href="javascript:window.print()" style="text-decoration:none;color:#000000">ALVAR&Aacute;</a> 
            <Br> <Br>
            DE<Br> <BR>
            LICEN&Ccedil;A </td>
        </tr>
        <tr> 
          <td class="tabfonte" height="214"> <table width="100%" height="163" border="1" cellpadding="3" cellspacing="0" bordercolor="#000000">
              <tr> 
                <td class="tabfonte" width="19%" nowrap><strong>Inscri&ccedil;&atilde;o 
                  do Alvar&aacute;:</strong></td>
                <td class="tabfonte" width="81%"> 
                  <?=$inscricao?>
                  - 
                  <script>
              var x = CalculaDV("<?=$inscricao?>",11);
			  document.write(x);
              </script> </td>
              </tr>
              <tr> 
                <td class="tabfonte" width="19%" nowrap><strong>CNPJ/CPF:</strong></td>
                <td class="tabfonte" width="81%"> 
                  <?=$z01_cgccpf?>&nbsp;
                </td>
              </tr>
              <tr> 
                <td class="tabfonte" width="19%" nowrap><strong>Nome do Constribuinte:</strong>&nbsp;</td>
                <td class="tabfonte" width="81%"> 
                  <?=$z01_nome?>&nbsp;

                </td>
              </tr>
              <tr> 
                <td class="tabfonte" width="19%" valign="top" nowrap><strong>Localiza&ccedil;&atilde;o:</strong></td>
                <td class="tabfonte" width="81%"> 
                  <?=$z01_ender?>
                  <br> 
                  <?=$z01_bairro?>
                  - 
                  <?=$z01_cep?>
                  - 
                  <?=$z01_uf?>
                </td>
              </tr>
              <tr> 
                <td class="tabfonte" width="19%" nowrap><strong>Data Inicio:</strong></td>
                <td class="tabfonte" width="81%"> 
                  <?=db_date($q02_dtinic,'/')?>&nbsp;

                </td>
              </tr>
              <?
		  for ($contador=0; $contador < pg_numrows($result); $contador ++){
		     db_fieldsmemory($result,$contador);
		  ?>
              <?
		  }
		  ?>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td class="tabfonte" height="108" valign="middle" align="center"> <table width="100%" border="1" cellpadding="3" cellspacing="0" bordercolor="#000000">
        <tr> 
          <td class="tabfonte" width="9%" height="26">&nbsp;&nbsp;<strong>Codigo 
            </strong> </td>
          <td width="79%" height="26" align="center" class="tabfonte">&nbsp;&nbsp;<strong>Descri&ccedil;&atilde;o 
            Atividade</strong></td>
          <td class="tabfonte" width="12%" height="26" align="center"> <strong>Tipo</strong></td>
        </tr>
        <?
  	    for ($contador=0; $contador < pg_numrows($result); $contador ++){
		   db_fieldsmemory($result,$contador);
		?>
        <tr> 
          <td class="tabfonte" width="9%"> 
            <?=$q07_ativ?>&nbsp;

          </td>
          <td class="tabfonte" width="79%">&nbsp; 
            <?=$q03_descr?>&nbsp;

          </td>
          <td class="tabfonte" align="center" width="12%">&nbsp; 
            <?=$j14_tipo?>&nbsp;

          </td>
        </tr>
        <?
		}
		?>
      </table></td>
  </tr>
  <tr>
    <td class="tabfonte" height="70" style="font-size:11px" valign="middle" align="center">Este documento 
      dever&aacute; ser fixado em lugar visivel no estabelecimento, sob pena de       
      multa cfe. C&oacute;digo Tribut&aacute;rio do Munic&iacute;pio</td>
  </tr>
</table>
</body>
</html>