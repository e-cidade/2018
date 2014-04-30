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
include("dbforms/db_funcoes.php");
include("classes/db_rhregime_classe.php");
include("dbforms/db_classesgenericas.php");
$gform       = new cl_formulario_rel_pes;
$clrhregime  = new cl_rhregime;
$rotulocampo = new rotulocampo;
$rotulocampo->label("DBtxt23");
$rotulocampo->label("DBtxt25");
db_postmemory($HTTP_POST_VARS);
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

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

  <div class="container" style="width:690px !important;">
  
	<form name="form1" method="post" action="">

  <fieldset>
  
    <Legend><strong>Servidores por Cargo/Lotação/Secretaria</strong></Legend>
    
	
	<table class="form-container">
	  <tr>
	    <td nowrap colspan="2">
			<?
			  if(!isset($tipo)){
			    $tipo = "l";
			  }
			  if(!isset($filtro)){
			    $filtro = "i";
			  }
			  
			  $gform->tipores = true;
 			  $gform->usalota = true;           		// PERMITIR SELEÇÃO DE LOTAÇÕES
 			  $gform->usaLotaFieldsetClass = true;  // PERMITIR SELEÇÃO DE LOTAÇÕES
 			  $gform->usaorga = true;               // PERMITIR SELEÇÃO DE ÓRGÃO
 			  $gform->usacarg = true;               // PERMITIR SELEÇÃO DE Cargo
				//$gform->mostaln = true;             // Removido campo tipo de ordem e carregado manualmente 
			                                        
 			  $gform->masnome = "ordem";            
		                                          
 			  $gform->ca1nome = "cargoi";           // NOME DO CAMPO DO CARGO INICIAL
 			  $gform->ca2nome = "cargof";           // NOME DO CAMPO DO CARGO FINAL
 			  $gform->ca3nome = "selcargo";         
 			  $gform->ca4nome = "Cargo";            
		                                          
 			  $gform->lo1nome = "lotaci";           // NOME DO CAMPO DA LOTAÇÃO INICIAL
 			  $gform->lo2nome = "lotacf";           // NOME DO CAMPO DA LOTAÇÃO FINAL
 			  $gform->lo3nome = "sellot";           
		                                          
 			  $gform->or1nome = "orgaoi";           // NOME DO CAMPO DO ÓRGÃO INICIAL
 			  $gform->or2nome = "orgaof";           // NOME DO CAMPO DO ÓRGÃO FINAL
 			  $gform->or3nome = "selorg";           // NOME DO CAMPO DE SELEÇÃO DE ÓRGÃOS
 			  $gform->or4nome = "Secretaria";       // NOME DO CAMPO DE SELEÇÃO DE ÓRGÃOS
		                                          
 			  $gform->trenome = "tipo";             // NOME DO CAMPO TIPO DE RESUMO
 			  $gform->tfinome = "filtro";           // NOME DO CAMPO TIPO DE FILTRO
		                                          
 			  $gform->resumopadrao = "l";           // TIPO DE RESUMO PADRÃO
 			  $gform->filtropadrao = "i";           
 			  $gform->strngtipores = "loc";         // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
		                                          
 			  $gform->selecao = true;               
 			  $gform->onchpad = true;               // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
		
 			  $gform->manomes = true;
			  $gform->gera_form( db_anofolha(), db_mesfolha() );
			  ?>
			  <tr>
			    <td colspan="2">&nbsp;</td>
			  </tr>
	</table>
	
	<fieldset class="separator">
	
   <Legend><strong>Selecione os Vinculos</strong></Legend>
   
	  <table border="0" >
	  
	   <tr>
	     <td>
	  		      <?
	  		      db_input("valor", 3, 0, true, 'hidden', 3);
	  		      db_input("colunas_sselecionados", 3, 0, true, 'hidden', 3);
	  		      db_input("colunas_nselecionados", 3, 0, true, 'hidden', 3);
	  		       if(!isset($result_regime)){
	  		          $result_regime = $clrhregime->sql_record($clrhregime->sql_query_file(null, "rh30_codreg, rh30_codreg||'-'||rh30_descr as rh30_descr", "rh30_codreg" , " rh30_instit = ".db_getsession('DB_instit') ));
	  		          for($x=0; $x<$clrhregime->numrows; $x++){
	  		               db_fieldsmemory($result_regime,$x);
	  		               $arr_colunas[$rh30_codreg]= $rh30_descr;
	  		          }
	  		        }
	  		        $arr_colunas_final   = Array();
	  		        $arr_colunas_inicial = Array();
	  		        if(isset($colunas_sselecionados) && $colunas_sselecionados != ""){
	  		           $colunas_sselecionados = split(",",$colunas_sselecionados);
	  		           for($Ic=0;$Ic < count($colunas_sselecionados);$Ic++){
	  		              $arr_colunas_final[$colunas_sselecionados[$Ic]] = $arr_colunas[$colunas_sselecionados[$Ic]]; 
	  		           }
	  		        }
	  		        if(isset($colunas_nselecionados) && $colunas_nselecionados != ""){
	  		           $colunas_nselecionados = split(",",$colunas_nselecionados);
	  		           for($Ic=0;$Ic < count($colunas_nselecionados);$Ic++){
	  		              $arr_colunas_inicial[$colunas_nselecionados[$Ic]] = $arr_colunas[$colunas_nselecionados[$Ic]]; 
	  		           }
	  		        }
	  		        if(!isset($colunas_sselecionados) || !isset($colunas_sselecionados) || $colunas_sselecionados == ""){
	  		           $arr_colunas_final  = Array();
	  		           $arr_colunas_inicial = $arr_colunas;
	  		        }
	  		       db_multiploselect("rh30_codreg","rh30_descr", "nselecionados", "sselecionados", $arr_colunas_inicial, $arr_colunas_final, 6, 250, "", "", true, "js_complementar('c');");
	  		       ?>
       </td>
      </tr>
	  </table>
  </fieldset>
	
	<fieldset class="separator">
	
   <Legend><strong>Opções de Impressão</strong></Legend>
   
    <table border="0" class="form-container">
    
      <tr>
	     <td style="width: 173px !important;"><strong>Listar Servidores :</strong>&nbsp;
       </td>
       <td>
         <?
           $x = array("f"=>"NÃO","t"=>"SIM");
           db_select('func',$x,true,4,"");
         ?>
  	   </td>
      </tr>
      <tr>
	     <td ><strong>Imprimir Endereço :</strong>&nbsp;
       </td>
       <td>
         <?
           $x = array("f"=>"NÃO","t"=>"SIM");
           db_select('endereco',$x,true,4,"");
         ?>
  	   </td>
      </tr>
      <tr>
	     <td ><strong>Imprimir Remuneração :</strong>&nbsp;
       </td>
       <td>
         <?
           $x = array("f"=>"NÃO","t"=>"SIM");
           db_select('padrao',$x,true,4,"");
         ?>
  	   </td>
      </tr>
      <tr>
              <td title="Tipo de ordem" align="right" nowrap="nowrap">
                <strong>Tipo de ordem:</strong>
              </td>
              <td align="left">
               <select name="ordem" id="ordem">
          <option style="background-color: rgb(215, 204, 6);" value="a">Alfabética</option>
            <option style="background-color: rgb(248, 236, 7);" value="d">Numérica</option>
      	
    </select>
    
              </td>
      </tr>      
      <tr >
        <td nowrap><strong>Quebrar :</strong>&nbsp;
        </td>
        <td>
          <?
			      $xxy = array(
					            "n"=>"NÃO", 
				              "s"=>"SIM"
					              );
			  	  db_select('quebrar',$xxy,true,4,"");
				  ?>
				</td>
      </tr>
    </table>
    </fieldset>
    
  </fieldset>
  
  </form>
  
  <input  name="relatorio" id="relatorio" type="button" value="Relatório" onclick="js_emite();" >
  
  </div>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script type="text/javascript">

function js_complementar(opcao){
  selecionados = "";
  virgula_ssel = "";

  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
    virgula_ssel = ",";
  }
  document.form1.colunas_sselecionados.value = selecionados;

  selecionados = "";
  virgula_ssel = "";
  for(var i=0; i<document.form1.nselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.nselecionados.options[i].value;
    virgula_ssel = ",";
  }
  document.form1.colunas_nselecionados.value = selecionados;
 }

function js_emite(){
	
  selecionados = "";
  virgula_ssel = "";
  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
    virgula_ssel = ",";
  }

  qry  = "?colunas="	        + selecionados;
  qry += "&ano="		          + document.form1.anofolha.value;
  qry += "&mes="			        + document.form1.mesfolha.value;
  qry += "&funcion="	        + document.form1.func.value;
  qry += "&ordem="						+ document.form1.ordem.value;
  qry += "&lShowEndereco="    + document.form1.endereco.value;
  qry += "&lShowRemuneracao=" + document.form1.padrao.value;
  qry += "&tipo="		          + document.form1.tipo.value;
  qry += "&quebrar="          + document.form1.quebrar.value;
  qry += "&selecao="          + document.form1.selecao.value;
    
  if(document.form1.selcargo){
    if(document.form1.selcargo.length > 0){
      faixacargo = js_campo_recebe_valores();
      qry+= "&fca="+faixacargo;
    }
  }else if(document.form1.cargoi){
    carini = document.form1.cargoi.value;
    carfim = document.form1.cargof.value;
    qry+= "&cai="+carini;
    qry+= "&caf="+carfim;
  }

  if(document.form1.sellot){
    if(document.form1.sellot.length > 0){
      faixalot = js_campo_recebe_valores();
      qry+= "&flt="+faixalot;
    }
  }else if(document.form1.lotaci){
    lotini = document.form1.lotaci.value;
    lotfim = document.form1.lotacf.value;
    qry+= "&lti="+lotini;
    qry+= "&ltf="+lotfim;
  }
  if(document.form1.selorg){
    if(document.form1.selorg.length > 0){
      faixaorg = js_campo_recebe_valores();
      qry+= "&for="+faixaorg;
    }
  }else if(document.form1.orgaoi){
    orgini = document.form1.orgaoi.value;
    orgfim = document.form1.orgaof.value;
    qry+= "&ori="+orgini;
    qry+= "&orf="+orgfim;
  }
  jan = window.open('pes2_relfuncaolotacaoorgao002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
</body>
</html>