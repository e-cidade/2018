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
//CLASSE DA ENTIDADE atividadeimpacto
class cl_atividadeimpacto {
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
   var $am03_sequencial = 0;
   var $am03_criterioatividadeimpacto = 0;
   var $am03_ramo = null;
   var $am03_descricao = null;
   var $am03_potencialpoluidor = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 am03_sequencial = int4 = Atividade
                 am03_criterioatividadeimpacto = int4 = Critério de Medição
                 am03_ramo = varchar(10) = Ramo da Atividade
                 am03_descricao = varchar(255) = Descrição
                 am03_potencialpoluidor = varchar(20) = Potencial Poluidor
                 ";
   //funcao construtor da classe
   function cl_atividadeimpacto() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atividadeimpacto");
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
       $this->am03_sequencial = ($this->am03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am03_sequencial"]:$this->am03_sequencial);
       $this->am03_criterioatividadeimpacto = ($this->am03_criterioatividadeimpacto == ""?@$GLOBALS["HTTP_POST_VARS"]["am03_criterioatividadeimpacto"]:$this->am03_criterioatividadeimpacto);
       $this->am03_ramo = ($this->am03_ramo == ""?@$GLOBALS["HTTP_POST_VARS"]["am03_ramo"]:$this->am03_ramo);
       $this->am03_descricao = ($this->am03_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["am03_descricao"]:$this->am03_descricao);
       $this->am03_potencialpoluidor = ($this->am03_potencialpoluidor == ""?@$GLOBALS["HTTP_POST_VARS"]["am03_potencialpoluidor"]:$this->am03_potencialpoluidor);
     }else{
       $this->am03_sequencial = ($this->am03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am03_sequencial"]:$this->am03_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($am03_sequencial){
      $this->atualizacampos();
     if($this->am03_criterioatividadeimpacto == null ){
       $this->erro_sql = " Campo Critério de Medição não informado.";
       $this->erro_campo = "am03_criterioatividadeimpacto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am03_ramo == null ){
       $this->erro_sql = " Campo Ramo da Atividade não informado.";
       $this->erro_campo = "am03_ramo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am03_descricao == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "am03_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am03_potencialpoluidor == null ){
       $this->erro_sql = " Campo Potencial Poluidor não informado.";
       $this->erro_campo = "am03_potencialpoluidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am03_sequencial == "" || $am03_sequencial == null ){
       $result = db_query("select nextval('atividadeimpacto_am03_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atividadeimpacto_am03_sequencial_seq do campo: am03_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->am03_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from atividadeimpacto_am03_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am03_sequencial)){
         $this->erro_sql = " Campo am03_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am03_sequencial = $am03_sequencial;
       }
     }
     if(($this->am03_sequencial == null) || ($this->am03_sequencial == "") ){
       $this->erro_sql = " Campo am03_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atividadeimpacto(
                                       am03_sequencial
                                      ,am03_criterioatividadeimpacto
                                      ,am03_ramo
                                      ,am03_descricao
                                      ,am03_potencialpoluidor
                       )
                values (
                                $this->am03_sequencial
                               ,$this->am03_criterioatividadeimpacto
                               ,'$this->am03_ramo'
                               ,'$this->am03_descricao'
                               ,'$this->am03_potencialpoluidor'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de atividade de impacto local ($this->am03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de atividade de impacto local já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de atividade de impacto local ($this->am03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am03_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am03_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20767,'$this->am03_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3737,20767,'','".AddSlashes(pg_result($resaco,0,'am03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3737,20774,'','".AddSlashes(pg_result($resaco,0,'am03_criterioatividadeimpacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3737,20769,'','".AddSlashes(pg_result($resaco,0,'am03_ramo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3737,20770,'','".AddSlashes(pg_result($resaco,0,'am03_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3737,20773,'','".AddSlashes(pg_result($resaco,0,'am03_potencialpoluidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($am03_sequencial=null) {
      $this->atualizacampos();
     $sql = " update atividadeimpacto set ";
     $virgula = "";
     if(trim($this->am03_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am03_sequencial"])){
       $sql  .= $virgula." am03_sequencial = $this->am03_sequencial ";
       $virgula = ",";
       if(trim($this->am03_sequencial) == null ){
         $this->erro_sql = " Campo Atividade não informado.";
         $this->erro_campo = "am03_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am03_criterioatividadeimpacto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am03_criterioatividadeimpacto"])){
       $sql  .= $virgula." am03_criterioatividadeimpacto = $this->am03_criterioatividadeimpacto ";
       $virgula = ",";
       if(trim($this->am03_criterioatividadeimpacto) == null ){
         $this->erro_sql = " Campo Critério de Medição não informado.";
         $this->erro_campo = "am03_criterioatividadeimpacto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am03_ramo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am03_ramo"])){
       $sql  .= $virgula." am03_ramo = '$this->am03_ramo' ";
       $virgula = ",";
       if(trim($this->am03_ramo) == null ){
         $this->erro_sql = " Campo Ramo da Atividade não informado.";
         $this->erro_campo = "am03_ramo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am03_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am03_descricao"])){
       $sql  .= $virgula." am03_descricao = '$this->am03_descricao' ";
       $virgula = ",";
       if(trim($this->am03_descricao) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "am03_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am03_potencialpoluidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am03_potencialpoluidor"])){
       $sql  .= $virgula." am03_potencialpoluidor = '$this->am03_potencialpoluidor' ";
       $virgula = ",";
       if(trim($this->am03_potencialpoluidor) == null ){
         $this->erro_sql = " Campo Potencial Poluidor não informado.";
         $this->erro_campo = "am03_potencialpoluidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am03_sequencial!=null){
       $sql .= " am03_sequencial = $this->am03_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am03_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20767,'$this->am03_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am03_sequencial"]) || $this->am03_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3737,20767,'".AddSlashes(pg_result($resaco,$conresaco,'am03_sequencial'))."','$this->am03_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am03_criterioatividadeimpacto"]) || $this->am03_criterioatividadeimpacto != "")
             $resac = db_query("insert into db_acount values($acount,3737,20774,'".AddSlashes(pg_result($resaco,$conresaco,'am03_criterioatividadeimpacto'))."','$this->am03_criterioatividadeimpacto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am03_ramo"]) || $this->am03_ramo != "")
             $resac = db_query("insert into db_acount values($acount,3737,20769,'".AddSlashes(pg_result($resaco,$conresaco,'am03_ramo'))."','$this->am03_ramo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am03_descricao"]) || $this->am03_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3737,20770,'".AddSlashes(pg_result($resaco,$conresaco,'am03_descricao'))."','$this->am03_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am03_potencialpoluidor"]) || $this->am03_potencialpoluidor != "")
             $resac = db_query("insert into db_acount values($acount,3737,20773,'".AddSlashes(pg_result($resaco,$conresaco,'am03_potencialpoluidor'))."','$this->am03_potencialpoluidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de atividade de impacto local nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de atividade de impacto local nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($am03_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am03_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20767,'$am03_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3737,20767,'','".AddSlashes(pg_result($resaco,$iresaco,'am03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3737,20774,'','".AddSlashes(pg_result($resaco,$iresaco,'am03_criterioatividadeimpacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3737,20769,'','".AddSlashes(pg_result($resaco,$iresaco,'am03_ramo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3737,20770,'','".AddSlashes(pg_result($resaco,$iresaco,'am03_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3737,20773,'','".AddSlashes(pg_result($resaco,$iresaco,'am03_potencialpoluidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from atividadeimpacto
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am03_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am03_sequencial = $am03_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de atividade de impacto local nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de atividade de impacto local nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am03_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:atividadeimpacto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($am03_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from atividadeimpacto ";
     $sql .= "      inner join criterioatividadeimpacto on criterioatividadeimpacto.am01_sequencial = atividadeimpacto.am03_criterioatividadeimpacto";
     $sql .= "      inner join atividadeimpactoporte    on am03_sequencial = am04_atividadeimpacto";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am03_sequencial)) {
         $sql2 .= " where atividadeimpacto.am03_sequencial = $am03_sequencial ";
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
   public function sql_query_file ($am03_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from atividadeimpacto ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am03_sequencial)){
         $sql2 .= " where atividadeimpacto.am03_sequencial = $am03_sequencial ";
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
    * Método que retorna atividade de impacto, seu porte e critério de medição
    * @param $iCodigoAtividade - código da atividade
    * @param $sCampos - campos da consulta
    */
   public function getAtividadeImpactoPorteCriterio ( $iCodigoAtividade = null, $sCampos = null) {

    if (is_null($sCampos)) {
      $sCampos = "atividadeimpacto.*, am01_descricao, am02_sequencial, am02_descricao ";
    }

    $sSql  = " select $sCampos ";
    $sSql .= "   from atividadeimpacto";
    $sSql .= "        inner join criterioatividadeimpacto  on  criterioatividadeimpacto.am01_sequencial = atividadeimpacto.am03_criterioatividadeimpacto";
    $sSql .= "        inner join atividadeimpactoporte     on am03_sequencial = am04_atividadeimpacto";
    $sSql .= "        inner join porteatividadeimpacto     on am02_sequencial = am04_porteatividadeimpacto ";

    if (!empty($iCodigoAtividade)) {
      $sSql .= " where atividadeimpacto.am03_sequencial = $iCodigoAtividade ";
    }

    return $sSql;
  }

  public function sql_query_agrupado ($am03_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "", $sGroup = "am03_sequencial") {

    $sql  = "select {$campos}";
    $sql .= "  from atividadeimpacto ";
    $sql .= "      inner join criterioatividadeimpacto on criterioatividadeimpacto.am01_sequencial = atividadeimpacto.am03_criterioatividadeimpacto";
    $sql .= "      inner join atividadeimpactoporte    on am03_sequencial = am04_atividadeimpacto";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($am03_sequencial)) {
        $sql2 .= " where atividadeimpacto.am03_sequencial = $am03_sequencial ";
      }
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }

    if (!empty($sGroup)) {
     $sql2 .= " group by $sGroup ";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }

}