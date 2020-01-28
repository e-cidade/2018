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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_solicita_classe.php");
include("classes/db_solicitatipo_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_solicitempcmater_classe.php");
include("classes/db_solicitemunid_classe.php");
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamitemsol_classe.php");
include("classes/db_pcorcamitemproc_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_pcorcamjulg_classe.php");
include("classes/db_pcdotac_classe.php");
include("classes/db_pcproc_classe.php");
include("classes/db_pcprocitem_classe.php");
include("classes/db_liclicitem_classe.php");
include("classes/db_liclicita_classe.php");

$clsolicita         = new cl_solicita;
$clsolicitatipo     = new cl_solicitatipo;
$clsolicitem        = new cl_solicitem;
$clsolicitempcmater = new cl_solicitempcmater;
$clsolicitemunid    = new cl_solicitemunid;
$clpcorcam          = new cl_pcorcam;
$clpcorcamitem      = new cl_pcorcamitem;
$clpcorcamitemsol   = new cl_pcorcamitemsol;
$clpcorcamitemproc  = new cl_pcorcamitemproc;
$clpcorcamval       = new cl_pcorcamval;
$clpcorcamjulg      = new cl_pcorcamjulg;
$clpcdotac          = new cl_pcdotac;
$clpcproc           = new cl_pcproc;
$clpcprocitem       = new cl_pcprocitem;
$clliclicitem       = new cl_liclicitem();
$clliclicita        = new cl_liclicita;

$clrotulo = new rotulocampo;
$clrotulo->label("l03_descr");

$clsolicita->rotulo->label();
$clsolicitatipo->rotulo->label();
$clsolicitem->rotulo->label();
$clsolicitempcmater->rotulo->label();
$clsolicitemunid->rotulo->label();
$clliclicita->rotulo->label();

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="">
<center>
<form name="form1" method="post" action="com3_conssolic002.php">
<?
  if(isset($solicitacao)){
    if($solicitacao=="1"){
      $result_itensdotac = $clsolicitem->sql_record(      
      $clsolicitem->sql_query_rel(null,"distinct 
                                        pc11_codigo,
                                        pc11_seq,
                                        pc11_quant,
                                        pc11_vlrun,
                                        pc11_prazo,
                                        pc11_resum,
                                        pc11_just,
                                        pc11_liberado,
                                        pc17_codigo,
                                        pc17_quant,
					                              m61_descr,
					                              m61_usaquant,
					                              pc01_codmater,
					                              pc01_descrmater,
					                              pc01_servico,
					                              o56_elemento,
					                              o56_descr",
                                       "pc11_codigo,
                                        pc01_descrmater",
                                       "pc11_numero=$numero")
                                       );
      $numrows_itensdotac = $clsolicitem->numrows;
      echo "<fieldset>";
      echo "<legend><strong>Itens/Dotações</strong></legend>";
      echo "<table width='70%' border='0'>\n";
      for($i=0;$i<$numrows_itensdotac;$i++){
        
        db_fieldsmemory($result_itensdotac,$i);
	      echo "  <tr>\n";
        echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Item: </strong></td>\n";
        echo "    <td colspan='1' align='left'  bgcolor='#FFFFFF'><font color='#333333'>$pc11_seq</font></td>\n";
        echo "    <td colspan='1' nowrap='nowrap' align='right' bgcolor='#CCCCCC'><strong>Código material: </strong></td>\n";
      	echo "    <td colspan='1' align='left' bgcolor='#FFFFFF'><font color='#333333'>$pc01_codmater</font></td>\n";
	      echo "  </tr>\n";
	      echo "  <tr>\n";
        echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Quantidade: </strong></td>\n";
        echo "    <td colspan='1' align='left'  bgcolor='#FFFFFF'><font color='#333333'>$pc11_quant</font></td>\n";
        echo "    <td colspan='1' nowrap='nowrap' align='right' bgcolor='#CCCCCC'><strong>Valor Unitário: </stromg></td>\n";
        echo "    <td colspan='1' align='left'  bgcolor='#FFFFFF'><font color='#333333'>".db_formatar($pc11_vlrun,"v")."</font></td>\n";
	      echo "  </tr>\n";
        if(isset($pc01_codmater) && trim($pc01_codmater)!=""){
          
      	  echo "  <tr>\n";
      	  echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Descrição: </strong></td>\n";
      	  echo "    <td colspan='3' align='left' bgcolor='#FFFFFF'><font color='#333333'>$pc01_descrmater</font></td>\n";
      	  echo "  </tr>\n";
      	  echo "  <tr>\n";
      	  echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Sub. Elemento: </strong></td>\n";
      	  echo "    <td colspan='3' align='left' bgcolor='#FFFFFF'><font color='#333333'>".db_formatar($o56_elemento,"elemento")."</font></td>\n";
      	  echo "  </tr>\n";
      	  echo "  <tr>\n";
      	  echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Descrição: </strong></td>\n";
      	  echo "    <td colspan='3' align='left' bgcolor='#FFFFFF'><font color='#333333'>$o56_descr</font></td>\n";
      	  echo "  </tr>\n";
      	  if(isset($pc17_codigo) && trim($pc17_codigo)!=""){
      	    if($m61_usaquant=='t'){
      	      $m61_descr .= " ($pc17_quant UNIDADES)";
      	    }
      	    echo "  <tr>\n";
      	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Referência: </strong></td>\n";       
      	    echo "    <td colspan='3' align='left' bgcolor='#FFFFFF'><font color='#333333'>$m61_descr</font></td>\n";
      	    echo "  </tr>\n";
      	  }else if($pc01_servico=='t'){
      	    echo "  <tr>\n";
      	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Referência: </strong></td>\n";       
      	    echo "    <td colspan='3' align='left' bgcolor='#FFFFFF'><font color='#333333'>SERVIÇO</font></td>\n";
      	    echo "  </tr>\n";
      	  }
        }
        if(isset($pc11_resum) && trim($pc11_resum)!=""){
          
      	  echo "  <tr>\n";
      	  echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Resumo: </strong></td>\n";
      	  echo "    <td colspan='3' align='left' bgcolor='#FFFFFF'><font color='#333333'>$pc11_resum</font></td>\n";
      	  echo "  </tr>\n";
        }
        if(isset($pc11_prazo) && trim($pc11_prazo)!=""){
          
      	  echo "  <tr>\n";
      	  echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Prazo entrega: </strong></td>\n";
      	  echo "    <td colspan='3' align='left' bgcolor='#FFFFFF'><font color='#333333'>$pc11_prazo</font></td>\n";
      	  echo "  </tr>\n";
        }
        if(isset($pc11_just) && trim($pc11_just)!=""){
          
      	  echo "  <tr>\n";
      	  echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Justificativa: </strong></td>\n";
      	  echo "    <td colspan='3' align='left' bgcolor='#FFFFFF'><font color='#333333'>$pc11_just</font></td>\n";
      	  echo "  </tr>\n";
        }
        if(isset($pc11_pgto) && trim($pc11_pgto)!=""){
          
      	  echo "  <tr>\n";
      	  echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Cond. Pagamento: </strong></td>\n";
      	  echo "    <td colspan='3' align='left'  bgcolor='#FFFFFF'><font color='#333333'>$pc11_pgto</font></td>\n";
      	  echo "  </tr>\n";
        }
        $result_dotacoes = $clpcdotac->sql_record($clpcdotac->sql_query_file($pc11_codigo,db_getsession("DB_anousu"),null,"pc13_coddot"));
        if($clpcdotac->numrows>0){
          
      	  echo "  <tr>\n";
      	  echo "    <td colspan='4' align='center'  bgcolor='#CCCCCC'>";db_ancora("Clique aqui para ver dotações do item $pc11_codigo","js_verdotac($pc11_codigo,$pc01_codmater,$numero)",1);echo"</td>\n";
      	  echo "  </tr>\n";
        } 
        if(($i+1)!=$numrows_itensdotac){
          
      	  echo "  <tr>\n";
      	  echo "    <td colspan='4' align='left'  bgcolor='#CCCCCC'>&nbsp;</td>\n";
      	  echo "  </tr>\n";
      	  echo "  <tr>\n";
      	  echo "    <td colspan='4' align='left'  bgcolor='#CCCCCC'>&nbsp;</td>\n";
      	  echo "  </tr>\n";
        }
      }
      echo "</table>\n";
      echo "</fieldset>";
    }else if($solicitacao=="2" || $solicitacao=="4"){
      if($solicitacao=="2"){
        $result_orcamsol = $clpcorcam->sql_record($clpcorcam->sql_query_gercons(null,"pcorcam.pc20_codorc,pcorcam.pc20_dtate,pcorcam.pc20_hrate,pc22_orcamitem,z01_numcgm,z01_nome,pc23_valor,pc23_quant,pc01_descrmater,pc24_pontuacao,pc17_quant,pc17_codigo,m61_descr,m61_usaquant,pc01_servico","pc20_codorc,pc21_orcamforne,pc22_orcamitem","pc11_numero=$numero"));
      }else if($solicitacao=="4"){
        $result_orcamsol = $clpcorcam->sql_record($clpcorcam->sql_query_gerconspc(null,"pcorcam.pc20_codorc,pcorcam.pc20_dtate,pcorcam.pc20_hrate,pc22_orcamitem,z01_numcgm,z01_nome,pc23_valor,pc23_quant,pc01_descrmater,pc24_pontuacao,pc17_quant,pc17_codigo,m61_descr,m61_usaquant,pc01_servico,pc21_prazoent,pc21_validadorc,pc23_validmin","pc20_codorc,pc21_orcamforne,pc22_orcamitem","pc11_numero=$numero"));
      }
      $numrows_orcamsol= $clpcorcam->numrows;
      if($numrows_orcamsol>0){
  echo "<fieldset>";
  echo "<legend><strong>Orçamento da solicitação</strong></legend>";
	echo "<table width='70%' border='0'>\n";
	$antigorcam = "";
	$antigoforn = "";
	$julgar     = false;
	for($i=0;$i<$numrows_orcamsol;$i++){
	  db_fieldsmemory($result_orcamsol,$i);
	    if($antigorcam!=$pc20_codorc){
	      if(($i+1)!=$numrows_orcamsol && $i!=0){
		echo "  <tr>\n";
		echo "    <td colspan='4' nowrap='nowrap' align='left'  bgcolor='#CCCCCC'>&nbsp;</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td colspan='4' nowrap='nowrap' align='left'  bgcolor='#CCCCCC'>&nbsp;</td>\n";
		echo "  </tr>\n";
	      }
	      echo "  <tr>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Orçamento: </strong></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>$pc20_codorc</font></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Data/Hora entrega: </strong></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>".db_formatar($pc20_dtate,"d")." - $pc20_hrate</font></td>\n";
	      echo "  </tr>\n";
	      echo "  <tr>\n";
	      echo "    <td colspan='4' nowrap='nowrap' align='left'  bgcolor='#CCCCCC'>&nbsp;</td>\n";
	      echo "  </tr>\n";
	      echo "  <tr>\n";
	      echo "    <td align='left' colspan='4'><h4><strong>Fornecedores do orçamento</strong></h4></td>\n";
	      echo "  </tr>\n";
	      $antigorcam = $pc20_codorc;
	    }
	    if($z01_numcgm!=$antigoforn){
	      if($i==0){
	          
	        echo "<tr>";
 	        echo "  <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>CGM: </strong></td>";
 	        echo "  <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>{$z01_numcgm}</font></td>";
 	        echo "  <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Fornecedor</strong></td>";
 	        echo "  <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>{$z01_nome}</font></td>";
 	        echo "</tr>";
	        if ($solicitacao=="4") {
	          
	          $sValidadeOrcamento = db_formatar($pc21_validadorc,"d");
	          $sPrazoEntrega      = db_formatar($pc21_prazoent,"d");
	          echo "<tr>";
	          echo "  <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Validade do Orçamento: </strong></td>";
	          echo "  <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>{$sValidadeOrcamento}</font></td>";
	          echo "  <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Prazo de Entrega: </strong></td>";
	          echo "  <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>{$sPrazoEntrega}</font></td>";
	          echo "</tr>";
	        }
	      }
	      $antigoforn = $z01_numcgm;
	    }
	    if($julgar==false && trim($pc24_pontuacao)!=""){
	      $julgar = true;
	    }
	}
	if($julgar==true){
	  echo "  <tr>\n";
	  echo "    <td align='left' colspan='4'>&nbsp;</td>\n";
	  echo "  </tr>\n";
	  echo "  <tr>\n";
	  echo "    <td align='left' colspan='4'><h4><strong>Julgar orçamento</strong></h4></td>\n";
	  echo "  </tr>\n";
	  for($i=0;$i<$numrows_orcamsol;$i++){
	    db_fieldsmemory($result_orcamsol,$i);
	    if($pc24_pontuacao==1){
	      echo "  <tr>\n";
	      echo "    <td colspan='4' nowrap='nowrap' align='left' bgcolor='#DEB887'><strong>Item no orçamento: </strong><font color='#333333'>$pc22_orcamitem - $pc01_descrmater</font></td>\n";
	      echo "  </tr>\n";
	      if(trim($pc17_codigo)!=""){
		echo "  <tr>\n";
		echo "    <td colspan='4' nowrap='nowrap' align='left' bgcolor='#DEB887'>
		            <strong>Referência: </strong> 
		            <font color='#333333'>
			        $m61_descr";
		if($m61_usaquant=="t"){
		  echo "        ($pc17_quant UNIDADES)";
		}
		echo "
			    </font>
			  </td>\n";
		echo "  </tr>\n";
	      }else if(isset($pc01_servico)){
		echo "  <tr>\n";
		echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Referência: </strong></td>\n";       
		echo "    <td colspan='3' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>SERVIÇO</font></td>\n";
		echo "  </tr>\n";
	      }
	      echo "  <tr>\n";
	      echo "    <td colspan='4' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Fornecedor: </strong><font color='#333333'>$z01_numcgm - $z01_nome</font></td>\n";
	      echo "  </tr>\n";
	      echo "  <tr>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Qtd. Lançada: </strong></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>$pc23_quant</font></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Vlr. Lançado: </strong></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>".db_formatar($pc23_valor,"v")."</font></td>\n";
	      echo "  </tr>\n";
	      if($solicitacao=="4"){
	      	echo "  <tr>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Validade Mínima: </strong></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>".db_formatar($pc23_validmin,"d")."</font></td>\n";
	      echo "    <td colspan='2' nowrap='nowrap' align='left' bgcolor='#CCCCCC'></td>\n";
	      
	      
	      echo "  </tr>\n";
	      }
	      echo "  <tr>\n";
	      echo "    <td colspan='4' nowrap='nowrap' align='left' bgcolor='#CCCCCC'>&nbsp;</td>\n";
	      echo "  </tr>\n";
	    }
	  }
	}else{
	  echo "  <tr>\n";
	  echo "    <td align='center' colspan='4' bgcolor='#CCCCCC'><BR><font color='#333333'><strong>Nenhum item lançado no 'Lançar valores'.</strong></font></td>\n";
	  echo "  </tr>\n";
	}
	echo "</table>\n";
	echo "</fieldset>";
      }else{
	echo "<table width='70%' border='0'>\n";
	echo "  <tr>\n";
        if($solicitacao=="2"){
	  echo "    <td align='center'><h3><strong>Não existe orçamento para esta solicitação.</strong></h3></td>\n";
	}else if($solicitacao=="4"){
	  echo "    <td align='center'><h3><strong>Não existe orçamento para processo de compras desta solicitação.</strong></h3></td>\n";
	}
	echo "  </tr>\n";
	echo "</table>\n";
      }
    }else if($solicitacao=="3" || $solicitacao=="5"){
      $result_processos = $clpcprocitem->sql_record($clpcprocitem->sql_query_pcmater(null,"pc80_codproc,pc80_data,id_usuario,nome,pc80_resumo,pc11_codigo,pc11_seq,pc81_codprocitem,pc01_codmater,pc01_descrmater,o56_elemento,o56_descr,pc11_resum,pc17_quant,pc17_codigo,m61_descr,m61_usaquant,pc01_servico,e54_autori,e60_numemp,e60_anousu,e60_codemp,e54_anulad","pc80_codproc,pc11_codigo","pc10_numero=$numero"));
      $numrows_processos = $clpcprocitem->numrows;
      if($solicitacao=="3"){
	if($numrows_processos>0){
	  echo "<fieldset>";
	  echo "<legend><strong>Processos de Compras</strong></legend>";
	  echo "<table width='70%' border='0'>\n";
	  $pc80_codproc_ant = "";
	  for($i=0;$i<$numrows_processos;$i++){
	    db_fieldsmemory($result_processos,$i);
	    if($pc80_codproc!=$pc80_codproc_ant){
	      echo "  <tr>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Número PC: </strong></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>$pc80_codproc</font></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Data: </strong></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>".db_formatar($pc80_data,"d")."</font></td>\n";
	      echo "  </tr>\n";
	      echo "  <tr>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Usuário: </strong></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>$id_usuario</font></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Nome:</strong></td>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>$nome</font></td>\n";
	      echo "  </tr>\n";
	      echo "  <tr>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Resumo: </strong></td>\n";
	      echo "    <td colspan='3' align='left' bgcolor='#FFFFFF'><font color='#333333'>$pc80_resumo</font></td>\n";
	      echo "  </tr>\n";
	      echo "</table>\n";
	      echo "</fieldset>";
	      echo "<fieldset>";
	      echo "<legend><strong>Itens do processo de compras N&ordm; $pc80_codproc</strong></legend>";
	      echo "<table width='70%' border='0'>\n";
	      $pc80_codproc_ant = $pc80_codproc;
	    }
	    echo "  <tr>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Item sol.: </strong></td>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#DEB887'><font color='#333333'>$pc11_codigo</font></td>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Sequencial sol.: </strong></td>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#DEB887'><font color='#333333'>$pc11_seq</font></td>\n";
	    echo "  </tr>\n";
	    echo "  <tr>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Item proc.: </strong></td>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#DEB887'><font color='#333333'>$pc81_codprocitem</font></td>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Autorização: </strong></td>\n";
	    if(trim($e54_autori)!="" && trim($e54_anulad)==""){
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#DEB887'><font color='#333333'>$e54_autori</font></td>\n";
	    }else{                                                          
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#DEB887'><font color='#333333'>Não gerada</font></td>\n";
	    }
	    echo "  </tr>\n";
	    echo "  <tr>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Material: </strong></td>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>$pc01_codmater</font></td>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Descrição: </strong></td>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>$pc01_descrmater</font></td>\n";
	    echo "  </tr>\n";
	    echo "  <tr>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Sub-elemento: </strong></td>";
	    echo "    <td colspan='3' nowrap='nowrap' align='left' bgcolor='#FFFFFF'>
			<font color='#333333'>".db_formatar($o56_elemento,"elemento")." - $o56_descr</font>
		      </td>\n";
	    echo "  </tr>\n";
	    if(trim($pc17_codigo)!=""){
	      echo "  <tr>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Referência: </strong></td>";
	      echo "    <td colspan='3' nowrap='nowrap' align='left' bgcolor='#FFFFFF'>";
	      echo "      <font color='#333333'>
			      $m61_descr";
	      if($m61_usaquant=="t"){
		echo "        ($pc17_quant UNIDADES)";
	      }
	      echo "
			  </font>
			</td>\n";
	      echo "  </tr>\n";
	    }else if(isset($pc01_servico)){
	      echo "  <tr>\n";
	      echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Referência: </strong></td>\n";       
	      echo "    <td colspan='3' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'><strong>SERVIÇO</strong></font></td>\n";
	      echo "  </tr>\n";
	    }
	    echo "  <tr>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Usuário: </strong></td>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>$id_usuario</font></td>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#CCCCCC'><strong>Nome: </strong></td>\n";
	    echo "    <td colspan='1' nowrap='nowrap' align='left' bgcolor='#FFFFFF'><font color='#333333'>$nome</font></td>\n";
	    echo "  </tr>\n";
	    if(($i+1)!=$numrows_processos){
	      echo "  <tr>\n";
	      echo "    <td colspan='4' nowrap='nowrap' align='right' bgcolor='#CCCCCC'>&nbsp;</td>\n";
	      echo "  </tr>\n";
	    }
	  }
	  echo "</table>\n";
	  echo "</fieldset>";
	}else{
	  echo "<table border='0' >\n";
	  echo "  <tr>\n";
	  echo "    <td align='center'><h3><strong>Não existe processo de compras para esta solicitação.</strong></h3></td>\n";
	  echo "  </tr>\n";
	  echo "</table>\n";
	}
      }else if($solicitacao=="5"){
	$countaut = 0;
	$arr_aut  = Array();
	if($numrows_processos>0){
	  echo "<fieldset>";
	  echo "<legend><strong>Autorizações de empenho</strong></legend>";
	  echo "<table width='70%' border='0' >\n";
	  for($i=0;$i<$numrows_processos;$i++){
	    db_fieldsmemory($result_processos,$i);
	    if(trim($e54_autori)!=""){
	      $countaut++;
	      if($countaut==1){
		echo "  <tr>\n";
		echo "    <td colspan='2' width='50%' align='center' bgcolor='#DEB887'><strong>Autorizações</strong></td>\n";
		echo "    <td colspan='2' width='50%' align='center' bgcolor='#DEB887'><strong>Empenhadas</strong></td>\n";
		echo "  </tr>\n";
	      }	      
	      if(!in_array($e54_autori."_".$e60_numemp,$arr_aut)){
		$arr_aut[$e54_autori."_".$e60_numemp] = $e54_autori."_".$e60_numemp;
		echo "  <tr>\n";
		if(trim($e54_anulad)==""){
		  echo "    <td colspan='2' width='50%' align='center' bgcolor='#FFFFFF'><font color='#333333'><strong>";db_ancora("$e54_autori","js_pesquisaaut($e54_autori);",1);echo"</strong></font></td>\n";
		}else{
		  echo "    <td colspan='2' width='50%' align='center' bgcolor='#FFFFFF'><font color='#333333'><strong>$e54_autori (anulada)</strong></font></td>\n";
		}
		if(trim($e60_numemp)!=""){
		  echo "    <td colspan='2' width='50%' align='center' bgcolor='#FFFFFF'><font color='#333333'><strong>";db_ancora("$e60_codemp/$e60_anousu","js_pesquisaemp($e60_numemp);",1);echo"</strong></font></td>\n";
		}else{
		  echo "    <td colspan='2' width='50%' align='center' bgcolor='#FFFFFF'><font color='#333333'><strong>Não empenhada</strong></font></td>\n";
		}
		echo "  </tr>\n";
	      }
	    }
	  }
	  if($countaut==0){
	    echo "  <tr>\n";
	    echo "    <td align='center'><h3><strong>Não existe autorização de empenho para processos de compras desta solicitação.</strong></h3></td>\n";
	    echo "  </tr>\n";
	  }
	  echo "</table>\n";
	  echo "</fieldset>";
	}else{
	  echo "<table border='0' >\n";
	  echo "  <tr>\n";
	  echo "    <td align='center'><h3><strong>Não existe autorização de empenho para processos de compras desta solicitação.</strong></h3></td>\n";
	  echo "  </tr>\n";
	  echo "</table>\n";
	}
      }
    }else if ($solicitacao == "7"){
 	  $res_liclicitem = $clliclicitem->sql_record($clliclicitem->sql_query(null,"distinct l20_codigo","l20_codigo","pc11_numero=$numero"));
	  if ($clliclicitem->numrows > 0){
	       db_fieldsmemory($res_liclicitem,0);
               $res_liclicita = $clliclicita->sql_record($clliclicita->sql_query($l20_codigo));
	       if ($clliclicita->numrows > 0){
	            db_fieldsmemory($res_liclicita,0);
      ?>
          <table width="70%" border="0">
	          <tr>
	      			<td nowrap="nowrap"><strong><h3>Licitação</h3></strong></td>
	    			</tr>
	    			<tr>
	      			<td nowrap="nowrap">
        	      <? echo "<b>".$Ll20_codigo."</b>&nbsp;"; ?>
        	    </td>
        	    <td nowrap="nowrap">
                <? db_input('l20_codigo',20,$Il20_codigo,true,'text',4,"readonly"); ?>
	      			</td>
              <td nowrap="nowrap"><b>Modalidade:</b>&nbsp;</td>
              <td nowrap="nowrap">
              <?
                db_input('l20_codtipocom',6,$Il20_codtipocom,true,'text',4,"readonly");
		            db_input('l03_descr',35,$Il03_descr,true,'text',4,"readonly");
              ?>
              </td>    
	    			</tr>
            <tr>
              <td nowrap="nowrap"><b><?=@$Ll20_datacria?></b>&nbsp;</td>
              <td nowrap="nowrap">
	              <?php		
                  $ano = substr(@$l20_datacria,0,4);
	                $mes = substr(@$l20_datacria,5,2);
	                $dia = substr(@$l20_datacria,8,2);
	                db_inputdata('l20_datacria',"$dia","$mes","$ano",true,'text',3,"","","#FFFFFF");
	              ?>
              </td>
              <td nowrap="nowrap"><b><?=@$Ll20_horacria?></b>&nbsp;</td>
              <td nowrap="nowrap">
          	    <?php		
                  db_input('l20_horacria',5,$Il20_horacria,true,'text',4,"readonly");	     
          	    ?>
              </td>      
            </tr>
            <tr>
              <td nowrap="nowrap"><b><?=@$Ll20_dataaber?></b>&nbsp;</td>
              <td nowrap="nowrap">
	              <?php		
                  $ano1 = substr(@$l20_dataaber,0,4);
	                $mes1 = substr(@$l20_dataaber,5,2);
	                $dia1 = substr(@$l20_dataaber,8,2);
	                db_inputdata('l20_dataaber',"$dia1","$mes1","$ano1",true,'text',3,"","","#FFFFFF");
	              ?>
              </td>
              <td nowrap="nowrap"><b><?=@$Ll20_horaaber?></b>&nbsp;</td>
              <td nowrap="nowrap">
	              <?php		
                  db_input('l20_horaaber',5,$Il20_horaaber,true,'text',4,"readonly");	     
	              ?>
              </td>      
            </tr>    
            <tr>
              <td nowrap="nowrap"><b><?=@$Ll20_dtpublic?></b>&nbsp;</td>
              <td nowrap="nowrap">
	              <?php
                  $ano2 = substr($l20_dtpublic,0,4);
	                $mes2 = substr($l20_dtpublic,5,2);
	                $dia2 = substr($l20_dtpublic,8,2);
	                db_inputdata('l20_dtpublic',"$dia2","$mes2","$ano2",true,'text',3,"","","#FFFFFF");
                ?>
              </td>
              <td nowrap="nowrap"><b><?=@$Ll20_id_usucria?></b>&nbsp;</td>
              <td nowrap="nowrap">
                <?php
                  db_input('l20_id_usucria',6,$Il20_id_usucria,true,'text',4,"readonly");
                  db_input('nome',34,@$Inome,true,'text',4,"readonly");
                ?>
              </td>
            </tr>
            <tr> 
      	      <td nowrap="nowrap" colspan="1"><b><?=@$Ll20_local?></b>&nbsp;</td>
      	      <td nowrap="nowrap" colspan="3">
      	      <? 
      	        db_textarea("l20_local","","60",$Il20_local,true,'text',4,"readonly");
      	      ?>
      	      </td>
      	    </tr>
      	    <tr> 
       	      <td nowrap="nowrap" colspan="1"><b><?=@$Ll20_objeto?></b>&nbsp;</td>
       	      <td nowrap="nowrap" colspan="3">
      	      <? 
      	        db_textarea("l20_objeto","","60",$Il20_objeto,true,'text',4,"readonly");
      	      ?>
      	      </td>        
    	      </tr>  
            <tr>
              <td nowrap="nowrap" colspan="4" align="center">
                <table border="0" align="center">
                  <tr>
                    <td colspan="2" align="">
                      <iframe name="itens" id="itens" src="forms/db_frminfolic.php?l20_codigo=<?=$l20_codigo?>" width="540" height="150" marginwidth="0" marginheight="0" frameborder="1"></iframe>
                    </td>
                  </tr>     
                </table>
	      			</td>
            </tr>
	  			</table>
      <?
      	       }
	  }else{
      ?>

	  <table border="0">
	    <tr>
	      <td align="center"><h3><strong>Essa solicitação não esta em processo licitatório.</strong></h3></td>
	    </tr>
	  </table>
      <?
	  }
     }
  }
?>
</form>
</center>
<script>
function js_verdotac(codigo,mater,numero){  
  qry  = "";
  qry += "pc13_codigo="+codigo;
  qry += "&pc16_codmater="+mater;
  qry += "&numero="+numero;
  qry += "&consulta=consulta";
  js_OpenJanelaIframe('top.corpo','db_iframe_dotac','com1_seldotac001.php?'+qry,'Dotações do item '+codigo,true);
}
function js_pesquisaaut(autorizacao){
  js_JanelaAutomatica('empautoriza',autorizacao);
}
function js_pesquisaemp(empenho){
  js_OpenJanelaIframe('top.corpo','iframeautoriza','func_empempenho001.php?e60_numemp='+empenho,'Empenho '+empenho,true);
}
</script>
</body>
</html>
