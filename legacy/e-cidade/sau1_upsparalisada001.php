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
include("libs/db_stdlibwebseller.php");
include("classes/db_sau_upsparalisada_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_sau_motivo_ausencia_classe.php");
include("classes/db_agendamentos_ext_classe.php");
require("libs/db_app.utils.php");
require("libs/db_utils.php");
$clmotivo_ausencia        = new cl_sau_motivo_ausencia;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clsau_upsparalisada      = new cl_sau_upsparalisada;
$clagendamentos           = new cl_agendamentos_ext;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_opcao2 = 1;
$db_botao = true;

//altera exclui inicio
if(isset($opcao)){
 /////comeca classe alterar excluir
 $db_opcao2 = 22; 
 $campos = "";
 //$sql =  $clsau_upsparalisada->sql_query_file("","*",""," s140_i_codigo = $s140_i_codigo");
 $result1 = $clsau_upsparalisada->sql_record("select s140_d_inicio, 
                                                     s140_d_fim,
                                                     s140_i_tipo,
                                                     (s140_d_fim - s140_d_inicio) + 1 as s140_i_quantidade,
                                                     s140_c_horaini,
                                                     s140_c_horafim
                                              from sau_upsparalisada 
                                              where s140_i_codigo = $s140_i_codigo");
 
 if($clsau_upsparalisada->numrows>0){
   db_fieldsmemory($result1,0);
 }
 if( $opcao == "alterar"){
   $db_opcao = 2;
   $db_botao1 = true;
 }else{
   if( $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
     $db_opcao = 3;
     $db_botao1 = true;
   }else{
     if(isset($alterar)){
       $db_opcao = 2;
       $db_botao1 = true;
     }
   }
 }
}

if(isset($incluir)||isset($alterar)){

  $inicio_ano = substr( $s140_d_inicio, 6, 4 );
  $inicio_mes = substr( $s140_d_inicio, 3, 2 );
  $inicio_dia = substr( $s140_d_inicio, 0, 2 );

  $fim_ano = substr( $s140_d_fim, 6, 4 );
  $fim_mes = substr( $s140_d_fim, 3, 2 );
  $fim_dia = substr( $s140_d_fim, 0, 2 );

  $sWhere  = " s140_i_unidade = $s140_i_unidade ";
  $sWhere .= "and (s140_d_inicio between '$inicio_ano/$inicio_mes/$inicio_dia' and '$fim_ano/$fim_mes/$fim_dia' ";
  $sWhere .= "or s140_d_fim between '$inicio_ano/$inicio_mes/$inicio_dia' and '$fim_ano/$fim_mes/$fim_dia' ";
  $sWhere .= "or '$inicio_ano/$inicio_mes/$inicio_dia' between s140_d_fim and s140_d_fim ";
  $sWhere .= "or '$fim_ano/$fim_mes/$fim_dia' between s140_d_inicio and s140_d_inicio)";
  if (($s140_c_horaini != "") && ($s140_c_horafim != "")){

    $sWhere .= "and ( s140_c_horaini between  '$s140_c_horaini' and '$s140_c_horafim' ";
    $sWhere .= "      or s140_c_horafim between  '$s140_c_horaini' and '$s140_c_horafim' ";
    $sWhere .= "      or '$s140_c_horaini' between  s140_c_horaini and s140_c_horafim ";
    $sWhere .= "      or '$s140_c_horafim' between  s140_c_horaini and s140_c_horafim )";

  }
  if(isset($alterar)){
    $sWhere = " and s140_i_codigo != $s140_i_codigo";
  }
  $sSql = $clsau_upsparalisada->sql_query_file("","*",null, $sWhere);
  $clsau_upsparalisada->sql_record($sSql);
  
  $sWhere  = " sd04_i_unidade = $s140_i_unidade ";
  $sWhere .= "and (sd23_d_consulta between '$inicio_ano/$inicio_mes/$inicio_dia' and '$fim_ano/$fim_mes/$fim_dia')";
  if (($s140_c_horaini != "") && ($s140_c_horafim != "")){

    $sWhere .= "and ( sd30_c_horaini between  '$s140_c_horaini' and '$s140_c_horafim' ";
    $sWhere .= "      or sd30_c_horafim between  '$s140_c_horaini' and '$s140_c_horafim' ";
    $sWhere .= "      or '$s140_c_horaini' between  sd30_c_horaini and sd30_c_horafim ";
    $sWhere .= "      or '$s140_c_horafim' between  sd30_c_horaini and sd30_c_horafim )";

  }
  
  $inicio_ano = substr( $s140_d_inicio, 6, 4 );
  $inicio_mes = substr( $s140_d_inicio, 3, 2 );
  $inicio_dia = substr( $s140_d_inicio, 0, 2 );
  $fim_ano    = substr( $s140_d_fim, 6, 4 );
  $fim_mes    = substr( $s140_d_fim, 3, 2 );
  $fim_dia    = substr( $s140_d_fim, 0, 2 );
  $sWhere    .= "and sd23_d_consulta between '$inicio_ano/$inicio_mes/$inicio_dia' and '$fim_ano/$fim_mes/$fim_dia' ";
  $sSql = $clagendamentos->sql_query_ext("","*",null, $sWhere);
  $rsAgendamentos = $clagendamentos->sql_record($sSql);
  
  $iDias          = quantDias($s140_d_inicio,$s140_d_fim)+1;
  $aVet           = explode("/",$s140_d_inicio);
  $dInicio        = $aVet[2].'-'.$aVet[1].'-'.$aVet[0];
  $lLibera        = true;
  for($iY=0;$iY < $iDias;$iY++){

    $aFichas = array();
    for($iX=0; $iX < $clagendamentos->numrows; $iX++){

      $oAgendamentos = db_utils::fieldsmemory($rsAgendamentos,$iX);
        if ($oAgendamentos->sd23_d_consulta == $dInicio) {

          $iTam = array_search($oAgendamentos->sd30_c_tipograde,$aFichas);
          if ($iTam == false) {

            $iTam                      = count($aFichas);
            $aFichas[$iTam]["quant"]   = 1;
            $aFichas[$iTam]["idficha"] = "".$oAgendamentos->sd23_i_ficha;

          } else {

            $aFichas[$iTam]["quant"]++;
            $aFichas[$iTam]["idficha"] = ",".$Agendamentos->sd23_i_ficha;

          }
          $aFichas[$iTam]["tipo"]    = $oAgendamentos->sd30_c_tipograde;
          $aFichas[$iTam]["fichas"]  = $oAgendamentos->sd30_i_fichas;
          $aFichas[$iTam]["horaini"] = $oAgendamentos->sd30_c_horaini;
          $aFichas[$iTam]["horafim"] = $oAgendamentos->sd30_c_horafim;
          

        }
    }

    if(!empty($aFichas)){
      
      //echo"<br>";print_r($aFichas);
      //Percorre todas as fichas referente ao dia verificando se é possivel marcar a ausencia
      for($iZ=0; $iZ < count($aFichas); $iZ++){

        //Minutos paralizados 
        $sIni = $s140_c_horaini;
        if ($s140_c_horaini < $aFichas[$iZ]["horaini"]) {
          $sIni = $aFichas[$iZ]["horaini"];
        }
        $sFim = $s140_c_horafim;
        if ($s140_c_horafim > $aFichas[$iZ]["horafim"]) {
          $sFim = $aFichas[$iZ]["horafim"];
        }
        $aIni = explode(':',$sIni);
        $aFim = explode(':',$sFim);
        $iMinutosParalizados = (((int)$aFim[0]*60)+(int)$aFim[1])-(((int)$aIni[0]*60)+(int)$aIni[1]);
        //Minutos da grade
        $aIni              = explode(':',$aFichas[$iZ]["horaini"]);
        $aFim              = explode(':',$aFichas[$iZ]["horafim"]);
        $iMinutosDaGrade   = (((int)$aFim[0]*60)+(int)$aFim[1])-(((int)$aIni[0]*60)+(int)$aIni[1]);
        if ($aFichas[$iZ]["tipo"] == "P") {

          //Quantidade de fichas paralizadas
          $iFichaParalizadas = ($aFichas[$iZ]["fichas"]/$iMinutosDaGrade)*$iMinutosParalizados;
          //Quantidade de ficha livres = quantidade de fichas da grade - quantidade de fichas afestadas
          $iFichasLivres     = $aFichas[$iZ]["fichas"]-$iFichaParalizadas;
          //Se a quantidade de fichas livres for menor que a quantidade de fichas agendadas blokear incusão
          //echo"<br> if (Livres=$iFichasLivres < Agendadas=".$aFichas[$iZ]["quant"].") { ";
          if ($iFichasLivres < $aFichas[$iZ]["quant"]) {
            $lLibera         = False;
          }

        }else{
        
          //Tempo em minutos de cada ficha
          $MinutosPorFicha = $iMinutosDaGrade/$aFichas[$iZ]["fichas"];
          $aIdFichas       = explode(",",$aFichas[$iTam]["idficha"]);
          $iMinutosIni     = ((int)$aIni[0]*60)+(int)$aIni[1];
          for ($iId=0; $iId < count($aIdFichas); $iId++) {

            //tempo em minutos do inicio consulta
            $iMinutosIniConsulta = ($iMinutosIni+(((int)$aIdFichas-1)*$MinutosPorFicha));
            //tempo em minutos do fim da consulta
            $iMinutosFimConsulta = $iMinutosIniConsulta+$MinutosPorFicha;
            //tempo em minutos do inicio da paralização 
            $aVet = explode(':',$s140_c_horaini);
            $iMinutosIniParalizacao = (((int)$aVet[0]*60)+(int)$aVet[1]);
            //tempo em minutos do fim da paralização
            $aVet = explode(':',$s140_c_horafim);
            $iMinutosFimParalizacao = (((int)$aVet[0]*60)+(int)$aVet[1]);
            //verificar se há intercecção entre os tempos
            //echo"<br> Consulata = $iMinutosIniConsulta - $iMinutosFimConsulta |".
            //    " Paralização $iMinutosIniParalizacao - $iMinutosFimParalizacao ";
            if((($iMinutosIniConsulta > $iMinutosIniParalizacao)&&
                ($iMinutosIniConsulta < $iMinutosFimParalizacao))
                ||
                (($iMinutosFimConsulta > $iMinutosIniParalizacao)&&
                 ($iMinutosFimConsulta < $iMinutosFimParalizacao))
              ){
              $lLibera = false;
            }

          }
        
        }
      
      }
    }
  }
  if ($lLibera == false){
    $clsau_upsparalisada->numrows = 1;
  }
  
  if( $clsau_upsparalisada->numrows > 0 ){

    $clsau_upsparalisada->erro_status = "0";
    $clsau_upsparalisada->erro_msg    = "A UPS não poderá ter o intervalo de paralização pois possui lançamentos nesse ".
                                        " período.";

  }else{

    if(isset($incluir)){

      db_inicio_transacao();
      $clsau_upsparalisada->incluir($s140_i_codigo);
      db_fim_transacao();

    }
    if (isset($alterar)) {

      db_inicio_transacao();
      $db_opcao = 2;
      $clsau_upsparalisada->alterar($s140_i_codigo);
      db_fim_transacao();

    }
  }
}
if (isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $clsau_upsparalisada->excluir($s140_i_codigo);
  db_fim_transacao();

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("/widgets/dbautocomplete.widget.js");
db_app::load("webseller.js");

?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
   if(isset($chavepesquisa) && !empty($chavepesquisa))
   {
    $s140_i_unidade = $chavepesquisa;
    $resultado = db_query("select descrdepto from db_depart where coddepto = $chavepesquisa");
     db_fieldsmemory($resultado,0);
   }
	include("forms/db_frmsau_upsparalisada.php");
	?>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1","s140_i_unidade",true,1,"s140_i_unidade",true);
</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($clsau_upsparalisada->erro_status=="0"){
    $clsau_upsparalisada->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsau_upsparalisada->erro_campo!=""){
      echo "<script> document.form1.".$clsau_upsparalisada->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsau_upsparalisada->erro_campo.".focus();</script>";
    }
  }else{
    $clsau_upsparalisada->erro(true,false);
    db_redireciona("sau1_upsparalisada001.php?chavepesquisa=".$chavepesquisa);
  }
}
?>