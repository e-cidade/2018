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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cgm_classe.php");
include("dbforms/db_funcoes.php");
$clcgm = new cl_cgm;
$clcgm->rotulo->label();
$db_opcao = 3;
if(isset($chavepesquisa) && $chavepesquisa!="null"){
 $result = $clcgm->sql_record($clcgm->sql_query($chavepesquisa));
 db_fieldsmemory($result,0);
 ?>
 <html>
 <head>
 <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 <meta http-equiv="Expires" CONTENT="0">
 <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
 <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
 <table border="1" cellspacing="0" cellpading="0" width="100%">
  <tr>
   <td nowrap title="<?=@$Tz01_cgccpf?>">
    <?=@$Lz01_cgccpf?>
    <?db_input('z01_cgccpf',15,@$Iz01_cgccpf,true,'text',3,"");?>
    <?if(strlen($z01_cgccpf)==11 || $z01_cgccpf==""){?>
     <?=@$Lz01_ident?>
     <?db_input('z01_ident',15,$Iz01_ident,true,'text',3);?>
    <?}?>
   </td>
   <td align="left">
    &nbsp;
   </td>
  </tr>
  <tr align="left" valign="top">
   <?if(strlen($z01_cgccpf)==11 || $z01_cgccpf==""){?>
   <td>
    <table width="50%" border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td width="27%" title='<?=$Tz01_numcgm?>' nowrap>
       <?=$Lz01_numcgm?>
      </td>
      <td width="73%" nowrap>
       <?db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title=<?=@$Tz01_nome?>>
       <?=@$Lz01_nome?>
      </td>
      <td nowrap title="<?=@$Tz01_nome?>">
       <?db_input('z01_nome',40,$Iz01_nome,true,'text',3,"");?>
      </td>
     </tr>
     <tr>
      <td nowrap title=<?=@$Tz01_pai?>>
       <?=@$Lz01_pai?>
      </td>
      <td nowrap title="<?=@$Tz01_pai?>">
       <?db_input('z01_pai',40,$Iz01_pai,true,'text',3,"");?>
      </td>
     </tr>
     <tr>
      <td nowrap title=<?=@$Tz01_mae?>>
       <?=@$Lz01_mae?>
      </td>
      <td nowrap title="<?=@$Tz01_mae?>">
       <?db_input('z01_mae',40,$Iz01_mae,true,'text',3,"");?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=$Tz01_nasc?>">
       <?=$Lz01_nasc?>
      </td>
      <td nowrap title="<?=$Tz01_nasc?>">
       <?db_inputdata('z01_nasc',@$z01_nasc_dia,@$z01_nasc_mes,@$z01_nasc_ano,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=$Tz01_estciv?>">
       <?=$Lz01_estciv?>
      </td>
      <td nowrap title="<?=$Tz01_estciv?>">
       <?
       $x = array("1"=>"Solteiro","2"=>"Casado","3"=>"Viúvo","4"=>"Divorciado");
       db_select('z01_estciv',$x,true,3);
       ?>
       <?=$Lz01_sexo?>
       <?
       $sex = array("M"=>"Masculino","F"=>"Feminino");
       db_select('z01_sexo',$sex,true,3);
       ?>
      </td>
     </tr>
    </table>
   </td>
   <td>
    <table width="50%" border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td width="27%" title="<?=$Tz01_profis?>" nowrap>
       <?=$Lz01_profis?>
      </td>
      <td nowrap>
       <?db_input('z01_profis',40,$Iz01_profis,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=$Tz01_nacion?>">
       <?=$Lz01_nacion?>
      </td>
      <td nowrap title="<?=$Tz01_nacion?>">
       <?
       $x = array("1"=>"Brasileira","2"=>"Estrangeira");
       db_select('z01_nacion',$x,true,3);
       ?>
      </td>
     </tr>
     <tr>
      <td nowrap title=<?=@$Tz01_cnh?>>
       <?=@$Lz01_cnh?>
      </td>
      <td nowrap title="<?=@$Tz01_cnh?>">
       <?db_input('z01_cnh',15,$Iz01_cnh,true,'text',3,"");?>
       <?=@$Lz01_categoria?>
       <?
       $y = array(""=>"","A"=>"A","B"=>"B","C"=>"C","D"=>"D","E"=>"E","AB"=>"AB","AC"=>"AC","AD"=>"AD","AE"=>"AE");
       db_select('z01_categoria',$y,true,3);
       ?>
      </td>
     </tr>
     <tr>
      <td nowrap title=<?=@$Tz01_dtemissao?>>
       <?=@$Lz01_dtemissao?>
      </td>
      <td nowrap title="<?=@$Tz01_dtemissao?>">
       <?db_inputdata('z01_dtemissao',@$z01_dtemissao_dia,@$z01_dtemissao_mes,@$z01_dtemissao_ano,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title=<?=@$Tz01_dthabilitacao?>>
       <?=@$Lz01_dthabilitacao?>
      </td>
      <td nowrap title="<?=@$Tz01_dthabilitacao?>">
       <?db_inputdata('z01_dthabilitacao',@$z01_dthabilitacao_dia,@$z01_dthabilitacao_mes,@$z01_dthabilitacao_ano,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title=<?=@$Tz01_dtvencimento?>>
       <?=@$Lz01_dtvencimento?>
      </td>
      <td nowrap title="<?=@$Tz01_dtvencimento?>">
       <?db_inputdata('z01_dtvencimento',@$z01_dtvencimento_dia,@$z01_dtvencimento_mes,@$z01_dtvencimento_ano,true,'text',3);?>
      </td>
     </tr>
    </table>
   <?}else{?>
   <td colspan="2">
    <table width="50%" border="0" cellspacing="3" cellpadding="0">
     <tr>
      <td width="27%" title='<?=$Tz01_numcgm?>' nowrap>
       <?=$Lz01_numcgm?>
      </td>
      <td width="73%" nowrap>
       <?db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',3);?>
      </td>
      <td nowrap title=<?=@$Tz01_nome?>>
        <?=@$Lz01_nome?>
      </td>
      <td nowrap title="<?=@$Tz01_nome?>" colspan="2">
       <?db_input('z01_nome',40,$Iz01_nome,true,'text',$db_opcao,"");?>
      </td>
     </tr>
     <tr>
      <td nowrap title=<?=@$Tz01_nomecomple?>>
       <?=@$Lz01_nomecomple?>
      </td>
      <td nowrap title="<?=@$Tz01_nomecomple?>" colspan=4 >
       <?db_input('z01_nomecomple',80,$Iz01_nomecomple,true,'text',$db_opcao,"");?>
      </td>
      <td></td>
      <td></td>
     </tr>
     <tr>
      <td nowrap title="<?=$Tz01_tipcre?>">
       <?=$Lz01_tipcre?>
      </td>
      <td nowrap>
       <?
       $x = array("2"=>"Empresa Privada","1"=>"Empresa Pública");
       db_select('z01_tipcre',$x,true,$db_opcao);
       ?>
      </td>
      <td nowrap title=<?=@$Tz01_contato?>>
       <?=@$Lz01_contato?>
      </td>
      <td nowrap title="<?=@$Tz01_contato?>"  >
       <?db_input('z01_contato',40,$Iz01_contato,true,'text',$db_opcao,"");?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_incest?>">
       <?=@$Lz01_incest?>
      </td>
      <td nowrap>
       <?db_input('z01_incest',15,$Iz01_incest,true,'text',$db_opcao);?>
      </td>
      <td nowrap title=<?=@$Tz01_nomefanta?>>
       <?=@$Lz01_nomefanta?>
      </td>
      <td nowrap title="<?=@$Tz01_nomefanta?>"  >
       <?db_input('z01_nomefanta',40,$Iz01_nomefanta,true,'text',$db_opcao,"");?>
      </td>
     </tr>
    </table>
   <?}?>
   </td>
  </tr>
  <tr>
   <td width="50%" valign="top">
    <table>
     <tr>
      <td nowrap title="<?=@$Tz01_cep?>">
       <?=@$Lz01_cep?>
      </td>
      <td nowrap>
       <?db_input('z01_cep',9,$Iz01_cep,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_ender?>">
       <?=@$Lz01_ender?>
      </td>
      <td nowrap>
       <?
       db_input('z01_ender',40,$Iz01_ender,true,'text',3);
       ?>
      </td>
     </tr>
     <tr>
      <td width="29%" nowrap title="<?=@$Tz01_numero?>">
       <?=@$Lz01_numero?>
      </td>
      <td width="71%" nowrap  ><a name="AN3">
       <?db_input('z01_numero',8,$Iz01_numero,true,'text',3);?>
       &nbsp;
       <?=@$Lz01_compl?>
       <?db_input('z01_compl',10,$Iz01_compl,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_munic?>">
       <?=@$Lz01_munic?>
      </td>
      <td nowrap colspan=4>
       <?db_input('z01_munic',20,$Iz01_munic,true,'text',3);?>
       <?=@$Lz01_uf?>
       <?db_input('z01_uf',2,$Iz01_uf,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_bairro?>">
       <?=@$Lz01_bairro?>
      </td>
      <td nowrap>
       <?db_input('z01_bairro',25,$Iz01_bairro,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_telef?>">
       <?=@$Lz01_telef?>
      </td>
      <td nowrap>
       <?db_input('z01_telef',12,$Iz01_telef,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_fax?>">
       <?=@$Lz01_fax?>
      </td>
      <td nowrap>
       <?db_input('z01_fax',12,$Iz01_fax,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_telcel?>">
       <?=@$Lz01_telcel?>
      </td>
      <td nowrap>
       <?db_input('z01_telcel',12,$Iz01_telcel,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_email?>">
       <?=@$Lz01_email?>
      </td>
      <td nowrap>
       <?db_input('z01_email',30,$Iz01_email,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_cxpostal?>">
       <?=@$Lz01_cxpostal?>
      </td>
      <td nowrap>
       <?db_input('z01_cxpostal',10,$Iz01_cxpostal,true,'text',3);?>
      </td>
     </tr>
    </table>
   </td>
   <td width="50%" valign="top">
    <table>
     <tr>
      <td nowrap title="<?=@$Tz01_cepcon?>">
       <?=@$Lz01_cepcon?>
      </td>
      <td nowrap>
       <?db_input('z01_cepcon',9,$Iz01_cepcon,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_endcon?>">
       <?=@$Lz01_endcon?>
      </td>
      <td nowrap>
       <?db_input('z01_endcon',40,$Iz01_endcon,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td width="29%" nowrap title="<?=@$Tz01_numcon?>">
       <?=@$Lz01_numcon?>
      </td>
      <td width="71%" nowrap >
       <?db_input('z01_numcon',8,$Iz01_numcon,true,'text',3);?>
       <?=@$Lz01_comcon?>
       <?db_input('z01_comcon',10,$Iz01_comcon,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_muncon?>">
       <?=@$Lz01_muncon?>
      </td>
      <td nowrap>
       <?db_input('z01_muncon',20,$Iz01_muncon,true,'text',3);?>
       <?echo "<b>UF:</b>"?>
       <?db_input('z01_ufcon',2,$Iz01_ufcon,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_baicon?>">
       <?=@$Lz01_baicon?>
      </td>
      <td nowrap>
       <?db_input('z01_baicon',25,$Iz01_baicon,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_telcon?>">
       <?=@$Lz01_telcon?>
      </td>
      <td nowrap>
       <?db_input('z01_telcon',12,$Iz01_telcon,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_celcon?>">
       <?=@$Lz01_celcon?>
      </td>
      <td nowrap>
       <?db_input('z01_celcon',12,$Iz01_celcon,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_emailc?>">
       <?=@$Lz01_emailc?>
      </td>
      <td nowrap>
       <?db_input('z01_emailc',30,$Iz01_emailc,true,'text',3);?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Tz01_cxposcon?>">
       <?=@$Lz01_cxposcon?>
      </td>
      <td nowrap>
       <?db_input('z01_cxposcon',10,$Iz01_cxposcon,true,'text',3);?>
      </td>
     </tr>
    </table>
   </td>
  </tr>
  <tr align="left" valign="middle">
   <td height="21" colspan="2" nowrap>
    <table border="0" width="100%">
     <tr nowrap>
      <td nowrap>
       <?=@$Lz01_cadast?>
       <?db_inputdata('z01_cadast',@$z01_cadast_dia,@$z01_cadast_mes,@$z01_cadast_ano,true,'text',3);?>
      </td>
      <td nowrap>
       <?=@$Lz01_ultalt?>
       <?db_inputdata('z01_ultalt',@$z01_ultalt_dia,@$z01_ultalt_mes,@$z01_ultalt_ano,true,'text',3);?>
      </td>
     </tr>
    </table>
   </td>
  </tr>
 <table>
 <script>
  <?if(strlen($z01_cgccpf)==11 || $z01_cgccpf==""){?>
   parent.document.form1.pessoa.value = "FÍSICA";
  <?}else{?>
   parent.document.form1.pessoa.value = "JURÍDICA";
  <?}?>
 </script>
<?}?>
</body>
</html>