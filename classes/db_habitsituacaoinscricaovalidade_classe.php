<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: Habitacao
//CLASSE DA ENTIDADE habitsituacaoinscricaovalidade
class cl_habitsituacaoinscricaovalidade { 
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
   var $ht14_sequencial = 0; 
   var $ht14_habitsituacaoinscricao = 0; 
   var $ht14_datainicial_dia = null; 
   var $ht14_datainicial_mes = null; 
   var $ht14_datainicial_ano = null; 
   var $ht14_datainicial = null; 
   var $ht14_datafinal_dia = null; 
   var $ht14_datafinal_mes = null; 
   var $ht14_datafinal_ano = null; 
   var $ht14_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht14_sequencial = int4 = Sequencial 
                 ht14_habitsituacaoinscricao = int4 = Situação da Inscrição 
                 ht14_datainicial = date = Data Inicial 
                 ht14_datafinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_habitsituacaoinscricaovalidade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitsituacaoinscricaovalidade"); 
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
       $this->ht14_sequencial = ($this->ht14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht14_sequencial"]:$this->ht14_sequencial);
       $this->ht14_habitsituacaoinscricao = ($this->ht14_habitsituacaoinscricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht14_habitsituacaoinscricao"]:$this->ht14_habitsituacaoinscricao);
       if($this->ht14_datainicial == ""){
         $this->ht14_datainicial_dia = ($this->ht14_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht14_datainicial_dia"]:$this->ht14_datainicial_dia);
         $this->ht14_datainicial_mes = ($this->ht14_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht14_datainicial_mes"]:$this->ht14_datainicial_mes);
         $this->ht14_datainicial_ano = ($this->ht14_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht14_datainicial_ano"]:$this->ht14_datainicial_ano);
         if($this->ht14_datainicial_dia != ""){
            $this->ht14_datainicial = $this->ht14_datainicial_ano."-".$this->ht14_datainicial_mes."-".$this->ht14_datainicial_dia;
         }
       }
       if($this->ht14_datafinal == ""){
         $this->ht14_datafinal_dia = ($this->ht14_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht14_datafinal_dia"]:$this->ht14_datafinal_dia);
         $this->ht14_datafinal_mes = ($this->ht14_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht14_datafinal_mes"]:$this->ht14_datafinal_mes);
         $this->ht14_datafinal_ano = ($this->ht14_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht14_datafinal_ano"]:$this->ht14_datafinal_ano);
         if($this->ht14_datafinal_dia != ""){
            $this->ht14_datafinal = $this->ht14_datafinal_ano."-".$this->ht14_datafinal_mes."-".$this->ht14_datafinal_dia;
         }
       }
     }else{
       $this->ht14_sequencial = ($this->ht14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht14_sequencial"]:$this->ht14_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht14_sequencial){ 
      $this->atualizacampos();
     if($this->ht14_habitsituacaoinscricao == null ){ 
       $this->erro_sql = " Campo Situação da Inscrição nao Informado.";
       $this->erro_campo = "ht14_habitsituacaoinscricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht14_datainicial == null ){ 
       $this->ht14_datainicial = "null";
     }
     if($this->ht14_datafinal == null ){ 
       $this->ht14_datafinal = "null";
     }
     if($ht14_sequencial == "" || $ht14_sequencial == null ){
       $result = db_query("select nextval('habitsituacaoinscricaovalidade_ht14_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitsituacaoinscricaovalidade_ht14_sequencial_seq do campo: ht14_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht14_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitsituacaoinscricaovalidade_ht14_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht14_sequencial)){
         $this->erro_sql = " Campo ht14_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht14_sequencial = $ht14_sequencial; 
       }
     }
     if(($this->ht14_sequencial == null) || ($this->ht14_sequencial == "") ){ 
       $this->erro_sql = " Campo ht14_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitsituacaoinscricaovalidade(
                                       ht14_sequencial 
                                      ,ht14_habitsituacaoinscricao 
                                      ,ht14_datainicial 
                                      ,ht14_datafinal 
                       )
                values (
                                $this->ht14_sequencial 
                               ,$this->ht14_habitsituacaoinscricao 
                               ,".($this->ht14_datainicial == "null" || $this->ht14_datainicial == ""?"null":"'".$this->ht14_datainicial."'")." 
                               ,".($this->ht14_datafinal == "null" || $this->ht14_datafinal == ""?"null":"'".$this->ht14_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Validade da Situação da Inscrição da Habitação ($this->ht14_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Validade da Situação da Inscrição da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Validade da Situação da Inscrição da Habitação ($this->ht14_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht14_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht14_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16999,'$this->ht14_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3002,16999,'','".AddSlashes(pg_result($resaco,0,'ht14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3002,17000,'','".AddSlashes(pg_result($resaco,0,'ht14_habitsituacaoinscricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3002,17001,'','".AddSlashes(pg_result($resaco,0,'ht14_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3002,17002,'','".AddSlashes(pg_result($resaco,0,'ht14_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht14_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitsituacaoinscricaovalidade set ";
     $virgula = "";
     if(trim($this->ht14_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht14_sequencial"])){ 
       $sql  .= $virgula." ht14_sequencial = $this->ht14_sequencial ";
       $virgula = ",";
       if(trim($this->ht14_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht14_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht14_habitsituacaoinscricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht14_habitsituacaoinscricao"])){ 
       $sql  .= $virgula." ht14_habitsituacaoinscricao = $this->ht14_habitsituacaoinscricao ";
       $virgula = ",";
       if(trim($this->ht14_habitsituacaoinscricao) == null ){ 
         $this->erro_sql = " Campo Situação da Inscrição nao Informado.";
         $this->erro_campo = "ht14_habitsituacaoinscricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht14_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht14_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht14_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ht14_datainicial = '$this->ht14_datainicial' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht14_datainicial_dia"])){ 
         $sql  .= $virgula." ht14_datainicial = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ht14_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht14_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht14_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ht14_datafinal = '$this->ht14_datafinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht14_datafinal_dia"])){ 
         $sql  .= $virgula." ht14_datafinal = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($ht14_sequencial!=null){
       $sql .= " ht14_sequencial = $this->ht14_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht14_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16999,'$this->ht14_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht14_sequencial"]) || $this->ht14_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3002,16999,'".AddSlashes(pg_result($resaco,$conresaco,'ht14_sequencial'))."','$this->ht14_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht14_habitsituacaoinscricao"]) || $this->ht14_habitsituacaoinscricao != "")
           $resac = db_query("insert into db_acount values($acount,3002,17000,'".AddSlashes(pg_result($resaco,$conresaco,'ht14_habitsituacaoinscricao'))."','$this->ht14_habitsituacaoinscricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht14_datainicial"]) || $this->ht14_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,3002,17001,'".AddSlashes(pg_result($resaco,$conresaco,'ht14_datainicial'))."','$this->ht14_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht14_datafinal"]) || $this->ht14_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,3002,17002,'".AddSlashes(pg_result($resaco,$conresaco,'ht14_datafinal'))."','$this->ht14_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Validade da Situação da Inscrição da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Validade da Situação da Inscrição da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht14_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht14_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16999,'$ht14_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3002,16999,'','".AddSlashes(pg_result($resaco,$iresaco,'ht14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3002,17000,'','".AddSlashes(pg_result($resaco,$iresaco,'ht14_habitsituacaoinscricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3002,17001,'','".AddSlashes(pg_result($resaco,$iresaco,'ht14_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3002,17002,'','".AddSlashes(pg_result($resaco,$iresaco,'ht14_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitsituacaoinscricaovalidade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht14_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht14_sequencial = $ht14_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Validade da Situação da Inscrição da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht14_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Validade da Situação da Inscrição da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht14_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht14_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitsituacaoinscricaovalidade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht14_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitsituacaoinscricaovalidade ";
     $sql .= "      inner join habitsituacaoinscricao  on  habitsituacaoinscricao.ht13_sequencial = habitsituacaoinscricaovalidade.ht14_habitsituacaoinscricao";
     $sql2 = "";
     if($dbwhere==""){
       if($ht14_sequencial!=null ){
         $sql2 .= " where habitsituacaoinscricaovalidade.ht14_sequencial = $ht14_sequencial "; 
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
   function sql_query_file ( $ht14_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitsituacaoinscricaovalidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht14_sequencial!=null ){
         $sql2 .= " where habitsituacaoinscricaovalidade.ht14_sequencial = $ht14_sequencial "; 
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
?>