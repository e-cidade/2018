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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_utils.php");
require_once ("classes/db_issvar_classe.php");
require_once ("classes/db_issvarnotas_classe.php");
require_once ("classes/db_issvarlev_classe.php");
require_once ("classes/db_levanta_classe.php");
require_once ("classes/db_levusu_classe.php");
require_once ("classes/db_levvalor_classe.php");
require_once ("classes/db_levinscr_classe.php");
require_once ("classes/db_numpref_classe.php");
require_once ("classes/db_cadvenc_classe.php");
require_once ("classes/db_parissqn_classe.php");
require_once ("classes/db_db_confplan_classe.php");
require_once ("classes/db_arrecad_classe.php");
require_once ("classes/db_arreinscr_classe.php");
require_once ("classes/db_arrenumcgm_classe.php");
require_once ("classes/db_issvarlevold_classe.php");
require_once ("classes/db_parfiscal_classe.php");

$cllevanta  = new cl_levanta;
$cllevvalor  = new cl_levvalor;
$clissvarlevold  = new cl_issvarlevold;
$cllevinscr  = new cl_levinscr;
$cllevusu  = new cl_levusu;
$clissvar   = new cl_issvar;
$clissvarlev   = new cl_issvarlev;
$clnumpref  = new cl_numpref;
$clcadvenc  = new cl_cadvenc;
$clparissqn = new cl_parissqn;
$cldb_confplan = new cl_db_confplan;
$clarrecad = new cl_arrecad;
$clarreinscr = new cl_arreinscr;
$clarrenumcgm = new cl_arrenumcgm;
$clparfiscal = new cl_parfiscal;
$oDaoInformacaoDebito = db_utils::getDao('informacaodebito');

$clrotulo = new rotulocampo;
$clrotulo->label("y60_codlev");
$clrotulo->label("z01_nome");
$db_opcao = 1;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if ( isset($importar) ) {
  $result_fiscais=$cllevusu->sql_record($cllevusu->sql_query_file($y60_codlev));
  if ( $cllevusu->numrows!=0 ) {
    $passou=true;  
    $sqlerro=false;
    db_inicio_transacao();
    $result = $cllevanta->sql_record($cllevanta->sql_query_file($y60_codlev)); 
    db_fieldsmemory($result,0);
    
    //--------------------------------------------------
    //Verifica se já foi  importado....
    if($y60_importado == 't'){
      $erro_msg = "Levantamento já exportado!";
      $sqlerro = true;
    }else{
      $cllevanta->y60_importado = 'true';
      $cllevanta->alterar($y60_codlev);
      $erro_msg= $cllevanta->erro_msg;  
      if($cllevanta->erro_status==0){
        $sqlerro=true;
      }
    } 
    //--------------------------------------------------
    
    ///------------**********************************************************------------------------
    //rotina que monta o array com o perido de exclusao...
    if ($sqlerro == false){
      $result = $cllevvalor->sql_record($cllevvalor->sql_query_file(null,"y63_ano as max_ano,y63_mes as max_mes","y63_ano desc,y63_mes desc ","y63_codlev=$y60_codlev"));   
	  if ($cllevvalor->numrows > 0) {
        $result = $cllevvalor->sql_record($cllevvalor->sql_query_file(null,"y63_ano as max_ano,y63_mes as max_mes","y63_ano desc,y63_mes desc ","y63_codlev=$y60_codlev"));   
        db_fieldsmemory($result,0);
		
        $result = $cllevvalor->sql_record($cllevvalor->sql_query_file(null,"y63_ano as min_ano,y63_mes as min_mes","y63_ano asc,y63_mes asc ","y63_codlev=$y60_codlev"));   
        db_fieldsmemory($result,0);
		
//        $result = $cllevvalor->sql_record($cllevvalor->sql_query_file(null,"y63_ano as min_ano,y63_mes as min_mes","y63_ano ,y63_mes "));   
//        db_fieldsmemory($result,0);
      
      
        $arr = array();
        $cont = 0;
        while(1==1){
          $arr[$cont][0] =  $min_ano; 
          $arr[$cont][1] =  $min_mes; 
          $cont++;
          $min_mes++;
         if($min_mes>12){
            $min_mes =1;
            if($min_ano != $max_ano){
              $min_ano++; 
            }  
          }
          if($min_ano == $max_ano){
            if($min_mes == $max_mes){
              $arr[$cont][0] =  $min_ano; 
              $arr[$cont][1] =  $min_mes; 
              break;
            }
          }
        }

      }else{
		
		 db_msgbox('Levantamento sem valores. \\nVerifique.');
         $sqlerro=true;
		 $passou = false;
         
      }
	}
    ///------------**********************************************************------------------------
    
    
    //--------------------+++++++++++++++++++++++++++++++++++++++++++++++++++++--------------------------------
    //ROTINA QUE EXCLUI DOS ISSVAR E ARRECAD
    if($sqlerro == false){
      /*
      $result22 = $cllevinscr->sql_record($cllevinscr->sql_query_file($y60_codlev)); 
      db_fieldsmemory($result22,0);
      
      $result11  = $clissvar->sql_record($clissvar->sql_query_arrecad(null,"arrecad.k00_numpre,k00_numpar,q05_codigo,q05_ano,q05_mes","","k00_inscr=$y62_inscr"));  
      $numrows11 = $clissvar->numrows;
      */
      $result_levinfo=$cllevanta->sql_record($cllevanta->sql_query_inf($y60_codlev));
      db_fieldsmemory($result_levinfo,0);
      if (isset($y62_inscr) && $y62_inscr!=""){
        $tab="arreinscr";
        $where = "arreinscr.k00_inscr=$y62_inscr";
      }else if (isset($y93_numcgm)&&$y93_numcgm!=""){
        $tab="arrenumcgm";
        $where = "arrenumcgm.k00_numcgm=$y93_numcgm";
      }
      $sql11="select arrecad.k00_numpre,k00_numpar,q05_codigo,q05_ano,q05_mes 
                from issvar 
                     left  join issvarlev on q18_codigo              = q05_codigo
                     left  join issarqsimplesregissvar on q68_issvar = q05_codigo
                     inner join $tab      on $tab.k00_numpre         = q05_numpre 
                     inner join arrecad   on $tab.k00_numpre         = arrecad.k00_numpre 
                                         and arrecad.k00_numpar      = q05_numpar
                     left  join issplannumpre          on q32_numpre = q05_numpre 
               where $where 
                 and q18_codigo is null
                 and q32_numpre is null
                 and q68_issvar is null";

      $result11=pg_exec($sql11);
      $numrows11 = pg_numrows($result11);
      
      for($x=0; $x<$numrows11; $x++){
        db_fieldsmemory($result11,$x,true);
        $exclui = false; 
        for($q=0; $q<count($arr); $q++ ){
          if($q05_ano==$arr[$q][0] &&$q05_mes==$arr[$q][1]  ){
            $exclui = true;
            break;
          }
        }
        if($exclui==false){
          continue;
        }  
        if($sqlerro == false){
          // echo "numpre=$k00_numpre======numpar $k00_numpar";
          
          $clarrecad->excluir_arrecad($k00_numpre,$k00_numpar);
          if($clarrecad->erro_status==0){
            $erro_msg = $clarrecad->erro_msg;
            $sqlerro  = true;
            break;
          }
        }
        if($sqlerro == false){
          $clissvarlevold->y85_codissvar = $q05_codigo;
          $clissvarlevold->y85_codlev = $y60_codlev;
          $clissvarlevold->incluir();
          if($clissvarlevold->erro_status==0){
            $erro_msg = $clissvarlevold->erro_msg;
            $sqlerro  = true;
            break;
          }
        }
        if($sqlerro == false){
          $clissvarlev->sql_record($clissvarlev->sql_query_file($q05_codigo,$y60_codlev));
          if($clissvarlev->numrows>0){
            
            $clissvarlev->q18_codigo = $q05_codigo;
            $clissvarlev->q18_codlev = $y60_codlev;
            $clissvarlev->excluir($q05_codigo,$y60_codlev);
            if($clissvarlev->erro_status==0){
              $erro_msg = $clissvarlev->erro_msg;
              $sqlerro  = true;
              break;
            }
          }
        }
        
        if($sqlerro == false){
          $clissvar->excluir_issvar($q05_codigo,$y60_codlev);
          if($clissvar->erro_status==0){
            $erro_msg = $clissvar->erro_msg;
            $sqlerro  = true;
            break;
          }
        }	 
        
        
      }
    }
    //--FINAL------------------+++++++++++++++++++++++++++++++++++++++++++++++++++++--------------------------------
    
    
    
    
    
    if($sqlerro == false){ 
     
      $sql=$cllevvalor->sql_query_inf(null,"sum(y63_saldo) as y63_saldo,
                                            y62_inscr,
                                            y93_numcgm,
                                            y63_mes,
                                            y63_ano,
                                            y63_aliquota,
                                            y63_sequencia,
                                            x.z01_numcgm,
                                            y63_dtvenc",
                                            "",
                                            "y63_codlev = $y60_codlev and y63_saldo > 0 
                                             group by y63_ano,y63_mes,y63_aliquota,y62_inscr,y93_numcgm,y63_sequencia,x.z01_numcgm,y63_dtvenc 
                                             order by y63_dtvenc");
      $resultado  = $cllevanta->sql_record($sql);
      $numrows = $cllevanta->numrows;
    }
    if($numrows == 0){
      $erro_msg = "Valores não disponíveis para este levantamento. Importação cancelada.";
      $sqlerro= true;
    }
    
    $parc =0;
    $numpre=$clnumpref->sql_numpre();
    for($i=0; $i<$numrows; $i++){
      if($sqlerro == true){
        break;
      }
      db_fieldsmemory($resultado,$i);  
      $parc++;
      if($sqlerro == false){
        $clissvar->q05_numpre=$numpre;
        $clissvar->q05_numpar=$parc;
        $clissvar->q05_valor=$y63_saldo;
        $clissvar->q05_ano=$y63_ano;
        $clissvar->q05_mes=$y63_mes;
        $clissvar->q05_histor="Levantamento fiscal...";
        $clissvar->q05_aliq=$y63_aliquota;;
        
        $bruto = ($y63_saldo/$y63_aliquota)*100;
        $clissvar->q05_bruto="$bruto";
        $clissvar->q05_vlrinf="$bruto";
        $clissvar->incluir(null);
        $erro_msg = $clissvar->erro_msg;
        if($clissvar->erro_status==0){
          $sqlerro=true;
        }
        $codigo=$clissvar->q05_codigo;
        
      }  	
      ///***FINAL DA INCLUSÃO NO AR********************///**************************************//*********// 
      //***************INCLUI NA TABELA ISSVARLEV
      
      if($sqlerro==false){
        $clissvarlev->y60_codigo = $codigo;
        $clissvarlev->y60_codlev = $y60_codlev;
        $clissvarlev->incluir($codigo,$y60_codlev);  
        $erro_msg = $clissvarlev->erro_msg;
        if($clissvarlev->erro_status==0){
          $sqlerro=true;
        }
      }
      
      if ($sqlerro==false) {
      	
      	/**
				 * Rotina criada para guardar data do débito de issqn variável igual a data de realização do levantamento fiscal
				 * Para essa função tambem existe uma trigger na tabela issvar, que a cada operação atualiza a data do débito com a data atual do sistema
				 * Essa parte foi criada para atualizar os dados
      	 */
      	
      	$sWhere                = "k163_numpre = {$numpre} and k163_numpar = {$parc}";
      	$sSqlInformacaoDebito  = $oDaoInformacaoDebito->sql_query_file(null, "*", null, $sWhere);
      	$rsInformacaoDebito    = $oDaoInformacaoDebito->sql_record($sSqlInformacaoDebito);
      	
      	if ($oDaoInformacaoDebito->numrows > 0) {

      		$oInformacaoDebito = db_utils::fieldsMemory($rsInformacaoDebito, 0);
      		
      		$oDaoInformacaoDebito->k163_sequencial = $oInformacaoDebito->k163_sequencial;
      		$oDaoInformacaoDebito->k163_data       = $y60_data != '' ? $y60_data : date('Y-m-d', db_getsession('DB_datausu'));
      		$oDaoInformacaoDebito->alterar($oInformacaoDebito->k163_sequencial);
      		
      	} else {
      	
					$oDaoInformacaoDebito->k163_numpre = $numpre;
					$oDaoInformacaoDebito->k163_numpar = $parc;
					$oDaoInformacaoDebito->k163_data   = $y60_data != '' ? $y60_data : date('Y-m-d', db_getsession('DB_datausu'));
					$oDaoInformacaoDebito->incluir(null);
				
      	}
      		
				if ($oDaoInformacaoDebito->erro_status == '0') {
					$sqlerro = true;
				}
				 
      }
      //***************************************
      
      //****inclui no arreinscr************************************///
      if(!$sqlerro && isset($y62_inscr) && $y62_inscr!=""){
        $clarreinscr->sql_record($clarreinscr->sql_query_file($numpre,$y62_inscr));
        if($clarreinscr->numrows==0){
          $clarreinscr->k00_numpre=$numpre;  
          $clarreinscr->k00_inscr=$y62_inscr;  
          $clarreinscr->k00_perc=100;  
          $clarreinscr->incluir($numpre,$y62_inscr);
          //  $clarreinscr->erro(true,false);
          if($clarreinscr->erro_status==0){
            $erro_msg=$clarreinscr->erro_msg;
            $sqlerro=true;
          }
        }
      } 
      if(!$sqlerro && isset($y93_numcgm) && $y93_numcgm!=""){
        $clarrenumcgm->sql_record($clarrenumcgm->sql_query_file($y93_numcgm,$numpre));
        if($clarrenumcgm->numrows==0){
          $clarrenumcgm->k00_numpre=$numpre;  
          $clarrenumcgm->k00_numcgm=$y93_numcgm;  
          $clarrenumcgm->incluir($y93_numcgm,$numpre);
          //  $clarreinscr->erro(true,false);
          if($clarrenumcgm->erro_status==0){
            $sqlerro=true;
            $erro_msg=$clarrenumcgm->erro_msg;
          }
        }
      } 
      //********************************************************///
      
      //////////////INCLUSÃO NO ARRECAD/***************************************////////////////////////**********///////
      if(!$sqlerro){
        
        $result66=$clparissqn->sql_record($clparissqn->sql_query_file());
        db_fieldsmemory($result66,0);
        $clarrecad->k00_tipo=$q60_tipo; 
        //$clarrecad->k00_receit=$q60_receit;
        
        $result_parfiscal=$clparfiscal->sql_record($clparfiscal->sql_query_file());
        db_fieldsmemory($result_parfiscal,0);
        if ($y60_espontaneo=='f'){
          $clarrecad->k00_receit=$y32_receit;
        }else{
          $clarrecad->k00_receit=$y32_receitexp;
        }
        
        $result77=$clcadvenc->sql_record($clcadvenc->sql_query_file($q60_codvencvar,$y63_mes,"q82_venc,q82_hist"));
        db_fieldsmemory($result77,0);
        $clarrecad->k00_hist=$q82_hist; 
        if($y63_ano == db_getsession("DB_anousu")){
          //	   $clarrecad->k00_dtvenc="$q82_venc";
          $clarrecad->k00_dtvenc="$y63_dtvenc";
        }else{
          $res = $cldb_confplan->sql_record($cldb_confplan->sql_query());
          if($cldb_confplan->numrows > 0){
            db_fieldsmemory($res,0);
          }else{
            db_msgbox("Tabela db_confplan vazia!");
            db_redireciona("iss1_issvar014.php");
            exit;
          }
          $qmes = $y63_mes;
          $qano = $y63_ano;
          $qmes += 1;
          if($qmes > 12){
            $qmes = 1;
            $qano += 1;
          }
          $clarrecad->k00_dtvenc="$y63_dtvenc";
        }
        
        $arr = split  ("-",$clarrecad->k00_dtvenc); 
        
        
        $clarrecad->k00_numcgm=$z01_numcgm; 
        $clarrecad->k00_dtoper= $arr[0]."-".$arr[1]."-01"; 
        $clarrecad->k00_valor=$y63_saldo; 
        $clarrecad->k00_numpre=$numpre; 
        $clarrecad->k00_numtot=1; 
        $clarrecad->k00_numpar=$parc; 
        $clarrecad->k00_numdig='0'; 
        $clarrecad->k00_tipojm='0'; 
        $clarrecad->incluir();
        //$clarrecad->erro(true,false);
        $erro_msg = $clarrecad->erro_msg;
        if($clarrecad->erro_status==0){
          $sqlerro=true;
        }
      }        
    }
    
    db_fim_transacao($sqlerro);
  }else{
    db_msgbox("Não existem fiscais cadastrados para o levantamento!!Exportação Cancelada!!");
    $passou=false;
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
<script>
function  js_importa(){
  if(document.form1.y60_codlev.value == ''){
    alert("Informe o levantamento");
    return false;
  }
  return true;
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr> 
<td width="360">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td height="430" align="left" valign="top" bgcolor="#CCCCCC">
<center>
<form name="form1" method="post" action="">
<table border="0" cellspacing="0" cellpadding="0">

<tr>
<br>
<td nowrap title="<?=@$Ty60_codlev?>">
<?
db_ancora(@$Ly60_codlev,"js_lev(true);",$db_opcao);
?>
</td>	
<td>	
<?
db_input('y60_codlev',6,$Iy60_codlev,true,'text',$db_opcao," onchange='js_lev(false);'");
db_input('z01_nome',40,$Iz01_nome,true,'text',3);
?>
</td>
</tr>
<tr>
<td colspan="2" align="center"  height="25" nowrap>
<input name="importar" type="submit"  onClick="return js_importa();" value="Exportar levantamento">
</td>
<td>
</tr>
</table>
</form>
</center>
</td>
</tr>
</table>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_lev(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_levanta02.php?funcao_js=parent.js_mostralev1|y60_codlev|DBtxtnome_origem','Pesquisa',true);
  }else{
    lev = document.form1.y60_codlev.value;
    if(lev != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe','func_levanta02.php?pesquisa_chave='+lev+'&funcao_js=parent.js_mostralev','Pesquisa',false);
    }else{
      document.form1.z01_nome.value='';
    }     
  }
}
function js_mostralev(chave,erro){
  if(erro==true){ 
    alert('Levantamento não encontrado para exportação.');  
    document.form1.y60_codlev.value=""; 
    document.form1.y60_codev.focus(); 
  } else{
    document.form1.z01_nome.value = chave;
  } 
}
function js_mostralev1(chave1,chave2){
  document.form1.y60_codlev.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
</script>
<?
if(isset($importar)&&$passou==true){
  db_msgbox($erro_msg);
	echo "<script>location.href='fis4_importalevan001.php';</script>";
}  
?>