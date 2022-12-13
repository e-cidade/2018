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
include(modification("libs/db_libpessoal.php"));
include(modification("classes/db_afasta_classe.php"));
include(modification("classes/db_codmovsefip_classe.php"));
include(modification("classes/db_movcasadassefip_classe.php"));
include(modification("classes/db_rhpessoal_classe.php"));
include(modification("classes/db_pontofx_classe.php"));
include(modification("classes/db_pontofs_classe.php"));
include(modification("classes/db_cfpess_classe.php"));
include(modification("classes/db_rhrubricas_classe.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);
$clafasta          = new cl_afasta;
$clrhpessoal       = new cl_rhpessoal;
$clcodmovsefip     = new cl_codmovsefip;
$clmovcasadassefip = new cl_movcasadassefip;
$clpontofx         = new cl_pontofx;
$clpontofs         = new cl_pontofs;
$clcfpess          = new cl_cfpess;
$clrhrubricas      = new cl_rhrubricas;
$clafastaassenta   = new cl_afastaassenta;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$db_botao = false;
$db_opcao = 33;

if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;

  $sSqlAfastaAssenta = $clafastaassenta->sql_query(null, "*", null, " h81_afasta = {$r45_codigo}");
  $rsAfastaAssenta   = db_query($sSqlAfastaAssenta);

  if(!$rsAfastaAssenta || pg_num_rows($rsAfastaAssenta) == 0){

    $clafasta->excluir($r45_codigo);

  } elseif(pg_num_rows($rsAfastaAssenta) > 0) {

    $clafasta->erro_status = "0";
    $clafasta->erro_msg    = 'Para a exclusão deste registro é necessário excluir o assentamento no RH vinculado ao afastamento.';

  }

  if($clafasta->erro_status != "0"){
    $result_pontofx = $clpontofx->sql_record($clpontofx->sql_query_file(db_anofolha(),db_mesfolha(),$r45_regist));
    $numrows_pontofx = $clpontofx->numrows;

    if($numrows_pontofx > 0){
      $subpes = db_anofolha();
      $subpes.= db_mesfolha();
      ferias($r45_regist);
		
      $result_param = $clcfpess->sql_record($clcfpess->sql_query_file(db_anofolha(),db_mesfolha(),db_getsession("DB_instit"),"r11_confer"));
      if($clcfpess->numrows > 0){
        db_fieldsmemory($result_param, 0);
      }

      /**
       * Realiza a proporcionalização do ponto
       */
      $oDataRetorno = null;
      $oCompetencia = DBPessoal::getCompetenciaFolha();
      $oServidor = ServidorRepository::getInstanciaByCodigo($r45_regist, $oCompetencia->getAno(), $oCompetencia->getMes());
      $oProporcionalizacaoPontoSalario = new ProporcionalizacaoPontoSalario($oServidor->getPonto(Ponto::SALARIO), 2, $oDataRetorno);
      $oProporcionalizacaoPontoSalario->processar();
      /*
      for($i=0; $i<$numrows_pontofx; $i++){
        db_fieldsmemory($result_pontofx, $i);
        $valor_ponto = $r90_valor;
        $quant_ponto = $r90_quant;

        if($F019 > 0 && $F019 < 30 && strtolower($r11_confer) == "f"){
          $result_procp = $clrhrubricas->sql_record($clrhrubricas->sql_query_file(null,db_getsession('DB_instit'),"rh27_propq","","rh27_rubric = '".$r90_rubric."' and rh27_calcp = 't'"));
          if($clrhrubricas->numrows > 0){
            db_fieldsmemory($result_procp, 0);
            if($r90_valor > 0){
              $valor_ponto = ($r90_valor / 30) * (30 - $F019);
            }
            if($r90_quant > 0 && $rh27_propq == "t"){
              $quant_ponto = ($r90_quant / 30) * (30 - $F019);
            }
          }
        }
	
        $clpontofs->r10_anousu = db_anofolha();
        $clpontofs->r10_mesusu = db_mesfolha();
        $clpontofs->r10_regist = $r45_regist;
        $clpontofs->r10_rubric = $r90_rubric;
        $clpontofs->r10_valor  = "round($valor_ponto,2)";
        $clpontofs->r10_quant  = "$quant_ponto";
        $clpontofs->r10_lotac  = $r90_lotac;
        $clpontofs->r10_datlim = $r90_datlim;
        $clpontofs->r10_instit = db_getsession("DB_instit");
        $result_pontofs = $clpontofs->sql_record($clpontofs->sql_query_file(db_anofolha(),db_mesfolha(),$r45_regist,$r90_rubric));
        if($clpontofs->numrows > 0){
          $clpontofs->alterar(db_anofolha(),db_mesfolha(),$r45_regist,$r90_rubric);
        }else{
          $clpontofs->incluir(db_anofolha(),db_mesfolha(),$r45_regist,$r90_rubric);
        }
        if($clpontofs->erro_status=="0"){
          break;
        }
      }
      */
    }
  }
  

  db_fim_transacao(!$clafasta->erro_status);
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clafasta->sql_record($clafasta->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include(modification("forms/db_frmafasta.php"));
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($clafasta->erro_status=="0"){
    $clafasta->erro(true,false);
  }else if($clpontofs->erro_status=="0"){
    $clpontofs->erro(true,false);
  }else{
    $clafasta->erro(true,false);
    $db_opcao=33;
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>