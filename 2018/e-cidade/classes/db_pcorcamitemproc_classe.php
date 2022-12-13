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
//CLASSE DA ENTIDADE pcorcamitemproc
class cl_pcorcamitemproc {
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
    var $pc31_orcamitem = 0;
    var $pc31_pcprocitem = 0;
    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 pc31_orcamitem = int4 = Código sequencial do item no orçamento 
                 pc31_pcprocitem = int8 = Código sequencial do item no processo 
                 ";
    //funcao construtor da classe
    function cl_pcorcamitemproc() {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("pcorcamitemproc");
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
            $this->pc31_orcamitem = ($this->pc31_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_orcamitem"]:$this->pc31_orcamitem);
            $this->pc31_pcprocitem = ($this->pc31_pcprocitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_pcprocitem"]:$this->pc31_pcprocitem);
        }else{
            $this->pc31_orcamitem = ($this->pc31_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_orcamitem"]:$this->pc31_orcamitem);
            $this->pc31_pcprocitem = ($this->pc31_pcprocitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc31_pcprocitem"]:$this->pc31_pcprocitem);
        }
    }
    // funcao para inclusao
    function incluir ($pc31_orcamitem,$pc31_pcprocitem){
        $this->atualizacampos();
        $this->pc31_orcamitem = $pc31_orcamitem;
        $this->pc31_pcprocitem = $pc31_pcprocitem;
        if(($this->pc31_orcamitem == null) || ($this->pc31_orcamitem == "") ){
            $this->erro_sql = " Campo pc31_orcamitem nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if(($this->pc31_pcprocitem == null) || ($this->pc31_pcprocitem == "") ){
            $this->erro_sql = " Campo pc31_pcprocitem nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into pcorcamitemproc(
                                       pc31_orcamitem 
                                      ,pc31_pcprocitem 
                       )
                values (
                                $this->pc31_orcamitem 
                               ,$this->pc31_pcprocitem 
                      )";
        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
                $this->erro_sql   = "Items do processo do orcamento ($this->pc31_orcamitem."-".$this->pc31_pcprocitem) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_banco = "Items do processo do orcamento já Cadastrado";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }else{
                $this->erro_sql   = "Items do processo do orcamento ($this->pc31_orcamitem."-".$this->pc31_pcprocitem) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir= 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->pc31_orcamitem."-".$this->pc31_pcprocitem;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir= pg_affected_rows($result);
        $resaco = $this->sql_record($this->sql_query_file($this->pc31_orcamitem,$this->pc31_pcprocitem));
        if(($resaco!=false)||($this->numrows!=0)){
            $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
            $acount = pg_result($resac,0,0);
            $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
            $resac = db_query("insert into db_acountkey values($acount,6444,'$this->pc31_orcamitem','I')");
            $resac = db_query("insert into db_acountkey values($acount,6445,'$this->pc31_pcprocitem','I')");
            $resac = db_query("insert into db_acount values($acount,1044,6444,'','".AddSlashes(pg_result($resaco,0,'pc31_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            $resac = db_query("insert into db_acount values($acount,1044,6445,'','".AddSlashes(pg_result($resaco,0,'pc31_pcprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
        return true;
    }
    // funcao para alteracao
    function alterar ($pc31_orcamitem=null,$pc31_pcprocitem=null) {
        $this->atualizacampos();
        $sql = " update pcorcamitemproc set ";
        $virgula = "";
        if(trim($this->pc31_orcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc31_orcamitem"])){
            $sql  .= $virgula." pc31_orcamitem = $this->pc31_orcamitem ";
            $virgula = ",";
            if(trim($this->pc31_orcamitem) == null ){
                $this->erro_sql = " Campo Código sequencial do item no orçamento nao Informado.";
                $this->erro_campo = "pc31_orcamitem";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->pc31_pcprocitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc31_pcprocitem"])){
            $sql  .= $virgula." pc31_pcprocitem = $this->pc31_pcprocitem ";
            $virgula = ",";
            if(trim($this->pc31_pcprocitem) == null ){
                $this->erro_sql = " Campo Código sequencial do item no processo nao Informado.";
                $this->erro_campo = "pc31_pcprocitem";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if($pc31_orcamitem!=null){
            $sql .= " pc31_orcamitem = $this->pc31_orcamitem";
        }
        if($pc31_pcprocitem!=null){
            $sql .= " and  pc31_pcprocitem = $this->pc31_pcprocitem";
        }
        $resaco = $this->sql_record($this->sql_query_file($this->pc31_orcamitem,$this->pc31_pcprocitem));
        if($this->numrows>0){
            for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac,0,0);
                $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                $resac = db_query("insert into db_acountkey values($acount,6444,'$this->pc31_orcamitem','A')");
                $resac = db_query("insert into db_acountkey values($acount,6445,'$this->pc31_pcprocitem','A')");
                if(isset($GLOBALS["HTTP_POST_VARS"]["pc31_orcamitem"]))
                    $resac = db_query("insert into db_acount values($acount,1044,6444,'".AddSlashes(pg_result($resaco,$conresaco,'pc31_orcamitem'))."','$this->pc31_orcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                if(isset($GLOBALS["HTTP_POST_VARS"]["pc31_pcprocitem"]))
                    $resac = db_query("insert into db_acount values($acount,1044,6445,'".AddSlashes(pg_result($resaco,$conresaco,'pc31_pcprocitem'))."','$this->pc31_pcprocitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            }
        }
        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "Items do processo do orcamento nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : ".$this->pc31_orcamitem."-".$this->pc31_pcprocitem;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "Items do processo do orcamento nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : ".$this->pc31_orcamitem."-".$this->pc31_pcprocitem;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : ".$this->pc31_orcamitem."-".$this->pc31_pcprocitem;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    function excluir ($pc31_orcamitem=null,$pc31_pcprocitem=null,$dbwhere=null) {
        if($dbwhere==null || $dbwhere==""){
            $resaco = $this->sql_record($this->sql_query_file($pc31_orcamitem,$pc31_pcprocitem));
        }else{
            $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
        }
        if(($resaco!=false)||($this->numrows!=0)){
            for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac,0,0);
                $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                $resac = db_query("insert into db_acountkey values($acount,6444,'$pc31_orcamitem','E')");
                $resac = db_query("insert into db_acountkey values($acount,6445,'$pc31_pcprocitem','E')");
                $resac = db_query("insert into db_acount values($acount,1044,6444,'','".AddSlashes(pg_result($resaco,$iresaco,'pc31_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1044,6445,'','".AddSlashes(pg_result($resaco,$iresaco,'pc31_pcprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            }
        }
        $sql = " delete from pcorcamitemproc
                    where ";
        $sql2 = "";
        if($dbwhere==null || $dbwhere ==""){
            if($pc31_orcamitem != ""){
                if($sql2!=""){
                    $sql2 .= " and ";
                }
                $sql2 .= " pc31_orcamitem = $pc31_orcamitem ";
            }
            if($pc31_pcprocitem != ""){
                if($sql2!=""){
                    $sql2 .= " and ";
                }
                $sql2 .= " pc31_pcprocitem = $pc31_pcprocitem ";
            }
        }else{
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "Items do processo do orcamento nao Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : ".$pc31_orcamitem."-".$pc31_pcprocitem;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "Items do processo do orcamento nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : ".$pc31_orcamitem."-".$pc31_pcprocitem;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : ".$pc31_orcamitem."-".$pc31_pcprocitem;
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
            $this->erro_sql   = "Record Vazio na Tabela:pcorcamitemproc";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    function sql_query ( $pc31_orcamitem=null,$pc31_pcprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from pcorcamitemproc ";
        $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamitemproc.pc31_orcamitem";
        $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = pcorcamitemproc.pc31_pcprocitem";
        $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
        $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
        $sql .= "      inner join pcproc  as a on   a.pc80_codproc = pcprocitem.pc81_codproc";
        $sql2 = "";
        if($dbwhere==""){
            if($pc31_orcamitem!=null ){
                $sql2 .= " where pcorcamitemproc.pc31_orcamitem = $pc31_orcamitem ";
            }
            if($pc31_pcprocitem!=null ){
                if($sql2!=""){
                    $sql2 .= " and ";
                }else{
                    $sql2 .= " where ";
                }
                $sql2 .= " pcorcamitemproc.pc31_pcprocitem = $pc31_pcprocitem ";
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
    function sql_query_file ( $pc31_orcamitem=null,$pc31_pcprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from pcorcamitemproc ";
        $sql2 = "";
        if($dbwhere==""){
            if($pc31_orcamitem!=null ){
                $sql2 .= " where pcorcamitemproc.pc31_orcamitem = $pc31_orcamitem ";
            }
            if($pc31_pcprocitem!=null ){
                if($sql2!=""){
                    $sql2 .= " and ";
                }else{
                    $sql2 .= " where ";
                }
                $sql2 .= " pcorcamitemproc.pc31_pcprocitem = $pc31_pcprocitem ";
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
    function sql_query_orcam ( $pc31_orcamitem=null,$pc31_pcprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from pcorcamitemproc ";
        $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamitemproc.pc31_orcamitem";
        $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
        $sql2 = "";
        if($dbwhere==""){
            if($pc31_orcamitem!=null ){
                $sql2 .= " where pcorcamitemproc.pc31_orcamitem = $pc31_orcamitem ";
            }
            if($pc31_pcprocitem!=null ){
                if($sql2!=""){
                    $sql2 .= " and ";
                }else{
                    $sql2 .= " where ";
                }
                $sql2 .= " pcorcamitemproc.pc31_pcprocitem = $pc31_pcprocitem ";
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
    function sql_query_solicitem ( $pc31_orcamitem=null,$pc31_pcprocitem=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from pcorcamitemproc ";
        $sql .= "      inner join pcorcamitem          on pcorcamitem.pc22_orcamitem          = pcorcamitemproc.pc31_orcamitem";
        $sql .= "      inner join pcprocitem           on pcprocitem.pc81_codprocitem         = pcorcamitemproc.pc31_pcprocitem";
        $sql .= "      inner join solicitem            on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita             on solicita.pc10_numero                = solicitem.pc11_numero";
        $sql .= "      left  join solicitempcmater     on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo";
        $sql .= "      left  join pcmater              on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater";
        $sql .= "      left  join pcsubgrupo           on pcsubgrupo.pc04_codsubgrupo         = pcmater.pc01_codsubgrupo";
        $sql .= "      left  join pctipo               on pctipo.pc05_codtipo                 = pcsubgrupo.pc04_codtipo";
        $sql .= "      left  join solicitemunid        on solicitemunid.pc17_codigo           = solicitem.pc11_codigo";
        $sql .= "      left  join matunid              on matunid.m61_codmatunid              = solicitemunid.pc17_unid";
        $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= "      left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
        $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
        $sql .= "      left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori";
        $sql2 = "";
        if($dbwhere==""){
            if($pc31_orcamitem!=null ){
                $sql2 .= " where pcorcamitemsol.pc31_orcamitem = $pc31_orcamitem ";
            }
            if($pc31_pcprocitem!=null ){
                if($sql2!=""){
                    $sql2 .= " and ";
                }else{
                    $sql2 .= " where ";
                }
                $sql2 .= " pcorcamitemsol.pc31_pcprocitem = $pc31_pcprocitem ";
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


    public function sql_query_orcamento_item($campos = "*", $dbwhere = null, $ordem = null) {

        $sql  = " select {$campos}";
        $sql .= "   from pcorcamitemproc ";
        $sql .= "        inner join pcorcamitem  on pcorcamitem.pc22_orcamitem = pcorcamitemproc.pc31_orcamitem";
        $sql .= "        inner join pcorcamval   on pcorcamval.pc23_orcamitem = pcorcamitem.pc22_orcamitem";
        $sql .= "        inner join pcorcamforne on pcorcamforne.pc21_orcamforne = pcorcamval.pc23_orcamforne";

        if (!empty($dbwhere)) {
            $sql .= " where {$dbwhere} ";
        }

        if (!empty($ordem)) {
            $sql .= " order by {$ordem} ";
        }
        return $sql;
    }
}
?>