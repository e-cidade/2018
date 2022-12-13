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

//MODULO: licitação
//CLASSE DA ENTIDADE liclicitaedital
class cl_liclicitaedital { 
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
   var $l27_sequencial = 0; 
   var $l27_arquivo = 0; 
   var $l27_arqnome = null; 
   var $l27_liclicita = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l27_sequencial = int4 = sequencial 
                 l27_arquivo = oid = Arquivo 
                 l27_arqnome = varchar(50) = Nome do arquivo 
                 l27_liclicita = int4 = codigo 
                 ";
   //funcao construtor da classe 
   function cl_liclicitaedital() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitaedital"); 
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
       $this->l27_sequencial = ($this->l27_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l27_sequencial"]:$this->l27_sequencial);
       $this->l27_arquivo = ($this->l27_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["l27_arquivo"]:$this->l27_arquivo);
       $this->l27_arqnome = ($this->l27_arqnome == ""?@$GLOBALS["HTTP_POST_VARS"]["l27_arqnome"]:$this->l27_arqnome);
       $this->l27_liclicita = ($this->l27_liclicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l27_liclicita"]:$this->l27_liclicita);
     }else{
       $this->l27_sequencial = ($this->l27_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l27_sequencial"]:$this->l27_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($l27_sequencial){ 
      $this->atualizacampos();
     if($this->l27_arquivo == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "l27_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l27_arqnome == null ){ 
       $this->erro_sql = " Campo Nome do arquivo nao Informado.";
       $this->erro_campo = "l27_arqnome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l27_liclicita == null ){ 
       $this->erro_sql = " Campo codigo nao Informado.";
       $this->erro_campo = "l27_liclicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l27_sequencial == "" || $l27_sequencial == null ){
       $result = db_query("select nextval('liclicitaedital_l27_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitaedital_l27_sequencial_seq do campo: l27_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l27_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liclicitaedital_l27_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l27_sequencial)){
         $this->erro_sql = " Campo l27_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l27_sequencial = $l27_sequencial; 
       }
     }
     if(($this->l27_sequencial == null) || ($this->l27_sequencial == "") ){ 
       $this->erro_sql = " Campo l27_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitaedital(
                                       l27_sequencial 
                                      ,l27_arquivo 
                                      ,l27_arqnome 
                                      ,l27_liclicita 
                       )
                values (
                                $this->l27_sequencial 
                               ,$this->l27_arquivo 
                               ,'$this->l27_arqnome' 
                               ,$this->l27_liclicita 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "liclicitaedital ($this->l27_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "liclicitaedital já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "liclicitaedital ($this->l27_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l27_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l27_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9410,'$this->l27_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1617,9410,'','".AddSlashes(pg_result($resaco,0,'l27_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1617,9411,'','".AddSlashes(pg_result($resaco,0,'l27_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1617,9429,'','".AddSlashes(pg_result($resaco,0,'l27_arqnome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1617,9413,'','".AddSlashes(pg_result($resaco,0,'l27_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l27_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update liclicitaedital set ";
     $virgula = "";
     if(trim($this->l27_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l27_sequencial"])){ 
       $sql  .= $virgula." l27_sequencial = $this->l27_sequencial ";
       $virgula = ",";
       if(trim($this->l27_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "l27_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l27_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l27_arquivo"])){ 
       $sql  .= $virgula." l27_arquivo = $this->l27_arquivo ";
       $virgula = ",";
       if(trim($this->l27_arquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "l27_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l27_arqnome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l27_arqnome"])){ 
       $sql  .= $virgula." l27_arqnome = '$this->l27_arqnome' ";
       $virgula = ",";
       if(trim($this->l27_arqnome) == null ){ 
         $this->erro_sql = " Campo Nome do arquivo nao Informado.";
         $this->erro_campo = "l27_arqnome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l27_liclicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l27_liclicita"])){ 
       $sql  .= $virgula." l27_liclicita = $this->l27_liclicita ";
       $virgula = ",";
       if(trim($this->l27_liclicita) == null ){ 
         $this->erro_sql = " Campo codigo nao Informado.";
         $this->erro_campo = "l27_liclicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l27_sequencial!=null){
       $sql .= " l27_sequencial = $this->l27_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l27_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9410,'$this->l27_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l27_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1617,9410,'".AddSlashes(pg_result($resaco,$conresaco,'l27_sequencial'))."','$this->l27_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l27_arquivo"]))
           $resac = db_query("insert into db_acount values($acount,1617,9411,'".AddSlashes(pg_result($resaco,$conresaco,'l27_arquivo'))."','$this->l27_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l27_arqnome"]))
           $resac = db_query("insert into db_acount values($acount,1617,9429,'".AddSlashes(pg_result($resaco,$conresaco,'l27_arqnome'))."','$this->l27_arqnome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l27_liclicita"]))
           $resac = db_query("insert into db_acount values($acount,1617,9413,'".AddSlashes(pg_result($resaco,$conresaco,'l27_liclicita'))."','$this->l27_liclicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitaedital nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l27_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitaedital nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l27_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l27_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l27_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l27_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9410,'$l27_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1617,9410,'','".AddSlashes(pg_result($resaco,$iresaco,'l27_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1617,9411,'','".AddSlashes(pg_result($resaco,$iresaco,'l27_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1617,9429,'','".AddSlashes(pg_result($resaco,$iresaco,'l27_arqnome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1617,9413,'','".AddSlashes(pg_result($resaco,$iresaco,'l27_liclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liclicitaedital
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l27_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l27_sequencial = $l27_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitaedital nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l27_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitaedital nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l27_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l27_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitaedital";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l27_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitaedital ";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitaedital.l27_liclicita";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
     $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
     $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
     $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
     $sql2 = "";
     if($dbwhere==""){
       if($l27_sequencial!=null ){
         $sql2 .= " where liclicitaedital.l27_sequencial = $l27_sequencial "; 
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
   function sql_query_file ( $l27_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitaedital ";
     $sql2 = "";
     if($dbwhere==""){
       if($l27_sequencial!=null ){
         $sql2 .= " where liclicitaedital.l27_sequencial = $l27_sequencial "; 
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