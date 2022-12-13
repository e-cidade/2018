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

//MODULO: licitacao
//CLASSE DA ENTIDADE pcorcamfornelichabilitacao
class cl_pcorcamfornelichabilitacao { 
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
   var $l17_sequencial = 0; 
   var $l17_pcorcamfornelic = 0; 
   var $l17_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l17_sequencial = int4 = Código 
                 l17_pcorcamfornelic = int4 = Fornecedor 
                 l17_situacao = int4 = Situação 
                 ";
   //funcao construtor da classe 
   function cl_pcorcamfornelichabilitacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamfornelichabilitacao"); 
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
       $this->l17_sequencial = ($this->l17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l17_sequencial"]:$this->l17_sequencial);
       $this->l17_pcorcamfornelic = ($this->l17_pcorcamfornelic == ""?@$GLOBALS["HTTP_POST_VARS"]["l17_pcorcamfornelic"]:$this->l17_pcorcamfornelic);
       $this->l17_situacao = ($this->l17_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["l17_situacao"]:$this->l17_situacao);
     }else{
       $this->l17_sequencial = ($this->l17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l17_sequencial"]:$this->l17_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($l17_sequencial){ 
      $this->atualizacampos();
     if($this->l17_pcorcamfornelic == null ){ 
       $this->erro_sql = " Campo Fornecedor não informado.";
       $this->erro_campo = "l17_pcorcamfornelic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l17_situacao == null ){ 
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "l17_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l17_sequencial == "" || $l17_sequencial == null ){
       $result = db_query("select nextval('pcorcamfornelichabilitacao_l17_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcorcamfornelichabilitacao_l17_sequencial_seq do campo: l17_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l17_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcorcamfornelichabilitacao_l17_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l17_sequencial)){
         $this->erro_sql = " Campo l17_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l17_sequencial = $l17_sequencial; 
       }
     }
     if(($this->l17_sequencial == null) || ($this->l17_sequencial == "") ){ 
       $this->erro_sql = " Campo l17_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamfornelichabilitacao(
                                       l17_sequencial 
                                      ,l17_pcorcamfornelic 
                                      ,l17_situacao 
                       )
                values (
                                $this->l17_sequencial 
                               ,$this->l17_pcorcamfornelic 
                               ,$this->l17_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Habilitação dos Fornecedores da Licitação ($this->l17_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Habilitação dos Fornecedores da Licitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Habilitação dos Fornecedores da Licitação ($this->l17_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l17_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l17_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21721,'$this->l17_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3904,21721,'','".AddSlashes(pg_result($resaco,0,'l17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3904,21722,'','".AddSlashes(pg_result($resaco,0,'l17_pcorcamfornelic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3904,21723,'','".AddSlashes(pg_result($resaco,0,'l17_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($l17_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pcorcamfornelichabilitacao set ";
     $virgula = "";
     if(trim($this->l17_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l17_sequencial"])){ 
       $sql  .= $virgula." l17_sequencial = $this->l17_sequencial ";
       $virgula = ",";
       if(trim($this->l17_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "l17_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l17_pcorcamfornelic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l17_pcorcamfornelic"])){ 
       $sql  .= $virgula." l17_pcorcamfornelic = $this->l17_pcorcamfornelic ";
       $virgula = ",";
       if(trim($this->l17_pcorcamfornelic) == null ){ 
         $this->erro_sql = " Campo Fornecedor não informado.";
         $this->erro_campo = "l17_pcorcamfornelic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l17_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l17_situacao"])){ 
       $sql  .= $virgula." l17_situacao = $this->l17_situacao ";
       $virgula = ",";
       if(trim($this->l17_situacao) == null ){ 
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "l17_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l17_sequencial!=null){
       $sql .= " l17_sequencial = $this->l17_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l17_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21721,'$this->l17_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l17_sequencial"]) || $this->l17_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3904,21721,'".AddSlashes(pg_result($resaco,$conresaco,'l17_sequencial'))."','$this->l17_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l17_pcorcamfornelic"]) || $this->l17_pcorcamfornelic != "")
             $resac = db_query("insert into db_acount values($acount,3904,21722,'".AddSlashes(pg_result($resaco,$conresaco,'l17_pcorcamfornelic'))."','$this->l17_pcorcamfornelic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l17_situacao"]) || $this->l17_situacao != "")
             $resac = db_query("insert into db_acount values($acount,3904,21723,'".AddSlashes(pg_result($resaco,$conresaco,'l17_situacao'))."','$this->l17_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Habilitação dos Fornecedores da Licitação não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Habilitação dos Fornecedores da Licitação não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($l17_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($l17_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21721,'$l17_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3904,21721,'','".AddSlashes(pg_result($resaco,$iresaco,'l17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3904,21722,'','".AddSlashes(pg_result($resaco,$iresaco,'l17_pcorcamfornelic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3904,21723,'','".AddSlashes(pg_result($resaco,$iresaco,'l17_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pcorcamfornelichabilitacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($l17_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " l17_sequencial = $l17_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Habilitação dos Fornecedores da Licitação não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Habilitação dos Fornecedores da Licitação não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l17_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamfornelichabilitacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($l17_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from pcorcamfornelichabilitacao ";
     $sql .= "      inner join pcorcamfornelic  on  pcorcamfornelic.pc31_orcamforne = pcorcamfornelichabilitacao.l17_pcorcamfornelic";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = pcorcamfornelic.pc31_orcamforne";
     $sql .= "      inner join cgm           on pcorcamforne.pc21_numcgm = cgm.z01_numcgm ";
     $sql .= "      inner join liclicitatipoempresa  on  liclicitatipoempresa.l32_sequencial = pcorcamfornelic.pc31_liclicitatipoempresa";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l17_sequencial)) {
         $sql2 .= " where pcorcamfornelichabilitacao.l17_sequencial = $l17_sequencial "; 
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
   public function sql_query_file ($l17_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pcorcamfornelichabilitacao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l17_sequencial)){
         $sql2 .= " where pcorcamfornelichabilitacao.l17_sequencial = $l17_sequencial "; 
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
