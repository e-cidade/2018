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
//CLASSE DA ENTIDADE matestoqueitemnota
class cl_matestoqueitemnota { 
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
   var $m74_codmatestoqueitem = 0; 
   var $m74_codempnota = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m74_codmatestoqueitem = int8 = Código sequencial do lançamento 
                 m74_codempnota = int4 = Nota 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueitemnota() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueitemnota"); 
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
       $this->m74_codmatestoqueitem = ($this->m74_codmatestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m74_codmatestoqueitem"]:$this->m74_codmatestoqueitem);
       $this->m74_codempnota = ($this->m74_codempnota == ""?@$GLOBALS["HTTP_POST_VARS"]["m74_codempnota"]:$this->m74_codempnota);
     }else{
       $this->m74_codmatestoqueitem = ($this->m74_codmatestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m74_codmatestoqueitem"]:$this->m74_codmatestoqueitem);
       $this->m74_codempnota = ($this->m74_codempnota == ""?@$GLOBALS["HTTP_POST_VARS"]["m74_codempnota"]:$this->m74_codempnota);
     }
   }
   // funcao para inclusao
   function incluir ($m74_codmatestoqueitem,$m74_codempnota){ 
      $this->atualizacampos();
       $this->m74_codmatestoqueitem = $m74_codmatestoqueitem; 
       $this->m74_codempnota = $m74_codempnota; 
     if(($this->m74_codmatestoqueitem == null) || ($this->m74_codmatestoqueitem == "") ){ 
       $this->erro_sql = " Campo m74_codmatestoqueitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->m74_codempnota == null) || ($this->m74_codempnota == "") ){ 
       $this->erro_sql = " Campo m74_codempnota nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueitemnota(
                                       m74_codmatestoqueitem 
                                      ,m74_codempnota 
                       )
                values (
                                $this->m74_codmatestoqueitem 
                               ,$this->m74_codempnota 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "notas de item de estoque ($this->m74_codmatestoqueitem."-".$this->m74_codempnota) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "notas de item de estoque já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "notas de item de estoque ($this->m74_codmatestoqueitem."-".$this->m74_codempnota) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m74_codmatestoqueitem."-".$this->m74_codempnota;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m74_codmatestoqueitem,$this->m74_codempnota));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6330,'$this->m74_codmatestoqueitem','I')");
       $resac = db_query("insert into db_acountkey values($acount,6331,'$this->m74_codempnota','I')");
       $resac = db_query("insert into db_acount values($acount,1032,6330,'','".AddSlashes(pg_result($resaco,0,'m74_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1032,6331,'','".AddSlashes(pg_result($resaco,0,'m74_codempnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m74_codmatestoqueitem=null,$m74_codempnota=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueitemnota set ";
     $virgula = "";
     if(trim($this->m74_codmatestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m74_codmatestoqueitem"])){ 
       $sql  .= $virgula." m74_codmatestoqueitem = $this->m74_codmatestoqueitem ";
       $virgula = ",";
       if(trim($this->m74_codmatestoqueitem) == null ){ 
         $this->erro_sql = " Campo Código sequencial do lançamento nao Informado.";
         $this->erro_campo = "m74_codmatestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m74_codempnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m74_codempnota"])){ 
       $sql  .= $virgula." m74_codempnota = $this->m74_codempnota ";
       $virgula = ",";
       if(trim($this->m74_codempnota) == null ){ 
         $this->erro_sql = " Campo Nota nao Informado.";
         $this->erro_campo = "m74_codempnota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m74_codmatestoqueitem!=null){
       $sql .= " m74_codmatestoqueitem = $this->m74_codmatestoqueitem";
     }
     if($m74_codempnota!=null){
       $sql .= " and  m74_codempnota = $this->m74_codempnota";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m74_codmatestoqueitem,$this->m74_codempnota));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6330,'$this->m74_codmatestoqueitem','A')");
         $resac = db_query("insert into db_acountkey values($acount,6331,'$this->m74_codempnota','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m74_codmatestoqueitem"]))
           $resac = db_query("insert into db_acount values($acount,1032,6330,'".AddSlashes(pg_result($resaco,$conresaco,'m74_codmatestoqueitem'))."','$this->m74_codmatestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m74_codempnota"]))
           $resac = db_query("insert into db_acount values($acount,1032,6331,'".AddSlashes(pg_result($resaco,$conresaco,'m74_codempnota'))."','$this->m74_codempnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notas de item de estoque nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m74_codmatestoqueitem."-".$this->m74_codempnota;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notas de item de estoque nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m74_codmatestoqueitem."-".$this->m74_codempnota;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m74_codmatestoqueitem."-".$this->m74_codempnota;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m74_codmatestoqueitem=null,$m74_codempnota=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m74_codmatestoqueitem,$m74_codempnota));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6330,'$m74_codmatestoqueitem','E')");
         $resac = db_query("insert into db_acountkey values($acount,6331,'$m74_codempnota','E')");
         $resac = db_query("insert into db_acount values($acount,1032,6330,'','".AddSlashes(pg_result($resaco,$iresaco,'m74_codmatestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1032,6331,'','".AddSlashes(pg_result($resaco,$iresaco,'m74_codempnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueitemnota
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m74_codmatestoqueitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m74_codmatestoqueitem = $m74_codmatestoqueitem ";
        }
        if($m74_codempnota != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m74_codempnota = $m74_codempnota ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notas de item de estoque nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m74_codmatestoqueitem."-".$m74_codempnota;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notas de item de estoque nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m74_codmatestoqueitem."-".$m74_codempnota;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m74_codmatestoqueitem."-".$m74_codempnota;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueitemnota";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m74_codmatestoqueitem=null,$m74_codempnota=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemnota ";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = matestoqueitemnota.m74_codempnota";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemnota.m74_codmatestoqueitem";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empnota.e69_numemp";
     $sql .= "      inner join matestoque  as a on   a.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql2 = "";
     if($dbwhere==""){
       if($m74_codmatestoqueitem!=null ){
         $sql2 .= " where matestoqueitemnota.m74_codmatestoqueitem = $m74_codmatestoqueitem "; 
       } 
       if($m74_codempnota!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " matestoqueitemnota.m74_codempnota = $m74_codempnota "; 
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
   function sql_query_file ( $m74_codmatestoqueitem=null,$m74_codempnota=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemnota ";
     $sql2 = "";
     if($dbwhere==""){
       if($m74_codmatestoqueitem!=null ){
         $sql2 .= " where matestoqueitemnota.m74_codmatestoqueitem = $m74_codmatestoqueitem "; 
       } 
       if($m74_codempnota!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " matestoqueitemnota.m74_codempnota = $m74_codempnota "; 
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
   function sql_query_itens ( $m74_codmatestoqueitem=null,$m74_codempnota=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemnota ";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = matestoqueitemnota.m74_codempnota";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemnota.m74_codmatestoqueitem";
     $sql .= "      inner join matestoqueitemoc  on  matestoqueitemoc.m73_codmatestoqueitem = matestoqueitem.m71_codlanc";
     $sql .= "      inner join matestoque  as a on   a.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join matmater on matmater.m60_codmater = m70_codmatmater";
     $sql .= "      inner join matordemitem  on  matordemitem.m52_codlanc = matestoqueitemoc.m73_codmatordemitem";
     $sql .= "      inner join empempitem  on  empempitem.e62_numemp = matordemitem.m52_numemp and empempitem.e62_sequen = matordemitem.m52_sequen";
     $sql .= "      inner join empempenho on empempenho.e60_numemp = matordemitem.m52_numemp";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join matordem  on  matordem.m51_codordem = matordemitem.m52_codordem";
     $sql2 = "";
     if($dbwhere==""){
       if($m74_codmatestoqueitem!=null ){
         $sql2 .= " where matestoqueitemnota.m74_codmatestoqueitem = $m74_codmatestoqueitem "; 
       } 
       if($m74_codempnota!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " matestoqueitemnota.m74_codempnota = $m74_codempnota "; 
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
   function sql_query_itensunid ( $m74_codmatestoqueitem=null,$m74_codempnota=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemnota ";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = matestoqueitemnota.m74_codempnota";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemnota.m74_codmatestoqueitem";
     $sql .= "      inner join matestoqueitemoc  on  matestoqueitemoc.m73_codmatestoqueitem = matestoqueitem.m71_codlanc";
     $sql .= "      inner join matestoque  as a on   a.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join matmater on matmater.m60_codmater = m70_codmatmater";
     $sql .= "      inner join matordemitem  on  matordemitem.m52_codlanc = matestoqueitemoc.m73_codmatordemitem";
     $sql .= "      inner join empempitem  on  empempitem.e62_numemp = matordemitem.m52_numemp and empempitem.e62_sequen = matordemitem.m52_sequen";
     $sql .= "      inner join empempenho on empempenho.e60_numemp = matordemitem.m52_numemp";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join matordem  on  matordem.m51_codordem = matordemitem.m52_codordem";
     $sql .= "      inner join matestoqueitemunid  on  matestoqueitemunid.m75_codmatestoqueitem = matestoqueitem.m71_codlanc";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matestoqueitemunid.m75_codmatunid";
     $sql2 = "";
     if($dbwhere==""){
       if($m74_codmatestoqueitem!=null ){
         $sql2 .= " where matestoqueitemnota.m74_codmatestoqueitem = $m74_codmatestoqueitem "; 
       } 
       if($m74_codempnota!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " matestoqueitemnota.m74_codempnota = $m74_codempnota "; 
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