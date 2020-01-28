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

//MODULO: Arrecada��o
//CLASSE DA ENTIDADE convenioarrecadacao
class cl_convenioarrecadacao { 
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
   var $ar14_sequencial = 0; 
   var $ar14_bancoagencia = 0; 
   var $ar14_cadarrecadacao = 0; 
   var $ar14_cadconvenio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar14_sequencial = int4 = Sequ�ncial 
                 ar14_bancoagencia = int4 = Banco da ag�ncia 
                 ar14_cadarrecadacao = int4 = Arrecada��o 
                 ar14_cadconvenio = int4 = Conv�nio 
                 ";
   //funcao construtor da classe 
   function cl_convenioarrecadacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("convenioarrecadacao"); 
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
       $this->ar14_sequencial = ($this->ar14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar14_sequencial"]:$this->ar14_sequencial);
       $this->ar14_bancoagencia = ($this->ar14_bancoagencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ar14_bancoagencia"]:$this->ar14_bancoagencia);
       $this->ar14_cadarrecadacao = ($this->ar14_cadarrecadacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar14_cadarrecadacao"]:$this->ar14_cadarrecadacao);
       $this->ar14_cadconvenio = ($this->ar14_cadconvenio == ""?@$GLOBALS["HTTP_POST_VARS"]["ar14_cadconvenio"]:$this->ar14_cadconvenio);
     }else{
       $this->ar14_sequencial = ($this->ar14_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar14_sequencial"]:$this->ar14_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar14_sequencial){ 
      $this->atualizacampos();
     if($this->ar14_bancoagencia == null ){ 
       $this->erro_sql = " Campo Banco da ag�ncia nao Informado.";
       $this->erro_campo = "ar14_bancoagencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar14_cadarrecadacao == null ){ 
       $this->erro_sql = " Campo Arrecada��o nao Informado.";
       $this->erro_campo = "ar14_cadarrecadacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar14_cadconvenio == null ){ 
       $this->erro_sql = " Campo Conv�nio nao Informado.";
       $this->erro_campo = "ar14_cadconvenio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar14_sequencial == "" || $ar14_sequencial == null ){
       $result = db_query("select nextval('convenioarrecadacao_ar14_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: convenioarrecadacao_ar14_sequencial_seq do campo: ar14_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar14_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from convenioarrecadacao_ar14_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar14_sequencial)){
         $this->erro_sql = " Campo ar14_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar14_sequencial = $ar14_sequencial; 
       }
     }
     if(($this->ar14_sequencial == null) || ($this->ar14_sequencial == "") ){ 
       $this->erro_sql = " Campo ar14_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into convenioarrecadacao(
                                       ar14_sequencial 
                                      ,ar14_bancoagencia 
                                      ,ar14_cadarrecadacao 
                                      ,ar14_cadconvenio 
                       )
                values (
                                $this->ar14_sequencial 
                               ,$this->ar14_bancoagencia 
                               ,$this->ar14_cadarrecadacao 
                               ,$this->ar14_cadconvenio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arrecada��o do conv�nio ($this->ar14_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arrecada��o do conv�nio j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arrecada��o do conv�nio ($this->ar14_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar14_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar14_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12538,'$this->ar14_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2189,12538,'','".AddSlashes(pg_result($resaco,0,'ar14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2189,12539,'','".AddSlashes(pg_result($resaco,0,'ar14_bancoagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2189,12540,'','".AddSlashes(pg_result($resaco,0,'ar14_cadarrecadacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2189,12541,'','".AddSlashes(pg_result($resaco,0,'ar14_cadconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar14_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update convenioarrecadacao set ";
     $virgula = "";
     if(trim($this->ar14_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar14_sequencial"])){ 
       $sql  .= $virgula." ar14_sequencial = $this->ar14_sequencial ";
       $virgula = ",";
       if(trim($this->ar14_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequ�ncial nao Informado.";
         $this->erro_campo = "ar14_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar14_bancoagencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar14_bancoagencia"])){ 
       $sql  .= $virgula." ar14_bancoagencia = $this->ar14_bancoagencia ";
       $virgula = ",";
       if(trim($this->ar14_bancoagencia) == null ){ 
         $this->erro_sql = " Campo Banco da ag�ncia nao Informado.";
         $this->erro_campo = "ar14_bancoagencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar14_cadarrecadacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar14_cadarrecadacao"])){ 
       $sql  .= $virgula." ar14_cadarrecadacao = $this->ar14_cadarrecadacao ";
       $virgula = ",";
       if(trim($this->ar14_cadarrecadacao) == null ){ 
         $this->erro_sql = " Campo Arrecada��o nao Informado.";
         $this->erro_campo = "ar14_cadarrecadacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar14_cadconvenio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar14_cadconvenio"])){ 
       $sql  .= $virgula." ar14_cadconvenio = $this->ar14_cadconvenio ";
       $virgula = ",";
       if(trim($this->ar14_cadconvenio) == null ){ 
         $this->erro_sql = " Campo Conv�nio nao Informado.";
         $this->erro_campo = "ar14_cadconvenio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar14_sequencial!=null){
       $sql .= " ar14_sequencial = $this->ar14_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar14_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12538,'$this->ar14_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar14_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2189,12538,'".AddSlashes(pg_result($resaco,$conresaco,'ar14_sequencial'))."','$this->ar14_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar14_bancoagencia"]))
           $resac = db_query("insert into db_acount values($acount,2189,12539,'".AddSlashes(pg_result($resaco,$conresaco,'ar14_bancoagencia'))."','$this->ar14_bancoagencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar14_cadarrecadacao"]))
           $resac = db_query("insert into db_acount values($acount,2189,12540,'".AddSlashes(pg_result($resaco,$conresaco,'ar14_cadarrecadacao'))."','$this->ar14_cadarrecadacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar14_cadconvenio"]))
           $resac = db_query("insert into db_acount values($acount,2189,12541,'".AddSlashes(pg_result($resaco,$conresaco,'ar14_cadconvenio'))."','$this->ar14_cadconvenio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arrecada��o do conv�nio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar14_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arrecada��o do conv�nio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar14_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar14_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar14_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar14_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12538,'$ar14_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2189,12538,'','".AddSlashes(pg_result($resaco,$iresaco,'ar14_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2189,12539,'','".AddSlashes(pg_result($resaco,$iresaco,'ar14_bancoagencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2189,12540,'','".AddSlashes(pg_result($resaco,$iresaco,'ar14_cadarrecadacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2189,12541,'','".AddSlashes(pg_result($resaco,$iresaco,'ar14_cadconvenio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from convenioarrecadacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar14_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar14_sequencial = $ar14_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arrecada��o do conv�nio nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar14_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arrecada��o do conv�nio nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar14_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar14_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:convenioarrecadacao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar14_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from convenioarrecadacao ";
     $sql .= "      inner join cadconvenio  on  cadconvenio.ar11_sequencial = convenioarrecadacao.ar14_cadconvenio";
     $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = convenioarrecadacao.ar14_bancoagencia";
     $sql .= "      inner join cadarrecadacao  on  cadarrecadacao.ar16_sequencial = convenioarrecadacao.ar14_cadarrecadacao";
     $sql .= "      inner join db_config  on  db_config.codigo = cadconvenio.ar11_instit";
     $sql .= "      inner join cadtipoconvenio  on  cadtipoconvenio.ar12_sequencial = cadconvenio.ar11_cadtipoconvenio";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = bancoagencia.db89_db_bancos";
     $sql .= "      inner join db_config  as a on   a.codigo = cadarrecadacao.ar16_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($ar14_sequencial!=null ){
         $sql2 .= " where convenioarrecadacao.ar14_sequencial = $ar14_sequencial "; 
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
   function sql_query_file ( $ar14_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from convenioarrecadacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar14_sequencial!=null ){
         $sql2 .= " where convenioarrecadacao.ar14_sequencial = $ar14_sequencial "; 
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