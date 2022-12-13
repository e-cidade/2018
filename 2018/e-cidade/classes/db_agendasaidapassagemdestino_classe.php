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
//MODULO: tfd
//CLASSE DA ENTIDADE agendasaidapassagemdestino
class cl_agendasaidapassagemdestino { 
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
   var $tf38_sequencial = 0; 
   var $tf38_agendasaida = 0; 
   var $tf38_valorunitario = 0; 
   var $tf38_cgs = 0; 
   var $tf38_fica = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf38_sequencial = int4 = Código 
                 tf38_agendasaida = int4 = Agenda de Saída 
                 tf38_valorunitario = float8 = Valor Unitário 
                 tf38_cgs = int4 = CGS 
                 tf38_fica = bool = Fica 
                 ";
   //funcao construtor da classe 
   function cl_agendasaidapassagemdestino() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agendasaidapassagemdestino"); 
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
       $this->tf38_sequencial = ($this->tf38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tf38_sequencial"]:$this->tf38_sequencial);
       $this->tf38_agendasaida = ($this->tf38_agendasaida == ""?@$GLOBALS["HTTP_POST_VARS"]["tf38_agendasaida"]:$this->tf38_agendasaida);
       $this->tf38_valorunitario = ($this->tf38_valorunitario == ""?@$GLOBALS["HTTP_POST_VARS"]["tf38_valorunitario"]:$this->tf38_valorunitario);
       $this->tf38_cgs = ($this->tf38_cgs == ""?@$GLOBALS["HTTP_POST_VARS"]["tf38_cgs"]:$this->tf38_cgs);
       $this->tf38_fica = ($this->tf38_fica == "f"?@$GLOBALS["HTTP_POST_VARS"]["tf38_fica"]:$this->tf38_fica);
     }else{
       $this->tf38_sequencial = ($this->tf38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tf38_sequencial"]:$this->tf38_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($tf38_sequencial){ 
      $this->atualizacampos();
     if($this->tf38_agendasaida == null ){ 
       $this->erro_sql = " Campo Agenda de Saída não informado.";
       $this->erro_campo = "tf38_agendasaida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf38_valorunitario == null ){ 
       $this->erro_sql = " Campo Valor Unitário não informado.";
       $this->erro_campo = "tf38_valorunitario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf38_cgs == null ){ 
       $this->erro_sql = " Campo CGS não informado.";
       $this->erro_campo = "tf38_cgs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf38_fica == null ){ 
       $this->erro_sql = " Campo Fica não informado.";
       $this->erro_campo = "tf38_fica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf38_sequencial == "" || $tf38_sequencial == null ){
       $result = db_query("select nextval('agendasaidapassagemdestino_tf38_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agendasaidapassagemdestino_tf38_sequencial_seq do campo: tf38_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf38_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from agendasaidapassagemdestino_tf38_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf38_sequencial)){
         $this->erro_sql = " Campo tf38_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf38_sequencial = $tf38_sequencial; 
       }
     }
     if(($this->tf38_sequencial == null) || ($this->tf38_sequencial == "") ){ 
       $this->erro_sql = " Campo tf38_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agendasaidapassagemdestino(
                                       tf38_sequencial 
                                      ,tf38_agendasaida 
                                      ,tf38_valorunitario 
                                      ,tf38_cgs 
                                      ,tf38_fica 
                       )
                values (
                                $this->tf38_sequencial 
                               ,$this->tf38_agendasaida 
                               ,$this->tf38_valorunitario 
                               ,$this->tf38_cgs 
                               ,'$this->tf38_fica' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Passagem de destino da agenda de saída ($this->tf38_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Passagem de destino da agenda de saída já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Passagem de destino da agenda de saída ($this->tf38_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf38_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf38_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21855,'$this->tf38_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3935,21855,'','".AddSlashes(pg_result($resaco,0,'tf38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3935,21856,'','".AddSlashes(pg_result($resaco,0,'tf38_agendasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3935,21857,'','".AddSlashes(pg_result($resaco,0,'tf38_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3935,21866,'','".AddSlashes(pg_result($resaco,0,'tf38_cgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3935,21867,'','".AddSlashes(pg_result($resaco,0,'tf38_fica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($tf38_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update agendasaidapassagemdestino set ";
     $virgula = "";
     if(trim($this->tf38_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf38_sequencial"])){ 
       $sql  .= $virgula." tf38_sequencial = $this->tf38_sequencial ";
       $virgula = ",";
       if(trim($this->tf38_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "tf38_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf38_agendasaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf38_agendasaida"])){ 
       $sql  .= $virgula." tf38_agendasaida = $this->tf38_agendasaida ";
       $virgula = ",";
       if(trim($this->tf38_agendasaida) == null ){ 
         $this->erro_sql = " Campo Agenda de Saída não informado.";
         $this->erro_campo = "tf38_agendasaida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf38_valorunitario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf38_valorunitario"])){ 
       $sql  .= $virgula." tf38_valorunitario = $this->tf38_valorunitario ";
       $virgula = ",";
       if(trim($this->tf38_valorunitario) == null ){ 
         $this->erro_sql = " Campo Valor Unitário não informado.";
         $this->erro_campo = "tf38_valorunitario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf38_cgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf38_cgs"])){ 
       $sql  .= $virgula." tf38_cgs = $this->tf38_cgs ";
       $virgula = ",";
       if(trim($this->tf38_cgs) == null ){ 
         $this->erro_sql = " Campo CGS não informado.";
         $this->erro_campo = "tf38_cgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf38_fica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf38_fica"])){ 
       $sql  .= $virgula." tf38_fica = '$this->tf38_fica' ";
       $virgula = ",";
       if(trim($this->tf38_fica) == null ){ 
         $this->erro_sql = " Campo Fica não informado.";
         $this->erro_campo = "tf38_fica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf38_sequencial!=null){
       $sql .= " tf38_sequencial = $this->tf38_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf38_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21855,'$this->tf38_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf38_sequencial"]) || $this->tf38_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3935,21855,'".AddSlashes(pg_result($resaco,$conresaco,'tf38_sequencial'))."','$this->tf38_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf38_agendasaida"]) || $this->tf38_agendasaida != "")
             $resac = db_query("insert into db_acount values($acount,3935,21856,'".AddSlashes(pg_result($resaco,$conresaco,'tf38_agendasaida'))."','$this->tf38_agendasaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf38_valorunitario"]) || $this->tf38_valorunitario != "")
             $resac = db_query("insert into db_acount values($acount,3935,21857,'".AddSlashes(pg_result($resaco,$conresaco,'tf38_valorunitario'))."','$this->tf38_valorunitario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf38_cgs"]) || $this->tf38_cgs != "")
             $resac = db_query("insert into db_acount values($acount,3935,21866,'".AddSlashes(pg_result($resaco,$conresaco,'tf38_cgs'))."','$this->tf38_cgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["tf38_fica"]) || $this->tf38_fica != "")
             $resac = db_query("insert into db_acount values($acount,3935,21867,'".AddSlashes(pg_result($resaco,$conresaco,'tf38_fica'))."','$this->tf38_fica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Passagem de destino da agenda de saída não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Passagem de destino da agenda de saída não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($tf38_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($tf38_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21855,'$tf38_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3935,21855,'','".AddSlashes(pg_result($resaco,$iresaco,'tf38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3935,21856,'','".AddSlashes(pg_result($resaco,$iresaco,'tf38_agendasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3935,21857,'','".AddSlashes(pg_result($resaco,$iresaco,'tf38_valorunitario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3935,21866,'','".AddSlashes(pg_result($resaco,$iresaco,'tf38_cgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3935,21867,'','".AddSlashes(pg_result($resaco,$iresaco,'tf38_fica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from agendasaidapassagemdestino
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($tf38_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " tf38_sequencial = $tf38_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Passagem de destino da agenda de saída não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Passagem de destino da agenda de saída não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf38_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:agendasaidapassagemdestino";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($tf38_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from agendasaidapassagemdestino ";
     $sql .= "      inner join tfd_agendasaida  on  tfd_agendasaida.tf17_i_codigo = agendasaidapassagemdestino.tf38_agendasaida";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_agendasaida.tf17_i_login";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_agendasaida.tf17_i_pedidotfd";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($tf38_sequencial)) {
         $sql2 .= " where agendasaidapassagemdestino.tf38_sequencial = $tf38_sequencial "; 
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
   public function sql_query_file ($tf38_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from agendasaidapassagemdestino ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($tf38_sequencial)){
         $sql2 .= " where agendasaidapassagemdestino.tf38_sequencial = $tf38_sequencial "; 
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
