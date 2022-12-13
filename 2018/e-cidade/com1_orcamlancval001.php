<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include(modification("classes/db_pcorcam_classe.php"));
include(modification("classes/db_pcorcamitem_classe.php"));
include(modification("classes/db_pcorcamitemproc_classe.php"));
include(modification("classes/db_pcorcamforne_classe.php"));
include(modification("classes/db_pcorcamval_classe.php"));
include(modification("classes/db_pcorcamjulg_classe.php"));
include(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$clpcorcam 		   = new cl_pcorcam;
$clpcorcamitem 	   = new cl_pcorcamitem;
$clpcorcamitemproc = new cl_pcorcamitemproc;
$clpcorcamforne    = new cl_pcorcamforne;
$clpcorcamval 	   = new cl_pcorcamval;
$clpcorcamjulg     = new cl_pcorcamjulg;

$db_opcao = 1;
$db_botao = true;

if(isset($alterar) || isset($incluir)){
	
  $sqlerro=false;
  
  if(isset($valores) && trim($valores)!=""){
    $arrval = split("valor_",$valores);
  }else{
    $sqlerro=true;
    $erro_msg = "Usuário: \\n\\nValores do orçamento não informados. \\nAltere antes de continuar. \\n\\nAdministrador: ";
  }
  if(isset($qtdades) && trim($qtdades)!=""){
    $arrqtd = split("qtde_",$qtdades);
  }else{
    $sqlerro=true;
    $erro_msg = "Usuário: \\n\\nQuantidades do orçamento não informadas. \\nAltere antes de continuar. \\n\\nAdministrador: ";
  }
  if(isset($obss) && trim($obss)!=""){
    $arrmrk = split("obs_",$obss);
  }
  if(isset($valoresun) && trim($valoresun)!=""){
    $arrvalun = split("vlrun_",$valoresun);
  }
  if(isset($dataval) && trim($dataval)!=""){
    $arrdat = split("#",$dataval);
  }
  if(isset($valoresbdi) && trim($valoresbdi)!=""){
    $arrbdi = split("bdi_",$valoresbdi);
  }
  if(isset($valoresencargos) && trim($valoresencargos)!=""){
    $arrencargos = split("encargossociais_",$valoresencargos);
  }
  if (isset($taxasestimadas) && trim($taxasestimadas)!="") {
    $arrtaxasestimadas = split("taxaestimada_", $taxasestimadas);
  }


  if(sizeof($arrval)>0 && $sqlerro == false){

  	if($sqlerro==false){
  		
      $validadorc = $pc21_validadorc_ano."-".$pc21_validadorc_mes."-".$pc21_validadorc_dia;		
      $prazoent   = $pc21_prazoent_ano."-".$pc21_prazoent_mes."-".$pc21_prazoent_dia ;

      if ($prazoent=="--" || trim($prazoent)==""){
       $prazoent=null;
      }

      if ($validadorc=="--" || trim($validadorc)==""){
        $validadorc=null;
      }

      $clpcorcamforne->pc21_validadorc = $validadorc;
      $clpcorcamforne->pc21_prazoent   = $prazoent;
      $clpcorcamforne->pc21_orcamforne = $pc21_orcamforne;
      $clpcorcamforne->alterar($pc21_orcamforne);

  	  if($clpcorcamforne->erro_status==0){
  		$sqlerro=true;
  	    $erro_msg=$clpcorcamforne->erro_msg;
  	  }
  	}
  	
    db_inicio_transacao();
    
    for($i=1;$i<sizeof($arrval);$i++){

      $codvalun     = split("_", $arrvalun[$i]);
      $codval       = split("_", $arrval[$i]);
      $codqtd       = split("_", $arrqtd[$i]);
      $desmrk       = split("_", $arrmrk[$i]);
      $bdi          = split("_", $arrbdi[$i]);
      $encargos     = split("_", $arrencargos[$i]);
      $taxaestimada = split("_", $arrtaxasestimadas[$i]);

      if (trim(@$arrdat[$i])!=""){
        $validmin = $arrdat[$i];
      } else {
        $validmin = null;
      }

      if (trim(@$arrdat[$i])=="--"){
        $validmin = null;
      }

      if(isset($desmrk[1])){
        $orcammrk = str_replace("yw00000wy"," ",$desmrk[1]);
      }else{
        $orcammrk = "";
      }
      
      $orcamitem  = $codval[0];
      $orcamval   = $codval[1];
      $orcamitem2 = $codqtd[0];
      $orcamqtd   = $codqtd[1];
      $valorunit  = $codvalun[1];
      
      if(isset($alterar) && $sqlerro==false){
        $clpcorcamval->excluir($pc21_orcamforne,$orcamitem);
		  
        if($clpcorcamval->erro_status==0){

          $erro_msg = $clpcorcamval->erro_msg;
          $sqlerro=true;
          unset($incluir);
        } else {
          $incluir="incluir";
        }
	    
      }
      if(isset($incluir) && $sqlerro==false && $orcamval!=0 ) {

        $pc23_valor = $orcamval;

        $clpcorcamval->pc23_orcamforne        = $pc21_orcamforne;
        $clpcorcamval->pc23_orcamitem         = $orcamitem;
        $clpcorcamval->pc23_valor             = $orcamval;
        $clpcorcamval->pc23_quant             = $orcamqtd;
        $clpcorcamval->pc23_obs               = $orcammrk;
        $clpcorcamval->pc23_bdi               = isset($bdi[1]) ? $bdi[1] : null;
        $clpcorcamval->pc23_encargossociais   = isset($encargos[1]) ? $encargos[1] : null;
        $clpcorcamval->pc23_taxaestimada      = isset($taxaestimada[1]) ? $taxaestimada[1] : null;

        if (isset($validmin) && trim(@$validmin)!="" && $validmin != null){
          $arr_d	= split("-",$validmin);		
          $validmin = $arr_d[2]."-".$arr_d[1]."-".$arr_d[0];
        } else {
          $validmin = "null";
        }	

        $clpcorcamval->pc23_validmin  = $validmin; 
        $clpcorcamval->pc23_vlrun     = $valorunit;
        $clpcorcamval->incluir($pc21_orcamforne,$orcamitem);

        $erro_msg = $clpcorcamval->erro_msg;

        if($clpcorcamval->erro_status==0){
          $sqlerro=true;
          break;
        }
  		
      }
      
      if($sqlerro == false){
		    $clpcorcamjulg->excluir($orcamitem);
		
        if($clpcorcamjulg->erro_status==0){
          $erro_msg = $clpcorcamjulg->erro_msg;
          $sqlerro=true;
        }

        /**
        * @todo passar para Classes
        * Aqui Começa o Julgamento por item
        */
        $result_itemfornec  = $clpcorcamval->sql_record($clpcorcamval->sql_query_file(null,null,"pc23_orcamforne,pc23_orcamitem,pc23_valor,pc23_quant","pc23_valor"," pc23_orcamitem=$orcamitem and pc23_valor<>0 and trim(pc23_valor::text) <> ''"));
        $numrows_itemfornec = $clpcorcamval->numrows;
		
        if(isset($sol) && $sol=="true"){

          $result_lancitem = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmatersol($orcamitem,"pc11_quant"));	
        } elseif(isset($sol) && $sol=="false"){

          $result_lancitem = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterproc($orcamitem,"pc11_quant"));
        }

        db_fieldsmemory($result_lancitem,0);      
        $pontuacao = 1;
	
    		for ($ii = 0; $ii < $numrows_itemfornec; $ii++) {

          db_fieldsmemory($result_itemfornec, $ii);

          if ($pc11_quant==$pc23_quant && $pc23_valor!=0) {

            $clpcorcamjulg->pc24_orcamitem  = $pc23_orcamitem;
            $clpcorcamjulg->pc24_orcamforne = $pc23_orcamforne;
            $clpcorcamjulg->pc24_pontuacao  = $pontuacao;
            $clpcorcamjulg->incluir($pc23_orcamitem,$pc23_orcamforne);

            if ($clpcorcamjulg->erro_status == 0) {

              $erro_msg = $clpcorcamjulg->erro_msg;
              $sqlerro=true;
              break;
            }

            $pontuacao++;
          }
    		}
      }
    }

   /**
    * Quando o Orcamento é julgado por Lote
    */
     $oOrcamento = new OrcamentoCompra($pc20_codorc);
     if ($oOrcamento->getFormaJulgamento() == OrcamentoCompra::FORMA_JULGAMENTO_LOTE) {
       $oOrcamento->julgar(new JulgamentoOrcamentoLote());
     }
    db_fim_transacao($sqlerro);
  }

}

 $rsVerificaAut = $clpcorcamitemproc->sql_record($clpcorcamitemproc->sql_query_solicitem(null,null," distinct pc81_codproc ","","pc22_codorc=".$pc20_codorc." and e54_autori is not null and e54_anulad is null"));
 
 if ($clpcorcamitemproc->numrows > 0 ) {

	 db_msgbox("Não é possível lançar valores, existe uma autorização para esse orçamento de processo de compra!");
 	 db_redireciona("com1_selorc001.php?sol=false");
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td bgcolor="#CCCCCC">&nbsp;</td></tr>
  <tr><td bgcolor="#CCCCCC">&nbsp;</td></tr>
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include(modification("forms/db_frmorcamlancval.php"));
	?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar)){
  if(isset($alterar)){
    $erro_msg = str_replace("Inclusao","Alteracao",$erro_msg);
    $erro_msg = str_replace("EXclusão","Alteracao",$erro_msg);
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
