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

//MODULO: Arrecadacao
//CLASSE DA ENTIDADE declaracaoquitacaoreg
class cl_declaracaoquitacaoreg { 
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
   var $ar31_sequencial = 0; 
   var $ar31_declaracaoquitacao = 0; 
   var $ar31_numpre = 0; 
   var $ar31_numpar = 0; 
   var $ar31_receita = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar31_sequencial = int8 = Código do Registro 
                 ar31_declaracaoquitacao = int8 = Código Declaração 
                 ar31_numpre = int8 = Numpre 
                 ar31_numpar = int8 = Numpar 
                 ar31_receita = int8 = Receita 
                 ";
   //funcao construtor da classe 
   function cl_declaracaoquitacaoreg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("declaracaoquitacaoreg"); 
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
       $this->ar31_sequencial = ($this->ar31_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar31_sequencial"]:$this->ar31_sequencial);
       $this->ar31_declaracaoquitacao = ($this->ar31_declaracaoquitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar31_declaracaoquitacao"]:$this->ar31_declaracaoquitacao);
       $this->ar31_numpre = ($this->ar31_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["ar31_numpre"]:$this->ar31_numpre);
       $this->ar31_numpar = ($this->ar31_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["ar31_numpar"]:$this->ar31_numpar);
       $this->ar31_receita = ($this->ar31_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["ar31_receita"]:$this->ar31_receita);
     }else{
       $this->ar31_sequencial = ($this->ar31_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar31_sequencial"]:$this->ar31_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar31_sequencial){ 
      $this->atualizacampos();
     if($this->ar31_declaracaoquitacao == null ){ 
       $this->erro_sql = " Campo Código Declaração nao Informado.";
       $this->erro_campo = "ar31_declaracaoquitacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar31_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "ar31_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar31_numpar == null ){ 
       $this->erro_sql = " Campo Numpar nao Informado.";
       $this->erro_campo = "ar31_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar31_receita == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "ar31_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar31_sequencial == "" || $ar31_sequencial == null ){
       $result = db_query("select nextval('declaracaoquitacaoreg_ar31_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: declaracaoquitacaoreg_ar31_sequencial_seq do campo: ar31_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar31_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from declaracaoquitacaoreg_ar31_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar31_sequencial)){
         $this->erro_sql = " Campo ar31_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar31_sequencial = $ar31_sequencial; 
       }
     }
     if(($this->ar31_sequencial == null) || ($this->ar31_sequencial == "") ){ 
       $this->erro_sql = " Campo ar31_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into declaracaoquitacaoreg(
                                       ar31_sequencial 
                                      ,ar31_declaracaoquitacao 
                                      ,ar31_numpre 
                                      ,ar31_numpar 
                                      ,ar31_receita 
                       )
                values (
                                $this->ar31_sequencial 
                               ,$this->ar31_declaracaoquitacao 
                               ,$this->ar31_numpre 
                               ,$this->ar31_numpar 
                               ,$this->ar31_receita 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registro de Declaração de Quitação ($this->ar31_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro de Declaração de Quitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registro de Declaração de Quitação ($this->ar31_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar31_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar31_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17159,'$this->ar31_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3032,17159,'','".AddSlashes(pg_result($resaco,0,'ar31_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3032,17160,'','".AddSlashes(pg_result($resaco,0,'ar31_declaracaoquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3032,17161,'','".AddSlashes(pg_result($resaco,0,'ar31_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3032,17162,'','".AddSlashes(pg_result($resaco,0,'ar31_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3032,17163,'','".AddSlashes(pg_result($resaco,0,'ar31_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar31_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update declaracaoquitacaoreg set ";
     $virgula = "";
     if(trim($this->ar31_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar31_sequencial"])){ 
       $sql  .= $virgula." ar31_sequencial = $this->ar31_sequencial ";
       $virgula = ",";
       if(trim($this->ar31_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do Registro nao Informado.";
         $this->erro_campo = "ar31_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar31_declaracaoquitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar31_declaracaoquitacao"])){ 
       $sql  .= $virgula." ar31_declaracaoquitacao = $this->ar31_declaracaoquitacao ";
       $virgula = ",";
       if(trim($this->ar31_declaracaoquitacao) == null ){ 
         $this->erro_sql = " Campo Código Declaração nao Informado.";
         $this->erro_campo = "ar31_declaracaoquitacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar31_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar31_numpre"])){ 
       $sql  .= $virgula." ar31_numpre = $this->ar31_numpre ";
       $virgula = ",";
       if(trim($this->ar31_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "ar31_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar31_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar31_numpar"])){ 
       $sql  .= $virgula." ar31_numpar = $this->ar31_numpar ";
       $virgula = ",";
       if(trim($this->ar31_numpar) == null ){ 
         $this->erro_sql = " Campo Numpar nao Informado.";
         $this->erro_campo = "ar31_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar31_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar31_receita"])){ 
       $sql  .= $virgula." ar31_receita = $this->ar31_receita ";
       $virgula = ",";
       if(trim($this->ar31_receita) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "ar31_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar31_sequencial!=null){
       $sql .= " ar31_sequencial = $this->ar31_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar31_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17159,'$this->ar31_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar31_sequencial"]) || $this->ar31_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3032,17159,'".AddSlashes(pg_result($resaco,$conresaco,'ar31_sequencial'))."','$this->ar31_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar31_declaracaoquitacao"]) || $this->ar31_declaracaoquitacao != "")
           $resac = db_query("insert into db_acount values($acount,3032,17160,'".AddSlashes(pg_result($resaco,$conresaco,'ar31_declaracaoquitacao'))."','$this->ar31_declaracaoquitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar31_numpre"]) || $this->ar31_numpre != "")
           $resac = db_query("insert into db_acount values($acount,3032,17161,'".AddSlashes(pg_result($resaco,$conresaco,'ar31_numpre'))."','$this->ar31_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar31_numpar"]) || $this->ar31_numpar != "")
           $resac = db_query("insert into db_acount values($acount,3032,17162,'".AddSlashes(pg_result($resaco,$conresaco,'ar31_numpar'))."','$this->ar31_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar31_receita"]) || $this->ar31_receita != "")
           $resac = db_query("insert into db_acount values($acount,3032,17163,'".AddSlashes(pg_result($resaco,$conresaco,'ar31_receita'))."','$this->ar31_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Declaração de Quitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar31_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Declaração de Quitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar31_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar31_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17159,'$ar31_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3032,17159,'','".AddSlashes(pg_result($resaco,$iresaco,'ar31_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3032,17160,'','".AddSlashes(pg_result($resaco,$iresaco,'ar31_declaracaoquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3032,17161,'','".AddSlashes(pg_result($resaco,$iresaco,'ar31_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3032,17162,'','".AddSlashes(pg_result($resaco,$iresaco,'ar31_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3032,17163,'','".AddSlashes(pg_result($resaco,$iresaco,'ar31_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from declaracaoquitacaoreg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar31_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar31_sequencial = $ar31_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Declaração de Quitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar31_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Declaração de Quitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar31_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:declaracaoquitacaoreg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar31_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from declaracaoquitacaoreg ";
     $sql .= "      inner join declaracaoquitacao  on  declaracaoquitacao.ar30_sequencial = declaracaoquitacaoreg.ar31_declaracaoquitacao";
     $sql .= "      inner join db_config  on  db_config.codigo = declaracaoquitacao.ar30_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = declaracaoquitacao.ar30_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ar31_sequencial!=null ){
         $sql2 .= " where declaracaoquitacaoreg.ar31_sequencial = $ar31_sequencial "; 
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
   function sql_query_file ( $ar31_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from declaracaoquitacaoreg ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar31_sequencial!=null ){
         $sql2 .= " where declaracaoquitacaoreg.ar31_sequencial = $ar31_sequencial "; 
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