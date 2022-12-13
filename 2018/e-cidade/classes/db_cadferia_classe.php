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

//MODULO: pessoal
//CLASSE DA ENTIDADE cadferia
class cl_cadferia { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $r30_anousu = 0; 
   var $r30_mesusu = 0; 
   var $r30_numcgm = 0; 
   var $r30_regist = 0; 
   var $r30_faltas = 0; 
   var $r30_perai_dia = null; 
   var $r30_perai_mes = null; 
   var $r30_perai_ano = null; 
   var $r30_perai = null; 
   var $r30_peraf_dia = null; 
   var $r30_peraf_mes = null; 
   var $r30_peraf_ano = null; 
   var $r30_peraf = null; 
   var $r30_ndias = 0; 
   var $r30_dias1 = 0; 
   var $r30_dias2 = 0; 
   var $r30_per1i_dia = null; 
   var $r30_per1i_mes = null; 
   var $r30_per1i_ano = null; 
   var $r30_per1i = null; 
   var $r30_per1f_dia = null; 
   var $r30_per1f_mes = null; 
   var $r30_per1f_ano = null; 
   var $r30_per1f = null; 
   var $r30_per2i_dia = null; 
   var $r30_per2i_mes = null; 
   var $r30_per2i_ano = null; 
   var $r30_per2i = null; 
   var $r30_per2f_dia = null; 
   var $r30_per2f_mes = null; 
   var $r30_per2f_ano = null; 
   var $r30_per2f = null; 
   var $r30_proc1 = null; 
   var $r30_proc2 = null; 
   var $r30_abono = 0; 
   var $r30_proc1d = null; 
   var $r30_vliq1 = "0";
   var $r30_vfgt1 = "0";
   var $r30_virf1 = "0";
   var $r30_vpre1 = "0";
   var $r30_vliq1d = "0";
   var $r30_vfgt1d = "0";
   var $r30_virf1d = "0";
   var $r30_vpre1d = "0";
   var $r30_vliq2 = "0";
   var $r30_vfgt2 = "0";
   var $r30_virf2 = "0";
   var $r30_vliq2d = "0";
   var $r30_virf2d = "0";
   var $r30_vfgt2d = "0";
   var $r30_vpre2d = "0";
   var $r30_vpre2 = "0";
   var $r30_tip1 = null;
   var $r30_tip2 = null;
   var $r30_psal1 = 'false';
   var $r30_psal2 = 'false';
   var $r30_proc2d = "null";
   var $r30_ponto = "null";
   var $r30_paga13 = 'f';
   var $r30_descad = "0";
   var $r30_tipoapuracaomedia = "0";
   var $r30_periodolivreinicial_dia = null; 
   var $r30_periodolivreinicial_mes = null; 
   var $r30_periodolivreinicial_ano = null; 
   var $r30_periodolivreinicial = null; 
   var $r30_periodolivrefinal_dia = null; 
   var $r30_periodolivrefinal_mes = null; 
   var $r30_periodolivrefinal_ano = null; 
   var $r30_periodolivrefinal = null; 
   var $r30_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r30_anousu = int4 = Ano do Exercicio 
                 r30_mesusu = int4 = Mes do Exercicio 
                 r30_numcgm = int4 = Codigo do Funcionario no cgm 
                 r30_regist = int4 = Matrícula 
                 r30_faltas = int4 = Faltas Durante o Período Aquisitivo 
                 r30_perai = date = Período Aquisitivo 
                 r30_peraf = date = Final Período 
                 r30_ndias = float8 = Total de Dias a Gozar 
                 r30_dias1 = float8 = Dias 
                 r30_dias2 = float8 = Dias 
                 r30_per1i = date = Início Gozo 
                 r30_per1f = date = Final Gozo 
                 r30_per2i = date = Início Gozo 
                 r30_per2f = date = Final Gozo 
                 r30_proc1 = varchar(7) = Pagamento do 1o. periodo 
                 r30_proc2 = varchar(7) = Pagamento do 2o periodo 
                 r30_abono = float8 = Número de Dias do Abono 
                 r30_proc1d = varchar(7) = Ano/Mes do pagto da 1a dif 
                 r30_vliq1 = float8 = vlr total das ferias - 1a 
                 r30_vfgt1 = float8 = base fgts ferias (1a) 
                 r30_virf1 = float8 = base irf ferias (1a) 
                 r30_vpre1 = float8 = base prev ferias (1a) 
                 r30_vliq1d = float8 = vlr ferias dif (1a) 
                 r30_vfgt1d = float8 = base fgts ferias dif (1a) 
                 r30_virf1d = float8 = base irfferias dif (1a) 
                 r30_vpre1d = float8 = base prev ferias dif (1a) 
                 r30_vliq2 = float8 = valor ferias (2a) 
                 r30_vfgt2 = float8 = base fgts ferias (2a) 
                 r30_virf2 = float8 = base irf ferias (2a) 
                 r30_vliq2d = float8 = valor ferias dif (2a) 
                 r30_virf2d = float8 = base irf ferias dif (2a) 
                 r30_vfgt2d = float8 = base fgts ferias dif (2a) 
                 r30_vpre2d = float8 = base prev ferias dif (2a) 
                 r30_vpre2 = float8 = base prev ferias (2a) 
                 r30_tip1 = varchar(2) = Pgto da 1a parc ou total
                 r30_tip2 = varchar(2) = Pgto da 2a parcela 
                 r30_psal1 = boolean = .t. = pontofs/.f.=pontofe 
                 r30_psal2 = boolean = t.t= sal pontofs/.f. = pontofe 
                 r30_proc2d = varchar(7) = Ano/Mês do pagto da dif do 2o período 
                 r30_ponto = varchar(1) = Salario/Complementar 
                 r30_paga13 = boolean = pagamento so 1/3 das ferias 
                 r30_descad = float8 = descontos sobre o adiantamento 
                 r30_tipoapuracaomedia = int4 = Tipo da Apuração da Média 
                 r30_periodolivreinicial = date = Data Média Inicial 
                 r30_periodolivrefinal = date = Data Média Final 
                 r30_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_cadferia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadferia"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->r30_anousu = ($this->r30_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_anousu"]:$this->r30_anousu);
       $this->r30_mesusu = ($this->r30_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_mesusu"]:$this->r30_mesusu);
       $this->r30_numcgm = ($this->r30_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_numcgm"]:$this->r30_numcgm);
       $this->r30_regist = ($this->r30_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_regist"]:$this->r30_regist);
       $this->r30_faltas = ($this->r30_faltas == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_faltas"]:$this->r30_faltas);
       if($this->r30_perai == ""){
         $this->r30_perai_dia = ($this->r30_perai_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_perai_dia"]:$this->r30_perai_dia);
         $this->r30_perai_mes = ($this->r30_perai_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_perai_mes"]:$this->r30_perai_mes);
         $this->r30_perai_ano = ($this->r30_perai_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_perai_ano"]:$this->r30_perai_ano);
         if($this->r30_perai_dia != ""){
            $this->r30_perai = $this->r30_perai_ano."-".$this->r30_perai_mes."-".$this->r30_perai_dia;
         }
       }
       if($this->r30_peraf == ""){
         $this->r30_peraf_dia = ($this->r30_peraf_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_peraf_dia"]:$this->r30_peraf_dia);
         $this->r30_peraf_mes = ($this->r30_peraf_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_peraf_mes"]:$this->r30_peraf_mes);
         $this->r30_peraf_ano = ($this->r30_peraf_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_peraf_ano"]:$this->r30_peraf_ano);
         if($this->r30_peraf_dia != ""){
            $this->r30_peraf = $this->r30_peraf_ano."-".$this->r30_peraf_mes."-".$this->r30_peraf_dia;
         }
       }
       $this->r30_ndias = ($this->r30_ndias == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_ndias"]:$this->r30_ndias);
       $this->r30_dias1 = ($this->r30_dias1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_dias1"]:$this->r30_dias1);
       $this->r30_dias2 = ($this->r30_dias2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_dias2"]:$this->r30_dias2);
       if($this->r30_per1i == ""){
         $this->r30_per1i_dia = ($this->r30_per1i_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per1i_dia"]:$this->r30_per1i_dia);
         $this->r30_per1i_mes = ($this->r30_per1i_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per1i_mes"]:$this->r30_per1i_mes);
         $this->r30_per1i_ano = ($this->r30_per1i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per1i_ano"]:$this->r30_per1i_ano);
         if($this->r30_per1i_dia != ""){
            $this->r30_per1i = $this->r30_per1i_ano."-".$this->r30_per1i_mes."-".$this->r30_per1i_dia;
         }
       }
       if($this->r30_per1f == ""){
         $this->r30_per1f_dia = ($this->r30_per1f_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per1f_dia"]:$this->r30_per1f_dia);
         $this->r30_per1f_mes = ($this->r30_per1f_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per1f_mes"]:$this->r30_per1f_mes);
         $this->r30_per1f_ano = ($this->r30_per1f_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per1f_ano"]:$this->r30_per1f_ano);
         if($this->r30_per1f_dia != ""){
            $this->r30_per1f = $this->r30_per1f_ano."-".$this->r30_per1f_mes."-".$this->r30_per1f_dia;
         }
       }
       if($this->r30_per2i == ""){
         $this->r30_per2i_dia = ($this->r30_per2i_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per2i_dia"]:$this->r30_per2i_dia);
         $this->r30_per2i_mes = ($this->r30_per2i_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per2i_mes"]:$this->r30_per2i_mes);
         $this->r30_per2i_ano = ($this->r30_per2i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per2i_ano"]:$this->r30_per2i_ano);
         if($this->r30_per2i_dia != ""){
            $this->r30_per2i = $this->r30_per2i_ano."-".$this->r30_per2i_mes."-".$this->r30_per2i_dia;
         }
       }
       if($this->r30_per2f == ""){
         $this->r30_per2f_dia = ($this->r30_per2f_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per2f_dia"]:$this->r30_per2f_dia);
         $this->r30_per2f_mes = ($this->r30_per2f_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per2f_mes"]:$this->r30_per2f_mes);
         $this->r30_per2f_ano = ($this->r30_per2f_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_per2f_ano"]:$this->r30_per2f_ano);
         if($this->r30_per2f_dia != ""){
            $this->r30_per2f = $this->r30_per2f_ano."-".$this->r30_per2f_mes."-".$this->r30_per2f_dia;
         }
       }
       $this->r30_proc1 = ($this->r30_proc1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_proc1"]:$this->r30_proc1);
       $this->r30_proc2 = ($this->r30_proc2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_proc2"]:$this->r30_proc2);
       $this->r30_abono = ($this->r30_abono == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_abono"]:$this->r30_abono);
       $this->r30_proc1d = ($this->r30_proc1d == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_proc1d"]:$this->r30_proc1d);
       $this->r30_vliq1 = ($this->r30_vliq1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vliq1"]:$this->r30_vliq1);
       $this->r30_vfgt1 = ($this->r30_vfgt1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vfgt1"]:$this->r30_vfgt1);
       $this->r30_virf1 = ($this->r30_virf1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_virf1"]:$this->r30_virf1);
       $this->r30_vpre1 = ($this->r30_vpre1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vpre1"]:$this->r30_vpre1);
       $this->r30_vliq1d = ($this->r30_vliq1d == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vliq1d"]:$this->r30_vliq1d);
       $this->r30_vfgt1d = ($this->r30_vfgt1d == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vfgt1d"]:$this->r30_vfgt1d);
       $this->r30_virf1d = ($this->r30_virf1d == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_virf1d"]:$this->r30_virf1d);
       $this->r30_vpre1d = ($this->r30_vpre1d == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vpre1d"]:$this->r30_vpre1d);
       $this->r30_vliq2 = ($this->r30_vliq2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vliq2"]:$this->r30_vliq2);
       $this->r30_vfgt2 = ($this->r30_vfgt2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vfgt2"]:$this->r30_vfgt2);
       $this->r30_virf2 = ($this->r30_virf2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_virf2"]:$this->r30_virf2);
       $this->r30_vliq2d = ($this->r30_vliq2d == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vliq2d"]:$this->r30_vliq2d);
       $this->r30_virf2d = ($this->r30_virf2d == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_virf2d"]:$this->r30_virf2d);
       $this->r30_vfgt2d = ($this->r30_vfgt2d == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vfgt2d"]:$this->r30_vfgt2d);
       $this->r30_vpre2d = ($this->r30_vpre2d == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vpre2d"]:$this->r30_vpre2d);
       $this->r30_vpre2 = ($this->r30_vpre2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_vpre2"]:$this->r30_vpre2);
       $this->r30_tip1 = ($this->r30_tip1 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_tip1"]:$this->r30_tip1);
       $this->r30_tip2 = ($this->r30_tip2 == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_tip2"]:$this->r30_tip2);
       $this->r30_psal1 = ($this->r30_psal1 == "f"?@$GLOBALS["HTTP_POST_VARS"]["r30_psal1"]:$this->r30_psal1);
       $this->r30_psal2 = ($this->r30_psal2 == "f"?@$GLOBALS["HTTP_POST_VARS"]["r30_psal2"]:$this->r30_psal2);
       $this->r30_proc2d = ($this->r30_proc2d == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_proc2d"]:$this->r30_proc2d);
       $this->r30_ponto = ($this->r30_ponto == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_ponto"]:$this->r30_ponto);
       $this->r30_paga13 = ($this->r30_paga13 == "f"?@$GLOBALS["HTTP_POST_VARS"]["r30_paga13"]:$this->r30_paga13);
       $this->r30_descad = ($this->r30_descad == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_descad"]:$this->r30_descad);
       $this->r30_tipoapuracaomedia = ($this->r30_tipoapuracaomedia == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_tipoapuracaomedia"]:$this->r30_tipoapuracaomedia);
       if($this->r30_periodolivreinicial == ""){
         $this->r30_periodolivreinicial_dia = ($this->r30_periodolivreinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_periodolivreinicial_dia"]:$this->r30_periodolivreinicial_dia);
         $this->r30_periodolivreinicial_mes = ($this->r30_periodolivreinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_periodolivreinicial_mes"]:$this->r30_periodolivreinicial_mes);
         $this->r30_periodolivreinicial_ano = ($this->r30_periodolivreinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_periodolivreinicial_ano"]:$this->r30_periodolivreinicial_ano);
         if($this->r30_periodolivreinicial_dia != ""){
            $this->r30_periodolivreinicial = $this->r30_periodolivreinicial_ano."-".$this->r30_periodolivreinicial_mes."-".$this->r30_periodolivreinicial_dia;
         }
       }
       if($this->r30_periodolivrefinal == ""){
         $this->r30_periodolivrefinal_dia = ($this->r30_periodolivrefinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_periodolivrefinal_dia"]:$this->r30_periodolivrefinal_dia);
         $this->r30_periodolivrefinal_mes = ($this->r30_periodolivrefinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_periodolivrefinal_mes"]:$this->r30_periodolivrefinal_mes);
         $this->r30_periodolivrefinal_ano = ($this->r30_periodolivrefinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_periodolivrefinal_ano"]:$this->r30_periodolivrefinal_ano);
         if($this->r30_periodolivrefinal_dia != ""){
            $this->r30_periodolivrefinal = $this->r30_periodolivrefinal_ano."-".$this->r30_periodolivrefinal_mes."-".$this->r30_periodolivrefinal_dia;
         }
       }
       $this->r30_obs = ($this->r30_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["r30_obs"]:$this->r30_obs);
     }else{
     }
   }
   // funcao para Inclusão
   function incluir (){ 
      $this->atualizacampos();
     if($this->r30_anousu == null ){ 
       $this->erro_sql = " Campo Ano do Exercicio não informado.";
       $this->erro_campo = "r30_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_mesusu == null ){ 
       $this->erro_sql = " Campo Mes do Exercicio não informado.";
       $this->erro_campo = "r30_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_numcgm == null ){ 
       $this->erro_sql = " Campo Codigo do Funcionario no cgm não informado.";
       $this->erro_campo = "r30_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_regist == null ){ 
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "r30_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_faltas == null ){ 
       $this->erro_sql = " Campo Faltas Durante o Período Aquisitivo não informado.";
       $this->erro_campo = "r30_faltas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_perai == null ){ 
       $this->erro_sql = " Campo Período Aquisitivo não informado.";
       $this->erro_campo = "r30_perai_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_peraf == null ){ 
       $this->erro_sql = " Campo Final Período não informado.";
       $this->erro_campo = "r30_peraf_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_ndias == null ){ 
       $this->erro_sql = " Campo Total de Dias a Gozar não informado.";
       $this->erro_campo = "r30_ndias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_dias1 == null ){ 
       $this->erro_sql = " Campo Dias não informado.";
       $this->erro_campo = "r30_dias1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_dias2 == null ){ 
       $this->erro_sql = " Campo Dias não informado.";
       $this->erro_campo = "r30_dias2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_per1i == null ){ 
       $this->erro_sql = " Campo Início Gozo não informado.";
       $this->erro_campo = "r30_per1i_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_per1f == null ){ 
       $this->erro_sql = " Campo Final Gozo não informado.";
       $this->erro_campo = "r30_per1f_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_per2i == null ){ 
       $this->erro_sql = " Campo Início Gozo não informado.";
       $this->erro_campo = "r30_per2i_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_per2f == null ){ 
       $this->erro_sql = " Campo Final Gozo não informado.";
       $this->erro_campo = "r30_per2f_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_proc1 == null ){ 
       $this->erro_sql = " Campo Pagamento do 1o. periodo não informado.";
       $this->erro_campo = "r30_proc1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->r30_abono == null ){ 
       $this->erro_sql = " Campo Número de Dias do Abono não informado.";
       $this->erro_campo = "r30_abono";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_proc1d == null ){ 
       $this->erro_sql = " Campo Ano/Mes do pagto da 1a dif não informado.";
       $this->erro_campo = "r30_proc1d";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vliq1 == null ){ 
       $this->erro_sql = " Campo vlr total das ferias - 1a não informado.";
       $this->erro_campo = "r30_vliq1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vfgt1 == null ){ 
       $this->erro_sql = " Campo base fgts ferias (1a) não informado.";
       $this->erro_campo = "r30_vfgt1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_virf1 == null ){ 
       $this->erro_sql = " Campo base irf ferias (1a) não informado.";
       $this->erro_campo = "r30_virf1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vpre1 == null ){ 
       $this->erro_sql = " Campo base prev ferias (1a) não informado.";
       $this->erro_campo = "r30_vpre1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vliq1d == null ){ 
       $this->erro_sql = " Campo vlr ferias dif (1a) não informado.";
       $this->erro_campo = "r30_vliq1d";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vfgt1d == null ){ 
       $this->erro_sql = " Campo base fgts ferias dif (1a) não informado.";
       $this->erro_campo = "r30_vfgt1d";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_virf1d == null ){ 
       $this->erro_sql = " Campo base irfferias dif (1a) não informado.";
       $this->erro_campo = "r30_virf1d";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vpre1d == null ){ 
       $this->erro_sql = " Campo base prev ferias dif (1a) não informado.";
       $this->erro_campo = "r30_vpre1d";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vliq2 == null ){ 
       $this->erro_sql = " Campo valor ferias (2a) não informado.";
       $this->erro_campo = "r30_vliq2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vfgt2 == null ){ 
       $this->erro_sql = " Campo base fgts ferias (2a) não informado.";
       $this->erro_campo = "r30_vfgt2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_virf2 == null ){ 
       $this->erro_sql = " Campo base irf ferias (2a) não informado.";
       $this->erro_campo = "r30_virf2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vliq2d == null ){ 
       $this->erro_sql = " Campo valor ferias dif (2a) não informado.";
       $this->erro_campo = "r30_vliq2d";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_virf2d == null ){ 
       $this->erro_sql = " Campo base irf ferias dif (2a) não informado.";
       $this->erro_campo = "r30_virf2d";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vfgt2d == null ){ 
       $this->erro_sql = " Campo base fgts ferias dif (2a) não informado.";
       $this->erro_campo = "r30_vfgt2d";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vpre2d == null ){ 
       $this->erro_sql = " Campo base prev ferias dif (2a) não informado.";
       $this->erro_campo = "r30_vpre2d";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_vpre2 == null ){ 
       $this->erro_sql = " Campo base prev ferias (2a) não informado.";
       $this->erro_campo = "r30_vpre2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->r30_tip1 == null ){ 
       $this->erro_sql = " Campo Pgto da 1a parc ou total não informado.";
       $this->erro_campo = "r30_tip1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->r30_psal1 == null ){ 
       $this->erro_sql = " Campo .t. = pontofs/.f.=pontofe não informado.";
       $this->erro_campo = "r30_psal1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_psal2 == null ){ 
       $this->erro_sql = " Campo t.t= sal pontofs/.f. = pontofe não informado.";
       $this->erro_campo = "r30_psal2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->r30_ponto == null ){ 
       $this->erro_sql = " Campo Salario/Complementar não informado.";
       $this->erro_campo = "r30_ponto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_paga13 == null ){ 
       $this->erro_sql = " Campo pagamento so 1/3 das ferias não informado.";
       $this->erro_campo = "r30_paga13";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_descad == null ){ 
       $this->erro_sql = " Campo descontos sobre o adiantamento não informado.";
       $this->erro_campo = "r30_descad";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r30_tipoapuracaomedia == null ){ 
       $this->r30_tipoapuracaomedia = "1";
     }
     if($this->r30_periodolivreinicial == null ){ 
       $this->r30_periodolivreinicial = "null";
     }
     if($this->r30_periodolivrefinal == null ){ 
       $this->r30_periodolivrefinal = "null";
     }
     $sql = "insert into cadferia(
                                       r30_anousu 
                                      ,r30_mesusu 
                                      ,r30_numcgm 
                                      ,r30_regist 
                                      ,r30_faltas 
                                      ,r30_perai 
                                      ,r30_peraf 
                                      ,r30_ndias 
                                      ,r30_dias1 
                                      ,r30_dias2 
                                      ,r30_per1i 
                                      ,r30_per1f 
                                      ,r30_per2i 
                                      ,r30_per2f 
                                      ,r30_proc1 
                                      ,r30_proc2 
                                      ,r30_abono 
                                      ,r30_proc1d 
                                      ,r30_vliq1 
                                      ,r30_vfgt1 
                                      ,r30_virf1 
                                      ,r30_vpre1 
                                      ,r30_vliq1d 
                                      ,r30_vfgt1d 
                                      ,r30_virf1d 
                                      ,r30_vpre1d 
                                      ,r30_vliq2 
                                      ,r30_vfgt2 
                                      ,r30_virf2 
                                      ,r30_vliq2d 
                                      ,r30_virf2d 
                                      ,r30_vfgt2d 
                                      ,r30_vpre2d 
                                      ,r30_vpre2 
                                      ,r30_tip1 
                                      ,r30_tip2 
                                      ,r30_psal1 
                                      ,r30_psal2 
                                      ,r30_proc2d 
                                      ,r30_ponto 
                                      ,r30_paga13 
                                      ,r30_descad 
                                      ,r30_tipoapuracaomedia 
                                      ,r30_periodolivreinicial 
                                      ,r30_periodolivrefinal 
                                      ,r30_obs 
                       )
                values (
                                $this->r30_anousu 
                               ,$this->r30_mesusu 
                               ,$this->r30_numcgm 
                               ,$this->r30_regist 
                               ,$this->r30_faltas 
                               ,".($this->r30_perai == "null" || $this->r30_perai == ""?"null":"'".$this->r30_perai."'")." 
                               ,".($this->r30_peraf == "null" || $this->r30_peraf == ""?"null":"'".$this->r30_peraf."'")." 
                               ,$this->r30_ndias 
                               ,$this->r30_dias1 
                               ,$this->r30_dias2 
                               ,".($this->r30_per1i == "null" || $this->r30_per1i == ""?"null":"'".$this->r30_per1i."'")." 
                               ,".($this->r30_per1f == "null" || $this->r30_per1f == ""?"null":"'".$this->r30_per1f."'")." 
                               ,".($this->r30_per2i == "null" || $this->r30_per2i == ""?"null":"'".$this->r30_per2i."'")." 
                               ,".($this->r30_per2f == "null" || $this->r30_per2f == ""?"null":"'".$this->r30_per2f."'")." 
                               ,'$this->r30_proc1' 
                               ,'$this->r30_proc2' 
                               ,$this->r30_abono 
                               ,'$this->r30_proc1d' 
                               ,$this->r30_vliq1 
                               ,$this->r30_vfgt1 
                               ,$this->r30_virf1 
                               ,$this->r30_vpre1 
                               ,$this->r30_vliq1d 
                               ,$this->r30_vfgt1d 
                               ,$this->r30_virf1d 
                               ,$this->r30_vpre1d 
                               ,$this->r30_vliq2 
                               ,$this->r30_vfgt2 
                               ,$this->r30_virf2 
                               ,$this->r30_vliq2d 
                               ,$this->r30_virf2d 
                               ,$this->r30_vfgt2d 
                               ,$this->r30_vpre2d 
                               ,$this->r30_vpre2 
                               ,'$this->r30_tip1' 
                               ,'$this->r30_tip2' 
                               ,'$this->r30_psal1' 
                               ,'$this->r30_psal2' 
                               ,'$this->r30_proc2d' 
                               ,'$this->r30_ponto' 
                               ,'$this->r30_paga13' 
                               ,$this->r30_descad 
                               ,$this->r30_tipoapuracaomedia 
                               ,".($this->r30_periodolivreinicial == "null" || $this->r30_periodolivreinicial == ""?"null":"'".$this->r30_periodolivreinicial."'")." 
                               ,".($this->r30_periodolivrefinal == "null" || $this->r30_periodolivrefinal == ""?"null":"'".$this->r30_periodolivrefinal."'")." 
                               ,'$this->r30_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Ferias                                 () não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Ferias                                 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Ferias                                 () não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update cadferia set ";
     $virgula = "";
     if(trim($this->r30_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_anousu"])){ 
       $sql  .= $virgula." r30_anousu = $this->r30_anousu ";
       $virgula = ",";
       if(trim($this->r30_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio não informado.";
         $this->erro_campo = "r30_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_mesusu"])){ 
       $sql  .= $virgula." r30_mesusu = $this->r30_mesusu ";
       $virgula = ",";
       if(trim($this->r30_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio não informado.";
         $this->erro_campo = "r30_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_numcgm"])){ 
       $sql  .= $virgula." r30_numcgm = $this->r30_numcgm ";
       $virgula = ",";
       if(trim($this->r30_numcgm) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario no cgm não informado.";
         $this->erro_campo = "r30_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_regist"])){ 
       $sql  .= $virgula." r30_regist = $this->r30_regist ";
       $virgula = ",";
       if(trim($this->r30_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "r30_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_faltas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_faltas"])){ 
       $sql  .= $virgula." r30_faltas = $this->r30_faltas ";
       $virgula = ",";
       if(trim($this->r30_faltas) == null ){ 
         $this->erro_sql = " Campo Faltas Durante o Período Aquisitivo não informado.";
         $this->erro_campo = "r30_faltas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_perai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_perai_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r30_perai_dia"] !="") ){ 
       $sql  .= $virgula." r30_perai = '$this->r30_perai' ";
       $virgula = ",";
       if(trim($this->r30_perai) == null ){ 
         $this->erro_sql = " Campo Período Aquisitivo não informado.";
         $this->erro_campo = "r30_perai_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r30_perai_dia"])){ 
         $sql  .= $virgula." r30_perai = null ";
         $virgula = ",";
         if(trim($this->r30_perai) == null ){ 
           $this->erro_sql = " Campo Período Aquisitivo não informado.";
           $this->erro_campo = "r30_perai_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r30_peraf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_peraf_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r30_peraf_dia"] !="") ){ 
       $sql  .= $virgula." r30_peraf = '$this->r30_peraf' ";
       $virgula = ",";
       if(trim($this->r30_peraf) == null ){ 
         $this->erro_sql = " Campo Final Período não informado.";
         $this->erro_campo = "r30_peraf_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r30_peraf_dia"])){ 
         $sql  .= $virgula." r30_peraf = null ";
         $virgula = ",";
         if(trim($this->r30_peraf) == null ){ 
           $this->erro_sql = " Campo Final Período não informado.";
           $this->erro_campo = "r30_peraf_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r30_ndias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_ndias"])){ 
       $sql  .= $virgula." r30_ndias = $this->r30_ndias ";
       $virgula = ",";
       if(trim($this->r30_ndias) == null ){ 
         $this->erro_sql = " Campo Total de Dias a Gozar não informado.";
         $this->erro_campo = "r30_ndias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_dias1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_dias1"])){ 
       $sql  .= $virgula." r30_dias1 = $this->r30_dias1 ";
       $virgula = ",";
       if(trim($this->r30_dias1) == null ){ 
         $this->erro_sql = " Campo Dias não informado.";
         $this->erro_campo = "r30_dias1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_dias2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_dias2"])){ 
       $sql  .= $virgula." r30_dias2 = $this->r30_dias2 ";
       $virgula = ",";
       if(trim($this->r30_dias2) == null ){ 
         $this->erro_sql = " Campo Dias não informado.";
         $this->erro_campo = "r30_dias2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_per1i)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_per1i_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r30_per1i_dia"] !="") ){ 
       $sql  .= $virgula." r30_per1i = '$this->r30_per1i' ";
       $virgula = ",";
       if(trim($this->r30_per1i) == null ){ 
         $this->erro_sql = " Campo Início Gozo não informado.";
         $this->erro_campo = "r30_per1i_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r30_per1i_dia"])){ 
         $sql  .= $virgula." r30_per1i = null ";
         $virgula = ",";
         if(trim($this->r30_per1i) == null ){ 
           $this->erro_sql = " Campo Início Gozo não informado.";
           $this->erro_campo = "r30_per1i_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r30_per1f)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_per1f_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r30_per1f_dia"] !="") ){ 
       $sql  .= $virgula." r30_per1f = '$this->r30_per1f' ";
       $virgula = ",";
       if(trim($this->r30_per1f) == null ){ 
         $this->erro_sql = " Campo Final Gozo não informado.";
         $this->erro_campo = "r30_per1f_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r30_per1f_dia"])){ 
         $sql  .= $virgula." r30_per1f = null ";
         $virgula = ",";
         if(trim($this->r30_per1f) == null ){ 
           $this->erro_sql = " Campo Final Gozo não informado.";
           $this->erro_campo = "r30_per1f_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r30_per2i)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_per2i_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r30_per2i_dia"] !="") ){ 
       $sql  .= $virgula." r30_per2i = '$this->r30_per2i' ";
       $virgula = ",";
       if(trim($this->r30_per2i) == null ){ 
         $this->erro_sql = " Campo Início Gozo não informado.";
         $this->erro_campo = "r30_per2i_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r30_per2i_dia"])){ 
         $sql  .= $virgula." r30_per2i = null ";
         $virgula = ",";
         if(trim($this->r30_per2i) == null ){ 
           $this->erro_sql = " Campo Início Gozo não informado.";
           $this->erro_campo = "r30_per2i_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r30_per2f)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_per2f_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r30_per2f_dia"] !="") ){ 
       $sql  .= $virgula." r30_per2f = '$this->r30_per2f' ";
       $virgula = ",";
       if(trim($this->r30_per2f) == null ){ 
         $this->erro_sql = " Campo Final Gozo não informado.";
         $this->erro_campo = "r30_per2f_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r30_per2f_dia"])){ 
         $sql  .= $virgula." r30_per2f = null ";
         $virgula = ",";
         if(trim($this->r30_per2f) == null ){ 
           $this->erro_sql = " Campo Final Gozo não informado.";
           $this->erro_campo = "r30_per2f_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r30_proc1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_proc1"])){ 
       $sql  .= $virgula." r30_proc1 = '$this->r30_proc1' ";
       $virgula = ",";
       if(trim($this->r30_proc1) == null ){ 
         $this->erro_sql = " Campo Pagamento do 1o. periodo não informado.";
         $this->erro_campo = "r30_proc1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_proc2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_proc2"])){ 
       $sql  .= $virgula." r30_proc2 = '$this->r30_proc2' ";
       $virgula = ",";
       if(trim($this->r30_proc2) == null ){ 
         $this->erro_sql = " Campo Pagamento do 2o periodo não informado.";
         $this->erro_campo = "r30_proc2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_abono)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_abono"])){ 
       $sql  .= $virgula." r30_abono = $this->r30_abono ";
       $virgula = ",";
       if(trim($this->r30_abono) == null ){ 
         $this->erro_sql = " Campo Número de Dias do Abono não informado.";
         $this->erro_campo = "r30_abono";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_proc1d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_proc1d"])){ 
       $sql  .= $virgula." r30_proc1d = '$this->r30_proc1d' ";
       $virgula = ",";
       if(trim($this->r30_proc1d) == null ){ 
         $this->erro_sql = " Campo Ano/Mes do pagto da 1a dif não informado.";
         $this->erro_campo = "r30_proc1d";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vliq1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vliq1"])){ 
       $sql  .= $virgula." r30_vliq1 = $this->r30_vliq1 ";
       $virgula = ",";
       if(trim($this->r30_vliq1) == null ){ 
         $this->erro_sql = " Campo vlr total das ferias - 1a não informado.";
         $this->erro_campo = "r30_vliq1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vfgt1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vfgt1"])){ 
       $sql  .= $virgula." r30_vfgt1 = $this->r30_vfgt1 ";
       $virgula = ",";
       if(trim($this->r30_vfgt1) == null ){ 
         $this->erro_sql = " Campo base fgts ferias (1a) não informado.";
         $this->erro_campo = "r30_vfgt1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_virf1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_virf1"])){ 
       $sql  .= $virgula." r30_virf1 = $this->r30_virf1 ";
       $virgula = ",";
       if(trim($this->r30_virf1) == null ){ 
         $this->erro_sql = " Campo base irf ferias (1a) não informado.";
         $this->erro_campo = "r30_virf1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vpre1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vpre1"])){ 
       $sql  .= $virgula." r30_vpre1 = $this->r30_vpre1 ";
       $virgula = ",";
       if(trim($this->r30_vpre1) == null ){ 
         $this->erro_sql = " Campo base prev ferias (1a) não informado.";
         $this->erro_campo = "r30_vpre1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vliq1d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vliq1d"])){ 
       $sql  .= $virgula." r30_vliq1d = $this->r30_vliq1d ";
       $virgula = ",";
       if(trim($this->r30_vliq1d) == null ){ 
         $this->erro_sql = " Campo vlr ferias dif (1a) não informado.";
         $this->erro_campo = "r30_vliq1d";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vfgt1d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vfgt1d"])){ 
       $sql  .= $virgula." r30_vfgt1d = $this->r30_vfgt1d ";
       $virgula = ",";
       if(trim($this->r30_vfgt1d) == null ){ 
         $this->erro_sql = " Campo base fgts ferias dif (1a) não informado.";
         $this->erro_campo = "r30_vfgt1d";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_virf1d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_virf1d"])){ 
       $sql  .= $virgula." r30_virf1d = $this->r30_virf1d ";
       $virgula = ",";
       if(trim($this->r30_virf1d) == null ){ 
         $this->erro_sql = " Campo base irfferias dif (1a) não informado.";
         $this->erro_campo = "r30_virf1d";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vpre1d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vpre1d"])){ 
       $sql  .= $virgula." r30_vpre1d = $this->r30_vpre1d ";
       $virgula = ",";
       if(trim($this->r30_vpre1d) == null ){ 
         $this->erro_sql = " Campo base prev ferias dif (1a) não informado.";
         $this->erro_campo = "r30_vpre1d";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vliq2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vliq2"])){ 
       $sql  .= $virgula." r30_vliq2 = $this->r30_vliq2 ";
       $virgula = ",";
       if(trim($this->r30_vliq2) == null ){ 
         $this->erro_sql = " Campo valor ferias (2a) não informado.";
         $this->erro_campo = "r30_vliq2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vfgt2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vfgt2"])){ 
       $sql  .= $virgula." r30_vfgt2 = $this->r30_vfgt2 ";
       $virgula = ",";
       if(trim($this->r30_vfgt2) == null ){ 
         $this->erro_sql = " Campo base fgts ferias (2a) não informado.";
         $this->erro_campo = "r30_vfgt2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_virf2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_virf2"])){ 
       $sql  .= $virgula." r30_virf2 = $this->r30_virf2 ";
       $virgula = ",";
       if(trim($this->r30_virf2) == null ){ 
         $this->erro_sql = " Campo base irf ferias (2a) não informado.";
         $this->erro_campo = "r30_virf2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vliq2d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vliq2d"])){ 
       $sql  .= $virgula." r30_vliq2d = $this->r30_vliq2d ";
       $virgula = ",";
       if(trim($this->r30_vliq2d) == null ){ 
         $this->erro_sql = " Campo valor ferias dif (2a) não informado.";
         $this->erro_campo = "r30_vliq2d";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_virf2d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_virf2d"])){ 
       $sql  .= $virgula." r30_virf2d = $this->r30_virf2d ";
       $virgula = ",";
       if(trim($this->r30_virf2d) == null ){ 
         $this->erro_sql = " Campo base irf ferias dif (2a) não informado.";
         $this->erro_campo = "r30_virf2d";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vfgt2d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vfgt2d"])){ 
       $sql  .= $virgula." r30_vfgt2d = $this->r30_vfgt2d ";
       $virgula = ",";
       if(trim($this->r30_vfgt2d) == null ){ 
         $this->erro_sql = " Campo base fgts ferias dif (2a) não informado.";
         $this->erro_campo = "r30_vfgt2d";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vpre2d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vpre2d"])){ 
       $sql  .= $virgula." r30_vpre2d = $this->r30_vpre2d ";
       $virgula = ",";
       if(trim($this->r30_vpre2d) == null ){ 
         $this->erro_sql = " Campo base prev ferias dif (2a) não informado.";
         $this->erro_campo = "r30_vpre2d";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_vpre2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_vpre2"])){ 
       $sql  .= $virgula." r30_vpre2 = $this->r30_vpre2 ";
       $virgula = ",";
       if(trim($this->r30_vpre2) == null ){ 
         $this->erro_sql = " Campo base prev ferias (2a) não informado.";
         $this->erro_campo = "r30_vpre2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_tip1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_tip1"])){ 
       $sql  .= $virgula." r30_tip1 = '$this->r30_tip1' ";
       $virgula = ",";
       if(trim($this->r30_tip1) == null ){ 
         $this->erro_sql = " Campo Pgto da 1a parc ou total não informado.";
         $this->erro_campo = "r30_tip1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_tip2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_tip2"])){ 
       $sql  .= $virgula." r30_tip2 = '$this->r30_tip2' ";
       $virgula = ",";
       if(trim($this->r30_tip2) == null ){ 
         $this->erro_sql = " Campo Pgto da 2a parcela não informado.";
         $this->erro_campo = "r30_tip2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_psal1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_psal1"])){ 
       $sql  .= $virgula." r30_psal1 = '$this->r30_psal1' ";
       $virgula = ",";
       if(trim($this->r30_psal1) == null ){ 
         $this->erro_sql = " Campo .t. = pontofs/.f.=pontofe não informado.";
         $this->erro_campo = "r30_psal1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_psal2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_psal2"])){ 
       $sql  .= $virgula." r30_psal2 = '$this->r30_psal2' ";
       $virgula = ",";
       if(trim($this->r30_psal2) == null ){ 
         $this->erro_sql = " Campo t.t= sal pontofs/.f. = pontofe não informado.";
         $this->erro_campo = "r30_psal2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_proc2d)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_proc2d"])){ 
       $sql  .= $virgula." r30_proc2d = '$this->r30_proc2d' ";
       $virgula = ",";
       if(trim($this->r30_proc2d) == null ){ 
         $this->erro_sql = " Campo Ano/Mês do pagto da dif do 2o período não informado.";
         $this->erro_campo = "r30_proc2d";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_ponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_ponto"])){ 
       $sql  .= $virgula." r30_ponto = '$this->r30_ponto' ";
       $virgula = ",";
       if(trim($this->r30_ponto) == null ){ 
         $this->erro_sql = " Campo Salario/Complementar não informado.";
         $this->erro_campo = "r30_ponto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_paga13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_paga13"])){ 
       $sql  .= $virgula." r30_paga13 = '$this->r30_paga13' ";
       $virgula = ",";
       if(trim($this->r30_paga13) == null ){ 
         $this->erro_sql = " Campo pagamento so 1/3 das ferias não informado.";
         $this->erro_campo = "r30_paga13";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_descad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_descad"])){ 
       $sql  .= $virgula." r30_descad = $this->r30_descad ";
       $virgula = ",";
       if(trim($this->r30_descad) == null ){ 
         $this->erro_sql = " Campo descontos sobre o adiantamento não informado.";
         $this->erro_campo = "r30_descad";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r30_tipoapuracaomedia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_tipoapuracaomedia"])){ 
        if(trim($this->r30_tipoapuracaomedia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r30_tipoapuracaomedia"])){ 
           $this->r30_tipoapuracaomedia = "0" ; 
        } 
       $sql  .= $virgula." r30_tipoapuracaomedia = $this->r30_tipoapuracaomedia ";
       $virgula = ",";
     }
     if(trim($this->r30_periodolivreinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_periodolivreinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r30_periodolivreinicial_dia"] !="") ){ 
       $sql  .= $virgula." r30_periodolivreinicial = '$this->r30_periodolivreinicial' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r30_periodolivreinicial_dia"])){ 
         $sql  .= $virgula." r30_periodolivreinicial = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r30_periodolivrefinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_periodolivrefinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r30_periodolivrefinal_dia"] !="") ){ 
       $sql  .= $virgula." r30_periodolivrefinal = '$this->r30_periodolivrefinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r30_periodolivrefinal_dia"])){ 
         $sql  .= $virgula." r30_periodolivrefinal = null ";
         $virgula = ",";
       }
     }
     if(trim($this->r30_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r30_obs"])){ 
       $sql  .= $virgula." r30_obs = '$this->r30_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Ferias                                 não Alterado. Alteração Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Ferias                                 não foi Alterado. Alteração Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ( $oid=null ,$dbwhere=null) { 

     $sql = " delete from cadferia
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
       $sql2 = "oid = '$oid'";
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Ferias                                 não Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Ferias                                 não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:cadferia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($oid = null, $campos = "cadferia.oid,*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from cadferia ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = cadferia.r30_anousu and  pessoal.r01_mesusu = cadferia.r30_mesusu and  pessoal.r01_regist = cadferia.r30_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      left  join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu and  funcao.r37_mesusu = pessoal.r01_mesusu and  funcao.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pessoal.r01_anousu and  lotacao.r13_mesusu = pessoal.r01_mesusu and  lotacao.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu and  cargo.r65_mesusu = pessoal.r01_mesusu and  cargo.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($oid)) {
          $sql2 = " where cadferia.oid = '$oid'";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($oid = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cadferia ";
     $sql2 = "";
     if (empty($dbwhere)) {
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   function sql_query_pesquisa ( $oid = null,$campos="cadferia.oid,*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from cadferia ";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_anousu  = cadferia.r30_anousu 
                                            and  rhpessoalmov.rh02_mesusu  = cadferia.r30_mesusu
                              					    and  rhpessoalmov.rh02_regist  = cadferia.r30_regist
																						and  rhpessoalmov.rh02_instit  = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhpessoal     on  rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist " ; 
     $sql .= "      inner join cgm           on  cgm.z01_numcgm            = rhpessoal.rh01_numcgm";
     $sql .= "      left  join rhpesrescisao on  rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where cadferia.oid = $oid";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
