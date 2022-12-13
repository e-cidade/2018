<?php

/**
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
//CLASSE DA ENTIDADE econsigmovimentoservidor
class cl_econsigmovimentoservidor { 
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
   var $rh134_sequencial = 0; 
   var $rh134_econsigmovimento = 0; 
   var $rh134_regist = 0; 
   var $rh134_econsigmotivo = 0; 
   var $rh134_nome = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh134_sequencial = int4 = Código Sequencial 
                 rh134_econsigmovimento = int4 = E-CONSIG Movimento 
                 rh134_regist = int4 = Servidor 
                 rh134_econsigmotivo = int4 = Motivo 
                 rh134_nome = varchar(50) = Nome do Servidor 
                 ";
   //funcao construtor da classe 
   function cl_econsigmovimentoservidor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("econsigmovimentoservidor"); 
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
       $this->rh134_sequencial = ($this->rh134_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh134_sequencial"]:$this->rh134_sequencial);
       $this->rh134_econsigmovimento = ($this->rh134_econsigmovimento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh134_econsigmovimento"]:$this->rh134_econsigmovimento);
       $this->rh134_regist = ($this->rh134_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh134_regist"]:$this->rh134_regist);
       $this->rh134_econsigmotivo = ($this->rh134_econsigmotivo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh134_econsigmotivo"]:$this->rh134_econsigmotivo);
       $this->rh134_nome = ($this->rh134_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["rh134_nome"]:$this->rh134_nome);
     }else{
       $this->rh134_sequencial = ($this->rh134_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh134_sequencial"]:$this->rh134_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh134_sequencial){ 
      $this->atualizacampos();
     if($this->rh134_econsigmovimento == null ){ 
       $this->erro_sql = " Campo E-CONSIG Movimento não informado.";
       $this->erro_campo = "rh134_econsigmovimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh134_regist == null ){ 
       $this->erro_sql = " Campo Servidor não informado.";
       $this->erro_campo = "rh134_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh134_nome == null ){ 
       $this->erro_sql = " Campo Nome do Servidor não informado.";
       $this->erro_campo = "rh134_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh134_sequencial == "" || $rh134_sequencial == null ){
       $result = db_query("select nextval('econsigmovimentoservidor_rh134_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: econsigmovimentoservidor_rh134_sequencial_seq do campo: rh134_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh134_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from econsigmovimentoservidor_rh134_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh134_sequencial)){
         $this->erro_sql = " Campo rh134_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh134_sequencial = $rh134_sequencial; 
       }
     }
     if(($this->rh134_sequencial == null) || ($this->rh134_sequencial == "") ){ 
       $this->erro_sql = " Campo rh134_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into econsigmovimentoservidor(
                                       rh134_sequencial 
                                      ,rh134_econsigmovimento 
                                      ,rh134_regist 
                                      ,rh134_econsigmotivo 
                                      ,rh134_nome 
                       )
                values (
                                $this->rh134_sequencial 
                               ,$this->rh134_econsigmovimento 
                               ,$this->rh134_regist 
                               ,$this->rh134_econsigmotivo 
                               ,'$this->rh134_nome' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "E-CONSIG Movimento Servidor ($this->rh134_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "E-CONSIG Movimento Servidor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "E-CONSIG Movimento Servidor ($this->rh134_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh134_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh134_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20447,'$this->rh134_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3676,20447,'','".AddSlashes(pg_result($resaco,0,'rh134_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3676,20448,'','".AddSlashes(pg_result($resaco,0,'rh134_econsigmovimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3676,20449,'','".AddSlashes(pg_result($resaco,0,'rh134_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3676,20871,'','".AddSlashes(pg_result($resaco,0,'rh134_econsigmotivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3676,20880,'','".AddSlashes(pg_result($resaco,0,'rh134_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh134_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update econsigmovimentoservidor set ";
     $virgula = "";
     if(trim($this->rh134_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh134_sequencial"])){ 
       $sql  .= $virgula." rh134_sequencial = $this->rh134_sequencial ";
       $virgula = ",";
       if(trim($this->rh134_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "rh134_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh134_econsigmovimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh134_econsigmovimento"])){ 
       $sql  .= $virgula." rh134_econsigmovimento = $this->rh134_econsigmovimento ";
       $virgula = ",";
       if(trim($this->rh134_econsigmovimento) == null ){ 
         $this->erro_sql = " Campo E-CONSIG Movimento não informado.";
         $this->erro_campo = "rh134_econsigmovimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh134_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh134_regist"])){ 
       $sql  .= $virgula." rh134_regist = $this->rh134_regist ";
       $virgula = ",";
       if(trim($this->rh134_regist) == null ){ 
         $this->erro_sql = " Campo Servidor não informado.";
         $this->erro_campo = "rh134_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh134_econsigmotivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh134_econsigmotivo"])){ 
        if(trim($this->rh134_econsigmotivo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh134_econsigmotivo"])){ 
           $this->rh134_econsigmotivo = "0" ; 
        } 
       $sql  .= $virgula." rh134_econsigmotivo = $this->rh134_econsigmotivo ";
       $virgula = ",";
     }
     if(trim($this->rh134_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh134_nome"])){ 
       $sql  .= $virgula." rh134_nome = '$this->rh134_nome' ";
       $virgula = ",";
       if(trim($this->rh134_nome) == null ){ 
         $this->erro_sql = " Campo Nome do Servidor não informado.";
         $this->erro_campo = "rh134_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh134_sequencial!=null){
       $sql .= " rh134_sequencial = $this->rh134_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh134_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20447,'$this->rh134_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh134_sequencial"]) || $this->rh134_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3676,20447,'".AddSlashes(pg_result($resaco,$conresaco,'rh134_sequencial'))."','$this->rh134_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh134_econsigmovimento"]) || $this->rh134_econsigmovimento != "")
             $resac = db_query("insert into db_acount values($acount,3676,20448,'".AddSlashes(pg_result($resaco,$conresaco,'rh134_econsigmovimento'))."','$this->rh134_econsigmovimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh134_regist"]) || $this->rh134_regist != "")
             $resac = db_query("insert into db_acount values($acount,3676,20449,'".AddSlashes(pg_result($resaco,$conresaco,'rh134_regist'))."','$this->rh134_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh134_econsigmotivo"]) || $this->rh134_econsigmotivo != "")
             $resac = db_query("insert into db_acount values($acount,3676,20871,'".AddSlashes(pg_result($resaco,$conresaco,'rh134_econsigmotivo'))."','$this->rh134_econsigmotivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh134_nome"]) || $this->rh134_nome != "")
             $resac = db_query("insert into db_acount values($acount,3676,20880,'".AddSlashes(pg_result($resaco,$conresaco,'rh134_nome'))."','$this->rh134_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "E-CONSIG Movimento Servidor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh134_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "E-CONSIG Movimento Servidor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh134_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh134_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20447,'$rh134_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3676,20447,'','".AddSlashes(pg_result($resaco,$iresaco,'rh134_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3676,20448,'','".AddSlashes(pg_result($resaco,$iresaco,'rh134_econsigmovimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3676,20449,'','".AddSlashes(pg_result($resaco,$iresaco,'rh134_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3676,20871,'','".AddSlashes(pg_result($resaco,$iresaco,'rh134_econsigmotivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3676,20880,'','".AddSlashes(pg_result($resaco,$iresaco,'rh134_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from econsigmovimentoservidor
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh134_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh134_sequencial = $rh134_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "E-CONSIG Movimento Servidor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh134_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "E-CONSIG Movimento Servidor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh134_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:econsigmovimentoservidor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh134_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from econsigmovimentoservidor ";
     $sql .= "      inner join econsigmovimento  on  econsigmovimento.rh133_sequencial = econsigmovimentoservidor.rh134_econsigmovimento";
     $sql .= "      left  join econsigmotivo  on  econsigmotivo.rh147_sequencial = econsigmovimentoservidor.rh134_econsigmotivo";
     $sql .= "      inner join db_config  on  db_config.codigo = econsigmovimento.rh133_instit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh134_sequencial)) {
         $sql2 .= " where econsigmovimentoservidor.rh134_sequencial = $rh134_sequencial "; 
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
   public function sql_query_file ($rh134_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from econsigmovimentoservidor ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh134_sequencial)){
         $sql2 .= " where econsigmovimentoservidor.rh134_sequencial = $rh134_sequencial "; 
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

  public function sql_todos_movimentos ($iCodigoArquivo) {

    $sSql  =" SELECT rh134_sequencial,                                                                                                                                     ";
    $sSql .="        rh134_regist,                                                                                                                                         ";
    $sSql .="        rh135_rubrica,                                                                                                                                        ";
    $sSql .="        rh135_valor,                                                                                                                                          ";
    $sSql .="        rh134_econsigmotivo,                                                                                                                                  ";
    $sSql .="        rh134_nome                                                                                                                                            ";
    $sSql .="   FROM econsigmovimentoservidor                                                                                                                              ";
    $sSql .="  INNER JOIN econsigmovimento                 ON econsigmovimento.rh133_sequencial         = econsigmovimentoservidor.rh134_econsigmovimento                  ";
    $sSql .="   LEFT JOIN econsigmotivo                    ON econsigmotivo.rh147_sequencial            = econsigmovimentoservidor.rh134_econsigmotivo                     ";
    $sSql .="   LEFT JOIN econsigmovimentoservidorrubrica  ON econsigmovimentoservidor.rh134_sequencial = econsigmovimentoservidorrubrica.rh135_econsigmovimentoservidor   ";
    $sSql .="  WHERE rh133_sequencial = {$iCodigoArquivo}                                                                                                                  ";
    
    return $sSql;
  }

}
