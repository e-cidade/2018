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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
// classes
include ("classes/db_mobimportacao_classe.php");
include ("classes/db_moblevantamento_classe.php");
include ("classes/db_moblevantamentolog_classe.php");
include ("classes/db_moblevantamentoedi_classe.php");

$clmobimportacao      = new cl_mobimportacao;
$clmoblevantamento    = new cl_moblevantamento;
$clmoblevantamentolog = new cl_moblevantamentolog;
$clmoblevantamentoedi = new cl_moblevantamentoedi;


db_postmemory($HTTP_POST_VARS);

 
if( isset($importa) ) {


  $erro = false;

  db_postmemory($_FILES);

  //print_r($matriculas);
  //print_r($testadas);
  //print_r($edificacoes);
  db_inicio_transacao();

  
  $clmobimportacao->j95_data = date("Y-m-d",db_getsession("DB_datausu"));
  $clmobimportacao->j95_idusuario = db_getsession("DB_id_usuario");
  $clmobimportacao->incluir(0);

  if($clmobimportacao->erro_status == 0 ){
    $erro = true;
    $msg = $clmobimportacao->erro_msg;
  }
  
  /*
  $sql = "insert into mobimporta values (nextval('mobimporta_codimp_seq'),
                                      ".db_getsession("DB_id_usuario").",
				      '".date('Y-m-d')."')";

  $res = pg_exec($sql);


  $sql = "select last_value from mobimporta_codimp_seq";
  $res = pg_exec($sql);
  $codimp = pg_result($res,0,0);
  */

  if($erro == false ){

    $arq_matriculas = file($matriculas["tmp_name"]);
    ///
    for($i=0;$i<count($arq_matriculas);$i++){
      $conteudo = split(";" ,$arq_matriculas[$i]);
 
 
 
      /*
      $sql = "insert into mobavaliacao values (
           ".$conteudo[0].",
	      '".$conteudo[1]."',
	      '".$conteudo[2]."',
	    '".$conteudo[3]."',
	    '".$conteudo[4]."',
	    '".$conteudo[5]."',
	    '".$conteudo[6]."',
	    '".$conteudo[7]."',
	    '".$conteudo[8]."',
	    '".trim($conteudo[9])."',
	    $codimp)";

      $res = pg_exec($sql);
      */


     //$this->j97_sequen =
     $clmoblevantamento->j97_codimporta = $clmobimportacao->j95_codimporta;
     $clmoblevantamento->j97_matric     = $conteudo[0]; 
     $clmoblevantamento->j97_endcor     = "$conteudo[1]"; 
     $clmoblevantamento->j97_cidade     = "$conteudo[2]";
     $clmoblevantamento->j97_profun     = $conteudo[3];
     $clmoblevantamento->j97_sitterreno = $conteudo[4];
     $clmoblevantamento->j97_pedol      = $conteudo[5];
     $clmoblevantamento->j97_topog      = $conteudo[6];
     $clmoblevantamento->j97_vistoria   = $conteudo[7];
     $clmoblevantamento->j97_muro       = $conteudo[8];
     $clmoblevantamento->j97_calcada    = $conteudo[9];

     $clmoblevantamento->incluir(0);

     if($clmoblevantamento->erro_status == 0 ){
       $erro = true;
       $msg = $clmoblevantamento->erro_msg;
       break;
     }



   
    }
  }
  
  if($erro == false){
      $arq_testadas   = file($testadas["tmp_name"]);
      ///
      for($i=0;$i<count($arq_testadas);$i++){
        $conteudo = split(";",$arq_testadas[$i]);
        /*
        $sql = "insert into mobavalogradouro values (
            ".$conteudo[0].",
	    '".$conteudo[1]."',
	    '".$conteudo[2]."',
	    '".$conteudo[3]."',
	    '".$conteudo[4]."',
	    '".$conteudo[5]."',
	    '".$conteudo[6]."',
	    '".$conteudo[7]."',
	    '".$conteudo[8]."',
	    '".$conteudo[9]."',
	    '".trim($conteudo[10])."',
	    $codimp)";

        $res = pg_exec($sql);
        */

       //$clmoblevantamentolog->j98_sequen     =
       $clmoblevantamentolog->j98_codimporta = $clmobimportacao->j95_codimporta;
       $clmoblevantamentolog->j98_matric     = $conteudo[0];
       $clmoblevantamentolog->j98_codigo     = $conteudo[1];
       $clmoblevantamentolog->j98_testada    = $conteudo[2];
       $clmoblevantamentolog->j98_pavim      = $conteudo[3];
       $clmoblevantamentolog->j98_agua       = $conteudo[4];
       $clmoblevantamentolog->j98_esgoto     = $conteudo[5];
       $clmoblevantamentolog->j98_eletrica   = $conteudo[6];
       $clmoblevantamentolog->j98_meiofio    = $conteudo[7];
       $clmoblevantamentolog->j98_iluminacao = $conteudo[8];
       $clmoblevantamentolog->j98_telefonia  = $conteudo[9];
       $clmoblevantamentolog->j98_lixo       = trim($conteudo[10]);
 
       $clmoblevantamentolog->incluir(0);

       if($clmoblevantamentolog->erro_status == 0 ){
         $erro = true;
         $msg = $clmoblevantamentolog->erro_msg;
         break;
       }

      }

  }

  if($erro == false){

    $arq_edificacoes= file($edificacoes["tmp_name"]);
    


    for($i=0;$i<count($arq_edificacoes);$i++){
      $conteudo = split(";",$arq_edificacoes[$i]);
     
     
      /*$sql = "insert into mobavaedificacao values (
            ".$conteudo[0].",
	    '".$conteudo[1]."',
	    '".$conteudo[2]."',
	    '".$conteudo[3]."',
	    '".$conteudo[4]."',
	    '".$conteudo[5]."',
	    '".$conteudo[6]."',
	    '".$conteudo[7]."',
	    '".$conteudo[8]."',
	    '".$conteudo[9]."',
	    '".trim($conteudo[10])."',
	    '".trim($conteudo[11])."',
	    '".trim($conteudo[12])."',
	    '".trim($conteudo[13])."',
	    '".trim($conteudo[14])."',
	    '".trim($conteudo[15])."',
	    '".trim($conteudo[16])."',
	    ".(trim($conteudo[17])+0).",
	    $codimp)";
	    

      $res = pg_exec($sql);
      */

      //$clmoblevantamentoedi->j96_sequen        =
      $clmoblevantamentoedi->j96_codimporta    = $clmobimportacao->j95_codimporta;
      $clmoblevantamentoedi->j96_matric        = $conteudo[0];
      $clmoblevantamentoedi->j96_codigo        = $conteudo[1];
      $clmoblevantamentoedi->j96_numero        = $conteudo[2];
      $clmoblevantamentoedi->j96_compl         = $conteudo[3];
      $clmoblevantamentoedi->j96_paredes       = ($conteudo[4]==''?'0':$conteudo[4]);
      $clmoblevantamentoedi->j96_cobertura     = ($conteudo[5]==''?'0':$conteudo[5]);
      $clmoblevantamentoedi->j96_revexterno    = ($conteudo[6]==''?'0':$conteudo[6]);
      $clmoblevantamentoedi->j96_esquadrias    = ($conteudo[7]==''?'0':$conteudo[7]);
      $clmoblevantamentoedi->j96_forro         = ($conteudo[8]==''?'0':$conteudo[8]);
      $clmoblevantamentoedi->j96_pintura       = ($conteudo[9]==''?'0':$conteudo[9]);
      $clmoblevantamentoedi->j96_piso          = ($conteudo[10]==''?'0':$conteudo[10]);
      $clmoblevantamentoedi->j96_revinterno    = ($conteudo[11]==''?'0':$conteudo[11]);
      $clmoblevantamentoedi->j96_instsanitario = ($conteudo[12]==''?'0':$conteudo[12]);
      $clmoblevantamentoedi->j96_insteletrica  = ($conteudo[13]==''?'0':$conteudo[13]);
      $clmoblevantamentoedi->j96_idade         = ($conteudo[14]==''?'0':$conteudo[14]);
      $clmoblevantamentoedi->j96_tipoconstr    = ($conteudo[15]==''?'0':$conteudo[15]);
      $clmoblevantamentoedi->j96_subtitulo     = ($conteudo[16]==''?'0':$conteudo[16]);


      $clmoblevantamentoedi->incluir(0);

      if($clmoblevantamentoedi->erro_status == 0 ){
        $erro = true;
        $msg = $clmoblevantamentoedi->erro_msg;
        break;
      }

      
    }
    
  }

  db_fim_transacao($erro);
  //db_msgbox($msg);
  //exit;
  if($erro == false){
  	$msg = 'Processo Concluído com sucesso.';
  }


}

$clmobimportacao->rotulo->label();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC" >
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name='form1' method='post' enctype="multipart/form-data">
<table>
  <tr>
    <td nowrap title="<?=@$Tj95_pda?>">
       <?=@$Lj95_pda?>
    </td>
    <td> 
    <?
    $x = array('1'=>'Pda 1','2'=>'Pda 2','3'=>'Pda 3','4'=>'Pda 4','5'=>'Pda 5','6'=>'Pda 6','7'=>'Pda 7','8'=>'Pda 8','9'=>'Pda 9','10'=>'pda 10','11'=>'Pda 11','12'=>'Pda 12','13'=>'Pda 13','14'=>'Pda 14','15'=>'Pda 15');
    db_select('j95_pda',$x,true,$db_opcao,"");
    ?>
    </td>
  </tr>
 
<tr>
<td><strong>Matriculas(Avaliacao.txt):</strong></td>
<td><input name='matriculas' value='Importar' type='file'></td>
</tr>
<tr>
<td><strong>Testadas(Logradouro.txt):</strong></td>
<td><input name='testadas' value='Importar' type='file'></td>
</tr>
<tr>
<td><strong>Edificações(Edificacao.txt):</strong></td>
<td><input name='edificacoes' value='Importar' type='file'></td>
</tr>
<tr>
<td colspan=2 align=center><input name='importa' value='Importar' type='submit'></td>
</tr>
<?
if(isset($importa)){
  if(isset($codimp)){
    echo "</tr>
          <tr>
          <td colspan=2 align=center><br><br>Importação dos dados Realizada. Código: $codimp</td>
          </tr>
    ";

  }

}

?>


</table>
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($erro)){
  db_msgbox($msg);
}

?>