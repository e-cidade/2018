<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: meioambiente
//CLASSE DA ENTIDADE parecertecnico
class cl_parecertecnico {
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
   var $am08_sequencial = 0;
   var $am08_empreendimento = 0;
   var $am08_protprocesso = 0;
   var $am08_pareceranterior = 0;
   var $am08_dataemissao_dia = null;
   var $am08_dataemissao_mes = null;
   var $am08_dataemissao_ano = null;
   var $am08_dataemissao = null;
   var $am08_datavencimento_dia = null;
   var $am08_datavencimento_mes = null;
   var $am08_datavencimento_ano = null;
   var $am08_datavencimento = null;
   var $am08_tipolicenca = 0;
   var $am08_datageracao_dia = null;
   var $am08_datageracao_mes = null;
   var $am08_datageracao_ano = null;
   var $am08_datageracao = null;
   var $am08_favoravel = 'f';
   var $am08_observacao = null;
   var $am08_arquivo = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 am08_sequencial = int4 = Cod. Licença
                 am08_empreendimento = int4 = Empreendimento
                 am08_protprocesso = int4 = Protocolo
                 am08_pareceranterior = int4 = Parecer Anterior
                 am08_dataemissao = date = Data de Emissão
                 am08_datavencimento = date = Data de Vencimento
                 am08_tipolicenca = int4 = Tipo de Licença
                 am08_datageracao = date = Data de Geração
                 am08_favoravel = bool = Favorável
                 am08_observacao = text = Observações
                 am08_arquivo = oid = Arquivo Parecer Técnico
                 ";
   //funcao construtor da classe
   function cl_parecertecnico() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("parecertecnico");
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
       $this->am08_sequencial = ($this->am08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_sequencial"]:$this->am08_sequencial);
       $this->am08_empreendimento = ($this->am08_empreendimento == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_empreendimento"]:$this->am08_empreendimento);
       $this->am08_protprocesso = ($this->am08_protprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_protprocesso"]:$this->am08_protprocesso);
       $this->am08_pareceranterior = ($this->am08_pareceranterior == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_pareceranterior"]:$this->am08_pareceranterior);
       if($this->am08_dataemissao == ""){
         $this->am08_dataemissao_dia = ($this->am08_dataemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_dia"]:$this->am08_dataemissao_dia);
         $this->am08_dataemissao_mes = ($this->am08_dataemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_mes"]:$this->am08_dataemissao_mes);
         $this->am08_dataemissao_ano = ($this->am08_dataemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_ano"]:$this->am08_dataemissao_ano);
         if($this->am08_dataemissao_dia != ""){
            $this->am08_dataemissao = $this->am08_dataemissao_ano."-".$this->am08_dataemissao_mes."-".$this->am08_dataemissao_dia;
         }
       }
       if($this->am08_datavencimento == ""){
         $this->am08_datavencimento_dia = ($this->am08_datavencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_dia"]:$this->am08_datavencimento_dia);
         $this->am08_datavencimento_mes = ($this->am08_datavencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_mes"]:$this->am08_datavencimento_mes);
         $this->am08_datavencimento_ano = ($this->am08_datavencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_ano"]:$this->am08_datavencimento_ano);
         if($this->am08_datavencimento_dia != ""){
            $this->am08_datavencimento = $this->am08_datavencimento_ano."-".$this->am08_datavencimento_mes."-".$this->am08_datavencimento_dia;
         }
       }
       $this->am08_tipolicenca = ($this->am08_tipolicenca == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_tipolicenca"]:$this->am08_tipolicenca);
       if($this->am08_datageracao == ""){
         $this->am08_datageracao_dia = ($this->am08_datageracao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_datageracao_dia"]:$this->am08_datageracao_dia);
         $this->am08_datageracao_mes = ($this->am08_datageracao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_datageracao_mes"]:$this->am08_datageracao_mes);
         $this->am08_datageracao_ano = ($this->am08_datageracao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_datageracao_ano"]:$this->am08_datageracao_ano);
         if($this->am08_datageracao_dia != ""){
            $this->am08_datageracao = $this->am08_datageracao_ano."-".$this->am08_datageracao_mes."-".$this->am08_datageracao_dia;
         }
       }
       $this->am08_favoravel = ($this->am08_favoravel == "f"?@$GLOBALS["HTTP_POST_VARS"]["am08_favoravel"]:$this->am08_favoravel);
       $this->am08_observacao = ($this->am08_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_observacao"]:$this->am08_observacao);
       $this->am08_arquivo = ($this->am08_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_arquivo"]:$this->am08_arquivo);
     }else{
       $this->am08_sequencial = ($this->am08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_sequencial"]:$this->am08_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($am08_sequencial=null){
      $this->atualizacampos();
     if($this->am08_empreendimento == null ){
       $this->erro_sql = " Campo Empreendimento não informado.";
       $this->erro_campo = "am08_empreendimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am08_protprocesso == null ){
       $this->erro_sql = " Campo Protocolo não informado.";
       $this->erro_campo = "am08_protprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->am08_favoravel == null ){
       $this->erro_sql = " Campo Favorável não informado.";
       $this->erro_campo = "am08_favoravel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am08_pareceranterior == null ){
       $this->am08_pareceranterior = "0";
     }
     if($this->am08_dataemissao == null ){
       $this->am08_dataemissao = "null";
     }
     if($this->am08_datavencimento == null ){
       $this->am08_datavencimento = "null";
     }
     if($this->am08_tipolicenca == null){
       $this->erro_sql = " Campo Tipo de Licença não informado.";
       $this->erro_campo = "am08_tipolicenca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am08_datageracao == null ){
       $this->am08_datageracao = date('Y-m-d');
     }
     if($this->am08_observacao == null ){
      $this->am08_observacao = null;
     }
     if($this->am08_arquivo == null ){
       $this->am08_arquivo = 'null';
     }
     if($am08_sequencial == "" || $am08_sequencial == null ){
       $result = db_query("select nextval('licencaempreendimento_am08_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: licencaempreendimento_am08_sequencial_seq do campo: am08_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->am08_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from licencaempreendimento_am08_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am08_sequencial)){
         $this->erro_sql = " Campo am08_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am08_sequencial = $am08_sequencial;
       }
     }
     if(($this->am08_sequencial == null) || ($this->am08_sequencial == "") ){
       $this->erro_sql = " Campo am08_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into parecertecnico(
                                       am08_sequencial
                                      ,am08_empreendimento
                                      ,am08_protprocesso
                                      ,am08_pareceranterior
                                      ,am08_dataemissao
                                      ,am08_datavencimento
                                      ,am08_tipolicenca
                                      ,am08_datageracao
                                      ,am08_favoravel
                                      ,am08_observacao
                                      ,am08_arquivo
                       )
                values (
                                $this->am08_sequencial
                               ,$this->am08_empreendimento
                               ,$this->am08_protprocesso
                               ,$this->am08_pareceranterior
                               ,".($this->am08_dataemissao == "null" || $this->am08_dataemissao == ""?"null":"'".$this->am08_dataemissao."'")."
                               ,".($this->am08_datavencimento == "null" || $this->am08_datavencimento == ""?"null":"'".$this->am08_datavencimento."'")."
                               ,$this->am08_tipolicenca
                               ,".($this->am08_datageracao == "null" || $this->am08_datageracao == ""?"null":"'".$this->am08_datageracao."'")."
                               ,'$this->am08_favoravel'
                               ,'$this->am08_observacao'
                               ,$this->am08_arquivo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Emissao de Pareceres Técnicos ($this->am08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Emissao de Pareceres Técnicos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Emissao de Pareceres Técnicos ($this->am08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am08_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am08_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20805,'$this->am08_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3744,20805,'','".AddSlashes(pg_result($resaco,0,'am08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20806,'','".AddSlashes(pg_result($resaco,0,'am08_empreendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20807,'','".AddSlashes(pg_result($resaco,0,'am08_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20808,'','".AddSlashes(pg_result($resaco,0,'am08_pareceranterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20809,'','".AddSlashes(pg_result($resaco,0,'am08_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20810,'','".AddSlashes(pg_result($resaco,0,'am08_datavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20811,'','".AddSlashes(pg_result($resaco,0,'am08_tipolicenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20856,'','".AddSlashes(pg_result($resaco,0,'am08_datageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20857,'','".AddSlashes(pg_result($resaco,0,'am08_favoravel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20858,'','".AddSlashes(pg_result($resaco,0,'am08_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20872,'','".AddSlashes(pg_result($resaco,0,'am08_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($am08_sequencial=null) {
      $this->atualizacampos();
     $sql = " update parecertecnico set ";
     $virgula = "";
     if(trim($this->am08_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_sequencial"])){
       $sql  .= $virgula." am08_sequencial = $this->am08_sequencial ";
       $virgula = ",";
       if(trim($this->am08_sequencial) == null ){
         $this->erro_sql = " Campo Cod. Licença não informado.";
         $this->erro_campo = "am08_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am08_empreendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_empreendimento"])){
       $sql  .= $virgula." am08_empreendimento = $this->am08_empreendimento ";
       $virgula = ",";
       if(trim($this->am08_empreendimento) == null ){
         $this->erro_sql = " Campo Empreendimento não informado.";
         $this->erro_campo = "am08_empreendimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am08_protprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_protprocesso"])){
       $sql  .= $virgula." am08_protprocesso = $this->am08_protprocesso ";
       $virgula = ",";
       if(trim($this->am08_protprocesso) == null ){
         $this->erro_sql = " Campo Protocolo não informado.";
         $this->erro_campo = "am08_protprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am08_pareceranterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_pareceranterior"])){
        if(trim($this->am08_pareceranterior)=="" && isset($GLOBALS["HTTP_POST_VARS"]["am08_pareceranterior"])){
           $this->am08_pareceranterior = "0" ;
        }
       $sql  .= $virgula." am08_pareceranterior = $this->am08_pareceranterior ";
       $virgula = ",";
     }
     if(trim($this->am08_dataemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_dia"] !="") ){
       $sql  .= $virgula." am08_dataemissao = '$this->am08_dataemissao' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_dia"])){
         $sql  .= $virgula." am08_dataemissao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->am08_datavencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_dia"] !="") ){
       $sql  .= $virgula." am08_datavencimento = '$this->am08_datavencimento' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_dia"])){
         $sql  .= $virgula." am08_datavencimento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->am08_tipolicenca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_tipolicenca"])){
       $sql  .= $virgula." am08_tipolicenca = $this->am08_tipolicenca ";
       $virgula = ",";
       if(trim($this->am08_tipolicenca) == null ){
         $this->erro_sql = " Campo Tipo de Licença não informado.";
         $this->erro_campo = "am08_tipolicenca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am08_datageracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_datageracao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["am08_datageracao_dia"] !="") ){
       $sql  .= $virgula." am08_datageracao = '$this->am08_datageracao' ";
       $virgula = ",";
       if(trim($this->am08_datageracao) == null ){
         $this->am08_datageracao = date('Y-m-d');
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["am08_datageracao_dia"])){
         $sql  .= $virgula." am08_datageracao = null ";
         $virgula = ",";
         if(trim($this->am08_datageracao) == null ){
           $this->erro_sql = " Campo Data de Geração não informado.";
           $this->erro_campo = "am08_datageracao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->am08_favoravel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_favoravel"])){
       $sql  .= $virgula." am08_favoravel = '$this->am08_favoravel' ";
       $virgula = ",";
       if(trim($this->am08_favoravel) == null ){
         $this->erro_sql = " Campo Favorável não informado.";
         $this->erro_campo = "am08_favoravel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am08_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_observacao"])){
       $sql  .= $virgula." am08_observacao = '$this->am08_observacao' ";
       $virgula = ",";
       if(trim($this->am08_observacao) == null ){
         $this->erro_sql = " Campo Observações não informado.";
         $this->erro_campo = "am08_observacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am08_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_arquivo"])){
       $sql  .= $virgula." am08_arquivo = $this->am08_arquivo ";
       $virgula = ",";
       if(trim($this->am08_arquivo) == null ){
         $this->am08_arquivo = 'null';
       }
     }
     $sql .= " where ";
     if($am08_sequencial!=null){
       $sql .= " am08_sequencial = $this->am08_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am08_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20805,'$this->am08_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_sequencial"]) || $this->am08_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3744,20805,'".AddSlashes(pg_result($resaco,$conresaco,'am08_sequencial'))."','$this->am08_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_empreendimento"]) || $this->am08_empreendimento != "")
             $resac = db_query("insert into db_acount values($acount,3744,20806,'".AddSlashes(pg_result($resaco,$conresaco,'am08_empreendimento'))."','$this->am08_empreendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_protprocesso"]) || $this->am08_protprocesso != "")
             $resac = db_query("insert into db_acount values($acount,3744,20807,'".AddSlashes(pg_result($resaco,$conresaco,'am08_protprocesso'))."','$this->am08_protprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_pareceranterior"]) || $this->am08_pareceranterior != "")
             $resac = db_query("insert into db_acount values($acount,3744,20808,'".AddSlashes(pg_result($resaco,$conresaco,'am08_pareceranterior'))."','$this->am08_pareceranterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_dataemissao"]) || $this->am08_dataemissao != "")
             $resac = db_query("insert into db_acount values($acount,3744,20809,'".AddSlashes(pg_result($resaco,$conresaco,'am08_dataemissao'))."','$this->am08_dataemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_datavencimento"]) || $this->am08_datavencimento != "")
             $resac = db_query("insert into db_acount values($acount,3744,20810,'".AddSlashes(pg_result($resaco,$conresaco,'am08_datavencimento'))."','$this->am08_datavencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_tipolicenca"]) || $this->am08_tipolicenca != "")
             $resac = db_query("insert into db_acount values($acount,3744,20811,'".AddSlashes(pg_result($resaco,$conresaco,'am08_tipolicenca'))."','$this->am08_tipolicenca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_datageracao"]) || $this->am08_datageracao != "")
             $resac = db_query("insert into db_acount values($acount,3744,20856,'".AddSlashes(pg_result($resaco,$conresaco,'am08_datageracao'))."','$this->am08_datageracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_favoravel"]) || $this->am08_favoravel != "")
             $resac = db_query("insert into db_acount values($acount,3744,20857,'".AddSlashes(pg_result($resaco,$conresaco,'am08_favoravel'))."','$this->am08_favoravel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_observacao"]) || $this->am08_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3744,20858,'".AddSlashes(pg_result($resaco,$conresaco,'am08_observacao'))."','$this->am08_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_arquivo"]) || $this->am08_arquivo != "")
             $resac = db_query("insert into db_acount values($acount,3744,20872,'".AddSlashes(pg_result($resaco,$conresaco,'am08_arquivo'))."','$this->am08_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Emissao de Pareceres Técnicos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Emissao de Pareceres Técnicos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($am08_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am08_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20805,'$am08_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3744,20805,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20806,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_empreendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20807,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20808,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_pareceranterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20809,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20810,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_datavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20811,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_tipolicenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20856,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_datageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20857,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_favoravel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20858,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20872,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from parecertecnico
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am08_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am08_sequencial = $am08_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Emissao de Pareceres Técnicos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Emissao de Pareceres Técnicos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am08_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:parecertecnico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($am08_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from parecertecnico ";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = parecertecnico.am08_protprocesso";
     $sql .= "      inner join empreendimento  on  empreendimento.am05_sequencial = parecertecnico.am08_empreendimento";
     $sql .= "      inner join tipolicenca  on  tipolicenca.am09_sequencial = parecertecnico.am08_tipolicenca";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql .= "      inner join bairro  on  bairro.j13_codi = empreendimento.am05_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = empreendimento.am05_ruas";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = empreendimento.am05_cgm";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am08_sequencial)) {
         $sql2 .= " where parecertecnico.am08_sequencial = $am08_sequencial ";
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
   public function sql_query_file ($am08_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from parecertecnico ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am08_sequencial)){
         $sql2 .= " where parecertecnico.am08_sequencial = $am08_sequencial ";
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

  /**
   * Criamos a query para que seja criada a query que busca o sequencial da licença
   * vinculada ao parecer anterior
   *
   * @param  int $am08_sequencial
   *
   * @return string
   */
  public function sql_query_codigo_licenca($am08_sequencial) {

    $sSql  = "select am13_sequencial                                                               ";
    $sSql .= "  from parecertecnico                                                                ";
    $sSql .= "       left join licencaempreendimento on am13_parecertecnico = am08_pareceranterior ";
    $sSql .= " where am08_sequencial = {$am08_sequencial}                                          ";

    return $sSql;
  }

}