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
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_inicial_classe.php");
require_once ("classes/db_processoforoinicial_classe.php");
require_once ("classes/db_parjuridico_classe.php");
$oPost                 = db_utils::postMemory($_POST);
$clinicial             = new cl_inicial;
$clprocessoforoinicial = new cl_processoforoinicial;
$oDaoParJuridico       = new cl_parjuridico();

$db_opcao = 1;
$clrotulo = new rotulocampo;
$clrotulo->label('v60_peticao');
$clrotulo->label('v60_inicial');

$aParametrosJuridico = $oDaoParJuridico->getParametrosJuridico(db_getsession('DB_instit'), db_getsession('DB_anousu'));

if ( !$aParametrosJuridico ) {
 db_msgbox(_M('tributario.juridico.jur2_reemitepetquitini001.verifique_parametros_juridico'));
 $db_opcao =3;
}
$oParametrosJuridico = $aParametrosJuridico[0];

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
<body bgcolor=#CCCCCC>

<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Relatórios - Reemite Petição - Inicial Quitada</legend>
    <table class="form-container">
    <? 
      if ( ( isset($oPost->inicial) && !empty($oPost->inicial) ) &&  isset($oPost->emite) ) {
        
        $rsInicial       = $clinicial->sql_record( $clinicial->sql_query_file($inicial,"1", null) );
        $iNumRowsInicial = $clinicial->numrows;
        
        /**
         * Valida se a inical existe
         */
        if ( $iNumRowsInicial == 0 ) {
          
          db_msgbox(_M('tributario.juridico.jur2_reemitepetquitini001.inicial_nao_existe'));
          db_redireciona();
        }
      
        $sWhere                       = "processoforoinicial.v71_inicial = {$inicial} and processoforoinicial.v71_anulado is false";
        $rsProcessoForoInicial        = $clprocessoforoinicial->sql_record($clprocessoforoinicial->sql_query(null,"v70_codforo",null,$sWhere));
        $iNumRowsProcessoForoInicial  = $clprocessoforoinicial->numrows;
        
        if ( $iNumRowsProcessoForoInicial == 0 ) {
          
          db_msgbox(_M('tributario.juridico.jur2_reemitepetquitini001.inicial_sem_codigo_processo'));
          db_redireciona();
        }
      
        $sSqlDadosInicial =" select case when k00_inscr is not null                              \n";
        $sSqlDadosInicial.="             then 'inscricao'                                        \n";
        $sSqlDadosInicial.="             when k00_matric is not null                             \n";
        $sSqlDadosInicial.="             then 'matricula'                                        \n";
        $sSqlDadosInicial.="             else 'cgm'                                              \n";
        $sSqlDadosInicial.="        end as tipo,                                                 \n";
        $sSqlDadosInicial.="        case when k00_inscr is not null                              \n";
        $sSqlDadosInicial.="             then k00_inscr                                          \n";
        $sSqlDadosInicial.="             when k00_matric is not null                             \n";
        $sSqlDadosInicial.="             then k00_matric                                         \n";
        $sSqlDadosInicial.="             else arrenumcgm.k00_numpre                              \n";
        $sSqlDadosInicial.="        end as chave_pesquisa,v59_numpre, v59_inicial                \n";
        $sSqlDadosInicial.="   from inicialnumpre                                                \n";
        $sSqlDadosInicial.="        inner join arrenumcgm  on arrenumcgm.k00_numpre = v59_numpre \n";
        $sSqlDadosInicial.="        left  join arreinscr   on arreinscr.k00_numpre  = v59_numpre \n";
        $sSqlDadosInicial.="        left  join arrematric  on arrematric.k00_numpre = v59_numpre \n";
        $sSqlDadosInicial.="  where v59_inicial = $inicial                                       \n";
        
        $rsDadosInicial   = db_query($sSqlDadosInicial);
        
        if ( $rsDadosInicial && pg_num_rows($rsDadosInicial) > 0){
          $oDadosInicial =  db_utils::fieldsMemory( $rsDadosInicial, 0 );
        }
      
        $iChave             = $oDadosInicial->chave_pesquisa;
        $iInicial           = $oDadosInicial->v59_inicial;
        $sTipo              = $oDadosInicial->tipo;
        $sDadosIni          = "xx".$iInicial."ww".$iChave."ww".$sTipo;
        $sQueryString       = "iCodigoPeticao={$v60_peticao}&dadosini={$sDadosIni}&sTipoPeticao=inicialquitada";
        $sProgramaRelatorio = empty($oParametrosJuridico->v19_templateinicialquitada) ? "jur2_inicialquit002.php" : "jur2_peticao003.php";
      
        /**
         * Abre janela do relatório
         */   
        echo " <script>                                                                                                            \n";
        echo "   jan = window.open('{$sProgramaRelatorio}?{$sQueryString}&fazparcela=true',                                         \n";
        echo "                     '',                                                                                             \n";
        echo "                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '); \n";
        echo "   jan.moveTo(0,0);                                                                                                  \n";
        echo " </script>                                                                                                           \n";
      }
    ?>
      <tr>
        <td>
          <?db_ancora('Petições:','js_pesquisa(true);',$db_opcao);?>
        </td>
        <td>
		      <? db_input('v60_peticao',8,$Iv60_peticao,true,'text',$db_opcao,"onchange='js_pesquisa(false);'")?>
		      <? db_input('inicial',8,$Iv60_inicial,true,'hidden',$db_opcao)?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input  name="emite" id="emite" type="submit" value="Emitir" <? echo $db_opcao == 3 ? "disabled" : ""; ?> >
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisa(mostra){
  
  if (mostra) {
      
    db_iframe.jan.location.href = 'func_jurpeticoes.php?tipo=q&funcao_js=parent.js_mostra1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
    
  } else {
    db_iframe.jan.location.href = 'func_jurpeticoes.php?tipo=q&pesquisa_chave='+document.form1.v60_peticao.value+'&funcao_js=parent.js_mostra';
  }
}
function js_mostra(chave, chave1, erro) {
	
  if (erro) {
	  
    document.form1.v60_peticao.value = "";
    document.form1.v60_peticao.focus();
    document.form1.inicial.value     = "";
  }else{
	  
    document.form1.v60_peticao.value = chave;
    document.form1.inicial.value     = chave1;
  }
}

function js_mostra1(chave1, chave2){
	
  document.form1.v60_peticao.value = chave1;
  document.form1.inicial.value     = chave2;
  db_iframe.hide();
}
</script>


<?
if ( isset($ordem) ) {
	
  echo " <script>      \n";
  echo "   js_emite(); \n";
  echo " </script>     \n";  
}
$func_iframe                 = new janela('db_iframe','');
$func_iframe->posX           = 1;
$func_iframe->posY           = 20;
$func_iframe->largura        = 780;
$func_iframe->altura         = 430;
$func_iframe->titulo         = 'Pesquisa';
$func_iframe->iniciarVisivel =  false;
$func_iframe->mostrar();

?>
<script>

$("v60_peticao").addClassName("field-size2");

</script>