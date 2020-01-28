<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("classes/db_pcfornecertif_classe.php");
require_once("classes/db_pcfornecertifdoc_classe.php");
require_once("classes/db_pctipodoccertif_classe.php");
require_once("classes/db_pcdoccertif_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_pcfornecertifrenovacao_classe.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
$clpcfornecertif = new cl_pcfornecertif;
$clpcfornecertifdoc = new cl_pcfornecertifdoc;
$clpctipodoccertif = new cl_pctipodoccertif;
$clpcdoccertif = new cl_pcdoccertif;
$clpcparam = new cl_pcparam;
$clrotulo = new rotulocampo;
$clrotulo->label("pc71_codigo");
$clrotulo->label("pc71_descr");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$DB_coddepto = db_getsession("DB_coddepto");
$oParam = db_utils::fieldsMemory($clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"))),0);

if (isset ($atualizar)) {
	db_inicio_transacao();
	$sqlerro = false;
	$clpcfornecertif->pc74_data         = date('Y-m-d', db_getsession("DB_datausu"));
	$clpcfornecertif->pc74_hora         = db_hora();
	$clpcfornecertif->pc74_usuario      = db_getsession("DB_id_usuario");
	$clpcfornecertif->pc74_pcforne      = $pc74_pcforne;
	$clpcfornecertif->pc74_pctipocertif = $pc72_pctipocertif;
	$clpcfornecertif->pc74_solicitante  = "$pc74_solicitante";
	$clpcfornecertif->pc74_coddepto     = $DB_coddepto;	
	$clpcfornecertif->pc74_validade     = implode("-", array_reverse(explode("/",$pc74_validade)));
	if ($oParam->pc30_validadepadraocertificado > 0 && $clpcfornecertif->pc74_validade == "") {
    $sqlerro = true;
    $erro_msg = "AVISO: Campo: Validade do Certificado não informado!";	
  }
  if ($sqlerro == false) {
	  $clpcfornecertif->incluir(null);
	  if(isset($pc74_codigo) && $pc74_codigo!="") {
	  	$oPcRenovacao = new cl_pcfornecertifrenovacao();	 	
	  	$oPcRenovacao->pc35_pcfornecertiforiginal = $pc74_codigo;
	  	$oPcRenovacao->pc35_fornecertffilho       = $clpcfornecertif->pc74_codigo;
	  	$oPcRenovacao->pc35_datarenovacao         = date('Y-m-d', db_getsession("DB_datausu")); 
	  	$oPcRenovacao->incluir(null);
	  }
	
	  $erro_msg = $clpcfornecertif->erro_msg;
	  $codigo = $clpcfornecertif->pc74_codigo;
	  if ($clpcfornecertifdoc->erro_status == '0') {
	 	  $sqlerro = true;
	  }
  }
	$vt = $HTTP_POST_VARS;
	$ta = sizeof($vt);
	reset($vt);
	$dadosant = "";

	for ($i = 0; $i < $ta; $i ++) {
		$chave = key($vt);
		
		if (substr($chave, 0, 4) == "DATA") {
			$dados = split("_", $chave);
 		  if ($dados[1] != $dadosant) {
       
			  $dadosant = $dados[1];
				$obtes = $HTTP_POST_VARS;
				if (array_key_exists("OBS_".$dados[1], $obtes)) {
					$obs = $obtes["OBS_".$dados[1]];
				} else {
					$obs = "";
				}
				
				if ($sqlerro == false) {
					
					$result_ob = $clpctipodoccertif->sql_record($clpctipodoccertif->sql_query(null, "pc72_obrigatorio", null, "pc72_pctipocertif=$pc72_pctipocertif and pc72_pcdoccertif=".$dados[1]));
					db_fieldsmemory($result_ob, 0);
					$valid = $vt["DATA_".$dados[1]."_ano"]."-".$vt["DATA_".$dados[1]."_mes"]."-".$vt["DATA_".$dados[1]."_dia"];
					if ($pc72_obrigatorio == 't') {
						$ob = 1;
						if ($valid=="--"){
							$sqlerro=true;
							$erro_msg = "O documento ".$dados[1]." é obrigatório!!";
							break;
						}
					} else {
						$ob = 0;
						
              //Se a validade está em branco concatena a observação com a expressão "- DOCUMENTO NÃO ATUALIZADO"
              if($valid == "--"){
                
                if ( isset($pc75_obs) && (trim(substr($pc75_obs,-26)) == "- DOCUMENTO NÃO ATUALIZADO" || trim(substr($obs,-26)) == "- DOCUMENTO NÃO ATUALIZADO") ) {
                  $clpcfornecertifdoc->pc75_obs         = $obs;
                } else {
                  $clpcfornecertifdoc->pc75_obs         = $obs."- DOCUMENTO NÃO ATUALIZADO";
                } 
                $valid = "null";
                
              } else {
                $valid = $vt["DATA_".$dados[1]."_ano"]."-".$vt["DATA_".$dados[1]."_mes"]."-".$vt["DATA_".$dados[1]."_dia"];
              }
					}
					
				  $dataemissao = $vt["EMISSAO_{$dados[1]}_ano"]."-".$vt["EMISSAO_{$dados[1]}_mes"]."-".$vt["EMISSAO_{$dados[1]}_dia"];
          if ($dataemissao == "--") {
            $dataemissao = "";
          }					
					
          $clpcfornecertifdoc->pc75_obrigatorio   = "$ob";
					$clpcfornecertifdoc->pc75_obs           = "$obs";
					$clpcfornecertifdoc->pc75_pcfornecertif = $codigo;
					$clpcfornecertifdoc->pc75_pcdoccertif   = $dados[1];
					$clpcfornecertifdoc->pc75_validade      = $valid;
					$clpcfornecertifdoc->pc75_dataemissao   = $dataemissao;
          $clpcfornecertifdoc->pc75_apresentado   = $vt["APRESENTADO_{$dados[1]}"];
					
					$clpcfornecertifdoc->incluir(null);
					if ($clpcfornecertifdoc->erro_status == '0') {
						$erro_msg = $clpcfornecertifdoc->erro_msg;
						$sqlerro = true;
						break;
					}
				}
		  }
		}
		$proximo = next($vt);
	}
	db_fim_transacao($sqlerro);
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
</head>
<script>
function js_atualizar(){
  document.form1.pc74_solicitante.value = parent.document.form1.pc74_solicitante.value;   
  document.form1.pc74_validade.value    = parent.document.form1.pc74_validade.value;
  document.form1.pc74_codigo.value      = parent.document.form1.pc74_codigo.value;
  document.form1.oSocial.value          = parent.document.form1.oSocial.value;
  document.form1.atualizar.value        = "ok";
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
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;

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
<body onLoad="parent.js_loadFielDocs()"> 
  <form name="form1" method="post">
  <table border="0" width="100%" cellspacing="0" cellpadding="0" nowrap >
  <tr>
    <td align="center" valign="top" >
    
<?

db_input('pc72_pctipocertif', 8, '', true, 'hidden', 3);
db_input('pc74_codigo', 8, '', true, 'hidden', 3);
db_input('oSocial', 8, '', true, 'hidden', 3);
db_input('pc74_pcforne', 8, '', true, 'hidden', 3);
db_input('pc74_solicitante', 8, '', true, 'hidden', 3);
db_input('pc74_validade', 8, '', true, 'hidden', 3);
db_input('atualizar', 8, '', true, 'hidden', 3);
echo "<script>
        document.form1.pc74_pcforne.value=parent.document.form1.pc60_numcgm.value;
        document.form1.pc74_solicitante.value=parent.document.form1.pc74_solicitante.value;
      </script>";
?>     
      <table border='0'  nowrap>
            
<? 


if (isset ($pc72_pctipocertif) && $pc72_pctipocertif != "") {
	
	$result01 = $clpctipodoccertif->sql_record($clpctipodoccertif->sql_query(null, "*", "pc72_pcdoccertif", "pc72_pctipocertif=$pc72_pctipocertif"));
	$numrows01 = $clpctipodoccertif->numrows;
	if ($numrows01 > 0) {
		echo "  <tr>
                        <td colspan=2><b>* campo obrigatório</b></td>
<td></td>
<td></td>
<td></td>
<td></td>
                 </tr>
                    
                   <tr>
               <td class='cabec' align='center'  title='$Tpc71_codigo'>".str_replace(":", "", $Lpc71_codigo)."</td>
               <td class='cabec' align='center'  title='$Tpc71_descr'>".str_replace(":", "", $Lpc71_descr)."</td>
               
               <td class='cabec' align='center'  title='Validade'><b>Validade</b></td>
               <td class='cabec' align='center'  title='Emiss'><b>Emissão</b></td>
               
               <td class='cabec' align='center'  title='apresentado'><b>Apresentado</b></td>
               <td class='cabec' align='center'  title='Obs'><b>Observação</b></td>   			 
				      </tr>";
	}
	for ($i = 0; $i < $numrows01; $i ++) {
		db_fieldsmemory($result01, $i);
		if ($pc72_obrigatorio=='t'){
			$corpo="corpoob";
			$ast="*";
		}else{
			$corpo="corpo";
			$ast="";
		}
		
		$dia="DATA_".$pc71_codigo."_dia";
		$mes="DATA_".$pc71_codigo."_mes";
		$ano="DATA_".$pc71_codigo."_ano";
		
		$dia_e="EMISSAO_".$pc71_codigo."_dia";
    $mes_e="EMISSAO_".$pc71_codigo."_mes";
    $ano_e="EMISSAO_".$pc71_codigo."_ano";
		
    $result_docforne = $clpcfornecertifdoc->sql_record($clpcfornecertifdoc->sql_query(null,"*","pc75_codigo desc","pc75_pcdoccertif=$pc72_pcdoccertif and pc74_pctipocertif = $pc72_pctipocertif and pc74_pcforne=$pc74_pcforne"));
		if ($clpcfornecertifdoc->numrows>0){
			db_fieldsmemory($result_docforne,0);
			if ($pc75_validade>date('Y-m-d',db_getsession("DB_datausu"))){
			  $$ano=substr($pc75_validade,0,4);
			  $$mes=substr($pc75_validade,5,2);
			  $$dia=substr($pc75_validade,8,2);
			}		
		}	
    echo "<tr>
                 <td  class='$corpo'  align='center' title='$Tpc71_codigo'><small>$pc71_codigo</small></td>
                 <td  class='$corpo'  align='center' title='$Tpc71_descr'><small>$pc71_descr</small></td>
                         <td  class='$corpo'  align='center' title='Validade' nowrap ><b>$ast</b>";
    db_inputdata("DATA_$pc71_codigo", @$$dia, @$$mes, @$$ano, true, "text", 1);
    echo "       </td>
                         <td  class='$corpo'  align='center' title='Validade' nowrap >";
                         

    db_inputdata("EMISSAO_$pc71_codigo", @$$dia_e, @$$mes_e, @$$ano_e, true, "text", 1);
    echo "       </td>
                         <td  class='$corpo'  align='center' title='Validade' nowrap >";
                         
                                                 
    $x = array(0=>"Selecione...", 1=>"SIM", 2=>"NÂO");
    db_select("APRESENTADO_$pc71_codigo", $x, true, 1, "");
    echo "       </td>
                         <td  class='$corpo'  align='center' title='Validade' nowrap >";                         

                         
    db_textarea("OBS_$pc71_codigo", 0, 45, "", true, "text", 1);
    echo "       </td>
                  </tr>";
	}
	    
}
?>    
      </table>
      
    </td>
  </tr>  
  </table>
  <form>
  </body>
</html>  
<?

if (isset ($atualizar)) {
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
	} else {
		db_msgbox($erro_msg);

    echo"";
     
?>
		<script>
		    
		    if (confirm("Imprimir certificado?")){
                         jan = window.open("com2_certforne002.php?codigo=<?=$codigo?>&oSocial=<?=$oSocial?>",'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
                         jan.moveTo(0,0);  
		    }
		</script>
<?
		echo "<script>parent.location.href='com4_lancadoc001.php';</script>";
	}
	//db_redireciona("com1_pctipodoccertifalt003.php?pc72_pctipocertif=$pc72_pctipocertif");
}
?>