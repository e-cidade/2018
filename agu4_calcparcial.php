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
include("classes/db_aguabase_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$claguabase = new cl_aguabase;
$claguabase->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('z01_numcgm');
$db_opcao = 1;
if (isset($calcular)) {
  $lErro = false;
  db_inicio_transacao();
  if (isset($HTTP_POST_VARS['x01_matric'])) {
    for ($x=$parc_ini; $x<=$parc_fim; $x++) {
      $sql = "select fc_agua_calculoparcial($anousu, $x, $x01_matric, 1, true, true)";        
      $result = db_query($sql);
      if (pg_numrows($result) != 0) {      	
      	$retorno_result = pg_result($result,0,0);
        $retorno = substr($retorno_result,0,1);        
        if ($retorno!='1') {
          $lErro = true;
          $claguabase->erro_msg = "Erro: ".$retorno_result;
          $claguabase->erro_status = '0';
          break;
        }        
      } else {
        $claguabase->erro_msg = pg_last_error();
        $claguabase->erro_status = '0';
        break;
      }      
    }
  } else {
    $lErro = true;
    $claguabase->erro_msg = 'Matricula não informada.';
    $claguabase->erro_status = '0';
  }
  db_fim_transacao($lErro);
}


if (isset($demonstrativo)) {
  if (isset($HTTP_POST_VARS['x01_matric'])) {
    $result = db_query("select fc_calculoiptu($x01_matric,$anousu,true,false,false,false,true)");
    if (($result!=false) && (pg_numrows($result) != 0)) {
      $retorno_result = @pg_result($result,0,0);
      $retorno = substr($retorno_result,0,1);
      if ($retorno!='9' and $retorno!=' ') {
        $claguabase->erro_msg = "Erro: ".$retorno_result;
        $claguabase->erro_status = '0';
      } else {
        $claguabase->erro_msg = "Demonstrativo efetuado!";
        $claguabase->erro_status = '0';
      }
    } else {
      $claguabase->erro_msg = pg_last_error();
      $claguabase->erro_status = '0';
    }
  } else {
    $claguabase->erro_msg = 'Matricula não informada.';
    $claguabase->erro_status = '0';
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
function js_verificacalculo(){
   if(document.form1.x01_matric.value == ""){
     alert('Informe uma Matrícula.');
   return false;
   }
   return true;
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.x01_matric.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
   <form name="form1" action="" method="post" onSubmit="return js_verificacalculo();">
      <table width="387" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="27" height="25" title="<?=$Tz01_nunmcgm?>"> 
              <?
        db_ancora('<strong>Matricula:</strong>','js_mostranomes(true);',4)
        ?>
            </td>
            <td width="360" height="25"> 
              <?
        db_input("x01_matric",8,$Ix01_matric,true,'text',4," onchange='js_mostranomes(false);' ")
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
              $result=db_query("select " . db_getsession("DB_anousu") . "as j18_anousu");
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
          <td height="25">
          <strong>Parcela Inicial:</strong>
          </td>
          <td height="25">
          <?
          $result1=array("1"=>"Janeiro","2"=>"Feveireiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
          db_select("parc_ini",$result1,true,$db_opcao,"","","","","");
          
          if (!isset($parc_ini)){
            $posicao=date('m',db_getsession("DB_datausu"));
            $parc_ini=@$result1[$posicao];
          }

          ?>
          </td>
          </tr>

          <tr>
          <td height="25">
          <strong>Parcela Final:</strong>
          </td>
          <td height="25">
          <?
          $result2=array("1"=>"Janeiro","2"=>"Feveireiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
          db_select("parc_fim",$result2,true,$db_opcao,"","","","","");
          
          if (!isset($parc_fim)){
            $posicao=date('m',db_getsession("DB_datausu"));
            $parc_fim=@$result2[$posicao];
          }

          ?>
          </td>
          </tr>
        
          
          <tr> 
            <td height="25">&nbsp;</td>
            <td height="25"> <input name="calcular"  type="submit" id="calcular" value="Calcular">
              <input name="demonstrativo"  type="submit" id="demonstrativo" value="Demonstrativo"> 
              <?
        if(isset($calcular)){
          ?>
              <input name="Limpar"  type="button" id="limpr" value="Limpar" onClick="document.form1.x01_matric.value='';document.form1.z01_nome.value=''">
                <input name="ultimo"  type="button" id="ultimo" value="&Uacute;ltimo C&aacute;lculo" onClick="func_nome.show();  func_nome.focus();">
              <?
        }
        ?>
            </td>
          </tr>
    <tr>
      <td colspan=3>
        <textarea id="text_demo" name="text_demo" rows=20 cols=95 style="visibility:hidden"><?=$retorno_result?></textarea>
      </td>
    <tr>
        </table>
      </form>
     </td>
  </tr>
 <tr>
 </table>
</body>
</html>

<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_mostranomes(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_aguabase.php?funcao_js=parent.js_preenche|0|1';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_aguabase.php?pesquisa_chave='+document.form1.x01_matric.value+'&funcao_js=parent.js_preenche1'; 
  }
}
 function js_preenche(chave,chave1){
   document.form1.x01_matric.value = chave;
   document.form1.z01_nome.value = chave1;
   func_nome.hide();
 }
 function js_preenche1(chave,chave1){
   document.form1.z01_nome.value = chave;
   if(chave1==false){
     document.form1.x01_matric.select();
     document.form1.x01_matric.focus();
   }
   func_nome.hide();
 }

</script>
<?

if ( isset($calcular) ) {
  if ( $lErro == false ) {
    db_msgbox("Calculo efetuado com sucesso");
  }else{
    db_msgbox("{$claguabase->erro_msg}");
  }
}

$func_nome = new janela('func_nome','');
$func_nome ->posX=1;
$func_nome ->posY=20;
$func_nome ->largura=770;
$func_nome ->altura=430;
$func_nome ->titulo="Pesquisa";
$func_nome ->iniciarVisivel = false;
$func_nome ->mostrar();

if(isset($calcular) && $lErro == false){
  ?>
  <script>
    func_nome.jan.location.href = "agu3_conscadastro_002_detalhes.php?solicitacao=Calculo&parametro=<?=$HTTP_POST_VARS['x01_matric']?>";
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  </script>
  <?
}
else if(isset($demonstrativo)){
  ?>
  <script>
   document.form1.text_demo.style.visibility = "visible";
  </script>
  <?
}
?>