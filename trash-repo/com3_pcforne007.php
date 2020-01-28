<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require ("libs/db_stdlib.php");
include ("classes/db_pcfornecertif_classe.php");
include ("classes/db_pcfornecertifdoc_classe.php");
include ("classes/db_pctipodoccertif_classe.php");
include ("classes/db_pcdoccertif_classe.php");
require ("libs/db_conecta.php");
include ("dbforms/db_funcoes.php");
$clpcfornecertif = new cl_pcfornecertif;
$clpcfornecertifdoc = new cl_pcfornecertifdoc;
$clpctipodoccertif = new cl_pctipodoccertif;
$clpcdoccertif = new cl_pcdoccertif;
$clrotulo = new rotulocampo;
$clrotulo->label("pc71_codigo");
$clrotulo->label("pc71_descr");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<script>
function js_atualizar(){
  document.form1.pc74_solicitante.value=parent.document.form1.pc74_solicitante.value;
  document.form1.atualizar.value="ok";
  document.form1.submit();
}
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].name.substr(0,1) == "C"){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
function js_marcaob(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].name.substr(0,1) == "O"){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
</script>
<style>
.cabec{
  background-color: #999999;
  text-decoration: underline;
  font-weight: lighter;
}
.corpo{
  border: 1px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #cccccc;
}

.corpoob{
  border: 1px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #cccccc;
}
              
</style>
<body>
  <fieldset>
  <legend style="font-weight: bold;">&nbsp;Documentos&nbsp;</legend>
  <table width="550">
    <tr>
      <td><b>* Documento obrigatório</b></td>
    </tr>
  </table>
  <table style="border: 2px inset white;" cellspacing="0" cellpadding="0" width="550">
    <?
      $sSqlCertificado = $clpctipodoccertif->sql_query(null, "*", "pc72_pcdoccertif", "pc72_pctipocertif=$pc74_pctipocertif");
      $result01        = $clpctipodoccertif->sql_record($sSqlCertificado);
      $numrows01       = $clpctipodoccertif->numrows;
      if ($numrows01 > 0) {

        // Titulos dos Campos a serem mostrados
        echo "<tr>";
        echo "<th class='table_header' align='center' title='$Tpc71_codigo'>".str_replace(":", "", $Lpc71_codigo)."</th>";
        echo "<th class='table_header' align='center' title='$Tpc71_descr'>".str_replace(":", "", $Lpc71_descr)."</th>";
        echo "<th class='table_header' align='center' title='Validade'><b>Validade</b></th>";
        echo "<th class='table_header' align='center' title='Obs'><b>Observação</b></th>";
        echo "</tr>";
      }
      for ($i = 0; $i < $numrows01; $i++) {
        db_fieldsmemory($result01, $i);
        $pc75_validade         = "";      
        $pc75_obs              = "";
        // Condições de Campo a serem buscados
        $sCamposFornecedor     = "pc75_pcdoccertif=$pc72_pcdoccertif and pc75_pcfornecertif = $pc74_codigo";
        // Executa a busca
        $sSqlFornTipoDocumento = $clpcfornecertifdoc->sql_query_file(null, "*", null, $sCamposFornecedor);
        // Result Set da query acima
        $result_docforne       = $clpcfornecertifdoc->sql_record($sSqlFornTipoDocumento);

        if ($clpcfornecertifdoc->numrows > 0){
          db_fieldsmemory($result_docforne, 0);
        } else {
          $pc75_obs="Não apresentado"; 
        }   
        if ($pc72_obrigatorio == 't') {
          $corpo = "corpoob";
          $ast   = "*";
        } else {
          $corpo = "corpo";
          $ast   = "";
        }
        //echo "<tbody class='gridtotalizador'></tbody>";
        echo "<tr class='tr_tab'>
               <td class='linhagrid' align='center' title='$Tpc71_codigo'><small><b>$ast</b>$pc71_codigo</small></td>
               <td class='linhagrid' align='center' title='$Tpc71_descr'><small>$pc71_descr</small></td>
               <td class='linhagrid' align='center' title='Validade' nowrap>".db_formatar($pc75_validade,"d")."&nbsp </td>
               <td class='linhagrid' align='center' title='Observação' nowrap>$pc75_obs&nbsp;</td>
             </tr>";
  }
    ?>
  </table>
  </fieldset>
  </body>
</html>