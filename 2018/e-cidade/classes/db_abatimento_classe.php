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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE abatimento
class cl_abatimento {
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
   var $k125_sequencial = 0;
   var $k125_tipoabatimento = 0;
   var $k125_datalanc_dia = null;
   var $k125_datalanc_mes = null;
   var $k125_datalanc_ano = null;
   var $k125_datalanc = null;
   var $k125_hora = null;
   var $k125_usuario = 0;
   var $k125_instit = 0;
   var $k125_valor = 0;
   var $k125_perc = 0;
   var $k125_valordisponivel = 0;
   var $k125_abatimentosituacao = 0;
   var $k125_observacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k125_sequencial = int4 = Sequencial
                 k125_tipoabatimento = int4 = Tipo de Abatimento
                 k125_datalanc = date = Data de Lançamento
                 k125_hora = char(5) = Hora
                 k125_usuario = int4 = Usuário
                 k125_instit = int4 = Instituição
                 k125_valor = numeric(15,2) = Valor
                 k125_perc = numeric(15,10) = Percentual
                 k125_valordisponivel = numeric(15,2) = Valor Disponível
                 k125_abatimentosituacao = int4 = Situação
                 k125_observacao = text = Observação
                 ";
   //funcao construtor da classe
   function cl_abatimento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("abatimento");
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
       $this->k125_sequencial = ($this->k125_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_sequencial"]:$this->k125_sequencial);
       $this->k125_tipoabatimento = ($this->k125_tipoabatimento == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_tipoabatimento"]:$this->k125_tipoabatimento);
       if($this->k125_datalanc == ""){
         $this->k125_datalanc_dia = ($this->k125_datalanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_datalanc_dia"]:$this->k125_datalanc_dia);
         $this->k125_datalanc_mes = ($this->k125_datalanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_datalanc_mes"]:$this->k125_datalanc_mes);
         $this->k125_datalanc_ano = ($this->k125_datalanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_datalanc_ano"]:$this->k125_datalanc_ano);
         if($this->k125_datalanc_dia != ""){
            $this->k125_datalanc = $this->k125_datalanc_ano."-".$this->k125_datalanc_mes."-".$this->k125_datalanc_dia;
         }
       }
       $this->k125_hora = ($this->k125_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_hora"]:$this->k125_hora);
       $this->k125_usuario = ($this->k125_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_usuario"]:$this->k125_usuario);
       $this->k125_instit = ($this->k125_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_instit"]:$this->k125_instit);
       $this->k125_valor = ($this->k125_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_valor"]:$this->k125_valor);
       $this->k125_perc = ($this->k125_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_perc"]:$this->k125_perc);
       $this->k125_valordisponivel = ($this->k125_valordisponivel == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_valordisponivel"]:$this->k125_valordisponivel);
       $this->k125_abatimentosituacao = ($this->k125_abatimentosituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_abatimentosituacao"]:$this->k125_abatimentosituacao);
       $this->k125_observacao = ($this->k125_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_observacao"]:$this->k125_observacao);
     }else{
       $this->k125_sequencial = ($this->k125_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k125_sequencial"]:$this->k125_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k125_sequencial){
      $this->atualizacampos();
     if($this->k125_tipoabatimento == null ){
       $this->erro_sql = " Campo Tipo de Abatimento nao Informado.";
       $this->erro_campo = "k125_tipoabatimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k125_datalanc == null ){
       $this->erro_sql = " Campo Data de Lançamento nao Informado.";
       $this->erro_campo = "k125_datalanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k125_hora == null ){
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k125_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k125_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "k125_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k125_instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "k125_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k125_valor == null ){
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "k125_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k125_perc == null ){
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "k125_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k125_abatimentosituacao == null ){
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "k125_abatimentosituacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k125_sequencial == "" || $k125_sequencial == null ){
       $result = db_query("select nextval('abatimento_k125_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: abatimento_k125_sequencial_seq do campo: k125_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k125_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from abatimento_k125_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k125_sequencial)){
         $this->erro_sql = " Campo k125_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k125_sequencial = $k125_sequencial;
       }
     }
     if(($this->k125_sequencial == null) || ($this->k125_sequencial == "") ){
       $this->erro_sql = " Campo k125_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into abatimento(
                                       k125_sequencial
                                      ,k125_tipoabatimento
                                      ,k125_datalanc
                                      ,k125_hora
                                      ,k125_usuario
                                      ,k125_instit
                                      ,k125_valor
                                      ,k125_perc
                                      ,k125_valordisponivel
                                      ,k125_abatimentosituacao
                                      ,k125_observacao
                       )
                values (
                                $this->k125_sequencial
                               ,$this->k125_tipoabatimento
                               ,".($this->k125_datalanc == "null" || $this->k125_datalanc == ""?"null":"'".$this->k125_datalanc."'")."
                               ,'$this->k125_hora'
                               ,$this->k125_usuario
                               ,$this->k125_instit
                               ,$this->k125_valor
                               ,$this->k125_perc
                               ,$this->k125_valordisponivel
                               ,$this->k125_abatimentosituacao
                               ,'$this->k125_observacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Abatimentos ($this->k125_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Abatimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Abatimentos ($this->k125_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k125_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k125_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18062,'$this->k125_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3191,18062,'','".AddSlashes(pg_result($resaco,0,'k125_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3191,18063,'','".AddSlashes(pg_result($resaco,0,'k125_tipoabatimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3191,18064,'','".AddSlashes(pg_result($resaco,0,'k125_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3191,18065,'','".AddSlashes(pg_result($resaco,0,'k125_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3191,18066,'','".AddSlashes(pg_result($resaco,0,'k125_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3191,18067,'','".AddSlashes(pg_result($resaco,0,'k125_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3191,18068,'','".AddSlashes(pg_result($resaco,0,'k125_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3191,18069,'','".AddSlashes(pg_result($resaco,0,'k125_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3191,19632,'','".AddSlashes(pg_result($resaco,0,'k125_valordisponivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3191,20136,'','".AddSlashes(pg_result($resaco,0,'k125_abatimentosituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3191,20137,'','".AddSlashes(pg_result($resaco,0,'k125_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($k125_sequencial=null) {
      $this->atualizacampos();
     $sql = " update abatimento set ";
     $virgula = "";
     if(trim($this->k125_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k125_sequencial"])){
       $sql  .= $virgula." k125_sequencial = $this->k125_sequencial ";
       $virgula = ",";
       if(trim($this->k125_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k125_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k125_tipoabatimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k125_tipoabatimento"])){
       $sql  .= $virgula." k125_tipoabatimento = $this->k125_tipoabatimento ";
       $virgula = ",";
       if(trim($this->k125_tipoabatimento) == null ){
         $this->erro_sql = " Campo Tipo de Abatimento nao Informado.";
         $this->erro_campo = "k125_tipoabatimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k125_datalanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k125_datalanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k125_datalanc_dia"] !="") ){
       $sql  .= $virgula." k125_datalanc = '$this->k125_datalanc' ";
       $virgula = ",";
       if(trim($this->k125_datalanc) == null ){
         $this->erro_sql = " Campo Data de Lançamento nao Informado.";
         $this->erro_campo = "k125_datalanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k125_datalanc_dia"])){
         $sql  .= $virgula." k125_datalanc = null ";
         $virgula = ",";
         if(trim($this->k125_datalanc) == null ){
           $this->erro_sql = " Campo Data de Lançamento nao Informado.";
           $this->erro_campo = "k125_datalanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k125_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k125_hora"])){
       $sql  .= $virgula." k125_hora = '$this->k125_hora' ";
       $virgula = ",";
       if(trim($this->k125_hora) == null ){
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k125_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k125_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k125_usuario"])){
       $sql  .= $virgula." k125_usuario = $this->k125_usuario ";
       $virgula = ",";
       if(trim($this->k125_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "k125_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k125_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k125_instit"])){
       $sql  .= $virgula." k125_instit = $this->k125_instit ";
       $virgula = ",";
       if(trim($this->k125_instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "k125_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k125_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k125_valor"])){
       $sql  .= $virgula." k125_valor = $this->k125_valor ";
       $virgula = ",";
       if(trim($this->k125_valor) == null ){
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k125_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k125_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k125_perc"])){
       $sql  .= $virgula." k125_perc = $this->k125_perc ";
       $virgula = ",";
       if(trim($this->k125_perc) == null ){
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "k125_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k125_valordisponivel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k125_valordisponivel"])){
       $sql  .= $virgula." k125_valordisponivel = $this->k125_valordisponivel ";
       $virgula = ",";
     }
     if(trim($this->k125_abatimentosituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k125_abatimentosituacao"])){
       $sql  .= $virgula." k125_abatimentosituacao = $this->k125_abatimentosituacao ";
       $virgula = ",";
       if(trim($this->k125_abatimentosituacao) == null ){
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "k125_abatimentosituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k125_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k125_observacao"])){
       $sql  .= $virgula." k125_observacao = '$this->k125_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k125_sequencial!=null){
       $sql .= " k125_sequencial = $this->k125_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k125_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,18062,'$this->k125_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k125_sequencial"]) || $this->k125_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3191,18062,'".AddSlashes(pg_result($resaco,$conresaco,'k125_sequencial'))."','$this->k125_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k125_tipoabatimento"]) || $this->k125_tipoabatimento != "")
             $resac = db_query("insert into db_acount values($acount,3191,18063,'".AddSlashes(pg_result($resaco,$conresaco,'k125_tipoabatimento'))."','$this->k125_tipoabatimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k125_datalanc"]) || $this->k125_datalanc != "")
             $resac = db_query("insert into db_acount values($acount,3191,18064,'".AddSlashes(pg_result($resaco,$conresaco,'k125_datalanc'))."','$this->k125_datalanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k125_hora"]) || $this->k125_hora != "")
             $resac = db_query("insert into db_acount values($acount,3191,18065,'".AddSlashes(pg_result($resaco,$conresaco,'k125_hora'))."','$this->k125_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k125_usuario"]) || $this->k125_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3191,18066,'".AddSlashes(pg_result($resaco,$conresaco,'k125_usuario'))."','$this->k125_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k125_instit"]) || $this->k125_instit != "")
             $resac = db_query("insert into db_acount values($acount,3191,18067,'".AddSlashes(pg_result($resaco,$conresaco,'k125_instit'))."','$this->k125_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k125_valor"]) || $this->k125_valor != "")
             $resac = db_query("insert into db_acount values($acount,3191,18068,'".AddSlashes(pg_result($resaco,$conresaco,'k125_valor'))."','$this->k125_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k125_perc"]) || $this->k125_perc != "")
             $resac = db_query("insert into db_acount values($acount,3191,18069,'".AddSlashes(pg_result($resaco,$conresaco,'k125_perc'))."','$this->k125_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k125_valordisponivel"]) || $this->k125_valordisponivel != "")
             $resac = db_query("insert into db_acount values($acount,3191,19632,'".AddSlashes(pg_result($resaco,$conresaco,'k125_valordisponivel'))."','$this->k125_valordisponivel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k125_abatimentosituacao"]) || $this->k125_abatimentosituacao != "")
             $resac = db_query("insert into db_acount values($acount,3191,20136,'".AddSlashes(pg_result($resaco,$conresaco,'k125_abatimentosituacao'))."','$this->k125_abatimentosituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k125_observacao"]) || $this->k125_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3191,20137,'".AddSlashes(pg_result($resaco,$conresaco,'k125_observacao'))."','$this->k125_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Abatimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k125_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Abatimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($k125_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($k125_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,18062,'$k125_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3191,18062,'','".AddSlashes(pg_result($resaco,$iresaco,'k125_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3191,18063,'','".AddSlashes(pg_result($resaco,$iresaco,'k125_tipoabatimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3191,18064,'','".AddSlashes(pg_result($resaco,$iresaco,'k125_datalanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3191,18065,'','".AddSlashes(pg_result($resaco,$iresaco,'k125_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3191,18066,'','".AddSlashes(pg_result($resaco,$iresaco,'k125_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3191,18067,'','".AddSlashes(pg_result($resaco,$iresaco,'k125_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3191,18068,'','".AddSlashes(pg_result($resaco,$iresaco,'k125_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3191,18069,'','".AddSlashes(pg_result($resaco,$iresaco,'k125_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3191,19632,'','".AddSlashes(pg_result($resaco,$iresaco,'k125_valordisponivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3191,20136,'','".AddSlashes(pg_result($resaco,$iresaco,'k125_abatimentosituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3191,20137,'','".AddSlashes(pg_result($resaco,$iresaco,'k125_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from abatimento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k125_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k125_sequencial = $k125_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Abatimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k125_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Abatimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k125_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:abatimento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

  /**
   * @param null $iSequencial
   * @param string $sCampos
   * @param string|null $sOrdem
   * @param string|null $sWhere
   *
   * @return string
   */
  public function sql_query($iSequencial = null, $sCampos = "*", $sOrdem = null, $sWhere = null) {

    $sSql = "select {$sCampos} ";

    $sSql .= " from abatimento ";
    $sSql .= "      inner join db_config  on  db_config.codigo = abatimento.k125_instit";
    $sSql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = abatimento.k125_usuario";
    $sSql .= "      inner join tipoabatimento  on  tipoabatimento.k126_sequencial = abatimento.k125_tipoabatimento";
    $sSql .= "      inner join abatimentosituacao  on  abatimentosituacao.k165_sequencial = abatimento.k125_abatimentosituacao";
    $sSql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
    $sSql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
    $sSql .= "      left join abatimentorecibo on k125_sequencial = k127_abatimento";
    $sSql .= "      left join arrenumcgm on arrenumcgm.k00_numpre = k127_numprerecibo";

    if ($iSequencial) {
     $sWhere .= " abatimento.k125_sequencial = {$iSequencial}" . (!empty($sWhere) ? " and " : '');
    }

    if ($sWhere) {
     $sSql .= " where {$sWhere} ";
    }


    if ($sOrdem) {
     $sSql .= " order by {$sOrdem}";
    }

    return $sSql;
  }

  /**
   * @param integer|null $iSequencial
   * @param string $sCampos
   * @param string|null $sOrdem
   * @param string|null $sWhere
   *
   * @return string
   */
  public function sql_query_file($iSequencial = null, $sCampos = "*", $sOrdem = null, $sWhere = null) {

    $sSql = "select {$sCampos} ";
    $sSql .= " from abatimento ";

    if ($iSequencial) {
      $sWhere .= "k125_sequencial = {$iSequencial}" . (!empty($sWhere) ? " and " : '');
    }

    if ($sWhere) {
      $sSql .= " where  {$sWhere}";
    }

    if ($sOrdem) {
      $sSql .= " order by {$sOrdem}";
    }

    return $sSql;
  }

   public function sql_queryCreditoManual($sCampos, $sWhere) {

  	$sSqlCreditoManual  = "select {$sCampos}                                                                                                          ";
  	$sSqlCreditoManual .= "  from abatimento                                                                                                          ";
  	$sSqlCreditoManual .= " inner join abatimentorecibo            on abatimentorecibo.k127_abatimento           = abatimento.k125_sequencial         ";
  	$sSqlCreditoManual .= " inner join arrenumcgm                  on arrenumcgm.k00_numpre                      = abatimentorecibo.k127_numprerecibo ";
  	$sSqlCreditoManual .= "  left join abatimentoprotprocesso      on abatimentoprotprocesso.k159_abatimento     = abatimento.k125_sequencial         ";
  	$sSqlCreditoManual .= "  left join abatimentoprocessoexterno   on abatimentoprocessoexterno.k160_abatimento  = abatimento.k125_sequencial         ";
  	$sSqlCreditoManual .= " where abatimento.k125_tipoabatimento = 3                                                                                  ";
  	$sSqlCreditoManual .= "   and {$sWhere}                                                                                                           ";

  	return $sSqlCreditoManual;

  }
   public function sql_queryAbatimentoNumpre($iNumpre, $iTipoAbatimento = null) {

    $sSqlCredito  = "select abatimentorecibo.*,                                                               ";
    $sSqlCredito .= "       abatimento.*,                                                                     ";
    $sSqlCredito .= "       recibo.*,                                                                         ";
    $sSqlCredito .= "       tabrec.*,                                                                         ";
    $sSqlCredito .= "       histcalc.*,                                                                       ";
    $sSqlCredito .= "       arretipo.*                                                                        ";
    $sSqlCredito .= "  from abatimentorecibo                                                                  ";
    $sSqlCredito .= " inner join abatimento on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento  ";
    $sSqlCredito .= " inner join recibo     on recibo.k00_numpre          = abatimentorecibo.k127_numprerecibo";
    $sSqlCredito .= " inner join arretipo   on arretipo.k00_tipo          = recibo.k00_tipo                   ";
    $sSqlCredito .= " inner join tabrec     on tabrec.k02_codigo          = recibo.k00_receit                 ";
    $sSqlCredito .= " inner join histcalc   on histcalc.k01_codigo        = recibo.k00_hist                   ";
    $sSqlCredito .= " where abatimentorecibo.k127_numprerecibo            = {$iNumpre}                        ";

    if (!empty($iTipoAbatimento)) {
      $sSqlCredito .= " and abatimento.k125_tipoabatimento                = {$iTipoAbatimento}                ";
    }

    return $sSqlCredito;

  }
   public function sql_queryListaCreditosTransferencia($sCampos, $sWhere, $sOrderBy) {

   	$dDataSistema             = date('Y-m-d', db_getsession('DB_datausu'));

    $sSqlCreditosDisponiveis  = "select {$sCampos}                                                                                                                                            ";
    $sSqlCreditosDisponiveis .= "  from abatimento                                                                                                                                            ";
    $sSqlCreditosDisponiveis .= "  inner join abatimentorecibo            on abatimentorecibo.k127_abatimento = abatimento.k125_sequencial                                                    ";
    $sSqlCreditosDisponiveis .= "  inner join arrenumcgm                  on arrenumcgm.k00_numpre            = abatimentorecibo.k127_numprerecibo                                            ";
    $sSqlCreditosDisponiveis .= "   left join arrematric                  on arrematric.k00_numpre            = abatimentorecibo.k127_numprerecibo                                            ";
    $sSqlCreditosDisponiveis .= "   left join arreinscr                   on arreinscr.k00_numpre             = abatimentorecibo.k127_numprerecibo                                            ";
    $sSqlCreditosDisponiveis .= "  where k125_tipoabatimento = 3                                                                                                                              ";
    $sSqlCreditosDisponiveis .= "    and {$sWhere} 																																																								                            ";

    /**
     * Para validar a data de vencimento do credito
     * Caso o crédito possua uma ou mais regras vinculadas, calcula-se a data de lançamento do crédito somado ao menor tempo de validade dentre as regras vinculadas
     * Caso não possua regra vinculada, somará 99999999 dias a data de lançamento
     */
    $sSqlCreditosDisponiveis .= "    and (k125_datalanc + ((select coalesce(min(k155_tempovalidade::integer), 99999999)                                                                       ";
    $sSqlCreditosDisponiveis .= " 		                        from abatimentoregracompensacao                                                                                                 ";
    $sSqlCreditosDisponiveis .= " 		                       inner join regracompensacao on regracompensacao.k155_sequencial = abatimentoregracompensacao.k156_regracompensacao               ";
    $sSqlCreditosDisponiveis .= " 		                       where abatimentoregracompensacao.k156_abatimento = abatimento.k125_sequencial)::integer||' days')::interval) >= '{$dDataSistema}'";

    /**
     * Caso o crédito esteja vinculado a alguma regra e uma ou mais dessas regras não permita transferencia, não será permitido a transferência do crédito
     */
    $sSqlCreditosDisponiveis .= "    and not exists (select 1                                                                                                                                 ";
    $sSqlCreditosDisponiveis .= "                      from abatimentoregracompensacao                                                                                                        ";
    $sSqlCreditosDisponiveis .= "                     inner join regracompensacao on regracompensacao.k155_sequencial = abatimentoregracompensacao.k156_regracompensacao                      ";
    $sSqlCreditosDisponiveis .= "                     where abatimentoregracompensacao.k156_abatimento = abatimento.k125_sequencial                                                           ";
    $sSqlCreditosDisponiveis .= "                       and k155_permitetransferencia is false)                                                                                               ";
    $sSqlCreditosDisponiveis .= "  order by k125_sequencial                                                                                                                                   ";

    return $sSqlCreditosDisponiveis;

  }
   /**
   * Busca informacoes do credito
   *
   * @param integer $iCredito - sequencial do credito
   * @access public
   * @return string
   */
  public function sql_queryDadosCreditos($iCredito) {

    $sSql = " select *,";

    /**
     * Origem
     */
    $sSql .= " case                                                                                  ";
    $sSql .= "   when abatimentodisbanco.k132_sequencial is not null and abatimento.k125_perc >= 100 ";
    $sSql .= "     then 'PAGAMENTO EM DUPLICIDADE/MAIOR'                                             ";
    $sSql .= "   when abatimentodisbanco.k132_sequencial is not null and abatimento.k125_perc  < 100 ";
    $sSql .= "     then 'PAGAMENTO A MENOR'                                                          ";
    $sSql .= "   when abatimentotransferencia.k158_sequencial is not null                            ";
    $sSql .= "     then 'TRANSFERENCIA'                                                              ";
    $sSql .= "   else 'MANUAL'                                                                       ";
    $sSql .= " end as origem,                                                                        ";

    /**
     * Dono do credito
     */
    $sSql .= " cgm.z01_numcgm as dono_credito ";

    /**
     * Abatimento
     */
    $sSql .= " from abatimento ";

    /**
     * Usuario
     */
    $sSql .= " inner join db_config      on  db_config.codigo               = abatimento.k125_instit         ";
    $sSql .= " inner join db_usuarios    on  db_usuarios.id_usuario         = abatimento.k125_usuario        ";
    $sSql .= " inner join tipoabatimento on  tipoabatimento.k126_sequencial = abatimento.k125_tipoabatimento ";

    /**
     * Recibo
     */
    $sSql .= " inner join abatimentorecibo on abatimentorecibo.k127_abatimento = abatimento.k125_sequencial         ";
    $sSql .= " inner join recibo           on recibo.k00_numpre                = abatimentorecibo.k127_numprerecibo ";

    /**
     * CGM
     */
    $sSql .= " inner join arrenumcgm on recibo.k00_numpre     = arrenumcgm.k00_numpre ";
    $sSql .= " inner join cgm        on arrenumcgm.k00_numcgm = cgm.z01_numcgm        ";

    /**
     * Tipo do debito
     */
    $sSql .= " inner join tabrec   on tabrec.k02_codigo = recibo.k00_receit  ";
    $sSql .= " inner join arretipo on arretipo.k00_tipo = recibo.k00_tipo    ";

    /**
     * Disbanco
     */
    $sSql .= " left join abatimentodisbanco on abatimentodisbanco.k132_abatimento  = abatimento.k125_sequencial ";

    /**
     * Transferencia e utilizacao
     */
    $sSql .= " left join abatimentotransferencia on abatimentotransferencia.k158_abatimentodestino = abatimento.k125_sequencial                    ";
    $sSql .= " left join abatimentoutilizacao    on abatimentoutilizacao.k157_abatimento           = abatimentotransferencia.k158_abatimentoorigem ";

    /**
     * Where
     */
    $sSql .= " where abatimento.k125_sequencial = {$iCredito} ";

    return $sSql;
  }

  /**
   * Busca os abatimentos do Tipo desconto
   * @param string $sCampos
   * @param string $sWhere
   * @param string $sOrder
   * @param string $sGroup
   * @return string
   */
  public function sql_queryDescontos($sCampos = '*', $sWhere = '', $sOrder = '', $sGroup = '') {

    $sSql = "select {$sCampos}                                                                                             ";
    $sSql.= "  from abatimento                                                                                             ";
    $sSql.= "       inner join abatimentoarreckey on abatimento.k125_sequencial       = abatimentoarreckey.k128_abatimento ";
    $sSql.= "       inner join arreckey           on arreckey.k00_sequencial          = abatimentoarreckey.k128_arreckey   ";
    $sSql.= " where abatimento.k125_tipoabatimento = 2 and abatimento.k125_instit     = ".db_getsession('DB_instit')."     ";

    if (!empty($sWhere)) {
      $sSql.= " and "     .$sWhere;
    }

    if (!empty($sGroup)) {
      $sSql.= " group by ".$sGroup;
    }

    if (!empty($sOrder)) {
      $sSql.= " order by ".$sOrder;
    }

    return $sSql;
  }

  /**
   * Busca Origem dos Créditos
   * @param string $sCampos
   * @param string $sWhere
   * @param string $sOrder
   * @param string $sGroup
   * @return string
   */
  public function sql_query_origem($sCampos = '*', $sWhere = '', $sOrder = '', $sGroup = '') {

    $sSql  = "select {$sCampos} ";
    $sSql .= "  from abatimento";
    $sSql .= "    inner join abatimentorecibo on abatimentorecibo.k127_abatimento = abatimento.k125_sequencial";
    $sSql .= "    inner join recibo on recibo.k00_numpre = abatimentorecibo.k127_numprerecibo";
    $sSql .= "    inner join abatimentoarreckey on k128_abatimento = k125_sequencial";
    $sSql .= "    inner join arreckey on k128_arreckey = k00_sequencial";
    $sSql .= "    inner join arrenumcgm on recibo.k00_numpre = arrenumcgm.k00_numpre";
    $sSql .= "    inner join cgm on arrenumcgm.k00_numcgm = cgm.z01_numcgm";
    $sSql .= "    inner join arretipo on arretipo.k00_tipo = arreckey.k00_tipo";

    if (!empty($sWhere)) {
      $sSql .= " where " . $sWhere;
    }

    if (!empty($sGroup)) {
      $sSql .= " group by " . $sGroup;
    }

    if (!empty($sOrder)) {
      $sSql .= " order by " . $sOrder;
    }

    return $sSql;
  }

  /**
   * Busca utiliza
   * @param string $sCampos
   * @param string $sWhere
   * @param string $sOrder
   * @param string $sGroup
   * @return string
   */
  public function sql_query_utilizacao($sCampos = '*', $sWhere = '', $sOrder = '', $sGroup = '') {

    $sSql  = "select {$sCampos}";
    $sSql .= "  from abatimento";
    $sSql .= "   inner join abatimentoutilizacao        on k157_abatimento   = k125_sequencial       ";
    $sSql .= "   inner join abatimentorecibo            on k127_abatimento   = k125_sequencial       ";
    $sSql .= "   inner join arrenumcgm                  on arrenumcgm.k00_numpre = k127_numprerecibo ";
    $sSql .= "   inner join recibo                      on recibo.k00_numpre = k127_numprerecibo     ";
    $sSql .= "   inner join cgm                         on arrenumcgm.k00_numcgm = z01_numcgm        ";
    $sSql .= "   left  join abatimentoutilizacaodestino on k170_utilizacao   = k157_sequencial       ";
    $sSql .= "   left  join arretipo                    on k170_tipo         = arretipo.k00_tipo     ";
    $sSql .= "   left  join tabrec                      on k170_receit       = k02_codigo            ";

    if (!empty($sWhere)) {
      $sSql .= " where " . $sWhere;
    }

    if (!empty($sGroup)) {
      $sSql.= " group by ".$sGroup;
    }

    if (!empty($sOrder)) {
      $sSql .= " order by " . $sOrder;
    }

    return $sSql;
  }
}
