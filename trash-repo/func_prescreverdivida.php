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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include_once("dbforms/db_classesgenericas.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arrecant_classe.php");
include("classes/db_prescricao_classe.php");
include("classes/db_prescricaolista_classe.php");
include("classes/db_arreprescr_classe.php");
include("classes/db_listadeb_classe.php");
include("classes/db_arrehist_classe.php");
include("classes/db_divida_classe.php");
include("libs/db_sql.php");

$cliframe_seleciona = new cl_iframe_seleciona;
$clprescricao = new cl_prescricao;
$clarrecad    = new cl_arrecad;
$clarrecant   = new cl_arrecant;
$clarreprescr = new cl_arreprescr;
$cllistadeb   = new cl_listadeb;
$clarrehist   = new cl_arrehist;
$cldivida     = new cl_divida;
$oDaoPrescricaolista= new cl_prescricaolista;

//db_postmemory($HTTP_SERVER_VARS,2);
//db_postmemory($HTTP_POST_VARS,2);
//exit; 
//arreprescr k30_anulado is false
//prescricao and prescricao.k31_situacao = 1

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$instit = db_getsession("DB_instit");

$where    = "";
$inner    = "";
$wheresel = "";
$whereval = "";
$innersel = "";
$erromsg  = "";
$db_opcao = 1;
if (isset($numcgm) && $numcgm != "" ){
  $inner    = " inner join arrenumcgm on arrenumcgm.k00_numpre = a.k00_numpre ";
  $innersel = " inner join arrenumcgm on arrenumcgm.k00_numpre = a.k00_numpre ";
  $where    = " and  arrenumcgm.k00_numcgm = $numcgm ";
  $wheresel = " and  arrenumcgm.k00_numcgm = $numcgm ";
}else if (isset($matric) && $matric != ""){
  $inner    = " inner join arrematric on arrematric.k00_numpre = a.k00_numpre ";
  $innersel = " inner join arrematric on arrematric.k00_numpre = a.k00_numpre ";
  $where    = " and arrematric.k00_matric = $matric ";
  $wheresel = " and arrematric.k00_matric = $matric ";
}else if (isset($inscr) && $inscr != "" ){
  $inner    = " inner join arreinscr on arreinscr.k00_numpre = a.k00_numpre ";
  $innersel = " inner join arreinscr on arreinscr.k00_numpre = a.k00_numpre ";
  $where    = " and arreinscr.k00_inscr = $inscr ";
  $wheresel = " and arreinscr.k00_inscr = $inscr ";
}
if(isset($ex) && $ex != ""){
  $whereexerc  = " and v01_exerc in ($ex) ";
}else{
  $whereexerc  = "";
}
if(isset($vlrmin) && $vlrmin != "" && isset($vlrmax) && $vlrmax != ""){
  $whereval = " where db_total between $vlrmin and $vlrmax ";
}else if (isset($vlrmin) && $vlrmin != "" && (!isset($vlrmax) || $vlrmax == "")){
  $whereval = " where db_total > $vlrmin ";
}else if( isset($vlrmax) && $vlrmax != "" && (!isset($vlrmin) || $vlrmin == "")){
  $whereval = " where db_total < $vlrmax ";
}

if($prescrnoti=="n"){
  $notificado = " and notidebitos.k53_numpre is null ";
}else{
  $notificado = "  ";
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0">
<table width="100%" height="" border="0" align="center" cellspacing="0"	bgcolor="#CCCCCC">
	<? include("forms/db_frmprescrevedebitosalt.php"); ?>
</table>
	<?
	// se clicou em processar
	$datadeb="";
	if(isset($processar) && $processar != ''){
	  
	  if(isset($geral) && $geral == 't'){
	    
	    if(isset($lista) && $lista != ''){
	      
	      // verificar arretipo e lista
	      $sqllista = "select * from listatipos
					inner join arretipo on k00_tipo = k62_tipodeb 
					where k62_lista = $lista and k03_tipo <> 5"; 
	      $resultlista =  db_query($sqllista);
	      $linhaslista = pg_num_rows($resultlista);

	      $sqlVerificaLista = " select	distinct
									                    listadeb.k61_codigo, 
									                    listadeb.k61_numpre, 
									                    listadeb.k61_numpar, 
									                    certter.v14_parcel, 
									                    termo.v07_parcel, 
									                    notidebitos.k53_numpre,
                                      certdiv.v14_certid,
                                      arrecad.k00_tipo,
                                      arretipo.k03_tipo,
                                      arretipo.k00_descr,
																			lista.k60_datadeb
                              --      arrenumcgm.k00_numcgm,
                              --      arrematric.k00_matric,
                              --      arreinscr.k00_inscr
			from listadeb
      inner join lista          on k61_codigo               = k60_codigo
                               and lista.k60_instit         = $instit
      left  join divida         on listadeb.k61_numpre      = divida.v01_numpre
                               and listadeb.k61_numpar      = divida.v01_numpar
															 and divida.v01_instit        = ".db_getsession('DB_instit')."
      left  join certdiv        on certdiv.v14_coddiv       = divida.v01_coddiv
      inner join arrecad        on listadeb.k61_numpre      = arrecad.k00_numpre 
                               and listadeb.k61_numpar      = arrecad.k00_numpar
      inner join arretipo       on arretipo.k00_tipo        = arrecad.k00_tipo
    --  left  join arrenumcgm     on arrenumcgm.k00_numpre  = arrecad.k00_numpre
    --  left  join arrematric     on arrematric.k00_numpre  = arrecad.k00_numpre
    --  left  join arreinscr      on arreinscr.k00_numpre   = arrecad.k00_numpre
      left  join inicialnumpre  on inicialnumpre.v59_numpre = divida.v01_numpre
      left  join termo          on termo.v07_numpre         = listadeb.k61_numpre
			                         and termo.v07_instit         = ".db_getsession('DB_instit')."
      left  join certter        on certter.v14_parcel       = termo.v07_parcel
      left  join notidebitos    on notidebitos.k53_numpre   = listadeb.k61_numpre
                               and notidebitos.k53_numpar   = listadeb.k61_numpar
      where k61_codigo = $lista 
			";
			//die($sqlVerificaLista);
	      /*
	       and (
	       termo.v07_numpre         is not null
	       or certter.v14_parcel       is not null
	       or notidebitos.k53_numpre   is not null
	       or inicialnumpre.v59_numpre is not null)";*/
	      //die($sqlVerificaLista);

	      // Aqui tu verifica as inconsistencias...
	      //... and k03_tipo <> 5 

	      //die($sqlVerificaLista);

	      if($linhaslista > 0){
	        db_msgbox("Lista gerado com tipo de debitos diferente da divida ativa");
	        echo "<script> js_volta(); </script>";
	        exit;
	      }else{
	        // verifica se tem algum com tipo diferente de 5 (divida ativa)
          $sqlVerificaLista1   = $sqlVerificaLista ."and k03_tipo <> 5 ";
	        $rsListaPrescr1 = $cllistadeb->sql_record($sqlVerificaLista1);
	        $intListadeb1 = $cllistadeb->numrows;
	        if($intListadeb1 > 0){
	          db_msgbox("EXISTEM DÉBITOS NA LISTA QUE NÃO É PERMITIDA A PRESCRIÇÃO - ATUALIZE A TABELA DÉBITOS E GERE NOVAMENTE A LISTA");
	          echo "
		         <script>
		           if(confirm('Deseja emitir relatório com os débitos da lista que não podem ser prescritos?')){
		             js_OpenJanelaIframe('','db_iframe_relatorio','cai4_prescrevedebitosgeral002.php?lista=$lista','',false);
			       } 
		         </script>
                ";
	          // gerar PDF
	          echo "<script> js_volta(); </script>";
	          exit;
	        }else{
	          // não tem nenhum registro com tipo <> de 5... então vai prescrever a lista.
	          $rsListaPrescr = $cllistadeb->sql_record($sqlVerificaLista);
	          //echo $sqlVerificaLista;
	          $intListadeb = $cllistadeb->numrows;
	          $chaves = '';
	          $chaves1 = '';
	          if($intListadeb > 0){
	            $sustenido = '';
	            $sustenido1 = '';
	            for($iprescr = 0; $iprescr < $intListadeb; $iprescr++){
	              db_atutermometro($iprescr,$intListadeb,'termometro1');
	              db_fieldsmemory($rsListaPrescr,$iprescr);

	              //
	              if($k03_tipo <> 5) {
	                db_msgbox("Existe(m) débito(s) inválido(s) para prescrição na lista selecionada, operação cancelada ! (Tipo: $k00_tipo-".trim($k00_descr).")");
	                echo "<script> js_volta(); </script>";
	                exit;

	              }else if(isset($k53_numpre) && $k53_numpre != '' ){
	                
	                if (isset($prescrnoti)&&$prescrnoti=="n"){
	                  $chaves1 .= $sustenido1.$k61_numpre.'-'.$k61_numpar;
	                  $sustenido1 = 'XVX';
	                  $datadeb=$k60_datadeb;
	                  continue;
	                }
	                /*
	                 db_msgbox('Existem debitos notificados na lista selecionada, operação cancelada !');
	                 echo "<script> js_volta(); </script>";
	                 exit;
	                 */
	              }
	              $chaves .= $sustenido.$k61_numpre.'-'.$k61_numpar;
	              $sustenido = '#';
	              //$k31_obs = '';
	            }
	          }else{
	            db_msgbox("Verifique a lista selecionada !");
	            echo "<script> js_volta(); </script>";
	            exit;

	          }
	        }
	      }

	    }
	    //select na lista para buscar os numpres e numpar
	  }

	  //die($chaves);
		if(isset($chaves1)){
			db_putsession('chaves1', $chaves1);
		}
	
	  $sqlerro = false;
	  
	  if ($chaves==""){
	    db_msgbox("Processamendo concluido!!Nenhum registro prescrito!!");
	    if (isset($prescrnoti)&&$prescrnoti=="n"&&$chaves1!=""){
        
				echo "
		      <script>
		      jan = window.open('div2_debnoti.php?lista=$lista&datadeb=$datadeb','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		      </script>
     	 ";
				
			
	    }
	    echo "<script>js_volta();</script>";
	    exit;

	  }
	  
	  db_inicio_transacao();
	  
    $clprescricao->k31_instit  = db_getsession("DB_instit");
	  $clprescricao->k31_hora    = db_hora();
	  $clprescricao->k31_data    = date("Y-m-d",db_getsession("DB_datausu"));
	  $clprescricao->k31_usuario = db_getsession("DB_id_usuario");
    $clprescricao->k31_obs     = " ".@$k31_obs;
    $clprescricao->k31_situacao= 1;
	  $clprescricao->incluir( null );
	  
	  if( $clprescricao->erro_status == 0){
	    $sqlerro = true;
	    $erromsg = "prescricao - ".$clprescricao->erro_msg;
	  }
	  
	  if ( isset($lista) && trim($lista) != '' ) {
	    
      $oDaoPrescricaolista->k122_lista      = $lista;
      $oDaoPrescricaolista->k122_prescricao = $clprescricao->k31_codigo; 
  	  $oDaoPrescricaolista->incluir( null );
  	  
      if( $oDaoPrescricaolista->erro_status == 0){
        $sqlerro = true;
        $erromsg = "prescricaolista - ".$oDaoPrescricaolista->erro_msg;
      }
	  }
	  
	  $numpres = split('#',$chaves);
	  $numrows = count($numpres);
	  $i = 0;

	  // print_r($numpres);
	  if ($sqlerro == false) {

	    foreach ($numpres as $ni => $nv) {
	      db_atutermometro($i,$numrows,'termometro');

	      $i++;

	      list ($k00_numpre,$k00_numpar) = split('-',$nv);

	      if ($sqlerro == false) {
	        $result_deb = debitos_numpre($k00_numpre,0,null,db_getsession("DB_datausu"),db_getsession("DB_anousu"),$k00_numpar);
					if (pg_numrows($result_deb) == 0) {
	          $sqlerro = true;
	          $erromsg = "correcao nao processada - numpre: $k00_numpre - parcela: $k00_numpar";
	          break;
	        }

	        for ($reg=0; $reg < pg_numrows($result_deb); $reg++) {
	          db_fieldsmemory($result_deb, $reg);

	          if ($sqlerro == false) {

	            $clarreprescr->k30_prescricao = $clprescricao->k31_codigo;
	            $clarreprescr->k30_numpre     = $k00_numpre;
	            $clarreprescr->k30_numpar     = $k00_numpar;
	            $clarreprescr->k30_numcgm     = $k00_numcgm;
	            $clarreprescr->k30_dtoper     = $k00_dtoper;
	            $clarreprescr->k30_receit     = $k00_receit;
	            $clarreprescr->k30_hist       = $k00_hist;
	            $clarreprescr->k30_valor      = $vlrhis;
	            $clarreprescr->k30_numtot     = $k00_numtot;
	            $clarreprescr->k30_dtvenc     = $k00_dtvenc;
	            $clarreprescr->k30_numdig     = $k00_numdig;
	            $clarreprescr->k30_tipo       = $k00_tipo;
	            $clarreprescr->k30_tipojm     = $k00_tipojm;
	            $clarreprescr->k30_vlrcorr    = $vlrcor;
	            $clarreprescr->k30_vlrjuros   = $vlrjuros;
	            $clarreprescr->k30_multa      = $vlrmulta;
              $clarreprescr->k30_desconto   = $vlrdesconto;
              $clarreprescr->k30_anulado    = "false";
	            $clarreprescr->incluir( null );
	            if ($clarreprescr->erro_status == 0) {
	              $sqlerro = true;
	              $erromsg =  "arreprescr - ".$clarreprescr->erro_msg;
	              break;
	            }

	            if ($sqlerro == false) {
	              // insere em arrecant
	              $clarrecant->k00_numcgm  = $k00_numcgm;
	              $clarrecant->k00_dtoper  = $k00_dtoper;
	              $clarrecant->k00_receit  = $k00_receit;
	              $clarrecant->k00_hist    = $k00_hist;
	              $clarrecant->k00_valor   = $vlrhis;
	              $clarrecant->k00_dtvenc  = $k00_dtvenc;
	              $clarrecant->k00_numpre  = $k00_numpre;
	              $clarrecant->k00_numpar  = $k00_numpar;
	              $clarrecant->k00_numtot  = $k00_numtot;
	              $clarrecant->k00_numdig  = $k00_numdig;
	              $clarrecant->k00_tipo    = $k00_tipo;
	              $clarrecant->k00_tipojm  = $k00_tipojm;
	              $clarrecant->incluir();
	              if( $clarrecant->erro_status == 0){
	                $sqlerro = true;
	                $erromsg =  "arrecant - ".$clarrecant->erro_msg;
	                break;
	              }

	              if ($sqlerro == false) {
	                // tira da tabela arrecad
	                $clarrecad->excluir(null,"k00_numpre = $k00_numpre and k00_numpar = $k00_numpar and k00_receit = $k00_receit");
	                if( $clarrecad->erro_status == 0){
	                  $sqlerro = true;
	                  $erromsg =  "arrecad - ".$clarrecad->erro_msg;
	                  break;
	                }

	              }

	            }

	          }

	        } // fim do for do select por numpre/numpar/receita retornado com fc_calcula

	      }

	    } // fim do for por numpre/parcela da lista

	  }
	  //$sqlerro=true;
	  db_fim_transacao($sqlerro);

	  if($sqlerro==true){
	    db_msgbox($erromsg);
	  }else{
	    db_msgbox("Processamendo concluido com sucesso!");
	    if (isset($prescrnoti)&&$prescrnoti=="n"&&$chaves1!=""){
	      echo "
      <script>
      jan = window.open('div2_debnoti.php?lista=$lista&datadeb=$datadeb','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      </script>
      ";
	    }
	    echo "<script>js_volta();</script>";
	  }
	}
	?>
</body>
</html>
<script>
/*function js_onclicproc(){
  //  js_ajax_msg('aguarde processando ...');
}
function js_volta(){
  parent.db_iframe_proc.hide();
  parent.js_limpacampos();
  location.href = 'cai4_prescrevedebitos001.php
}
function js_mostraruas(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_preencheruas|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form2.j14_codigo.value+'&funcao_js=parent.js_preencheruas';	
  }
}
function js_preencheruas(chave,chave1){
  document.form2.j14_codigo.value = chave;
  document.form2.j14_nome.value = chave1;
  db_iframe.hide();
}*/
</script>