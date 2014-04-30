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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_arretipo_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arrecant_classe.php");
include("classes/db_arrehist_classe.php");
include("classes/db_arreold_classe.php");
include("classes/db_divida_classe.php");
include("classes/db_divold_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require_once("model/cancelamentoDebitos.model.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$oCancelaDebito     = new cancelamentoDebitos();
$cliframe_seleciona = new cl_iframe_seleciona;
$clarretipo         = new cl_arretipo;
$clarrecad          = new cl_arrecad;
$clarrecant         = new cl_arrecant;
$clarrehist         = new cl_arrehist;
$clarreold          = new cl_arreold;
$cldivold           = new cl_divold;
$cldivida           = new cl_divida;
$clrotulo           = new rotulocampo;

$clarrecad->rotulo->label();
$clrotulo->label("k00_tipo");
$lErro    = false;
$sMsgErro = "";

if (isset($cancelar)&&isset($chaves)&&$chaves!=""){
	
  $numpre     = "";
  $numpre_ant = "teste";
  $numpar     = "";
  $receita    = "";
  $vir        = "";
  $info       = split('#',$chaves);
  $aDebitos   = array();
  
  db_inicio_transacao();
  
  try {

    for ($w=0; $w < count($info); $w++) {

      $dados   = split('-',$info[$w]);
      $numpre  = $dados[0];
      $numpar  = $dados[1];
      $receita = $dados[2];
      
      // Verifica se existe Pagamento Parcial para o débito selecionado
      $sSqlPgtoParcial = "select fc_verifica_abatimento(1,{$numpre},{$numpar},{$receita}) as pgtoparcial";
      $rsPgtoParcial   = db_query($sSqlPgtoParcial);    
        
      if (! $rsPgtoParcial) {
        
        $sMsgErro = "Erro ao rodar Pgto Parcial.";
        throw new Exception($sMsgErro);
      } else {
        
      	$lPgtoParcial = db_utils::fieldsMemory($rsPgtoParcial,0)->pgtoparcial;
	      if ($lPgtoParcial == 't') {
	      	
	        $sMsgErro = "Pagamento parcial existente para o débito - Numpre : {$numpre} Numpar : {$numpar} Receita : {$receita} ";
	        throw new Exception($sMsgErro);
	      }
      } 
      
      $sWhereDebito   = "     arrecad.k00_numpre    = $numpre  "; 
      $sWhereDebito  .= " and arrecad.k00_numpar    = $numpar  "; 
      $sWhereDebito  .= " and arrecad.k00_receit    = $receita ";
      $sWhereDebito  .= " and arreinstit.k00_instit = ".db_getsession('DB_instit');
        
      $result_arrediv = $clarrecad->sql_record($clarrecad->sql_query_div(null,"distinct arrecad.*,divida.v01_coddiv",null,$sWhereDebito));
      $nurows_arrediv = $clarrecad->numrows;
             
      for( $y=0; $y < $nurows_arrediv; $y++){
          
        db_fieldsmemory($result_arrediv,$y);
          
        $coddiv = $v01_coddiv;
     
        $aDadosDebitos = array();
        $aDadosDebitos['Numpre']  = $numpre;
        $aDadosDebitos['Numpar']  = $numpar;
        $aDadosDebitos['Receita'] = $receita;
  
        $aDebitos[] = $aDadosDebitos;     
      
        $sSqlDivold     = $cldivold->sql_query_old(null,"distinct divold.*",null,"k10_coddiv = $v01_coddiv");
        $result_divold  = $cldivold->sql_record($sSqlDivold);
        $numrows_divold = $cldivold->numrows;
        
        for ($i=0; $i < $numrows_divold; $i++) {
         
          db_fieldsmemory($result_divold,$i); 
         
          $sql_arreold  = "select distinct * ";
          $sql_arreold .= "  from (select distinct on (k00_numpre, k00_numpar, k00_receit) * ";
          $sql_arreold .= "          from arreold ";
          $sql_arreold .= "         where k00_numpre = $k10_numpre ";
          $sql_arreold .= "           and k00_numpar = $k10_numpar ";
          $sql_arreold .= "           and k00_receit = $k10_receita ";
          $sql_arreold .= "      order by k00_numpre, k00_numpar, k00_receit, k00_dtoper desc) as x ";
  
          $result_arreold  = $clarreold->sql_record( $sql_arreold );
          $numrows_arreold = $clarreold->numrows;
         
          for ($z=0; $z < $numrows_arreold; $z++) {
            
            db_fieldsmemory($result_arreold,$z);
            
            $clarrecad->k00_numpre = $k00_numpre;
            $clarrecad->k00_numpar = $k00_numpar;
            $clarrecad->k00_numcgm = $k00_numcgm;
            $clarrecad->k00_dtoper = $k00_dtoper;
            $clarrecad->k00_receit = $k00_receit;
            $clarrecad->k00_hist   = $k00_hist  ;
            $clarrecad->k00_valor  = $k00_valor ;
            $clarrecad->k00_dtvenc = $k00_dtvenc;
            $clarrecad->k00_numtot = $k00_numtot;
            $clarrecad->k00_numdig = $k00_numdig;
            $clarrecad->k00_tipo   = $k00_tipo  ;
            $clarrecad->k00_tipojm = "$k00_tipojm";
            $clarrecad->incluir();
            
            if($clarrecad->erro_status==0){
              throw new Exception($clarrecad->erro_msg);
            }
            
            $clarreold->excluir_where("arreold.k00_numpre=$k00_numpre and 
                                       arreold.k00_numpar=$k00_numpar and 
                                       arreold.k00_receit=$k00_receit and 
                                       arreold.k00_dtoper='$k00_dtoper'");
                                       
            if($clarreold->erro_status==0){
              throw new Exception($clarreold->erro_msg);
            }
          }
            
          $cldivold->excluir(null,"k10_coddiv  = $k10_coddiv and 
                                   k10_numpre  = $k10_numpre and 
                                   k10_numpar  = $k10_numpar and 
                                   k10_receita = $k10_receita");
          
          if ($cldivold->erro_status==0) {
            throw new Exception($cldivold->erro_msg);
          }
        }
      }
    }
     
    if ( count($aDebitos) > 0 ) {
      try {
        $oCancelaDebito->setArreHistTXT("CANCELAMENTO DE IMPORTAÇÃO DE DÍVIDA PARCIAL");
        $oCancelaDebito->setTipoCancelamento(2);
        $oCancelaDebito->setCadAcao(4);
        $oCancelaDebito->geraCancelamento($aDebitos);
      } catch (Exception $eException) {
        throw new Exception($eException->getMessage());
      }        
    }    
    
  } catch (Exception $eException) {
  	
    $lErro    = true;
    $sMsgErro = $eException->getMessage();
  }
  
  db_fim_transacao($lErro);
  
  if ( !$lErro ) {
  	
    db_msgbox("Cancelamento Efetuado!!");
    echo "<script>location.href='div4_cancimportdiv001.php'</script>";
  } else {
    db_msgbox("Cancelamento não efetuado!!\\n".$sMsgErro);
  }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_passainfo(valor){
  document.form1.controle.value=valor;
  document.form1.submit();
}

function js_submit_form(){
  js_gera_chaves();
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<center>
<form name="form1" method="post">

<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
      <? 
         $inner_arrecad = "";
         $inner_tipo = "";
         $inner = "";
         $where = "";
	 $tab = " arretipo ";
         if (isset($z01_numcgm)&&$z01_numcgm!=""){
	   $inner_arrecad = " inner join arrecad    on arrecad.k00_numpre = arrenumcgm.k00_numpre
                    		inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre 
												                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." ";

	   $inner = " inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre ";
	   $where = " and arrenumcgm.k00_numcgm = $z01_numcgm ";
	   $tab = " arrenumcgm  ";
	 }else if (isset($j01_matric)&&$j01_matric!=""){
	   $inner_arrecad = " inner join arrecad on arrecad.k00_numpre = arrematric.k00_numpre
                    		inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre 
												                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." ";
           $inner = " inner join arrematric on arrematric.k00_numpre = arrecad.k00_numpre ";
	   $where = " and arrematric.k00_matric = $j01_matric ";
	   $tab = " arrematric  ";
	 }else if (isset($q02_inscr)&&$q02_inscr!=""){
	   $inner_arrecad = " inner join arrecad    on arrecad.k00_numpre = arreinscr.k00_numpre
                    		inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre 
												                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." ";
     $inner         = " inner join arreinscr on arreinscr.k00_numpre = arrecad.k00_numpre ";
	   $where         = "                     and arreinscr.k00_inscr = $q02_inscr ";
	   $tab           = " arreinscr ";
	 }
  ?>
  <tr>
    <td colspan=2>
    <?
           $campos = " distinct arrecad.k00_numpre,arrecad.k00_numpar,arrecad.k00_receit,k02_descr,arrecad.k00_dtvenc,v01_exerc ";
           $sql_numpres = "select $campos  ";
           $sql_numpres .= "  from $tab ";
           $sql_numpres .= "  			$inner_arrecad ";
           $sql_numpres .= "        inner join arretipo  on arretipo.k00_tipo = arrecad.k00_tipo  ";
           $sql_numpres .= "        inner join cadtipo   on cadtipo.k03_tipo  = arretipo.k03_tipo ";
           $sql_numpres .= "  			inner join tabrec    on tabrec.k02_codigo = arrecad.k00_receit ";
           $sql_numpres .= "  			inner join divida    on v01_numpre        = arrecad.k00_numpre ";
           $sql_numpres .= "  		                      and v01_numpar        = arrecad.k00_numpar ";
					 $sql_numpres .= "  		                      and v01_instit        = ".db_getsession('DB_instit');
           $sql_numpres .= "  			left  join issvardiv on q19_coddiv        = divida.v01_coddiv ";
           $sql_numpres .= "  			inner join divold    on k10_coddiv        = v01_coddiv ";
           $sql_numpres .= " where arretipo.k03_tipo = 5 and q19_coddiv is null $where ";
           $sql_numpres .= "order by arrecad.k00_numpre, ";
           $sql_numpres .= "         arrecad.k00_numpar";

           $cliframe_seleciona->sql           = $sql_numpres;
           $cliframe_seleciona->campos        = "k00_numpre,k00_numpar,k00_receit,k02_descr,k00_dtvenc,v01_exerc";
           $cliframe_seleciona->legenda       = "Numpre's";
           // $cliframe_seleciona->sql_marca=$sql_marca;
           $cliframe_seleciona->iframe_height = "400";
           $cliframe_seleciona->iframe_width  = "550";
           $cliframe_seleciona->iframe_nome   = "numpres"; 
           $cliframe_seleciona->chaves        = "k00_numpre,k00_numpar,k00_receit";
           $cliframe_seleciona->iframe_seleciona(1);    
	   
    
    ?>
    </td>
  </tr>
  <tr height="20px">
  <td ></td>
  <td ></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="cancelar" type="submit"  value="Cancelar" onclick="js_submit_form();">
  </td>
  </tr>
  </table>
  <?
    db_input('z01_numcgm',10,'',true,'hidden',3);
    db_input('j01_matric',10,'',true,'hidden',3);
    db_input('q02_inscr' ,10,'',true,'hidden',3);
    db_input('inner'     ,10,'',true,'hidden',3);
    db_input('where'     ,10,'',true,'hidden',3);
  ?>
  </form>
</center>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_mandadados(tipor,tipdes,inner,where){
    js_OpenJanelaIframe('top.corpo','db_iframe','div4_importadivida033.php?k00_tipo_or='+tipor+'&k00_tipo_des='+tipdes+'&txt_where='+where+'&txt_inner='+inner,'Pesquisa',true);
    jan.moveTo(0,0);
}
</script>