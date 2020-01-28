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
include("dbforms/db_funcoes.php");
include("classes/db_rechumano_classe.php");
include("classes/db_rechumanoescola_classe.php");
include("classes/db_telefonerechumano_classe.php");
include("classes/db_db_uf_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpesdoc_classe.php");
include("classes/db_rhraca_classe.php");
include("classes/db_rhinstrucao_classe.php");
include("classes/db_rhestcivil_classe.php");
include("classes/db_rhnacionalidade_classe.php");
include("classes/db_rhfotos_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrechumano = new cl_rechumano;
$cltelefonerechumano = new cl_telefonerechumano;
$clrechumanoescola = new cl_rechumanoescola;
$cldb_uf = new cl_db_uf;
$clrhpessoal = new cl_rhpessoal;
$clrhpesdoc = new cl_rhpesdoc;
$clrhraca = new cl_rhraca;
$clrhinstrucao = new cl_rhinstrucao;
$clrhestcivil = new cl_rhestcivil;
$clrhnacionalidade = new cl_rhnacionalidade;
$clrhfotos = new cl_rhfotos;
$clrechumano->rotulo->label();
$clrhpessoal->rotulo->label();
$clrhpesdoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("rh06_descr");
$clrotulo->label("rh21_descr");
$clrotulo->label("rh08_descr");
$clrotulo->label("rh18_descr");
$clrotulo->label("rh37_descr");
$clrotulo->label("r70_descr");
$clrotulo->label("r59_descr");
$clrotulo->label("db90_descr");
$clrotulo->label("rh50_oid");
$db_opcao = 22;
$db_opcao1 = 3;
$db_botao = false;
if(isset($chavepesquisa)){
 $escola = db_getsession("DB_coddepto");
 $db_opcao = 2;
 $db_opcao1 = 3;
 $result = $clrechumano->sql_record($clrechumano->sql_query($chavepesquisa));
 db_fieldsmemory($result,0);
 $db_botao = true;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?if(isset($chavepesquisa)){?>
<table border='0' width="97%">
 <tr>
  <td>
   <fieldset><legend align="left"><b>DADOS PESSOAIS</b></legend>
    <table>
     <tr>
      <td nowrap title="<?=@$Trh50_oid?>" rowspan="7" id='fotofunc'>
       <?
       $regist = "";
       if(isset($rh01_numcgm)){
               $regist = $rh01_numcgm;
       }
       ?>
      </td>
      <td nowrap title="<?=@$Trh01_regist?>">
       <b>Matrícula:</b>
      </td>
      <td nowrap>
       <?db_input('rh01_regist',6,@$Irh01_regist,true,'text',3,"");?>
      </td>
      <td nowrap title="<?=@$Trh01_sexo?>">
       <?=@$Lrh01_sexo?>
      </td>
      <td nowrap>
       <?
       $arr_sexo = array('M' => 'Masculino','F'=>'Feminino');
       db_select("rh01_sexo",$arr_sexo,true,3,"");
       ?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Trh01_numcgm?>">
       <?db_ancora(@$Lrh01_numcgm,"js_pesquisarh01_numcgm(true);",3);?>
      </td>
      <td nowrap>
       <?db_input('rh01_numcgm',6,$Irh01_numcgm,true,'text',3,"onchange='js_pesquisarh01_numcgm(false);' tabIndex='1'")?>
       <?db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')?>
      </td>
      <td nowrap title="<?=@$Trh01_raca?>">
       <?db_ancora(@$Lrh01_raca,"js_pesquisarh01_raca(true);",3);?>
      </td>
      <td nowrap>
       <?db_input('rh18_raca',5,@$Irh18_raca,true,'text',3,'')?>
       <?db_input('rh18_descr',20,@$Irh18_descr,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Trh01_instru?>">
       <?db_ancora(@$Lrh01_instru,"js_pesquisarh01_instru(true);",3);?>
      </td>
      <td nowrap>
       <?
       $result_instru = $clrhinstrucao->sql_record($clrhinstrucao->sql_query_file());
       db_selectrecord("rh01_instru",$result_instru,"",3);
       ?>
      </td>
      <td nowrap title="<?=@$Trh01_estciv?>">
       <?db_ancora(@$Lrh01_estciv,"js_pesquisarh01_estciv(true);",3);?>
      </td>
      <td nowrap>
       <?
       $result_estciv = $clrhestcivil->sql_record($clrhestcivil->sql_query_file());
       db_selectrecord("rh01_estciv",$result_estciv,"",3);
       ?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Trh01_nacion?>">
       <?db_ancora(@$Lrh01_nacion,"js_pesquisarh01_nacion(true);",3);?>
      </td>
      <td nowrap>
       <?
       if(!isset($rh01_nacion)){
               $rh01_nacion = 10;
       }
       $result_nacion = $clrhnacionalidade->sql_record($clrhnacionalidade->sql_query_file());
       db_selectrecord("rh01_nacion",$result_nacion,"",3,"","","","","");
       ?>
      </td>
      <td nowrap title="<?=@$Trh01_anoche?>">
       <?=@$Lrh01_anoche?>
      </td>
      <td nowrap>
       <?db_input('rh01_anoche',4,$Irh01_anoche,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Trh01_natura?>">
       <?=@$Lrh01_natura?>
      </td>
      <td nowrap>
       <?db_input('rh01_natura',42,$Irh01_natura,true,'text',3,"")?>
      </td>
      <td nowrap title="<?=@$Trh01_nasc?>">
       <?=@$Lrh01_nasc?>
      </td>
      <td nowrap>
       <?db_inputdata('rh01_nasc',@$rh01_nasc_dia,@$rh01_nasc_mes,@$rh01_nasc_ano,true,'text',3,"")?>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
 <tr>
  <td>
   <fieldset width="95%"><legend align="left"><b>TELEFONES</b></legend>
    <table border="0">
    <?
    $result = $cltelefonerechumano->sql_record($cltelefonerechumano->sql_query("","*","","ed30_i_rechumano = $chavepesquisa"));
    if($cltelefonerechumano->numrows>0){
     for($x=0;$x<$cltelefonerechumano->numrows;$x++){
      db_fieldsmemory($result,$x);
      $ed30_i_ramal = $ed30_i_ramal==0?"":$ed30_i_ramal;
      ?>
      <tr>
       <td nowrap>
        <b>Tipo:</b> <?db_input('ed13_c_descr',20,@$Ied13_c_descr,true,'text',3,"")?>
        <b>Número:</b> <?db_input('ed30_i_numero',20,@$Ied30_i_numero,true,'text',3,"")?>
        <b>Ramal:</b> <?db_input('ed30_i_ramal',20,@$Ied30_i_ramal,true,'text',3,"")?>
       </td>
      </tr>
      <?
     }
    }else{
     ?>
     <tr>
      <td nowrap>Nenhum registro.</td>
     </tr>
     <?
    }
    ?>
    </table>
   </fieldset>
  </td>
 </tr>
 <tr>
  <td>
   <fieldset>
    <legend align="left"><b>DADOS ADMISSIONAIS</b></legend>
    <center>
    <table border="0">
     <tr>
      <td nowrap title="<?=@$Trh01_funcao?>">
       <?db_ancora(@$Lrh01_funcao,"",3);?>
       <?db_input('rh01_funcao',6,$Irh01_funcao,true,'text',3,"")?>
       <?db_input('rh37_descr',40,$Irh37_descr,true,'text',3,'')?>
      </td>
      <td nowrap>
       <?db_ancora(@$Lrh01_lotac,"",3);?>
       <?db_input('rh01_lotac',6,$Irh01_lotac,true,'text',3,"")?>
       <?db_input('r70_descr',40,$Ir70_descr,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Trh01_admiss?>">
       <?=@$Lrh01_admiss?>
       <?db_inputdata('rh01_admiss',@$rh01_admiss_dia,@$rh01_admiss_mes,@$rh01_admiss_ano,true,'text',3,"")?>
      </td>
      <td nowrap title="<?=@$Trh01_tipadm?>">
       <?=@$Lrh01_tipadm?>
       <?
       $h01_tipadm = array(
                           1 => 'Admissao do 1o emprego',
                           2 => 'Admissao c/ emprego anterior',
                           3 => 'Transf de empreg s/ onus p/ a cedente',
                           4 => 'Transf de empreg c/ onus p/ a cedente'
                          );
       db_select("rh01_tipadm",$h01_tipadm,true,3,"");
       ?>
      </td>
     </tr>
    </table>
    </center>
   </fieldset>
  </td>
 </tr>
 <tr>
  <td>
   <fieldset>
    <legend align="left"><b>DOCUMENTOS</b></legend>
    <center>
    <table border="0" width="100%">
     <tr>
      <td nowrap title="<?=@$Trh16_titele?>" width="20%">
       <?=@$Lrh16_titele?>
      </td>
      <td>
       <?db_input('rh16_titele',11,$Irh16_titele,true,'text',3,"")?>
       <?=@$Lrh16_zonael?>
       <?db_input('rh16_zonael',3,$Irh16_zonael,true,'text',3,"")?>
       <?=@$Lrh16_secaoe?>
       <?db_input('rh16_secaoe',4,$Irh16_secaoe,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Trh16_reserv?>">
       <?=@$Lrh16_reserv?>
      </td>
      <td>
       <?db_input('rh16_reserv',15,$Irh16_reserv,true,'text',3,"")?>
       <?=@$Lrh16_catres?>
       <?db_input('rh16_catres',4,$Irh16_catres,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Trh16_ctps_n?>">
       <?=@$Lrh16_ctps_n?>
      </td>
      <td>
       <?db_input('rh16_ctps_n',7,$Irh16_ctps_n,true,'text',3,"")?>
       <?=@$Lrh16_ctps_s?>
       <?db_input('rh16_ctps_s',4,$Irh16_ctps_s,true,'text',3,"")?>
       <?=@$Lrh16_ctps_d?>
       <?db_input('rh16_ctps_d',1,$Irh16_ctps_d,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Trh16_ctps_uf?>">
       <?db_ancora(@$Lrh16_ctps_uf,"",3);?>
      </td>
      <td>
       <?
       $result_uf = $cldb_uf->sql_record($cldb_uf->sql_query_file(null,"db12_codigo as rh16_ctps_uf,db12_uf"));
       db_selectrecord("rh16_ctps_uf",$result_uf,true,3,"","","","0-Nenhum...");
       ?>
       <?=@$Lrh16_pis?>
       <?db_input('rh16_pis',11,$Irh16_pis,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Trh16_carth_n?>">
       <?=@$Lrh16_carth_n?>
      </td>
      <td>
       <?db_input('rh16_carth_n',11,$Irh16_carth_n,true,'text',3,"")?>
       <?=@$Lr16_carth_cat?>
       <?db_input('r16_carth_cat',3,$Ir16_carth_cat,true,'text',3,"")?>
       <?=@$Lrh16_carth_val?>
       <?db_inputdata('rh16_carth_val',@$rh16_carth_val_dia,@$rh16_carth_val_mes,@$rh16_carth_val_ano,true,'text',3,"")?>
      </td>
     </tr>
    </table>
    </center>
  </td>
 </tr>
</table>
<?}?>
</center>
</table>
</body>
</html>