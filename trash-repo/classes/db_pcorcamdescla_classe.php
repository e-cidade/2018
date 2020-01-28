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

//MODULO: compras
//CLASSE DA ENTIDADE pcorcamdescla
class cl_pcorcamdescla { 
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
   var $pc32_orcamitem = 0; 
   var $pc32_orcamforne = 0; 
   var $pc32_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc32_orcamitem = int4 = Código sequencial do item no orçamento 
                 pc32_orcamforne = int8 = Código do orcamento deste fornecedor 
                 pc32_motivo = text = Motivo da desclassificação 
                 ";
   //funcao construtor da classe 
   function cl_pcorcamdescla() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamdescla"); 
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
       $this->pc32_orcamitem = ($this->pc32_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc32_orcamitem"]:$this->pc32_orcamitem);
       $this->pc32_orcamforne = ($this->pc32_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc32_orcamforne"]:$this->pc32_orcamforne);
       $this->pc32_motivo = ($this->pc32_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc32_motivo"]:$this->pc32_motivo);
     }else{
       $this->pc32_orcamitem = ($this->pc32_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc32_orcamitem"]:$this->pc32_orcamitem);
       $this->pc32_orcamforne = ($this->pc32_orcamforne == ""?@$GLOBALS["HTTP_POST_VARS"]["pc32_orcamforne"]:$this->pc32_orcamforne);
     }
   }
   // funcao para inclusao
   function incluir ($pc32_orcamitem,$pc32_orcamforne){ 
      $this->atualizacampos();
     if($this->pc32_motivo == null ){ 
       $this->erro_sql = " Campo Motivo da desclassificação nao Informado.";
       $this->erro_campo = "pc32_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->pc32_orcamitem = $pc32_orcamitem; 
       $this->pc32_orcamforne = $pc32_orcamforne; 
     if(($this->pc32_orcamitem == null) || ($this->pc32_orcamitem == "") ){ 
       $this->erro_sql = " Campo pc32_orcamitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->pc32_orcamforne == null) || ($this->pc32_orcamforne == "") ){ 
       $this->erro_sql = " Campo pc32_orcamforne nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamdescla(
                                       pc32_orcamitem 
                                      ,pc32_orcamforne 
                                      ,pc32_motivo 
                       )
                values (
                                $this->pc32_orcamitem 
                               ,$this->pc32_orcamforne 
                               ,'$this->pc32_motivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens desclassificados dos fornecedores ($this->pc32_orcamitem."-".$this->pc32_orcamforne) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens desclassificados dos fornecedores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens desclassificados dos fornecedores ($this->pc32_orcamitem."-".$this->pc32_orcamforne) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc32_orcamitem."-".$this->pc32_orcamforne;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc32_orcamitem,$this->pc32_orcamforne));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7910,'$this->pc32_orcamitem','I')");
       $resac = db_query("insert into db_acountkey values($acount,7911,'$this->pc32_orcamforne','I')");
       $resac = db_query("insert into db_acount values($acount,1326,7910,'','".AddSlashes(pg_result($resaco,0,'pc32_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1326,7911,'','".AddSlashes(pg_result($resaco,0,'pc32_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1326,7912,'','".AddSlashes(pg_result($resaco,0,'pc32_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc32_orcamitem=null,$pc32_orcamforne=null) { 
      $this->atualizacampos();
     $sql = " update pcorcamdescla set ";
     $virgula = "";
     if(trim($this->pc32_orcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc32_orcamitem"])){ 
       $sql  .= $virgula." pc32_orcamitem = $this->pc32_orcamitem ";
       $virgula = ",";
       if(trim($this->pc32_orcamitem) == null ){ 
         $this->erro_sql = " Campo Código sequencial do item no orçamento nao Informado.";
         $this->erro_campo = "pc32_orcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc32_orcamforne)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc32_orcamforne"])){ 
       $sql  .= $virgula." pc32_orcamforne = $this->pc32_orcamforne ";
       $virgula = ",";
       if(trim($this->pc32_orcamforne) == null ){ 
         $this->erro_sql = " Campo Código do orcamento deste fornecedor nao Informado.";
         $this->erro_campo = "pc32_orcamforne";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc32_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc32_motivo"])){ 
       $sql  .= $virgula." pc32_motivo = '$this->pc32_motivo' ";
       $virgula = ",";
       if(trim($this->pc32_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo da desclassificação nao Informado.";
         $this->erro_campo = "pc32_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc32_orcamitem!=null){
       $sql .= " pc32_orcamitem = $this->pc32_orcamitem";
     }
     if($pc32_orcamforne!=null){
       $sql .= " and  pc32_orcamforne = $this->pc32_orcamforne";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc32_orcamitem,$this->pc32_orcamforne));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7910,'$this->pc32_orcamitem','A')");
         $resac = db_query("insert into db_acountkey values($acount,7911,'$this->pc32_orcamforne','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc32_orcamitem"]))
           $resac = db_query("insert into db_acount values($acount,1326,7910,'".AddSlashes(pg_result($resaco,$conresaco,'pc32_orcamitem'))."','$this->pc32_orcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc32_orcamforne"]))
           $resac = db_query("insert into db_acount values($acount,1326,7911,'".AddSlashes(pg_result($resaco,$conresaco,'pc32_orcamforne'))."','$this->pc32_orcamforne',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc32_motivo"]))
           $resac = db_query("insert into db_acount values($acount,1326,7912,'".AddSlashes(pg_result($resaco,$conresaco,'pc32_motivo'))."','$this->pc32_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens desclassificados dos fornecedores nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc32_orcamitem."-".$this->pc32_orcamforne;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens desclassificados dos fornecedores nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc32_orcamitem."-".$this->pc32_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc32_orcamitem."-".$this->pc32_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc32_orcamitem=null,$pc32_orcamforne=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc32_orcamitem,$pc32_orcamforne));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7910,'$pc32_orcamitem','E')");
         $resac = db_query("insert into db_acountkey values($acount,7911,'$pc32_orcamforne','E')");
         $resac = db_query("insert into db_acount values($acount,1326,7910,'','".AddSlashes(pg_result($resaco,$iresaco,'pc32_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1326,7911,'','".AddSlashes(pg_result($resaco,$iresaco,'pc32_orcamforne'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1326,7912,'','".AddSlashes(pg_result($resaco,$iresaco,'pc32_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcorcamdescla
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc32_orcamitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc32_orcamitem = $pc32_orcamitem ";
        }
        if($pc32_orcamforne != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc32_orcamforne = $pc32_orcamforne ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens desclassificados dos fornecedores nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc32_orcamitem."-".$pc32_orcamforne;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens desclassificados dos fornecedores nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc32_orcamitem."-".$pc32_orcamforne;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc32_orcamitem."-".$pc32_orcamforne;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamdescla";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc32_orcamitem=null,$pc32_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamdescla ";
     $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = pcorcamdescla.pc32_orcamforne";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamdescla.pc32_orcamitem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamforne.pc21_codorc";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql2 = "";
     if($dbwhere==""){
       if($pc32_orcamitem!=null ){
         $sql2 .= " where pcorcamdescla.pc32_orcamitem = $pc32_orcamitem "; 
       } 
       if($pc32_orcamforne!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamdescla.pc32_orcamforne = $pc32_orcamforne "; 
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
   function sql_query_descla_lote ( $pc32_orcamitem=null,$pc32_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamdescla ";
     $sql .= "      inner join pcorcamforne     on pcorcamforne.pc21_orcamforne    = pcorcamdescla.pc32_orcamforne";
     $sql .= "      inner join pcorcamitem      on pcorcamitem.pc22_orcamitem      = pcorcamdescla.pc32_orcamitem";
     $sql .= "      inner join cgm              on cgm.z01_numcgm                  = pcorcamforne.pc21_numcgm";
     $sql .= "      inner join pcorcam          on pcorcam.pc20_codorc             = pcorcamforne.pc21_codorc";
     $sql .= "      inner join pcorcam as orc   on orc.pc20_codorc                 = pcorcamitem.pc22_codorc";
     $sql .= "      inner join pcorcamitemlic   on pcorcamitemlic.pc26_orcamitem   = pcorcamdescla.pc32_orcamitem";
     $sql .= "      inner join liclicitemlote   on liclicitemlote.l04_liclicitem   = pcorcamitemlic.pc26_liclicitem";
     $sql .= "      inner join liclicitem       on liclicitem.l21_codigo           = liclicitemlote.l04_liclicitem";
     $sql .= "      inner join pcprocitem       on  pcprocitem.pc81_codprocitem    = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join solicitem        on  solicitem.pc11_codigo          = pcprocitem.pc81_solicitem";
     $sql .= "      left  join solicitemunid    on  solicitemunid.pc17_codigo      = solicitem.pc11_codigo";
     $sql .= "      left  join matunid          on  matunid.m61_codmatunid         = solicitemunid.pc17_unid";
     $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater          on  pcmater.pc01_codmater          = solicitempcmater.pc16_codmater";
     $sql2 = "";
     if($dbwhere==""){
       if($pc32_orcamitem!=null ){
         $sql2 .= " where pcorcamdescla.pc32_orcamitem = $pc32_orcamitem "; 
       } 
       if($pc32_orcamforne!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamdescla.pc32_orcamforne = $pc32_orcamforne "; 
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
   function sql_query_file ( $pc32_orcamitem=null,$pc32_orcamforne=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamdescla ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc32_orcamitem!=null ){
         $sql2 .= " where pcorcamdescla.pc32_orcamitem = $pc32_orcamitem "; 
       } 
       if($pc32_orcamforne!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamdescla.pc32_orcamforne = $pc32_orcamforne "; 
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