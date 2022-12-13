<?
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

//MODULO: compras
//CLASSE DA ENTIDADE pcorcamitemsol
class cl_pcorcamitemsol { 
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
   var $pc29_orcamitem = 0; 
   var $pc29_solicitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc29_orcamitem = int4 = Código sequencial do item no orçamento 
                 pc29_solicitem = int8 = Código sequencial do registro 
                 ";
   //funcao construtor da classe 
   function cl_pcorcamitemsol() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamitemsol"); 
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
       $this->pc29_orcamitem = ($this->pc29_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc29_orcamitem"]:$this->pc29_orcamitem);
       $this->pc29_solicitem = ($this->pc29_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc29_solicitem"]:$this->pc29_solicitem);
     }else{
       $this->pc29_orcamitem = ($this->pc29_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc29_orcamitem"]:$this->pc29_orcamitem);
       $this->pc29_solicitem = ($this->pc29_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc29_solicitem"]:$this->pc29_solicitem);
     }
   }
   // funcao para inclusao
   function incluir ($pc29_orcamitem,$pc29_solicitem){ 
      $this->atualizacampos();
       $this->pc29_orcamitem = $pc29_orcamitem; 
       $this->pc29_solicitem = $pc29_solicitem; 
     if(($this->pc29_orcamitem == null) || ($this->pc29_orcamitem == "") ){ 
       $this->erro_sql = " Campo pc29_orcamitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->pc29_solicitem == null) || ($this->pc29_solicitem == "") ){ 
       $this->erro_sql = " Campo pc29_solicitem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamitemsol(
                                       pc29_orcamitem 
                                      ,pc29_solicitem 
                       )
                values (
                                $this->pc29_orcamitem 
                               ,$this->pc29_solicitem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens do orcamento de solicitação ($this->pc29_orcamitem."-".$this->pc29_solicitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens do orcamento de solicitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens do orcamento de solicitação ($this->pc29_orcamitem."-".$this->pc29_solicitem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc29_orcamitem."-".$this->pc29_solicitem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc29_orcamitem,$this->pc29_solicitem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6389,'$this->pc29_orcamitem','I')");
       $resac = db_query("insert into db_acountkey values($acount,5516,'$this->pc29_solicitem','I')");
       $resac = db_query("insert into db_acount values($acount,1045,6389,'','".AddSlashes(pg_result($resaco,0,'pc29_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1045,5516,'','".AddSlashes(pg_result($resaco,0,'pc29_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc29_orcamitem=null,$pc29_solicitem=null) { 
      $this->atualizacampos();
     $sql = " update pcorcamitemsol set ";
     $virgula = "";
     if(trim($this->pc29_orcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc29_orcamitem"])){ 
       $sql  .= $virgula." pc29_orcamitem = $this->pc29_orcamitem ";
       $virgula = ",";
       if(trim($this->pc29_orcamitem) == null ){ 
         $this->erro_sql = " Campo Código sequencial do item no orçamento nao Informado.";
         $this->erro_campo = "pc29_orcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc29_solicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc29_solicitem"])){ 
       $sql  .= $virgula." pc29_solicitem = $this->pc29_solicitem ";
       $virgula = ",";
       if(trim($this->pc29_solicitem) == null ){ 
         $this->erro_sql = " Campo Código sequencial do registro nao Informado.";
         $this->erro_campo = "pc29_solicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc29_orcamitem!=null){
       $sql .= " pc29_orcamitem = $this->pc29_orcamitem";
     }
     if($pc29_solicitem!=null){
       $sql .= " and  pc29_solicitem = $this->pc29_solicitem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc29_orcamitem,$this->pc29_solicitem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6389,'$this->pc29_orcamitem','A')");
         $resac = db_query("insert into db_acountkey values($acount,5516,'$this->pc29_solicitem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc29_orcamitem"]))
           $resac = db_query("insert into db_acount values($acount,1045,6389,'".AddSlashes(pg_result($resaco,$conresaco,'pc29_orcamitem'))."','$this->pc29_orcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc29_solicitem"]))
           $resac = db_query("insert into db_acount values($acount,1045,5516,'".AddSlashes(pg_result($resaco,$conresaco,'pc29_solicitem'))."','$this->pc29_solicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens do orcamento de solicitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc29_orcamitem."-".$this->pc29_solicitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens do orcamento de solicitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc29_orcamitem."-".$this->pc29_solicitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc29_orcamitem."-".$this->pc29_solicitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc29_orcamitem=null,$pc29_solicitem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc29_orcamitem,$pc29_solicitem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6389,'$pc29_orcamitem','E')");
         $resac = db_query("insert into db_acountkey values($acount,5516,'$pc29_solicitem','E')");
         $resac = db_query("insert into db_acount values($acount,1045,6389,'','".AddSlashes(pg_result($resaco,$iresaco,'pc29_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1045,5516,'','".AddSlashes(pg_result($resaco,$iresaco,'pc29_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcorcamitemsol
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc29_orcamitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc29_orcamitem = $pc29_orcamitem ";
        }
        if($pc29_solicitem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc29_solicitem = $pc29_solicitem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens do orcamento de solicitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc29_orcamitem."-".$pc29_solicitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens do orcamento de solicitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc29_orcamitem."-".$pc29_solicitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc29_orcamitem."-".$pc29_solicitem;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamitemsol";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc29_orcamitem=null,$pc29_solicitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitemsol ";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamitemsol.pc29_orcamitem";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      left  join pcorcamforne  on  pcorcamforne.pc21_codorc = pcorcam.pc20_codorc";
     $sql .= "      left  join cgm on  z01_numcgm=pc21_numcgm";
     $sql .= "      left  join pcorcamval  on  pcorcamval.pc23_orcamitem = pcorcamitem.pc22_orcamitem
                                          and  pcorcamval.pc23_orcamforne= pcorcamforne.pc21_orcamforne";
     $sql .= "      left  join pcorcamjulg  on  pcorcamjulg.pc24_orcamitem = pcorcamitem.pc22_orcamitem
                                           and  pcorcamjulg.pc24_orcamforne= pcorcamforne.pc21_orcamforne";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcorcamitemsol.pc29_solicitem";
     $sql .= "      left  join solicitemele  on  solicitemele.pc18_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      left  join pcdotac  on  pcdotac.pc13_codigo = solicitem.pc11_codigo";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_depart on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      left  join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc29_orcamitem!=null ){
         $sql2 .= " where pcorcamitemsol.pc29_orcamitem = $pc29_orcamitem "; 
       } 
       if($pc29_solicitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamitemsol.pc29_solicitem = $pc29_solicitem "; 
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
   function sql_query_dotac ( $pc29_orcamitem=null,$pc29_solicitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitemsol ";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamitemsol.pc29_orcamitem";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      left  join pcorcamforne  on  pcorcamforne.pc21_codorc = pcorcam.pc20_codorc";
     $sql .= "      left  join cgm on  z01_numcgm=pc21_numcgm";
     $sql .= "      left  join pcorcamval  on  pcorcamval.pc23_orcamitem = pcorcamitem.pc22_orcamitem
                                          and  pcorcamval.pc23_orcamforne= pcorcamforne.pc21_orcamforne";
     $sql .= "      left  join pcorcamjulg  on  pcorcamjulg.pc24_orcamitem = pcorcamitem.pc22_orcamitem
                                           and  pcorcamjulg.pc24_orcamforne= pcorcamforne.pc21_orcamforne";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcorcamitemsol.pc29_solicitem";
     $sql .= "      left  join solicitemele  on  solicitemele.pc18_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      left  join pcdotac  on  pcdotac.pc13_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join pcdotaccontrapartida  on  pcdotac.pc13_sequencial = pc19_pcdotac";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_depart on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      left  join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc29_orcamitem!=null ){
         $sql2 .= " where pcorcamitemsol.pc29_orcamitem = $pc29_orcamitem "; 
       } 
       if($pc29_solicitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamitemsol.pc29_solicitem = $pc29_solicitem "; 
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
   function sql_query_file ( $pc29_orcamitem=null,$pc29_solicitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamitemsol ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc29_orcamitem!=null ){
         $sql2 .= " where pcorcamitemsol.pc29_orcamitem = $pc29_orcamitem "; 
       } 
       if($pc29_solicitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamitemsol.pc29_solicitem = $pc29_solicitem "; 
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
   function sql_query_orcam ( $pc29_orcamitem=null,$pc29_solicitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from pcorcamitemsol ";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamitemsol.pc29_orcamitem";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql2 = "";
     if($dbwhere==""){
       if($pc29_orcamitem!=null ){
         $sql2 .= " where pcorcamitemsol.pc29_orcamitem = $pc29_orcamitem "; 
       } 
       if($pc29_solicitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamitemsol.pc29_solicitem = $pc29_solicitem "; 
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
   function sql_query_pcmater ( $pc29_orcamitem=null,$pc29_solicitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamitemsol ";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamitemsol.pc29_orcamitem";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcorcamitemsol.pc29_solicitem";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";     
     $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";     
     $sql2 = "";
     if($dbwhere==""){
       if($pc29_orcamitem!=null ){
         $sql2 .= " where pcorcamitemsol.pc29_orcamitem = $pc29_orcamitem "; 
       } 
       if($pc29_solicitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamitemsol.pc29_solicitem = $pc29_solicitem "; 
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
   function sql_query_solicitem ( $pc29_orcamitem=null,$pc29_solicitem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamitemsol ";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamitemsol.pc29_orcamitem";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcorcamitemsol.pc29_solicitem";
     $sql .= "      inner join solicita   on  solicita.pc10_numero  = solicitem.pc11_numero ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto ";
     $sql .= "      left  join pcprocitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql2 = "";
     if($dbwhere==""){
       if($pc29_orcamitem!=null ){
         $sql2 .= " where pcorcamitemsol.pc29_orcamitem = $pc29_orcamitem "; 
       } 
       if($pc29_solicitem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pcorcamitemsol.pc29_solicitem = $pc29_solicitem "; 
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