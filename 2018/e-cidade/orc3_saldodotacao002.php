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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_orcorgao_classe.php"));
include(modification("classes/db_orcunidade_classe.php"));
include(modification("classes/db_orcdotacao_classe.php"));
include(modification("classes/db_orcdotacaocontr_classe.php"));
include(modification("classes/db_orcparametro_classe.php"));
require(modification("libs/db_liborcamento.php"));
$clorcparametro = new cl_orcparametro;
$clorcorgao = new cl_orcorgao;
$clorcunidade = new cl_orcunidade;
$clorcdotacao = new cl_orcdotacao;
$clorcdotacaocontr = new cl_orcdotacaocontr;
$clestrutura = new cl_estrutura;
$clorcorgao->rotulo->label();
$clorcunidade->rotulo->label();
$clorcdotacao->rotulo->label();
$clorcdotacaocontr->rotulo->label();
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
<?
  if(isset($o58_coddot) && $o58_coddot!=""){
     $filtro = " and o58_coddot=$o58_coddot  ";
  }else{
    $filtro = "";
    if(isset($o50_estrutdespesa) && $o50_estrutdespesa!=""){
       $matriz=split('\.',$o50_estrutdespesa);
       for($i=0; $i<count($matriz); $i++){
	 switch($i){
	   case 0://orgao
		$o40_orgao = $matriz[$i];
		break;
	   case 1://unidade
		$o41_unidade = $matriz[$i];
		break;
	   case 2://funcao
		$o52_funcao = $matriz[$i];
		break;
	   case 3://subfuncao
		$o53_subfuncao = $matriz[$i];
		break;
	   case 4://programa
		$o54_programa = $matriz[$i];
		break;
	   case 5://projativ
		$o55_projativ = $matriz[$i];
		break;
	   case 6://elemento de despesa
		$o56_elemento = $matriz[$i];
		break;
	   case 7://tipo de  recurso
		$o58_codigo = $matriz[$i];
		break;
	   case 8://contra recurso
		$o61_codigo = $matriz[$i];
		break;
	 }
       }
    }

     if(!empty($o40_orgao)){
       $filtro .= " and o58_orgao = $o40_orgao ";
     }
     if(!empty($o41_unidade)){
       if($filtro!="")
       $filtro .= " and o58_unidade = $o41_unidade ";
     }
     if(!empty($o52_funcao)){
       $filtro .= " and o58_funcao = $o52_funcao ";
     }
     if(!empty($o53_subfuncao)){
       $filtro .= " and o58_subfuncao = $o53_subfuncao ";
     }
     if(!empty($o54_programa)){
       $filtro .= " and o58_programa = $o54_programa ";
     }
     if(!empty($o55_projativ)){
       $filtro .= " and o58_projativ = $o55_projativ ";
     }
     if(!empty($o56_elemento)){
       $filtro .= " and o56_elemento = '$o56_elemento'";
     }
     if(!empty($o58_codigo)){
       $filtro .= " and o58_codigo = $o58_codigo ";
     }
     if(!empty($o61_codigo)){
       $filtro .= " and o61_codigo = $o61_codigo ";
     }
  }
 $sql = "select fc_estruturaldotacao(".db_getsession("DB_anousu").",o58_coddot) as dl_estrutural,o55_descr::text,o56_descr,o58_coddot
         from orcdotacao d
	      inner join orcprojativ p on p.o55_anousu = ".db_getsession("DB_anousu")." and p.o55_projativ = d.o58_projativ
	      inner join orcelemento e on e.o56_codele = d.o58_codele and e.o56_anousu = d.o58_anousu
         left outer join orcdotacaocontr x on x.o61_anousu = d.o58_anousu and x.o61_coddot=d.o58_coddot ";
 $sql .= " where o58_anousu=".db_getsession("DB_anousu")." and o58_instit=".db_getsession("DB_instit")." $filtro ";
// echo $sql;
  ?>
 <table border='0' width='100%'>
 <tr align='center'>
   <td >
    <input name="retorna" value="Fechar" onclick="parent.db_iframe_pesquisa.hide();" type="button">
   </td>
 </tr>
 <tr align='center'>
   <td>
 <?
 $sql .= " order by dl_estrutural";
 db_lovrot($sql,16,"()","","js_abre|o58_coddot");
 ?>
   </td>
 </tr>
 </table>
</center>
</body>
</html>
<script>
function js_abre(coddot){
  if (typeof coddot === 'undefined' || coddot == '') {
    alert('Código da dotação inválido.');
    return false;
  }
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_orgao','func_saldoorcdotacao.php?coddot='+coddot,'pesquisa',true);
}
</script>