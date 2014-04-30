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
include("classes/db_carteira_classe.php");
include("classes/db_leitor_classe.php");
include("classes/db_biblioteca_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcarteira = new cl_carteira;
$clleitor = new cl_leitor;
$clbiblioteca = new cl_biblioteca;
$clcarteira->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi10_codigo");
$clrotulo->label("bi07_nome");
$clrotulo->label("z01_nome");
$depto = db_getsession("DB_coddepto");
$db_opcao = 1;
$db_botao = true;
if(empty($bi16_leitor)){
 $opcao = 1;
}else{
 $opcao = 3;
 ///verifica outras carteiras do leitor
 $result = $clcarteira->sql_record($clcarteira->sql_query("","carteira.*,leitorcategoria.bi07_nome,biblioteca.bi17_nome","bi16_validade desc"," bi10_codigo = $bi16_leitor AND bi17_coddepto = $depto"));
// die(">>> ".$clcarteira->sql_query("","carteira.*,leitorcategoria.bi07_nome,biblioteca.bi17_nome","bi16_validade desc"," bi10_codigo = $bi16_leitor AND bi17_coddepto = $depto") );
 $linhas = $clcarteira->numrows;
 if($linhas!=0){
  db_fieldsmemory($result,0);
 }
 ///verifica validades
 $pontos = 0;
 if(str_replace("-","",@$bi16_validade)<date("Ymd")){
  //inválida
  $pontos++;
  if($linhas>0 && !isset($incluir)){
   db_msgbox("Carteira deste leitor está vencida desde ".db_formatar(@$bi16_validade,'d')." !");
  }
  $bi16_codigo = "";
 }else{
  //válida
  $pontos--;
 }
}
if(isset($incluir)){
 db_inicio_transacao();
 $clcarteira->bi16_valida = 'S';
 $clcarteira->bi16_usuario = db_getsession("DB_id_usuario");
 $clcarteira->incluir($bi16_codigo);
 db_fim_transacao();
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="">
<table width="95%" border="0" align="center">
 <tr>
  <td>
   <fieldset width="50%"><legend><b>Dados do Leitor para Cadastro de Carteira:</b></legend>
    <table border="0">
     <tr>
      <td nowrap title="<?=@$Tbi16_leitor?>">
      <?db_ancora(@$Lbi16_leitor,"",$opcao);?>
      </td>
      <td>
       <?db_input('bi16_leitor',10,$Ibi16_leitor,true,'text',$opcao,"")?>
       <?db_input('z01_nome',50,@$z01_nome,true,'text',3," ")?>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
</table>
<table border="0" width="95%" align="center">
 <tr>
  <td colspan="2">
   <fieldset width="100%"><legend><b>Nova Carteira:</b></legend>
    <?include("forms/db_frmcarteira.php");?>
   </fieldset>
  </td>
 </tr>
</table>
</form>
<?if($linhas>0){?>
 <table border="0" width="95%" align="center">
  <tr>
   <td colspan="2">
    <fieldset width="100%"><legend><b>Carteiras deste Leitor:</b></legend>
     <table border="1" width="100%" cellspacing="0" cellpadding="1">
      <tr bgcolor="#888888">
       <td><b>Código</b></td>
       <td><b>Biblioteca</b></td>
       <td><b>Categoria</b></td>
       <td><b>Inclusão</b></td>
       <td><b>Válido até</b></td>
       <td><b>Situação</b></td>
      </tr>
      <?
      $cor1 = "#f3f3f3";
      $cor2 = "#ababab";
      $cor  = "";
      for($x=0;$x<$linhas;$x++){

        db_fieldsmemory($result,$x);
        if ($cor == $cor1) {
          $cor = $cor2;
        } else {
          $cor = $cor1;
        }
        
        
        $click   = "location.href='bib1_carteira002.php?chavepesquisa=$bi16_codigo'";
        $corover = "#DEB887";
        if (str_replace("-","",$bi16_validade)<date("Ymd")) {
        
          $situacao = "red";
          $texto    = "VENCIDA";
          $sql      = "UPDATE carteira SET bi16_valida = 'N' WHERE bi16_codigo = $bi16_codigo";
          $query    = db_query($sql);
        } else {
        
          $situacao = "green";
          $texto    = "VÁLIDA";
        }
        ?>
        <tr bgcolor="<?=$cor?>" onclick="<?=$click?>" onmouseover="bgColor='<?=$corover?>'" onmouseout="bgColor='<?=$cor?>'">
         <td><?=$bi16_codigo?></td>
         <td><?=$bi17_nome?></td>
         <td><?=$bi07_nome?></td>
         <td><?=db_formatar($bi16_inclusao,'d')?></td>
         <td><?=db_formatar($bi16_validade,'d')?></td>
         <td align="center" style="color:#FFFFFF;" bgcolor="<?=@$situacao?>"><?=@$texto?></td>
        </tr>
        <?
      }
      ?>
      <tr>
       <td align="center" colspan="6">
        Clique na linha para alteração dos dados (Somente carteiras válidas).
       </td>
      </tr>
     </table>
    </fieldset>
   </td>
  </tr>
 </table>
<?}?>
</body>
</html>
<?
if(isset($incluir)){
 if($clcarteira->erro_status=="0"){
  $clcarteira->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcarteira->erro_campo!=""){
   echo "<script> document.form1.".$clcarteira->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcarteira->erro_campo.".focus();</script>";
  };
 }else{
  $clcarteira->pagina_retorno = "bib1_carteira001.php?bi16_leitor=$bi16_leitor&z01_nome=$z01_nome";
  $clcarteira->erro(true,true);
 };
};
?>