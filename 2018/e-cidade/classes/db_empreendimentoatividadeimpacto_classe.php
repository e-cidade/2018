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
//CLASSE DA ENTIDADE empreendimentoatividadeimpacto
class cl_empreendimentoatividadeimpacto {
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
   var $am06_sequencial = 0;
   var $am06_atividadeimpacto = 0;
   var $am06_empreendimento = 0;
   var $am06_principal = 'f';
   var $am06_atividadeimpactoporte = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 am06_sequencial = int4 = Código Empreendimento Atividade
                 am06_atividadeimpacto = int4 = Código da Atividade
                 am06_empreendimento = int4 = Código do Empreendimento
                 am06_principal = bool = Atividade
                 am06_atividadeimpactoporte = int4 = Porte
                 ";
   //funcao construtor da classe
   function cl_empreendimentoatividadeimpacto() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empreendimentoatividadeimpacto");
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
       $this->am06_sequencial = ($this->am06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am06_sequencial"]:$this->am06_sequencial);
       $this->am06_atividadeimpacto = ($this->am06_atividadeimpacto == ""?@$GLOBALS["HTTP_POST_VARS"]["am06_atividadeimpacto"]:$this->am06_atividadeimpacto);
       $this->am06_empreendimento = ($this->am06_empreendimento == ""?@$GLOBALS["HTTP_POST_VARS"]["am06_empreendimento"]:$this->am06_empreendimento);
       $this->am06_principal = ($this->am06_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["am06_principal"]:$this->am06_principal);
       $this->am06_atividadeimpactoporte = ($this->am06_atividadeimpactoporte == ""?@$GLOBALS["HTTP_POST_VARS"]["am06_atividadeimpactoporte"]:$this->am06_atividadeimpactoporte);
     }else{
       $this->am06_sequencial = ($this->am06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am06_sequencial"]:$this->am06_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($am06_sequencial){
      $this->atualizacampos();
     if($this->am06_atividadeimpacto == null ){
       $this->erro_sql = " Campo Código da Atividade não informado.";
       $this->erro_campo = "am06_atividadeimpacto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am06_empreendimento == null ){
       $this->erro_sql = " Campo Código do Empreendimento não informado.";
       $this->erro_campo = "am06_empreendimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am06_principal == null ){
       $this->erro_sql = " Campo Atividade não informado.";
       $this->erro_campo = "am06_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am06_atividadeimpactoporte == null ){
       $this->erro_sql = " Campo Porte não informado.";
       $this->erro_campo = "am06_atividadeimpactoporte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am06_sequencial == "" || $am06_sequencial == null ){
       $result = db_query("select nextval('empreendimentoatividadeimpacto_am06_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empreendimentoatividadeimpacto_am06_sequencial_seq do campo: am06_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->am06_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from empreendimentoatividadeimpacto_am06_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am06_sequencial)){
         $this->erro_sql = " Campo am06_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am06_sequencial = $am06_sequencial;
       }
     }
     if(($this->am06_sequencial == null) || ($this->am06_sequencial == "") ){
       $this->erro_sql = " Campo am06_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empreendimentoatividadeimpacto(
                                       am06_sequencial
                                      ,am06_atividadeimpacto
                                      ,am06_empreendimento
                                      ,am06_principal
                                      ,am06_atividadeimpactoporte
                       )
                values (
                                $this->am06_sequencial
                               ,$this->am06_atividadeimpacto
                               ,$this->am06_empreendimento
                               ,'$this->am06_principal'
                               ,$this->am06_atividadeimpactoporte
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro Empreendimento e Ativdade ($this->am06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro Empreendimento e Ativdade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro Empreendimento e Ativdade ($this->am06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am06_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am06_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20793,'$this->am06_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3742,20793,'','".AddSlashes(pg_result($resaco,0,'am06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3742,20794,'','".AddSlashes(pg_result($resaco,0,'am06_atividadeimpacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3742,20795,'','".AddSlashes(pg_result($resaco,0,'am06_empreendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3742,20796,'','".AddSlashes(pg_result($resaco,0,'am06_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3742,20804,'','".AddSlashes(pg_result($resaco,0,'am06_atividadeimpactoporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($am06_sequencial=null) {
      $this->atualizacampos();
     $sql = " update empreendimentoatividadeimpacto set ";
     $virgula = "";
     if(trim($this->am06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am06_sequencial"])){
       $sql  .= $virgula." am06_sequencial = $this->am06_sequencial ";
       $virgula = ",";
       if(trim($this->am06_sequencial) == null ){
         $this->erro_sql = " Campo Código Empreendimento Atividade não informado.";
         $this->erro_campo = "am06_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am06_atividadeimpacto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am06_atividadeimpacto"])){
       $sql  .= $virgula." am06_atividadeimpacto = $this->am06_atividadeimpacto ";
       $virgula = ",";
       if(trim($this->am06_atividadeimpacto) == null ){
         $this->erro_sql = " Campo Código da Atividade não informado.";
         $this->erro_campo = "am06_atividadeimpacto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am06_empreendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am06_empreendimento"])){
       $sql  .= $virgula." am06_empreendimento = $this->am06_empreendimento ";
       $virgula = ",";
       if(trim($this->am06_empreendimento) == null ){
         $this->erro_sql = " Campo Código do Empreendimento não informado.";
         $this->erro_campo = "am06_empreendimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am06_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am06_principal"])){
       $sql  .= $virgula." am06_principal = '$this->am06_principal' ";
       $virgula = ",";
       if(trim($this->am06_principal) == null ){
         $this->erro_sql = " Campo Atividade não informado.";
         $this->erro_campo = "am06_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am06_atividadeimpactoporte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am06_atividadeimpactoporte"])){
       $sql  .= $virgula." am06_atividadeimpactoporte = $this->am06_atividadeimpactoporte ";
       $virgula = ",";
       if(trim($this->am06_atividadeimpactoporte) == null ){
         $this->erro_sql = " Campo Porte não informado.";
         $this->erro_campo = "am06_atividadeimpactoporte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am06_sequencial!=null){
       $sql .= " am06_sequencial = $this->am06_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am06_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20793,'$this->am06_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am06_sequencial"]) || $this->am06_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3742,20793,'".AddSlashes(pg_result($resaco,$conresaco,'am06_sequencial'))."','$this->am06_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am06_atividadeimpacto"]) || $this->am06_atividadeimpacto != "")
             $resac = db_query("insert into db_acount values($acount,3742,20794,'".AddSlashes(pg_result($resaco,$conresaco,'am06_atividadeimpacto'))."','$this->am06_atividadeimpacto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am06_empreendimento"]) || $this->am06_empreendimento != "")
             $resac = db_query("insert into db_acount values($acount,3742,20795,'".AddSlashes(pg_result($resaco,$conresaco,'am06_empreendimento'))."','$this->am06_empreendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am06_principal"]) || $this->am06_principal != "")
             $resac = db_query("insert into db_acount values($acount,3742,20796,'".AddSlashes(pg_result($resaco,$conresaco,'am06_principal'))."','$this->am06_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am06_atividadeimpactoporte"]) || $this->am06_atividadeimpactoporte != "")
             $resac = db_query("insert into db_acount values($acount,3742,20804,'".AddSlashes(pg_result($resaco,$conresaco,'am06_atividadeimpactoporte'))."','$this->am06_atividadeimpactoporte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro Empreendimento e Ativdade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro Empreendimento e Ativdade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($am06_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am06_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20793,'$am06_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3742,20793,'','".AddSlashes(pg_result($resaco,$iresaco,'am06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3742,20794,'','".AddSlashes(pg_result($resaco,$iresaco,'am06_atividadeimpacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3742,20795,'','".AddSlashes(pg_result($resaco,$iresaco,'am06_empreendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3742,20796,'','".AddSlashes(pg_result($resaco,$iresaco,'am06_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3742,20804,'','".AddSlashes(pg_result($resaco,$iresaco,'am06_atividadeimpactoporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empreendimentoatividadeimpacto
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am06_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am06_sequencial = $am06_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro Empreendimento e Ativdade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro Empreendimento e Ativdade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am06_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empreendimentoatividadeimpacto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($am06_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from empreendimentoatividadeimpacto ";
     $sql .= "      inner join atividadeimpacto  on  atividadeimpacto.am03_sequencial = empreendimentoatividadeimpacto.am06_atividadeimpacto";
     $sql .= "      inner join atividadeimpactoporte  on  atividadeimpactoporte.am04_sequencial = empreendimentoatividadeimpacto.am06_atividadeimpactoporte";
     $sql .= "      inner join empreendimento  on  empreendimento.am05_sequencial = empreendimentoatividadeimpacto.am06_empreendimento";
     $sql .= "      inner join criterioatividadeimpacto  on  criterioatividadeimpacto.am01_sequencial = atividadeimpacto.am03_criterioatividadeimpacto";
     $sql .= "      inner join porteatividadeimpacto  on  porteatividadeimpacto.am02_sequencial = atividadeimpactoporte.am04_porteatividadeimpacto";
     $sql .= "      inner join bairro  on  bairro.j13_codi = empreendimento.am05_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = empreendimento.am05_ruas";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empreendimento.am05_cgm";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am06_sequencial)) {
         $sql2 .= " where empreendimentoatividadeimpacto.am06_sequencial = $am06_sequencial ";
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
   public function sql_query_file ($am06_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from empreendimentoatividadeimpacto ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am06_sequencial)){
         $sql2 .= " where empreendimentoatividadeimpacto.am06_sequencial = $am06_sequencial ";
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

}
