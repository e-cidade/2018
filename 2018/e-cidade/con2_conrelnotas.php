<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_orcparamrel_classe.php"));
require_once(modification("classes/db_orcparamrelnota_classe.php"));
require_once(modification("classes/db_orcparamrelnotaperiodo_classe.php"));
require_once(modification("model/relatorioContabil.model.php"));

$clorcparamrel            = new cl_orcparamrel;
$clorcparamrelnota        = new cl_orcparamrelnota;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$o42_tamanhofontenota  = 6;
$o42_tamanhofontedados = 8;

$clorcparamrelnota->rotulo->label();

$clrotulo   = new rotulocampo;
$clrotulo->label('c83_codrel');
$clrotulo->label('o42_descrrel');

$aPeriodos = array();

db_postmemory($_POST);        

if (isset($oPost->o42_tamanhofontenota)) {
  $o42_tamanhofontenota = $oPost->o42_tamanhofontenota; 
}

if (isset($oPost->o42_tamanhofontedados)) {
  $o42_tamanhofontedados = $oPost->o42_tamanhofontedados;
}

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$instit   = db_getsession("DB_instit");
$anousu   = db_getsession("DB_anousu");
$sqlerro  = false;
$erro_msg = "";
$oRelatorio = new relatorioContabil($c83_codrel);
$db_opcao = 1;

$oPost = db_utils::postMemory($_POST);

$aPeriodosRelatorios = $oRelatorio->getPeriodos();
if (count($aPeriodosRelatorios)> 0) {

  foreach ($aPeriodosRelatorios as $oPeriodo) { 
    $aPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao; 
  }

} else {

  $aPeriodos = array("0" => "Nenhum","1B"=>"Primeiro Bimestre","2B"=>"Segundo Bimestre",
                     "3B"=> "Terceiro Bimestre","4B"=>"Quarto Bimestre","5B"=>"Quinto Bimestre",
                     "6B"=> "Sexto Bimestre","1Q"=>"Primeiro Quadrimestre","2Q"=>"Segundo Quadrimestre",
                     "3Q"=> "Terceiro Quadrimestre","1S"=>"Primeiro Semestre","2S"=>"Segundo Semestre");
}

if (!isset($opcao)){
  $opcao = "gravar";
}

if (isset($opcao) && $opcao == "alterar" || $opcao == "excluir") {
  
  $sSqlPeriodos = $clorcparamrelnota->sql_query_file(null, null, null,null,"*", 
                                                     null,"o42_sequencial = {$o42_sequencial}"
                                                     );
  
  $res = $clorcparamrelnota->sql_record($sSqlPeriodos);
  if ($res !== false && $clorcparamrelnota->numrows > 0 ) {

    db_fieldsmemory($res, 0);
    $o42_nota  = htmlentities($o42_nota );
    $o42_fonte = htmlentities($o42_fonte );

  } else {

    $o42_periodo = "0";
    $o42_nota    = "";
    $o42_fonte   = "";
  }
}

if (isset($opcao) && $opcao == "alterar"){
  $db_opcao = 2;
}

if (isset($opcao) && $opcao == "excluir"){
  $db_opcao = 3;
}

if (isset($gravar)) {
  
  db_inicio_transacao();

  if (trim($o42_nota) == "" && trim($o42_fonte) == ""){
    $sqlerro  = true;
    $erro_msg = "Campo Fonte/Notas Explicativas é de preenchimento obrigatório.";
  }

  if ($sqlerro == false){
    
    $clorcparamrelnota->o42_nota              = $o42_nota;
    $clorcparamrelnota->o42_fonte             = $o42_fonte;
  
    $clorcparamrelnota->o42_periodo           = $o42_periodo;
    $clorcparamrelnota->o42_instit            = $instit;
    $clorcparamrelnota->o42_anousu            = $anousu;
    $clorcparamrelnota->o42_sequencial        = null;
    $clorcparamrelnota->o42_tamanhofontenota  = $o42_tamanhofontenota;
    $clorcparamrelnota->o42_tamanhofontedados = $o42_tamanhofontedados;
    
 
    $clorcparamrelnota->incluir($c83_codrel,$anousu,$instit,$o42_periodo);
    $erro_msg = $clorcparamrelnota->erro_msg;
    if ($clorcparamrelnota->erro_status == 0 ) {
      $sqlerro = true;
    }
  }
  if (!$sqlerro) {
    
    $aPeriodosRelatorios = $oRelatorio->getPeriodos();
    if (count($aPeriodosRelatorios) > 0) {

      $oDaoNotaPeriodo = new cl_orcparamrelnotaperiodo;
      $oDaoNotaPeriodo->o118_orcparamrelnota = $clorcparamrelnota->o42_sequencial;
      $oDaoNotaPeriodo->o118_periodo         = $o42_periodo;
      $oDaoNotaPeriodo->incluir(null);
      if ($oDaoNotaPeriodo->erro_status == 0) {
        
        $erro_msg = $clorcparamrelnota->erro_msg;        
        $sqlerro  = true;
        
      }
          
    }
  }
  db_fim_transacao($sqlerro);
  
  $o42_tamanhofontenota  = 6;
  $o42_tamanhofontedados = 8;
}

if (isset($alterar)) {
  
  db_inicio_transacao();
  
  $clorcparamrelnota->o42_sequencial        = $o42_sequencial; 
  $clorcparamrelnota->o42_nota              = $o42_nota;
  $clorcparamrelnota->o42_fonte             = $o42_fonte;
  
  $clorcparamrelnota->o42_periodo           = $o42_periodo;
  $clorcparamrelnota->o42_instit            = $instit;
  $clorcparamrelnota->o42_anousu            = $anousu;
  $clorcparamrelnota->o42_codparrel         = $c83_codrel;
  $clorcparamrelnota->o42_tamanhofontenota  = $o42_tamanhofontenota;
  $clorcparamrelnota->o42_tamanhofontedados = $o42_tamanhofontedados;
  
  $clorcparamrelnota->alterar($c83_codrel,$anousu,$instit,$o42_periodo);
  $erro_msg = $clorcparamrelnota->erro_msg;
  if ($clorcparamrelnota->erro_status == 0 ) {
    $sqlerro = true;
    db_msgbox($clorcparamrelnota->erro_msg);
  }
  
  if (!$sqlerro) {
    
    $aPeriodosRelatorios = $oRelatorio->getPeriodos();
    if (count($aPeriodosRelatorios) > 0) {

      $oDaoNotaPeriodo = new cl_orcparamrelnotaperiodo;
      $oDaoNotaPeriodo->excluir(null,"o118_orcparamrelnota = {$o42_sequencial}");
      $oDaoNotaPeriodo->o118_orcparamrelnota = $clorcparamrelnota->o42_sequencial;
      $oDaoNotaPeriodo->o118_periodo         = $o42_periodo;
      $oDaoNotaPeriodo->incluir(null);
      $erro_msg = $clorcparamrelnota->erro_msg;
      if ($oDaoNotaPeriodo->erro_status == 0) {
        
       $erro_msg = $clorcparamrelnota->erro_msg;        
       $sqlerro  = true;
        
      }
    }
  }
  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    $opcao    = "gravar";
    $db_opcao = 1;
  }
  $o42_tamanhofontenota  = 6;
  $o42_tamanhofontedados = 8;
}

if (isset($excluir)) {

  db_inicio_transacao();
  $oDaoNotaPeriodo = new cl_orcparamrelnotaperiodo;
  $oDaoNotaPeriodo->excluir(null,"o118_orcparamrelnota = {$o42_sequencial}");
  $clorcparamrelnota->o42_periodo   = $o42_periodo;
  $clorcparamrelnota->o42_instit    = $instit;
  $clorcparamrelnota->o42_anousu    = $anousu;
  $clorcparamrelnota->o42_codparrel = $c83_codrel;
  
  $clorcparamrelnota->excluir(null, null, null,null,"o42_sequencial={$o42_sequencial}");
  $erro_msg = $clorcparamrelnota->erro_msg;
  if ($clorcparamrelnota->erro_status == 0 ) {
    
    $sqlerro = true;
    db_msgbox($clorcparamrelnota->erro_msg);
  }
  $o42_sequencial   = "";  
  db_fim_transacao($sqlerro);

  if ($sqlerro == false) {
    
    $opcao    = "gravar";
    $db_opcao = 1;
  }
  $o42_tamanhofontenota  = 6;
  $o42_tamanhofontedados = 8;
}

if ($db_opcao == 1) {
  
  $o42_periodo    = "0";
  $o42_nota       = "";
  $o42_fonte      = "";
  $o42_sequencial = "";
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_limpar() {
  document.location.href="con2_conrelnotas.php?c83_codrel=<?=$c83_codrel?>";
}
function js_verifica(opcao) {

  if (opcao == "gravar" || opcao == "alterar") {
  
    if (document.form1.o42_periodo.value == "0") {
    
      alert("Informe um periodo válido.");
      document.form1.o42_periodo.focus();

      return false;
    }
    if (document.form1.o42_tamanhofontenota.value == "0" || document.form1.o42_tamanhofontenota.value == "") {
    
      alert("Tamanho da Fonte da Nota não pode ser vazio.");
      document.form1.o42_tamanhofontenota.focus();

      return false;
    }
    if (document.form1.o42_tamanhofontedados.value == "0" || document.form1.o42_tamanhofontedados.value == "") {
    
      alert("Tamanho da Fonte da Fonte não pode ser vazio.");
      document.form1.o42_tamanhofontedados.focus();

      return false;
    }
  }

  return true;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="post" action="" onSubmit="return js_verifica('<?=$opcao?>');">
<center>
  <table align="center" style="width: 980px; margin-top: 10px;">
   <tr>
    <td>
      <table align="center" border=0>
        <? 
           db_input('c83_codrel',5,$Ic83_codrel,true,'hidden',3,"");
        ?>
         <tr>
            <td colspan="2" align=center title="<?=$To42_periodo?>"><?=$Lo42_periodo?>
            <?
				      db_select("o42_periodo",$aPeriodos,true,$db_opcao,"");
				
				      if ($db_opcao == 3){
				        $readonly = " readonly ";
				        $style    = ";background-color:#DEB887;";
				      } else {
				        $readonly = "";
				        $style    = "";
				      }
				    ?>
				    </td>
				 </tr>
			</table>
			
      <fieldset>
        <legend><b><?php echo str_replace(':', null, $Lo42_nota); ?></b></legend>
			  <table>	 
          <tr>
            <td colspan="2">
	            <?
	              if ($db_opcao != 1) {
	                db_input("o42_sequencial",10,0,true,"hidden");
	              }
	            ?>
	            <textarea title="<?=$To42_nota?>" <?=$readonly?> style="font-family:Arial;font-size:12pt<?=$style?>" 
	                      name=o42_nota rows=8 cols=100 value=<?=$o42_nota?> ><?=$o42_nota?></textarea>
            </td>
          </tr>
          <tr>
            <td style="width: 150px;">
              <b>Tamanho da Fonte:</b>
            </td>
            <td>
              <? 
                db_input('o42_tamanhofontenota',10,$Io42_tamanhofontenota,true,'text',1,"");
              ?>
            </td>  
          </tr>
        </table>
      </fieldset>
      
      <fieldset>
        <legend><b><?= str_replace(':', null, $Lo42_fonte) ?></b></legend>
        <table>
          <tr>
            <td colspan="2">
             <textarea title="<?=$To42_fonte?>" <?=$readonly?> style="font-family:Arial;font-size:12pt<?=$style?>" 
                       name=o42_fonte rows=5 cols=100 value=<?=$o42_fonte?> ><?=$o42_fonte?></textarea>
            </td>
          </tr> 
          <tr>
            <td style="width: 150px;">
              <b>Tamanho da Fonte:</b>
            </td>
            <td>
              <? 
                db_input('o42_tamanhofontedados',10,$Io42_tamanhofontedados,true,'text',1,"");
              ?>
            </td>  
          </tr>
        </table>
      </fieldset>
      </table>
      <table style="width: 980px;">      
				 <tr>
				   <td colspan=2 align="center"><input type=submit name="<?=$opcao?>" value=<?=ucfirst($opcao)?>>
				   <?
				      if ($db_opcao != 1) {
				   ?>
				   &nbsp;&nbsp;<input type=button name="novo" value=Novo onClick="js_limpar();">
				   <?
				      }
				   ?>
				   </td>
				 </tr>
				 <tr><td colspan=2>&nbsp;</td></tr>
				 <tr>
				   <td colspan=2>
				     <table border=0 width="100%">
				       <tr>
				         <td>
									   <?
									     $chavepri= array("o42_sequencial" => @$o42_sequencial);
									     $cliframe_alterar_excluir->chavepri= $chavepri;
									     $cliframe_alterar_excluir->sql     = $clorcparamrelnota->sql_query_periodo($c83_codrel,
									                                                                                $anousu,
									                                                                                $instit,null,"
									                                                                                orcparamrel.o42_codparrel,
									                                                                                o42_anousu,
									                                                                                o42_instit,
									                                                                                o42_sequencial,
									                                                                                case when o118_sequencial is null then 
									                                                                                    o42_periodo 
									                                                                                  else 
									                                                                                   o114_sigla
									                                                                                  end as o42_periodo,  
									                                                                                o42_nota,
									                                                                                o42_fonte"); 
									     $cliframe_alterar_excluir->campos  = "o42_sequencial,o42_periodo,o42_nota,o42_fonte";
									     $cliframe_alterar_excluir->legenda = "Fonte/Notas Explicativas";
									     $cliframe_alterar_excluir->iframe_height = "200";
									     $cliframe_alterar_excluir->iframe_width  = "100%";
									
									     $clorcparamrelnota->sql_record($cliframe_alterar_excluir->sql); 
									     if ($clorcparamrelnota->numrows == 0) {
									       $cliframe_alterar_excluir->msg_vazio = "Nenhum registro cadastrado.";
									     }
									
									     $cliframe_alterar_excluir->iframe_alterar_excluir(1);
									   ?>
				          </td>
				       </tr> 
				     </table>
				   </td>
				 </tr>
				</table>
    </td>
   </tr>
  </table> 
</center>
</form>
<?
   if (trim($erro_msg)) {
     db_msgbox($erro_msg);
   }
?>
</body>
</html>