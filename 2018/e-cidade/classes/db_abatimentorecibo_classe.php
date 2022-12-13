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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE abatimentorecibo
class cl_abatimentorecibo {
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
   var $k127_sequencial = 0;
   var $k127_abatimento = 0;
   var $k127_numprerecibo = 0;
   var $k127_numpreoriginal = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k127_sequencial = int4 = Sequencial
                 k127_abatimento = int4 = Abatimento
                 k127_numprerecibo = int4 = Numpre do Recibo
                 k127_numpreoriginal = int4 = Numpre Original
                 ";
   //funcao construtor da classe
   function cl_abatimentorecibo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("abatimentorecibo");
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
       $this->k127_sequencial = ($this->k127_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k127_sequencial"]:$this->k127_sequencial);
       $this->k127_abatimento = ($this->k127_abatimento == ""?@$GLOBALS["HTTP_POST_VARS"]["k127_abatimento"]:$this->k127_abatimento);
       $this->k127_numprerecibo = ($this->k127_numprerecibo == ""?@$GLOBALS["HTTP_POST_VARS"]["k127_numprerecibo"]:$this->k127_numprerecibo);
       $this->k127_numpreoriginal = ($this->k127_numpreoriginal == ""?@$GLOBALS["HTTP_POST_VARS"]["k127_numpreoriginal"]:$this->k127_numpreoriginal);
     }else{
       $this->k127_sequencial = ($this->k127_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k127_sequencial"]:$this->k127_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($k127_sequencial){
      $this->atualizacampos();
     if($this->k127_abatimento == null ){
       $this->erro_sql = " Campo Abatimento não informado.";
       $this->erro_campo = "k127_abatimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k127_numprerecibo == null ){
       $this->erro_sql = " Campo Numpre do Recibo não informado.";
       $this->erro_campo = "k127_numprerecibo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k127_numpreoriginal == null ){
       $this->k127_numpreoriginal = "0";
     }
     if($k127_sequencial == "" || $k127_sequencial == null ){
       $result = db_query("select nextval('abatimentorecibo_k127_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: abatimentorecibo_k127_sequencial_seq do campo: k127_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k127_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from abatimentorecibo_k127_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k127_sequencial)){
         $this->erro_sql = " Campo k127_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k127_sequencial = $k127_sequencial;
       }
     }
     if(($this->k127_sequencial == null) || ($this->k127_sequencial == "") ){
       $this->erro_sql = " Campo k127_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into abatimentorecibo(
                                       k127_sequencial
                                      ,k127_abatimento
                                      ,k127_numprerecibo
                                      ,k127_numpreoriginal
                       )
                values (
                                $this->k127_sequencial
                               ,$this->k127_abatimento
                               ,$this->k127_numprerecibo
                               ,$this->k127_numpreoriginal
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Recibo do Abatimento ($this->k127_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Recibo do Abatimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Recibo do Abatimento ($this->k127_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k127_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k127_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18074,'$this->k127_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3194,18074,'','".AddSlashes(pg_result($resaco,0,'k127_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3194,18075,'','".AddSlashes(pg_result($resaco,0,'k127_abatimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3194,18076,'','".AddSlashes(pg_result($resaco,0,'k127_numprerecibo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3194,21261,'','".AddSlashes(pg_result($resaco,0,'k127_numpreoriginal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($k127_sequencial=null) {
      $this->atualizacampos();
     $sql = " update abatimentorecibo set ";
     $virgula = "";
     if(trim($this->k127_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k127_sequencial"])){
       $sql  .= $virgula." k127_sequencial = $this->k127_sequencial ";
       $virgula = ",";
       if(trim($this->k127_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "k127_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k127_abatimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k127_abatimento"])){
       $sql  .= $virgula." k127_abatimento = $this->k127_abatimento ";
       $virgula = ",";
       if(trim($this->k127_abatimento) == null ){
         $this->erro_sql = " Campo Abatimento não informado.";
         $this->erro_campo = "k127_abatimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k127_numprerecibo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k127_numprerecibo"])){
       $sql  .= $virgula." k127_numprerecibo = $this->k127_numprerecibo ";
       $virgula = ",";
       if(trim($this->k127_numprerecibo) == null ){
         $this->erro_sql = " Campo Numpre do Recibo não informado.";
         $this->erro_campo = "k127_numprerecibo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k127_numpreoriginal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k127_numpreoriginal"])){
        if(trim($this->k127_numpreoriginal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k127_numpreoriginal"])){
           $this->k127_numpreoriginal = "0" ;
        }
       $sql  .= $virgula." k127_numpreoriginal = $this->k127_numpreoriginal ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k127_sequencial!=null){
       $sql .= " k127_sequencial = $this->k127_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k127_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,18074,'$this->k127_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k127_sequencial"]) || $this->k127_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3194,18074,'".AddSlashes(pg_result($resaco,$conresaco,'k127_sequencial'))."','$this->k127_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k127_abatimento"]) || $this->k127_abatimento != "")
             $resac = db_query("insert into db_acount values($acount,3194,18075,'".AddSlashes(pg_result($resaco,$conresaco,'k127_abatimento'))."','$this->k127_abatimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k127_numprerecibo"]) || $this->k127_numprerecibo != "")
             $resac = db_query("insert into db_acount values($acount,3194,18076,'".AddSlashes(pg_result($resaco,$conresaco,'k127_numprerecibo'))."','$this->k127_numprerecibo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k127_numpreoriginal"]) || $this->k127_numpreoriginal != "")
             $resac = db_query("insert into db_acount values($acount,3194,21261,'".AddSlashes(pg_result($resaco,$conresaco,'k127_numpreoriginal'))."','$this->k127_numpreoriginal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Recibo do Abatimento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k127_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Recibo do Abatimento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k127_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k127_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($k127_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($k127_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,18074,'$k127_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3194,18074,'','".AddSlashes(pg_result($resaco,$iresaco,'k127_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3194,18075,'','".AddSlashes(pg_result($resaco,$iresaco,'k127_abatimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3194,18076,'','".AddSlashes(pg_result($resaco,$iresaco,'k127_numprerecibo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3194,21261,'','".AddSlashes(pg_result($resaco,$iresaco,'k127_numpreoriginal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from abatimentorecibo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($k127_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k127_sequencial = $k127_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Recibo do Abatimento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k127_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Recibo do Abatimento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k127_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k127_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:abatimentorecibo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
  public function sql_query ($k127_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from abatimentorecibo ";
    $sql .= "      inner join abatimento  on  abatimento.k125_sequencial = abatimentorecibo.k127_abatimento";
    $sql .= "      inner join db_config  on  db_config.codigo = abatimento.k125_instit";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = abatimento.k125_usuario";
    $sql .= "      inner join tipoabatimento  on  tipoabatimento.k126_sequencial = abatimento.k125_tipoabatimento";
    $sql .= "      inner join abatimentosituacao  on  abatimentosituacao.k165_sequencial = abatimento.k125_abatimentosituacao";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($k127_sequencial)) {
        $sql2 .= " where abatimentorecibo.k127_sequencial = $k127_sequencial ";
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

  public function sql_query_dados_recibo( $k127_sequencial = null, $campos = "*", $ordem = null, $dbwhere = ""){

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
    $sql .= " from abatimentorecibo ";
    $sql .= "      inner join recibo   on recibo.k00_numpre   = abatimentorecibo.k127_numprerecibo ";
    $sql .= "      inner join arretipo on arretipo.k00_tipo   = recibo.k00_tipo                    ";
    $sql .= "      inner join tabrec   on tabrec.k02_codigo   = recibo.k00_receit                  ";
    $sql .= "      inner join histcalc on histcalc.k01_codigo = recibo.k00_hist                    ";
    $sql2 = "";
    if($dbwhere==""){
      if($k127_sequencial!=null ){
        $sql2 .= " where abatimentorecibo.k127_sequencial = $k127_sequencial ";
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

   // funcao do sql
  public function sql_query_file ($k127_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

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
    $sql .= " from abatimentorecibo ";
    $sql2 = "";
    if($dbwhere==""){
      if($k127_sequencial!=null ){
        $sql2 .= " where abatimentorecibo.k127_sequencial = $k127_sequencial ";
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
  public function sqlNumpresOrigemCredito($iAbatimento, $sCampos="*", $sWhere = "") {

    $sSqlCredito  = "select {$sCampos}                                                                        ";
    $sSqlCredito .= "  from abatimentorecibo                                                                  ";
    $sSqlCredito .= "       inner join abatimento on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento  ";
    $sSqlCredito .= "       inner join recibopaga on recibopaga.k00_numnov      = abatimentorecibo.k127_numpreoriginal ";
    $sSqlCredito .= " where abatimentorecibo.k127_abatimento = {$iAbatimento}                                       ";

    if (!empty($sWhere)) {
      $sSqlCredito .= " and {$sWhere} ";
    }

    return $sSqlCredito;
  }
}