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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_cadattdinamicoatributosvalor
class cl_db_cadattdinamicoatributosvalor { 
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
   var $db110_sequencial = 0; 
   var $db110_db_cadattdinamicoatributos = 0; 
   var $db110_cadattdinamicovalorgrupo = 0; 
   var $db110_valor = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db110_sequencial = int4 = C�digo Sequencial 
                 db110_db_cadattdinamicoatributos = int4 = C�digo Atributo Din�mico 
                 db110_cadattdinamicovalorgrupo = int4 = Grupo de Valores de um Atributo 
                 db110_valor = varchar(100) = Valor 
                 ";
   //funcao construtor da classe 
   function cl_db_cadattdinamicoatributosvalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_cadattdinamicoatributosvalor"); 
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
       $this->db110_sequencial = ($this->db110_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db110_sequencial"]:$this->db110_sequencial);
       $this->db110_db_cadattdinamicoatributos = ($this->db110_db_cadattdinamicoatributos == ""?@$GLOBALS["HTTP_POST_VARS"]["db110_db_cadattdinamicoatributos"]:$this->db110_db_cadattdinamicoatributos);
       $this->db110_cadattdinamicovalorgrupo = ($this->db110_cadattdinamicovalorgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["db110_cadattdinamicovalorgrupo"]:$this->db110_cadattdinamicovalorgrupo);
       $this->db110_valor = ($this->db110_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["db110_valor"]:$this->db110_valor);
     }else{
       $this->db110_sequencial = ($this->db110_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db110_sequencial"]:$this->db110_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db110_sequencial){ 
      $this->atualizacampos();
     if($this->db110_db_cadattdinamicoatributos == null ){ 
       $this->erro_sql = " Campo C�digo Atributo Din�mico nao Informado.";
       $this->erro_campo = "db110_db_cadattdinamicoatributos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db110_cadattdinamicovalorgrupo == null ){ 
       $this->erro_sql = " Campo Grupo de Valores de um Atributo nao Informado.";
       $this->erro_campo = "db110_cadattdinamicovalorgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db110_valor == null ){ 
       $this->db110_valor = '';
     }
     if($db110_sequencial == "" || $db110_sequencial == null ){
       $result = db_query("select nextval('db_cadattdinamicoatributosvalor_db110_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_cadattdinamicoatributosvalor_db110_sequencial_seq do campo: db110_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db110_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_cadattdinamicoatributosvalor_db110_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db110_sequencial)){
         $this->erro_sql = " Campo db110_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db110_sequencial = $db110_sequencial; 
       }
     }
     if(($this->db110_sequencial == null) || ($this->db110_sequencial == "") ){ 
       $this->erro_sql = " Campo db110_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_cadattdinamicoatributosvalor(
                                       db110_sequencial 
                                      ,db110_db_cadattdinamicoatributos 
                                      ,db110_cadattdinamicovalorgrupo 
                                      ,db110_valor 
                       )
                values (
                                $this->db110_sequencial 
                               ,$this->db110_db_cadattdinamicoatributos 
                               ,$this->db110_cadattdinamicovalorgrupo 
                               ,'$this->db110_valor' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "db_cadattdinamicoatributosvalor ($this->db110_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "db_cadattdinamicoatributosvalor j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "db_cadattdinamicoatributosvalor ($this->db110_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db110_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db110_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17880,'$this->db110_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3161,17880,'','".AddSlashes(pg_result($resaco,0,'db110_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3161,17881,'','".AddSlashes(pg_result($resaco,0,'db110_db_cadattdinamicoatributos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3161,17905,'','".AddSlashes(pg_result($resaco,0,'db110_cadattdinamicovalorgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3161,17882,'','".AddSlashes(pg_result($resaco,0,'db110_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db110_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_cadattdinamicoatributosvalor set ";
     $virgula = "";
     if(trim($this->db110_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db110_sequencial"])){ 
       $sql  .= $virgula." db110_sequencial = $this->db110_sequencial ";
       $virgula = ",";
       if(trim($this->db110_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "db110_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db110_db_cadattdinamicoatributos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db110_db_cadattdinamicoatributos"])){ 
       $sql  .= $virgula." db110_db_cadattdinamicoatributos = $this->db110_db_cadattdinamicoatributos ";
       $virgula = ",";
       if(trim($this->db110_db_cadattdinamicoatributos) == null ){ 
         $this->erro_sql = " Campo C�digo Atributo Din�mico nao Informado.";
         $this->erro_campo = "db110_db_cadattdinamicoatributos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db110_cadattdinamicovalorgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db110_cadattdinamicovalorgrupo"])){ 
       $sql  .= $virgula." db110_cadattdinamicovalorgrupo = $this->db110_cadattdinamicovalorgrupo ";
       $virgula = ",";
       if(trim($this->db110_cadattdinamicovalorgrupo) == null ){ 
         $this->erro_sql = " Campo Grupo de Valores de um Atributo nao Informado.";
         $this->erro_campo = "db110_cadattdinamicovalorgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     
     if(trim($this->db110_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db110_valor"])){ 
       $sql  .= $virgula." db110_valor = '$this->db110_valor' ";
       $virgula = ",";
     }
     
     $sql .= " where ";
     if($db110_sequencial!=null){
       $sql .= " db110_sequencial = $this->db110_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db110_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17880,'$this->db110_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db110_sequencial"]) || $this->db110_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3161,17880,'".AddSlashes(pg_result($resaco,$conresaco,'db110_sequencial'))."','$this->db110_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db110_db_cadattdinamicoatributos"]) || $this->db110_db_cadattdinamicoatributos != "")
           $resac = db_query("insert into db_acount values($acount,3161,17881,'".AddSlashes(pg_result($resaco,$conresaco,'db110_db_cadattdinamicoatributos'))."','$this->db110_db_cadattdinamicoatributos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db110_cadattdinamicovalorgrupo"]) || $this->db110_cadattdinamicovalorgrupo != "")
           $resac = db_query("insert into db_acount values($acount,3161,17905,'".AddSlashes(pg_result($resaco,$conresaco,'db110_cadattdinamicovalorgrupo'))."','$this->db110_cadattdinamicovalorgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db110_valor"]) || $this->db110_valor != "")
           $resac = db_query("insert into db_acount values($acount,3161,17882,'".AddSlashes(pg_result($resaco,$conresaco,'db110_valor'))."','$this->db110_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "db_cadattdinamicoatributosvalor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db110_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "db_cadattdinamicoatributosvalor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db110_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db110_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db110_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db110_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17880,'$db110_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3161,17880,'','".AddSlashes(pg_result($resaco,$iresaco,'db110_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3161,17881,'','".AddSlashes(pg_result($resaco,$iresaco,'db110_db_cadattdinamicoatributos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3161,17905,'','".AddSlashes(pg_result($resaco,$iresaco,'db110_cadattdinamicovalorgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3161,17882,'','".AddSlashes(pg_result($resaco,$iresaco,'db110_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_cadattdinamicoatributosvalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db110_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db110_sequencial = $db110_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "db_cadattdinamicoatributosvalor nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db110_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "db_cadattdinamicoatributosvalor nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db110_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db110_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:db_cadattdinamicoatributosvalor";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db110_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_cadattdinamicoatributosvalor ";
     $sql .= "      inner join db_cadattdinamicoatributos  on  db_cadattdinamicoatributos.db109_sequencial = db_cadattdinamicoatributosvalor.db110_db_cadattdinamicoatributos";
     $sql .= "      inner join db_cadattdinamicovalorgrupo  on  db_cadattdinamicovalorgrupo.db120_sequencial = db_cadattdinamicoatributosvalor.db110_cadattdinamicovalorgrupo";
     $sql .= "      inner join db_cadattdinamico  on  db_cadattdinamico.db118_sequencial = db_cadattdinamicoatributos.db109_db_cadattdinamico";
     $sql2 = "";
     if($dbwhere==""){
       if($db110_sequencial!=null ){
         $sql2 .= " where db_cadattdinamicoatributosvalor.db110_sequencial = $db110_sequencial "; 
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
   function sql_query_file ( $db110_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_cadattdinamicoatributosvalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($db110_sequencial!=null ){
         $sql2 .= " where db_cadattdinamicoatributosvalor.db110_sequencial = $db110_sequencial "; 
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