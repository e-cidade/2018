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

//MODULO: Acordos
//CLASSE DA ENTIDADE acordoitemexecutadoempautitem
class cl_acordoitemexecutadoempautitem { 
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
   var $ac19_sequencial = 0; 
   var $ac19_sequen = 0; 
   var $ac19_autori = 0; 
   var $ac19_acordoitemexecutado = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac19_sequencial = int4 = Sequencial 
                 ac19_sequen = int4 = Sequencia 
                 ac19_autori = int4 = Autorização 
                 ac19_acordoitemexecutado = int4 = Acordo Item 
                 ";
   //funcao construtor da classe 
   function cl_acordoitemexecutadoempautitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoitemexecutadoempautitem"); 
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
       $this->ac19_sequencial = ($this->ac19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac19_sequencial"]:$this->ac19_sequencial);
       $this->ac19_sequen = ($this->ac19_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["ac19_sequen"]:$this->ac19_sequen);
       $this->ac19_autori = ($this->ac19_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["ac19_autori"]:$this->ac19_autori);
       $this->ac19_acordoitemexecutado = ($this->ac19_acordoitemexecutado == ""?@$GLOBALS["HTTP_POST_VARS"]["ac19_acordoitemexecutado"]:$this->ac19_acordoitemexecutado);
     }else{
       $this->ac19_sequencial = ($this->ac19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac19_sequencial"]:$this->ac19_sequencial);
       $this->ac19_sequen = ($this->ac19_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["ac19_sequen"]:$this->ac19_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($ac19_sequencial){ 
      $this->atualizacampos();
     if($this->ac19_autori == null ){ 
       $this->erro_sql = " Campo Autorização nao Informado.";
       $this->erro_campo = "ac19_autori";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac19_acordoitemexecutado == null ){ 
       $this->erro_sql = " Campo Acordo Item nao Informado.";
       $this->erro_campo = "ac19_acordoitemexecutado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac19_sequencial == "" || $ac19_sequencial == null ){
       $result = db_query("select nextval('acordoitemexecutadoempautitem_ac19_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoitemexecutadoempautitem_ac19_sequencial_seq do campo: ac19_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac19_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoitemexecutadoempautitem_ac19_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac19_sequencial)){
         $this->erro_sql = " Campo ac19_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac19_sequencial = $ac19_sequencial; 
       }
     }
     if(($this->ac19_sequencial == null) || ($this->ac19_sequencial == "") ){ 
       $this->erro_sql = " Campo ac19_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoitemexecutadoempautitem(
                                       ac19_sequencial 
                                      ,ac19_sequen 
                                      ,ac19_autori 
                                      ,ac19_acordoitemexecutado 
                       )
                values (
                                $this->ac19_sequencial 
                               ,$this->ac19_sequen 
                               ,$this->ac19_autori 
                               ,$this->ac19_acordoitemexecutado 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens executados na autorizacao ($this->ac19_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens executados na autorizacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens executados na autorizacao ($this->ac19_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac19_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac19_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16194,'$this->ac19_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2839,16194,'','".AddSlashes(pg_result($resaco,0,'ac19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2839,16195,'','".AddSlashes(pg_result($resaco,0,'ac19_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2839,16196,'','".AddSlashes(pg_result($resaco,0,'ac19_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2839,16197,'','".AddSlashes(pg_result($resaco,0,'ac19_acordoitemexecutado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac19_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoitemexecutadoempautitem set ";
     $virgula = "";
     if(trim($this->ac19_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac19_sequencial"])){ 
       $sql  .= $virgula." ac19_sequencial = $this->ac19_sequencial ";
       $virgula = ",";
       if(trim($this->ac19_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac19_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac19_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac19_sequen"])){ 
       $sql  .= $virgula." ac19_sequen = $this->ac19_sequen ";
       $virgula = ",";
       if(trim($this->ac19_sequen) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "ac19_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac19_autori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac19_autori"])){ 
       $sql  .= $virgula." ac19_autori = $this->ac19_autori ";
       $virgula = ",";
       if(trim($this->ac19_autori) == null ){ 
         $this->erro_sql = " Campo Autorização nao Informado.";
         $this->erro_campo = "ac19_autori";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac19_acordoitemexecutado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac19_acordoitemexecutado"])){ 
       $sql  .= $virgula." ac19_acordoitemexecutado = $this->ac19_acordoitemexecutado ";
       $virgula = ",";
       if(trim($this->ac19_acordoitemexecutado) == null ){ 
         $this->erro_sql = " Campo Acordo Item nao Informado.";
         $this->erro_campo = "ac19_acordoitemexecutado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac19_sequencial!=null){
       $sql .= " ac19_sequencial = $this->ac19_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac19_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16194,'$this->ac19_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac19_sequencial"]) || $this->ac19_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2839,16194,'".AddSlashes(pg_result($resaco,$conresaco,'ac19_sequencial'))."','$this->ac19_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac19_sequen"]) || $this->ac19_sequen != "")
           $resac = db_query("insert into db_acount values($acount,2839,16195,'".AddSlashes(pg_result($resaco,$conresaco,'ac19_sequen'))."','$this->ac19_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac19_autori"]) || $this->ac19_autori != "")
           $resac = db_query("insert into db_acount values($acount,2839,16196,'".AddSlashes(pg_result($resaco,$conresaco,'ac19_autori'))."','$this->ac19_autori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac19_acordoitemexecutado"]) || $this->ac19_acordoitemexecutado != "")
           $resac = db_query("insert into db_acount values($acount,2839,16197,'".AddSlashes(pg_result($resaco,$conresaco,'ac19_acordoitemexecutado'))."','$this->ac19_acordoitemexecutado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens executados na autorizacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens executados na autorizacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac19_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac19_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16194,'$ac19_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2839,16194,'','".AddSlashes(pg_result($resaco,$iresaco,'ac19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2839,16195,'','".AddSlashes(pg_result($resaco,$iresaco,'ac19_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2839,16196,'','".AddSlashes(pg_result($resaco,$iresaco,'ac19_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2839,16197,'','".AddSlashes(pg_result($resaco,$iresaco,'ac19_acordoitemexecutado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoitemexecutadoempautitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac19_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac19_sequencial = $ac19_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens executados na autorizacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens executados na autorizacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac19_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoitemexecutadoempautitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutadoempautitem ";
     $sql .= "      inner join empautitem  on  empautitem.e55_autori = acordoitemexecutadoempautitem.ac19_autori and  empautitem.e55_sequen = acordoitemexecutadoempautitem.ac19_sequen";
     $sql .= "      inner join acordoitemexecutado  on  acordoitemexecutado.ac29_sequencial = acordoitemexecutadoempautitem.ac19_acordoitemexecutado";
     $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautitem.e55_autori";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empautitem.e55_item";
     $sql .= "      inner join acordoitem  as a on   a.ac20_sequencial = acordoitemexecutado.ac29_acordoitem";
     $sql2 = "";
     if($dbwhere==""){
       if($ac19_sequencial!=null ){
         $sql2 .= " where acordoitemexecutadoempautitem.ac19_sequencial = $ac19_sequencial "; 
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
   function sql_query_file ( $ac19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutadoempautitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac19_sequencial!=null ){
         $sql2 .= " where acordoitemexecutadoempautitem.ac19_sequencial = $ac19_sequencial "; 
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
  
  function sql_query_contrato($ac19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutadoempautitem ";
     $sql .= "      inner join empautitem  on  empautitem.e55_autori = acordoitemexecutadoempautitem.ac19_autori and  empautitem.e55_sequen = acordoitemexecutadoempautitem.ac19_sequen";
     $sql .= "      inner join acordoitemexecutado  on  acordoitemexecutado.ac29_sequencial = acordoitemexecutadoempautitem.ac19_acordoitemexecutado";
     $sql .= "      inner join empautoriza   on  empautoriza.e54_autori = empautitem.e55_autori";
     $sql .= "      inner join pcmater       on  pcmater.pc01_codmater = empautitem.e55_item";
     $sql .= "      inner join acordoitem    on   ac20_sequencial = acordoitemexecutado.ac29_acordoitem";
     $sql .= "      inner join acordoposicao on   ac26_sequencial = ac20_acordoposicao";
     $sql .= "      inner join acordo        on   ac26_acordo = ac16_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($ac19_sequencial!=null ){
         $sql2 .= " where acordoitemexecutadoempautitem.ac19_sequencial = $ac19_sequencial "; 
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