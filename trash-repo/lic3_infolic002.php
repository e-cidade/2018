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

require("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_liclicita_classe.php");
$clliclicita = new cl_liclicita;
$clliclicita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("l03_descr");

$result=$clliclicita->sql_record($clliclicita->sql_query($l20_codigo));
if ($clliclicita->numrows==0){	
	db_redireciona('db_erros.php?fechar=true&db_erro=Este registro não possui licitação.');
	exit;
}else{
	db_fieldsmemory($result,0);
}    
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
</style>
<form name="form1" method="post" action="" >
<center>
<table border='0'>
  <tr align='left'>
    <td align="left">
      <table border="0">
        <tr>
          <td nowrap align="right" title="<?=@$Tl20_codigo?>">
            <?=@$Ll20_codigo?>
          </td>
          <td> 
            <?
              db_input('l20_codigo',10,$Il20_codigo,true,'text',3)
            ?>
          </td>
          <td nowrap align="right" title="<?=$Tl20_edital?>">
            <?=$Ll20_edital?>
          </td>
          <td>
            <?
              db_input("l20_edital",10,$Il20_numero,true,"text",3);
            ?>
            <?=$Ll20_anousu?>
            <?
              db_input("l20_anousu",10,$Il20_anousu,true,"text",3);
            ?>
          </td>
        </tr>            
        <tr>
          <td nowrap align="right" title="<?=@$Tl20_codtipocom?>">
            <b>Modalidade:</b>
          </td>            
          <td> 
            <?
              db_input('l20_codtipocom',6,$Il20_codtipocom,true,'text',3);
              db_input('l03_descr',35,$Il03_descr,true,'text',3);
            ?>
          </td>   
          <td nowrap align="right" title="<?=$Tl20_numero?>">
            <?=$Ll20_numero?>
          </td>
          <td>
            <?
              db_input("l20_numero",10,$Il20_numero,true,"text",3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap align="right" title="<?=@$Tl20_datacria?>">
            <b><?=@$Ll20_datacria?></b>
          </td>
	        <td> 
			      <?		
		          $ano=substr(@$l20_datacria,0,4);
		  	      $mes=substr(@$l20_datacria,5,2);
			        $dia=substr(@$l20_datacria,8,2);
			        db_inputdata('l20_datacria',"$dia","$mes","$ano",true,'text',3);
			      ?>
	        </td>
	        <td nowrap align="right" title="<?=@$Tl20_horacria?>">
	          <b><?=@$Ll20_horacria?></b>
	        </td>
	        <td> 
		        <?		
	            db_input('l20_horacria',5,$Il20_horacria,true,'text',3);	     
		        ?>
	        </td>      
	      </tr>
	      <tr>
	       <td nowrap align="right" title="<?=@$Tl20_dataaber?>">
	         <b><?=@$Ll20_dataaber?></b>
	       </td>
	        <td> 
				    <?		
			         $ano1=substr(@$l20_dataaber,0,4);
					     $mes1=substr(@$l20_dataaber,5,2);
					     $dia1=substr(@$l20_dataaber,8,2);
					     db_inputdata('l20_dataaber',"$dia1","$mes1","$ano1",true,'text',3);
				    ?>
	        </td>
	        <td nowrap align="right" title="<?=@$Tl20_horaaber?>">
	          <b><?=@$Ll20_horaaber?></b>
	        </td>
	        <td> 
	    	    <?		
	            db_input('l20_horaaber',5,$Il20_horaaber,true,'text',3);	     
		        ?>
	        </td>      
	      </tr>    
	      <tr>
	        <td nowrap align="right" title="<?=@$Tl20_dtpublic?>">
	          <b><?=@$Ll20_dtpublic?></b>
	        </td>
	        <td> 
				    <?
			         $ano2=substr($l20_dtpublic,0,4);
					     $mes2=substr($l20_dtpublic,5,2);
					     $dia2=substr($l20_dtpublic,8,2);
					     db_inputdata('l20_dtpublic',"$dia2","$mes2","$ano2",true,'text',3);
			      ?>
	        </td>
	        <td nowrap align="right" title="<?=@$Tl20_id_usucria?>">
	          <?=@$Ll20_id_usucria?>
	        </td>          
	        <td> 
	          <?
	            db_input('l20_id_usucria',6,$Il20_id_usucria,true,'text',3);
	            db_input('nome',45,$Inome,true,'text',3);
	          ?>
	        </td>
	      </tr>
				<tr>
					<td nowrap align='right'>
					  <b>Situação:</b>
					</td>
					<td>
		       <?		
	           db_input('l08_descr',15,'',true,'text',3);	     
		       ?>
		       </td>
		       <td>
		          <b>Data Situação:</b>
		       </td>
		       <td>
		       <?
		       $l11_data = ""; 
		       
		       	$oDaoLicLicitacaoSituacao = db_utils::getDao("liclicitasituacao");
		       	
		       	$sWhere  = " l11_liclicita       = ".$l20_codigo;
		       	$sWhere .= " and l11_licsituacao = ".$l08_sequencial;
		       	
		       	$sOrder  = " l11_sequencial desc limit 1 ";
		       	
		       	$sCampos = " l11_data ";

		       	$sSql  = $oDaoLicLicitacaoSituacao->sql_query(null,$sCampos,$sOrder,$sWhere);
		       	$rsSql = $oDaoLicLicitacaoSituacao->sql_record($sSql);
		       	if ($oDaoLicLicitacaoSituacao->numrows > 0) {
		       		$l11_data = db_formatar(db_utils::fieldsMemory($rsSql,0)->l11_data,'d');
		       		 
		          db_input('l11_data',10,'',true,'text',3);
		       	}
		       
		       ?>
					</td>
				</tr>
	      <tr> 
	      	<td align='right' title="<?=@$Tl20_local?>">
	      	  <b><?=@$Ll20_local?></b>
		      </td>
	        <td colspan='3' align='left'>
					  <? 
				   	  db_textarea("l20_local","","85",$Il20_local,true,'text',3);
					  ?>
		      </td>
	      </tr>
		    <tr> 
		      <td align='right' title="<?=@$Tl20_objeto?>">
		        <b><?=@$Ll20_objeto?></b>
		      </td>
	        <td colspan='3' align='left'>
					  <? 
					    db_textarea("l20_objeto","","85",$Il20_objeto,true,'text',3);
					  ?>
		      </td>        
		    </tr>  
	      <tr>
	        <td colspan='4' align='center'>
		        <input name="voltar" type="button" value="Voltar" onclick="parent.db_iframe_infolic.hide();" >
		      </td>
	      </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align='center' valign='top' colspan='1' align='center'>
      <? if ( isset($l20_codigo) ) { ?>  
      <table>
        <tr>
          <td>
            <iframe name="itens" id="itens" src="forms/db_frminfolic.php?l20_codigo=<?=$l20_codigo?>" 
                    width="900" height="150" marginwidth="0" marginheight="0" frameborder="0"></iframe>
          </td>
        </tr>     
      </table>
      <?}?>  
    </td>
  </tr>
  <tr>
    <td align="center">
	    <input name='proc'    type='button' value='Processos de Compras'    onclick="js_mostra('p');">
	    <input name='sol'     type='button' value='Solicitações de Compras' onclick="js_mostra('s');">
	    <input name='sit'     type='button' value='Situações da Licitação'  onclick="js_mostra('m');">
	    <input name='editais' type='button' value='Editais'                 onclick="js_mostra('e');">
	    <input name='atas'    type='button' value='Atas'                    onclick="js_mostra('a');">
	    <input name='minutas' type='button' value='Minutas'                 onclick="js_mostra('mn');">
    </td>
  </tr>
</table>
</center>
</form>
</body>
</html>
<script>
function js_mostra(tipo){
  if ( tipo == 'mn' ) {
  
    var sUrl = 'lic3_infolicminuta002.php?l20_codigo='+document.form1.l20_codigo.value;
    js_OpenJanelaIframe('top.corpo','db_iframe_mostra',sUrl,'Consulta Minutas da Licitação',true);
  } else if ( tipo == 'a' ) {
  
    var sUrl = 'lic3_infolicata002.php?l20_codigo='+document.form1.l20_codigo.value;
    js_OpenJanelaIframe('top.corpo','db_iframe_mostra',sUrl,'Consulta Atas da Licitação',true);
  } else if ( tipo == 'e' ) {
  
    var sUrl = 'lic3_infolicanexo002.php?l20_codigo='+document.form1.l20_codigo.value;
    js_OpenJanelaIframe('top.corpo','db_iframe_mostra',sUrl,'Consulta Editais da Licitação',true);    
  } else {
  
    var sUrl = 'lic3_infolic003.php?l20_codigo='+document.form1.l20_codigo.value+'&tipo='+tipo;
		js_OpenJanelaIframe('top.corpo','db_iframe_mostra',sUrl,'Consulta Licitação',true);	
  }
}
</script>