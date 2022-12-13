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
//CLASSE DA ENTIDADE orcimpactovalmov
class cl_orcimpactovalmov { 
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
   var $o64_codseqimpmov = 0; 
   var $o64_codimpmov = 0; 
   var $o64_exercicio = 0; 
   var $o64_valor = 0; 
   var $o64_proces = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o64_codseqimpmov = int8 = Código 
                 o64_codimpmov = int4 = Código 
                 o64_exercicio = int4 = Exercício 
                 o64_valor = float8 = Valor 
                 o64_proces = int4 = Processo 
                 ";
   //funcao construtor da classe 
   function cl_orcimpactovalmov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcimpactovalmov"); 
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
       $this->o64_codseqimpmov = ($this->o64_codseqimpmov == ""?@$GLOBALS["HTTP_POST_VARS"]["o64_codseqimpmov"]:$this->o64_codseqimpmov);
       $this->o64_codimpmov = ($this->o64_codimpmov == ""?@$GLOBALS["HTTP_POST_VARS"]["o64_codimpmov"]:$this->o64_codimpmov);
       $this->o64_exercicio = ($this->o64_exercicio == ""?@$GLOBALS["HTTP_POST_VARS"]["o64_exercicio"]:$this->o64_exercicio);
       $this->o64_valor = ($this->o64_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o64_valor"]:$this->o64_valor);
       $this->o64_proces = ($this->o64_proces == ""?@$GLOBALS["HTTP_POST_VARS"]["o64_proces"]:$this->o64_proces);
     }else{
       $this->o64_codseqimpmov = ($this->o64_codseqimpmov == ""?@$GLOBALS["HTTP_POST_VARS"]["o64_codseqimpmov"]:$this->o64_codseqimpmov);
     }
   }
   // funcao para inclusao
   function incluir ($o64_codseqimpmov){ 
      $this->atualizacampos();
     if($this->o64_codimpmov == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "o64_codimpmov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o64_exercicio == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "o64_exercicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o64_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o64_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o64_proces == null ){ 
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "o64_proces";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o64_codseqimpmov == "" || $o64_codseqimpmov == null ){
       $result = db_query("select nextval('orcimpactovalmov_o64_codseqimpmov_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcimpactovalmov_o64_codseqimpmov_seq do campo: o64_codseqimpmov"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o64_codseqimpmov = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcimpactovalmov_o64_codseqimpmov_seq");
       if(($result != false) && (pg_result($result,0,0) < $o64_codseqimpmov)){
         $this->erro_sql = " Campo o64_codseqimpmov maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o64_codseqimpmov = $o64_codseqimpmov; 
       }
     }
     if(($this->o64_codseqimpmov == null) || ($this->o64_codseqimpmov == "") ){ 
       $this->erro_sql = " Campo o64_codseqimpmov nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcimpactovalmov(
                                       o64_codseqimpmov 
                                      ,o64_codimpmov 
                                      ,o64_exercicio 
                                      ,o64_valor 
                                      ,o64_proces 
                       )
                values (
                                $this->o64_codseqimpmov 
                               ,$this->o64_codimpmov 
                               ,$this->o64_exercicio 
                               ,$this->o64_valor 
                               ,$this->o64_proces 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores dos movimentos ($this->o64_codseqimpmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores dos movimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores dos movimentos ($this->o64_codseqimpmov) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o64_codseqimpmov;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o64_codseqimpmov));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6680,'$this->o64_codseqimpmov','I')");
       $resac = db_query("insert into db_acount values($acount,1096,6680,'','".AddSlashes(pg_result($resaco,0,'o64_codseqimpmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1096,6681,'','".AddSlashes(pg_result($resaco,0,'o64_codimpmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1096,6682,'','".AddSlashes(pg_result($resaco,0,'o64_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1096,6683,'','".AddSlashes(pg_result($resaco,0,'o64_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1096,6685,'','".AddSlashes(pg_result($resaco,0,'o64_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o64_codseqimpmov=null) { 
      $this->atualizacampos();
     $sql = " update orcimpactovalmov set ";
     $virgula = "";
     if(trim($this->o64_codseqimpmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o64_codseqimpmov"])){ 
       $sql  .= $virgula." o64_codseqimpmov = $this->o64_codseqimpmov ";
       $virgula = ",";
       if(trim($this->o64_codseqimpmov) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o64_codseqimpmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o64_codimpmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o64_codimpmov"])){ 
       $sql  .= $virgula." o64_codimpmov = $this->o64_codimpmov ";
       $virgula = ",";
       if(trim($this->o64_codimpmov) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o64_codimpmov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o64_exercicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o64_exercicio"])){ 
       $sql  .= $virgula." o64_exercicio = $this->o64_exercicio ";
       $virgula = ",";
       if(trim($this->o64_exercicio) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o64_exercicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o64_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o64_valor"])){ 
       $sql  .= $virgula." o64_valor = $this->o64_valor ";
       $virgula = ",";
       if(trim($this->o64_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o64_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o64_proces)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o64_proces"])){ 
       $sql  .= $virgula." o64_proces = $this->o64_proces ";
       $virgula = ",";
       if(trim($this->o64_proces) == null ){ 
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "o64_proces";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o64_codseqimpmov!=null){
       $sql .= " o64_codseqimpmov = $this->o64_codseqimpmov";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o64_codseqimpmov));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6680,'$this->o64_codseqimpmov','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o64_codseqimpmov"]))
           $resac = db_query("insert into db_acount values($acount,1096,6680,'".AddSlashes(pg_result($resaco,$conresaco,'o64_codseqimpmov'))."','$this->o64_codseqimpmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o64_codimpmov"]))
           $resac = db_query("insert into db_acount values($acount,1096,6681,'".AddSlashes(pg_result($resaco,$conresaco,'o64_codimpmov'))."','$this->o64_codimpmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o64_exercicio"]))
           $resac = db_query("insert into db_acount values($acount,1096,6682,'".AddSlashes(pg_result($resaco,$conresaco,'o64_exercicio'))."','$this->o64_exercicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o64_valor"]))
           $resac = db_query("insert into db_acount values($acount,1096,6683,'".AddSlashes(pg_result($resaco,$conresaco,'o64_valor'))."','$this->o64_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o64_proces"]))
           $resac = db_query("insert into db_acount values($acount,1096,6685,'".AddSlashes(pg_result($resaco,$conresaco,'o64_proces'))."','$this->o64_proces',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores dos movimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o64_codseqimpmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores dos movimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o64_codseqimpmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o64_codseqimpmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o64_codseqimpmov=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o64_codseqimpmov));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6680,'$o64_codseqimpmov','E')");
         $resac = db_query("insert into db_acount values($acount,1096,6680,'','".AddSlashes(pg_result($resaco,$iresaco,'o64_codseqimpmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1096,6681,'','".AddSlashes(pg_result($resaco,$iresaco,'o64_codimpmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1096,6682,'','".AddSlashes(pg_result($resaco,$iresaco,'o64_exercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1096,6683,'','".AddSlashes(pg_result($resaco,$iresaco,'o64_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1096,6685,'','".AddSlashes(pg_result($resaco,$iresaco,'o64_proces'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcimpactovalmov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o64_codseqimpmov != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o64_codseqimpmov = $o64_codseqimpmov ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores dos movimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o64_codseqimpmov;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores dos movimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o64_codseqimpmov;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o64_codseqimpmov;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcimpactovalmov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   function sql_query ( $o64_codseqimpmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactovalmov ";
     $sql .= "      inner join orcimpactomov  on  orcimpactomov.o63_codimpmov = orcimpactovalmov.o64_codimpmov";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcimpactomov.o63_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcimpactomov.o63_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcimpactomov.o63_anoexe and  orcprograma.o54_programa = orcimpactomov.o63_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcimpactomov.o63_anoexe and  orcprojativ.o55_projativ = orcimpactomov.o63_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcimpactomov.o63_anoexe and  orcorgao.o40_orgao = orcimpactomov.o63_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcimpactomov.o63_anoexe and  orcunidade.o41_orgao = orcimpactomov.o63_orgao and  orcunidade.o41_unidade = orcimpactomov.o63_unidade";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcimpactomov.o63_produto";
     $sql .= "      inner join orcimpactoperiodo  on  orcimpactoperiodo.o96_codperiodo = orcimpactomov.o63_codperiodo";
     $sql2 = "";
     if($dbwhere==""){
       if($o64_codseqimpmov!=null ){
         $sql2 .= " where orcimpactovalmov.o64_codseqimpmov = $o64_codseqimpmov "; 
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

   function sql_query_file ( $o64_codseqimpmov=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactovalmov ";
     $sql2 = "";
     if($dbwhere==""){
       if($o64_codseqimpmov!=null ){
         $sql2 .= " where orcimpactovalmov.o64_codseqimpmov = $o64_codseqimpmov "; 
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
   function sql_query_soma ( $o64_codseqimp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactomov ";
     $sql .= "    inner join orcimpactovalmov on orcimpactovalmov.o64_codimpmov = orcimpactomov.o63_codimpmov";     
     $sql .= "    inner join orcimpactomovtiporec on orcimpactomovtiporec.o67_codseqimpmov = orcimpactovalmov.o64_codseqimpmov";
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
   function sql_query_dad ( $o64_codseqimp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcimpactomov ";
     $sql .= "    left join orcimpactovalmov     on orcimpactomov.o63_codimpmov = orcimpactovalmov.o64_codimpmov";
     $sql .= "    left join orcimpactovalmovele  on o66_codseqimpmov = o64_codseqimpmov";
     $sql .= "    left join orcelemento       on o66_codele    = o56_codele and o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "    left join orcimpactomovtiporec on o67_codseqimpmov = o64_codseqimpmov";
     $sql .= "    left join orctiporec        on o67_codigo    = o15_codigo";
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