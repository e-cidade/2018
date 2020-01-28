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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("classes/db_orcduplicacao_classe.php");
include("classes/db_orcduplicacaodotacao_classe.php");
include("classes/db_orcduplicacaoreceita_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcreceita_classe.php");
include("classes/db_conaberturaexe_classe.php");
include("dbforms/db_funcoes.php");

//Inicializando e tipando variaveis.
(string)$sErro       = null;
(string)$sForm       = null;
(string)$sLabel      = null;
(real)  $rValorIni   = 0;
(real)  $rValorAtu   = 0;
(real)  $rAcrescimos = 0;
(real)  $rReducoes   = 0;
(bool)  $lErro       = false;
(bool)  $lSqlErro    = false;
(bool)  $lNovo       = false;
(int)   $iTipo       = 0;
(int)   $c91_seq     = 0;
$p                   = db_utils::postMemory($_POST);
$g                   = db_utils::postMemory($_GET);

if ($g->tipo == 'Rec'){
  $iTipo  = 3; 
	$sLabel = "Média Projetada da Arrecadação da Receita";
}else{
  $iTipo = 2;
	$sLabel = "Média Projetada da liquidação da despesa";
}

$clorcduplicacao         = new cl_orcduplicacao;
$clorcduplicacaoreceita  = new cl_orcduplicacaoreceita;
$clorcduplicacaodotacao  = new cl_orcduplicacaodotacao;
$clconaberturaexe        = new cl_conaberturaexe;
$clorcdotacao            = new cl_orcdotacao;
$clorcreceita            = new cl_orcreceita;
$db_opcao                = 1;
$db_botao                = true;

$clconaberturaexe->rotulo->label();
if(isset($p->incluir)){

  db_inicio_transacao();
  //incluindo na conaberturaexe o tipo . 
	$clconaberturaexe->c91_id_usuario    = db_getSession("DB_id_usuario");
	$clconaberturaexe->c91_instit        = db_getSession("DB_instit");
	$clconaberturaexe->c91_hora          = db_hora();
	$clconaberturaexe->c91_data          = date("ymd",db_getsession("DB_datausu"));
	$clconaberturaexe->c91_anousuorigem  = $p->c91_anousuorigem;
	$clconaberturaexe->c91_anousudestino = $p->c91_anousudestino;
	$clconaberturaexe->c91_situacao      = 1;
	$clconaberturaexe->c91_tipo          = $iTipo;
	$clconaberturaexe->c91_ppa           = '2';
	$clconaberturaexe->c91_origem        = $p->c91_origem;
  $clconaberturaexe->incluir(null);

	if ($clconaberturaexe->erro_status = 0){
      $lSqlErro = true;
			$sErro    = $clconaberturaexe->erro_msg;
	}

	if ($lSqlErro == false){
    
		if  ($iTipo == 2){
	  	 //incluindo na orcduplicacao e orcduplicacao receita ou dotacao conforme a variavel iTipo
        $rsDot    = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"orcdotacao.*",'',
		                                  "o58_instit=".db_getSession("DB_instit")."
																			 AND o58_anousu=".$p->c91_anousuorigem));
		
		   $iNumRows = $clorcdotacao->numrows;
		   if ($iNumRows > 0){

				 //db_criatermometro("divorcodatacao",'concluído..','blue',1);
       
			   for ($i = 0;$i < $iNumRows;$i++){ 
         
				   //db_aututermometro($i,$iNumRows,"divorcdotacao"); 
				   $oDot  = db_utils::fieldsMemory($rsDot,$i);
           //verificando qual a origem dos valores.
  			   if ($p->c91_origem == 1){
          
					   $rValorIni = $oDot->o58_valor;
       
			     }else if ($p->c91_origem == 2 ){
               
					  	
						  $rsSal = db_dotacaosaldo(8,2,2,true," o58_coddot = ".$oDot->o58_coddot."
						                            and o58_anousu =".$p->c91_anousuorigem,$p->c91_anousuorigem,
																		    db_getsession("DB_anousu")."-01-01",
																				date("ymd",db_getsession("DB_datausu")));

             $oSal        = db_utils::fieldsMemory($rsSal,0);
					   $rValorIni   = $oDot->o58_valor;
             $rAcrescimos = $oSal->suplementado;   
             $rReducoes   = $oSal->reduzido;   
				  }else if ($p->c91_origem == 3){

					 	
            $rsSal = db_dotacaosaldo(8,2,2,true," o58_coddot = ".$oDot->o58_coddot."
                                     and o58_anousu =".$p->c91_anousuorigem,$p->c91_anousuorigem,
                                     db_getsession("DB_anousu")."-01-01",
																		db_getsession("DB_anousu"). cal_days_in_month(CAL_GREGORIAN, 
																		                             $p->mesusu,db_getsession("DB_anousu")));
             $oSal        = db_utils::fieldsMemory($rsSal,0);
             $rAcrescimos = (($oSal->liquidado_acumulado/$p->mesusu)*12);   
			    }
				  $rValorAtu = (($rValorIni+$rAcrescimos)-$rReducoes);
			    $clorcduplicacao->o75_conaberturaexe  = $clconaberturaexe->c91_sequencial;
 		      $clorcduplicacao->o75_tipo            = $iTipo;
          $clorcduplicacao->o75_previsaoinicial = $rValorIni;
          $clorcduplicacao->o75_acrescimos      = $rAcrescimos;
          $clorcduplicacao->o75_reducoes        = $rReducoes;
          $clorcduplicacao->o75_atualizado      = $rValorAtu;
          $clorcduplicacao->o75_percentual      = '0';
          $clorcduplicacao->o75_valorduplicar   = '0';
          $clorcduplicacao->o75_importar        = '0';
				  $clorcduplicacao->incluir(null);
 
				   if ($clorcduplicacao->erro_status == 0){
              
							$lSqlErro = true;
			        $sErro    = $clorcduplicacao->erro_msg;
              break;

				 }
				 if ($lSqlErro == false){
            
            if ($iTipo == 2){

               $clorcduplicacaodotacao->o76_orcduplicacao = $clorcduplicacao->o75_sequencial;
							 $clorcduplicacaodotacao->o76_anousu        = $p->c91_anousuorigem;  
							 $clorcduplicacaodotacao->o76_coddot        = $oDot->o58_coddot;
							 $clorcduplicacaodotacao->incluir(null);
							 if ($clorcduplicacaodotacao->erro_status == 0){

                 db_msgbox("erro dot");           
                   $lSqlErro = true;
            	     $sErro    = $clorcduplicacaodotacao->erro_msg;
                   break;
							 }

						}

				 }
      }
		 }
    }else if ($iTipo == 3){

        $rsRec    = $clorcreceita->sql_record($clorcreceita->sql_query(null,null,"orcreceita.*",'',
		                                  "o70_instit=".db_getSession("DB_instit")."
																			 AND o70_anousu=".$p->c91_anousuorigem));
		
		    $iNumRows = $clorcreceita->numrows;
		    if ($iNumRows > 0){
       
			   for ($i = 0;$i < $iNumRows;$i++){ 
         
				   $oRec  = db_utils::fieldsMemory($rsRec,$i);
           //verificando qual a origem dos valores.
  			   if ($p->c91_origem == 1){
          
					   $rValorIni = $oRec->o70_valor;
       
			     }elseif ($p->c91_origem == 2){
               
					  	if ($i > 0){	 
                pg_query('drop table work_receita');
						  }
						  $rsSre = db_receitasaldo(11,1,2,true," o70_codrec = ".$oRec->o70_codrec."
						                            and o70_anousu =".$p->c91_anousuorigem,$p->c91_anousuorigem,
																		    db_getsession("DB_anousu")."-01-01",
																				date("ymd",db_getsession("DB_datausu")));

						 $oSre        = db_utils::fieldsMemory($rsSre,0);
					   $rValorIni   = $oRec->o70_valor;
             $rAcrescimos = $oSre->saldo_prevadic_acum;   
              
				  }elseif ($p->c91_origem == 3){
               
					  	if ($i > 0){	 
                pg_query('drop table work_receita');
						  }
						  $rsSre = db_receitasaldo(11,1,3,true," o70_codrec = ".$oRec->o70_codrec."
						                            and o70_anousu =".$p->c91_anousuorigem,$p->c91_anousuorigem,
																		    db_getsession("DB_anousu")."-01-01",
																		    db_getsession("DB_anousu")."-".$p->mesusu."-".cal_days_in_month(CAL_GREGORIAN, 
																		                             $p->mesusu,db_getsession("DB_anousu")));

						 $oSre        = db_utils::fieldsMemory($rsSre,0);//saldo da receita

             if(abs($oSre->saldo_arrecadado_acumulado) == 0) {
               $rAcrescimos = round(($oSre->saldo_inicial/$p->mesusu), 2); 
             } else {
               $rAcrescimos = ((round($oSre->saldo_arrecadado_acumulado,2)/$p->mesusu)*12); 
             }

				  }
				  $rValorAtu = (($rValorIni+$rAcrescimos)-$rReducoes);
			    $clorcduplicacao->o75_conaberturaexe  = $clconaberturaexe->c91_sequencial;
 		      $clorcduplicacao->o75_tipo            = $iTipo;
          $clorcduplicacao->o75_previsaoinicial = $rValorIni;
          $clorcduplicacao->o75_acrescimos      = $rAcrescimos;
          $clorcduplicacao->o75_reducoes        = $rReducoes;
          $clorcduplicacao->o75_atualizado      = $rValorAtu;
          $clorcduplicacao->o75_percentual      = '0';
          $clorcduplicacao->o75_valorduplicar   = '0';
          $clorcduplicacao->o75_importar        = '0';
				  $clorcduplicacao->incluir(null);
 
				   if ($clorcduplicacao->erro_status == 0){
              
							$lSqlErro = true;
			        $sErro    = $clorcduplicacao->erro_msg;
              break;

				 }
				 if ($lSqlErro == false){
            
            $clorcduplicacaoreceita->o77_orcduplicacao = $clorcduplicacao->o75_sequencial;
					  $clorcduplicacaoreceita->o77_anousu        = $p->c91_anousuorigem;  
						$clorcduplicacaoreceita->o77_codrec        = $oRec->o70_codrec;
						$clorcduplicacaoreceita->incluir(null);
						if ($clorcduplicacaoreceita->erro_status == 0){

                 db_msgbox("erro dot");           
                   $lSqlErro = true;
            	     $sErro    = $clorcduplicacaoreceita->erro_msg;
                   break;
							 }

						}

				 }
				 
		/*	echo "<b>DOTACAO:</B>".$oDot->o58_coddot."<br>";
		  echo "<b>INICIAL:</B>".$rValorIni."<br>";
		  echo "<b>ACRESCIMO:</B>".$rAcrescimos."<br>";
		  echo "<b>REDUCOES:</B>".$rReducoes."<br>";
		  echo "<b>ATUALIZADO:</B>".$rValorAtu."<hr>";
			if ($i == 4)exit;*/
      }
		}
		}
		$sSqlErro = true;
  db_fim_transacao($lSqlErro);
}
$rs1  = $clconaberturaexe->sql_record($clconaberturaexe->sql_query(null,'*','',"c91_tipo=1 
                                                                   and c91_situacao = 2 
																																	 and c91_anousudestino= ".(db_getSession("DB_anousu")+1)."
																																	 and c91_instit=".db_getSession("DB_instit")));	
//echo "<br><br>".$clconaberturaexe->sql_query(null,'*','',"c91_tipo=1
//                                                and c91_situacao = 2
//                                                and c91_anousudestino= ".(db_getSession("DB_anousu")+1));
if ($clconaberturaexe->numrows == 0){

    $db_botao = false;
		$sErro    = "Antes de realizar esse procedimento,é necessário abrir o Exercicio contábil";
		$bErro    = true;
		$db_opcao = 3;

}else{
  
	 $rs2  = $clconaberturaexe->sql_record($clconaberturaexe->sql_query(null,'*','',"c91_tipo=$iTipo
	                                                                    and c91_situacao  in(1,2) 
                                                                      and c91_anousudestino = ".(db_getSession("DB_anousu")+1)."
																																	    and c91_instit=".db_getSession("DB_instit")));	
	if ($clconaberturaexe->numrows == 0){
		$lNovo   = true;
		$oCon1   = db_utils::fieldsMemory($rs1,0);
		
	}else{

		$oCon2   = db_utils::fieldsMemory($rs2,0);
    $c91_seq = $oCon2->c91_sequencial;
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="25">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
<table  cellspacing="0" cellpadding="0">
<tr><td>
    <fieldset><legend><b>Duplicação do Orcamento</b></legend>
		<table>
		<form name='form1' method="post">
		<?
     if ($lNovo){
    ?>
             <tr>
                <td nowrap title="<?=@$Tc91_anousuorigem?>">
                 <?=@$Lc91_anousuorigem?>
                </td> 
                 <td> 
                 <?
                   $c91_anousuorigem = $oCon1->c91_anousuorigem;
                   db_input('c91_anousuorigem',5,$Ic91_anousuorigem,true,'text',3,"")
                 ?>
								</td>
						 </tr>
             <tr>
              <td nowrap title="<?=@$Tc91_anousudestino?>">
                 <?=@$Lc91_anousudestino?>
              </td> 
              <td> 
                 <?
                  $c91_anousudestino = $oCon1->c91_anousudestino;
                   db_input('c91_anousudestino',5,$Ic91_anousudestino,true,'text',3,"")
                 ?>
							</td>
					 </tr>
          <tr><td colspan=3><Fieldset><legend><b>Escolha a Previsão:</b></legend>
         <input type='radio'  onclick='js_mostraMes(false)' value='1' name='c91_origem' id='c91_origem1'><label for='c91_origem1'>Previsão Inicial</label><br> 
         <input type='radio' onclick='js_mostraMes(false)' value='2' name='c91_origem' id='c91_origem2'><label for='c91_origem2'>Previsão Atualizada</label><br> 
         <input type='radio' onclick='js_mostraMes(true)' checked value='3' name='c91_origem' id='c91_origem3'>
				 <label for='c91_origem3'><?=$sLabel?></label> 
         </Fieldset></td></tr> 
				 <tr  id='usumes'>
				    <td>
						<b>Selecione o Mês:</b>
						</td>
						<td>
        		<?
						$mesusu = (date("m")-1);
						$result1=array("1"=>"Janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
    		    db_select("mesusu",$result1,true,2,'',"","","","");
				?>
						   
						</td>
				 </tr>		
				 <tr><td colspan='3' style='text-align:center'>
       <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
      onclick='return js_geraImp()' type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
			</td></tr>
		
	  <?
		 }elseif ($sErro == false){
      ?>
  
        <script>
				     
  js_OpenJanelaIframe('top.corpo','db_iframe_orcduplicacao','orc4_orcduplicao005.php?o75_conaberturaexe=<?=$c91_seq?>','Pesquisa',true);
				</script> 

      <?      
		 }
		?>
		</form>
		</table>
		</fieldset>
	</td>
  </tr>
</table>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if ($sErro){

   db_msgbox($sErro);

}
?>
<script>
js_tabulacaoforms("form1","o75_conaberturaexe",true,1,"o75_conaberturaexe",true);
function js_mostraMes(mostra){

   if (mostra){
      document.getElementById('usumes').style.display='';
  }else{
      document.getElementById('usumes').style.display='none';
	}

}
</script>