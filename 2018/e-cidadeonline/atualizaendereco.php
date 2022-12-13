<?
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

session_start();
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_cgmatualiza_classe.php");
include("classes/db_db_cgmatualizaliga_classe.php");
include("classes/db_cgm_classe.php");
include("libs/db_mail_class.php");

$cldb_cgmatualiza = new cl_db_cgmatualiza;
$cldb_cgmatualizaliga = new cl_db_cgmatualizaliga;
$cl_cgm = new cl_cgm;
$cldb_cgmatualiza->rotulo->label();
$db_opcao = "";
db_postmemory($HTTP_POST_VARS);
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                    FROM db_menupref
                    WHERE m_arquivo = 'digitaaidof.php'
                    ORDER BY m_descricao
                   ");
if(pg_num_rows($result)>0){
	db_fieldsmemory($result,0);
	if($m_publico != 't'){
	  if(!session_is_registered("DB_acesso"))
	    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
	}
}
$db_verificaip = db_verifica_ip();
mens_help();
$dblink="atualizaendereco.php";
db_logs("","",0,"Atualiza endereço CGM.");
db_mensagem("endereco_cab","endereco_rod");
if($db_verificaip == "0"){
  $onsubmit = "onsubmit=\"return js_verificaCGCCPF((this.cgc.value==''?'':this.cgc),'');\"";
}else{
  $onsubmit = "";
}  
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
if($cgmlogin==""){
 db_redireciona("centro_pref.php");
}else{
 $result = $cl_cgm->sql_record($cl_cgm->sql_query("","*","","cgm.z01_numcgm = $cgmlogin"));
 if($cl_cgm->numrows != 0){
  db_fieldsmemory($result,0);
  $w11_cgccpf = $z01_cgccpf;
 }
}
////gravar
if(isset($incluir)){
 @$result2 = $cldb_cgmatualiza->sql_record($cldb_cgmatualiza->sql_query("","*","","w11_numcgm = $cgmlogin"));
 db_inicio_transacao();
 if($cldb_cgmatualiza->numrows == 0){
  //insert
  $cldb_cgmatualiza->incluir(null);
  //cgmatualizaliga se cgm não for novo
  if($w11_cgmnovo=="f"){
   $cldb_cgmatualizaliga->w12_cgmatualiza = $cldb_cgmatualiza->w11_sequencial;
   $cldb_cgmatualizaliga->w12_numcgm = $w11_numcgm;
   $cldb_cgmatualizaliga->incluir(null);
  }
 }else{
  db_fieldsmemory($result2,0);
  //update
  $cldb_cgmatualiza->alterar($w11_sequencial);
 }
 db_fim_transacao();
 if($cldb_cgmatualiza->erro_status=="0"){
  @$cldb_cgmatualiza->erro();
 }else{
  db_msgbox("Seus dados foram encaminhados para análise.");
  //encaminhar email
  $mensagemDestinatario = "
$nomeinst
Atualização/Pedido de CGM - Prefeitura On-Line
----------------------------
Nome:     $w11_nome
CPF/CNPJ: $w11_cgccpf
E-mail:   $w11_email

".date("d/m/Y - H:i:s")." - ".getenv("REMOTE_ADDR")."
Seus dados do CGM foram encaminhados para análise.
Aguarde retorno sobre seu pedido.

$url

Não responda este e-mail, ele foi gerado automaticamente pelo Servidor.
----------------------------
";

  //$headers   = "Content-Type:text\n Bcc: $email";
  //$enviando  = mail($w11_email,"Prefeitura On-Line - Atualização/Pedido de CGM",$mensagemDestinatario,$headers);
    
  $rsConsultaConfigDBPref = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file(db_getsession('DB_instit'),"w13_emailadmin"));
  db_fieldsmemory($rsConsultaConfigDBPref,0);
 
  $oMail = new mail();
  $oMail->Send($w11_email,$w13_emailadmin,'Prefeitura On-Line - Atualização/Pedido de CGM',$mensagemDestinatario);
  
  
  db_redireciona("centro_pref.php");
 }
}
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script language="JavaScript" src="scripts/scripts.js"></script>
<style type="text/css">
<?//db_estilosite();?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<?//mens_div();?>
<center>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="<?=$DB_align1?>">
      <?=$DB_mens1?>
    </td>
  </tr>
  <tr>
   <td align="center" valign="top" class="texto">
<?if(!isset($tipo)){?>
 <br><br><br><br><br>
 <form>
  <input type="hidden" name="id_usuario" value="<?=$id_usuario?>">
  <input type="hidden" name="w11_email" value="<?=$w11_email?>">
  <input type="hidden" name="cgmlogin" value="<?=$cgmlogin?>">
  <?=strlen(@$w11_cgccpf)>=14?"CNPJ":"CPF"?>:
  <input type="text" name="w11_cgccpf" value="<?=@$w11_cgccpf?>"><br><br>
  CGM no Município:
  <select name="tipo">
   <option value="s">Sim</option>
   <option value="n">Não</option>
  </select><br><br><br>
  <input type="submit" value="Próximo">
 </form>
<?
}else{
if($tipo=="s")
 $tipo = 3;
if($tipo=="n")
 $tipo = "";
//se ja efetuou pedido de cgm
if($cgmlogin==0){
 $novocgm = "t";
 $result = $cldb_cgmatualiza->sql_record($cldb_cgmatualiza->sql_query("","*","","w11_cgccpf = '$w11_cgccpf'"));
 @$z01_cgccpf = $w11_cgccpf;
 @$z01_email = $w11_email;
 if($cldb_cgmatualiza->numrows != 0){
  db_fieldsmemory($result,0);
 }
 @$w11_munic = $w11_munic==""?$munic:$w11_munic;
 @$w11_uf    = $w11_uf==""?$uf:$w11_uf;
}else{
 $novocgm = "f";
 $w11_numcgm = $z01_numcgm;
 $w11_nome = $z01_nome;
 $w11_ender = $z01_ender;
 $w11_numero = $z01_numero;
 $w11_compl = $z01_compl;
 $w11_bairro = $z01_bairro;
 $w11_munic = $z01_munic;
 $w11_uf = $z01_uf;
 $w11_cep = $z01_cep;
 $w11_cxpostal = $z01_cxpostal;
 $w11_telef = $z01_telef;
 $w11_ident = $z01_ident;
 $w11_login = $z01_login;
 $w11_incest = $z01_incest;
 $w11_telcel = $z01_telcel;
 $w11_email = $z01_email;
 $w11_endcon = $z01_endcon;
 $w11_numcon = $z01_numcon;
 $w11_comcon = $z01_comcon;
 $w11_baicon = $z01_baicon;
 $w11_numcon = $z01_numcon;
 $w11_ufcon = $z01_ufcon;
 $w11_cepcon = $z01_cepcon;
 $w11_cxposcon = $z01_cxposcon;
 $w11_telcon = $z01_telcon;
 $w11_celcon = $z01_celcon;
 $w11_emailc = $z01_emailc;
 $w11_nacion = $z01_nacion;
 $w11_estciv = $z01_estciv;
 $w11_profis = $z01_profis;
 $w11_tipcre = $z01_tipcre;
 $w11_cgccpf = $z01_cgccpf;
 $w11_fax = $z01_fax;
 $w11_nasc = $z01_nasc;
 $w11_mae = $z01_mae;
 $w11_sexo = $z01_sexo;
 $w11_contato = $z01_contato;
 $w11_hora = $z01_hora;
 $w11_nomefanta = $z01_nomefanta;
 $w11_cnh = $z01_cnh;
 $w11_categoria = $z01_categoria;
 $w11_dtemissao = $z01_dtemissao;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" class="texto">
  <tr>
    <td nowrap title="<?=@$Tw11_nome?>">
     Nome:
    </td>
    <td>
<?
db_input('w11_numcgm',10,"numcgm",true,'hidden',"","")
?>
<?
db_input('w11_nome',50,$Iw11_nome,true,'text',"","")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_ender?>">
       <?
       db_ancora("<b>Endereço:</b>","js_pesquisaw11_ender(true);","");
       ?>
    </td>
    <td>
<?
db_input('w11_ender',50,$Iw11_ender,true,'text',"",'');
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_numero?>">
      Número:
    </td>
    <td>
<?
db_input('w11_numero',6,$Iw11_numero,true,'text',"","")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_compl?>">
      Complemento:
    </td>
    <td>
<?
db_input('w11_compl',20,$Iw11_compl,true,'text',"","")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_bairro?>">
       <?
       db_ancora("<b>Bairro:</b>","js_pesquisaw11_bairro(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('w11_bairro',30,$Iw11_bairro,true,'text',"",'');
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_munic?>">
      Municipio:
    </td>
    <td>
<?
db_input('w11_munic',20,$Iw11_munic,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_uf?>">
       UF:
    </td>
    <td>
<?
db_input('w11_uf',2,$Iw11_uf,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_cep?>">
       CEP
    </td>
    <td>
<?
db_input('w11_cep',8,$Iw11_cep,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_cxpostal?>">
      Caixa Postal:
    </td>
    <td>
<?
db_input('w11_cxpostal',20,$Iw11_cxpostal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_telef?>">
      Telefone:
    </td>
    <td>
<?
db_input('w11_telef',12,$Iw11_telef,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_ident?>">
       RG:
    </td>
    <td>
<?
db_input('w11_ident',20,$Iw11_ident,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_incest?>">
      Inscrição Estadual:
    </td>
    <td>
<?
db_input('w11_incest',15,$Iw11_incest,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_telcel?>">
      Celular:
    </td>
    <td>
<?
db_input('w11_telcel',12,$Iw11_telcel,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_email?>">
      Email:
    </td>
    <td>
<?
db_input('w11_email',50,$Iw11_email,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_endcon?>">
      Endereço Comercial:
    </td>
    <td>
<?
db_input('w11_endcon',50,$Iw11_endcon,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_numcon?>">
     Número:
    </td>
    <td>
<?
db_input('w11_numcon',4,$Iw11_numcon,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_comcon?>">
     Complemento:
    </td>
    <td>
<?
db_input('w11_comcon',20,$Iw11_comcon,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_baicon?>">
      Bairro:
    </td>
    <td>
<?
db_input('w11_baicon',20,$Iw11_baicon,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_muncon?>">
      Município:
    </td>
    <td>
<?
db_input('w11_muncon',20,$Iw11_muncon,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_ufcon?>">
      UF:
    </td>
    <td>
<?
db_input('w11_ufcon',2,$Iw11_ufcon,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_cepcon?>">
      CEP:
    </td>
    <td>
<?
db_input('w11_cepcon',8,$Iw11_cepcon,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_cxposcon?>">
      Caixa Postal:
    </td>
    <td>
<?
db_input('w11_cxposcon',20,$Iw11_cxposcon,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_telcon?>">
      Telefone:
    </td>
    <td>
<?
db_input('w11_telcon',12,$Iw11_telcon,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_celcon?>">
      Celular:
    </td>
    <td>
<?
db_input('w11_celcon',12,$Iw11_celcon,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_emailc?>">
      Email:
    </td>
    <td>
<?
db_input('w11_emailc',50,$Iw11_emailc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_nacion?>">
      Nacionalidade:
    </td>
    <td>
<?
$x = array('1'=>'Brasileira','2'=>'Estrangeira');
db_select('w11_nacion',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_estciv?>">
      Estado Civil:
    </td>
    <td>
<?
db_input('w11_estciv',4,$Iw11_estciv,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_profis?>">
      Profissão:
    </td>
    <td>
<?
db_input('w11_profis',40,$Iw11_profis,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_tipcre?>">
      Tipo de Credor:
    </td>
    <td>
<?
$x = array('2'=>'Empresa Privada','1'=>'Empresa Pública');
db_select('w11_tipcre',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_cgccpf?>">
     CNPJ/CPF:
    </td>
    <td>
<?
db_input('w11_cgccpf',14,$Iw11_cgccpf,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_fax?>">
      Fax:
    </td>
    <td>
<?
db_input('w11_fax',12,$Iw11_fax,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_nasc?>">
      Nascimento:
    </td>
    <td>
<?
db_inputdata('w11_nasc',@$w11_nasc_dia,@$w11_nasc_mes,@$w11_nasc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_mae?>">
      Nome da Mãe:
    </td>
    <td>
<?
db_input('w11_mae',40,$Iw11_mae,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_sexo?>">
      Sexo:
    </td>
    <td>
<?
$x = array('M'=>'Masculino','F'=>'Feminino');
db_select('w11_sexo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_contato?>">
      Contato:
    </td>
    <td>
<?
db_input('w11_contato',40,$Iw11_contato,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_nomefanta?>">
     Nome Fantasia:
    </td>
    <td>
<?
db_input('w11_nomefanta',40,$Iw11_nomefanta,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_cnh?>">
     CNH:
    </td>
    <td>
<?
db_input('w11_cnh',20,$Iw11_cnh,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_categoria?>">
     Categoria:
    </td>
    <td>
<?
db_input('w11_categoria',2,$Iw11_categoria,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_dtemissao?>">
     Data Emissão:
    </td>
    <td>
<?
db_inputdata('w11_dtemissao',@$w11_dtemissao_dia,@$w11_dtemissao_mes,@$w11_dtemissao_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_dthabilitacao?>">
     Primeira CNH:
    </td>
    <td>
<?
db_inputdata('w11_dthabilitacao',@$w11_dthabilitacao_dia,@$w11_dthabilitacao_mes,@$w11_dthabilitacao_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_nomecomple?>">
     Nome Completo:
    </td>
    <td>
<?
db_input('w11_nomecomple',50,$Iw11_nomecomple,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw11_dtvencimento?>">
     Vencimento CNH:
    </td>
    <td>
<?
db_inputdata('w11_dtvencimento',@$w11_dtvencimento_dia,@$w11_dtvencimento_mes,@$w11_dtvencimento_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
 <input name="w11_revisado" type="hidden" id="w11_revisado" value="f">
 <input name="w11_cgmnovo" type="hidden" id="w11_cgmnovo" value="<?=$novocgm?>">
 <input name="incluir" type="submit" id="db_opcao" value="Enviar Dados">
</form>
   </td>
  </tr>
  <tr>
    <td align="<?=$DB_align2?>">
      <?=$DB_mens2?>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script>
function js_pesquisaw11_ender(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true);
  }else{
     if(document.form1.w11_ender.value != ''){
        js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.w11_ender.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
     }else{
       document.form1.j14_nome.value = '';
     }
  }
}
function js_mostraruas(chave,erro){
  document.form1.w11_ender.value = chave;
}
function js_mostraruas1(chave1,chave2){
  document.form1.w11_ender.value = chave2;
  db_iframe_ruas.hide();
}
function js_pesquisaw11_bairro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true);
  }else{
     if(document.form1.w11_bairro.value != ''){
        js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.w11_bairro.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
     }else{
       document.form1.j13_descr.value = '';
     }
  }
}
function js_mostrabairro(chave,erro){
 document.form1.w11_bairro.value = chave;
}
function js_mostrabairro1(chave1,chave2){
 document.form1.w11_bairro.value = chave2;
 db_iframe_bairro.hide();
}
function js_pesquisaw11_cep(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_ceplogradouros','func_ceplogradouros.php?funcao_js=parent.js_mostraceplogradouros1|cp06_cep|cp06_codlogradouro','Pesquisa',true);
  }else{
     if(document.form1.w11_cep.value != ''){
        js_OpenJanelaIframe('','db_iframe_ceplogradouros','func_ceplogradouros.php?pesquisa_chave='+document.form1.w11_cep.value+'&funcao_js=parent.js_mostraceplogradouros','Pesquisa',false);
     }else{
       document.form1.cp06_codlogradouro.value = '';
     }
  }
}
function js_mostraceplogradouros(chave,erro){
  document.form1.cp06_codlogradouro.value = chave;
  if(erro==true){
    document.form1.w11_cep.focus();
    document.form1.w11_cep.value = '';
  }
}
function js_mostraceplogradouros1(chave1,chave2){
  document.form1.w11_cep.value = chave1;
  document.form1.cp06_codlogradouro.value = chave2;
  db_iframe_ceplogradouros.hide();
}
</script>
<?}?>