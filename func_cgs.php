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
include("dbforms/db_funcoes.php");
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_POST_VARS);
if(!isset($pesquisar))
   parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcgm = new cl_cgm;
$clrotulo = new rotulocampo;
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");
$clcgm->rotulo->label("z01_cgccpf");
$clrotulo->label("DBtxt30");
$clrotulo->label("DBtxt31");

if(isset($script) && $script != ""){
?>
<script>
<?
  $vals = "";
  $vir = "";
  $camp = split(",",$valores);
  for($f=0;$f<count($camp);$f++){
    $vals .= $vir."'".$camp[$f]."'";
    $vir = ",";
  }
  echo $script."(".$vals.")";
?>
</script>
<?
exit;
}
if(isset($testanome) && !isset($pesquisa_chave)){
  $funmat = split("\|",$funcao_js);
  $func_antes = $funmat[0];
  $valores = "";
  $camp = "";
  $vir = "";
  for($i=1;$i<count($funmat);$i++){
    if($funmat[$i] == "0")
      $funmat[$i] = "z01_numcgm";
    if($funmat[$i] == "1")
      $funmat[$i] = "z01_nome";
    $valores .= "|".$funmat[$i];  
    $camp  .= $vir.$funmat[$i];
    $vir .= ",";
  }
  $funmat[0] = "js_testanome";
  $funcao_js = $funmat[0]."|z01_numcgm|z01_ender|z01_cgccpf".$valores;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_submit_numcgm_buscanome(numcgm){
    document.form_busca_dados.numcgm_busca_dados.value = numcgm;
    document.form_busca_dados.submit();
  }
<?
if(isset($testanome) && !isset($pesquisa_chave)){
?>
  function js_testanome(z01_numcgm,ender,cgccpf,<?=$camp?>){
    alerta = "";
    if(ender == ""){
      alerta += "Endereço\n";
    }
    if(cgccpf == ""){
      alerta += "CPF/CNPJ\n";
    }
    if(alerta != ""){
      alert("O Contribuinte não possui o CGM atualizado")
      <?
      //testa permissao de menu
      
      echo "location.href = 'prot1_cadcgm002.php?chavepesquisa='+z01_numcgm+'&testanome=$func_antes&valores=$valores&funcao_js=".$func_antes.$valores."';";
      ?>
    }else{
      <?=$func_antes."(".$camp.")"?>;
    }
  }
<?
}
?>  
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
       <table width="100%" border="0" cellspacing="0">
        <form name="form2" method="post" action="" >
        <tr> 
          <td align="right">C&oacute;digo: </td>
          <td >
                  <!--input name="numcgmDigitadoParaPesquisa" type="text" id="numcgmDigitadoParaPesquisa" value="<? if (isset($numcgmDigitadoParaPesquisa)){echo $numcgmDigitadoParaPesquisa;} ?>" size="10" maxlength="6"-->
          <?
                  db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',4,"","numcgmDigitadoParaPesquisa");
                  ?>
                  </td>
          <td align="right">&nbsp;<?=$DBtxt30?>: </td>
          <td>
                  <!--input name="nomeDigitadoParaPesquisa" type="text" id="nomeDigitadoParaPesquisa4" value="<? if (isset($nomeDigitadoParaPesquisa)){echo $nomeDigitadoParaPesquisa;} ?>" size="41" maxlength="40"-->
                  <?
                  db_input('z01_cgccpf',20,$Iz01_cgccpf,true,'text',1,"",'cpf');
                  ?>
          </td>
        
        </tr>
        <tr> 
          <td align="right">&nbsp;Nome: </td>
          <td>
                  <!--input name="nomeDigitadoParaPesquisa" type="text" id="nomeDigitadoParaPesquisa4" value="<? if (isset($nomeDigitadoParaPesquisa)){echo $nomeDigitadoParaPesquisa;} ?>" size="41" maxlength="40"-->
                  <?
                  db_input('z01_nome',40,$Iz01_nome,true,'text',4,"",'nomeDigitadoParaPesquisa');
                  ?>
          </td>
          <td align="right">&nbsp;<?=$DBtxt31?>: </td>
          <td>
                  <!--input name="nomeDigitadoParaPesquisa" type="text" id="nomeDigitadoParaPesquisa4" value="<? if (isset($nomeDigitadoParaPesquisa)){echo $nomeDigitadoParaPesquisa;} ?>" size="41" maxlength="40"-->
                  <?
                  db_input('z01_cgccpf',20,$Iz01_cgccpf,true,'text',1,"",'cnpj');
                  ?>
          </td>
        
        
                </tr>
        <tr> 
                
          <td colspan="4" align="center"><br>
                    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
            <input name="limpar" type="button" id="naoencontrado2" value="Limpar" onClick="js_limpa()">
            <input name="Fechar" type="button" id="limpar" value="Fechar" onClick="parent.func_nome.hide();">
            <?
            if(isset($testanome)){
            ?>
            <input name="Incluir" type="button" value="Incluir Novo CGM" onClick="location.href = 'prot1_cadcgm001.php?testanome=<?=$func_antes?>&valores=<?=$valores?>&funcao_js=<?=$func_antes.$valores?>'">
            <script>
            var permissao_parcelamento = <?=db_permissaomenu(db_getsession("DB_anousu"),604,1305)?>;
            if(permissao_parcelamento == false){
              document.form2.Incluir.disabled = true;
            }
            </script>
            <?
            }
            ?>
            </td>
            
        </tr>
                </form>
      </table> 
</td>
<script>
function js_limpa(){
  for(i =0;i < document.form2.elements.length;i++){
    if(document.form2.elements[i].type == 'text'){
      document.form2.elements[i].value = "";
    }
  }
}
</script>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

if(!isset($pesquisa_chave)){

   echo "<script>
         js_limpa();
         document.form2.nomeDigitadoParaPesquisa.focus();
         </script>";


  if(isset($campos)==false){
    $campos = "
      cgm.z01_numcgm, z01_nome,trim(z01_cgccpf) as z01_cgccpf, case when length(trim(z01_cgccpf)) = 14 then 'JURIDICA' else 'FÍSICA' end as tipo, trim(z01_ender) as z01_ender, z01_munic, z01_uf, z01_cep, z01_email,z01_login
    ";
  }
  $clnome = new cl_cgm;
  if (isset($nomeDigitadoParaPesquisa) && ($nomeDigitadoParaPesquisa!="") ){
        $nomeDigitadoParaPesquisa = strtoupper($nomeDigitadoParaPesquisa);
        //$sql = $clnome->sqlnome($nomeDigitadoParaPesquisa,$campos);
        $sql = "select cgs.z01_i_numcgs as z01_numcgm,
                  cgm.z01_nome,
                  cgm.z01_ident,
                  cgm.z01_cgccpf,
                  cgm.z01_cep,
                  cgm.z01_ender,
                  cgm.z01_numero,
                  cgm.z01_compl,
                  cgm.z01_bairro,
                  cgm.z01_nasc,
                  cgm.z01_sexo,
                 'cgm' as tabela
             from CGM
             inner join cgs_cgm on cgs_cgm.z01_i_numcgm = cgm.z01_numcgm
             inner join cgs on cgs.z01_i_numcgs = cgs_cgm.z01_i_cgscgm
            where cgm.z01_nome like '$nomeDigitadoParaPesquisa%'

           union

           select cgs.z01_i_numcgs as z01_numcgm,
                  cgs_und.z01_v_nome as z01_nome,
                  cgs_und.z01_v_ident as z01_ident,
                  cgs_und.z01_v_cgccpf as z01_cgccpf,
                  cgs_und.z01_v_cep as z01_cep,
                  cgs_und.z01_v_ender as z01_ender,
                  cgs_und.z01_i_numero as z01_numero,
                  cgs_und.z01_v_compl as z01_compl,
                  cgs_und.z01_v_bairro as z01_bairro,
                  cgs_und.z01_d_nasc as  z01_nasc,
                  cgs_und.z01_v_sexo as  z01_sexo,
                 'cgs' as tabela
             from CGS_UND
             inner join cgs on cgs.z01_i_numcgs = cgs_und.z01_i_cgsund
            where cgs_und.z01_v_nome like '$nomeDigitadoParaPesquisa%'
           order by z01_nome";
  }else if(isset($numcgmDigitadoParaPesquisa) && $numcgmDigitadoParaPesquisa != ""){
    //$sql = $clnome->sql_query($numcgmDigitadoParaPesquisa,$campos);
    $sql = "select cgs.z01_i_numcgs as z01_numcgm,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_nome
                  else
                    cgs_und.z01_v_nome
                  end as z01_nome,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_ident
                  else
                    cgs_und.z01_v_ident
                  end as z01_ident,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_cgccpf
                  else
                    cgs_und.z01_v_cgccpf
                  end as z01_cgccpf,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_cep
                  else
                    cgs_und.z01_v_cep
                  end as z01_cep,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_ender
                  else
                    cgs_und.z01_v_ender
                  end as z01_ender,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_numero
                  else
                    cgs_und.z01_i_numero
                  end as z01_numero,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_compl
                  else
                    cgs_und.z01_v_compl
                  end as z01_compl,

                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_bairro
                  else
                    cgs_und.z01_v_bairro
                  end as z01_bairro,

                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_nasc
                  else
                    cgs_und.z01_d_nasc
                  end as z01_nasc,

                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_sexo
                  else
                    cgs_und.z01_v_sexo
                  end as z01_sexo
             from CGS
             left join cgs_cgm on cgs_cgm.z01_i_cgscgm = cgs.z01_i_numcgs
             left join cgm     on z01_numcgm = cgs_cgm.z01_i_numcgm
             left join cgs_und on cgs_und.z01_i_cgsund = cgs.z01_i_numcgs
            where cgs.z01_i_numcgs = $numcgmDigitadoParaPesquisa
            order by z01_nome";

  }else if(isset($cpf) && $cpf != ""){
    //$sql = $clnome->sql_query("",$campos,""," z01_cgccpf = '$cpf' ");
    $sql = "select cgs.z01_i_numcgs as z01_numcgm,
                  cgm.z01_nome,
                  cgm.z01_ident,
                  cgm.z01_cgccpf,
                  cgm.z01_cep,
                  cgm.z01_ender,
                  cgm.z01_numero,
                  cgm.z01_compl,
                  cgm.z01_bairro,
                  cgm.z01_nasc,
                  cgm.z01_sexo,
                 'cgm' as tabela
             from CGM
             inner join cgs_cgm on cgs_cgm.z01_i_numcgm = cgm.z01_numcgm
             inner join cgs on cgs.z01_i_numcgs = cgs_cgm.z01_i_cgscgm
            where cgm.z01_cgccpf = '$cpf'

           union

           select cgs.z01_i_numcgs as z01_numcgm,
                  cgs_und.z01_v_nome as z01_nome,
                  cgs_und.z01_v_ident as z01_ident,
                  cgs_und.z01_v_cgccpf as z01_cgccpf,
                  cgs_und.z01_v_cep as z01_cep,
                  cgs_und.z01_v_ender as z01_ender,
                  cgs_und.z01_i_numero as z01_numero,
                  cgs_und.z01_v_compl as z01_compl,
                  cgs_und.z01_v_bairro as z01_bairro,
                  cgs_und.z01_d_nasc as  z01_nasc,
                  cgs_und.z01_v_sexo as  z01_sexo,
                 'cgs' as tabela
             from CGS_UND
             inner join cgs on cgs.z01_i_numcgs = cgs_und.z01_i_cgsund
            where cgs_und.z01_v_cgccpf = '$cpf'
           order by z01_nome";
  }else if(isset($cnpj) && $cnpj != ""){
    //$sql = $clnome->sql_query("",$campos,""," z01_cgccpf = '$cnpj' ");
    $sql = "select cgs.z01_i_numcgs as z01_numcgm,
                  cgm.z01_nome,
                  cgm.z01_ident,
                  cgm.z01_cgccpf,
                  cgm.z01_cep,
                  cgm.z01_ender,
                  cgm.z01_numero,
                  cgm.z01_compl,
                  cgm.z01_bairro,
                  cgm.z01_nasc,
                  cgm.z01_sexo,
                 'cgm' as tabela
             from CGM
             inner join cgs_cgm on cgs_cgm.z01_i_numcgm = cgm.z01_numcgm
             inner join cgs on cgs.z01_i_numcgs = cgs_cgm.z01_i_cgscgm
            where cgm.z01_cgccpf = '$cnpj'

           union

           select cgs.z01_i_numcgs as z01_numcgm,
                  cgs_und.z01_v_nome as z01_nome,
                  cgs_und.z01_v_ident as z01_ident,
                  cgs_und.z01_v_cgccpf as z01_cgccpf,
                  cgs_und.z01_v_cep as z01_cep,
                  cgs_und.z01_v_ender as z01_ender,
                  cgs_und.z01_i_numero as z01_numero,
                  cgs_und.z01_v_compl as z01_compl,
                  cgs_und.z01_v_bairro as z01_bairro,
                  cgs_und.z01_d_nasc as  z01_nasc,
                  cgs_und.z01_v_sexo as  z01_sexo,
                 'cgs' as tabela
             from CGS_UND
             inner join cgs on cgs.z01_i_numcgs = cgs_und.z01_i_cgsund
            where cgs_und.z01_v_cgccpf = '$cnpj'
           order by z01_nome";
  }else{
    $sql = "";   
    if(isset($z01_numcgm) && $z01_numcgm != ""){
      $sql = $clnome->sql_query($z01_numcgm,$campos);
    }
  }
    system("echo \"$sql\" > /tmp/tmp.sql ");
  db_lovrot($sql,14,"()","",$funcao_js);
}else{
  if($pesquisa_chave!=""){

    $sql = "select cgs.z01_i_numcgs as z01_numcgm,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_nome
                  else
                    cgs_und.z01_v_nome
                  end as z01_nome,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_ident
                  else
                    cgs_und.z01_v_ident
                  end as z01_ident,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_cgccpf
                  else
                    cgs_und.z01_v_cgccpf
                  end as z01_cgccpf,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_cep
                  else
                    cgs_und.z01_v_cep
                  end as z01_cep,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_ender
                  else
                    cgs_und.z01_v_ender
                  end as z01_ender,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_numero
                  else
                    cgs_und.z01_i_numero
                  end as z01_numero,
                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_compl
                  else
                    cgs_und.z01_v_compl
                  end as z01_compl,

                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_bairro
                  else
                    cgs_und.z01_v_bairro
                  end as z01_bairro,

                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_nasc
                  else
                    cgs_und.z01_d_nasc
                  end as z01_nasc,

                  case when cgs_und.z01_i_cgsund is null then
                    cgm.z01_sexo
                  else
                    cgs_und.z01_v_sexo
                  end as z01_sexo
             from CGS
             left join cgs_cgm on cgs_cgm.z01_i_cgscgm = cgs.z01_i_numcgs
             left join cgm     on cgm.z01_numcgm = cgs_cgm.z01_i_numcgm
             left join cgs_und on cgs_und.z01_i_cgsund = cgs.z01_i_numcgs
            where cgs.z01_i_numcgs = $pesquisa_chave
            order by z01_nome";
           
    //$result = $clcgm->sql_record($clcgm->sql_query($pesquisa_chave));
    system("echo \"$sql\" > /tmp/tmp.sql ");

    $result = $clcgm->sql_record($sql);

    if(!isset($testanome)){ 
      if(($result!=false) && (pg_numrows($result) != 0)){
         db_fieldsmemory($result,0);
         echo "<script>".$funcao_js."(false,\"$z01_nome\");</script>";
      }else{
         echo "<script>".$funcao_js."(true,'Código (".$z01_numcgm.") não Encontrado');</script>";
      }
    }else{
      if(($result!=false) && (pg_numrows($result) != 0)){
        db_fieldsmemory($result,0);
        echo "<script>\n";
          if($z01_ender == '' || $z01_cgccpf == ''){
            echo "alert('Contribuinte com o CGM desatualizado')\n
            ".$funcao_js."(true,'Contribuinte com o CGM desatualizado');\n";
          }else{
            echo "".$funcao_js."(false,\"$z01_nome\");\n";
          }
        echo"</script>\n";
      }else{
        //echo "<script> alert('Código (".$pesquisa_chave.") não Encontrado')</script>";
        echo "<script>".$funcao_js."(true,'Código (".$pesquisa_chave.") não Encontrado');</script>\n";
      }
    }
  }
}
  ?>
    </td>
  </tr>
</table>
</body>
</html>