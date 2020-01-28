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
                   WHERE m_arquivo = 'digitaconsultaprocesso.php'
                   ORDER BY m_descricao
                  ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode("erroscripts=3")."'</script>";
}
mens_help();
db_logs("","",0,"Listando processos.");
db_mensagem("opcoesprocesso_cab","opcoesprocesso_rod");
$db_verifica_ip = db_verifica_ip();
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if ( !isset($cod_processo) ){
 if(isset($HTTP_POST_VARS["cod_processo"])){
   $cod_processo =  $HTTP_POST_VARS["cod_processo"];
   $cod_processo = 0 + $cod_processo;
 }
}else{
  $cod_processo = 0 + $cod_processo;
}
if (!isset($cod_processo) or !is_int($cod_processo) or $cod_processo == "" ){
   msgbox("Código Inválido.");
   db_logs("","",0,"Código do Processo Invalido. Numero: $cod_processo ");
   db_redireciona("digitaconsultaprocesso.php?".base64_encode("erroscripts=Código do Processo Inválido. Número: $cod_processo"));
}

$cgc = $HTTP_POST_VARS["cgc"];
$cpf = $HTTP_POST_VARS["cpf"];
if (!empty($cgc) ){
  $cgccpf = $cgc;
} else {
  if (!empty($cpf) ) {
    $cgccpf = $cpf;
  } else {
    $cgccpf = "xxxxxxxxxxxxxx";
  }
}
$cgccpf = str_replace(".","",$cgccpf);
$cgccpf = str_replace("/","",$cgccpf);
$cgccpf = str_replace("-","",$cgccpf);  
if (!isset($cgccpf) or empty($cgccpf) ){
  db_logs("","",0,"Variavel CGCCPF Invalida.");
  db_redireciona("digitaconsultaprocesso.php?".base64_encode("erroscripts=Variável CNPJ/CPF Inválida."));
}
$result = db_query("select ident from db_config");
if (pg_numrows($result) == 0){
   $ident = 0;
}
db_fieldsmemory($result,0);
$sql_exe = "select * from  protprocesso
             inner join procandam on p58_codandam = p61_codandam
             inner join tipoproc on p58_codigo = p51_codigo
             inner join cgm on p58_numcgm = z01_numcgm
             inner join db_depart on p61_coddepto = coddepto
            where p58_codproc = $cod_processo
              and trim(z01_cgccpf) = '$cgccpf'
           ";
/*
if($cgccpf != "" ) {
  $sql_exe = $sql_exe . " and trim(z01_cgccpf) = '$cgccpf'";
}
*/
$result = db_query($sql_exe);
if (pg_numrows($result) == 0 ){
  db_logs("$cod_processo","",0,"Dados Inconsistentes. Processo : $cod_processo");
  db_redireciona("digitaconsultaprocesso.php?".base64_encode("erroscripts=Processo não Cadastrado. Número: $cod_processo, verifique CNPJ/CPC"));
  $script = false; 
}else if(pg_result($result,0,"z01_cgccpf") == "00000000000000" || pg_result($result,0,"z01_cgccpf") == "              " || trim(pg_result($result,0,"z01_cgccpf")) !=  "$cgccpf" ) {
  $script = true; 
}
db_fieldsmemory($result,0);
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("digitaconsultaprocesso.php");
</script>
<style type="text/css">
<?
db_estilosite();
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?mens_div();?>
<center>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
  <tr>
    <td height="50" align="<?=$DB_align1?>">
      <?=$DB_mens1?>
    </td>
  </tr>
  <tr>
   <td align="center" valign="middle">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="texto">
     <tr>
      <td valign="top">
       <table width="99%" border="0" cellspacing="0" cellpadding="0" class="texto">
        <?if( $ident != 2 ){?>
        <tr>
         <td valign="top" align="left">
          <table width="100%" style="border: 1px solid black" cellspacing="3" cellpadding="0" class="texto">
           <tr>
            <td width="130">Processo:</td>
            <td colspan="3" align="left">
             <?=$cod_processo?>
             -
             <script>
              var x = CalculaDV("<?=$cod_processo?>",11);
              document.write(x);
             </script>
            </td>
           </tr>
           <tr>
            <td colspan="1" align="left">Data Solicita&ccedil;&atilde;o:</td>
            <td colspan="3" align="left"><?=db_formatar($p58_dtproc,'d')?></td>
           </tr>
           <tr>
            <td>Requerente:</td>
            <td colspan="3"><?=$z01_nome?></td>
           </tr>
           <tr>
            <td>Solicitante:</td>
            <td colspan="3"><?=$p58_requer?></td>
           </tr>
           <tr>
            <td height="30" valign="top">Anota&ccedil;&otilde;es:</td>
            <td colspan="3" valign="top"><?=$p58_obs?></td>
           </tr>
           <tr>
            <td colspan="3">
             <fieldset width="100%" style="border: 1px solid black">
             <legend><strong>Situação Atual</strong></legend>
             <table class="texto">
              <tr>
               <td width="120" colspan="1">Departamento</td>
               <td colspan="3">
                <?=$descrdepto?>
               </td>
              </tr>
              <tr nowrap>
               <td nowrap>Data:</td>
               <td>
                <?=db_formatar($p61_dtandam,'d')?>
               </td>
              </tr>
              <tr>
               <td>Despacho:</td>
               <td><?=$p61_despacho?></td>
              </tr>
              <tr>
               <td>Observações:</td>
               <td colspan="3"><?=$p58_despacho?></td>
              </tr>
             </table>
            </fieldset>
            </td>
           </tr>
          </table>
          </td>
         </tr>
         <?}?>
         <tr>
          <td align="center" valign="top">
           <table width="100%" border="1" cellspacing="0" cellpadding="0" class="texto">
            <?
            $totreg = pg_numrows($result) - 1;
            for ($contador=0;$contador <= $totreg;$contador ++) {
              if ( ($totreg == 0) and ( $p58_codigo == "" ) ) {
                ?>
                <tr>
                 <td colspan="3" align="center">PROCESSO
                  SEM ANDAMENTO
                 </td>
                </tr>
                <?
              break;
              }
            }
          ?>
           </table>
          </td>
         </tr>
        </table>
        </td>
       </tr>
      </table>
      </td>
     </tr>
     <tr>
      <td height="50" align="<?=$DB_align2?>">
       <?=$DB_mens2?>
      </td>
     </tr>
    </table>
   </td>
 </tr>
</table>
</center>