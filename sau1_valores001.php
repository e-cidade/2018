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
include("classes/db_procedimentos_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clprocedimentos = new cl_procedimentos;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $clprocedimentos->incluir($sd09_i_codigo);
  db_fim_transacao();
}
elseif(isset($aterar))
?>
<html>
 <head>
  <title> <?=$TITULO?></title>
  <link href="../../config/estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body topmargin="0" leftmargin="0" class="texto">
 
<?
 $proc  = $_GET["Procedimento"];
 $sqlvalor = "SELECT * FROM valores WHERE procedimento_i_codigo = $proc";
 $query = @pg_query($conexao,$sqlvalor);
 if($query)
 {
  $linhas = pg_num_rows($query);
  if($linhas<>0)
   $array = @pg_fetch_array($query,0);
 }
 else
 {
  echo "E R R O !";
  exit;
 }
?>
 <script language="Javascript">
  function SendReg(url)
  {
   window.opener.setRecipients(url);
   window.close();
  }
  function cent(amount)
  {
   //retorna o valor com 2 casas decimais
   return(amount == Math.floor(amount)) ? amount + '.00' : ( (amount*10 == Math.floor(amount*10)) ? amount + '0' : amount);
  }
  function dec(cantidad, decimales)
  {
   //arredonda o valor
   var cantidad = parseFloat(cantidad);
   var decimales = parseFloat(decimales);
   decimales = (!decimales ? 2 : decimales);
   return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
  }
  //verifica soma
  function VerificaSoma()
  {
   Soma=0;
   for(i=0;i<14;i++)
   {
    Soma -= cent(dec(this.form[i].value,2));
   }
   document.form.total.value = (cent(dec(Soma,2)))*(-1);
   document.form.total.value = cent(dec(document.form.total.value,2));
  }
  function FormataValor(Campo)
  {
   //formata o valor e soma
   var vr = Campo.value;
   vr = vr.replace(",", ".");
   Campo.value = cent(dec(vr,2));
   VerificaSoma();
   Campo.disabled=true;
  }
  function EnviaDados()
  {
   a1  = "<?=$proc?>";
   a2  = this.form.sala.value;
   a3  = this.form.valor.value;
   a4  = this.form.servico.value;
   a5  = this.form.anestesia.value;
   a6  = this.form.material.value;
   a7  = this.form.contraste.value;
   a8  = this.form.filme.value;
   a9  = this.form.gesso.value;
   a10 = this.form.quimio.value;
   a11 = this.form.dialise.value;
   a12 = this.form.sadtrat.value;
   a13 = this.form.sadtpc.value;
   a14 = this.form.sadtoutros.value;
   a15 = this.form.outros.value;
   a16 = this.form.filme2.value;
   a17 = this.form.total.value;
   if(confirm("Confirma valores?"))
   {
    location="procvalores.php?Array="+a1+","+a2+","+a3+","+a4+","+a5+","+a6+","+a7+","+a8+","+a9+","+a10+","+a11+","+a12+","+a13+","+a14+","+a15+","+a16+","+a17;
   }
   else
   {
    close();
   }
  }
 </script>
<table width="100%" border="0" cellspacing="0" cellpading="0" bgcolor="#ffffff">
 <form method="post" name="form">
  <tr>
   <td class="bold" align="left" bgcolor="#eaeaea" colspan="2"><img src="../../images/valores.gif"> Cadastro de Valores para o Procedimento.</td>
  </tr>
  <tr><td height="1" bgcolor="#888888" colspan="2"></td></tr>
  <tr>
   <td class="bold" width="75%">Procedimento:</td>
   <td class="bold3"><?=str_pad($proc,3,0,str_pad_left)?></td>
  </tr>
  <tr>
   <td class="bold">Taxa da Sala:</td>
   <td><input type="text" name="sala" value="<?=number_format($array[1],2,'.','')?>" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Valor do Procedimento:</td>
   <td><input type="text" value="<?=number_format($array[2],2,'.','')?>" name="valor" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Servico Profissional:</td>
   <td><input type="text" value="<?=number_format($array[3],2,'.','')?>" name="servico" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Anestesia:</td>
   <td><input type="text" value="<?=number_format($array[4],2,'.','')?>" name="anestesia" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Material Médico:</td>
   <td><input type="text" value="<?=number_format($array[5],2,'.','')?>" name="material" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Contraste:</td>
   <td><input type="text" value="<?=number_format($array[6],2,'.','')?>" name="contraste" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Filme:</td>
   <td><input type="text" value="<?=number_format($array[7],2,'.','')?>" name="filme" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Gesso:</td>
   <td><input type="text" value="<?=number_format($array[8],2,'.','')?>" name="gesso" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Quimioterapia:</td>
   <td><input type="text" value="<?=number_format($array[9],2,'.','')?>" name="quimio" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Dialise:</td>
   <td><input type="text" value="<?=number_format($array[10],2,'.','')?>" name="dialise" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Sadt rat:</td>
   <td><input type="text" value="<?=number_format($array[11],2,'.','')?>" name="sadtrat" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Sadt pc:</td>
   <td><input type="text" value="<?=number_format($array[12],2,'.','')?>" name="sadtpc" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Sadt Outros:</td>
   <td><input type="text" value="<?=number_format($array[13],2,'.','')?>" name="sadtoutros" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Outros:</td>
   <td><input type="text" value="<?=number_format($array[14],2,'.','')?>" name="outros" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Filme2:</td>
   <td><input type="text" value="<?=number_format($array[15],2,'.','')?>" name="filme2" size="8" onBlur="FormataValor(this)"></td>
  </tr>
  <tr>
   <td class="bold">Total:</td>
   <td class="blue">
    <input type="text" name="total" value="<?=number_format($array[16],2,'.','')?>" size="7" style="border:0; font-weight: bold;" disabled>
   </td>
  </tr>
  <tr><td height="1" bgcolor="#888888" colspan="2"></td></tr>
  <tr>
   <td align="center" height="80" bgcolor="#eaeaea" colspan="2">
    <input type="button" value="Atualizar" onclick="EnviaDados()" style="text-align: center; font-family: Arial; color: #000000;">
    <input type="button" value="Refazer" onclick="location='procvalores.php?Procedimento=<?=$_GET[Procedimento]?>&Servico=<?=$_GET[Servico]?>&Tipo=<?=$_GET[Tipo]?>&FEtaria=<?=$_GET[FEtaria]?>&Especialidade=<?=$_GET[Especialidade]?>&Valores=<?=$_GET[Valores]?>&Grupo=<?=$_GET[Grupo]?>'" style="text-align: center; font-family: Arial; color: #000000;">
    <input type="button" value="Cancelar" onclick="javascript:window.close()" style="text-align: center; font-family: Arial; color: #000000;">
    <br><br>
    <div class="pequeno">
    <img src="../../images/ajuda.gif">
    Se acontecer algum erro na entrada de dados, clique em <b>Refazer</b>.
    </div>
   </td>
  </tr>
 </form>
</table>
<?
if(isset($_GET["Array"]))
{
 //grava dados
 $Array  = $_GET["Array"];
 $Grava  = explode(",",$Array);
 $SqlValores = "INSERT INTO valores
                VALUES
                (
                 $Grava[0],
                 $Grava[1],
                 $Grava[2],
                 $Grava[3],
                 $Grava[4],
                 $Grava[5],
                 $Grava[6],
                 $Grava[7],
                 $Grava[8],
                 $Grava[9],
                 $Grava[10],
                 $Grava[11],
                 $Grava[12],
                 $Grava[13],
                 $Grava[14],
                 $Grava[15]
                )
                ";
 $insert = @pg_query($conexao,$SqlValores);
 if(!$insert)
 {
  $SqlValores = "UPDATE valores SET
                  valor_c_sala         = $Grava[1],
                  valor_f_valor        = $Grava[2],
                  valor_f_servico      = $Grava[3],
                  valor_f_anestesia    = $Grava[4],
                  valor_f_material     = $Grava[5],
                  valor_f_contraste    = $Grava[6],
                  valor_f_filme        = $Grava[7],
                  valor_f_gesso        = $Grava[8],
                  valor_f_quimio       = $Grava[9],
                  valor_f_dialise      = $Grava[10],
                  valor_f_sadtrat      = $Grava[11],
                  valor_f_sadtpc       = $Grava[12],
                  valor_f_sadtout      = $Grava[13],
                  valor_f_outro        = $Grava[14],
                  valor_f_filme2       = $Grava[15],
                  valor_f_total        = $Grava[16]
                 WHERE procedimento_i_codigo = $Grava[0]
                ";
  $update = @pg_query($conexao,$SqlValores);
  if($update)
   $Msg = "Valores Atualizados!";
 }
 else
  $Msg = "Valores Gravados!";
 ?>
 <table width="100%" height="500" border="0" cellspacing="0" cellpading="0" bgcolor="#ffffff">
  <tr>
   <td align="center">
    <img src="../../images/sistema.gif"><br><br>
    <div class="green"><?=$Msg?></div>
    <br><br><br><br><br>
    <input type="button" value="Voltar" onclick="javascript:history.back()">
    <input type="button" value="Fechar" onclick="javascript:window.close()">
   </td>
  </tr>
 </table>
 <?
}
?>
</body>
</html>