<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("dbforms/db_classesgenericas.php");
include ("classes/db_pcproc_classe.php");
include ("classes/db_pcprocitem_classe.php");
include ("classes/db_solicita_classe.php");
include ("classes/db_solicitem_classe.php");
include ("classes/db_liclicitem_classe.php");
include ("classes/db_proctransfer_classe.php");
include ("classes/db_proctransferproc_classe.php");
include ("classes/db_solicitemprot_classe.php");
include ("classes/db_pcandpadraodepto_classe.php");
include ("classes/db_solandpadraodepto_classe.php");
include ("classes/db_solandam_classe.php");
include ("classes/db_procandam_classe.php");
include ("classes/db_protprocesso_classe.php");
include ("classes/db_solordemtransf_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$cliframe_seleciona = new cl_iframe_seleciona;
$clpcproc = new cl_pcproc;
$clpcprocitem = new cl_pcprocitem;
$clsolicita = new cl_solicita;
$clsolicitem = new cl_solicitem;
$clliclicitem = new cl_liclicitem;
$clproctransfer  = new cl_proctransfer;
$clproctransferproc = new cl_proctransferproc;
$clsolicitemprot = new cl_solicitemprot;
$clpcandpadraodepto = new cl_pcandpadraodepto;
$clprocandam = new cl_procandam;
$clsolandpadraodepto = new cl_solandpadraodepto;
$clsolandam = new cl_solandam;
$clprotprocesso = new cl_protprocesso;
$clsolordemtransf = new cl_solordemtransf;
$clrotulo = new rotulocampo;
$clrotulo->label("pc11_numero");
$clrotulo->label("pc11_codigo");
$clrotulo->label("pc11_quant");
$clrotulo->label("pc11_seq");
$clrotulo->label("pc11_vlrun");
$clrotulo->label("pc11_resum");
$clrotulo->label("pc01_codmater");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("pc01_servico");
$clrotulo->label("pc17_unid");
$clrotulo->label("pc17_quant");
$clrotulo->label("m61_descr");
$clrotulo->label("m61_usaquant");
$db_opcao = 1;
$db_botao = true;

if (isset ($incluir)&&$incluir!="") {
	
	$sqlerro = false;
	
	db_inicio_transacao();
	
	$arr_dados = array();
	$dados 	   = split('#', $valores);
	
	for($w=0;$w<count($dados);$w++){
		if (trim($dados[$w])!=""){
			$info=split('_', $dados[$w]);
			if (array_key_exists($info[2],$arr_dados)){	    	
	    		$esp="#";
			} else{
				$esp="";
			}			
			@$arr_dados[@$info[2]] .= @$esp.@$info[1]."_".@$info[3];			
		}
	}
	reset($arr_dados);
	for($w=0;$w<count($arr_dados);$w++){
		$depto_dest=key($arr_dados);		
		$cod_ord=split('#',$arr_dados[$depto_dest]);
		$clproctransfer->p62_hora = db_hora();
		$clproctransfer->p62_dttran = date("Y-m-d", db_getsession("DB_datausu"));
		$clproctransfer->p62_id_usuario = db_getsession("DB_id_usuario");
		$clproctransfer->p62_coddepto = db_getsession("DB_coddepto");
		$clproctransfer->p62_coddeptorec = $depto_dest;
		$clproctransfer->p62_id_usorec = '0';
		$clproctransfer->incluir(null);
		$codtran=$clproctransfer->p62_codtran;
		if ($clproctransfer->erro_status == 0) {
			$sqlerro == true;
			$erro_msg=$clproctransfer->erro_msg;
		}		
		for($i=0;$i<count($cod_ord);$i++){
			$info_item=split("_",$cod_ord[$i]);
			$solicitem=$info_item[0];
			$ordem=$info_item[1];
			if ($sqlerro == false) {
				$result_proc=$clsolicitemprot->sql_record($clsolicitemprot->sql_query_file($solicitem));
				if ($clsolicitemprot->numrows>0){
					db_fieldsmemory($result_proc,0);					
                    $clproctransferproc->incluir($codtran,$pc49_protprocesso);
                    if ($clproctransferproc->erro_status==0){
                       	$sqlerro=true;
                       	$erro_msg=$clproctransferproc->erro_msg;
                      	break;
                    }
                    if ($sqlerro == false) {
                        $clprotprocesso->p58_codproc= $pc49_protprocesso;
                        $clprotprocesso->p58_despacho="".@$despacho;                        	
                        $clprotprocesso->alterar($pc49_protprocesso);
                      	if ($clprotprocesso->erro_status==0){
                       	  	$sqlerro=true;
                       	  	$erro_msg=$clprotprocesso->erro_msg;
                    		break;                    		
                	    }
            	    }
				}
			}
			if ($sqlerro == false) {
				$clsolordemtransf->pc41_solicitem=$solicitem;
				$clsolordemtransf->pc41_codtran=$codtran;
				$clsolordemtransf->pc41_ordem=$ordem;
				$clsolordemtransf->incluir(null);
				if($clsolordemtransf->erro_status==0){
					$sqlerro=true;
					$erro_msg=$clsolordemtransf->erro_msg;
				}
			}				
		}
		next($arr_dados);				
	}
	db_fim_transacao($sqlerro);
	if ($sqlerro==false){
		db_msgbox("Transferência efetuada com sucesso!!");
		echo "<script>parent.location.href='com4_soltranfand001.php';</script>";
	}else{		
		db_msgbox("Transferência Cancelada!!");
		db_msgbox(@$erro_msg);
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>

function js_submit_form(){ 
  document.form1.codsolant.value=document.form1.codsol.value;
  document.form1.despacho.value=parent.document.form1.despacho.value;
  //js_gera_chaves();
}

</script>

<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
    
    if (isset ($codsol) && $codsol != "") {
    	
      if (isset($tipo)&&$tipo=="P"){
        $where=" pc81_codproc=$codsol and pc81_codproc is not null ";
      }else {
     	$where=" pc10_numero=$codsol ";
      }
    	
      $result = $clsolicita->sql_record($clsolicita->sql_query_andsol("distinct pc11_numero	,
	                                                                            pc11_codigo	,
	                                                                            pc47_pctipoandam as tipoandam,
																				pc11_quant	,
																				pc11_seq	,
																				pc11_vlrun	,
																				pc11_resum	,
																				pc01_codmater	,
																				pc01_descrmater	,
																				pc01_servico	,
																				pc17_unid	,
																				pc17_quant	,
																				m61_descr	,
																				m61_usaquant	","where $where 
      																								 and p64_codtran is not null 
      																								 and ( case  
            																							    when ( 	   e61_autori is not null 
            																							    	   and e54_anulad is not null 
                     																							   and e54_autori = ( select max(e55_autori)  
                                          																			  					 from empautitem
                                          																			  					      inner join empautitempcprocitem on empautitempcprocitem.e73_autori = empautitem.e55_autori
                                          																			  					                                     and empautitempcprocitem.e73_sequen = empautitem.e55_sequen
                                          																			  					      inner join pcprocitem           on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem  
                                         																							      where pcprocitem.pc81_solicitem = x.pc11_codigo )
            																							    	   )  
																		                   					  or ( e61_autori is null)    
                   																						   		 then true  
  																									          else false   
   																							              	end )
   																							         and y.pc43_depto=".db_getsession("DB_coddepto")));
    
    $numrows=$clsolicita->numrows;
    
    }else{
      $numrows="0";
    }
	
    if($numrows>0){
	
	echo " <table border='1' cellspacing='0' cellpadding='0'>
		     <tr class='bordas'>
			   <td class='bordas' align='center'title='Inverte marcação' >".(isset($tipo) && $tipo=="S"?"<a title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'><b>M</b></a>":"")."</td>
		       <td class='bordas' align='center'><b><small>$RLpc11_numero	 </small></b></td>
		       <td class='bordas' align='center'><b><small>$RLpc11_codigo	 </small></b></td>
		       <td class='bordas' align='center'><b><small>$RLpc11_quant	 </small></b></td>
		       <td class='bordas' align='center'><b><small>$RLpc11_vlrun	 </small></b></td>	      
		       <td class='bordas' align='center'><b><small>$RLpc01_codmater	 </small></b></td>
		       <td class='bordas' align='center'><b><small>$RLpc01_descrmater</small></b></td>
		       <td class='bordas' align='center'><b><small>$RLpc01_servico	 </small></b></td>
			   <td class='bordas' align='center'><b><small>Depto. Destino	 </small></b></td> ";
          
  }else echo"<b>Nenhum registro encontrado...</b>";
	 
    echo " </tr>";
        
	for($i=0; $i<$numrows; $i++){
	    
      db_fieldsmemory($result,$i);
			    
	  if (isset($tipo)&&$tipo=="S"){
	    $result_procitem=$clpcprocitem->sql_record($clpcprocitem->sql_query_file(null,"*",null,"pc81_solicitem=$pc11_codigo"));
	    if ($clpcprocitem->numrows!=0){
	      continue;
	    }
	  }
	    
	  if ($pc01_servico=='t'){
	  	$pc01_servico="Sim";
	  }else{
	    $pc01_servico="Não";
	  }
	       
	  if ( $tipoandam == 5 || $tipoandam == 6 ) {
	  	$rsLiclicitem = $clliclicitem->sql_record($clliclicitem->sql_query_proc(null,"*",null," pc11_codigo =  {$pc11_codigo}"));
	  	if($clliclicitem->numrows > 0){
	  		continue;
	  	}
	  }
	  
	  
	  echo "<tr>	    
			  <td class='bordas_corp' align='center' title='Inverte a marcação'><input type='checkbox' name='CHECK_$pc11_codigo' id='CHECK_$pc11_codigo' ".(isset($tipo) && $tipo=="P"?"onClick='js_marca(this);'":"")."></td>
   	          <td class='bordas_corp' align='center'><small>$pc11_numero	&nbsp; 					  </small></td>
   	          <td class='bordas_corp' align='center'><small>$pc11_codigo&nbsp;	 		 		  </small></td>
   	          <td class='bordas_corp' align='center'><small>$pc11_quant	&nbsp; 					  </small></td>
   	          <td class='bordas_corp' align='center'><small>".db_formatar($pc11_vlrun,'f')."&nbsp; </small></td>
   	          <td class='bordas_corp' align='center'><small>$pc01_codmater&nbsp;					  </small></td>
   	          <td class='bordas_corp' align='center'><small>$pc01_descrmater	&nbsp; 				  </small></td>
   	          <td class='bordas_corp' align='center'><small>$pc01_servico&nbsp;	 				  </small></td>
		   	  <td class='bordas_corp' align='center'><small>";
	
	  echo "<select name='depto_$pc11_codigo'>";

$passa=false;
$result_proc=$clsolandam->sql_record($clsolandam->sql_query_andpad(null,"distinct pc43_codigo,pc43_depto,pc43_ordem,descrdepto","pc43_codigo desc","pc43_solicitem=$pc11_codigo and pc47_pctipoandam = 3 "));
if($clsolandam->numrows>0){
	db_fieldsmemory($result_proc,0);
	if (isset($tipo)&&$tipo=="S"){
		$result_procitem=$clpcprocitem->sql_record($clpcprocitem->sql_query_file(null,"*",null,"pc81_solicitem=$pc11_codigo"));
		if ($clpcprocitem->numrows>0){
			$passa=true;
		}
	}
}
if ($passa==false){
	$result_atual=$clsolandam->sql_record($clsolandam->sql_query_andpad(null,"*","pc43_codigo desc limit 1","pc43_solicitem=$pc11_codigo and pc47_pctipoandam<>2 "));
	if($clsolandam->numrows>0){
		db_fieldsmemory($result_atual,0);
		if ($pc47_pctipoandam==3||$pc47_pctipoandam==4||$pc47_pctipoandam==6){
			$where_t = " pc47_pctipoandam = 5 ";
		}else{
			$ordem=$pc43_ordem+1;
			$where_t = " pc47_ordem = $ordem ";	
		}			
		$result_prox=$clsolandpadraodepto->sql_record($clsolandpadraodepto->sql_query(null,"*",null,"$where_t and pc47_solicitem=$pc11_codigo"));
		if ($clsolandpadraodepto->numrows>0){
			db_fieldsmemory($result_prox,0);
			echo "<option value='".$pc48_depto."_".$pc47_ordem."'>$descrdepto</option>";
		}	
	}
}
$wher="";
if (isset($tipo)&&$tipo=="P"){
	$wher=" and (pc47_pctipoandam = 3 or pc47_pctipoandam = 7 )";
}

$result_ant=$clsolandam->sql_record($clsolandam->sql_query_andpad(null,"distinct pc43_codigo,pc43_depto,pc43_ordem,descrdepto","pc43_codigo desc","pc43_solicitem=$pc11_codigo and pc47_pctipoandam<>4 and pc47_pctipoandam<>6 $wher "));
if($clsolandam->numrows>0){
	for($w=1;$w<$clsolandam->numrows;$w++){
		db_fieldsmemory($result_ant,$w);
		if(isset($pc48_depto)&&$pc48_depto!=""){
			if ($pc48_depto==$pc43_depto){				
				continue;
			}
		}
		echo "<option value='".$pc43_depto."_".$pc43_ordem."'>$descrdepto</option>";
	}

}
echo "</select>
</small></td>";
}
echo "</tr> 
</table>";
db_input('incluir', 10, '', true, 'hidden', 3);
db_input('despacho', 200, '', true, 'hidden', 3);
db_input('codsol', 10, '', true, 'hidden', 3);
db_input('codsolant', 10, '', true, 'hidden', 3);
db_input('cods', 10, '', true, 'hidden', 3);
db_input('tipo', 10, '', true, 'hidden', 3);
db_input('valores', 10, '', true, 'hidden', 3);
?>
    </center>
    </td>
  </tr>
</table>
</form>
<script>
</script>
</body>



</html>
<?

if (isset($tipo) && $tipo == "P") {
	echo " <script>																 ";
	echo "   function js_marca(obj){											 "; 
	echo "     var OBJ = document.form1;										 ";
	echo "     for(i=0;i<OBJ.length;i++){										 ";
	echo "     	 if(OBJ.elements[i].type == 'checkbox'){						 ";
	echo "         OBJ.elements[i].checked = obj.checked						 ";            
	echo "     	 }			 													 ";
	echo "     }																 ";
	echo "     				 													 ";
	echo "	 }				 													 ";
	echo "</script>			 													 ";	
} else {
	echo " <script>																 ";
	echo "   function js_marca(obj){											 "; 
	echo "     var OBJ = document.form1;										 ";
	echo "     for(i=0;i<OBJ.length;i++){										 ";
	echo "     	 if(OBJ.elements[i].type == 'checkbox'){						 ";
	echo "         OBJ.elements[i].checked = !(OBJ.elements[i].checked == true); ";            
	echo "     	 }			 													 ";
	echo "     }																 ";
	echo "     return false; 													 ";
	echo "	 }				 													 ";
	echo "</script>			 													 ";
}
?>