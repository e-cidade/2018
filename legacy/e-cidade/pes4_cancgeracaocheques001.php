<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhemissaocheque_classe.php");
include("classes/db_rhemissaochequeitem_classe.php");
include("dbforms/db_classesgenericas.php");

$oPost = db_utils::postMemory($_POST);

$clrhemissaocheque     = new cl_rhemissaocheque();
$clrhemissaochequeitem = new cl_rhemissaochequeitem();

$clrhemissaochequeitem->rotulo->label();

$db_opcao = 1;

if ( isset($oPost->cancelar) ) {
	
	$lSqlErro = false;
	
	db_inicio_transacao();
	
	$sWhere = "";
  $sAnd   = "";
  
	if ( $oPost->tipoFiltro == "s") {
	  $sWhere .= " r18_sequencial in ({$oPost->lista})";
	} else {
		
		if ( isset($oPost->chequeitemini) && trim($oPost->chequeitemini) != "" ) {
			$sWhere .= " r18_sequencial >= {$oPost->chequeitemini}";
			$sAnd    = " and ";
 		}
		
		if ( isset($oPost->chequeitemfin) && trim($oPost->chequeitemfin) != "" ) {
			$sWhere .= " {$sAnd} r18_sequencial <= {$oPost->chequeitemfin}";
		}
		
	}
  
  $sSqlCheque        = $clrhemissaochequeitem->sql_query_file(null,"distinct r18_emissaocheque",null,$sWhere);
  $rsConsultaCheques = $clrhemissaochequeitem->sql_record($sSqlCheque);
  $iLinhasCheques    = $clrhemissaochequeitem->numrows;	
	
	if ( $iLinhasCheques > 0 ) {
		
	  for( $iInd=0; $iInd < $iLinhasCheques; $iInd++ ){
      
      $oCheques = db_utils::fieldsMemory($rsConsultaCheques,$iInd);
      $aLotes[] = $oCheques->r18_emissaocheque; 
      
    }		
		
		
		$clrhemissaochequeitem->excluir(null,$sWhere);
		
		if ( $clrhemissaochequeitem->erro_status == 0 ) {
			$lSqlErro = true;
		}
	
		$sMsgErro = $clrhemissaochequeitem->erro_msg;
		
		
		if ( !$lSqlErro ) {

      $sWhereLote  = "     r15_sequencial in (".implode(",",$aLotes).") "; 
      $sWhereLote .= " and r18_sequencial is null                       ";
      
      $sSqlLote        = $clrhemissaocheque->sql_query_item(null,"distinct r15_sequencial",null,$sWhereLote);
      $rsConsultaLotes = $clrhemissaocheque->sql_record($sSqlLote);
      $iLinhasLote     = $clrhemissaocheque->numrows;			
			
      for ( $iInd=0; $iInd < $iLinhasLote; $iInd++ ) {
        
        $oLote = db_utils::fieldsMemory($rsConsultaLotes,$iInd);
        
        $clrhemissaocheque->excluir($oLote->r15_sequencial);
        
        if ( $clrhemissaocheque->erro_status == 0 ) {
          $lSqlErro = true;
        }
      
        $sMsgErro = $clrhemissaocheque->erro_msg;
      }	
		}
		
	} else {
		$lSqlErro = true;
		$sMsgErro = "Nenhum registro encontrado!";
	}
	
	db_fim_transacao($lSqlErro);
	
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="post" action="">
	<table align="center" style="padding-top:25px;">
	  <tr>
	    <td colspan="2">
			  <fieldset>
			    <legend align="center">
			      <b>Cancela Geração por Cheque</b>
			    </legend>
				  <table><!--
				    <tr>
				      <td align="right">
				        <?
                  db_ancora("<b>Cód. Geração</b>","js_pesquisaGeracao(true);",$db_opcao);
				        ?>
				      </td>
				      <td>
				        <?
                  db_input("r15_sequencial",10,$Ir15_sequencial,true,"text",$db_opcao,"onChange='js_pesquisaGeracao(false);'");
                  db_input("r15_descricao" ,40,$Ir15_descricao,true,"text",3);
				        ?>
				      </td>
				    </tr>
            --><tr>
            <tr>
              <td align="right">
                <b>Tipo Filtro:</b>
              </td>
              <td>
                <?
                
                  $aTipoFiltro = array( "s"=>"Selecionado",
                                        "i"=>"Intervalo" ); 
                  
                  db_select("tipoFiltro",$aTipoFiltro,true,1,"onChange='document.form1.submit();'");
                ?>  
              </td>
            </tr>
            <?
               if (isset($oPost->tipoFiltro) && $oPost->tipoFiltro == "i") {
               	
	               echo "<tr>";
	               echo "  <td> ";
	               db_ancora("<b>Inicial:</b>","js_pesquisaGeracaoIni(true);",1,"");
	               echo "  </td>";               	
	               echo "  <td> ";
	               db_input("chequeitemini",10,true,$Ir18_sequencial,"text","");
	               echo "  </td>";
	               echo "</tr>";
	               echo "<tr>";
	               echo "  <td> ";
	               db_ancora("<b>Final:</b>","js_pesquisaGeracaoFin(true);",1,"");
	               echo "  </td>";                 
	               echo "  <td> ";
	               db_input("chequeitemfin",10,true,$Ir18_sequencial,"text","");
	               echo "  </td>";               	
	               echo "</tr>";
	               
               } else {
               	
               	 $clauxiliar = new cl_arquivo_auxiliar;
               	 
								 $clauxiliar->cabecalho      = "<strong>Cheques Gerados</strong>";
								 $clauxiliar->codigo         = "r18_sequencial";
								 $clauxiliar->descr          = "z01_nome";
								 $clauxiliar->nomeobjeto     = 'chequesSel';
								 $clauxiliar->funcao_js      = 'js_mostraa';
								 $clauxiliar->funcao_js_hide = 'js_mostraa1';
								 $clauxiliar->sql_exec       = "";
								 $clauxiliar->func_arquivo   = "func_rhemissaochequeitem.php";
								 $clauxiliar->nomeiframe     = "iframe_cheque";
								 $clauxiliar->onclick        = "";
								 $clauxiliar->localjan       = "";
								 $clauxiliar->db_opcao       = 2;
								 $clauxiliar->tipo           = 2;
								 $clauxiliar->linhas         = 10;
								 $clauxiliar->vwhidth        = 400;
								 $clauxiliar->funcao_gera_formulario();
                  
								 db_input("lista",50,"",true,"hidden"); 
              }
               
            ?>
				  </table>
			  </fieldset>
		  </td>
	  </tr>
	  <tr>
	    <td align="center"> 
	      <input name="cancelar"  type="submit" value="Processar" onClick="return js_cancelar();" >
	    </td>
	  </tr>
	</table> 	  
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_cancelar(){

  var doc = document.form1;
  
  if ( doc.tipoFiltro.value == "s") {
	  if(doc.chequesSel){
	    if(doc.chequesSel.length > 0){
	      document.form1.lista.value = js_campo_recebe_valores();
	    } else {
	      alert('Nenhum registro selecionado!');
	      return false;
	    }
	  }
  } else {
    if ( doc.chequeitemini.value == "" && doc.chequeitemfin.value == "")  {
      alert('Intervalo não informado!');
      return false;
    }
  }
}  
  
function js_pesquisaGeracaoIni(mostra){
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_geracao','func_rhemissaochequeitem.php?funcao_js=parent.js_mostraGeracaoIni|r18_sequencial','Pesquisa',true);
  }
}

function js_mostraGeracaoIni(chave1){
  document.form1.chequeitemini.value = chave1; 
  db_iframe_geracao.hide();
}


function js_pesquisaGeracaoFin(mostra){
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_geracao','func_rhemissaochequeitem.php?funcao_js=parent.js_mostraGeracaoFin|r18_sequencial','Pesquisa',true);
  }
}

function js_mostraGeracaoFin(chave1){
  document.form1.chequeitemfin.value = chave1; 
  db_iframe_geracao.hide();
}


</script>

<?
   
   if ( isset($oPost->cancelar) ) {

   	 db_msgbox($sMsgErro);
   	 
   	 if ( !$lSqlErro ) {
   	 	  echo "<script>document.location.href='pes4_cancgeracaocheques001.php'</script>";
   	 }
   	
   }
   
?>