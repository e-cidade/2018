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
include("classes/db_db_cgmatualiza_classe.php");
include("classes/db_db_cgmatualizaliga_classe.php");
include("classes/db_cgm_classe.php");
$cldb_cgmatualiza = new cl_db_cgmatualiza;
$cldb_cgmatualizaliga = new cl_db_cgmatualizaliga;
$cl_cgm = new cl_cgm;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function MD() {
  if(document.form1.marca.value == 'D') {
    var check = false;
        document.form1.marca.value = "M";
  } else {
    var check = true;
        document.form1.marca.value = "D";
  }
  for(i = 0;i < document.form1.elements.length;i++) {
    if(document.form1.elements[i].type == "checkbox") {
          document.form1.elements[i].checked = check;
        }
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
-->
</style>

</head>
<body bgcolor="#CCCCCC" bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <?
  if(isset($HTTP_POST_VARS["numcgm"])) {
        $cgccpf = @$HTTP_POST_VARS["ck_cgccpf"]=="1"?@$HTTP_POST_VARS["cgccpf"]:@$HTTP_POST_VARS["z01_cgccpf"];
        $nome = @$HTTP_POST_VARS["ck_nome"]=="1"?@$HTTP_POST_VARS["nome"]:@$HTTP_POST_VARS["z01_nome"];
        $ender = @$HTTP_POST_VARS["ck_ender"]=="1"?@$HTTP_POST_VARS["ender"]:@$HTTP_POST_VARS["z01_ender"];
        $munic = @$HTTP_POST_VARS["ck_munic"]=="1"?@$HTTP_POST_VARS["munic"]:@$HTTP_POST_VARS["z01_munic"];
        $uf = @$HTTP_POST_VARS["ck_uf"]=="1"?@$HTTP_POST_VARS["uf"]:@$HTTP_POST_VARS["z01_uf"];
        $cep = @$HTTP_POST_VARS["ck_cep"]=="1"?@$HTTP_POST_VARS["cep"]:@$HTTP_POST_VARS["z01_cep"];
        $telef = @$HTTP_POST_VARS["ck_telef"]=="1"?@$HTTP_POST_VARS["telef"]:@$HTTP_POST_VARS["z01_telef"];
        $ident = @$HTTP_POST_VARS["ck_ident"]=="1"?@$HTTP_POST_VARS["ident"]:@$HTTP_POST_VARS["z01_ident"];
        $bairro = @$HTTP_POST_VARS["ck_bairro"]=="1"?@$HTTP_POST_VARS["bairro"]:@$HTTP_POST_VARS["z01_bairro"];
        $incest = @$HTTP_POST_VARS["ck_incest"]=="1"?@$HTTP_POST_VARS["incest"]:@$HTTP_POST_VARS["z01_incest"];
        $telcel = @$HTTP_POST_VARS["ck_telcel"]=="1"?@$HTTP_POST_VARS["telcel"]:@$HTTP_POST_VARS["z01_telcel"];
        $email = @$HTTP_POST_VARS["ck_email"]=="1"?@$HTTP_POST_VARS["email"]:@$HTTP_POST_VARS["z01_email"];
        $endcon = @$HTTP_POST_VARS["ck_endcon"]=="1"?@$HTTP_POST_VARS["endcon"]:@$HTTP_POST_VARS["z01_endcon"];
        $muncon = @$HTTP_POST_VARS["ck_muncon"]=="1"?@$HTTP_POST_VARS["muncon"]:@$HTTP_POST_VARS["z01_muncon"];
        $baicon = @$HTTP_POST_VARS["ck_baicon"]=="1"?@$HTTP_POST_VARS["baicon"]:@$HTTP_POST_VARS["z01_baicon"];
        $ufcon = @$HTTP_POST_VARS["ck_ufcon"]=="1"?@$HTTP_POST_VARS["ufcon"]:@$HTTP_POST_VARS["z01_ufcon"];
        $cepcon = @$HTTP_POST_VARS["ck_cepcon"]=="1"?@$HTTP_POST_VARS["cepcon"]:@$HTTP_POST_VARS["z01_cepcon"];
        $telcon = @$HTTP_POST_VARS["ck_telcon"]=="1"?@$HTTP_POST_VARS["telcon"]:@$HTTP_POST_VARS["z01_telcon"];
        $celcon = @$HTTP_POST_VARS["ck_celcon"]=="1"?@$HTTP_POST_VARS["celcon"]:@$HTTP_POST_VARS["z01_celcon"];
        $emailc = @$HTTP_POST_VARS["ck_emailc"]=="1"?@$HTTP_POST_VARS["emailc"]:@$HTTP_POST_VARS["z01_emailc"];

        if(!empty($numcgm)){
         $result = pg_exec("UPDATE db_cgmatualiza SET w11_revisado = 't' WHERE w11_sequencial = $retorno") or die("Erro(53) atualizando tabela db_cgmatualiza");
         if(pg_cmdtuples($result) <= 0)
          db_erro("Erro atualizando tabela db_cgmatualiza.");
           $sql = "UPDATE cgm SET
                       z01_cgccpf = '$cgccpf',
                       z01_nome = '$nome',
                       z01_ender = '$ender',
                       z01_munic = '$munic',
                       z01_uf = '$uf',
                       z01_cep = '$cep',
                       z01_telef = '$telef',
                       z01_ident = '$ident',
                       z01_bairro = '$bairro',
                       z01_incest = '$incest',
                       z01_telcel = '$telcel',
                       z01_email = '$email',
                       z01_endcon = '$endcon',
                       z01_muncon = '$muncon',
                       z01_baicon = '$baicon',
                       z01_ufcon = '$ufcon',
                       z01_cepcon = '$cepcon',
                       z01_telcon = '$telcon',
                       z01_celcon = '$celcon',
                       z01_emailc = '$emailc'
                   WHERE z01_numcgm = $retorno";
         $result = pg_query($sql) or die("Erro atualizando CGM $retorno.");
         $email_usu = $email;
         $msg = "Seus dados do CGM foram atualizados. Utilize seu login e senha para acessar suas informações.";
        }else{
         ///cria novo cgm
         $cl_cgm->z01_cgccpf = $cgccpf;
         $cl_cgm->z01_nome   = $nome;
         $cl_cgm->z01_ender  = $ender;
         $cl_cgm->z01_munic  = $munic;
         $cl_cgm->z01_uf     = $uf;
         $cl_cgm->z01_cep    = $cep;
         $cl_cgm->z01_telef  = $telef;
         $cl_cgm->z01_ident  = $ident;
         $cl_cgm->z01_bairro = $bairro;
         $cl_cgm->z01_incest = $incest;
         $cl_cgm->z01_telcel = $telcel;
         $cl_cgm->z01_email  = $email;
         $cl_cgm->z01_endcon = $endcon;
         $cl_cgm->z01_muncon = $muncon;
         $cl_cgm->z01_baicon = $baicon;
         $cl_cgm->z01_ufcon  = $ufcon;
         $cl_cgm->z01_cepcon = $cepcon;
         $cl_cgm->z01_telcon = $telcon;
         $cl_cgm->z01_celcon = $celcon;
         $cl_cgm->z01_emailc = $emailc;
         $cl_cgm->incluir(null);
         $numcgm = $cl_cgm->z01_numcgm;
         @$cl_cgm->erro();
         $email_usu = $cl_cgm->z01_email;
         ///insert em db_cgmatualizaliga
         $result = pg_exec("UPDATE db_cgmatualiza SET w11_revisado = 't' WHERE w11_sequencial = $retorno") or die("Erro(54) atualizando tabela db_cgmatualiza no insert");
         ///grava db_cgmatualizaliga
         $cldb_cgmatualizaliga->w12_cgmatualiza = $retorno;
         $cldb_cgmatualizaliga->w12_numcgm      = $numcgm;
         $cldb_cgmatualizaliga->incluir(null);
         if($cl_cgm->erro_status=="0"){
          @$cl_cgm->erro();
          $msg = "Não foi possível aceitar seu pedido de CGM. Entre em contato com a Prefeitura.";
         }else{
          $msg = "Seus dados do CGM foram aceitos. Faça pedido de senha no Portal Prefeitura On-line.";
         }
   }
   ///buscar dados da prefeitura
   $result = pg_exec("select * from db_config order by codigo")or die("Erro buscando dados da prefeitura.");
   db_fieldsmemory($result,0);
   /////
   //encaminhar email
   $mensagemDestinatario = "
   $nomeinst
   Atualização/Pedido de CGM - Prefeitura On-Line
   ----------------------------
   Nome:     $nome
   CPF/CNPJ: $cgccpf
   E-mail:   $email_usu

   ".date("d/m/Y - H:i:s")." - ".getenv("REMOTE_ADDR")."
   $msg

   $url

   Não responda este e-mail, ele foi gerado automaticamente pelo Servidor.
   ----------------------------
   ";
   $headers   = "Content-Type:text\n Bcc: $email";
   $enviando  = mail($email_usu,"Prefeitura On-Line - Atualização/Pedido de CGM",$mensagemDestinatario,$headers) or die("Erro enviando e-mail.");
   db_redireciona("pre4_atuend002.php");
   ////////
 }elseif(isset($retorno)){
        $sql = "SELECT * FROM db_cgmatualiza
                 LEFT JOIN db_cgmatualizaliga on w12_cgmatualiza = w11_sequencial
                 LEFT JOIN cgm on z01_numcgm = w12_numcgm
                WHERE w11_sequencial = $retorno
                  and w11_revisado = 'f'
               ";
        $result = pg_exec($sql);
        if(pg_numrows($result) > 0)
          db_fieldsmemory($result,0);
        else
          db_erro("Erro no select da tabela db_cgmatualizaliga.");
        /*
        $result = pg_exec("SELECT * FROM cgm WHERE w11_sequencial = $retorno and w11_revisado = 'f'");
        if(pg_numrows($result) > 0)
          db_fieldsmemory($result,0);
        else
          db_erro("Erro no select da tabela cgm.");
        */
?>
<center>
  <form name="form1" method="post">
    <table width="80%" border="1" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="3%" align="center" valign="middle">&nbsp;</td>
        <td height="30" colspan="2"> <div align="center"><b><u>Solicita&ccedil;&atilde;o do 
            Contribuinte</u></b></div></td>
        <td height="30" colspan="2"> <div align="center"><b><u>Cadastro do Sistema</u></b></div></td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="button" name="marca" value="D" onclick="MD()" style="font-weight: bold"> 
        </td>
        <td width="16%" nowrap><b>Numcgm:</b></td>
        <td width="28%"> <input type="text" name="numcgm" size="6" value="<?=@$w12_numcgm?>" readonly>
        </td>
        <td width="16%" nowrap><b>&nbsp;Numcgm:</b></td>
        <td width="28%"> <input type="text" name="z01_numcgm" size="6" value="<?=@$z01_numcgm?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_cgccpf" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Cgccpf:</b></td>
        <td width="28%"> <input type="text" name="cgccpf" size="14" maxlength="14" value="<?=@$w11_cgccpf?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Cgccpf:</b></td>
        <td width="28%"> <input type="text" name="z01_cgccpf" size="14" maxlength="14" value="<?=@$z01_cgccpf?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_nome" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Nome:</b></td>
        <td width="28%"> <input type="text" name="nome" size="40" maxlength="40" value="<?=@$w11_nome?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Nome:</b></td>
        <td width="28%"> <input type="text" name="z01_nome" size="40" maxlength="40" value="<?=@$z01_nome?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_ender" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Endere&ccedil;o:</b></td>
        <td width="28%"> <input type="text" name="ender" size="40" maxlength="40" value="<?=@$w11_ender?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Endere&ccedil;o:</b></td>
        <td width="28%"> <input type="text" name="z01_ender" size="40" maxlength="40" value="<?=@$z01_ender?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_munic" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Munic&iacute;pio:</b></td>
        <td width="28%"> <input type="text" name="munic" size="20" maxlength="20" value="<?=@$w11_munic?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Munic&iacute;pio:</b></td>
        <td width="28%"> <input type="text" name="z01_munic" size="20" maxlength="20" value="<?=@$z01_munic?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_uf" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>UF:</b></td>
        <td width="28%"> <input type="text" name="uf" size="2" maxlength="2" value="<?=@$w11_uf?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;UF:</b></td>
        <td width="28%"> <input type="text" name="z01_uf" size="2" maxlength="2" value="<?=@$z01_uf?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_cep" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>CEP:</b></td>
        <td width="28%"> <input type="text" name="cep" size="8" maxlength="8" value="<?=@$w11_cep?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;CEP:</b></td>
        <td width="28%"> <input type="text" name="z01_cep" size="8" maxlength="8" value="<?=@$z01_cep?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_telef" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Telefone:</b></td>
        <td width="28%"> <input type="text" name="telef" size="12" maxlength="12" value="<?=@$w11_telef?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Telefone:</b></td>
        <td width="28%"> <input type="text" name="z01_telef" size="12" maxlength="12" value="<?=@$z01_telef?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_ident" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Identidade No.</b></td>
        <td width="28%"> <input type="text" name="ident" size="15" maxlength="15" value="<?=@$w11_ident?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Identidade No.</b></td>
        <td width="28%"> <input type="text" name="z01_ident" size="15" maxlength="15" value="<?=@$z01_ident?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_bairro" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Bairro:</b></td>
        <td width="28%"> <input type="text" name="bairro" size="20" maxlength="20" value="<?=@$w11_bairro?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Bairro:</b></td>
        <td width="28%"> <input type="text" name="z01_bairro" size="20" maxlength="20" value="<?=@$z01_bairro?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_incest" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Inscri&ccedil;&atilde;o Estadual:</b></td>
        <td width="28%"> <input type="text" name="incest" size="15" maxlength="15" value="<?=@$w11_incest?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Inscri&ccedil;&atilde;o Estadual:</b></td>
        <td width="28%"> <input type="text" name="z01_incest" size="15" maxlength="15" value="<?=@$z01_incest?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_telcel" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Telefone Celular:</b></td>
        <td width="28%"> <input type="text" name="telcel" size="12" maxlength="12" value="<?=@$w11_telcel?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Telefone Celular:</b></td>
        <td width="28%"> <input type="text" name="z01_telcel" size="12" maxlength="12" value="<?=@$z01_telcel?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_email" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>email:</b></td>
        <td width="28%"> <input type="text" name="email" size="30" maxlength="30" value="<?=@$w11_email?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;email:</b></td>
        <td width="28%"> <input type="text" name="z01_email" size="30" maxlength="30" value="<?=@$z01_email?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_endcon" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Endere&ccedil;o Contato:</b></td>
        <td width="28%"> <input type="text" name="endcon" size="40" maxlength="40" value="<?=@$w11_endcon?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Endere&ccedil;o Contato:</b></td>
        <td width="28%"> <input type="text" name="z01_endcon" size="40" maxlength="40" value="<?=@$z01_endcon?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_muncon" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Munic&iacute;pio Contato:</b></td>
        <td width="28%"> <input type="text" name="muncon" size="20" maxlength="20" value="<?=@$w11_muncon?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Munic&iacute;pio Contato:</b></td>
        <td width="28%"> <input type="text" name="z01_muncon" size="20" maxlength="20" value="<?=@$z01_muncon?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_baicon" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Bairro Contato</b></td>
        <td width="28%"> <input type="text" name="baicon" size="20" maxlength="20" value="<?=@$w11_baicon?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Bairro Contato</b></td>
        <td width="28%"> <input type="text" name="z01_baicon" size="20" maxlength="20" value="<?=@$z01_baicon?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_ufcon" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>UF Contato:</b></td>
        <td width="28%"> <input type="text" name="ufcon" size="2" maxlength="2" value="<?=@$w11_ufcon?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;UF Contato:</b></td>
        <td width="28%"> <input type="text" name="z01_ufcon" size="2" maxlength="2" value="<?=@$z01_ufcon?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_cepcon" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>CEP Contato:</b></td>
        <td width="28%"> <input type="text" name="cepcon" size="8" maxlength="8" value="<?=@$w11_cepcon?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;CEP Contato:</b></td>
        <td width="28%"> <input type="text" name="z01_cepcon" size="8" maxlength="8" value="<?=@$z01_cepcon?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input name="ck_telcon" type="checkbox" id="ck_telcon" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Telefone Contato:</b></td>
        <td width="28%"> <input type="text" name="telcon" size="12" maxlength="12" value="<?=@$w11_telcon?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Telefone Contato:</b></td>
        <td width="28%"> <input type="text" name="z01_telcon" size="12" maxlength="12" value="<?=@$z01_telcon?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input name="ck_celcon" type="checkbox" id="ck_celcon" value="1" checked> 
        </td>
        <td width="16%" nowrap><b>Celular Contato:</b></td>
        <td width="28%"> <input type="text" name="celcon" size="12" maxlength="12" value="<?=@$w11_celcon?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;Celular Contato:</b></td>
        <td width="28%"> <input type="text" name="z01_celcon" size="12" maxlength="12" value="<?=@$z01_celcon?>" readonly> 
        </td>
      </tr>
      <tr> 
        <td width="3%" align="center" valign="middle"> <input type="checkbox" name="ck_emailc" value="1" checked> 
        </td>
        <td width="16%"><b>email Contato:</b></td>
        <td width="28%"> <input type="text" name="emailc" size="30" maxlength="30" value="<?=@$w11_emailc?>">
        </td>
        <td width="16%" nowrap><b>&nbsp;email Contato:</b></td>
        <td width="28%"> <input type="text" name="z01_emailc" size="30" maxlength="30" value="<?=@$z01_emailc?>" readonly> 
        </td>
      </tr>
    </table>
  </form>
</center>
<script>
parent.document.getElementById("atualizar").disabled = false;
</script>
<?
} else {
  $query = "SELECT w11_sequencial as seq,w11_nome,substr(w11_cgccpf,1,3)||'.'||substr(w11_cgccpf,4,3)||'.'||substr(w11_cgccpf,7,3)||'/'||substr(w11_cgccpf,10,2) as cgccpf,w11_ender as endereço,w11_bairro,substr(w11_cep,1,2)||'.'||substr(w11_cep,3,3)||'-'||substr(w11_cep,6,3) as cep FROM db_cgmatualiza WHERE w11_revisado = 'f' ORDER BY w11_nome";
  echo "<center><Br><Br>\n";
  db_lov($query,100,"pre4_atuend002.php");
  echo "</center>\n";
}
?>           
</body>
</html>