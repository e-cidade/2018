<?php
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
//CLASSE DA ENTIDADE rhpessoalmov
class cl_rhpessoalmov { 
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
   var $rh02_instit = 0; 
   var $rh02_seqpes = 0; 
   var $rh02_anousu = 0; 
   var $rh02_mesusu = 0; 
   var $rh02_regist = 0; 
   var $rh02_codreg = 0; 
   var $rh02_tipsal = null; 
   var $rh02_folha = null; 
   var $rh02_fpagto = 0; 
   var $rh02_tbprev = 0; 
   var $rh02_hrsmen = 0; 
   var $rh02_hrssem = 0; 
   var $rh02_ocorre = null; 
   var $rh02_equip = 'f'; 
   var $rh02_tpcont = 0; 
   var $rh02_vincrais = 0; 
   var $rh02_salari = 0; 
   var $rh02_lota = 0; 
   var $rh02_funcao = 0; 
   var $rh02_rhtipoapos = 0; 
   var $rh02_validadepensao_dia = null; 
   var $rh02_validadepensao_mes = null; 
   var $rh02_validadepensao_ano = null; 
   var $rh02_validadepensao = null; 
   var $rh02_deficientefisico = 'f'; 
   var $rh02_portadormolestia = 'f'; 
   var $rh02_datalaudomolestia_dia = null; 
   var $rh02_datalaudomolestia_mes = null; 
   var $rh02_datalaudomolestia_ano = null; 
   var $rh02_datalaudomolestia = null; 
   var $rh02_tipodeficiencia = 0; 
   var $rh02_abonopermanencia = 'f'; 
   var $rh02_diasgozoferias = 0; 
   var $rh02_horasdiarias = 0; 
   var $rh02_cedencia = null; 
   var $rh02_onus = null; 
   var $rh02_ressarcimento = null; 
   var $rh02_datacedencia_dia = null; 
   var $rh02_datacedencia_mes = null; 
   var $rh02_datacedencia_ano = null; 
   var $rh02_datacedencia = null; 
   var $rh02_cnpjcedencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh02_instit = int4 = Cod. Instituição 
                 rh02_seqpes = int4 = Sequência 
                 rh02_anousu = int4 = Ano do Exercício 
                 rh02_mesusu = int4 = Mês do Exercício 
                 rh02_regist = int4 = Registro 
    rh02_codreg = int4 = Regime
                 rh02_tipsal = varchar(1) = Tipo de Salário 
                 rh02_folha = varchar(1) = Tipo de Folha 
                 rh02_fpagto = int4 = Pagamento 
                 rh02_tbprev = int4 = Tab.  Previdência 
                 rh02_hrsmen = int4 = Horas Mensais 
                 rh02_hrssem = int4 = Horas Semanais 
                 rh02_ocorre = varchar(2) = Agentes Nocivos 
                 rh02_equip = bool = Equiparação 
                 rh02_tpcont = int4 = Tipo de Contrato 
                 rh02_vincrais = int4 = Vínculo 
                 rh02_salari = float8 = Salário 
                 rh02_lota = int4 = Código da Lotação 
                 rh02_funcao = int4 = Cargo 
                 rh02_rhtipoapos = int4 = Tipo de Apos./Pensão 
                 rh02_validadepensao = date = Validade Pensão 
                 rh02_deficientefisico = bool = Deficiente Físico 
                 rh02_portadormolestia = bool = Portador de Moléstia 
                 rh02_datalaudomolestia = date = Data do Laudo 
    rh02_tipodeficiencia = int4 = Código do tipo de deficiência
                 rh02_abonopermanencia = bool = Abono Permanência 
                 rh02_diasgozoferias = int4 = Dias Padrão a Gozar 
                 rh02_horasdiarias = int4 = Horas Diárias 
                 rh02_cedencia = char(1) = Tipo 
                 rh02_onus = char(1) = Onus 
                 rh02_ressarcimento = char(1) = Ressarcimento 
                 rh02_datacedencia = date = Data Movimentação 
                 rh02_cnpjcedencia =  varchar(2) = CNPJ Origem/Destino 
                 ";
   //funcao construtor da classe 
   function cl_rhpessoalmov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhpessoalmov"); 
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
       $this->rh02_instit = ($this->rh02_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_instit"]:$this->rh02_instit);
       $this->rh02_seqpes = ($this->rh02_seqpes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_seqpes"]:$this->rh02_seqpes);
       $this->rh02_anousu = ($this->rh02_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_anousu"]:$this->rh02_anousu);
       $this->rh02_mesusu = ($this->rh02_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_mesusu"]:$this->rh02_mesusu);
       $this->rh02_regist = ($this->rh02_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_regist"]:$this->rh02_regist);
       $this->rh02_codreg = ($this->rh02_codreg == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_codreg"]:$this->rh02_codreg);
       $this->rh02_tipsal = ($this->rh02_tipsal == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_tipsal"]:$this->rh02_tipsal);
       $this->rh02_folha = ($this->rh02_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_folha"]:$this->rh02_folha);
       $this->rh02_fpagto = ($this->rh02_fpagto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_fpagto"]:$this->rh02_fpagto);
       $this->rh02_tbprev = ($this->rh02_tbprev == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_tbprev"]:$this->rh02_tbprev);
       $this->rh02_hrsmen = ($this->rh02_hrsmen == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_hrsmen"]:$this->rh02_hrsmen);
       $this->rh02_hrssem = ($this->rh02_hrssem == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_hrssem"]:$this->rh02_hrssem);
       $this->rh02_ocorre = ($this->rh02_ocorre == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_ocorre"]:$this->rh02_ocorre);
       $this->rh02_equip = ($this->rh02_equip == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh02_equip"]:$this->rh02_equip);
       $this->rh02_tpcont = ($this->rh02_tpcont == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_tpcont"]:$this->rh02_tpcont);
       $this->rh02_vincrais = ($this->rh02_vincrais == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_vincrais"]:$this->rh02_vincrais);
       $this->rh02_salari = ($this->rh02_salari == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_salari"]:$this->rh02_salari);
       $this->rh02_lota = ($this->rh02_lota == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_lota"]:$this->rh02_lota);
       $this->rh02_funcao = ($this->rh02_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_funcao"]:$this->rh02_funcao);
       $this->rh02_rhtipoapos = ($this->rh02_rhtipoapos == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_rhtipoapos"]:$this->rh02_rhtipoapos);
       if($this->rh02_validadepensao == ""){
         $this->rh02_validadepensao_dia = ($this->rh02_validadepensao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_validadepensao_dia"]:$this->rh02_validadepensao_dia);
         $this->rh02_validadepensao_mes = ($this->rh02_validadepensao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_validadepensao_mes"]:$this->rh02_validadepensao_mes);
         $this->rh02_validadepensao_ano = ($this->rh02_validadepensao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_validadepensao_ano"]:$this->rh02_validadepensao_ano);
         if($this->rh02_validadepensao_dia != ""){
            $this->rh02_validadepensao = $this->rh02_validadepensao_ano."-".$this->rh02_validadepensao_mes."-".$this->rh02_validadepensao_dia;
         }
       }
       $this->rh02_deficientefisico = ($this->rh02_deficientefisico == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh02_deficientefisico"]:$this->rh02_deficientefisico);
       $this->rh02_portadormolestia = ($this->rh02_portadormolestia == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh02_portadormolestia"]:$this->rh02_portadormolestia);
       if($this->rh02_datalaudomolestia == ""){
         $this->rh02_datalaudomolestia_dia = ($this->rh02_datalaudomolestia_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_datalaudomolestia_dia"]:$this->rh02_datalaudomolestia_dia);
         $this->rh02_datalaudomolestia_mes = ($this->rh02_datalaudomolestia_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_datalaudomolestia_mes"]:$this->rh02_datalaudomolestia_mes);
         $this->rh02_datalaudomolestia_ano = ($this->rh02_datalaudomolestia_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_datalaudomolestia_ano"]:$this->rh02_datalaudomolestia_ano);
         if($this->rh02_datalaudomolestia_dia != ""){
            $this->rh02_datalaudomolestia = $this->rh02_datalaudomolestia_ano."-".$this->rh02_datalaudomolestia_mes."-".$this->rh02_datalaudomolestia_dia;
         }
       }
       $this->rh02_tipodeficiencia = ($this->rh02_tipodeficiencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_tipodeficiencia"]:$this->rh02_tipodeficiencia);
       $this->rh02_abonopermanencia = ($this->rh02_abonopermanencia == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh02_abonopermanencia"]:$this->rh02_abonopermanencia);
       $this->rh02_diasgozoferias = ($this->rh02_diasgozoferias == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_diasgozoferias"]:$this->rh02_diasgozoferias);
       $this->rh02_horasdiarias = ($this->rh02_horasdiarias == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_horasdiarias"]:$this->rh02_horasdiarias);
       $this->rh02_cedencia = ($this->rh02_cedencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_cedencia"]:$this->rh02_cedencia);
       $this->rh02_onus = ($this->rh02_onus == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_onus"]:$this->rh02_onus);
       $this->rh02_ressarcimento = ($this->rh02_ressarcimento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_ressarcimento"]:$this->rh02_ressarcimento);
       $this->rh02_datacedencia = ($this->rh02_datacedencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_datacedencia"]:$this->rh02_datacedencia);
       $this->rh02_cnpjcedencia = ($this->rh02_cnpjcedencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_cnpjcedencia"]:$this->rh02_cnpjcedencia);
     }else{
       $this->rh02_instit = ($this->rh02_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_instit"]:$this->rh02_instit);
       $this->rh02_seqpes = ($this->rh02_seqpes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh02_seqpes"]:$this->rh02_seqpes);
     }
   }
   // funcao para Inclusão
   function incluir ($rh02_seqpes,$rh02_instit){ 
      $this->atualizacampos();
     if($this->rh02_anousu == null ){ 
       $this->erro_sql = " Campo Ano do Exercício não informado.";
       $this->erro_campo = "rh02_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_mesusu == null ){ 
       $this->erro_sql = " Campo Mês do Exercício não informado.";
       $this->erro_campo = "rh02_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_regist == null ){ 
       $this->erro_sql = " Campo Registro não informado.";
       $this->erro_campo = "rh02_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_codreg == null ){ 
      $this->erro_sql = " Campo Regime não informado.";
       $this->erro_campo = "rh02_codreg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_tipsal == null ){ 
       $this->erro_sql = " Campo Tipo de Salário não informado.";
       $this->erro_campo = "rh02_tipsal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_folha == null ){ 
       $this->erro_sql = " Campo Tipo de Folha não informado.";
       $this->erro_campo = "rh02_folha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_fpagto == null ){ 
       $this->erro_sql = " Campo Pagamento não informado.";
       $this->erro_campo = "rh02_fpagto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_tbprev == null ){ 
       $this->erro_sql = " Campo Tab.  Previdência não informado.";
       $this->erro_campo = "rh02_tbprev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_hrsmen == null ){ 
       $this->erro_sql = " Campo Horas Mensais não informado.";
       $this->erro_campo = "rh02_hrsmen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_hrssem == null ){ 
       $this->erro_sql = " Campo Horas Semanais não informado.";
       $this->erro_campo = "rh02_hrssem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_equip == null ){ 
       $this->erro_sql = " Campo Equiparação não informado.";
       $this->erro_campo = "rh02_equip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_tpcont == null ){ 
       $this->erro_sql = " Campo Tipo de Contrato não informado.";
       $this->erro_campo = "rh02_tpcont";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_vincrais == null ){ 
       $this->erro_sql = " Campo Vínculo não informado.";
       $this->erro_campo = "rh02_vincrais";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_salari == null ){ 
       $this->rh02_salari = "0";
     }
     if($this->rh02_lota == null ){ 
       $this->erro_sql = " Campo Código da Lotação não informado.";
       $this->erro_campo = "rh02_lota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_funcao == null ){ 
       $this->erro_sql = " Campo Cargo não informado.";
       $this->erro_campo = "rh02_funcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_rhtipoapos == null ){ 
       $this->rh02_rhtipoapos = "0";
     }
     if($this->rh02_validadepensao == null ){ 
       $this->rh02_validadepensao = "null";
     }
     if($this->rh02_deficientefisico == null ){ 
       $this->erro_sql = " Campo Deficiente Físico não informado.";
       $this->erro_campo = "rh02_deficientefisico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_portadormolestia == null ){ 
       $this->erro_sql = " Campo Portador de Moléstia não informado.";
       $this->erro_campo = "rh02_portadormolestia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_datalaudomolestia == null ){ 
       $this->rh02_datalaudomolestia = "null";
     }
     if($this->rh02_tipodeficiencia == null ){ 
       $this->rh02_tipodeficiencia = "0";
     }
     if($this->rh02_abonopermanencia == null ){ 
       $this->rh02_abonopermanencia = "f";
     }
     if($this->rh02_diasgozoferias == null ){ 
       $this->erro_sql = " Campo Dias Padrão a Gozar não informado.";
       $this->erro_campo = "rh02_diasgozoferias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_horasdiarias == null ){ 
       $this->rh02_horasdiarias = "0";
     }
     if($this->rh02_cedencia == null ){ 
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "rh02_cedencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_onus == null ){ 
       $this->erro_sql = " Campo Onus não informado.";
       $this->erro_campo = "rh02_onus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_ressarcimento == null ){ 
       $this->erro_sql = " Campo Ressarcimento não informado.";
       $this->erro_campo = "rh02_ressarcimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh02_datacedencia == null ){ 
       $this->rh02_datacedencia = "null";
     }

     if ($this->rh02_cnpjcedencia == null ) { 
       $this->rh02_cnpjcedencia = "";
     }

     if($rh02_seqpes == "" || $rh02_seqpes == null ){
       $result = db_query("select nextval('rhpessoalmov_rh02_seqpes_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalmov_rh02_seqpes_seq do campo: rh02_seqpes"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh02_seqpes = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalmov_rh02_seqpes_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh02_seqpes)){
         $this->erro_sql = " Campo rh02_seqpes maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh02_seqpes = $rh02_seqpes; 
       }
     }
     if(($this->rh02_seqpes == null) || ($this->rh02_seqpes == "") ){ 
       $this->erro_sql = " Campo rh02_seqpes não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh02_instit == null) || ($this->rh02_instit == "") ){ 
       $this->erro_sql = " Campo rh02_instit não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalmov(
                                       rh02_instit 
                                      ,rh02_seqpes 
                                      ,rh02_anousu 
                                      ,rh02_mesusu 
                                      ,rh02_regist 
                                      ,rh02_codreg 
                                      ,rh02_tipsal 
                                      ,rh02_folha 
                                      ,rh02_fpagto 
                                      ,rh02_tbprev 
                                      ,rh02_hrsmen 
                                      ,rh02_hrssem 
                                      ,rh02_ocorre 
                                      ,rh02_equip 
                                      ,rh02_tpcont 
                                      ,rh02_vincrais 
                                      ,rh02_salari 
                                      ,rh02_lota 
                                      ,rh02_funcao 
                                      ,rh02_rhtipoapos 
                                      ,rh02_validadepensao 
                                      ,rh02_deficientefisico 
                                      ,rh02_portadormolestia 
                                      ,rh02_datalaudomolestia 
                                      ,rh02_tipodeficiencia 
                                      ,rh02_abonopermanencia 
                                      ,rh02_diasgozoferias 
                                      ,rh02_horasdiarias 
                                      ,rh02_cedencia 
                                      ,rh02_onus 
                                      ,rh02_ressarcimento 
                                      ,rh02_datacedencia 
                                      ,rh02_cnpjcedencia 
                       )
                values (
                                $this->rh02_instit 
                               ,$this->rh02_seqpes 
                               ,$this->rh02_anousu 
                               ,$this->rh02_mesusu 
                               ,$this->rh02_regist 
                               ,$this->rh02_codreg 
                               ,'$this->rh02_tipsal' 
                               ,'$this->rh02_folha' 
                               ,$this->rh02_fpagto 
                               ,$this->rh02_tbprev 
                               ,$this->rh02_hrsmen 
                               ,$this->rh02_hrssem 
                               ,'$this->rh02_ocorre' 
                               ,'$this->rh02_equip' 
                               ,$this->rh02_tpcont 
                               ,$this->rh02_vincrais 
                               ,$this->rh02_salari 
                               ,$this->rh02_lota 
                               ,$this->rh02_funcao 
                               ,$this->rh02_rhtipoapos 
                               ,".($this->rh02_validadepensao == "null" || $this->rh02_validadepensao == ""?"null":"'".$this->rh02_validadepensao."'")." 
                               ,'$this->rh02_deficientefisico' 
                               ,'$this->rh02_portadormolestia' 
                               ,".($this->rh02_datalaudomolestia == "null" || $this->rh02_datalaudomolestia == ""?"null":"'".$this->rh02_datalaudomolestia."'")." 
                               ,$this->rh02_tipodeficiencia 
                               ,'$this->rh02_abonopermanencia' 
                               ,$this->rh02_diasgozoferias 
                               ,$this->rh02_horasdiarias 
                               ,'$this->rh02_cedencia' 
                               ,'$this->rh02_onus' 
                               ,'$this->rh02_ressarcimento' 
                               ,".($this->rh02_datacedencia == "null" || $this->rh02_datacedencia == ""?"null":"'".$this->rh02_datacedencia."'")." 
                               ,'$this->rh02_cnpjcedencia' 
                      )";

     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de pessoal ($this->rh02_seqpes."-".$this->rh02_instit) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de pessoal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de pessoal ($this->rh02_seqpes."-".$this->rh02_instit) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh02_seqpes."-".$this->rh02_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh02_seqpes,$this->rh02_instit  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7021,'$this->rh02_seqpes','I')");
         $resac = db_query("insert into db_acountkey values($acount,9913,'$this->rh02_instit','I')");
         $resac = db_query("insert into db_acount values($acount,1158,9913,'','".AddSlashes(pg_result($resaco,0,'rh02_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7021,'','".AddSlashes(pg_result($resaco,0,'rh02_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7022,'','".AddSlashes(pg_result($resaco,0,'rh02_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7023,'','".AddSlashes(pg_result($resaco,0,'rh02_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7024,'','".AddSlashes(pg_result($resaco,0,'rh02_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7025,'','".AddSlashes(pg_result($resaco,0,'rh02_codreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7026,'','".AddSlashes(pg_result($resaco,0,'rh02_tipsal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7027,'','".AddSlashes(pg_result($resaco,0,'rh02_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7028,'','".AddSlashes(pg_result($resaco,0,'rh02_fpagto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7034,'','".AddSlashes(pg_result($resaco,0,'rh02_tbprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7035,'','".AddSlashes(pg_result($resaco,0,'rh02_hrsmen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7036,'','".AddSlashes(pg_result($resaco,0,'rh02_hrssem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7037,'','".AddSlashes(pg_result($resaco,0,'rh02_ocorre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7038,'','".AddSlashes(pg_result($resaco,0,'rh02_equip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7637,'','".AddSlashes(pg_result($resaco,0,'rh02_tpcont'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7638,'','".AddSlashes(pg_result($resaco,0,'rh02_vincrais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,7042,'','".AddSlashes(pg_result($resaco,0,'rh02_salari'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,9454,'','".AddSlashes(pg_result($resaco,0,'rh02_lota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,15594,'','".AddSlashes(pg_result($resaco,0,'rh02_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,15614,'','".AddSlashes(pg_result($resaco,0,'rh02_rhtipoapos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,15615,'','".AddSlashes(pg_result($resaco,0,'rh02_validadepensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,17761,'','".AddSlashes(pg_result($resaco,0,'rh02_deficientefisico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,17762,'','".AddSlashes(pg_result($resaco,0,'rh02_portadormolestia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,17763,'','".AddSlashes(pg_result($resaco,0,'rh02_datalaudomolestia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,20972,'','".AddSlashes(pg_result($resaco,0,'rh02_tipodeficiencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,20987,'','".AddSlashes(pg_result($resaco,0,'rh02_abonopermanencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,21277,'','".AddSlashes(pg_result($resaco,0,'rh02_diasgozoferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,21933,'','".AddSlashes(pg_result($resaco,0,'rh02_horasdiarias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,21934,'','".AddSlashes(pg_result($resaco,0,'rh02_cedencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,21935,'','".AddSlashes(pg_result($resaco,0,'rh02_onus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,21936,'','".AddSlashes(pg_result($resaco,0,'rh02_ressarcimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,21937,'','".AddSlashes(pg_result($resaco,0,'rh02_datacedencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1158,21938,'','".AddSlashes(pg_result($resaco,0,'rh02_cnpjcedencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh02_seqpes=null,$rh02_instit=null) { 

     $this->atualizacampos();

     $sql = " update rhpessoalmov set ";
     $virgula = "";
     if(trim($this->rh02_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_instit"])){ 
       $sql  .= $virgula." rh02_instit = $this->rh02_instit ";
       $virgula = ",";
       if(trim($this->rh02_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição não informado.";
         $this->erro_campo = "rh02_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_seqpes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_seqpes"])){ 
       $sql  .= $virgula." rh02_seqpes = $this->rh02_seqpes ";
       $virgula = ",";
       if(trim($this->rh02_seqpes) == null ){ 
         $this->erro_sql = " Campo Sequência não informado.";
         $this->erro_campo = "rh02_seqpes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_anousu"])){ 
       $sql  .= $virgula." rh02_anousu = $this->rh02_anousu ";
       $virgula = ",";
       if(trim($this->rh02_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercício não informado.";
         $this->erro_campo = "rh02_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_mesusu"])){ 
       $sql  .= $virgula." rh02_mesusu = $this->rh02_mesusu ";
       $virgula = ",";
       if(trim($this->rh02_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês do Exercício não informado.";
         $this->erro_campo = "rh02_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_regist"])){ 
       $sql  .= $virgula." rh02_regist = $this->rh02_regist ";
       $virgula = ",";
       if(trim($this->rh02_regist) == null ){ 
         $this->erro_sql = " Campo Registro não informado.";
         $this->erro_campo = "rh02_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_codreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_codreg"])){ 
       $sql  .= $virgula." rh02_codreg = $this->rh02_codreg ";
       $virgula = ",";
       if(trim($this->rh02_codreg) == null ){ 
        $this->erro_sql = " Campo Regime não informado.";
         $this->erro_campo = "rh02_codreg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_tipsal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_tipsal"])){ 
       $sql  .= $virgula." rh02_tipsal = '$this->rh02_tipsal' ";
       $virgula = ",";
       if(trim($this->rh02_tipsal) == null ){ 
         $this->erro_sql = " Campo Tipo de Salário não informado.";
         $this->erro_campo = "rh02_tipsal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_folha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_folha"])){ 
       $sql  .= $virgula." rh02_folha = '$this->rh02_folha' ";
       $virgula = ",";
       if(trim($this->rh02_folha) == null ){ 
         $this->erro_sql = " Campo Tipo de Folha não informado.";
         $this->erro_campo = "rh02_folha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_fpagto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_fpagto"])){ 
       $sql  .= $virgula." rh02_fpagto = $this->rh02_fpagto ";
       $virgula = ",";
       if(trim($this->rh02_fpagto) == null ){ 
         $this->erro_sql = " Campo Pagamento não informado.";
         $this->erro_campo = "rh02_fpagto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_tbprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_tbprev"])){ 
       $sql  .= $virgula." rh02_tbprev = $this->rh02_tbprev ";
       $virgula = ",";
       if(trim($this->rh02_tbprev) == null ){ 
         $this->erro_sql = " Campo Tab.  Previdência não informado.";
         $this->erro_campo = "rh02_tbprev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_hrsmen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_hrsmen"])){ 
       $sql  .= $virgula." rh02_hrsmen = $this->rh02_hrsmen ";
       $virgula = ",";
       if(trim($this->rh02_hrsmen) == null ){ 
         $this->erro_sql = " Campo Horas Mensais não informado.";
         $this->erro_campo = "rh02_hrsmen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_hrssem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_hrssem"])){ 
       $sql  .= $virgula." rh02_hrssem = $this->rh02_hrssem ";
       $virgula = ",";
       if(trim($this->rh02_hrssem) == null ){ 
         $this->erro_sql = " Campo Horas Semanais não informado.";
         $this->erro_campo = "rh02_hrssem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_ocorre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_ocorre"])){ 
       $sql  .= $virgula." rh02_ocorre = '$this->rh02_ocorre' ";
       $virgula = ",";
     }
     if(trim($this->rh02_equip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_equip"])){ 
       $sql  .= $virgula." rh02_equip = '$this->rh02_equip' ";
       $virgula = ",";
       if(trim($this->rh02_equip) == null ){ 
         $this->erro_sql = " Campo Equiparação não informado.";
         $this->erro_campo = "rh02_equip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_tpcont)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_tpcont"])){ 
       $sql  .= $virgula." rh02_tpcont = $this->rh02_tpcont ";
       $virgula = ",";
       if(trim($this->rh02_tpcont) == null ){ 
         $this->erro_sql = " Campo Tipo de Contrato não informado.";
         $this->erro_campo = "rh02_tpcont";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_vincrais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_vincrais"])){ 
       $sql  .= $virgula." rh02_vincrais = $this->rh02_vincrais ";
       $virgula = ",";
       if(trim($this->rh02_vincrais) == null ){ 
         $this->erro_sql = " Campo Vínculo não informado.";
         $this->erro_campo = "rh02_vincrais";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_salari)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_salari"])){ 
        if(trim($this->rh02_salari)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh02_salari"])){ 
           $this->rh02_salari = "0" ; 
        } 
       $sql  .= $virgula." rh02_salari = $this->rh02_salari ";
       $virgula = ",";
     }
     if(trim($this->rh02_lota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_lota"])){ 
       $sql  .= $virgula." rh02_lota = $this->rh02_lota ";
       $virgula = ",";
       if(trim($this->rh02_lota) == null ){ 
         $this->erro_sql = " Campo Código da Lotação não informado.";
         $this->erro_campo = "rh02_lota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_funcao"])){ 
       $sql  .= $virgula." rh02_funcao = $this->rh02_funcao ";
       $virgula = ",";
       if(trim($this->rh02_funcao) == null ){ 
         $this->erro_sql = " Campo Cargo não informado.";
         $this->erro_campo = "rh02_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_rhtipoapos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_rhtipoapos"])){ 
        if(trim($this->rh02_rhtipoapos)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh02_rhtipoapos"])){ 
           $this->rh02_rhtipoapos = "0" ; 
        } 
       $sql  .= $virgula." rh02_rhtipoapos = $this->rh02_rhtipoapos ";
       $virgula = ",";
     }
     if(trim($this->rh02_validadepensao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_validadepensao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh02_validadepensao_dia"] !="") ){ 
       $sql  .= $virgula." rh02_validadepensao = '$this->rh02_validadepensao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh02_validadepensao_dia"])){ 
         $sql  .= $virgula." rh02_validadepensao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh02_deficientefisico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_deficientefisico"])){ 
       $sql  .= $virgula." rh02_deficientefisico = '$this->rh02_deficientefisico' ";
       $virgula = ",";
       if(trim($this->rh02_deficientefisico) == null ){ 
         $this->erro_sql = " Campo Deficiente Físico não informado.";
         $this->erro_campo = "rh02_deficientefisico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_portadormolestia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_portadormolestia"])){ 
       $sql  .= $virgula." rh02_portadormolestia = '$this->rh02_portadormolestia' ";
       $virgula = ",";
       if(trim($this->rh02_portadormolestia) == null ){ 
         $this->erro_sql = " Campo Portador de Moléstia não informado.";
         $this->erro_campo = "rh02_portadormolestia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_datalaudomolestia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_datalaudomolestia_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh02_datalaudomolestia_dia"] !="") ){ 
       $sql  .= $virgula." rh02_datalaudomolestia = '$this->rh02_datalaudomolestia' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh02_datalaudomolestia_dia"])){ 
         $sql  .= $virgula." rh02_datalaudomolestia = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh02_tipodeficiencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_tipodeficiencia"])){ 
        if(trim($this->rh02_tipodeficiencia)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh02_tipodeficiencia"])){ 
           $this->rh02_tipodeficiencia = "0" ; 
        } 
       $sql  .= $virgula." rh02_tipodeficiencia = $this->rh02_tipodeficiencia ";
       $virgula = ",";
     }
     if(trim($this->rh02_abonopermanencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_abonopermanencia"])){ 

      if(!$this->rh02_abonopermanencia) {
        $this->rh02_abonopermanencia = 'f';
      }

       $sql  .= $virgula." rh02_abonopermanencia = '$this->rh02_abonopermanencia' ";
       $virgula = ",";
     }

     if(trim($this->rh02_diasgozoferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_diasgozoferias"])){ 
       $sql  .= $virgula." rh02_diasgozoferias = $this->rh02_diasgozoferias ";
       $virgula = ",";
       if(trim($this->rh02_diasgozoferias) == null ){ 
        $this->erro_sql = " Campo Dias padrão a Gozar não informado.";
         $this->erro_campo = "rh02_diasgozoferias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_horasdiarias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_horasdiarias"])){ 
        if(trim($this->rh02_horasdiarias)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh02_horasdiarias"])){ 
           $this->rh02_horasdiarias = "0" ; 
        } 
       $sql  .= $virgula." rh02_horasdiarias = $this->rh02_horasdiarias ";
       $virgula = ",";
     }
     if(trim($this->rh02_cedencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_cedencia"])){ 
       $sql  .= $virgula." rh02_cedencia = '$this->rh02_cedencia' ";
       $virgula = ",";
       if(trim($this->rh02_cedencia) == null ){ 
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "rh02_cedencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_onus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_onus"])){ 
       $sql  .= $virgula." rh02_onus = '$this->rh02_onus' ";
       $virgula = ",";
       if(trim($this->rh02_onus) == null ){ 
         $this->erro_sql = " Campo Onus não informado.";
         $this->erro_campo = "rh02_onus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh02_ressarcimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_ressarcimento"])){ 
       $sql  .= $virgula." rh02_ressarcimento = '$this->rh02_ressarcimento' ";
       $virgula = ",";
       if(trim($this->rh02_ressarcimento) == null ){ 
         $this->erro_sql = " Campo Ressarcimento não informado.";
         $this->erro_campo = "rh02_ressarcimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if(trim($this->rh02_datacedencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_datacedencia"]) && ($GLOBALS["HTTP_POST_VARS"]["rh02_datacedencia"] !="") ){ 
       $sql  .= $virgula." rh02_datacedencia = '$this->rh02_datacedencia' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh02_datacedencia"])){ 
         $sql  .= $virgula." rh02_datacedencia = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh02_cnpjcedencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh02_cnpjcedencia"]) && $GLOBALS["HTTP_POST_VARS"]["rh02_cnpjcedencia"] != "") { 
       $sql  .= $virgula." rh02_cnpjcedencia = '$this->rh02_cnpjcedencia' ";
       $virgula = ",";
     } else {
       $sql  .= $virgula." rh02_cnpjcedencia = null ";
       $virgula = ",";
     }
     $sql .= " where ";

     if($rh02_seqpes!=null){
       $sql .= " rh02_seqpes = $this->rh02_seqpes";
     }
     if($rh02_instit!=null){
       $sql .= " and  rh02_instit = $this->rh02_instit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh02_seqpes,$this->rh02_instit));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,7021,'$this->rh02_seqpes','A')");
           $resac = db_query("insert into db_acountkey values($acount,9913,'$this->rh02_instit','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_instit"]) || $this->rh02_instit != "")
             $resac = db_query("insert into db_acount values($acount,1158,9913,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_instit'))."','$this->rh02_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_seqpes"]) || $this->rh02_seqpes != "")
             $resac = db_query("insert into db_acount values($acount,1158,7021,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_seqpes'))."','$this->rh02_seqpes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_anousu"]) || $this->rh02_anousu != "")
             $resac = db_query("insert into db_acount values($acount,1158,7022,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_anousu'))."','$this->rh02_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_mesusu"]) || $this->rh02_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,1158,7023,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_mesusu'))."','$this->rh02_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_regist"]) || $this->rh02_regist != "")
             $resac = db_query("insert into db_acount values($acount,1158,7024,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_regist'))."','$this->rh02_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_codreg"]) || $this->rh02_codreg != "")
             $resac = db_query("insert into db_acount values($acount,1158,7025,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_codreg'))."','$this->rh02_codreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_tipsal"]) || $this->rh02_tipsal != "")
             $resac = db_query("insert into db_acount values($acount,1158,7026,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_tipsal'))."','$this->rh02_tipsal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_folha"]) || $this->rh02_folha != "")
             $resac = db_query("insert into db_acount values($acount,1158,7027,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_folha'))."','$this->rh02_folha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_fpagto"]) || $this->rh02_fpagto != "")
             $resac = db_query("insert into db_acount values($acount,1158,7028,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_fpagto'))."','$this->rh02_fpagto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_tbprev"]) || $this->rh02_tbprev != "")
             $resac = db_query("insert into db_acount values($acount,1158,7034,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_tbprev'))."','$this->rh02_tbprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_hrsmen"]) || $this->rh02_hrsmen != "")
             $resac = db_query("insert into db_acount values($acount,1158,7035,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_hrsmen'))."','$this->rh02_hrsmen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_hrssem"]) || $this->rh02_hrssem != "")
             $resac = db_query("insert into db_acount values($acount,1158,7036,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_hrssem'))."','$this->rh02_hrssem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_ocorre"]) || $this->rh02_ocorre != "")
             $resac = db_query("insert into db_acount values($acount,1158,7037,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_ocorre'))."','$this->rh02_ocorre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_equip"]) || $this->rh02_equip != "")
             $resac = db_query("insert into db_acount values($acount,1158,7038,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_equip'))."','$this->rh02_equip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_tpcont"]) || $this->rh02_tpcont != "")
             $resac = db_query("insert into db_acount values($acount,1158,7637,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_tpcont'))."','$this->rh02_tpcont',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_vincrais"]) || $this->rh02_vincrais != "")
             $resac = db_query("insert into db_acount values($acount,1158,7638,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_vincrais'))."','$this->rh02_vincrais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_salari"]) || $this->rh02_salari != "")
             $resac = db_query("insert into db_acount values($acount,1158,7042,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_salari'))."','$this->rh02_salari',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_lota"]) || $this->rh02_lota != "")
             $resac = db_query("insert into db_acount values($acount,1158,9454,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_lota'))."','$this->rh02_lota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_funcao"]) || $this->rh02_funcao != "")
             $resac = db_query("insert into db_acount values($acount,1158,15594,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_funcao'))."','$this->rh02_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_rhtipoapos"]) || $this->rh02_rhtipoapos != "")
             $resac = db_query("insert into db_acount values($acount,1158,15614,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_rhtipoapos'))."','$this->rh02_rhtipoapos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_validadepensao"]) || $this->rh02_validadepensao != "")
             $resac = db_query("insert into db_acount values($acount,1158,15615,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_validadepensao'))."','$this->rh02_validadepensao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_deficientefisico"]) || $this->rh02_deficientefisico != "")
             $resac = db_query("insert into db_acount values($acount,1158,17761,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_deficientefisico'))."','$this->rh02_deficientefisico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_portadormolestia"]) || $this->rh02_portadormolestia != "")
             $resac = db_query("insert into db_acount values($acount,1158,17762,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_portadormolestia'))."','$this->rh02_portadormolestia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_datalaudomolestia"]) || $this->rh02_datalaudomolestia != "")
             $resac = db_query("insert into db_acount values($acount,1158,17763,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_datalaudomolestia'))."','$this->rh02_datalaudomolestia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_tipodeficiencia"]) || $this->rh02_tipodeficiencia != "")
             $resac = db_query("insert into db_acount values($acount,1158,20972,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_tipodeficiencia'))."','$this->rh02_tipodeficiencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_abonopermanencia"]) || $this->rh02_abonopermanencia != "")
             $resac = db_query("insert into db_acount values($acount,1158,20987,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_abonopermanencia'))."','$this->rh02_abonopermanencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_diasgozoferias"]) || $this->rh02_diasgozoferias != "")
             $resac = db_query("insert into db_acount values($acount,1158,21277,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_diasgozoferias'))."','$this->rh02_diasgozoferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_horasdiarias"]) || $this->rh02_horasdiarias != "")
             $resac = db_query("insert into db_acount values($acount,1158,21933,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_horasdiarias'))."','$this->rh02_horasdiarias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_cedencia"]) || $this->rh02_cedencia != "")
             $resac = db_query("insert into db_acount values($acount,1158,21934,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_cedencia'))."','$this->rh02_cedencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_onus"]) || $this->rh02_onus != "")
             $resac = db_query("insert into db_acount values($acount,1158,21935,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_onus'))."','$this->rh02_onus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_ressarcimento"]) || $this->rh02_ressarcimento != "")
             $resac = db_query("insert into db_acount values($acount,1158,21936,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_ressarcimento'))."','$this->rh02_ressarcimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_datacedencia"]) || $this->rh02_datacedencia != "")
             $resac = db_query("insert into db_acount values($acount,1158,21937,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_datacedencia'))."','$this->rh02_datacedencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh02_cnpjcedencia"]) || $this->rh02_cnpjcedencia != "")
             $resac = db_query("insert into db_acount values($acount,1158,21938,'".AddSlashes(pg_result($resaco,$conresaco,'rh02_cnpjcedencia'))."','$this->rh02_cnpjcedencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     
     $result = db_query($sql);
     if (!$result) { 

       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de pessoal não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh02_seqpes."-".$this->rh02_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {

         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de pessoal não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh02_seqpes."-".$this->rh02_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh02_seqpes."-".$this->rh02_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh02_seqpes=null,$rh02_instit=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh02_seqpes,$rh02_instit));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,7021,'$rh02_seqpes','E')");
           $resac  = db_query("insert into db_acountkey values($acount,9913,'$rh02_instit','E')");
           $resac  = db_query("insert into db_acount values($acount,1158,9913,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7021,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_seqpes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7022,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7023,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7024,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7025,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_codreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7026,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_tipsal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7027,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7028,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_fpagto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7034,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_tbprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7035,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_hrsmen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7036,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_hrssem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7037,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_ocorre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7038,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_equip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7637,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_tpcont'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7638,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_vincrais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,7042,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_salari'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,9454,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_lota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,15594,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,15614,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_rhtipoapos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,15615,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_validadepensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,17761,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_deficientefisico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,17762,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_portadormolestia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,17763,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_datalaudomolestia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,20972,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_tipodeficiencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,20987,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_abonopermanencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,21277,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_diasgozoferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,21933,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_horasdiarias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,21934,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_cedencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,21935,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_onus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,21936,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_ressarcimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,21937,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_datacedencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1158,21938,'','".AddSlashes(pg_result($resaco,$iresaco,'rh02_cnpjcedencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalmov
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh02_seqpes)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh02_seqpes = $rh02_seqpes ";
        }
        if (!empty($rh02_instit)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh02_instit = $rh02_instit ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de pessoal não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh02_seqpes."-".$rh02_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de pessoal não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh02_seqpes."-".$rh02_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh02_seqpes."-".$rh02_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalmov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
  public function sql_query ( $rh02_seqpes=null,$rh02_instit=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= "  from rhpessoalmov ";
    $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhpessoalmov.rh02_regist     ";
    $sql .= "      inner join db_config  on  db_config.codigo = rhpessoalmov.rh02_instit          ";
    $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhpessoalmov.rh02_lota
      and  rhlota.r70_instit = rhpessoalmov.rh02_instit            ";
    $sql .= "      inner join rhregime  on  rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
      and  rhregime.rh30_instit = rhpessoalmov.rh02_instit       ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm                          ";
    $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = rhlota.r70_codestrut";
    $sql .= "      left  join tipodeficiencia  on  tipodeficiencia.rh150_sequencial = rhpessoalmov.rh02_tipodeficiencia";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($rh02_seqpes)) {
        $sql2 .= " where rhpessoalmov.rh02_seqpes = $rh02_seqpes ";
      }
      if (!empty($rh02_instit)) {
        if (!empty($sql2)) {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " rhpessoalmov.rh02_instit = $rh02_instit ";
      }
    } else if (!empty($dbwhere)) {
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
// funcao do sql
  public function sql_query_file ($rh02_seqpes = null,$rh02_instit = null, $campos = "*", $ordem = null, $dbwhere = "") {
    $sql = "select ";
    if($campos != "*" ) {
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from rhpessoalmov ";
    $sql2 = "";
    if(empty($dbwhere)){
      if(!empty($rh02_seqpes)){
        $sql2 .= " where rhpessoalmov.rh02_seqpes = $rh02_seqpes ";
      }
      if(!empty($rh02_instit)){
        if(!empty($sql2)){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " rhpessoalmov.rh02_instit = $rh02_instit ";
      }
    }else if(!empty($dbwhere)){
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

  function atualiza_incluir (){
    $this->incluir($this->rh02_seqpes);
  }

  function sql_query_rescisao ( $rh02_seqpes=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from rhpessoalmov ";
    $sql .= "      left  join rhpesrescisao  on  rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
    $sql2 = "";
    if($dbwhere==""){
      if($rh02_seqpes!=null ){
        $sql2 .= " where rhpessoalmov.rh02_seqpes = $rh02_seqpes ";
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

  /**
   * Parametros
   * @param integer $iMatricula
   * @param integer $iAno
   * @param integer $iMes
   * @return string
   */
  function sql_query_parametros($iMatricula, $iAno, $iMes) {

    $sSql  = "     select *                                       ";
    $sSql .= "       from rhpessoalmov                            ";
    $sSql .= " inner join rhregime on rh30_codreg = rh02_codreg   ";
    $sSql .= "      where rh02_regist             = {$iMatricula} ";
    $sSql .= "        and rh02_anousu             = {$iAno}       ";
    $sSql .= "        and rh02_mesusu             = {$iMes}       ";

    return $sSql;
  }

  /**
   * Monta query buscando todas as matriculas pelo tipo de folha e agrupando por regime, lotacao ou cargo
   */
  function sql_query_faixasSalariais( $oParametros, $sTipoFolha ) {

    $iAnoFolha        = $oParametros->iAnoFolha;
    $iMesFolha        = $oParametros->iMesFolha;
    $iInstituicao     = $oParametros->iInstituicao;
    $sQuebraRelatorio = $oParametros->sQuebraRelatorio;

    $sCampoAgrupador      = "";
    $sTabela              = "";
    $sSigla               = "";

    $aTiposFolha = array('salario'      => 'gerfsal',
      'complementar' => 'gerfcom',
      'rescisao'     => 'gerfres',
      'decimo13'     => 'gerfs13');

    $aSiglasFolha = array('salario'      => 'r14',
      'complementar' => 'r48',
      'rescisao'     => 'r20',
      'decimo13'     => 'r35');

    $aQuebra = array('regime'  => 'rh02_codreg',
      'lotacao' => 'rh02_lota'  ,
      'cargo'   => 'rh02_funcao');

    $aSubQueryQuebra = array("regime"  => "select rh30_descr from rhregime where rh30_codreg = agrupador",
      "lotacao" => "select r70_descr  from rhlota   where r70_codigo  = agrupador",
      "cargo"   => "select rh37_descr from rhfuncao where rh37_funcao = agrupador and rh37_instit = {$iInstituicao}",
      "geral"   => "select 'Geral'");

    $sTabela         = $aTiposFolha[$sTipoFolha];
    $sSigla          = $aSiglasFolha[$sTipoFolha];
    $sSubQuery       = $aSubQueryQuebra[$sQuebraRelatorio];
    $sCampoAgrupador = "1";

    if ( $sQuebraRelatorio != 'geral' ) {

      if ( !array_key_exists($sQuebraRelatorio, $aQuebra) ) {
        throw new ParameterException('Não existe tipo de Quebra Especificada.');
      }

      if ( !array_key_exists($sTipoFolha, $aTiposFolha) ) {
        throw new ParameterException('Não existe tipo de Folha Especificada.');
      }

      $sCampoAgrupador = $aQuebra[$sQuebraRelatorio];
    }

    $sSql  = "select sum({$sSigla}_valor)   as valor_provento,                          \n";
    $sSql .= "        {$sSigla}_regist      as matricula_servidor,                      \n";
    $sSql .= "        cast('{$sTipoFolha}'  as varchar) as tipo_folha,                  \n";
    $sSql .= "        agrupador             as codigo_agrupador,                        \n";
    $sSql .= "        ({$sSubQuery})        as descricao_agrupador                      \n";
    $sSql .= "  from (select {$sSigla}_regist,                                          \n";
    $sSql .= "               {$sCampoAgrupador}         as agrupador,                   \n";
    $sSql .= "               {$sSigla}_valor                                            \n";
    $sSql .= "          from {$sTabela}                                                 \n";
    $sSql .= "               inner join rhpessoal     on rh01_regist = {$sSigla}_regist \n";
    $sSql .= "               inner join rhpessoalmov  on rh02_anousu = {$sSigla}_anousu \n";
    $sSql .= "                                       and rh02_mesusu = {$sSigla}_mesusu \n";
    $sSql .= "                                       and rh02_regist = {$sSigla}_regist \n";
    $sSql .= "                                       and rh02_instit = {$sSigla}_instit \n";
    $sSql .= "        where {$sSigla}_anousu = {$iAnoFolha}                             \n";
    $sSql .= "          and {$sSigla}_mesusu = {$iMesFolha}                             \n";
    $sSql .= "          and {$sSigla}_pd     = 1                                        \n";
    $sSql .= "          and {$sSigla}_instit = {$iInstituicao}                          \n";
    $sSql .= "          and {$sSigla}_rubric < 'R950'                                   \n";

    if (!empty($oParametros->sSelecao)) {

      $sWhereSelecao  = str_replace("#s#", "{$sSigla}", $oParametros->sSelecao);
      $sSql          .= "         and {$sWhereSelecao}                                  \n";
    }

    if ( $sTabela == 'gerfcom' && !empty($oParametros->iComplementar) ) {
      $sSql .= " and r48_semest = {$oParametros->iComplementar}                         \n";
    }

    $sSql .= "       ) as x                                                             \n";
    $sSql .= "group by {$sSigla}_regist,                                                \n";
    $sSql .= "         agrupador                                                        \n";

    return  $sSql;
  }

  function sql_query_dados_bancario ( $rh02_seqpes=null,$rh02_instit=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql = "select ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from rhpessoalmov ";
    $sql .= "      inner join rhpesbanco on rh44_seqpes = rh02_seqpes ";
    $sql2 = "";
    if($dbwhere==""){

      if($rh02_seqpes!=null ){
        $sql2 .= " where rhpessoalmov.rh02_seqpes = $rh02_seqpes ";
      }
      if($rh02_instit!=null ){
        if($sql2!=""){
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " rhpessoalmov.rh02_instit = $rh02_instit ";
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

  function sql_queryFinanceiroPeloCodigo ($iAno, $iMes, $sTabela, $sSigla, $sWhere, $sOrdem, $lPeriodoAtual = false) {

    $iInstituicao = db_getsession('DB_instit');

    if ($lPeriodoAtual) {

      $iAnoFolha = "fc_anofolha($iInstituicao)";
      $iMesFolha = "fc_mesfolha($iInstituicao)";

    } else {

      $iAnoFolha = $iAno;
      $iMesFolha = $iMes;

    }

    $sSql  = "select {$sSigla}rubric as rubric,                                                      \n";
    $sSql .= "       rh27_descr,                                                                     \n";
    $sSql .= "       {$sSigla}regist as regist,                                                      \n";
    $sSql .= "       z01_nome,                                                                       \n";
    $sSql .= "       rh25_recurso,                                                                   \n";
    $sSql .= "       z01_numcgm,                                                                       \n";
    $sSql .= "       o15_descr,                                                                      \n";
    $sSql .= "       {$sSigla}quant as quant,                                                        \n";
    $sSql .= "       r70_estrut as lotacao,                                                          \n";
    $sSql .= "       {$sSigla}valor as valor,                                                        \n";
    $sSql .= "       r70_descr as descricao,                                                         \n";
    $sSql .= "       rh02_funcao as cargo,                                                           \n";
    $sSql .= "       rh37_descr as desc_cargo,                                                       \n";
    if ( $sSigla == "r90_" ) {
      $sSql .= "    0 as pd                                                               \n";
    } else {
      $sSql .= "       {$sSigla}pd as pd                                                               \n";
    }
    $sSql .= "  from {$sTabela}                                                                      \n";
    $sSql .= "       inner join rhpessoalmov on rhpessoalmov.rh02_regist = {$sSigla}regist           \n";
    $sSql .= "                              and rhpessoalmov.rh02_instit = {$sSigla}instit           \n";
    $sSql .= "                              and rhpessoalmov.rh02_anousu = {$iAno}                   \n";
    $sSql .= "                              and rhpessoalmov.rh02_mesusu = {$iMes}                   \n";
    $sSql .= "       inner join rhpessoal    on rhpessoal.rh01_regist    = rhpessoalmov.rh02_regist  \n";
    $sSql .= "       inner join rhregime     on rhregime.rh30_codreg     = rhpessoalmov.rh02_codreg  \n";
    $sSql .= "       inner join rhlota       on rhlota.r70_codigo        = rhpessoalmov.rh02_lota    \n";
    $sSql .= "                              and rhlota.r70_instit        = rhpessoalmov.rh02_instit  \n";
    $sSql .= "        left join rhlotaexe    on rhlotaexe.rh26_codigo    = rhlota.r70_codigo         \n";
    $sSql .= "                              and rhlotaexe.rh26_anousu    = rhpessoalmov.rh02_anousu  \n";
    $sSql .= "        left join (select distinct                                                     \n";
    $sSql .= "                          rh25_codigo,                                                 \n";
    $sSql .= "                          rh25_recurso,                                                \n";
    $sSql .= "                          rh25_projativ                                                \n";
    $sSql .= "                     from rhlotavinc                                                   \n";
    $sSql .= "                    where rh25_anousu = {$iAno})                                       \n";
    $sSql .= "               as rhlotavinc   on rhlotavinc.rh25_codigo   = rhpessoalmov.rh02_lota    \n";
    $sSql .= "        left join rhfuncao     on rhfuncao.rh37_funcao     = rhpessoalmov.rh02_funcao  \n";
    $sSql .= "                              and rhfuncao.rh37_instit     = rhpessoalmov.rh02_instit  \n";
    $sSql .= "        left join rhpescargo   on rhpescargo.rh20_seqpes   = rhpessoalmov.rh02_seqpes  \n";
    $sSql .= "        left join rhcargo      on rhcargo.rh04_codigo      = rhpescargo.rh20_cargo     \n";
    $sSql .= "                              and rhcargo.rh04_instit      = rhpessoalmov.rh02_instit  \n";
    $sSql .= "       inner join rhrubricas   on rhrubricas.rh27_rubric   = {$sSigla}rubric           \n";
    $sSql .= "                              and rhrubricas.rh27_instit   = {$iInstituicao}           \n";
    $sSql .= "        left join orctiporec   on orctiporec.o15_codigo    = rhlotavinc.rh25_recurso   \n";
    $sSql .= "       inner join cgm          on cgm.z01_numcgm           = rhpessoal.rh01_numcgm     \n";
    $sSql .= "        left join orcorgao     on orcorgao.o40_orgao       = rhlotaexe.rh26_orgao      \n";
    $sSql .= "                              and orcorgao.o40_anousu      = rhpessoalmov.rh02_anousu  \n";
    $sSql .= "                              and orcorgao.o40_instit      = rhpessoalmov.rh02_instit  \n";
    $sSql .= " where {$sSigla}anousu = {$iAno}                                                       \n";
    $sSql .= "   and {$sSigla}mesusu = {$iMes}                                                       \n";
    $sSql .= "   and {$sSigla}instit = {$iInstituicao}                                               \n";
    $sSql .= "       {$sWhere}                                                                       \n";
    $sSql .= "       {$sOrdem}                                                                       \n";

    return $sSql;

  }

  function sql_query_baseServidores( $iMesFolha, $iAnoFolha, $iInstituicao, $sCampos = "", $sWhere = "", $sOrdem = "", $sAgrupamento = ""  ) {

    if ( empty($sCampos) ) {
      $sCampos = "*";
    }
    $sSQLBase = "select {$sCampos}                                                                                              \n";
    $sSQLBase.= "  from rhpessoal                                                                                               \n";
    $sSQLBase.= "       inner join cgm                  on cgm.z01_numcgm                = rhpessoal.rh01_numcgm                \n";
    $sSQLBase.= "       inner join rhpessoalmov         on rhpessoalmov.rh02_regist      = rhpessoal.rh01_regist                \n";
    $sSQLBase.= "       left  join rhpescargo           on rhpescargo.rh20_seqpes        = rhpessoalmov.rh02_seqpes             \n";
    $sSQLBase.= "       left  join rhcargo              on rhcargo.rh04_codigo           = rhpescargo.rh20_cargo                \n";
    $sSQLBase.= "                                      and rhcargo.rh04_instit           = rhpessoalmov.rh02_instit             \n";
    $sSQLBase.= "       left  join rhfuncao             on rhfuncao.rh37_funcao          = rhpessoalmov.rh02_funcao             \n";
    $sSQLBase.= "                                      and rhfuncao.rh37_instit          = rhpessoalmov.rh02_instit             \n";
    $sSQLBase.= "       left  join rhlota               on rhlota.r70_codigo             = rhpessoalmov.rh02_lota               \n";
    $sSQLBase.= "                                      and rhlota.r70_instit             = rhpessoalmov.rh02_instit             \n";
    $sSQLBase.= "       left  join rhlotaexe            on rh26_codigo                   = r70_codigo                           \n";
    $sSQLBase.= "                                      and rh26_anousu                   = rh02_anousu                          \n";
    $sSQLBase.= "       left  join orcorgao             on o40_orgao                     = rh26_orgao                           \n";
    $sSQLBase.= "                                      and o40_anousu                    = rhpessoalmov.rh02_anousu             \n";
    $sSQLBase.= "                                      and o40_instit                    = rhpessoalmov.rh02_instit             \n";
    $sSQLBase.= "       left  join rhlotavinc           on rh25_codigo                   = r70_codigo                           \n";
    $sSQLBase.= "                                      and rh25_anousu                   = rhpessoalmov.rh02_anousu             \n";
    $sSQLBase.= "       left  join orctiporec           on o15_codigo                    = rh25_recurso                         \n";
    $sSQLBase.= "       inner join rhregime             on rhregime.rh30_codreg          = rhpessoalmov.rh02_codreg             \n";
    $sSQLBase.= "                                      and rhregime.rh30_instit          = rhpessoalmov.rh02_instit             \n";
    $sSQLBase.= "       left  join rhpesrescisao        on rhpesrescisao.rh05_seqpes     = rhpessoalmov.rh02_seqpes             \n";
    $sSQLBase.= "       left  join rhpespadrao          on rhpespadrao.rh03_seqpes       = rhpessoalmov.rh02_seqpes             \n";
    $sSQLBase.= "                                      and rhpespadrao.rh03_anousu       = rhpessoalmov.rh02_anousu             \n";
    $sSQLBase.= "                                      and rhpespadrao.rh03_mesusu       = rhpessoalmov.rh02_mesusu             \n";
    $sSQLBase.= "       left  join padroes              on padroes.r02_anousu            = rhpespadrao.rh03_anousu              \n";
    $sSQLBase.= "                                      and padroes.r02_mesusu            = rhpespadrao.rh03_mesusu              \n";
    $sSQLBase.= "                                      and padroes.r02_regime            = rhpespadrao.rh03_regime              \n";
    $sSQLBase.= "                                      and padroes.r02_codigo            = rhpespadrao.rh03_padrao              \n";
    $sSQLBase.= "                                      and padroes.r02_instit            = rhpessoalmov.rh02_instit             \n";
    $sSQLBase.= "       left  join rhpeslocaltrab       on rhpeslocaltrab.rh56_seqpes    = rhpessoalmov.rh02_seqpes             \n";
    $sSQLBase.= "                                      and rhpeslocaltrab.rh56_princ     = 't'                                  \n";
    $sSQLBase.= "       left  join rhlocaltrab          on rhpeslocaltrab.rh56_localtrab = rhlocaltrab.rh55_codigo              \n";
    $sSQLBase.= "       left  join rhpesdoc             on rhpesdoc.rh16_regist          = rhpessoal.rh01_regist                \n";
    $sSQLBase.= "       left  join rhpesbanco           on rhpesbanco.rh44_seqpes        = rhpessoalmov.rh02_seqpes             \n";
    $sSQLBase.= "       left  join (select distinct rhipe.*,                                                                    \n";
    $sSQLBase.= "                          rh01_regist as rh62_regist                                                           \n";
    $sSQLBase.= "                     from rhiperegist                                                                          \n";
    $sSQLBase.= "                          inner join rhipe     on rh14_sequencia = rh62_sequencia                              \n";
    $sSQLBase.= "                          inner join rhpessoal on rh62_regist    = rh01_regist                                 \n";
    $sSQLBase.= "                  ) as rhipe           on rh01_regist                   = rhipe.rh62_regist                    \n";
    $sSQLBase.= "       left  join rhinstrucao          on rhinstrucao.rh21_instru       = rhpessoal.rh01_instru                \n";
    $sSQLBase.= "       left  join rhestcivil           on rhestcivil.rh08_estciv        = rhpessoal.rh01_estciv                \n";

    $sSQLBase.= " where rh02_anousu = $iAnoFolha                                                                                \n";
    $sSQLBase.= "   and rh02_mesusu = $iMesFolha                                                                                \n";
    $sSQLBase.= "   and rh02_instit = $iInstituicao                                                                             \n";
    if ( !empty($sWhere) ) {
      $sSQLBase.= "   and {$sWhere}                                                                                             \n";
    }
    if ( !empty($sAgrupamento) ) {
      $sSQLBase.= "group by {$sAgrupamento}";
    }
    if ( !empty($sOrdem) ) {
      $sSQLBase.= " order by {$sOrdem}                                                                                          \n";
    }

    return $sSQLBase;
  }

  /**
   * Retorna informacoes dos servidores
   *
   * @param  integer $iAno
   * @param  integer $iMes
   * @param  integer $iInstituicao
   * @param  integer $iMatricula
   * @param  string  $sCampos
   * @access public
   * @return string
   */
  public function sql_queryDadosServidor( $iAno, $iMes, $iInstituicao, $iMatricula, $sCampos = '' ) {

    if ( empty($sCampos) ) {
      $sCampos = "*";
    }
    $sSql  = " select $sCampos                                                                    \n";
    $sSql .= "   from rhpessoal                                                                   \n";
    $sSql .= "        left join rhpessoalmov on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist  \n";
    $sSql .= "                              and rhpessoalmov.rh02_anousu = $iAno                  \n";
    $sSql .= "                              and rhpessoalmov.rh02_mesusu = $iMes                  \n";
    $sSql .= "                              and rhpessoalmov.rh02_instit = $iInstituicao          \n";
    $sSql .= "  where rh01_regist = $iMatricula                                                   \n";

    return $sSql;
  }

  public function sql_queryValorVariaveisCalculo( $iAnoFolha, $iMesFolha, $iMatricula, $iInstituicao ) {

    $sSql  = "select substr(valor_variaveis_calculo, 111, 11) as variavel_salario_base_progressao";
    $sSql .= "  from db_fxxx({$iMatricula},                                                      ";
    $sSql .= "               {$iAnoFolha},                                                       ";
    $sSql .= "               {$iMesFolha},                                                       ";
    $sSql .= "               {$iInstituicao}) as valor_variaveis_calculo                         ";

    return $sSql;

  }

  /**
   * Busca matricula posterior e anterior a corrente
   *
   * @param integer $iMatricula
   * @access public
   * @return string
   */
  public function sql_queryPaginacao( $iMatricula, $iAnoUso, $iMesUsu ) {

    $sSql  = " select ";

    /**
     * Busca matricula anterior a atual, e que possua algum calculo
     */
    $sSql .= " ( select                                                                            ";
    $sSql .= "   max(rhpessoalmov.rh02_regist)                                                     ";
    $sSql .= "     from rhpessoalmov                                                               ";
    $sSql .= "     left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
    $sSql .= "                            and rhpessoalmov.rh02_anousu = {$iAnoUso}                ";
    $sSql .= "                            and rhpessoalmov.rh02_mesusu = {$iMesUsu}                ";
    $sSql .= "       where rhpessoalmov.rh02_regist < {$iMatricula}                                ";
    $sSql .= "         and rhpessoalmov.rh02_anousu = {$iAnoUso}                                   ";
    $sSql .= "         and rhpessoalmov.rh02_mesusu = {$iMesUsu}                                   ";
    $sSql .= "         and (                                                                       ";
    $sSql .= "              rhpesrescisao.rh05_seqpes is null                                      ";
    $sSql .= "           or exists (select 1                                                       ";
    $sSql .= "                        from gerfres                                                 ";
    $sSql .= "                       where r20_regist = rhpessoalmov.rh02_regist                   ";
    $sSql .= "                         and r20_anousu = {$iAnoUso}                                 ";
    $sSql .= "                         and r20_mesusu = {$iMesUsu}                                 ";
    $sSql .= "                     )                                                               ";
    $sSql .= "         )                                                                           ";
    $sSql .= " ) as anterior,                                                                      ";

    /**
     * Busca matricula posterior a atual, e que possua algum calculo
     */
    $sSql .= " ( select                                                                                  ";
    $sSql .= "   min(rhpessoalmov.rh02_regist)                                                           ";
    $sSql .= "     from rhpessoalmov                                                                     ";
    $sSql .= "          left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes  ";
    $sSql .= "    where rhpessoalmov.rh02_regist > {$iMatricula}                                         ";
    $sSql .= "      and rhpessoalmov.rh02_anousu = {$iAnoUso}                                            ";
    $sSql .= "      and rhpessoalmov.rh02_mesusu = {$iMesUsu}                                            ";
    $sSql .= "      and ( rhpesrescisao.rh05_seqpes is null                                              ";
    $sSql .= "            or exists (select 1                                                            ";
    $sSql .= "            from gerfres                                                                   ";
    $sSql .= "           where r20_regist = rhpessoalmov.rh02_regist                                     ";
    $sSql .= "             and r20_anousu = {$iAnoUso}                                                   ";
    $sSql .= "             and r20_mesusu = {$iMesUsu}                                                   ";
    $sSql .= "          )                                                                                ";
    $sSql .= "      )                                                                                    ";
    $sSql .= " ) as posterior                                                                            ";

    $sSql .= "  from rhpessoal                   ";
    $sSql .= " where rh01_regist = {$iMatricula} ";

    return $sSql;
  }

  /**
   * Busca servidores por cargo, lotação e Secretarias
   *
   * @param string  $sCampos
   * @param integer $sWhere
   * @param integer $sOrder
   * @param integer $iInstit
   * @param integer $iAnoUsu
   * @param integer $iMesUsu
   *
   * @access public
   * @return string
   */
  public function sql_servidorCargoLotacaoSecretarias( $sCampos, $sWhere, $sOrder, $iInstit, $iAnoUsu, $iMesUsu ) {

    if ( empty($sCampos) ) {
      $sCampos = "*";
    }
    $sSql  =" select distinct rh02_regist                                                             as r01_regist,\n";
    $sSql .="        z01_nome,                                                                                      \n";
    $sSql .="        rh02_hrsmen,                                                                                   \n";
    $sSql .="        rh01_admiss,                                                                                   \n";
    $sSql .="        rh37_descr,                                                                                    \n";
    $sSql .="        r70_estrut                                                                       as cod_lota,  \n";
    $sSql .="        r70_descr                                                                        as descr_lota,\n";
    $sSql .="        rpad(trim(coalesce(z01_ender,''))||','||coalesce(z01_numero::char(4),''),40,' ') as endereco,  \n";
    $sSql .="        rpad(coalesce(z01_bairro,''),25,' ')                                             as z01_bairro,\n";
    $sSql .="        rpad(coalesce(z01_munic,''),25,' ')                                              as z01_munic, \n";
    $sSql .="        rpad(coalesce(z01_uf,''),2,' ')                                                  as z01_uf,    \n";
    $sSql .="        rpad(coalesce(z01_cep,''),8,' ')                                                 as z01_cep,   \n";
    $sSql .="        substr( db_fxxx( rh01_regist, $iAnoUsu, $iMesUsu, $iInstit), 111, 11)            as r02_valor, \n";
    $sSql .="        {$sCampos}                                                                                     \n";
    $sSql .= "  from rhpessoal                                                                                      \n";
    $sSql .= "       inner join cgm                  on cgm.z01_numcgm                = rhpessoal.rh01_numcgm       \n";
    $sSql .= "       inner join rhpessoalmov         on rhpessoalmov.rh02_regist      = rhpessoal.rh01_regist       \n";
    $sSql .= "       left  join rhpescargo           on rhpescargo.rh20_seqpes        = rhpessoalmov.rh02_seqpes    \n";
    $sSql .= "       left  join rhcargo              on rhcargo.rh04_codigo           = rhpescargo.rh20_cargo       \n";
    $sSql .= "                                      and rhcargo.rh04_instit           = rhpessoalmov.rh02_instit    \n";
    $sSql .= "       left  join rhfuncao             on rhfuncao.rh37_funcao          = rhpessoalmov.rh02_funcao    \n";
    $sSql .= "                                      and rhfuncao.rh37_instit          = rhpessoalmov.rh02_instit    \n";
    $sSql .= "       left  join rhlota               on rhlota.r70_codigo             = rhpessoalmov.rh02_lota      \n";
    $sSql .= "                                      and rhlota.r70_instit             = rhpessoalmov.rh02_instit    \n";
    $sSql .= "       left  join rhlotaexe            on rh26_codigo                   = r70_codigo                  \n";
    $sSql .= "                                      and rh26_anousu                   = rh02_anousu                 \n";
    $sSql .= "       left  join orcorgao             on o40_orgao                     = rh26_orgao                  \n";
    $sSql .= "                                      and o40_anousu                    = rhpessoalmov.rh02_anousu    \n";
    $sSql .= "                                      and o40_instit                    = rhpessoalmov.rh02_instit    \n";
    $sSql .= "       left  join rhlotavinc           on rh25_codigo                   = r70_codigo                  \n";
    $sSql .= "                                      and rh25_anousu                   = rhpessoalmov.rh02_anousu    \n";
    $sSql .= "       left  join orctiporec           on o15_codigo                    = rh25_recurso                \n";
    $sSql .= "       inner join rhregime             on rhregime.rh30_codreg          = rhpessoalmov.rh02_codreg    \n";
    $sSql .= "                                      and rhregime.rh30_instit          = rhpessoalmov.rh02_instit    \n";
    $sSql .= "       left  join rhpesrescisao        on rhpesrescisao.rh05_seqpes     = rhpessoalmov.rh02_seqpes    \n";
    $sSql .= "       left  join rhpespadrao          on rhpespadrao.rh03_seqpes       = rhpessoalmov.rh02_seqpes    \n";
    $sSql .= "                                      and rhpespadrao.rh03_anousu       = rhpessoalmov.rh02_anousu    \n";
    $sSql .= "                                      and rhpespadrao.rh03_mesusu       = rhpessoalmov.rh02_mesusu    \n";
    $sSql .= "       left  join padroes              on padroes.r02_anousu            = rhpespadrao.rh03_anousu     \n";
    $sSql .= "                                      and padroes.r02_mesusu            = rhpespadrao.rh03_mesusu     \n";
    $sSql .= "                                      and padroes.r02_regime            = rhpespadrao.rh03_regime     \n";
    $sSql .= "                                      and padroes.r02_codigo            = rhpespadrao.rh03_padrao     \n";
    $sSql .= "                                      and padroes.r02_instit            = rhpessoalmov.rh02_instit    \n";
    $sSql .= "       left  join rhpeslocaltrab       on rhpeslocaltrab.rh56_seqpes    = rhpessoalmov.rh02_seqpes    \n";
    $sSql .= "                                      and rhpeslocaltrab.rh56_princ     = 't'                         \n";
    $sSql .= "       left  join rhlocaltrab          on rhpeslocaltrab.rh56_localtrab = rhlocaltrab.rh55_codigo     \n";
    $sSql .= "       left  join rhpesdoc             on rhpesdoc.rh16_regist          = rhpessoal.rh01_regist       \n";
    $sSql .= "       left  join rhpesbanco           on rhpesbanco.rh44_seqpes        = rhpessoalmov.rh02_seqpes    \n";
    $sSql .= "       left  join (select distinct rhipe.*,                                                           \n";
    $sSql .= "                          rh01_regist as rh62_regist                                                  \n";
    $sSql .= "                     from rhiperegist                                                                 \n";
    $sSql .= "                          inner join rhipe     on rh14_sequencia = rh62_sequencia                     \n";
    $sSql .= "                          inner join rhpessoal on rh62_regist    = rh01_regist                        \n";
    $sSql .= "                  ) as rhipe           on rh01_regist                   = rhipe.rh62_regist           \n";
    $sSql .= "       left  join rhinstrucao          on rhinstrucao.rh21_instru       = rhpessoal.rh01_instru       \n";
    $sSql .= "       left  join rhestcivil           on rhestcivil.rh08_estciv        = rhpessoal.rh01_estciv       \n";
    $sSql .= " where rh02_anousu = $iAnoUsu                                                                         \n";
    $sSql .= "   and rh02_mesusu = $iMesUsu                                                                         \n";
    $sSql .= "   and rh02_instit = $iInstit                                                                         \n";
    $sSql .= "   and rh01_admiss <= to_date('$iAnoUsu-$iMesUsu-'||(ndias($iAnoUsu,$iMesUsu)), 'YYYY-mm-dd')         \n";
    $sSql .= "   and ( rh05_recis is null OR rh05_recis >= to_date('$iAnoUsu-$iMesUsu-01', 'YYYY-mm-dd') )          \n";
    if ( !empty($sWhere) ) {
      $sSql .= " {$sWhere}                                                                                          \n";
    }
    if ( !empty($sOrder) ) {
      $sSql .= "  {$sOrder}                                                                                         \n";
    }

    return $sSql;
  }

  /**
   * Monta o SQL dos dados da planilha CSV do IAPEP
   * @param string $sFiltroAdmissao - Indica qual o período vai buscar. Fixo < 20/12/2012 ou > 20/12/2012
   * @param integer $iAnoCompetencia - Ano da competencia
   * @param integer $iMesCompetencia - Mes da competencia
   * @param integer $iInstit - Instituicao da sessao
   * @param boolean $lIsPlanilha13 - Indicador se é planilha de salário ou de salario 13
   * @param string $sOrder - Ordem dos registros
   * @param float $fContribuicaoPatronalServidor - Percentual multiplicador do IAPEP - Fixo 24%
   * @return string - SQL a ser executado
   */
  public function sql_geracaoPlanilhasIAPEP( $sTabela, $sSigla, $sFiltroAdmissao, $iAnoCompetencia, $iMesCompetencia, $iInstit, $sTipoVinculo, $lIsPlanilha13 = false, $sOrder = 'z01_nome', $fContribuicaoPatronalServidor = '0.24' ) {


    /**
     * Valida tipo de planilha gerfsal ou gerfs13
     *
     * Planilha Salário    -> gerfsal
     *                        rubrica R985
     *                        prefixo r14
     *
     * Planilha Salário 13 -> gerfs13
     *                        rubrica R986
     *                        prefixo r35
     *
     */
    // Lógica antiga $sRubricaCalculo           = " 'R981', 'R975' ";
    $sRubricaCalculo          = " 'R985', 'R975' ";
    $sTabelaCalculo           = $sTabela;//'gerfsal';
    $sPrefixoTabelaCalculo    = $sSigla;//'r14';

    if( $lIsPlanilha13 ){
      $sRubricaCalculo       = " 'R986', 'R975' ";
    }

    /**
     * Valida filtros de admissão, sendo eles
     *
     * admitido ate  20/12/2012 -> rh01_admiss <= '2012-12-20'
     * admitido após 21/12/2012 -> rh01_admiss >= '2012-12-21'
     *
     * Padrão -> após 21/12/2012
     */
    $sSqlSubQueryAdmissao  = " ( select rh01_admiss                                                                              \n";
    $sSqlSubQueryAdmissao .= "      from rhpessoal x                                                                             \n";
    $sSqlSubQueryAdmissao .= "     where x.rh01_numcgm = cgm.z01_numcgm                                                          \n";
    $sSqlSubQueryAdmissao .= "  order by x.rh01_admiss ASC limit 1)                                                              \n";

    $sWhereAdmissao   = " and {$sSqlSubQueryAdmissao} >= '2012-12-21' \n";
    if( $sFiltroAdmissao == '2012-12-20' ){
      $sWhereAdmissao = " and {$sSqlSubQueryAdmissao} <= '2012-12-20' \n";
    }

    /**
     * Define clausulas From e Where padrão dos calculos
     */
    $sFromWhereCalculo  = "             from $sTabelaCalculo                                                                    \n";
    $sFromWhereCalculo .= "            where {$sPrefixoTabelaCalculo}_regist = rh02_regist                                      \n";
    $sFromWhereCalculo .= "              and {$sPrefixoTabelaCalculo}_anousu = rh02_anousu                                      \n";
    $sFromWhereCalculo .= "              and {$sPrefixoTabelaCalculo}_mesusu = rh02_mesusu                                      \n";

    /**
     * Where especifico para PIAUI
     */
    $sSqlEspecificoMPPI  = "      and rh02_funcao <> 70            --Especifico código cargo estagiario / PIAUI                 \n";
    $sSqlEspecificoMPPI .= "      and rh02_tbprev NOT IN (0 , 1)   --Não buscar tabelas do INSS e nao informados / PIAUI        \n";

    $sSql  = "   select '$sTabelaCalculo',                                                                                      \n";
    $sSql .= "          rh02_regist,                                                                                            \n";
    $sSql .= "          z01_nome,                                                                                               \n";
    $sSql .= "          z01_cgccpf,                                                                                             \n";
    $sSql .= "          rh02_seqpes,                                                                                            \n";

    $sSql .= " ( select rh01_admiss                                                                                             \n";
    $sSql .= "      from rhpessoal x                                                                                            \n";
    $sSql .= "     where x.rh01_numcgm = cgm.z01_numcgm                                                                         \n";
    $sSql .= "  order by x.rh01_admiss ASC limit 1)                                    as rh01_admiss,                          \n";

    $sSql .= " ( select rh05_recis                                                                                              \n";
    $sSql .= "      from rhpesrescisao x                                                                                        \n";
    $sSql .= "     where x.rh05_seqpes = rhpessoalmov.rh02_seqpes                                                               \n";
    $sSql .= "  order by x.rh05_recis ASC limit 1)                                    as data_rescisao,                         \n";

    $sSql .= "(select coalesce ( sum( {$sPrefixoTabelaCalculo}_valor ), 0 )                                                     \n";
    $sSql .= "        {$sFromWhereCalculo}                                                                                      \n";
    $sSql .= "    and {$sPrefixoTabelaCalculo}_pd     = 1      )                       as remuneracao_bruta,                    \n";

    $sSql .= "(select coalesce ( sum( {$sPrefixoTabelaCalculo}_valor ), 0 )                                                     \n";
    $sSql .= "        {$sFromWhereCalculo}                                                                                      \n";
    $sSql .= "    and {$sPrefixoTabelaCalculo}_rubric = 'R992' )                       as contribuicao_previdenciaria,          \n";

    $sSql .= "(select coalesce ( sum( {$sPrefixoTabelaCalculo}_valor ), 0 )                                                     \n";
    $sSql .= "        {$sFromWhereCalculo}                                                                                      \n";
    $sSql .= "    and {$sPrefixoTabelaCalculo}_rubric = 'R993' )                       as contribuicao_previdenciaria_servidor, \n";

    $sSql .= "(select round    ( sum( {$sPrefixoTabelaCalculo}_valor ) * $fContribuicaoPatronalServidor, 2)                     \n";
    $sSql .= "        {$sFromWhereCalculo}                                                                                      \n";
    $sSql .= "    and {$sPrefixoTabelaCalculo}_rubric = 'R992' )                       as contribuicao_patronal_servidor        \n";

    $sSql .= "     from rhpessoalmov                                                                                            \n";
    $sSql .= "          inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist                        \n";
    $sSql .= "          inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm                           \n";
    $sSql .= "          inner join rhregime      on rhpessoalmov.rh02_codreg  = rhregime.rh30_codreg                            \n";
    $sSql .= "                                  and rhregime.rh30_vinculo     = '{$sTipoVinculo}'                               \n";
    $sSql .= "          left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes                        \n";
    $sSql .= "                                                                                                                  \n";
    $sSql .= "    where rh02_instit = $iInstit                                                                                  \n";
    $sSql .= "      and rh02_anousu = $iAnoCompetencia                                                                          \n";
    $sSql .= "      and rh02_mesusu = $iMesCompetencia                                                                          \n";

    $sSql .= $sSqlEspecificoMPPI;

    $sSql .= $sWhereAdmissao;

    $sSql .= " group by rh02_regist,                                                                                            \n";
    $sSql .= "          rh02_anousu,                                                                                            \n";
    $sSql .= "          rh02_mesusu,                                                                                            \n";
    $sSql .= "          rh05_recis,                                                                                             \n";
    $sSql .= "          z01_nome,                                                                                               \n";
    $sSql .= "          z01_cgccpf,                                                                                             \n";
    $sSql .= "          z01_numcgm,                                                                                             \n";
    $sSql .= "          rh01_admiss,                                                                                            \n";
    $sSql .= "          rh02_seqpes                                                                                             \n";

    /**
     * Ordenação padrão por nome do servidor
     */
    if ( !empty($sOrder) ) {
      $sSql.= " order by {$sOrder}                                                                                              \n";
    }
    return $sSql;
  }

  /**
   * Monta o SQL do relatório dos totalizadores das planilhas geradas no metódo de cima.
   * @param string $sFiltroAdmissao - Indica qual o período vai buscar. Fixo < 20/12/2012 ou > 20/12/2012
   * @param integer $iAnoCompetencia - Ano da competencia
   * @param integer $iMesCompetencia - Mes da competencia
   * @param integer $iInstit - Intituicao da sessao
   * @param float $fContribuicaoPatronalServidor - Percentual multiplicador do IAPEP - Fixo 24%
   * @return string - SQL a ser executado.
   */
  public function sql_geracaoPDFIAPEP(  $sFiltroAdmissao, $iAnoCompetencia, $iMesCompetencia, $iInstit, $sTipoVinculo, $fContribuicaoPatronalServidor = '0.24' ) {

    // $aFolhas      CalculoFolhaAdiantamento.model.php    CalculoFolhaFerias.model.php          CalculoFolhaProvisaoFerias.model.php  CalculoFolhaSalario.model.php
    //       CalculoFolha13o.model.php             CalculoFolhaComplementar.model.php    CalculoFolhaProvisao13o.model.php     CalculoFolhaRescisao.model.php


    /**
     * Valida filtros de admissão, sendo eles
     *
     * admitido ate  20/12/2012 -> rh01_admiss <= '2012-12-20'
     * admitido após 21/12/2012 -> rh01_admiss >= '2012-12-21'
     *
     * Padrão -> após 21/12/2012
     */
    $sSqlSubQueryAdmissao  = " ( select rh01_admiss                                                                              \n";
    $sSqlSubQueryAdmissao .= "      from rhpessoal x                                                                             \n";
    $sSqlSubQueryAdmissao .= "     where x.rh01_numcgm = cgm.z01_numcgm                                                          \n";
    $sSqlSubQueryAdmissao .= "  order by x.rh01_admiss ASC limit 1)                                                              \n";

    $sWhereAdmissao   = " and {$sSqlSubQueryAdmissao} >= '2012-12-21' \n";

    if( $sFiltroAdmissao == '2012-12-20' ){
      $sWhereAdmissao = " and {$sSqlSubQueryAdmissao} <= '2012-12-20' \n";
    }



    /**
     * Where especifico para PIAUI
     */
    $sSqlEspecificoMPPI  = "      and rh02_funcao <> 70            --Especifico código cargo estagiario / PIAUI                                                                           \n";
    $sSqlEspecificoMPPI .= "      and rh02_tbprev NOT IN (0 , 1)   --Não buscar tabelas do INSS e nao informados / PIAUI                                                                  \n";

    $sSql   = "select round ( sum ( remuneracao_bruta                    ), 2 )                                                    as TotalFolhaBruta,                                    \n";
    $sSql  .= "       round ( sum ( contribuicao_previdenciaria          ), 2 )                                                    as TotalContribuicaoPrevidenciaria,                    \n";
    $sSql  .= "       round ( sum ( contribuicao_previdenciaria_servidor ), 2 )                                                    as TotalContribuicaoPrevidenciariaServidor,            \n";
    $sSql  .= "       round ( sum ( contribuicao_patronal_servidor       ), 2 )                                                    as TotalContribuicaoPatronalServidor,                  \n";
    $sSql  .= "                                                                                                                                                                           \n";
    $sSql  .= "       round ( sum ( remuneracao_bruta_13                    ), 2 )                                                 as TotalFolhaBruta13,                                  \n";
    $sSql  .= "       round ( sum ( contribuicao_previdenciaria_13          ), 2 )                                                 as TotalContribuicaoprevidenciaria13,                  \n";
    $sSql  .= "       round ( sum ( contribuicao_previdenciaria_servidor_13 ), 2 )                                                 as TotalContribuicaoprevidenciariaservidor13,          \n";
    $sSql  .= "       round ( sum ( contribuicao_patronal_servidor_13       ), 2 )                                                 as TotalContribuicaopatronalservidor13,                \n";
    $sSql  .= "                                                                                                                                                                           \n";
    $sSql  .= "       round ( sum ( remuneracao_bruta                    ) + sum ( remuneracao_bruta_13                    ), 2 )  as TotalizadorFolhaBruta,                              \n";
    $sSql  .= "       round ( sum ( contribuicao_previdenciaria_servidor ) + sum ( contribuicao_previdenciaria_servidor_13 ), 2 )  as TotalizadorContribuicaoPrevidenciariaServidor,      \n";

    $sSql  .= "       round ( sum ( contribuicao_previdenciaria_servidor ) + sum ( contribuicao_previdenciaria_servidor_13 ) +                                                            \n";
    $sSql  .= "       sum ( contribuicao_patronal_servidor       ) + sum ( contribuicao_patronal_servidor_13       ), 2 )          as TotalizadorContribuicaoPrevicendiaPatronalServidor, \n";

    $sSql  .= "       round ( sum ( contribuicao_patronal_servidor       ) + sum ( contribuicao_patronal_servidor_13       ), 2 )  as TotalizadorContribuicaoPatronalServidor,            \n";
    $sSql  .= "       count (*)                                                                                                    as QuantidadeServidores                                \n";
    $sSql  .= " from (                                                                                                                                                                    \n";
    $sSql  .= "       select                                                                                                                                                              \n";
    $sSql  .= "           (select rh05_recis                                                                                                                                              \n";
    $sSql  .= "              from rhpesrescisao x                                                                                                                                         \n";
    $sSql  .= "             where x.rh05_seqpes = rhpessoalmov.rh02_seqpes                                                                                                                \n";
    $sSql  .= "          order by x.rh05_recis ASC limit 1) as data_rescisao,                                                                                                             \n";

    $sSql  .= "           (select coalesce(sum(valor),0) as remuneracao_bruta                                                                                                             \n";
    $sSql  .= "              from (select sum(r14_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfsal                                                                                                                                         \n";
    $sSql  .= "                     where r14_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r14_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r14_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r14_pd = 1                                                                                                                                      \n";
    $sSql  .= "             union all                                                                                                                                                     \n";
    $sSql  .= "                    select sum(r48_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfcom                                                                                                                                         \n";
    $sSql  .= "                     where r48_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r48_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r48_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r48_pd = 1                                                                                                                                      \n";
    $sSql  .= "             union all                                                                                                                                                     \n";
    $sSql  .= "                    select sum(r20_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfres                                                                                                                                         \n";
    $sSql  .= "                     where r20_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r20_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r20_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r20_pd = 1) as soma_valor),                                                                                                                     \n";

    $sSql  .= "           (select coalesce(sum(valor),0) as contribuicao_previdenciaria                                                                                                   \n";
    $sSql  .= "              from (select sum(r14_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfsal                                                                                                                                         \n";
    $sSql  .= "                     where r14_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r14_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r14_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r14_rubric = 'R992'                                                                                                                             \n";
    $sSql  .= "             union all                                                                                                                                                     \n";
    $sSql  .= "                    select sum(r48_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfcom                                                                                                                                         \n";
    $sSql  .= "                     where r48_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r48_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r48_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r48_rubric = 'R992'                                                                                                                             \n";
    $sSql  .= "             union all                                                                                                                                                     \n";
    $sSql  .= "                    select sum(r20_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfres                                                                                                                                         \n";
    $sSql  .= "                     where r20_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r20_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r20_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r20_rubric = 'R992' ) as soma_valor),                                                                                                           \n";

    $sSql  .= "           (select coalesce(sum(valor),0) as contribuicao_previdenciaria_servidor                                                                                          \n";
    $sSql  .= "              from (select sum(r14_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfsal                                                                                                                                         \n";
    $sSql  .= "                     where r14_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r14_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r14_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r14_rubric = 'R993'                                                                                                                             \n";
    $sSql  .= "             union all                                                                                                                                                     \n";
    $sSql  .= "                    select sum(r48_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfcom                                                                                                                                         \n";
    $sSql  .= "                     where r48_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r48_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r48_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r48_rubric = 'R993'                                                                                                                             \n";
    $sSql  .= "             union all                                                                                                                                                     \n";
    $sSql  .= "                    select sum(r20_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfres                                                                                                                                         \n";
    $sSql  .= "                     where r20_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r20_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r20_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r20_rubric = 'R993' ) as soma_valor),                                                                                                           \n";

    $sSql  .= "           (select round ( coalesce(sum(valor),0) * $fContribuicaoPatronalServidor, 2) as contribuicao_patronal_servidor                                                   \n";
    $sSql  .= "              from (select sum(r14_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfsal                                                                                                                                         \n";
    $sSql  .= "                     where r14_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r14_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r14_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r14_rubric = 'R992'                                                                                                                             \n";
    $sSql  .= "             union all                                                                                                                                                     \n";
    $sSql  .= "                    select sum(r48_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfcom                                                                                                                                         \n";
    $sSql  .= "                     where r48_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r48_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r48_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r48_rubric = 'R992'                                                                                                                             \n";
    $sSql  .= "             union all                                                                                                                                                     \n";
    $sSql  .= "                    select sum(r20_valor) as valor                                                                                                                         \n";
    $sSql  .= "                      from gerfres                                                                                                                                         \n";
    $sSql  .= "                     where r20_regist = rh02_regist                                                                                                                        \n";
    $sSql  .= "                       and r20_anousu = rh02_anousu                                                                                                                        \n";
    $sSql  .= "                       and r20_mesusu = rh02_mesusu                                                                                                                        \n";
    $sSql  .= "                       and r20_rubric = 'R992' ) as soma_valor),                                                                                                           \n";

    $sSql  .= "              (select coalesce ( sum ( r35_valor ), 0 )                                                                                                                    \n";
    $sSql  .= "                 from gerfs13                                                                                                                                              \n";
    $sSql  .= "                where r35_regist = rh02_regist                                                                                                                             \n";
    $sSql  .= "                  and r35_anousu = rh02_anousu                                                                                                                             \n";
    $sSql  .= "                  and r35_mesusu = rh02_mesusu                                                                                                                             \n";
    $sSql  .= "                  and r35_pd     = 1 )                  as remuneracao_bruta_13,                                                                                           \n";
    $sSql  .= "              (select coalesce ( sum ( r35_valor ), 0 )                                                                                                                    \n";
    $sSql  .= "                 from gerfs13                                                                                                                                              \n";
    $sSql  .= "                where r35_regist = rh02_regist                                                                                                                             \n";
    $sSql  .= "                  and r35_anousu = rh02_anousu                                                                                                                             \n";
    $sSql  .= "                  and r35_mesusu = rh02_mesusu                                                                                                                             \n";
    $sSql  .= "                  and r35_rubric = 'R992' )             as contribuicao_previdenciaria_13,                                                                                 \n";
    $sSql  .= "              (select coalesce ( sum ( r35_valor ), 0 )                                                                                                                    \n";
    $sSql  .= "                 from gerfs13                                                                                                                                              \n";
    $sSql  .= "                where r35_regist = rh02_regist                                                                                                                             \n";
    $sSql  .= "                  and r35_anousu = rh02_anousu                                                                                                                             \n";
    $sSql  .= "                  and r35_mesusu = rh02_mesusu                                                                                                                             \n";
    $sSql  .= "                  and r35_rubric = 'R993' )             as contribuicao_previdenciaria_servidor_13,                                                                        \n";
    $sSql  .= "              (select round ( coalesce ( sum ( r35_valor ), 0 ) * $fContribuicaoPatronalServidor, 2)                                                                       \n";
    $sSql  .= "                 from gerfs13                                                                                                                                              \n";
    $sSql  .= "                where r35_regist = rh02_regist                                                                                                                             \n";
    $sSql  .= "                  and r35_anousu = rh02_anousu                                                                                                                             \n";
    $sSql  .= "                  and r35_mesusu = rh02_mesusu                                                                                                                             \n";
    $sSql  .= "                  and r35_rubric = 'R992' )              as contribuicao_patronal_servidor_13                                                                              \n";
    $sSql .= "     from rhpessoalmov                                                                                                                                                      \n";
    $sSql .= "          inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist                                                                                  \n";
    $sSql .= "          inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm                                                                                     \n";
    $sSql .= "          left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes                                                                                  \n";
    $sSql .= "          inner join rhregime      on rhpessoalmov.rh02_codreg  = rhregime.rh30_codreg                                                                                      \n";
    $sSql .= "                                  and rhregime.rh30_vinculo     = '{$sTipoVinculo}'                                                                                         \n";
    $sSql .= "                                                                                                                                                                            \n";
    $sSql .= "    where rh02_instit = $iInstit                                                                                                                                            \n";
    $sSql .= "      and rh02_anousu = $iAnoCompetencia                                                                                                                                    \n";
    $sSql .= "      and rh02_mesusu = $iMesCompetencia                                                                                                                                    \n";
    $sSql .= "      and ( extract(year from (select rh05_recis                                                                                                                            \n";
    $sSql .= "                                 from rhpesrescisao x                                                                                                                       \n";
    $sSql .= "                                where x.rh05_seqpes = rhpessoalmov.rh02_seqpes                                                                                              \n";
    $sSql .= "                                order by x.rh05_recis ASC limit 1) ) >= $iAnoCompetencia                                                                                    \n";
    $sSql .= "      and   extract(month from (select rh05_recis                                                                                                                           \n";
    $sSql .= "                                from rhpesrescisao x                                                                                                                        \n";
    $sSql .= "                               where x.rh05_seqpes = rhpessoalmov.rh02_seqpes                                                                                               \n";
    $sSql .= "                            order by x.rh05_recis ASC limit 1)) >= $iMesCompetencia                                                                                         \n";
    $sSql .= "      or (select rh05_recis                                                                                                                                                 \n";
    $sSql .= "            from rhpesrescisao x                                                                                                                                            \n";
    $sSql .= "           where x.rh05_seqpes = rhpessoalmov.rh02_seqpes                                                                                                                   \n";
    $sSql .= "        order by x.rh05_recis ASC limit 1) is null)                                                                                                                         \n";
    $sSql .= $sSqlEspecificoMPPI;

    $sSql .= $sWhereAdmissao;

    $sSql .= " group by rh02_regist,                                                                                                                                                      \n";
    $sSql .= "          rh02_anousu,                                                                                                                                                      \n";
    $sSql .= "          rh02_mesusu,                                                                                                                                                      \n";
    $sSql .= "          rh05_recis,                                                                                                                                                       \n";
    $sSql .= "          z01_nome,                                                                                                                                                         \n";
    $sSql .= "          z01_cgccpf,                                                                                                                                                       \n";
    $sSql .= "          z01_numcgm,                                                                                                                                                       \n";
    $sSql .= "          rh01_admiss,                                                                                                                                                      \n";
    $sSql .= "          rh02_seqpes  ) as t                                                                                                                                               \n";

    return $sSql;
  }

  /**
   * Retorna as Variáveis para cálculo conforme o servidor
   * @param  Integer $iMatricula  Matricula do Servidor
   * @param  Integer $iAno        Ano competência
   * @param  Integer $iMes        Mês competência
   * @param  Integer $Instituicao Instiotuição
   * @return String               Sql
   */
  function sql_getVariaveisCalculo($iMatricula, $iAno, $iMes, $iInstituicao){

    $sSql  = " select 0::VARCHAR||trim(substr(db_fxxx,1,11))   as F001,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,12,11))  as F002,               ";
    $sSql .= "        substr(db_fxxx,23,11)                    as F003,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,34,11))  as F004,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,45,11))  as F005,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,56,11))  as F006,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,67,11))  as F006_clt,           ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,78,11))  as F007,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,89,11))  as F008,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,100,11)) as F009,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,111,11)) as F010,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,122,11)) as F011,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,133,11)) as F012,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,144,11)) as F013,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,155,11)) as F014,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,166,11)) as F015,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,177,11)) as F022,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,188,11)) as F024,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,199,11)) as F025,               ";
    $sSql .= "        0::VARCHAR||trim(substr(db_fxxx,210,11)) as F030,               ";
    $sSql .= "        substr(db_fxxx,221) as padrao                                   ";
    $sSql .= "   from (                                                               ";
    $sSql .= "    select db_fxxx(rh02_regist,rh02_anousu,rh02_mesusu,{$iInstituicao}) ";
    $sSql .= "      from   rhpessoalmov                                               ";
    $sSql .= "     where rh02_anousu = $iAno                                          ";
    $sSql .= "       and rh02_mesusu = $iMes                                          ";
    $sSql .= "       and rh02_regist = $iMatricula                                    ";
    $sSql .= "       and rh02_instit = $iInstituicao        ) as x;                   ";

    return $sSql;
  }

  function sql_getDadosServidoresTempoServico($sCampos = '*', $iAnoFolha, $iMesFolha, $iRegist){

    $sSql  = "select $sCampos                                                                           ";
    $sSql .= " from rhpessoalmov                                                                        ";
    $sSql .= "      inner join rhpessoal      on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist ";
    $sSql .= "      left  join rhpesdoc       on rh16_regist                 = rh01_regist              ";
    $sSql .= "      inner join rhlota         on rhlota.r70_codigo           = rhpessoalmov.rh02_lota   ";
    $sSql .= "                               and rhlota.r70_instit           = rhpessoalmov.rh02_instit ";
    $sSql .= "      inner join cgm            on cgm.z01_numcgm              = rhpessoal.rh01_numcgm    ";
    $sSql .= "      left join rhpesrescisao   on rhpesrescisao.rh05_seqpes   = rhpessoalmov.rh02_seqpes ";
    $sSql .= "      left join rhpespadrao     on rhpespadrao.rh03_seqpes     = rhpessoalmov.rh02_seqpes ";
    $sSql .= "      left join rhregime        on rhregime.rh30_codreg        = rhpessoalmov.rh02_codreg ";
    $sSql .= "                               and rhregime.rh30_instit        = rhpessoalmov.rh02_instit ";
    $sSql .= "      left join rhpesrubcalc    on rhpesrubcalc.rh65_seqpes    = rhpessoalmov.rh02_seqpes ";
    $sSql .= "                               and (rh65_rubric = 'R927' or rh65_rubric = 'R929')         ";
    $sSql .= "      left join rhpesfgts       on rhpesfgts.rh15_regist       = rhpessoalmov.rh02_regist ";
    $sSql .= "      left join tpcontra        on tpcontra.h13_codigo         = rh02_tpcont              ";
    $sSql .= "      left join rhinssoutros    on rh51_seqpes                 = rh02_seqpes              ";
    $sSql .= "      left join rhpesprop       on rh19_regist                 = rh02_regist              ";
    $sSql .= "      left join rhfuncao        on rh37_funcao                 = rh01_funcao              ";
    $sSql .= "                               and rh37_instit                 = rh02_instit              ";
    $sSql .= "      left join padroes         on rh03_anousu                 = r02_anousu               ";
    $sSql .= "                               and rh03_mesusu                 = r02_mesusu               ";
    $sSql .= "                               and rh03_regime                 = r02_regime               ";
    $sSql .= "                               and rh03_padrao                 = r02_codigo               ";
    $sSql .= "                               and r02_instit                  = rh02_instit              ";
    $sSql .= "where rh02_anousu = $iAnoFolha                                                            ";
    $sSql .= "  and rh02_mesusu = $iMesFolha                                                            ";
    $sSql .= "  and rh02_regist = $iRegist                                                              ";

    return $sSql;
  }

  function sql_query_arquivo_iapep($sFolha, $sSigla, $sBase, $iAno, $iMes, $iTipo_Folha, $sRub_Permanencia, $sWhere) {

    $iInstit = db_getsession("DB_instit");

    $sSql  = "  select                                                                                                                                  \n";
    $sSql .= "        valor                                                                              as valor                                       \n";
    $sSql .= "       ,rh01_regist                                                                        as regist                                      \n";
    $sSql .= "       ,( '1'                                                                               )::varchar as tipo_registro                   \n";
    $sSql .= "       ,( '5'                                                                               )::varchar as ente                            \n";
    $sSql .= "       ,( rh30_vinculo                                                                      )::varchar as vinculo                         \n";
    $sSql .= "       ,( lpad(rh01_regist,6,'0')                                                           )::varchar as matricula                       \n";
    $sSql .= "       ,( e_base                                                                            )::varchar as is_contribuicao_previdenciaria  \n";
    $sSql .= "       ,( lpad(z01_cgccpf,11,'0')                                                           )::varchar as cpf                             \n";
    $sSql .= "       ,( pd                                                                                )::varchar as provento_desconto               \n";
    $sSql .= "       ,( rubric                                                                            )::varchar as rubrica                         \n";
    $sSql .= "       ,( rpad(descr_rubric,19,' ')                                                         )::varchar as descricao_rubrica               \n";
    $sSql .= "       ,( valor                                                                             )::numeric(10,2) as valor_rubrica             \n";
    $sSql .= "       ,( $iAno                                                                             )::varchar as ano_competencia                 \n";
    $sSql .= "       ,( lpad($iMes,2,'0')                                                                 )::varchar as mes_competencia                 \n";
    $sSql .= "       ,( $iTipo_Folha                                                                      )::varchar as tipo_folha                      \n";
    $sSql .= "       ,( case when regist_perm is not null then 1 else 2 end                               )::varchar as possui_abono                    \n";
    $sSql .= "       ,( case when rh30_vinculo <> 'A' and rh02_portadormolestia = true then 1 else 2 end  )::varchar as possui_molestia                 \n";
    $sSql .= "                                                                                                                                          \n";
    $sSql .= "from rhpessoal                                                                                                                            \n";
    $sSql .= "     inner join cgm             on rh01_numcgm    = z01_numcgm                                                                            \n";
    $sSql .= "     inner join rhpessoalmov    on rh02_anousu    = $iAno                                                                                 \n";
    $sSql .= "                               and rh02_mesusu    = $iMes                                                                                 \n";
    $sSql .= "                               and rh02_regist    = rh01_regist                                                                           \n";
    $sSql .= "                               and rh02_instit    = $iInstit                                                                              \n";
    $sSql .= "     left join  rhpesrescisao   on rh05_seqpes    = rh02_seqpes                                                                           \n";
    $sSql .= "     inner join (select ".$sSigla."regist as regist,                                                                                      \n";
    $sSql .= "                        ".$sSigla."rubric as rubric,                                                                                      \n";
    $sSql .= "                        ".$sSigla."valor  as valor,                                                                                       \n";
    $sSql .= "                        ".$sSigla."pd     as pd,                                                                                          \n";
    $sSql .= "                        rh27_descr       as descr_rubric,                                                                                 \n";
    $sSql .= "                        case when ".$sSigla."rubric in (select r09_rubric from basesr                                                     \n";
    $sSql .= "                                                       where r09_anousu = fc_anofolha($iInstit)                                           \n";
    $sSql .= "                                                         and r09_mesusu = fc_mesfolha($iInstit)                                           \n";
    $sSql .= "                                                         and r09_base   in ".$sBase.")                                                    \n";
    $sSql .= "                             then 1 else 2                                                                                                \n";
    $sSql .= "                        end as e_base                                                                                                     \n";
    $sSql .= "                 from ".$sFolha."                                                                                                         \n";
    $sSql .= "                 inner join rhrubricas on rh27_rubric = ".$sSigla."rubric and rh27_instit = $iInstit                                      \n";
    $sSql .= "                 where ".$sSigla."pd <> 3                                                                                                 \n";
    $sSql .= "                   and ".$sSigla."anousu = $iAno                                                                                          \n";
    $sSql .= "                   and ".$sSigla."mesusu = $iMes                                                                                          \n";
    $sSql .= "                   and ".$sSigla."instit = $iInstit                                                                                       \n";
    $sSql .= "                ) as calculo on rh01_regist = regist                                                                                      \n";
    $sSql .= "     left join (select ".$sSigla."regist as regist_perm from ".$sFolha." where ".$sSigla."anousu = $iAno and ".$sSigla."mesusu = $iMes    \n";
    $sSql .= "                                                                            and ".$sSigla."instit = $iInstit                              \n";
    $sSql .= "                                                                            and ".$sSigla."rubric in ($sRub_Permanencia)                  \n";
    $sSql .= "               ) as calc_perm on regist_perm = rh01_regist                                                                                \n";
    $sSql .= "     inner join rhlota          on r70_codigo     = rh02_lota and r70_instit = $iInstit                                                   \n";
    $sSql .= "     inner join rhfuncao        on rh01_funcao    = rh37_funcao and rh37_instit = $iInstit                                                \n";
    $sSql .= "     inner join rhinstrucao     on rh01_instru    = rh21_instru                                                                           \n";
    $sSql .= "     inner join rhestcivil      on rh01_estciv    = rh08_estciv                                                                           \n";
    $sSql .= "     left join rhiperegist      on rh62_regist    = rh01_regist                                                                           \n";
    $sSql .= "     left join rhipe            on rh14_sequencia = rh62_sequencia and rh14_instit = $iInstit                                             \n";
    $sSql .= "     left join  rhpeslocaltrab  on rh56_seqpes    = rh02_seqpes                                                                           \n";
    $sSql .= "                               and rh56_princ     = 't'                                                                                   \n";
    $sSql .= "     left join  rhlocaltrab     on rh56_localtrab = rh55_codigo and rh55_instit = $iInstit                                                \n";
    $sSql .= "     left join  rhpesdoc        on rh16_regist    = rh01_regist                                                                           \n";
    $sSql .= "     left join  rhpespadrao     on rh02_seqpes    = rh03_seqpes                                                                           \n";
    $sSql .= "     inner join rhregime        on rh30_codreg    = rh02_codreg and rh30_instit = $iInstit                                                \n";
    $sSql .= "     left join  rhpesbanco      on rh44_seqpes    = rh02_seqpes                                                                           \n";
    $sSql .= "where rh30_regime = 1                                                                                                                     \n";
    $sSql .= "$sWhere                                                                                                                                   \n";
    $sSql .= "order by rh01_regist, rubric                                                                                                              \n";

    return $sSql;
  }

  function sql_query_rescisao_afastamento($iAno, $iMes, $sWhere="") {

    $iInstit = db_getsession("DB_instit");

    $sSql  = "  select distinct                                                                                                                                   \n";
    $sSql .= "         case                                                                                                                                       \n";
    $sSql .= "           when substr(r70_estrut,10,2) = '03' then 'FUNDEB 60%'                                                                                    \n";
    $sSql .= "           else case                                                                                                                                \n";
    $sSql .= "                  when substr(r70_estrut,10,2) = '04'                                                                                               \n";
    $sSql .= "                  then 'FUNDEB 40%'                                                                                                                 \n";
    $sSql .= "                  else o15_descr                                                                                                                    \n";
    $sSql .= "                end                                                                                                                                 \n";
    $sSql .= "        end as recurso,                                                                                                                             \n";
    $sSql .= "        rh01_regist,                                                                                                                                \n";
    $sSql .= "        z01_nome,                                                                                                                                   \n";
    $sSql .= "        z01_cgccpf,                                                                                                                                 \n";
    $sSql .= "        rh01_admiss,                                                                                                                                \n";
    $sSql .= "        rh01_nasc,                                                                                                                                  \n";
    $sSql .= "        z01_ender,                                                                                                                                  \n";
    $sSql .= "        z01_numero,                                                                                                                                 \n";
    $sSql .= "        z01_compl,                                                                                                                                  \n";
    $sSql .= "        z01_bairro,                                                                                                                                 \n";
    $sSql .= "        z01_munic,                                                                                                                                  \n";
    $sSql .= "        z01_cep,                                                                                                                                    \n";
    $sSql .= "        z01_uf,                                                                                                                                     \n";
    $sSql .= "        z01_mae,                                                                                                                                    \n";
    $sSql .= "        rh16_ctps_n,                                                                                                                                \n";
    $sSql .= "        rh16_ctps_s,                                                                                                                                \n";
    $sSql .= "        rh16_ctps_d,                                                                                                                                \n";
    $sSql .= "        rh16_ctps_uf,                                                                                                                               \n";
    $sSql .= "        rh16_pis,                                                                                                                                   \n";
    $sSql .= "        rh05_aviso,                                                                                                                                 \n";
    $sSql .= "        rh05_recis,                                                                                                                                 \n";
    $sSql .= "        rh05_mremun,                                                                                                                                \n";
    $sSql .= "        h13_tpcont,                                                                                                                                 \n";
    $sSql .= "        r59_descr,                                                                                                                                  \n";
    $sSql .= "        o55_projativ,                                                                                                                               \n";
    $sSql .= "        o55_descr,                                                                                                                                  \n";
    $sSql .= "        o15_codigo,                                                                                                                                 \n";
    $sSql .= "        o15_descr,                                                                                                                                  \n";
    $sSql .= "        o40_orgao,                                                                                                                                  \n";
    $sSql .= "        o40_descr,                                                                                                                                  \n";
    $sSql .= "        o41_unidade,                                                                                                                                \n";
    $sSql .= "        o41_descr,                                                                                                                                  \n";
    $sSql .= "        rh30_regime,                                                                                                                                \n";
    $sSql .= "        r59_movsef,                                                                                                                                 \n";
    $sSql .= "        substr(db_fxxx(rh01_regist,rh02_anousu,rh02_mesusu,1),111,11)::float8 as f010,                                                              \n";
    $sSql .= "        r59_codsaq                                                                                                                                  \n";
    $sSql .= " from rhpessoalmov                                                                                                                                  \n";
    $sSql .= "       inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist                                                             \n";
    $sSql .= "       inner join tpcontra      on tpcontra.h13_codigo       = rhpessoalmov.rh02_tpcont                                                             \n";
    $sSql .= "       inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm                                                                \n";
    $sSql .= "       inner join rhlota        on rhlota.r70_codigo         = rhpessoalmov.rh02_lota                                                               \n";
    $sSql .= "                               and rhlota.r70_instit         = rhpessoalmov.rh02_instit                                                             \n";
    $sSql .= "       left  join rhpesdoc      on rhpesdoc.rh16_regist      = rhpessoal.rh01_regist                                                                \n";
    $sSql .= "       left  join rhregime      on rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg                                                             \n";
    $sSql .= "                               and rhregime.rh30_instit      = rhpessoalmov.rh02_instit                                                             \n";
    $sSql .= "       left  join rhcadregime   on rhregime.rh30_regime      = rhcadregime.rh52_regime                                                              \n";
    $sSql .= "       left  join rhlotaexe     on rhlotaexe.rh26_anousu     = rhpessoalmov.rh02_anousu                                                             \n";
    $sSql .= "                               and rhlotaexe.rh26_codigo     = rhlota.r70_codigo                                                                    \n";
    $sSql .= "       left  join orcunidade    on orcunidade.o41_anousu     = rhlotaexe.rh26_anousu                                                                \n";
    $sSql .= "                               and orcunidade.o41_orgao      = rhlotaexe.rh26_orgao                                                                 \n";
    $sSql .= "                               and orcunidade.o41_unidade    = rhlotaexe.rh26_unidade                                                               \n";
    $sSql .= "       left  join orcorgao      on orcorgao.o40_anousu       = orcunidade.o41_anousu                                                                \n";
    $sSql .= "                               and orcorgao.o40_orgao        = orcunidade.o41_orgao                                                                 \n";
    $sSql .= "       left  join rhlotavinc    on rhlotavinc.rh25_codigo    = rhlotaexe.rh26_codigo                                                                \n";
    $sSql .= "                               and rhlotavinc.rh25_anousu    = rhpessoalmov.rh02_anousu                                                             \n";
    $sSql .= "                               and rhlotavinc.rh25_vinculo   = rhregime.rh30_vinculo                                                                \n";
    $sSql .= "       left  join orcprojativ   on orcprojativ.o55_anousu    = rhpessoalmov.rh02_anousu                                                             \n";
    $sSql .= "                               and orcprojativ.o55_projativ  = rhlotavinc.rh25_projativ                                                             \n";
    $sSql .= "       left  join orctiporec    on orctiporec.o15_codigo     = rhlotavinc.rh25_recurso                                                              \n";
    $sSql .= "       left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes                                                             \n";
    $sSql .= "       left  join rescisao      on rescisao.r59_anousu       = rhpessoalmov.rh02_anousu                                                             \n";
    $sSql .= "                               and rescisao.r59_mesusu       = rhpessoalmov.rh02_mesusu                                                             \n";
    $sSql .= "                               and rescisao.r59_regime       = rhregime.rh30_regime                                                                 \n";
    $sSql .= "                               and rescisao.r59_causa        = rhpesrescisao.rh05_causa                                                             \n";
    $sSql .= "                               and rescisao.r59_caub         = rhpesrescisao.rh05_caub::char(2)                                                     \n";
    $sSql .= "                               and case when (rhpesrescisao.rh05_recis - rhpessoal.rh01_admiss) >= 365 then 'N' else 'S' end = rescisao.r59_menos1  \n";
    $sSql .= "                               and rescisao.r59_instit       = rhpessoalmov.rh02_instit                                                             \n";
    $sSql .= "       left  join gerfres       on gerfres.r20_anousu        = rhpessoalmov.rh02_anousu                                                             \n";
    $sSql .= "                               and gerfres.r20_mesusu        = rhpessoalmov.rh02_mesusu                                                             \n";
    $sSql .= "                               and gerfres.r20_regist        = rhpessoalmov.rh02_regist                                                             \n";
    $sSql .= "                               and gerfres.r20_instit        = $iInstit                                                                             \n";
    $sSql .= "       left  join afasta        on rhpessoal.rh01_regist     = afasta.r45_regist                                                                    \n";
    $sSql .= "                               and rhpessoalmov.rh02_anousu  = afasta.r45_anousu                                                                    \n";
    $sSql .= "                               and rhpessoalmov.rh02_mesusu  = afasta.r45_mesusu                                                                    \n";
    $sSql .= "                               and afasta.r45_situac         = 2                                                                                    \n";

    $sSql .= "where ";
    $sSql .= " case";
    $sSql .= "     when gerfres.r20_regist IS NOT NULL and r20_anousu = $iAno and  r20_mesusu = $iMes ";/* Caso tenha cálculo */
    $sSql .= "       then (rhpessoalmov.rh02_anousu = $iAno and rhpessoalmov.rh02_mesusu = $iMes)";
    $sSql .= "     else (extract(year from rhpesrescisao.rh05_recis) = $iAno and extract(month from rhpesrescisao.rh05_recis) = $iMes)"; /* Caso não tenha calculo*/
    $sSql .= " end";

    $sSql .= "    and rhpessoalmov.rh02_instit                     = $iInstit                                                                                     \n";
    $sSql .= "    and rhpesrescisao.rh05_seqpes is not null                                                                                                       \n";
    $sSql .= "    and case                                                                                                                                        \n";
    $sSql .= "          when gerfres.r20_regist is null                                       /* Caso não tenha cálculo                              */           \n";
    $sSql .= "          then (afasta.r45_regist is not null and rhcadregime.rh52_regime <> 2) /* Valida se tem afastamento e se o servidor não é CLT */           \n";
    $sSql .= "          else true                                                             /* no caso de haver cálculo retorna semrpre            */           \n";
    $sSql .= "        end                                                                                                                                         \n";
    $sSql .= "                                  $sWhere                                                                                                           \n";
    $sSql .= "  order by rh01_regist                                                                                                                              \n";

    return $sSql;
  }

  /**
   * Retorna todas as matriculas com duplo vinculo ativas na competência informada como parâmetro
   * @param  integer $iAnoUsu Ano competência
   * @param  integer $iMesUsu Mês competência
   * @return string SQL
   */
  public function sql_duplo_vinculo($iAnoUsu, $iMesUsu) {

    $sSql  = " SELECT array_to_string(array_accum(rh01_regist), ',') as rh01_regist                    ";
    $sSql .= "   FROM rhpessoalmov                                                                    ";
    $sSql .= "  INNER JOIN rhpessoal AS rhpessoal ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist ";
    $sSql .= "   LEFT JOIN rhpesrescisao ON rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes      ";
    $sSql .= " WHERE rh02_anousu = {$iAnoUsu}                                                         ";
    $sSql .= "       AND rh02_mesusu = {$iMesUsu}                                                     ";
    $sSql .= "       AND (rh05_recis IS NULL                                                          ";
    $sSql .= "        OR (extract(YEAR                                                                ";
    $sSql .= "                    FROM rh05_recis) >= '{$iAnoUsu}'                                    ";
    $sSql .= "            AND extract(MONTH                                                           ";
    $sSql .= "                        FROM rh05_recis) >= '{$iMesUsu}'))                              ";
    $sSql .= " GROUP BY rh01_numcgm HAVING count(rh01_regist) > 1;                                    ";

    return $sSql;
  }

  /**
   * Retorna os duplos vínculos ativos, para a matricula informada como parâmetro.
   *
   * @param  integer $iMatricula Matricula do servidor
   * @param  integer $iAnoUsu    Ano da competência
   * @param  integer $iMesUsu    Mês da competência
   * @return string SQL
   */
  public function sql_duplo_vinculo_matricula($iMatricula, $iAnoUsu, $iMesUsu, $iInstit = null) {

    if (is_null($iInstit)) {
      $iInstit = db_getsession('DB_instit');
    }

    $sSql  = " SELECT rh01_regist                                                                     ";
    $sSql .= "   FROM rhpessoalmov                                                                    ";
    $sSql .= "  INNER JOIN rhpessoal as rhpessoal ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist ";
    $sSql .= "   LEFT JOIN rhpesrescisao ON rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes      ";
    $sSql .= "  WHERE      rh01_numcgm = (select rh01_numcgm                                          ";
    $sSql .= "                              from rhpessoal                                            ";
    $sSql .= "                             where rh01_regist = {$iMatricula})                         ";
    $sSql .= "         AND rh01_regist <> {$iMatricula}                                               ";
    $sSql .= "         AND rh02_anousu  = {$iAnoUsu}                                                  ";
    $sSql .= "         AND rh02_mesusu  = {$iMesUsu}                                                  ";
    $sSql .= "         AND rh02_instit  = {$iInstit}                                                  ";
    $sSql .= "         AND ( rh05_recis IS NULL                                                       ";
    $sSql .= "             OR (extract(YEAR FROM rh05_recis) >= '{$iAnoUsu}'                          ";
    $sSql .= "                 AND extract(MONTH FROM rh05_recis) >= '{$iMesUsu}')                    ";
    $sSql .= "                )                                                                       ";
    $sSql .= "         AND rh02_instit  = {$iInstit}                                                  ";

    return $sSql;
  }

  /**
   * Valida se a matricula passada
   * por parâmetro está rescindida.
   *
   * @param  Integer $iMatricula
   * @return Boolean
   */
  public function isRescindido($iMatricula) {

    $sSql = "
      SELECT rh02_regist
      FROM rhpessoalmov
      INNER JOIN rhpesrescisao ON rh05_seqpes = rh02_seqpes
      WHERE rh02_regist = {$iMatricula}
      ";

    $rsResult = db_query($sSql);
    return (bool) pg_num_rows($rsResult);

  }

  /**
   * Retorna os servidores, juntamente com os duplos inculos ativos.
   *
   * @param  string $sMatriculasMatriculas Sql que retorna uma lista de matriculas para ser buscado seus duplos vinculos.
   * @param  integer $iAnoUsu
   * @param  integer $iMesUsu
   * @param  integer $iInstit
   * @example $sSqlServidores   = $oDaoRhPessoalMov->sql_query_file(null, null, 'rh02_regist');
   *          $sSqlDuploVinculo = $oDaoRhPessoalMov->sql_servidores_duplo_vinculo($sSqlServidores,[...
   *
   * @return String sql
   */
  public function sql_servidores_duplo_vinculo($sMatriculasMatriculas, $iAnoUsu, $iMesUsu, $iInstit = null, $lRescindido = false) {

    if (empty($iInstit)) {
      $iInstit = db_getsession('DB_instit');
    }

    $sSql  = "SELECT rh01_regist                                                                     ";
    $sSql .= "  FROM rhpessoalmov                                                                    ";
    $sSql .= " INNER JOIN rhpessoal as rhpessoal ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist ";
    $sSql .= "  LEFT JOIN rhpesrescisao ON rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes      ";
    $sSql .= " WHERE      rh01_numcgm in (select rh01_numcgm                                         ";
    $sSql .= "                             from rhpessoal                                            ";
    $sSql .= "                            where rh01_regist in ({$sMatriculasMatriculas})            ";
    $sSql .= "                           )                                                           ";
    $sSql .= "        AND rh02_anousu  = {$iAnoUsu}                                                  ";
    $sSql .= "        AND rh02_mesusu  = {$iMesUsu}                                                  ";
    $sSql .= "        AND rh02_instit  = {$iInstit}                                                  ";

    $sRescindido = "";
    if ( !$lRescindido ) {
      $sRescindido = " not ";
    }

    $sSql .= " and {$sRescindido} exists ( select 1                                                              ";
    $sSql .= "                               from rhpesrescisao                                                  ";
    $sSql .= "                              where     (    rh05_recis is not null                                ";
    $sSql .= "                                          or (     extract(year from rh05_recis) >= '{$iAnoUsu}'   ";
    $sSql .= "                                               and extract(month from rh05_recis) >= '{$iMesUsu}'  ";
    $sSql .= "                                             )                                                     ";
    $sSql .= "                                        )                                                          ";
    $sSql .= "                                     and rh05_seqpes = rhpessoalmov.rh02_seqpes)                   ";
  
    return $sSql;
  }


  /**
   * Retorna os servidores, juntamente com os duplos vinculos ativos ou riscindidos na ccompetencia informada por parâmetro.
   *
   * @param  string $sMatriculasMatriculas Sql que retorna uma lista de matriculas para ser buscado seus duplos vinculos.
   * @param  integer $iAnoUsu
   * @param  integer $iMesUsu
   * @param  integer $iInstit
   * @example $sSqlServidores   = $oDaoRhPessoalMov->sql_query_file(null, null, 'rh02_regist');
   *          $sSqlDuploVinculo = $oDaoRhPessoalMov->sql_servidores_duplo_vinculo($sSqlServidores,[...
   *
   * @return String sql
   */
  public function sql_servidores_duplo_vinculo_rescindidos($sMatriculasMatriculas, $iAnoUsu, $iMesUsu, $iInstit = null) {

    if (empty($iInstit)) {
      $iInstit = db_getsession('DB_instit');
    }

    $sSql  = "SELECT rh01_regist                                                                     ";
    $sSql .= "  FROM rhpessoalmov                                                                    ";
    $sSql .= " INNER JOIN rhpessoal as rhpessoal ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist ";
    $sSql .= "  LEFT JOIN rhpesrescisao ON rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes      ";
    $sSql .= "      WHERE rh01_numcgm in (select rh01_numcgm                                         ";
    $sSql .= "                             from rhpessoal                                            ";
    $sSql .= "                            where rh01_regist in ({$sMatriculasMatriculas})            ";
    $sSql .= "                           )                                                           ";
    $sSql .= "        AND rh02_anousu  = {$iAnoUsu}                                                  ";
    $sSql .= "        AND rh02_mesusu  = {$iMesUsu}                                                  ";
    $sSql .= "        AND rh02_instit  = {$iInstit}                                                  ";
    $sSql .= "        and not exists ( select 1                                                      ";
    $sSql .= "                           from rhpesrescisao                                          ";
    $sSql .= "                          where extract(year from rh05_recis) > '{$iAnoUsu}'           ";
    $sSql .= "                            and extract(month from rh05_recis) > '{$iMesUsu}'          ";
    $sSql .= "                            and rh05_seqpes = rhpessoalmov.rh02_seqpes)                ";

    return $sSql;
  } 
}