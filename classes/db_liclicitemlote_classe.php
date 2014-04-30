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

//MODULO: licitação
//CLASSE DA ENTIDADE liclicitemlote
class cl_liclicitemlote { 
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
   var $l04_codigo = 0; 
   var $l04_liclicitem = 0; 
   var $l04_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l04_codigo = int8 = Cód. Sequencial 
                 l04_liclicitem = int8 = Item 
                 l04_descricao = varchar(40) = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_liclicitemlote() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitemlote"); 
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
       $this->l04_codigo = ($this->l04_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l04_codigo"]:$this->l04_codigo);
       $this->l04_liclicitem = ($this->l04_liclicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["l04_liclicitem"]:$this->l04_liclicitem);
       $this->l04_descricao = ($this->l04_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["l04_descricao"]:$this->l04_descricao);
     }else{
       $this->l04_codigo = ($this->l04_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l04_codigo"]:$this->l04_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($l04_codigo){ 
      $this->atualizacampos();
     if($this->l04_liclicitem == null ){ 
       $this->erro_sql = " Campo Item nao Informado.";
       $this->erro_campo = "l04_liclicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l04_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "l04_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l04_codigo == "" || $l04_codigo == null ){
       $result = db_query("select nextval('liclicitemlote_l04_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitemlote_l04_codigo_seq do campo: l04_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l04_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liclicitemlote_l04_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $l04_codigo)){
         $this->erro_sql = " Campo l04_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l04_codigo = $l04_codigo; 
       }
     }
     if(($this->l04_codigo == null) || ($this->l04_codigo == "") ){ 
       $this->erro_sql = " Campo l04_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitemlote(
                                       l04_codigo 
                                      ,l04_liclicitem 
                                      ,l04_descricao 
                       )
                values (
                                $this->l04_codigo 
                               ,$this->l04_liclicitem 
                               ,'$this->l04_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lote de itens de Licitação ($this->l04_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lote de itens de Licitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lote de itens de Licitação ($this->l04_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l04_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l04_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10012,'$this->l04_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1719,10012,'','".AddSlashes(pg_result($resaco,0,'l04_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1719,10013,'','".AddSlashes(pg_result($resaco,0,'l04_liclicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1719,10014,'','".AddSlashes(pg_result($resaco,0,'l04_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l04_codigo=null) { 
      $this->atualizacampos();
     $sql = " update liclicitemlote set ";
     $virgula = "";
     if(trim($this->l04_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l04_codigo"])){ 
       $sql  .= $virgula." l04_codigo = $this->l04_codigo ";
       $virgula = ",";
       if(trim($this->l04_codigo) == null ){ 
         $this->erro_sql = " Campo Cód. Sequencial nao Informado.";
         $this->erro_campo = "l04_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l04_liclicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l04_liclicitem"])){ 
       $sql  .= $virgula." l04_liclicitem = $this->l04_liclicitem ";
       $virgula = ",";
       if(trim($this->l04_liclicitem) == null ){ 
         $this->erro_sql = " Campo Item nao Informado.";
         $this->erro_campo = "l04_liclicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l04_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l04_descricao"])){ 
       $sql  .= $virgula." l04_descricao = '$this->l04_descricao' ";
       $virgula = ",";
       if(trim($this->l04_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "l04_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l04_codigo!=null){
       $sql .= " l04_codigo = $this->l04_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l04_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10012,'$this->l04_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l04_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1719,10012,'".AddSlashes(pg_result($resaco,$conresaco,'l04_codigo'))."','$this->l04_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l04_liclicitem"]))
           $resac = db_query("insert into db_acount values($acount,1719,10013,'".AddSlashes(pg_result($resaco,$conresaco,'l04_liclicitem'))."','$this->l04_liclicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l04_descricao"]))
           $resac = db_query("insert into db_acount values($acount,1719,10014,'".AddSlashes(pg_result($resaco,$conresaco,'l04_descricao'))."','$this->l04_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote de itens de Licitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l04_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lote de itens de Licitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l04_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l04_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10012,'$l04_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1719,10012,'','".AddSlashes(pg_result($resaco,$iresaco,'l04_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1719,10013,'','".AddSlashes(pg_result($resaco,$iresaco,'l04_liclicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1719,10014,'','".AddSlashes(pg_result($resaco,$iresaco,'l04_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liclicitemlote
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l04_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l04_codigo = $l04_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lote de itens de Licitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l04_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lote de itens de Licitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l04_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l04_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitemlote";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l04_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitemlote ";
     $sql .= "      inner join liclicitem  on  liclicitem.l21_codigo = liclicitemlote.l04_liclicitem";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitem.l21_codliclicita";
     $sql2 = "";
     if($dbwhere==""){
       if($l04_codigo!=null ){
         $sql2 .= " where liclicitemlote.l04_codigo = $l04_codigo "; 
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
   function sql_query_file ( $l04_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitemlote ";
     $sql2 = "";
     if($dbwhere==""){
       if($l04_codigo!=null ){
         $sql2 .= " where liclicitemlote.l04_codigo = $l04_codigo "; 
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
   function sql_query_julgamento ( $l04_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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

     $sql .= " from liclicitemlote";
     $sql .= "      inner join liclicitem       on liclicitem.l21_codigo           = liclicitemlote.l04_liclicitem";
     $sql .= "      inner join pcorcamitemlic   on pcorcamitemlic.pc26_liclicitem  = liclicitem.l21_codigo";
     $sql .= "      inner join liclicita        on liclicita.l20_codigo            = liclicitem.l21_codliclicita";
     $sql .= "      inner join pcprocitem       on pcprocitem.pc81_codprocitem     = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join solicitem        on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      inner join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater";
     $sql .= "      inner join pcorcamval       on pcorcamval.pc23_orcamitem       = pcorcamitemlic.pc26_orcamitem";
     $sql .= "      inner join pcorcamforne     on pcorcamforne.pc21_orcamforne    = pcorcamval.pc23_orcamforne";
     $sql .= "      inner join cgm              on cgm.z01_numcgm                  = pcorcamforne.pc21_numcgm";
     $sql .= "      left  join pcorcamdescla    on pcorcamdescla.pc32_orcamitem    = pcorcamval.pc23_orcamitem and";
     $sql .= "                                     pcorcamdescla.pc32_orcamforne   = pcorcamval.pc23_orcamforne";
     $sql .= "      left  join pcorcamjulg      on pcorcamjulg.pc24_orcamitem      = pcorcamitemlic.pc26_orcamitem and
                                                   pcorcamjulg.pc24_orcamforne     = pcorcamforne.pc21_orcamforne";
     $sql2 = "";
     if($dbwhere==""){
       if($l04_codigo!=null ){
         $sql2 .= " where liclicitemlote.l04_codigo = $l04_codigo "; 
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
   function sql_query_licitacao ( $l04_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitem ";
     $sql .= "      left  join liclicitemlote   on liclicitemlote.l04_liclicitem   = liclicitem.l21_codigo";
     $sql .= "      inner join liclicita        on liclicita.l20_codigo            = liclicitem.l21_codliclicita"; 
     $sql .= "      inner join pcprocitem       on liclicitem.l21_codpcprocitem    = pcprocitem.pc81_codprocitem";
     $sql .= "      inner join pcproc           on pcproc.pc80_codproc             = pcprocitem.pc81_codproc";
     $sql .= "      inner join solicitem        on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicita         on solicita.pc10_numero            = solicitem.pc11_numero";
     $sql .= "      inner join db_depart        on db_depart.coddepto              = solicita.pc10_depto";
     $sql .= "      inner join db_usuarios      on solicita.pc10_login             = db_usuarios.id_usuario";
     $sql .= "      left  join solicitemunid    on solicitemunid.pc17_codigo       = solicitem.pc11_codigo";
     $sql .= "      left  join matunid          on matunid.m61_codmatunid          = solicitemunid.pc17_unid";     
     $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater"; 
     $sql .= "      left  join solicitemele     on solicitemele.pc18_solicitem     = solicitem.pc11_codigo";    
     $sql2 = "";
     if($dbwhere==""){
       if($l04_codigo!=null ){
         $sql2 .= " where liclicitemlote.l04_codigo = $l04_codigo "; 
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