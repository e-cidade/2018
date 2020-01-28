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
include("classes/db_orcdotacaocontr_classe.php");
include("classes/db_orcreceita_classe.php");
include("classes/db_orcfontes_classe.php");
include("classes/db_conaberturaexe_classe.php");
include("dbforms/db_funcoes.php");

//Inicializando e tipando variaveis.
(string)$sErro       = null;
(string)$sForm       = null;
(string)$sDescr      = null;
(float) $rValorIni   = 0;
(float) $rValorAtu   = 0;
(float) $rAcrescimos = 0;
(float) $rReducoes   = 0;
(bool)  $lErro       = false;
(bool)  $lSqlErro    = false;
(bool)  $lNovo       = false;
(int)   $iTipo       = 0;
(int)   $c91_seq     = 0;
$p                   = db_utils::postMemory($_POST);
$g                   = db_utils::postMemory($_GET);
$sMsgErro            = '';
$db_botao = false;
$iTipo = $g->tipo;
if ($iTipo == 2){
  $sDescr    = "despesa";
  $sLblDescr = "Processa Duplicação do Orçamento da Despesa"; 
}else if ($iTipo == 3){

  $sDescr = "receita";
  $sLblDescr = "Processa Duplicação do Orçamento da Receita"; 

}
$clorcduplicacao         = new cl_orcduplicacao;
$clorcduplicacaoreceita  = new cl_orcduplicacaoreceita;
$clorcduplicacaodotacao  = new cl_orcduplicacaodotacao;
$clconaberturaexe        = new cl_conaberturaexe;
$clorcdotacao            = new cl_orcdotacao;
$clorcdotacaocontr       = new cl_orcdotacaocontr;
$clorcreceita            = new cl_orcreceita;
$clorcfontes             = new cl_orcfontes;
$db_opcao                = 22;
$clconaberturaexe->rotulo->label();
db_inicio_transacao();
if (isset($p->processa)){

  if ($iTipo == 2){

    $rsDot = $clorcduplicacaodotacao->sql_record($clorcduplicacaodotacao->sql_query(null,"*",
                                                                                    '',
                                                                                    "o75_conaberturaexe=".$p->o75_conaberturaexe."
                                                                                     and o75_importar is true")); 

      //db_msgbox($clorcduplicacaodotacao->erro_msg);
      // die($clorcduplicacaodotacao->sql_query(null,"*",'',"o75_conaberturaexe=".$p->o75_conaberturaexe."
      //                                                  and o75_importar is true and o54_instit = c91_instit"));
      if ($clorcduplicacaodotacao->numrows  > 0){

        $iNumRows = $clorcduplicacaodotacao->numrows;
        for ($i = 0;$i < $iNumRows;$i++){

          $oDot        = db_utils::fieldsMemory($rsDot,$i);
          $fValor      = $oDot->o75_valorduplicar;
          $iAnoDestino = $oDot->c91_anousudestino; 
          $rsVdot      = $clorcdotacao->sql_record($clorcdotacao->sql_query($oDot->o58_anousu,$oDot->o58_coddot));
          if ($clorcdotacao->numrows == 1){

            $oVdot       = db_utils::fieldsMemory($rsVdot,0);
            $clorcdotacao->o58_coddot            = $oDot->o58_coddot;
            $clorcdotacao->o58_anousu            = $iAnoDestino;
            $clorcdotacao->o58_orgao             = $oVdot->o58_orgao;
            $clorcdotacao->o58_unidade           = $oVdot->o58_unidade;
            $clorcdotacao->o58_funcao            = $oVdot->o58_funcao;
            $clorcdotacao->o58_subfuncao         = $oVdot->o58_subfuncao;
            $clorcdotacao->o58_programa          = $oVdot->o58_programa;
            $clorcdotacao->o58_projativ          = $oVdot->o58_projativ;
            $clorcdotacao->o58_codele            = $oVdot->o58_codele;
            $clorcdotacao->o58_codigo            = $oVdot->o58_codigo;
            $clorcdotacao->o58_valor             = $fValor;
            $clorcdotacao->o58_instit            = $oVdot->o58_instit;
            $clorcdotacao->o58_concarpeculiar    = "000";
            $clorcdotacao->o58_localizadorgastos = $oVdot->o58_localizadorgastos;
            //$clorcdotacao->elemento    = $oVdot->elemento;
            $clorcdotacao->incluir($clorcdotacao->o58_anousu,$clorcdotacao->o58_coddot);
            if ($clorcdotacao->erro_status == "0"){

              $lSqlErro = true;
              $sErro    = $clorcdotacao->erro_msg;
              break;

            }
            if (!$lSqlErro) {
              
              $sSqlContrapartida = $clorcdotacaocontr->sql_query_file($oDot->o58_anousu,$oDot->o58_coddot);
              $rsContrapartida   = $clorcdotacaocontr->sql_record($sSqlContrapartida);
              if ($clorcdotacaocontr->numrows > 0) {
                
                $iNumRowsContrapartida = $clorcdotacaocontr->numrows;
                for ($iInd = 0; $iInd < $iNumRowsContrapartida; $iInd++) {
                  
                  $oContrapartida = db_utils::fieldsMemory($rsContrapartida);
                  $clorcdotacaocontr->o61_anousu = $iAnoDestino;
                  $clorcdotacaocontr->o61_coddot = $oContrapartida->o61_coddot;
                  $clorcdotacaocontr->o61_codigo = $oContrapartida->o61_codigo;
                  $clorcdotacaocontr->incluir(null);
                  if ($clorcdotacaocontr->erro_status == 0) {
                    
                     $lSqlErro = true;
                     $sErro    = $clorcdotacaocontr->erro_msg;
                     break;
                    
                  }
                }
              }
            }
          }
        }
      }
    if ($lSqlErro == false){

      $clconaberturaexe->c91_situacao = 2;
      $clconaberturaexe->c91_sequencial = $p->o75_conaberturaexe;
      $clconaberturaexe->alterar($clconaberturaexe->c91_sequencial);
      if ($clconaberturaexe->erro_status == "0"){

        $lSqlErro = true;
        $sErro    = $clconaberturaexe->erro_msg;
        break;

      }
    }
  }elseif($iTipo == 3){

    $rsRec = $clorcduplicacaoreceita->sql_record($clorcduplicacaoreceita->sql_query(null,"*",'',"o75_conaberturaexe=".$p->o75_conaberturaexe."
          and o75_importar is true")); 
      //db_msgbox($clorcduplicacaoreceita->erro_msg);
      //die($clorcduplicacaoreceita->sql_query(null,"*",'',"o75_conaberturaexe=".$p->o75_conaberturaexe."
      //                                                 and o75_importar is true"));
      if ($clorcduplicacaoreceita->numrows  > 0){

        $iNumRows = $clorcduplicacaoreceita->numrows;
        for ($i = 0;$i < $iNumRows;$i++){

          $oRec        = db_utils::fieldsMemory($rsRec,$i);
          $fValor      = $oRec->o75_valorduplicar;
          $iAnoDestino = $oRec->c91_anousudestino;
          $rsVRec      = $clorcreceita->sql_record($clorcreceita->sql_query_file($oRec->o70_anousu,$oRec->o70_codrec));
          if ($clorcreceita->numrows == 1){
            $oVrec       = db_utils::fieldsMemory($rsVRec,0);

            $clorcreceita->o70_codrec         = $oRec->o70_codrec;
            $clorcreceita->o70_anousu         = $oRec->c91_anousudestino;
            $clorcreceita->o70_codfon         = $oVrec->o70_codfon;
            $clorcreceita->o70_codigo         = $oVrec->o70_codigo;
            $clorcreceita->o70_valor          = $fValor;
            $clorcreceita->o70_reclan         = "{$oVrec->o70_reclan}";
            $clorcreceita->o70_instit         = $oVrec->o70_instit;
            $clorcreceita->o70_concarpeculiar = $oVrec->o70_concarpeculiar;

            // Forca o Globals pois na classe esta pegando do Globals se o valor for = 'f'
            $GLOBALS["HTTP_POST_VARS"]["o70_reclan"] = "{$oVrec->o70_reclan}";

            // procura o codcon no orcfontes, pois pode ocorrer de ter alguma receita que foi excluida do plano de contas e nesse caso vai dar erro
            //die($clorcfontes->sql_query($oVrec->o70_codfon, $oRec->c91_anousudestino));
            $rsVfontes = $clorcfontes->sql_record($clorcfontes->sql_query($oVrec->o70_codfon, $oRec->c91_anousudestino));

            if ($clorcfontes->numrows > 0) {
              $clorcreceita->incluir($oRec->c91_anousudestino,$clorcreceita->o70_codrec);
              if ($clorcreceita->erro_status == "0"){

                $lSqlErro = true;
                $sErro    = $clorcreceita->erro_msg;
                break;

              }
            }
          }
        }
      }
    if ($lSqlErro == false){

      $clconaberturaexe->c91_situacao = 2;
      $clconaberturaexe->c91_sequencial = $p->o75_conaberturaexe;
      $clconaberturaexe->alterar($clconaberturaexe->c91_sequencial);
      if ($clconaberturaexe->erro_status == "0"){

        $lSqlErro = true;
        $sErro    = $clconaberturaexe->erro_msg;
        break;

      }
    }
  }

  db_fim_transacao($lSqlErro);
  if ($lSqlErro == true){

    db_msgbox('Erro ao importar!\\Erro:'.$sErro);

  }else{

    db_msgbox('Importacao realizada Com sucesso!');
    db_redireciona("orc4_importaduplicacao001.php?tipo=$iTipo");

  }
}
if (isset($g->chavepesquisa) && $g->chavepesquisa != ''){
  $rsCon =  $clconaberturaexe->sql_record($clconaberturaexe->sql_query($g->chavepesquisa));
  if ($clconaberturaexe->numrows > 0){

    $db_opcao           = 3;
    $db_botao           = true;
    $sMsgErro           = null;
    $oCon               = db_utils::fieldsmemory($rsCon,0);
    $c91_anousudestino  = $oCon->c91_anousudestino;
    $c91_anousuorigem   = $oCon->c91_anousuorigem;
    $o75_conaberturaexe = $oCon->c91_sequencial;
    $sSqlTotalRegistros = $clorcduplicacao->sql_query_file(null,
                                                           "count(*) as total",null,
                                                           "o75_conaberturaexe={$o75_conaberturaexe}
                                                           and o75_importar is true");
    $rsTotalRegistros   = $clorcduplicacao->sql_record($sSqlTotalRegistros);
    $iTotalRegistos     = db_utils::fieldsMemory($rsTotalRegistros,0)->total;
    if ($iTotalRegistos == 0) {

      $db_botao = false;
      $sMsgErro = "Não há nenhuma {$sDescr} Configurada! Verifique.";
      
    }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table width="790" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="360" height="25">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<center>
<table>
<tr>
<td>
<fieldset><legend><b> Processa Duplicação do Orçamento  da <?=$sDescr;?></b></legend>
<table width='100%'>
<form name='form1' method="post">


<tr>
<input type='hidden' name='o75_conaberturaexe' value='<?=@$o75_conaberturaexe?>'>
<td nowrap title="<?=@$Tc91_anousuorigem?>">
<?=@$Lc91_anousuorigem?>
</td>
<td> 
<?
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
db_input('c91_anousudestino',5,$Ic91_anousudestino,true,'text',3,"")
?>
</td>
</tr>
<tr>
  <td colspan="1">
     <b>Total de <?=$sDescr?>(s) a importar: </b>
  </td>
  <td>
     <?
     db_input('iTotalRegistos',5,'',true,'text',3,"");
     ?>
  </td>
</tr>  
<tr>
<td colspan='3' style='text-align: center'><input name="processa" onclick='return js_geraImp()' type="submit"
id="db_opcao" value="Processar" <?=($db_botao==false?"disabled":"")?>></td>
</tr>
</table>
</fieldset>
     <b><?=$sMsgErro?></b>
</body>
</html>
<script>
//js_tabulacaoforms("form1","o75_conaberturaexe",true,1,"o75_conaberturaexe",true);
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_duplicacao','func_conaberturaexe.php?ano=1&tipo=<?=$iTipo;?>&situacao=1&funcao_js=parent.js_preenchepesquisa|c91_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_duplicacao.hide();
  <?
    if($db_opcao!=1){
      echo "location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&tipo=$iTipo'";
    }
  ?>
}
function js_geraImp(){

  return (confirm('Confirma o processamento ?'));
}
</script>
<?
if($db_opcao==22){
  echo "<script>js_pesquisa();</script>";
}
?>
<table cellspacing="0" cellpadding="0">
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>