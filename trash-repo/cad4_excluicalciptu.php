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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_iptunump_classe.php");
require_once("classes/db_arrecant_classe.php");
require_once("classes/db_iptunumpold_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("model/cancelamentoDebitos.model.php");
require_once("libs/db_sql.php");


db_postmemory($HTTP_POST_VARS);
$oCancelaDebito = new cancelamentoDebitos();
$cliptubase     = new cl_iptubase;
$cliptunump     = new cl_iptunump;
$clarrecant     = new cl_arrecant;
$cliptunumpold  = new cl_iptunumpold;

$cliptubase->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('z01_numcgm');
$erro          = false;
$descricaoerro = "";

if (isset($excluircalculo)) {

  if (isset($HTTP_POST_VARS['j01_matric'])) {

    $result = $cliptunump->sql_record($cliptunump->sql_query($anousu,$j01_matric));
   
    if ($cliptunump->numrows > 0) {
   	
     	db_fieldsmemory($result,0);
  
      //Verifica a situal��o do caldulo de IPTU e se poder� ser exclu�do
      
      $sSql = "select k00_numpre,
                      k10_numpre,
                      count(arrecant) as arrecant,
                      count(arrepaga) as arrepaga,
                      ( select 1
                          from abatimentoarreckey
                               inner join abatimento on abatimento.k125_sequencial = abatimentoarreckey.k128_abatimento
                               inner join arreckey   on arreckey.k00_sequencial    = abatimentoarreckey.k128_arreckey
                         where abatimento.k125_tipoabatimento = 1
                           and arreckey.k00_numpre            = x.k00_numpre
                        limit 1 
                      ) as abatimento
                 from ( select distinct 
                               arrecad.k00_numpre,
                               divold.k10_numpre,
                               arrecant.k00_numpar as arrecant,
                               arrepaga.k00_numpar as arrepaga
                          from iptunump 
  		                         left join arrecad  on arrecad.k00_numpre  = iptunump.j20_numpre
  		                         left join arrecant on arrecant.k00_numpre = iptunump.j20_numpre
  		                         left join arrepaga on arrepaga.k00_numpre = iptunump.j20_numpre
  		                         left join divold   on divold.k10_numpre   = iptunump.j20_numpre
                         where iptunump.j20_matric = $j01_matric 
                           and iptunump.j20_numpre = $j20_numpre ) as x 
                group by k00_numpre,
                         k10_numpre";
  
  		$resultarre = $clarrecant->sql_record($sSql);
  		
   		db_fieldsmemory($resultarre,0);
   		
   		if ($k10_numpre != "") {
  
   			$descricaoerro = pg_result(pg_query("select fc_iptu_geterro(32,'')"),0,0);
   			$erro          = true;
  
   		} else if ( $arrepaga > 0 ) {
   			
   			$descricaoerro = pg_result(pg_query("select fc_iptu_geterro(33,'')"),0,0);
        $erro          = true;
         			
   		} else if ( $arrecant > 0){
   			
   			$descricaoerro = pg_result(pg_query("select  fc_iptu_geterro(34,'')"),0,0);
        $erro          = true;
        
      } else if ( isset($abatimento) && trim($abatimento) != '' ){
        
        $descricaoerro = 'Opera��o Cancelada, D�bito com Pagamento Parcial!';
        $erro          = true;
   		}
  
  		if($erro == false ) {
  
        $sql = pg_query("BEGIN");
  
        
        $sSqlDadosDebito = " select k00_numpre, 
                                    k00_numpar, 
                                    k00_receit 
                               from arrecad 
                              where k00_numpre = $j20_numpre ";
        
        $rsDadosDebito   = db_query($sSqlDadosDebito);                            
        $aDadosDebito    = db_utils::getColectionByRecord($rsDadosDebito);                            
        
        $aDebitos = array();
        
        foreach ($aDadosDebito as $oDadosDebito) {
        	
  		    $aDadosDebitos = array();  
  		    $aDadosDebitos['Numpre']  = $oDadosDebito->k00_numpre;
  		    $aDadosDebitos['Numpar']  = $oDadosDebito->k00_numpar;
  		    $aDadosDebitos['Receita'] = $oDadosDebito->k00_receit;
  		    
  		    // inserir na iptunumpold
  		    $cliptunumpold->j130_anousu = db_getsession("DB_anousu");
  		    $cliptunumpold->j130_matric = $j01_matric;
  		    $cliptunumpold->j130_numpre = $oDadosDebito->k00_numpre;
  		    $cliptunumpold->incluir();
  		    
          if($cliptunumpold->erro_status==0){
            $sqlerro = true;
            db_msgbox($cliptunumpold->erro_msg);
          }		    
          
  		    $aDebitos[] = $aDadosDebitos;     
        }
  
        if ( count($aDebitos) > 0 ) {
        	
  		    try {
  		    	
  		      $oCancelaDebito->setArreHistTXT("EXCLUS�O DE C�LCULO PARCIAL");
  		      $oCancelaDebito->setTipoCancelamento(2);
  		      $oCancelaDebito->setCadAcao(3);
  		      $oCancelaDebito->geraCancelamento($aDebitos);
  		               
  		    } catch (Exception $eException) {
  		    	
  	        $cliptubase->erro_msg    = $eException->getMessage();
  	        $cliptubase->erro_status = '0';	    	
  		    }       
        }
  	    
  	    if ( $cliptubase->erro_status != '0' ) {
  	    	
  		    if (!pg_query("delete from iptunump where j20_numpre = $j20_numpre") ) {
  		    	
  	  		  $cliptubase->erro_msg = 'Erro ao Excluir iptunump.';
  		      $cliptubase->erro_status = '0';
  		      
  		   	} else {
  		        
  				  if (!pg_query("delete from iptucalv where j21_anousu = $anousu and j21_matric = $j01_matric ")) {
  				  	
  	  		    $cliptubase->erro_msg = 'Erro ao Excluir iptucalv.';
  		        $cliptubase->erro_status = '0';
  			    } else {
  			      	
  		        if (!pg_query("delete from iptucale where j22_anousu = $anousu and j22_matric = $j01_matric ")) {
  		        	
  	  		      $cliptubase->erro_msg = 'Erro ao Excluir iptucalv.';
  		          $cliptubase->erro_status = '0';
  			      } else {
  			        	
  		          if (!pg_query("delete from iptucalc where j23_anousu = $anousu and j23_matric = $j01_matric ")) {
  		          	
  	  		        $cliptubase->erro_msg = 'Erro ao Excluir iptucalc.';
  		            $cliptubase->erro_status = '0';
  			        }
  				    }
  				  }
  				}
  		  }
  		  if ($cliptubase->erro_status != '0') {
  		  	
    		  $sql = pg_query("COMMIT");
          $cliptubase->erro_msg    = 'C�lculo Excluido com Sucesso.';
  	      $cliptubase->erro_status = '0';
  		  } else {
     		  $sql = pg_query("ROLLBACK");
  	    }	    
  		}  
	  } else {
      $cliptubase->erro_msg    = 'Matricula n�o calculada.';
	    $cliptubase->erro_status = '0';
	  }
  } else {
    $cliptubase->erro_msg    = 'Matricula n�o informada.';
    $cliptubase->erro_status = '0';
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
function  js_verificacalculo(){
   if(document.form1.j01_matric.value == ""){
     alert('Informe uma Matr�cula.');
	 return false;
   }
   return true;
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.j01_matric.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <form name="form1" action="" method="post" onSubmit="return js_verificacalculo();">
	    <table width="292" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="27" height="25" title="<?=$Tz01_nunmcgm?>"> 
              <?
				db_ancora('<strong>Matricula:</strong>','js_mostranomes(true);',4)
				?>
            </td>
            <td width="265" height="25"> 
              <?
				db_input("j01_matric",8,$Ij01_matric,true,'text',4," onchange='js_mostranomes(false);' ")
				?>
            </td>
          </tr>
          <tr>
            <td height="25">
              <?
				db_ancora('<strong>Nome:</strong>','js_mostranomes(true);',4)
				?>
            </td>
            <td height="25">
              <?
				db_input("z01_nome",40,$Iz01_nome,true,'text',3)
				?>
            </td>
          </tr>

          <tr>
            <td height="25">
				<strong>Ano:</strong>
            </td>
            <td height="25">
              <?
	      $result=pg_query("select distinct j18_anousu from cfiptu order by j18_anousu desc");
	      if(pg_numrows($result) > 0){
		?>
		<select name="anousu">
		<?
  	        for($i=0;$i<pg_numrows($result);$i++){
		db_fieldsmemory($result,$i);
	        ?>
	        <option value='<?=$j18_anousu?>'><?=$j18_anousu?></option>
	        <?
	        }
		?>
		</select>
		<?
	      }
	        ?>
            </td>
          </tr>


	  
          <tr> 
            <td height="25">&nbsp;</td>
            <td height="25">
			    <input name="excluircalculo"  type="submit" id="excluircalculo" value="Excluir C&aacute;lculo">
            </td>
          </tr>
        </table>
      </form>
     </td>
  </tr>
</table>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_mostranomes(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_preenche1|j01_matric|z01_nome';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_preenche';	
  }
}
 function js_preenche(chave){
   document.form1.z01_nome.value = chave;
   func_nome.hide();
 }

function js_preenche1(chave1, chave2) {
	document.form1.j01_matric.value = chave1;
	document.form1.z01_nome.value   = chave2; 	
  func_nome.hide();
}

</script>
<?
$func_nome = new janela('func_nome','');
$func_nome ->posX=1;
$func_nome ->posY=20;
$func_nome ->largura=770;
$func_nome ->altura=430;
$func_nome ->titulo="Pesquisa";
$func_nome ->iniciarVisivel = false;
$func_nome ->mostrar();

$cliptubase->erro(true,false);

if(isset($calcular)){
//echo  'func_nome.jan.location.href = "cad3_conscadastro_002_detalhes.php?solicitacao=Calculo&parametro="'.$HTTP_POST_VARS['j01_matric'];

?>
<script>
  func_nome.jan.location.href = "cad3_conscadastro_002_detalhes.php?solicitacao=Calculo&parametro=<?=$HTTP_POST_VARS['j01_matric']?>";
  func_nome.mostraMsg();
  func_nome.show();
  func_nome.focus();
</script>
<?
}
if($erro==true){
  echo "<script>alert('$descricaoerro');</script>";
}else{
  echo "<script>document.form1.j01_matric.value = '';document.form1.z01_nome.value = '';</script>";  
}
?>