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

//MODULO: material
//CLASSE DA ENTIDADE transmater
class cl_transmater {
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
    var $m63_codmatmater = 0;
    var $m63_codpcmater = 0;
    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 m63_codmatmater = int8 = Código do material 
                 m63_codpcmater = int4 = Código do Material 
                 ";
    //funcao construtor da classe
    function cl_transmater() {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("transmater");
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
            $this->m63_codmatmater = ($this->m63_codmatmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m63_codmatmater"]:$this->m63_codmatmater);
            $this->m63_codpcmater = ($this->m63_codpcmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m63_codpcmater"]:$this->m63_codpcmater);
        }else{
        }
    }
    // funcao para inclusao
    function incluir (){
        $this->atualizacampos();
        if($this->m63_codmatmater == null ){
            $this->erro_sql = " Campo Código do material nao Informado.";
            $this->erro_campo = "m63_codmatmater";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->m63_codpcmater == null ){
            $this->erro_sql = " Campo Código do Material nao Informado.";
            $this->erro_campo = "m63_codpcmater";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into transmater(
                                       m63_codmatmater 
                                      ,m63_codpcmater 
                       )
                values (
                                $this->m63_codmatmater 
                               ,$this->m63_codpcmater 
                      )";
        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
                $this->erro_sql   = "transmater () nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_banco = "transmater já Cadastrado";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }else{
                $this->erro_sql   = "transmater () nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir= 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir= pg_affected_rows($result);

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file(null,"*", null, "m63_codmatmater={$this->m63_codmatmater} and m63_codpcmater={$this->m63_codpcmater}"));
            if(($resaco!=false)||($this->numrows!=0)){

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac,0,0);
                $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                $resac = db_query("insert into db_acountkey values($acount,6829,'$this->m63_codpcmater','I')");
                $resac = db_query("insert into db_acountkey values($acount,6830,'$this->m63_codmatmater','I')");
                $resac = db_query("insert into db_acount values($acount,1120,6830,'','".AddSlashes(pg_result($resaco,0,'m63_codmatmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1120,6829,'','".AddSlashes(pg_result($resaco,0,'m63_codpcmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            }
        }

        return true;
    }
    // funcao para alteracao
    function alterar ( $oid=null ) {
        $this->atualizacampos();
        $sql = " update transmater set ";
        $virgula = "";
        if(trim($this->m63_codmatmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m63_codmatmater"])){
            $sql  .= $virgula." m63_codmatmater = $this->m63_codmatmater ";
            $virgula = ",";
            if(trim($this->m63_codmatmater) == null ){
                $this->erro_sql = " Campo Código do material nao Informado.";
                $this->erro_campo = "m63_codmatmater";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->m63_codpcmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m63_codpcmater"])){
            $sql  .= $virgula." m63_codpcmater = $this->m63_codpcmater ";
            $virgula = ",";
            if(trim($this->m63_codpcmater) == null ){
                $this->erro_sql = " Campo Código do Material nao Informado.";
                $this->erro_campo = "m63_codpcmater";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        $sql .= "oid = '$oid'";

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file(null,"*", null, "m63_codmatmater={$this->m63_codmatmater} and m63_codpcmater={$this->m63_codpcmater}"));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac,0,0);
                    $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac = db_query("insert into db_acountkey values($acount,6829,'$this->m63_codpcmater','A')");
                    $resac = db_query("insert into db_acountkey values($acount,6830,'$this->m63_codmatmater','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["m63_codmatmater"]) || $this->m63_codmatmater != "")
                        $resac = db_query("insert into db_acount values($acount,1120,6830,'".AddSlashes(pg_result($resaco,$conresaco,'m63_codmatmater'))."','$this->m63_codmatmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["m63_codpcmater"]) || $this->m63_codpcmater != "")
                        $resac = db_query("insert into db_acount values($acount,1120,6829,'".AddSlashes(pg_result($resaco,$conresaco,'m63_codpcmater'))."','$this->m63_codpcmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                }
            }
        }

        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "transmater nao Alterado. Alteracao Abortada.\\n";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "transmater nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    function excluir ( $oid=null ,$dbwhere=null) {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {
                $resaco = $this->sql_record($this->sql_query_file(null,"*", null, "m63_codmatmater={$this->m63_codmatmater} and m63_codpcmater={$this->m63_codpcmater}"));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
            }
            if (($resaco != false) || ($this->numrows!=0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac,0,0);
                    $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac  = db_query("insert into db_acountkey values($acount,6829,'$this->m63_codpcmater','E')");
                    $resac  = db_query("insert into db_acountkey values($acount,6830,'$this->m63_codmatmater','E')");
                    $resac  = db_query("insert into db_acount values($acount,1120,6830,'','".AddSlashes(pg_result($resaco,$iresaco,'m63_codmatmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,1120,6829,'','".AddSlashes(pg_result($resaco,$iresaco,'m63_codpcmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                }
            }
        }

        $sql = " delete from transmater
                    where ";
        $sql2 = "";
        if($dbwhere==null || $dbwhere ==""){
            $sql2 = "oid = '$oid'";
        }else{
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "transmater nao Excluído. Exclusão Abortada.\\n";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "transmater nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
            $this->erro_sql   = "Record Vazio na Tabela:transmater";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    function alterar_where ( $oid=null ,$dbwhere=null ) {
        $this->atualizacampos();
        $sql = " update transmater set ";
        $virgula = "";
        if(trim($this->m63_codmatmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m63_codmatmater"])){
            $sql  .= $virgula." m63_codmatmater = $this->m63_codmatmater ";
            $virgula = ",";
            if(trim($this->m63_codmatmater) == null ){
                $this->erro_sql = " Campo Código do material nao Informado.";
                $this->erro_campo = "m63_codmatmater";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->m63_codpcmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m63_codpcmater"])){
            $sql  .= $virgula." m63_codpcmater = $this->m63_codpcmater ";
            $virgula = ",";
            if(trim($this->m63_codpcmater) == null ){
                $this->erro_sql = " Campo Código do Material nao Informado.";
                $this->erro_campo = "m63_codpcmater";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if($dbwhere==null || $dbwhere ==""){
            $sql .= " where oid = '$oid'";
        }else{
            $sql .= "where ".$dbwhere;
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file(null,"*", null, "m63_codmatmater={$this->m63_codmatmater} and m63_codpcmater={$this->m63_codpcmater}"));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac,0,0);
                    $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac = db_query("insert into db_acountkey values($acount,6829,'$this->m63_codpcmater','A')");
                    $resac = db_query("insert into db_acountkey values($acount,6830,'$this->m63_codmatmater','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["m63_codmatmater"]) || $this->m63_codmatmater != "")
                        $resac = db_query("insert into db_acount values($acount,1120,6830,'".AddSlashes(pg_result($resaco,$conresaco,'m63_codmatmater'))."','$this->m63_codmatmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["m63_codpcmater"]) || $this->m63_codpcmater != "")
                        $resac = db_query("insert into db_acount values($acount,1120,6829,'".AddSlashes(pg_result($resaco,$conresaco,'m63_codpcmater'))."','$this->m63_codpcmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                }
            }
        }

        $result = @db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("\n","",@pg_last_error());
            $this->erro_sql   = "transmater nao Alterado. Alteracao Abortada.\\n";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "transmater nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    function sql_query ( $oid = null,$campos="transmater.oid,*",$ordem=null,$dbwhere=""){
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
        $sql .= " from transmater ";
        $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = transmater.m63_codpcmater";
        $sql .= "      inner join matmater  on  matmater.m60_codmater = transmater.m63_codmatmater";
        $sql2 = "";
        if($dbwhere==""){
            if( $oid != "" && $oid != null){
                $sql2 = " where transmater.oid = '$oid'";
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
    function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from transmater ";
        $sql2 = "";
        if($dbwhere==""){
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
