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

//MODULO: material
//CLASSE DA ENTIDADE matestoqueval
class cl_matestoqueval { 
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
   var $m72_codmatestoqueitem = 0; 
   var $m72_validade_dia = null; 
   var $m72_validade_mes = null; 
   var $m72_validade_ano = null; 
   var $m72_validade = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m72_codmatestoqueitem = int8 = Codigo sequencial do registro 
                 m72_validade = date = Validade 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueval() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueval"); 
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
       $this->m72_codmatestoqueitem = ($this->m72_codmatestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m72_codmatestoqueitem"]:$this->m72_codmatestoqueitem);
       if($this->m72_validade == ""){
         $this->m72_validade_dia = ($this->m72_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m72_validade_dia"]:$this->m72_validade_dia);
         $this->m72_validade_mes = ($this->m72_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m72_validade_mes"]:$this->m72_validade_mes);
         $this->m72_validade_ano = ($this->m72_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m72_validade_ano"]:$this->m72_validade_ano);
         if($this->m72_validade_dia != ""){
            $this->m72_validade = $this->m72_validade_ano."-".$this->m72_validade_mes."-".$this->m72_validade_dia;
         }
       }
     }else{
       $this->m72_codmatestoqueitem = ($this->m72_codmatestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m72_codmatestoqueitem"]:$this->m72_codmatestoqueitem);
     }
   }
   // funcao para inclusao
   function incluir ($m72_codmatestoqueitem){ 
      $this->atualizacampos();
     if($this->m72_validade == null ){ 
       $this->erro_sql = " Campo Validade nao Informado.";
       $this->erro_campo = "m72_validade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->m72_codmatestoqueitem = $m72_codmatestoqueitem; 
     if(($this->m72_codmatestoqueitem == null) || ($this->m72_codmatestoqueitem == "") ){ 
       $this->erro_sql = " Campo m72_codmatestoqueitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueval(
                                       m72_codmatestoqueitem 
                                      ,m72_validade 
                       )
                values (
                                $this->m72_codmatestoqueitem 
                               ,".($this->m72_validade == "null" || $this->m72_validade == ""?"null":"'".$this->m72_validade."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Validade dos itens do estoque ($this->m72_codmatestoqueitem) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Validade dos itens do estoque j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Validade dos itens do estoque ($this->m72_codmatestoqueitem) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m72_codmatestoqueitem;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m72_codmatestoqueitem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6278,'$this->m72_codmatestoqueitem','I')");
       $resac = db_query("insert into db_acount values($acount,1021,6278,'','".AddSlashes(pg_result($resaco,0,'m72_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1021,6279,'','".AddSlashes(pg_result($resaco,0,'m72_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m72_codmatestoqueitem=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueval set ";
     $virgula = "";
     if(trim($this->m72_codmatestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m72_codmatestoqueitem"])){ 
       $sql  .= $virgula." m72_codmatestoqueitem = $this->m72_codmatestoqueitem ";
       $virgula = ",";
       if(trim($this->m72_codmatestoqueitem) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial do registro nao Informado.";
         $this->erro_campo = "m72_codmatestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m72_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m72_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m72_validade_dia"] !="") ){ 
       $sql  .= $virgula." m72_validade = '$this->m72_validade' ";
       $virgula = ",";
       if(trim($this->m72_validade) == null ){ 
         $this->erro_sql = " Campo Validade nao Informado.";
         $this->erro_campo = "m72_validade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m72_validade_dia"])){ 
         $sql  .= $virgula." m72_validade = null ";
         $virgula = ",";
         if(trim($this->m72_validade) == null ){ 
           $this->erro_sql = " Campo Validade nao Informado.";
           $this->erro_campo = "m72_validade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($m72_codmatestoqueitem!=null){
       $sql .= " m72_codmatestoqueitem = $this->m72_codmatestoqueitem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m72_codmatestoqueitem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6278,'$this->m72_codmatestoqueitem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m72_codmatestoqueitem"]))
           $resac = db_query("insert into db_acount values($acount,1021,6278,'".AddSlashes(pg_result($resaco,$conresaco,'m72_codmatestoqueitem'))."','$this->m72_codmatestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m72_validade"]))
           $resac = db_query("insert into db_acount values($acount,1021,6279,'".AddSlashes(pg_result($resaco,$conresaco,'m72_validade'))."','$this->m72_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Validade dos itens do estoque nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m72_codmatestoqueitem;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Validade dos itens do estoque nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m72_codmatestoqueitem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m72_codmatestoqueitem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m72_codmatestoqueitem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m72_codmatestoqueitem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6278,'$m72_codmatestoqueitem','E')");
         $resac = db_query("insert into db_acount values($acount,1021,6278,'','".AddSlashes(pg_result($resaco,$iresaco,'m72_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1021,6279,'','".AddSlashes(pg_result($resaco,$iresaco,'m72_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueval
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m72_codmatestoqueitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m72_codmatestoqueitem = $m72_codmatestoqueitem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Validade dos itens do estoque nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m72_codmatestoqueitem;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Validade dos itens do estoque nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m72_codmatestoqueitem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m72_codmatestoqueitem;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueval";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m72_codmatestoqueitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueval ";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueval.m72_codmatestoqueitem";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql2 = "";
     if($dbwhere==""){
       if($m72_codmatestoqueitem!=null ){
         $sql2 .= " where matestoqueval.m72_codmatestoqueitem = $m72_codmatestoqueitem "; 
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
   function sql_query_file ( $m72_codmatestoqueitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueval ";
     $sql2 = "";
     if($dbwhere==""){
       if($m72_codmatestoqueitem!=null ){
         $sql2 .= " where matestoqueval.m72_codmatestoqueitem = $m72_codmatestoqueitem "; 
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