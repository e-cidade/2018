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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_rhrubricas_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_GET_VARS,2);

$clrhrubricas = new cl_rhrubricas;
$clrhrubricas->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("rh137_tipodocumento");
$clrotulo->label("rh137_numero");
$clrotulo->label("rh137_datainicio");
$clrotulo->label("rh137_datafim");
$clrotulo->label("rh137_descricao");


if(!isset($iAno) || (isset($iAno) && trim($iAno)=="")){
  $iAno = db_anofolha();
}
if(!isset($iMes) || (isset($iMes) && trim($iMes)=="")){
  $iMes = db_mesfolha();
}

/*
 * Buscamos os dados da rubrica, funcamentação legal e os pontes existentes para a rubrica
 */

$sCamposRubricas = " rh27_rubric,
                     rh27_descr,
                     case
                        when rh27_pd = 1 then 'Provento'
                        when rh27_pd = 2 then 'Desconto'
                        else 'Base'
                     end as tipo_rubrica,
                     rhfundamentacaolegal.*";
$rsDadosRubrica = $clrhrubricas->sql_record($clrhrubricas->sql_query($rubrica,db_getsession("DB_instit"),$sCamposRubricas));
if($clrhrubricas->numrows > 0){
	db_fieldsmemory($rsDadosRubrica,0);
}else{
	db_msgbox("Rubrica inexistente");
	echo "<script>location.href = 'pes3_codfinanc001.php'</script>";
}

function montaLinkPontos($iRubrica, $iAno, $iMes) {
	
	$sQuery = "";
	 
	$aPontos = array();
	$aPontos["gerfsal"]["sigla"] = "r14";
	$aPontos["gerfsal"]["descricao"] = "SALÁRIO";
	
	$aPontos["gerffer"]["sigla"] = "r31";
	$aPontos["gerffer"]["descricao"] = "FÉRIAS";
	
	$aPontos["gerfres"]["sigla"] = "r20";
	$aPontos["gerfres"]["descricao"] = "RESCISÃO";
	
	$aPontos["gerfadi"]["sigla"] = "r22";
	$aPontos["gerfadi"]["descricao"] = "ADIANTAMENTO";
	
	$aPontos["gerfs13"]["sigla"] = "r35";
	$aPontos["gerfs13"]["descricao"] = "13º SALÁRIO";
	
	$aPontos["gerfcom"]["sigla"] = "r48";
	$aPontos["gerfcom"]["descricao"] = "COMPLEMENTAR";
	
	$aPontos["gerffx" ]["sigla"] = "r53";
	$aPontos["gerffx"]["descricao"] = "PONTO FIXO";
	
	foreach($aPontos as $sPonto => $aDados) {
		$sWhere = "";
		$sWhere  = "{$aDados["sigla"]}_rubric = '{$iRubrica}'";
		$sWhere .= " and {$aDados["sigla"]}_anousu = {$iAno}";
		$sWhere .= " and {$aDados["sigla"]}_mesusu = {$iMes}";
		$sWhere .= " and {$aDados["sigla"]}_instit = ".db_getsession("DB_instit");
		$sSqlPonto = "select *
	                	from {$sPonto}
		               where {$sWhere}";
		$rsDadosPonto = db_query($sSqlPonto);
		if (pg_num_rows($rsDadosPonto) > 0) {
			
			if ($sQuery == "") {
			  $sQuery = "?sigla={$aDados["sigla"]}&arquivo={$sPonto}&rubrica={$iRubrica}&ano={$iAno}&mes={$iMes}";
			  echo " <script>"; 
			  echo "   $('sigla').value='{$aDados["sigla"]}';";
			  echo "   $('arquivo').value='$sPonto';";
			  echo " </script>";	
			}
			
			echo "<tr>";
			echo "  <td class='links2'>";
		  echo "    <a class='links2' href='pes3_codfinanc021.php?sigla={$aDados["sigla"]}&arquivo={$sPonto}&rubrica={$iRubrica}&ano={$iAno}&mes={$iMes}' 
		               target='registros' 
		               onclick=\"$('sigla').value='{$aDados["sigla"]}'; $('arquivo').value='$sPonto';\"> {$aDados["descricao"]} </a>";
		  echo "  </td>";
		  echo "<tr>";
		}
	
	}
	
	return $sQuery;
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
    .links2 {
    	font-weight: bold;
    	color: #0587CD;
    	text-decoration: none;
    	font-size:10px;
    }
    a.links2:hover {
        color:black;
    	text-decoration: underline;
    }
  </style>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
 <br>
<div align="center">
<form name="form1">
 <input type="hidden" id="rubrica" name="rubrica" value="<?=$rubrica?>">
 <input type="hidden" id="sigla"   name="sigla"   value="<?=@$sigla?>">
 <input type="hidden" id="arquivo" name="arquivo" value="<?=@$arquivo?>">
 <fieldset style="width: 1100px; height:835px;">
  <legend><b>Ficha Financeira por Código</b></legend>
  <table width="100%" height="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td width="70%">
        <fieldset>
         <legend> <b>Dados da Rubrica</b> </legend>
          <table width="100%">
            <tr>
              <td width="20%"><?=$Lrh27_rubric?></td>
              <td width="80%">
                <?
                  db_input('rh27_rubric', 8, $Irh27_rubric, true, 'text', 3); 
                  db_input('rh27_descr', 30, $Irh27_descr, true, 'text', 3);
                ?>  
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
               <fieldset>
                <legend><b>Fundamentação Legal</b></legend>
                <table>
                 <tr>
                   <td><b>Tipo de Documento:</b></td>
                   <td>
                     <?
                       $x = array('1' => 'Decreto',
                     		          '2' => 'Decreto Lei',
                     		          '3' => 'Emenda Constitucional',
                     		          '4' => 'Instrução Normativa',
                     		          '5' => 'Lei',
                     		          '6' => 'Medida Provisória',
                     		          '7' => 'Nota',
                     		          '8' => 'Ordem de Serviço',
                     		          '9' => 'Portaria',
                     		          '10' => 'Resolução');
                       db_select('rh137_tipodocumentacao', $x, true, 3, "")
                     ?> 
                   </td>
                   <td><b>Número:</b></td>
                   <td>
                    <?php
                     db_input('rh137_numero', 10, $Irh137_numero, true, 'text', 3,"");
                    ?>
                   </td>
                 </tr>
                 <tr>
                   <td> <b>Data Início: </b> </td>
                   <td> 
                     <?php db_inputdata( 'rh137_datainicio', @$rh137_datainicio_dia, @$rh137_datainicio_mes, @$rh137_datainicio_ano, true, 'text', 3, ""); ?> 
                   </td>
                   <td> <b> Data Fim:</b> </td>
                   <td> 
                     <?php db_inputdata( 'rh137_datafim', @$rh137_datafim_dia, @$rh137_datafim_mes, @$rh137_datafim_ano, true, 'text', 3, ""); ?> 
                   </td>
                 </tr>
                 <tr>
                   <td colspan="4">
                     <fieldset>
                       <legend> Observações</legend>
                       <?php db_textarea('rh137_descricao', 1, 65, $Irh137_descricao, true, 'text', 3, ""); ?>
                     </fieldset>
                   </td>
                 </tr>
                </table>
               </fieldset> 
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
      <td width="40%" valign="top"> 
        <fieldset style="height:92%">
          <legend><b><?=$tipo_rubrica?></b></legend>
          <table id="pontos">
           <? $sQuery = montaLinkPontos($rubrica, $iAno, $iMes); ?>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan="2" height="80%" valign="top">
        <fieldset style="width: 98%; height:97%">
          <legend> <b> Registros </b> <b id="ctnRegistros"></b></legend>
           <iframe id="registros" height="100%" width="100%" name="registros" src="pes3_codfinanc021.php<?=$sQuery?>"></iframe>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan=2 align="center">
        <table>
          <tr>
            <?
            
            if(!isset($chamada_origem)){
            	$chamada_origem = 'pes3_codfinanc001.php';
            } else {
            	$chamada_origem .= "?ano=$iAno&mes=$iMes&r01_regist=$r01_regist&xopcao=$xopcao";
              db_input("xopcao"    ,"",null,true,"hidden");
              db_input("r01_regist","",null,true,"hidden");
              db_input("chamada_origem","",null,true,"hidden");
            }
            ?>
            <td>
              <input name="retornar" type="button" id="retornar" value="Voltar" title="Retornar" onclick="location.href='<?=$chamada_origem?>'">
            </td>
            <td>
              <input name="pesquisar" type="submit" id="pesquisar"  title="Atualiza a Consulta" value="Atualizar">
            </td>
            <td>
              <input name="imprimir" type="button" id="imprimir" value="Imprimir" title="Imprimir" onclick="js_relatorio();">
            </td>
            <td>
              <strong>Período:</strong> 
              <?
    	         db_input("iAno",4,'',true,'text',4);
    	         echo "/";
	             db_input("iMes",2,'',true,'text',4)
	            ?>  
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
 </fieldset>
</form> 
</div>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
  function js_relatorio() {
	  
	  var sUrl = "pes2_codfinanc002.php?";
	      sUrl = sUrl+"sigla="+$F("sigla");
	      sUrl = sUrl+"&arquivo="+$F("arquivo");
	    	sUrl = sUrl+"&rubrica=<?=$rubrica?>";
	      sUrl = sUrl+"&ano=<?=$iAno?>"
	    	sUrl = sUrl+"&mes=<?=$iMes?>";
	  jan = window.open(sUrl,'sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
	  jan.moveTo(0,0);
	}
</script>
</html>