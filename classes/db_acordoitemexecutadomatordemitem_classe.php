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
//CLASSE DA ENTIDADE acordoitemexecutadomatordemitem
class cl_acordoitemexecutadomatordemitem { 
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
   var $ac30_sequencial = 0; 
   var $ac30_acordoitemexecutado = 0; 
   var $ac30_matordemitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac30_sequencial = int4 = Código Sequencial 
                 ac30_acordoitemexecutado = int4 = Item Executado 
                 ac30_matordemitem = int4 = Item da Ordem de Compra 
                 ";
   //funcao construtor da classe 
   function cl_acordoitemexecutadomatordemitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoitemexecutadomatordemitem"); 
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
       $this->ac30_sequencial = ($this->ac30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac30_sequencial"]:$this->ac30_sequencial);
       $this->ac30_acordoitemexecutado = ($this->ac30_acordoitemexecutado == ""?@$GLOBALS["HTTP_POST_VARS"]["ac30_acordoitemexecutado"]:$this->ac30_acordoitemexecutado);
       $this->ac30_matordemitem = ($this->ac30_matordemitem == ""?@$GLOBALS["HTTP_POST_VARS"]["ac30_matordemitem"]:$this->ac30_matordemitem);
     }else{
       $this->ac30_acordoitemexecutado = ($this->ac30_acordoitemexecutado == ""?@$GLOBALS["HTTP_POST_VARS"]["ac30_acordoitemexecutado"]:$this->ac30_acordoitemexecutado);
     }
   }
   // funcao para inclusao
   function incluir ($ac30_acordoitemexecutado){ 
      $this->atualizacampos();
     if($this->ac30_sequencial == null ){ 
       $this->erro_sql = " Campo Código Sequencial nao Informado.";
       $this->erro_campo = "ac30_sequencial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac30_matordemitem == null ){ 
       $this->erro_sql = " Campo Item da Ordem de Compra nao Informado.";
       $this->erro_campo = "ac30_matordemitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac30_sequencial == "" || $ac30_sequencial == null ){
       $result = db_query("select nextval('acordoitemexecutadomatordemitem_ac30_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoitemexecutadomatordemitem_ac30_sequencial_seq do campo: ac30_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac30_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoitemexecutadomatordemitem_ac30_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac30_sequencial)){
         $this->erro_sql = " Campo ac30_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac30_sequencial = $ac30_sequencial; 
       }
     }
     if(($this->ac30_acordoitemexecutado == null) || ($this->ac30_acordoitemexecutado == "") ){ 
       $this->erro_sql = " Campo ac30_acordoitemexecutado nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoitemexecutadomatordemitem(
                                       ac30_sequencial 
                                      ,ac30_acordoitemexecutado 
                                      ,ac30_matordemitem 
                       )
                values (
                                $this->ac30_sequencial 
                               ,$this->ac30_acordoitemexecutado 
                               ,$this->ac30_matordemitem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens executados na ordem de compra ($this->ac30_acordoitemexecutado) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens executados na ordem de compra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens executados na ordem de compra ($this->ac30_acordoitemexecutado) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac30_acordoitemexecutado;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac30_acordoitemexecutado));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16733,'$this->ac30_acordoitemexecutado','I')");
       $resac = db_query("insert into db_acount values($acount,2943,16732,'','".AddSlashes(pg_result($resaco,0,'ac30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2943,16733,'','".AddSlashes(pg_result($resaco,0,'ac30_acordoitemexecutado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2943,16734,'','".AddSlashes(pg_result($resaco,0,'ac30_matordemitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac30_acordoitemexecutado=null) { 
      $this->atualizacampos();
     $sql = " update acordoitemexecutadomatordemitem set ";
     $virgula = "";
     if(trim($this->ac30_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac30_sequencial"])){ 
       $sql  .= $virgula." ac30_sequencial = $this->ac30_sequencial ";
       $virgula = ",";
       if(trim($this->ac30_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "ac30_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac30_acordoitemexecutado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac30_acordoitemexecutado"])){ 
       $sql  .= $virgula." ac30_acordoitemexecutado = $this->ac30_acordoitemexecutado ";
       $virgula = ",";
       if(trim($this->ac30_acordoitemexecutado) == null ){ 
         $this->erro_sql = " Campo Item Executado nao Informado.";
         $this->erro_campo = "ac30_acordoitemexecutado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac30_matordemitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac30_matordemitem"])){ 
       $sql  .= $virgula." ac30_matordemitem = $this->ac30_matordemitem ";
       $virgula = ",";
       if(trim($this->ac30_matordemitem) == null ){ 
         $this->erro_sql = " Campo Item da Ordem de Compra nao Informado.";
         $this->erro_campo = "ac30_matordemitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac30_acordoitemexecutado!=null){
       $sql .= " ac30_acordoitemexecutado = $this->ac30_acordoitemexecutado";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac30_acordoitemexecutado));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16733,'$this->ac30_acordoitemexecutado','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac30_sequencial"]) || $this->ac30_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2943,16732,'".AddSlashes(pg_result($resaco,$conresaco,'ac30_sequencial'))."','$this->ac30_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac30_acordoitemexecutado"]) || $this->ac30_acordoitemexecutado != "")
           $resac = db_query("insert into db_acount values($acount,2943,16733,'".AddSlashes(pg_result($resaco,$conresaco,'ac30_acordoitemexecutado'))."','$this->ac30_acordoitemexecutado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac30_matordemitem"]) || $this->ac30_matordemitem != "")
           $resac = db_query("insert into db_acount values($acount,2943,16734,'".AddSlashes(pg_result($resaco,$conresaco,'ac30_matordemitem'))."','$this->ac30_matordemitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens executados na ordem de compra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac30_acordoitemexecutado;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens executados na ordem de compra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac30_acordoitemexecutado;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac30_acordoitemexecutado;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac30_acordoitemexecutado=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac30_acordoitemexecutado));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16733,'$ac30_acordoitemexecutado','E')");
         $resac = db_query("insert into db_acount values($acount,2943,16732,'','".AddSlashes(pg_result($resaco,$iresaco,'ac30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2943,16733,'','".AddSlashes(pg_result($resaco,$iresaco,'ac30_acordoitemexecutado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2943,16734,'','".AddSlashes(pg_result($resaco,$iresaco,'ac30_matordemitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoitemexecutadomatordemitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac30_acordoitemexecutado != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac30_acordoitemexecutado = $ac30_acordoitemexecutado ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens executados na ordem de compra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac30_acordoitemexecutado;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens executados na ordem de compra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac30_acordoitemexecutado;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac30_acordoitemexecutado;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoitemexecutadomatordemitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac30_acordoitemexecutado=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutadomatordemitem ";
     $sql .= "      inner join matordemitem  on  matordemitem.m52_codlanc = acordoitemexecutadomatordemitem.ac30_matordemitem";
     $sql .= "      inner join acordoitemexecutado  on  acordoitemexecutado.ac29_sequencial = acordoitemexecutadomatordemitem.ac30_acordoitemexecutado";
     $sql .= "      inner join matordem  on  matordem.m51_codordem = matordemitem.m52_codordem";
     $sql .= "      inner join acordoitem  as a on   a.ac20_sequencial = acordoitemexecutado.ac29_acordoitem";
     $sql2 = "";
     if($dbwhere==""){
       if($ac30_acordoitemexecutado!=null ){
         $sql2 .= " where acordoitemexecutadomatordemitem.ac30_acordoitemexecutado = $ac30_acordoitemexecutado "; 
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
   function sql_query_file ( $ac30_acordoitemexecutado=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutadomatordemitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac30_acordoitemexecutado!=null ){
         $sql2 .= " where acordoitemexecutadomatordemitem.ac30_acordoitemexecutado = $ac30_acordoitemexecutado "; 
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