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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamforne_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_pcorcamjulg_classe.php");
include("classes/db_pcorcamdescla_classe.php");
include("classes/db_liclicitemlote_classe.php");
include("classes/db_liclicita_classe.php");
require("classes/db_registroprecovalores_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clpcorcam         = new cl_pcorcam;
$clpcorcamitem     = new cl_pcorcamitem;
$clpcorcamforne    = new cl_pcorcamforne;
$clpcorcamval      = new cl_pcorcamval;
$clpcorcamjulg     = new cl_pcorcamjulg;
$clpcorcamdescla   = new cl_pcorcamdescla;
$clliclicitemlote  = new cl_liclicitemlote;
$clliclicita       = new cl_liclicita;
$oDaoRegistroValor = new cl_registroprecovalores;     
$lRegistroPreco    = false;
if (isset($l20_codigo) && $l20_codigo) {
  
  $sSqlDadosLicitacao = $clliclicita->sql_query_file($l20_codigo);
  $rsDadosLicitacao   = $clliclicita->sql_record($sSqlDadosLicitacao);
  if ($clliclicita->numrows > 0) {

    $oDadosLicitacao  = db_utils::fieldsMemory($rsDadosLicitacao, 0);
    $lRegistroPreco   = $oDadosLicitacao->l20_usaregistropreco=='t'?true:false;
      
  }
  
}
$db_opcao = 1;
$db_botao = true;
if (isset($alterar) || isset($incluir)) {
  
  $sqlerro=false;
  if (isset($valores) && trim($valores)!="") {
  	$arrval = split("valor_",$valores);
  } else {
    
    $sqlerro=true;
    $erro_msg = "Usu�rio: \\n\\nValores do or�amento n�o informados. \\nAltere antes de continuar. \\n\\nAdministrador: ";
    
  }
  
  if (isset($qtdades) && trim($qtdades)!="") {
    
    $arrqtd = split("qtde_",$qtdades);
    $arrqtdorcada = split("qtdeOrcada_",$qtdadesOrcadas);
    
  } else {
    
    $sqlerro=true;
    $erro_msg = "Usu�rio: \\n\\nQuantidades do or�amento n�o informadas. \\nAltere antes de continuar. \\n\\nAdministrador: ";
    
  }
  if (isset($obss) && trim($obss)!="") {
    $arrmrk = split("obs_",$obss);
  }
  
  if (isset($valoresun) && trim($valoresun)!="") {
    $arrvalun = split("vlrun_",$valoresun);
  }
  
  if (isset($dataval) && trim($dataval)!="") {
    $arrdat = split("#",$dataval);
  }

  if (sizeof($arrval) > 0 && $sqlerro == false) {
    
  	if ($sqlerro == false) {
  	  
  		$validadorc=$pc21_validadorc_ano."-".$pc21_validadorc_mes."-".$pc21_validadorc_dia;		
	  	$prazoent=$pc21_prazoent_ano."-".$pc21_prazoent_mes."-".$pc21_prazoent_dia ;
	  	if (trim($prazoent)=="--" || trim($prazoent)=='') {
  			$prazoent=null;
		  }
		  if (trim($validadorc)=="--"){
  			$validadorc=null;
		  }
		  $clpcorcamforne->pc21_validadorc = $validadorc;
		  $clpcorcamforne->pc21_prazoent = $prazoent;
      $clpcorcamforne->pc21_orcamforne=$pc21_orcamforne;
  	  $clpcorcamforne->alterar($pc21_orcamforne);
  		if ($clpcorcamforne->erro_status == 0) {
  		  
  			$sqlerro=true;
  			$erro_msg=$clpcorcamforne->erro_msg;
  			
  		}
  	}
  }
  
  if (sizeof($arrval) > 0 && $sqlerro == false) {
    
    db_inicio_transacao();
    for ($i = 1; $i < sizeof($arrval); $i++) {
      
      $codvalun = split("_",$arrvalun[$i]);
      $codval   = split("_",$arrval[$i]);
      $codqtd   = split("_",$arrqtd[$i]);
      $desmrk   = split("_",$arrmrk[$i]);
      $validmin = @$arrdat[$i];
      $quantOrc = split("_",$arrqtdorcada[$i]);

      if ($quantOrc >  $codqtd ) {
       
        $codfornecedor="$pc21_orcamforne";
        $item = $codval[0];
        $mensagem="Quantidade do item or�ado menor que a quantidade solicitada";
        $clpcorcamdescla->pc32_orcamitem="$item";
        $clpcorcamdescla->pc32_orcamitem="$codfornecedor";
        $clpcorcamdescla->pc32_motivo="$mensagem";
        $clpcorcamdescla->incluir($item,$codfornecedor);
      }
      if (isset($desmrk[1])) {

        $orcammrk = str_replace("yw00000wy"," ",$desmrk[1]);
      } else {
        $orcammrk = "";
      }
      $orcamitem  = $codval[0];
      $orcamval   = $codval[1];
      $orcamitem2 = $codqtd[0];
      $orcamqtd   = $codqtd[1];
      $valorunit  = $codvalun[1];
      if (strpos(trim($valorunit),',')!="") {
	    
        $valorunit=str_replace('.','',$valorunit);
	      $valorunit=str_replace(',','.',$valorunit);
	    }
	   
	    if (strpos(trim($orcamval),',')!=""){
	    
	      $orcamval=str_replace('.','',$orcamval);
	      $orcamval=str_replace(',','.',$orcamval);
	      
	    }
   
      if (isset($alterar) && $sqlerro==false) {

        $clpcorcamval->excluir($pc21_orcamforne,$orcamitem);
  	    if ($clpcorcamval->erro_status==0) {
  	     
  	      $erro_msg = $clpcorcamval->erro_msg;
  	      $sqlerro=true;
	        unset($incluir);
	       
	      } else {
	        $incluir="incluir";
	      }
	      if ($lRegistroPreco) {
	        $oDaoRegistroValor->excluir(null, "pc56_orcamforne = {$pc21_orcamforne} and pc56_orcamitem = {$orcamitem}");
	      }
      }
      if (isset($incluir) && $sqlerro == false && $orcamval != 0) {

        $pc23_valor = $orcamval;
	      if (trim($validmin)!= '' ) {
			   
	        $arr_d    = split("-",$validmin);	
			    $validmin = $arr_d[2]."-".$arr_d[1]."-".$arr_d[0];
		   	  if (trim($validmin) == "--" || trim($validmin) == '') {
				    $validmin=null;
			    }				
		    } else {
			    $validmin=null;
		    }	
	   	  $clpcorcamval->pc23_validmin  = $validmin;
	      $clpcorcamval->pc23_orcamforne= $pc21_orcamforne;
	      $clpcorcamval->pc23_orcamitem = $orcamitem;
	      $clpcorcamval->pc23_valor     = $orcamval;
	      $clpcorcamval->pc23_quant     = $orcamqtd;
	      $clpcorcamval->pc23_obs       = $orcammrk;
 	      $clpcorcamval->pc23_vlrun     = $valorunit;
	      $clpcorcamval->incluir($pc21_orcamforne,$orcamitem);
	      $erro_msg = $clpcorcamval->erro_msg;
	      if ($clpcorcamval->erro_status==0) {

	        $sqlerro=true;
 	        break;
 	        
	      }
	      /*
	       * Caso for registro de preco , devemos incluir os valores do historico do registro de preco
	       */
	      if ($lRegistroPreco) {
	        
	        /**
	         * verifica o c�digo do item da solicitacao
	         */
	        $sSqlDadosRegistroPreco  = "SELECT pc81_solicitem";
          $sSqlDadosRegistroPreco .= "  from pcorcamitem ";
          $sSqlDadosRegistroPreco .= "       inner join pcorcamitemlic on pc26_orcamitem    = pc22_orcamitem ";
          $sSqlDadosRegistroPreco .= "       inner join liclicitem     on l21_codigo        = pc26_liclicitem ";
          $sSqlDadosRegistroPreco .= "       inner join pcprocitem     on l21_codpcprocitem = pc81_codprocitem ";
          $sSqlDadosRegistroPreco .= "       inner join solicitem      on pc11_codigo       = pc81_solicitem   ";
          $sSqlDadosRegistroPreco .= " where pc22_orcamitem  = {$orcamitem} ";
  	      
          $rsRegistroPreco = db_query($sSqlDadosRegistroPreco);
          if (pg_num_rows($rsRegistroPreco) != 1) {
            
            $sqlerro  = true;
            $erro_msg = "N�o existem registros de pre�os para esta solicita��o.";
            break;
          }
          $oDadosRegistroPreco = db_utils::fieldsMemory($rsRegistroPreco, 0);
          $oDaoRegistroValor->pc56_ativo          = "true";
	        $oDaoRegistroValor->pc56_orcamforne     = $pc21_orcamforne;
	        $oDaoRegistroValor->pc56_orcamitem      = $orcamitem;
	        $oDaoRegistroValor->pc56_valorunitario  = $valorunit;
	        $oDaoRegistroValor->pc56_solicitem      = $oDadosRegistroPreco->pc81_solicitem;
	        $oDaoRegistroValor->incluir(null);
	        $erro_msg = $oDaoRegistroValor->erro_msg;
	        if ($oDaoRegistroValor->erro_status == 0) {
	          
	          $sqlerro=true;
            break;
            
	        }
	      }
      }
    }
    db_fim_transacao($sqlerro);
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
</head>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>	      
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr><td bgcolor="#CCCCCC">&nbsp;</td></tr>
  <tr><td bgcolor="#CCCCCC">&nbsp;</td></tr>
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	if (isset($lic)&&@$lic!=""&&isset($l20_codigo)&&@$l20_codigo!=""){
		$result_info=$clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterlic(null,"distinct pc22_codorc as pc20_codorc",null,"l21_codliclicita=$l20_codigo and l21_situacao = 0 and l08_altera is true"));

             if ($clpcorcamitem->numrows>0){
	     db_fieldsmemory($result_info,0);
        } 
	}
	include("forms/db_frmorcamlancvallic.php");
	?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($achou)&&$achou==true){
     db_msgbox('Licitacao tem itens sem lote.\nFavor definir lote para estes itens.');
     echo "<script>
              document.location.href='lic1_lancavallic001.php';
           </script>";
}

if(isset($incluir) || isset($alterar)){
  if(isset($alterar)){
    $erro_msg = str_replace("Inclusao","Alteracao",$erro_msg);
    $erro_msg = str_replace("EXclus�o","Alteracao",$erro_msg);
  }
  if($sqlerro==true){
    $erro_msg = str_replace("\n","\\n",$erro_msg);
    db_msgbox($erro_msg);
  }else{
    echo "
    <script>
      x = document.form1;
      tf= false;
      for(i=0;i<x.length;i++){
	if(x.elements[i].type == 'select-one'){
	  numero = new Number(x.elements[i].length);
	  for(ii=0;ii<numero;ii++){	    
	    if(x.elements[i].options[ii].selected==true){
	      numeroteste = new Number(ii+1);
	      if(numeroteste<numero && tf==false){
	        x.elements[i].options[ii+1].selected = true;		
		js_dalocation(x.elements[i].options[ii+1].value);
		tf = true;
	      }else if(tf==false){
	        x.elements[i].options[0].selected = true;		
		js_dalocation(x.elements[i].options[0].value);
		tf = true;
	      }
	    }
	  }
	}
      }
    </script>
    ";
  }
}
?>