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
include("classes/db_portaria_classe.php");
include("classes/db_assenta_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_portariaassenta_classe.php");
include("classes/db_portariatipo_classe.php");
include("classes/db_rhparam_classe.php");
include('libs/db_utils.php');

$oPost = db_utils::postMemory($HTTP_POST_VARS);
$oGet  = db_utils::postMemory($HTTP_GET_VARS);

$clportaria        = new cl_portaria;
$classenta         = new cl_assenta;
$clrhparam		   = new cl_rhparam;
$clrhpessoal       = new cl_rhpessoal;
$clportariaassenta = new cl_portariaassenta;
$clportariatipo    = new cl_portariatipo;

$db_opcao = 3;
$lSqlErro = false;
$db_opcao_numero = 3;


if (isset($oPost->excluir)) {
	
  db_inicio_transacao();	
	
  $rsConsultaPortariaAssenta = $clportariaassenta->sql_record($clportariaassenta->sql_query_file(null,"*",null," h33_portaria = {$oPost->h31_sequencial} "));
  $iNroPortariaAssenta = $clportariaassenta->numrows;
  
  for ($i=0; $i < $iNroPortariaAssenta; $i++){
    $oPortariaAssenta = db_utils::fieldsMemory($rsConsultaPortariaAssenta,$i);

    $clportariaassenta->excluir($oPortariaAssenta->h33_sequencial);
    if($clportariaassenta->erro_status == 0){
  	  $lSqlErro = true;
  	  $sMsgErro = $clportariaassenta->erro_msg;
    }
    
    /**
     * verificamos se o assentamento tem vinculo com afastamento no pessoal, 
     * e efetuamos a exclusao do mesmo
     */
    $clafastaassenta   = new cl_afastaassenta();
    $sSqlAfastaAssenta = $clafastaassenta->sql_query_file(null, "h81_afasta", null, "h81_assenta = {$oPortariaAssenta->h33_assenta}");
    $rsAfastaAssenta   = db_query($sSqlAfastaAssenta);

    if (!$rsAfastaAssenta) {
      throw new DBException($rsAfastaAssenta->erro_msg);
    }

    if (pg_num_rows($rsAfastaAssenta) > 0) {
      
      $clafastaassenta->excluir(null, "h81_assenta = {$oPortariaAssenta->h33_assenta}");
      $clafasta = new cl_afasta();
      $clafasta->excluir(db_utils::fieldsMemory($rsAfastaAssenta, 0)->h81_afasta);
    }

    $classenta->excluir($oPortariaAssenta->h33_assenta);

    if($classenta->erro_status == 0){
 	  $lSqlErro = true;
 	  $sMsgErro = $classenta->erro_msg;     
    }    
    
  }
  
  $clportaria->excluir($oPost->h31_sequencial);  
  if($clportaria->erro_status == 0){
	$lSqlErro = true;
	$sMsgErro = $clportaria->erro_msg;
  }
	
  db_fim_transacao($lSqlErro);
  
  
} else if (isset($oGet->chavepesquisa)) {
	
  $rsConsultaPortaria = $clportaria->sql_record($clportaria->sql_query_asse($oGet->chavepesquisa));	

  if ( $clportaria->numrows > 0 ) {	
  	
  	$oPortaria = db_utils::fieldsMemory($rsConsultaPortaria,0);
	
	  $h31_sequencial	  = $oPortaria->h31_sequencial;  	
  	$h31_portariatipo = $oPortaria->h31_portariatipo; 
  	$h12_descr        = $oPortaria->h12_descr;         
  	$h31_numero       = $oPortaria->h31_numero;        
  	$h31_anousu       = $oPortaria->h31_anousu;
  	$h31_amparolegal  = $oPortaria->h31_amparolegal;
  	
  	$aDtInicio = split("-",$oPortaria->h31_dtinicio);
  	$h31_dtinicio_dia = $aDtInicio[2];     
  	$h31_dtinicio_mes = $aDtInicio[1];
  	$h31_dtinicio_ano = $aDtInicio[0];  	

  	$aDtPortaria = split("-",$oPortaria->h31_dtportaria); 
  	$h31_dtportaria_dia = $aDtPortaria[2];
  	$h31_dtportaria_mes = $aDtPortaria[1];
  	$h31_dtportaria_ano = $aDtPortaria[0];    
  	
  	$aDtConc = split("-",$oPortaria->h16_dtconc);    
  	$h16_dtconc_dia = $aDtConc[2];
  	$h16_dtconc_mes = $aDtConc[1];
  	$h16_dtconc_ano = $aDtConc[0];
  	  	  	
  	$aDtTerm = split("-",$oPortaria->h16_dtterm);  		      
    $h16_dtterm_dia = $aDtTerm[2];        
    $h16_dtterm_mes = $aDtTerm[1];
    $h16_dtterm_ano = $aDtTerm[0];
    
    $h16_codigo	    = $oPortaria->h16_quant;
    $h16_quant      = $oPortaria->h16_quant;
    $quantidade     = $oPortaria->h16_quant;
    $h16_atofic     = $oPortaria->h16_atofic;       
    $h16_histor     = $oPortaria->h16_histor;

    $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
    $rsComplemento   = db_query($oDaoAssentaAttr->sql_query(null,null, "h80_db_cadattdinamicovalorgrupo", null, "h80_assenta = {$oPortaria->h16_codigo}"));


    if (pg_num_rows($rsComplemento) > 0) {
      db_fieldsmemory($rsComplemento,0);
    }


              	

    echo "<script>";
    echo "  parent.iframe_funcionarios.js_carregaGrid('portaria',".$oGet->chavepesquisa.");";
    echo "</script>";
    
  }
	
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
      
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
  <table>
    <tr> 
      <td> 
	    <?
	      include("forms/db_frmportarialotecol.php");
	    ?>
	  </td>
    </tr>
  </table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1","h31_portariatipo",true,1,"h31_portariatipo",true);
js_pesquisa_Assinaturas(false);
      
</script>
<?

if(isset($oPost->excluir)){
  
  if ($lSqlErro){
  	
  	db_msgbox($sMsgErro);
  	
  } else {
  	
    echo "<script>";
    echo " document.form1.db_opcao.disabled  = true;";
    echo " document.form1.pesquisar.disabled = false;";
    echo " document.form1.imprimir.disabled  = false;";
    echo " parent.iframe_funcionarios.js_limpaFiltros();";
    echo " parent.iframe_funcionarios.document.getElementById('listaFuncionarios').innerHTML = '';";   
    echo "</script>";
    
    $clportaria->erro(true,true);
  }
  
} else if (!isset($oGet->chavepesquisa))  {
	
	echo "<script>";
	  
    echo " document.form1.db_opcao.disabled  = true;";
    //echo " document.form1.pesquisar.disabled = false;";
    echo " document.form1.imprimir.disabled  = false;";
    echo " js_pesquisa(3);";   
    echo "</script>";
	
}

?>