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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcimpactoval
class cl_orcimpactoval { 
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
   var $o91_codseqimp = 0; 
   var $o91_codimp = 0; 
   var $o91_exercicio = 0; 
   var $o91_valor = 0; 
   var $o91_proces = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o91_codseqimp = int8 = Sequencial 
                 o91_codimp = int4 = Código 
                 o91_exercicio = int4 = Exercício 
                 o91_valor = float8 = Valor 
                 o91_proces = int4 = Processo 
                 ";
   //funcao construtor da classe 
   function cl_orcimpactoval() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcimpactoval"); 
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
       $this->o91_codseqimp = ($this->o91_codseqimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o91_codseqimp"]:$this->o91_codseqimp);
       $this->o91_codimp = ($this->o91_codimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o91_codimp"]:$this->o91_codimp);
       $this->o91_exercicio = ($this->o91_exercicio == ""?@$GLOBALS["HTTP_POST_VARS"]["o91_exercicio"]:$this->o91_exercicio);
       $this->o91_valor = ($this->o91_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o91_valor"]:$this->o91_valor);
       $this->o91_proces = ($this->o91_proces == ""?@$GLOBALS["HTTP_POST_VARS"]["o91_proces"]:$this->o91_proces);
     }else{
       $this->o91_codseqimp = ($this->o91_codseqimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o91_codseqimp"]:$this->o91_codseqimp);
     }
   }
   // funcao para inclusao
   function incluir ($o91_codseqimp){ 
      $this->atualizacampos();
     if($this->o91_codimp == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "o91_codimp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o91_exercicio == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "o91_exercicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o91_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o91_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o91_proces == null ){ 
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "o91_proces";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o91_codseqimp == "" || $o91_codseqimp == null ){
       $result = db_query("select nextval('orcimpactoval_o91_codseqimp_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcimpactoval_o91_codseqimp_seq do campo: o91_codseqimp"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o91_codseqimp = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcimpactoval_o91_codseqimp_seq");
       if(($result != false) && (pg_result($result,0,0) < $o91_codseqimp)){
         $this->erro_sql = " Campo o91_codseqimp maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o91_codseqimp = $o91_codseqimp; 
       }
     }
     if(($this->o91_codseqimp == null) || ($this->o91_codseqimp == "") ){ 
       $this->erro_sql = " Campo o91_codseqimp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcimpactoval(
                                       o91_codseqimp 
                                      ,o91_codimp 
                                      ,o91_exercicio 
                                      ,o91_valor 
                                      ,o91_proces 
                       )
                values (
                                $this->o91_codseqimp 
                               ,$this->o91_codimp 
                               ,$this->o91_exercicio 
                               ,$this->o91_valor 
                               ,$this->o91_proces 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores do impacto orçamentário ($this->o91_codseqimp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores do impacto orçamentário já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores do impacto orçamentário ($this->o91_codseqimp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o91_codseqimp;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o91_codseqimp));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6641,'$this->o91_codseqimp','I')");
       $resac = db_query("insert into db_acount values($acount,1089,6641,'','".AddSlashes(pg_result($resaco,0,'o91_codseqimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1089,6642,'','".AddSlashes(pg_result($resaco,0,'o91_codimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1089,6643,'','".AddSlashes(pg_result($resaco,0,'o91_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1089,6644,'','".AddSlashes(pg_result($resaco,0,'o91_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1089,6646,'','".AddSlashes(pg_result($resaco,0,'o91_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o91_codseqimp=null) { 
      $this->atualizacampos();
     $sql = " update orcimpactoval set ";
     $virgula = "";
     if(trim($this->o91_codseqimp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o91_codseqimp"])){ 
       $sql  .= $virgula." o91_codseqimp = $this->o91_codseqimp ";
       $virgula = ",";
       if(trim($this->o91_codseqimp) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o91_codseqimp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o91_codimp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o91_codimp"])){ 
       $sql  .= $virgula." o91_codimp = $this->o91_codimp ";
       $virgula = ",";
       if(trim($this->o91_codimp) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o91_codimp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o91_exercicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o91_exercicio"])){ 
       $sql  .= $virgula." o91_exercicio = $this->o91_exercicio ";
       $virgula = ",";
       if(trim($this->o91_exercicio) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o91_exercicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o91_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o91_valor"])){ 
       $sql  .= $virgula." o91_valor = $this->o91_valor ";
       $virgula = ",";
       if(trim($this->o91_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o91_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o91_proces)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o91_proces"])){ 
       $sql  .= $virgula." o91_proces = $this->o91_proces ";
       $virgula = ",";
       if(trim($this->o91_proces) == null ){ 
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "o91_proces";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o91_codseqimp!=null){
       $sql .= " o91_codseqimp = $this->o91_codseqimp";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o91_codseqimp));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6641,'$this->o91_codseqimp','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o91_codseqimp"]))
           $resac = db_query("insert into db_acount values($acount,1089,6641,'".AddSlashes(pg_result($resaco,$conresaco,'o91_codseqimp'))."','$this->o91_codseqimp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o91_codimp"]))
           $resac = db_query("insert into db_acount values($acount,1089,6642,'".AddSlashes(pg_result($resaco,$conresaco,'o91_codimp'))."','$this->o91_codimp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o91_exercicio"]))
           $resac = db_query("insert into db_acount values($acount,1089,6643,'".AddSlashes(pg_result($resaco,$conresaco,'o91_exercicio'))."','$this->o91_exercicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o91_valor"]))
           $resac = db_query("insert into db_acount values($acount,1089,6644,'".AddSlashes(pg_result($resaco,$conresaco,'o91_valor'))."','$this->o91_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o91_proces"]))
           $resac = db_query("insert into db_acount values($acount,1089,6646,'".AddSlashes(pg_result($resaco,$conresaco,'o91_proces'))."','$this->o91_proces',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores do impacto orçamentário nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o91_codseqimp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores do impacto orçamentário nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o91_codseqimp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o91_codseqimp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o91_codseqimp=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o91_codseqimp));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6641,'$o91_codseqimp','E')");
         $resac = db_query("insert into db_acount values($acount,1089,6641,'','".AddSlashes(pg_result($resaco,$iresaco,'o91_codseqimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1089,6642,'','".AddSlashes(pg_result($resaco,$iresaco,'o91_codimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1089,6643,'','".AddSlashes(pg_result($resaco,$iresaco,'o91_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1089,6644,'','".AddSlashes(pg_result($resaco,$iresaco,'o91_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1089,6646,'','".AddSlashes(pg_result($resaco,$iresaco,'o91_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcimpactoval
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o91_codseqimp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o91_codseqimp = $o91_codseqimp ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores do impacto orçamentário nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o91_codseqimp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores do impacto orçamentário nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o91_codseqimp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o91_codseqimp;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcimpactoval";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o91_codseqimp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactoval ";
     $sql .= "      inner join orcimpacto  on  orcimpacto.o90_codimp = orcimpactoval.o91_codimp";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcimpacto.o90_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcimpacto.o90_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcimpacto.o90_anoexe and  orcprograma.o54_programa = orcimpacto.o90_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcimpacto.o90_anoexe and  orcprojativ.o55_projativ = orcimpacto.o90_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcimpacto.o90_anoexe and  orcorgao.o40_orgao = orcimpacto.o90_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcimpacto.o90_anoexe and  orcunidade.o41_orgao = orcimpacto.o90_orgao and  orcunidade.o41_unidade = orcimpacto.o90_unidade";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcimpacto.o90_produto";
     $sql .= "      inner join orcimpactoperiodo  on  orcimpactoperiodo.o96_codperiodo = orcimpacto.o90_codperiodo";
     $sql2 = "";
     if($dbwhere==""){
       if($o91_codseqimp!=null ){
         $sql2 .= " where orcimpactoval.o91_codseqimp = $o91_codseqimp "; 
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

   function sql_query_file ( $o91_codseqimp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactoval ";
     $sql2 = "";
     if($dbwhere==""){
       if($o91_codseqimp!=null ){
         $sql2 .= " where orcimpactoval.o91_codseqimp = $o91_codseqimp "; 
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
   function sql_query_dad ( $o91_codseqimp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpacto ";
     $sql .= "    left join orcimpactoval     on orcimpacto.o90_codimp = orcimpactoval.o91_codimp";
     $sql .= "    left join orcimpactovalele  on o94_codseqimp = o91_codseqimp";
     $sql .= "    left join orcelemento       on o94_codele    = o56_codele and o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "    left join orcimpactotiporec on o93_codseqimp = o91_codseqimp";
     $sql .= "    left join orctiporec        on o93_codigo    = o15_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($o24_codseqppa!=null ){
         $sql2 .= " where orcppaval.o24_codseqppa = $o24_codseqppa "; 
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