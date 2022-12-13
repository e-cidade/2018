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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_jsplibwebseller.php");
include("libs/db_stdlibwebseller.php");
require_once("ext/php/adodb-time.inc.php");
require_once("libs/db_utils.php");

include("classes/db_agendamentos_ext_classe.php");
include("classes/db_ausencias_ext_classe.php");

include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

include("classes/db_sau_motivo_ausencia_classe.php");
$clmotivo_ausencia = new cl_sau_motivo_ausencia;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
//$clmedicos                = new cl_medicos;
//$clunidademedicos         = new cl_unidademedicos;
//$clsau_tipoficha          = new cl_sau_tipoficha;
//$cldiasemana              = new cl_diasemana;
//$clundmedhorario          = new cl_undmedhorario_ext;

$clausencias    = new cl_ausencias_ext;
$clagendamentos = new cl_agendamentos_ext;

$db_botao = true;
$db_opcao = 1;
$db_opcao2= 1;
$datausu  = date("Y",db_getsession( "DB_datausu" ) )."/".
            date("m",db_getsession( "DB_datausu" ) )."/".
            date("d",db_getsession( "DB_datausu" ) );

function busca_ficha($sValor, $aArray){
  foreach ($aArray as $key => $value) {
    if ($aArray[$key]['tipo'] == $sValor) {
      return $key;
    }
  }
  return -1;
}

if(isset($incluir)||isset($alterar)){

  $db_opcao = isset($alterar)?2:$db_opcao;	
  $db_opcao2 = isset($alterar)?22:$db_opcao;	
  $inicio_ano = substr( $sd06_d_inicio, 6, 4 );
  $inicio_mes = substr( $sd06_d_inicio, 3, 2 );
  $inicio_dia = substr( $sd06_d_inicio, 0, 2 );

  $fim_ano = substr( $sd06_d_fim, 6, 4 );
  $fim_mes = substr( $sd06_d_fim, 3, 2 );
  $fim_dia = substr( $sd06_d_fim, 0, 2 );

  $sWhere  = "sd27_i_codigo = $sd06_i_especmed ";
  $sWhere .= "and sd23_d_consulta between '$inicio_ano/$inicio_mes/$inicio_dia' and '$fim_ano/$fim_mes/$fim_dia' ";
  if( isset($sd06_i_undmedhorario) && (int)$sd06_i_undmedhorario > 0 ){
    $sWhere .= " and agendamentos.sd23_i_undmedhor = $sd06_i_undmedhorario ";
  }
  $sSql = $clagendamentos->sql_query_ext("","*",null, $sWhere);
  $rsAgendamentos = $clagendamentos->sql_record($sSql);
  
  $iDias          = quantDias($sd06_d_inicio,$sd06_d_fim)+1;
  $aVet           = explode("/",$sd06_d_inicio);
  $dInicio        = $aVet[2].'-'.$aVet[1].'-'.$aVet[0];
  $lLibera        = true;
  for($iY=0;$iY < $iDias;$iY++){

    $aFichas = array();
    for($iX=0; $iX < $clagendamentos->numrows; $iX++){

      $oAgendamentos = db_utils::fieldsmemory($rsAgendamentos,$iX);
        if ($oAgendamentos->sd23_d_consulta == $dInicio) {

          $iTam = busca_ficha($oAgendamentos->sd30_c_tipograde,$aFichas);
          if ($iTam == -1) {

            $iTam                      = count($aFichas);
            $aFichas[$iTam]["quant"]   = 1;
            $aFichas[$iTam]["idficha"] = "".$oAgendamentos->sd23_i_ficha;

          } else {

            $aFichas[$iTam]["quant"]++;
            $aFichas[$iTam]["idficha"] .= ",".$oAgendamentos->sd23_i_ficha;

          }
          $aFichas[$iTam]["tipo"]    = $oAgendamentos->sd30_c_tipograde;
          $aFichas[$iTam]["fichas"]  = $oAgendamentos->sd30_i_fichas;
          $aFichas[$iTam]["horaini"] = $oAgendamentos->sd30_c_horaini;
          $aFichas[$iTam]["horafim"] = $oAgendamentos->sd30_c_horafim;
          

        }
    }
    if(!empty($aFichas)){

      //Percorre todas as fichas referente ao dia verificando se é possivel marcar a ausencia
      for($iZ=0; $iZ < count($aFichas); $iZ++){

        //Minutos paralizados 
        $sIni = $aFichas[$iZ]["horaini"];
        if(isset($sd06_c_horainicio) && $sd06_c_horainicio != "") {
          $sIni = $sd06_c_horainicio;
        }
        $sFim = $aFichas[$iZ]["horafim"];
        if (isset($sd06_c_horafim) && $sd06_c_horafim != "") {
          $sFim = $sd06_c_horafim;
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
            $aVet = explode(':',$sIni);
            $iMinutosIniParalizacao = (((int)$aVet[0]*60)+(int)$aVet[1]);
            //tempo em minutos do fim da paralização
            $aVet = explode(':',$sFim);
            $iMinutosFimParalizacao = (((int)$aVet[0]*60)+(int)$aVet[1]);
            //verificar se há intercecção entre os tempos
            //echo"Consulta = $iMinutosIniConsulta - $iMinutosFimConsulta |".
            //    " Paralização $iMinutosIniParalizacao - $iMinutosFimParalizacao <br>";
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
    
    $aInicio = explode("-",$dInicio);
    $dInicio = somaDataDiaMesAno($aInicio[2],$aInicio[1],$aInicio[0],1,0,0,2);
  }
  
  if ($lLibera == false) {

    $clausencias->erro_status = "0";
    $clausencias->erro_msg    = "Profissional não poderá ter o intervalo de Folga/Férias pois possui".
                                " agendamento nesse período.";

  } else {
        if(isset($incluir)){

            db_inicio_transacao();
            $clausencias->incluir(null);
            db_fim_transacao();
        }

        if(isset($alterar)){
            db_inicio_transacao();
            $clausencias->alterar($sd06_i_codigo);
            db_fim_transacao();
        }
    }
}

if(isset($excluir)){
    $db_opcao = 3;	
    db_inicio_transacao();
    $clausencias->excluir($sd06_i_codigo);
    db_fim_transacao();
}


//Botões Alterar/Excluir
if(isset($opcao)){
	$db_botao1 = true;
	$db_opcao  = $opcao=="alterar"?2:3;
	$db_opcao2 = $opcao=="alterar"?22:3;
	
	$result = $clausencias->sql_record($clausencias->sql_query_ext($sd06_i_codigo,"*, (sd06_d_fim - sd06_d_inicio) + 1 as sd06_i_qtd"));
	if( $clausencias->numrows > 0 ){
		db_fieldsmemory($result,0);
	}
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmundmedhorario007.php");
        ?>
    </center>
        </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd30_i_undmed",true,1,"sd30_i_undmed",true);
</script>
<?
if(isset($incluir)||isset($alterar)){
	if($clausencias->erro_status=="0"){
		$clausencias->erro(true,false);
		$db_botao=true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if($clausencias->erro_campo!=""){
			echo "<script> document.form1.".$clausencias->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clausencias->erro_campo.".focus();</script>";
		}
	}else{
		$clausencias->erro(true,false);
		db_redireciona("sau1_undmedhorario007.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
	}
}
if(isset($excluir)){
	if($clausencias->erro_status=="0"){
		$clausencias->erro(true,false);
	}else{
		$clausencias->erro(true,false);
		db_redireciona("sau1_undmedhorario007.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
	}
}
?>